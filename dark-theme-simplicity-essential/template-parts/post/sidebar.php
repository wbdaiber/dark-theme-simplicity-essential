<?php
/**
 * Post Sidebar
 * Version: 2.1 — added Work With Me CTA
 * @package Dark_Theme_Simplicity
 */

$display_settings = $args['display_settings'] ?? array();
$headings = $args['headings'] ?? array();
$layout_classes = $args['layout_classes'] ?? array();
?>

<div class="post-sidebar sidebar-container <?php echo esc_attr( $layout_classes['sidebar_width_class'] ); ?> flex-shrink-0">
    <div class="sticky top-24 space-y-4">

        <!-- Share Buttons -->
        <?php if ( $display_settings['show_share'] === 'yes' ) : ?>
            <?php get_template_part( 'template-parts/post/share-buttons-desktop' ); ?>
        <?php endif; ?>

        <!-- Table of Contents -->
        <?php if ( $display_settings['has_toc'] && $display_settings['show_toc'] === 'yes' ) : ?>
            <?php get_template_part( 'template-parts/post/table-of-contents', null, array(
                'headings' => $headings
            ) ); ?>
        <?php endif; ?>

        <!-- Sidebar Widgets -->
        <?php if ( $display_settings['show_widgets'] === 'yes' ) : ?>
            <?php get_template_part( 'template-parts/post/widgets' ); ?>
        <?php endif; ?>

        <!-- Work With Me CTA -->
        <?php get_template_part( 'template-parts/post/sidebar-cta' ); ?>

    </div>
</div>
