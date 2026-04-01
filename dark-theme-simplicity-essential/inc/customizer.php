<?php
/**
 * Dark Theme Simplicity Theme Customizer
 *
 * @package Dark_Theme_Simplicity
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Load the customizer repeater control class FIRST
require_once get_template_directory() . '/inc/customizer-repeater-control.php';

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
if (!function_exists('dark_theme_simplicity_customize_register')) {
    function dark_theme_simplicity_customize_register( $wp_customize ) {
        // Add a panel for front page sections
        $wp_customize->add_panel( 'dark_theme_simplicity_frontpage_panel', array(
            'title'       => __( 'Front Page Sections', 'dark-theme-simplicity' ),
            'description' => __( 'Customize your front page sections', 'dark-theme-simplicity' ),
            'priority'    => 30,
        ) );

        // Add a section for site identity
        $wp_customize->add_section( 'dark_theme_simplicity_site_identity', array(
            'title'       => __( 'Site Identity', 'dark-theme-simplicity' ),
            'description' => __( 'Customize your site logo and title', 'dark-theme-simplicity' ),
            'priority'    => 20,
        ) );

        // Add a section for footer options
        $wp_customize->add_section( 'dark_theme_simplicity_footer_options', array(
            'title'       => __( 'Footer Options', 'dark-theme-simplicity' ),
            'description' => __( 'Customize footer elements and links', 'dark-theme-simplicity' ),
            'priority'    => 130,
        ) );

        // Add a section for social media links
        $wp_customize->add_section( 'dark_theme_simplicity_social_links', array(
            'title'       => __( 'Social Media Links', 'dark-theme-simplicity' ),
            'description' => __( 'Add your social media profile URLs here. These will display in the footer "Connect" section if you haven\'t created a Social menu. To fully customize the social links, create a menu and assign it to the "Social Menu" location.', 'dark-theme-simplicity' ),
            'priority'    => 135,
        ) );

        // Site Title
        $wp_customize->add_setting( 'dark_theme_simplicity_site_title', array(
            'default'           => __( 'Brad Daiber', 'dark-theme-simplicity' ),
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'refresh',
        ) );

        $wp_customize->add_control( 'dark_theme_simplicity_site_title', array(
            'label'    => __( 'Site Title', 'dark-theme-simplicity' ),
            'section'  => 'dark_theme_simplicity_site_identity',
            'settings' => 'dark_theme_simplicity_site_title',
            'type'     => 'text',
        ) );

        // Footer Tagline
        $wp_customize->add_setting( 'dark_theme_simplicity_footer_tagline', array(
            'default'           => __( 'Helping businesses establish a powerful online presence through strategic digital marketing solutions.', 'dark-theme-simplicity' ),
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'refresh',
        ) );

        $wp_customize->add_control( 'dark_theme_simplicity_footer_tagline', array(
            'label'    => __( 'Footer Tagline Text', 'dark-theme-simplicity' ),
            'description' => __( 'The text that appears below the logo in the footer.', 'dark-theme-simplicity' ),
            'section'  => 'dark_theme_simplicity_footer_options',
            'settings' => 'dark_theme_simplicity_footer_tagline',
            'type'     => 'textarea',
        ) );

        // Footer Copyright
        $wp_customize->add_setting( 'dark_theme_simplicity_footer_copyright', array(
            'default'           => __( 'Brad Daiber', 'dark-theme-simplicity' ),
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'refresh',
        ) );

        $wp_customize->add_control( 'dark_theme_simplicity_footer_copyright', array(
            'label'    => __( 'Footer Copyright Name', 'dark-theme-simplicity' ),
            'description' => __( 'The name that appears in the copyright text. Year will be added automatically.', 'dark-theme-simplicity' ),
            'section'  => 'dark_theme_simplicity_footer_options',
            'settings' => 'dark_theme_simplicity_footer_copyright',
            'type'     => 'text',
        ) );

        // Logo Color
        $wp_customize->add_setting( 'dark_theme_simplicity_logo_color', array(
            'default'           => '#60a5fa',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'refresh',
        ) );

        $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'dark_theme_simplicity_logo_color', array(
            'label'    => __( 'Logo Color', 'dark-theme-simplicity' ),
            'section'  => 'dark_theme_simplicity_site_identity',
            'settings' => 'dark_theme_simplicity_logo_color',
        ) ) );

        // Hero Section
        $wp_customize->add_section( 'dark_theme_simplicity_hero_section', array(
            'title'       => __( 'Hero Section', 'dark-theme-simplicity' ),
            'description' => __( 'Customize the hero section on the front page', 'dark-theme-simplicity' ),
            'panel'       => 'dark_theme_simplicity_frontpage_panel',
            'priority'    => 10,
        ) );

        // Hero Background Image
        $wp_customize->add_setting( 'dark_theme_simplicity_hero_bg_image', array(
            'default'           => '',
            'sanitize_callback' => 'esc_url_raw',
            'transport'         => 'refresh',
        ) );

        $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'dark_theme_simplicity_hero_bg_image', array(
            'label'    => __( 'Background Image', 'dark-theme-simplicity' ),
            'section'  => 'dark_theme_simplicity_hero_section',
            'settings' => 'dark_theme_simplicity_hero_bg_image',
        ) ) );

        // Hero Heading
        $wp_customize->add_setting( 'dark_theme_simplicity_hero_heading', array(
            'default'           => __( 'I Turn Lean Content Teams Into Growth Engines', 'dark-theme-simplicity' ),
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'refresh',
        ) );

        $wp_customize->add_control( 'dark_theme_simplicity_hero_heading', array(
            'label'    => __( 'Heading', 'dark-theme-simplicity' ),
            'section'  => 'dark_theme_simplicity_hero_section',
            'settings' => 'dark_theme_simplicity_hero_heading',
            'type'     => 'text',
        ) );

        // Hero Subheading
        $wp_customize->add_setting( 'dark_theme_simplicity_hero_subheading', array(
            'default'           => __( 'Content marketing leader. 10+ years turning B2B & B2C value propositions into multi-channel strategies that grow revenue.', 'dark-theme-simplicity' ),
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'refresh',
        ) );

        $wp_customize->add_control( 'dark_theme_simplicity_hero_subheading', array(
            'label'    => __( 'Subheading', 'dark-theme-simplicity' ),
            'section'  => 'dark_theme_simplicity_hero_section',
            'settings' => 'dark_theme_simplicity_hero_subheading',
            'type'     => 'text',
        ) );

        // Hero Button Color
        $wp_customize->add_setting( 'dark_theme_simplicity_hero_button_color', array(
            'default'           => '#0085ff', // Blue-300
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'refresh',
        ) );

        $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'dark_theme_simplicity_hero_button_color', array(
            'label'    => __( 'Button Color', 'dark-theme-simplicity' ),
            'section'  => 'dark_theme_simplicity_hero_section',
            'settings' => 'dark_theme_simplicity_hero_button_color',
        ) ) );

        // Hero Button Hover Color
        $wp_customize->add_setting( 'dark_theme_simplicity_hero_button_hover_color', array(
            'default'           => '#0057a7', // Blue-400
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'refresh',
        ) );

        $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'dark_theme_simplicity_hero_button_hover_color', array(
            'label'    => __( 'Button Hover Color', 'dark-theme-simplicity' ),
            'section'  => 'dark_theme_simplicity_hero_section',
            'settings' => 'dark_theme_simplicity_hero_button_hover_color',
        ) ) );

        // Primary CTA Button Text
        $wp_customize->add_setting( 'dark_theme_simplicity_hero_cta_primary_text', array(
            'default'           => __( 'View My Work', 'dark-theme-simplicity' ),
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'refresh',
        ) );

        $wp_customize->add_control( 'dark_theme_simplicity_hero_cta_primary_text', array(
            'label'    => __( 'Primary Button Text', 'dark-theme-simplicity' ),
            'section'  => 'dark_theme_simplicity_hero_section',
            'settings' => 'dark_theme_simplicity_hero_cta_primary_text',
            'type'     => 'text',
        ) );

        // Primary CTA Button Link
        $wp_customize->add_setting( 'dark_theme_simplicity_hero_cta_primary_url', array(
            'default'           => '#about',
            'sanitize_callback' => 'esc_url_raw',
            'transport'         => 'refresh',
        ) );

        $wp_customize->add_control( 'dark_theme_simplicity_hero_cta_primary_url', array(
            'label'    => __( 'Primary Button Link', 'dark-theme-simplicity' ),
            'section'  => 'dark_theme_simplicity_hero_section',
            'settings' => 'dark_theme_simplicity_hero_cta_primary_url',
            'type'     => 'url',
        ) );

        // Secondary CTA Button Text
        $wp_customize->add_setting( 'dark_theme_simplicity_hero_cta_secondary_text', array(
            'default'           => __( 'Get In Touch', 'dark-theme-simplicity' ),
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'refresh',
        ) );

        $wp_customize->add_control( 'dark_theme_simplicity_hero_cta_secondary_text', array(
            'label'    => __( 'Secondary Button Text', 'dark-theme-simplicity' ),
            'section'  => 'dark_theme_simplicity_hero_section',
            'settings' => 'dark_theme_simplicity_hero_cta_secondary_text',
            'type'     => 'text',
        ) );

        // Secondary CTA Button Link
        $wp_customize->add_setting( 'dark_theme_simplicity_hero_cta_secondary_url', array(
            'default'           => '#contact',
            'sanitize_callback' => 'esc_url_raw',
            'transport'         => 'refresh',
        ) );

        $wp_customize->add_control( 'dark_theme_simplicity_hero_cta_secondary_url', array(
            'label'    => __( 'Secondary Button Link', 'dark-theme-simplicity' ),
            'section'  => 'dark_theme_simplicity_hero_section',
            'settings' => 'dark_theme_simplicity_hero_cta_secondary_url',
            'type'     => 'url',
        ) );

        // Services Section
        $wp_customize->add_section( 'dark_theme_simplicity_services_section', array(
            'title'       => __( 'Services Section', 'dark-theme-simplicity' ),
            'description' => __( 'Customize the services section on the front page', 'dark-theme-simplicity' ),
            'panel'       => 'dark_theme_simplicity_frontpage_panel',
            'priority'    => 20,
        ) );

        // Services Section Title
        $wp_customize->add_setting( 'dark_theme_simplicity_services_title', array(
            'default'           => __( 'Our Services', 'dark-theme-simplicity' ),
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'refresh',
        ) );

        $wp_customize->add_control( 'dark_theme_simplicity_services_title', array(
            'label'    => __( 'Section Title', 'dark-theme-simplicity' ),
            'section'  => 'dark_theme_simplicity_services_section',
            'settings' => 'dark_theme_simplicity_services_title',
            'type'     => 'text',
        ) );

        // Services Section Description
        $wp_customize->add_setting( 'dark_theme_simplicity_services_description', array(
            'default'           => __( 'Comprehensive digital marketing solutions to elevate your online presence.', 'dark-theme-simplicity' ),
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'refresh',
        ) );

        $wp_customize->add_control( 'dark_theme_simplicity_services_description', array(
            'label'    => __( 'Section Description', 'dark-theme-simplicity' ),
            'section'  => 'dark_theme_simplicity_services_section',
            'settings' => 'dark_theme_simplicity_services_description',
            'type'     => 'textarea',
        ) );

        // Service Cards Background Color
        $wp_customize->add_setting( 'dark_theme_simplicity_service_card_bg_color', array(
            'default'           => '#1e1e24',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'refresh',
        ) );

        $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'dark_theme_simplicity_service_card_bg_color', array(
            'label'    => __( 'Service Card Background Color', 'dark-theme-simplicity' ),
            'section'  => 'dark_theme_simplicity_services_section',
            'settings' => 'dark_theme_simplicity_service_card_bg_color',
        ) ) );

        // Service Cards Accent Color
        $wp_customize->add_setting( 'dark_theme_simplicity_service_card_accent_color', array(
            'default'           => '#0085ff', // Blue-300
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'refresh',
        ) );

        $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'dark_theme_simplicity_service_card_accent_color', array(
            'label'    => __( 'Service Card Accent Color', 'dark-theme-simplicity' ),
            'section'  => 'dark_theme_simplicity_services_section',
            'settings' => 'dark_theme_simplicity_service_card_accent_color',
        ) ) );

        // Service Items Repeater
        $default_services = array(
            array(
                'icon' => 'globe',
                'title' => __('Strategic SEO', 'dark-theme-simplicity'),
                'description' => __('Boost your visibility with search engine optimization that drives organic traffic.', 'dark-theme-simplicity')
            ),
            array(
                'icon' => 'file-text',
                'title' => __('Content Creation', 'dark-theme-simplicity'),
                'description' => __('Engaging, on-brand content that resonates with your target audience.', 'dark-theme-simplicity')
            ),
            array(
                'icon' => 'monitor',
                'title' => __('Website Development', 'dark-theme-simplicity'),
                'description' => __('Custom websites designed for user experience and conversion optimization.', 'dark-theme-simplicity')
            ),
            array(
                'icon' => 'database',
                'title' => __('Brand Strategy', 'dark-theme-simplicity'),
                'description' => __('Cohesive visual identity and messaging that distinguishes your business.', 'dark-theme-simplicity')
            )
        );

        $wp_customize->add_setting( 'dark_theme_simplicity_service_items', array(
            'default'           => json_encode($default_services),
            'sanitize_callback' => 'dark_theme_simplicity_sanitize_repeater',
            'transport'         => 'refresh',
        ) );

        $wp_customize->add_control( new Dark_Theme_Simplicity_Customizer_Repeater_Control( $wp_customize, 'dark_theme_simplicity_service_items', array(
            'label'       => __( 'Service Items', 'dark-theme-simplicity' ),
            'section'     => 'dark_theme_simplicity_services_section',
            'fields'      => array(
                array(
                    'id'    => 'icon',
                    'type'  => 'select',
                    'label' => __( 'Icon', 'dark-theme-simplicity' ),
                    'choices' => array(
                        'globe'    => __( 'Globe (SEO)', 'dark-theme-simplicity' ),
                        'file-text' => __( 'Document (Content)', 'dark-theme-simplicity' ),
                        'monitor'  => __( 'Computer (Web Dev)', 'dark-theme-simplicity' ),
                        'database' => __( 'Database (Brand)', 'dark-theme-simplicity' ),
                        'bar-chart' => __( 'Chart (Analytics)', 'dark-theme-simplicity' ),
                        'users'    => __( 'Users (Social)', 'dark-theme-simplicity' ),
                        'search'   => __( 'Search', 'dark-theme-simplicity' ),
                        'mail'     => __( 'Email', 'dark-theme-simplicity' ),
                        'image'    => __( 'Image', 'dark-theme-simplicity' ),
                        'layout'   => __( 'Layout (Design)', 'dark-theme-simplicity' ),
                        'code'     => __( 'Code', 'dark-theme-simplicity' ),
                        'trending-up' => __( 'Trending Up (Growth)', 'dark-theme-simplicity' ),
                    ),
                ),
                array(
                    'id'    => 'title',
                    'type'  => 'text',
                    'label' => __( 'Title', 'dark-theme-simplicity' ),
                ),
                array(
                    'id'    => 'description',
                    'type'  => 'textarea',
                    'label' => __( 'Description', 'dark-theme-simplicity' ),
                ),
            ),
        ) ) );

        // Benefits Section
        $wp_customize->add_section( 'dark_theme_simplicity_benefits_section', array(
            'title'       => __( 'Benefits Section', 'dark-theme-simplicity' ),
            'description' => __( 'Customize the benefits section on the front page', 'dark-theme-simplicity' ),
            'panel'       => 'dark_theme_simplicity_frontpage_panel',
            'priority'    => 30,
        ) );

        // Benefits Section Title
        $wp_customize->add_setting( 'dark_theme_simplicity_benefits_title', array(
            'default'           => __( 'Key Benefits', 'dark-theme-simplicity' ),
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'refresh',
        ) );

        $wp_customize->add_control( 'dark_theme_simplicity_benefits_title', array(
            'label'    => __( 'Section Title', 'dark-theme-simplicity' ),
            'section'  => 'dark_theme_simplicity_benefits_section',
            'settings' => 'dark_theme_simplicity_benefits_title',
            'type'     => 'text',
        ) );

        // Benefits Section Description
        $wp_customize->add_setting( 'dark_theme_simplicity_benefits_description', array(
            'default'           => __( 'We deliver real results through strategic digital solutions tailored to your business goals.', 'dark-theme-simplicity' ),
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'refresh',
        ) );

        $wp_customize->add_control( 'dark_theme_simplicity_benefits_description', array(
            'label'    => __( 'Section Description', 'dark-theme-simplicity' ),
            'section'  => 'dark_theme_simplicity_benefits_section',
            'settings' => 'dark_theme_simplicity_benefits_description',
            'type'     => 'textarea',
        ) );

        // Benefits Items Repeater
        $default_benefits = array(
            array(
                'title' => __('Data-Driven', 'dark-theme-simplicity'),
                'description' => __('Our strategies are backed by thorough research and analytics for measurable outcomes.', 'dark-theme-simplicity')
            ),
            array(
                'title' => __('Customized Approach', 'dark-theme-simplicity'),
                'description' => __('Solutions are tailored to your specific industry, audience, and business objectives.', 'dark-theme-simplicity')
            ),
            array(
                'title' => __('Transparent Process', 'dark-theme-simplicity'),
                'description' => __('Clear communication and regular reporting keep you informed every step of the way.', 'dark-theme-simplicity')
            ),
            array(
                'title' => __('Continuous Optimization', 'dark-theme-simplicity'),
                'description' => __('We consistently refine strategies based on performance data to maximize ROI.', 'dark-theme-simplicity')
            )
        );

        $wp_customize->add_setting( 'dark_theme_simplicity_benefit_items', array(
            'default'           => json_encode($default_benefits),
            'sanitize_callback' => 'dark_theme_simplicity_sanitize_repeater',
            'transport'         => 'refresh',
        ) );

        $wp_customize->add_control( new Dark_Theme_Simplicity_Customizer_Repeater_Control( $wp_customize, 'dark_theme_simplicity_benefit_items', array(
            'label'       => __( 'Benefit Items', 'dark-theme-simplicity' ),
            'section'     => 'dark_theme_simplicity_benefits_section',
            'fields'      => array(
                array(
                    'id'    => 'title',
                    'type'  => 'text',
                    'label' => __( 'Title', 'dark-theme-simplicity' ),
                ),
                array(
                    'id'    => 'description',
                    'type'  => 'textarea',
                    'label' => __( 'Description', 'dark-theme-simplicity' ),
                ),
            ),
        ) ) );

        // Approach Section
        $wp_customize->add_section( 'dark_theme_simplicity_approach_section', array(
            'title'       => __( 'Approach Section', 'dark-theme-simplicity' ),
            'description' => __( 'Customize the approach section on the front page', 'dark-theme-simplicity' ),
            'panel'       => 'dark_theme_simplicity_frontpage_panel',
            'priority'    => 40,
        ) );

        // Approach Section Title
        $wp_customize->add_setting( 'dark_theme_simplicity_approach_title', array(
            'default'           => __( 'How I work with clients', 'dark-theme-simplicity' ),
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'refresh',
        ) );

        $wp_customize->add_control( 'dark_theme_simplicity_approach_title', array(
            'label'    => __( 'Section Title', 'dark-theme-simplicity' ),
            'section'  => 'dark_theme_simplicity_approach_section',
            'settings' => 'dark_theme_simplicity_approach_title',
            'type'     => 'text',
        ) );

        // Approach Section Description
        $wp_customize->add_setting( 'dark_theme_simplicity_approach_description', array(
            'default'           => __( 'I believe in a collaborative approach to content strategy. Your business is unique, and your content strategy should be too.', 'dark-theme-simplicity' ),
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'refresh',
        ) );

        $wp_customize->add_control( 'dark_theme_simplicity_approach_description', array(
            'label'    => __( 'Section Description', 'dark-theme-simplicity' ),
            'section'  => 'dark_theme_simplicity_approach_section',
            'settings' => 'dark_theme_simplicity_approach_description',
            'type'     => 'textarea',
        ) );

        // Approach Items Repeater
        $default_approach_items = dts_default_approach_items();

        $wp_customize->add_setting( 'dark_theme_simplicity_approach_items', array(
            'default'           => json_encode($default_approach_items),
            'sanitize_callback' => 'dark_theme_simplicity_sanitize_repeater',
            'transport'         => 'refresh',
        ) );

        $wp_customize->add_control( new Dark_Theme_Simplicity_Customizer_Repeater_Control( $wp_customize, 'dark_theme_simplicity_approach_items', array(
            'label'       => __( 'Approach Items', 'dark-theme-simplicity' ),
            'section'     => 'dark_theme_simplicity_approach_section',
            'fields'      => array(
                array(
                    'id'    => 'title',
                    'type'  => 'text',
                    'label' => __( 'Title', 'dark-theme-simplicity' ),
                ),
                array(
                    'id'    => 'description',
                    'type'  => 'textarea',
                    'label' => __( 'Description', 'dark-theme-simplicity' ),
                ),
            ),
        ) ) );

        // About Section
        $wp_customize->add_section( 'dark_theme_simplicity_about_section', array(
            'title'       => __( 'About Section', 'dark-theme-simplicity' ),
            'description' => __( 'Customize the about section on the front page', 'dark-theme-simplicity' ),
            'panel'       => 'dark_theme_simplicity_frontpage_panel',
            'priority'    => 50,
        ) );

        // About Section Title
        $wp_customize->add_setting( 'dark_theme_simplicity_about_title', array(
            'default'           => __( 'Digital Marketing Specialist', 'dark-theme-simplicity' ),
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'refresh',
        ) );

        $wp_customize->add_control( 'dark_theme_simplicity_about_title', array(
            'label'    => __( 'Section Title', 'dark-theme-simplicity' ),
            'section'  => 'dark_theme_simplicity_about_section',
            'settings' => 'dark_theme_simplicity_about_title',
            'type'     => 'text',
        ) );

        // About Section Subtitle
        $wp_customize->add_setting( 'dark_theme_simplicity_about_subtitle', array(
            'default'           => __( 'With over a decade of experience helping businesses thrive online.', 'dark-theme-simplicity' ),
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'refresh',
        ) );

        $wp_customize->add_control( 'dark_theme_simplicity_about_subtitle', array(
            'label'    => __( 'Section Subtitle', 'dark-theme-simplicity' ),
            'section'  => 'dark_theme_simplicity_about_section',
            'settings' => 'dark_theme_simplicity_about_subtitle',
            'type'     => 'text',
        ) );

        // About Image
        $wp_customize->add_setting( 'dark_theme_simplicity_about_image', array(
            'default'           => get_template_directory_uri() . '/assets/images/about-image.jpg',
            'sanitize_callback' => 'esc_url_raw',
            'transport'         => 'refresh',
        ) );

        $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'dark_theme_simplicity_about_image', array(
            'label'    => __( 'About Image', 'dark-theme-simplicity' ),
            'section'  => 'dark_theme_simplicity_about_section',
            'settings' => 'dark_theme_simplicity_about_image',
        ) ) );

        // About Content First Paragraph
        $wp_customize->add_setting( 'dark_theme_simplicity_about_content_1', array(
            'default'           => __( 'I\'m Brad Daiber, a seasoned digital marketing consultant with a passion for helping businesses establish a powerful online presence. With a background in SEO, content creation, and web design, I provide comprehensive solutions tailored to your specific needs.', 'dark-theme-simplicity' ),
            'sanitize_callback' => 'sanitize_textarea_field',
            'transport'         => 'refresh',
        ) );

        $wp_customize->add_control( 'dark_theme_simplicity_about_content_1', array(
            'label'    => __( 'First Paragraph', 'dark-theme-simplicity' ),
            'section'  => 'dark_theme_simplicity_about_section',
            'settings' => 'dark_theme_simplicity_about_content_1',
            'type'     => 'textarea',
        ) );

        // About Content Second Paragraph
        $wp_customize->add_setting( 'dark_theme_simplicity_about_content_2', array(
            'default'           => __( 'My approach combines data-driven strategies with creative thinking to deliver measurable results. Whether you\'re looking to increase website traffic, improve conversion rates, or establish your brand voice, I\'m here to help you achieve your goals.', 'dark-theme-simplicity' ),
            'sanitize_callback' => 'sanitize_textarea_field',
            'transport'         => 'refresh',
        ) );

        $wp_customize->add_control( 'dark_theme_simplicity_about_content_2', array(
            'label'    => __( 'Second Paragraph', 'dark-theme-simplicity' ),
            'section'  => 'dark_theme_simplicity_about_section',
            'settings' => 'dark_theme_simplicity_about_content_2',
            'type'     => 'textarea',
        ) );

        // Contact Section
        $wp_customize->add_section( 'dark_theme_simplicity_contact_section', array(
            'title'       => __( 'Contact Section', 'dark-theme-simplicity' ),
            'description' => __( 'Customize the contact section on the front page', 'dark-theme-simplicity' ),
            'panel'       => 'dark_theme_simplicity_frontpage_panel',
            'priority'    => 60,
        ) );

        // Contact Section Title
        $wp_customize->add_setting( 'dark_theme_simplicity_contact_title', array(
            'default'           => __( 'Contact Me', 'dark-theme-simplicity' ),
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'refresh',
        ) );

        $wp_customize->add_control( 'dark_theme_simplicity_contact_title', array(
            'label'    => __( 'Section Title', 'dark-theme-simplicity' ),
            'section'  => 'dark_theme_simplicity_contact_section',
            'settings' => 'dark_theme_simplicity_contact_title',
            'type'     => 'text',
        ) );

        // Contact Section Description
        $wp_customize->add_setting( 'dark_theme_simplicity_contact_description', array(
            'default'           => __( 'Let\'s discuss how we can elevate your online presence.', 'dark-theme-simplicity' ),
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'refresh',
        ) );

        $wp_customize->add_control( 'dark_theme_simplicity_contact_description', array(
            'label'    => __( 'Section Description', 'dark-theme-simplicity' ),
            'section'  => 'dark_theme_simplicity_contact_section',
            'settings' => 'dark_theme_simplicity_contact_description',
            'type'     => 'textarea',
        ) );

        // Email
        $wp_customize->add_setting( 'dark_theme_simplicity_contact_email', array(
            'default'           => 'hello@braddaiber.com',
            'sanitize_callback' => 'sanitize_email',
            'transport'         => 'refresh',
        ) );

        $wp_customize->add_control( 'dark_theme_simplicity_contact_email', array(
            'label'    => __( 'Email Address', 'dark-theme-simplicity' ),
            'section'  => 'dark_theme_simplicity_contact_section',
            'settings' => 'dark_theme_simplicity_contact_email',
            'type'     => 'text',
        ) );

        // LinkedIn
        $wp_customize->add_setting( 'dark_theme_simplicity_contact_linkedin', array(
            'default'           => 'linkedin.com/in/braddaiber',
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'refresh',
        ) );

        $wp_customize->add_control( 'dark_theme_simplicity_contact_linkedin', array(
            'label'    => __( 'LinkedIn Profile', 'dark-theme-simplicity' ),
            'description' => __( 'Enter your LinkedIn URL (e.g., linkedin.com/in/username)', 'dark-theme-simplicity' ),
            'section'  => 'dark_theme_simplicity_contact_section',
            'settings' => 'dark_theme_simplicity_contact_linkedin',
            'type'     => 'text',
        ) );

        // Contact Accent Color
        $wp_customize->add_setting( 'dark_theme_simplicity_contact_accent_color', array(
            'default'           => '#0085ff', // Blue-300
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'refresh',
        ) );

        $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'dark_theme_simplicity_contact_accent_color', array(
            'label'    => __( 'Contact Accent Color', 'dark-theme-simplicity' ),
            'section'  => 'dark_theme_simplicity_contact_section',
            'settings' => 'dark_theme_simplicity_contact_accent_color',
        ) ) );

        // Privacy Policy URL
        $wp_customize->add_setting( 'dark_theme_simplicity_privacy_url', array(
            'default'           => '#',
            'sanitize_callback' => 'esc_url_raw',
            'transport'         => 'refresh',
        ) );

        $wp_customize->add_control( 'dark_theme_simplicity_privacy_url', array(
            'label'    => __( 'Privacy Policy URL', 'dark-theme-simplicity' ),
            'section'  => 'dark_theme_simplicity_footer_options',
            'settings' => 'dark_theme_simplicity_privacy_url',
            'type'     => 'url',
        ) );

        // Privacy Policy Text
        $wp_customize->add_setting( 'dark_theme_simplicity_privacy_text', array(
            'default'           => __( 'Privacy Policy', 'dark-theme-simplicity' ),
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'refresh',
        ) );

        $wp_customize->add_control( 'dark_theme_simplicity_privacy_text', array(
            'label'    => __( 'Privacy Policy Text', 'dark-theme-simplicity' ),
            'section'  => 'dark_theme_simplicity_footer_options',
            'settings' => 'dark_theme_simplicity_privacy_text',
            'type'     => 'text',
        ) );

        // Terms of Service URL
        $wp_customize->add_setting( 'dark_theme_simplicity_terms_url', array(
            'default'           => '#',
            'sanitize_callback' => 'esc_url_raw',
            'transport'         => 'refresh',
        ) );

        $wp_customize->add_control( 'dark_theme_simplicity_terms_url', array(
            'label'    => __( 'Terms of Service URL', 'dark-theme-simplicity' ),
            'section'  => 'dark_theme_simplicity_footer_options',
            'settings' => 'dark_theme_simplicity_terms_url',
            'type'     => 'url',
        ) );

        // Terms of Service Text
        $wp_customize->add_setting( 'dark_theme_simplicity_terms_text', array(
            'default'           => __( 'Terms of Service', 'dark-theme-simplicity' ),
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'refresh',
        ) );

        $wp_customize->add_control( 'dark_theme_simplicity_terms_text', array(
            'label'    => __( 'Terms of Service Text', 'dark-theme-simplicity' ),
            'section'  => 'dark_theme_simplicity_footer_options',
            'settings' => 'dark_theme_simplicity_terms_text',
            'type'     => 'text',
        ) );

        // Cookie Policy URL
        $wp_customize->add_setting( 'dark_theme_simplicity_cookie_url', array(
            'default'           => '#',
            'sanitize_callback' => 'esc_url_raw',
            'transport'         => 'refresh',
        ) );

        $wp_customize->add_control( 'dark_theme_simplicity_cookie_url', array(
            'label'    => __( 'Cookie Policy URL', 'dark-theme-simplicity' ),
            'section'  => 'dark_theme_simplicity_footer_options',
            'settings' => 'dark_theme_simplicity_cookie_url',
            'type'     => 'url',
        ) );

        // Cookie Policy Text
        $wp_customize->add_setting( 'dark_theme_simplicity_cookie_text', array(
            'default'           => __( 'Cookie Policy', 'dark-theme-simplicity' ),
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'refresh',
        ) );

        $wp_customize->add_control( 'dark_theme_simplicity_cookie_text', array(
            'label'    => __( 'Cookie Policy Text', 'dark-theme-simplicity' ),
            'section'  => 'dark_theme_simplicity_footer_options',
            'settings' => 'dark_theme_simplicity_cookie_text',
            'type'     => 'text',
        ) );

        // Default Widget Visibility Setting
        $wp_customize->add_setting( 'dark_theme_simplicity_default_show_widgets', array(
            'default'           => 'yes',
            'sanitize_callback' => 'dark_theme_simplicity_sanitize_checkbox',
            'transport'         => 'refresh',
        ) );

        $wp_customize->add_control( 'dark_theme_simplicity_default_show_widgets', array(
            'label'       => __( 'Show Sidebar Widgets by Default', 'dark-theme-simplicity' ),
            'description' => __( 'Default setting for showing sidebar widgets on new pages/posts. Individual pages can override this setting.', 'dark-theme-simplicity' ),
            'section'     => 'dark_theme_simplicity_footer_options',
            'settings'    => 'dark_theme_simplicity_default_show_widgets',
            'type'        => 'checkbox',
        ) );

        // LinkedIn URL
        $wp_customize->add_setting( 'dark_theme_simplicity_linkedin_url', array(
            'default'           => '',
            'sanitize_callback' => 'esc_url_raw',
            'transport'         => 'refresh',
        ) );
        
        $wp_customize->add_control( 'dark_theme_simplicity_linkedin_url', array(
            'label'    => __( 'LinkedIn URL', 'dark-theme-simplicity' ),
            'section'  => 'dark_theme_simplicity_social_links',
            'settings' => 'dark_theme_simplicity_linkedin_url',
            'type'     => 'url',
        ) );
        
        // Twitter/X URL
        $wp_customize->add_setting( 'dark_theme_simplicity_twitter_url', array(
            'default'           => '',
            'sanitize_callback' => 'esc_url_raw',
            'transport'         => 'refresh',
        ) );
        
        $wp_customize->add_control( 'dark_theme_simplicity_twitter_url', array(
            'label'    => __( 'Twitter/X URL', 'dark-theme-simplicity' ),
            'section'  => 'dark_theme_simplicity_social_links',
            'settings' => 'dark_theme_simplicity_twitter_url',
            'type'     => 'url',
        ) );

        // Blog Index Hero Section
        $wp_customize->add_section( 'dark_theme_simplicity_blog_hero_section', array(
            'title'       => __( 'Blog Index Hero', 'dark-theme-simplicity' ),
            'description' => __( 'Customize the hero section on the blog index page', 'dark-theme-simplicity' ),
            'priority'    => 100,
        ) );

        // Blog Hero Title
        $wp_customize->add_setting( 'dark_theme_simplicity_blog_hero_title', array(
            'default'           => __( 'Insights & Resources', 'dark-theme-simplicity' ),
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'refresh',
        ) );

        $wp_customize->add_control( 'dark_theme_simplicity_blog_hero_title', array(
            'label'    => __( 'Blog Hero Title', 'dark-theme-simplicity' ),
            'description' => __( 'The main title displayed in the blog index hero section', 'dark-theme-simplicity' ),
            'section'  => 'dark_theme_simplicity_blog_hero_section',
            'settings' => 'dark_theme_simplicity_blog_hero_title',
            'type'     => 'text',
        ) );

        // Blog Hero Description
        $wp_customize->add_setting( 'dark_theme_simplicity_blog_hero_description', array(
            'default'           => __( 'The latest tools, trends, and strategies to elevate your digital presence and maximize your business growth.', 'dark-theme-simplicity' ),
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'refresh',
        ) );

        $wp_customize->add_control( 'dark_theme_simplicity_blog_hero_description', array(
            'label'    => __( 'Blog Hero Description', 'dark-theme-simplicity' ),
            'description' => __( 'The description text displayed below the title in the blog index hero section', 'dark-theme-simplicity' ),
            'section'  => 'dark_theme_simplicity_blog_hero_section',
            'settings' => 'dark_theme_simplicity_blog_hero_description',
            'type'     => 'textarea',
        ) );

        // Blog Hero Background Image
        $wp_customize->add_setting( 'dark_theme_simplicity_blog_hero_bg_image', array(
            'default'           => '',
            'sanitize_callback' => 'esc_url_raw',
            'transport'         => 'refresh',
        ) );

        $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'dark_theme_simplicity_blog_hero_bg_image', array(
            'label'    => __( 'Blog Hero Background Image', 'dark-theme-simplicity' ),
            'description' => __( 'Upload or select an image for the blog index hero section background', 'dark-theme-simplicity' ),
            'section'  => 'dark_theme_simplicity_blog_hero_section',
            'settings' => 'dark_theme_simplicity_blog_hero_bg_image',
        ) ) );

        // Blog Hero Background Overlay Opacity
        $wp_customize->add_setting( 'dark_theme_simplicity_blog_hero_overlay_opacity', array(
            'default'           => '70',
            'sanitize_callback' => 'absint',
            'transport'         => 'refresh',
        ) );

        $wp_customize->add_control( 'dark_theme_simplicity_blog_hero_overlay_opacity', array(
            'label'       => __( 'Background Overlay Opacity (%)', 'dark-theme-simplicity' ),
            'description' => __( 'Adjust the opacity of the dark overlay on the background image (0-100)', 'dark-theme-simplicity' ),
            'section'     => 'dark_theme_simplicity_blog_hero_section',
            'settings'    => 'dark_theme_simplicity_blog_hero_overlay_opacity',
            'type'        => 'range',
            'input_attrs' => array(
                'min'  => 0,
                'max'  => 100,
                'step' => 5,
            ),
        ) );

        // Blog Hero Title Color
        $wp_customize->add_setting( 'dark_theme_simplicity_blog_hero_title_color', array(
            'default'           => '#ffffff',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'refresh',
        ) );

        $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'dark_theme_simplicity_blog_hero_title_color', array(
            'label'    => __( 'Blog Hero Title Color', 'dark-theme-simplicity' ),
            'section'  => 'dark_theme_simplicity_blog_hero_section',
            'settings' => 'dark_theme_simplicity_blog_hero_title_color',
        ) ) );

        // Blog Hero Description Color
        $wp_customize->add_setting( 'dark_theme_simplicity_blog_hero_desc_color', array(
            'default'           => '#ffffff',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'refresh',
        ) );

        $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'dark_theme_simplicity_blog_hero_desc_color', array(
            'label'    => __( 'Blog Hero Description Color', 'dark-theme-simplicity' ),
            'section'  => 'dark_theme_simplicity_blog_hero_section',
            'settings' => 'dark_theme_simplicity_blog_hero_desc_color',
        ) ) );

        // Blog Hero Description Opacity
        $wp_customize->add_setting( 'dark_theme_simplicity_blog_hero_desc_opacity', array(
            'default'           => '70',
            'sanitize_callback' => 'absint',
            'transport'         => 'refresh',
        ) );

        $wp_customize->add_control( 'dark_theme_simplicity_blog_hero_desc_opacity', array(
            'label'       => __( 'Description Text Opacity (%)', 'dark-theme-simplicity' ),
            'description' => __( 'Adjust the opacity of the description text (0-100)', 'dark-theme-simplicity' ),
            'section'     => 'dark_theme_simplicity_blog_hero_section',
            'settings'    => 'dark_theme_simplicity_blog_hero_desc_opacity',
            'type'        => 'range',
            'input_attrs' => array(
                'min'  => 0,
                'max'  => 100,
                'step' => 5,
            ),
        ) );

        // Blog Hero Padding
        $wp_customize->add_setting( 'dark_theme_simplicity_blog_hero_padding', array(
            'default'           => 'medium',
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'refresh',
        ) );

        $wp_customize->add_control( 'dark_theme_simplicity_blog_hero_padding', array(
            'label'    => __( 'Blog Hero Padding', 'dark-theme-simplicity' ),
            'section'  => 'dark_theme_simplicity_blog_hero_section',
            'settings' => 'dark_theme_simplicity_blog_hero_padding',
            'type'     => 'select',
            'choices'  => array(
                'small'  => __( 'Small', 'dark-theme-simplicity' ),
                'medium' => __( 'Medium (Default)', 'dark-theme-simplicity' ),
                'large'  => __( 'Large', 'dark-theme-simplicity' ),
                'extra-large' => __( 'Extra Large', 'dark-theme-simplicity' ),
            ),
        ) );

        // Blog Hero Text Alignment
        $wp_customize->add_setting( 'dark_theme_simplicity_blog_hero_alignment', array(
            'default'           => 'left',
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'refresh',
        ) );

        $wp_customize->add_control( 'dark_theme_simplicity_blog_hero_alignment', array(
            'label'    => __( 'Blog Hero Text Alignment', 'dark-theme-simplicity' ),
            'section'  => 'dark_theme_simplicity_blog_hero_section',
            'settings' => 'dark_theme_simplicity_blog_hero_alignment',
            'type'     => 'radio',
            'choices'  => array(
                'left'   => __( 'Left', 'dark-theme-simplicity' ),
                'center' => __( 'Center', 'dark-theme-simplicity' ),
                'right'  => __( 'Right', 'dark-theme-simplicity' ),
            ),
        ) );

        // Tools Template Section
        $wp_customize->add_section( 'dark_theme_simplicity_tools_section', array(
            'title'       => __( 'Tools Template', 'dark-theme-simplicity' ),
            'description' => __( 'Customize the Tools Template page', 'dark-theme-simplicity' ),
            'priority'    => 130,
        ) );
        
        // Tools Hero Title
        $wp_customize->add_setting( 'dark_theme_simplicity_tools_hero_title', array(
            'default'           => __( 'Tools & Resources', 'dark-theme-simplicity' ),
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'refresh',
        ) );

        $wp_customize->add_control( 'dark_theme_simplicity_tools_hero_title', array(
            'label'       => __( 'Tools Hero Title', 'dark-theme-simplicity' ),
            'description' => __( 'The default title for the Tools Template hero section', 'dark-theme-simplicity' ),
            'section'     => 'dark_theme_simplicity_tools_section',
            'settings'    => 'dark_theme_simplicity_tools_hero_title',
            'type'        => 'text',
        ) );
        
        // Tools Hero Description
        $wp_customize->add_setting( 'dark_theme_simplicity_tools_hero_description', array(
            'default'           => __( 'Explore our collection of tools designed to help you optimize your digital presence.', 'dark-theme-simplicity' ),
            'sanitize_callback' => 'sanitize_textarea_field',
            'transport'         => 'refresh',
        ) );

        $wp_customize->add_control( 'dark_theme_simplicity_tools_hero_description', array(
            'label'       => __( 'Tools Hero Description', 'dark-theme-simplicity' ),
            'description' => __( 'The default description for the Tools Template hero section', 'dark-theme-simplicity' ),
            'section'     => 'dark_theme_simplicity_tools_section',
            'settings'    => 'dark_theme_simplicity_tools_hero_description',
            'type'        => 'textarea',
        ) );
        
        // Tools Section Title
        $wp_customize->add_setting( 'dark_theme_simplicity_tools_section_title', array(
            'default'           => __( 'Available Tools', 'dark-theme-simplicity' ),
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'refresh',
        ) );

        $wp_customize->add_control( 'dark_theme_simplicity_tools_section_title', array(
            'label'       => __( 'Tools Section Title', 'dark-theme-simplicity' ),
            'description' => __( 'The heading for the tools grid section', 'dark-theme-simplicity' ),
            'section'     => 'dark_theme_simplicity_tools_section',
            'settings'    => 'dark_theme_simplicity_tools_section_title',
            'type'        => 'text',
        ) );
        
        // Tools Contact Title
        $wp_customize->add_setting( 'dark_theme_simplicity_tools_contact_title', array(
            'default'           => __( 'Get Support', 'dark-theme-simplicity' ),
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'refresh',
        ) );

        $wp_customize->add_control( 'dark_theme_simplicity_tools_contact_title', array(
            'label'       => __( 'Contact Section Title', 'dark-theme-simplicity' ),
            'description' => __( 'The heading for the contact section', 'dark-theme-simplicity' ),
            'section'     => 'dark_theme_simplicity_tools_section',
            'settings'    => 'dark_theme_simplicity_tools_contact_title',
            'type'        => 'text',
        ) );
        
        // Tools Contact Description
        $wp_customize->add_setting( 'dark_theme_simplicity_tools_contact_description', array(
            'default'           => __( 'Questions about our tools? Reach out for assistance.', 'dark-theme-simplicity' ),
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'refresh',
        ) );

        $wp_customize->add_control( 'dark_theme_simplicity_tools_contact_description', array(
            'label'       => __( 'Contact Section Description', 'dark-theme-simplicity' ),
            'description' => __( 'The description text for the contact section', 'dark-theme-simplicity' ),
            'section'     => 'dark_theme_simplicity_tools_section',
            'settings'    => 'dark_theme_simplicity_tools_contact_description',
            'type'        => 'textarea',
        ) );
        
        // Tools Hero Background Image
        $wp_customize->add_setting( 'dark_theme_simplicity_tools_hero_bg_image', array(
            'default'           => '',
            'sanitize_callback' => 'esc_url_raw',
            'transport'         => 'refresh',
        ) );

        $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'dark_theme_simplicity_tools_hero_bg_image', array(
            'label'       => __( 'Tools Hero Background Image', 'dark-theme-simplicity' ),
            'description' => __( 'Upload or select an image for the tools hero section background', 'dark-theme-simplicity' ),
            'section'     => 'dark_theme_simplicity_tools_section',
            'settings'    => 'dark_theme_simplicity_tools_hero_bg_image',
        ) ) );

        // Tools Hero Background Overlay Opacity
        $wp_customize->add_setting( 'dark_theme_simplicity_tools_hero_overlay_opacity', array(
            'default'           => '70',
            'sanitize_callback' => 'absint',
            'transport'         => 'refresh',
        ) );

        $wp_customize->add_control( 'dark_theme_simplicity_tools_hero_overlay_opacity', array(
            'label'       => __( 'Background Overlay Opacity (%)', 'dark-theme-simplicity' ),
            'description' => __( 'Adjust the opacity of the dark overlay on the background image (0-100)', 'dark-theme-simplicity' ),
            'section'     => 'dark_theme_simplicity_tools_section',
            'settings'    => 'dark_theme_simplicity_tools_hero_overlay_opacity',
            'type'        => 'range',
            'input_attrs' => array(
                'min'  => 0,
                'max'  => 100,
                'step' => 5,
            ),
        ) );
        
        // Tools Hero Title Color
        $wp_customize->add_setting( 'dark_theme_simplicity_tools_hero_title_color', array(
            'default'           => '#ffffff',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'refresh',
        ) );

        $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'dark_theme_simplicity_tools_hero_title_color', array(
            'label'    => __( 'Tools Hero Title Color', 'dark-theme-simplicity' ),
            'section'  => 'dark_theme_simplicity_tools_section',
            'settings' => 'dark_theme_simplicity_tools_hero_title_color',
        ) ) );

        // Tools Hero Description Color
        $wp_customize->add_setting( 'dark_theme_simplicity_tools_hero_desc_color', array(
            'default'           => '#ffffff',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'refresh',
        ) );

        $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'dark_theme_simplicity_tools_hero_desc_color', array(
            'label'    => __( 'Tools Hero Description Color', 'dark-theme-simplicity' ),
            'section'  => 'dark_theme_simplicity_tools_section',
            'settings' => 'dark_theme_simplicity_tools_hero_desc_color',
        ) ) );

        // Add Blog Post Display Options section to customizer
        $wp_customize->add_section('dark_theme_simplicity_post_display', array(
            'title'       => __('Blog Post Display Options', 'dark-theme-simplicity'),
            'description' => __('Configure default display options for blog posts.', 'dark-theme-simplicity'),
            'priority'    => 160,
        ));
        
        // Default setting for Show Table of Contents
        $wp_customize->add_setting('dark_theme_simplicity_default_show_toc', array(
            'default'           => 'yes',
            'sanitize_callback' => 'dark_theme_simplicity_sanitize_yes_no',
            'type'              => 'theme_mod',
        ));
        
        $wp_customize->add_control('dark_theme_simplicity_default_show_toc', array(
            'label'    => __('Show Table of Contents by default', 'dark-theme-simplicity'),
            'section'  => 'dark_theme_simplicity_post_display',
            'type'     => 'radio',
            'choices'  => array(
                'yes' => __('Yes', 'dark-theme-simplicity'),
                'no'  => __('No', 'dark-theme-simplicity'),
            ),
        ));
        
        // Default setting for Share Buttons
        $wp_customize->add_setting('dark_theme_simplicity_default_show_share', array(
            'default'           => 'yes',
            'sanitize_callback' => 'dark_theme_simplicity_sanitize_yes_no',
            'type'              => 'theme_mod',
        ));
        
        $wp_customize->add_control('dark_theme_simplicity_default_show_share', array(
            'label'    => __('Show Share Buttons by default', 'dark-theme-simplicity'),
            'section'  => 'dark_theme_simplicity_post_display',
            'type'     => 'radio',
            'choices'  => array(
                'yes' => __('Yes', 'dark-theme-simplicity'),
                'no'  => __('No', 'dark-theme-simplicity'),
            ),
        ));
        
        // Default setting for Sidebar Widgets
        $wp_customize->add_setting('dark_theme_simplicity_default_show_widgets', array(
            'default'           => 'yes',
            'sanitize_callback' => 'dark_theme_simplicity_sanitize_yes_no',
            'type'              => 'theme_mod',
        ));
        
        $wp_customize->add_control('dark_theme_simplicity_default_show_widgets', array(
            'label'    => __('Show Sidebar Widgets by default', 'dark-theme-simplicity'),
            'section'  => 'dark_theme_simplicity_post_display',
            'priority' => 20, // Ensure this appears after other controls
            'type'     => 'radio',
            'choices'  => array(
                'yes' => __('Yes', 'dark-theme-simplicity'),
                'no'  => __('No', 'dark-theme-simplicity'),
            ),
        ));
    }
}
add_action( 'customize_register', 'dark_theme_simplicity_customize_register' );

/**
 * Generate custom CSS for customizer settings
 */
