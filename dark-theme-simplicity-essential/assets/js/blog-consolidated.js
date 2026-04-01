/**
 * Consolidated Blog JavaScript
 * Combines functionality from: dts-post-share.js, blog-fixes.js, blog-hero-modals.js
 * Dependencies: jQuery (for some features)
 */

(function() {
    'use strict';

    // ============================================
    // UTILITY FUNCTIONS
    // ============================================
    
    /**
     * Copy text to clipboard with feedback
     */
    function copyToClipboard(text) {
        const textarea = document.createElement('textarea');
        textarea.value = text;
        textarea.style.position = 'fixed';
        textarea.style.opacity = '0';
        document.body.appendChild(textarea);
        textarea.select();
        
        let success = false;
        try {
            success = document.execCommand('copy');
            
            // Show feedback
            const message = success ? 
                (window.DTS_PostShare_L10n?.copySuccess || 'Link copied to clipboard!') : 
                (window.DTS_PostShare_L10n?.copyError || 'Failed to copy link');
            
            showToast(message, success ? 'success' : 'error');
        } catch (err) {
            console.error('Copy failed:', err);
        }
        
        document.body.removeChild(textarea);
        return success;
    }

    /**
     * Show toast notification
     */
    function showToast(message, type = 'success') {
        const existingToast = document.querySelector('.copy-toast');
        if (existingToast) existingToast.remove();

        const toast = document.createElement('div');
        toast.className = `copy-toast ${type}`;
        toast.textContent = message;
        toast.style.cssText = `
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: ${type === 'success' ? '#10b981' : '#ef4444'};
            color: white;
            padding: 12px 24px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            z-index: 10000;
            animation: slideIn 0.3s ease-out;
        `;
        
        document.body.appendChild(toast);
        setTimeout(() => toast.remove(), 3000);
    }

    /**
     * Position dropdown relative to button
     */
    function positionDropdown(button, dropdown) {
        const rect = button.getBoundingClientRect();
        const dropdownRect = dropdown.getBoundingClientRect();
        const viewportWidth = window.innerWidth;
        
        // Reset inline styles
        dropdown.style.right = '';
        dropdown.style.left = '';
        
        // Position logic
        if (rect.right + dropdownRect.width > viewportWidth) {
            dropdown.style.right = '0';
        } else {
            dropdown.style.left = '0';
        }
    }

    // ============================================
    // SHARE & TOC FUNCTIONALITY
    // ============================================
    
    class ShareTocManager {
        constructor() {
            this.activeDropdown = null;
            this.init();
        }

        init() {
            this.initDesktopShare();
            this.initMobileDropdowns();
            this.initCopyLinks();
            this.setupOutsideClickHandler();
            this.initMobileModals();
        }

        initDesktopShare() {
            const shareBtn = document.getElementById('share-btn');
            const shareDropdown = document.getElementById('share-dropdown');
            
            if (shareBtn && shareDropdown) {
                shareBtn.addEventListener('click', (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                    this.toggleDropdown(shareBtn, shareDropdown);
                });
            }
        }

        initMobileDropdowns() {
            // Mobile share dropdown
            const mobileShareBtn = document.querySelector('.mobile-share-btn');
            const mobileShareDropdown = document.querySelector('.mobile-share-dropdown');
            
            if (mobileShareBtn && mobileShareDropdown) {
                mobileShareBtn.addEventListener('click', (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                    this.toggleDropdown(mobileShareBtn, mobileShareDropdown);
                });
            }

            // Mobile TOC dropdown
            const mobileTocBtn = document.querySelector('.mobile-toc-btn');
            const mobileTocDropdown = document.querySelector('.mobile-toc-dropdown');
            
            if (mobileTocBtn && mobileTocDropdown) {
                mobileTocBtn.addEventListener('click', (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                    this.toggleDropdown(mobileTocBtn, mobileTocDropdown);
                });
            }
        }

        toggleDropdown(button, dropdown) {
            const isOpen = !dropdown.classList.contains('hidden');
            
            // Close any open dropdown
            this.closeAllDropdowns();
            
            if (!isOpen) {
                dropdown.classList.remove('hidden');
                dropdown.style.display = 'block';
                button.setAttribute('aria-expanded', 'true');
                positionDropdown(button, dropdown);
                this.activeDropdown = { button, dropdown };
            }
        }

        closeAllDropdowns() {
            document.querySelectorAll('.desktop-share-dropdown, .mobile-share-dropdown, .mobile-toc-dropdown').forEach(dropdown => {
                dropdown.classList.add('hidden');
                dropdown.style.display = 'none';
            });
            
            document.querySelectorAll('#share-btn, .mobile-share-btn, .mobile-toc-btn').forEach(btn => {
                btn.setAttribute('aria-expanded', 'false');
            });
            
            this.activeDropdown = null;
        }

        initCopyLinks() {
            document.querySelectorAll('button[onclick*="copyToClipboard"]').forEach(button => {
                button.onclick = null;
                button.addEventListener('click', (e) => {
                    e.preventDefault();
                    const url = button.getAttribute('onclick').match(/'([^']+)'/)?.[1];
                    if (url) copyToClipboard(url);
                });
            });
        }

        setupOutsideClickHandler() {
            document.addEventListener('click', (e) => {
                if (this.activeDropdown && 
                    !this.activeDropdown.button.contains(e.target) && 
                    !this.activeDropdown.dropdown.contains(e.target)) {
                    this.closeAllDropdowns();
                }
            });
        }

        initMobileModals() {
            // Mobile hero share button
            const heroShareBtn = document.querySelector('.hero-share-btn');
            if (heroShareBtn) {
                heroShareBtn.addEventListener('click', (e) => {
                    e.preventDefault();
                    this.showModal('share');
                });
            }

            // Mobile hero TOC button
            const heroTocBtn = document.querySelector('.hero-toc-btn');
            if (heroTocBtn) {
                heroTocBtn.addEventListener('click', (e) => {
                    e.preventDefault();
                    this.showModal('toc');
                });
            }
        }

        showModal(type) {
            const existingModal = document.querySelector('.hero-modal-overlay');
            if (existingModal) existingModal.remove();

            const sourceElement = type === 'share' ? 
                document.querySelector('.desktop-share-dropdown, .mobile-share-dropdown') :
                document.querySelector('.toc-desktop .toc-list, .mobile-toc-dropdown .toc-list');

            if (!sourceElement) return;

            const modal = document.createElement('div');
            modal.className = 'hero-modal-overlay';
            modal.innerHTML = `
                <div class="hero-modal">
                    <div class="hero-modal-header">
                        <h3>${type === 'share' ? 'Share This Post' : 'Table of Contents'}</h3>
                        <button class="hero-modal-close" aria-label="Close">×</button>
                    </div>
                    <div class="hero-modal-content">
                        ${sourceElement.innerHTML}
                    </div>
                </div>
            `;

            document.body.appendChild(modal);
            document.body.style.overflow = 'hidden';

            // Close handlers
            modal.querySelector('.hero-modal-close').addEventListener('click', () => this.closeModal());
            modal.addEventListener('click', (e) => {
                if (e.target === modal) this.closeModal();
            });

            // Animate in
            requestAnimationFrame(() => modal.classList.add('show'));
        }

        closeModal() {
            const modal = document.querySelector('.hero-modal-overlay');
            if (modal) {
                modal.classList.remove('show');
                setTimeout(() => {
                    modal.remove();
                    document.body.style.overflow = '';
                }, 300);
            }
        }
    }

    // ============================================
    // BLOG ENHANCEMENTS (from blog-fixes.js)
    // ============================================
    
    function initBlogEnhancements() {
        // Note: Video handling removed - now handled by consolidated-theme.js

        // Table enhancements
        jQuery('.entry-content table').each(function() {
            const $table = jQuery(this);
            if (!$table.parent().hasClass('table-wrapper')) {
                const wrapper = jQuery('<div class="table-wrapper"><div class="scroll-indicator">← Scroll →</div></div>');
                $table.wrap(wrapper);
                
                // Check if table needs scroll
                const checkScroll = () => {
                    const wrapper = $table.parent();
                    if ($table.width() > wrapper.width()) {
                        wrapper.addClass('has-scroll');
                    } else {
                        wrapper.removeClass('has-scroll');
                    }
                };
                
                checkScroll();
                jQuery(window).on('resize', checkScroll);
            }
        });

        // Smooth scrolling for TOC links
        jQuery('.toc-link').on('click', function(e) {
            e.preventDefault();
            const target = jQuery(jQuery(this).attr('href'));
            if (target.length) {
                jQuery('html, body').animate({
                    scrollTop: target.offset().top - 100
                }, 500);
            }
        });

        // Add IDs to headings without them
        jQuery('.entry-content h2, .entry-content h3').each(function() {
            if (!this.id) {
                const text = jQuery(this).text();
                const id = text.toLowerCase()
                    .replace(/[^\w\s-]/g, '')
                    .replace(/\s+/g, '-')
                    .trim();
                this.id = id;
            }
        });
    }

    // ============================================
    // INITIALIZATION
    // ============================================
    
    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

    function init() {
        // Initialize share/TOC functionality
        new ShareTocManager();
        
        // Initialize blog enhancements (jQuery-dependent)
        if (typeof jQuery !== 'undefined') {
            jQuery(document).ready(function() {
                initBlogEnhancements();
            });
        }
    }

    // Make copyToClipboard available globally for onclick attributes
    window.copyToClipboard = copyToClipboard;

})();

