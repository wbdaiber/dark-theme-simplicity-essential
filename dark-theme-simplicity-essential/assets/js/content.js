/**
 * Content Module - Dark Theme Simplicity
 * Handles video embeds, smooth scrolling, TOC functionality, and content enhancements
 * Dependencies: core.js
 * Version: 2.0.0
 */

window.DTSContent = (function() {
    'use strict';
    
    // ============================================
    // VIDEO HANDLING
    // ============================================
    
    /**
     * Initialize responsive video embeds
     */
    function initializeResponsiveVideos() {
        DTSConfig.features.debug && console.log('🎥 Initializing responsive videos...');
        
        // Video platform patterns
        const videoSelectors = [
            'iframe[src*="youtube.com"]',
            'iframe[src*="youtu.be"]',
            'iframe[src*="vimeo.com"]',
            'iframe[src*="dailymotion.com"]',
            'iframe[src*="videopress.com"]',
            '.wp-block-embed iframe',
            '.wp-block-video video'
        ];
        
        // Find all video elements
        const videos = document.querySelectorAll(videoSelectors.join(', '));
        
        videos.forEach(video => {
            // Skip if already wrapped
            if (video.closest('.responsive-video-wrapper')) {
                return;
            }
            
            // Create wrapper
            const wrapper = document.createElement('div');
            wrapper.className = 'responsive-video-wrapper';
            
            // Calculate aspect ratio from iframe dimensions if available
            const width = video.getAttribute('width') || 560;
            const height = video.getAttribute('height') || 315;
            const aspectRatio = (height / width) * 100;
            
            // Set responsive styles
            wrapper.style.position = 'relative';
            wrapper.style.paddingBottom = `${aspectRatio}%`;
            wrapper.style.height = '0';
            wrapper.style.overflow = 'hidden';
            wrapper.style.borderRadius = '0.75rem';
            
            // Style the video element
            video.style.position = 'absolute';
            video.style.top = '0';
            video.style.left = '0';
            video.style.width = '100%';
            video.style.height = '100%';
            
            // Wrap the video
            video.parentNode.insertBefore(wrapper, video);
            wrapper.appendChild(video);
        });
        
        DTSConfig.features.debug && console.log(`✅ Wrapped ${videos.length} video elements`);
    }
    
    /**
     * Handle window resize for videos
     */
    const handleVideoResize = window.DTSCore.debounce(function() {
        DTSConfig.features.debug && console.log('📐 Adjusting video sizes for resize...');
        
        const wrappers = document.querySelectorAll('.responsive-video-wrapper');
        wrappers.forEach(wrapper => {
            const video = wrapper.querySelector('iframe, video');
            if (!video) return;
            
            // Get viewport dimensions
            const viewportWidth = window.innerWidth;
            
            // Adjust margins based on screen size
            if (viewportWidth <= 767) {
                wrapper.style.margin = '1.5rem auto';
                wrapper.style.maxWidth = '450px';
            } else if (viewportWidth <= 1023) {
                wrapper.style.margin = '2rem auto';
                wrapper.style.maxWidth = '600px';
            } else {
                wrapper.style.margin = '2.5rem auto';
                wrapper.style.maxWidth = '854px';
            }
        });
    }, 250);
    
    // ============================================
    // SMOOTH SCROLLING
    // ============================================
    
    /**
     * Initialize smooth scrolling for anchor links
     */
    function initializeSmoothScrolling() {
        DTSConfig.features.debug && console.log('⚡ Setting up smooth scrolling...');
        
        // Enhanced selectors for all TOC and anchor links
        const selectors = [
            '.table-of-contents a',
            '.toc-desktop a',
            '.mobile-toc-list a',
            'a[href^="#"]',
            '.toc-link'
        ];
        
        const tocLinks = document.querySelectorAll(selectors.join(', '));
        
        tocLinks.forEach(link => {
            link.addEventListener(DTSConfig.events.click, function(e) {
                const href = this.getAttribute('href');
                
                // Skip non-anchor links
                if (!href || !href.startsWith('#') || href === '#') {
                    return;
                }
                
                const targetId = href.substring(1);
                const target = document.getElementById(targetId);
                
                if (target) {
                    e.preventDefault();
                    
                    // Calculate offset for fixed header
                    const headerOffset = 100;
                    const elementPosition = target.getBoundingClientRect().top;
                    const offsetPosition = elementPosition + window.pageYOffset - headerOffset;
                    
                    // Use native smooth scrolling if supported
                    if ('scrollBehavior' in document.documentElement.style) {
                        window.scrollTo({
                            top: offsetPosition,
                            behavior: 'smooth'
                        });
                    } else {
                        // jQuery fallback for older browsers
                        if (window.jQuery) {
                            window.jQuery('html, body').animate({
                                scrollTop: offsetPosition
                            }, DTSConfig.animations.scrollSmooth);
                        } else {
                            window.scrollTo(0, offsetPosition);
                        }
                    }
                    
                    // Update focus for accessibility
                    target.focus();
                    
                    // Update URL hash without jumping
                    if (history.pushState) {
                        history.pushState(null, null, href);
                    }
                }
            });
        });
        
        DTSConfig.features.debug && console.log(`✅ Smooth scrolling enabled for ${tocLinks.length} links`);
    }
    
    /**
     * Handle hash scrolling on page load
     */
    function handleHashScrolling() {
        // Check if there's a hash in the URL on page load
        if (window.location.hash) {
            setTimeout(() => {
                const target = document.querySelector(window.location.hash);
                if (target) {
                    const headerOffset = 100;
                    const elementPosition = target.getBoundingClientRect().top;
                    const offsetPosition = elementPosition + window.pageYOffset - headerOffset;
                    
                    window.scrollTo({
                        top: offsetPosition,
                        behavior: 'smooth'
                    });
                }
            }, DTSConfig.animations.slow); // Delay to ensure page is fully loaded
        }
    }
    
    // ============================================
    // HEADING ID GENERATION
    // ============================================
    
    /**
     * Generate IDs for headings to enable TOC linking
     */
    function generateHeadingIds() {
        DTSConfig.features.debug && console.log('🏷️ Generating heading IDs...');
        
        const headings = document.querySelectorAll('.entry-content h2, .entry-content h3, .entry-content h4');
        let count = 0;
        
        headings.forEach(heading => {
            if (!heading.id) {
                // Create ID from heading text
                let text = heading.textContent || heading.innerText;
                let id = text.toLowerCase()
                    .replace(/[^\w\s-]/g, '') // Remove special characters
                    .replace(/\s+/g, '-') // Replace spaces with hyphens
                    .replace(/--+/g, '-') // Replace multiple hyphens with single
                    .replace(/^-|-$/g, ''); // Remove leading/trailing hyphens
                
                // Ensure uniqueness
                let finalId = id;
                let counter = 1;
                while (document.getElementById(finalId)) {
                    finalId = `${id}-${counter}`;
                    counter++;
                }
                
                heading.id = finalId;
                count++;
            }
        });
        
        DTSConfig.features.debug && console.log(`✅ Generated ${count} heading IDs`);
    }
    
    // ============================================
    // TABLE ENHANCEMENTS
    // ============================================
    
    /**
     * Enhance tables with responsive wrappers and scroll indicators
     */
    function enhanceTables() {
        DTSConfig.features.debug && console.log('📊 Enhancing tables...');
        
        const tables = document.querySelectorAll('.entry-content table');
        
        tables.forEach(table => {
            // Skip if already wrapped
            if (table.closest('.table-wrapper')) {
                return;
            }
            
            // Create wrapper
            const wrapper = document.createElement('div');
            wrapper.className = 'table-wrapper';
            
            // Create scroll indicator
            const scrollIndicator = document.createElement('div');
            scrollIndicator.className = 'scroll-indicator';
            scrollIndicator.textContent = '← Scroll →';
            scrollIndicator.style.display = 'none';
            
            // Wrap table
            table.parentNode.insertBefore(wrapper, table);
            wrapper.appendChild(scrollIndicator);
            wrapper.appendChild(table);
            
            // Check if table needs scrolling
            const checkScroll = () => {
                if (table.scrollWidth > wrapper.clientWidth) {
                    wrapper.classList.add('has-scroll');
                    scrollIndicator.style.display = 'block';
                } else {
                    wrapper.classList.remove('has-scroll');
                    scrollIndicator.style.display = 'none';
                }
            };
            
            // Check on load and resize
            checkScroll();
            window.addEventListener('resize', window.DTSCore.debounce(checkScroll, 250));
        });
        
        DTSConfig.features.debug && console.log(`✅ Enhanced ${tables.length} tables`);
    }
    
    // ============================================
    // LAZY LOADING
    // ============================================
    
    /**
     * Initialize lazy loading for images
     */
    function initializeLazyLoading() {
        if ('IntersectionObserver' in window) {
            DTSConfig.features.debug && console.log('👁️ Setting up lazy loading...');
            
            const imageObserver = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        img.src = img.dataset.src;
                        img.classList.remove('lazy');
                        observer.unobserve(img);
                    }
                });
            });
            
            const lazyImages = document.querySelectorAll('img[data-src]');
            lazyImages.forEach(img => imageObserver.observe(img));
            
            DTSConfig.features.debug && console.log(`✅ Lazy loading enabled for ${lazyImages.length} images`);
        }
    }
    
    // ============================================
    // AJAX CONTENT HANDLING
    // ============================================
    
    /**
     * Handle dynamically loaded content
     */
    function handleAjaxContent() {
        DTSConfig.features.debug && console.log('🔄 Setting up AJAX content handler...');
        
        // Use MutationObserver to detect new content
        const observer = new MutationObserver((mutations) => {
            mutations.forEach((mutation) => {
                if (mutation.type === 'childList' && mutation.addedNodes.length > 0) {
                    mutation.addedNodes.forEach((node) => {
                        if (node.nodeType === Node.ELEMENT_NODE) {
                            // Re-initialize content enhancements for new content
                            initializeResponsiveVideos();
                            enhanceTables();
                            generateHeadingIds();
                            initializeSmoothScrolling();
                        }
                    });
                }
            });
        });
        
        // Start observing
        observer.observe(document.body, {
            childList: true,
            subtree: true
        });
        
        DTSConfig.features.debug && console.log('✅ AJAX content handler active');
    }
    
    // ============================================
    // INITIALIZATION
    // ============================================
    
    /**
     * Initialize all content functionality
     */
    function init() {
        DTSConfig.features.debug && console.log('📄 Content Module: Initializing...');
        
        try {
            // Core content enhancements
            generateHeadingIds();
            initializeResponsiveVideos();
            enhanceTables();
            initializeSmoothScrolling();
            initializeLazyLoading();
            
            // Dynamic content handling
            handleAjaxContent();
            
            // Event listeners
            window.addEventListener('resize', handleVideoResize);
            
            // Handle hash scrolling on load
            handleHashScrolling();
            
            DTSConfig.features.debug && console.log('✅ Content Module: All functionality loaded');
        } catch (error) {
            if (window.DTSCore) {
                window.DTSCore.handleError('Content Module', error);
            } else {
                console.error('Content Module Error:', error);
            }
        }
    }
    
    // ============================================
    // PUBLIC API
    // ============================================
    
    return {
        init,
        initializeResponsiveVideos,
        initializeSmoothScrolling,
        generateHeadingIds,
        enhanceTables,
        initializeLazyLoading
    };
})();

// Auto-initialize when DOM is ready
if (window.DTSCore) {
    window.DTSCore.ready(window.DTSContent.init);
} else {
    document.addEventListener(DTSConfig.events.domReady, window.DTSContent.init);
}