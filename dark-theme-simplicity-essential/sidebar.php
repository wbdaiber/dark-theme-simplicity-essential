<?php
/**
 * The sidebar containing the main widget area.
 *
 * @package Dark_Theme_Simplicity
 */

$show_widgets = true;

if (is_singular()) {
    $raw_setting = get_post_meta(get_the_ID(), '_show_sidebar_widgets', true);
    
    if ($raw_setting === '') {
        $show_sidebar_widgets = get_theme_mod('dark_theme_simplicity_default_show_widgets', 'yes');
        $show_widgets = ($show_sidebar_widgets === 'yes');
    } else {
        $show_widgets = ($raw_setting === 'yes');
    }
}

if (!$show_widgets) {
    return;
}

if (is_single() && is_active_sidebar('sidebar-post')) {
    // Single post sidebar
    ?>
    <aside id="secondary" class="widget-area bg-dark-400 p-6 rounded-lg border border-white/10">
        <?php dynamic_sidebar('sidebar-post'); ?>
    </aside>
    <?php
} elseif (is_page() && is_active_sidebar('sidebar-page')) {
    // Page sidebar
    ?>
    <aside id="secondary" class="widget-area bg-dark-400 p-6 rounded-lg border border-white/10">
        <?php dynamic_sidebar('sidebar-page'); ?>
    </aside>
    <?php
} elseif (is_active_sidebar('sidebar-1')) {
    // Default sidebar
    ?>
    <aside id="secondary" class="widget-area bg-dark-400 p-6 rounded-lg border border-white/10">
        <?php dynamic_sidebar('sidebar-1'); ?>
    </aside>
    <?php
}

 