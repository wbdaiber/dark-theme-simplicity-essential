<?php
/**
 * Custom shortcodes for Dark Theme Simplicity
 *
 * @package Dark_Theme_Simplicity
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Shortcode to display pages in a grid or list layout
 * 
 * Usage: [display_pages number=3 parent=123 exclude="45,67" orderby="menu_order" order="ASC" layout="grid" columns=3]
 * 
 * @param array $atts Shortcode attributes
 * @return string HTML output
 */
function dark_theme_simplicity_display_pages_shortcode($atts) {
    $atts = shortcode_atts(array(
        'number' => -1,         // Number of pages to display, -1 for all
        'parent' => 0,          // Parent page ID (0 for top level)
        'exclude' => '',        // Comma-separated IDs to exclude
        'orderby' => 'menu_order', // Order by field (menu_order, title, date, modified, etc.)
        'order' => 'ASC',       // ASC or DESC
        'layout' => 'grid',     // grid or list
        'columns' => 3,         // 1-4 columns for grid layout
        'show_excerpt' => true, // Show excerpt
        'show_date' => true,    // Show modified date
        'cta_text' => 'Read More', // Call to action text
    ), $atts, 'display_pages');
    
    // Sanitize attributes
    $number = intval($atts['number']);
    $parent = intval($atts['parent']);
    $exclude_ids = sanitize_text_field($atts['exclude']);
    $orderby = sanitize_key($atts['orderby']);
    $order = in_array(strtoupper($atts['order']), array('ASC', 'DESC')) ? strtoupper($atts['order']) : 'ASC';
    $layout = in_array($atts['layout'], array('grid', 'list')) ? $atts['layout'] : 'grid';
    $columns = min(max(intval($atts['columns']), 1), 4); // Between 1 and 4
    $show_excerpt = filter_var($atts['show_excerpt'], FILTER_VALIDATE_BOOLEAN);
    $show_date = filter_var($atts['show_date'], FILTER_VALIDATE_BOOLEAN);
    $cta_text = sanitize_text_field($atts['cta_text']);
    
    // Convert exclude to array
    $exclude_array = !empty($exclude_ids) ? explode(',', $exclude_ids) : array();
    
    // Query args
    $args = array(
        'post_type' => 'page',
        'posts_per_page' => $number,
        'post_parent' => $parent,
        'post__not_in' => $exclude_array,
        'orderby' => $orderby,
        'order' => $order,
    );
    
    // Get the pages
    $pages_query = new WP_Query($args);
    
    // Start output buffer
    ob_start();
    
    if ($pages_query->have_posts()) {
        // Grid layout CSS classes
        $grid_classes = 'grid gap-6 mb-8';
        switch ($columns) {
            case 1:
                $grid_classes .= ' grid-cols-1';
                break;
            case 2:
                $grid_classes .= ' grid-cols-1 md:grid-cols-2';
                break;
            case 3:
                $grid_classes .= ' grid-cols-1 md:grid-cols-2 lg:grid-cols-3';
                break;
            case 4:
                $grid_classes .= ' grid-cols-1 md:grid-cols-2 lg:grid-cols-4';
                break;
        }
        
        if ($layout === 'grid') {
            echo '<div class="' . $grid_classes . '">';
        } else {
            echo '<div class="space-y-6 mb-8">';
        }
        
        while ($pages_query->have_posts()) {
            $pages_query->the_post();
            
            // Get the excerpt or generate one from content
            $excerpt = get_the_excerpt();
            if (empty($excerpt)) {
                $excerpt = wp_trim_words(get_the_content(), 20, '...');
            }
            
            if ($layout === 'grid') {
                // Grid item
                ?>
                <div class="overflow-hidden border border-white/10 backdrop-blur-lg bg-dark-100/50 rounded-xl transition-all duration-300 hover:bg-dark-100">
                    <div class="aspect-video bg-gradient-to-tr from-blue-300/20 to-purple-300/20">
                        <?php if (has_post_thumbnail()) : ?>
                            <a href="<?php the_permalink(); ?>">
                                <?php the_post_thumbnail('medium', ['class' => 'object-cover w-full h-full opacity-60']); ?>
                            </a>
                        <?php else: ?>
                            <a href="<?php the_permalink(); ?>" class="w-full h-full block">
                                <div class="w-full h-full bg-gradient-to-tr from-blue-300/20 to-purple-300/20"></div>
                            </a>
                        <?php endif; ?>
                    </div>
                    <div class="p-6">
                        <?php if ($show_date) : ?>
                            <div class="flex gap-2 mb-3 items-center">
                                <span class="text-xs text-light-100/50 flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Last Updated: <?php echo get_the_modified_date(); ?>
                                </span>
                            </div>
                        <?php endif; ?>
                        <h3 class="text-xl font-bold mb-2 line-clamp-2">
                            <a href="<?php the_permalink(); ?>" class="text-white hover:text-blue-300 transition-colors">
                                <?php the_title(); ?>
                            </a>
                        </h3>
                        <?php if ($show_excerpt) : ?>
                            <p class="text-light-100/70 line-clamp-3 mb-4">
                                <?php echo $excerpt; ?>
                            </p>
                        <?php endif; ?>
                        <a href="<?php the_permalink(); ?>" class="inline-flex items-center text-blue-300 hover:text-blue-400 transition-colors">
                            <?php echo $cta_text; ?>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </a>
                    </div>
                </div>
                <?php
            } else {
                // List item
                ?>
                <div class="flex flex-col md:flex-row gap-6 border border-white/10 backdrop-blur-lg bg-dark-100/50 rounded-xl p-6 transition-all duration-300 hover:bg-dark-100">
                    <?php if (has_post_thumbnail()) : ?>
                        <div class="md:w-1/3 lg:w-1/4">
                            <a href="<?php the_permalink(); ?>" class="block">
                                <?php the_post_thumbnail('medium', ['class' => 'w-full h-auto rounded-lg opacity-80']); ?>
                            </a>
                        </div>
                    <?php endif; ?>
                    <div class="<?php echo has_post_thumbnail() ? 'md:w-2/3 lg:w-3/4' : 'w-full'; ?>">
                        <h3 class="text-xl font-bold mb-2">
                            <a href="<?php the_permalink(); ?>" class="text-white hover:text-blue-300 transition-colors">
                                <?php the_title(); ?>
                            </a>
                        </h3>
                        <?php if ($show_date) : ?>
                            <div class="mb-2">
                                <span class="text-xs text-light-100/50 flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Last Updated: <?php echo get_the_modified_date(); ?>
                                </span>
                            </div>
                        <?php endif; ?>
                        <?php if ($show_excerpt) : ?>
                            <p class="text-light-100/70 mb-4">
                                <?php echo $excerpt; ?>
                            </p>
                        <?php endif; ?>
                        <a href="<?php the_permalink(); ?>" class="inline-flex items-center text-blue-300 hover:text-blue-400 transition-colors">
                            <?php echo $cta_text; ?>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </a>
                    </div>
                </div>
                <?php
            }
        }
        
        echo '</div>';
    } else {
        echo '<div class="bg-dark-300 p-8 rounded-xl border border-white/10">';
        echo '<p class="text-center text-light-100/70 text-lg">No pages found.</p>';
        echo '</div>';
    }
    
    // Reset post data
    wp_reset_postdata();
    
    // Return the buffer
    return ob_get_clean();
}
add_shortcode('display_pages', 'dark_theme_simplicity_display_pages_shortcode');

