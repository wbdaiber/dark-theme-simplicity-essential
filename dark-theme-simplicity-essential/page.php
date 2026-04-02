<?php get_header(); ?>

<?php 
// Check if sidebar widgets should be shown
$show_sidebar_widgets = get_post_meta(get_the_ID(), '_show_sidebar_widgets', true);
if ($show_sidebar_widgets === '') {
    $show_sidebar_widgets = get_theme_mod('dark_theme_simplicity_default_show_widgets', 'yes');
} else {
    // Convert from '1'/'0' format to 'yes'/'no' format
    $show_sidebar_widgets = ($show_sidebar_widgets === '1') ? 'yes' : 'no';
}

// Determine if we should show sidebar
$show_sidebar = ($show_sidebar_widgets === 'yes' && (is_active_sidebar('sidebar-page') || is_active_sidebar('sidebar-1')));
$content_class = $show_sidebar ? 'flex-1' : 'max-w-4xl mx-auto';

// Check if table of contents should be shown
$show_toc = get_post_meta(get_the_ID(), '_show_table_of_contents', true);
if ($show_toc === '') {
    $show_toc = '1'; // Default to showing TOC
}

// Check if share buttons should be shown
$show_share = get_post_meta(get_the_ID(), '_show_share_buttons', true);
if ($show_share === '') {
    $show_share = '1'; // Default to showing share buttons
}
?>

