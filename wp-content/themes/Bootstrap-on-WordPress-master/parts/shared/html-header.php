<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge">

    <title>
        <?php wp_title( '|', true, 'right' ); ?><?php bloginfo( 'name' ); ?>
    </title>
		<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
		<link rel="shortcut icon" href="<?php echo get_stylesheet_directory_uri(); ?>/images/favicon.svg"/>
		<?php wp_head(); ?>
    <?php if (function_exists('style_define')): ?>
      <link rel="stylesheet" href="http://localhost/wp-content/themes/Bootstrap-on-WordPress-master/custom-styles/<?= style_define(); ?>.css">
    <?php endif; ?>
  </head>
<body <?php body_class(); ?>>

