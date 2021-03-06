<?php
	/**
	 * Bootstrap on Wordpress functions and definitions
	 *
	 * For more information on hooks, actions, and filters, see http://codex.wordpress.org/Plugin_API.
	 *
	 * @package 	WordPress
	 * @subpackage 	Bootstrap 5.0.1
	 * @autor 		Babobski
	 */

	define('BOOTSTRAP_VERSION', '5.0.1');

	/* ========================================================================================================================

	Add language support to theme

	======================================================================================================================== */
	add_action('after_setup_theme', 'my_theme_setup');
	function my_theme_setup(){
		load_theme_textdomain('wp_babobski', get_template_directory() . '/language');
	}



	/* ========================================================================================================================

	Required external files

	======================================================================================================================== */

	require_once( 'external/bootstrap-utilities.php' );
	require_once( 'external/bs5navwalker.php' );

	/* ========================================================================================================================

	Add html 5 support to wordpress elements

	======================================================================================================================== */

	add_theme_support( 'html5', array(
		'comment-list',
		'search-form',
		'comment-form',
		'gallery',
		'caption',
	) );

	/* ========================================================================================================================

	Theme specific settings

	======================================================================================================================== */

	add_theme_support('post-thumbnails');

	//add_image_size( 'name', width, height, crop true|false );

	register_nav_menus(array('primary' => 'Primary Navigation'));

	/* ========================================================================================================================

	Actions and Filters

	======================================================================================================================== */

	add_action( 'wp_enqueue_scripts', 'bootstrap_script_init' );

	add_filter( 'body_class', array( 'BsWp', 'add_slug_to_body_class' ) );

	/* ========================================================================================================================

	Custom Post Types - include custom post types and taxonomies here e.g.

	e.g. require_once( 'custom-post-types/your-custom-post-type.php' );

	======================================================================================================================== */



	/* ========================================================================================================================

	Scripts

	======================================================================================================================== */

	/**
	 * Add scripts via wp_head()
	 *
	 * @return void
	 * @author Keir Whitaker
	 */
	if ( !function_exists( 'bootstrap_script_init' ) ) {
		function bootstrap_script_init() {

			// Get theme version number (located in style.css)
			$theme = wp_get_theme();

			wp_register_script('bootstrap', get_template_directory_uri() . '/js/bootstrap.bundle.min.js', array( 'jquery' ), BOOTSTRAP_VERSION, true);
			wp_enqueue_script('bootstrap');

			wp_register_script( 'site', get_template_directory_uri() . '/js/site.js', array( 'jquery', 'bootstrap' ), $theme->get( 'Version' ), true );
			wp_enqueue_script( 'site' );

			wp_register_style( 'bootstrap', get_template_directory_uri() . '/css/bootstrap.min.css', array(), BOOTSTRAP_VERSION, 'all' );
			wp_enqueue_style( 'bootstrap' );

			wp_register_style( 'screen', get_template_directory_uri() . '/style.css', array(), $theme->get( 'Version' ), 'screen' );
			wp_enqueue_style( 'screen' );
		}
	}

	/* ========================================================================================================================

	Security & cleanup wp admin

	======================================================================================================================== */

	//remove wp version
	function theme_remove_version() {
		return '';
	}

	add_filter('the_generator', 'theme_remove_version');

	//remove default footer text
	function remove_footer_admin () {
		echo "";
	}

	add_filter('admin_footer_text', 'remove_footer_admin');

	//remove wordpress logo from adminbar
	function wp_logo_admin_bar_remove() {
		global $wp_admin_bar;

		/* Remove their stuff */
		$wp_admin_bar->remove_menu('wp-logo');
	}

	add_action('wp_before_admin_bar_render', 'wp_logo_admin_bar_remove', 0);

	// Remove default Dashboard widgets
	if ( !function_exists( 'disable_default_dashboard_widgets' ) ) {
		function disable_default_dashboard_widgets() {

			//remove_meta_box('dashboard_right_now', 'dashboard', 'core');
			remove_meta_box('dashboard_activity', 'dashboard', 'core');
			remove_meta_box('dashboard_recent_comments', 'dashboard', 'core');
			remove_meta_box('dashboard_incoming_links', 'dashboard', 'core');
			remove_meta_box('dashboard_plugins', 'dashboard', 'core');
	
			remove_meta_box('dashboard_quick_press', 'dashboard', 'core');
			remove_meta_box('dashboard_recent_drafts', 'dashboard', 'core');
			remove_meta_box('dashboard_primary', 'dashboard', 'core');
			remove_meta_box('dashboard_secondary', 'dashboard', 'core');
		}
	}
	add_action('admin_menu', 'disable_default_dashboard_widgets');

	remove_action('welcome_panel', 'wp_welcome_panel');

	// Disable the emoji's
	if ( !function_exists( 'disable_emojis' ) ) {
		function disable_emojis() {
			remove_action('wp_head', 'print_emoji_detection_script', 7);
			remove_action('admin_print_scripts', 'print_emoji_detection_script');
			remove_action('wp_print_styles', 'print_emoji_styles');
			remove_action('admin_print_styles', 'print_emoji_styles');
			remove_filter('the_content_feed', 'wp_staticize_emoji');
			remove_filter('comment_text_rss', 'wp_staticize_emoji');
			remove_filter('wp_mail', 'wp_staticize_emoji_for_email');

			// Remove from TinyMCE
			add_filter('tiny_mce_plugins', 'disable_emojis_tinymce');
		}
	}
	add_action('init', 'disable_emojis');

	// Filter out the tinymce emoji plugin.
	function disable_emojis_tinymce($plugins) {
		if (is_array($plugins)) {
			return array_diff($plugins, array('wpemoji'));
		} else {
			return array();
		}
	}

	add_action('admin_head', 'custom_logo_guttenberg');

	if ( !function_exists( 'custom_logo_guttenberg' ) ) {
		function custom_logo_guttenberg() {
			echo '<link rel="stylesheet" type="text/css" href="'.get_bloginfo('stylesheet_directory').
			'/css/admin-custom.css" />';
		}
	}

	/* ========================================================================================================================

	Custom login

	======================================================================================================================== */

	// Add custom css
	if ( !function_exists( 'my_custom_login' ) ) {
		function my_custom_login() {
			echo '<link rel="stylesheet" type="text/css" href="' . get_bloginfo('stylesheet_directory') . '/css/custom-login-style.css" />';
		}
	}
	add_action('login_head', 'my_custom_login');

	// Link the logo to the home of our website
	if ( !function_exists( 'my_login_logo_url' ) ) {
		function my_login_logo_url() {
			return get_bloginfo( 'url' );
		}
	}
	add_filter( 'login_headerurl', 'my_login_logo_url' );

	// Change the title text
	if ( !function_exists( 'my_login_logo_url_title' ) ) {
	function my_login_logo_url_title() {
		return get_bloginfo( 'name' );
	}
	}
	add_filter( 'login_headertext', 'my_login_logo_url_title' );
	

	/* ========================================================================================================================

	Comments

	======================================================================================================================== */

	/**
	 * Custom callback for outputting comments
	 *
	 * @return void
	 * @author Keir Whitaker
	 */
	if (!function_exists( 'bootstrap_comment' )) {
		function bootstrap_comment( $comment, $args, $depth ) {
			$GLOBALS['comment'] = $comment;
			?>
			<?php if ( $comment->comment_approved == '1' ): ?>
			<li class="row">
				<div class="col-4 col-md-2">
					<?php echo get_avatar( $comment ); ?>
				</div>
				<div class="col-8 col-md-10">
					<h4><?php comment_author_link() ?></h4>
					<time><a href="#comment-<?php comment_ID() ?>" pubdate><?php comment_date() ?> at <?php comment_time() ?></a></time>
					<?php comment_text() ?>
				</div>
			<?php endif;
		}
	}

