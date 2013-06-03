<?php
/**
 * Campaigns
 *
 * @package Fundify
 * @since Fundify 1.0
 */

get_header(); ?>

	<div class="title pattern-<?php echo rand(1,4); ?>">
		<div class="container">
			<?php if ( is_post_type_archive( 'download' ) ) : ?>
				<h1><?php _e( 'Discover Projects', 'fundify' ); ?></h1>
			<?php elseif ( is_tax( 'download_category' ) ) : ?>
				<?php
					global $wp_query;
					$term = $wp_query->get_queried_object();
					$title = $term->name;
				?>
				<h1><?php echo $title; ?></h1>
			<?php endif; ?>
		</div>
		<!-- / container -->
	</div>
	<div id="content">
		<div class="container">
			<?php get_search_form(); ?>

			<div class="sort-tabs" id="filter">
				<?php if ( ! is_tax( 'download_category' ) ) : ?>
				<h3><?php _e( 'Show', 'fundify' ); ?></h3>
				<div class="dropdown">
					<div class="current"><?php _e( 'All', 'fundify' ); ?></div>
					<ul class="option-set" data-option-key="filter">
						<li><a class="selected" href="#filter" data-filter="*">All</a></li>
						<?php
							$categories = get_terms( 'download_category', array( 'hide_empty' => 0 ) );
							foreach ( $categories as $category ) :
						?>
						<li><a href="#filter" data-filter=".<?php echo $category->slug; ?>"><?php echo $category->name; ?></a></li>
						<?php endforeach; ?>
					</ul>
				</div>
				<?php endif; ?>

				<?php if ( fundify_is_crowdfunding() ) : ?>
				<ul class="option-set home">
					<li><a href="#" data-filter=".new-this-week"><?php _e( 'New this Week', 'fundify' ); ?></a></li>
					<li><a href="#" data-filter=".staff-pick"><?php _e( 'Staff Picks', 'fundify' ); ?></a></li>
				</ul>
				<?php endif; ?>
			</div>

			<div id="projects">
				<section>
					<?php while ( have_posts() ) : the_post(); ?>
						<?php get_template_part( 'content', 'campaign' ); ?>
					<?php endwhile; ?>
				</section>

				<?php fundify_content_nav( 'nav-below' ); ?>
			</div>
		</div>
		<!-- / container -->
	</div>
	<!-- / content -->

<?php get_footer(); ?>