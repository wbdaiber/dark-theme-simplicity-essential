#!/bin/bash
#
# generate-critical.sh — Extract above-fold CSS from source files
#
# Produces minified critical CSS for each page type by concatenating
# relevant selectors from source CSS files. Run after any CSS change:
#   ./scripts/generate-critical.sh
#
# The critical CSS is inlined in <head> by critical-css.php for first paint
# before render-blocking stylesheets load.

set -euo pipefail

THEME_DIR="$(cd "$(dirname "$0")/.." && pwd)"
CSS_DIR="$THEME_DIR/assets/css"
CRITICAL_DIR="$CSS_DIR/critical"

mkdir -p "$CRITICAL_DIR"

# Minify CSS: collapse whitespace, remove comments, trim
minify() {
  # Remove comments
  sed 's|/\*[^*]*\*\+\([^/*][^*]*\*\+\)*/||g' |
  # Collapse whitespace
  tr '\n' ' ' |
  sed 's/  */ /g' |
  # Remove spaces around CSS punctuation
  sed 's/ *{ */{/g; s/ *} */}/g; s/ *: */:/g; s/ *; */;/g; s/ *, */,/g' |
  # Trim
  sed 's/^ *//; s/ *$//'
}

# ============================================================
# Shared critical blocks (used by all page types)
# ============================================================

# 1. Essential :root variables (subset from tokens.css — only what above-fold needs)
CRITICAL_VARS=':root{--dark-bg:rgb(26,26,28);--dark-300:rgba(31,41,55,1);--dark-400:rgba(55,65,81,1);--text-primary:rgba(255,255,255,0.9);--text-secondary:rgba(255,255,255,0.95);--text-muted:rgba(255,255,255,0.85);--accent-blue:rgba(59,130,246,0.9);--accent-blue-hover:rgba(59,130,246,1);--font-sans:'"'"'Inter'"'"',-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif;--spacing-xs:0.25rem;--spacing-sm:0.5rem;--spacing-md:1rem;--spacing-lg:1.5rem;--spacing-xl:2rem;--spacing-2xl:3rem;--primary-blue:#0085ff;--primary-blue-hover:#0057a7;--border-color:rgba(255,255,255,0.1);--content-default:800px;--content-full:1200px;--radius-sm:0.25rem;--radius-md:0.5rem;--radius-lg:0.75rem}'

# 2. Box model reset
CRITICAL_RESET='*,*::before,*::after{box-sizing:border-box}'

# 3. HTML + body base
CRITICAL_HTML='html{font-size:16px;-webkit-font-smoothing:antialiased;-moz-osx-font-smoothing:grayscale;text-rendering:optimizeLegibility}'
CRITICAL_BODY='body{margin:0;padding-top:4rem;font-family:var(--font-sans);font-size:1rem;line-height:1.6;color:var(--text-primary);background-color:var(--dark-bg);min-height:100vh}'

# 4. Heading/text base
CRITICAL_HEADINGS='h1,h2,h3{margin:0 0 var(--spacing-md);font-weight:600;line-height:1.3;color:var(--text-secondary)}h1{font-size:2.25rem;margin-bottom:var(--spacing-lg)}'
CRITICAL_TEXT='p{margin:0 0 var(--spacing-md);color:var(--text-primary)}a{color:var(--accent-blue);text-decoration:none}'

# 5. Header skeleton
CRITICAL_HEADER='.site-header{position:fixed;top:0;left:0;width:100%;height:4rem;background-color:rgba(18,18,20,.95);backdrop-filter:blur(12px);-webkit-backdrop-filter:blur(12px);border-bottom:1px solid rgba(255,255,255,.1);z-index:100}'

# 6. Container base
CRITICAL_CONTAINER='.container{width:100%;max-width:var(--content-full);margin:0 auto;padding:0 var(--spacing-md)}'

# 7. Utility classes used above the fold
CRITICAL_UTILS='.hidden{display:none}.flex{display:flex}.relative{position:relative}.absolute{position:absolute}.inset-0{top:0;right:0;bottom:0;left:0}.z-10{z-index:10}.items-center{align-items:center}.justify-center{justify-content:center}.text-center{text-align:center}.w-full{width:100%}.mx-auto{margin-left:auto;margin-right:auto}.overflow-hidden{overflow:hidden}'