<div class="container mx-auto py-16">
    <div class="<?php echo $show_sidebar ? 'flex flex-col md:flex-row gap-8' : ''; ?>">
        <!-- Main content column -->
        <div class="<?php echo esc_attr($content_class); ?>">
            <?php while (have_posts()) : the_post(); ?>
                <article <?php post_class('page-content bg-dark-300 p-6 rounded-lg'); ?>>
                    <header class="entry-header mb-8">
                        <!-- Add breadcrumbs -->
                        <div class="page-breadcrumbs mb-6">
                            <a href="<?php echo esc_url(home_url('/')); ?>" class="hover:text-blue-300 transition-colors">Home</a>
                            <span class="separator">›</span>
                            <?php
                            // Get parent pages if they exist
                            $parent_id = wp_get_post_parent_id(get_the_ID());
                            if ($parent_id) {
                                $breadcrumbs = [];
                                while ($parent_id) {
                                    $parent_page = get_post($parent_id);
                                    $breadcrumbs[] = '<a href="' . esc_url(get_permalink($parent_page->ID)) . '" class="hover:text-blue-300 transition-colors">' . esc_html($parent_page->post_title) . '</a>';
                                    $parent_id = wp_get_post_parent_id($parent_page->ID);
                                }
                                // Output parent pages in reverse order (from highest level to current page's parent)
                                for ($i = count($breadcrumbs) - 1; $i >= 0; $i--) {
                                    echo $breadcrumbs[$i] . '<span class="separator mx-1 text-light-100/50">›</span>';
                                }
                            }
                            ?>
                            <span class="text-light-100/90"><?php the_title(); ?></span>
                        </div>

                        <?php if (has_post_thumbnail()) : ?>
                            <div class="page-thumbnail mb-6">
                                <?php the_post_thumbnail('large', ['class' => 'w-full h-auto rounded-lg']); ?>
                            </div>
                        <?php endif; ?>

                        <h1 class="entry-title text-3xl md:text-4xl font-bold mb-4 text-white">
                            <?php the_title(); ?>
                        </h1>
                    </header>

                    <?php if ($show_toc === '1' && !is_front_page() && !is_home()): ?>
                        <div class="table-of-contents bg-dark-100 p-4 mb-6 rounded-lg border border-white/10">
                            <div class="toc-heading flex items-center justify-between mb-3">
                                <h2 class="toc-title text-lg font-bold text-white"><?php _e('Table of Contents', 'dark-theme-simplicity'); ?></h2>
                            </div>
                            <div class="toc-content">
                                <?php 
                                // Auto-generate TOC based on h2 tags in content
                                $content = get_the_content();
                                $pattern = '/<h2[^>]*id=["\']([^"\']+)["\'][^>]*>(.*?)<\/h2>/i';
                                preg_match_all($pattern, $content, $matches);
                                
                                if (!empty($matches[0])) {
                                    echo '<ul class="pl-4 space-y-2 text-light-100/70">';
                                    for ($i = 0; $i < count($matches[0]); $i++) {
                                        $id = $matches[1][$i];
                                        $title = strip_tags($matches[2][$i]);
                                        echo '<li class="toc-item hover:text-light-100 transition-colors"><a href="#' . esc_attr($id) . '">' . esc_html($title) . '</a></li>';
                                    }
                                    echo '</ul>';
                                } else {
                                    echo '<p class="text-light-100/70">' . __('No headings found in this content.', 'dark-theme-simplicity') . '</p>';
                                }
                                ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="entry-content text-light-100/80">
                        <?php the_content(); ?>
                    </div>

                    <?php if ($show_share === '1'): ?>
                        <div class="share-buttons mt-8 pt-4 border-t border-white/10">
                            <h2 class="share-title text-lg font-bold text-white mb-3"><?php _e('Share This', 'dark-theme-simplicity'); ?></h2>
                            <div class="share-links flex space-x-4">
                                <!-- Twitter/X Share -->
                                <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode(get_permalink()); ?>&text=<?php echo urlencode(get_the_title()); ?>" class="inline-flex items-center text-light-100/70 hover:text-blue-300 transition-colors" target="_blank" rel="noopener noreferrer">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 mr-2">
                                        <path d="M18.244 2.25h3.308l-7.227 8.259 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                                    </svg>
                                    <span>X</span>
                                </a>
                                
                                <!-- Facebook Share -->
                                <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(get_permalink()); ?>" class="inline-flex items-center text-light-100/70 hover:text-blue-300 transition-colors" target="_blank" rel="noopener noreferrer">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 mr-2 text-[#1877F2]">
                                        <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                    </svg>
                                    <span>Facebook</span>
                                </a>
                                
                                <!-- LinkedIn Share -->
                                <a href="https://www.linkedin.com/sharing/share-offsite/?url=<?php echo urlencode(get_permalink()); ?>" class="inline-flex items-center text-light-100/70 hover:text-blue-300 transition-colors" target="_blank" rel="noopener noreferrer">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 mr-2 text-[#0A66C2]">
                                        <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                                    </svg>
                                    <span>LinkedIn</span>
                                </a>
                                
                                <!-- Email Share -->
                                <a href="mailto:?subject=<?php echo urlencode(get_the_title()); ?>&body=<?php echo urlencode(get_permalink()); ?>" class="inline-flex items-center text-light-100/70 hover:text-blue-300 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5 mr-2 text-blue-300">
                                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                                        <polyline points="22,6 12,13 2,6"></polyline>
                                    </svg>
                                    <span>Email</span>
                                </a>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php
                    // If comments are open or we have at least one comment, load up the comment template
                    if (comments_open() || get_comments_number()) :
                        comments_template();
                    endif;
                    ?>
                </article>
            <?php endwhile; ?>
        </div>
        
        <!-- Sidebar column - only show if widgets are enabled -->
        <?php if ($show_sidebar) : ?>
            <div class="md:w-64 lg:w-80 flex-shrink-0">
                <?php 
                // First sidebar wrapper - use sidebar-page if active, otherwise fallback to sidebar-1
                echo '<aside id="secondary" class="widget-area bg-dark-400 p-6 rounded-lg">';
                if (is_active_sidebar('sidebar-page')) {
                    dynamic_sidebar('sidebar-page');
                } else {
                    dynamic_sidebar('sidebar-1');
                }
                echo '</aside>';
                
                // Do not output a second sidebar - this prevents the duplicate sidebar issue
                ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php get_footer(); ?> 