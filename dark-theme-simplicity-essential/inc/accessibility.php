<?php
/**
 * Accessibility improvements for Dark Theme Simplicity
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Add skip links for accessibility
 */
function dark_theme_simplicity_skip_links() {
    $skip_links = array(
        '#content' => __('Skip to main content', 'dark-theme-simplicity'),
        '#primary-navigation' => __('Skip to navigation', 'dark-theme-simplicity'),
        '#what-we-do' => __('Skip to services', 'dark-theme-simplicity'),
        '#about' => __('Skip to about section', 'dark-theme-simplicity'),
        '#contact' => __('Skip to contact', 'dark-theme-simplicity'),
        '#footer' => __('Skip to footer', 'dark-theme-simplicity')
    );
    
    echo '<div class="skip-links">';
    foreach ($skip_links as $target => $text) {
        echo '<a class="skip-link screen-reader-text" href="' . esc_attr($target) . '">' . esc_html($text) . '</a>';
    }
    echo '</div>';
}
add_action('wp_body_open', 'dark_theme_simplicity_skip_links', 5);

/**
 * Add screen reader text styles
 */
function dark_theme_simplicity_accessibility_styles() {
    $styles = '
        /* Accessibility styles */
        .screen-reader-text {
            border: 0;
            clip: rect(1px, 1px, 1px, 1px);
            clip-path: inset(50%);
            height: 1px;
            margin: -1px;
            overflow: hidden;
            padding: 0;
            position: absolute !important;
            width: 1px;
            word-wrap: normal !important;
        }
        
        .screen-reader-text:focus {
            background-color: #0085ff;
            clip: auto !important;
            clip-path: none;
            color: #ffffff;
            display: block;
            font-size: 1rem;
            font-weight: 600;
            height: auto;
            left: 5px;
            line-height: normal;
            padding: 15px 23px 14px;
            text-decoration: none;
            top: 5px;
            width: auto;
            z-index: 100000;
            border-radius: 4px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        
        /* Focus styles for keyboard navigation */
        a:focus,
        button:focus,
        input:focus,
        textarea:focus,
        select:focus,
        [tabindex="0"]:focus {
            outline: 2px solid #0085ff;
            outline-offset: 2px;
        }
        
        /* Skip link styling when focused */
        .skip-link.screen-reader-text:focus {
            margin: 0;
        }
        
        /* Skip links container */
        .skip-links {
            position: absolute;
            top: 0;
            left: 0;
            z-index: 100001;
        }
        
        .skip-links .skip-link:nth-child(2):focus {
            top: 60px;
        }
        
        .skip-links .skip-link:nth-child(3):focus {
            top: 120px;
        }
        
        .skip-links .skip-link:nth-child(4):focus {
            top: 180px;
        }
        
        .skip-links .skip-link:nth-child(5):focus {
            top: 240px;
        }
        
        .skip-links .skip-link:nth-child(6):focus {
            top: 300px;
        }
        
        /* ARIA live regions for dynamic content */
        .aria-live-region {
            position: absolute;
            left: -10000px;
            width: 1px;
            height: 1px;
            overflow: hidden;
        }
        
        /* Form validation styling */
        .form-error {
            color: #dc2626;
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }
        
        .form-success {
            color: #059669;
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }
        
        /* Invalid field styling */
        input:invalid:not(:focus):not(:placeholder-shown),
        textarea:invalid:not(:focus):not(:placeholder-shown) {
            border-color: #dc2626;
            box-shadow: 0 0 0 1px #dc2626;
        }
        
        /* Valid field styling */
        input:valid:not(:placeholder-shown),
        textarea:valid:not(:placeholder-shown) {
            border-color: #059669;
        }
    ';
    
    // Don't add inline styles on single posts - they should be in single-consolidated.css
    if (!is_singular('post')) {
        wp_add_inline_style('dark-theme-simplicity-style', $styles);
    }
}
add_action('wp_enqueue_scripts', 'dark_theme_simplicity_accessibility_styles', 20);

/**
 * Add aria-label to read more links
 */
function dark_theme_simplicity_accessible_read_more_link($link) {
    // Get the post title
    $post_title = get_the_title();
    
    // Replace the default "Read more" text with an accessible version
    return str_replace(
        'Read more',
        sprintf(
            '<span class="screen-reader-text">%s </span>%s<span class="screen-reader-text"> %s</span>',
            __('Read more about', 'dark-theme-simplicity'),
            __('Read more', 'dark-theme-simplicity'),
            $post_title
        ),
        $link
    );
}
add_filter('the_content_more_link', 'dark_theme_simplicity_accessible_read_more_link');

/**
 * Add ARIA live region for dynamic announcements
 */
function dark_theme_simplicity_add_aria_live_region() {
    echo '<div id="aria-live-region" class="aria-live-region" aria-live="polite" aria-atomic="false"></div>';
    echo '<div id="aria-live-region-assertive" class="aria-live-region" aria-live="assertive" aria-atomic="false"></div>';
}
add_action('wp_footer', 'dark_theme_simplicity_add_aria_live_region');

/**
 * Add focus handling for keyboard navigation and form validation
 */
function dark_theme_simplicity_keyboard_navigation_js() {
    $script = "
        (function() {
            // Add a class to indicate keyboard navigation
            var isUsingKeyboard = false;
            
            window.addEventListener('keydown', function(e) {
                if (e.key === 'Tab') {
                    isUsingKeyboard = true;
                    document.body.classList.add('is-using-keyboard');
                }
            });
            
            window.addEventListener('mousedown', function() {
                isUsingKeyboard = false;
                document.body.classList.remove('is-using-keyboard');
            });
            
            // Make dropdown menus accessible via keyboard
            var menuItems = document.querySelectorAll('.menu-item-has-children > a');
            
            menuItems.forEach(function(menuItem) {
                menuItem.setAttribute('aria-expanded', 'false');
                
                menuItem.addEventListener('click', function(event) {
                    var expanded = this.getAttribute('aria-expanded') === 'true';
                    this.setAttribute('aria-expanded', !expanded);
                });
            });
            
            // Form validation and ARIA live region announcements
            function announceToScreenReader(message, priority) {
                var liveRegion = priority === 'assertive' 
                    ? document.getElementById('aria-live-region-assertive')
                    : document.getElementById('aria-live-region');
                    
                if (liveRegion) {
                    liveRegion.textContent = '';
                    setTimeout(function() {
                        liveRegion.textContent = message;
                    }, 100);
                }
            }
            
            // Copy button success feedback
            window.announceSuccess = function(message) {
                announceToScreenReader(message || 'Link copied to clipboard', 'polite');
            };
            
            // Form validation feedback
            var forms = document.querySelectorAll('form');
            forms.forEach(function(form) {
                form.addEventListener('submit', function(e) {
                    var invalidFields = form.querySelectorAll(':invalid');
                    if (invalidFields.length > 0) {
                        announceToScreenReader('Please correct the errors in the form', 'assertive');
                    }
                });
            });
            
            // Real-time validation feedback
            var inputs = document.querySelectorAll('input[required], textarea[required]');
            inputs.forEach(function(input) {
                input.addEventListener('blur', function() {
                    if (this.validity.valid && this.value.trim() !== '') {
                        announceToScreenReader(this.labels[0].textContent + ' is valid', 'polite');
                    } else if (!this.validity.valid) {
                        announceToScreenReader(this.labels[0].textContent + ' has an error', 'assertive');
                    }
                });
            });
        })();
    ";
    
    wp_add_inline_script('dark-theme-simplicity-script', $script);
}
add_action('wp_enqueue_scripts', 'dark_theme_simplicity_keyboard_navigation_js');

/**
 * Add language attributes to HTML tag
 */
function dark_theme_simplicity_language_attributes() {
    add_filter('language_attributes', function($output) {
        return $output . ' class="no-js"';
    });
}
add_action('after_setup_theme', 'dark_theme_simplicity_language_attributes');

/**
 * Remove 'no-js' class from HTML element when JavaScript is available
 */
function dark_theme_simplicity_js_detection() {
    $script = "document.documentElement.className = document.documentElement.className.replace('no-js', 'js');";
    wp_add_inline_script('dark-theme-simplicity-script', $script, 'before');
}
add_action('wp_enqueue_scripts', 'dark_theme_simplicity_js_detection', 5);