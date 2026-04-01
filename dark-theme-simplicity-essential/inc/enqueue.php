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
   // Check if critical CSS file exists for this page type
   $critical_css_file = dts_get_critical_css_file();
   $has_critical_css = !empty($critical_css_file);
   
   if ($has_critical_css) {
       // CRITICAL CSS MODE: Load styles asynchronously via preload hints
       // The actual CSS loading is handled by dts_add_css_preload_hints() in critical-css.php
       
       // Only enqueue print styles normally (they don't block rendering)
       wp_enqueue_style('dts-print', get_template_directory_uri() . '/assets/css/print.css', array(), '1.0.0', 'print');
       
   } else {
       // FALLBACK MODE: Normal CSS loading for when critical CSS is not available
       
       // Base CSS (merged core, layout, components)
       wp_enqueue_style('dts-base', get_template_directory_uri() . '/assets/css/base.css', array(), '2.0.0');
       wp_enqueue_style('dts-responsive', get_template_directory_uri() . '/assets/css/responsive.css', array('dts-base'), '2.0.3');
       
       // Keep header styles separate (well organized)
       wp_enqueue_style('dts-header', get_template_directory_uri() . '/assets/css/header.css', array('dts-base'), '2.0.0');
       wp_enqueue_style('dts-conversion-cta', get_template_directory_uri() . '/assets/css/conversion-cta.css', array('dts-header'), '1.0.0');
       
       // Main theme stylesheet (REQUIRED - contains WordPress theme header)
       wp_enqueue_style('dark-theme-simplicity-style', get_stylesheet_uri(), array('dts-responsive'), '2.0.0');
       
       // Print styles (loaded with media='print' to prevent blocking)
       wp_enqueue_style('dts-print', get_template_directory_uri() . '/assets/css/print.css', array(), '1.0.0', 'print');
      
       // Page-specific styles
       if (is_front_page()) {
           wp_enqueue_style('dts-homepage', get_template_directory_uri() . '/assets/css/pages/homepage.css', array('dts-base'), '2.0.0');
           wp_enqueue_style('dts-hero-cta', get_template_directory_uri() . '/assets/css/hero-cta.css', array('dts-homepage'), '1.0.0');
       }
       
       if (is_single()) {
           wp_enqueue_style('dts-single-post', get_template_directory_uri() . '/assets/css/pages/single-post.css', array('dark-theme-simplicity-style'), '2.0.2');
       }
       
       if (is_home() || is_archive() || is_search()) {
           wp_enqueue_style('dts-archive', get_template_directory_uri() . '/assets/css/pages/archive.css', array('dts-base'), '2.0.0');
       }
   }

   // === JAVASCRIPT FILES (NEW MODULAR STRUCTURE) ===
  
   // Configuration (loaded first, no dependencies)
   wp_enqueue_script(
       'dts-config',
       get_template_directory_uri() . '/assets/js/config.js',
       array(),
       '1.0.0',
       true
   );
  
   // Core utilities (depends on config)
   wp_enqueue_script(
       'dts-core',
       get_template_directory_uri() . '/assets/js/core.js',
       array('dts-config'),
       '2.0.0',
       true
   );
  
   // Navigation functionality (header, mobile menu)
   wp_enqueue_script(
       'dts-navigation',
       get_template_directory_uri() . '/assets/js/navigation.js',
       array('dts-config', 'dts-core'),
       '2.0.0',
       true
   );
  
   // Content functionality (videos, smooth scroll, TOC)
   wp_enqueue_script(
       'dts-content',
       get_template_directory_uri() . '/assets/js/content.js',
       array('dts-config', 'dts-core'),
       '2.0.0',
       true
   );

   // Single post functionality (share buttons, blog features)
   if (is_single()) {
       wp_enqueue_script(
           'dts-single-post',
           get_template_directory_uri() . '/assets/js/pages/single-post.js',
           array('dts-config', 'dts-core'),
           '2.0.0',
           true
       );
   }
  
   // Editor customizations (only in admin/editor)
   if (is_admin()) {
       wp_enqueue_script(
           'dark-theme-editor-customizations',
           get_template_directory_uri() . '/assets/js/editor-customizations.js',
           array('wp-blocks', 'wp-dom-ready', 'wp-edit-post'),
           '1.2.1',
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
   // Customizer styles (only if files exist)
   if (file_exists(get_template_directory() . '/assets/css/customizer.css')) {
       wp_enqueue_style(
           'dark-theme-customizer-style',
           get_template_directory_uri() . '/assets/css/customizer.css',
           array(),
           '1.2.1'
       );
   }
  
   if (file_exists(get_template_directory() . '/assets/css/customizer-repeater.css')) {
       wp_enqueue_style(
           'dark-theme-customizer-repeater',
           get_template_directory_uri() . '/assets/css/customizer-repeater.css',
           array(),
           '1.2.1'
       );
   }
  
   if (file_exists(get_template_directory() . '/assets/css/customizer-fixes.css')) {
       wp_enqueue_style(
           'dark-theme-customizer-fixes',
           get_template_directory_uri() . '/assets/css/customizer-fixes.css',
           array(),
           '1.2.1'
       );
   }
  
   // Consolidated customizer JavaScript
   wp_enqueue_script(
       'dark-theme-customizer-consolidated',
       get_template_directory_uri() . '/assets/js/pages/customizer.js',
       array('jquery', 'customize-controls', 'jquery-ui-sortable'),
       '1.2.1',
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