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
	'description' => __( 'A horizontally scrolling row of words that loops seamlessly, with solid + outline items and an optional reaction to scroll velocity.', 'fw' ),
	'tab'         => __( 'Content', 'fw' ),
	'popup_size'  => 'large',

	'title_template' => '
		{{ if ( o && o["items"] && o["items"].length ) { }}
			<div style="margin:.4rem 0 0;font-weight:700;">
				{{ for ( var i = 0; i < Math.min(o["items"].length,4); i++ ) { }}
					{{- o["items"][i].text || "" }}{{ if ( i < Math.min(o["items"].length,4)-1 ) { }} · {{ } }}
				{{ } }}
			</div>
		{{ } else { }}
			<em>No items added</em>
		{{ } }}
	',
);
