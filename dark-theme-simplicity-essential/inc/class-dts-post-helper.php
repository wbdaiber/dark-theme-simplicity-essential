<?php
/**
 * Post Helper Class for Dark Theme Simplicity
 * 
 * @package Dark_Theme_Simplicity
 */

class DTS_Post_Helper {
    
    /**
     * Post ID
     * @var int
     */
    private $post_id;
    
    /**
     * Display settings cache
     * @var array
     */
    private $display_settings;
    
    /**
     * Constructor
     * 
     * @param int $post_id Post ID
     */
    public function __construct( $post_id ) {
        $this->post_id = $post_id;
    }
    
    /**
     * Get post display settings
     * 
     * @return array
     */
    public function get_display_settings() {
        if ( isset( $this->display_settings ) ) {
            return $this->display_settings;
        }
        
        // Get post meta
        $show_widgets = get_post_meta( $this->post_id, '_show_sidebar_widgets', true );
        $show_toc = get_post_meta( $this->post_id, '_show_table_of_contents', true );
        $show_share = get_post_meta( $this->post_id, '_show_share_buttons', true );
        
        // Get theme defaults
        $default_show_widgets = get_theme_mod( 'dark_theme_simplicity_default_show_widgets', 'yes' );
        $default_show_toc = get_theme_mod( 'dark_theme_simplicity_default_show_toc', 'yes' );
        $default_show_share = get_theme_mod( 'dark_theme_simplicity_default_show_share', 'yes' );
        
        // Apply defaults if not set
        if ( $show_widgets === '' ) $show_widgets = $default_show_widgets;
        if ( $show_toc === '' ) $show_toc = $default_show_toc;
        if ( $show_share === '' ) $show_share = $default_show_share;
        
        // Check for TOC content
        $toc_headings = $this->extract_headings();
        $has_toc = ! empty( $toc_headings ) && $show_toc === 'yes';
        
        $this->display_settings = array(
            'show_widgets' => $show_widgets,
            'show_toc' => $show_toc,
            'show_share' => $show_share,
            'has_toc' => $has_toc,
            'toc_headings' => $toc_headings
        );
        
        return $this->display_settings;
    }
    
    /**
     * Get layout classes based on display settings
     * 
     * @return array
     */
    public function get_layout_classes() {
        $settings = $this->get_display_settings();
        
        // Base classes
        $sidebar_class = ( $settings['show_widgets'] === 'yes' ) ? '' : 'no-sidebar';
        $toc_class = ( $settings['show_toc'] === 'yes' ) ? '' : 'no-toc';
        $share_class = ( $settings['show_share'] === 'yes' ) ? '' : 'no-share';
        
        // Visibility classes
        $visibility_classes = trim( "$sidebar_class $toc_class $share_class" );
        
        // Calculate hidden elements
        $hidden_elements = 0;
        if ( $settings['show_toc'] !== 'yes' ) $hidden_elements++;
        if ( $settings['show_widgets'] !== 'yes' ) $hidden_elements++;
        if ( $settings['show_share'] !== 'yes' ) $hidden_elements++;
        
        // Responsive classes
        $responsive_class = '';
        $content_width_class = 'md:w-4/5 lg:w-3/4';
        $sidebar_width_class = 'md:w-1/5 lg:w-1/4';

        switch ( $hidden_elements ) {
            case 3:
                $responsive_class = 'full-content';
                $content_width_class = 'md:w-full lg:w-full';
                break;
            case 2:
                $responsive_class = 'wide-content';
                $content_width_class = 'md:w-11/12 lg:w-11/12';
                break;
            case 1:
                $responsive_class = 'wider-content';
                $content_width_class = 'md:w-4/5 lg:w-3/4';
                break;
        }

        // Adjust sidebar width if needed — still use 1/4 at lg+ for CTA readability
        if ( $settings['show_toc'] !== 'yes' && $settings['show_share'] !== 'yes' ) {
            $sidebar_width_class = 'md:w-1/6 lg:w-1/4';
        }
        
        // Centered layout
        $centered_layout = '';
        if ( $hidden_elements === 3 ) {
            $centered_layout = 'flex flex-col items-center';
        }
        
        return apply_filters( 'dts_post_layout_classes', array(
            'visibility_classes' => $visibility_classes,
            'responsive_class' => $responsive_class,
            'content_width_class' => $content_width_class,
            'sidebar_width_class' => $sidebar_width_class,
            'centered_layout' => $centered_layout,
            'hidden_elements' => $hidden_elements
        ), $this->post_id );
    }
    
