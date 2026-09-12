<?php
/**
 * Core logic: settings storage and whether the notice should currently display.
 *
 * @package Local_Closure_Notice
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

class LCN_Notice {

	const OPTION_NAME = 'lcn_settings';

	/**
	 * Default settings.
	 *
	 * @return array
	 */
	public static function default_settings() {
		return array(
			'enabled'        => false,
			'display_type'   => 'banner', // banner|popup.
			'position'       => 'top',    // top|bottom (banner only).
			'title'          => __( "We're Closed", 'local-closure-notice' ),
			'message'        => __( 'We are closed right now. Please check back soon.', 'local-closure-notice' ),
			'bg_color'       => '#b91c1c',
			'text_color'     => '#ffffff',
			'dismissible'    => true,
			'start_datetime' => '',
			'end_datetime'   => '',
		);
	}

	/**
	 * Get the saved settings, merged with defaults.
	 *
	 * @return array
	 */
	public static function get_settings() {
		$saved = get_option( self::OPTION_NAME, array() );

		return wp_parse_args( $saved, self::default_settings() );
	}

	/**
	 * Whether the notice should be shown right now, based on the enabled
	 * flag and the optional start/end window (compared in the site's
	 * configured timezone).
	 *
	 * @return bool
	 */
	public static function is_active() {
		$settings = self::get_settings();

		if ( empty( $settings['enabled'] ) ) {
			return false;
		}

		$now = current_time( 'timestamp' ); // phpcs:ignore WordPress.DateTime.CurrentTimeTimestamp.Requested

		if ( ! empty( $settings['start_datetime'] ) ) {
			$start = strtotime( $settings['start_datetime'] );
			if ( $start && $now < $start ) {
				return false;
			}
		}

		if ( ! empty( $settings['end_datetime'] ) ) {
			$end = strtotime( $settings['end_datetime'] );
			if ( $end && $now > $end ) {
				return false;
			}
		}

		return true;
	}
}
