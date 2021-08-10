<?php
if (!is_user_logged_in()) {
    wp_redirect('http://localhost/login');
    exit;
}
?>

<?php BsWp::get_template_parts(array(
    'parts/shared/html-header',
    // 'parts/shared/header' 
)); ?>

<div class="homepage-shop-redirect-buttons">
    <a href="localhost/weapons"><button class="homepage-weapons-redirect-button">Weapons</button></a>
    <a href="localhost/ammo"><button class="homepage-ammo-redirect-button">Ammo</button></a>
    <a href="localhost/accessories"><button class="homepage-accessories-redirect-button">Accessories</button></a>
</div>

<?php BsWp::get_template_parts(array(
    'parts/shared/footer',
    'parts/shared/html-footer'
)); ?>