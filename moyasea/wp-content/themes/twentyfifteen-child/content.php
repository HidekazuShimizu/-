<?php
/**
 * The default template for displaying content
 *
 * Used for both single and index/archive/search.
 *
 * @package WordPress
 * @subpackage Twenty_Fifteen
 * @since Twenty Fifteen 1.0
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php
		// Post thumbnail.
		twentyfifteen_post_thumbnail();
	?>

	<header class="entry-header">
		<?php
		if ( is_single() ) :
			the_title( '<h1 class="entry-title">', '</h1>' );
			else :
				the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' );
			endif;
			?>
	</header><!-- .entry-header -->

	<div class="entry-content">
		<?php
			if ( is_single( ) ) :
				/* translators: %s: Name of current post */
				the_content(
					sprintf(
					__( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'twentyfifteen' ),
					get_the_title()
				)
			);
			wp_link_pages(
				array(
					'before' => '<div class="page-links">' . __( 'Pages:', 'twentyfifteen' ),
					'after' => '</div>',
					'link_before' => '<span class="page-number">',
					'link_after' => '</span>',
				)
			);
			else :
				/* translators: %s: Name of current post */
				the_excerpt(
					sprintf(
					__( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'twentyfifteen' ),
					get_the_title()
				)
			);
			wp_link_pages(
				array(
					'before' => '<div class="page-links">' . __( 'Pages:', 'twentyfifteen' ),
					'after' => '</div>',
					'link_before' => '<span class="page-number">',
					'link_after' => '</span>',
				)
			);
			endif;
		?>
	</div><!-- .entry-content -->

	<?php
	// Author bio.
	if ( is_single() && get_the_author_meta( 'description' ) ) :
		get_template_part( 'author-bio' );
		endif;
	?>

	<footer class="entry-footer">
		<?php twentyfifteen_entry_meta(); ?>
		<?php edit_post_link( __( 'Edit', 'twentyfifteen' ), '<span class="edit-link">', '</span>' ); ?>
	</footer><!-- .entry-footer -->

</article><!-- #post-<?php the_ID(); ?> -->
