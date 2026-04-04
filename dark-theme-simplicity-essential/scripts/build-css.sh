#!/usr/bin/env bash
# Build production style.css from Tailwind v4 source.
#
# Compiles tailwind-input.css → post-processes to v3-compatible format → prepends WP header.
# Output: style.css (ready to deploy)
#
# Usage: ./scripts/build-css.sh
#        ./scripts/build-css.sh --dry-run   (writes to style-v4-preview.css instead)

set -euo pipefail
cd "$(dirname "$0")/.."

DRY_RUN=false
if [[ "${1:-}" == "--dry-run" ]]; then
  DRY_RUN=true
fi

# 1. Compile Tailwind v4
echo "Compiling Tailwind v4..."
./tailwindcss -i tailwind-input.css -o style-v4-raw.css 2>&1

# 2. Post-process
echo "Post-processing..."
node -e '
const fs = require("fs");
let css = fs.readFileSync("style-v4-raw.css", "utf8");

// --- Strip @layer wrappers (output must be flat/unlayered) ---
css = css.replace(/@layer properties;\n?/g, "");
css = css.replace(/@layer theme \{\n/g, "");
css = css.replace(/@layer utilities \{\n/g, "");
css = css.replace(/@layer properties \{\n/g, "");
css = css.replace(/^}\s*$/gm, "");

// Remove :host (web component selector not needed)
css = css.replace(/:root, :host/g, ":root");

// --- Convert logical properties to physical (LTR) ---
// Specific directional first
css = css.replace(/padding-inline-start/g, "padding-left");
css = css.replace(/padding-inline-end/g, "padding-right");
css = css.replace(/margin-inline-start/g, "margin-left");
css = css.replace(/margin-inline-end/g, "margin-right");
css = css.replace(/inset-inline-start/g, "left");
css = css.replace(/inset-inline-end/g, "right");
css = css.replace(/border-inline-start/g, "border-left");
css = css.replace(/border-inline-end/g, "border-right");
css = css.replace(/margin-block-start/g, "margin-top");
css = css.replace(/margin-block-end/g, "margin-bottom");
css = css.replace(/padding-block-start/g, "padding-top");
css = css.replace(/padding-block-end/g, "padding-bottom");
css = css.replace(/inset-block-start/g, "top");
css = css.replace(/inset-block-end/g, "bottom");
css = css.replace(/border-block-start/g, "border-top");
css = css.replace(/border-block-end/g, "border-bottom");

// Shorthand expansions
css = css.replace(/padding-inline: ([^;]+);/g, "padding-left: $1; padding-right: $1;");
css = css.replace(/padding-block: ([^;]+);/g, "padding-top: $1; padding-bottom: $1;");
css = css.replace(/margin-inline: auto;/g, "margin-left: auto; margin-right: auto;");
css = css.replace(/margin-inline: ([^;]+);/g, "margin-left: $1; margin-right: $1;");
css = css.replace(/margin-block: ([^;]+);/g, "margin-top: $1; margin-bottom: $1;");
css = css.replace(/inset-inline: ([^;]+);/g, "left: $1; right: $1;");
css = css.replace(/inset-block: ([^;]+);/g, "top: $1; bottom: $1;");
css = css.replace(/border-block-style: ([^;]+);/g, "border-top-style: $1; border-bottom-style: $1;");
css = css.replace(/border-block-width: ([^;]+);/g, "border-top-width: $1; border-bottom-width: $1;");
css = css.replace(/border-inline-style: ([^;]+);/g, "border-left-style: $1; border-right-style: $1;");
css = css.replace(/border-inline-width: ([^;]+);/g, "border-left-width: $1; border-right-width: $1;");

// --- Resolve calc(var(--spacing) * N) to fixed rem values ---
const spacingMap = {
  "0":"0px","0.5":"0.125rem","1":"0.25rem","2":"0.5rem","2.5":"0.625rem",
  "3":"0.75rem","4":"1rem","5":"1.25rem","6":"1.5rem","7":"1.75rem",
  "8":"2rem","10":"2.5rem","11":"2.75rem","12":"3rem","14":"3.5rem",
  "16":"4rem","20":"5rem","24":"6rem","28":"7rem","32":"8rem","36":"9rem",
  "40":"10rem","44":"11rem","48":"12rem","52":"13rem","56":"14rem",
  "64":"16rem","80":"20rem","96":"24rem",
  "-1":"-0.25rem","-2":"-0.5rem","-3":"-0.75rem","-4":"-1rem",
  "-6":"-1.5rem","-8":"-2rem","-12":"-3rem"
};
css = css.replace(/calc\(var\(--spacing\) \* (-?[\d.]+)\)/g, (m, n) => spacingMap[n] || m);
css = css.replace(/var\(--spacing\)/g, "0.25rem");

// --- Convert color-mix() to rgba() ---
css = css.replace(/color-mix\(in srgb, (#[0-9a-fA-F]{3,8}) (\d+)%, transparent\)/g, (m, hex, pct) => {
  let h = hex.length <= 4 ? hex.slice(1).split("").map(c => c + c).join("") : hex.slice(1);
  const r = parseInt(h.slice(0, 2), 16);
  const g = parseInt(h.slice(2, 4), 16);
  const b = parseInt(h.slice(4, 6), 16);
  return "rgba(" + r + "," + g + "," + b + "," + (parseInt(pct) / 100) + ")";
});
// Remove @supports blocks for color-mix (already converted)
css = css.replace(/\s*@supports \(color: color-mix\(in lab, red, red\)\) \{\n[^}]*\}\n/g, "\n");

// --- Remove oklab from gradient positions ---
css = css.replace(/ in oklab/g, "");

// --- Inject v3 preflight resets after :root block ---
const rootEnd = css.indexOf("}", css.indexOf(":root {")) + 1;
const preflight = `
/* Preflight resets (matches v3 compiled output) */
h1,h2,h3,h4,h5,h6,hr,p,pre{margin:0}
h1,h2,h3,h4,h5,h6{font-size:inherit;font-weight:inherit}
a{color:inherit;text-decoration:inherit}
ol,ul,menu{list-style:none;margin:0;padding:0}
img,svg,video,canvas,audio,iframe,embed,object{display:block;vertical-align:middle}
img,video{max-width:100%;height:auto}
`;
css = css.slice(0, rootEnd) + preflight + css.slice(rootEnd);

// --- Clean up ---
css = css.replace(/\n{3,}/g, "\n\n");

fs.writeFileSync("style-v4-processed.css", css);
console.log("  Post-processed:", css.length, "bytes");
'

# 3. Prepend WP theme header
echo "Adding WP header..."
cat > /tmp/wp-header.txt << 'HEADER'
/*
Theme Name: Dark Theme Simplicity
Theme URI: https://github.com/wbdaiber/dark-theme-simplicity
Author: William Daiber
Author URI: https://github.com/wbdaiber
Description: A sleek, modern dark theme with customizable sections for service cards, benefits, and approach sections.
Version: 3.2
Requires at least: 5.9
Tested up to: 6.4
Requires PHP: 7.4
License: GNU General Public License v2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Text Domain: dark-theme-simplicity-3
Tags: dark, custom-colors, custom-menu, custom-logo, editor-style, featured-images, footer-widgets, theme-options
*/

HEADER

if $DRY_RUN; then
  cat /tmp/wp-header.txt style-v4-processed.css > style-v4-preview.css
  echo "Dry run complete: style-v4-preview.css ($(wc -c < style-v4-preview.css) bytes)"
  echo "Diff against production: diff style.css style-v4-preview.css"
else
  cat /tmp/wp-header.txt style-v4-processed.css > style.css
  echo "Built: style.css ($(wc -c < style.css) bytes)"
fi

# 4. Clean up intermediate files
rm -f style-v4-raw.css style-v4-processed.css

echo "Done. Remember to deploy + purge cache + verify visually."
