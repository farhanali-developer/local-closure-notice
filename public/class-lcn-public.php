<?php
/**
 * Front-end rendering: enqueues assets and outputs the banner or popup
 * markup when the notice is currently active.
 *
 * @package Local_Closure_Notice
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

class LCN_Public {

	public function __construct() {
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_assets' ) );
		add_action( 'wp_footer', array( $this, 'render_notice' ) );
	}

	/**
	 * Only load the (tiny) CSS/JS when the notice is actually active.
	 */
	public function enqueue_assets() {
		if ( ! LCN_Notice::is_active() ) {
			return;
		}

		wp_enqueue_style( 'lcn-public', LCN_PLUGIN_URL . 'public/css/lcn-public.css', array(), LCN_VERSION );
		wp_enqueue_script( 'lcn-public', LCN_PLUGIN_URL . 'public/js/lcn-public.js', array(), LCN_VERSION, true );
	}

	/**
	 * Print the banner/popup markup in the footer.
	 */
	public function render_notice() {
		if ( ! LCN_Notice::is_active() ) {
			return;
		}

		$settings = LCN_Notice::get_settings();
		$key      = md5( $settings['title'] . '|' . $settings['message'] . '|' . $settings['end_datetime'] );
		$classes  = array( 'lcn-notice', 'lcn-' . $settings['display_type'] );

		if ( 'banner' === $settings['display_type'] ) {
			$classes[] = 'lcn-position-' . $settings['position'];
		}
		?>
		<div
			id="lcn-notice"
			class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>"
			data-dismiss-key="lcn_dismissed_<?php echo esc_attr( $key ); ?>"
			style="background-color:<?php echo esc_attr( $settings['bg_color'] ); ?>;color:<?php echo esc_attr( $settings['text_color'] ); ?>;"
			role="alert"
		>
			<?php if ( 'popup' === $settings['display_type'] ) : ?>
				<div class="lcn-overlay"></div>
			<?php endif; ?>
			<div class="lcn-box">
				<?php if ( ! empty( $settings['title'] ) ) : ?>
					<strong class="lcn-title"><?php echo esc_html( $settings['title'] ); ?></strong>
				<?php endif; ?>
				<span class="lcn-message"><?php echo esc_html( $settings['message'] ); ?></span>
				<?php if ( ! empty( $settings['dismissible'] ) ) : ?>
					<button type="button" class="lcn-close" aria-label="<?php esc_attr_e( 'Dismiss', 'local-closure-notice' ); ?>">&times;</button>
				<?php endif; ?>
			</div>
		</div>
		<?php
	}
}
