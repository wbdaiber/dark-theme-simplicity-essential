<?php
/**
 * Logo Bar Section — "Featured In" / "Worked With"
 * Only renders when logos are configured in the customizer.
 *
 * @package Dark_Theme_Simplicity
 */

$logo_items_json = get_theme_mod('dark_theme_simplicity_logo_items', '');
$logo_items = ! empty( $logo_items_json ) ? json_decode( $logo_items_json, true ) : array();

if ( empty( $logo_items ) || ! is_array( $logo_items ) ) {
    return; // Nothing to show — section stays hidden
}

$logo_title = get_theme_mod( 'dark_theme_simplicity_logo_bar_title', 'Trusted By' );
?>

<!-- Logo Bar Section -->
<section class="py-12 px-4 sm:px-6 lg:px-8 bg-dark-200 logo-bar-section" aria-label="<?php echo esc_attr( $logo_title ); ?>">
    <div class="container mx-auto max-w-5xl">
        <p class="text-center text-sm font-medium text-light-100/50 uppercase tracking-widest mb-8">
            <?php echo esc_html( $logo_title ); ?>
        </p>
        <div class="flex flex-wrap items-center justify-center gap-8 md:gap-12">
            <?php foreach ( $logo_items as $logo ) :
                if ( ! is_array( $logo ) || empty( $logo['url'] ) ) continue;
                $alt  = isset( $logo['name'] ) ? $logo['name'] : 'Logo';
                $link = isset( $logo['link'] ) ? $logo['link'] : '';
            ?>
                <?php if ( $link ) : ?>
                    <a href="<?php echo esc_url( $link ); ?>" target="_blank" rel="noopener noreferrer" class="logo-bar-item">
                <?php else : ?>
                    <div class="logo-bar-item">
                <?php endif; ?>

                <img src="<?php echo esc_url( $logo['url'] ); ?>"
                     alt="<?php echo esc_attr( $alt ); ?>"
                     class="h-8 md:h-10 w-auto logo-bar-logo"
                     loading="lazy">

                <?php if ( $link ) : ?>
                    </a>
                <?php else : ?>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>
</section>
