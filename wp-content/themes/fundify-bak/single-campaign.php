<?php
/**
 * The Template for displaying all single campaigns.
 *
 * @package Fundify
 * @since Fundify 1.0
 */

global $wp_embed;

get_header(); ?>

	<?php while ( have_posts() ) : the_post(); $campaign = new ATCF_Campaign( $post->ID ); ?>
	<div class="title <?php echo '' == $campaign->author() ? '' : 'title-two'; ?> pattern-<?php echo rand(1,4); ?>">
		<div class="container">
			<h1><?php the_title() ;?></h1>
			
			<?php if ( '' != $campaign->author() ) : ?>
			<h3><?php printf( __( 'By %s', 'fundify' ), esc_attr( $campaign->author() ) ); ?></h3>
			<?php endif; ?>
		</div>
		<!-- / container -->
	</div>
	<div id="content" class="post-details">
		<div class="container">
			<?php do_action( 'atcf_campaign_before', $campaign ); ?>
			
			<?php locate_template( array( 'searchform-campaign.php' ), true ); ?>
			<div class="sort-tabs campaign">
				<ul>
					<li><a href="#description" class="campaign-view-descrption tabber"><?php _e( 'Overview', 'fundify' ); ?></a></li>
					
					<?php if ( '' != $campaign->updates() ) : ?>
					<li><a href="#updates" class="tabber"><?php _e( 'Updates', 'fundify' ); ?></a></li>
					<?php endif; ?>
					
					<li><a href="#comments" class="tabber"><?php _e( 'Comments', 'fundify' ); ?></a></li>
					<li><a href="#backers" class="tabber"><?php _e( 'Backers', 'fundify' ); ?></a></li>
					
					<?php if ( get_current_user_id() == $post->post_author || current_user_can( 'manage_options' ) ) : ?>
					<li><a href="<?php echo atcf_create_permalink( 'edit', get_permalink() ); ?>"><?php _e( 'Edit Campaign', 'fundify' ); ?></a></li>
					<?php endif; ?>
				</ul>
			</div>
			<article class="project-details">
				<div class="image">
					<?php if ( $campaign->video() ) : ?>
						<div class="video-container">
							<?php echo $wp_embed->run_shortcode( '[embed]' . $campaign->video() . '[/embed]' ); ?>
						</div>
					<?php else : ?>
						<?php the_post_thumbnail( 'blog' ); ?>
					<?php endif; ?>
				</div>
				<div class="right-side">
					<ul>
						<li>
							<h3><?php echo $campaign->backers_count(); ?></h3>
							<p><?php echo _n( 'Backer', 'Backers', $campaign->backers_count(), 'number of backers', 'fundify' ); ?></p>
						</li>
						<li>
							<h3><?php echo $campaign->current_amount(); ?></h3>
							<p><?php printf( __( 'Pledged of %s Goal', 'fundify' ), $campaign->goal() ); ?></p>
						</li>
						<li>
							<h3><?php echo $campaign->days_remaining(); ?></h3>
							<p><?php echo _n( 'Day to Go', 'Days to Go', $campaign->days_remaining(), 'fundify' ); ?></p>
						</li>
					</ul>
					<a href="#contribute-now" class="btn-green"><?php _e( 'Contribute Now', 'fundify' ); ?></a>
					<p class="fund">
						<?php if ( 'fixed' == $campaign->type() ) : ?>
						<?php printf( __( 'This project will only be funded if at least %1$s is pledged by %2$s.', 'fundify' ), $campaign->goal(), $campaign->end_date() ); ?>
						<?php elseif ( 'flexible' == $campaign->type() ) : ?>
						<?php printf( __( 'All funds will be collected on %1$s.', 'fundify' ), $campaign->end_date() ); ?>
						<?php endif; ?>
					</p>
				</div>
				<div class="div-c"></div>
			</article>
			<aside id="sidebar">
				<?php
					$author = get_user_by( 'id', $post->post_author );

					if ( '' != $author->user_description || '' != $author->display_name ) : ?>
				<div class="widget widget-bio">
					<h3><?php _e( 'About the Author', 'fundify' ); ?></h3>

					<?php echo get_avatar( $author->user_email, 40 ); ?>

					<div class="author-bio">
						<strong><?php echo $author->display_name; ?></strong><br />
						<small>
							<?php 
								$count = count_user_posts( $author->ID );
								printf( _nx( 'Created %1$d Campaign', 'Created %1$d Campaigns', '1: Number of Campaigns 2: EDD Object', 'fundify' ), $count );  
							?> 
							&bull; 
							<a href="<?php echo get_author_posts_url( $author->ID ); ?>"><?php _e( 'View Profile', 'fundify' ); ?></a></small>
					</div>

					<ul class="author-bio-links">
						<li class="contact-link"><?php echo make_clickable( $author->user_url ); ?></li>
						<?php
							$methods = _wp_get_user_contactmethods();

							foreach ( $methods as $key => $method ) :
								if ( '' == $author->$key )
									continue;
						?>
							<li class="contact-<?php echo $key; ?>"><?php echo make_clickable( $author->$key ); ?></li>
						<?php endforeach; ?>
					</ul>

					<div class="author-bio-desc">
						<?php echo wpautop( $author->user_description ); ?>
					</div>
				</div>
				<?php endif; ?>

				<div class="sidebar-widgets">
					<div id="contribute-now" class="widget widget-pletges">
						<?php 
							if ( $campaign->is_active() ) :
								echo edd_get_purchase_link( array( 
									'download_id' => $post->ID,
									'class'       => '',
									'price'       => false,
									'text'        => __( 'Contribute Now', 'fundify' )
								) ); 
							else : // Inactive, just show options with no button
								fundify_campaign_contribute_options( edd_get_variable_prices( $post->ID ), 'checkbox', $post->ID );
							endif;
						?>					
					</div>
				</div>
			</aside>
			<div id="main-content">
				<div class="post-meta campaign-meta">
					<div class="date"><?php printf( __( 'Launched: %s', 'fundify' ), get_the_date() ); ?></div>
					<div class="funding-ends"><?php printf( __( 'Funding Ends: %s', 'fundify' ), date( get_option( 'date_format' ), strtotime( $campaign->end_date() ) ) ); ?></div>
					<?php if ( $campaign->location() ) : ?>
					<div class="location"><?php echo $campaign->location(); ?></div>
					<?php endif; ?>
				</div>

				<div class="entry-share">
					<?php _e( 'Share this campaign', 'fundify' ); ?>

					<?php $message = apply_filters( 'fundify_share_message', sprintf( __( 'Check out %s on %s! %s', 'fundify' ), $post->post_title, get_bloginfo( 'name' ), get_permalink() ) ); ?>

					<a href="<?php echo esc_url( sprintf( 'http://twitter.com/home?status=%s', urlencode( $message ) ) ); ?>" target="_blank" class="share-twitter"></a>
					<a href="<?php echo esc_url( sprintf( 'http://www.facebook.com/sharer.php?u=%s', urlencode( $message ) ) ); ?>" target="_blank" class="share-facebook"></a>
					<a href="https://plus.google.com/share?url=<?php the_permalink(); ?>" target="_blank" class="share-google"></a>
					<a href="http://pinterest.com/pin/create/button/?url=<?php the_permalink(); ?>" target="_blank" class="share-pinterest"></a>
					<a href="<?php the_permalink(); ?>" target="_blank" class="share-link"></a>
				</div>

				<div class="entry-content inner campaign-tabs">
					<div id="description">
						<?php the_content(); ?>

						<?php if ( '' != $campaign->contact_email() ) : ?>
							<div id="contact">
								<h3 class="contact-campaign-author sans"><?php _e( 'Contact', 'fundify' ); ?></h3>
								<p><?php printf( apply_filters( 'fundify_contact_blurb', __( '<strong>Have a question?</strong> If the above information does not help, contact the campaign author directly.', 'fundify' ) ) ); ?></p>

								<p><a href="mailto:<?php echo $campaign->contact_email(); ?>" class="button"><?php _e( 'Ask Question', 'fundify' ); ?></a></p>
							</div>
						<?php endif; ?>
					</div>

					<?php if ( '' != $campaign->updates() ) : ?>
						<div id="updates">
							<h3 class="campaign-updates-title sans"><?php _e( 'Updates', 'fundify' ); ?></h3>

							<?php echo $campaign->updates(); ?>
						</div>
					<?php endif; ?>

					<?php comments_template(); ?>

					<div id="backers">
						<?php $backers = $campaign->backers(); ?>

						<?php if ( empty( $backers ) ) : ?>
						<p><?php _e( 'No backers yet. Be the first!', 'fundify' ); ?></p>
						<?php else : ?>

							<ol class="backer-list">
							<?php foreach ( $backers as $backer ) : ?>
								<?php
									$payment_id = get_post_meta( $backer->ID, '_edd_log_payment_id', true );
									$user_info  = edd_get_payment_meta_user_info( $payment_id );
								?>

								<li class="backer">
									<?php echo get_avatar( $user_info[ 'email' ], 40 ); ?>

									<div class="backer-info">
										<strong><?php echo $user_info[ 'first_name' ]; ?> <?php echo $user_info[ 'last_name' ]; ?></strong><br />
										<?php echo edd_payment_amount( $payment_id ); ?>
									</div>
								</li>
							<?php endforeach; ?>
							</ol>

						<?php endif; ?>
					</div>
				</div>
			</div>
		</div>
		<!-- / container -->
	</div>
	<!-- / content -->
	<?php endwhile; ?>

<?php get_footer(); ?>