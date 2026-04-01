/**
 * Dark Theme Simplicity - Post Share Functionality
 * 
 * @package Dark_Theme_Simplicity
 */

(function() {
    'use strict';
    
    const DTS_PostShare = {
        
        // Configuration
        config: {
            selectors: {
                shareBtn: '#share-btn',
                shareDropdown: '#share-dropdown',
                shareContainer: '#share-container',
                mobileShareToggle: '.mobile-share-toggle',
                mobileShareDropdown: '.mobile-share-dropdown',
                mobileTocToggle: '.mobile-toc-toggle',
                mobileTocDropdown: '.mobile-toc-dropdown'
            },
            classes: {
                hidden: 'hidden',
                active: 'active'
            },
            dropdownDimensions: {
                width: 220,
                height: 200
            },
            animations: {
                feedbackDuration: 2500,
                feedbackRemoveDelay: 2800
            }
        },
        
        // Cache DOM elements
        elements: {},
        
        /**
         * Initialize the share functionality
         */
        init: function() {
            this.cacheElements();
            
            if (this.elements.shareBtn && this.elements.shareDropdown) {
                this.bindDesktopEvents();
            }
            
            if (this.elements.mobileShareToggle && this.elements.mobileShareDropdown) {
                this.bindMobileShareEvents();
            }
            
            if (this.elements.mobileTocToggle && this.elements.mobileTocDropdown) {
                this.bindMobileTocEvents();
            }
            
            this.bindGlobalEvents();
        },
        
        /**
         * Cache DOM elements
         */
        cacheElements: function() {
            const { selectors } = this.config;
            
            Object.keys(selectors).forEach(key => {
                this.elements[key] = document.querySelector(selectors[key]);
            });
        },
        
        /**
         * Bind desktop share events
         */
        bindDesktopEvents: function() {
            const { shareBtn, shareDropdown } = this.elements;
            
            shareBtn.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();
                this.toggleDesktopDropdown();
            });
            
            // Handle share links
            const shareLinks = shareDropdown.querySelectorAll('a[target="_blank"], button');
            shareLinks.forEach(link => {
                link.addEventListener('click', (e) => {
                    if (link.tagName === 'BUTTON') {
                        e.preventDefault();
                    }
                    
                    setTimeout(() => {
                        this.hideDesktopDropdown();
                    }, 100);
                });
            });
            
            // Window events
            window.addEventListener('resize', () => {
                if (!this.isDropdownHidden(shareDropdown)) {
                    this.positionDropdown();
                }
            });
            
            let scrollTimeout;
            window.addEventListener('scroll', () => {
                if (!this.isDropdownHidden(shareDropdown)) {
                    clearTimeout(scrollTimeout);
                    scrollTimeout = setTimeout(() => {
                        this.positionDropdown();
                    }, 10);
                }
            });
        },
        
        /**
         * Toggle desktop dropdown
         */
        toggleDesktopDropdown: function() {
            const { shareDropdown } = this.elements;
            const isHidden = this.isDropdownHidden(shareDropdown);
            
            if (isHidden) {
                this.showDesktopDropdown();
            } else {
                this.hideDesktopDropdown();
            }
        },
        
        /**
         * Show desktop dropdown
         */
        showDesktopDropdown: function() {
            const { shareBtn, shareDropdown } = this.elements;
            const { classes } = this.config;
            
            this.positionDropdown();
            shareDropdown.classList.remove(classes.hidden);
            shareDropdown.style.display = 'block';
            shareBtn.classList.add(classes.active);
        },
        
        /**
         * Hide desktop dropdown
         */
        hideDesktopDropdown: function() {
            const { shareBtn, shareDropdown } = this.elements;
            const { classes } = this.config;
            
            shareDropdown.classList.add(classes.hidden);
            shareDropdown.style.display = 'none';
            shareBtn.classList.remove(classes.active);
        },
        
        /**
         * Position dropdown correctly
         */
        positionDropdown: function() {
            const { shareBtn, shareDropdown } = this.elements;
            const { dropdownDimensions } = this.config;
            
            const btnRect = shareBtn.getBoundingClientRect();
            const viewportWidth = window.innerWidth;
            const viewportHeight = window.innerHeight;
            
            // Mobile positioning
            if (viewportWidth <= 480) {
                Object.assign(shareDropdown.style, {
                    position: 'fixed',
                    top: 'auto',
                    bottom: '20px',
                    left: '50%',
                    right: 'auto',
                    transform: 'translateX(-50%)',
                    zIndex: '99999'
                });
                return;
            }
            
            // Desktop positioning
            Object.assign(shareDropdown.style, {
                position: 'absolute',
                top: 'calc(100% + 4px)',
                bottom: 'auto',
                transform: 'none',
                zIndex: '99999'
            });
            
            // Horizontal positioning
            const spaceOnRight = viewportWidth - btnRect.right;
            const spaceOnLeft = btnRect.left;
            
            if (spaceOnRight < dropdownDimensions.width && spaceOnLeft > dropdownDimensions.width) {
                shareDropdown.style.right = 'auto';
                shareDropdown.style.left = '0';
            } else {
                shareDropdown.style.left = 'auto';
                shareDropdown.style.right = '0';
            }
            
            // Vertical positioning
            const spaceBelow = viewportHeight - btnRect.bottom;
            if (spaceBelow < dropdownDimensions.height && btnRect.top > dropdownDimensions.height) {
                shareDropdown.style.top = 'auto';
                shareDropdown.style.bottom = 'calc(100% + 4px)';
            }
        },
        
        /**
         * Bind mobile share events
         */
        bindMobileShareEvents: function() {
            const { mobileShareToggle, mobileShareDropdown } = this.elements;
            
            mobileShareToggle.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();
                this.toggleMobileShare();
            });
            
            // Keyboard navigation for dropdown
            mobileShareToggle.addEventListener('keydown', (e) => {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    this.toggleMobileShare();
                } else if (e.key === 'ArrowDown') {
                    e.preventDefault();
                    this.showMobileShare();
                    this.focusFirstShareItem();
                }
            });
            
            // Handle share links
            const mobileShareLinks = mobileShareDropdown.querySelectorAll('a, button');
            mobileShareLinks.forEach((link, index) => {
                link.addEventListener('click', () => {
                    setTimeout(() => {
                        this.hideMobileShare();
                    }, 100);
                });
                
                // Keyboard navigation within dropdown
                link.addEventListener('keydown', (e) => {
                    if (e.key === 'Escape') {
                        e.preventDefault();
                        this.hideMobileShare();
                        mobileShareToggle.focus();
                    } else if (e.key === 'ArrowDown') {
                        e.preventDefault();
                        const nextIndex = (index + 1) % mobileShareLinks.length;
                        mobileShareLinks[nextIndex].focus();
                    } else if (e.key === 'ArrowUp') {
                        e.preventDefault();
                        const prevIndex = index === 0 ? mobileShareLinks.length - 1 : index - 1;
                        mobileShareLinks[prevIndex].focus();
                    }
                });
            });
        },
        
        /**
         * Toggle mobile share
         */
        toggleMobileShare: function() {
            const { mobileShareDropdown } = this.elements;
            const isHidden = this.isElementHidden(mobileShareDropdown);
            
            if (isHidden) {
                this.showMobileShare();
                this.hideMobileToc();
            } else {
                this.hideMobileShare();
            }
        },
        
        /**
         * Show mobile share
         */
        showMobileShare: function() {
            const { mobileShareToggle, mobileShareDropdown } = this.elements;
            const { classes } = this.config;
            
            mobileShareDropdown.style.display = 'block';
            mobileShareToggle.classList.add(classes.active);
        },
        
        /**
         * Hide mobile share
         */
        hideMobileShare: function() {
            const { mobileShareToggle, mobileShareDropdown } = this.elements;
            const { classes } = this.config;
            
            mobileShareDropdown.style.display = 'none';
            mobileShareToggle.classList.remove(classes.active);
        },
        
        /**
         * Focus first share item in dropdown
         */
        focusFirstShareItem: function() {
            const { mobileShareDropdown } = this.elements;
            const firstLink = mobileShareDropdown.querySelector('a, button');
            if (firstLink) {
                setTimeout(() => {
                    firstLink.focus();
                }, 100);
            }
        },
        
        /**
         * Bind mobile TOC events
         */
        bindMobileTocEvents: function() {
            const { mobileTocToggle, mobileTocDropdown } = this.elements;
            
            mobileTocToggle.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();
                this.toggleMobileToc();
            });
            
            // Handle TOC links
            const tocLinks = mobileTocDropdown.querySelectorAll('a');
            tocLinks.forEach(link => {
                link.addEventListener('click', () => {
                    setTimeout(() => {
                        this.hideMobileToc();
                    }, 100);
                });
            });
        },
        
        /**
         * Toggle mobile TOC
         */
        toggleMobileToc: function() {
            const { mobileTocDropdown } = this.elements;
            const isHidden = this.isElementHidden(mobileTocDropdown);
            
            if (isHidden) {
                this.showMobileToc();
                this.hideMobileShare();
            } else {
                this.hideMobileToc();
            }
        },
        
        /**
         * Show mobile TOC
         */
        showMobileToc: function() {
            const { mobileTocToggle, mobileTocDropdown } = this.elements;
            const { classes } = this.config;
            
            mobileTocDropdown.style.display = 'block';
            mobileTocToggle.classList.add(classes.active);
        },
        
        /**
         * Hide mobile TOC
         */
        hideMobileToc: function() {
            const { mobileTocToggle, mobileTocDropdown } = this.elements;
            const { classes } = this.config;
            
            mobileTocDropdown.style.display = 'none';
            mobileTocToggle.classList.remove(classes.active);
        },
        
        /**
         * Bind global events
         */
        bindGlobalEvents: function() {
            // Outside click detection
            document.addEventListener('click', (e) => {
                this.handleOutsideClick(e);
            });
            
            // Keyboard events
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') {
                    this.closeAllDropdowns();
                }
            });
        },
        
        /**
         * Handle outside clicks
         */
        handleOutsideClick: function(e) {
            const { shareBtn, shareDropdown, shareContainer, 
                    mobileShareToggle, mobileShareDropdown,
                    mobileTocToggle, mobileTocDropdown } = this.elements;
            
            // Desktop share dropdown
            if (shareBtn && shareDropdown && shareContainer &&
                !shareBtn.contains(e.target) &&
                !shareDropdown.contains(e.target) &&
                !shareContainer.contains(e.target)) {
                this.hideDesktopDropdown();
            }
            
            // Mobile share dropdown
            if (mobileShareDropdown && mobileShareToggle &&
                !mobileShareToggle.contains(e.target) &&
                !mobileShareDropdown.contains(e.target)) {
                this.hideMobileShare();
            }
            
            // Mobile TOC dropdown
            if (mobileTocDropdown && mobileTocToggle &&
                !mobileTocToggle.contains(e.target) &&
                !mobileTocDropdown.contains(e.target)) {
                this.hideMobileToc();
            }
        },
        
        /**
         * Close all dropdowns
         */
        closeAllDropdowns: function() {
            if (this.elements.shareDropdown) {
                this.hideDesktopDropdown();
            }
            if (this.elements.mobileShareDropdown) {
                this.hideMobileShare();
            }
            if (this.elements.mobileTocDropdown) {
                this.hideMobileToc();
            }
        },
        
        /**
         * Check if dropdown is hidden
         */
        isDropdownHidden: function(dropdown) {
            return dropdown.classList.contains(this.config.classes.hidden);
        },
        
        /**
         * Check if element is hidden
         */
        isElementHidden: function(element) {
            return element.style.display === 'none' || element.style.display === '';
        },
        
        /**
         * Copy to clipboard
         */
        copyToClipboard: function(url) {
            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(url).then(() => {
                    this.showCopyFeedback(DTS_PostShare_L10n.copySuccess);
                }).catch((err) => {
                    console.error('Failed to copy: ', err);
                    this.fallbackCopyToClipboard(url);
                });
            } else {
                this.fallbackCopyToClipboard(url);
            }
            
            // Close all dropdowns after copying
            setTimeout(() => {
                this.closeAllDropdowns();
            }, 100);
        },
        
        /**
         * Fallback copy method
         */
        fallbackCopyToClipboard: function(text) {
            const textArea = document.createElement('textarea');
            Object.assign(textArea.style, {
                position: 'fixed',
                top: '0',
                left: '0',
                opacity: '0',
                width: '1px',
                height: '1px'
            });
            
            textArea.value = text;
            document.body.appendChild(textArea);
            textArea.focus();
            textArea.select();
            
            try {
                document.execCommand('copy');
                this.showCopyFeedback(DTS_PostShare_L10n.copySuccess);
            } catch (err) {
                console.error('Fallback: Could not copy text: ', err);
                this.showCopyFeedback(DTS_PostShare_L10n.copyError);
            }
            
            document.body.removeChild(textArea);
        },
        
        /**
         * Show copy feedback
         */
        showCopyFeedback: function(message) {
            // Remove existing feedback
            const existingFeedback = document.getElementById('copy-feedback');
            if (existingFeedback) {
                existingFeedback.remove();
            }
            
            const feedback = document.createElement('div');
            feedback.id = 'copy-feedback';
            feedback.textContent = message;
            feedback.className = 'dts-copy-feedback';
            
            document.body.appendChild(feedback);
            
            // Animate in
            requestAnimationFrame(() => {
                feedback.classList.add('show');
            });
            
            // Animate out
            setTimeout(() => {
                feedback.classList.remove('show');
            }, this.config.animations.feedbackDuration);
            
            // Remove element
            setTimeout(() => {
                if (feedback && feedback.parentNode) {
                    feedback.remove();
                }
            }, this.config.animations.feedbackRemoveDelay);
        }
    };
    
    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => {
            DTS_PostShare.init();
        });
    } else {
        DTS_PostShare.init();
    }
    
    // Expose copyToClipboard globally for onclick handlers
    window.copyToClipboard = function(url) {
        DTS_PostShare.copyToClipboard(url);
    };
    
})();