/**
 * Simple shortcode to display a styled "coming soon" notice
 * 
 * Usage: [coming_soon text="Feature launching next week" color="blue"]
 * 
 * @param array $atts Shortcode attributes
 * @return string HTML output
 */
function dark_theme_simplicity_coming_soon_shortcode($atts) {
    $atts = shortcode_atts(array(
        'text' => 'Coming Soon',
        'color' => 'blue', // blue, green, yellow, red, purple
    ), $atts, 'coming_soon');
    
    $text = sanitize_text_field($atts['text']);
    $color = sanitize_key($atts['color']);
    
    // Set colors based on selection
    switch ($color) {
        case 'green':
            $bg_class = 'bg-green-300/10';
            $border_class = 'border-green-300/20';
            $text_class = 'text-green-300';
            break;
        case 'yellow':
            $bg_class = 'bg-yellow-300/10';
            $border_class = 'border-yellow-300/20';
            $text_class = 'text-yellow-300';
            break;
        case 'red':
            $bg_class = 'bg-red-300/10';
            $border_class = 'border-red-300/20';
            $text_class = 'text-red-300';
            break;
        case 'purple':
            $bg_class = 'bg-purple-300/10';
            $border_class = 'border-purple-300/20';
            $text_class = 'text-purple-300';
            break;
        default: // blue
            $bg_class = 'bg-blue-300/10';
            $border_class = 'border-blue-300/20';
            $text_class = 'text-blue-300';
    }
    
    return '<div class="my-6 p-4 rounded-lg ' . $bg_class . ' border ' . $border_class . '">
        <p class="flex items-center ' . $text_class . ' font-medium">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
            </svg>
            ' . $text . '
        </p>
    </div>';
}
add_shortcode('coming_soon', 'dark_theme_simplicity_coming_soon_shortcode'); 