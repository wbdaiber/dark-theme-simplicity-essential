/**
 * Blog Hero Modals - Share and Table of Contents
 * Handles modal functionality for mobile hero buttons
 */

jQuery(document).ready(function($) {
    // Create modal HTML structure
    function createModalStructure() {
        const modalHTML = `
            <!-- Modal Overlay -->
            <div id="hero-modal-overlay" class="fixed inset-0 bg-black/80 backdrop-blur-sm z-[9999] hidden transition-opacity duration-300"></div>
            
            <!-- Share Modal -->
            <div id="hero-share-modal" class="fixed inset-x-4 bottom-4 max-w-lg mx-auto bg-dark-200 rounded-2xl shadow-2xl z-[10000] hidden transform translate-y-full transition-transform duration-300" role="dialog" aria-modal="true" aria-labelledby="hero-share-modal-title">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 id="hero-share-modal-title" class="text-xl font-bold text-white">Share this article</h3>
                        <button class="hero-modal-close text-gray-400 hover:text-white transition-colors" aria-label="Close share dialog">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <line x1="18" y1="6" x2="6" y2="18"></line>
                                <line x1="6" y1="6" x2="18" y2="18"></line>
                            </svg>
                        </button>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <!-- Share options will be injected here -->
                    </div>
                </div>
            </div>
            
            <!-- TOC Modal -->
            <div id="hero-toc-modal" class="fixed inset-x-4 bottom-4 max-w-lg mx-auto bg-dark-200 rounded-2xl shadow-2xl z-[10000] hidden transform translate-y-full transition-transform duration-300 max-h-[70vh]" role="dialog" aria-modal="true" aria-labelledby="hero-toc-modal-title">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 id="hero-toc-modal-title" class="text-xl font-bold text-white">Table of Contents</h3>
                        <button class="hero-modal-close text-gray-400 hover:text-white transition-colors" aria-label="Close table of contents dialog">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <line x1="18" y1="6" x2="6" y2="18"></line>
                                <line x1="6" y1="6" x2="18" y2="18"></line>
                            </svg>
                        </button>
                    </div>
                    <div class="toc-content overflow-y-auto max-h-[50vh] -mx-2 px-2">
                        <!-- TOC will be injected here -->
                    </div>
                </div>
            </div>
        `;
        
        $('body').append(modalHTML);
    }
    
    // Initialize modals
    createModalStructure();
    
    // Copy share options from existing mobile dropdown if available
    const existingShareOptions = $('.mobile-share-dropdown .grid').html();
    if (existingShareOptions) {
        $('#hero-share-modal .grid').html(existingShareOptions);
    }
    
    // Copy TOC from existing mobile dropdown if available
    const existingTOC = $('.mobile-toc-dropdown .mobile-toc-list').clone();
    if (existingTOC.length) {
        $('#hero-toc-modal .toc-content').html(existingTOC);
    }
    
    // Modal control functions
    function showModal(modalId) {
        $('#hero-modal-overlay').removeClass('hidden').css('opacity', '0');
        $(modalId).removeClass('hidden').css('transform', 'translateY(100%)');
        
        // Trigger animations
        setTimeout(() => {
            $('#hero-modal-overlay').css('opacity', '1');
            $(modalId).css('transform', 'translateY(0)');
        }, 10);
        
        // Prevent body scroll
        $('body').css('overflow', 'hidden');
        
        // Focus management - focus on close button
        setTimeout(() => {
            $(modalId).find('.hero-modal-close').focus();
        }, 100);
    }
    
    function hideModals() {
        $('#hero-modal-overlay').css('opacity', '0');
        $('.fixed[id^="hero-"][id$="-modal"]').css('transform', 'translateY(100%)');
        
        setTimeout(() => {
            $('#hero-modal-overlay').addClass('hidden');
            $('.fixed[id^="hero-"][id$="-modal"]').addClass('hidden');
        }, 300);
        
        // Restore body scroll
        $('body').css('overflow', '');
        
        // Return focus to the trigger button
        if (window.lastModalTrigger) {
            window.lastModalTrigger.focus();
            window.lastModalTrigger = null;
        }
    }
    
    // Hero button click handlers
    $('.mobile-hero-share-btn').on('click', function(e) {
        e.preventDefault();
        window.lastModalTrigger = this;
        showModal('#hero-share-modal');
    });
    
    $('.mobile-hero-toc-btn').on('click', function(e) {
        e.preventDefault();
        window.lastModalTrigger = this;
        showModal('#hero-toc-modal');
    });
    
    // Close modal handlers
    $('#hero-modal-overlay, .hero-modal-close').on('click', function() {
        hideModals();
    });
    
    // Close on escape key
    $(document).on('keydown', function(e) {
        if (e.key === 'Escape') {
            hideModals();
        }
    });
    
    // Handle TOC link clicks
    $('#hero-toc-modal').on('click', 'a', function(e) {
        e.preventDefault();
        const target = $(this).attr('href');
        
        // Close modal
        hideModals();
        
        // Smooth scroll to target after modal closes
        setTimeout(() => {
            const $target = $(target);
            if ($target.length) {
                $('html, body').animate({
                    scrollTop: $target.offset().top - 100
                }, 600);
                
                // Set focus for accessibility
                $target.attr('tabindex', '-1').focus();
                setTimeout(() => {
                    $target.removeAttr('tabindex');
                }, 1000);
            }
        }, 350);
    });
    
    // Handle share button clicks in modal
    $('#hero-share-modal').on('click', 'button', function(e) {
        if ($(this).attr('onclick')) {
            // Copy link button - let existing onclick handler run
            setTimeout(() => {
                hideModals();
            }, 100);
        }
    });
    
    $('#hero-share-modal').on('click', 'a', function() {
        // Social share links - close modal after short delay
        setTimeout(() => {
            hideModals();
        }, 100);
    });
});