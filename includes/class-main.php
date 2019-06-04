<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class WP_Job_Manager_Companies {

	/**
	 * @var $instance
	 */
	private static $instance;

	/**
	 * @var slug
	 */
	private $slug;

	/**
	 * Make sure only one instance is running.
	 */
	public static function instance() {
		if ( ! isset ( self::$instance ) ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Start things up.
	 *
	 * @since WP Job Manager - Company Profiles 1.0
	 */
	public function __construct() {
		$this->setup_globals();
		$this->setup_actions();
	}

	/**
	 * Set some smart defaults to class variables. Allow some of them to be
	 * filtered to allow for early overriding.
	 *
	 * @since WP Job Manager - Company Profiles 1.0
	 *
	 * @return void
	 */
	private function setup_globals() {
		// Some useful variables
		$this->file         = __FILE__;
		$this->basename     = plugin_basename( $this->file );
		$this->plugin_dir   = plugin_dir_path( $this->file );
		$this->plugin_url   = plugin_dir_url( $this->file );
		$this->lang_dir     = trailingslashit( $this->plugin_dir . 'languages' );
		$this->domain       = 'wp-job-manager-company-profiles';
		
		/**
		 * Included dependant classes
		 */
		$files = array(
            'class-taxonomy.php',
			'class-fields.php',
            'class-template-loader.php',
		);
		
        foreach ( $files as $file ) {
            include_once( $this->plugin_dir . '/' . $file );
		}
		
		$this->taxonomy = new WP_Job_Manager_Companies_Taxonomy;
		$this->fields = new WP_Job_Manager_Companies_Fields;
        $this->template = new WP_Job_Manager_Companies_Template_Loader;

		/**
		 * The slug for creating permalinks
		 */
		$this->slug = apply_filters( 'wp_job_manager_companies_company_slug', __( 'company',
		'wp-job-manager-company-profiles' ) );
	}

	/**
	 * Setup the default hooks and actions
	 *
	 * @since WP Job Manager - Company Profiles 1.0
	 *
	 * @return void
	 */
	private function setup_actions() {
		/** Load Textdomain */
		add_action( 'plugins_loaded', array( $this, 'load_plugin_textdomain' ) );
		
		/** Body Class for Taxonomy Page */
		add_action( 'body_class', array( $this, 'company_taxonomy_class' ) );
		
		/** Modify Page Title for Taxonomy Page */
		add_filter( 'pre_get_document_title', array( $this, 'page_title' ), 20 );

		/** Register Shortcode */
		add_shortcode( 'job_manager_companies', array( $this, 'job_manager_companies' ) );
	}

	public function get_slug() {
		return $this->slug;
	}

    #-------------------------------------------------------------------------------#
    #  Localisation
    #-------------------------------------------------------------------------------#

	public function load_plugin_textdomain() {
		$locale = apply_filters( 'plugin_locale', get_locale(), 'wp-job-manager-company-profiles' );

		load_textdomain( 'wp-job-manager-company-profiles', WP_LANG_DIR . "/wp-job-manager-company-profiles/wp-job-manager-company-profiles-$locale.mo" );
		load_plugin_textdomain( 'wp-job-manager-company-profiles', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}

    #-------------------------------------------------------------------------------#
    #  Body Class for Taxonomy Page
    #-------------------------------------------------------------------------------#

	public function company_taxonomy_class($classes) {
		$classes[] = is_tax('job_listing_company') ? 'single-company' : '';
		return $classes;
	}

    #-------------------------------------------------------------------------------#
    #  Modify Page Title for Taxonomy Page
    #-------------------------------------------------------------------------------#

	function page_title($title) {
		global $paged, $page;
		$sep = apply_filters( 'document_title_separator', '-' );
		
		if ( is_tax('job_listing_company') ) {
			$title = get_bloginfo( 'name' );
			$site_description = get_bloginfo( 'description', 'display' );
			if ( $site_description && ( is_home() || is_front_page() ) ) {
				$title = "$title $sep $site_description";
			} else {
				$term = get_queried_object();
				$title = sprintf( __( 'Jobs at %s', 'wp-job-manager-company-profiles' ), $term->name ) . " $sep $title";
			}
		}

		return $title;
	}

    #-------------------------------------------------------------------------------#
    #  Register the `[job_manager_companies]` shortcode
    #-------------------------------------------------------------------------------#

	public function job_manager_companies( $atts ) {
        $atts = shortcode_atts( array(), $atts );
        ob_start();
		$this->template->get_template_part( 'content', 'company_listing' );
        return ob_get_clean();
	}

}
