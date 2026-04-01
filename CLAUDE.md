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
- **Walker classes** (`functions.php` lines 595-709) — Four custom `Walker_Nav_Menu` subclasses for desktop, mobile, footer, and social menus, each outputting Tailwind-styled markup

### Critical CSS System

`inc/critical-css.php` inlines above-the-fold CSS from `assets/css/critical/` (homepage, single-post, archive) and async-loads remaining stylesheets. Page type detection in `dts_get_critical_css_file()` selects the correct critical file.

### JavaScript Loading Order

Scripts are vanilla JS loaded in dependency chain via `inc/enqueue.php`:
1. `config.js` (constants, no deps)
2. `core.js` (utilities, exposes `window.DTSCore`)
3. `header.js` → `navigation.js` → `content.js` → `consolidated-theme.js`
4. Page-specific: `blog-consolidated.js` (single posts), `customizer.js` (customizer preview)

### CSS Organization

Pre-compiled Tailwind CSS with no build step. Color palette uses custom dark tokens (`dark-200`, `dark-300`) with blue accents (`blue-300`–`blue-500`):
- `assets/css/base.css` — Core styles (resets, typography, components)
- `assets/css/pages/` — Page-specific styles (homepage, single-post, archive)
- `assets/css/critical/` — Inlined critical CSS per page type
- `assets/css/header.css`, `responsive.css` — Layout modules

### Performance Optimizations

The theme aggressively removes WordPress bloat in `functions.php` (lines 768-809): block library CSS, classic theme styles, global styles, emoji scripts, and jQuery Migrate. Custom image sizes registered: `dts-related-post` (400×300), `dts-mobile-hero` (800×450), `dts-desktop-hero` (1200×900).

### Shortcodes

`[display_pages]` — Renders pages in grid/list layout with attributes: `number`, `parent`, `exclude`, `orderby`, `order`, `layout` (grid/list), `columns` (1-4), `show_excerpt`, `show_date`, `cta_text`.

### AJAX Endpoints

Three wp_ajax handlers in `inc/admin.php`: dismiss admin notices, toggle featured post status, dismiss social media notice.

## Conventions

- **No build tools** — Edit CSS/JS files directly; they are production-ready
- **Tailwind utilities** are pre-compiled into `style.css` and component CSS files; do not add new Tailwind classes without recompiling
- **Theme mod keys** are prefixed with `dark_theme_simplicity_` (e.g., `dark_theme_simplicity_service_items`)
- **Text domain** is `dark-theme-simplicity-3` for all translatable strings
- **Accessibility** is built-in via `inc/accessibility.php` — maintain skip links, ARIA attributes, and semantic HTML
- H2 headings in post content automatically get IDs added (for TOC anchoring) via the `the_content` filter in `functions.php`
