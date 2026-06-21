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

		$var = function ( $key, $name ) use ( $atts ) {
			$raw = sc_mq_get( $key, $atts, '' );
			if ( is_array( $raw ) && ! empty( $raw['custom'] ) ) {
				$c = preg_replace( '/[^#0-9a-zA-Z(),.%\s-]/', '', (string) $raw['custom'] );
				if ( $c !== '' ) { return $name . ':' . $c . ';'; }
			}
			return '';
		};
		$style_var  = '--mq-gap:' . $gap . 'px;';
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
			$text  = esc_html( trim( (string) ( isset( $it['text'] ) ? $it['text'] : '' ) ) );
			if ( $text === '' ) { continue; }
			$st    = ( isset( $it['style'] ) && $it['style'] === 'outline' ) ? 'outline' : 'solid';
			$set  .= '<span class="fw-mq__item fw-mq__item--' . $st . '">' . $text . '</span>';
			if ( $sep !== '' ) {
				$set .= '<span class="fw-mq__sep">' . esc_html( $sep ) . '</span>';
			}
		}
		if ( $set === '' ) { return ''; }

		return '<div ' . $attr_html . '><div class="fw-mq__track">' . $set . $set . '</div></div>';
	}
}

echo sc_mq_render( $atts );
