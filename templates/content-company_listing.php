<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$wpjmcp = new WP_Job_Manager_Companies();

?>
<ul class="company_listings">
	<?php foreach ( $wpjmcp->get_companies() as $company_name ) { ?>
		<?php
			$all_company_data = $wpjmcp->all_company_data($company_name);
			$last_company_data = $wpjmcp->last_company_data($company_name);

			$company_desc = $last_company_data['company_info'];
		?>
		<li class="company_listing">
				<a href="<?php echo esc_url($wpjmcp->company_url($last_company_data['company_slug'], $company_name)); ?>">
					<img class="company_logo" src="<?php echo esc_url($last_company_data['company_logo']); ?>" alt="">
					<div class="company">
						<h3><?php echo esc_html($company_name); ?></h3>
						<p class="company-desc">
							<?php
								if (strlen($company_desc) <=100) {
									echo $company_desc;
								} else {
									echo substr($company_desc, 0, 100) . '...';
								}
							?>  
						</p>
					</div>
					<ul class="company-meta">
						<li class="open-positions"><?php echo esc_attr($all_company_data['count']); ?> <?php echo _n( 'Position', 'Positions', $all_company_data['count'], 'wp-job-manager-company-profiles' ); ?></li>
						<li class="location"><?php echo esc_html($last_company_data['company_location']); ?></li>
					</ul>
				</a>
				<?php if (get_theme_mod('capstone_companies_jobs_excerpt', 'enable') == 'enable') { ?>
					<div class="recent-jobs">
						<div class="inner">
							<h3 class="section-title"><?php echo esc_html__('Open Positions', 'wp-job-manager-company-profiles'); ?></h3>
							<?php if ($all_company_data['count'] > get_theme_mod('capstone_companies_jobs_excerpt_limit', 2)) { ?>
								<a class="see-all" href="<?php echo esc_url($wpjmcp->company_url($last_company_data['company_slug'], $company_name)); ?>" class="see-more"><?php echo esc_html__('see all', 'wp-job-manager-company-profiles'); ?> &#10230;</a>
							<?php } ?>
							<ul>
								<?php $i = 0; ?>
								<?php foreach ( $all_company_data['company_posts'] as $company ) { ?>
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
									<?php if (++$i == get_theme_mod('capstone_companies_jobs_excerpt_limit', 2)) break; ?>
								<?php } ?>
							</ul>
						</div>
					</div>
				<?php } ?>
		</li>
	<?php } ?>
</ul>
