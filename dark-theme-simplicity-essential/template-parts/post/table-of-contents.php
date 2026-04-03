<?php
/**
 * Table of Contents
 * 
 * @package Dark_Theme_Simplicity
 */

$headings = $args['headings'] ?? array();

if ( empty( $headings ) ) {
    return;
}
?>

<div class="toc-desktop p-1.5 rounded-lg">
    <div class="toc-label toc-heading text-xs font-medium mb-1 px-1" role="heading" aria-level="2">
        <?php esc_html_e( 'Contents', 'dark-theme-simplicity' ); ?>
    </div>
    <ul class="space-y-0.5 toc-list text-xs">
        <?php foreach ( $headings as $heading ) : ?>
            <li class="toc-item text-light-100/80 hover:text-blue-300">
                <a href="#<?php echo esc_attr( $heading['id'] ); ?>" 
                   class="toc-link flex items-start gap-1 transition-colors py-0.5 px-1 hover:bg-dark-300/30 rounded-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-blue-300 toc-caret mt-1 flex-shrink-0">
                        <polyline points="9 18 15 12 9 6"></polyline>
                    </svg>
                    <span class="toc-text break-words"><?php echo esc_html( $heading['text'] ); ?></span>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
</div>