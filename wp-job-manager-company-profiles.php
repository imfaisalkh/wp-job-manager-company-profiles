<?php
/**
 * @link              http://example.com
 * @since             1.0.0
 * @package           WP_JOB_MANAGER_COMPANY_PROFILES
 *
 * @wordpress-plugin
 * Plugin Name:       WP Job Manager - Company Profiles
 * Plugin URI:        https://wpscouts.net
 * Description:       Output a list of all companies that have posted a job, with a link to a company profile.
 * Version:           1.0.0
 * Author:            Faisal Khurshid
 * Author URI:        https://wpscouts.net
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wp-job-manage-company-profiles
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'WP_JOB_MANAGER_COMPANY_PROFILES', '1.0.0' );
define( 'WP_JOB_MANAGER_COMPANY_PROFILES_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-plugin-name-activator.php
 */
function activate_plugin_name() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-job-manager-company-profiles.php';
	$instance = new WP_Job_Manager_Companies();
	$instance->plugin_activation();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-plugin-name-deactivator.php
 */
function deactivate_plugin_name() {
	// require_once plugin_dir_path( __FILE__ ) . 'includes/class-plugin-name-deactivator.php';
	// Plugin_Name_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_plugin_name' );
register_deactivation_hook( __FILE__, 'deactivate_plugin_name' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-wp-job-manager-company-profiles.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_plugin_name() {

	$GLOBALS['wpjmcp'] = new WP_Job_Manager_Companies(); 
	// add_action( 'plugins_loaded', array( 'WP_Job_Manager_Companies', 'instance' ) );
}
run_plugin_name();
