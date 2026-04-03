# Theme Audit: Dark Theme Simplicity Essential on braddaiber.com

**Generated:** 2026-04-03
**Remediation completed:** 2026-04-03
**Theme type:** Custom Tailwind CSS + PHP (no page builder)
**Theme version:** 3.0 (style.css header says 3.2)

---

## Remediation Status

| Issue | Status | Notes |
|-------|--------|-------|
| C1 — scroll-cta inline style / `!important` chain | ✅ Fixed & deployed | Removed inline style + 3 `!important` declarations |
| C2 — Critical CSS `.desktop-nav` display value | ✅ Fixed & deployed | Changed `flex` → `block` in single-post + archive critical CSS |
| C3 — Unscoped `.page-header` in single-post.css | ✅ Fixed & deployed | Scoped to `.single .page-header` |
| W1 — Dynamic `animation-delay-*` construction | ✅ Fixed & deployed | Replaced with complete class map (caps at 600ms) |
| W2 — Walker `esc_attr()` on href | ✅ Fixed & deployed | Changed to `esc_url()` |
| W3 — Unescaped `$cta_text` | ✅ Fixed & deployed | Added `esc_html()` (both grid + list layouts) |
| W4 — Unescaped `get_the_title()` | ✅ Fixed & deployed | Added `esc_html()` wrapper |
| W5 — Inline `!important` on blog hero description | ✅ Fixed & deployed | Converted to CSS custom properties |
| W6 — `container mx-auto` redundancy | ✅ Fixed & deployed | Removed `mx-auto` (and `max-w-6xl`) from 13 instances |
| W7 — Unscoped `.hero-content` override | ✅ Fixed & deployed | Scoped to `.single .hero-content` |
| W8 — Duplicate `.no-sidebar .entry-content` | ✅ Fixed & deployed | Removed dead code from single-post.css |
| W9 — author-card.php inline `max-height` | ✅ Fixed & deployed | Moved to `max-h-[250px]` utility in utilities-extra.css |
| W10 — Missing `search.php` template | ⏭️ Skipped | No search function on the site; not needed |
| W11 — `get_the_ID()` before the loop | ✅ Fixed & deployed | Moved inside loop in both single.php and page.php |

**Additional fix:** Added missing `get_template_part('template-parts/post/author-card')` to `single.php` — was not included by any template.

---

## Executive Summary

- **3 Critical** issues (CSS architecture — no security or data-loss risks) — **all resolved**
- **11 Warnings** (escaping gaps, specificity conflicts, dead code) — **10 resolved, 1 skipped (N/A)**
- **8 Info** (minor hygiene, redundancy)
- **Tailwind pipeline health:** Healthy — all classes verified present, no purge gaps, `@source` coverage complete

## Tailwind Pipeline Status

| Check | Status |
|-------|--------|
| Config file | `tailwind-input.css` (v4, CSS-based `@theme`) |
| Content coverage | Complete — all PHP dirs covered by `@source` |
| Build script | `./scripts/build-css.sh` (standalone binary + Node post-processing) |
| Compiled CSS freshness | In sync — server and local byte-identical |
| Purge verification | All classes present in compiled CSS |
| @apply count | 0 (clean) |
| Dynamic class construction | 1 pattern (`animation-delay-*`) — partial string, limited CSS definitions |

## Server Environment

| Check | Status |
|-------|--------|
| Active theme | `dark-theme-simplicity-essential` (confirmed) |
| Customizer CSS | None (clean) |
| Active plugins | 4 (LiteSpeed Cache, Rank Math, ReactPress, WP File Manager) — none inject frontend CSS |
| CSS sync | style.css, components.css, utilities-extra.css all byte-identical local↔server |
| Plugin updates | 3 pending (wp-file-manager is security-sensitive) |

---

## Critical Fixes (do these first)

### C1. scroll-cta.php inline style duplicates CSS class — causes `!important` chain

**What:** The inline `style=` attribute on `#scroll-cta` duplicates every property from the `.scroll-cta` CSS class, making the CSS class dead code. The `.scroll-cta.is-visible` state class then needs 3 `!important` declarations to beat the inline style.

**Where:** `template-parts/post/scroll-cta.php:12`

**Root cause:** Component overreach — inline style was added for initial state, but the CSS class already defines the same initial state.

**Connected issues:** `single-post.css:641-643` has 3 `!important` declarations that exist solely to beat this inline style.

**Blast radius:** Low — only affects the scroll CTA component on single posts.

**Fix:**

