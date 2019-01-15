<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$wpjmcl = new Astoundify_Job_Manager_Companies();

?>
<ul class="company_listings">
	<?php foreach ( $wpjmcl->get_companies() as $company_name ) { ?>
		<?php $company_data = $wpjmcl->company_data($company_name); ?>
		<li class="company_listing">
			<a href="<?php echo esc_url($wpjmcl->company_url($company_name)); ?>">
				<img class="company_logo" src="#" alt="">
				<div class="company">
					<h3><?php echo esc_html($company_name); ?></h3>
					<p class="company-desc"><?php echo esc_html($company_data['company_info']); ?></p>
				</div>
				<div class="company-meta">
					<li class="open-positions"><?php echo esc_attr($company_data['count']); ?> Positions</li>
					<li class="since">Size: <?php echo esc_attr($company_data['company_size']); ?></li>
				</div>
			</a>
		</li>
		<?php wp_reset_postdata(); ?>
	<?php } ?>
</ul>
