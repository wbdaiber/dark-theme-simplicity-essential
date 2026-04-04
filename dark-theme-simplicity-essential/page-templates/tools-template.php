<?php
/**
 * Template Name: Tools Hub
 * Description: A template for displaying tools as pages in a grid layout similar to the blog.
 */

get_header();
?>

<main id="content" class="site-main bg-dark-200">
    <!-- Hero Section -->
    <section class="py-16 md:py-24 bg-dark-300 tools-hero-section">
        <div class="container">
            <div class="max-w-5xl text-left">
                <h1 class="text-4xl md:text-6xl font-bold tracking-tight mb-6 text-white reveal-text">
                    <?php
                    // Use page title if set, otherwise fall back to customizer setting
                    echo esc_html(get_the_title()) ?: esc_html(get_theme_mod('dark_theme_simplicity_tools_hero_title', 'Tools & Resources'));
                    ?>
                </h1>
                <?php if (get_the_content()) : ?>
                    <div class="text-xl md:text-2xl max-w-3xl reveal-text tools-hero-description text-light-100/70">
                        <?php the_content(); ?>
                    </div>
                <?php else: ?>
                    <div class="text-xl md:text-2xl max-w-3xl reveal-text tools-hero-description text-light-100/70">
                        <?php echo esc_html(get_theme_mod('dark_theme_simplicity_tools_hero_description', 'Explore our collection of tools designed to help you optimize your digital presence.')); ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <?php 
    // Output custom CSS for tools hero background and colors
    $tools_hero_bg = get_theme_mod('dark_theme_simplicity_tools_hero_bg_image', '');
    $tools_hero_overlay_opacity = get_theme_mod('dark_theme_simplicity_tools_hero_overlay_opacity', 70);
    $overlay_opacity_decimal = $tools_hero_overlay_opacity / 100;
    $title_color = get_theme_mod('dark_theme_simplicity_tools_hero_title_color', '#ffffff');
    $desc_color = get_theme_mod('dark_theme_simplicity_tools_hero_desc_color', '#ffffff');
    ?>
    <style>
        .tools-hero-section {
            position: relative;
            background-color: #121214;
            background-image: linear-gradient(135deg, rgba(59, 130, 246, 0.10) 0%, rgba(139, 92, 246, 0.12) 100%);
            background-blend-mode: screen;
            <?php if (!empty($tools_hero_bg)) : ?>
            background-image: url('<?php echo esc_url($tools_hero_bg); ?>');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            <?php endif; ?>
        }
        
        <?php if (!empty($tools_hero_bg)) : ?>
        .tools-hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(18, 18, 20, <?php echo esc_attr($overlay_opacity_decimal); ?>);
            z-index: 1;
        }
        
        .tools-hero-section .container {
            position: relative;
            z-index: 2;
        }
        <?php endif; ?>
        
        .tools-hero-section h1 {
            color: <?php echo esc_attr($title_color); ?> !important;
        }
        
        .tools-hero-description {
            color: <?php echo esc_attr($desc_color); ?> !important;
        }
    </style>

    <!-- Tools Grid Section -->
    <section class="py-16">
        <div class="container">
            <div class="mb-10">
                <h2 class="text-3xl md:text-4xl font-bold text-white">
                    <?php 
                    // Use meta field if set, otherwise fall back to customizer setting
                    $section_title = get_post_meta(get_the_ID(), 'tools_section_title', true);
                    echo esc_html($section_title ?: get_theme_mod('dark_theme_simplicity_tools_section_title', 'Available Tools')); 
                    ?>
                </h2>
            </div>

            <?php
            // Get child pages of current page by default, or all pages if this is set in custom field
            $show_all = get_post_meta(get_the_ID(), 'show_all_tools', true);
            $exclude_ids = get_post_meta(get_the_ID(), 'exclude_tools', true);
            $exclude_array = !empty($exclude_ids) ? explode(',', $exclude_ids) : array();
            
            // Add current page to excluded pages
            $exclude_array[] = get_the_ID();
            
            // Query args
            $args = array(
                'post_type' => 'page',
                'posts_per_page' => -1,
                'post__not_in' => $exclude_array,
                'orderby' => 'menu_order',
                'order' => 'ASC'
            );
            
            // If not showing all, only show child pages
            if (!$show_all) {
                $args['post_parent'] = get_the_ID();
            }
            
            $tools_query = new WP_Query($args);
            
            if ($tools_query->have_posts()) : 
            ?>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-12">
                    <?php 
                    while ($tools_query->have_posts()) : $tools_query->the_post(); 
                        // Get the excerpt with fallback logic: custom excerpt > WordPress excerpt > trimmed content
                        $custom_excerpt = get_post_meta(get_the_ID(), '_custom_excerpt', true);
                        $excerpt = '';
                        
                        if (!empty($custom_excerpt)) {
                            $excerpt = $custom_excerpt;
                        } else {
                            $excerpt = get_the_excerpt();
                            if (empty($excerpt)) {
                                $excerpt = wp_trim_words(get_the_content(), 20, '...');
                            }
                        }
                    ?>
                        <div class="overflow-hidden border border-white/10 backdrop-blur-lg bg-dark-100/50 rounded-xl transition-all duration-300 hover:bg-dark-100">
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
                                
                                <?php 
                                // Display tool type/tag if set
                                $tool_type = get_post_meta(get_the_ID(), 'tool_type', true);
                                if (!empty($tool_type)) : 
                                ?>
                                    <span class="absolute top-4 left-4 bg-blue-300/90 text-dark-300 px-2 py-1 rounded-full text-xs font-medium z-10">
                                        <?php echo esc_html($tool_type); ?>
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
                                <h3 class="text-xl md:text-2xl font-bold mb-2 line-clamp-2">
                                    <a href="<?php the_permalink(); ?>" class="text-white hover:text-blue-300 transition-colors">
                                        <?php the_title(); ?>
                                    </a>
                                </h3>
                                <p class="text-light-100/70 line-clamp-3 mb-4">
                                    <?php echo esc_html($excerpt); ?>
                                </p>
                                <a href="<?php the_permalink(); ?>" class="inline-flex items-center text-blue-300 hover:text-blue-400 transition-colors">
                                    Use Tool
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php 
            else : 
            ?>
                <div class="bg-dark-300 p-8 rounded-xl border border-white/10">
                    <p class="text-center text-light-100/70 text-lg">No tools found. Add some pages as children of this page to display them here.</p>
                </div>
            <?php 
            endif; 
            wp_reset_postdata(); 
            ?>
        </div>
    </section>

    <!-- Contact Section -->
    <section class="py-16 px-4 sm:px-6 lg:px-8 bg-dark-200 contact-section mt-16 relative">
        <div class="absolute top-0 left-0 w-full h-4 bg-gradient-to-r from-blue-500/0 via-blue-500/20 to-blue-500/0"></div>
        <div class="container">
            <div class="text-center mb-16">
                <span class="inline-block px-4 py-2 bg-blue-300/10 section-label rounded-full text-sm mb-4 border border-blue-300/20">
                    Need Help?
                </span>
                <h2 class="text-3xl md:text-4xl font-bold mb-4 text-light-100">
                    <?php 
                    // Use meta field if set, otherwise fall back to customizer setting
                    $contact_title = get_post_meta(get_the_ID(), 'contact_title', true);
                    echo esc_html($contact_title ?: get_theme_mod('dark_theme_simplicity_tools_contact_title', 'Get Support')); 
                    ?>
                </h2>
                <p class="text-xl text-light-100/70 max-w-2xl mx-auto">
                    <?php 
                    // Use meta field if set, otherwise fall back to customizer setting
                    $contact_description = get_post_meta(get_the_ID(), 'contact_description', true);
                    echo esc_html($contact_description ?: get_theme_mod('dark_theme_simplicity_tools_contact_description', 'Questions about our tools? Reach out for assistance.')); 
                    ?>
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-3xl mx-auto">
                <a href="mailto:<?php echo esc_attr(get_theme_mod('dark_theme_simplicity_contact_email', 'hello@braddaiber.com')); ?>" class="glass-card p-6">
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
                <a href="<?php echo esc_url( strpos( $linkedin_url, 'http' ) === 0 ? $linkedin_url : 'https://' . $linkedin_url ); ?>" target="_blank" rel="noopener noreferrer" class="glass-card p-6">
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
</style>

<?php get_footer(); ?>