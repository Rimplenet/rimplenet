<?php 
global $post, $wpdb, $current_user;
wp_get_current_user();
$userinfo = wp_get_current_user();
$user_id = $userinfo->ID; 

//Blank Page will just echo content
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<?php if ( ! get_theme_support( 'title-tag' ) ) : ?>
		<title><?php wp_title(''); ?> - <?php echo get_bloginfo('name'); ?></title>
	<?php endif; ?>

	<?php wp_head(); ?>
</head>

<body <?php body_class( 'rimplenet' ); ?>>

<?php while ( have_posts() ) : ?>

	<?php the_post(); ?>

	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

		<?php the_content(); ?>

	</article>

<?php endwhile; ?>

<?php wp_footer(); ?>

</body>
</html>