/**
 * Dark Theme Simplicity - Consolidated Customizer JavaScript
 * Combines: customizer-repeater.js + customizer-safety.js
 * Prevents conflicts and provides all customizer functionality
 */
(function($) {
    'use strict';

    // === SAFETY WRAPPER FOR ALL CUSTOMIZER OPERATIONS ===
    var safetyWrapper = {
        // Safe JSON stringify for repeater fields
        safeStringify: function(data) {
            try {
                return JSON.stringify(data);
            } catch (e) {
                console.error('Failed to stringify data:', e);
                return '[]';
            }
        },
        
        // Safe JSON parse for repeater fields
        safeParse: function(jsonString) {
            try {
                var data = JSON.parse(jsonString);
                return (data && typeof data === 'object') ? data : [];
            } catch (e) {
                console.error('Failed to parse JSON:', e);
                return [];
            }
        },
        
        // Validate approach items to ensure they won't break the site
        validateApproachItems: function(items) {
            var validItems = [];
            
            if (!Array.isArray(items)) {
                return validItems;
            }
            
            items.forEach(function(item) {
                if (typeof item !== 'object' || item === null) {
                    return;
                }
                
                var validItem = {
                    title: item.title || 'Title',
                    description: item.description || 'Description'
                };
                
                if (item.icon) {
                    validItem.icon = item.icon;
                }
                
                validItems.push(validItem);
            });
            
            return validItems;
        }
    };

    // === CUSTOMIZER REPEATER FUNCTIONALITY ===
    $(document).ready(function() {
        console.log('🎛️ Dark Theme Simplicity Customizer Consolidated Script Loading...');
        
        // Remove any previously attached event handlers to prevent duplicates
        $(document).off('click', '.customizer-repeater-toggle-item');
        $(document).off('click', '.customizer-repeater-add-control-field');
        $(document).off('click', '.customizer-repeater-remove-item');
        
        // Initialize: Ensure expanded items show content
        $('.customizer-repeater-general-control-repeater-item.expanded .customizer-repeater-item-content').show();
        
        // Toggle item content
        $(document).on('click', '.customizer-repeater-toggle-item', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            var $item = $(this).closest('.customizer-repeater-general-control-repeater-item');
            $item.toggleClass('expanded');
            $item.find('.customizer-repeater-item-content').slideToggle(300);
            
            return false;
        });

        // Add new item
        $(document).on('click', '.customizer-repeater-add-control-field', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            var $control = $(this).closest('.customizer-repeater-control-wrap');
            var $repeaterItems = $control.find('.customizer-repeater-general-control-repeater');
            var $firstItem = $repeaterItems.find('.customizer-repeater-general-control-repeater-item').first().clone();
            
            // Reset values in the cloned item
            $firstItem.find('.customizer-repeater-field-input').each(function() {
                $(this).val('');
            });
            
            // Reset title
            $firstItem.find('.customizer-repeater-item-title').text('New Item');
            
            // Make sure it's expanded
            $firstItem.addClass('expanded');
            $firstItem.find('.customizer-repeater-item-content').show();
            
            // Add to the container
            $repeaterItems.append($firstItem);
            
            // Update the value and trigger change
            updateRepeaterValue($control);
            
            return false;
        });
        
        // Remove item
        $(document).on('click', '.customizer-repeater-remove-item', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            var $control = $(this).closest('.customizer-repeater-control-wrap');
            var $repeaterItems = $control.find('.customizer-repeater-general-control-repeater-item');
            
            // Don't remove if it's the only item
            if ($repeaterItems.length > 1) {
                $(this).closest('.customizer-repeater-general-control-repeater-item').remove();
                updateRepeaterValue($control);
            }
            
            return false;
        });
        
        // Update when field values change
        $(document).on('change keyup input', '.customizer-repeater-field-input', function() {
            var $control = $(this).closest('.customizer-repeater-control-wrap');
            
            // If it's a title field, update the header title too
            if ($(this).data('field') === 'title') {
                var newTitle = $(this).val();
                if (newTitle) {
                    $(this).closest('.customizer-repeater-general-control-repeater-item')
                           .find('.customizer-repeater-item-title')
                           .text(newTitle);
                }
            }
            
            updateRepeaterValue($control);
        });
        
        // Make items sortable
        $('.customizer-repeater-general-control-repeater').sortable({
            items: '.customizer-repeater-general-control-repeater-item',
            handle: '.customizer-repeater-item-header',
            update: function(event, ui) {
                updateRepeaterValue($(this).closest('.customizer-repeater-control-wrap'));
            }
        });
        
        // === SAFE UPDATE REPEATER VALUE FUNCTION ===
        window.updateRepeaterValue = function($control) {
            try {
                var repeaterValue = [];
                
                $control.find('.customizer-repeater-general-control-repeater-item').each(function() {
                    var itemValue = {};
                    
                    $(this).find('.customizer-repeater-field-input').each(function() {
                        var $field = $(this);
                        var fieldId = $field.data('field');
                        var fieldValue = $field.val();
                        
                        if (fieldId) {
                            // Make sure we have valid values
                            if (fieldId === 'title' && !fieldValue) {
                                fieldValue = 'Title';
                            }
                            
                            if (fieldId === 'description' && !fieldValue) {
                                fieldValue = 'Description';
                            }
                            
                            itemValue[fieldId] = fieldValue;
                        }
                    });
                    
                    repeaterValue.push(itemValue);
                });
                
                // Validate approach items before saving
                var validatedValue = safetyWrapper.validateApproachItems(repeaterValue);
                
                // Use safe stringify
                var jsonValue = safetyWrapper.safeStringify(validatedValue);
                
                // Save backup before setting
                try {
                    var id = $control.find('.customizer-repeater-collector').attr('id') || 'repeater';
                    localStorage.setItem('dt_backup_' + id, jsonValue);
                } catch (e) {
                    console.error('Error saving backup:', e);
                }
                
                $control.find('.customizer-repeater-collector').val(jsonValue).trigger('change');
                
                // Log success
                console.log('✅ Safely updated repeater value:', validatedValue);
            } catch (error) {
                console.error('❌ Error updating repeater value:', error);
                
                // Try to recover using default empty value
                $control.find('.customizer-repeater-collector').val('[]').trigger('change');
            }
        };
        
        // === SAFETY BACKUP FUNCTIONALITY ===
        var safetyCheckInterval = setInterval(function() {
            var $collector = $('.customizer-repeater-collector');
            
            if ($collector.length) {
                $collector.each(function() {
                    var value = $(this).val();
                    var id = $(this).attr('id') || 'repeater';
                    
                    if (value) {
                        try {
                            JSON.parse(value);
                            localStorage.setItem('dt_backup_' + id, value);
                        } catch (e) {
                            console.error('Invalid JSON in repeater, not backing up:', e);
                        }
                    }
                });
            }
        }, 10000); // Check every 10 seconds
        
        // === INITIALIZATION COMPLETE ===
        if ($('.customizer-repeater-control-wrap').length) {
            console.log('✅ Customizer repeater control initialized successfully');
        } else {
            console.log('ℹ️ No customizer repeater control found on page');
        }
        
        console.log('🎉 Dark Theme Simplicity Customizer Consolidated Script Loaded Successfully!');
    });
    
    // === GLOBAL ERROR HANDLER ===
    window.addEventListener('error', function(e) {
        if (e.message && e.message.indexOf('customizer') !== -1) {
            console.error('🚨 Customizer Error Caught:', e.message);
            // Don't let customizer errors break the entire admin
            e.preventDefault();
            return false;
        }
    });

})(jQuery);