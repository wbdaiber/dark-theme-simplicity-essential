<!-- Services Section -->
<section id="what-we-do" class="py-16 px-4 md:px-0 services-section" data-lazy-load role="region" aria-labelledby="services-heading">
    <div class="container mx-auto max-w-6xl">
        <?php
        // Cache theme mod values to avoid redundant calls
        $services_title = get_theme_mod( 'dark_theme_simplicity_services_title', __( 'Our Services', 'dark-theme-simplicity' ) );
        $services_description = get_theme_mod( 'dark_theme_simplicity_services_description', __( 'Comprehensive digital marketing solutions to elevate your online presence.', 'dark-theme-simplicity' ) );
        ?>
        <div class="text-center mb-16">
            <span class="inline-block px-4 py-2 bg-blue-300/10 section-label rounded-full text-sm mb-4 border border-blue-300/20">
                <?php esc_html_e( 'What We Do', 'dark-theme-simplicity' ); ?>
            </span>
            <h2 id="services-heading" class="text-4xl md:text-5xl font-bold mb-6">
                <?php echo esc_html( $services_title ); ?>
            </h2>
            <p class="text-xl text-light-100/80 max-w-3xl mx-auto">
                <?php echo esc_html( $services_description ); ?>
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Service Cards -->
            <?php
            // Get theme customization colors
            $service_bg_color = get_theme_mod('dark_theme_simplicity_service_card_bg_color', '#1e1e24');
            $service_accent_color = get_theme_mod('dark_theme_simplicity_service_card_accent_color', '#0085ff');
            
            // Use the helper function if available and catch any errors
            try {
                if (function_exists('dt_get_service_items')) {
                    $service_items = dt_get_service_items();
                } else {
                    // Safely get and decode service items with error handling
                    $service_items_json = get_theme_mod('dark_theme_simplicity_service_items', '');
                    $service_items = json_decode($service_items_json, true);
                    
                    // Check for JSON decode errors
                    if (json_last_error() !== JSON_ERROR_NONE) {
                        error_log('JSON error in service items: ' . json_last_error_msg());
                        $service_items = array(); // Reset to empty if JSON is invalid
                    }
                    
                    // Make sure we have an array
                    if (!is_array($service_items)) {
                        $service_items = array();
                    }
                }
            } catch (Exception $e) {
                error_log('Exception when processing service items: ' . $e->getMessage());
                $service_items = array(); // Reset on any exception
            }
            
            if (!empty($service_items)) {
                foreach ($service_items as $index => $service) {
                    // Skip if not an array
                    if (!is_array($service)) {
                        continue;
                    }
                    
                    $animation_delay = $index * 200; // 0, 200, 400, etc.
                    $delay_class = $index > 0 ? "animation-delay-{$animation_delay}" : "";
                    
                    // Get values with defaults
                    $icon = isset($service['icon']) && !empty($service['icon']) ? $service['icon'] : 'globe';
                    $title = isset($service['title']) && !empty($service['title']) ? $service['title'] : 'Service Title';
                    $description = isset($service['description']) && !empty($service['description']) ? $service['description'] : 'Service description goes here.';
                    
                    // Get SVG icon based on the selected icon name
                    $svg_icon = '';
                    if (function_exists('dark_theme_simplicity_get_service_icon')) {
                        $svg_icon = dark_theme_simplicity_get_service_icon($icon);
                    } else {
                        // Fallback icon if function doesn't exist
                        $svg_icon = '<svg class="w-10 h-10 text-blue-300" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle></svg>';
                    }
                    ?>
                    <div class="glass-card p-6 rounded-lg group animate-fade-in-up <?php echo esc_attr($delay_class); ?>" style="background-color: <?php echo esc_attr($service_bg_color); ?> !important;" tabindex="0" role="article" aria-labelledby="service-title-<?php echo esc_attr($index); ?>">
                        <div class="p-4 rounded-lg w-18 h-18 mb-6 flex items-center justify-center transition-all" aria-hidden="true" role="img" aria-label="<?php echo esc_attr($title); ?> service icon">
                            <?php echo $svg_icon; ?>
                        </div>
                        <h3 id="service-title-<?php echo esc_attr($index); ?>" class="text-2xl font-bold mb-3 text-white"><?php echo esc_html($title); ?></h3>
                        <p class="text-xl text-light-100/70"><?php echo esc_html($description); ?></p>
                    </div>
                    <?php
                }
            } else {
                // Fallback if no service items are defined
                ?>
                <div class="glass-card p-6 rounded-lg group animate-fade-in-up" style="background-color: <?php echo esc_attr($service_bg_color); ?> !important;">
                    <div class="p-4 rounded-lg w-18 h-18 mb-6 flex items-center justify-center transition-all" aria-hidden="true">
                        <svg class="w-10 h-10 text-blue-300" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"></circle>
                            <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold mb-3 text-white">Strategic SEO</h3>
                    <p class="text-xl text-light-100/70">Boost your visibility with search engine optimization that drives organic traffic.</p>
                </div>
                <div class="glass-card p-6 rounded-lg group animate-fade-in-up animation-delay-200" style="background-color: <?php echo esc_attr($service_bg_color); ?> !important;">
                    <div class="p-4 rounded-lg w-18 h-18 mb-6 flex items-center justify-center transition-all" aria-hidden="true">
                        <svg class="w-10 h-10 text-blue-300" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                            <polyline points="14 2 14 8 20 8"></polyline>
                            <line x1="16" y1="13" x2="8" y2="13"></line>
                            <line x1="16" y1="17" x2="8" y2="17"></line>
                            <polyline points="10 9 9 9 8 9"></polyline>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold mb-3 text-white">Content Creation</h3>
                    <p class="text-xl text-light-100/70">Engaging, on-brand content that resonates with your target audience.</p>
                </div>
                <div class="glass-card p-6 rounded-lg group animate-fade-in-up animation-delay-400" style="background-color: <?php echo esc_attr($service_bg_color); ?> !important;">
                    <div class="p-4 rounded-lg w-18 h-18 mb-6 flex items-center justify-center transition-all" aria-hidden="true">
                        <svg class="w-10 h-10 text-blue-300" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect>
                            <line x1="8" y1="21" x2="16" y2="21"></line>
                            <line x1="12" y1="17" x2="12" y2="21"></line>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold mb-3 text-white">Website Development</h3>
                    <p class="text-xl text-light-100/70">Custom websites designed for user experience and conversion optimization.</p>
                </div>
            <?php
            }
            ?>
        </div>
    </div>
</section>