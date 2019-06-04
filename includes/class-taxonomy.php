<?php

class WP_Job_Manager_Companies_Taxonomy extends WP_Job_Manager_Companies {


	/**
	 * Setup the default hooks and actions
	 *
	 * @since WP Job Manager - Company Profiles 1.0
	 *
	 * @return void
	 */
	public function __construct() {
		/** Rgister Taxonomies */
		add_action( 'init', array( $this, 'register_company_taxonomy' ), 0 );
		add_action( 'init', array( $this, 'register_industry_taxonomy' ), 0 );

		/** Register Settings */
		add_filter( 'job_manager_settings', array( $this, 'job_manager_settings' ) );
	}

	#-------------------------------------------------------------------------------#
    #  Register `job_listing_company` taxonomy
    #-------------------------------------------------------------------------------#

	public function register_company_taxonomy() {
		$admin_capability = 'manage_job_listings';

		$company_singular  = __( 'Company', 'wp-job-manager-company-profiles' );
		$company_plural    = __( 'Companies', 'wp-job-manager-company-profiles' );

		if ( current_theme_supports( 'job-manager-templates' ) ) {
			$company_rewrite     = array(
				'slug'         => _x( 'company', 'Company slug - resave permalinks after changing this', 'wp-job-manager-company-profiles' ),
				'with_front'   => false,
				'hierarchical' => false
			);
		} else {
			$company_rewrite = false;
		}

		register_taxonomy( 'job_listing_company',
			array( 'job_listing' ),
			array(
				'hierarchical' 			=> false,
				'update_count_callback' => '_update_post_term_count',
				'label' 				=> $company_plural,
				'labels' => array(
					'name' 				=> $company_plural,
					'singular_name' 	=> $company_singular,
					'search_items' 		=> sprintf( __( 'Search %s', 'wp-job-manager-company-profiles' ), $company_plural ),
					'all_items' 		=> sprintf( __( 'All %s', 'wp-job-manager-company-profiles' ), $company_plural ),
					'parent_item' 		=> sprintf( __( 'Parent %s', 'wp-job-manager-company-profiles' ), $company_singular ),
					'parent_item_colon' => sprintf( __( 'Parent %s:', 'wp-job-manager-company-profiles' ), $company_singular ),
					'edit_item' 		=> sprintf( __( 'Edit %s', 'wp-job-manager-company-profiles' ), $company_singular ),
					'update_item' 		=> sprintf( __( 'Update %s', 'wp-job-manager-company-profiles' ), $company_singular ),
					'add_new_item' 		=> sprintf( __( 'Add New %s', 'wp-job-manager-company-profiles' ), $company_singular ),
					'new_item_name' 	=> sprintf( __( 'New %s Name', 'wp-job-manager-company-profiles' ),  $company_singular )
				),
				'show_ui' 				=> true,
				'query_var' 			=> true,
				'has_archive'           => true,
				'capabilities'			=> array(
					'manage_terms' 		=> $admin_capability,
					'edit_terms' 		=> $admin_capability,
					'delete_terms' 		=> $admin_capability,
					'assign_terms' 		=> $admin_capability,
				),
				'show_in_rest' 			=> true,
				'rewrite' 				=> $company_rewrite,
			)
		);

	}

    #-------------------------------------------------------------------------------#
    #  Register `job_listing_industry` taxonomy
    #-------------------------------------------------------------------------------#

	public function register_industry_taxonomy() {
		$admin_capability = 'manage_job_listings';

		$industry_singular  = __( 'Industry', 'wp-job-manager-company-profiles' );
		$industry_plural    = __( 'Industries', 'wp-job-manager-company-profiles' );

		if ( current_theme_supports( 'job-manager-templates' ) ) {
			$industry_rewrite     = array(
				'slug'         => _x( 'industry', 'Industry slug - resave permalinks after changing this', 'wp-job-manager-company-profiles' ),
				'with_front'   => false,
				'hierarchical' => false
			);
		} else {
			$industry_rewrite = false;
		}

		register_taxonomy( 'job_listing_industry',
			array( 'job_listing' ),
			array(
				'hierarchical' 			=> false,
				'update_count_callback' => '_update_post_term_count',
				'label' 				=> $industry_plural,
				'labels' => array(
					'name' 				=> $industry_plural,
					'singular_name' 	=> $industry_singular,
					'search_items' 		=> sprintf( __( 'Search %s', 'wp-job-manager-company-profiles' ), $industry_plural ),
					'all_items' 		=> sprintf( __( 'All %s', 'wp-job-manager-company-profiles' ), $industry_plural ),
					'parent_item' 		=> sprintf( __( 'Parent %s', 'wp-job-manager-company-profiles' ), $industry_singular ),
					'parent_item_colon' => sprintf( __( 'Parent %s:', 'wp-job-manager-company-profiles' ), $industry_singular ),
					'edit_item' 		=> sprintf( __( 'Edit %s', 'wp-job-manager-company-profiles' ), $industry_singular ),
					'update_item' 		=> sprintf( __( 'Update %s', 'wp-job-manager-company-profiles' ), $industry_singular ),
					'add_new_item' 		=> sprintf( __( 'Add New %s', 'wp-job-manager-company-profiles' ), $industry_singular ),
					'new_item_name' 	=> sprintf( __( 'New %s Name', 'wp-job-manager-company-profiles' ),  $industry_singular )
				),
				'show_ui' 				=> true,
				'query_var' 			=> true,
				'has_archive'           => true,
				'capabilities'			=> array(
					'manage_terms' 		=> $admin_capability,
					'edit_terms' 		=> $admin_capability,
					'delete_terms' 		=> $admin_capability,
					'assign_terms' 		=> $admin_capability,
				),
				'show_in_rest' 			=> true,
				'rewrite' 				=> $industry_rewrite,
			)
		);

	}

    #-------------------------------------------------------------------------------#
    #  Register `job_listing_company` taxonomy
    #-------------------------------------------------------------------------------#

	public function job_manager_settings( $settings ) {
        $settings[ 'job_submission' ][1][] = array(
            'name'     => 'job_manager_enable_user_specific_company',
            'std'      => '1',
            'label'    => __( 'User Specific Companies', 'wp-job-manager-company-profiles' ),
            'cb_label' => __( 'Enable user-specific companies', 'wp-job-manager-company-profiles' ),
            'desc'     => __( 'Users would only be limited to assigned companies under "Existing Company" tab.', 'wp-job-manager-company-profiles' ),
            'type'     => 'checkbox'
        );

        return $settings;
    }

}
