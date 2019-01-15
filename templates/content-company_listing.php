<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$wpjmcl = new Astoundify_Job_Manager_Companies();

?>
<ul class="company_listings">
	<?php foreach ( $wpjmcl->get_companies() as $company_name ) { ?>
		<?php
			$all_company_data = $wpjmcl->all_company_data($company_name);
			$last_company_data = $wpjmcl->last_company_data($company_name);
		?>
		<li class="company_listing">
			<a href="<?php echo esc_url($wpjmcl->company_url($last_company_data['company_slug'], $company_name)); ?>">
				<img class="company_logo" src="<?php echo esc_url($last_company_data['company_logo']); ?>" alt="">
				<div class="company">
					<h3><?php echo esc_html($company_name); ?></h3>
					<p class="company-desc"><?php echo esc_html($last_company_data['company_info']); ?></p>
				</div>
				<ul class="company-meta">
					<li class="open-positions"><?php echo esc_attr($all_company_data['count']); ?> Positions</li>
					<li class="since">Size: <?php echo esc_attr($last_company_data['company_size']); ?></li>
				</ul>
				<div class="recent-jobs">
					<h3><?php echo esc_html__('Open Positions', 'companies-listing'); ?></h3>
					<ul>
						<?php foreach ( $all_company_data['company_posts'] as $company ) { ?>
							<li>
								<h5 class="title"><?php echo esc_html($company->post_title); ?></h5>
							</li>
						<?php } ?>
					</ul>
				</div>
			</a>
		</li>
	<?php } ?>
</ul>
