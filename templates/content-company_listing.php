<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// Helper Variable(s)
global $wpjmcp;

$search_keywords = isset($_GET['search_keywords']) ? $_GET['search_keywords'] : null;
$search_location = isset($_GET['search_location']) ? $_GET['search_location'] : null;
$search_industry = isset($_GET['search_industry']) ? $_GET['search_industry'] : null;

$companies_query = $wpjmcp->get_companies_query(-1, $search_keywords, $search_location, $search_industry);
$excerpt_limit = get_theme_mod('capstone_companies_jobs_excerpt_limit', 2);

?>
<ul class="company_listings">
	<?php if ($companies_query) { ?>
		<?php foreach ( $companies_query as $company_name ) { ?>
			<?php
				$count = $wpjmcp->get_company_count($company_name);
				$company_info = $wpjmcp->get_company_info($company_name);
			?>
			<li class="company_listing">
					<a href="<?php echo esc_url($wpjmcp->company_url($company_info['company_slug'], $company_name)); ?>">
						<img class="company_logo" src="<?php echo esc_url($company_info['company_logo']); ?>" alt="">
						<div class="company">
							<h3><?php echo esc_html($company_name); ?></h3>
							<p class="company-desc">
								<?php
									if (strlen($company_info['company_info']) <=100) {
										echo $company_info['company_info'];
									} else {
										echo substr($company_info['company_info'], 0, 100) . '...';
									}
								?>  
							</p>
						</div>
						<ul class="company-meta">
							<li class="open-positions"><?php echo esc_attr($count); ?> <?php echo _n( 'Position', 'Positions', $count, 'wp-job-manager-company-profiles' ); ?></li>
							<li class="location"><?php echo esc_html($company_info['company_location']); ?></li>
						</ul>
					</a>
					<?php if (get_theme_mod('capstone_companies_jobs_excerpt', 'enable') == 'enable') { ?>
						<div class="recent-jobs">
							<div class="inner">
								<h3 class="section-title"><?php echo esc_html__('Open Positions', 'wp-job-manager-company-profiles'); ?></h3>
								<?php if ($count > get_theme_mod('capstone_companies_jobs_excerpt_limit', 2)) { ?>
									<a class="see-all" href="<?php echo esc_url($wpjmcp->company_url($company_info['company_slug'], $company_name)); ?>" class="see-more"><?php echo esc_html__('see all', 'wp-job-manager-company-profiles'); ?> &#10230;</a>
								<?php } ?>
								<ul>
									<?php foreach ( $wpjmcp->get_company_posts($company_name, $excerpt_limit) as $company ) { ?>
										<li>
											<div class="entry-header">
												<a class="title" href="<?php echo esc_url(get_permalink($company->ID)); ?>"><h5><?php echo esc_html($company->post_title); ?></h5></a>
												<?php if ( taxonomy_exists('job_listing_type') ) { ?>
													<?php echo get_the_term_list( $company->ID, 'job_listing_type', '<span class="types">(', null, ')</span>' ); ?>
												<?php } ?>
											</div>
											<div class="entry-footer">
												<span class="location">
													<?php if ( get_field('_job_location', $company->ID) ) { ?>
														<?php echo get_field('_job_location', $company->ID); ?>
													<?php } else { ?>
														<?php echo esc_html__('Anywhere', 'wp-job-manager-company-profiles'); ?>
													<?php } ?>
												</span>
												<?php if ( taxonomy_exists('job_listing_category') ) { ?>
													<?php echo get_the_term_list( $company->ID, 'job_listing_category', '<span class="categories">', null, '</span>' ); ?>
												<?php } ?>
											</div>
										</li>
									<?php } ?>
								</ul>
							</div>
						</div>
					<?php } ?>
			</li>
		<?php } ?>
	<?php } else { ?>
		<li class="no_company_listings_found">
			<?php echo esc_html('There are no listings matching your search.', 'wp-job-manager-company-profiles'); ?>
		</li>
	<?php } ?>

</ul>
