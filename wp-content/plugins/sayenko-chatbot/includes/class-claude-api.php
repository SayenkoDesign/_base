<?php
/**
 * Sayenko_Claude_API
 *
 * Retrieves relevant page content from the database, builds a context-aware
 * system prompt, and streams a response from Claude Opus via SSE.
 */

defined( 'ABSPATH' ) || exit;

class Sayenko_Claude_API {

	private const MODEL_DEFAULT = 'claude-haiku-4-5-20251001';
	private const API_URL    = 'https://api.anthropic.com/v1/messages';
	private const MAX_TOKENS = 1024;

	/** Characters of page content to include per matched page. */
	private const CHARS_PER_PAGE = 3000;

	/** How many pages to include in the context. */
	private const MAX_CONTEXT_PAGES = 5;

	// -----------------------------------------------------------------------
	// Public API
	// -----------------------------------------------------------------------

	/**
	 * Stream an assistant response to the current HTTP connection as SSE events.
	 *
	 * Each event is:  data: {"text":"..."}\n\n
	 * A final event:  data: [DONE]\n\n
	 * On error:       data: {"error":"..."}\n\n  followed by [DONE]
	 *
	 * @param string $message    The user's latest message.
	 * @param array  $history    Prior turns: [{role, content}, …]
	 */
	public function stream_response( string $message, array $history = [] ): void {
		$api_key = get_option( 'sayenko_chatbot_api_key', '' );

		if ( empty( $api_key ) ) {
			$this->sse_error( 'The chatbot is not configured yet. Please contact the site administrator.' );
			return;
		}

		$context       = $this->get_relevant_content( $message );
		$system_prompt = $this->build_system_prompt( $context );

		// Build the full messages array.
		$messages = [];
		foreach ( $history as $turn ) {
			$messages[] = [
				'role'    => $turn['role'],
				'content' => $turn['content'],
			];
		}
		$messages[] = [ 'role' => 'user', 'content' => $message ];

		$this->call_claude_stream( $api_key, $system_prompt, $messages );
	}

	// -----------------------------------------------------------------------
	// Content retrieval
	// -----------------------------------------------------------------------

