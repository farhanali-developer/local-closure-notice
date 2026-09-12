<?php
/**
 * Runs on plugin activation.
 *
 * @package Local_Closure_Notice
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

class LCN_Activator {

	/**
	 * Seed default settings if none exist yet.
	 */
	public static function activate() {
		if ( false === get_option( LCN_Notice::OPTION_NAME, false ) ) {
			add_option( LCN_Notice::OPTION_NAME, LCN_Notice::default_settings() );
		}
	}
}
