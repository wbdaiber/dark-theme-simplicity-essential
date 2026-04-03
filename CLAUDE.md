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
- `single.php` — Uses `DTS_Post_Helper` class (initialized inside the loop) to manage per-post settings (sidebar, TOC, share buttons). Includes hero, mobile TOC, sidebar, mobile CTA, author card, related posts, and scroll CTA.
- `page.php` — Static pages with optional sidebar, TOC, and share buttons via post meta
- Post templates in `template-parts/post/` — Hero, sidebar, TOC, share buttons, author card, related posts, scroll CTA, breadcrumbs
- `page-templates/tools-template.php` — Grid layout page template (not currently assigned to any published page)

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

### CSS Architecture (stabilized April 2026)

CSS uses a two-system architecture with clear ownership boundaries. Component CSS owns visual identity (appearance, decoration). Tailwind owns layout and spacing (positioning, sizing, flow). Load order (render-blocking, deterministic via `inc/enqueue.php`):

```
tokens.css → reset.css (@layer base) → style.css (Tailwind v4, post-processed) → utilities-extra.css → components.css → header.css → page-specific
```

Inline critical CSS (`<style id="critical-css">`) is injected in `<head>` before stylesheets for first paint. CSS loads render-blocking (no async preload pattern).

#### Ownership principle

| Property type | Owner | Examples |
|---|---|---|
| Background, gradient, backdrop-filter | Component CSS | `.glass-card` owns `background`, `backdrop-filter` |
| Border, border-radius | Component CSS | `.glass-card` owns `border`, `border-radius` |
| Shadow, transform, transition | Component CSS | `.glass-card` owns `box-shadow`, hover effects |
| Colors, typography in component context | Component CSS | `.entry-content` owns `font-size` |
| Padding (inside components) | Component CSS | `.glass-card` owns its own `padding` via Tailwind `p-6` |
| Width, max-width of containers | Component CSS | `.container` owns `max-width` — do NOT add `mx-auto` or `max-w-*` to elements with `.container` |
| Flex/grid layout | Tailwind | `flex`, `grid`, `gap`, `flex-col`, `md:flex-row` |
| Margin (between elements) | Tailwind | `mb-6`, `mt-4` |
| Responsive visibility | Tailwind | `hidden`, `md:block` |
| Text alignment, display | Tailwind | `text-center`, `inline-flex` |

**When both systems could apply:** Component CSS wins. Remove the Tailwind class — it does nothing and creates false expectations.

**Component overreach signal:** If you need inline styles to override a component property with per-instance values, the component shouldn't own that property. Refactor the component to release it (e.g., `.container` uses `padding-left`/`padding-right` instead of shorthand `padding` so templates can use `py-*` utilities for vertical spacing). Don't accumulate inline style overrides across templates.

#### Tailwind Utility Verification (REQUIRED)

This theme does NOT use Tailwind JIT compilation. All utility classes — including responsive variants (`md:`, `lg:`), state variants (`hover:`, `focus:`), and arbitrary values — must exist in either `style.css` or `assets/css/utilities-extra.css` to have any effect.

Before adding any Tailwind class to a template:
1. Grep the compiled stylesheets to confirm the class exists
2. If it doesn't exist, hand-write it in `assets/css/utilities-extra.css` in the correct `@media` block, respecting the property-type ordering within each block. Reference `assets/css/utilities-extra.css` directly for the current ordering — don't rely on this list if the file has been reorganized.
3. Never assume a class works just because it's valid Tailwind syntax
4. Only use the full recompile pipeline (`./scripts/build-css.sh`) when significant class coverage changes are needed — it's fragile and requires visual verification

Common gotcha: `hidden md:block` requires `md:block` to be explicitly defined. Any `hidden {breakpoint}:{display}` pattern needs verification — the responsive variant won't exist unless someone added it previously.

