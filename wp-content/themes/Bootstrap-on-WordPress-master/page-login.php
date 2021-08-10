<?php
function style_define()
{
	return 'style-login';
}
?>

<?php BsWp::get_template_parts(array(
	'parts/shared/html-header',
)); ?>

<div id="login-yellow-rectangle">
	<?php
	echo do_shortcode('[user_registration_my_account redirect_url=http://localhost/index.php]');
	?>
	<div id="login-black-rectangle"> </div>
	<div id="login-logo"> </div>
</div>
<div id="login-user-icon"></div>
<div id="login-password-icon"></div>

<?php BsWp::get_template_parts(array(
	'parts/shared/html-footer'
)); ?>