// Add CSS for animations
const style = document.createElement('style');
style.textContent = `
    @keyframes slideIn {
        from {
            transform: translateY(20px);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    .hero-modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.8);
        z-index: 9999;
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .hero-modal-overlay.show {
        opacity: 1;
    }

    .hero-modal {
        background: #1a1a1c;
        border-radius: 12px;
        max-width: 90%;
        max-height: 80vh;
        overflow: hidden;
        transform: scale(0.9);
        transition: transform 0.3s ease;
    }

    .hero-modal-overlay.show .hero-modal {
        transform: scale(1);
    }

    .hero-modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .hero-modal-header h3 {
        margin: 0;
        color: white;
        font-size: 1.25rem;
    }

    .hero-modal-close {
        background: none;
        border: none;
        color: white;
        font-size: 2rem;
        cursor: pointer;
        padding: 0;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .hero-modal-content {
        padding: 1rem;
        overflow-y: auto;
        max-height: calc(80vh - 60px);
    }

    /* Video container styles removed - handled by consolidated-theme.js */

    .table-wrapper {
        position: relative;
        overflow-x: auto;
        margin: 1rem 0;
    }

    .table-wrapper.has-scroll .scroll-indicator {
        display: block;
    }

    .scroll-indicator {
        display: none;
        position: absolute;
        top: 50%;
        right: 10px;
        transform: translateY(-50%);
        background: rgba(0, 0, 0, 0.7);
        color: white;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 12px;
        pointer-events: none;
    }
`;
document.head.appendChild(style);

