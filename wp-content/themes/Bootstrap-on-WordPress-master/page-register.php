<?php

if (is_user_logged_in()) {
  wp_redirect('http://localhost/');
  exit;
}

function style_define() {
  return 'style-register';
}
?>

<?php BsWp::get_template_parts( array( 
	'parts/shared/html-header', 
) ); ?>

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
	'parts/shared/html-footer' 
) ); ?>
