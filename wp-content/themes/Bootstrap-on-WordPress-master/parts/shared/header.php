<!-- <nav class="navbar navbar-expand-lg navbar-light bg-light">
	<div class="container-fluid">
		<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#primaryNav" aria-controls="primaryNav" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>

		<div class="collapse navbar-collapse" id="primaryNav">
			<?php
			wp_nav_menu( array(
				'menu'          	=> 'primary',
				'theme_location'	=> 'primary',
				'depth'         	=> 2,
				'container'			=> false,
				'menu_class'    	=> 'navbar-nav me-auto',
				'fallback_cb'   	=> '__return_false',
				'walker'         	=> new bootstrap_5_wp_nav_menu_walker())

			);
			?>
			<?php get_search_form(); ?>
		</div>
	</div>
</nav> -->
<?php
$user = wp_get_current_user();
if (in_array('customer', (array) $user->roles)) {
	//Customer header
	// echo do_shortcode("[hfe_template id='202']");
} else if (in_array('employee', (array) $user->roles)) {
	//Employee header
} else if (in_array('company', (array) $user->roles)) {
	//Company header
} else if (in_array('administrator', (array) $user->roles)) {
	//Admin header
}
?>

<!-- Handmade Header -->
<div class="header">
	<!-- First button (homepage) -->
	<div class="header-homepage-icon">
		<a href="http://localhost">
			<img src="http://localhost/wp-content/themes/Bootstrap-on-WordPress-master/images/favicon.svg" class="header-logo">
		</a>
	</div>
	<div class="header-buttons">
		<div class="header-item">
			<a href="http://localhost">
				Home
			</a>
		</div>

		<div class="header-item">
			<a href="http://localhost/companies-list">
				Companies list
			</a>
		</div>

		<?php if (in_array('employee', (array) $user->roles) || in_array('company', (array) $user->roles)) : ?>
			<div class="header-item">
				<a href="http://localhost/add-product">
					Add product
				</a>
			</div>
		<?php endif; ?>

		<?php if (in_array('customer', (array) $user->roles)) : ?>
			<div class="header-item">
				<a href="http://localhost/cart">
					Cart
				</a>
			</div>
		<?php endif; ?>

		<?php if (in_array('employee', (array) $user->roles)) : ?>
			<div class="header-item">
				<a href="http://localhost/my-companies">
					My companies
				</a>
			</div>
		<?php endif; ?>

		<?php if (in_array('company', (array) $user->roles)) : ?>
			<div class="header-item">
				<a href="http://localhost/my-employees">
					My employees
				</a>
			</div>
		<?php endif; ?>

		<!-- My account -->
		<div class="header-item">
			<a href="http://localhost/my-account">
				My account
			</a>
		</div>

		<!-- Notifications -->
		<?php if (in_array('employee', (array) $user->roles) || in_array('company', (array) $user->roles)) : ?>
			<div class="header-item">
				<a>
					Notifications
				</a>
			</div>
		<?php endif; ?>
	</div>
</div>