	/**
	 * Search the indexed pages for content relevant to the user's query.
	 *
	 * Uses a simple multi-keyword LIKE search with stop-word filtering.
	 * Falls back to returning the first few pages when no keywords are found.
	 *
	 * @param  string $query
	 * @return string  Formatted context block ready for the system prompt.
	 */
	private function get_relevant_content( string $query ): string {
		global $wpdb;
		$table = $wpdb->prefix . 'sayenko_chatbot_pages';

		// Bail early if nothing has been crawled yet.
		$total = (int) $wpdb->get_var( "SELECT COUNT(*) FROM {$table}" ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery
		if ( 0 === $total ) {
			return '';
		}

		$keywords = $this->extract_keywords( $query );

		if ( ! empty( $keywords ) ) {
			$conditions = [];
			$values     = [];
			foreach ( $keywords as $kw ) {
				$like         = '%' . $wpdb->esc_like( $kw ) . '%';
				$conditions[] = '(title LIKE %s OR content LIKE %s)';
				$values[]     = $like;
				$values[]     = $like;
			}
			$where = implode( ' OR ', $conditions );
			// phpcs:disable WordPress.DB.DirectDatabaseQuery, WordPress.DB.PreparedSQL.InterpolatedNotPrepared
			$rows = $wpdb->get_results(
				$wpdb->prepare(
					"SELECT title, url, content FROM {$table} WHERE {$where} LIMIT %d",
					array_merge( $values, [ self::MAX_CONTEXT_PAGES ] )
				)
			);
			// phpcs:enable
		} else {
			$rows = [];
		}

		// Fall back to the first N pages if keyword search returned nothing.
		if ( empty( $rows ) ) {
			$rows = $wpdb->get_results( // phpcs:ignore WordPress.DB.DirectDatabaseQuery
				$wpdb->prepare( "SELECT title, url, content FROM {$table} ORDER BY id LIMIT %d", self::MAX_CONTEXT_PAGES )
			);
		}

		return $this->format_context( $rows );
	}

	/**
	 * Pull meaningful words out of the user's query, dropping stop words.
	 *
	 * @param  string   $query
	 * @return string[]
	 */
	private function extract_keywords( string $query ): array {
		static $stop_words = [
			'the', 'a', 'an', 'is', 'are', 'was', 'were', 'be', 'been', 'being',
			'have', 'has', 'had', 'do', 'does', 'did', 'will', 'would', 'could',
			'should', 'may', 'might', 'can', 'to', 'for', 'of', 'in', 'on', 'at',
			'by', 'with', 'from', 'about', 'what', 'how', 'when', 'where', 'who',
			'why', 'which', 'that', 'this', 'it', 'its', 'you', 'your', 'my', 'me',
			'we', 'our', 'they', 'their', 'and', 'or', 'but', 'not', 'no', 'i',
		];

		$words = preg_split( '/\W+/u', strtolower( $query ), -1, PREG_SPLIT_NO_EMPTY );
		return array_values(
			array_filter(
				$words,
				fn( $w ) => strlen( $w ) > 2 && ! in_array( $w, $stop_words, true )
			)
		);
	}

	/**
	 * Convert DB rows into a markdown-style context string.
	 *
	 * @param  object[] $rows  Each has ->title, ->url, ->content
	 * @return string
	 */
	private function format_context( array $rows ): string {
		if ( empty( $rows ) ) {
			return '';
		}

		$context = '';
		foreach ( $rows as $row ) {
			$snippet  = mb_substr( trim( $row->content ), 0, self::CHARS_PER_PAGE );
			$context .= "### {$row->title}\nURL: {$row->url}\n\n{$snippet}\n\n---\n\n";
		}
		return $context;
	}

	// -----------------------------------------------------------------------
	// Prompt building
	// -----------------------------------------------------------------------

	private function build_system_prompt( string $context ): string {
		$target_url = get_option( 'sayenko_chatbot_target_url', 'https://sayenkodesign.com' );
		$domain     = parse_url( $target_url, PHP_URL_HOST ) ?? $target_url;

		$prompt  = "You are a friendly and knowledgeable assistant for {$domain}. ";
		$prompt .= "Your job is to help website visitors by answering questions about the company, its services, portfolio, process, and team.\n\n";

		if ( ! empty( $context ) ) {
			$prompt .= "Below is content from the website to help you answer accurately:\n\n";
			$prompt .= $context;
			$prompt .= "If a question cannot be answered from the content above, say so honestly and invite the visitor to contact the company directly or explore the website further.\n\n";
		} else {
			$prompt .= "Website content has not been indexed yet. Let the visitor know the chatbot is still being set up, and encourage them to browse the website or reach out directly.\n\n";
		}

		$prompt .= "Guidelines:\n";
		$prompt .= "- Be concise and helpful.\n";
		$prompt .= "- Do not invent information not present in the provided content.\n";
		$prompt .= "- Keep a professional yet approachable tone.\n";
		$prompt .= "- If asked about pricing, timelines, or specifics not in the content, suggest contacting the team directly.";

		return $prompt;
	}

	// -----------------------------------------------------------------------
	// Claude API streaming
	// -----------------------------------------------------------------------

	/**
	 * Open a streaming connection to the Anthropic API and forward text deltas
	 * to the client as SSE events.
	 */
	private function call_claude_stream( string $api_key, string $system_prompt, array $messages ): void {
		$model = get_option( 'sayenko_chatbot_model', self::MODEL_DEFAULT );

		$body = wp_json_encode( [
			'model'      => $model,
			'max_tokens' => self::MAX_TOKENS,
			'stream'     => true,
			'system'     => $system_prompt,
			'messages'   => $messages,
		] );

		$ch = curl_init( self::API_URL );
		if ( false === $ch ) {
			$this->sse_error( 'Could not initialise the API request.' );
			return;
		}

		// Buffer for partial SSE lines coming in from cURL.
		$line_buffer  = '';
		$raw_response = '';

		curl_setopt_array( $ch, [
			CURLOPT_POST           => true,
			CURLOPT_POSTFIELDS     => $body,
			CURLOPT_RETURNTRANSFER => false,
			CURLOPT_TIMEOUT        => 120,
			CURLOPT_SSL_VERIFYPEER => true,
			CURLOPT_HTTPHEADER     => [
				'Content-Type: application/json',
				'x-api-key: ' . $api_key,
				'anthropic-version: 2023-06-01',
			],
			CURLOPT_WRITEFUNCTION  => function ( $ch, $chunk ) use ( &$line_buffer, &$raw_response ) {
				$raw_response .= $chunk;
				$line_buffer  .= $chunk;

				// Process every complete line in the buffer.
				while ( false !== ( $pos = strpos( $line_buffer, "\n" ) ) ) {
					$line        = trim( substr( $line_buffer, 0, $pos ) );
					$line_buffer = substr( $line_buffer, $pos + 1 );

					if ( ! str_starts_with( $line, 'data: ' ) ) {
						continue;
					}

					$json = substr( $line, 6 );
					if ( '[DONE]' === $json ) {
						continue;
					}

					$event = json_decode( $json, true );
					if ( ! is_array( $event ) ) {
						continue;
					}

					// Forward text deltas only (skip thinking_delta, etc.).
					if (
						'content_block_delta' === ( $event['type'] ?? '' )
						&& 'text_delta' === ( $event['delta']['type'] ?? '' )
					) {
						$text = $event['delta']['text'] ?? '';
						if ( '' !== $text ) {
							echo 'data: ' . wp_json_encode( [ 'text' => $text ] ) . "\n\n";
							flush();
						}
					}

					// Surface API-level errors to the client.
					if ( 'error' === ( $event['type'] ?? '' ) ) {
						$msg = $event['error']['message'] ?? 'Unknown API error.';
						echo 'data: ' . wp_json_encode( [ 'error' => $msg ] ) . "\n\n";
						flush();
					}
				}

				return strlen( $chunk );
			},
		] );

		curl_exec( $ch );

		$http_code  = curl_getinfo( $ch, CURLINFO_HTTP_CODE );
		$curl_error = curl_error( $ch );
		curl_close( $ch );

		if ( ! empty( $curl_error ) ) {
			$this->sse_error( 'Connection error. Please try again.' );
		} elseif ( $http_code > 0 && 200 !== $http_code ) {
			$error_msg = "API error (HTTP {$http_code}).";
			$parsed    = json_decode( $raw_response, true );
			if ( ! empty( $parsed['error']['message'] ) ) {
				$error_msg = $parsed['error']['message'];
			}
			$this->sse_error( $error_msg );
		}

		// Signal end of stream.
		echo "data: [DONE]\n\n";
		flush();
	}

	/**
	 * Emit an SSE error event followed by [DONE] and return.
	 */
	private function sse_error( string $message ): void {
		echo 'data: ' . wp_json_encode( [ 'error' => $message ] ) . "\n\n";
		echo "data: [DONE]\n\n";
		flush();
	}
}
