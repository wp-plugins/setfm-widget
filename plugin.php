<?php
/*
Plugin Name: Set.fm
Plugin URI: http://www.set.fm
Description: The Set.fm Wordpress Widget enables you to display your most recent set or sets from your Wordpress sidebar.  
Version: 2.3
Author: Hugo Martinez
Author URI: http://hugo443.wordpress.com
Author Email: hugo@set.fm
Text Domain: widget-name-locale
Domain Path: /lang/
Network: false
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Copyright 2013 Set.fm (support@set.fm)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/


class Setfm extends WP_Widget {
	/*--------------------------------------------------*/
	/* Constructor
	/*--------------------------------------------------*/

	/**
	 * Specifies the classname and description, instantiates the widget,
	 * loads localization files, and includes necessary stylesheets and JavaScript.
	 */
	//public function __construct() {
	function Setfm() {  
  
	   // Define constants used throughout the plugin  
	   //$this--->init_plugin_constants();  
    if(!defined('PLUGIN_LOCALE')) { 
      define('PLUGIN_LOCALE', 'setfm-widget-locale'); 
    } // end if 
 
    if(!defined('PLUGIN_NAME')) { 
      define('PLUGIN_NAME', 'Set.fm'); 
    } // end if 
 
    if(!defined('PLUGIN_SLUG')) { 
      define('PLUGIN_SLUG', 'setfm-widget'); 
    } // end if 
    
	  $widget_opts = array (  
    'classname' => PLUGIN_NAME,   
    'description' => __('The Set.fm Wordpress Widget enables you to display your most recent set or sets from your Wordpress sidebar.  ', PLUGIN_LOCALE)  
     );    
          
		$this->WP_Widget(PLUGIN_SLUG, __(PLUGIN_NAME, PLUGIN_LOCALE), $widget_opts);  
	  load_plugin_textdomain(PLUGIN_LOCALE, false, dirname(plugin_basename( __FILE__ ) ) . '/lang/' );  
          
	  // Load JavaScript and stylesheets  
	  $this->register_scripts_and_styles();  

	} // end constructor

	/*--------------------------------------------------*/
	/* Widget API Functions
	/*--------------------------------------------------*/

	/**
	 * Outputs the content of the widget.
	 *
	 * @param	array	args		The array of form elements
	 * @param	array	instance	The current instance of the widget
	 */
	public function widget( $args, $instance ) {

		extract( $args, EXTR_SKIP );

		echo $before_widget;
      
		include( plugin_dir_path( __FILE__ ) . '/views/widget.php' );
    	//$artist_slug = $instance['artist_slug'];
    	artist_sets(get_option('artist_id'), true, true, false);

		echo $after_widget;

	} // end widget

	/**
	 * Processes the widget's options to be saved.
	 *
	 * @param	array	new_instance	The new instance of values to be generated via the update.
	 * @param	array	old_instance	The previous instance of values before the update.
	 */
	public function update( $new_instance, $old_instance ) {
    
		$instance = $old_instance;        
    $instance['artist_slug'] = ( ! empty( $new_instance['artist_slug'] ) ) ? strip_tags( $new_instance['artist_slug'] ) : '';
    update_option('artist_id', $instance['artist_slug'], '', yes);
		return $instance;

	} // end widget
	/**
	 * Generates the administration form for the widget.
	 *
	 * @param	array	instance	The array of keys and values for the widget.
	 */
	public function form( $instance ) {

  	// TODO:	Define default values for your variables
    $instance = wp_parse_args( 
      (array)$instance, 
      array('artist_slug' => '')
    );
    $artist_slug = strip_tags($new_instance['artist_slug']);
	// Display the admin form
	include( plugin_dir_path(__FILE__) . '/views/admin.php' );

	} // end form

	/*--------------------------------------------------*/
	/* Public Functions
	/*--------------------------------------------------*/

	/**
	 * Loads the Widget's text domain for localization and translation.
	 */
	public function widget_textdomain() {

		// TODO be sure to change 'widget-name' to the name of *your* plugin
		load_plugin_textdomain( PLUGIN_SLUG .'-locale', false, plugin_dir_path( __FILE__ ) . '/lang/' );

	} // end widget_textdomain

	/**
	 * Fired when the plugin is activated.
	 *
	 * @param		boolean	$network_wide	True if WPMU superadmin uses "Network Activate" action, false if WPMU is disabled or plugin is activated on an individual blog.
	 */
	public function activate( $network_wide ) {
		// TODO define activation functionality here
	} // end activate

	/**
	 * Fired when the plugin is deactivated.
	 *
	 * @param	boolean	$network_wide	True if WPMU superadmin uses "Network Activate" action, false if WPMU is disabled or plugin is activated on an individual blog
	 */
	public function deactivate( $network_wide ) {
		// TODO define deactivation functionality here
	} // end deactivate

	/**
	 * Registers and enqueues admin-specific styles.
	 */
	public function register_admin_styles() {

		// TODO:	Change 'widget-name' to the name of your plugin
		wp_enqueue_style(  PLUGIN_SLUG .'-admin-styles', plugins_url( PLUGIN_SLUG .'/css/admin.css' ) );

	} // end register_admin_styles

	/**
	 * Registers and enqueues admin-specific JavaScript.
	 */
	public function register_admin_scripts() {

		// TODO:	Change 'widget-name' to the name of your plugin
		wp_enqueue_script(  PLUGIN_SLUG .'-admin-script', plugins_url( PLUGIN_SLUG .'/js/admin.js' ), array('jquery') );

	} // end register_admin_scripts

	/**
	 * Registers and enqueues widget-specific styles.
	 */
	public function register_widget_styles() {

		// TODO:	Change 'widget-name' to the name of your plugin
		wp_enqueue_style(  PLUGIN_SLUG .'-widget-styles', plugins_url( PLUGIN_SLUG .'/css/widget.css' ) );

	} // end register_widget_styles

	/**
	 * Registers and enqueues widget-specific scripts.
	 */
	public function register_widget_scripts() {

		// TODO:	Change 'widget-name' to the name of your plugin
		wp_enqueue_script(  PLUGIN_SLUG .'-widget-script', plugins_url( PLUGIN_SLUG .'/js/widget.js' ), array('jquery') );

	} // end register_widget_scripts
	
    /*--------------------------------------------------*/ 
    /* Private Functions 
    /*--------------------------------------------------*/ 
     
       /** 
        * Registers and enqueues stylesheets for the administration panel and the 
        * public facing site. 
        */ 
       private function register_scripts_and_styles() { 
           if(is_admin()) { 
               $this->load_file(PLUGIN_NAME, '/' . PLUGIN_SLUG . '/js/admin.js', true); 
               $this->load_file(PLUGIN_NAME, '/' . PLUGIN_SLUG . '/css/admin.css'); 
           } else {  
               $this->load_file(PLUGIN_NAME, '/' . PLUGIN_SLUG . '/js/widget.js', true); 
               $this->load_file(PLUGIN_NAME, '/' . PLUGIN_SLUG . '/css/widget.css'); 
           } // end if/else 
       } // end register_scripts_and_styles 
 
       /** 
        * Helper function for registering and enqueueing scripts and styles. 
        * 
        * @name    The     ID to register with WordPress 
        * @file_path       The path to the actual file 
        * @is_script       Optional argument for if the incoming file_path is a JavaScript source file. 
        */ 
       private function load_file($name, $file_path, $is_script = false) { 
         
           $url = WP_PLUGIN_URL . $file_path; 
           $file = WP_PLUGIN_DIR . $file_path; 

           if(file_exists($file)) { 
               if($is_script) { 
                   wp_enqueue_script('jquery');
                   wp_register_script($name, $url, array('jquery')); 
                   wp_enqueue_script($name);  
               } else { 
                   wp_register_style($name, $url); 
                   wp_enqueue_style($name); 
               } // end if 
           } // end if 
     
         } // end load_file 

} // end class

// TODO:	Remember to change 'Widget_Name' to match the class name definition
add_action( 'widgets_init', create_function( '', 'register_widget("Setfm");' ) );
add_action('admin_menu', 'admin_init');
add_action( 'admin_enqueue_scripts', 'add_color_picker' );
function add_color_picker( $hook ) {
 
    if( is_admin() ) { 
        // Add the color picker css file       
        wp_enqueue_style( 'wp-color-picker' );          
        // Include our custom jQuery file with WordPress Color Picker dependency
        wp_enqueue_script( 'custom-script-handle', plugins_url( '/js/dashboard.js', __FILE__ ), array( 'wp-color-picker' ), false, true ); 
    }
}
function admin_init(){
  include( plugin_dir_path( __FILE__ ) . '/views/dashboard.php' );
  add_action( 'admin_init', 'register_page_options' );
  add_options_page( 'Set.fm Admin Page', 'Set.fm Settings', 'manage_options','setfm-plugin', 'dashboard_init' );    
  //add_action( 'admin_options', array( &$this, 'register_page_options') );
     
}
?>
