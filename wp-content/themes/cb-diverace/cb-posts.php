<?php

if( !\defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

function reading_time() {
    $content = get_post_field( 'post_content', $post->ID );
    $word_count = str_word_count( strip_tags( $content ) );
    $readingtime = ceil($word_count / 200);

    if ($readingtime == 1) {
      $timer = " minute";
    } else {
      $timer = " minutes";
    }
    $totalreadingtime = $readingtime . $timer;

    return $totalreadingtime;
}

add_shortcode('get-post-info', 'cb_diverace_get_post_info');
function cb_diverace_get_post_info() {
    ob_start();

    global $post;
	?>
	<div class="post-info">
		<div class="post-author">
			<div class="post-author-photo">
				<?php echo get_avatar( $post->post_author, 64 ); ?>
			</div>
			<div class="post-author-details">
				<h6>Written by <?php echo get_the_author( $post->post_author, 64 ); ?></h6>
				<p><?php the_date(); ?> &middot; <?php echo reading_time(); ?></p>
			</div>
		</div>
		<div class="post-sharing">
			<?php echo do_shortcode('[scriptless heading=""]');?>
		</div>
    </div>
    <?php
    
    $output_string = ob_get_contents();
    ob_end_clean();
    return $output_string;
}

add_shortcode('get-post-meta', 'cb_diverace_get_post_meta');
function cb_diverace_get_post_meta() {
    ob_start();

    global $post;
	?>
	<div class="post-meta">
		<div class="post-categories">	
			<p class="post-category"><?php echo get_the_term_list( $post->ID, 'post_tag', '', '', '' ); ?></p>
			<p class="post-published"><em>Originally published <?php echo get_the_date(); ?>, updated <?php echo get_the_modified_date(); ?></em></p>
		</div>
		<div class="post-sharing">
			<?php echo do_shortcode('[scriptless heading=""]');?>
		</div>
    </div>
    <?php
    
    $output_string = ob_get_contents();
    ob_end_clean();
    return $output_string;
}

add_shortcode('get-watch-feed', 'cb_diverace_get_watch_feed');
function cb_diverace_get_watch_feed($posts) {
    ob_start();
    
    if (!$posts) {
        $posts = 12;
    }
    
    // WP_Query arguments
    $args = array(
    	'post_type'              => array( 'post' ),
    	'posts_per_page'         => $posts,
    	'category_name'          => 'watch',
    );
    
    // The Query
    $query = new WP_Query( $args );
    
    // The Loop
    if ( $query->have_posts() ) {
    	while ( $query->have_posts() ) {
    		$query->the_post();
      
            /* link thumbnail to full size image for use with lightbox*/
            ?>
            <div class="discover-watch">
                <a href="<?php the_field('diverace_video'); ?>?rel=0&autoplay=1" class="popup-youtube">
                    <div class="post-thumbnail"><?php the_post_thumbnail('large-size'); ?></div>
                </a>
            </div>
                
    	<?php }
    } else {
    	// no posts found
    }
    
    // Restore original Post Data
    wp_reset_postdata();
        
	?>
    
    
    
    <?php
    
    $output_string = ob_get_contents();
    ob_end_clean();
    return $output_string;
}
