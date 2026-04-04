<!-- Benefits Section -->
<section id="benefits" class="py-16 px-4 sm:px-6 lg:px-8 bg-dark-300 mt-16 relative">
    <div class="absolute top-0 left-0 w-full h-4 bg-gradient-to-r from-blue-500/0 via-blue-500/20 to-blue-500/0"></div>
    <div class="container">
        <div class="text-center mb-16">
            <span class="inline-block px-4 py-2 bg-blue-300/10 section-label rounded-full text-sm mb-4 border border-blue-300/20">
                <?php esc_html_e( 'Why Choose Us', 'dark-theme-simplicity' ); ?>
            </span>
            <h2 class="text-3xl md:text-4xl font-bold mb-4 text-light-100">
                <?php echo esc_html(get_theme_mod('dark_theme_simplicity_benefits_title', 'Key Benefits')); ?>
            </h2>
            <p class="text-xl text-light-100/70 max-w-2xl mx-auto">
                <?php echo esc_html(get_theme_mod('dark_theme_simplicity_benefits_description', 'We deliver real results through strategic digital solutions tailored to your business goals.')); ?>
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-3xl mx-auto">
            <?php
            // Implement robust error handling pattern from approach section
            try {
                // Get benefit items from customizer
                $json_data = get_theme_mod('dark_theme_simplicity_benefit_items', '');
                
                // Handle empty JSON data
                if (empty($json_data)) {
                    $benefit_items = array();
                } else {
                    // Safely decode JSON
                    $benefit_items = json_decode($json_data, true);
                    
                    // Check for JSON errors
                    if (json_last_error() !== JSON_ERROR_NONE) {
                        // Log error
                        error_log('Section benefits JSON error: ' . json_last_error_msg() . ' in: ' . substr($json_data, 0, 100));
                        $benefit_items = array();
                    }
                    
                    // Ensure we have a valid array to work with
                    if (!is_array($benefit_items)) {
                        $benefit_items = array();
                    }
                }
            } catch (Exception $e) {
                // Log any unexpected exceptions
                error_log('Exception in benefits section: ' . $e->getMessage());
                $benefit_items = array();
            }
            
            // Only proceed with foreach if we have valid data
            if (!empty($benefit_items) && is_array($benefit_items)) {
                foreach ($benefit_items as $index => $item) :
                    // Ensure item is an array
                    if (!is_array($item)) {
                        continue;
                    }
                    
                    // Ensure we have the required keys with defaults
                    $title = isset($item['title']) && !empty($item['title']) 
                        ? $item['title'] 
                        : 'Benefit ' . ($index + 1);
                        
                    $description = isset($item['description']) && !empty($item['description'])
                        ? $item['description']
                        : 'Benefit description';
                        
                    $delay_map = array( 1 => 'animation-delay-200', 2 => 'animation-delay-400', 3 => 'animation-delay-600' );
                    $delay_class = isset( $delay_map[ $index ] ) ? $delay_map[ $index ] : ( $index > 3 ? 'animation-delay-600' : '' );
                ?>
                    <div class="glass-card p-6" data-lazy-load>
                        <h3 class="text-2xl font-bold mb-3 text-white"><?php echo esc_html($title); ?></h3>
                        <p class="text-xl text-light-100/70"><?php echo esc_html($description); ?></p>
                    </div>
                <?php
                endforeach;
            } else {
                // Default benefits if none are set
                $default_benefits = array(
                    array(
                        'title' => 'Data-Driven',
                        'description' => 'Our strategies are backed by thorough research and analytics for measurable outcomes.'
                    ),
                    array(
                        'title' => 'Customized Approach',
                        'description' => 'Solutions are tailored to your specific industry, audience, and business objectives.'
                    ),
                    array(
                        'title' => 'Transparent Process',
                        'description' => 'Clear communication and regular reporting keep you informed every step of the way.'
                    ),
                    array(
                        'title' => 'Continuous Optimization',
                        'description' => 'We consistently refine strategies based on performance data to maximize ROI.'
                    )
                );
                
                foreach ($default_benefits as $index => $item) :
                    $delay_map = array( 1 => 'animation-delay-200', 2 => 'animation-delay-400', 3 => 'animation-delay-600' );
                    $delay_class = isset( $delay_map[ $index ] ) ? $delay_map[ $index ] : ( $index > 3 ? 'animation-delay-600' : '' );
                ?>
                    <div class="glass-card p-6" data-lazy-load>
                        <h3 class="text-2xl font-bold mb-3 text-white"><?php echo esc_html($item['title']); ?></h3>
                        <p class="text-xl text-light-100/70"><?php echo esc_html($item['description']); ?></p>
                    </div>
                <?php
                endforeach;
            }
            ?>
        </div>
    </div>
</section>