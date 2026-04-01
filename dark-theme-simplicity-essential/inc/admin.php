<?php
/**
 * Admin functions for Dark Theme Simplicity
 * Includes meta boxes, admin notices, and quick edit functionality
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Add custom column to Posts table in admin to show Featured status
 */
function dark_theme_simplicity_custom_posts_columns($columns) {
    $new_columns = array();
    
    // Add columns up to 'title'
    foreach ($columns as $key => $value) {
        $new_columns[$key] = $value;
        if ($key == 'title') {
            // Add 'Featured' column after 'title'
            $new_columns['featured'] = __('Featured', 'dark-theme-simplicity');
        }
    }
    
    return $new_columns;
}
add_filter('manage_posts_columns', 'dark_theme_simplicity_custom_posts_columns');

/**
 * Add content to the custom column
 */
function dark_theme_simplicity_custom_posts_column_content($column_name, $post_id) {
    if ($column_name == 'featured') {
        $sticky_posts = get_option('sticky_posts');
        if (is_array($sticky_posts) && in_array($post_id, $sticky_posts)) {
            echo '<span style="color:#2271b1;"><span class="dashicons dashicons-star-filled"></span> ' . __('Featured', 'dark-theme-simplicity') . '</span>';
        } else {
            echo '—';
        }
    }
}
add_action('manage_posts_custom_column', 'dark_theme_simplicity_custom_posts_column_content', 10, 2);

/**
 * Add admin notices for features
 */
function dark_theme_simplicity_admin_notices() {
    // Only show to editors and admins
    if (!current_user_can('edit_others_posts')) {
        return;
    }
    
    // Check if the notice has been dismissed
    $dismissed = get_option('dark_theme_simplicity_featured_notice_dismissed', false);
    if (!$dismissed) {
        ?>
        <div class="notice notice-info is-dismissible" id="dark-theme-featured-notice">
            <p><strong><?php _e('Dark Theme Simplicity Tip:', 'dark-theme-simplicity'); ?></strong> 
            <?php _e('You can mark posts as "Featured" to highlight them on your blog homepage. Look for the "Feature" action in the quick edit menu or post edit screen.', 'dark-theme-simplicity'); ?></p>
            <button type="button" class="notice-dismiss-permanent" data-notice="featured">
                <span class="screen-reader-text"><?php _e('Dismiss this notice forever.', 'dark-theme-simplicity'); ?></span>
            </button>
        </div>
        <script>
            jQuery(document).ready(function($) {
                $(document).on('click', '.notice-dismiss-permanent', function() {
                    var notice = $(this).data('notice');
                    $.ajax({
                        url: ajaxurl,
                        type: 'POST',
                        data: {
                            action: 'dark_theme_simplicity_dismiss_notice',
                            notice: notice,
                            nonce: '<?php echo wp_create_nonce('dark_theme_simplicity_dismiss_notice'); ?>'
                        }
                    });
                    $('#dark-theme-featured-notice').hide();
                });
            });
        </script>
        <?php
    }
}
add_action('admin_notices', 'dark_theme_simplicity_admin_notices');

/**
 * Handle AJAX request to dismiss notices
 */
function dark_theme_simplicity_dismiss_featured_notice() {
    check_ajax_referer('dark_theme_simplicity_dismiss_notice', 'nonce');
    update_option('dark_theme_simplicity_featured_notice_dismissed', true);
    wp_die();
}
add_action('wp_ajax_dark_theme_simplicity_dismiss_notice', 'dark_theme_simplicity_dismiss_featured_notice');

/**
 * Add quick edit JavaScript to admin
 */
