<?php

// Import Classes
require WP_JOB_MANAGER_COMPANY_PROFILES_PLUGIN_DIR . 'includes/lib/class-gamajo-template-loader.php';
 
/**
 * Template loader for PW Sample Plugin.
 *
 * Only need to specify class properties here.
 *
 */
class WPJMCL_Template_Loader extends Gamajo_Template_Loader {
 
	/**
	 * Prefix for filter names.
	 *
	 * @since 1.0.0
	 * @type string
	 */
	protected $filter_prefix = 'wpjmcl';
 
	/**
	 * Directory name where custom templates for this plugin should be found in the theme.
	 *
	 * @since 1.0.0
	 * @type string
	 */
	protected $theme_template_directory = 'wp-job-manager-companies';
 
	/**
	 * Reference to the root directory path of this plugin.
	 *
	 * @since 1.0.0
	 * @type string
	 */
	protected $plugin_directory = WP_JOB_MANAGER_COMPANY_PROFILES_PLUGIN_DIR;
 
}