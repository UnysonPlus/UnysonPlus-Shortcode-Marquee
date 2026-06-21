<?php if ( ! defined( 'FW' ) ) {
	die( 'Forbidden' );
}

$options = array(

	/* ========================== CONTENT ========================== */
	'tab_content' => array(
		'title'   => __( 'Content', 'fw' ),
		'type'    => 'tab',
		'options' => array(
			'group' => array(
				'type'    => 'group',
				'options' => array(
					'items' => array(
						'type'          => 'addable-popup',
						'label'         => __( 'Items', 'fw' ),
						'popup-title'   => __( 'Add / Edit Item', 'fw' ),
						'template'      => '{{= text || "Item" }}',
						'popup-options' => array(
							'text' => array(
								'type'  => 'text',
								'label' => __( 'Text', 'fw' ),
								'value' => __( 'Scrolling text', 'fw' ),
							),
							'style' => array(
								'type'    => 'select',
								'label'   => __( 'Style', 'fw' ),
								'value'   => 'solid',
								'choices' => array(
									'solid'   => __( 'Solid', 'fw' ),
									'outline' => __( 'Outline', 'fw' ),
								),
							),
						),
					),
					'separator' => array(
						'type'  => 'text',
						'label' => __( 'Separator', 'fw' ),
						'desc'  => __( 'Optional glyph shown between items, e.g. · / ✳ / — . Leave blank for none.', 'fw' ),
						'value' => '·',
					),
				),
			),
		),
	),

	/* ========================== DESIGN ========================== */
	'tab_design' => array(
		'title'   => __( 'Design', 'fw' ),
		'type'    => 'tab',
		'options' => array(
			'group_motion' => array(
				'type'    => 'group',
				'options' => array(
					'direction' => array(
						'type'    => 'select',
						'label'   => __( 'Direction', 'fw' ),
						'value'   => 'left',
						'choices' => array(
							'left'  => __( 'Right → Left', 'fw' ),
							'right' => __( 'Left → Right', 'fw' ),
						),
					),
					'speed' => array(
						'type'    => 'select',
						'label'   => __( 'Speed', 'fw' ),
						'value'   => 'normal',
						'choices' => array(
							'slow'   => __( 'Slow', 'fw' ),
							'normal' => __( 'Normal', 'fw' ),
							'fast'   => __( 'Fast', 'fw' ),
						),
					),
					'pause_on_hover' => array(
						'type'         => 'switch',
						'label'        => __( 'Pause on Hover', 'fw' ),
						'right-choice' => array( 'value' => 'yes', 'label' => __( 'Yes', 'fw' ) ),
						'left-choice'  => array( 'value' => 'no',  'label' => __( 'No', 'fw' ) ),
						'value'        => 'yes',
					),
					'velocity' => array(
						'type'         => 'switch',
						'label'        => __( 'React to Scroll', 'fw' ),
						'desc'         => __( 'Speed up and follow the direction of scrolling. Falls back to a steady loop.', 'fw' ),
						'right-choice' => array( 'value' => 'yes', 'label' => __( 'Yes', 'fw' ) ),
						'left-choice'  => array( 'value' => 'no',  'label' => __( 'No', 'fw' ) ),
						'value'        => 'no',
					),
				),
			),
			'group_size' => array(
				'type'    => 'group',
				'options' => array(
					'size' => array(
						'type'    => 'select',
						'label'   => __( 'Text Size', 'fw' ),
						'value'   => 'lg',
						'choices' => array(
							'md' => __( 'Medium', 'fw' ),
							'lg' => __( 'Large', 'fw' ),
							'xl' => __( 'Extra Large', 'fw' ),
						),
					),
					'gap' => array(
						'type'       => 'slider',
						'label'      => __( 'Gap (px)', 'fw' ),
						'value'      => 48,
						'properties' => array( 'min' => 8, 'max' => 120, 'step' => 4 ),
					),
				),
			),
		),
	),

	/* ========================== STYLING ========================== */
	'tab_styling' => array(
		'title'   => __( 'Styling', 'fw' ),
		'type'    => 'tab',
		'options' => array(
			'group_colors' => array(
				'type'    => 'group',
				'options' => array(
					'text_color'   => function_exists( 'sc_color_field_compact' ) ? sc_color_field_compact( array( 'label' => __( 'Text Color', 'fw' ) ) ) : array( 'type' => 'color-picker', 'label' => __( 'Text Color', 'fw' ), 'value' => '' ),
					'accent_color' => function_exists( 'sc_color_field_compact' ) ? sc_color_field_compact( array( 'label' => __( 'Outline & Separator Color', 'fw' ) ) ) : array( 'type' => 'color-picker', 'label' => __( 'Outline & Separator Color', 'fw' ), 'value' => '' ),
					'font_size_preset' => function_exists( 'sc_font_size_field' ) ? sc_font_size_field() : array( 'type' => 'select', 'label' => __( 'Font Size', 'fw' ), 'choices' => array( '' => __( 'Default', 'fw' ) ) ),
				),
			),
			'group_spacings' => array(
				'type'    => 'group',
				'options' => array(
					'spacing' => array(
						'type'  => 'spacing',
						'label' => __( 'Margin & Padding', 'fw' ),
						'help'  => function_exists( 'sc_styling_help_text' ) ? sc_styling_help_text( 'spacing' ) : '',
					),
				),
			),
		),
	),

	'tab_animation' => array(
		'title'   => __( 'Animations', 'fw' ),
		'type'    => 'tab',
		'options' => function_exists( 'sc_get_animation_fields' ) ? sc_get_animation_fields() : array(),
	),
	'tab_advanced' => array(
		'title'   => __( 'Advanced', 'fw' ),
		'type'    => 'tab',
		'options' => array(
			'advanced_settings' => array(
				'type'    => 'group',
				'options' => function_exists( 'sc_get_advanced_tab' ) ? sc_get_advanced_tab() : array(),
			),
		),
	),
);
