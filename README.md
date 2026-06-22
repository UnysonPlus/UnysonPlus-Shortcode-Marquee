# Marquee — UnysonPlus shortcode

An add-on element for the [UnysonPlus](https://github.com/UnysonPlus) page
builder: a horizontally scrolling row that loops seamlessly. Each item can be
**text** (solid or outline), an **image**, or an **icon**, with an optional
separator glyph and an optional reaction to scroll velocity. The loop is pure
CSS; the velocity reaction is a small vanilla-JS enhancement.

This is **not** bundled in core — it installs on demand.

## Install

In WordPress: **Unyson+ → Extensions → Shortcodes**, then either:

- **From GitHub** — paste this repo's URL (`https://github.com/UnysonPlus/UnysonPlus-Shortcode-Marquee`) and click *Download & install*, or
- **Upload a .zip** — download this repo as a zip and upload it.

It installs into `wp-content/uploads/unysonplus-shortcodes/` (update-safe) and is
enabled automatically. You'll then find **Marquee** under the **Content** tab in
the page builder.

## Use

Add items — each is **Text** (solid/outline), an **Image**, or an **Icon** —
set a separator glyph, then pick a direction, speed and gap, and optionally turn
on *Pause on Hover* / *React to Scroll*. Icon size and image height live on the
**Styling** tab. The **Animations** tab still applies.

Mixing types lets one element do, say, `WORD · [icon] · WORD · [logo]` in a
single seamless loop.

## License

GPL-2.0-or-later.
