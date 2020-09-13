<?php
/*
* Followig class handling variation matrix
*/


/* 
**========== Direct access not allowed =========== 
*/
if( ! defined('ABSPATH' ) ){ exit; }


class NM_PDF_PREVIEW_wooproduct extends PPOM_Inputs{
	
	/*
	 * input control settings
	 */
	var $title, $desc, $settings, $currency, $format;
	
	/*
	 * this var is pouplated with current plugin meta
	*/
	var $plugin_meta;
	
	function __construct(){
		
		$this -> plugin_meta = ppom_get_plugin_meta();
		
		$this -> title 		= __ ( 'PDF Preview', 'ppom' );
		$this -> settings	= self::get_settings();
		$this -> icon		= __ ( '<i class="fa fa-list-alt" aria-hidden="true"></i>', 'ppom' );
		
	}
	
	
	private function get_settings(){
		
		return array (
			'title' => array (
					'type' => 'text',
					'title' => __ ( 'Titles', 'ppom' ),
					'desc' => __ ( 'It will as section heading wrapped in h2.', 'ppom' )
			),
			'data_name' => array (
					'type' => 'text',
					'title' => __ ( 'Data name', 'ppom' ),
					'desc' => __ ( 'REQUIRED: The identification name of this field, that you can insert into body email configuration. Note:Use only lowercase characters and underscores.', 'ppom' ) 
			),
			'description' => array (
					'type' => 'textarea',
					'title' => __ ( 'Description', 'ppom' ),
					'desc' => __ ( 'Type description, it will be display under section heading.', 'ppom' )
			),
			'width' => array (
				'type'    => 'select',
				'title'   => __ ( 'Width', 'ppom' ),
				'desc'    => __ ( 'Select width column.', 'ppom'),
				'options' => ppom_get_input_cols(),
				'default' => 12,
			),
			'options' => array (
					'type' => 'paired-quantity',
					'title' => __ ( 'Colunm Options', 'ppom' ),
					'desc' => __ ( 'Add variation matrix colunm options', 'ppom' )
			),
			'row_options' => array (
					'type' => 'textarea',
					'title' => __ ( 'Row Options', 'ppom' ),
					'desc' => __ ( 'Add variation matrix row options per line', 'ppom' )
			),
			'visibility' => array (
					'type' => 'select',
					'title' => __ ( 'Visibility', 'ppom' ),
					'desc' => __ ( 'Set field visibility based on user.', 'ppom'),
					'options'	=> ppom_field_visibility_options(),
					'default'	=> 'everyone',
			),
			'visibility_role' => array (
					'type' => 'text',
					'title' => __ ( 'User Roles', 'ppom' ),
					'desc' => __ ( 'Role separated by comma.', 'ppom'),
					'hidden' => true,
			),
			'desc_tooltip' => array (
					'type' => 'checkbox',
					'title' => __ ( 'Show tooltip (PRO)', 'ppom' ),
					'desc' => __ ( 'Show Description in Tooltip with Help Icon.', 'ppom' )
			),
			'required' => array (
						'type' => 'checkbox',
						'title' => __ ( 'Required', 'ppom' ),
						'desc' => __ ( 'Select this if it must be required.', 'ppom' ) 
			),
			'logic' => array (
					'type' => 'checkbox',
					'title' => __ ( 'Enable Conditions', "ppom" ),
					'desc' => __ ( 'Tick it to turn conditional logic to work below.', "ppom" )
			),
			'conditions' => array (
					'type' => 'html-conditions',
					'title' => __ ( 'Conditions', "ppom" ),
					'desc' => __ ( 'Tick it to turn conditional logic to work below', "ppom" )
			),
		);
	}
}