#### Core CSS files
| File | Owns |
|------|------|
| `assets/css/tokens.css` | ALL `:root` CSS custom properties — design system variables (colors, spacing, typography, shadows, z-index) |
| `assets/css/reset.css` | Wrapped in `@layer base`. Declares `@layer base, utilities;` cascade order at top. Element-level resets, body styles (SINGLE source for `body { padding-top: 4rem }`), base typography (h1-h6, p, a, lists), responsive typography scaling (md/lg breakpoints), list-style resets for nav/footer/TOC contexts, accessibility (.screen-reader-text, .skip-link, :focus-visible), prefers-reduced-motion/prefers-contrast |
| `style.css` | Tailwind v4 utilities, post-processed to v3-compatible format. Compiled via `./tailwindcss` then transformed by Node script (strips @layer wrappers, converts logical→physical properties, resolves spacing variables, converts color-mix→rgba, removes oklab). Includes injected v3 preflight resets. See "style.css regeneration" below. **v3 backup at `style.css.v3-backup` (local and server) — keep for reference.** |
| `assets/css/utilities-extra.css` | Additive sidecar loaded after style.css. Contains v3-compatible gradient class overrides (v4's gradient variable chain doesn't work), `bg-dark-400`, gradient variable initialization, and hand-written utility classes not in the v4 compile. |
| `assets/css/components.css` | Layout containers (.container, .content-container, .section + responsive scaling), reusable UI components with co-located responsive variants: .site/.site-main, .entry-content, .page-header, .hero-container, .sidebar-container, .card/.glass-card, .btn variants, .form-*, .table, .dropdown--top/bottom/left/right (positioning), .breadcrumbs, .badge, .alert, .widget, interactive feedback (.dts-copy-feedback, .toc-link.active), focus/accessibility enhancements |
| `assets/css/header.css` | .site-header, .site-logo, .site-title, .desktop-nav, .nav-menu, .header-nav-link, .mobile-menu*, .hamburger-*, navigation breakpoints |
| `assets/css/conversion-cta.css` | Nav CTA button, sidebar CTA card, mobile CTA |

#### Page-specific CSS (loaded conditionally)
| File | Owns |
|------|------|
| `assets/css/pages/homepage.css` | Homepage animations, service card Customizer background override (`.services-section .glass-card`), hero CTA buttons (.hero-headline, .hero-cta-primary/secondary), logo bar (.logo-bar-section, .logo-bar-logo with brightness(0) invert(1) filter), reduced-motion support |
| `assets/css/pages/single-post.css` | Post typography overrides (`.single .entry-content`), single-post hero overrides (`.single .hero-content`, `.single .page-header`), related posts, scroll CTA, sidebar responsive (`.post-sidebar` visibility), widget overflow |
| `assets/css/pages/archive.css` | Blog listing grid, post cards, pagination, filters, featured/sticky posts, loading skeletons |

#### Critical CSS (inlined in `<head>`, auto-generated)
| File | Purpose |
|------|---------|
| `assets/css/critical/homepage.css` | Above-fold: tokens, reset, header skeleton, hero section, CTA buttons, logo bar filter |
| `assets/css/critical/single-post.css` | Above-fold: tokens, reset, header skeleton, hero/page-header, sidebar visibility |
| `assets/css/critical/archive.css` | Above-fold: tokens, reset, header skeleton, grid skeleton |

Regenerate critical CSS after any above-fold CSS change: `./scripts/generate-critical.sh`

#### `style.css` regeneration pipeline

`style.css` is compiled from Tailwind v4 (`./tailwindcss` binary, v4.2.2) then heavily post-processed to produce v3-compatible output. **Do NOT deploy raw v4 output — it uses logical properties, CSS variables, and a gradient system that break the site.**

**Pipeline (automated via `./scripts/build-css.sh`):**
```bash
./scripts/build-css.sh           # builds style.css
./scripts/build-css.sh --dry-run # builds style-v4-preview.css for comparison
```
The script runs compile + all post-processing + WP header in one command. Steps it performs:
1. `./tailwindcss -i tailwind-input.css -o style-v4-raw.css`
2. Node post-processing: strips `@layer` wrappers, converts logical→physical properties, resolves spacing variables, converts `color-mix()`→`rgba()`, removes `in oklab` from gradients, injects v3 preflight resets
3. Prepends WP theme header (keep Version: 3.2 — never bump)

After building: deploy via SCP + purge LiteSpeed cache + verify visually.

**Known issues that post-processing does NOT fix (handled by utilities-extra.css):**
- v4's gradient variable chain (`--tw-gradient-position`, `--tw-gradient-via-stops`) doesn't produce visible gradients — utilities-extra.css overrides all `bg-gradient-to-*`, `from-*`, `via-*`, `to-*` classes with v3's simpler pattern
- `--tw-gradient-*` variables need v3-style initialization via `*,::before,::after{}` block in utilities-extra.css

