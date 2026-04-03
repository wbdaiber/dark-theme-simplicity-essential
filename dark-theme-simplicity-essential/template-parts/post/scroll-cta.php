<?php
/**
 * Scroll-triggered Conversational CTA (JoshWComeau pattern)
 * Slides in from bottom-left after 60% scroll, dismissible with localStorage memory
 *
 * @package Dark_Theme_Simplicity
 */

$contact_email = get_theme_mod( 'dark_theme_simplicity_contact_email', 'brad.daiber1@gmail.com' );
?>

<div id="scroll-cta" class="scroll-cta" aria-hidden="true">
    <div class="scroll-cta-inner">
        <p class="scroll-cta-text">
            <?php esc_html_e( "Hi there! I'm currently open to new opportunities. Want to connect?", 'dark-theme-simplicity' ); ?>
        </p>
        <div class="scroll-cta-actions">
            <a href="mailto:<?php echo esc_attr( $contact_email ); ?>" class="scroll-cta-yes">
                <?php esc_html_e( "Sure, let's talk!", 'dark-theme-simplicity' ); ?>
            </a>
            <button type="button" class="scroll-cta-no" id="scroll-cta-dismiss">
                <?php esc_html_e( "Nah I'm good", 'dark-theme-simplicity' ); ?>
            </button>
        </div>
    </div>
</div>
