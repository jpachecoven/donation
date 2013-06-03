<?php
/**
 * Template Name: Front Page
 *
 * This should be used in conjunction with the Fundify plugin.
 *
 * @package Fundify
 * @since Fundify 1.0
 */

get_header(); 
?>

	<div id="home-page-featured">
		<?php
			if ( fundify_is_crowdfunding()  ) :
				$featured = new ATCF_Campaign_Query( array( 
					'posts_per_page' => 'grid' == fundify_theme_mod( 'hero_style' ) ? apply_filters( 'fundify_hero_campaign_grid', 24 ) : 1,
					'meta_query'     => array(
						array(
							'key'     => '_campaign_featured',
							'value'   => 1,
							'compare' => '=',
							'type'    => 'numeric'
						)
					)
				) ); 
			else :
				$featured = new WP_Query( array( 
					'posts_per_page' => 'grid' == fundify_theme_mod( 'hero_style' ) ? apply_filters( 'fundify_hero_campaign_grid', 24 ) : 1
				) ); 
			endif; 
		?>
		<?php if ( 'grid' == fundify_theme_mod( 'hero_style' ) ) : ?>
			<?php for ( $i = 0; $i < 3; $i++ ) : shuffle( $featured->posts ); ?>
			<ul>
				<?php while ( $featured->have_posts() ) : $featured->the_post(); ?>
				<li><a href="<?php the_permalink(); ?>"><?php the_post_thumbnail(); ?></a></li>
				<?php endwhile; ?>
			</ul>
			<?php endfor; ?>
		<?php else : ?>
			<?php while ( $featured->have_posts() ) : $featured->the_post(); ?>
				<?php $thumbnail = wp_get_attachment_image_src( get_post_thumbnail_id(), 'fullsize' ); ?>
				<a href="<?php the_permalink(); ?>" class="home-page-featured-single" data-backstretch-source="<?php echo $thumbnail[0]; ?>"></a>
			<?php endwhile; ?>
		<?php endif; ?>

		<div class="hide-desktop mobile-feature">
			<a href="<?php echo get_permalink( $featured->posts[0]->ID ); ?>"><?php echo get_the_post_thumbnail( $featured->posts[0]->ID, 'fullsize' ); ?></a>
		</div>

		<h1>
			<?php 
				$string = fundify_theme_mod( 'hero_text' ); 
				$lines = explode( "\n", $string );
			?>
			<span><?php echo implode( '</span><br /><span>', $lines ); ?></span>
		</h1>
		<!-- / container -->
	</div>
	<div id="content">
		<div class="container">
			<?php locate_template( array( 'searchform-campaign.php' ), true ); ?>
			<div class="sort-tabs" id="filter">
				<h3><?php _e( 'Show', 'fundify' ); ?></h3>
				<div class="dropdown">
					<div class="current"><?php _e( 'All', 'fundify' ); ?></div>
					<ul class="option-set" data-option-key="filter">
						<li><a class="selected" href="#filter" data-filter="*">All</a></li>
						<?php
							$categories = get_terms( fundify_is_crowdfunding() ? 'download_category' : 'category', array( 'hide_empty' => 0 ) );
							foreach ( $categories as $category ) :
						?>
						<li><a href="#filter" data-filter=".<?php echo ! fundify_is_crowdfunding() ? 'category-' : ''; echo $category->slug; ?>"><?php echo $category->name; ?></a></li>
						<?php endforeach; ?>
					</ul>
				</div>

				<?php if ( fundify_is_crowdfunding()  ) : ?>
				<ul class="option-set home">
					<li><a href="#" data-filter=".new-this-week"><?php _e( 'New this Week', 'fundify' ); ?></a></li>
					<li><a href="#" data-filter=".staff-pick"><?php _e( 'Staff Picks', 'fundify' ); ?></a></li>
				</ul>
				<?php endif; ?>
			</div>
			<div id="projects">
				<section>
					<?php 
						if ( fundify_is_crowdfunding()  ) :
							$things = new ATCF_Campaign_Query();
						else :
							$things = new WP_Query( array(
								'posts_per_page' => get_option( 'posts_per_page' )
							) );
						endif;

						if ( $things->have_posts() ) :
					?>

						<?php while ( $things->have_posts() ) : $things->the_post(); ?>
							<?php get_template_part( 'content', fundify_is_crowdfunding() ? 'campaign' : 'post' ); ?>
						<?php endwhile; ?>

						<?php fundify_content_nav( 'nav-below' ); ?>

					<?php else : ?>

						<?php get_template_part( 'no-results', 'index' ); ?>

					<?php endif; ?>
				</section>
			</div>
		</div>
		<!-- / container -->
	</div>
	<!-- / content -->

<?php get_footer(); ?>