function dark_theme_simplicity_quick_edit_javascript() {
    global $current_screen;
    
    // Only load on post list screen
    if (!($current_screen && $current_screen->base === 'edit' && $current_screen->post_type === 'post')) {
        return;
    }
    
    // Get current sticky posts
    $sticky_posts = get_option('sticky_posts');
    if (!is_array($sticky_posts)) {
        $sticky_posts = array();
    }
    
    // Output inline script
    ?>
    <script type="text/javascript">
    jQuery(document).ready(function($) {
        // Add our custom action to the list of bulk actions
        $('.tablenav.top .bulkactions select, .tablenav.bottom .bulkactions select').append(
            '<option value="feature"><?php esc_html_e('Feature', 'dark-theme-simplicity'); ?></option>' +
            '<option value="unfeature"><?php esc_html_e('Unfeature', 'dark-theme-simplicity'); ?></option>'
        );
        
        // Add featured toggle to row actions
        $('table.wp-list-table.posts tr').each(function() {
            var id = $(this).attr('id');
            if (!id) return;
            
            var post_id = id.replace('post-', '');
            var is_sticky = <?php echo json_encode($sticky_posts); ?>.includes(parseInt(post_id));
            
            var html = is_sticky ?
                '<span class="unfeature"><a href="#" data-id="' + post_id + '"><?php esc_html_e('Unfeature', 'dark-theme-simplicity'); ?></a> |</span>' :
                '<span class="feature"><a href="#" data-id="' + post_id + '"><?php esc_html_e('Feature', 'dark-theme-simplicity'); ?></a> |</span>';
            
            $(this).find('.row-actions .trash').before(html);
        });
        
        // Handle feature/unfeature links
        $(document).on('click', '.row-actions .feature a, .row-actions .unfeature a', function(e) {
            e.preventDefault();
            var post_id = $(this).data('id');
            var action = $(this).parent().hasClass('feature') ? 'feature' : 'unfeature';
            
            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'dark_theme_simplicity_toggle_featured',
                    post_id: post_id,
                    featured: action === 'feature',
                    nonce: '<?php echo wp_create_nonce('dark_theme_simplicity_toggle_featured'); ?>'
                },
                success: function(response) {
                    if (response.success) {
                        location.reload();
                    }
                }
            });
        });
    });
    </script>
    <?php
}
add_action('admin_footer', 'dark_theme_simplicity_quick_edit_javascript');

/**
 * Handle AJAX request to toggle featured status
 */
function dark_theme_simplicity_toggle_featured() {
    check_ajax_referer('dark_theme_simplicity_toggle_featured', 'nonce');
    
    $post_id = isset($_POST['post_id']) ? intval($_POST['post_id']) : 0;
    $featured = isset($_POST['featured']) ? (bool) $_POST['featured'] : false;
    
    if (!$post_id || !current_user_can('edit_post', $post_id)) {
        wp_send_json_error(array('message' => __('Permission denied or invalid post.', 'dark-theme-simplicity')));
        return;
    }
    
    $sticky_posts = get_option('sticky_posts');
    if (!is_array($sticky_posts)) {
        $sticky_posts = array();
    }
    
    if ($featured) {
        // Add to sticky posts if not already there
        if (!in_array($post_id, $sticky_posts)) {
            $sticky_posts[] = $post_id;
            update_option('sticky_posts', $sticky_posts);
        }
    } else {
        // Remove from sticky posts
        $sticky_posts = array_diff($sticky_posts, array($post_id));
        update_option('sticky_posts', $sticky_posts);
    }
    
    wp_send_json_success(array(
        'message' => $featured ? __('Post featured.', 'dark-theme-simplicity') : __('Post unfeatured.', 'dark-theme-simplicity'),
        'featured' => $featured
    ));
}
add_action('wp_ajax_dark_theme_simplicity_toggle_featured', 'dark_theme_simplicity_toggle_featured');

/**
 * Social menu notice
 */
