<?php

if (!isset($_GET['company'])) {
    wp_redirect('http://localhost/companies-list');
}
?>


<?php BsWp::get_template_parts(array(
    'parts/shared/html-header',
    // 'parts/shared/header' 
)); ?>

<?php

$companyId = $_GET['company'];
$company = get_user_by('ID', $companyId);
echo "<h1>$company->user_login</h1>";
$args = [
    'numberposts' => -1,
    'post_type' => 'product',
    'post_status' => 'publish',
];
$listOfAllProducts = get_posts($args);
$companyProductsId = filterProducts($listOfAllProducts, $companyId);
displayProducts($companyProductsId);
function filterProducts($unfilteredProducts, $companyId)
{
    $filteredProducts = [];
    for ($i = 0; $i < count($unfilteredProducts); $i++) {
        $currentProduct = $unfilteredProducts[$i];
        $productId = $currentProduct->ID;
        $currentSeller = get_field( 'seller', $productId );
        if ($currentSeller == $companyId) {
            $filteredProducts[] = $productId;
        }
    }
    return $filteredProducts;
}
function displayProducts($products) {
    if (count($products) > 0) {
        echo "<h4>Company products:</h4>";
        echo "<div class=\"company-products-list\">";
            foreach ($products as $product) {
                $product = get_post($product);
                echo "<div class=\"company-products-list-box\">";
                echo "<p>$product->post_title</p>";
                echo "</div>";
            }
        echo "</div>";
    }
    else {
        echo "<p>This company doesn't have any products</p>";
    }
}

?>
<?php BsWp::get_template_parts(array(
    'parts/shared/footer',
    'parts/shared/html-footer'
)); ?>
