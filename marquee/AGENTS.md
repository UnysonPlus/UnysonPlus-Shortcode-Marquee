---
type: shortcode
name: marquee
provides: simple-element
distribution: installable add-on (not bundled in core)
---

# Marquee

A horizontally scrolling row that loops seamlessly. Each item is **text**
(solid/outline), an **image**, or an **icon**, separated by an optional glyph.
The loop is pure CSS; an optional "React to Scroll" mode nudges the
speed/direction with scroll velocity via a small vanilla-JS enhancement. The
wrapper supports the **Animations** tab.

Installable add-on shortcode (its own repo, installed from the Shortcodes
screen). NOT bundled in core.

## Forward-compatibility contract (do not break this)

Each item ALWAYS carries `kind` (`text`|`image`|`icon`, default `text`) and the
STABLE `text` key. New capabilities are added ONLY by introducing a new `kind`
value and/or new OPTIONAL keys — never by renaming/repurposing `text`/`kind` or
making an existing field required. `views/view.php` (`sc_mq_item_kind`) resolves
an unknown/missing `kind` text-first, so items saved by any older OR newer
version still render their words. Pre-`kind` saves (text-only) are unaffected.

## Registration

Auto-registered from `config.php` (simple content element; no class file).
Installed to `wp-content/uploads/unysonplus-shortcodes/marquee/`.

## Options schema (atts)

| Att | Type | Default | Notes |
|-----|------|---------|-------|
| `items` | addable-popup[] | `[]` | Each: `kind` (`text`/`image`/`icon`, default `text`), `text` (stable; also alt/aria for image+icon), `style` (`solid`/`outline`, text only), `image` (upload), `icon` (icon-v2) |
| `separator` | text | `·` | Glyph between items; blank for none |
| `icon_size` | slider | `28` | px; Icon items (CSS var `--mq-icon-size`) |
| `image_height` | slider | `44` | px; Image items (CSS var `--mq-img-h`) |
| `direction` | select | `left` | `left` (R→L) / `right` (L→R) |
| `speed` | select | `normal` | `slow` / `normal` / `fast` |
| `pause_on_hover` | switch | `yes` | |
| `velocity` | switch | `no` | React to scroll velocity (adds `data-mq-velocity="1"`) |
| `size` | select | `lg` | `md` / `lg` / `xl` |
| `gap` | slider | `48` | px between items (CSS var `--mq-gap`) |
| `text_color` / `accent_color` | compact color | `''` | `--mq-text` / `--mq-accent` (outline stroke + separator) |
| `font_size_preset`, `spacing`, `animation`/`gsap_motion` | | | Standard |

## Rendering

`views/view.php` outputs `.fw-mq > .fw-mq__track`, with one set of
`.fw-mq__item` (+ `.fw-mq__sep`) repeated **twice** so a CSS `translateX(-50%)`
loop is seamless. Spacing is per-item `margin-right` (uniform across the wrap).
Item HTML is built by `sc_mq_item_html()` per resolved kind: text →
`.fw-mq__item--solid/--outline`; image → `.fw-mq__item--image > img`
(`--mq-img-h`); icon → `.fw-mq__item--icon` holding `sc_mq_icon_html()` output
(`.fw-mq__icon-font` `<i>` or `.fw-mq__icon-img` `<img>`, `--mq-icon-size`). When
an icon-font item is present the view enqueues the icon-v2 pack CSS (like
icon-box). `static/css/styles.css` runs the loop; `static/js/scripts.js` only
matters when `velocity` is on. `static.php` resolves its URL from `__FILE__`
against uploads.

## Pitfalls

- The track repeats the item set twice; keep spacing on the items (margin), not
  a flex `gap`, or the wrap point won't be seamless.
- Outline items use `-webkit-text-stroke`; ensure `--mq-accent` has contrast.

## Files

- `config.php`, `options.php`, `static.php`, `views/view.php`
- `static/css/styles.css`, `static/js/scripts.js`, `static/img/page_builder.svg`
