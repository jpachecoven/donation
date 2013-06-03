<?php
/**
 * Fundify functions and definitions
 *
 * @package Fundify
 * @since Fundify 1.0
 */

/**
 * Set the content width based on the theme's design and stylesheet.
 *
 * @since Fundify 1.0
 */
if ( ! isset( $content_width ) )
	$content_width = 745; /* pixels */

if ( ! function_exists( 'fundify_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which runs
 * before the init hook. The init hook is too late for some features, such as indicating
 * support post thumbnails.
 *
 * @since Fundify 1.0
 */
function fundify_setup() {
	/**
	 * Custom template tags for this theme.
	 */
	require( get_template_directory() . '/inc/template-tags.php' );

	/**
	 * Custom functions that act independently of the theme templates
	 */
	require( get_template_directory() . '/inc/extras.php' );

	/**
	 * Customizer additions
	 */
	require( get_template_directory() . '/inc/customizer.php' );

	/**
	 * Make theme available for translation
	 * Translations can be filed in the /languages/ directory
	 * If you're building a theme based on Fundify, use a find and replace
	 * to change 'fundify' to the name of your theme in all the template files
	 */
	load_theme_textdomain( 'fundify', get_template_directory() . '/languages' );

	/**
	 * This theme supports AppThemer Crowdfunding Plugin
	 */
	add_theme_support( 'appthemer-crowdfunding' );

	/**
	 * Add default posts and comments RSS feed links to head
	 */
	add_theme_support( 'automatic-feed-links' );

	/**
	 * Custom Background
	 */
	add_theme_support( 'custom-background', array(
		'default-color' => 'ffffff',
		'default-image' => get_template_directory_uri() . '/images/bg_body.jpg'
	) );

	/**
	 * Enable support for Post Thumbnails
	 */
	add_theme_support( 'post-thumbnails' );
	set_post_thumbnail_size( 186, 186, true );
	add_image_size( 'blog', 745, 396, true );
	add_image_size( 'campaign', 253, 99999 );

	/**
	 * This theme uses wp_nav_menu() in one location.
	 */
	register_nav_menus( array(
		'primary-left' => __( 'Primary Left Menu', 'fundify' ),
		'primary-right' => __( 'Primary Right Menu', 'fundify' )
	) );
}
endif; // fundify_setup
add_action( 'after_setup_theme', 'fundify_setup' );

function fundify_infinite_scroll_render() {
	get_template_part( 'content', 'campaign' );
}

function fundify_infinite_scroll_query_args( $args ) {
	$args[ 'post_type' ] = 'download';

	return $args;
}

/**
 * Register widgetized area and update sidebar with default widgets
 *
 * @since Fundify 1.0
 */
function fundify_widgets_init() {
	register_sidebar( array(
		'name' => __( 'Sidebar', 'fundify' ),
		'id' => 'sidebar-1',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	for ( $i = 2; $i <= 4; $i++ ) {
		register_sidebar( array(
			'name' => sprintf( __( 'Footer Column %d', 'fundify' ), $i - 1 ),
			'id' => 'sidebar-' . $i,
			'before_widget' => '<div id="%1$s" class="%2$s">',
			'after_widget' => '</div>',
			'before_title' => '<h3 class="widget-title">',
			'after_title' => '</h3>',
		) );
	}
}
add_action( 'widgets_init', 'fundify_widgets_init' );

/**
 * Enqueue scripts and styles
 */
function fundify_scripts() {
	wp_enqueue_style( 'crowdfunding-fonts', 'http://fonts.googleapis.com/css?family=Merriweather:400,700|Oswald' );
	wp_enqueue_style( 'crowdfunding-style', get_stylesheet_uri() );

	if ( 'single' == fundify_theme_mod( 'hero_style' ) )
		wp_enqueue_script( 'crowdfunding-backstretch', get_template_directory_uri() . '/js/jquery.backstretch.min.js' );

	wp_enqueue_script( 'crowdfunding-isotope', get_template_directory_uri() . '/js/isotope.js', array( 'jquery' ) );
	wp_enqueue_script( 'crowdfunding-scripts', get_template_directory_uri() . '/js/fundify.js', array( 'crowdfunding-isotope' ) );

	$localization = array(
		'is_front_page' => is_front_page(),
		'hero_style'    => fundify_theme_mod( 'hero_style' )
	);

	wp_localize_script( 'crowdfunding-scripts', 'FundifyL10n', $localization );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'fundify_scripts' );


function fundify_is_crowdfunding() {
	if ( class_exists( 'Easy_Digital_Downloads' ) && class_exists( 'ATCF_Campaign' ) )
		return true;

	return false;
}

/**
 * Plugin Notice
 *
 * @since Fundify 1.0
 *
 * @return void
 */
function fundify_features_notice() {
	?>
	<div class="updated">
		<p><?php printf( 
					__( '<strong>Notice:</strong> To take advantage of all of the great features Fundify offers, please install the <a href="%s">AppThemer Crowdfunding Plugin</a>. <a href="%s" class="alignright">Hide this message.</a>', 'fundify' ), 
					wp_nonce_url( network_admin_url( 'update.php?action=install-plugin&plugin=appthemer-crowdfunding' ), 'install-plugin_appthemer-crowdfunding' ), 
					wp_nonce_url( add_query_arg( array( 'action' => 'fundify-hide-plugin-notice' ), admin_url( 'index.php' ) ), 'fundify-hide-plugin-notice' ) 
			); ?></p>
	</div>
<?php
}
if ( ( ! fundify_is_crowdfunding() ) && is_admin() && ! get_user_meta( get_current_user_id(), 'fundify-hide-plugin-notice', true ) )
	add_action( 'admin_notices', 'fundify_features_notice' );

/**
 * Hide plugin notice.
 *
 * @since Fundify 1.0
 *
 * @return void
 */
function fundify_hide_plugin_notice() {
	check_admin_referer( 'fundify-hide-plugin-notice' );

	$user_id = get_current_user_id();

	add_user_meta( $user_id, 'fundify-hide-plugin-notice', 1 );
}
if ( is_admin() )
	add_action( 'admin_action_fundify-hide-plugin-notice', 'fundify_hide_plugin_notice' );

/**
 * Contribute notw list options
 *
 * @since Fundify 1.0
 *
 * @return void
 */
function fundify_campaign_contribute_options( $prices, $type, $download_id ) {
	$campaign = new ATCF_Campaign( $download_id );
	$backers  = $campaign->backers_per_price();
?>
	<div class="edd_price_options <?php echo $campaign->is_active() ? 'active' : 'expired'; ?>">
		<ul>
			<?php foreach ( $prices as $key => $price ) : ?>
				<?php
					$amount  = $price[ 'amount' ];
					$limit   = isset ( $price[ 'limit' ] ) ? $price[ 'limit' ] : '';
					$bought  = isset ( $price[ 'bought' ] ) ? $price[ 'bought' ] : 0;
					$allgone = false;

					if ( $bought == absint( $limit ) && '' != $limit )
						$allgone = true;

					if ( edd_use_taxes() && edd_taxes_on_prices() )
						$amount += edd_calculate_tax( $amount );
				?>
				<li <?php if ( $allgone ) : ?>class="inactive"<?php endif; ?>>
					<h3><?php
						if ( $campaign->is_active() )
							if ( ! $allgone )
								printf(
									'<input type="%4$s" name="edd_options[price_id][]" id="%1$s" class="%2$s edd_price_options_input" value="%3$s"/>',
									esc_attr( 'edd_price_option_' . $download_id . '_' . $key ),
									esc_attr( 'edd_price_option_' . $download_id ),
									esc_attr( $key ),
									$type
								);
					?> <?php printf( __( 'Pledge %s', 'fundify' ), edd_currency_filter( edd_format_amount( $amount ) ) ); ?></h3>
					<div class="backers">
						<?php 
							$backer_count = isset ( $backers[ $amount ] ) ? esc_attr( $backers[ $amount ] ) : 0;
							printf( _n( '%d Backer', '%d Backers',  $backer_count, 'fundify' ), $backer_count ); 
						?> 

						<?php if ( '' != $limit && ! $allgone ) : ?>
							<small class="limit"><?php printf( __( 'Limited (%d of %d left)', 'fundify' ), $limit - $backer_count, $limit ); ?></small>
						<?php elseif ( $allgone ) : ?>
							<small class="gone"><?php _e( 'All gone!', 'fundify' ); ?></small>
						<?php endif; ?>
					</div>
					<?php echo wpautop( esc_html( $price[ 'name' ] ) ); ?>
				</li>
			<?php endforeach; ?>
		</ul>
	</div><!--end .edd_price_options-->
<?php
}
add_action( 'atcf_campaign_contribute_options', 'fundify_campaign_contribute_options', 10, 3 );

/**
 * Campaign category classes
 *
 * @since Fundify 1.0
 *
 * @return void
 */
function fundify_category_class( $classes ) {
	global $post;

	if ( fundify_is_crowdfunding() && 'download' == $post->post_type ) {
		$campaign = new ATCF_Campaign( $post );

		if ( is_front_page() || is_archive() ) {
			$age = date( 'U' ) - get_post_time( 'U', true, $post );

			if ( $age < ( 7 * 86400 ) )
				$classes[] = 'new-this-week';
		}

		if ( $campaign->featured() )
			$classes[] = 'staff-pick';
		
		$categories = get_the_terms( $post->ID, 'download_category' );

		if ( ! $categories )
			return $classes;

		foreach( $categories as $category )
			$classes[] = $category->slug;
	}

	return $classes;
}
add_filter( 'post_class', 'fundify_category_class' );

/**
 * Show campaigns on author archive.
 *
 * @since Fundify 1.0
 *
 * @return void
 */
function fundify_post_author_archive( $query ) {
	if ( $query->is_author )
		$query->set( 'post_type', 'download' );
}
add_action( 'pre_get_posts', 'fundify_post_author_archive' );

function fundify_search_filter( $query ) {
	if ( ! $query->is_search() )
		return;

	$post_type = isset ( $_GET[ 'type' ] ) ? esc_attr( $_GET[ 'type' ] ) : null;

	if ( ! in_array( $post_type, array( 'download', 'post' ) ) )
		return;

	if ( ! $post_type )
		$post_type = 'post';

	$query->set( 'post_type', $post_type );

	return $query;
};
add_filter( 'pre_get_posts', 'fundify_search_filter' );

/**
 * Implement the Custom Header feature
 */
require( get_template_directory() . '/inc/custom-header.php' );
