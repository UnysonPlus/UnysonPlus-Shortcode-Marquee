<?php if ( ! defined( 'FW' ) ) {
	die( 'Forbidden' );
}

/** @var array $atts */

if ( ! function_exists( 'sc_mq_get' ) ) {
	function sc_mq_get( $path, $atts, $default = '' ) {
		if ( function_exists( 'fw_akg' ) ) {
			$v = fw_akg( $path, $atts, null );
			if ( $v !== null ) { return $v; }
		}
		return isset( $atts[ $path ] ) ? $atts[ $path ] : $default;
	}
}

/**
 * Resolve an item's kind in a FORWARD-COMPATIBLE way.
 *
 * Items saved before `kind` existed have no `kind` key → they resolve to
 * 'text', so their words keep rendering exactly as before. Likewise, a `kind`
 * value written by some FUTURE version that this (older) renderer doesn't know
 * degrades gracefully: it shows the item's text if there is any, otherwise an
 * image/icon if present. The upshot: text content is never lost across versions.
 */
if ( ! function_exists( 'sc_mq_item_kind' ) ) {
	function sc_mq_item_kind( $it ) {
		$known = array( 'text', 'image', 'icon' );
		$kind  = isset( $it['kind'] ) ? (string) $it['kind'] : '';
		if ( in_array( $kind, $known, true ) ) {
			return $kind;
		}
		// Unknown / missing → infer from the data we do understand, text-first.
		if ( isset( $it['text'] ) && trim( (string) $it['text'] ) !== '' ) { return 'text'; }
		if ( ! empty( $it['image']['url'] ) ) { return 'image'; }
		if ( ! empty( $it['icon'] ) ) { return 'icon'; }
		return 'text';
	}
}

/** Render an icon-v2 value (font icon or uploaded image) to safe inline markup. */
if ( ! function_exists( 'sc_mq_icon_html' ) ) {
	function sc_mq_icon_html( $icon, $alt = '' ) {
		if ( ! is_array( $icon ) || empty( $icon['type'] ) ) { return ''; }
		if ( $icon['type'] === 'icon-font' && ! empty( $icon['icon-class'] ) ) {
			return '<i class="fw-mq__icon-font ' . esc_attr( $icon['icon-class'] ) . '" aria-hidden="true"></i>';
		}
		if ( $icon['type'] === 'custom-upload' && ! empty( $icon['url'] ) ) {
			return '<img class="fw-mq__icon-img" src="' . esc_url( $icon['url'] ) . '" alt="' . esc_attr( $alt ) . '" loading="lazy" decoding="async" />';
		}
		return '';
	}
}

/** Build one item's inner HTML for the given kind. Returns '' to skip the item. */
if ( ! function_exists( 'sc_mq_item_html' ) ) {
	function sc_mq_item_html( $it, $kind ) {
		$text = trim( (string) ( isset( $it['text'] ) ? $it['text'] : '' ) );

		if ( $kind === 'image' && ! empty( $it['image']['url'] ) ) {
			return '<span class="fw-mq__item fw-mq__item--image">'
				. '<img src="' . esc_url( $it['image']['url'] ) . '" alt="' . esc_attr( $text ) . '" loading="lazy" decoding="async" />'
				. '</span>';
		}

		if ( $kind === 'icon' ) {
			$icon = sc_mq_icon_html( isset( $it['icon'] ) ? $it['icon'] : null, $text );
			if ( $icon !== '' ) {
				return '<span class="fw-mq__item fw-mq__item--icon">' . $icon . '</span>';
			}
			// Icon kind with no usable icon → fall back to text so nothing vanishes.
		}

		// Default: text (also the fallback for image/icon items with no media).
		if ( $text === '' ) { return ''; }
		$st = ( isset( $it['style'] ) && $it['style'] === 'outline' ) ? 'outline' : 'solid';
		return '<span class="fw-mq__item fw-mq__item--' . $st . '">' . esc_html( $text ) . '</span>';
	}
}

