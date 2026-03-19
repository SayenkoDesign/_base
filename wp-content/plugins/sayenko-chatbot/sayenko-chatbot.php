<?php
/**
 * Plugin Name: Sayenko Design Chatbot
 * Description: AI-powered chatbot that crawls SayenkoDesign.com and answers visitor questions using Claude Opus.
 * Version:     1.0.0
 * Author:      Sayenko Design
 * License:     GPL-2.0-or-later
 */

defined( 'ABSPATH' ) || exit;

define( 'SAYENKO_CHATBOT_VERSION', '1.0.0' );
define( 'SAYENKO_CHATBOT_DIR', plugin_dir_path( __FILE__ ) );
define( 'SAYENKO_CHATBOT_URL', plugin_dir_url( __FILE__ ) );

require_once SAYENKO_CHATBOT_DIR . 'includes/class-crawler.php';
require_once SAYENKO_CHATBOT_DIR . 'includes/class-claude-api.php';
require_once SAYENKO_CHATBOT_DIR . 'includes/class-admin.php';

// ---------------------------------------------------------------------------
// Activation / Deactivation
// ---------------------------------------------------------------------------

register_activation_hook( __FILE__, 'sayenko_chatbot_activate' );
register_deactivation_hook( __FILE__, 'sayenko_chatbot_deactivate' );

function sayenko_chatbot_activate() {
	global $wpdb;

	if ( ! extension_loaded( 'curl' ) ) {
		wp_die( 'The Sayenko Design Chatbot plugin requires the PHP cURL extension.' );
	}

	$table           = $wpdb->prefix . 'sayenko_chatbot_pages';
	$charset_collate = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE IF NOT EXISTS {$table} (
		id          bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
		url         varchar(500)        NOT NULL,
		title       varchar(500)        NOT NULL DEFAULT '',
		content     longtext            NOT NULL,
		crawled_at  datetime            NOT NULL DEFAULT CURRENT_TIMESTAMP,
		PRIMARY KEY (id),
		UNIQUE KEY  url (url(191))
	) ENGINE=InnoDB {$charset_collate};";

	require_once ABSPATH . 'wp-admin/includes/upgrade.php';
	dbDelta( $sql );

	add_option( 'sayenko_chatbot_api_key',    '' );
	add_option( 'sayenko_chatbot_target_url', 'https://sayenkodesign.com' );
	add_option( 'sayenko_chatbot_max_pages',  100 );
	add_option( 'sayenko_chatbot_crawl_status', [] );
}

function sayenko_chatbot_deactivate() {
	wp_clear_scheduled_hook( 'sayenko_chatbot_scheduled_crawl' );
}

// ---------------------------------------------------------------------------
// AJAX: Chat (SSE streaming, public)
// ---------------------------------------------------------------------------

add_action( 'wp_ajax_sayenko_chat',        'sayenko_chatbot_handle_chat' );
add_action( 'wp_ajax_nopriv_sayenko_chat', 'sayenko_chatbot_handle_chat' );

function sayenko_chatbot_handle_chat() {
	if ( ! check_ajax_referer( 'sayenko_chatbot_nonce', 'nonce', false ) ) {
		http_response_code( 403 );
		exit;
	}

	$message = sanitize_text_field( wp_unslash( $_POST['message'] ?? '' ) );
	if ( empty( $message ) ) {
		http_response_code( 400 );
		exit;
	}

	$raw_history = isset( $_POST['history'] ) ? (array) $_POST['history'] : [];
	$history     = [];
	foreach ( $raw_history as $msg ) {
		if ( ! is_array( $msg ) || ! isset( $msg['role'], $msg['content'] ) ) {
			continue;
		}
		$role = in_array( $msg['role'], [ 'user', 'assistant' ], true ) ? $msg['role'] : 'user';
		$history[] = [
			'role'    => $role,
			'content' => sanitize_text_field( wp_unslash( $msg['content'] ) ),
		];
	}

	// Clear all output buffers so we can stream SSE.
	while ( ob_get_level() > 0 ) {
		ob_end_clean();
	}

	header( 'Content-Type: text/event-stream' );
	header( 'Cache-Control: no-cache, no-store, must-revalidate' );
	header( 'X-Accel-Buffering: no' ); // Disable Nginx proxy buffering.
	header( 'Connection: keep-alive' );

	$api = new Sayenko_Claude_API();
	$api->stream_response( $message, $history );
	exit;
}

