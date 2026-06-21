<?php if ( ! defined( 'FW' ) ) {
	die( 'Forbidden' );
}

/**
 * Frontend assets. Installed into the update-safe uploads dir
 * (wp-content/uploads/unysonplus-shortcodes/<name>/), so resolve our own URL
 * from __FILE__ against the uploads base (static.php runs isolated — no $this).
 */

$dir  = wp_normalize_path( dirname( __FILE__ ) );
$up   = wp_upload_dir();
$base = isset( $up['basedir'] ) ? wp_normalize_path( $up['basedir'] ) : '';
$uri  = ( $base !== '' && strpos( $dir, $base ) === 0 )
	? rtrim( $up['baseurl'], '/' ) . substr( $dir, strlen( $base ) )
	: plugins_url( '', __FILE__ );

$ver = '1.0.0';

wp_enqueue_style( 'fw-sc-marquee', $uri . '/static/css/styles.css', array(), $ver );

// JS only matters for the optional scroll-velocity reaction; load it lazily.
wp_enqueue_script( 'fw-sc-marquee', $uri . '/static/js/scripts.js', array(), $ver, true );
