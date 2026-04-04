<?php
/**
 * Related Posts CTA Card — "Work With Me" conversion card
 * Sits as the first item in the related posts grid
 * Differentiated from sidebar CTA with purple accent, social proof, gradient button
 *
 * @package Dark_Theme_Simplicity
 */

$contact_email = get_theme_mod( 'dark_theme_simplicity_contact_email', 'brad.daiber1@gmail.com' );
$linkedin_url  = get_theme_mod( 'dark_theme_simplicity_contact_linkedin', 'linkedin.com/in/brad-daiber-96884756/' );
if ( strpos( $linkedin_url, 'http' ) !== 0 ) {
    $linkedin_url = 'https://' . $linkedin_url;
}
?>

<div class="related-post-card related-cta-card hidden lg:block">
    <div class="related-cta-inner">
        <div class="related-cta-accent"></div>
        <div class="related-cta-content">
            <span class="related-cta-label">
                <?php esc_html_e( "Let's Connect", 'dark-theme-simplicity-3' ); ?>
            </span>
            <div class="related-cta-title">
                <?php esc_html_e( 'Work With Me', 'dark-theme-simplicity-3' ); ?>
            </div>
            <p class="related-cta-desc">
                <?php esc_html_e( 'Content strategist and web developer helping B2B companies drive measurable growth.', 'dark-theme-simplicity-3' ); ?>
            </p>
            <div class="related-cta-proof">
                <div class="related-cta-proof-item">
                    <span class="related-cta-proof-number">10+</span>
                    <span class="related-cta-proof-label"><?php esc_html_e( 'Years Experience', 'dark-theme-simplicity-3' ); ?></span>
                </div>
                <div class="related-cta-proof-divider"></div>
                <div class="related-cta-proof-item">
                    <span class="related-cta-proof-number">B2B</span>
                    <span class="related-cta-proof-label"><?php esc_html_e( 'SaaS Focus', 'dark-theme-simplicity-3' ); ?></span>
                </div>
            </div>
        </div>
        <div class="related-cta-actions">
            <a href="mailto:<?php echo esc_attr( $contact_email ); ?>" class="related-cta-primary">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                    <polyline points="22,6 12,13 2,6"></polyline>
                </svg>
                <?php esc_html_e( 'Get In Touch', 'dark-theme-simplicity-3' ); ?>
            </a>
            <a href="<?php echo esc_url( $linkedin_url ); ?>"
               target="_blank"
               rel="noopener noreferrer"
               class="related-cta-secondary">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"></path>
                    <rect x="2" y="9" width="4" height="12"></rect>
                    <circle cx="4" cy="4" r="2"></circle>
                </svg>
                <?php esc_html_e( 'LinkedIn', 'dark-theme-simplicity-3' ); ?>
            </a>
        </div>
    </div>
</div>
