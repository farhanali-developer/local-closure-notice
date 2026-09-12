<?php
/**
 * Plugin bootstrap file.
 *
 * @link              https://farhanali.me
 * @since             1.0.0
 * @package           Local_Closure_Notice
 *
 * @wordpress-plugin
 * Plugin Name:       Local Closure Notice
 * Plugin URI:        https://wordpress.org/plugins/local-closure-notice
 * Description:       One-click banner or popup for local businesses to announce holiday closures, weather closures, or "closed today" — no page editing required. Auto-expires on a set date.
 * Version:           1.0.0
 * Author:            Farhan Ali
 * Author URI:        https://farhanali.me/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       local-closure-notice
 * Domain Path:       /languages
 * Requires at least: 5.8
 * Requires PHP:      7.2
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 */
define( 'LCN_VERSION', '1.0.0' );
define( 'LCN_PLUGIN_FILE', __FILE__ );
define( 'LCN_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'LCN_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'LCN_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );

require LCN_PLUGIN_DIR . 'includes/class-lcn-notice.php';
require LCN_PLUGIN_DIR . 'includes/class-lcn-activator.php';

register_activation_hook( __FILE__, array( 'LCN_Activator', 'activate' ) );

if ( is_admin() ) {
	require LCN_PLUGIN_DIR . 'admin/class-lcn-admin.php';
	new LCN_Admin();
} else {
	require LCN_PLUGIN_DIR . 'public/class-lcn-public.php';
	new LCN_Public();
}
