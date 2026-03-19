<?php
/**
 * Sayenko_Chatbot_Admin
 *
 * WordPress admin settings page for the Sayenko Design Chatbot plugin.
 */

defined( 'ABSPATH' ) || exit;

class Sayenko_Chatbot_Admin {

	public function register_menus(): void {
		add_options_page(
			'Sayenko Design Chatbot',
			'Sayenko Chatbot',
			'manage_options',
			'sayenko-chatbot',
			[ $this, 'render_settings_page' ]
		);
	}

	public function render_settings_page(): void {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		// Handle form submission.
		$notice = '';
		if ( isset( $_POST['sayenko_chatbot_save'] ) ) {
			check_admin_referer( 'sayenko_chatbot_settings' );
			update_option( 'sayenko_chatbot_api_key',    sanitize_text_field( wp_unslash( $_POST['api_key']    ?? '' ) ) );
			update_option( 'sayenko_chatbot_target_url', esc_url_raw( wp_unslash( $_POST['target_url'] ?? '' ) ) );
			update_option( 'sayenko_chatbot_max_pages',  absint( $_POST['max_pages'] ?? 100 ) );
			$notice = '<div class="notice notice-success is-dismissible"><p>Settings saved.</p></div>';
		}

		$api_key    = get_option( 'sayenko_chatbot_api_key',    '' );
		$target_url = get_option( 'sayenko_chatbot_target_url', 'https://sayenkodesign.com' );
		$max_pages  = (int) get_option( 'sayenko_chatbot_max_pages', 100 );
		$status     = get_option( 'sayenko_chatbot_crawl_status', [] );

		global $wpdb;
		$page_count = (int) $wpdb->get_var( "SELECT COUNT(*) FROM {$wpdb->prefix}sayenko_chatbot_pages" ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery

		$admin_nonce = wp_create_nonce( 'sayenko_chatbot_admin_nonce' );
		?>
		<div class="wrap">
			<h1>
				<svg style="vertical-align:middle;margin-right:8px;" width="28" height="28" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path d="M20 2H4C2.9 2 2 2.9 2 4V22L6 18H20C21.1 18 22 17.1 22 16V4C22 2.9 21.1 2 20 2Z" fill="#1a1a2e"/>
				</svg>
				Sayenko Design Chatbot
			</h1>

			<?php echo $notice; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>

			<form method="post" action="">
				<?php wp_nonce_field( 'sayenko_chatbot_settings' ); ?>
				<table class="form-table" role="presentation">
					<tr>
						<th scope="row"><label for="api_key">Anthropic API Key</label></th>
						<td>
							<input
								type="password"
								id="api_key"
								name="api_key"
								value="<?php echo esc_attr( $api_key ); ?>"
								class="regular-text"
								autocomplete="off"
							/>
							<p class="description">
								Obtain your key from <a href="https://console.anthropic.com/" target="_blank" rel="noopener noreferrer">console.anthropic.com</a>.
								The chatbot uses <strong>Claude Opus</strong> (claude-opus-4-6).
							</p>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="target_url">Website URL to Crawl</label></th>
						<td>
							<input
								type="url"
								id="target_url"
								name="target_url"
								value="<?php echo esc_attr( $target_url ); ?>"
								class="regular-text"
								placeholder="https://sayenkodesign.com"
							/>
							<p class="description">Only pages on this domain will be crawled.</p>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="max_pages">Maximum Pages to Crawl</label></th>
						<td>
							<input
								type="number"
								id="max_pages"
								name="max_pages"
								value="<?php echo esc_attr( $max_pages ); ?>"
								min="1"
								max="500"
								class="small-text"
							/>
							<p class="description">Larger sites take longer; 100 pages is a good starting point.</p>
						</td>
					</tr>
				</table>

				<p class="submit">
					<input
						type="submit"
						name="sayenko_chatbot_save"
						class="button button-primary"
						value="Save Settings"
					/>
				</p>
			</form>

			<hr>

			<h2>Content Index</h2>
			<table class="form-table" role="presentation">
				<tr>
					<th scope="row">Pages indexed</th>
					<td><?php echo esc_html( number_format_i18n( $page_count ) ); ?></td>
				</tr>
				<tr>
					<th scope="row">Last crawl</th>
					<td>
						<?php
						echo ! empty( $status['last_crawl'] )
							? esc_html( $status['last_crawl'] )
							: '<em>Never</em>';
						?>
					</td>
				</tr>
			</table>

			<p>
				<button id="sayenko-crawl-btn" class="button button-secondary">
					Crawl Now
				</button>
				<button id="sayenko-clear-btn" class="button" style="margin-left:10px;">
					Clear Index
				</button>
				<span id="sayenko-crawl-msg" style="margin-left:12px;display:none;font-style:italic;"></span>
			</p>

			<hr>

			<h2>How It Works</h2>
			<ol>
				<li>Enter your <strong>Anthropic API key</strong> above and save.</li>
				<li>Click <strong>Crawl Now</strong> to index <?php echo esc_html( $target_url ); ?>. This may take a few minutes.</li>
				<li>The chatbot widget will appear in the bottom-right corner of every page on your site.</li>
				<li>Visitors can ask questions; Claude searches the indexed content for relevant context and answers in real time.</li>
				<li>Re-crawl periodically (or after publishing new content) to keep answers up to date.</li>
			</ol>
		</div>

		<script>
		(function ($) {
			var nonce = <?php echo wp_json_encode( $admin_nonce ); ?>;

			$('#sayenko-crawl-btn').on('click', function () {
				var $btn = $(this);
				var $msg = $('#sayenko-crawl-msg');
				$btn.prop('disabled', true).text('Crawling…');
				$msg.show().text('This may take several minutes for large sites. Please wait…');

				$.ajax({
					url:     ajaxurl,
					method:  'POST',
					timeout: 600000, // 10 minutes
					data: {
						action: 'sayenko_crawl',
						nonce:  nonce,
					},
					success: function (res) {
						if (res.success) {
							$msg.text('Done! Indexed ' + res.data.pages_crawled + ' page(s). Reloading…');
							setTimeout(function () { location.reload(); }, 1500);
						} else {
							$msg.text('Error: ' + (res.data || 'Unknown error.'));
							$btn.prop('disabled', false).text('Crawl Now');
						}
					},
					error: function () {
						$msg.text('Request failed or timed out. Please try again.');
						$btn.prop('disabled', false).text('Crawl Now');
					},
				});
			});

			$('#sayenko-clear-btn').on('click', function () {
				if (!confirm('Delete all indexed content? The chatbot will have no knowledge until you crawl again.')) {
					return;
				}
				$.ajax({
					url:    ajaxurl,
					method: 'POST',
					data: {
						action: 'sayenko_clear_data',
						nonce:  nonce,
					},
					success: function () { location.reload(); },
				});
			});
		}(jQuery));
		</script>
		<?php
	}
}
