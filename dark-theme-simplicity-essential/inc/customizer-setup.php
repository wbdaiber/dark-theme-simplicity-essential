<?php
/**
 * Customizer setup for Dark Theme Simplicity
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Sanitize yes/no values
 * This function must be defined before it's used by any other code
 */
function dark_theme_simplicity_sanitize_yes_no($input) {
    $valid = array('yes', 'no');
    
    if (in_array($input, $valid, true)) {
        return $input;
    }
    
    return 'yes';
}

/**
 * Sanitize checkbox values
 */
function dark_theme_simplicity_sanitize_checkbox($input) {
    return (isset($input) && true == $input) ? true : false;
}

/**
 * Add customizer descriptions to sections
 */
function dark_theme_simplicity_customizer_descriptions($wp_customize) {
    // Site Identity Section
    $wp_customize->get_section('title_tagline')->description = __('Customize your site identity including your logo, site title, and tagline.', 'dark-theme-simplicity');
    
    // Colors Section
    if ($wp_customize->get_section('colors')) {
        $wp_customize->get_section('colors')->description = __('Customize the colors of your theme. These settings affect the overall color scheme of your site.', 'dark-theme-simplicity');
    }
    
    // Homepage Settings
    if ($wp_customize->get_section('static_front_page')) {
        $wp_customize->get_section('static_front_page')->description = __('Choose what to display on your homepage: your latest posts or a static page.', 'dark-theme-simplicity');
    }
    
    // Header Section
    if ($wp_customize->get_section('header_image')) {
        $wp_customize->get_section('header_image')->description = __('Customize your header appearance including background image and text colors.', 'dark-theme-simplicity');
    }
    
    // Background Section
    if ($wp_customize->get_section('background_image')) {
        $wp_customize->get_section('background_image')->description = __('Set a background image or color for your site. This will be visible on boxed layouts.', 'dark-theme-simplicity');
    }
}
add_action('customize_register', 'dark_theme_simplicity_customizer_descriptions', 20);

/**
 * Add loading states to customizer repeater controls
 */
function dark_theme_simplicity_customizer_controls_scripts() {
    // Add loading state CSS
    $loading_css = "
        .customize-control-dark-theme-repeater .repeater-loading {
            display: none;
            color: #0085ba;
            margin-left: 5px;
            font-style: italic;
        }
        .customize-control-dark-theme-repeater .repeater-loading.active {
            display: inline;
        }
        .customize-control-dark-theme-repeater .action-button.loading {
            opacity: 0.5;
            cursor: not-allowed;
        }
        .customize-control-dark-theme-repeater .repeater-row {
            position: relative;
        }
        .customize-control-dark-theme-repeater .repeater-row-loading {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255,255,255,0.7);
            display: none;
            z-index: 10;
            align-items: center;
            justify-content: center;
        }
        .customize-control-dark-theme-repeater .repeater-row-loading.active {
            display: flex;
        }
    ";
    wp_add_inline_style('customize-controls', $loading_css);
    
    // Add loading state JavaScript
    $loading_js = "
        jQuery(document).ready(function($) {
            // Add loading states to repeater controls
            $(document).on('click', '.customize-control-dark-theme-repeater .add-new-repeater-row', function() {
                var button = $(this);
                var loadingText = button.data('loading-text') || 'Adding...';
                var origText = button.text();
                
                // Add loading state
                button.addClass('loading').prop('disabled', true);
                button.siblings('.repeater-loading').text(loadingText).addClass('active');
                
                // Remove loading state after action completes (1 second for demo)
                setTimeout(function() {
                    button.removeClass('loading').prop('disabled', false);
                    button.siblings('.repeater-loading').removeClass('active');
                }, 1000);
            });
            
            // Add loading state to delete operations
            $(document).on('click', '.customize-control-dark-theme-repeater .repeater-row-remove', function() {
                var row = $(this).closest('.repeater-row');
                var loadingDiv = $('<div class=\"repeater-row-loading\"><span class=\"spinner is-active\"></span></div>');
                
                // Add loading overlay
                row.append(loadingDiv);
                loadingDiv.addClass('active');
                
                // Remove loading state after action completes (500ms for demo)
                setTimeout(function() {
                    loadingDiv.removeClass('active');
                }, 500);
            });
        });
    ";
    wp_add_inline_script('customize-controls', $loading_js);
}
add_action('customize_controls_enqueue_scripts', 'dark_theme_simplicity_customizer_controls_scripts');

/**
 * Add ARIA labels to customizer controls for accessibility
 */
function dark_theme_simplicity_customizer_aria_labels() {
    $aria_js = "
        jQuery(document).ready(function($) {
            // Add ARIA attributes to repeater rows
            $('.customize-control-dark-theme-repeater').each(function(index) {
                var control = $(this);
                var controlID = 'repeater-control-' + index;
                
                // Add IDs and ARIA attributes to each repeater row
                control.find('.repeater-row').each(function(rowIndex) {
                    var row = $(this);
                    var rowID = controlID + '-row-' + rowIndex;
                    var toggleButton = row.find('.repeater-row-header');
                    var contentDiv = row.find('.repeater-row-content');
                    
                    // Set IDs
                    row.attr('id', rowID);
                    contentDiv.attr('id', rowID + '-content');
                    
                    // Set ARIA attributes
                    toggleButton.attr({
                        'aria-expanded': row.hasClass('expanded') ? 'true' : 'false',
                        'aria-controls': rowID + '-content'
                    });
                    
                    // Set ARIA for remove button
                    row.find('.repeater-row-remove').attr('aria-label', 'Remove this item');
                });
                
                // Event listener for toggle
                control.on('click', '.repeater-row-header', function() {
                    var button = $(this);
                    var expanded = button.attr('aria-expanded') === 'true';
                    button.attr('aria-expanded', !expanded);
                });
            });
        });
    ";
    wp_add_inline_script('customize-controls', $aria_js);
}
add_action('customize_controls_enqueue_scripts', 'dark_theme_simplicity_customizer_aria_labels');

/**
 * Add missing customizer settings
 */
function dark_theme_simplicity_add_widget_settings($wp_customize) {
    // Default setting for Sidebar Widgets
    // Check if the setting already exists before adding it
    if (!$wp_customize->get_setting('dark_theme_simplicity_default_show_widgets')) {
        $wp_customize->add_setting('dark_theme_simplicity_default_show_widgets', array(
            'default'           => 'yes',
            'sanitize_callback' => 'dark_theme_simplicity_sanitize_yes_no',
            'type'              => 'theme_mod',
        ));
        
        $wp_customize->add_control('dark_theme_simplicity_default_show_widgets', array(
            'label'    => __('Show Sidebar Widgets by default', 'dark-theme-simplicity'),
            'section'  => 'static_front_page',
            'priority' => 20, 
            'type'     => 'radio',
            'choices'  => array(
                'yes' => __('Yes', 'dark-theme-simplicity'),
                'no'  => __('No', 'dark-theme-simplicity'),
            ),
        ));
    }
}
add_action('customize_register', 'dark_theme_simplicity_add_widget_settings', 30); 