Before (`scroll-cta.php:12`):
```php
<div id="scroll-cta" class="scroll-cta" aria-hidden="true" style="position:fixed;bottom:1.5rem;left:1.5rem;z-index:50;max-width:320px;opacity:0;pointer-events:none;transition:opacity .4s ease,transform .4s ease">
```

After:
```php
<div id="scroll-cta" class="scroll-cta" aria-hidden="true">
```

Then remove `!important` from `single-post.css:640-644`:

Before:
```css
.scroll-cta.is-visible {
  opacity: 1 !important;
  transform: translateY(0) !important;
  pointer-events: auto !important;
}
```

After:
```css
.scroll-cta.is-visible {
  opacity: 1;
  transform: translateY(0);
  pointer-events: auto;
}
```

---

### C2. Critical CSS has wrong `.desktop-nav` display value

**What:** Critical CSS for single-post and archive pages sets `.desktop-nav { display: flex }` at the 768px breakpoint, but the authoritative source (`header.css:228`) sets `display: block`. During the critical-CSS-only rendering window (before full stylesheets load), the nav renders as flexbox instead of block, causing a brief layout shift.

**Where:**
- `assets/css/critical/single-post.css:15` — `.desktop-nav{display:flex}`
- `assets/css/critical/archive.css:14` — `.desktop-nav{display:flex}`
- `assets/css/critical/homepage.css:17` — `.desktop-nav{display:block}` (correct)
- `assets/css/header.css:228-230` — `.desktop-nav { display: block }` (authoritative)

**Root cause:** Critical CSS generator picked up the wrong value for `.desktop-nav` on single-post/archive pages, or the critical CSS was generated from a stale version of header.css.

**Connected issues:** None.

**Blast radius:** Visual-only — brief FOUC on page load for single posts and archive pages. Corrected once `header.css` loads.

**Fix:** Regenerate critical CSS:
```bash
./scripts/generate-critical.sh
```

If the generator still produces the wrong value, manually fix the critical CSS files:

In `assets/css/critical/single-post.css`, within the `@media(min-width:768px)` block, change:
```
.desktop-nav{display:flex}
```
to:
```
.desktop-nav{display:block}
```

Same fix in `assets/css/critical/archive.css`.

---

### C3. `.page-header` selector in single-post.css is unscoped

**What:** `single-post.css:88` defines `.page-header { position: relative; overflow: hidden; }` without scoping it to `.single`. This adds `overflow: hidden` to `.page-header` on ALL page types (not just single posts), since single-post.css loads after components.css. The `overflow: hidden` could clip absolutely-positioned decorative elements on archive or page templates.

**Where:** `assets/css/pages/single-post.css:88-91`

**Root cause:** Missing scope — should target `.single .page-header`.

**Connected issues:** `components.css:182` defines the base `.page-header` with `position: relative` but no `overflow: hidden`. The single-post.css override adds `overflow: hidden` for hero background image clipping, but it leaks to all contexts.

**Blast radius:** Medium — affects `.page-header` on any page type where `single-post.css` loads. If single-post.css is conditionally loaded (only on single posts), impact is contained.

**Fix:**

Before (`single-post.css:88-91`):
```css
.page-header {
  position: relative;
  overflow: hidden;
}
```

After:
```css
.single .page-header {
  position: relative;
  overflow: hidden;
}
```

**Note:** Check `inc/enqueue.php` to verify whether `single-post.css` loads conditionally. If it only loads on `is_single()`, this is contained but still should be scoped for safety.

---

## Warning Fixes (do these next)

### W1. `animation-delay-*` dynamic class construction (5 files)

**What:** Template files build `animation-delay-{$index * 200}` via string interpolation. CSS only defines delays for 200, 300, 400, 600ms. If a Customizer repeater has 5+ items, indices 4+ generate `animation-delay-800`, `animation-delay-1000`, etc. — these classes don't exist in CSS, so the staggered animation effect is lost.

**Where:**
- `template-parts/homepage/section-services.php:61`
- `template-parts/homepage/section-benefits.php:67,98`
- `template-parts/homepage/section-approach.php:25`
- `template-parts/homepage/section-contact.php:53`
- `section-approach.php:25`

**Root cause:** Dynamic class construction — Tailwind scanner can't detect partial strings.

**Fix (option A — cap the delay):** In each affected file, change:
```php
$animation_delay = $index * 200;
$delay_class = $index > 0 ? "animation-delay-{$animation_delay}" : "";
```
to:
```php
$delay_map = array( 1 => 'animation-delay-200', 2 => 'animation-delay-400', 3 => 'animation-delay-600' );
$delay_class = isset( $delay_map[ $index ] ) ? $delay_map[ $index ] : 'animation-delay-600';
```

