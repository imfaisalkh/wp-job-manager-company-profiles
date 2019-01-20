<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class WP_Job_Manager_Company_Fields {

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
		$this->file         = __FILE__;

		$this->basename     = plugin_basename( $this->file );
		$this->plugin_dir   = plugin_dir_path( $this->file );
		$this->plugin_url   = plugin_dir_url ( $this->file );
	}

	/**
	 * Setup the default hooks and actions
	 *
	 * @since WP Job Manager - Company Profiles 1.0
	 *
	 * @return void
	 */
	private function setup_actions() {
        // register fields
		add_action( 'init', array( $this, 'register_company_slug_field' ) );
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
    }

	/**
	 * Company Fields Options
	 */
	public function company_fields_options() {

        // Company Categories - Options
        function company_categories( $options ) {
            $args = array('', 'Information Technology', 'Banking & Finance', 'Marketing & Advertisement', 'Healthcare', 'Real Estate & Property', 'Textile & Garments', 'Education & Training', 'Accounts & Taxation', 'N.G.O & Social Services', 'Consultancy');
            $options = array_combine($args, $args);
            return $options;
        }
        add_filter( 'wpjmcp_company_industry_options', 'company_categories' );
    
        // Company Size - Options
        function company_size( $options ) {
            $args = array('', '1 - 49', '50 - 149', '150 - 249', '250 - 499', '500 - 749', '750-999', '1000 +');
            $options = array_combine($args, $args);
            return $options;
        }
        add_filter( 'wpjmcp_company_size_options', 'company_size' );
    
    }


	/**
	 * Company Fields Priority
	 */
	public function company_fields_priority($fields) {
        $company_fields = array(
            'company_name',
            'company_slug',
            'company_tagline',
            'company_foundation',
            'company_location',
            'company_industry',
            'company_website',
            'company_size',
            'company_email',
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

	/**
	 * Register Company Slug Field
	 */
	public function register_company_slug_field() {
		// add field in "front-end"
		function frontend_company_slug_field( $fields ) {
			$fields['company']['company_slug'] = array(
				'label'       => esc_html__( 'Company Slug', 'wp-job-manager-company-profiles' ),
				'type'        => 'text',
				'required'    => true,
				'priority'    => 2,
				'placeholder' => '',
			);
			return $fields;
		}
		add_filter( 'submit_job_form_fields', 'frontend_company_slug_field' );

		// add field in "back-end"
		function admin_company_slug_field( $fields ) {
			$fields['_company_slug'] = array(
			'label'       => esc_html__( 'Company Slug', 'wp-job-manager-company-profiles' ),
			'type'        => 'text',
			'required'    => true,
			'description' => esc_html__('If defined It\'ll be used in company permalink.', 'wp-job-manager-company-profiles'),
			);
			return $fields;
		}
		add_filter( 'job_manager_job_listing_data_fields', 'admin_company_slug_field' );
    }

    /**
	 * Register Company Foundation Field
	 */
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
                'priority'    => 4
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
                'placeholder' => 'e.g. Paris',
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
            'placeholder' => 'e.g. Paris',
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
	 * Register Company Categories Field
	 */
	public function register_company_industry_field() {
        // add field in "front-end"
        function frontend_company_industry_field( $fields ) {
            $fields['company']['company_industry'] = array(
                'label'       => esc_html__( 'Industry', 'wp-job-manager-company-profiles' ),
                'type'        => 'select',
                'required'    => false,
                'options'     => apply_filters( 'wpjmcp_company_industry_options', array('') ),
                'priority'    => 4
            );
            return $fields;
        }
        add_filter( 'submit_job_form_fields', 'frontend_company_industry_field' );

        // add field in "back-end"
        function admin_company_industry_field( $fields ) {
            $fields['_company_industry'] = array(
                'label'       => esc_html__( 'Company Industry', 'wp-job-manager-company-profiles' ),
                'type'        => 'select',
                'options'     => apply_filters( 'wpjmcp_company_industry_options', array('') ),
                'description' => ''
            );
            return $fields;
        }
        add_filter( 'job_manager_job_listing_data_fields', 'admin_company_industry_field' );
	}

}
