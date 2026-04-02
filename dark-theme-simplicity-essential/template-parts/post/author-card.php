<?php
/**
 * About Me — Two-column credibility card
 *
 * @package Dark_Theme_Simplicity
 */

$about_image = get_theme_mod( 'dark_theme_simplicity_about_image', get_template_directory_uri() . '/assets/images/about-image.svg' );
?>

<section class="max-w-6xl mx-auto px-4 mt-8 md:mt-12">
    <div class="bg-dark-300 border border-white/10 rounded-xl overflow-hidden">
        <div class="flex flex-col md:flex-row">

            <!-- Image column (40%) -->
            <div class="md:w-2/5">
                <img src="<?php echo esc_url( $about_image ); ?>"
                     alt="Brad Daiber"
                     class="w-full h-full object-cover"
                     style="max-height: 320px;"
                     loading="lazy">
            </div>

            <!-- Text column (60%) -->
            <div class="md:w-3/5 p-6 md:p-8 flex flex-col justify-center">
                <h3 class="text-lg md:text-xl font-bold text-white mb-1">
                    <?php esc_html_e( 'About Me', 'dark-theme-simplicity' ); ?>
                </h3>
                <p class="text-sm text-blue-300 font-medium mb-3">
                    <?php echo esc_html( get_theme_mod( 'dark_theme_simplicity_author_role', 'Content Director & Growth Strategist' ) ); ?>
                </p>
                <p class="text-sm text-light-100/70 leading-relaxed mb-4">
                    <?php echo esc_html( get_theme_mod(
                        'dark_theme_simplicity_author_bio',
                        'I bring this same analytical, data-driven approach to every content strategy engagement. From SEO and organic growth to full-funnel content programs, I help teams turn content into measurable revenue.'
                    ) ); ?>
                </p>

                <div class="flex flex-wrap gap-2">
                    <span class="inline-flex items-center px-2 py-0.5 bg-white/5 rounded text-xs text-light-100/50 border border-white/5">
                        <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="mr-1 text-blue-300" aria-hidden="true"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                        SEO &amp; Content Strategy
                    </span>
                    <span class="inline-flex items-center px-2 py-0.5 bg-white/5 rounded text-xs text-light-100/50 border border-white/5">
                        <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="mr-1 text-blue-300" aria-hidden="true"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                        Data-Driven Growth
                    </span>
                    <span class="inline-flex items-center px-2 py-0.5 bg-white/5 rounded text-xs text-light-100/50 border border-white/5">
                        <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="mr-1 text-blue-300" aria-hidden="true"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                        B2B SaaS
                    </span>
                </div>
            </div>

        </div>
    </div>
</section>