function dark_theme_simplicity_social_menu_notice() {
    // Only show to editors and admins
    if (!current_user_can('edit_theme_options')) {
        return;
    }
    
    // Check if the notice has been dismissed
    $dismissed = get_option('dark_theme_simplicity_social_notice_dismissed', false);
    if (!$dismissed) {
        ?>
        <div class="notice notice-info is-dismissible" id="dark-theme-social-notice">
            <p><strong><?php _e('Dark Theme Simplicity Tip:', 'dark-theme-simplicity'); ?></strong> 
            <?php _e('Set up your social media links by creating a "Social Menu" with custom links to your profiles.', 'dark-theme-simplicity'); ?></p>
            <p><?php printf(__('Go to <a href="%s">Appearance → Menus</a> to create a new menu and assign it to the "Social Menu" location.', 'dark-theme-simplicity'), admin_url('nav-menus.php')); ?></p>
            <p><?php printf(__('Alternatively, you can add your LinkedIn and Twitter/X URLs in the <a href="%s">Theme Customizer</a>.', 'dark-theme-simplicity'), admin_url('customize.php?autofocus[section]=dark_theme_simplicity_social_links')); ?></p>
            <button type="button" class="notice-dismiss-permanent" data-notice="social">
                <span class="screen-reader-text"><?php _e('Dismiss this notice forever.', 'dark-theme-simplicity'); ?></span>
            </button>
        </div>
        <script>
            jQuery(document).ready(function($) {
                $(document).on('click', '.notice-dismiss-permanent', function() {
                    var notice = $(this).data('notice');
                    if (notice === 'social') {
                        $.ajax({
                            url: ajaxurl,
                            type: 'POST',
                            data: {
                                action: 'dark_theme_simplicity_dismiss_social_notice',
                                nonce: '<?php echo wp_create_nonce('dark_theme_simplicity_dismiss_social_notice'); ?>'
                            }
                        });
                        $('#dark-theme-social-notice').hide();
                    }
                });
            });
        </script>
        <?php
    }
}
add_action('admin_notices', 'dark_theme_simplicity_social_menu_notice');

/**
 * Handle AJAX request to dismiss social menu notice
 */
function dark_theme_simplicity_dismiss_social_notice() {
    check_ajax_referer('dark_theme_simplicity_dismiss_social_notice', 'nonce');
    update_option('dark_theme_simplicity_social_notice_dismissed', true);
    wp_die();
}
add_action('wp_ajax_dark_theme_simplicity_dismiss_social_notice', 'dark_theme_simplicity_dismiss_social_notice');

/**
 * Add meta boxes for pages only
 */
function dark_theme_simplicity_add_page_meta_boxes() {
    add_meta_box(
        'dark_theme_simplicity_page_settings',
        __('Page Display Settings', 'dark-theme-simplicity'),
        'dark_theme_simplicity_page_settings_callback',
        'page',
        'side',
        'default'
    );
}
add_action('add_meta_boxes', 'dark_theme_simplicity_add_page_meta_boxes');

/**
 * Page Settings Meta Box callback
 */
