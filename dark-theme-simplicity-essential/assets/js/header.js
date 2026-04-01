/**
* Header Navigation JavaScript
* Handles all header functionality: mobile menu, scroll effects, keyboard navigation
* Version: 2.0.0 - Complete header solution
*/
(function() {
	'use strict';
   
	// DOM elements
	const header = document.querySelector('.site-header');
	const mobileMenuToggle = document.getElementById('mobile-menu-toggle');
	const mobileMenu = document.getElementById('mobile-menu');
	const mobileMenuOverlay = document.getElementById('mobile-menu-overlay');
	const body = document.body;
   
	// State
	let isMenuOpen = false;
	let lastScrollTop = 0;
	let scrollTimeout;
   
	/**
	 * Initialize all header functionality
	 */
	function init() {
		console.log('🚀 Header Navigation: Initializing...');
		
		if (!header) {
			console.warn('⚠️ Header element not found');
			return;
		}
	   
		setupMobileMenu();
		setupScrollEffects();
		setupKeyboardNavigation();
		setupAccessibility();
		
		console.log('✅ Header Navigation: All functionality loaded');
	}
   
	/**
	 * Setup mobile menu functionality
	 */
	function setupMobileMenu() {
		if (!mobileMenuToggle || !mobileMenu || !mobileMenuOverlay) {
			console.warn('⚠️ Mobile menu elements not found');
			return;
		}
	   
		console.log('📱 Setting up mobile menu...');
	   
		// Toggle button click
		mobileMenuToggle.addEventListener('click', handleToggleClick);
	   
		// Overlay click to close
		mobileMenuOverlay.addEventListener('click', closeMobileMenu);
	   
		// Close on escape key
		document.addEventListener('keydown', handleEscapeKey);
	   
		// Close on window resize (if switching to desktop)
		window.addEventListener('resize', handleResize);
		
		// Close menu when clicking on menu links
		const menuLinks = mobileMenu.querySelectorAll('a');
		menuLinks.forEach(link => {
			link.addEventListener('click', closeMobileMenu);
		});
		
		// Prevent menu close when clicking inside menu content
		mobileMenu.addEventListener('click', function(e) {
			e.stopPropagation();
		});
		
		console.log('✅ Mobile menu setup complete');
	}
	
	/**
	 * Handle toggle button click
	 */
	function handleToggleClick(e) {
		e.preventDefault();
		e.stopPropagation();
		
		console.log('🔄 Toggle button clicked, current state:', isMenuOpen ? 'open' : 'closed');
		
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
		console.log('📜 Setting up scroll effects...');
		
		let ticking = false;
	   
		window.addEventListener('scroll', function() {
			if (!ticking) {
				requestAnimationFrame(function() {
					handleScroll();
					ticking = false;
				});
				ticking = true;
			}
		});
		
		// Initial check
		handleScroll();
		
		console.log('✅ Scroll effects setup complete');
	}
   
	/**
	 * Setup keyboard navigation
	 */
	function setupKeyboardNavigation() {
		console.log('⌨️ Setting up keyboard navigation...');
		
		// Trap focus within mobile menu when open
		if (mobileMenu) {
			mobileMenu.addEventListener('keydown', trapFocus);
		}
		
		// Handle tab navigation in desktop menu
		const desktopMenuLinks = header.querySelectorAll('.desktop-nav a');
		desktopMenuLinks.forEach(link => {
			link.addEventListener('keydown', handleDesktopMenuKeydown);
		});
		
		console.log('✅ Keyboard navigation setup complete');
	}
	
	/**
	 * Setup accessibility features
	 */
	function setupAccessibility() {
		console.log('♿ Setting up accessibility features...');
		
		// Ensure proper ARIA attributes are set initially
		if (mobileMenuToggle) {
			mobileMenuToggle.setAttribute('aria-expanded', 'false');
		}
		
		if (mobileMenu) {
			mobileMenu.setAttribute('aria-hidden', 'true');
		}
		
		if (mobileMenuOverlay) {
			mobileMenuOverlay.setAttribute('aria-hidden', 'true');
		}
		
		console.log('✅ Accessibility setup complete');
	}
   
	/**
	 * Open mobile menu
	 */
	function openMobileMenu() {
		console.log('📖 Opening mobile menu...');
		
		isMenuOpen = true;
	   
		// Update ARIA attributes
		mobileMenuToggle.setAttribute('aria-expanded', 'true');
		mobileMenu.setAttribute('aria-hidden', 'false');
		mobileMenuOverlay.setAttribute('aria-hidden', 'false');
	   
		// Remove hidden classes
		mobileMenu.classList.remove('hidden');
		mobileMenuOverlay.classList.remove('hidden');
		
		// Add body class for styling
		body.classList.add('mobile-menu-open');
	   
		// Focus first menu item for accessibility
		const firstMenuItem = mobileMenu.querySelector('a');
		if (firstMenuItem) {
			setTimeout(() => firstMenuItem.focus(), 100);
		}
	   
		// Prevent body scroll on mobile
		if (window.innerWidth <= 768) {
			body.style.overflow = 'hidden';
		}
		
		console.log('✅ Mobile menu opened');
	}
   
	/**
	 * Close mobile menu
	 */
	function closeMobileMenu() {
		if (!isMenuOpen) return;
		
		console.log('📕 Closing mobile menu...');
	   
		isMenuOpen = false;
	   
		// Update ARIA attributes
		mobileMenuToggle.setAttribute('aria-expanded', 'false');
		mobileMenu.setAttribute('aria-hidden', 'true');
		mobileMenuOverlay.setAttribute('aria-hidden', 'true');
	   
		// Add hidden classes
		mobileMenu.classList.add('hidden');
		mobileMenuOverlay.classList.add('hidden');
		
		// Remove body class
		body.classList.remove('mobile-menu-open');
	   
		// Return focus to toggle button
		mobileMenuToggle.focus();
	   
		// Restore body scroll
		body.style.overflow = '';
		
		console.log('✅ Mobile menu closed');
	}
   
	/**
	 * Handle scroll events
	 */
	function handleScroll() {
		const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
	   
		// Add scrolled class when scrolling down
		if (scrollTop > 50) {
			header.classList.add('scrolled');
		} else {
			header.classList.remove('scrolled');
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
		if (window.innerWidth >= 768 && isMenuOpen) {
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
	   
		if (event.key === 'Tab') {
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
		document.addEventListener('DOMContentLoaded', init);
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