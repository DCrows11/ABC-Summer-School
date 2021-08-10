<?php

//Redirect if user is not logged in or not a company/employee
if (!is_user_logged_in()) {
    wp_redirect('http://localhost/login');
    exit;
} else {
    $user = wp_get_current_user();
    if (!in_array('company', (array) $user->roles) && !in_array('employee', (array) $user->roles)) {
        wp_redirect('http://localhost/');
        exit;
    }
}

?>

<?php BsWp::get_template_parts(array(
    'parts/shared/html-header',
    // 'parts/shared/header' 
)); ?>

<form method="post" enctype="multipart/form-data">
    <div id="add-product-form-product-name">
        <label for="product-name">Product Name</label><br>
        <input type="text" id="product-name" name="product-name" required><br>
    </div>
    <br>
    <div id="add-product-form-product-short-description">
        <label for="product-short-description">Short Description</label><br>
        <input type="text" id="product-short-description" name="product-short-description" required><br>
    </div>
    <br>
    <div id="add-product-form-product-long-description">
        <label for="product-long-description">Long Description</label><br>
        <input type="text" id="product-long-description" name="product-long-description" required><br>
    </div>
    <br>
    <div id="add-product-form-product-regular-price">
        <label for="product-regular-price">Regular Price ($)</label><br>
        <input type="number" step=0.01 id="product-regular-price" name="product-regular-price" required><br>
    </div>
    <br>
    <div id="add-product-form-product-sale-price">
        <label for="product-sale-price">Sale Price ($)</label><br>
        <input type="number" step=0.01 id="product-sale-price" name="product-sale-price" required><br>
    </div>
    <br>
    <div id="add-product-form-product-image">
        <label for="product-image">Product image</label><br>
        <input type="file" id="product-image" name="product-image"><br>
    </div>
    <br>
    <div id="add-product-form-product-category">
        <p>Product Category</p>
        <input type="radio" id="product-category-weapons" name="product-category" value="weapons" required>
        <label for="product-category">Weapons</label><br>
        <input type="radio" id="product-category-ammo" name="product-category" value="ammo" required>
        <label for="product-category">Ammo</label><br>
        <input type="radio" id="product-category-accessories" name="product-category" value="accessories" required>
        <label for="product-category">Accessories</label><br>
    </div>
    <br>
    <div id="add-product-form-submit-button">
        <input type="submit" value="Add product">
    </div>
</form>

<?php

if (isset($_POST['product-name'])) {
    $productName = $_GET['product-name'];
    $productShortDescription = $_GET['product-short-description'];
    $productLongDescription = $_GET['product-long-description'];
    $productRegularPrice = $_GET['product-regular-price'];
    $productSalePrice = $_GET['product-sale-price'];
    $productCategory = $_GET['product-category'];
    // echo "<pre>";
    // var_dump($_FILES['product-image']);
    // echo "</pre>";
}
?>

<?php BsWp::get_template_parts(array(
    'parts/shared/footer',
    'parts/shared/html-footer'
)); ?>