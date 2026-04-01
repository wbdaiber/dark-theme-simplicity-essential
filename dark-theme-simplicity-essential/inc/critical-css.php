<?php
/**
 * Critical CSS Integration System
 * 
 * Handles inline critical CSS injection and async loading of remaining CSS
 * 
 * @package Dark_Theme_Simplicity
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Get the critical CSS file path for the current page type
 * 
 * @return string|false Critical CSS file path or false if not found
 */
function dts_get_critical_css_file() {
    $critical_dir = get_template_directory() . '/assets/css/critical/';
    
    if (is_front_page()) {
        $file = $critical_dir . 'homepage.css';
    } elseif (is_single()) {
        $file = $critical_dir . 'single-post.css';
    } elseif (is_home() || is_archive() || is_search()) {
        $file = $critical_dir . 'archive.css';
    } else {
        // Fallback to homepage critical CSS for other page types
        $file = $critical_dir . 'homepage.css';
    }
    
    return file_exists($file) ? $file : false;
}

/**
 * Get the critical CSS content for the current page
 * 
 * @return string Critical CSS content or empty string if not found
 */
function dts_get_critical_css_content() {
    $file = dts_get_critical_css_file();
    
    if (!$file) {
        return '';
    }
    
    $content = file_get_contents($file);
    
    if ($content === false) {
        return '';
    }
    
    // Minify the CSS further if needed (remove extra whitespace)
    $content = preg_replace('/\s+/', ' ', $content);
    $content = trim($content);
    
    return $content;
}

/**
 * Output critical CSS inline in the head
 */
function dts_inline_critical_css() {
    $critical_css = dts_get_critical_css_content();
    
    if (empty($critical_css)) {
        return;
    }
    
    // Add cache busting based on file modification time
    $file = dts_get_critical_css_file();
    $version = $file ? filemtime($file) : time();
    
    echo sprintf(
        '<!-- Critical CSS (inline) - Generated: %s -->' . "\n" .
        '<style id="critical-css" data-version="%s">' . "\n" .
        '%s' . "\n" .
        '</style>' . "\n",
        date('Y-m-d H:i:s', $version),
        $version,
        $critical_css
    );
}

/**
 * Add resource hints for performance optimization
 */
function dts_add_resource_hints() {
    // DNS prefetch for external resources
    $external_domains = array(
        'braddaiber.com',
        'fonts.googleapis.com',
        'fonts.gstatic.com'
    );
    
    foreach ($external_domains as $domain) {
        echo sprintf('<link rel="dns-prefetch" href="//%s">' . "\n", esc_attr($domain));
    }
    
    // Preconnect to hero image domain for faster loading
    echo '<link rel="preconnect" href="//braddaiber.com" crossorigin>' . "\n";

    // Preload hero background image on front page for faster LCP (responsive)
    if (is_front_page()) {
        $upload_base = 'https://braddaiber.com/wp-content/uploads/2024/03/shutterstock_134653565-1';
        $sizes = array(
            array('file' => '-768x512.webp',    'media' => '(max-width: 767px)'),
            array('file' => '-1024x683.webp',   'media' => '(min-width: 768px) and (max-width: 1023px)'),
            array('file' => '-1536x1024.webp',  'media' => '(min-width: 1024px) and (max-width: 1535px)'),
            array('file' => '-scaled.webp',      'media' => '(min-width: 1536px)'),
        );
        foreach ($sizes as $size) {
            echo sprintf(
                '<link rel="preload" href="%s" as="image" media="%s" fetchpriority="high">' . "\n",
                esc_url($upload_base . $size['file']),
                esc_attr($size['media'])
            );
        }
    }
}

/**
 * Add preload hints for CSS files
 */
function dts_add_css_preload_hints() {
    $theme_ver = wp_get_theme()->get( 'Version' );

    // Get the CSS files that will be loaded asynchronously
    $css_files = array();

    // Base CSS files with versioning
    $css_files[] = array(
        'href' => get_template_directory_uri() . '/assets/css/base.css',
        'priority' => 'high'
    );
    $css_files[] = array(
        'href' => get_template_directory_uri() . '/assets/css/header.css',
        'priority' => 'high'
    );
    $css_files[] = array(
        'href' => get_template_directory_uri() . '/assets/css/conversion-cta.css',
        'priority' => 'high'
    );
    $css_files[] = array(
        'href' => get_template_directory_uri() . '/assets/css/responsive.css',
        'priority' => 'medium'
    );
    $css_files[] = array(
        'href' => get_stylesheet_uri(),
        'priority' => 'medium'
    );

    // Page-specific CSS
    if (is_front_page()) {
        $css_files[] = array(
            'href' => get_template_directory_uri() . '/assets/css/pages/homepage.css',
            'priority' => 'high'
        );
        $css_files[] = array(
            'href' => get_template_directory_uri() . '/assets/css/hero-cta.css',
            'priority' => 'high'
        );
        $css_files[] = array(
            'href' => get_template_directory_uri() . '/assets/css/logo-bar.css',
            'priority' => 'high'
        );
    } elseif (is_single()) {
        $css_files[] = array(
            'href' => get_template_directory_uri() . '/assets/css/pages/single-post.css',
            'priority' => 'high'
        );
    } elseif (is_home() || is_archive() || is_search()) {
        $css_files[] = array(
            'href' => get_template_directory_uri() . '/assets/css/pages/archive.css',
            'priority' => 'high'
        );
    }

    // Output preload hints with priorities (append theme version for cache busting)
    foreach ($css_files as $css_file) {
        $href = $css_file['href'];
        $href .= ( strpos( $href, '?' ) !== false ? '&' : '?' ) . 'ver=' . $theme_ver;
        $fetchpriority = isset($css_file['priority']) ? ' fetchpriority="' . esc_attr($css_file['priority']) . '"' : '';
        echo sprintf(
            '<link rel="preload" href="%s" as="style" onload="this.onload=null;this.rel=\'stylesheet\'"' . $fetchpriority . '>' . "\n",
            esc_url($href)
        );
    }

    // Add noscript fallback
    echo '<noscript>' . "\n";
    foreach ($css_files as $css_file) {
        $href = $css_file['href'];
        $href .= ( strpos( $href, '?' ) !== false ? '&' : '?' ) . 'ver=' . $theme_ver;
        echo sprintf(
            '<link rel="stylesheet" href="%s">' . "\n",
            esc_url($href)
        );
    }
    echo '</noscript>' . "\n";
}

