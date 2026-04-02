<?php

// ============================================
// 1. Include the Post Helper Class
// ============================================
require_once get_template_directory() . '/inc/class-dts-post-helper.php';

// ============================================
// 2. Enqueue the JavaScript and CSS
// ============================================
// REPLACED BY dts_enqueue_consolidated_blog_scripts() - see line 854
// add_action( 'wp_enqueue_scripts', 'dts_enqueue_post_assets' );
// function dts_enqueue_post_assets() {
// 	if ( is_singular( 'post' ) ) {
//     	// Enqueue the share functionality JavaScript
//     	wp_enqueue_script(
//         	'dts-post-share',
//         	get_template_directory_uri() . '/assets/js/dts-post-share.js',
//         	array(),
//         	'1.0.0',
//         	true
//     	);
//    	 
//     	// Localize script for translations
//     	wp_localize_script( 'dts-post-share', 'DTS_PostShare_L10n', array(
//         	'copySuccess' => __( 'Link copied to clipboard!', 'dark-theme-simplicity' ),
//         	'copyError' => __( 'Failed to copy link', 'dark-theme-simplicity' )
//     	) );
//    	 
//     	// Enqueue the share functionality CSS
//     	wp_enqueue_style(
//         	'dts-post-share',
//         	get_template_directory_uri() . '/assets/css/dts-post-share.css',
//         	array(),
//         	'1.0.0'
//     	);
// 	}
// }

// ============================================
// 3. Add custom hooks for extensibility
// ============================================

/**
 * Hook: After post content
 * Use this to add custom content after the post
 */
add_action( 'dts_after_post_content', 'dts_default_after_post_content' );
function dts_default_after_post_content() {
	// Add any default content here
	// Developers can remove this and add their own
}

/**
 * Filter: Modify post layout classes
 *
 * @param array $classes Layout classes
 * @param int $post_id Current post ID
 * @return array Modified classes
 */
add_filter( 'dts_post_layout_classes', 'dts_custom_layout_classes', 10, 2 );
function dts_custom_layout_classes( $classes, $post_id ) {
	// Example: Add custom class for specific category
	if ( has_category( 'featured', $post_id ) ) {
    	$classes['visibility_classes'] .= ' featured-post';
	}
    
	return $classes;
}

/**
 * Filter: Modify related posts title
 *
 * @param string $title Related posts section title
 * @return string Modified title
 */
add_filter( 'dts_related_posts_title', 'dts_custom_related_posts_title' );
function dts_custom_related_posts_title( $title ) {
	// Example: Change title based on category
	if ( is_singular() && has_category( 'tutorials' ) ) {
    	return __( 'More Tutorials', 'dark-theme-simplicity' );
	}
    
	return $title;
}

// ============================================
// 4. Register custom image sizes if needed
// ============================================
add_action( 'after_setup_theme', 'dts_register_image_sizes' );
function dts_register_image_sizes() {
	// Add custom image size for related posts if not exists
	add_image_size( 'dts-related-post', 400, 300, true );
    
	// Add mobile-optimized featured image size (16:9 aspect ratio for mobile)
	add_image_size( 'dts-mobile-hero', 800, 450, true );
    
	// Add desktop-optimized featured image size (4:3 aspect ratio for desktop)
	add_image_size( 'dts-desktop-hero', 1200, 900, true );
}

// ============================================
// 5. Add theme support for post formats (optional)
// ============================================
add_action( 'after_setup_theme', 'dts_post_theme_support' );
function dts_post_theme_support() {
	add_theme_support( 'post-formats', array(
    	'aside',
    	'gallery',
    	'link',
    	'image',
    	'quote',
    	'status',
    	'video',
    	'audio',
    	'chat'
	) );
}

