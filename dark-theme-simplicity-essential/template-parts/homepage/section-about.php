<!-- About Section -->
<section id="about" class="py-16 px-4 sm:px-6 lg:px-8 bg-dark-300 mt-16 relative" data-lazy-load role="region" aria-labelledby="about-heading">
    <div class="absolute top-0 left-0 w-full h-4 bg-gradient-to-r from-blue-500/0 via-blue-500/20 to-blue-500/0"></div>
    <div class="container">
        <div class="text-center mb-16">
            <span class="inline-block px-4 py-2 bg-blue-300/10 section-label rounded-full text-sm mb-4 border border-blue-300/20">
                <?php esc_html_e( 'About Me', 'dark-theme-simplicity' ); ?>
            </span>
            <?php
            // Get about content with fallback handling - cache theme mod values
            $about_title = get_theme_mod('dark_theme_simplicity_about_title', 'Digital Marketing Specialist');
            $about_subtitle = get_theme_mod('dark_theme_simplicity_about_subtitle', 'With over a decade of experience helping businesses thrive online.');
            $about_content_1 = get_theme_mod('dark_theme_simplicity_about_content_1', 'I\'m Brad Daiber, a seasoned digital marketing consultant with a passion for helping businesses establish a powerful online presence. With a background in SEO, content creation, and web design, I provide comprehensive solutions tailored to your specific needs.');
            $about_content_2 = get_theme_mod('dark_theme_simplicity_about_content_2', 'My approach combines data-driven strategies with creative thinking to deliver measurable results. Whether you\'re looking to increase website traffic, improve conversion rates, or establish your brand voice, I\'m here to help you achieve your goals.');
            $about_image = get_theme_mod('dark_theme_simplicity_about_image', get_template_directory_uri() . '/assets/images/about-image.svg');
            
            // Validate and sanitize content
            $about_title = !empty($about_title) ? $about_title : 'Digital Marketing Specialist';
            $about_subtitle = !empty($about_subtitle) ? $about_subtitle : 'With over a decade of experience helping businesses thrive online.';
            $about_content_1 = !empty($about_content_1) ? $about_content_1 : 'Professional content strategist with extensive experience in digital marketing and business growth.';
            $about_content_2 = !empty($about_content_2) ? $about_content_2 : 'Specialized in creating comprehensive solutions that drive results and exceed expectations.';
            $about_image = !empty($about_image) ? $about_image : get_template_directory_uri() . '/assets/images/about-image.svg';
            ?>
            <h2 id="about-heading" class="text-3xl md:text-4xl font-bold mb-4 text-light-100">
                <?php echo esc_html($about_title); ?>
            </h2>
            <p class="text-xl text-light-100/70 max-w-2xl mx-auto">
                <?php echo esc_html($about_subtitle); ?>
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
            <div class="order-2 md:order-1 animate-fade-in-up">
                <p class="text-light-100/80 mb-6">
                    <?php echo esc_html($about_content_1); ?>
                </p>
                <p class="text-light-100/80">
                    <?php echo esc_html($about_content_2); ?>
                </p>
            </div>
            <div class="order-1 md:order-2 flex justify-center animate-fade-in-up animation-delay-200">
                <div class="glass-card p-6" tabindex="0" role="img" aria-labelledby="about-image-description">
                    <img src="<?php echo esc_url($about_image); ?>" alt="Professional headshot of <?php echo esc_attr($about_title); ?> - Digital Marketing Specialist" class="max-w-full h-auto rounded-lg">
                    <span id="about-image-description" class="sr-only">Professional headshot photo showcasing <?php echo esc_attr($about_title); ?>, emphasizing their expertise in digital marketing and business consulting.</span>
                </div>
            </div>
        </div>
    </div>
</section>