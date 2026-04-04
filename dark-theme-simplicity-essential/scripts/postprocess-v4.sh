#!/usr/bin/env bash
# Post-process Tailwind v4 CSS output to produce v3-compatible format.
#
# Converts:
#   1. Strips @layer wrappers — outputs flat, unlayered CSS (matching v3)
#   2. Inlines @layer theme :root vars as plain :root block
#   3. Logical properties → physical properties (LTR assumed)
#   4. calc(var(--spacing) * N) → fixed rem values (--spacing = 0.25rem)
#   5. color-mix() → rgba() for opacity modifiers
#
# Usage: ./scripts/postprocess-v4.sh style-v4-raw.css > style-v4-processed.css

set -euo pipefail

INPUT="${1:?Usage: $0 <input.css>}"

# Use a node script for the complex transformations (layer stripping + color-mix)
node -e '
const fs = require("fs");
let css = fs.readFileSync(process.argv[1], "utf8");

// --- Strip @layer wrappers, keeping content ---
// Remove @layer properties; declaration
css = css.replace(/@layer properties;\n?/g, "");

// Unwrap @layer theme { ... } — keep the content
css = css.replace(/@layer theme \{[\n\r]/g, "");

// Unwrap @layer utilities { ... } — keep the content
css = css.replace(/@layer utilities \{[\n\r]/g, "");

// Unwrap @layer properties { ... } — keep the content
css = css.replace(/@layer properties \{[\n\r]/g, "");

// Remove closing braces that were @layer closers
// These are lines with just "}" at the end of each layer block
// We need to be smart: remove the LAST "}" that closes each layer
// Strategy: remove lines that are just "}" at column 0 (layer closers)
// vs indented "}" (rule closers)
css = css.replace(/^}\s*$/gm, "/* layer-end */");

// Count how many layer-end markers we have (should be 3: theme, utilities, properties)
const layerEnds = (css.match(/\/\* layer-end \*\//g) || []).length;
// Remove them (they are the @layer closing braces)
css = css.replace(/\/\* layer-end \*\/\n?/g, "");

// Remove ":host" from ":root, :host" selectors (v4 adds :host for web components)
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

// --- Resolve calc(var(--spacing) * N) to fixed rem ---
const spacingMap = {
  "0": "0px", "0.5": "0.125rem", "1": "0.25rem", "2": "0.5rem",
  "2.5": "0.625rem", "3": "0.75rem", "4": "1rem", "5": "1.25rem",
  "6": "1.5rem", "7": "1.75rem", "8": "2rem", "10": "2.5rem",
  "11": "2.75rem", "12": "3rem", "14": "3.5rem", "16": "4rem",
  "20": "5rem", "24": "6rem", "28": "7rem", "32": "8rem",
  "36": "9rem", "40": "10rem", "44": "11rem", "48": "12rem",
  "52": "13rem", "56": "14rem", "64": "16rem", "80": "20rem", "96": "24rem",
  "-1": "-0.25rem", "-2": "-0.5rem", "-3": "-0.75rem", "-4": "-1rem",
  "-6": "-1.5rem", "-8": "-2rem", "-12": "-3rem",
};
css = css.replace(/calc\(var\(--spacing\) \* (-?[\d.]+)\)/g, (match, n) => {
  return spacingMap[n] || match;
});
css = css.replace(/var\(--spacing\)/g, "0.25rem");

// --- Convert color-mix() to rgba() ---
css = css.replace(/color-mix\(in srgb, (#[0-9a-fA-F]{6}) (\d+)%, transparent\)/g, (match, hex, pct) => {
  const r = parseInt(hex.slice(1, 3), 16);
  const g = parseInt(hex.slice(3, 5), 16);
  const b = parseInt(hex.slice(5, 7), 16);
  const a = parseInt(pct) / 100;
  return `rgba(${r},${g},${b},${a})`;
});

// Also handle the @supports block for color-mix (remove it since we replaced above)
css = css.replace(/\s*@supports \(color: color-mix\(in lab, red, red\)\) \{[^}]*\}\s*/g, "\\n");

// --- Clean up extra blank lines ---
css = css.replace(/\n{3,}/g, "\\n\\n");

process.stdout.write(css);
' "$INPUT"
