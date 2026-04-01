<?php
/**
 * Related Post Card
 *
 * @package Dark_Theme_Simplicity
 */

// Get the first category
$post_categories = get_the_category();
$first_category = ! empty( $post_categories ) ? $post_categories[0] : null;

// Content type badge (set via post meta _content_type, fallback to "Article")
$content_type = get_post_meta( get_the_ID(), '_content_type', true );
if ( empty( $content_type ) ) {
    $content_type = 'Article';
}

// Reading time (static call avoids extra DB queries per card)
$card_reading_time = DTS_Post_Helper::calculate_reading_time( get_the_ID() );
?>

<a href="<?php the_permalink(); ?>" class="block h-full group">
    <div class="overflow-hidden backdrop-blur-lg bg-dark-300/80 rounded-xl transition-all duration-300 hover:bg-dark-300 h-full flex flex-col">
        <div class="aspect-video relative bg-gradient-to-tr from-blue-300/20 to-purple-300/20">
            <?php if ( has_post_thumbnail() ) : ?>
                <?php the_post_thumbnail( 'medium', array( 'class' => 'w-full h-full object-cover transition-transform duration-300 group-hover:scale-105' ) ); ?>
            <?php else : ?>
                <div class="w-full h-full bg-gradient-to-tr from-blue-300/20 to-purple-300/20"></div>
            <?php endif; ?>

            <span class="absolute top-4 left-4 bg-white/90 text-dark-300 px-2 py-0.5 rounded text-xs font-bold uppercase tracking-wide z-10">
                <?php echo esc_html( $content_type ); ?>
            </span>
        </div>

        <div class="p-4 md:p-5 flex flex-col flex-grow">
            <div class="flex gap-3 mb-3 items-center text-xs text-light-100/50">
                <span class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <?php echo get_the_modified_date(); ?>
                </span>
                <span class="text-light-100/30">·</span>
                <span><?php printf( esc_html__( '%d min read', 'dark-theme-simplicity' ), $card_reading_time ); ?></span>
            </div>

            <h3 class="text-lg md:text-xl font-bold mb-2 line-clamp-2 text-white hover:text-blue-300 transition-colors pl-0">
                <?php the_title(); ?>
            </h3>

            <p class="text-light-100/70 line-clamp-2 mb-4 text-sm">
                <?php
                if ( has_excerpt() ) {
                    echo get_the_excerpt();
                } else {
                    echo wp_trim_words( get_the_content(), 20, '...' );
                }
                ?>
            </p>

            <span class="text-blue-300 text-sm font-medium mt-auto">
                <?php printf( esc_html__( 'Read %s', 'dark-theme-simplicity' ), '&rarr;' ); ?>
            </span>
        </div>
    </div>
</a>