function dark_theme_simplicity_page_settings_callback($post) {
    wp_nonce_field('dark_theme_simplicity_page_settings', 'dark_theme_simplicity_page_settings_nonce');
    
    // Get current values
    $show_widgets = get_post_meta($post->ID, '_show_sidebar_widgets', true);
    $show_toc = get_post_meta($post->ID, '_show_table_of_contents', true);
    $show_share = get_post_meta($post->ID, '_show_share_buttons', true);
    $custom_excerpt = get_post_meta($post->ID, '_custom_excerpt', true);
    
    // Set defaults
    if ($show_widgets === '') $show_widgets = '1';
    if ($show_toc === '') $show_toc = '1';
    if ($show_share === '') $show_share = '1';
    
    // Check if this is a tool page (has Tools Hub template or is child of Tools Hub page)
    $page_template = get_page_template_slug($post->ID);
    $is_tool_page = ($page_template === 'page-templates/tools-template.php');
    
    // Check if parent has Tools Hub template
    if (!$is_tool_page && $post->post_parent > 0) {
        $parent_template = get_page_template_slug($post->post_parent);
        $is_tool_page = ($parent_template === 'page-templates/tools-template.php');
    }
    ?>
    
    <table class="form-table">
        <?php if ($is_tool_page) : ?>
        <tr>
            <td colspan="2">
                <label for="custom_excerpt">
                    <strong><?php _e('Custom Excerpt for Tools Hub', 'dark-theme-simplicity'); ?></strong>
                </label>
                <textarea 
                    id="custom_excerpt" 
                    name="custom_excerpt" 
                    rows="3" 
                    cols="50" 
                    style="width: 100%; margin-top: 5px;"
                    placeholder="<?php _e('Enter a custom description that will appear in the tools grid...', 'dark-theme-simplicity'); ?>"
                ><?php echo esc_textarea($custom_excerpt); ?></textarea>
                <p class="description">
                    <?php _e('This custom excerpt will be displayed in the tools grid. If empty, the WordPress excerpt or trimmed content will be used instead.', 'dark-theme-simplicity'); ?>
                    <br><span id="excerpt-char-count" style="color: #666;">0 characters</span>
                </p>
            </td>
        </tr>
        <?php endif; ?>
        <tr>
            <td>
                <label>
                    <input type="checkbox" name="show_sidebar_widgets" value="1" <?php checked($show_widgets, '1'); ?> />
                    <?php _e('Show Sidebar Widgets', 'dark-theme-simplicity'); ?>
                </label>
            </td>
        </tr>
        <tr>
            <td>
                <label>
                    <input type="checkbox" name="show_table_of_contents" value="1" <?php checked($show_toc, '1'); ?> />
                    <?php _e('Show Table of Contents', 'dark-theme-simplicity'); ?>
                </label>
            </td>
        </tr>
        <tr>
            <td>
                <label>
                    <input type="checkbox" name="show_share_buttons" value="1" <?php checked($show_share, '1'); ?> />
                    <?php _e('Show Share Buttons', 'dark-theme-simplicity'); ?>
                </label>
            </td>
        </tr>
    </table>
    
    <?php if ($is_tool_page) : ?>
    <script>
    jQuery(document).ready(function($) {
        var textArea = $('#custom_excerpt');
        var charCount = $('#excerpt-char-count');
        
        function updateCharCount() {
            var count = textArea.val().length;
            charCount.text(count + ' characters');
            
            if (count > 200) {
                charCount.css('color', '#d63638');
            } else if (count > 150) {
                charCount.css('color', '#dba617');
            } else {
                charCount.css('color', '#666');
            }
        }
        
        textArea.on('input', updateCharCount);
        updateCharCount(); // Initialize count
    });
    </script>
    <?php endif; ?>
    
    <?php
}

/**
 * Save page settings meta box data
 */
function dark_theme_simplicity_save_page_settings($post_id) {
    // Security checks
    if (!isset($_POST['dark_theme_simplicity_page_settings_nonce'])) {
        return;
    }
    
    if (!wp_verify_nonce($_POST['dark_theme_simplicity_page_settings_nonce'], 'dark_theme_simplicity_page_settings')) {
        return;
    }
    
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    
    if (!current_user_can('edit_page', $post_id)) {
        return;
    }
    
    // Save the settings
    $show_widgets = isset($_POST['show_sidebar_widgets']) ? '1' : '0';
    $show_toc = isset($_POST['show_table_of_contents']) ? '1' : '0';
    $show_share = isset($_POST['show_share_buttons']) ? '1' : '0';
    
    update_post_meta($post_id, '_show_sidebar_widgets', $show_widgets);
    update_post_meta($post_id, '_show_table_of_contents', $show_toc);
    update_post_meta($post_id, '_show_share_buttons', $show_share);
    
    // Save custom excerpt if provided
    if (isset($_POST['custom_excerpt'])) {
        $custom_excerpt = sanitize_textarea_field($_POST['custom_excerpt']);
        update_post_meta($post_id, '_custom_excerpt', $custom_excerpt);
    }
}
add_action('save_post', 'dark_theme_simplicity_save_page_settings');

/**
 * Add meta boxes for posts (Blog Post Display Options and Related Posts)
 */
function dark_theme_simplicity_add_post_meta_boxes() {
    add_meta_box(
        'dark_theme_simplicity_post_options',
        __('Post Display Options', 'dark-theme-simplicity'),
        'dark_theme_simplicity_post_options_callback',
        'post',
        'side',
        'default'
    );
    
    add_meta_box(
        'dark_theme_simplicity_related_posts',
        __('Related Posts', 'dark-theme-simplicity'),
        'dark_theme_simplicity_related_posts_callback',
        'post',
        'normal',
        'default'
    );
}
add_action('add_meta_boxes', 'dark_theme_simplicity_add_post_meta_boxes');

