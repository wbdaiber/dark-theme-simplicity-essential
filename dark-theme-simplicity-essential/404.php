<?php get_header(); ?>

<div class="container">
    <div class="error-404 not-found text-center py-16 md:py-24">
        <div class="max-w-2xl mx-auto px-4">
            <h1 class="text-6xl md:text-8xl font-bold text-primary mb-6">
                404
            </h1>
            
            <h2 class="text-2xl md:text-3xl font-bold mb-4">
                <?php esc_html_e('Page Not Found', 'dark-theme-simplicity'); ?>
            </h2>
            
            <p class="text-gray-600 dark:text-gray-400 mb-8">
                <?php esc_html_e('The page you are looking for might have been removed, had its name changed, or is temporarily unavailable.', 'dark-theme-simplicity'); ?>
            </p>

            <div class="search-form mb-8">
                <?php get_search_form(); ?>
            </div>

            <div class="helpful-links">
                <h3 class="text-xl font-bold mb-4">
                    <?php esc_html_e('You might want to try:', 'dark-theme-simplicity'); ?>
                </h3>
                
                <ul class="space-y-2">
                    <li>
                        <a href="<?php echo esc_url(home_url('/')); ?>" class="text-primary hover:text-primary-dark">
                            <?php esc_html_e('Go to Homepage', 'dark-theme-simplicity'); ?>
                        </a>
                    </li>
                    <?php
                    // Get recent posts
                    $recent_posts = wp_get_recent_posts(array(
                        'numberposts' => 5,
                        'post_status' => 'publish'
                    ));
                    
                    if ($recent_posts) :
                        foreach ($recent_posts as $post) :
                    ?>
                        <li>
                            <a href="<?php echo esc_url(get_permalink($post['ID'])); ?>" class="text-primary hover:text-primary-dark">
                                <?php echo esc_html($post['post_title']); ?>
                            </a>
                        </li>
                    <?php
                        endforeach;
                        wp_reset_postdata();
                    endif;
                    ?>
                </ul>
            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?> 