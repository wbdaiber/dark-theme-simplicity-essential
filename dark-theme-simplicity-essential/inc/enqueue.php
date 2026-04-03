<?php
/**
* Enqueue scripts and styles for Dark Theme Simplicity
* FINAL VERSION - All conflicts resolved
* Includes Critical CSS integration
*/

if (!defined('ABSPATH')) {
   exit; // Exit if accessed directly
}

// Include Critical CSS functionality
require_once get_template_directory() . '/inc/critical-css.php';

/**
* Enqueue scripts and styles - REFACTORED VERSION WITH CRITICAL CSS
*/
function dark_theme_simplicity_scripts() {
   $theme_ver = wp_get_theme()->get('Version');

   // === CSS FILES (render-blocking, deterministic load order) ===
   // Inline critical CSS handles first paint; these load the full styles.

   // 1. Design tokens (CSS custom properties)
   wp_enqueue_style('dts-tokens', get_template_directory_uri() . '/assets/css/tokens.css', array(), filemtime(get_template_directory() . '/assets/css/tokens.css') ?: $theme_ver);

   // 2. Element resets and base typography
   wp_enqueue_style('dts-reset', get_template_directory_uri() . '/assets/css/reset.css', array('dts-tokens'), filemtime(get_template_directory() . '/assets/css/reset.css') ?: $theme_ver);

   // 3. Main theme stylesheet — Tailwind v4 utilities (REQUIRED by WordPress)
   wp_enqueue_style('dark-theme-simplicity-style', get_stylesheet_uri(), array('dts-reset'), filemtime(get_template_directory() . '/style.css') ?: $theme_ver);

   // 3b. Extra utilities missing from the v3 Tailwind compile
   wp_enqueue_style('dts-utilities-extra', get_template_directory_uri() . '/assets/css/utilities-extra.css', array('dark-theme-simplicity-style'), filemtime(get_template_directory() . '/assets/css/utilities-extra.css') ?: $theme_ver);

   // 4. Reusable UI components (includes layout containers)
   wp_enqueue_style('dts-components', get_template_directory_uri() . '/assets/css/components.css', array('dts-utilities-extra'), filemtime(get_template_directory() . '/assets/css/components.css') ?: $theme_ver);

   // 5. Header styles
   wp_enqueue_style('dts-header', get_template_directory_uri() . '/assets/css/header.css', array('dts-components'), filemtime(get_template_directory() . '/assets/css/header.css') ?: $theme_ver);

   // 6. Conversion CTAs
   wp_enqueue_style('dts-conversion-cta', get_template_directory_uri() . '/assets/css/conversion-cta.css', array('dts-header'), filemtime(get_template_directory() . '/assets/css/conversion-cta.css') ?: $theme_ver);

   // Print styles
   wp_enqueue_style('dts-print', get_template_directory_uri() . '/assets/css/print.css', array(), filemtime(get_template_directory() . '/assets/css/print.css') ?: $theme_ver, 'print');

   // Page-specific styles (depend on components)
   if (is_front_page()) {
       wp_enqueue_style('dts-homepage', get_template_directory_uri() . '/assets/css/pages/homepage.css', array('dts-components'), filemtime(get_template_directory() . '/assets/css/pages/homepage.css') ?: $theme_ver);
   }

   if (is_single()) {
       wp_enqueue_style('dts-single-post', get_template_directory_uri() . '/assets/css/pages/single-post.css', array('dts-components'), filemtime(get_template_directory() . '/assets/css/pages/single-post.css') ?: $theme_ver);
   }

   if (is_home() || is_archive() || is_search()) {
       wp_enqueue_style('dts-archive', get_template_directory_uri() . '/assets/css/pages/archive.css', array('dts-components'), filemtime(get_template_directory() . '/assets/css/pages/archive.css') ?: $theme_ver);
   }

   // === JAVASCRIPT FILES (NEW MODULAR STRUCTURE) ===
  
   // Configuration (loaded first, no dependencies)
   wp_enqueue_script(
       'dts-config',
       get_template_directory_uri() . '/assets/js/config.js',
       array(),
       filemtime(get_template_directory() . '/assets/js/config.js') ?: $theme_ver,
       true
   );

   // Core utilities (depends on config)
   wp_enqueue_script(
       'dts-core',
       get_template_directory_uri() . '/assets/js/core.js',
       array('dts-config'),
       filemtime(get_template_directory() . '/assets/js/core.js') ?: $theme_ver,
       true
   );

   // Navigation functionality (header, mobile menu)
   wp_enqueue_script(
       'dts-navigation',
       get_template_directory_uri() . '/assets/js/navigation.js',
       array('dts-config', 'dts-core'),
       filemtime(get_template_directory() . '/assets/js/navigation.js') ?: $theme_ver,
       true
   );

   // Content functionality (videos, smooth scroll, TOC)
   wp_enqueue_script(
       'dts-content',
       get_template_directory_uri() . '/assets/js/content.js',
       array('dts-config', 'dts-core'),
       filemtime(get_template_directory() . '/assets/js/content.js') ?: $theme_ver,
       true
   );

   // Single post functionality (share buttons, blog features)
   if (is_single()) {
       wp_enqueue_script(
           'dts-single-post',
           get_template_directory_uri() . '/assets/js/pages/single-post.js',
           array('dts-config', 'dts-core'),
           filemtime(get_template_directory() . '/assets/js/pages/single-post.js') ?: $theme_ver,
           true
       );
   }

   // Editor customizations (only in admin/editor)
   if (is_admin()) {
       wp_enqueue_script(
           'dark-theme-editor-customizations',
           get_template_directory_uri() . '/assets/js/editor-customizations.js',
           array('wp-blocks', 'wp-dom-ready', 'wp-edit-post'),
           filemtime(get_template_directory() . '/assets/js/editor-customizations.js') ?: $theme_ver,
           true
       );
   }
}
add_action('wp_enqueue_scripts', 'dark_theme_simplicity_scripts');

