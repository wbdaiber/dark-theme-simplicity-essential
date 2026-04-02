# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

This is **Dark Theme Simplicity Essential** (v3.0), a custom WordPress theme for braddaiber.com. It's a dark-mode, performance-optimized theme built with Tailwind CSS (pre-compiled) and vanilla JavaScript. There are no build tools — CSS and JS are authored directly as production files.

## Deployment

The theme deploys to Hostinger via SCP. The remote WordPress path is:
```
/home/u239318559/domains/braddaiber.com/public_html/wp-content/themes/dark-theme-simplicity-essential
```

Use the SSH config host `hostinger` for file transfers:
```bash
scp -r dark-theme-simplicity-essential/ hostinger:/home/u239318559/domains/braddaiber.com/public_html/wp-content/themes/dark-theme-simplicity-essential
```

For remote WP-CLI commands, use the MCP tool `mcp__ssh-mcp__exec` (1000 char command limit).

## Architecture

### Customizer-First Content Model

Nearly all frontend content (hero text, service cards, benefits, approach steps, colors, footer) is managed through WordPress Customizer (`get_theme_mod()`), not hardcoded. The customizer setup lives across three files:
- `inc/customizer.php` — Main settings (hero, services, benefits, approach, about, contact, footer, colors)
- `inc/homepage-sections-customizer.php` — Front page panel organization
- `inc/customizer-repeater-control.php` — Custom `WP_Customize_Control` for repeatable items (service cards, benefits, approach steps) stored as JSON in theme mods

### Template Structure

- `front-page.php` — Homepage composed of 5 section includes from `template-parts/homepage/`
- `single.php` — Uses `DTS_Post_Helper` class to manage per-post settings (sidebar, TOC, share buttons)
- Post templates in `template-parts/post/` — Hero, sidebar, TOC, share buttons, related posts, breadcrumbs
- `page-templates/tools-template.php` — Grid layout for tools pages

### Key Classes

- **`DTS_Post_Helper`** (`inc/class-dts-post-helper.php`) — Manages post display settings via post meta (`_show_sidebar_widgets`, `_show_table_of_contents`, `_show_share_buttons`), extracts H2 headings for TOC
- **Walker classes** (`functions.php`) — `Dark_Theme_Simplicity_Walker_Nav` (parameterized, used for desktop with `header-nav-link` and mobile with `mobile-nav-link`) and `Dark_Theme_Simplicity_Walker_Simple_Menu` (footer/social menus with Tailwind classes)

### Critical CSS System

`inc/critical-css.php` inlines above-the-fold CSS from `assets/css/critical/` (homepage, single-post, archive) as a `<style id="critical-css">` block in `<head>` for first paint. Full stylesheets load render-blocking via standard `<link>` tags (deterministic cascade order). Page type detection in `dts_get_critical_css_file()` selects the correct critical file.

### JavaScript Loading Order

Scripts are vanilla JS loaded in dependency chain via `inc/enqueue.php`:
1. `config.js` (constants, no deps)
2. `core.js` (utilities, exposes `window.DTSCore`)
3. `navigation.js` → `content.js`
4. Page-specific: `pages/single-post.js` (single posts, depends on core), `blog-consolidated.js` (single posts, enqueued in `functions.php`), `pages/customizer.js` (admin customizer, jQuery)

### CSS Architecture (Grade A — April 2025)

CSS uses a layered architecture with clear file ownership. Each CSS property is defined in exactly ONE file. Load order (render-blocking, deterministic via `inc/enqueue.php`):

```
tokens.css → reset.css → style.css (Tailwind v4) → components.css → header.css → page-specific
```

Inline critical CSS (`<style id="critical-css">`) is injected in `<head>` before stylesheets for first paint. CSS loads render-blocking (no async preload pattern) — total CSS is ~40KB, well within modern performance budgets.

#### Core CSS files
| File | Owns |
|------|------|
| `assets/css/tokens.css` | ALL `:root` CSS custom properties — design system variables (colors, spacing, typography, shadows, z-index) |
| `assets/css/reset.css` | Element-level resets, body styles (SINGLE source for `body { padding-top: 4rem }`), base typography (h1-h6, p, a, lists), responsive typography scaling (md/lg breakpoints), accessibility (.screen-reader-text, .skip-link, :focus-visible), prefers-reduced-motion/prefers-contrast |
| `style.css` | Auto-generated Tailwind v4 utilities (no preflight). Regenerate: `./tailwindcss -i tailwind-input.css -o style-new.css` then prepend the WP theme header comment. Config: `tailwind-input.css`. Scans PHP templates + JS files for used classes. |
| `assets/css/components.css` | Layout containers (.container, .content-container, .section + responsive scaling), reusable UI components with co-located responsive variants: .site/.site-main, .entry-content, .content-layout (sidebar grid), .page-header, .hero-container, .sidebar-container, .card/.glass-card, .btn variants, .form-*, .table, .dropdown--top/bottom/left/right (positioning), .breadcrumbs, .badge, .alert, .widget, interactive feedback (.dts-copy-feedback, .toc-link.active), focus/accessibility enhancements |
| `assets/css/header.css` | .site-header, .site-logo, .site-title, .desktop-nav, .nav-menu, .header-nav-link, .mobile-menu*, .hamburger-*, navigation breakpoints |
| `assets/css/conversion-cta.css` | Nav CTA button, sidebar CTA card, mobile CTA |

