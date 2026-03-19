<?php
/**
 * Sayenko_Chatbot_Crawler
 *
 * BFS-crawls a target website and stores page content in the database.
 */

defined( 'ABSPATH' ) || exit;

class Sayenko_Chatbot_Crawler {

	/** @var string Base URL (no trailing slash). */
	private $target_url;

	/** @var string Hostname of the target site. */
	private $target_host;

	/** @var int Maximum number of pages to crawl. */
	private $max_pages;

	/** @var string[] Already-visited URLs. */
	private $visited = [];

	/** @var string[] URLs waiting to be crawled. */
	private $queue = [];

	/** @var int Pages successfully stored. */
	private $page_count = 0;

	/** File extensions that are never HTML pages. */
	private const SKIP_EXTENSIONS = [
		'jpg', 'jpeg', 'png', 'gif', 'svg', 'webp', 'avif',
		'pdf', 'zip', 'tar', 'gz', 'doc', 'docx', 'xls', 'xlsx',
		'ppt', 'pptx', 'mp3', 'mp4', 'avi', 'mov', 'wmv',
		'css', 'js', 'ico', 'woff', 'woff2', 'ttf', 'eot', 'otf',
		'xml', 'rss', 'atom',
	];

	/** URL path fragments that indicate non-content pages. */
	private const SKIP_PATHS = [
		'/feed', '/wp-admin', '/wp-login', '/wp-json',
		'/xmlrpc', '/wp-cron', '/checkout', '/cart',
		'/my-account', '?replytocom',
	];

	public function __construct() {
		$this->target_url  = rtrim( get_option( 'sayenko_chatbot_target_url', 'https://sayenkodesign.com' ), '/' );
		$this->target_host = parse_url( $this->target_url, PHP_URL_HOST );
		$this->max_pages   = absint( get_option( 'sayenko_chatbot_max_pages', 100 ) );
	}