if ( ! function_exists( 'sc_mq_render' ) ) {
	function sc_mq_render( $atts ) {
		$items = sc_mq_get( 'items', $atts, array() );
		if ( ! is_array( $items ) || empty( $items ) ) {
			if ( is_admin() || ( defined( 'DOING_AJAX' ) && DOING_AJAX ) ) {
				return '<div class="fw-mq__empty">' . esc_html__( 'Add at least one item.', 'fw' ) . '</div>';
			}
			return '';
		}

		$dir   = sc_mq_get( 'direction', $atts, 'left' ) === 'right' ? 'right' : 'left';
		$speed = sanitize_html_class( (string) sc_mq_get( 'speed', $atts, 'normal' ) );
		$size  = sanitize_html_class( (string) sc_mq_get( 'size', $atts, 'lg' ) );
		$pause = sc_mq_get( 'pause_on_hover', $atts, 'yes' ) === 'yes';
		$velo  = sc_mq_get( 'velocity', $atts, 'no' ) === 'yes';
		$sep   = trim( (string) sc_mq_get( 'separator', $atts, '·' ) );
		$gap   = (int) sc_mq_get( 'gap', $atts, 48 );
		if ( $gap < 0 ) { $gap = 0; }
		$icon_size = (int) sc_mq_get( 'icon_size', $atts, 28 );
		$img_h     = (int) sc_mq_get( 'image_height', $atts, 44 );

		// Enqueue the icon-font pack CSS only when an icon item is actually used
		// (mirrors icon-box). Cheap scan; bails the moment it finds one.
		$has_icon = false;
		foreach ( $items as $it ) {
			if ( sc_mq_item_kind( $it ) === 'icon' && ! empty( $it['icon']['type'] ) && $it['icon']['type'] === 'icon-font' ) {
				$has_icon = true;
				break;
			}
		}
		if ( $has_icon && function_exists( 'fw' ) && fw()->backend->option_type( 'icon-v2' ) && isset( fw()->backend->option_type( 'icon-v2' )->packs_loader ) ) {
			fw()->backend->option_type( 'icon-v2' )->packs_loader->enqueue_frontend_css();
		}

		$var = function ( $key, $name ) use ( $atts ) {
			$raw = sc_mq_get( $key, $atts, '' );
			if ( is_array( $raw ) && ! empty( $raw['custom'] ) ) {
				$c = preg_replace( '/[^#0-9a-zA-Z(),.%\s-]/', '', (string) $raw['custom'] );
				if ( $c !== '' ) { return $name . ':' . $c . ';'; }
			}
			return '';
		};
		$style_var  = '--mq-gap:' . $gap . 'px;';
		$style_var .= '--mq-icon-size:' . max( 1, $icon_size ) . 'px;';
		$style_var .= '--mq-img-h:' . max( 1, $img_h ) . 'px;';
		$style_var .= $var( 'text_color', '--mq-text' );
		$style_var .= $var( 'accent_color', '--mq-accent' );

		$classes = array( 'fw-mq', 'fw-mq--dir-' . $dir, 'fw-mq--speed-' . $speed, 'fw-mq--size-' . $size );
		if ( $pause ) { $classes[] = 'fw-mq--pause'; }

		$atts['base_class']       = 'marquee';
		$atts['unique_id_prefix'] = 'mq-';
		$atts['css_class']        = trim( implode( ' ', $classes ) . ' ' . ( isset( $atts['css_class'] ) ? $atts['css_class'] : '' ) );

		if ( function_exists( 'sc_build_wrapper_attr' ) ) {
			$attr = sc_build_wrapper_attr( $atts );
		} else {
			$attr = array( 'class' => esc_attr( $atts['css_class'] ) );
		}
		$attr['style'] = ( isset( $attr['style'] ) && $attr['style'] !== '' ? rtrim( $attr['style'], ';' ) . ';' : '' ) . $style_var;
		$attr['aria-hidden'] = 'true';
		if ( $velo ) { $attr['data-mq-velocity'] = '1'; }
		$attr_html = function_exists( 'fw_attr_to_html' ) ? fw_attr_to_html( $attr ) : '';

		// One set of items (with separators); the track repeats it twice for a
		// seamless -50% loop.
		$set = '';
		foreach ( $items as $it ) {
			if ( ! is_array( $it ) ) { continue; }
			$html = sc_mq_item_html( $it, sc_mq_item_kind( $it ) );
			if ( $html === '' ) { continue; }
			$set .= $html;
			if ( $sep !== '' ) {
				$set .= '<span class="fw-mq__sep">' . esc_html( $sep ) . '</span>';
			}
		}
		if ( $set === '' ) { return ''; }

		return '<div ' . $attr_html . '><div class="fw-mq__track">' . $set . $set . '</div></div>';
	}
}

echo sc_mq_render( $atts );
