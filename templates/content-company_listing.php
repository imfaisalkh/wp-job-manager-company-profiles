<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// Search Query Variable(s)
$search_query = [];
$search_keywords = isset($_GET['search_keywords']) ? $_GET['search_keywords'] : null;
$search_location = isset($_GET['search_location']) ? $_GET['search_location'] : null;
$search_industry = isset($_GET['search_industry']) ? $_GET['search_industry'] : null;

array_push($search_query, $search_keywords, $search_location, $search_industry);

// Query all `job_listing_company` terms
$term_args = array(
	'taxonomy'  => 'job_listing_company',
	'hide_empty' => false, // also retrieve terms which are not used yet
	'fields' => 'tt_ids',
);

$company_terms = get_terms($term_args);
$has_company_result = false;
?>
<ul class="company_listings">
	<?php if ( $company_terms ) { ?>
		<?php foreach ( $company_terms as $term_id ) { ?>
			<?php
				// get company data
				$term = get_term( $term_id, 'job_listing_company' ); // get the current term object from it's ID
				$company_query = capstone_get_company_data($term, $search_query, $return='query');
			?>
			<?php if ( $company_query->have_posts() ) { ?>
				<?php while ( $company_query->have_posts() ) : $company_query->the_post(); ?>
					<?php
						// Helper Variable(s)
						$company_permalink = get_term_link($term_id, 'job_listing_company');
						$company_desc = get_field('_company_description') ? get_field('_company_description') : get_field('_company_tagline');
						$company_desc_formatted = strlen($company_desc) <=100 ? $company_desc : substr($company_desc, 0, 100) . '...';
						$company_location = get_field('_company_location');
						$open_positions = $company_query->found_posts;
						$is_company_positions = get_theme_mod('capstone_companies_jobs_excerpt', 'enable');
						$company_positions_limit = get_theme_mod('capstone_companies_jobs_excerpt_limit', 2);
						$has_company_result = true;
					
						// Helper Variable(s) - Pass Variables
						set_query_var( 'term', $term );
						set_query_var( 'company_permalink', $company_permalink ); // pass variable to "get_template_part"
						set_query_var( 'open_positions', $open_positions ); // pass variable to "get_template_part"
						set_query_var( 'company_positions_limit', $company_positions_limit ); // pass variable to "get_template_part"
					?>
					
					<li class="company_listing">
						<a href="<?php echo esc_url($company_permalink); ?>">
							<div class="logo">
								<?php the_company_logo('capstone-listing-thumbnail'); ?>
							</div>
							<div class="company">
								<?php the_company_name( '<h3 class="title">', '</h3>' ); ?>
								<p class="company-desc"><?php echo $company_desc_formatted; ?></p>
							</div>
							<ul class="company-meta">
								<li class="open-positions"><?php echo esc_attr($open_positions); ?> <?php echo _n( 'Position', 'Positions', $open_positions, 'wp-job-manager-company-profiles' ); ?></li>
								<li class="location"><?php echo esc_html($company_location); ?></li>
							</ul>
						</a>
						<?php if ($is_company_positions == 'enable') { ?>
							<?php get_template_part( 'includes/company-positions.inc' ); ?>
						<?php } ?>
					</li>
				<?php endwhile; ?>
			<?php } ?>
		<?php } ?>
	<?php } ?>
	<?php if (!$has_company_result) { ?>
		<li class="no_company_listings_found">
			<?php echo esc_html__('There are no companies matching your criteria.', 'wp-job-manager-company-profiles'); ?>
		</li>
	<?php } ?>
</ul>