	/**
	 * Run the crawl. Clears existing data, then crawls from the target URL.
	 *
	 * @return array{pages_crawled: int, last_crawl: string}
	 */
	public function crawl(): array {
		// Crawling can take a while on large sites.
		set_time_limit( 0 );

		global $wpdb;
		$table = $wpdb->prefix . 'sayenko_chatbot_pages';

		// Remove stale data.
		$wpdb->query( "TRUNCATE TABLE {$table}" ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery

		$this->visited    = [];
		$this->queue      = [ $this->target_url ];
		$this->page_count = 0;

		while ( ! empty( $this->queue ) && $this->page_count < $this->max_pages ) {
			$url = array_shift( $this->queue );
			$url = strtok( $url, '#' ); // Strip fragment.
			$url = rtrim( $url, '/' );

			if ( empty( $url ) || in_array( $url, $this->visited, true ) ) {
				continue;
			}

			$this->visited[] = $url;

			$page = $this->fetch_page( $url );
			if ( null === $page ) {
				continue;
			}

			$wpdb->replace( // phpcs:ignore WordPress.DB.DirectDatabaseQuery
				$table,
				[
					'url'        => $url,
					'title'      => $page['title'],
					'content'    => $page['content'],
					'crawled_at' => current_time( 'mysql' ),
				],
				[ '%s', '%s', '%s', '%s' ]
			);

			$this->page_count++;

			foreach ( $page['links'] as $link ) {
				if ( ! in_array( $link, $this->visited, true ) && ! in_array( $link, $this->queue, true ) ) {
					$this->queue[] = $link;
				}
			}
		}

		$status = [
			'pages_crawled' => $this->page_count,
			'last_crawl'    => current_time( 'mysql' ),
		];
		update_option( 'sayenko_chatbot_crawl_status', $status );

		return $status;
	}

	/**
	 * Fetch a single page and return its parsed data, or null on failure.
	 *
	 * @param string $url
	 * @return array{title: string, content: string, links: string[]}|null
	 */
	private function fetch_page( string $url ): ?array {
		$response = wp_remote_get( $url, [
			'timeout'    => 15,
			'user-agent' => 'SayenkoChatbotCrawler/1.0 (WordPress plugin; contact@sayenkodesign.com)',
			'sslverify'  => true,
		] );

		if ( is_wp_error( $response ) ) {
			return null;
		}

		if ( 200 !== (int) wp_remote_retrieve_response_code( $response ) ) {
			return null;
		}

		$content_type = wp_remote_retrieve_header( $response, 'content-type' );
		if ( false === strpos( $content_type, 'text/html' ) ) {
			return null;
		}

		$html = wp_remote_retrieve_body( $response );
		if ( empty( $html ) ) {
			return null;
		}

		return $this->parse_html( $html, $url );
	}

	/**
	 * Parse an HTML string into title, plain-text content, and internal links.
	 *
	 * @param string $html
	 * @param string $page_url  Used to resolve relative links.
	 * @return array{title: string, content: string, links: string[]}
	 */
	private function parse_html( string $html, string $page_url ): array {
		$dom = new DOMDocument();
		libxml_use_internal_errors( true );
		$dom->loadHTML( mb_convert_encoding( $html, 'HTML-ENTITIES', 'UTF-8' ) );
		libxml_clear_errors();

		$xpath = new DOMXPath( $dom );

		// --- Title ---
		$title       = '';
		$title_nodes = $dom->getElementsByTagName( 'title' );
		if ( $title_nodes->length > 0 ) {
			$title = trim( $title_nodes->item( 0 )->textContent );
		}

		// --- Remove boilerplate elements before text extraction ---
		$boilerplate_query = '//script | //style | //nav | //header | //footer
		                    | //aside | //noscript | //iframe | //form
		                    | //*[contains(@class,"cookie")] | //*[contains(@class,"popup")]
		                    | //*[contains(@id,"cookie")] | //*[contains(@id,"popup")]';
		$remove_nodes      = $xpath->query( $boilerplate_query );
		foreach ( $remove_nodes as $node ) {
			if ( $node->parentNode ) {
				$node->parentNode->removeChild( $node );
			}
		}

		// --- Extract main content area (fall back to body) ---
		$content_query = '//main | //article
		                | //*[@id="content"] | //*[@id="main"] | //*[@id="primary"]
		                | //*[contains(@class,"entry-content")]
		                | //*[contains(@class,"site-content")]
		                | //*[contains(@class,"page-content")]';
		$content_nodes = $xpath->query( $content_query );

		if ( $content_nodes->length > 0 ) {
			$raw_text = $content_nodes->item( 0 )->textContent;
		} else {
			$body     = $dom->getElementsByTagName( 'body' );
			$raw_text = $body->length > 0 ? $body->item( 0 )->textContent : '';
		}

		// Normalise whitespace.
		$raw_text = preg_replace( '/[ \t]+/', ' ', $raw_text );
		$raw_text = preg_replace( '/\n{3,}/', "\n\n", $raw_text );
		$content  = trim( mb_substr( $raw_text, 0, 50000 ) );

		// --- Extract internal links ---
		$links      = [];
		$link_nodes = $dom->getElementsByTagName( 'a' );
		foreach ( $link_nodes as $a ) {
			$href       = $a->getAttribute( 'href' );
			$normalised = $this->normalise_url( $href, $page_url );
			if ( $normalised && $this->is_crawlable( $normalised ) ) {
				$links[] = $normalised;
			}
		}
		$links = array_unique( $links );

		return compact( 'title', 'content', 'links' );
	}

	/**
	 * Resolve and normalise a raw href relative to the current page.
	 *
	 * @param string $href
	 * @param string $base_url
	 * @return string|false  Absolute URL or false if it should be ignored.
	 */
	private function normalise_url( string $href, string $base_url ) {
		$href = trim( $href );

		if ( empty( $href )
			|| '#' === $href[0]
			|| str_starts_with( $href, 'mailto:' )
			|| str_starts_with( $href, 'tel:' )
			|| str_starts_with( $href, 'javascript:' )
		) {
			return false;
		}

		// Protocol-relative.
		if ( str_starts_with( $href, '//' ) ) {
			$scheme = parse_url( $base_url, PHP_URL_SCHEME ) ?? 'https';
			$href   = $scheme . ':' . $href;
		} elseif ( '/' === $href[0] ) {
			// Root-relative.
			$href = $this->target_url . $href;
		} elseif ( ! str_starts_with( $href, 'http' ) ) {
			// Relative path: resolve against the directory of $base_url.
			$base_dir = rtrim( dirname( $base_url ), '/' );
			$href     = $base_dir . '/' . $href;
		}

		// Strip fragment and trailing slash.
		$href = strtok( $href, '#' );
		$href = rtrim( $href, '/' );

		if ( parse_url( $href, PHP_URL_HOST ) !== $this->target_host ) {
			return false;
		}

		return $href ?: false;
	}

	/**
	 * Return true if the URL is safe to crawl (HTML page, not a media file or admin area).
	 */
	private function is_crawlable( string $url ): bool {
		$path = strtolower( parse_url( $url, PHP_URL_PATH ) ?? '' );

		// Skip known file extensions.
		$ext = pathinfo( $path, PATHINFO_EXTENSION );
		if ( $ext && in_array( $ext, self::SKIP_EXTENSIONS, true ) ) {
			return false;
		}

		// Skip non-content paths.
		foreach ( self::SKIP_PATHS as $fragment ) {
			if ( false !== strpos( $path, $fragment ) ) {
				return false;
			}
		}

		return true;
	}
}
