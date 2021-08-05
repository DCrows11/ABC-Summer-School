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
 * @subpackage 	Bootstrap 5.0.1
 * @autor 		Babobski
 */
?>
<?php BsWp::get_template_parts( array( 
	'parts/shared/html-header', 
	// 'parts/shared/header' 
) ); ?>

<?php

echo "test";

$args = array(
    'role'    => 'company',
    'orderby' => 'user_nicename',
    'order'   => 'ASC'
);
$users = get_users( $args );

echo '<ul>';
foreach ( $users as $user ) {
    echo "<li><a href=\"http://localhost/companies/?company=$user->id\">" . esc_html( $user->user_login ) . '</a></li>';
}
echo '</ul>';

?>


<?php BsWp::get_template_parts( array( 
	'parts/shared/footer',
	'parts/shared/html-footer' 
) ); ?>
