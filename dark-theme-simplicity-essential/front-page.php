<?php
get_header();
?>

<main id="content" class="site-main" role="main" aria-label="Main content area">
    <!-- Hero Section -->
    <section class="hero-section relative min-h-screen flex items-center justify-center overflow-hidden -mt-16" role="banner" aria-labelledby="hero-heading">
        <!-- Background Image with Overlay -->
        <?php
        $hero_bg_image = get_theme_mod('dark_theme_simplicity_hero_bg_image', 'https://braddaiber.com/wp-content/uploads/2024/03/shutterstock_134653565-1-scaled.webp');
        ?>
        <div class="absolute inset-0 bg-cover bg-center bg-no-repeat hero-bg hero-bg-loaded">
            <div class="absolute inset-0 bg-gradient-to-b from-dark-200/80 via-dark-200/70 to-dark-200"></div>
        </div>

        <div class="relative z-10 flex flex-col items-center w-full text-center px-4 max-w-4xl mx-auto">
            <h1 id="hero-heading" class="hero-headline animate-fade-in-up leading-none tracking-tight">
                <?php echo esc_html(get_theme_mod('dark_theme_simplicity_hero_heading', 'I Turn Lean Content Teams Into Growth Engines')); ?>
            </h1>
            <p class="hero-subheadline animate-fade-in-up animation-delay-200">
                <?php echo esc_html(get_theme_mod('dark_theme_simplicity_hero_subheading', 'Content marketing leader. 10+ years turning B2B & B2C value propositions into multi-channel strategies that grow revenue.')); ?>
            </p>

            <!-- Two CTAs: Primary action + Secondary (ghost) -->
            <?php
            $cta_primary_text = get_theme_mod('dark_theme_simplicity_hero_cta_primary_text', 'View My Work');
            $cta_primary_url  = get_theme_mod('dark_theme_simplicity_hero_cta_primary_url', '#about');
            $cta_secondary_text = get_theme_mod('dark_theme_simplicity_hero_cta_secondary_text', 'Get In Touch');
            $cta_secondary_url  = get_theme_mod('dark_theme_simplicity_hero_cta_secondary_url', '#contact');
            ?>
            <div class="hero-cta-group animate-fade-in-up animation-delay-300" role="navigation" aria-label="Primary actions">
                <a href="<?php echo esc_url($cta_primary_url); ?>" class="hero-cta-primary" aria-label="<?php echo esc_attr($cta_primary_text); ?>">
                    <?php echo esc_html($cta_primary_text); ?>
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                </a>
                <a href="<?php echo esc_url($cta_secondary_url); ?>" class="hero-cta-secondary" aria-label="<?php echo esc_attr($cta_secondary_text); ?>">
                    <?php echo esc_html($cta_secondary_text); ?>
                </a>
            </div>
        </div>
    </section>

    <?php
    // About Section — personal credibility right after the hook
    include(get_template_directory() . '/template-parts/homepage/section-about.php');

    // Services Section — what you do
    include(get_template_directory() . '/template-parts/homepage/section-services.php');

    // Featured In — publication logos, renders only when configured
    include(get_template_directory() . '/template-parts/homepage/section-featured.php');

    // Approach Section — how you work
    include(get_template_directory() . '/template-parts/homepage/section-approach.php');

    // Contact Section — final CTA
    include(get_template_directory() . '/template-parts/homepage/section-contact.php');
    ?>

</main>

<?php get_footer(); ?>
