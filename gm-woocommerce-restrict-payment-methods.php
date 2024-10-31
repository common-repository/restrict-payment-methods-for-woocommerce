<?php
/**
 * Plugin Name: Restrict Payment Methods For WooCommerce
 * Description: GM WooCommerce Restrict Payment Methods its restrict payment many condition
 * Version:     1.0
 * Author:      Gravity Master
 * License:     GPLv2 or later
 * Text Domain: gmwrpm
 */

/* Stop immediately if accessed directly. */
if ( ! defined( 'ABSPATH' ) ) {
	die();
}

/* All constants should be defined in this file. */
if ( ! defined( 'GMWRPM_PREFIX' ) ) {
	define( 'GMWRPM_PREFIX', 'gmwrpm' );
}
if ( ! defined( 'GMWRPM_PLUGIN_DIR' ) ) {
	define( 'GMWRPM_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
}
if ( ! defined( 'GMWRPM_PLUGIN_BASENAME' ) ) {
	define( 'GMWRPM_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
}
if ( ! defined( 'GMWRPM_PLUGIN_URL' ) ) {
	define( 'GMWRPM_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
}

/* Auto-load all the necessary classes. */
if( ! function_exists( 'gmwrpm_class_auto_loader' ) ) {
	
	function gmwrpm_class_auto_loader( $class ) {
		
		$includes = GMWRPM_PLUGIN_DIR . 'includes/' . $class . '.php';
		
		if( is_file( $includes ) && ! class_exists( $class ) ) {
			include_once( $includes );
			return;
		}
		
	}
}
spl_autoload_register('gmwrpm_class_auto_loader');

/* Initialize all modules now. */
new GMWRPM_Cron();
new GMWRPM_Comman();
new GMWRPM_Admin();
new GMWRPM_Frontend();

?>