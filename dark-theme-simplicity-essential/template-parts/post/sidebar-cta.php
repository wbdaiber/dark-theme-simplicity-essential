<?php
/**
 * Sidebar CTA - "Work With Me" card
 * Toptal "Hire the Author" pattern for recruiter conversion
 *
 * @package Dark_Theme_Simplicity
 */

$contact_email = get_theme_mod('dark_theme_simplicity_contact_email', 'brad.daiber1@gmail.com');
$linkedin_url = get_theme_mod('dark_theme_simplicity_contact_linkedin', 'linkedin.com/in/brad-daiber-96884756/');
if (strpos($linkedin_url, 'http') !== 0) {
    $linkedin_url = 'https://' . $linkedin_url;
}
?>

<div class="sidebar-cta-card">
    <div class="sidebar-cta-label">Available for hire</div>
    <h3 class="sidebar-cta-title">Work With Me</h3>
    <p class="sidebar-cta-desc">Looking for a content marketing leader who drives revenue? Let's talk.</p>
    <a href="mailto:<?php echo esc_attr($contact_email); ?>" class="sidebar-cta-primary">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
            <polyline points="22,6 12,13 2,6"></polyline>
        </svg>
        Get In Touch
    </a>
    <a href="<?php echo esc_url($linkedin_url); ?>" class="sidebar-cta-secondary" target="_blank" rel="noopener noreferrer">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"></path>
            <rect x="2" y="9" width="4" height="12"></rect>
            <circle cx="4" cy="4" r="2"></circle>
        </svg>
        LinkedIn
    </a>
</div>