**Fix (option B — add more CSS definitions):** Add to `assets/css/pages/homepage.css` after line 83:
```css
.animation-delay-800 {
    animation-delay: 0.8s;
}

.animation-delay-1000 {
    animation-delay: 1.0s;
}

.animation-delay-1200 {
    animation-delay: 1.2s;
}
```
Option A is preferred — it uses complete string mapping (the Tailwind-safe pattern) and caps the delay so animations don't become comically slow with many items.

---

### W2. Walker URL uses `esc_attr()` instead of `esc_url()`

**What:** `Dark_Theme_Simplicity_Walker_Simple_Menu` uses `esc_attr()` for the `href` attribute instead of `esc_url()`. This doesn't validate URL scheme, so a `javascript:` URL could pass through.

**Where:** `functions.php:196`

**Root cause:** Copy-paste from the attribute escaping pattern above it.

**Blast radius:** Low — menu URLs are set by admins only.

**Fix:**

Before (`functions.php:196`):
```php
$attributes .= ! empty($item->url)        ? ' href="'   . esc_attr($item->url        ) .'"' : '';
```

After:
```php
$attributes .= ! empty($item->url)        ? ' href="'   . esc_url($item->url         ) .'"' : '';
```

---

### W3. Unescaped `$cta_text` in shortcodes.php

**What:** `$cta_text` from shortcode attributes is output without escaping. Shortcode attributes are author-controlled, but best practice requires escaping.

**Where:** `inc/shortcodes.php:135`

**Fix:**

Before:
```php
<?php echo $cta_text; ?>
```

After:
```php
<?php echo esc_html($cta_text); ?>
```

Apply same fix at line 176 (list layout).

---

### W4. Unescaped `get_the_title()` in tools-template.php

**What:** `get_the_title()` does not escape its output (unlike `the_title()`).

**Where:** `page-templates/tools-template.php:18`

**Fix:**

Before:
```php
echo get_the_title() ?: esc_html(get_theme_mod('dark_theme_simplicity_tools_hero_title', 'Tools & Resources'));
```

After:
```php
echo esc_html(get_the_title()) ?: esc_html(get_theme_mod('dark_theme_simplicity_tools_hero_title', 'Tools & Resources'));
```

---

### W5. index.php inline `!important` with dynamic Customizer values

**What:** Uses `style="color: ... !important; opacity: ... !important;"` — the nuclear specificity option. Dynamic values are acceptable as inline styles, but `!important` is unnecessary.

**Where:** `index.php:57`

**Fix:**

Before:
```php
style="color: <?php echo esc_attr($desc_color); ?> !important; opacity: <?php echo esc_attr($desc_opacity_decimal); ?> !important;"
```

After (use CSS custom properties):
```php
style="--desc-color: <?php echo esc_attr($desc_color); ?>; --desc-opacity: <?php echo esc_attr($desc_opacity_decimal); ?>;"
```

Then add to `assets/css/pages/archive.css`:
```css
.blog-hero-description {
  color: var(--desc-color, var(--text-muted));
  opacity: var(--desc-opacity, 0.7);
}
```

---

### W6. `container mx-auto` redundancy (~10 instances)

**What:** `.container` in `components.css` already sets `margin: 0 auto`. Adding `mx-auto` does nothing but creates a false expectation that removing it would un-center the container. Per the CSS ownership principle: component CSS owns `margin` on `.container`.

**Where:** `archive.php:6`, `single.php:25`, `page.php:30`, `footer.php:2`, `header.php:17`, and ~5 more.

**Fix:** Remove `mx-auto` from any element that also has the `container` class. Example:

Before:
```html
<div class="container mx-auto">
```

After:
```html
<div class="container">
```

Also in `single.php:25`, remove `max-w-6xl` — `.container` owns `max-width: var(--content-full)`, so the Tailwind utility is either dead code or silently overridden.

---

### W7. `.hero-content` partial override between files

**What:** `components.css:209` and `single-post.css:140` both define `.hero-content`. The single-post version overrides `padding` but not `display`, `flex-direction`, or `gap`, creating an implicit merged state.

**Where:** `components.css:209-214` and `single-post.css:140-147`

**Fix:** Scope the single-post version:

Before (`single-post.css:140`):
```css
.hero-content {
  position: relative;
  z-index: 10;
  width: 100%;
  max-width: var(--content-wide);
  margin: 0 auto;
  padding: var(--spacing-lg) var(--spacing-md);
}
```

After:
```css
.single .hero-content {
  position: relative;
  z-index: 10;
  width: 100%;
  max-width: var(--content-wide);
  margin: 0 auto;
  padding: var(--spacing-lg) var(--spacing-md);
}
```

---

