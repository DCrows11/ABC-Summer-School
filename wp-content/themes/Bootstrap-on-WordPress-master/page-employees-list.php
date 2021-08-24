<?php BsWp::get_template_parts( array( 
	'parts/shared/html-header', 
	'parts/shared/header' 
) ); ?>

<?php

echo "test";

$args = array(
    'role'    => 'employee',
    'orderby' => 'user_nicename',
    'order'   => 'ASC'
);
$users = get_users( $args );

echo '<ul>';
foreach ( $users as $user ) {
    echo '<li>' . esc_html( $user->display_name ) . '</li>';
}
echo '</ul>';

?>


<?php BsWp::get_template_parts( array( 
	'parts/shared/footer',
	'parts/shared/html-footer' 
) ); ?>
