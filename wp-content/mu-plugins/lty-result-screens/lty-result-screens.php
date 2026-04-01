<?php
/**
 * Version: 1.0.0
 * Requires at least: 6.2
 * Requires PHP: 7.4
 * Author: Nera Comp
 * Text Domain: lty-result-screens
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'LTY_RS_VERSION', '1.0.0' );
define( 'LTY_RS_FILE', __FILE__ );
define( 'LTY_RS_PATH', plugin_dir_path( __FILE__ ) );
define( 'LTY_RS_URL', plugin_dir_url( __FILE__ ) );

add_action( 'plugins_loaded', function () {
	if ( ! function_exists( 'LTY' ) ) {
		return;
	}
	require_once LTY_RS_PATH . 'inc/class-lty-result-screens.php';
	LTY_Result_Screens::instance();
}, 20 );
