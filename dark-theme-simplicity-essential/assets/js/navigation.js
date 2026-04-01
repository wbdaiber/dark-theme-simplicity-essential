/**
 * Navigation Module - Dark Theme Simplicity
 * Handles all header functionality: mobile menu, scroll effects, keyboard navigation
 * Dependencies: core.js
 * Version: 2.0.0
 */
(function() {
	'use strict';
   
	// DOM elements
	const header = document.querySelector(DTSConfig.selectors.header);
	const mobileMenuToggle = document.querySelector(DTSConfig.selectors.mobileToggle);
	const mobileMenu = document.querySelector(DTSConfig.selectors.mobileMenu);
	const mobileMenuOverlay = document.querySelector(DTSConfig.selectors.mobileOverlay);
	const body = document.body;
   
	// State
	let isMenuOpen = false;
	let lastScrollTop = 0;
	let scrollTimeout;
   
	/**
	 * Initialize all header functionality
	 */
	function init() {
		DTSConfig.features.debug && console.log('🚀 Header Navigation: Initializing...');
		
		if (!header) {
			DTSConfig.features.debug && console.warn('⚠️ Header element not found');
			return;
		}
	   
		setupMobileMenu();
		setupScrollEffects();
		setupKeyboardNavigation();
		setupAccessibility();
		
		DTSConfig.features.debug && console.log('✅ Header Navigation: All functionality loaded');
	}
   
	/**
	 * Setup mobile menu functionality
	 */
	function setupMobileMenu() {
		if (!mobileMenuToggle || !mobileMenu || !mobileMenuOverlay) {
			DTSConfig.features.debug && console.warn('⚠️ Mobile menu elements not found');
			return;
		}
	   
		DTSConfig.features.debug && console.log('📱 Setting up mobile menu...');
	   
		// Toggle button click
		mobileMenuToggle.addEventListener(DTSConfig.events.click, handleToggleClick);
	   
		// Overlay click to close
		mobileMenuOverlay.addEventListener(DTSConfig.events.click, closeMobileMenu);
	   
		// Close on escape key
		document.addEventListener(DTSConfig.events.keydown, handleEscapeKey);
	   
		// Close on window resize (if switching to desktop)
		window.addEventListener(DTSConfig.events.resize, handleResize);
		
		// Close menu when clicking on menu links
		const menuLinks = mobileMenu.querySelectorAll('a');
		menuLinks.forEach(link => {
			link.addEventListener(DTSConfig.events.click, closeMobileMenu);
		});
		
		// Prevent menu close when clicking inside menu content
		mobileMenu.addEventListener(DTSConfig.events.click, function(e) {
			e.stopPropagation();
		});
		
		DTSConfig.features.debug && console.log('✅ Mobile menu setup complete');
	}
	
	/**
	 * Handle toggle button click
	 */
	function handleToggleClick(e) {
		e.preventDefault();
		e.stopPropagation();
		
		DTSConfig.features.debug && console.log('🔄 Toggle button clicked, current state:', isMenuOpen ? 'open' : 'closed');
		
		if (isMenuOpen) {
			closeMobileMenu();
		} else {
			openMobileMenu();
		}
	}
   
	/**
	 * Setup scroll effects for header
	 */
	function setupScrollEffects() {
		DTSConfig.features.debug && console.log('📜 Setting up scroll effects...');
		
		let ticking = false;
	   
		window.addEventListener(DTSConfig.events.scroll, function() {
			if (!ticking) {
				requestAnimationFrame(function() {
					handleScroll();
					ticking = false;
				});
				ticking = true;
			}
		}, { passive: true });
		
		// Initial check
		handleScroll();
		
		DTSConfig.features.debug && console.log('✅ Scroll effects setup complete');
	}
   
	/**
	 * Setup keyboard navigation
	 */
	function setupKeyboardNavigation() {
		DTSConfig.features.debug && console.log('⌨️ Setting up keyboard navigation...');
		
		// Trap focus within mobile menu when open
		if (mobileMenu) {
			mobileMenu.addEventListener('keydown', trapFocus);
		}
		
		// Handle tab navigation in desktop menu
		const desktopMenuLinks = header.querySelectorAll('.desktop-nav a');
		desktopMenuLinks.forEach(link => {
			link.addEventListener('keydown', handleDesktopMenuKeydown);
		});
		
		DTSConfig.features.debug && console.log('✅ Keyboard navigation setup complete');
	}
	
	/**
	 * Setup accessibility features
	 */
	function setupAccessibility() {
		DTSConfig.features.debug && console.log('♿ Setting up accessibility features...');
		
		// Ensure proper ARIA attributes are set initially
		if (mobileMenuToggle) {
			mobileMenuToggle.setAttribute(DTSConfig.aria.expanded, 'false');
		}
		
		if (mobileMenu) {
			mobileMenu.setAttribute(DTSConfig.aria.hidden, 'true');
		}
		
		if (mobileMenuOverlay) {
			mobileMenuOverlay.setAttribute(DTSConfig.aria.hidden, 'true');
		}
		
		DTSConfig.features.debug && console.log('✅ Accessibility setup complete');
	}
   
	/**
	 * Open mobile menu
	 */
	function openMobileMenu() {
		DTSConfig.features.debug && console.log('📖 Opening mobile menu...');
		
		isMenuOpen = true;
	   
		// Update ARIA attributes
		mobileMenuToggle.setAttribute(DTSConfig.aria.expanded, 'true');
		mobileMenu.setAttribute(DTSConfig.aria.hidden, 'false');
		mobileMenuOverlay.setAttribute(DTSConfig.aria.hidden, 'false');
	   
		// Remove hidden classes
		mobileMenu.classList.remove(DTSConfig.classes.hidden);
		mobileMenuOverlay.classList.remove(DTSConfig.classes.hidden);
		
		// Add body class for styling
		body.classList.add(DTSConfig.classes.mobileMenuOpen);
	   
		// Focus first menu item for accessibility
		const firstMenuItem = mobileMenu.querySelector('a');
		if (firstMenuItem) {
			setTimeout(() => firstMenuItem.focus(), DTSConfig.animations.fast);
		}
	   
		// Prevent body scroll on mobile
		if (window.innerWidth <= DTSConfig.breakpoints.mobile) {
			body.style.overflow = 'hidden';
		}
		
		DTSConfig.features.debug && console.log('✅ Mobile menu opened');
	}
   
	/**
	 * Close mobile menu
	 */
	function closeMobileMenu() {
		if (!isMenuOpen) return;
		
		DTSConfig.features.debug && console.log('📕 Closing mobile menu...');
	   
		isMenuOpen = false;
	   
		// Update ARIA attributes
		mobileMenuToggle.setAttribute(DTSConfig.aria.expanded, 'false');
		mobileMenu.setAttribute(DTSConfig.aria.hidden, 'true');
		mobileMenuOverlay.setAttribute(DTSConfig.aria.hidden, 'true');
	   
		// Add hidden classes
		mobileMenu.classList.add(DTSConfig.classes.hidden);
		mobileMenuOverlay.classList.add(DTSConfig.classes.hidden);
		
		// Remove body class
		body.classList.remove(DTSConfig.classes.mobileMenuOpen);
	   
		// Return focus to toggle button
		mobileMenuToggle.focus();
	   
		// Restore body scroll
		body.style.overflow = '';
		
		DTSConfig.features.debug && console.log('✅ Mobile menu closed');
	}
   
	/**
	 * Handle scroll events
	 */
	function handleScroll() {
		const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
	   
		// Add scrolled class when scrolling down
		if (scrollTop > 50) {
			header.classList.add(DTSConfig.classes.headerScrolled);
		} else {
			header.classList.remove(DTSConfig.classes.headerScrolled);
		}
		
		// Optional: Hide/show header on scroll (uncomment if desired)
		/*
		if (scrollTop > lastScrollTop && scrollTop > 100) {
			// Scrolling down
			header.style.transform = 'translateY(-100%)';
		} else {
			// Scrolling up
			header.style.transform = 'translateY(0)';
		}
		*/
	   
		lastScrollTop = scrollTop;
	}
   
	/**
	 * Handle escape key press
	 */
	function handleEscapeKey(event) {
		if (event.key === 'Escape' && isMenuOpen) {
			closeMobileMenu();
		}
	}
   
	/**
	 * Handle window resize
	 */
	function handleResize() {
		// Close mobile menu if switching to desktop
		if (window.innerWidth >= DTSConfig.breakpoints.mobile && isMenuOpen) {
			closeMobileMenu();
		}
	}
	
	/**
	 * Handle desktop menu keyboard navigation
	 */
	function handleDesktopMenuKeydown(event) {
		// Add any desktop-specific keyboard handling here
		if (event.key === 'Enter' || event.key === ' ') {
			event.target.click();
		}
	}
   
	/**
	 * Trap focus within mobile menu
	 */
	function trapFocus(event) {
		if (!isMenuOpen) return;
	   
		const focusableElements = mobileMenu.querySelectorAll('a, button, [tabindex]:not([tabindex="-1"])');
		const firstElement = focusableElements[0];
		const lastElement = focusableElements[focusableElements.length - 1];
	   
		if (event.keyCode === DTSConfig.keyCodes.tab) {
			if (event.shiftKey) {
				// Shift + Tab: focus previous element
				if (document.activeElement === firstElement) {
					event.preventDefault();
					lastElement.focus();
				}
			} else {
				// Tab: focus next element
				if (document.activeElement === lastElement) {
					event.preventDefault();
					firstElement.focus();
				}
			}
		}
	}
	
	/**
	 * Public API for external scripts
	 */
	window.HeaderNavigation = {
		openMenu: openMobileMenu,
		closeMenu: closeMobileMenu,
		toggleMenu: function() {
			if (isMenuOpen) {
				closeMobileMenu();
			} else {
				openMobileMenu();
			}
		},
		isMenuOpen: function() {
			return isMenuOpen;
		}
	};
   
	// Initialize when DOM is ready
	if (document.readyState === 'loading') {
		document.addEventListener(DTSConfig.events.domReady, init);
	} else {
		init();
	}
	
	// Global error handling
	window.addEventListener('error', function(e) {
		if (e.filename && e.filename.includes('header.js')) {
			console.error('🚨 Header Navigation Error:', e.message, e.lineno);
		}
	});
   
 })();