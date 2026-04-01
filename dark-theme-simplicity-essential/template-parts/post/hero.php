<?php
/**
 * Post Hero Section — Side-by-side layout (Maze.co pattern)
 * Text left, featured image right, compact single band
 *
 * @package Dark_Theme_Simplicity
 */

$display_settings = $args['display_settings'] ?? array();
$has_thumbnail = has_post_thumbnail();
$post_helper = new DTS_Post_Helper( get_the_ID() );
$reading_time = $post_helper->get_reading_time();

// Get category for inline display
$categories = get_the_category();
$first_category = ! empty( $categories ) ? $categories[0] : null;
?>

<div class="max-w-6xl mx-auto hero-container">
    <div class="page-header bg-dark-300 mobile-hero-card no-featured-image">
        <div class="w-full max-w-5xl mx-auto px-4 md:px-6 py-4 md:py-6">

            <?php if ( $has_thumbnail ) : ?>

            <!-- Side-by-side: text left, image right -->
            <div class="flex flex-col md:flex-row md:items-center md:gap-8">

                <!-- Text column -->
                <div class="flex-1 min-w-0">

                    <!-- Breadcrumbs -->
                    <?php get_template_part( 'template-parts/post/breadcrumbs' ); ?>

                    <!-- Category + reading time -->
                    <div class="flex flex-wrap items-center gap-3 text-sm text-light-100/70 mb-3">
                        <?php if ( $first_category ) : ?>
                            <a href="<?php echo esc_url( get_category_link( $first_category->term_id ) ); ?>"
                               class="inline-flex items-center rounded-full border px-2 py-0.5 text-xs font-semibold bg-blue-300/10 text-blue-300 hover:bg-blue-300/20 border-blue-300/20">
                                <?php echo esc_html( $first_category->name ); ?>
                            </a>
                        <?php endif; ?>
                        <span class="text-light-100/30">·</span>
                        <span class="flex items-center gap-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-blue-300">
                                <circle cx="12" cy="12" r="10"></circle>
                                <polyline points="12 6 12 12 16 14"></polyline>
                            </svg>
                            <?php printf( esc_html__( '%d min read', 'dark-theme-simplicity' ), $reading_time ); ?>
                        </span>
                    </div>

                    <!-- Title -->
                    <h1 class="text-2xl md:text-3xl lg:text-4xl font-bold mb-2 text-white leading-tight">
                        <?php the_title(); ?>
                    </h1>

                    <!-- Excerpt -->
                    <?php if ( has_excerpt() ) : ?>
                        <p class="text-sm md:text-base text-light-100/70 mb-3 leading-relaxed max-w-xl">
                            <?php echo get_the_excerpt(); ?>
                        </p>
                    <?php endif; ?>

                    <!-- Date -->
                    <div class="flex items-center gap-2 text-sm text-light-100/70">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-blue-300">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                            <line x1="16" y1="2" x2="16" y2="6"></line>
                            <line x1="8" y1="2" x2="8" y2="6"></line>
                            <line x1="3" y1="10" x2="21" y2="10"></line>
                        </svg>
                        <span><?php esc_html_e( 'Last Updated:', 'dark-theme-simplicity' ); ?> <?php echo esc_html( get_the_modified_date() ); ?></span>
                    </div>

                </div>

                <!-- Image column -->
                <div class="mt-4 md:mt-0 md:w-2/5 flex-shrink-0">
                    <div class="overflow-hidden rounded-lg">
                        <?php the_post_thumbnail( 'large', array(
                            'class'    => 'w-full h-full object-cover',
                            'style'    => 'max-height: 240px;',
                            'loading'  => 'eager',
                            'decoding' => 'async',
                            'sizes'    => '(max-width: 768px) 100vw, 400px'
                        ) ); ?>
                    </div>
                </div>

            </div>

            <?php else : ?>

            <!-- No featured image: centered text -->
            <div class="text-center">
                <?php get_template_part( 'template-parts/post/breadcrumbs' ); ?>

                <?php get_template_part( 'template-parts/post/category-badge' ); ?>

                <h1 class="text-2xl md:text-3xl lg:text-4xl font-bold mb-2 md:mb-3 text-white leading-tight">
                    <?php the_title(); ?>
                </h1>

                <?php get_template_part( 'template-parts/post/meta' ); ?>
            </div>

            <?php endif; ?>

        </div>
    </div>
</div>