add_role('company', 'Company', [
	'manage_options' => false,
]);

add_role('employee', 'Employee', [
	'manage_options' => false,
]);

/**
 * Add a value to an ACF field
 * 
 * @param string $fieldName the name of the field you want to add the value to
 * @param string $fieldId the id of the field
 * @param mixed $valueToBeAdded the value you want to add to the field
 */
function addToCustomField($fieldName, $fieldId, $valueToBeAdded) {
	if (get_field($fieldName, $fieldId) !== null) {
        if (!in_array($valueToBeAdded, get_field($fieldName, $fieldId))) {
            $listOfInvitations = get_field($fieldName, $fieldId);
            $listOfInvitations[] = $valueToBeAdded;
            update_field($fieldName, $listOfInvitations, $fieldId);
        }
    } else {
        $listOfInvitations = [$valueToBeAdded,];
        update_field($fieldName, $listOfInvitations, $fieldId);
    }
}
/**
 * Remove a value from an ACF field
 * 
 * @param string $fieldName the name of the field you want to remove the value from
 * @param string $fieldId the id of the field
 * @param mixed $valueToBeRemoved the value you want to remove from the field
 */
function removeFromCustomField($fieldName, $fieldId, $valueToBeRemoved) {
    if (count(get_field($fieldName, $fieldId)) > 1) {
        $listOfInvitationsFromCompanies = get_field($fieldName, $fieldId);
        $listOfInvitationsFromCompanies = \array_diff($listOfInvitationsFromCompanies, [$valueToBeRemoved]);
        update_field($fieldName, $listOfInvitationsFromCompanies, $fieldId);
    }
    else {
        update_field($fieldName, null, $fieldId);
    }
}

add_action( 'wp_enqueue_scripts', 'load_custom_product_style' );
function load_custom_product_style() {
	wp_register_style( 'product_css', get_stylesheet_directory_uri() . '/custom-styles/style-product-template.css', false, '1.0.0', 'all' );
	wp_enqueue_style('product_css');
}

add_action('woocommerce_before_single_product', 'delete_product_button');
function delete_product_button()
{
	if (!is_user_logged_in()) return;
	
	$user = wp_get_current_user();
	$userId = get_current_user_id();
	global $product;
	$productId = $product->get_id();
	$companyId = get_post_field( 'post_author', $productId );
	// echo "<h4>Id produs: $productId</h4>";
	// echo "<h4>Id companie: $companyId</h4>";
	
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
	
	if ($canDeleteProduct) : ?>

	<div class="delete-product-button">
		<a href="http://localhost/remove-product?r=<?php echo $productId; ?>"><button type="button">Remove Product</button></a>
	</div>

	<?php endif;
}

add_action('woocommerce_single_product_summary', 'sold_by_company');

function sold_by_company() {
	global $product;
	$productId = $product->get_id();
	$companyId = get_post_field( 'post_author', $productId );
	$company = get_user_by('id', $companyId);
	echo "<p>Sold by <a href=\"http://localhost/companies/?company=$company->id\">" . esc_html( $company->user_login ) . "</a></p>";
}