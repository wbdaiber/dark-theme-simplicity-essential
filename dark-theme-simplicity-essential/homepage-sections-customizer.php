<?php
/**
 * Homepage Sections Customizer
 * Adds controls for enabling/disabling homepage sections
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Sanitization function for repeater
 */
if (!function_exists('dt_sanitize_repeater_setting')) {
    function dt_sanitize_repeater_setting($input) {
        // Check if input is empty or not a string, return empty array JSON
        if (empty($input) || !is_string($input)) {
            return json_encode(array());
        }
        
        // Try to decode JSON and handle errors
        $input_decoded = json_decode($input, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            // Log the error for debugging
            error_log('Dark Theme Simplicity - JSON Error in approach_items: ' . json_last_error_msg() . ' - Input: ' . substr($input, 0, 100));
            
            // Return default items instead of corrupted data
            return json_encode(array(
                array(
                    'title' => 'Discover',
                    'description' => 'We start by understanding your needs and goals.',
                    'icon' => 'search'
                ),
                array(
                    'title' => 'Design', 
                    'description' => 'We create solutions tailored to your requirements.',
                    'icon' => 'design'
                ),
                array(
                    'title' => 'Deliver',
                    'description' => 'We implement and deliver results that exceed expectations.',
                    'icon' => 'delivery'
                )
            ));
        }
        
        // If not an array (e.g., got an object or scalar), return empty array JSON
        if (!is_array($input_decoded)) {
            return json_encode(array());
        }
        
        $sanitized_data = array();
        
        foreach ($input_decoded as $item) {
            if (is_array($item)) {
                $sanitized_item = array();
                
                // Ensure critical fields exist
                if (!isset($item['title'])) {
                    $item['title'] = 'Title';
                }
                
                if (!isset($item['description'])) {
                    $item['description'] = 'Description';
                }
                
                foreach ($item as $key => $value) {
                    switch ($key) {
                        case 'title':
                            $sanitized_item[$key] = sanitize_text_field($value);
                            break;
                        case 'description':
                            $sanitized_item[$key] = sanitize_textarea_field($value);
                            break;
                        case 'icon':
                            $sanitized_item[$key] = sanitize_text_field($value);
                            break;
                        case 'url':
                            $sanitized_item[$key] = esc_url_raw($value);
                            break;
                        default:
                            $sanitized_item[$key] = sanitize_text_field($value);
                            break;
                    }
                }
                
                $sanitized_data[] = $sanitized_item;
            }
        }
        
        // If we end up with an empty array after sanitization, provide defaults
        if (empty($sanitized_data)) {
            return json_encode(array(
                array(
                    'title' => 'Discover',
                    'description' => 'We start by understanding your needs and goals.',
                    'icon' => 'search'
                ),
                array(
                    'title' => 'Design', 
                    'description' => 'We create solutions tailored to your requirements.',
                    'icon' => 'design'
                ),
                array(
                    'title' => 'Deliver',
                    'description' => 'We implement and deliver results that exceed expectations.',
                    'icon' => 'delivery'
                )
            ));
        }
        
        // Use a try-catch for JSON encoding to handle any unexpected errors
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
                    'title' => 'Discover',
                    'description' => 'We start by understanding your needs and goals.',
                    'icon' => 'search'
                ),
                array(
                    'title' => 'Design', 
                    'description' => 'We create solutions tailored to your requirements.',
                    'icon' => 'design'
                ),
                array(
                    'title' => 'Deliver',
                    'description' => 'We implement and deliver results that exceed expectations.',
                    'icon' => 'delivery'
                )
            ));
        }
    }
}

// dt_get_approach_items() is defined in functions.php