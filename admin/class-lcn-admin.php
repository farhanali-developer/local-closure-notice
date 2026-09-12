<?php
/**
 * Admin-side UI: settings page under Settings > Closure Notice.
 *
 * @package Local_Closure_Notice
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

class LCN_Admin {

	const SETTINGS_GROUP = 'lcn_settings_group';
	const SETTINGS_SLUG  = 'lcn-settings';

	public function __construct() {
		add_action( 'admin_menu', array( $this, 'add_settings_page' ) );
		add_action( 'admin_init', array( $this, 'register_settings' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_assets' ) );
		add_filter( 'plugin_action_links_' . LCN_PLUGIN_BASENAME, array( $this, 'add_settings_link' ) );
	}

	/**
	 * Register the Settings > Closure Notice page.
	 */
	public function add_settings_page() {
		add_options_page(
			__( 'Local Closure Notice', 'local-closure-notice' ),
			__( 'Closure Notice', 'local-closure-notice' ),
			'manage_options',
			self::SETTINGS_SLUG,
			array( $this, 'render_settings_page' )
		);
	}

	/**
	 * Add a "Settings" link on the Plugins list row.
	 *
	 * @param array $links
	 * @return array
	 */
	public function add_settings_link( $links ) {
		$url = admin_url( 'options-general.php?page=' . self::SETTINGS_SLUG );
		array_unshift( $links, '<a href="' . esc_url( $url ) . '">' . esc_html__( 'Settings', 'local-closure-notice' ) . '</a>' );

		return $links;
	}

	/**
	 * Load the color picker only on our settings page.
	 *
	 * @param string $hook
	 */
	public function enqueue_admin_assets( $hook ) {
		if ( 'settings_page_' . self::SETTINGS_SLUG !== $hook ) {
			return;
		}

		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_script( 'wp-color-picker' );
		wp_add_inline_script( 'wp-color-picker', 'jQuery(function($){$(".lcn-color-field").wpColorPicker();});' );
	}

	/**
	 * Register the setting + sanitizer with the Settings API.
	 */
	public function register_settings() {
		register_setting(
			self::SETTINGS_GROUP,
			LCN_Notice::OPTION_NAME,
			array(
				'type'              => 'array',
				'sanitize_callback' => array( $this, 'sanitize_settings' ),
			)
		);
	}

	/**
	 * Sanitize submitted settings.
	 *
	 * @param array $input
	 * @return array
	 */
	public function sanitize_settings( $input ) {
		$defaults = LCN_Notice::default_settings();
		$clean    = array();

		$clean['enabled'] = ! empty( $input['enabled'] );

		$display_type           = isset( $input['display_type'] ) ? sanitize_key( $input['display_type'] ) : $defaults['display_type'];
		$clean['display_type']  = in_array( $display_type, array( 'banner', 'popup' ), true ) ? $display_type : $defaults['display_type'];

		$position          = isset( $input['position'] ) ? sanitize_key( $input['position'] ) : $defaults['position'];
		$clean['position'] = in_array( $position, array( 'top', 'bottom' ), true ) ? $position : $defaults['position'];

		$clean['title']   = isset( $input['title'] ) ? sanitize_text_field( $input['title'] ) : $defaults['title'];
		$clean['message'] = isset( $input['message'] ) ? sanitize_textarea_field( $input['message'] ) : $defaults['message'];

		$clean['bg_color']   = isset( $input['bg_color'] ) ? $this->sanitize_color( $input['bg_color'], $defaults['bg_color'] ) : $defaults['bg_color'];
		$clean['text_color'] = isset( $input['text_color'] ) ? $this->sanitize_color( $input['text_color'], $defaults['text_color'] ) : $defaults['text_color'];

		$clean['dismissible'] = ! empty( $input['dismissible'] );

		$clean['start_datetime'] = $this->sanitize_datetime_local( isset( $input['start_datetime'] ) ? $input['start_datetime'] : '' );
		$clean['end_datetime']   = $this->sanitize_datetime_local( isset( $input['end_datetime'] ) ? $input['end_datetime'] : '' );

		return $clean;
	}

	/**
	 * Sanitize a hex color, falling back to a default when invalid.
	 *
	 * @param string $color
	 * @param string $fallback
	 * @return string
	 */
	private function sanitize_color( $color, $fallback ) {
		$sanitized = sanitize_hex_color( $color );

		return $sanitized ? $sanitized : $fallback;
	}

	/**
	 * Sanitize a `datetime-local` input value ("Y-m-d\TH:i") into
	 * "Y-m-d H:i:s", or return an empty string.
	 *
	 * @param string $value
	 * @return string
	 */
	private function sanitize_datetime_local( $value ) {
		$value = sanitize_text_field( $value );

		if ( '' === $value ) {
			return '';
		}

		$timestamp = strtotime( $value );

		return $timestamp ? gmdate( 'Y-m-d H:i:s', $timestamp ) : '';
	}

	/**
	 * Convert a "Y-m-d H:i:s" stored value into the "Y-m-d\TH:i" format
	 * the `datetime-local` input expects.
	 *
	 * @param string $value
	 * @return string
	 */
	private function to_datetime_local( $value ) {
		if ( empty( $value ) ) {
			return '';
		}

		$timestamp = strtotime( $value );

		return $timestamp ? gmdate( 'Y-m-d\TH:i', $timestamp ) : '';
	}

	/**
	 * Render the settings page.
	 */
	public function render_settings_page() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$settings = LCN_Notice::get_settings();
		$name     = LCN_Notice::OPTION_NAME;
		?>
		<div class="wrap">
			<h1><?php esc_html_e( 'Local Closure Notice', 'local-closure-notice' ); ?></h1>
			<p><?php esc_html_e( 'Announce a holiday closure, weather closure, or "closed today" with a banner or popup — no page editing required. It hides itself automatically once the end date passes.', 'local-closure-notice' ); ?></p>

			<?php if ( LCN_Notice::is_active() ) : ?>
				<div class="notice notice-success inline"><p><?php esc_html_e( 'The notice is currently live on your site.', 'local-closure-notice' ); ?></p></div>
			<?php elseif ( ! empty( $settings['enabled'] ) ) : ?>
				<div class="notice notice-warning inline"><p><?php esc_html_e( 'Enabled, but outside its scheduled window — it is not showing on the site right now.', 'local-closure-notice' ); ?></p></div>
			<?php endif; ?>

			<form method="post" action="options.php">
				<?php settings_fields( self::SETTINGS_GROUP ); ?>
				<table class="form-table" role="presentation">
					<tr>
						<th scope="row"><?php esc_html_e( 'Show notice', 'local-closure-notice' ); ?></th>
						<td>
							<label>
								<input type="checkbox" name="<?php echo esc_attr( $name ); ?>[enabled]" value="1" <?php checked( ! empty( $settings['enabled'] ) ); ?> />
								<?php esc_html_e( 'Enable the closure notice on the front end', 'local-closure-notice' ); ?>
							</label>
						</td>
					</tr>
					<tr>
						<th scope="row"><?php esc_html_e( 'Display as', 'local-closure-notice' ); ?></th>
						<td>
							<label style="margin-right:16px;">
								<input type="radio" name="<?php echo esc_attr( $name ); ?>[display_type]" value="banner" <?php checked( 'banner', $settings['display_type'] ); ?> />
								<?php esc_html_e( 'Banner (bar across the site)', 'local-closure-notice' ); ?>
							</label>
							<label>
								<input type="radio" name="<?php echo esc_attr( $name ); ?>[display_type]" value="popup" <?php checked( 'popup', $settings['display_type'] ); ?> />
								<?php esc_html_e( 'Popup (modal on page load)', 'local-closure-notice' ); ?>
							</label>
						</td>
					</tr>
					<tr>
						<th scope="row"><?php esc_html_e( 'Banner position', 'local-closure-notice' ); ?></th>
						<td>
							<label style="margin-right:16px;">
								<input type="radio" name="<?php echo esc_attr( $name ); ?>[position]" value="top" <?php checked( 'top', $settings['position'] ); ?> />
								<?php esc_html_e( 'Top', 'local-closure-notice' ); ?>
							</label>
							<label>
								<input type="radio" name="<?php echo esc_attr( $name ); ?>[position]" value="bottom" <?php checked( 'bottom', $settings['position'] ); ?> />
								<?php esc_html_e( 'Bottom', 'local-closure-notice' ); ?>
							</label>
							<p class="description"><?php esc_html_e( 'Only applies when "Banner" is selected above.', 'local-closure-notice' ); ?></p>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for="lcn_title"><?php esc_html_e( 'Title', 'local-closure-notice' ); ?></label>
						</th>
						<td>
							<input type="text" id="lcn_title" name="<?php echo esc_attr( $name ); ?>[title]" value="<?php echo esc_attr( $settings['title'] ); ?>" class="regular-text" />
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for="lcn_message"><?php esc_html_e( 'Message', 'local-closure-notice' ); ?></label>
						</th>
						<td>
							<textarea id="lcn_message" name="<?php echo esc_attr( $name ); ?>[message]" rows="3" class="large-text"><?php echo esc_textarea( $settings['message'] ); ?></textarea>
						</td>
					</tr>
					<tr>
						<th scope="row"><?php esc_html_e( 'Colors', 'local-closure-notice' ); ?></th>
						<td>
							<label style="margin-right:16px;display:inline-block;">
								<?php esc_html_e( 'Background', 'local-closure-notice' ); ?><br />
								<input type="text" class="lcn-color-field" name="<?php echo esc_attr( $name ); ?>[bg_color]" value="<?php echo esc_attr( $settings['bg_color'] ); ?>" />
							</label>
							<label style="display:inline-block;">
								<?php esc_html_e( 'Text', 'local-closure-notice' ); ?><br />
								<input type="text" class="lcn-color-field" name="<?php echo esc_attr( $name ); ?>[text_color]" value="<?php echo esc_attr( $settings['text_color'] ); ?>" />
							</label>
						</td>
					</tr>
					<tr>
						<th scope="row"><?php esc_html_e( 'Visitor can dismiss', 'local-closure-notice' ); ?></th>
						<td>
							<label>
								<input type="checkbox" name="<?php echo esc_attr( $name ); ?>[dismissible]" value="1" <?php checked( ! empty( $settings['dismissible'] ) ); ?> />
								<?php esc_html_e( 'Show a close button and remember the dismissal in the visitor\'s browser', 'local-closure-notice' ); ?>
							</label>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for="lcn_start"><?php esc_html_e( 'Display from', 'local-closure-notice' ); ?></label>
						</th>
						<td>
							<input type="datetime-local" id="lcn_start" name="<?php echo esc_attr( $name ); ?>[start_datetime]" value="<?php echo esc_attr( $this->to_datetime_local( $settings['start_datetime'] ) ); ?>" />
							<p class="description"><?php esc_html_e( 'Optional. Leave blank to show immediately once enabled.', 'local-closure-notice' ); ?></p>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for="lcn_end"><?php esc_html_e( 'Auto-expire on', 'local-closure-notice' ); ?></label>
						</th>
						<td>
							<input type="datetime-local" id="lcn_end" name="<?php echo esc_attr( $name ); ?>[end_datetime]" value="<?php echo esc_attr( $this->to_datetime_local( $settings['end_datetime'] ) ); ?>" />
							<p class="description"><?php esc_html_e( 'Optional. The notice hides itself automatically after this date/time — no need to remember to turn it off.', 'local-closure-notice' ); ?></p>
						</td>
					</tr>
				</table>
				<?php submit_button(); ?>
			</form>
		</div>
		<?php
	}
}