    /**
     * Extract headings from post content
     * 
     * @return array
     */
    private function extract_headings() {
        $content = get_post_field( 'post_content', $this->post_id );
        $pattern = '/<h2.*?>(.*?)<\/h2>/i';
        
        preg_match_all( $pattern, $content, $matches );
        
        if ( empty( $matches[1] ) ) {
            return array();
        }
        
        $headings = array();
        foreach ( $matches[1] as $heading ) {
            $clean_heading = strip_tags( $heading );
            $headings[] = array(
                'text' => $clean_heading,
                'id' => sanitize_title( $clean_heading )
            );
        }
        
        return $headings;
    }
    
    /**
     * Estimate reading time in minutes (instance method)
     *
     * @return int Reading time in minutes (minimum 1)
     */
    public function get_reading_time() {
        return self::calculate_reading_time( $this->post_id );
    }

    /**
     * Estimate reading time in minutes (static, avoids extra instantiation)
     *
     * @param int $post_id Post ID
     * @return int Reading time in minutes (minimum 1)
     */
    public static function calculate_reading_time( $post_id ) {
        $content = get_post_field( 'post_content', $post_id );
        $word_count = str_word_count( wp_strip_all_tags( $content ) );
        return max( 1, ceil( $word_count / 230 ) );
    }

    /**
     * Get table of contents data
     *
     * @return array
     */
    public function get_table_of_contents() {
        $settings = $this->get_display_settings();
        return $settings['toc_headings'];
    }
    
    /**
     * Check if sidebar should be shown
     * 
     * @return bool
     */
    public function should_show_sidebar() {
        $settings = $this->get_display_settings();
        
        return $settings['show_toc'] === 'yes' || 
               $settings['show_share'] === 'yes' || 
               $settings['show_widgets'] === 'yes';
    }
    
    /**
     * Get related posts
     * 
     * @param int $count Number of posts to retrieve
     * @return WP_Query
     */
    public function get_related_posts( $count = 3 ) {
        // Check for manually selected related posts
        $manual_related = get_post_meta( $this->post_id, '_related_posts', true );
        
        if ( ! empty( $manual_related ) && is_array( $manual_related ) ) {
            $args = array(
                'post__in' => $manual_related,
                'posts_per_page' => $count,
                'post_status' => 'publish',
                'orderby' => 'post__in'
            );
            
            $query = new WP_Query( $args );
            
            if ( $query->have_posts() ) {
                return $query;
            }
        }
        
        // Fallback to category-based selection
        return $this->get_category_related_posts( $count );
    }
    
    /**
     * Get related posts by category
     * 
     * @param int $count Number of posts to retrieve
     * @return WP_Query
     */
    private function get_category_related_posts( $count = 3 ) {
        $categories = wp_get_post_categories( $this->post_id );
        
        $args = array(
            'category__in' => $categories,
            'post__not_in' => array( $this->post_id ),
            'posts_per_page' => $count,
            'orderby' => 'rand',
            'post_status' => 'publish'
        );
        
        $query = new WP_Query( $args );
        
        // If not enough posts, add recent posts
        if ( $query->post_count < $count ) {
            $existing_ids = wp_list_pluck( $query->posts, 'ID' );
            $exclude_ids = array_merge( array( $this->post_id ), $existing_ids );
            $posts_needed = $count - $query->post_count;
            
            $recent_args = array(
                'post__not_in' => $exclude_ids,
                'posts_per_page' => $posts_needed,
                'orderby' => 'date',
                'order' => 'DESC',
                'post_status' => 'publish'
            );
            
            $recent_query = new WP_Query( $recent_args );
            
            if ( $recent_query->have_posts() ) {
                $query->posts = array_merge( $query->posts, $recent_query->posts );
                $query->post_count = count( $query->posts );
            }
        }
        
        return $query;
    }
}