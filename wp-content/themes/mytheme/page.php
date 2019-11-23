<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">

	<?php wp_head(); ?>
</head>
<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package mytheme
 */

get_header();

?>
    <div class="container wrapper">
        <div class="row">
            <div class="col-sm-4">
                <?php get_sidebar(); ?>
                <?php custom_registration_function(); ?>
            </div>
            <div class="col-sm-8">
                <main id="main" class="site-main">
                    <?php echo do_shortcode( '[wpb_newusers]' ); ?> 
                </main>
            </div>  
        </div>  
    </div>
<?= get_footer();?>