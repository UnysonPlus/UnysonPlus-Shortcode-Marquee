<?php if ( ! defined( 'FW' ) ) {
	die( 'Forbidden' );
}

/**
 * Marquee — an installable UnysonPlus shortcode (add-on, not bundled in core).
 * Install from the Shortcodes screen: paste the GitHub repo URL, or upload the zip.
 */

$cfg = array();

$cfg['page_builder'] = array(
	'title'       => __( 'Marquee', 'fw' ),
	'description' => __( 'A seamlessly looping marquee / ticker of text, images and icons, with solid + outline text, an optional separator, and a reaction to scroll velocity.', 'fw' ),
	'tab'         => __( 'Content', 'fw' ),
	'popup_size'  => 'large',

	'title_template' => '
		{{ if ( o && o["items"] && o["items"].length ) { }}
			<div style="margin:.4rem 0 0;font-weight:700;">
				{{ for ( var i = 0; i < Math.min(o["items"].length,4); i++ ) {
					var it = o["items"][i];
					var label = it && it.text ? it.text : ( it && it.kind === "image" ? "[image]" : ( it && it.kind === "icon" ? "[icon]" : "" ) );
				}}
					{{- label }}{{ if ( i < Math.min(o["items"].length,4)-1 ) { }} · {{ } }}
				{{ } }}
			</div>
		{{ } else { }}
			<em>No items added</em>
		{{ } }}
	',
);
