<?php
// Check if this file is being accessed directly (not through WordPress)
if (!defined('ABSPATH')) {
    // Redirect to the explanation page
    header('Location: index.html');
    exit;
}

// If accessed through WordPress, behave like a normal index.php
get_header();
?>

<main id="content" class="site-main bg-dark-200">
    <!-- Hero Section -->
    <?php
    // Get customizer settings
    $hero_padding = get_theme_mod('dark_theme_simplicity_blog_hero_padding', 'medium');
    $hero_alignment = get_theme_mod('dark_theme_simplicity_blog_hero_alignment', 'left');
    
    // Set padding classes based on customizer setting
    $padding_classes = 'py-12 md:py-16'; // Small (default fallback)
    if ($hero_padding === 'medium') {
        $padding_classes = 'py-16 md:py-24';
    } elseif ($hero_padding === 'large') {
        $padding_classes = 'py-20 md:py-32';
    } elseif ($hero_padding === 'extra-large') {
        $padding_classes = 'py-24 md:py-40';
    }
    
    // Set alignment classes based on customizer setting
    $alignment_classes = 'text-left'; // Left (default fallback)
    $container_classes = '';
    if ($hero_alignment === 'center') {
        $alignment_classes = 'text-center';
        $container_classes = 'mx-auto';
    } elseif ($hero_alignment === 'right') {
        $alignment_classes = 'text-right';
        $container_classes = 'ml-auto';
    }
    ?>
    <section class="<?php echo esc_attr($padding_classes); ?> bg-dark-300 blog-hero-section">
        <div class="container mx-auto">
            <div class="max-w-5xl <?php echo esc_attr($container_classes); ?> <?php echo esc_attr($alignment_classes); ?>">
                <h1 class="text-4xl md:text-6xl font-bold tracking-tight mb-6 text-white reveal-text">
                    <?php 
                    if (is_home() && !is_front_page()) {
                        single_post_title();
                    } elseif (is_archive()) {
                        the_archive_title();
                    } elseif (is_search()) {
                        printf(esc_html__('Search Results for: %s', 'dark-theme-simplicity'), '<span>' . get_search_query() . '</span>');
                    } else {
                        echo esc_html(get_theme_mod('dark_theme_simplicity_blog_hero_title', 'Insights & Resources'));
                    }
                    ?>
                </h1>
                <p class="text-xl md:text-2xl max-w-3xl <?php echo $hero_alignment === 'center' ? 'mx-auto' : ($hero_alignment === 'right' ? 'ml-auto' : ''); ?> reveal-text blog-hero-description" style="color: <?php echo esc_attr($desc_color); ?> !important; opacity: <?php echo esc_attr($desc_opacity_decimal); ?> !important;">
                    <?php echo esc_html(get_theme_mod('dark_theme_simplicity_blog_hero_description', 'The latest tools, trends, and strategies to elevate your digital presence and maximize your business growth.')); ?>
                </p>
                <?php if (is_archive() && get_the_archive_description()) : ?>
                    <div class="archive-description text-xl text-light-100/70 mt-4 reveal-text">
                        <?php the_archive_description(); ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <?php 
    // Output custom CSS for blog hero background and colors
    $blog_hero_bg = get_theme_mod('dark_theme_simplicity_blog_hero_bg_image', '');
    $blog_hero_overlay_opacity = get_theme_mod('dark_theme_simplicity_blog_hero_overlay_opacity', 70);
    $overlay_opacity_decimal = $blog_hero_overlay_opacity / 100;
    $title_color = get_theme_mod('dark_theme_simplicity_blog_hero_title_color', '#ffffff');
    $desc_color = get_theme_mod('dark_theme_simplicity_blog_hero_desc_color', '#ffffff');
    $desc_opacity = get_theme_mod('dark_theme_simplicity_blog_hero_desc_opacity', 70);
    $desc_opacity_decimal = $desc_opacity / 100;
    ?>
    <style>
        .blog-hero-section {
            position: relative;
            background-color: #121214; /* Base dark color */
            <?php if (!empty($blog_hero_bg)) : ?>
            background-image: url('<?php echo esc_url($blog_hero_bg); ?>');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            <?php endif; ?>
        }
        
        <?php if (!empty($blog_hero_bg)) : ?>
        .blog-hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(18, 18, 20, <?php echo esc_attr($overlay_opacity_decimal); ?>);
            z-index: 1;
        }
        
        .blog-hero-section .container {
            position: relative;
            z-index: 2;
        }
        <?php endif; ?>
        
        .blog-hero-section h1 {
            color: <?php echo esc_attr($title_color); ?> !important;
        }
        
        .blog-hero-description {
            color: <?php echo esc_attr($desc_color); ?> !important;
            opacity: <?php echo esc_attr($desc_opacity_decimal); ?> !important;
        }
    </style>

    <?php 
    // Featured Posts Section
    // Only display on main blog page, not on archives or search results
    if (is_home() && !is_paged()) : 
        // Get sticky posts or recent posts if no sticky posts
        $featured_posts = get_option('sticky_posts');
        
        // Show admin notice if no featured posts are set and user can edit posts
        if (empty($featured_posts) && current_user_can('edit_posts')) : 
    ?>
        <div class="container mx-auto py-4">
            <div class="bg-blue-300/20 border-l-4 border-blue-300 text-light-100 p-4 rounded admin-notice">
                <p>
                    <strong><?php _e('Admin Notice:', 'dark-theme-simplicity'); ?></strong> 
                    <?php _e('No posts are currently set as featured. The section below shows recent posts instead. To feature posts, go to the', 'dark-theme-simplicity'); ?>
                    <a href="<?php echo admin_url('edit.php'); ?>" class="text-blue-300 hover:underline"><?php _e('Posts screen', 'dark-theme-simplicity'); ?></a>
                    <?php _e('and click "Edit" on a post. Check the "Stick this post to the front page" option in the "Visibility" section under "Publish" settings.', 'dark-theme-simplicity'); ?>
                </p>
            </div>
        </div>
    <?php
        endif;
        
        // Feature query setup
        if (empty($featured_posts)) {
            // If no sticky posts, get the 3 most recent
            $featured_query = new WP_Query(array(
                'posts_per_page' => 3,
                'ignore_sticky_posts' => 1
            ));
        } else {
            // If sticky posts exist, get up to 3 of them
            $featured_query = new WP_Query(array(
                'posts_per_page' => 3,
                'post__in' => $featured_posts,
                'ignore_sticky_posts' => 1
            ));
            
            // If we don't have enough sticky posts, get additional recent posts
            if ($featured_query->post_count < 3) {
                $additional_count = 3 - $featured_query->post_count;
                $additional_query = new WP_Query(array(
                    'posts_per_page' => $additional_count,
                    'post__not_in' => $featured_posts,
                    'ignore_sticky_posts' => 1
                ));
                
                // Merge the queries
                $featured_query->posts = array_merge($featured_query->posts, $additional_query->posts);
                $featured_query->post_count = count($featured_query->posts);
            }
        }
        
        if ($featured_query->have_posts()) : 
    ?>
    <!-- Featured Articles Section -->
    <section class="py-16 bg-black">
        <div class="container mx-auto">
            <div class="flex justify-between items-center mb-10">
                <h2 class="text-3xl md:text-4xl font-bold text-white">
                    <?php echo empty($featured_posts) ? esc_html__('Recent Articles', 'dark-theme-simplicity') : esc_html__('Featured Articles', 'dark-theme-simplicity'); ?>
                </h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <?php while ($featured_query->have_posts()) : $featured_query->the_post(); 
                    // Get the first category
                    $categories = get_the_category();
                    $category_name = !empty($categories) ? esc_html($categories[0]->name) : '';
                    
                    // Check if this post is sticky/featured
                    $is_sticky = is_array($featured_posts) && in_array(get_the_ID(), $featured_posts);
                ?>
                    <div class="overflow-hidden border border-white/10 backdrop-blur-lg bg-dark-100 rounded-xl transition-all duration-300 hover:bg-dark-100/80">
                        <?php if ($is_sticky && current_user_can('edit_posts')) : ?>
                            <div class="px-4 py-2 bg-blue-300 text-dark-300 text-xs font-medium flex items-center justify-center admin-notice">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4 mr-1">
                                    <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                                </svg>
                                <?php _e('Featured Article', 'dark-theme-simplicity'); ?>
                            </div>
                        <?php endif; ?>
                        <div class="aspect-video bg-gradient-to-tr from-blue-400/20 to-purple-400/20">
                            <?php if (has_post_thumbnail()) : ?>
                                <a href="<?php the_permalink(); ?>">
                                    <?php the_post_thumbnail('large', ['class' => 'object-cover w-full h-full opacity-70']); ?>
                                </a>
                            <?php else: ?>
                                <a href="<?php the_permalink(); ?>" class="w-full h-full block">
                                    <div class="w-full h-full bg-gradient-to-tr from-blue-400/20 to-purple-400/20"></div>
                                </a>
                            <?php endif; ?>
                            
                            <?php if (!empty($category_name)) : ?>
                                <span class="absolute top-4 left-4 bg-blue-300/90 text-dark-300 px-2 py-1 rounded-full text-xs font-medium z-10">
                                    <?php echo $category_name; ?>
                                </span>
                            <?php endif; ?>
                        </div>
                        <div class="p-6">
                            <div class="flex gap-2 mb-3 items-center">
                                <span class="text-xs text-light-100/50 flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Last Updated: <?php echo get_the_modified_date(); ?>
                                </span>
                            </div>
                            <h3 class="text-xl md:text-2xl font-bold mb-3 line-clamp-2">
                                <a href="<?php the_permalink(); ?>" class="text-white hover:text-blue-300 transition-colors">
                                    <?php the_title(); ?>
                                </a>
                            </h3>
                            <p class="text-light-100/70 line-clamp-3">
                                <?php echo wp_trim_words(get_the_excerpt(), 25, '...'); ?>
                            </p>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </section>
    <?php 
        endif; 
        wp_reset_postdata(); 
    endif; 
    ?>

    <!-- All Articles Section -->
    <section class="py-16">
        <div class="container mx-auto">
            <div class="mb-10">
                <h2 class="text-3xl md:text-4xl font-bold text-white">All Articles</h2>
            </div>

            <?php if (have_posts()) : ?>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-12">
                    <?php 
                    // Get sticky posts to avoid duplicating featured posts in the main listing
                    $sticky_ids = get_option('sticky_posts', array());
                    
                    while (have_posts()) : the_post(); 
                        // We're no longer skipping sticky posts - they should appear in both sections
                        // Get the first category
                        $categories = get_the_category();
                        $category_name = !empty($categories) ? esc_html($categories[0]->name) : '';
                        
                        // Check if this is a featured post for styling purposes
                        $is_featured = is_array($sticky_ids) && in_array(get_the_ID(), $sticky_ids);
                    ?>
                        <div class="overflow-hidden border border-white/10 backdrop-blur-lg bg-dark-100/50 rounded-xl transition-all duration-300 hover:bg-dark-100 <?php echo $is_featured ? 'border-blue-300/30' : ''; ?>">
                            <div class="aspect-video bg-gradient-to-tr from-blue-300/20 to-purple-300/20">
                                <?php if (has_post_thumbnail()) : ?>
                                    <a href="<?php the_permalink(); ?>">
                                        <?php the_post_thumbnail('large', ['class' => 'object-cover w-full h-full opacity-60']); ?>
                                    </a>
                                <?php else: ?>
                                    <a href="<?php the_permalink(); ?>" class="w-full h-full block">
                                        <div class="w-full h-full bg-gradient-to-tr from-blue-300/20 to-purple-300/20"></div>
                                    </a>
                                <?php endif; ?>
                            </div>
                            <div class="p-6">
                                <div class="flex gap-2 mb-3 items-center">
                                    <?php if (!empty($category_name)) : ?>
                                        <span class="bg-blue-300/10 text-blue-300 border border-blue-300/20 px-2 py-0.5 rounded-full text-xs font-medium">
                                            <?php echo $category_name; ?>
                                        </span>
                                    <?php endif; ?>
                                    <span class="text-xs text-light-100/50 flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        Last Updated: <?php echo get_the_modified_date(); ?>
                                    </span>
                                </div>
                                <h3 class="text-xl md:text-2xl font-bold mb-2 line-clamp-2">
                                    <a href="<?php the_permalink(); ?>" class="text-white hover:text-blue-300 transition-colors">
                                        <?php the_title(); ?>
                                    </a>
                                </h3>
                                <p class="text-light-100/70 line-clamp-3 mb-4">
                                    <?php echo wp_trim_words(get_the_excerpt(), 20, '...'); ?>
                                </p>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>

                <div class="pagination flex justify-center items-center space-x-3 mt-12">
                    <?php
                    echo paginate_links(array(
                        'prev_text' => '<span class="text-blue-300">&larr; Previous</span>',
                        'next_text' => '<span class="text-blue-300">Next &rarr;</span>',
                        'class' => 'pagination',
                        'before_page_number' => '<span class="px-3 py-2 bg-dark-300 text-blue-300 rounded-lg hover:bg-dark-400 transition-colors">',
                        'after_page_number' => '</span>',
                    ));
                    ?>
                </div>

            <?php else : ?>
                <div class="bg-dark-300 p-8 rounded-xl border border-white/10">
                    <p class="text-center text-light-100/70 text-lg">No posts found.</p>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Contact Section -->
    <section class="py-16 bg-dark-200 contact-section mt-16 relative">
        <div class="absolute top-0 left-0 w-full h-4 bg-gradient-to-r from-blue-500/0 via-blue-500/20 to-blue-500/0"></div>
        <div class="container mx-auto max-w-5xl">
            <div class="text-center mb-16">
                <span class="inline-block px-4 py-2 bg-blue-300/10 section-label rounded-full text-sm mb-4 border border-blue-300/20">
                    Get in Touch
                </span>
                <h2 class="text-3xl md:text-4xl font-bold mb-4 text-light-100">
                    <?php echo esc_html(get_theme_mod('dark_theme_simplicity_contact_title', 'Contact Me')); ?>
                </h2>
                <p class="text-xl text-light-100/70 max-w-2xl mx-auto">
                    <?php echo esc_html(get_theme_mod('dark_theme_simplicity_contact_description', 'Let\'s discuss how we can elevate your online presence.')); ?>
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-3xl mx-auto">
                <a href="mailto:<?php echo esc_attr(get_theme_mod('dark_theme_simplicity_contact_email', 'hello@braddaiber.com')); ?>" class="glass-card p-6 rounded-xl">
                    <div class="flex items-center space-x-4">
                        <div class="bg-blue-300/20 p-3 rounded-lg">
                            <svg class="w-6 h-6 contact-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                                <polyline points="22,6 12,13 2,6"></polyline>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-white">Email</h3>
                    </div>
                </a>

                <?php $linkedin_url = get_theme_mod('dark_theme_simplicity_contact_linkedin', 'linkedin.com/in/braddaiber'); ?>
                <a href="<?php echo esc_url( strpos( $linkedin_url, 'http' ) === 0 ? $linkedin_url : 'https://' . $linkedin_url ); ?>" target="_blank" rel="noopener noreferrer" class="glass-card p-6 rounded-xl">
                    <div class="flex items-center space-x-4">
                        <div class="bg-blue-300/20 p-3 rounded-lg">
                            <svg class="w-6 h-6 contact-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"></path>
                                <rect x="2" y="9" width="4" height="12"></rect>
                                <circle cx="4" cy="4" r="2"></circle>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-white">LinkedIn</h3>
                    </div>
                </a>
            </div>
        </div>
    </section>
