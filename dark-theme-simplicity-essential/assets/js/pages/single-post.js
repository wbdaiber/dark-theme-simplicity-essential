/**
 * Single Post Module - Dark Theme Simplicity
 * Handles share buttons, TOC dropdowns, and blog-specific functionality
 * Dependencies: core.js
 * Version: 2.0.0
 */

window.DTSSinglePost = (function() {
    'use strict';
    
    // ============================================
    // SHARE & TOC MANAGER CLASS
    // ============================================
    
    class ShareTocManager {
        constructor() {
            // Use the new unique IDs for sidebar share button
            this.desktopShareBtn = document.querySelector('#sidebar-share-btn');
            this.desktopShareDropdown = document.querySelector('#sidebar-share-dropdown');
            
            this.init();
        }
        
        init() {
            this.setupDesktopShare();
            this.setupShareButtons();
            this.setupOutsideClickHandler();
            this.setupKeyboardHandlers();
        }
        
        
        setupDesktopShare() {
            if (!this.desktopShareBtn || !this.desktopShareDropdown) {
                return;
            }
            
            this.desktopShareBtn.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();
                this.toggleDesktopShare();
            });
        }
        
        
        toggleDesktopShare() {
            if (!this.desktopShareDropdown) {
                return;
            }
            
            if (this.desktopShareDropdown.classList.contains('hidden')) {
                this.openDesktopShare();
            } else {
                this.closeDesktopShare();
            }
        }
        
        
        openDesktopShare() {
            this.desktopShareDropdown.classList.remove('hidden');
            this.desktopShareDropdown.style.display = 'block';
            this.desktopShareBtn.classList.add('active');
            this.desktopShareBtn.setAttribute('aria-expanded', 'true');
            
            // Position dropdown
            if (window.DTSCore) {
                window.DTSCore.positionDropdown(this.desktopShareDropdown, this.desktopShareBtn);
            }
        }
        
        closeDesktopShare() {
            if (this.desktopShareDropdown) {
                this.desktopShareDropdown.classList.add('hidden');
                this.desktopShareDropdown.style.display = 'none';
            }
            if (this.desktopShareBtn) {
                this.desktopShareBtn.classList.remove('active');
                this.desktopShareBtn.setAttribute('aria-expanded', 'false');
            }
        }
        
        closeAll() {
            this.closeDesktopShare();
        }
        
        setupShareButtons() {
            // Handle all share buttons
            const shareButtons = document.querySelectorAll('[data-share-platform]');
            
            shareButtons.forEach(button => {
                button.addEventListener('click', (e) => {
                    e.preventDefault();
                    this.handleShare(button);
                });
            });
            
            // Handle copy link buttons
            const copyButtons = document.querySelectorAll('[data-copy-url], .copy-link');
            copyButtons.forEach(button => {
                button.addEventListener('click', (e) => {
                    e.preventDefault();
                    this.copyCurrentUrl();
                });
            });
        }
        
        async handleShare(button) {
            const platform = button.dataset.sharePlatform;
            const url = encodeURIComponent(window.location.href);
            const title = encodeURIComponent(document.title);
            
            let shareUrl = '';
            
            switch (platform) {
                case 'twitter':
                    shareUrl = `https://twitter.com/intent/tweet?url=${url}&text=${title}`;
                    break;
                case 'facebook':
                    shareUrl = `https://www.facebook.com/sharer/sharer.php?u=${url}`;
                    break;
                case 'linkedin':
                    shareUrl = `https://www.linkedin.com/sharing/share-offsite/?url=${url}`;
                    break;
                case 'reddit':
                    shareUrl = `https://reddit.com/submit?url=${url}&title=${title}`;
                    break;
                case 'email':
                    shareUrl = `mailto:?subject=${title}&body=${url}`;
                    break;
                default:
                    return;
            }
            
            // Open share window
            if (platform === 'email') {
                window.location.href = shareUrl;
            } else {
                window.open(shareUrl, 'share', 'width=600,height=400,scrollbars=yes,resizable=yes');
            }
            
            // Close dropdowns after sharing
            setTimeout(() => {
                this.closeAll();
            }, 300);
        }
        
        async copyCurrentUrl() {
            if (window.DTSCore) {
                const success = await window.DTSCore.copyToClipboard(window.location.href);
                if (success) {
                    window.DTSCore.showCopyFeedback('Link copied to clipboard!');
                } else {
                    window.DTSCore.showNotification('Failed to copy link', 'error');
                }
            } else {
                // Fallback without core utilities
                try {
                    await navigator.clipboard.writeText(window.location.href);
                    console.log('Link copied to clipboard');
                } catch (err) {
                    console.error('Failed to copy link:', err);
                }
            }
            
            // Close dropdowns after copying
            setTimeout(() => {
                this.closeAll();
            }, 500);
        }
        
        setupOutsideClickHandler() {
            document.addEventListener('click', (e) => {
                // Check if click is outside desktop share dropdown
                const isInsideShare = e.target.closest('#share-dropdown, #share-btn');
                
                if (!isInsideShare) {
                    this.closeAll();
                }
            });
        }
        
        setupKeyboardHandlers() {
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') {
                    this.closeAll();
                }
            });
        }
        
        setupAutoClose() {
            // Auto-close desktop TOC when clicking on TOC links
            const tocLinks = document.querySelectorAll('.toc-link');
            tocLinks.forEach(link => {
                link.addEventListener('click', () => {
                    setTimeout(() => {
                        this.closeAll();
                    }, 300);
                });
            });
        }
    }
    
    // Desktop-only functionality - no mobile modals needed
    
    // ============================================
    // INITIALIZATION
    // ============================================
    
    function init() {
        try {
            // Initialize desktop-only functionality
            const shareManager = new ShareTocManager();
            return shareManager;
        } catch (error) {
            if (window.DTSCore) {
                window.DTSCore.handleError('Single Post Module', error);
            } else {
                console.error('Single Post Module Error:', error);
            }
        }
    }
    
    // ============================================
    // PUBLIC API
    // ============================================
    
    return {
        init,
        ShareTocManager
    };
})();

// Auto-initialize when DOM is ready
if (window.DTSCore) {
    window.DTSCore.ready(window.DTSSinglePost.init);
} else {
    document.addEventListener('DOMContentLoaded', window.DTSSinglePost.init);
}