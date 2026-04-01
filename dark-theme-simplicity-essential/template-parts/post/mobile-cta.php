<?php
/**
 * Mobile CTA - In-content "Work With Me" card
 * Shown below desktop breakpoint where sidebar CTA is hidden
 *
 * @package Dark_Theme_Simplicity
 */

$contact_email = get_theme_mod( 'dark_theme_simplicity_contact_email', 'brad.daiber1@gmail.com' );
$linkedin_url = get_theme_mod( 'dark_theme_simplicity_contact_linkedin', 'linkedin.com/in/brad-daiber-96884756/' );
if ( strpos( $linkedin_url, 'http' ) !== 0 ) {
    $linkedin_url = 'https://' . $linkedin_url;
}
?>

<div class="mobile-cta-card max-w-6xl mx-auto mt-8 px-4">
    <div class="bg-dark-300 border border-white/10 rounded-xl p-6 text-center">
        <span class="inline-block px-3 py-1 bg-blue-300/10 rounded-full text-xs font-medium text-blue-300 border border-blue-300/20 mb-3">
            <?php esc_html_e( 'Available for hire', 'dark-theme-simplicity' ); ?>
        </span>
        <h3 class="text-xl font-bold text-white mb-2">
            <?php esc_html_e( 'Work With Me', 'dark-theme-simplicity' ); ?>
        </h3>
        <p class="text-sm text-light-100/70 mb-4 max-w-md mx-auto">
            <?php esc_html_e( 'Looking for a content marketing leader who drives revenue? Let\'s talk.', 'dark-theme-simplicity' ); ?>
        </p>
        <div class="flex flex-col sm:flex-row gap-3 justify-center">
            <a href="mailto:<?php echo esc_attr( $contact_email ); ?>"
               class="inline-flex items-center justify-center gap-2 bg-blue-300/90 hover:bg-blue-300 text-white font-medium px-5 py-2.5 rounded-lg transition-colors text-sm">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                    <polyline points="22,6 12,13 2,6"></polyline>
                </svg>
                <?php esc_html_e( 'Get In Touch', 'dark-theme-simplicity' ); ?>
            </a>
            <a href="<?php echo esc_url( $linkedin_url ); ?>"
               target="_blank"
               rel="noopener noreferrer"
               class="inline-flex items-center justify-center gap-2 bg-dark-400 hover:opacity-80 text-white font-medium px-5 py-2.5 rounded-lg transition-colors text-sm border border-white/10">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"></path>
                    <rect x="2" y="9" width="4" height="12"></rect>
                    <circle cx="4" cy="4" r="2"></circle>
                </svg>
                <?php esc_html_e( 'LinkedIn', 'dark-theme-simplicity' ); ?>
            </a>
        </div>
    </div>
</div>
