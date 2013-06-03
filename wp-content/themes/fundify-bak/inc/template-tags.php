<?php
/**
 * Custom template tags for this theme.
 *
 * Eventually, some of the functionality here could be replaced by core features
 *
 * @package Fundify
 * @since Fundify 1.0
 */

if ( ! function_exists( 'fundify_comment' ) ) :
/**
 * Template for comments and pingbacks.
 *
 * Used as a callback by wp_list_comments() for displaying the comments.
 *
 * @since Fundify 1.0
 */
function fundify_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	switch ( $comment->comment_type ) :
		case 'pingback' :
		case 'trackback' :
	?>
	<li class="post pingback">
		<p><?php _e( 'Pingback:', 'fundify' ); ?> <?php comment_author_link(); ?><?php edit_comment_link( __( '(Edit)', 'fundify' ), ' ' ); ?></p>
	<?php
			break;
		default :
	?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
		<article id="comment-<?php comment_ID(); ?>" class="comment">
			<div class="reply"><?php comment_reply_link( array_merge( $args, array( 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?></div>
			<header class="comment-meta">
				<?php echo get_avatar( $comment, 53 ); ?>
				<?php printf( __( '%s', 'fundify' ), sprintf( '<cite class="fn">%s</cite>', get_comment_author_link() ) ); ?>
				<a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>"><time><?php comment_date(); ?></time></a>
			</header>
			<!-- .comment-meta -->
			
			<section class="comment-content comment">
				<?php comment_text(); ?>
				<?php edit_comment_link( __( 'Edit', 'fundify' ) ); ?>
			</section>
			<!-- .comment-content -->
			
			<!-- .reply --> 
		</article>
	<?php
			break;
	endswitch;
}
endif; // ends check for fundify_comment()

if ( ! function_exists( 'fundify_content_nav' ) ) :
/**
 * Displays navigation to next/previous pages when applicable.
 *
 * @since Fundify 1.0
 */
function fundify_content_nav( $html_id ) {
	global $wp_query;

	$html_id = esc_attr( $html_id );

	$strings = array(
		__( '<span class="meta-nav">&larr;</span> Older Posts', 'fundify' ),
		__( 'Newer Posts <span class="meta-nav">&rarr;</span>', 'fundify' )
	);

	if ( 'download' == get_query_var( 'post_type' ) ) {
		$strings = array(
			sprintf( __( '<span class="meta-nav">&larr;</span> Older %s', 'fundify' ), edd_get_label_plural() ),
			sprintf( __( 'Newer %s <span class="meta-nav">&rarr;</span>', 'fundify' ), edd_get_label_plural() )
		);
	}

	if ( $wp_query->max_num_pages > 1 ) : ?>
		<nav id="<?php echo $html_id; ?>" class="navigation" role="navigation">
			<div class="nav-previous alignleft"><?php next_posts_link( $strings[0] ); ?></div>
			<div class="nav-next alignright"><?php previous_posts_link( $strings[1] ); ?></div>
		</nav><!-- #<?php echo $html_id; ?> .navigation -->
	<?php endif;
}
endif;