</main>

<!-- Add animation JavaScript for reveal effects -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('revealed');
                    observer.unobserve(entry.target);
                }
            });
        },
        { threshold: 0.1 }
    );

    const elements = document.querySelectorAll('.reveal-text');
    elements.forEach((el) => observer.observe(el));
});
</script>

<!-- Add animation CSS for reveal effects -->
<style>
.reveal-text {
    opacity: 0;
    transform: translateY(20px);
    transition: opacity 0.8s ease, transform 0.8s ease;
}

.reveal-text.revealed {
    opacity: 1;
    transform: translateY(0);
}

.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Enhanced typography for better visual hierarchy */
h3.text-xl.md\:text-2xl {
    letter-spacing: -0.01em;
    line-height: 1.3;
    margin-bottom: 0.75rem;
}

.text-light-100\/70.line-clamp-3 {
    font-size: 0.95rem;
    line-height: 1.6;
    color: rgba(248, 250, 252, 0.7);
}

/* Enhanced hover states */
.overflow-hidden:hover h3 a {
    color: #60a5fa;
}

/* Post thumbnails hover effect */
.aspect-video {
    position: relative;
    width: 100%;
    height: 0;
    padding-bottom: 56.25%; /* 16:9 Aspect Ratio */
    overflow: hidden;
}

