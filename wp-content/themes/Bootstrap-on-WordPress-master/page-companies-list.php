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
