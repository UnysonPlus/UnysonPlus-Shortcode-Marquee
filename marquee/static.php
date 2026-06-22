<?php if ( ! defined( 'FW' ) ) {
	die( 'Forbidden' );
}

/**
 * Frontend assets. This shortcode may live in EITHER the update-safe uploads dir
 * (installed via the Shortcodes screen) OR a theme's framework-customizations
 * (bundled by a theme). static.php runs isolated (no $this / extension path), so
 * resolve our own URL by mapping __DIR__ against the known WordPress roots.
 */

if ( ! function_exists( 'fw_sc_mq_dir_to_uri' ) ) {
	function fw_sc_mq_dir_to_uri( $dir ) {
		$dir  = wp_normalize_path( $dir );
		$maps = array();
		$up   = wp_upload_dir();
		if ( ! empty( $up['basedir'] ) ) { $maps[] = array( wp_normalize_path( $up['basedir'] ), rtrim( $up['baseurl'], '/' ) ); }
		$maps[] = array( wp_normalize_path( get_stylesheet_directory() ), rtrim( get_stylesheet_directory_uri(), '/' ) );
		$maps[] = array( wp_normalize_path( get_template_directory() ), rtrim( get_template_directory_uri(), '/' ) );
		if ( defined( 'WP_PLUGIN_DIR' ) ) { $maps[] = array( wp_normalize_path( WP_PLUGIN_DIR ), rtrim( plugins_url(), '/' ) ); }
		foreach ( $maps as $m ) {
			if ( $m[0] !== '' && strpos( $dir, $m[0] ) === 0 ) { return $m[1] . substr( $dir, strlen( $m[0] ) ); }
		}
		return rtrim( content_url(), '/' );
	}
}

$uri = fw_sc_mq_dir_to_uri( dirname( __FILE__ ) );
$ver = '1.0.2';

wp_enqueue_style( 'fw-sc-marquee', $uri . '/static/css/styles.css', array(), $ver );
// JS only matters for the optional scroll-velocity reaction; load it lazily.
wp_enqueue_script( 'fw-sc-marquee', $uri . '/static/js/scripts.js', array(), $ver, true );