/**
 * Post options meta box callback
 */
function dark_theme_simplicity_post_options_callback($post) {
    wp_nonce_field('dark_theme_simplicity_post_options_nonce', 'dark_theme_simplicity_post_options_nonce');
    
    // Get current values
    $show_toc = get_post_meta($post->ID, '_show_table_of_contents', true);
    $show_share = get_post_meta($post->ID, '_show_share_buttons', true);
    $show_widgets = get_post_meta($post->ID, '_show_sidebar_widgets', true);
    
    // Get defaults from customizer
    $default_toc = get_theme_mod('dark_theme_simplicity_default_show_toc', 'yes');
    $default_share = get_theme_mod('dark_theme_simplicity_default_show_share', 'yes');
    $default_widgets = get_theme_mod('dark_theme_simplicity_default_show_widgets', 'yes');
    ?>
    <table class="form-table">
        <tr>
            <td>
                <label for="show_table_of_contents"><?php _e('Table of Contents', 'dark-theme-simplicity'); ?></label>
                <select name="show_table_of_contents" id="show_table_of_contents" style="width: 100%;">
                    <option value="" <?php selected($show_toc, ''); ?>><?php printf(__('Default (%s)', 'dark-theme-simplicity'), ucfirst($default_toc)); ?></option>
                    <option value="yes" <?php selected($show_toc, 'yes'); ?>><?php _e('Show', 'dark-theme-simplicity'); ?></option>
                    <option value="no" <?php selected($show_toc, 'no'); ?>><?php _e('Hide', 'dark-theme-simplicity'); ?></option>
                </select>
            </td>
        </tr>
        <tr>
            <td>
                <label for="show_share_buttons"><?php _e('Share Buttons', 'dark-theme-simplicity'); ?></label>
                <select name="show_share_buttons" id="show_share_buttons" style="width: 100%;">
                    <option value="" <?php selected($show_share, ''); ?>><?php printf(__('Default (%s)', 'dark-theme-simplicity'), ucfirst($default_share)); ?></option>
                    <option value="yes" <?php selected($show_share, 'yes'); ?>><?php _e('Show', 'dark-theme-simplicity'); ?></option>
                    <option value="no" <?php selected($show_share, 'no'); ?>><?php _e('Hide', 'dark-theme-simplicity'); ?></option>
                </select>
            </td>
        </tr>
        <tr>
            <td>
                <label for="show_sidebar_widgets"><?php _e('Sidebar Widgets', 'dark-theme-simplicity'); ?></label>
                <select name="show_sidebar_widgets" id="show_sidebar_widgets" style="width: 100%;">
                    <option value="" <?php selected($show_widgets, ''); ?>><?php printf(__('Default (%s)', 'dark-theme-simplicity'), ucfirst($default_widgets)); ?></option>
                    <option value="yes" <?php selected($show_widgets, 'yes'); ?>><?php _e('Show', 'dark-theme-simplicity'); ?></option>
                    <option value="no" <?php selected($show_widgets, 'no'); ?>><?php _e('Hide', 'dark-theme-simplicity'); ?></option>
                </select>
            </td>
        </tr>
    </table>
    <?php
}

/**
 * Related posts meta box callback
 */