# 8. Accessibility (always above fold)
CRITICAL_A11Y='.screen-reader-text{border:0;clip:rect(1px,1px,1px,1px);clip-path:inset(50%);height:1px;margin:-1px;overflow:hidden;padding:0;position:absolute!important;width:1px;word-wrap:normal!important}.skip-link{position:absolute;top:-40px;left:0;background:var(--accent-blue);color:#fff;padding:8px 16px;text-decoration:none;border-radius:var(--radius-sm);z-index:100}:focus-visible{outline:2px solid var(--accent-blue);outline-offset:2px}'

# 9. Shared responsive (nav breakpoint + typography)
CRITICAL_RESPONSIVE_MD='@media(min-width:768px){html{font-size:17px}h1{font-size:2.5rem}.container{padding:0 var(--spacing-lg)}.desktop-nav{display:flex}.mobile-menu-toggle,.mobile-menu,.mobile-menu-overlay{display:none!important}}'
CRITICAL_RESPONSIVE_LG='@media(min-width:1024px){html{font-size:18px}h1{font-size:3rem}.container{padding:0 var(--spacing-xl)}}'

# Shared base for all page types
SHARED_BASE="${CRITICAL_VARS}
${CRITICAL_RESET}
${CRITICAL_HTML}
${CRITICAL_BODY}
${CRITICAL_HEADINGS}
${CRITICAL_TEXT}
${CRITICAL_HEADER}
${CRITICAL_CONTAINER}"

# ============================================================
# Homepage critical CSS
# ============================================================
HOMEPAGE_SPECIFIC='.hero-section{min-height:100vh;display:flex;align-items:center;justify-content:center;position:relative;overflow:hidden;background-size:cover;background-position:center;background-repeat:no-repeat;background-color:var(--dark-bg)}'
HOMEPAGE_GRADIENT='.bg-gradient-to-b{background-image:linear-gradient(to bottom,rgba(18,18,20,.8),rgba(18,18,20,.7),#121214)}'
HOMEPAGE_HERO='.hero-headline{font-size:2.5rem;font-weight:700;color:rgba(255,255,255,.95);margin-bottom:1.5rem;line-height:1.15;max-width:800px;letter-spacing:-0.02em}.hero-subheadline{font-size:1.25rem;color:rgba(255,255,255,.7);margin-bottom:3rem;font-weight:400;max-width:600px;line-height:1.6}'
HOMEPAGE_CTA='.hero-cta-group{display:flex;flex-wrap:wrap;justify-content:center;gap:1rem;margin-top:0.5rem}.hero-cta-primary{display:inline-flex;align-items:center;gap:0.5rem;background-color:var(--primary-blue);color:#fff;padding:1rem 2rem;font-size:1.125rem;font-weight:600;border-radius:4px;text-decoration:none;letter-spacing:0.01em}.hero-cta-secondary{display:inline-flex;align-items:center;gap:0.5rem;background-color:transparent;color:rgba(255,255,255,.9);padding:1rem 2rem;font-size:1.125rem;font-weight:500;border:1px solid rgba(255,255,255,.3);border-radius:4px;text-decoration:none}'
HOMEPAGE_LOGO='.logo-bar-section{border-top:1px solid rgba(255,255,255,.05);border-bottom:1px solid rgba(255,255,255,.05)}.logo-bar-logo{height:2rem;width:auto;filter:brightness(0) invert(1);opacity:0.5;transition:opacity 0.3s ease,filter 0.3s ease}.logo-bar-logo:hover{opacity:1;filter:none}'
HOMEPAGE_BG='.hero-bg{background-image:url('"'"'https://braddaiber.com/wp-content/uploads/2024/03/shutterstock_134653565-1-768x512.webp'"'"')}@media(min-width:768px){.hero-bg{background-image:url('"'"'https://braddaiber.com/wp-content/uploads/2024/03/shutterstock_134653565-1-1024x683.webp'"'"')}}@media(min-width:1024px){.hero-bg{background-image:url('"'"'https://braddaiber.com/wp-content/uploads/2024/03/shutterstock_134653565-1-1536x1024.webp'"'"')}}@media(min-width:1536px){.hero-bg{background-image:url('"'"'https://braddaiber.com/wp-content/uploads/2024/03/shutterstock_134653565-1-scaled.webp'"'"')}}'
HOMEPAGE_RESPONSIVE='@media(min-width:768px){.hero-headline{font-size:3.5rem}.hero-subheadline{font-size:1.375rem}.logo-bar-logo{height:2.5rem}.desktop-nav{display:block}.mobile-menu-toggle{display:none}}@media(min-width:1024px){.hero-headline{font-size:4.25rem}.hero-subheadline{font-size:1.5rem}.hero-cta-primary,.hero-cta-secondary{padding:1.125rem 2.5rem}}'