if (!function_exists('dark_theme_simplicity_customizer_css')) {
    function dark_theme_simplicity_customizer_css() {
        // Get customizer values for quicker access
        $service_bg_color = get_theme_mod('dark_theme_simplicity_service_card_bg_color', '#1e1e24');
        $service_accent_color = get_theme_mod('dark_theme_simplicity_service_card_accent_color', '#0085ff');
        $contact_accent_color = get_theme_mod('dark_theme_simplicity_contact_accent_color', '#0085ff');
        $logo_color = get_theme_mod('dark_theme_simplicity_logo_color', '#60a5fa');
        $hero_button_color = get_theme_mod('dark_theme_simplicity_hero_button_color', '#0085ff');
        $hero_button_hover_color = get_theme_mod('dark_theme_simplicity_hero_button_hover_color', '#0057a7');
        $hero_bg_image = get_theme_mod('dark_theme_simplicity_hero_bg_image', 'https://braddaiber.com/wp-content/uploads/2024/03/shutterstock_134653565-1-scaled.webp');
        ?>
        <style type="text/css">
            /* CSS Custom Properties for Theme Settings */
            :root {
                --logo-color: <?php echo esc_attr($logo_color); ?>;
                --hero-bg-image: url('<?php echo esc_url($hero_bg_image); ?>');
                --hero-btn-color: <?php echo esc_attr($hero_button_color); ?>;
                --hero-btn-hover: <?php echo esc_attr($hero_button_hover_color); ?>;
                --service-bg: <?php echo esc_attr($service_bg_color); ?>;
                --service-accent: <?php echo esc_attr($service_accent_color); ?>;
                --contact-accent: <?php echo esc_attr($contact_accent_color); ?>;
            }
        
            /* Logo Color */
            .site-header .text-blue-400 path,
            .site-footer .text-blue-400 path {
                stroke: var(--logo-color) !important;
            }
        
            /* Hero Section */
            .hero-section {
                background-image: var(--hero-bg-image);
            }
            
            .hero-cta-primary {
                background-color: var(--hero-btn-color) !important;
            }

            .hero-cta-primary:hover {
                background-color: var(--hero-btn-hover) !important;
            }
            
            /* Global section labels - keep these the default blue color */
            .section-label {
                color: #0085ff !important;
            }
            
            /* Services Section */
            /* Service Card Background */
            .service-card {
                background-color: var(--service-bg) !important;
            }
            
            /* Service Icons */
            #what-we-do .text-blue-300 {
                color: var(--service-accent) !important;
            }
            
            /* Service Card Border on Hover */
            #what-we-do .hover\:border-blue-500:hover {
                border-color: var(--service-accent) !important;
            }
            
            /* Contact Section */
            /* Contact Icons */
            .contact-section .contact-icon {
                color: var(--contact-accent) !important;
            }
            
            /* Contact Icon Backgrounds */
            .contact-section .bg-blue-300\/20 {
                background-color: var(--contact-accent)20 !important;
            }
        </style>
        <?php
    }
}
// Only load full customizer CSS on pages that need it (not single posts)
if (!function_exists('dark_theme_simplicity_should_load_customizer_css')) {
    function dark_theme_simplicity_should_load_customizer_css() {
        // Load on front page, pages with specific templates, but not on single posts
        return is_front_page() || is_page() || is_home() || is_archive();
    }
}