function dark_theme_simplicity_related_posts_callback($post) {
    wp_nonce_field('dark_theme_simplicity_related_posts_nonce', 'dark_theme_simplicity_related_posts_nonce');
    
    $related_posts = get_post_meta($post->ID, '_related_posts', true);
    if (!is_array($related_posts)) {
        $related_posts = array();
    }
    
    // Get all published posts except current one
    $all_posts = get_posts(array(
        'post_type' => 'post',
        'post_status' => 'publish',
        'numberposts' => -1,
        'exclude' => array($post->ID),
        'orderby' => 'title',
        'order' => 'ASC'
    ));
    ?>
    <div id="related-posts-selector">
        <p><?php _e('Select up to 3 related posts to display. If none are selected, the system will automatically show related posts from the same category.', 'dark-theme-simplicity'); ?></p>
        
        <div style="max-height: 300px; overflow-y: auto; border: 1px solid #ddd; padding: 10px; background: #f9f9f9;">
            <?php foreach ($all_posts as $available_post) : ?>
                <label style="display: block; margin-bottom: 8px;">
                    <input type="checkbox" 
                           name="related_posts[]" 
                           value="<?php echo $available_post->ID; ?>"
                           <?php checked(in_array($available_post->ID, $related_posts)); ?>
                           class="related-post-checkbox" />
                    <?php echo esc_html($available_post->post_title); ?>
                    <small style="color: #666; display: block; margin-left: 20px;">
                        <?php echo get_the_date('Y-m-d', $available_post->ID); ?> | 
                        <?php 
                        $categories = get_the_category($available_post->ID);
                        if ($categories) {
                            echo esc_html($categories[0]->name);
                        }
                        ?>
                    </small>
                </label>
            <?php endforeach; ?>
        </div>
        
        <p class="description">
            <strong><?php _e('Selected:', 'dark-theme-simplicity'); ?></strong> 
            <span id="selected-count"><?php echo count($related_posts); ?></span>/3
        </p>
    </div>
    
    <script>
    jQuery(document).ready(function($) {
        function updateSelectedCount() {
            var count = $('.related-post-checkbox:checked').length;
            $('#selected-count').text(count);
            
            if (count >= 3) {
                $('.related-post-checkbox:not(:checked)').prop('disabled', true);
            } else {
                $('.related-post-checkbox').prop('disabled', false);
            }
        }
        
        $('.related-post-checkbox').on('change', updateSelectedCount);
        updateSelectedCount();
    });
    </script>
    <?php
}

/**
 * Save post meta boxes data (extends the existing save_post action)
 */
function dark_theme_simplicity_save_post_meta($post_id) {
    // Only process for posts
    if (get_post_type($post_id) !== 'post') {
        return;
    }
    
    // Check if our nonces are valid for post options
    if (isset($_POST['dark_theme_simplicity_post_options_nonce']) && 
        wp_verify_nonce($_POST['dark_theme_simplicity_post_options_nonce'], 'dark_theme_simplicity_post_options_nonce')) {
        
        // Check if user has permission to edit this post
        if (current_user_can('edit_post', $post_id) && !defined('DOING_AUTOSAVE')) {
            // Save post options
            if (isset($_POST['show_table_of_contents'])) {
                update_post_meta($post_id, '_show_table_of_contents', sanitize_text_field($_POST['show_table_of_contents']));
            }
            
            if (isset($_POST['show_share_buttons'])) {
                update_post_meta($post_id, '_show_share_buttons', sanitize_text_field($_POST['show_share_buttons']));
            }
            
            if (isset($_POST['show_sidebar_widgets'])) {
                update_post_meta($post_id, '_show_sidebar_widgets', sanitize_text_field($_POST['show_sidebar_widgets']));
            }
        }
    }
    
    // Check if our nonces are valid for related posts
    if (isset($_POST['dark_theme_simplicity_related_posts_nonce']) && 
        wp_verify_nonce($_POST['dark_theme_simplicity_related_posts_nonce'], 'dark_theme_simplicity_related_posts_nonce')) {
        
        // Check if user has permission to edit this post
        if (current_user_can('edit_post', $post_id) && !defined('DOING_AUTOSAVE')) {
            // Save related posts
            if (isset($_POST['related_posts']) && is_array($_POST['related_posts'])) {
                $related_posts = array_map('intval', $_POST['related_posts']);
                // Limit to 3 posts
                $related_posts = array_slice($related_posts, 0, 3);
                update_post_meta($post_id, '_related_posts', $related_posts);
            } else {
                delete_post_meta($post_id, '_related_posts');
            }
        }
    }
}
add_action('save_post', 'dark_theme_simplicity_save_post_meta');