// ============================================
// 6. Helper function to check if template parts exist
// ============================================
function dts_get_template_part( $slug, $name = null, $args = array() ) {
	$templates = array();
	$name = (string) $name;
    
	if ( '' !== $name ) {
    	$templates[] = "{$slug}-{$name}.php";
	}
    
	$templates[] = "{$slug}.php";
    
	// Check if template exists before including
	$located = '';
	foreach ( $templates as $template ) {
    	if ( file_exists( get_template_directory() . '/template-parts/' . $template ) ) {
        	$located = get_template_directory() . '/template-parts/' . $template;
        	break;
    	}
	}
    
	if ( $located ) {
    	get_template_part( $slug, $name, $args );
	} else {
    	// Fallback or error handling
    	if ( WP_DEBUG ) {
        	error_log( "Template part not found: {$slug}" . ( $name ? "-{$name}" : '' ) );
    	}
	}
}

/**
 * Fallback navigation menu when no menu is assigned
 */
function fallback_nav_menu() {
	$menu_items = array(
    	home_url('/blog') => __('Blog', 'dark-theme-simplicity'),
    	home_url('/about') => __('About', 'dark-theme-simplicity'),
	);
    
	echo '<ul class="nav-menu">';
	foreach ($menu_items as $url => $label) {
    	printf(
        	'<li><a href="%s" class="header-nav-link">%s</a></li>',
        	esc_url($url),
        	esc_html($label)
    	);
	}
	echo '</ul>';
}

/**
 * Parameterized Walker for simple menus (Footer, Social)
 * Pass $extra_rel to add rel attributes (e.g., 'noopener noreferrer' for social links)
 */
class Dark_Theme_Simplicity_Walker_Simple_Menu extends Walker_Nav_Menu {
	private $extra_rel;

	public function __construct($extra_rel = '') {
		$this->extra_rel = $extra_rel;
	}

	function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
		$classes = empty($item->classes) ? array() : (array) $item->classes;
		$class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args));
		$class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';

		$id = apply_filters('nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args);
		$id = $id ? ' id="' . esc_attr($id) . '"' : '';

		$output .= '<li' . $id . $class_names .'>';

		$attributes = ! empty($item->attr_title) ? ' title="'  . esc_attr($item->attr_title) .'"' : '';
		$attributes .= ! empty($item->target)    ? ' target="' . esc_attr($item->target     ) .'"' : '';
		$attributes .= ! empty($item->xfn)       ? ' rel="'    . esc_attr($item->xfn        ) .'"' : '';
		$attributes .= ! empty($item->url)        ? ' href="'   . esc_attr($item->url        ) .'"' : '';

		$link_classes = 'text-light-100/70 hover:text-blue-400 transition-colors duration-200';
		$rel_attr = $this->extra_rel ? ' rel="' . esc_attr($this->extra_rel) . '"' : '';

		$item_output = isset($args->before) ? $args->before : '';
		$item_output .= '<a class="' . $link_classes . '"' . $attributes . $rel_attr . '>';
		$item_output .= (isset($args->link_before) ? $args->link_before : '') . apply_filters('the_title', $item->title, $item->ID) . (isset($args->link_after) ? $args->link_after : '');
		$item_output .= '</a>';
		$item_output .= isset($args->after) ? $args->after : '';

		$output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
	}
}

/**
 * Theme functions and definitions
 */

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

/**
 * Emergency fix for blank pages - Prevent fatal errors and auto-recover
 */
if (!function_exists('dt_prevent_fatal_errors')) {
	function dt_prevent_fatal_errors() {
    	// Catch any fatal errors during customizer operations
    	if (isset($_POST['wp_customize']) && $_POST['wp_customize'] === 'on') {
        	ini_set('memory_limit', '512M');
        	set_time_limit(300);
    	}
	}
	add_action('init', 'dt_prevent_fatal_errors', 1);
}

/**
 * Default approach items — single source of truth
 */
function dts_default_approach_items() {
	return array(
		array(
			'title' => __('1. Discovery', 'dark-theme-simplicity'),
			'description' => __('I start by understanding your business, audience, and goals to create a tailored strategy.', 'dark-theme-simplicity')
		),
		array(
			'title' => __('2. Strategy Development', 'dark-theme-simplicity'),
			'description' => __('Based on research and your goals, I develop a content strategy aligned with your business objectives.', 'dark-theme-simplicity')
		),
		array(
			'title' => __('3. Implementation', 'dark-theme-simplicity'),
			'description' => __('I create content that engages your audience and drives the results you\'re looking for.', 'dark-theme-simplicity')
		),
		array(
			'title' => __('4. Analysis & Optimization', 'dark-theme-simplicity'),
			'description' => __('I continuously monitor performance and optimize your content strategy for better results.', 'dark-theme-simplicity')
		)
	);
}

