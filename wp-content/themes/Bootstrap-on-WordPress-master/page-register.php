<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * Please see /external/bootsrap-utilities.php for info on BsWp::get_template_parts()
 *
 * @package 	WordPress
 * @subpackage 	Bootstrap 5.0.1S
 * @autor 		Babobski
 */
?>

<?php
function style_define() {
  return 'style-register';
}
?>

<?php BsWp::get_template_parts( array( 
	'parts/shared/html-header', 
) ); ?>

<!-- <div class="content">
	<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>

		<h2>
			<?php the_title(); ?>
		</h2>
		<?php the_content(); ?>
		<?php comments_template( '', true ); ?>

	<?php endwhile; ?>
</div> -->

<div id="register-form-rectangle">
  <div id="register-form-logo"></div>
  <div id="black-rectangle-register-form"></div>
  <div id="registration-form-tos-consent">
    <p>By submitting this form you agree to our <a href="http://localhost/privacy-policy/">Privacy Policy</a> and our <a href="http://localhost/terms-of-service/">Terms of Service</a></p>
  </div>
  <ul class="nav nav-tabs" id="register-tab" role="tablist">
    <li class="nav-item" role="presentation">
      <button class="nav-link active" id="customer-tab" data-bs-toggle="tab" data-bs-target="#customer" type="button" role="tab" aria-controls="customer" aria-selected="true">Customer</button>
    </li>
    <li class="nav-item" role="presentation">
      <button class="nav-link register-form-tab" id="employee-tab" data-bs-toggle="tab" data-bs-target="#employee" type="button" role="tab" aria-controls="employee" aria-selected="false">Employee</button>
    </li>
    <li class="nav-item" role="presentation">
      <button class="nav-link register-form-tab" id="company-tab" data-bs-toggle="tab" data-bs-target="#company" type="button" role="tab" aria-controls="company" aria-selected="false">Seller</button>
    </li>
  </ul>
  <div class="tab-content" id="register-tab-content">
    <div class="tab-pane fade show active" id="customer" role="tabpanel" aria-labelledby="customer-tab"><?php 
    echo do_shortcode('[user_registration_form id="17"]');
    ?></div>
    <div class="tab-pane fade" id="employee" role="tabpanel" aria-labelledby="employee-tab"><?php 
    echo do_shortcode('[user_registration_form id="44"]');
    ?></div>
    <div class="tab-pane fade" id="company" role="tabpanel" aria-labelledby="company-tab"><?php 
    echo do_shortcode('[user_registration_form id="43"]');
    ?></div>
  </div>
</div>


<?php BsWp::get_template_parts( array( 
	'parts/shared/footer',
	'parts/shared/html-footer' 
) ); ?>