// Conditional loading of customizer CSS
add_action( 'wp_head', function() {
    if (dark_theme_simplicity_should_load_customizer_css()) {
        dark_theme_simplicity_customizer_css();
    } else if (is_singular('post')) {
        // On single posts, only output essential customizer CSS
        dark_theme_simplicity_minimal_customizer_css();
    }
});

// Minimal customizer CSS for single posts
if (!function_exists('dark_theme_simplicity_minimal_customizer_css')) {
    function dark_theme_simplicity_minimal_customizer_css() {
        $logo_color = get_theme_mod('dark_theme_simplicity_logo_color', '#60a5fa');
        ?>
        <style type="text/css">
            /* Essential customizer styles for single posts */
            .site-header .text-blue-400 path,
            .site-footer .text-blue-400 path {
                stroke: <?php echo esc_attr($logo_color); ?> !important;
            }
        </style>
        <?php
    }
}

/**
 * Sanitize repeater values
 */
if (!function_exists('dark_theme_simplicity_sanitize_repeater')) {
    function dark_theme_simplicity_sanitize_repeater( $input ) {
        // Check if input is empty or not a string
        if (empty($input) || !is_string($input)) {
            return json_encode(array());
        }
        
        // Try to decode JSON safely
        $input_decoded = json_decode($input, true);
        
        // Check for JSON errors
        if (json_last_error() !== JSON_ERROR_NONE) {
            error_log('Dark Theme Simplicity - JSON Error in repeater: ' . json_last_error_msg() . ' - Input: ' . substr($input, 0, 100));
            
            // Return default empty array if JSON is invalid
            return json_encode(array());
        }
        
        // If not an array, return empty array JSON
        if (!is_array($input_decoded)) {
            return json_encode(array());
        }
        
        $sanitized_data = array();
        
        foreach ($input_decoded as $key => $value) {
            // Skip if value is not an array
            if (!is_array($value)) {
                continue;
            }
            
            $sanitized_item = array();
            
            // Ensure required fields exist with defaults
            if (!isset($value['title']) || empty($value['title'])) {
                $sanitized_item['title'] = 'Title';
            } else {
                $sanitized_item['title'] = sanitize_text_field($value['title']);
            }
            
            if (!isset($value['description']) || empty($value['description'])) {
                $sanitized_item['description'] = 'Description';
            } else {
                $sanitized_item['description'] = sanitize_textarea_field($value['description']);
            }
            
            // Handle optional fields
            if (isset($value['icon'])) {
                $sanitized_item['icon'] = sanitize_text_field($value['icon']);
            }
            
            $sanitized_data[] = $sanitized_item;
        }
        
        // If we end up with an empty array, add at least one default item
        if (empty($sanitized_data)) {
            $sanitized_data[] = array(
                'title' => 'Default Item',
                'description' => 'Default Description',
                'icon' => 'default'
            );
        }
        
        // Use try-catch for JSON encoding to handle any unexpected errors
        try {
            $json_result = json_encode($sanitized_data);
            if ($json_result === false) {
                throw new Exception('JSON encode failed');
            }
            return $json_result;
        } catch (Exception $e) {
            error_log('Dark Theme Simplicity - JSON Encoding Error: ' . $e->getMessage());
            // Return a safe default
            return json_encode(array(
                array(
                    'title' => 'Default Item',
                    'description' => 'Default Description',
                    'icon' => 'default'
                )
            ));
        }
    }
}

