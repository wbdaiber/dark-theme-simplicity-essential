<footer class="site-footer bg-dark-300 border-t border-white/10 mt-0">
    <div class="container mx-auto px-4 py-16">
        <!-- Footer Top: Three Column Layout -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-12 mb-12">
            <!-- Logo + Tagline -->
            <div class="space-y-6">
                <a href="<?php echo esc_url(home_url('/')); ?>" class="flex items-center gap-3">
                    <!-- 3D Cube SVG -->
                    <span class="inline-block">
                        <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg" class="text-blue-400">
                            <!-- Top face -->
                            <path d="M16 8L4 14L16 20L28 14L16 8Z" stroke="#60a5fa" stroke-width="2" fill="none" />
                            <!-- Front face -->
                            <path d="M4 14V22L16 28V20L4 14Z" stroke="#60a5fa" stroke-width="2" fill="none" />
                            <!-- Right face -->
                            <path d="M16 20V28L28 22V14L16 20Z" stroke="#60a5fa" stroke-width="2" fill="none" />
                            <!-- Inner lines for 3D effect -->
                            <path d="M16 8V20" stroke="#60a5fa" stroke-width="1.5" opacity="0.8" />
                        </svg>
                    </span>
                    <span class="text-xl font-bold text-white"><?php echo esc_html(get_theme_mod('dark_theme_simplicity_site_title', 'Brad Daiber')); ?></span>
                </a>
                <p class="text-light-100/70 text-lg max-w-md"><?php echo esc_html(get_theme_mod('dark_theme_simplicity_footer_tagline', 'Helping businesses establish a powerful online presence through strategic digital marketing solutions.')); ?></p>
            </div>
            
            <!-- Navigation -->
            <div>
                <div class="text-lg font-bold mb-4 text-white">Navigation</div>
                <?php
                if (has_nav_menu('footer')) {
                    wp_nav_menu(array(
                        'theme_location' => 'footer',
                        'container'      => false,
                        'menu_class'     => 'space-y-3',
                        'fallback_cb'    => false,
                        'depth'          => 1,
                        'walker'         => new Dark_Theme_Simplicity_Walker_Simple_Menu(),
                    ));
                } else {
                    // Fallback to default navigation links if no menu is set
                    ?>
                    <ul class="space-y-3">
                        <li><a href="<?php echo esc_url(home_url('/')); ?>" class="text-light-100/70 hover:text-blue-400 transition-colors">Home</a></li>
                        <li><a href="<?php echo esc_url(home_url('/blog/')); ?>" class="text-light-100/70 hover:text-blue-400 transition-colors">Blog</a></li>
                        <li><a href="#about" class="text-light-100/70 hover:text-blue-400 transition-colors">About</a></li>
                        <li><a href="#contact" class="text-light-100/70 hover:text-blue-400 transition-colors">Contact</a></li>
                    </ul>
                    <?php
                }
                ?>
            </div>
            
            <!-- Connect -->
            <div>
                <div class="text-lg font-bold mb-4 text-white">Connect</div>
                <?php
                if (has_nav_menu('social')) {
                    wp_nav_menu(array(
                        'theme_location' => 'social',
                        'container'      => false,
                        'menu_class'     => 'flex flex-col space-y-3',
                        'fallback_cb'    => false,
                        'depth'          => 1,
                        'walker'         => new Dark_Theme_Simplicity_Walker_Simple_Menu('noopener noreferrer'),
                    ));
                } else {
                    // Fallback to default social links if no menu is set
                    ?>
                    <div class="flex flex-col space-y-3">
                        <?php if (get_theme_mod('dark_theme_simplicity_linkedin_url')): ?>
                        <a href="<?php echo esc_url(get_theme_mod('dark_theme_simplicity_linkedin_url')); ?>" class="flex items-center gap-3 text-light-100/70 hover:text-blue-400 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"></path>
                                <rect x="2" y="9" width="4" height="12"></rect>
                                <circle cx="4" cy="4" r="2"></circle>
                            </svg>
                            <span>LinkedIn</span>
                        </a>
                        <?php endif; ?>
                        <?php if (get_theme_mod('dark_theme_simplicity_twitter_url')): ?>
                        <a href="<?php echo esc_url(get_theme_mod('dark_theme_simplicity_twitter_url')); ?>" class="flex items-center gap-3 text-light-100/70 hover:text-blue-400 transition-colors">
                            <!-- X (Twitter) Icon -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M4 4l11.5 11.5"></path>
                                <path d="M20 4L8.5 15.5"></path>
                                <path d="M4 20l7.5-7.5"></path>
                                <path d="M12.5 15.5L20 20"></path>
                            </svg>
                            <span>Twitter</span>
                        </a>
                        <?php endif; ?>
                        <?php if (!get_theme_mod('dark_theme_simplicity_linkedin_url') && !get_theme_mod('dark_theme_simplicity_twitter_url')): ?>
                        <p class="text-light-100/50 italic">Add social links in Customizer or create a Social menu</p>
                        <?php endif; ?>
                    </div>
                    <?php
                }
                ?>
            </div>
        </div>
        
        <!-- Footer Bottom -->
        <div class="pt-8 flex flex-col md:flex-row justify-between items-center border-t border-white/10">
            <p class="text-light-100/50 text-sm mb-4 md:mb-0">
                © <?php echo date('Y'); ?> <?php echo esc_html(get_theme_mod('dark_theme_simplicity_footer_copyright', 'Brad Daiber')); ?>. All rights reserved.
            </p>
            
            <?php if (has_nav_menu('legal')): ?>
                <?php wp_nav_menu(array(
                    'theme_location' => 'legal',
                    'container'      => 'div',
                    'container_class'=> 'flex space-x-6',
                    'menu_class'     => '',
                    'fallback_cb'    => false,
                    'depth'          => 1,
                    'walker'         => new Dark_Theme_Simplicity_Walker_Legal_Menu(),
                )); ?>
            <?php else: ?>
                <div class="flex space-x-6">
                    <a href="<?php echo esc_url(get_theme_mod('dark_theme_simplicity_privacy_url', '#')); ?>" class="text-light-100/50 hover:text-light-100 text-sm"><?php echo esc_html(get_theme_mod('dark_theme_simplicity_privacy_text', 'Privacy Policy')); ?></a>
                    <a href="<?php echo esc_url(get_theme_mod('dark_theme_simplicity_terms_url', '#')); ?>" class="text-light-100/50 hover:text-light-100 text-sm"><?php echo esc_html(get_theme_mod('dark_theme_simplicity_terms_text', 'Terms of Service')); ?></a>
                    <a href="<?php echo esc_url(get_theme_mod('dark_theme_simplicity_cookie_url', '#')); ?>" class="text-light-100/50 hover:text-light-100 text-sm"><?php echo esc_html(get_theme_mod('dark_theme_simplicity_cookie_text', 'Cookie Policy')); ?></a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</footer>

<?php wp_footer(); ?>

<?php if (is_page() && !is_front_page()): ?>
<script>
// Fix for duplicate empty button widget in page templates
jQuery(document).ready(function($) {
    // Target all Button widgets that could be problematic
    $('.page-content .wp-block-button__link').each(function() {
        var $button = $(this);
        var buttonText = $button.text().trim();
        
        // If button text is just "Button" or contains "?>"
        if (buttonText === 'Button' || buttonText.indexOf('?>') !== -1 || buttonText === '') {
            // Find the closest widget container and hide it
            $button.closest('.widget').hide();
        }
    });
    
    // Hide any duplicate widget areas (second widget area)
    if ($('.page-content .widget-area').length > 1) {
        $('.page-content .widget-area:not(:first)').hide();
    }
    
    // Specifically target PHP error sidebar
    if ($('#secondary').length > 1) {
        $('#secondary:nth-child(2)').hide();
    }
});
</script>
<?php endif; ?>

</body>
</html>