<?php
/**
 * Related Posts Section
 *
 * @package Dark_Theme_Simplicity
 */

$post_helper = new DTS_Post_Helper( get_the_ID() );
$related_query = $post_helper->get_related_posts();
?>

<section class="bg-dark-200 py-8 md:py-16 mt-8 md:mt-16 mobile-section border-t border-white/10">
	<div class="container mx-auto">
    	<h2 class="text-xl md:text-3xl font-medium mb-6 md:mb-8 text-center text-white px-4">
        	<?php echo esc_html( apply_filters( 'dts_related_posts_title', __( 'Related Articles', 'dark-theme-simplicity' ) ) ); ?>
    	</h2>
   	 
    	<div class="related-posts-grid">
        	<?php if ( $related_query->have_posts() ) : ?>

            	<?php get_template_part( 'template-parts/post/related-posts-cta' ); ?>

            	<?php while ( $related_query->have_posts() ) : $related_query->the_post(); ?>
                	<div class="related-post-card">
                    	<?php get_template_part( 'template-parts/post/related-posts-card' ); ?>
                	</div>
            	<?php endwhile; ?>
            	<?php wp_reset_postdata(); ?>
        	<?php else : ?>
            	<div class="col-span-full text-center">
                	<p class="text-light-100/70"><?php esc_html_e( 'No related posts found.', 'dark-theme-simplicity' ); ?></p>
            	</div>
        	<?php endif; ?>
    	</div>
	</div>
</section>