/**
 * Get approach items from customizer, with safe JSON decoding and fallback to defaults
 */
function dt_get_approach_items() {
	$items = get_theme_mod('dark_theme_simplicity_approach_items', '');

	if (empty($items)) {
		return dts_default_approach_items();
	}

	$decoded = json_decode($items, true);

	if (json_last_error() !== JSON_ERROR_NONE || !is_array($decoded)) {
		error_log('Dark Theme Simplicity: Corrupt JSON in approach_items: ' . json_last_error_msg());
		return dts_default_approach_items();
	}

	return $decoded;
}

/**
 * Theme setup
 */
function dark_theme_simplicity_setup() {
	// Add default posts and comments RSS feed links to head
	add_theme_support('automatic-feed-links');

	// Let WordPress manage the document title
	add_theme_support('title-tag');

	// Enable support for Post Thumbnails on posts and pages
	add_theme_support('post-thumbnails');

	// Register navigation menus
	register_nav_menus(array(
    	'primary' => esc_html__('Primary Menu', 'dark-theme-simplicity'),
    	'footer' => esc_html__('Footer Menu', 'dark-theme-simplicity'),
    	'social' => esc_html__('Social Menu', 'dark-theme-simplicity'),
    	'legal' => esc_html__('Legal Menu', 'dark-theme-simplicity'),
	));

	// Switch default core markup to output valid HTML5
	add_theme_support('html5', array(
    	'search-form',
    	'comment-form',
    	'comment-list',
    	'gallery',
    	'caption',
    	'style',
    	'script',
	));

	// Add theme support for selective refresh for widgets
	add_theme_support('customize-selective-refresh-widgets');

	// Add support for custom logo
	add_theme_support('custom-logo', array(
    	'height'  	=> 250,
    	'width'   	=> 250,
    	'flex-width'  => true,
    	'flex-height' => true,
	));
}
add_action('after_setup_theme', 'dark_theme_simplicity_setup');

/**
 * Register widget areas
 */
function dark_theme_simplicity_widgets_init() {
	register_sidebar(array(
    	'name'      	=> esc_html__('Sidebar', 'dark-theme-simplicity'),
    	'id'        	=> 'sidebar-1',
    	'description'   => esc_html__('Add widgets here to appear in your sidebar on all pages.', 'dark-theme-simplicity'),
    	'before_widget' => '<section id="%1$s" class="widget %2$s mb-8">',
    	'after_widget'  => '</section>',
    	'before_title'  => '<div class="widget-title text-xl font-bold mb-4 text-white" role="heading" aria-level="2">',
    	'after_title'   => '</div>',
	));
    
	register_sidebar(array(
    	'name'      	=> esc_html__('Post Sidebar', 'dark-theme-simplicity'),
    	'id'        	=> 'sidebar-post',
    	'description'   => esc_html__('Add widgets here to appear in your sidebar on single posts.', 'dark-theme-simplicity'),
    	'before_widget' => '<section id="%1$s" class="widget %2$s mb-8">',
    	'after_widget'  => '</section>',
    	'before_title'  => '<div class="widget-title text-xl font-bold mb-4 text-white" role="heading" aria-level="2">',
    	'after_title'   => '</div>',
	));
    
	register_sidebar(array(
    	'name'      	=> esc_html__('Page Sidebar', 'dark-theme-simplicity'),
    	'id'        	=> 'sidebar-page',
    	'description'   => esc_html__('Add widgets here to appear in your sidebar on pages.', 'dark-theme-simplicity'),
    	'before_widget' => '<section id="%1$s" class="widget %2$s mb-8">',
    	'after_widget'  => '</section>',
    	'before_title'  => '<div class="widget-title text-xl font-bold mb-4 text-white" role="heading" aria-level="2">',
    	'after_title'   => '</div>',
	));
}
add_action('widgets_init', 'dark_theme_simplicity_widgets_init');