// ============================================
// SCROLL-TRIGGERED CTA (JoshWComeau pattern)
// ============================================

function initScrollCTA() {
    var cta = document.getElementById('scroll-cta');
    var dismiss = document.getElementById('scroll-cta-dismiss');
    if (!cta || !dismiss) return;

    var STORAGE_KEY = 'dts_scroll_cta_dismissed';
    var dismissed = false;

    try {
        dismissed = localStorage.getItem(STORAGE_KEY) === '1';
    } catch (e) {}

    if (dismissed) {
        cta.remove();
        return;
    }

    var shown = false;

    function checkScroll() {
        if (shown || dismissed) return;
        var scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        var docHeight = document.documentElement.scrollHeight - window.innerHeight;
        if (docHeight > 0 && (scrollTop / docHeight) >= 0.6) {
            cta.classList.add('is-visible');
            cta.setAttribute('aria-hidden', 'false');
            shown = true;
        }
    }

    window.addEventListener('scroll', checkScroll, { passive: true });

    dismiss.addEventListener('click', function() {
        cta.classList.remove('is-visible');
        cta.setAttribute('aria-hidden', 'true');
        dismissed = true;
        try {
            localStorage.setItem(STORAGE_KEY, '1');
        } catch (e) {}
        setTimeout(function() { cta.remove(); }, 400);
    });
}

// Run immediately if DOM is ready, otherwise wait
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initScrollCTA);
} else {
    initScrollCTA();
}