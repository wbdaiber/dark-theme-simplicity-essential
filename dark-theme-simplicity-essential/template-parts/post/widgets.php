<?php
/**
 * Sidebar Widgets
 * 
 * @package Dark_Theme_Simplicity
 */
?>

<div class="mt-3 sidebar-widgets">
    <?php
    // Check for post-specific sidebar first
    if ( is_active_sidebar( 'sidebar-post' ) ) {
        echo '<div class="widget-area bg-dark-400 p-4 rounded-lg text-sm">';
        dynamic_sidebar( 'sidebar-post' );
        echo '</div>';
    } 
    // Fallback to main sidebar
    elseif ( is_active_sidebar( 'sidebar-1' ) ) {
        echo '<div class="widget-area bg-dark-400 p-4 rounded-lg text-sm">';
        dynamic_sidebar( 'sidebar-1' );
        echo '</div>';
    } 
    // Show message if no widgets
    else {
        ?>
        <div class="widget-area bg-dark-400 p-4 rounded-lg text-sm">
            <div class="widget">
                <div class="widget-title text-sm font-bold mb-2 text-white">
                    <?php esc_html_e( 'No Widgets Found', 'dark-theme-simplicity' ); ?>
                </div>
                <p class="text-light-100/70 text-xs">
                    <?php esc_html_e( 'Add widgets to the Post Sidebar area in the WordPress dashboard.', 'dark-theme-simplicity' ); ?>
                </p>
            </div>
        </div>
        <?php
    }
    ?>
</div>