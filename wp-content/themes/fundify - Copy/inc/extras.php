<?php
/**
 * 
 *
 * @package Fundify
 * @since Fundify 1.0
 */

/**
 * Get our wp_nav_menu() fallback, wp_page_menu(), to show a home link.
 *
 * @since Fundify 1.0
 */
function fundify_page_menu_args( $args ) {
	$args['show_home'] = true;
	return $args;
}
add_filter( 'wp_page_menu_args', 'fundify_page_menu_args' );

/**
 * Filters wp_title to print a neat <title> tag based on what is being viewed.
 *
 * @since Fundify 1.1
 */
function fundify_wp_title( $title, $sep ) {
	global $page, $paged;

	if ( is_feed() )
		return $title;

	// Add the blog name
	$title .= get_bloginfo( 'name' );

	// Add the blog description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) )
		$title .= " $sep $site_description";

	// Add a page number if necessary:
	if ( $paged >= 2 || $page >= 2 )
		$title .= " $sep " . sprintf( __( 'Page %s', 'fundify' ), max( $paged, $page ) );

	return $title;
}
add_filter( 'wp_title', 'fundify_wp_title', 10, 2 );

function fundify_custom_excerpt_more( $more ) {
	global $post;

	if ( 'download' != $post->post_type )
		return $more;

	return '&hellip;';
}
add_filter( 'excerpt_more', 'fundify_custom_excerpt_more' );

function fundify_excerpt_length( $length ) {
	return rand(20, 40);
}
add_filter( 'excerpt_length', 'fundify_excerpt_length' );

function fundify_darken_color( $color ) {
	if ( ! preg_match( '/^#?([0-9a-f]{2})([0-9a-f]{2})([0-9a-f]{2})$/i', $color, $parts ) )
		return;

	$out = '';

	for( $i = 1; $i <= 3; $i++ ) {
		$parts[$i] = hexdec( $parts[$i] );
		$parts[$i] = round($parts[$i] * 70/100);
		$out .= str_pad( dechex( $parts[$i] ), 2, '0', STR_PAD_LEFT );
	}

	return $out;
}

/**
 * Number of posts user has written.
 *
 * @since 3.0.0
 * @uses $wpdb WordPress database object for queries.
 *
 * @param int $userid User ID.
 * @return int Amount of posts user has written.
 */
function fundify_count_user_campaigns( $userid ) {
	global $wpdb;

	$where = get_posts_by_author_sql( 'download', true, $userid);

	$count = $wpdb->get_var( "SELECT COUNT(*) FROM $wpdb->posts $where" );

	return apply_filters('get_usernumposts', $count, $userid);
}