cat > "$CRITICAL_DIR/homepage.css" << CSSEOF
/* Critical CSS — Homepage (auto-generated by generate-critical.sh) */
${SHARED_BASE}
${CRITICAL_UTILS}
${HOMEPAGE_SPECIFIC}
${HOMEPAGE_GRADIENT}
${HOMEPAGE_HERO}
${HOMEPAGE_CTA}
${HOMEPAGE_LOGO}
${HOMEPAGE_BG}
${HOMEPAGE_RESPONSIVE}
CSSEOF

# ============================================================
# Single Post critical CSS
# ============================================================
SINGLE_STRUCTURE='.site-main{flex:1;width:100%;background-color:var(--dark-bg)}.entry-content{width:100%;max-width:var(--content-default);margin:0 auto}'
SINGLE_HERO='.page-header{display:flex;flex-direction:column;align-items:center;justify-content:center;min-height:auto;padding:var(--spacing-lg) var(--spacing-md);background-color:var(--dark-300);position:relative;overflow:hidden}.hero-container{width:100%;margin-bottom:var(--spacing-xl);position:relative}'
SINGLE_SIDEBAR='.sidebar-container{position:sticky;top:100px;max-height:calc(100vh - 120px);overflow-y:auto}.post-sidebar{display:none}@media(min-width:1024px){.post-sidebar{display:block!important}}'
SINGLE_RESPONSIVE='@media(min-width:1024px){.entry-content{font-size:1.125rem}}'

cat > "$CRITICAL_DIR/single-post.css" << CSSEOF
/* Critical CSS — Single Post (auto-generated by generate-critical.sh) */
${SHARED_BASE}
${SINGLE_STRUCTURE}
${SINGLE_HERO}
${SINGLE_SIDEBAR}
${CRITICAL_UTILS}
${CRITICAL_A11Y}
${CRITICAL_RESPONSIVE_MD}
${CRITICAL_RESPONSIVE_LG}
${SINGLE_RESPONSIVE}
CSSEOF

# ============================================================
# Archive/Blog critical CSS
# ============================================================
ARCHIVE_STRUCTURE='.site-main{flex:1;width:100%;background-color:var(--dark-bg)}'
ARCHIVE_GRID='.grid{display:grid;gap:var(--spacing-md)}.grid-cols-1{grid-template-columns:repeat(1,1fr)}.rounded-xl{border-radius:var(--radius-lg)}'
ARCHIVE_RESPONSIVE='@media(min-width:768px){.md\:grid-cols-3{grid-template-columns:repeat(3,1fr)}}'
ARCHIVE_MOTION='@media(prefers-reduced-motion:reduce){*,*::before,*::after{animation-duration:0s!important;animation-iteration-count:1!important;scroll-behavior:auto!important}}'

cat > "$CRITICAL_DIR/archive.css" << CSSEOF
/* Critical CSS — Archive/Blog (auto-generated by generate-critical.sh) */
${SHARED_BASE}
${ARCHIVE_STRUCTURE}
${ARCHIVE_GRID}
${CRITICAL_UTILS}
${CRITICAL_A11Y}
${CRITICAL_RESPONSIVE_MD}
${ARCHIVE_RESPONSIVE}
${CRITICAL_RESPONSIVE_LG}
${ARCHIVE_MOTION}
CSSEOF

# ============================================================
# Report
# ============================================================
echo "Critical CSS generated:"
for f in homepage.css single-post.css archive.css; do
  size=$(wc -c < "$CRITICAL_DIR/$f" | tr -d ' ')
  echo "  $f: ${size} bytes"
done
echo ""
echo "Target: < 14KB each (fits in first TCP round-trip)"
