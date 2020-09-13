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
			'data_name' => array (
					'type' => 'text',
					'title' => __ ( 'Data name', 'ppom' ),
					'desc' => __ ( 'REQUIRED: The identification name of this field, that you can insert into body email configuration. Note:Use only lowercase characters and underscores.', 'ppom' ) 
			),
		);
	}
}