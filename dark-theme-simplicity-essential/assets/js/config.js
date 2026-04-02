/**
 * Dark Theme Simplicity - JavaScript Configuration
 * Centralized configuration for all JavaScript constants and settings
 * Version: 1.0.0
 */

const DTSConfig = {
  // Animation & Timing (in milliseconds)
  animations: {
    fast: 100,
    base: 300,
    slow: 500,
    scrollSmooth: 600,
    notification: 3000
  },
  
  // Breakpoints (in pixels, matching CSS)
  breakpoints: {
    mobile: 768,
    tablet: 1024,
    desktop: 1280,
    wide: 1440
  },
  
  // Common DOM Selectors
  selectors: {
    // Header & Navigation
    header: '.site-header',
    mobileMenu: '#mobile-menu',
    mobileToggle: '#mobile-menu-toggle',
    mobileOverlay: '#mobile-menu-overlay',
    navLinks: '.header-nav-link',
    
    // Content
    entryContent: '.entry-content',

    // Blog & Posts
    shareBtn: '#share-btn',
    mobileShareBtn: '.mobile-share-btn',
    shareDropdown: '#share-dropdown',
    
    // Forms
    contactForm: '.wpcf7-form',
    searchForm: '.search-form',
    
    // Utilities
    copyButtons: '[data-copy]',
    notifications: '.notification'
  },
  
  // CSS Classes
  classes: {
    // Visibility
    hidden: 'hidden',
    show: 'show',
    active: 'active',
    
    // Mobile Menu
    mobileMenuOpen: 'mobile-menu-open',

    // Header
    headerScrolled: 'scrolled',

    // Components
    videoResponsive: 'responsive-video-wrapper'
  },
  
  // UI Messages
  messages: {
    copySuccess: 'Link copied to clipboard!',
    copyError: 'Failed to copy link',
    formSubmitting: 'Submitting...',
    formSuccess: 'Thank you for your message!',
    formError: 'An error occurred. Please try again.',
    searchNoResults: 'No results found',
    loadingMore: 'Loading more...'
  },
  
  // Event Names
  events: {
    domReady: 'DOMContentLoaded',
    load: 'load',
    resize: 'resize',
    scroll: 'scroll',
    click: 'click',
    keydown: 'keydown',
    focus: 'focus',
    blur: 'blur',
    submit: 'submit'
  },
  
  // Keyboard Codes
  keyCodes: {
    enter: 13,
    escape: 27,
    space: 32,
    tab: 9,
    arrowUp: 38,
    arrowDown: 40,
    arrowLeft: 37,
    arrowRight: 39
  },
  
  // ARIA Attributes
  aria: {
    expanded: 'aria-expanded',
    hidden: 'aria-hidden',
    current: 'aria-current',
    label: 'aria-label',
    describedBy: 'aria-describedby',
    controls: 'aria-controls',
    selected: 'aria-selected'
  },
  
  // Feature Flags
  features: {
    smoothScroll: true,
    stickyHeader: true,
    mobileMenu: true,
    notifications: true,
    copyToClipboard: true,
    lazyLoad: false,
    debug: false
  },
  
  // API & External URLs
  urls: {
    ajax: window.dts_ajax ? window.dts_ajax.ajax_url : '/wp-admin/admin-ajax.php',
    nonce: window.dts_ajax ? window.dts_ajax.nonce : ''
  },
  
  // Utility Functions
  utils: {
    // Check if mobile based on breakpoint
    isMobile: () => window.innerWidth < DTSConfig.breakpoints.mobile,
    
    // Check if tablet based on breakpoint
    isTablet: () => window.innerWidth >= DTSConfig.breakpoints.mobile && window.innerWidth < DTSConfig.breakpoints.desktop,
    
    // Check if desktop based on breakpoint
    isDesktop: () => window.innerWidth >= DTSConfig.breakpoints.desktop,
    
    // Debounce function
    debounce: (func, wait) => {
      let timeout;
      return function executedFunction(...args) {
        const later = () => {
          clearTimeout(timeout);
          func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
      };
    },
    
    // Throttle function
    throttle: (func, limit) => {
      let inThrottle;
      return function(...args) {
        if (!inThrottle) {
          func.apply(this, args);
          inThrottle = true;
          setTimeout(() => inThrottle = false, limit);
        }
      };
    }
  }
};

// Export for use in other modules
if (typeof module !== 'undefined' && module.exports) {
  module.exports = DTSConfig;
}