### W8. `.no-sidebar .entry-content` duplicate definitions

**What:** Identical `max-width` values at matching breakpoints in both `components.css` and `single-post.css`. The single-post.css copies are 100% dead code.

**Where:** `single-post.css:501,509` duplicate `components.css:104,109,113,118`

**Fix:** Remove the duplicate definitions from `single-post.css`. The `components.css` versions are the canonical source.

---

### W9. author-card.php inline `max-height`

**What:** `style="max-height: 250px;"` is a lazy override — should be a utility or component rule.

**Where:** `template-parts/post/author-card.php:21`

**Fix:**

Before:
```html
<img ... style="max-height: 250px;" ...>
```

After (add to `utilities-extra.css`):
```css
.max-h-\[250px\] {
  max-height: 250px;
}
```

Then in template:
```html
<img ... class="w-16 h-16 md:w-full md:h-auto object-cover max-h-[250px]" ...>
```

---

### W10. Missing `search.php` template

**What:** No dedicated search results template. WordPress falls back to `index.php`, which shows the blog hero, featured articles section, etc. — not ideal for search results.

**Where:** Theme root — `search.php` does not exist.

**Fix:** Create `search.php` with appropriate search results layout, or verify that `index.php`'s `is_search()` conditional (line 51) provides an acceptable experience and decide this is intentional.

---

### W11. `single.php` and `page.php` use `get_the_ID()` before the loop

**What:** Both files call `get_the_ID()` and `get_post_meta()` before `the_post()`. Works in practice because WordPress pre-sets the global `$post` for these template types, but is technically fragile.

**Where:** `single.php:11`, `page.php:5,18,24`

**Fix (single.php):**

Before:
```php
$post_helper = new DTS_Post_Helper( get_the_ID() );
```

After — move inside the loop after `the_post()`:
```php
while ( have_posts() ) : the_post();
$post_helper = new DTS_Post_Helper( get_the_ID() );
```

Same pattern for `page.php` — move `get_post_meta()` calls after `the_post()`.

---

## PHP <-> CSS Connection Map

| Template | Condition | Class Applied | CSS Rule | Survives Purge? | Visual Result |
|----------|-----------|---------------|----------|-----------------|---------------|
| `section-services.php:61` | `$index > 0` | `animation-delay-{$index*200}` | `homepage.css:69-83` | Only for 200/400/600 | Items 4+ animate without stagger |
| `section-services.php:77` | Always (Customizer) | `glass-card` + `--service-card-bg` | `homepage.css:86` compound `.services-section .glass-card` | Yes | Customizer bg color applied |
| `scroll-cta.php:12` | Always | `.scroll-cta` | `single-post.css:628-638` | Yes | CSS class dead — inline style wins |
| `scroll-cta.php:12` | JS adds `is-visible` | `.scroll-cta.is-visible` | `single-post.css:640-643` | Yes | Works via `!important` (fragile) |
| `index.php:57` | Always (Customizer) | inline `!important` color/opacity | No CSS class | N/A | Dynamic color works, but `!important` is overkill |
| `page.php:31` | `$show_sidebar` | `flex flex-col md:flex-row gap-8` | Tailwind utilities | Yes | Layout toggles correctly |
| `author-card.php:21` | Always | inline `max-height: 250px` | No CSS class | N/A | Caps image height; should be utility |

---

## Architectural Recommendations

1. **Animation delay pattern:** Replace dynamic string construction with a complete class map. This is the standard Tailwind-safe pattern for conditional classes.

2. **Inline style policy:** Dynamic values from PHP (Customizer colors, background images) should use CSS custom properties (`style="--var: value"`) with a CSS rule reading `var(--var)`, not direct property declarations. This avoids specificity 1000 and keeps CSS the single source of truth for styling.

3. **Selector scoping in page-specific CSS:** Every selector in `single-post.css` should be scoped to `.single`, every selector in `archive.css` should be scoped to `.archive` / `.blog` / `.search`. Unscoped selectors leak to all page types.

4. **Critical CSS regeneration:** After any fix to header.css, components.css, or page-specific CSS, run `./scripts/generate-critical.sh` and verify the output matches the authoritative stylesheets.

5. **`!important` reduction plan:** After fixing C1 (scroll-cta inline style), 3 `!important` declarations can be removed. The `homepage.css` animation `!important` declarations (`.dts-animate-in`) could be removed by changing the JS to toggle a data attribute instead of fighting CSS specificity.

6. **Plugin security:** Update `wp-file-manager` immediately — it's a known target for exploits when outdated.

---

## File-by-File Appendix