/**
* Function to check if current page is a child of Tools template
*/
function is_tools_child_page() {
   if (!is_page()) {
       return false;
   }
  
   $page_id = get_the_ID();
   $parent_id = wp_get_post_parent_id($page_id);

   if (!$parent_id) {
       return false;
   }

   $template = get_page_template_slug($parent_id);
   return $template === 'page-templates/tools-template.php';
}

/**
* Add body class for tools child pages
*/
function dark_theme_simplicity_body_classes($classes) {
   if (function_exists('is_tools_child_page') && is_tools_child_page()) {
       $classes[] = 'tools-child-page';
   }
   return $classes;
}
add_filter('body_class', 'dark_theme_simplicity_body_classes');

/**
* Enqueue customizer scripts (CONSOLIDATED VERSION)
*/
function dark_theme_simplicity_customize_controls_enqueue_scripts() {
   $theme_ver = wp_get_theme()->get('Version');

   // Customizer styles (only if files exist)
   if (file_exists(get_template_directory() . '/assets/css/customizer.css')) {
       wp_enqueue_style(
           'dark-theme-customizer-style',
           get_template_directory_uri() . '/assets/css/customizer.css',
           array(),
           $theme_ver
       );
   }

   if (file_exists(get_template_directory() . '/assets/css/customizer-repeater.css')) {
       wp_enqueue_style(
           'dark-theme-customizer-repeater',
           get_template_directory_uri() . '/assets/css/customizer-repeater.css',
           array(),
           $theme_ver
       );
   }

   if (file_exists(get_template_directory() . '/assets/css/customizer-fixes.css')) {
       wp_enqueue_style(
           'dark-theme-customizer-fixes',
           get_template_directory_uri() . '/assets/css/customizer-fixes.css',
           array(),
           $theme_ver
       );
   }

   // Consolidated customizer JavaScript
   wp_enqueue_script(
       'dark-theme-customizer-consolidated',
       get_template_directory_uri() . '/assets/js/pages/customizer.js',
       array('jquery', 'customize-controls', 'jquery-ui-sortable'),
       $theme_ver,
       true
   );
}
add_action('customize_controls_enqueue_scripts', 'dark_theme_simplicity_customize_controls_enqueue_scripts');

/**
 * Dequeue jQuery on front page — homepage JS doesn't use it.
 * jQuery is only needed for blog-consolidated.js on single posts.
 */
function dts_optimize_script_loading() {
    if (is_front_page()) {
        wp_dequeue_script('jquery');
        wp_deregister_script('jquery');
    }
}
add_action('wp_enqueue_scripts', 'dts_optimize_script_loading', 99);

/**
 * Add defer attribute to theme scripts to prevent render blocking.
 * Defer lets the browser parse HTML and render content before executing JS.
 */
function dts_add_defer_to_scripts($tag, $handle, $src) {
    $defer_handles = array(
        'dts-config',
        'dts-core',
        'dts-navigation',
        'dts-content',
        'dts-single-post',
    );

    if (in_array($handle, $defer_handles)) {
        return str_replace(' src=', ' defer src=', $tag);
    }

    return $tag;
}
add_filter('script_loader_tag', 'dts_add_defer_to_scripts', 10, 3);