#### Page-specific CSS (loaded conditionally)
| File | Owns |
|------|------|
| `assets/css/pages/homepage.css` | Homepage sections, animations, section cards, hero CTA buttons (.hero-headline, .hero-cta-primary/secondary), logo bar (.logo-bar-section, .logo-bar-logo with brightness(0) invert(1) filter) |
| `assets/css/pages/single-post.css` | Post typography overrides, hero section, related posts, scroll CTA, sidebar responsive, widget overflow, comments |
| `assets/css/pages/archive.css` | Blog listing grid, post cards, pagination, filters, featured/sticky posts, loading skeletons |

#### Critical CSS (inlined in `<head>`, auto-generated)
| File | Purpose |
|------|---------|
| `assets/css/critical/homepage.css` | Above-fold: tokens, reset, header skeleton, hero section, CTA buttons, logo bar filter |
| `assets/css/critical/single-post.css` | Above-fold: tokens, reset, header skeleton, hero/page-header, sidebar visibility |
| `assets/css/critical/archive.css` | Above-fold: tokens, reset, header skeleton, grid skeleton |

Regenerate critical CSS after any above-fold CSS change: `./scripts/generate-critical.sh`

#### Tailwind CLI
The Tailwind v4 standalone CLI binary is at `./tailwindcss` (v4.2.2) with input config at `tailwind-input.css`. To regenerate `style.css` after adding new Tailwind classes to templates:
```bash
./tailwindcss -i tailwind-input.css -o style-new.css
# Then prepend the WordPress theme header comment from the old style.css
```
The config disables Tailwind's preflight (we use `reset.css` instead) and strips unused default color families to reduce output size.

#### Files NOT to touch
- `assets/css/print.css` — Print styles (isolated by `@media print`)
- `assets/css/customizer.css`, `customizer-repeater.css`, `customizer-fixes.css` — Admin customizer UI
- `assets/css/editor-style.css` — Block editor styles

### Performance Optimizations

The theme aggressively removes WordPress bloat in `functions.php` (lines 768-809): block library CSS, classic theme styles, global styles, emoji scripts, and jQuery Migrate. Custom image sizes registered: `dts-related-post` (400×300), `dts-mobile-hero` (800×450), `dts-desktop-hero` (1200×900).

### Shortcodes

`[display_pages]` — Renders pages in grid/list layout with attributes: `number`, `parent`, `exclude`, `orderby`, `order`, `layout` (grid/list), `columns` (1-4), `show_excerpt`, `show_date`, `cta_text`.

### AJAX Endpoints

Three wp_ajax handlers in `inc/admin.php`: dismiss admin notices, toggle featured post status, dismiss social media notice.

## Conventions

- **No build tools required** — CSS/JS files are production-ready. Tailwind CLI (`./tailwindcss`) is available for regenerating `style.css` when new utility classes are needed.
- **CSS file ownership** — Every selector belongs to exactly ONE file. New components go in `components.css`. Layout containers are in `components.css`. Design token changes go in `tokens.css`. Element-level resets and typography scaling go in `reset.css`.
- **Adding Tailwind classes** — Add the class to a PHP template or JS file, then run `./tailwindcss -i tailwind-input.css -o style-new.css` and prepend the WP theme header. Do not manually edit `style.css`.
- **Critical CSS** — After changing above-fold styles, run `./scripts/generate-critical.sh` to regenerate. Critical CSS is inlined in `<head>` for first paint; full stylesheets load render-blocking after.
- **`body { padding-top: 4rem }`** — Defined ONLY in `reset.css`. Never add this to other files.
- **Theme mod keys** are prefixed with `dark_theme_simplicity_` (e.g., `dark_theme_simplicity_service_items`)
- **Text domain** is `dark-theme-simplicity-3` for all translatable strings
- **Accessibility** is built-in via `inc/accessibility.php` — maintain skip links, ARIA attributes, and semantic HTML
- H2 headings in post content automatically get IDs added (for TOC anchoring) via the `the_content` filter in `functions.php`
- **Deployment** — Upload individual files via `scp file hostinger:/remote/path` rather than `scp -r` which can create nested directories. Always purge LiteSpeed cache after: `wp litespeed-purge all`
