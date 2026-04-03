<!-- Approach Section -->
<section id="approach" class="py-16 px-4 sm:px-6 lg:px-8 bg-dark-200 mt-16 relative" data-lazy-load>
    <div class="absolute top-0 left-0 w-full h-4 bg-gradient-to-r from-blue-500/0 via-blue-500/20 to-blue-500/0"></div>
    <div class="container">
        <div class="text-center mb-16">
            <span class="inline-block px-4 py-2 bg-blue-300/10 section-label rounded-full text-sm mb-4 border border-blue-300/20">
                <?php esc_html_e( 'My Process', 'dark-theme-simplicity' ); ?>
            </span>
            <h2 class="text-3xl md:text-4xl font-bold mb-4 text-light-100">
                <?php echo esc_html(get_theme_mod('dark_theme_simplicity_approach_title', 'How I work with clients')); ?>
            </h2>
            <p class="text-xl text-light-100/70 max-w-2xl mx-auto">
                <?php echo esc_html(get_theme_mod('dark_theme_simplicity_approach_description', 'I believe in a collaborative approach to content strategy. Your business is unique, and your content strategy should be too.')); ?>
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-3xl mx-auto">
            <?php
            $approach_items = dt_get_approach_items();
            foreach ($approach_items as $index => $item) :
                if (!is_array($item)) continue;
                $title = !empty($item['title']) ? $item['title'] : 'Item ' . ($index + 1);
                $description = !empty($item['description']) ? $item['description'] : 'Description';
                $delay_map = array( 1 => 'animation-delay-200', 2 => 'animation-delay-400', 3 => 'animation-delay-600' );
                $delay_class = isset( $delay_map[ $index ] ) ? $delay_map[ $index ] : ( $index > 3 ? 'animation-delay-600' : '' );
            ?>
                <div class="glass-card p-6 animate-fade-in-up <?php echo esc_attr($delay_class); ?>">
                    <div class="flex items-start space-x-4">
                        <div class="bg-blue-300/20 p-3 rounded-lg">
                            <span class="text-xl font-bold text-blue-300"><?php echo esc_html($index + 1); ?></span>
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold mb-3 text-white"><?php echo esc_html($title); ?></h3>
                            <p class="text-xl text-light-100/70"><?php echo esc_html($description); ?></p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>