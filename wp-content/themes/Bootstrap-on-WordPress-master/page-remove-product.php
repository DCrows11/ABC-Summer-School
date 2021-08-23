<?php

    if (!is_user_logged_in()) {
        wp_redirect('http://localhost/login');
        exit;
    }

    if (isset($_GET['r'])) {
        $productId = $_GET['r'];

        $pf = new WC_Product_Factory();
        $product = $pf->get_product($productId);
    }
    else {
        wp_redirect('http://localhost/');
        exit;
    }
?>

<?php BsWp::get_template_parts( array( 
	'parts/shared/html-header', 
	// 'parts/shared/header' 
) ); ?>

<?php

    $user = wp_get_current_user();
	$userId = get_current_user_id();
	$companyId = get_post_field( 'post_author', $productId );

	$canDeleteProduct = false;

	if (in_array('employee', (array) $user->roles)) {
		$companies = (array)get_field( 'employed_to_companies_list', 'user_' . $userId );
		if (in_array ( $companyId, $companies )) {
			$canDeleteProduct = true;
		}
	}

	if ($userId == $companyId) {
		$canDeleteProduct = true;
	}

	if (in_array('administrator', (array) $user->roles)) {
		$canDeleteProduct = true;
	}

    if(isset($_POST['confirm-remove'])) {
        if ($canDeleteProduct) {
            if ($product->delete()) {
                echo "<h4>The product has been removed.</h4>";
            }
            else {
                echo "<h4>Failed to remove product.</h4>";
            }
        }
    } else {
        if (!$canDeleteProduct) {
            echo "<h4>You can't delete this product!</h4>";
        }
        else {
            $productsAsPost = get_post($productId);
            echo "<h4>Are you sure you want to delete <a href=\"http://localhost/product/$productsAsPost->post_name\">$productsAsPost->post_title</a>? To cancel, close this page.</h4>";
        }
        if ($canDeleteProduct) : ?>

            <form method="post">
                <input type="submit" name="confirm-remove" value="Remove Product">
            </form>
    
        <?php endif;
    }
    
?>

<?php BsWp::get_template_parts( array( 
	'parts/shared/footer',
	'parts/shared/html-footer' 
) ); ?>