<?php
/**
 * Related Posts CTA Card — "Work With Me" conversion card
 * Sits as the first item in the related posts grid (Detailed pattern)
 *
 * @package Dark_Theme_Simplicity
 */

$contact_email = get_theme_mod( 'dark_theme_simplicity_contact_email', 'brad.daiber1@gmail.com' );
$linkedin_url  = get_theme_mod( 'dark_theme_simplicity_contact_linkedin', 'linkedin.com/in/brad-daiber-96884756/' );
if ( strpos( $linkedin_url, 'http' ) !== 0 ) {
    $linkedin_url = 'https://' . $linkedin_url;
}
?>

<div class="related-post-card">
    <div class="overflow-hidden bg-gradient-to-br from-blue-300/10 to-blue-300/5 border border-blue-300/20 rounded-xl h-full flex flex-col justify-between p-6">

        <!-- Top: label + headline -->
        <div>
            <span class="inline-block px-2 py-0.5 bg-blue-300/20 rounded-full text-xs font-semibold text-blue-300 border border-blue-300/20 mb-4">
                <?php esc_html_e( 'Available for Hire', 'dark-theme-simplicity' ); ?>
            </span>
            <h3 class="text-xl md:text-2xl font-bold text-white mb-2">
                <?php esc_html_e( 'Work With Me', 'dark-theme-simplicity' ); ?>
            </h3>
            <p class="text-sm text-light-100/70 leading-relaxed mb-4">
                <?php esc_html_e( 'Looking for a content marketing leader who drives revenue? Let\'s talk.', 'dark-theme-simplicity' ); ?>
            </p>
        </div>

        <!-- Bottom: action links -->
        <div class="flex flex-col gap-2">
            <a href="mailto:<?php echo esc_attr( $contact_email ); ?>"
               class="inline-flex items-center justify-center gap-2 bg-blue-300/90 hover:bg-blue-300 text-white font-medium px-4 py-2 rounded-lg transition-colors text-sm">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                    <polyline points="22,6 12,13 2,6"></polyline>
                </svg>
                <?php esc_html_e( 'Get In Touch', 'dark-theme-simplicity' ); ?>
            </a>
            <a href="<?php echo esc_url( $linkedin_url ); ?>"
               target="_blank"
               rel="noopener noreferrer"
               class="inline-flex items-center justify-center gap-2 text-light-100/70 hover:text-blue-300 font-medium text-sm transition-colors">
                <?php esc_html_e( 'LinkedIn', 'dark-theme-simplicity' ); ?>
                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <line x1="7" y1="17" x2="17" y2="7"></line>
                    <polyline points="7 7 17 7 17 17"></polyline>
                </svg>
            </a>
        </div>

    </div>
</div>
