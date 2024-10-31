<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.mikehit.com
 * @since             1.1.0
 * @package           Popup Lead plugin
 *
 * @wordpress-plugin
 * Plugin Name:       Popup Lead
 * Plugin URI:        https://www.mikehit.com/popup-lead
 * Description:       Tools to boost your marketing and lead gen efforts. Using popup lead is a to to increase your site's conversions! 

 * Version:           1.1.0
 * Author:            Iterion Technology
 * Author URI:        https://www.mikehit.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       popup-lead
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-popup-lead-activator.php
 */
function activate_popup_lead() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-popup-lead-activator.php';
	popup_lead_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-popup-lead-deactivator.php
 */
function deactivate_popup_lead() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-popup-lead-deactivator.php';
	popup_lead_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_popup_lead' );
register_deactivation_hook( __FILE__, 'deactivate_popup_lead' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-popup-lead.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_popup_lead() {

	$plugin = new popup_lead();
	$plugin->run();

}
run_popup_lead();
