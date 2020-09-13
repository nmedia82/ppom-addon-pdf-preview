<?php
/**
 * Plugin Name: PPOM Addon PDF Preview
 * Plugin URI: http://najeebmedia.com
 * Description: An customized addon to PPOM
 * Version: 1.0
 * Author: Najeeb Ahmad
 * Author URI: http://najeebmedia.com
 * Text Domain: ppom-vm
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
define('PDF_PREVIEW_VERSION', 2.0 );

include_once PDF_PREVIEW_PATH . "/lib/mpdf/vendor/autoload.php";
 
class PPOM_PDF_PREVIEW {
    
    /**
	 * the static object instace
	 */
	private static $ins = null;
	protected static $mpdf;
	
	public static function get_instance(){

		// create a new object if it doesn't exist.
		is_null(self::$ins) && self::$ins = new self;
		return self::$ins;
	}
    
    function __construct() {
        
        $pdf_dir_path = ppom_get_dir_path('pdf_dir');

        self::$mpdf = new \Mpdf\Mpdf([
                'tempDir' => $pdf_dir_path
            ]);

        // Load action for input scripts
        add_action('ppom_hooks_inputs', array($this, 'hook_input_scripts'), 10, 2 );
        
        //Rendering vm inputs
        add_action('ppom_rendering_inputs', array($this, 'render_input_pdf_preview'), 10, 5 );

        // File path
        add_filter('nm_input_class-pdf_preview', array($this, 'addon_path_pdf_preview'), 10, 2);

        // Loading all input in PRO
        add_filter('ppom_all_inputs', array($this, 'load_addon'), 10, 2);
        
        
        // add_filter('ppom_file_preview_html', array($this, 'display_pdf_file'), 10, 3);
        
        
    }
    
    function display_pdf_file( $html, $file_name, $settings ){
        
    //     $pdf_fileurl = ppom_get_dir_url() . $file_name;
        
    //     $file_dir_path	= ppom_get_dir_path();
	   // $file_path = $file_dir_path . $file_name;
	    
	   // ?>
	    
	    
	   // <?php
        
       
        
        return $pdf_html;
        
        
    }
   

    /*
    **============= Load scripts ================ 
    */
    function hook_input_scripts($field, $data_name) {

            wp_enqueue_style('ppom-est-vm', PDF_PREVIEW_URL."/css/vm.css");
            wp_enqueue_script('ppom-pdf-lib-js', 'https://cdn.jsdelivr.net/npm/pdfjs-dist@2.0.385/build/pdf.min.js', array('jquery'), PDF_PREVIEW_VERSION, true);
            // wp_enqueue_script('ppom-pdf-lib', PDF_PREVIEW_URL.'/js/pdf.js', array('jquery'), PDF_PREVIEW_VERSION, true);
            wp_enqueue_script('ppom-pdf-pre', PDF_PREVIEW_URL.'/js/ppom-pdf-preview.js', array('jquery'), PDF_PREVIEW_VERSION, true);
            
            wp_localize_script( 'ppom-pdf-pre', 'ppom_pdfpreview_vars', array(
              'dir_path'    => ppom_get_dir_path(),
            ));
            
        if( $field['type'] != 'pdf_preview' ) return '';
        
        if( $field['type'] == 'pdf_preview' ) {

              
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
        $input_wrapper_class = 'ppom-input-quantities';
        $input_wrapper_class = apply_filters('ppom_input_wrapper_class', $input_wrapper_class, $meta);

        $html  = '';
        $html .= '<div class="'.esc_attr($input_wrapper_class).'">';
        
            if( $field_label ){
                $html .= '<label class="form-control-label" for="'.esc_attr($data_name).'">';
                $html .= sprintf(__("%s", "ppom"), $field_label) .'</label>';
            }
           
                
        $html .= '</div>';    //form-group
        
        echo apply_filters('ppom_vm_html', $html);
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

function pdf_preview_start() {
    return PPOM_PDF_PREVIEW::get_instance();
}

pdf_preview_start();