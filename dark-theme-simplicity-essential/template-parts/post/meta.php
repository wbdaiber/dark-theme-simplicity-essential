<?php
/**
 * Post Meta Information
 *
 * @package Dark_Theme_Simplicity
 */

$post_helper = new DTS_Post_Helper( get_the_ID() );
$reading_time = $post_helper->get_reading_time();
?>

<div class="flex flex-wrap items-center justify-center md:justify-start gap-4 text-sm text-light-100/80">
    <time datetime="<?php echo esc_attr( get_the_modified_date( 'c' ) ); ?>" class="flex items-center gap-2">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-blue-300">
            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
            <line x1="16" y1="2" x2="16" y2="6"></line>
            <line x1="8" y1="2" x2="8" y2="6"></line>
            <line x1="3" y1="10" x2="21" y2="10"></line>
        </svg>
        <span><?php esc_html_e( 'Last Updated:', 'dark-theme-simplicity' ); ?> <?php echo esc_html( get_the_modified_date() ); ?></span>
    </time>
    <span class="text-light-100/40">·</span>
    <span class="flex items-center gap-2">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-blue-300">
            <circle cx="12" cy="12" r="10"></circle>
            <polyline points="12 6 12 12 16 14"></polyline>
        </svg>
        <?php printf( esc_html__( '%d min read', 'dark-theme-simplicity' ), $reading_time ); ?>
    </span>
</div>