// ---------------------------------------------------------------------------
// AJAX: Crawl (admin only)
// ---------------------------------------------------------------------------

add_action( 'wp_ajax_sayenko_crawl', 'sayenko_chatbot_handle_crawl' );

function sayenko_chatbot_handle_crawl() {
	check_ajax_referer( 'sayenko_chatbot_admin_nonce', 'nonce' );
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_send_json_error( 'Insufficient permissions.' );
	}

	$crawler = new Sayenko_Chatbot_Crawler();
	$result  = $crawler->crawl();
	wp_send_json_success( $result );
}

// ---------------------------------------------------------------------------
// AJAX: Clear data (admin only)
// ---------------------------------------------------------------------------

add_action( 'wp_ajax_sayenko_clear_data', 'sayenko_chatbot_handle_clear_data' );

function sayenko_chatbot_handle_clear_data() {
	check_ajax_referer( 'sayenko_chatbot_admin_nonce', 'nonce' );
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_send_json_error( 'Insufficient permissions.' );
	}

	global $wpdb;
	$wpdb->query( "TRUNCATE TABLE {$wpdb->prefix}sayenko_chatbot_pages" ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery
	update_option( 'sayenko_chatbot_crawl_status', [] );
	wp_send_json_success( 'Indexed data cleared.' );
}

// ---------------------------------------------------------------------------
// Frontend: enqueue assets + render widget
// ---------------------------------------------------------------------------

add_action( 'wp_enqueue_scripts', function () {
	wp_enqueue_style(
		'sayenko-chatbot',
		SAYENKO_CHATBOT_URL . 'assets/css/chatbot.css',
		[],
		SAYENKO_CHATBOT_VERSION
	);
	wp_enqueue_script(
		'sayenko-chatbot',
		SAYENKO_CHATBOT_URL . 'assets/js/chatbot.js',
		[],
		SAYENKO_CHATBOT_VERSION,
		true
	);
	wp_localize_script( 'sayenko-chatbot', 'sayenkoChatbot', [
		'ajaxUrl' => admin_url( 'admin-ajax.php' ),
		'nonce'   => wp_create_nonce( 'sayenko_chatbot_nonce' ),
	] );
} );

add_action( 'wp_footer', 'sayenko_chatbot_render_widget' );

function sayenko_chatbot_render_widget() {
	?>
	<div id="sayenko-chatbot-widget" role="complementary" aria-label="Chat assistant">

		<div id="sayenko-chatbot-window" class="sayenko-chatbot-hidden" aria-hidden="true">
			<div id="sayenko-chatbot-header">
				<div class="sayenko-chatbot-header-info">
					<div class="sayenko-chatbot-avatar">SD</div>
					<div>
						<div class="sayenko-chatbot-title">Sayenko Design</div>
						<div class="sayenko-chatbot-subtitle">Ask us anything</div>
					</div>
				</div>
				<button id="sayenko-chatbot-close" aria-label="Close chat">&times;</button>
			</div>

			<div id="sayenko-chatbot-messages" role="log" aria-live="polite">
				<div class="sayenko-chatbot-message sayenko-chatbot-message--assistant">
					Hi! I'm here to answer any questions you have about Sayenko Design. How can I help you today?
				</div>
			</div>

			<div id="sayenko-chatbot-input-area">
				<textarea
					id="sayenko-chatbot-input"
					placeholder="Type a message..."
					rows="1"
					aria-label="Chat message"
				></textarea>
				<button id="sayenko-chatbot-send" aria-label="Send message">
					<svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
						<path d="M2 21L23 12L2 3V10L17 12L2 14V21Z" fill="currentColor"/>
					</svg>
				</button>
			</div>
		</div>

		<button id="sayenko-chatbot-toggle" aria-label="Open chat assistant" aria-expanded="false">
			<svg id="sayenko-chatbot-icon-chat" width="26" height="26" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
				<path d="M20 2H4C2.9 2 2 2.9 2 4V22L6 18H20C21.1 18 22 17.1 22 16V4C22 2.9 21.1 2 20 2Z" fill="white"/>
			</svg>
			<svg id="sayenko-chatbot-icon-x" width="26" height="26" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="display:none">
				<path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z" fill="white"/>
			</svg>
		</button>

	</div>
	<?php
}

// ---------------------------------------------------------------------------
// Admin menu
// ---------------------------------------------------------------------------

add_action( 'admin_menu', function () {
	$admin = new Sayenko_Chatbot_Admin();
	$admin->register_menus();
} );
