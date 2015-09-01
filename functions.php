<?php
/**
 * @package Twenty_Fifteen_Srcset
 */

/**
 * Defines additional images sizes to be used in srcset definitions
 *
 * @since Twenty Fifteen Srcset 1.0
 */
function twentyfifteen_srcset_setup() {

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * See: https://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
	 */
	add_theme_support( 'post-thumbnails' );
	set_post_thumbnail_size( 825, 510, true );

	add_image_size( 'post-thumbnail', 825, 510, true );			// Standard
	add_image_size( 'post-thumbnail-640', 640, 396, true );		// iPhone 4S/5S
	add_image_size( 'post-thumbnail-@2x', 1650, 1020, true );	// @2x Retina

}
add_action( 'after_setup_theme', 'twentyfifteen_srcset_setup' );

/**
 * Overrides the default output of featured images in Twenty Fifteen.
 * Adds srcset definitions.
 */
function twentyfifteen_post_thumbnail() {

	if ( post_password_required() || is_attachment() || ! has_post_thumbnail() ) {
		return;
	}

	// Requires the responsive images plugin:
	// https://github.com/ResponsiveImagesCG/wp-tevko-responsive-images
	if ( ! function_exists( 'tevkori_get_srcset_string' ) || ! function_exists( 'tevkori_get_sizes' ) ) {
		return;
	}

	$sizes_args = array(
		'sizes' => array(
			array(
				'mq_name' => 'min-width',
				'mq_value' => '87.6875em',
				'size_value' => '825px'
			),
			array(
				'mq_name' => 'min-width',
				'mq_value' => '59.6875em',
				'size_value' => '58.8235470588vw'
			),
			array(
				'mq_name' => 'min-width',
				'mq_value' => '38.75em',
				'size_value' => '84.6154vw'
			),
			array(
				'size_value'  => '100vw'
			),
		)
	);

	if ( is_singular() ) :
	?>

	<div class="post-thumbnail">
		<img src="<?php echo prefix_get_src( get_post_thumbnail_id(), 'post-thumbnail' ); ?>" <?php echo tevkori_get_srcset_string( get_post_thumbnail_id(), 'post-thumbnail' ); ?> sizes="<?php echo tevkori_get_sizes( get_post_thumbnail_id(), 'post-thumbnail', $sizes_args ); ?>">
	</div><!-- .post-thumbnail -->

	<?php else : ?>

	<a class="post-thumbnail" href="<?php the_permalink(); ?>" aria-hidden="true">
		<img src="<?php echo prefix_get_src( get_post_thumbnail_id(), 'post-thumbnail' ); ?>" <?php echo tevkori_get_srcset_string( get_post_thumbnail_id(), 'post-thumbnail' ); ?> sizes="<?php echo tevkori_get_sizes( get_post_thumbnail_id(), 'post-thumbnail', $sizes_args ); ?>" alt="<?php the_title(); ?>">
	</a>

	<?php endif; // End is_singular()
}

/**
 * Gets src url for an image.
 *
 * @param int $id
 * @param string $size
 *
 * @returns string $image url
 */
function prefix_get_src( $id, $size ) {
	$image = wp_get_attachment_image_src( $id, $size, true );
	if ( $image ) {
		return $image[0];
	}
	return '';
}