/**
 * Core Utilities - Dark Theme Simplicity
 * Common utilities, helpers, and shared functionality
 * Dependencies: None (vanilla JS only)
 * Version: 2.0.0
 */

window.DTSCore = (function() {
    'use strict';
    
    // ============================================
    // CLIPBOARD UTILITIES
    // ============================================
    
    /**
     * Modern clipboard copy function with fallback
     * @param {string} text - Text to copy to clipboard
     * @returns {Promise<boolean>} - Success status
     */
    async function copyToClipboard(text) {
        try {
            // Try modern clipboard API first
            if (navigator.clipboard && window.isSecureContext) {
                await navigator.clipboard.writeText(text);
                return true;
            } else {
                // Fallback for older browsers
                return fallbackCopyTextToClipboard(text);
            }
        } catch (error) {
            DTSConfig.features.debug && console.warn('Clipboard copy failed:', error);
            return fallbackCopyTextToClipboard(text);
        }
    }
    
    /**
     * Fallback clipboard copy using deprecated document.execCommand
     * @param {string} text - Text to copy
     * @returns {boolean} - Success status
     */
    function fallbackCopyTextToClipboard(text) {
        const textArea = document.createElement("textarea");
        textArea.value = text;
        
        // Avoid scrolling to bottom
        textArea.style.top = "0";
        textArea.style.left = "0";
        textArea.style.position = "fixed";
        textArea.style.opacity = "0";
        
        document.body.appendChild(textArea);
        textArea.focus();
        textArea.select();
        
        try {
            const successful = document.execCommand('copy');
            document.body.removeChild(textArea);
            return successful;
        } catch (err) {
            DTSConfig.features.debug && console.warn('Fallback clipboard copy failed:', err);
            document.body.removeChild(textArea);
            return false;
        }
    }
    
    // ============================================
    // NOTIFICATION UTILITIES
    // ============================================
    
    /**
     * Show notification feedback to user
     * @param {string} message - Message to display
     * @param {string} type - Notification type (success, error, info)
     * @param {number} duration - Display duration in ms
     */
    function showNotification(message, type = 'success', duration = DTSConfig.animations.notification) {
        // Remove any existing notifications
        const existing = document.querySelector('.dts-notification');
        if (existing) {
            existing.remove();
        }
        
        // Create notification element
        const notification = document.createElement('div');
        notification.className = `dts-notification dts-notification--${type}`;
        notification.textContent = message;
        
        // Add to DOM
        document.body.appendChild(notification);
        
        // Trigger show animation
        requestAnimationFrame(() => {
            notification.classList.add(DTSConfig.classes.show);
        });
        
        // Auto-remove after duration
        setTimeout(() => {
            notification.classList.remove(DTSConfig.classes.show);
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.parentNode.removeChild(notification);
                }
            }, DTSConfig.animations.base); // Wait for hide animation
        }, duration);
    }
    
    /**
     * Show copy feedback notification
     * @param {string} text - Text that was copied
     */
    function showCopyFeedback(text = DTSConfig.messages.copySuccess) {
        showNotification(text, 'success', DTSConfig.animations.notification);
    }
    
    // ============================================
    // POSITIONING UTILITIES
    // ============================================
    
    /**
     * Position dropdown relative to trigger element
     * @param {Element} dropdown - Dropdown element
     * @param {Element} trigger - Trigger element
     * @param {string} position - Position preference ('bottom', 'top', 'auto')
     */
    function positionDropdown(dropdown, trigger, position = 'auto') {
        if (!dropdown || !trigger) return;
        
        const triggerRect = trigger.getBoundingClientRect();
        const dropdownRect = dropdown.getBoundingClientRect();
        const viewportHeight = window.innerHeight;
        const viewportWidth = window.innerWidth;
        
        // Reset position classes
        dropdown.classList.remove('dropdown--top', 'dropdown--bottom', 'dropdown--left', 'dropdown--right');
        
        // Determine vertical position
        const spaceBelow = viewportHeight - triggerRect.bottom;
        const spaceAbove = triggerRect.top;
        
        let verticalClass = 'dropdown--bottom';
        if (position === 'top' || (position === 'auto' && spaceBelow < dropdownRect.height && spaceAbove > dropdownRect.height)) {
            verticalClass = 'dropdown--top';
        }
        
        // Determine horizontal position
        const spaceRight = viewportWidth - triggerRect.right;
        const spaceLeft = triggerRect.left;
        
        let horizontalClass = 'dropdown--right';
        if (spaceRight < dropdownRect.width && spaceLeft > dropdownRect.width) {
            horizontalClass = 'dropdown--left';
        }
        
        dropdown.classList.add(verticalClass, horizontalClass);
    }
    
    // ============================================
    // PERFORMANCE UTILITIES
    // ============================================
    
    /**
     * Debounce function execution
     * @param {Function} func - Function to debounce
     * @param {number} wait - Wait time in ms
     * @param {boolean} immediate - Execute immediately on first call
     * @returns {Function} - Debounced function
     */
    function debounce(func, wait, immediate = false) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                timeout = null;
                if (!immediate) func.apply(this, args);
            };
            const callNow = immediate && !timeout;
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
            if (callNow) func.apply(this, args);
        };
    }
    
    /**
     * Throttle function execution
     * @param {Function} func - Function to throttle
     * @param {number} limit - Time limit in ms
     * @returns {Function} - Throttled function
     */
    function throttle(func, limit) {
        let inThrottle;
        return function executedFunction(...args) {
            if (!inThrottle) {
                func.apply(this, args);
                inThrottle = true;
                setTimeout(() => inThrottle = false, limit);
            }
        };
    }
    
    // ============================================
    // DOM UTILITIES
    // ============================================
    
    /**
     * Wait for DOM to be ready
     * @param {Function} callback - Function to execute when ready
     */
    function ready(callback) {
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', callback);
        } else {
            callback();
        }
    }
    
    /**
     * Create element with attributes and content
     * @param {string} tag - HTML tag name
     * @param {Object} attributes - Element attributes
     * @param {string|Element} content - Element content
     * @returns {Element} - Created element
     */
    function createElement(tag, attributes = {}, content = '') {
        const element = document.createElement(tag);
        
        // Set attributes
        Object.entries(attributes).forEach(([key, value]) => {
            if (key === 'className') {
                element.className = value;
            } else if (key === 'dataset') {
                Object.entries(value).forEach(([dataKey, dataValue]) => {
                    element.dataset[dataKey] = dataValue;
                });
            } else {
                element.setAttribute(key, value);
            }
        });
        
        // Set content
        if (typeof content === 'string') {
            element.innerHTML = content;
        } else if (content instanceof Element) {
            element.appendChild(content);
        }
        
        return element;
    }
    
    // ============================================
    // ERROR HANDLING
    // ============================================
    
    /**
     * Global error handler
     * @param {string} context - Context where error occurred
     * @param {Error} error - Error object
     */
    function handleError(context, error) {
        console.group(`❌ Error in ${context}`);
        console.error('Error details:', error);
        console.trace();
        console.groupEnd();
        
        // In development, you might want to show user-friendly errors
        if (window.location.hostname === 'localhost' || window.location.hostname.includes('staging')) {
            showNotification(`Error in ${context}: ${error.message}`, 'error', 5000);
        }
    }
    
    // ============================================
    // PUBLIC API
    // ============================================
    
    return {
        // Clipboard utilities
        copyToClipboard,
        showCopyFeedback,
        
        // Notification utilities
        showNotification,
        
        // Positioning utilities
        positionDropdown,
        
        // Performance utilities
        debounce,
        throttle,
        
        // DOM utilities
        ready,
        createElement,
        
        // Error handling
        handleError
    };
})();

// Auto-initialize notification styles if not already present
document.addEventListener('DOMContentLoaded', function() {
    if (!document.querySelector('#dts-core-styles')) {
        const styles = document.createElement('style');
        styles.id = 'dts-core-styles';
        styles.textContent = `
            .dts-notification {
                position: fixed;
                top: 20px;
                right: 20px;
                padding: 16px 24px;
                border-radius: 12px;
                font-size: 14px;
                font-weight: 600;
                z-index: 999999;
                transform: translateY(-100px) scale(0.9);
                opacity: 0;
                transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
                box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.3), 
                            0 10px 10px -5px rgba(0, 0, 0, 0.2);
                border: 1px solid rgba(255, 255, 255, 0.1);
                backdrop-filter: blur(10px);
                color: white;
            }
            
            .dts-notification.show {
                transform: translateY(0) scale(1);
                opacity: 1;
            }
            
            .dts-notification--success {
                background: linear-gradient(135deg, #10B981, #059669);
            }
            
            .dts-notification--error {
                background: linear-gradient(135deg, #EF4444, #DC2626);
            }
            
            .dts-notification--info {
                background: linear-gradient(135deg, #3B82F6, #2563EB);
            }
        `;
        document.head.appendChild(styles);
    }
});