<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class WP_Job_Manager_Companies_Fields extends WP_Job_Manager_Companies  {

	/**
	 * Setup the default hooks and actions
	 *
	 * @since WP Job Manager - Company Profiles 1.0
	 *
	 * @return void
	 */
	public function __construct() {
        // register fields
        add_action( 'init', array( $this, 'register_company_taxonomy_field' ) );
        add_action( 'init', array( $this, 'register_company_industry_field' ) );
        add_action( 'init', array( $this, 'register_company_foundation_field' ) );
        add_action( 'init', array( $this, 'register_company_location_field' ) );
        add_action( 'init', array( $this, 'register_company_size_field' ) );
        add_action( 'init', array( $this, 'register_company_description_field' ) );
        add_action( 'init', array( $this, 'register_company_email_field' ) );
        add_action( 'init', array( $this, 'register_company_facebook_field' ) );
        add_action( 'init', array( $this, 'register_company_linkedin_field' ) );
        
        // register field options
        add_action( 'init', array( $this, 'company_fields_options' ) );
        
        // set fields priority
        add_filter( 'submit_job_form_fields', array( $this, 'company_fields_priority' ) );
    
        // handle company term field
        add_filter( 'job_manager_term_select_field_wp_dropdown_categories_args', array( $this, 'job_manager_term_select_field_wp_dropdown_categories_args' ), 10, 3 );
        add_filter( 'submit_job_form_fields_get_job_data', array( $this, 'submit_job_form_fields_get_job_data' ), 10, 2 );

        // Modify "Term Select" Field
        add_filter( 'job_manager_term_select_field_wp_dropdown_categories_args', array( $this, 'modify_term_select_field' ) );

        // Create "Company Term" on Submission
        add_action( 'job_manager_update_job_data', array( $this, 'create_company_term' ), 10, 2 );

        // Make sure that "New Company" form is blank
        add_filter( 'submit_job_form_fields_get_user_data', array( $this, 'blank_new_company' ) );

    }

    #-------------------------------------------------------------------------------#
    #  Company Fields - Options
    #-------------------------------------------------------------------------------#

	public function company_fields_options() {

        // Company Size
        function company_size( $options ) {
            $args = array('', '1 - 49', '50 - 149', '150 - 249', '250 - 499', '500 - 749', '750-999', '1000 +');
            $options = array_combine($args, $args);
            return $options;
        }
        add_filter( 'wpjmcp_company_size_options', 'company_size' );
    
    }

    #-------------------------------------------------------------------------------#
    #  Company Fields - Priority
    #-------------------------------------------------------------------------------#

	public function company_fields_priority($fields) {
        $company_fields = array(
            'company_name',
            'company_term',
            'company_email',
            'company_tagline',
            'company_foundation',
            'company_location',
            'company_industry',
            'company_website',
            'company_size',
            'company_description',
            'company_video',
            'company_twitter',
            'company_facebook',
            'company_linkedin',
            'company_logo'
        );

        foreach ($company_fields as $key => $value) {
            $fields['company'][$value]['priority'] = $key + 1;
        }

        return $fields;
    }


    #-------------------------------------------------------------------------------#
    #  Register Company Taxonomy Field
    #-------------------------------------------------------------------------------#

	public function register_company_taxonomy_field() {
        // add field in "front-end"
        function frontend_company_taxonomy_field( $fields ) {
            $fields['company']['company_term'] = array(
                'label'       => __( 'Company Name', 'wp-job-manager-company-profiles' ),
                'type'        => 'term-select',
                'taxonomy'    => 'job_listing_company',
                'required'    => false,
                'description' => esc_html__( 'It\'ll populate rest of the form with company data.', 'wp-job-manager-company-profiles' ),
                'priority'    => '1',
                'default'     => -1
            );
    
            return $fields;
        }
        add_filter( 'submit_job_form_fields', 'frontend_company_taxonomy_field' );
    }


    #-------------------------------------------------------------------------------#
    #  Handle Company Taxonomy Field
    #-------------------------------------------------------------------------------#

    // add a default "select company" option
    public function job_manager_term_select_field_wp_dropdown_categories_args( $args, $key, $field ) {
		if ( 'company_term' !== $key ) {
			return $args;
		}

		$args['show_option_none'] = __( 'Select Company', 'wp-job-manager-company-profiles' );
		$args['option_none_value'] = '';

		return $args;
    }
    
    // keep "company term" value persistent on reload form
    public function submit_job_form_fields_get_job_data( $fields, $job ) {
		$field = isset( $fields['company'][ 'company_term' ] ) ? $fields['company'][ 'company_term' ] : false;

		if ( $field ) {
			$fields['company'][ 'company_term' ][ 'value' ] = wp_get_object_terms( $job->ID, $field['taxonomy'], array( 'fields' => 'ids' ) );
		}

		return $fields;
	}


    #-------------------------------------------------------------------------------#
    #  Register Company Foundation Field
    #-------------------------------------------------------------------------------#

	public function register_company_foundation_field() {
		// add field in "front-end"
		function frontend_company_foundation_field( $fields ) {
			$fields['company']['company_foundation'] = array(
				'label'       => esc_html__( 'Foundation Year', 'wp-job-manager-company-profiles' ),
				'type'        => 'text',
				'required'    => false,
				'priority'    => 4,
				'placeholder' => 'e.g. 1992',
			);
			return $fields;
		}
		add_filter( 'submit_job_form_fields', 'frontend_company_foundation_field' );

		// add field in "back-end"
		function admin_company_foundation_field( $fields ) {
			$fields['_company_foundation'] = array(
                'label'       => esc_html__( 'Foundation Year', 'wp-job-manager-company-profiles' ),
                'type'        => 'text',
                'required'    => false,
                'placeholder' => 'e.g. 1992',
			);
			return $fields;
		}
		add_filter( 'job_manager_job_listing_data_fields', 'admin_company_foundation_field' );
    }
    
    /**
	 * Register Company Facebook Field
	 */
	public function register_company_facebook_field() {
		// add field in "front-end"
		function frontend_company_facebook_field( $fields ) {
			$fields['company']['company_facebook'] = array(
				'label'       => esc_html__( 'Facebook Username', 'wp-job-manager-company-profiles' ),
				'type'        => 'text',
				'required'    => false,
				'priority'    => 4,
			);
			return $fields;
		}
		add_filter( 'submit_job_form_fields', 'frontend_company_facebook_field' );

		// add field in "back-end"
		function admin_company_facebook_field( $fields ) {
			$fields['_company_facebook'] = array(
                'label'       => esc_html__( 'Facebook Username', 'wp-job-manager-company-profiles' ),
                'type'        => 'text',
                'required'    => false,
                'placeholder' => '',
			);
			return $fields;
		}
		add_filter( 'job_manager_job_listing_data_fields', 'admin_company_facebook_field' );
    }
    
    /**
	 * Register Company Linkedn Field
	 */
	public function register_company_linkedin_field() {
		// add field in "front-end"
		function frontend_company_linkedin_field( $fields ) {
			$fields['company']['company_linkedin'] = array(
				'label'       => esc_html__( 'LinkedIn Username', 'wp-job-manager-company-profiles' ),
				'type'        => 'text',
				'required'    => false,
				'priority'    => 9,
			);
			return $fields;
		}
		add_filter( 'submit_job_form_fields', 'frontend_company_linkedin_field' );

		// add field in "back-end"
		function admin_company_linkedin_field( $fields ) {
			$fields['_company_linkedin'] = array(
                'label'       => esc_html__( 'LinkedIn Username', 'wp-job-manager-company-profiles' ),
                'type'        => 'text',
                'required'    => false,
                'placeholder' => '',
			);
			return $fields;
		}
		add_filter( 'job_manager_job_listing_data_fields', 'admin_company_linkedin_field' );
	}


    /**
	 * Register Company Description Field
	 */
	public function register_company_description_field() {
        // add field in "front-end"
        function frontend_company_description_field( $fields ) {
            $fields['company']['company_description'] = array(
                'label'       => esc_html__( 'Description', 'wp-job-manager-company-profiles' ),
                'type'        => 'textarea',
                'required'    => false,
                'description' => esc_html__( 'Describe your company in a few words.', 'wp-job-manager-company-profiles' ),
                'priority'    => 4
            );
            return $fields;
        }
        add_filter( 'submit_job_form_fields', 'frontend_company_description_field' );

        // add field in "back-end"
        function admin_company_description_field( $fields ) {
            $fields['_company_description'] = array(
            'label'       => esc_html__( 'Company Description', 'wp-job-manager-company-profiles' ),
            'type'        => 'textarea',
            'required'    => false,
            'placeholder' => esc_html__( 'Describe your company in a few words.', 'wp-job-manager-company-profiles' ),
            );
            return $fields;
        }
        add_filter( 'job_manager_job_listing_data_fields', 'admin_company_description_field' );
	}

    /**
	 * Register Company Email Field
	 */
	public function register_company_email_field() {
        // add field in "front-end"
        function frontend_company_email_field( $fields ) {
            $fields['company']['company_email'] = array(
                'label'       => esc_html__( 'Company Email', 'wp-job-manager-company-profiles' ),
                'type'        => 'text',
                'required'    => false,
                'priority'    => 1.5
            );
            return $fields;
        }
        add_filter( 'submit_job_form_fields', 'frontend_company_email_field' );

        // add field in "back-end"
        function admin_company_email_field( $fields ) {
            $fields['_company_email'] = array(
                'label'       => esc_html__( 'Company Email', 'wp-job-manager-company-profiles' ),
                'type'        => 'text',
                'required'    => false,
                'placeholder' => esc_html__( 'Company email which is used to contact company', 'wp-job-manager-company-profiles' ),
                'description' => esc_html__( 'Company Website will be used for contact, if email not provided.', 'wp-job-manager-company-profiles' )
            );
            return $fields;
        }
        add_filter( 'job_manager_job_listing_data_fields', 'admin_company_email_field' );
	}

    /**
	 * Register Company Location Field
	 */
	public function register_company_location_field() {
        // add field in "front-end"
        function frontend_company_location_field( $fields ) {
            $fields['company']['company_location'] = array(
                'label'       => esc_html__( 'Location', 'wp-job-manager-company-profiles' ),
                'type'        => 'text',
                'required'    => false,
                'placeholder' => esc_html__( 'e.g. Paris', 'wp-job-manager-company-profiles' ),
                'priority'    => 4
            );
            return $fields;
        }
        add_filter( 'submit_job_form_fields', 'frontend_company_location_field' );

        // add field in "back-end"
        function admin_company_location_field( $fields ) {
            $fields['_company_location'] = array(
            'label'       => esc_html__( 'Company Location', 'wp-job-manager-company-profiles' ),
            'type'        => 'text',
            'required'    => false,
            'placeholder' => esc_html__( 'e.g. Paris', 'wp-job-manager-company-profiles' ),
            'description' => ''
            );
            return $fields;
        }
        add_filter( 'job_manager_job_listing_data_fields', 'admin_company_location_field' );
	}


    /**
	 * Register Company Size Field
	 */
	public function register_company_size_field() {
        // add field in "front-end"
        function frontend_company_size_field( $fields ) {
            $fields['company']['company_size'] = array(
                'label'       => esc_html__( 'Company Size', 'wp-job-manager-company-profiles' ),
                'type'        => 'select',
                'required'    => false,
                'options'     => apply_filters( 'wpjmcp_company_size_options', array('') ),
                'priority'    => 4
            );
            return $fields;
        }
        add_filter( 'submit_job_form_fields', 'frontend_company_size_field' );

        // add field in "back-end"
        function admin_company_size_field( $fields ) {
            $fields['_company_size'] = array(
            'label'       => esc_html__( 'Company Size', 'wp-job-manager-company-profiles' ),
            'type'        => 'select',
            'options'     => apply_filters( 'wpjmcp_company_size_options', array('') ),
            'description' => ''
            );
            return $fields;
        }
        add_filter( 'job_manager_job_listing_data_fields', 'admin_company_size_field' );
	}

    /**
	 * Register Company Industry Field
	 */
	public function register_company_industry_field() {

        // add field in "front-end"
        function frontend_company_industry_field( $fields ) {
            $fields['company']['company_industry'] = array(
                'label'       => esc_html__( 'Industry', 'wp-job-manager-company-profiles' ),
                'type'        => 'term-select',
                'taxonomy'    => 'job_listing_industry',
                'required'    => false,
                'description' => esc_html__( 'It\'ll populate rest of the form with company data.', 'wp-job-manager-company-profiles' ),
                'priority'    => '4',
                'default'     => -1
            );
    
            return $fields;
        }
        add_filter( 'submit_job_form_fields', 'frontend_company_industry_field' );

    }
    
    #-------------------------------------------------------------------------------#
    #  Modify "Term Select" Field
    #-------------------------------------------------------------------------------#

	public function modify_term_select_field($args) {
        if ($args['taxonomy'] != 'job_listing_company') {
            return $args;
        } else {
            if (!current_user_can('administrator')) { // don't modify if admin
                // if not admin, check if (logged in + limitation enabled)
                if ( get_option('job_manager_enable_user_specific_company') ) {
                    $nullify_term_id = '9028403284021830'; // arbitrary number to force return "no terms" (since 'include' param treats '0' as return all)
                    if ( is_user_logged_in() ) {
                        $current_user_id = get_current_user_id();
                        $current_user_terms = capstone_get_user_companies($current_user_id);
                        $args['include'] = $current_user_terms ? $current_user_terms : $nullify_term_id;
                    } else { // if not logged-in
                        $args['include'] = $nullify_term_id;
                    }
                } else {
                    return $args;
                }
            }
            return $args;
        }

    }

    #-------------------------------------------------------------------------------#
    #  Create "Company Term" on Submission
    #-------------------------------------------------------------------------------#

	public function create_company_term($id, $values) {
        // $is_editing_job = isset($_GET['action']) ? $_GET['action'] == 'edit' : false;
        $is_editing_job = isset($_GET['action']) ? $_GET['action'] == 'edit' : false;

        $current_user_id = get_current_user_id();
        $company_uniqueness = false;
        $company_name = $values['company']['company_name'];
        $company_term = $values['company']['company_term'];
        $term_exist = term_exists( $company_name, 'job_listing_company' );
        $term = wp_insert_term( $company_name, 'job_listing_company' ); // create company term

        if (is_wp_error($term)) { // if term already exist
            if (!$is_editing_job) { // isn't editing existing job
                if ($company_term == '') {

                    // START: User-Specific Company Uniqueness Test
                    $user_company_args = array(
                        'taxonomy'  => 'job_listing_company',
                        'name' => $company_name,
                        'hide_empty' => false, // also retrieve terms which are not used yet
                        'meta_query' => array(
                            array(
                            'key'       => 'company_assigned_users',
                            'value'     => $current_user_id,
                            'compare'   => 'LIKE'
                            )
                        ),
                    );
                    $user_companies = get_terms( $user_company_args );
                    if (!empty($user_companies)) {
                        $company_uniqueness = true;
                    }
                    // END: User-Specific Company Uniqueness Test
    
                    if ($company_uniqueness) { // if company is supposed to be unique, throw an error
                        if (!$_POST['job_id']) { // if editing job before submission but after preview
                            throw new Exception( __( 'A company with the name provided already exists in our system for your account. Please choose that company under "Existing Company" tab.', 'wp-job-manager-company-profiles' ) );
                        } else {
                            wp_set_post_terms( $id, $company_name, 'job_listing_company' );  // re-assign term to the job listing
                        }
                    } else { // otherwise, create a new company with same name but different slug
                        $company_name_unique = $company_name .' '. rand(10 ,99); // make company name unique
                        $term = wp_insert_term( $company_name_unique, 'job_listing_company' ); // create company term
                        
                        update_term_meta( $term['term_id'], 'company_assigned_users', array($current_user_id) ); // assign user to company term
                        wp_set_post_terms( $id, $company_name_unique, 'job_listing_company' );  // assign term to the job listing
                        
                        $company_name_generic = substr($company_name_unique, 0, -2); // make company name generic
                        wp_update_term( $term['term_id'], 'job_listing_company', array('name' => $company_name_generic) ); // update company name from unique to generic
                    }
                }
            } else { // when editing job submission
                // update_term_meta( $term['term_id'], 'company_assigned_users', array($current_user_id) ); // assign user to company term
                wp_set_post_terms( $id, $company_name, 'job_listing_company' );  // re-assign term to the job listing
            }
        } else {
            update_term_meta( $term['term_id'], 'company_assigned_users', array($current_user_id) ); // assign user to company term
            wp_set_post_terms( $id, $company_name, 'job_listing_company' );  // assign term to the job listing
        }
    }

    #-------------------------------------------------------------------------------#
    #  Make sure that "New Company" form is blank
    #-------------------------------------------------------------------------------#

	public function blank_new_company($fields) {
        if (empty( $_POST['submit_job'] )) {
            foreach ( $fields['company'] as $key => $field ) {
                $fields['company'][ $key ]['value'] = '';
            }
        }
        return $fields;
    }


}