/**
 * Add JavaScript to handle CSS loading and performance monitoring
 */
function dts_add_css_loading_script() {
    ?>
    <script>
    // Critical CSS Loading Enhancement with Performance Monitoring
    (function() {
        // Performance tracking
        var perfStart = performance.now();
        var cssLoadCount = 0;
        var totalCSSFiles = document.querySelectorAll('link[rel="preload"][as="style"]').length;

        // Function to load CSS asynchronously
        function loadCSS(href, before, media) {
            var doc = window.document;
            var ss = doc.createElement("link");
            var ref;
            if (before) {
                ref = before;
            } else {
                var refs = (doc.body || doc.getElementsByTagName("head")[0]).childNodes;
                ref = refs[refs.length - 1];
            }

            var sheets = doc.styleSheets;
            ss.rel = "stylesheet";
            ss.href = href;
            ss.media = "only x";

            function ready(cb) {
                if (doc.body) {
                    return cb();
                }
                setTimeout(function() {
                    ready(cb);
                });
            }

            ready(function() {
                ref.parentNode.insertBefore(ss, (before ? ref : ref.nextSibling));
            });

            var onloadcssdefined = function(cb) {
                var resolvedHref = ss.href;
                var i = sheets.length;
                while (i--) {
                    if (sheets[i].href === resolvedHref) {
                        return cb();
                    }
                }
                setTimeout(function() {
                    onloadcssdefined(cb);
                });
            };

            function loadCB() {
                if (ss.addEventListener) {
                    ss.removeEventListener("load", loadCB);
                }
                ss.media = media || "all";

                // Performance tracking
                cssLoadCount++;
                if (cssLoadCount === totalCSSFiles) {
                    var perfEnd = performance.now();
                    console.log('All CSS loaded in ' + (perfEnd - perfStart) + ' milliseconds');

                    // Remove loading state
                    if (document.body) {
                        document.body.classList.remove('dts-loading');
                        document.body.classList.add('dts-loaded');
                    }
                }
            }

            if (ss.addEventListener) {
                ss.addEventListener("load", loadCB);
            }
            ss.onloadcssdefined = onloadcssdefined;
            onloadcssdefined(loadCB);
            return ss;
        }

        // Add loading state to body (deferred — body may not exist yet in <head>)
        document.addEventListener('DOMContentLoaded', function() {
            if (document.body) document.body.classList.add('dts-loading');
        });

        // Support for browsers that don't support preload
        if (!window.CSS || !CSS.supports('(--foo: red)')) {
            var preloadLinks = document.querySelectorAll('link[rel="preload"][as="style"]');
            for (var i = 0; i < preloadLinks.length; i++) {
                var link = preloadLinks[i];
                link.rel = 'stylesheet';
            }
        }

        // Intersection Observer for lazy loading below-the-fold content
        if ('IntersectionObserver' in window) {
            var lazyElements = document.querySelectorAll('[data-lazy-load]');

            var lazyObserver = new IntersectionObserver(function(entries) {
                entries.forEach(function(entry) {
                    if (entry.isIntersecting) {
                        var element = entry.target;
                        element.classList.add('dts-animate-in');
                        lazyObserver.unobserve(element);
                    }
                });
            }, {
                rootMargin: '50px 0px'
            });

            lazyElements.forEach(function(element) {
                lazyObserver.observe(element);
            });
        }
    })();
    </script>
    <?php
}

/**
 * Initialize critical CSS system
 */
function dts_init_critical_css() {
    // Only load on frontend
    if (is_admin()) {
        return;
    }
    
    // Hook into wp_head early to inline critical CSS
    add_action('wp_head', 'dts_inline_critical_css', 1);
    
    // Add resource hints for performance
    add_action('wp_head', 'dts_add_resource_hints', 2);
    
    // Add preload hints and loading script
    add_action('wp_head', 'dts_add_css_preload_hints', 3);
    add_action('wp_head', 'dts_add_css_loading_script', 4);
}

// Initialize the critical CSS system
add_action('init', 'dts_init_critical_css');

/**
 * Admin notice to remind about critical CSS generation
 */
function dts_critical_css_admin_notice() {
    if (!current_user_can('manage_options')) {
        return;
    }
    
    $critical_files = array(
        get_template_directory() . '/assets/css/critical/homepage.css',
        get_template_directory() . '/assets/css/critical/single-post.css',
        get_template_directory() . '/assets/css/critical/archive.css',
    );
    
    $missing_files = array();
    foreach ($critical_files as $file) {
        if (!file_exists($file)) {
            $missing_files[] = basename($file);
        }
    }
    
    if (!empty($missing_files)) {
        $class = 'notice notice-warning is-dismissible';
        $message = sprintf(
            __('Critical CSS files are missing: %s. Run the generation script to create them: <code>./scripts/generate-critical.sh</code>', 'dark-theme-simplicity'),
            implode(', ', $missing_files)
        );
        
        printf('<div class="%1$s"><p>%2$s</p></div>', esc_attr($class), $message);
    }
}
add_action('admin_notices', 'dts_critical_css_admin_notice');