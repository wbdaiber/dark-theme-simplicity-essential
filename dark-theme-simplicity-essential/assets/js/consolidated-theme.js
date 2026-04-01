/**
* Dark Theme Simplicity - Theme JavaScript (Mobile Menu Removed)
* Includes: Responsive Videos + Theme Functions + Other Features
* Version: 2.0.0 - Mobile menu functionality moved to header.js
*/
(function($) {
    'use strict';
 
    // === RESPONSIVE VIDEOS FUNCTIONALITY ===
    function initializeResponsiveVideos() {
        console.log('🎥 Initializing responsive videos...');
       
        // Fix WordPress default video embeds
        $('.wp-block-embed iframe, .wp-block-video video').each(function() {
            var $this = $(this);
           
            if (!$this.parent().hasClass('responsive-video-wrapper') &&
                !$this.parent().hasClass('wp-block-embed__wrapper')) {
                $this.wrap('<div class="responsive-video-wrapper"></div>');
            }
        });
       
        // Find all video elements and make them responsive
        $('iframe[src*="youtube.com"], iframe[src*="youtu.be"], iframe[src*="vimeo.com"], iframe[src*="dailymotion.com"], iframe[src*="videopress.com"]').each(function() {
            var $iframe = $(this);
           
            if (!$iframe.parent().hasClass('responsive-video-wrapper') &&
                !$iframe.parent().hasClass('wp-block-embed__wrapper')) {
                $iframe.wrap('<div class="responsive-video-wrapper"></div>');
            }
        });
       
        // Add appropriate margins to video wrappers
        setTimeout(function() {
            $('.responsive-video-wrapper').each(function() {
                var $wrapper = $(this);
               
                if (!$wrapper.hasClass('margin-applied')) {
                    $wrapper.addClass('margin-applied');
                   
                    var screenWidth = $(window).width();
                   
                    if (screenWidth >= 1024) {
                        $wrapper.css({
                            'margin-top': '1.5rem',
                            'margin-bottom': '1.5rem'
                        });
                    } else if (screenWidth >= 768) {
                        $wrapper.css({
                            'margin-top': '1.5rem',
                            'margin-bottom': '1.5rem'
                        });
                    } else {
                        $wrapper.css({
                            'margin-top': '1rem',
                            'margin-bottom': '1rem',
                            'max-width': '100%',
                            'width': '100%'
                        });
                    }
                }
            });
        }, 500);
       
        console.log('✅ Responsive videos initialized');
    }
 
    // === WINDOW RESIZE HANDLER ===
    function handleWindowResize() {
        var resizeTimer;
        $(window).on('resize', function() {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(function() {
                // Recalculate iframe heights
                $('.responsive-video-wrapper iframe').each(function() {
                    var $iframe = $(this);
                    var width = $iframe.width();
                    var aspectRatio = $iframe.attr('height') / $iframe.attr('width');
                   
                    if (width && aspectRatio) {
                        $iframe.css('height', width * aspectRatio + 'px');
                    }
                });
               
                // Update video wrapper margins based on new screen size
                $('.responsive-video-wrapper').each(function() {
                    var $wrapper = $(this);
                    var screenWidth = $(window).width();
                   
                    if (screenWidth >= 1024) {
                        $wrapper.css({
                            'margin-top': '1.5rem',
                            'margin-bottom': '1.5rem',
                            'max-width': '',
                            'width': ''
                        });
                    } else if (screenWidth >= 768) {
                        $wrapper.css({
                            'margin-top': '1.5rem',
                            'margin-bottom': '1.5rem',
                            'max-width': '',
                            'width': ''
                        });
                    } else {
                        $wrapper.css({
                            'margin-top': '1rem',
                            'margin-bottom': '1rem',
                            'max-width': '100%',
                            'width': '100%'
                        });
                    }
                });
            }, 250);
        }).trigger('resize');
    }
 
    // === SMOOTH SCROLLING FOR ANCHOR LINKS ===
    function initializeSmoothScrolling() {
        console.log('🔗 Initializing smooth scrolling...');
        
        $('a[href^="#"]').on('click', function(e) {
            var target = $(this.hash);
            if (target.length) {
                e.preventDefault();
                $('html, body').animate({
                    scrollTop: target.offset().top - 100
                }, 600);
               
                // Set focus for accessibility
                target.attr('tabindex', '-1');
                target.focus();
               
                setTimeout(function() {
                    target.removeAttr('tabindex');
                }, 1000);
            }
        });
       
        // Handle hash on page load
        if (window.location.hash) {
            setTimeout(function() {
                var target = $(window.location.hash);
                if (target.length) {
                    $('html, body').animate({
                        scrollTop: target.offset().top - 100
                    }, 300);
                }
            }, 500);
        }
        
        console.log('✅ Smooth scrolling initialized');
    }
 
    // === AJAX CONTENT HANDLER ===
    function handleAjaxContent() {
        console.log('🔄 Setting up AJAX content handler...');
        
        $(document).ajaxComplete(function() {
            setTimeout(function() {
                // Re-initialize responsive videos for new content
                $('iframe[src*="youtube.com"], iframe[src*="youtu.be"], iframe[src*="vimeo.com"], iframe[src*="dailymotion.com"], iframe[src*="videopress.com"]').each(function() {
                    var $iframe = $(this);
                   
                    if (!$iframe.parent().hasClass('responsive-video-wrapper') &&
                        !$iframe.parent().hasClass('wp-block-embed__wrapper')) {
                        $iframe.wrap('<div class="responsive-video-wrapper"></div>');
                       
                        var $wrapper = $iframe.parent('.responsive-video-wrapper');
                        var screenWidth = $(window).width();
                       
                        if (screenWidth >= 1024) {
                            $wrapper.css({
                                'margin-top': '1.5rem',
                                'margin-bottom': '1.5rem'
                            });
                        } else if (screenWidth >= 768) {
                            $wrapper.css({
                                'margin-top': '1.5rem',
                                'margin-bottom': '1.5rem'
                            });
                        } else {
                            $wrapper.css({
                                'margin-top': '1rem',
                                'margin-bottom': '1rem',
                                'max-width': '100%',
                                'width': '100%'
                            });
                        }
                    }
                });
            }, 500);
        });
        
        console.log('✅ AJAX content handler setup complete');
    }
 
    // Note: Heading ID generation moved to blog-fixes.js for better implementation
 
    // === ENHANCED FORM HANDLING ===
    function initializeFormEnhancements() {
        console.log('📝 Initializing form enhancements...');
        
        // Add focus classes to form inputs
        $('input, textarea, select').on('focus', function() {
            $(this).addClass('focused');
        }).on('blur', function() {
            $(this).removeClass('focused');
        });
        
        // Handle contact forms
        $('.wpcf7-form').on('wpcf7:mailsent', function() {
            console.log('✅ Contact form submitted successfully');
        });
        
        console.log('✅ Form enhancements initialized');
    }
 
    // === LAZY LOADING ENHANCEMENT ===
    function initializeLazyLoading() {
        console.log('🖼️ Initializing lazy loading enhancements...');
        
        // Add loading="lazy" to images that don't have it
        $('img').each(function() {
            if (!$(this).attr('loading')) {
                $(this).attr('loading', 'lazy');
            }
        });
        
        console.log('✅ Lazy loading enhancements initialized');
    }
 
    // === MAIN INITIALIZATION ===
    $(document).ready(function() {
        console.log('🚀 Dark Theme Simplicity - Theme Script Loading...');
        console.log('📱 Note: Mobile menu is handled by header.js');
       
        // Initialize all functionality (mobile menu removed)
        initializeResponsiveVideos();
        handleWindowResize();
        initializeSmoothScrolling();
        handleAjaxContent();
        // Heading IDs handled by blog-fixes.js
        initializeFormEnhancements();
        initializeLazyLoading();
       
        // Add prose class to entry-content if not present
        if ($('.entry-content').length && !$('.entry-content').hasClass('prose')) {
            $('.entry-content').addClass('prose');
        }
       
        console.log('🎉 Dark Theme Simplicity - Theme functionality loaded successfully!');
    });
 
    // === GLOBAL ERROR HANDLER ===
    window.addEventListener('error', function(e) {
        if (e.filename && e.filename.includes('consolidated-theme.js')) {
            console.error('🚨 Theme JavaScript Error:', e.message, e.filename, e.lineno);
        }
    });
 
 })(jQuery);