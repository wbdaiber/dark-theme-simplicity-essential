<?php get_header(); ?>

<main id="content" class="site-main bg-dark-200">
    <!-- Hero Section -->
    <section class="py-16 md:py-24 bg-dark-300">
        <div class="container mx-auto px-4">
            <div class="max-w-5xl">
                <h1 class="text-4xl md:text-6xl font-bold tracking-tight mb-6 text-white reveal-text">
                    <?php 
                    if (is_category()) {
                        single_cat_title();
                    } elseif (is_tag()) {
                        single_tag_title();
                    } elseif (is_author()) {
                        echo get_the_author();
                    } elseif (is_date()) {
                        if (is_day()) {
                            echo get_the_date();
                        } elseif (is_month()) {
                            echo get_the_date('F Y');
                        } elseif (is_year()) {
                            echo get_the_date('Y');
                        }
                    } else {
                        the_archive_title();
                    }
                    ?>
                </h1>
                <div class="text-xl md:text-2xl text-light-100/70 max-w-3xl reveal-text">
                    <?php 
                    if (is_category() && category_description()) {
                        echo category_description();
                    } elseif (is_tag() && tag_description()) {
                        echo tag_description();
                    } elseif (is_author() && get_the_author_meta('description')) {
                        echo get_the_author_meta('description');
                    } else {
                        echo 'Explore our collection of articles on this topic, offering insights, tips, and strategies to help you grow your knowledge and skills.';
                    }
                    ?>
                </div>
            </div>
        </div>
    </section>

    <!-- All Articles Section -->
    <section class="py-16">
        <div class="container mx-auto px-4">
            <div class="mb-10">
                <h2 class="text-3xl md:text-4xl font-bold text-white">All Articles</h2>
            </div>

            <?php if (have_posts()) : ?>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-12">
                    <?php while (have_posts()) : the_post(); 
                        // Get the first category
                        $categories = get_the_category();
                        $category_name = !empty($categories) ? esc_html($categories[0]->name) : '';
                    ?>
                        <div class="overflow-hidden border border-white/10 backdrop-blur-lg bg-dark-100/50 rounded-xl transition-all duration-300 hover:bg-dark-100">
                            <div class="aspect-video relative bg-gradient-to-tr from-blue-300/20 to-purple-300/20">
                                <?php if (has_post_thumbnail()) : ?>
                                    <?php the_post_thumbnail('large', ['class' => 'object-cover w-full h-full opacity-60']); ?>
                                <?php else: ?>
                                    <div class="w-full h-full bg-gradient-to-tr from-blue-300/20 to-purple-300/20"></div>
                                <?php endif; ?>
                            </div>
                            <div class="p-6">
                                <div class="flex gap-2 mb-3 items-center">
                                    <?php if (!empty($category_name)) : ?>
                                        <span class="bg-blue-300/10 text-blue-300 border border-blue-300/20 px-2 py-0.5 rounded-full text-xs font-medium">
                                            <?php echo $category_name; ?>
                                        </span>
                                    <?php endif; ?>
                                    <span class="text-xs text-light-100/50 mt-0.5">
                                        <?php echo get_the_date(); ?>
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

/* Enhance hover states */
.overflow-hidden:hover h3 a {
    color: #60a5fa;
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