/**
 * Automatically add IDs to H2 headings in post content
 * This enables the Table of Contents to work without requiring JavaScript
 */
function add_heading_ids($content) {
	// Only process on single posts/pages
	if (!is_singular()) {
    	return $content;
	}
    
	$pattern = '/<h2([^>]*)>(.*?)<\/h2>/i';
	$content = preg_replace_callback($pattern, function($matches) {
    	$attributes = $matches[1];
    	$heading_text = strip_tags($matches[2]);
   	 
    	// Generate ID from heading text
    	$id = sanitize_title($heading_text);
   	 
    	// Check if ID attribute already exists
    	if (strpos($attributes, 'id=') === false) {
        	$attributes .= ' id="' . $id . '"';
    	}
   	 
    	return '<h2' . $attributes . '>' . $matches[2] . '</h2>';
	}, $content);
    
	return $content;
}
add_filter('the_content', 'add_heading_ids');

/**
 * Get service icon SVG based on icon name
 *
 * @param string $icon Icon identifier
 * @return string SVG markup for the requested icon
 */
function dark_theme_simplicity_get_service_icon($icon) {
	$svg = '';
    
	switch ($icon) {
    	case 'globe':
        	$svg = '<svg class="w-10 h-10 text-blue-300" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            	<circle cx="12" cy="12" r="10"></circle>
            	<path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path>
        	</svg>';
        	break;
    	case 'file-text':
        	$svg = '<svg class="w-10 h-10 text-blue-300" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            	<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
            	<polyline points="14 2 14 8 20 8"></polyline>
            	<line x1="16" y1="13" x2="8" y2="13"></line>
            	<line x1="16" y1="17" x2="8" y2="17"></line>
            	<polyline points="10 9 9 9 8 9"></polyline>
        	</svg>';
        	break;
    	case 'monitor':
        	$svg = '<svg class="w-10 h-10 text-blue-300" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            	<rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect>
            	<line x1="8" y1="21" x2="16" y2="21"></line>
            	<line x1="12" y1="17" x2="12" y2="21"></line>
        	</svg>';
        	break;
    	case 'database':
        	$svg = '<svg class="w-10 h-10 text-blue-300" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            	<ellipse cx="12" cy="5" rx="9" ry="3"></ellipse>
            	<path d="M21 12c0 1.66-4 3-9 3s-9-1.34-9-3"></path>
            	<path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5"></path>
        	</svg>';
        	break;
    	case 'bar-chart':
        	$svg = '<svg class="w-10 h-10 text-blue-300" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            	<line x1="12" y1="20" x2="12" y2="10"></line>
            	<line x1="18" y1="20" x2="18" y2="4"></line>
            	<line x1="6" y1="20" x2="6" y2="16"></line>
        	</svg>';
        	break;
    	case 'users':
        	$svg = '<svg class="w-10 h-10 text-blue-300" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            	<path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
            	<circle cx="9" cy="7" r="4"></circle>
            	<path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
            	<path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
        	</svg>';
        	break;
    	case 'search':
        	$svg = '<svg class="w-10 h-10 text-blue-300" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            	<circle cx="11" cy="11" r="8"></circle>
            	<line x1="21" y1="21" x2="16.65" y2="16.65"></line>
        	</svg>';
        	break;
    	case 'mail':
        	$svg = '<svg class="w-10 h-10 text-blue-300" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            	<path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
            	<polyline points="22,6 12,13 2,6"></polyline>
        	</svg>';
        	break;
    	case 'image':
        	$svg = '<svg class="w-10 h-10 text-blue-300" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            	<rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
            	<circle cx="8.5" cy="8.5" r="1.5"></circle>
            	<polyline points="21 15 16 10 5 21"></polyline>
        	</svg>';
        	break;
    	case 'layout':
        	$svg = '<svg class="w-10 h-10 text-blue-300" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            	<rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
            	<line x1="3" y1="9" x2="21" y2="9"></line>
            	<line x1="9" y1="21" x2="9" y2="9"></line>
        	</svg>';
        	break;
    	case 'code':
        	$svg = '<svg class="w-10 h-10 text-blue-300" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            	<polyline points="16 18 22 12 16 6"></polyline>
            	<polyline points="8 6 2 12 8 18"></polyline>
        	</svg>';
        	break;
    	case 'trending-up':
        	$svg = '<svg class="w-10 h-10 text-blue-300" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            	<polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline>
            	<polyline points="17 6 23 6 23 12"></polyline>
        	</svg>';
        	break;
    	default:
        	// Default icon if the requested one doesn't exist
        	$svg = '<svg class="w-10 h-10 text-blue-300" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            	<circle cx="12" cy="12" r="10"></circle>
        	</svg>';
	}
    
	return $svg;
}