.aspect-video a {
    display: block;
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    overflow: hidden;
    cursor: pointer;
}

.aspect-video a img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease, opacity 0.3s ease;
}

.aspect-video a:hover img {
    transform: scale(1.05);
    opacity: 0.9;
}

/* Gradient background hover effect for posts without thumbnails */
.aspect-video a div {
    transition: transform 0.3s ease, opacity 0.3s ease;
}

.aspect-video a:hover div {
    transform: scale(1.05);
    opacity: 0.9;
    background-image: linear-gradient(to top right, rgba(96, 165, 250, 0.3), rgba(192, 132, 252, 0.3));
}

/* Admin notice styling */
.admin-notice {
    display: block;
}

@media print {
    .admin-notice {
        display: none;
    }
}

/* Enhanced pagination styling */
.pagination .page-numbers {
    color: #60a5fa;
    font-weight: 500;
    transition: all 0.2s ease;
}

.pagination .page-numbers.current {
    background-color: #60a5fa;
    color: #1e1e24;
    border-radius: 0.5rem;
    padding: 0.5rem 1rem;
}

.pagination .page-numbers:hover:not(.current) {
    color: #93c5fd;
}

.pagination .dots {
    color: rgba(255, 255, 255, 0.5);
    margin: 0 0.5rem;
}
</style>

<?php get_footer(); ?>