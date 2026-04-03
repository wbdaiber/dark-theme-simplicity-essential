<!DOCTYPE html>
<html <?php language_attributes(); ?> class="h-full">
<head>
	<meta charset="<?php bloginfo('charset'); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<?php wp_head(); ?>
</head>
<body <?php body_class('min-h-full bg-dark-200 text-light-100'); ?>>
<?php wp_body_open(); ?>

<?php
$contact_email = get_theme_mod('dark_theme_simplicity_contact_email', 'brad.daiber1@gmail.com');
?>

<header class="site-header">
	<div class="container h-full flex items-center justify-between">
		<!-- Logo + Site Title -->
		<a href="<?php echo esc_url(home_url('/')); ?>" class="site-logo">
			<?php get_template_part('template-parts/homepage/logo'); ?>
			<span class="site-title"><?php echo esc_html(get_theme_mod('dark_theme_simplicity_site_title', get_bloginfo('name'))); ?></span>
		</a>

		<!-- Desktop Navigation + CTA -->
		<div class="desktop-nav-group">
			<nav class="desktop-nav" aria-label="Primary Navigation">
				<?php
				wp_nav_menu(array(
					'theme_location' => 'primary',
					'container'      => false,
					'menu_class'     => 'nav-menu',
					'fallback_cb'    => 'fallback_nav_menu',
					'items_wrap'     => '<ul id="%1$s" class="%2$s">%3$s</ul>',
					'depth'          => 1,
					'walker'         => new Dark_Theme_Simplicity_Walker_Nav('header-nav-link', true),
				));
				?>
			</nav>
			<a href="mailto:<?php echo esc_attr($contact_email); ?>" class="nav-cta-btn" aria-label="Contact me via email">
				Contact Me
			</a>
		</div>

		<!-- Mobile Menu Toggle -->
		<button
			id="mobile-menu-toggle"
			class="mobile-menu-toggle"
			aria-label="Toggle navigation menu"
			aria-expanded="false"
			aria-controls="mobile-menu"
		>
			<span class="hamburger-line"></span>
			<span class="hamburger-line"></span>
			<span class="hamburger-line"></span>
		</button>
	</div>

	<!-- Mobile Menu Overlay -->
	<div id="mobile-menu-overlay" class="mobile-menu-overlay hidden" aria-hidden="true"></div>

	<!-- Mobile Navigation -->
	<nav id="mobile-menu" class="mobile-menu hidden" aria-label="Mobile Navigation">
		<!-- Mobile CTA at top for priority -->
		<div class="mobile-cta-wrapper">
			<a href="mailto:<?php echo esc_attr($contact_email); ?>" class="mobile-cta-btn">
				<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
					<path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
					<polyline points="22,6 12,13 2,6"></polyline>
				</svg>
				Contact Me
			</a>
		</div>
		<?php
		wp_nav_menu(array(
			'theme_location' => 'primary',
			'container'      => false,
			'menu_class'     => 'mobile-nav-menu',
			'fallback_cb'    => 'fallback_nav_menu',
			'items_wrap'     => '<ul id="%1$s" class="%2$s">%3$s</ul>',
			'depth'          => 1,
			'walker'         => new Dark_Theme_Simplicity_Walker_Nav('mobile-nav-link'),
		));
		?>
	</nav>
</header>

<?php
?>