/**
 * Parameterized Walker for primary navigation (Desktop, Mobile)
 * Pass $link_class for the <a> CSS class, $use_group to add 'group' class on top-level <li>
 */
class Dark_Theme_Simplicity_Walker_Nav extends Walker_Nav_Menu {
	private $link_class;
	private $use_group;

	public function __construct($link_class = 'header-nav-link', $use_group = false) {
		$this->link_class = $link_class;
		$this->use_group = $use_group;
	}

	public function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0) {
		$indent = ($depth) ? str_repeat("\t", $depth) : '';

		$classes = empty($item->classes) ? array() : (array) $item->classes;
		$classes[] = 'menu-item-' . $item->ID;

		if (in_array('current-menu-item', $classes)) {
			$classes[] = 'active';
		}

		$classes[] = 'relative';

		if ($this->use_group && $depth === 0) {
			$classes[] = 'group';
		}

		$class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args, $depth));
		$class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';

		$id = apply_filters('nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args, $depth);
		$id = $id ? ' id="' . esc_attr($id) . '"' : '';

		$output .= $indent . '<li' . $id . $class_names .'>';

		$atts = array();
		$atts['title']  = !empty($item->attr_title) ? $item->attr_title : '';
		$atts['target'] = !empty($item->target) ? $item->target : '';
		$atts['rel']    = !empty($item->xfn) ? $item->xfn : '';
		$atts['href']   = !empty($item->url) ? $item->url : '';
		$atts['class']  = $this->link_class;
		$atts = apply_filters('nav_menu_link_attributes', $atts, $item, $args, $depth);

		$attributes = '';
		foreach ($atts as $attr => $value) {
			if (!empty($value)) {
				$value = ('href' === $attr) ? esc_url($value) : esc_attr($value);
				$attributes .= ' ' . $attr . '="' . $value . '"';
			}
		}

		$title = apply_filters('the_title', $item->title, $item->ID);
		$title = apply_filters('nav_menu_item_title', $title, $item, $args, $depth);

		$item_output = $args->before;
		$item_output .= '<a'. $attributes .'>';
		$item_output .= $args->link_before . $title . $args->link_after;
		$item_output .= '</a>';
		$item_output .= $args->after;

		$output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
	}
}

// ============================================
// Disable WordPress emoji scripts and styles for performance
// ============================================
add_action( 'init', 'dts_disable_wp_emojis' );
function dts_disable_wp_emojis() {
    // Front-end
    remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
    remove_action( 'wp_print_styles', 'print_emoji_styles' );

    // Admin
    remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
    remove_action( 'admin_print_styles', 'print_emoji_styles' );

    // Feeds, emails, and embeds
    remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
    remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
    remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );

    // Admin editor
    add_filter( 'tiny_mce_plugins', 'dts_disable_emojis_tinymce' );

    // Remove DNS prefetch
    add_filter( 'wp_resource_hints', 'dts_remove_emoji_dns_prefetch', 10, 2 );
}

function dts_disable_emojis_tinymce( $plugins ) {
    if ( is_array( $plugins ) ) {
        return array_diff( $plugins, array( 'wpemoji' ) );
    }
    return array();
}

