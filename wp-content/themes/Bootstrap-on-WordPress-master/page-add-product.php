<?php

//Redirect if user is not logged in, not a company/employee, or if user doesn't belong to a company
if (!is_user_logged_in()) {
    wp_redirect('http://localhost/login');
    exit;
} else {
    $user = wp_get_current_user();
    if (!in_array('company', (array) $user->roles) && !in_array('employee', (array) $user->roles)) {
        wp_redirect('http://localhost/');
        exit;
    } else {
        if (in_array('employee', (array) $user->roles)) {
            if (!get_field('employed_to_companies_list', 'user_' . get_current_user_id())) {
                wp_redirect('http://localhost/');
                exit;
            }
        }
    }
}

?>

<?php BsWp::get_template_parts(array(
    'parts/shared/html-header',
    // 'parts/shared/header' 
)); ?>

<div class="custom-content-class">

<form method="post" enctype="multipart/form-data">
    <?php
    //If user is an employee, display field for selecting the company to which he wants to add the product
    if (in_array('employee', (array) $user->roles)) :
    ?>
        <div id="add-product-form-product-company">
            <label for="product-company">Product Company</label><br>
            <?php
            $listOfJoinedCompanies = get_field('employed_to_companies_list', 'user_' . get_current_user_id());
            wp_dropdown_users($args = [
                'name' => 'product-company',
                'include' => $listOfJoinedCompanies,
            ]);
            ?>
            <br>
        </div>
        <br>
    <?php endif; ?>
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
    <div id="add-product-form-product-selling-price">
        <label for="product-selling-price">Selling Price ($)</label><br>
        <input type="number" step=0.01 id="product-selling-price" name="product-selling-price" required><br>
    </div>
    <br>
    <div id="add-product-form-product-production-price">
        <label for="product-production-price">Production Price ($)</label><br>
        <input type="number" step=0.01 id="product-production-price" name="product-production-price" required><br>
    </div>
    <br>
    <div id="add-product-form-product-image">
        <label for="product-image">Product image (only .png and .jpg allowed, under 2MB)</label><br>
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

</div>

<?php

if (isset($_POST['product-name'])) {
    $canAddProduct = true;
    $productImage = $_FILES["product-image"];
    $productImageName = $productImage["name"];
    $productImageSize = $productImage["size"];
    $imageFileType = strtolower(pathinfo($productImageName, PATHINFO_EXTENSION));
    //Check if image is .jpg or .png
    if ($imageFileType !== 'jpg' && $imageFileType !== 'png') {
        echo "<p style=\"color:red\">Could not add product, image file type is not .png or .jpg</p>";
        $canAddProduct = false;
    }
    //Check if image is under 2MB
    if ($_FILES["product-image"]["size"] > 2097152) {
        echo "<p style=\"color:red\">Could not add product, image size is too large</p>";
        $canAddProduct = false;
    }
    if ($canAddProduct) {
        $productName = $_POST['product-name'];
        $productShortDescription = $_POST['product-short-description'];
        $productLongDescription = $_POST['product-long-description'];
        $productSellingPrice = $_POST['product-selling-price'];
        $productProductionPrice = $_POST['product-production-price'];
        $productCategory = $_POST['product-category'];
        if ($productCategory == 'weapons') {
            $productCategoryId = 16;
        } else if ($productCategory == 'ammo') {
            $productCategoryId = 17;
        } else if ($productCategory == 'accessories') {
            $productCategoryId = 18;
        }
        if (in_array('employee', (array) $user->roles)) {
            $productCompany = $_POST['product-company'];
        } else {
            $productCompany = $user->id;
        }
        //Create product object
        $product = new WC_Product_Simple();
        //Setting the product title
        $product->set_name($productName);
        //Setting the selling price
        $product->set_price($productSellingPrice);
        $product->set_regular_price($productSellingPrice);
        //Setting the product descriptions
        $product->set_short_description($productShortDescription);
        $product->set_description($productLongDescription);
        //Setting the categories
        $product->set_category_ids((array)$productCategoryId);
        //Saving the product
        $product->save();
        //Setting product author
        wp_update_post([
            'id' => $product->get_id(),
            'author' => $productCompany,
        ]);
        //Setting the input price
        update_field('product_production_price', $productProductionPrice, $product->get_id());
        //Uploading the product image
        $uploads_dir = 'wp-content/uploads/custom-product-images';
        $tmp_name = $productImage["tmp_name"];
        $name = basename($productImageName);
        $newImageName = "product-" . $product->get_id() . "-image.png";
        move_uploaded_file($tmp_name, "$uploads_dir/$newImageName");
        //Setting Attachment
        $postMimeType = "image/" . $imageFileType;
        $attachmentArgs = [
            'post_mime_type' => $postMimeType,
        ];
        $attachmentId = wp_insert_attachment($attachmentArgs, $newImageName, $product->get_id());

        require_once(ABSPATH . 'wp-admin/includes/image.php');
        wp_update_attachment_metadata($attachmentId, wp_generate_attachment_metadata($attachmentId, "$uploads_dir/$newImageName"));


        $product->set_image_id((int)$attachmentId);
        $product->save();
    }
}
?>

<?php BsWp::get_template_parts(array(
    'parts/shared/footer',
    'parts/shared/html-footer'
)); ?>