### PHP Templates
| File | Status | Notes |
|------|--------|-------|
| `front-page.php` | ✅ Clean | |
| `single.php` | ⚠️ Warning | W11: `get_the_ID()` before loop; W6: `container mx-auto max-w-6xl` redundancy |
| `page.php` | ⚠️ Warning | W11: `get_post_meta()` before loop; W6: `container mx-auto` |
| `index.php` | ⚠️ Warning | W5: inline `!important`; W6: `container mx-auto` |
| `archive.php` | ⚠️ Warning | W6: `container mx-auto`; minor unescaped `category_description()` |
| `404.php` | ✅ Clean | |
| `header.php` | ⚠️ Warning | W6: `container mx-auto` |
| `footer.php` | ⚠️ Warning | W6: `container mx-auto` |
| `sidebar.php` | ✅ Clean | |
| `section-approach.php` | ⚠️ Warning | W1: dynamic `animation-delay-*` |
| `page-templates/tools-template.php` | ⚠️ Warning | W4: unescaped `get_the_title()` |
| `template-parts/homepage/section-services.php` | ⚠️ Warning | W1: dynamic `animation-delay-*` |
| `template-parts/homepage/section-benefits.php` | ⚠️ Warning | W1: dynamic `animation-delay-*` |
| `template-parts/homepage/section-approach.php` | ⚠️ Warning | W1: dynamic `animation-delay-*` |
| `template-parts/homepage/section-contact.php` | ⚠️ Warning | W1: dynamic `animation-delay-*` |
| `template-parts/homepage/section-featured.php` | ✅ Clean | |
| `template-parts/homepage/section-logos.php` | ✅ Clean | |
| `template-parts/homepage/section-about.php` | ✅ Clean | |
| `template-parts/post/scroll-cta.php` | 🔴 Critical | C1: inline style duplicates CSS class |
| `template-parts/post/author-card.php` | ⚠️ Warning | W9: inline `max-height` |
| `template-parts/post/related-posts.php` | ✅ Clean | |
| `template-parts/post/breadcrumbs.php` | ✅ Clean | |
| `template-parts/post/mobile-toc.php` | ✅ Clean | |
| `template-parts/global/contact-section.php` | ✅ Clean | |
| `inc/class-dts-post-helper.php` | ✅ Clean | |
| `inc/shortcodes.php` | ⚠️ Warning | W3: unescaped `$cta_text` |
| `inc/admin.php` | ✅ Clean | Admin-only context |
| `inc/enqueue.php` | ✅ Clean | |
| `inc/customizer.php` | ✅ Clean | |
| `inc/customizer-repeater-control.php` | ✅ Clean | Admin-only |
| `inc/accessibility.php` | ✅ Clean | |
| `inc/critical-css.php` | ✅ Clean | |
| `functions.php` | ⚠️ Warning | W2: walker `esc_attr()` on URL |

### CSS Files
| File | Status | Notes |
|------|--------|-------|
| `assets/css/tokens.css` | ✅ Clean | |
| `assets/css/reset.css` | ✅ Clean | 5 `!important` (all accessibility — required) |
| `style.css` | ✅ Clean | Compiled output, byte-identical local↔server |
| `assets/css/utilities-extra.css` | ✅ Clean | Some `md:` duplicates with style.css (harmless weight) |
| `assets/css/components.css` | ⚠️ Warning | W6: `.mobile-single-col` uses `!important` nuclear override |
| `assets/css/header.css` | ✅ Clean | 1 `!important` (desktop mobile-menu hiding — justified) |
| `assets/css/conversion-cta.css` | ✅ Clean | |
| `assets/css/pages/homepage.css` | ⚠️ Warning | 3 `!important` (animation state toggles) |
| `assets/css/pages/single-post.css` | 🔴 Critical | C1: 3 `!important` from scroll-cta war; C3: unscoped `.page-header`; W7/W8: partial/duplicate overrides |
| `assets/css/pages/archive.css` | ✅ Clean | |
| `assets/css/critical/homepage.css` | ✅ Clean | |
| `assets/css/critical/single-post.css` | 🔴 Critical | C2: `.desktop-nav{display:flex}` should be `block` |
| `assets/css/critical/archive.css` | 🔴 Critical | C2: `.desktop-nav{display:flex}` should be `block` |
| `assets/css/print.css` | ✅ Clean | Not audited (isolated by @media print) |
| `assets/css/customizer.css` | ✅ Clean | Admin-only |
| `assets/css/editor-style.css` | ✅ Clean | Admin-only |

---

*Audit completed: 2026-04-03 | Auditor: Claude Code (Opus 4.6)*
*Script: wp-theme-audit.sh adapted with project-specific knowledge from CLAUDE.md*
