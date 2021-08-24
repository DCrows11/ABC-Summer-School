<?php

if (!isset($_GET['company'])) {
    wp_redirect('http://localhost/companies-list');
}
?>


<?php BsWp::get_template_parts(array(
    'parts/shared/html-header',
    // 'parts/shared/header' 
)); ?>

<div class="custom-content-class">

<?php

$companyId = $_GET['company'];
$company = get_user_by('ID', $companyId);
echo "<h1>$company->user_login</h1>";
$args = [
    'numberposts' => -1,
    'post_type' => 'product',
    'post_status' => 'publish',
    'author' => $companyId
];
$productsList = get_posts($args);
$productsIdsList = filterProducts($productsList);
displayProducts($productsIdsList);
function filterProducts($unfilteredProducts)
{
    $filteredProducts = [];
    for ($i = 0; $i < count($unfilteredProducts); $i++) {
        $currentProduct = $unfilteredProducts[$i];
        $filteredProducts[] = $currentProduct->ID;
    }
    return $filteredProducts;
}
function displayProducts($products) {
    if (count($products) > 0) {
        $displayProducts = "";

        echo "<h4>Company products:</h4>";
        echo "<div class=\"company-products-list\">";
            foreach ($products as $product) {
                $displayProducts = $displayProducts . $product . ",";
            }
        $displayProducts = "[products ids=\"" . $displayProducts . "\"]";
        echo do_shortcode($displayProducts);
        echo "</div>";
    }
    else {
        echo "<p>This company doesn't have any products</p>";
    }
}

?>

</div>

<?php BsWp::get_template_parts(array(
    'parts/shared/footer',
    'parts/shared/html-footer'
)); ?>