/**
 * Sanitize font weight
 */
if (!function_exists('dark_theme_simplicity_sanitize_font_weight')) {
    function dark_theme_simplicity_sanitize_font_weight( $input ) {
        $valid = array( '300', '400', '500', '600', '700' );
        
        if ( in_array( $input, $valid ) ) {
            return $input;
        }
        
        return '500'; // Default
    }
}

/**
 * Sanitize RGBA color value
 */
if (!function_exists('dark_theme_simplicity_sanitize_rgba')) {
    function dark_theme_simplicity_sanitize_rgba( $color ) {
        if ( empty( $color ) || is_array( $color ) ) {
            return 'rgba(255, 255, 255, 0.7)';
        }
        
        // Check if it's an rgba color
        if ( strpos( $color, 'rgba' ) !== false ) {
            // Get the rgba values
            preg_match( '/^rgba\(\s*(\d+),\s*(\d+),\s*(\d+),\s*([\d\.]+)\s*\)$/', $color, $matches );
            if ( count( $matches ) === 5 ) {
                $red = intval( $matches[1] );
                $green = intval( $matches[2] );
                $blue = intval( $matches[3] );
                $alpha = floatval( $matches[4] );
                
                // Make sure RGB values are between 0-255
                $red = max( 0, min( 255, $red ) );
                $green = max( 0, min( 255, $green ) );
                $blue = max( 0, min( 255, $blue ) );
                
                // Make sure alpha is between 0-1
                $alpha = max( 0, min( 1, $alpha ) );
                
                return sprintf( 'rgba(%d, %d, %d, %.2f)', $red, $green, $blue, $alpha );
            }
        }
        
        // If it's a hex color, just return it
        if ( preg_match( '/^#[a-f0-9]{6}$/i', $color ) ) {
            return $color;
        }
        
        // Default to semi-transparent white
        return 'rgba(255, 255, 255, 0.7)';
    }
}