function dts_remove_emoji_dns_prefetch( $urls, $relation_type ) {
    if ( 'dns-prefetch' === $relation_type ) {
        foreach ( $urls as $key => $url ) {
            if ( strpos( $url, 'https://s.w.org/images/core/emoji/' ) !== false ) {
                unset( $urls[ $key ] );
            }
        }
    }
    return $urls;
}

// Include other files
require_once get_template_directory() . '/inc/enqueue.php';
require_once get_template_directory() . '/inc/admin.php';
require_once get_template_directory() . '/inc/customizer-setup.php';
// Load customizer-repeater-control.php before customizer.php
if (!class_exists('Dark_Theme_Simplicity_Customizer_Repeater_Control')) {
	require_once get_template_directory() . '/inc/customizer-repeater-control.php';
}
require_once get_template_directory() . '/inc/customizer.php';
require_once get_template_directory() . '/inc/accessibility.php';
require_once get_template_directory() . '/inc/homepage-sections-customizer.php';
require_once get_template_directory() . '/inc/shortcodes.php';

// === STAGE 2: CONSOLIDATED SINGLE-POST STYLES ===
// DISABLED - Using new CSS structure from inc/enqueue.php instead

// === REMOVE WORDPRESS CORE CSS BLOAT ===
// Remove unnecessary WordPress default styles to reduce page weight
add_action( 'wp_enqueue_scripts', 'dts_remove_wordpress_core_bloat', 100 );
function dts_remove_wordpress_core_bloat() {
    // Remove WordPress Block Library CSS (only if not using Gutenberg blocks)
    if ( ! is_admin() && ! has_blocks() ) {
        wp_dequeue_style( 'wp-block-library' );
        wp_deregister_style( 'wp-block-library' );
    }
    
    // Remove Classic Theme Styles - these are for backward compatibility
    wp_dequeue_style( 'classic-theme-styles' );
    wp_deregister_style( 'classic-theme-styles' );
    
    // Remove Global Styles - only needed if using FSE features
    wp_dequeue_style( 'global-styles' );
    wp_deregister_style( 'global-styles' );
    
    // Remove ReactPress plugin styles on non-React pages
    if ( is_singular( 'post' ) || is_home() || is_archive() ) {
        wp_dequeue_style( 'reactpress' );
        wp_deregister_style( 'reactpress' );
        wp_dequeue_script( 'reactpress' );
        wp_deregister_script( 'reactpress' );
    }
}

// === REMOVE JQUERY MIGRATE ===
// Remove jQuery Migrate which is rarely needed in modern themes
add_action( 'wp_default_scripts', 'dts_remove_jquery_migrate' );
function dts_remove_jquery_migrate( $scripts ) {
    if ( ! is_admin() && isset( $scripts->registered['jquery'] ) ) {
        $script = $scripts->registered['jquery'];
        if ( $script->deps ) {
            $script->deps = array_diff( $script->deps, array( 'jquery-migrate' ) );
        }
    }
}

// === CONSOLIDATED BLOG JAVASCRIPT ===
// Replace multiple blog JS files with single consolidated file
add_action( 'wp_enqueue_scripts', 'dts_enqueue_consolidated_blog_scripts', 25 );
function dts_enqueue_consolidated_blog_scripts() {
    if ( ! is_singular( 'post' ) ) {
        return;
    }
    
    // Enqueue consolidated blog script
    wp_enqueue_script(
        'blog-consolidated',
        get_template_directory_uri() . '/assets/js/blog-consolidated.js',
        array( 'dts-core' ), // Uses DTSCore utilities
        '1.3.0',
        true
    );
    
    // Localize script for translations (was previously on dts-post-share)
    wp_localize_script( 'blog-consolidated', 'DTS_PostShare_L10n', array(
        'copySuccess' => __( 'Link copied to clipboard!', 'dark-theme-simplicity' ),
        'copyError' => __( 'Failed to copy link', 'dark-theme-simplicity' )
    ) );
}