**Critical `@theme` values in `tailwind-input.css` (must match v3 output):**
- `--color-dark-300: #0c0c0e` (v3 value — NOT the same as tokens.css `--surface-300` which is `#1f2937`)
- `--color-dark-400: initial` (v3 never generated this class; elements with `bg-dark-400` get their background from utilities-extra.css)
- `--font-sans`, `--radius-*`, `--shadow-*` must match tokens.css values

**Token naming:** tokens.css uses `--surface-300` / `--surface-400` for the dark blue-gray colors (`#1f2937`, `#374151`). Tailwind uses `--color-dark-300` / `--color-dark-400` for a different, darker color scale. These are intentionally different names to prevent confusion.

**Adding missing utility classes:** See "Tailwind Utility Verification" section.

**v3 reference:** `style.css.v3-backup` (local and server) contains the original v3 compiled output. Keep it — v3's actual color values, preflight rules, and class definitions are needed to debug v4 regressions.

#### How to safely change CSS
- **To change a component's appearance** → edit its CSS class in the owning file (e.g., `.glass-card` in `components.css`)
- **To change layout/spacing** → edit Tailwind classes in the PHP template, but verify the class exists first (see "Tailwind Utility Verification" section)
- **To add a missing Tailwind utility** → see "Tailwind Utility Verification" section
- **Never put the same property in both a component class and a Tailwind utility on the same element** — if a component class owns `border-radius`, don't also add `rounded-lg` to the HTML

#### Comments
Comments are disabled site-wide via `functions.php`. The `comments_template()` call has been removed from `single.php`. Do not restyle `comments.php`.

#### Files NOT to touch
- `assets/css/print.css` — Print styles (isolated by `@media print`)
- `assets/css/customizer.css`, `customizer-repeater.css`, `customizer-fixes.css` — Admin customizer UI
- `assets/css/editor-style.css` — Block editor styles
- `assets/css/header.css`, `assets/css/conversion-cta.css` — Owned by other systems, stable

### Performance Optimizations

The theme aggressively removes WordPress bloat in `functions.php`: block library CSS, classic theme styles, global styles, emoji scripts, and jQuery Migrate. Comments are also disabled site-wide. Custom image sizes registered: `dts-related-post` (400×300), `dts-mobile-hero` (800×450), `dts-desktop-hero` (1200×900).

### Shortcodes

`[display_pages]` — Renders pages in grid/list layout with attributes: `number`, `parent`, `exclude`, `orderby`, `order`, `layout` (grid/list), `columns` (1-4), `show_excerpt`, `show_date`, `cta_text`.

### AJAX Endpoints

Three wp_ajax handlers in `inc/admin.php`: dismiss admin notices, toggle featured post status, dismiss social media notice.

## Conventions

- **No build tools required for daily work** — CSS/JS files are production-ready. `style.css` is a compiled artifact that CAN be regenerated via the post-processing pipeline (see "style.css regeneration") but this is fragile and requires visual verification.
- **CSS file ownership** — Every selector belongs to exactly ONE file. New components go in `components.css`. Layout containers are in `components.css`. Design token changes go in `tokens.css`. Element-level resets and typography scaling go in `reset.css`.
- **Adding Tailwind classes** — See "Tailwind Utility Verification" section under CSS Architecture.
- **Critical CSS** — After changing above-fold styles, run `./scripts/generate-critical.sh` to regenerate. Critical CSS is inlined in `<head>` for first paint; full stylesheets load render-blocking after.
- **`body { padding-top: 4rem }`** — Defined ONLY in `reset.css`. Never add this to other files.
- **Theme mod keys** are prefixed with `dark_theme_simplicity_` (e.g., `dark_theme_simplicity_service_items`)
- **Text domain** is `dark-theme-simplicity-3` for all translatable strings
- **Accessibility** is built-in via `inc/accessibility.php` — maintain skip links, ARIA attributes, and semantic HTML
- H2 headings in post content automatically get IDs added (for TOC anchoring) via the `the_content` filter in `functions.php`
- **Deployment** — Upload individual files via `scp file hostinger:/remote/path` rather than `scp -r` which can create nested directories. Always purge LiteSpeed cache after: `wp litespeed-purge all`
