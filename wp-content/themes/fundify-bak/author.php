<?php
/**
 * Campaigns
 *
 * @package Fundify
 * @since Fundify 1.1
 */

global $wp_query;
$author = $wp_query->get_queried_object();

get_header(); ?>

	<div class="title title-two pattern-<?php echo rand(1,4); ?>">
		<div class="container">
			<h1><?php echo $author->user_nicename; ?></h1>
			<h3><?php 
				$count = count_user_posts( $author->ID );
				printf( _nx( 'Created %1$d Campaign', 'Created %1$d Campaigns', '1: Number of Campaigns 2: EDD Object', 'fundify' ), $count ); 
			?></h3>
		</div>
		<!-- / container -->
	</div>
	<div id="content">
		<div class="container">
			<div class="single-author-bio">
				<?php echo get_avatar( $author->user_email, 80 ); ?>

				<div class="author-bio big">
					<?php echo wpautop( $author->user_description ); ?>
				</div>

				<ul class="author-bio-links">
					<li class="contact-link"><?php echo make_clickable( $author->user_url ); ?></li>
					<?php
						$methods = _wp_get_user_contactmethods();

						foreach ( $methods as $key => $method ) :
							if ( '' == $method )
								continue;
					?>
						<li class="contact-<?php echo $key; ?>"><?php echo make_clickable( $author->$key ); ?></li>
					<?php endforeach; ?>
				</ul>
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