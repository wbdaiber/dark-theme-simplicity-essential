<!-- Contact Section -->
<section id="contact" class="py-16 px-4 sm:px-6 lg:px-8 bg-dark-200 contact-section" data-lazy-load>
    <div class="container">
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
            <?php
            // Get contact information with validation
            $contact_email = get_theme_mod('dark_theme_simplicity_contact_email', 'hello@braddaiber.com');
            $contact_linkedin = get_theme_mod('dark_theme_simplicity_contact_linkedin', 'linkedin.com/in/braddaiber');
            
            // Validate email
            if (!is_email($contact_email)) {
                $contact_email = 'hello@braddaiber.com';
            }
            
            // Validate LinkedIn URL - remove protocol if present to avoid double https://
            $contact_linkedin = str_replace(array('https://', 'http://'), '', $contact_linkedin);
            if (empty($contact_linkedin) || !filter_var('https://' . $contact_linkedin, FILTER_VALIDATE_URL)) {
                $contact_linkedin = 'linkedin.com/in/braddaiber';
            }
            
            // Create contact items array for dynamic animation delays
            $contact_items = array(
                array(
                    'type' => 'email',
                    'url' => 'mailto:' . $contact_email,
                    'title' => 'Email',
                    'target' => '',
                    'rel' => ''
                ),
                array(
                    'type' => 'linkedin',
                    'url' => 'https://' . $contact_linkedin,
                    'title' => 'LinkedIn',
                    'target' => '_blank',
                    'rel' => 'noopener noreferrer'
                )
            );
            
            foreach ($contact_items as $index => $item) :
                $delay_map = array( 1 => 'animation-delay-200', 2 => 'animation-delay-400', 3 => 'animation-delay-600' );
                $delay_class = isset( $delay_map[ $index ] ) ? $delay_map[ $index ] : ( $index > 3 ? 'animation-delay-600' : '' );
            ?>
            <a href="<?php echo esc_attr($item['url']); ?>" <?php echo $item['target'] ? 'target="' . esc_attr($item['target']) . '"' : ''; ?> <?php echo $item['rel'] ? 'rel="' . esc_attr($item['rel']) . '"' : ''; ?> class="glass-card p-6 animate-fade-in-up <?php echo esc_attr($delay_class); ?>">
                <div class="flex items-center space-x-4">
                    <div class="bg-blue-300/20 p-3 rounded-lg">
                        <?php if ($item['type'] === 'email') : ?>
                            <svg class="w-6 h-6 contact-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                                <polyline points="22,6 12,13 2,6"></polyline>
                            </svg>
                        <?php else : ?>
                            <svg class="w-6 h-6 contact-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"></path>
                                <rect x="2" y="9" width="4" height="12"></rect>
                                <circle cx="4" cy="4" r="2"></circle>
                            </svg>
                        <?php endif; ?>
                    </div>
                    <h3 class="text-2xl font-bold text-white"><?php echo esc_html($item['title']); ?></h3>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>