/**
 * Sanitize checkbox values
 */
if (!function_exists('dark_theme_simplicity_sanitize_checkbox')) {
    // This function is already defined in customizer-setup.php
    // We're just adding the function_exists check to prevent duplicate definition errors
}

/**
 * Helper function to get service items
 */
if (!function_exists('dt_get_service_items')) {
    function dt_get_service_items() {
        $items = get_theme_mod('dark_theme_simplicity_service_items', '');
        
        if (empty($items)) {
            // Return default items if none set
            return array(
                array(
                    'icon' => 'globe',
                    'title' => 'Strategic SEO',
                    'description' => 'Boost your visibility with search engine optimization that drives organic traffic.'
                ),
                array(
                    'icon' => 'file-text',
                    'title' => 'Content Creation',
                    'description' => 'Engaging, on-brand content that resonates with your target audience.'
                ),
                array(
                    'icon' => 'monitor',
                    'title' => 'Website Development',
                    'description' => 'Custom websites designed for user experience and conversion optimization.'
                ),
                array(
                    'icon' => 'database',
                    'title' => 'Brand Strategy',
                    'description' => 'Cohesive visual identity and messaging that distinguishes your business.'
                )
            );
        }
        
        $decoded = json_decode($items, true);
        
        // If JSON is corrupted, return defaults
        if (json_last_error() !== JSON_ERROR_NONE || !is_array($decoded)) {
            // Log the error
            error_log('Dark Theme Simplicity: Corrupt JSON in service_items: ' . json_last_error_msg());
            
            // Return default items
            return array(
                array(
                    'icon' => 'globe',
                    'title' => 'Strategic SEO',
                    'description' => 'Boost your visibility with search engine optimization that drives organic traffic.'
                ),
                array(
                    'icon' => 'file-text',
                    'title' => 'Content Creation',
                    'description' => 'Engaging, on-brand content that resonates with your target audience.'
                ),
                array(
                    'icon' => 'monitor',
                    'title' => 'Website Development',
                    'description' => 'Custom websites designed for user experience and conversion optimization.'
                ),
                array(
                    'icon' => 'database',
                    'title' => 'Brand Strategy',
                    'description' => 'Cohesive visual identity and messaging that distinguishes your business.'
                )
            );
        }
        
        return $decoded;
    }
}
