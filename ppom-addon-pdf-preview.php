<?php
/**
 * Plugin Name: PPOM Add-on PDF Preview
 * Plugin URI: http://najeebmedia.com
 * Description: PPOM Add-on PDF Preview render all PDF pages on product pages.
 * Version: 1.0
 * Author: Najeeb Ahmad
 * Author URI: http://najeebmedia.com
 * Text Domain: ppom-pdf-preview
 * License: GPL2
 */

/* 
**========== Direct access not allowed =========== 
*/
if( ! defined('ABSPATH' ) ){ exit; }
 
/* 
**============= Define constant ================ 
*/
define('PDF_PREVIEW_PATH', untrailingslashit(plugin_dir_path( __FILE__ )) );
define('PDF_PREVIEW_URL', untrailingslashit(plugin_dir_url( __FILE__ )) );
define('PDF_PREVIEW_VERSION', 1.0 );
 
class PPOM_PDF_PREVIEW {
    
    /**
	 * the static object instace
	 */
	private static $ins = null;
	
	public static function get_instance(){

		// create a new object if it doesn't exist.
		is_null(self::$ins) && self::$ins = new self;
		return self::$ins;
	}
    
    function __construct() {

        // Load action for input scripts
        add_action('ppom_hooks_inputs', array($this, 'hook_input_scripts'), 10, 2 );
        
        //Rendering vm inputs
        add_action('ppom_rendering_inputs', array($this, 'render_input_pdf_preview'), 10, 5 );

        // File path
        add_filter('nm_input_class-pdf_preview', array($this, 'addon_path_pdf_preview'), 10, 2);

        // Loading all input in PRO
        add_filter('ppom_all_inputs', array($this, 'load_addon'), 10, 2);
    }
 

    /*
    **============= Load scripts ================ 
    */
    function hook_input_scripts($field, $data_name) {

        if( $field['type'] != 'pdf_preview' ) return '';
        
        if( $field['type'] == 'pdf_preview' ) {
            
            wp_enqueue_style('ppom-est-vm', PDF_PREVIEW_URL."/css/ppom-pdf-preview.css");
            wp_enqueue_script('ppom-pdf-lib-js', 'https://cdn.jsdelivr.net/npm/pdfjs-dist@2.0.385/build/pdf.min.js', array('jquery'), PDF_PREVIEW_VERSION, true);
            wp_enqueue_script('ppom-pdf-pre', PDF_PREVIEW_URL.'/js/ppom-pdf-preview.js', array('jquery'), PDF_PREVIEW_VERSION, true);
            
            wp_localize_script( 'ppom-pdf-pre', 'ppom_pdfpreview_vars', array(
              'dir_path'    => ppom_get_dir_path(),
            ));
        }
    }


    /*
    **============= vm inputs path load ================ 
    */
    function addon_path_pdf_preview($path, $type) {
        
        if( file_exists(PDF_PREVIEW_PATH. '/classes/input.pdf_preview.php') ) {
             $path = PDF_PREVIEW_PATH. "/classes/input.{$type}.php";
        }
        return $path;
    }


    /* 
    **============= Loading all PRO inputs ================ 
    */
    function load_addon( $inputs_array, $inputObj) {
       
       // checking vm addon is enable
        $inputs_array['pdf_preview'] = $inputObj->get_input ( 'pdf_preview' );

        return $inputs_array;
    }


    /*
    **============= Frontent meta rendering ================ 
    */
    function render_input_pdf_preview($meta, $data_name, $classes, $field_label, $options) {
        
        if( $meta['type'] != 'pdf_preview' ) return '';
        
        $meta['id'] = $data_name;
        $input_wrapper_class = 'form-group';
        $input_wrapper_class = apply_filters('ppom_input_wrapper_class', $input_wrapper_class, $meta);

        $html  = '';
        $html .= '<div class="'.esc_attr($input_wrapper_class).'">';
            $html .= '<div id="ppom-pdf-preview-wrapper" class="form-row"></div>';
        $html .= '</div>';    //form-group
        
        echo apply_filters('ppom_pdf_preview_html', $html);
    }

    
    /*
    **============= Load templates function ================ 
    */
    function load_template( $template_name, $vars = null) {

        if( $vars != null && is_array($vars) ){
            extract( $vars );
        };

        $template_path =  PDF_PREVIEW_PATH . "/templates/{$template_name}";
        if( file_exists( $template_path ) ){
            require ( $template_path );
        } else {
            die( "Error while loading file {$template_path}" );
        }
    }
        
}


add_action('plugins_loaded', 'pdf_preview_start');
function pdf_preview_start(){
    return PPOM_PDF_PREVIEW::get_instance();
}