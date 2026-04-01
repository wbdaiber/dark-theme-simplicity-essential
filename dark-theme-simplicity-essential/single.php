<?php
/**
 * The template for displaying all single posts - Mobile Optimized
 *
 * @package Dark_Theme_Simplicity
 */

get_header();

// Get the post helper instance
$post_helper = new DTS_Post_Helper( get_the_ID() );
$display_settings = $post_helper->get_display_settings();
$layout_classes = $post_helper->get_layout_classes();
?>

<main id="content" class="site-main">
    	<?php while ( have_posts() ) : the_post(); ?>
       	 
        	<!-- Hero Section -->
        	<?php get_template_part( 'template-parts/post/hero', null, array(
            	'display_settings' => $display_settings
        	) ); ?>
       	 
        	<!-- Main Content -->
        	<article class="post-container container mx-auto px-4 max-w-6xl bg-dark-300 <?php echo esc_attr( $layout_classes['visibility_classes'] ); ?>">
            	<div class="post-content p-4 md:p-12">
               	 
                	<!-- Mobile TOC -->
                	<?php if ( $display_settings['has_toc'] && $display_settings['show_toc'] === 'yes' ) : ?>
                    	<?php get_template_part( 'template-parts/post/mobile-toc', null, array(
                        	'headings' => $post_helper->get_table_of_contents(),
                        	'show_share' => $display_settings['show_share']
                    	) ); ?>
                	<?php endif; ?>
               	 
                	<!-- Content Layout -->
                	<div class="flex flex-col md:flex-row gap-3 md:gap-4 <?php echo esc_attr( $layout_classes['centered_layout'] ); ?>">
                   	 
                    	<!-- Main Content Column -->
                    	<div class="flex-1 <?php echo esc_attr( $layout_classes['content_width_class'] ); ?>">
                        	<div class="entry-content prose prose-invert max-w-none text-light-100/80 prose-p:leading-relaxed prose-headings:mt-8 prose-headings:mb-4">
                            	<?php the_content(); ?>
                        	</div>
                    	</div>
                   	 
                    	<!-- Sidebar -->
                    	<?php if ( $post_helper->should_show_sidebar() ) : ?>
                        	<?php get_template_part( 'template-parts/post/sidebar', null, array(
                            	'display_settings' => $display_settings,
                            	'headings' => $post_helper->get_table_of_contents(),
                            	'layout_classes' => $layout_classes
                        	) ); ?>
                    	<?php endif; ?>
                   	 
                	</div>
            	</div>
        	</article>
       	 
        	<!-- Mobile CTA (visible only below desktop where sidebar is hidden) -->
        	<div class="lg:hidden">
            	<?php get_template_part( 'template-parts/post/mobile-cta' ); ?>
        	</div>

        	<!-- Related Posts Section -->
        	<?php get_template_part( 'template-parts/post/related-posts' ); ?>

    	<?php endwhile; ?>
</main>

<!-- Scroll-triggered CTA (appears at 60% scroll on desktop) -->
<?php get_template_part( 'template-parts/post/scroll-cta' ); ?>

<?php
get_footer();
