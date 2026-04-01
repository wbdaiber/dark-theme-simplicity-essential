<?php
/**
 * Customizer Repeater Control
 */

if (!defined('ABSPATH')) {
    exit;
}

if (class_exists('WP_Customize_Control') && !class_exists('Dark_Theme_Simplicity_Customizer_Repeater_Control')) {
    class Dark_Theme_Simplicity_Customizer_Repeater_Control extends WP_Customize_Control {
        public $type = 'dark-theme-repeater';
        public $fields = array();

        public function __construct($manager, $id, $args = array()) {
            parent::__construct($manager, $id, $args);
            if (isset($args['fields'])) {
                $this->fields = $args['fields'];
            }
        }

        public function enqueue() {
            wp_enqueue_script(
                'dark-theme-repeater-control',
                get_template_directory_uri() . '/js/customizer-repeater.js',
                array('jquery', 'customize-controls'),
                '1.0.0',
                true
            );
            wp_enqueue_style(
                'dark-theme-repeater-control',
                get_template_directory_uri() . '/css/customizer-repeater.css',
                array(),
                '1.0.0'
            );
        }

        public function render_content() {
            $values = json_decode($this->value(), true);
            if (!is_array($values)) {
                $values = array();
            }
            ?>
            <div class="customizer-repeater-control-wrap">
                <span class="customize-control-title"><?php echo esc_html($this->label); ?></span>
                <?php if (!empty($this->description)) : ?>
                    <span class="description customize-control-description"><?php echo esc_html($this->description); ?></span>
                <?php endif; ?>
                
                <div class="customizer-repeater-general-control-repeater">
                    <?php
                    if (!empty($values)) {
                        foreach ($values as $index => $item) {
                            $this->render_repeater_item($item, $index);
                        }
                    } else {
                        // Render one empty item
                        $this->render_repeater_item(array(), 0);
                    }
                    ?>
                </div>
                
                <button type="button" class="button customizer-repeater-add-control-field">
                    <?php esc_html_e('Add New Item', 'dark-theme-simplicity'); ?>
                </button>
                
                <input type="hidden" class="customizer-repeater-collector" <?php $this->link(); ?> value="<?php echo esc_attr($this->value()); ?>" />
            </div>
            <?php
        }

        private function render_repeater_item($item = array(), $index = 0) {
            $title = isset($item['title']) ? $item['title'] : 'Item';
            ?>
            <div class="customizer-repeater-general-control-repeater-item">
                <div class="customizer-repeater-item-header">
                    <span class="customizer-repeater-item-title"><?php echo esc_html($title); ?></span>
                    <div class="customizer-repeater-item-controls">
                        <button type="button" class="customizer-repeater-toggle-item">
                            <span class="dashicons dashicons-arrow-down-alt2"></span>
                        </button>
                        <button type="button" class="customizer-repeater-remove-item">
                            <span class="dashicons dashicons-no-alt"></span>
                        </button>
                    </div>
                </div>
                
                <div class="customizer-repeater-item-content" style="display: none;">
                    <?php
                    foreach ($this->fields as $field) {
                        $field_value = isset($item[$field['id']]) ? $item[$field['id']] : '';
                        $this->render_field($field, $field_value);
                    }
                    ?>
                </div>
            </div>
            <?php
        }

        private function render_field($field, $value = '') {
            ?>
            <div class="customizer-repeater-field">
                <label><?php echo esc_html($field['label']); ?></label>
                <?php
                switch ($field['type']) {
                    case 'text':
                        ?>
                        <input type="text" class="customizer-repeater-field-input" data-field="<?php echo esc_attr($field['id']); ?>" value="<?php echo esc_attr($value); ?>" />
                        <?php
                        break;
                    case 'textarea':
                        ?>
                        <textarea class="customizer-repeater-field-input" data-field="<?php echo esc_attr($field['id']); ?>"><?php echo esc_textarea($value); ?></textarea>
                        <?php
                        break;
                    case 'select':
                        ?>
                        <select class="customizer-repeater-field-input" data-field="<?php echo esc_attr($field['id']); ?>">
                            <?php
                            if (isset($field['choices']) && is_array($field['choices'])) {
                                foreach ($field['choices'] as $choice_value => $choice_label) {
                                    ?>
                                    <option value="<?php echo esc_attr($choice_value); ?>" <?php selected($value, $choice_value); ?>>
                                        <?php echo esc_html($choice_label); ?>
                                    </option>
                                    <?php
                                }
                            }
                            ?>
                        </select>
                        <?php
                        break;
                }
                ?>
            </div>
            <?php
        }
    }
} 