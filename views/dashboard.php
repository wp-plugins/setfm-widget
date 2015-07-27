<?php
$sets = [];
function dashboard_init(){
  init();
  //register_page_options();
  display_page();  
}

function display_page() { 
    ?>
    <p>
    <b>Getting Started</b>
    <p>
    The Set.fm widget must first be placed in a widget area.
    Go to "appearance > widgets" and add the Set.fm widget to your sidebar.
    Once you've dropped in the Set.fm widget you will be presented with a log-in.
    Enter your Set.fm user email and password to connect your account.
    Next choose the Artist from the dropdown; you may only have one Artist in your account.
    <p>
    Now edit the widget display options from "Settings > Set.fm Settings" in your Dashboard Sidebar.

    <div class="wrap">             
        <form method="post" action="options.php">             
	        <?php 
	        	  settings_fields('setfm_settings_group');      
	            do_settings_sections('setfm-plugin');
	            submit_button();
              ?>
	        
        </form>
    </div>
    <?php    
}

function init(){
	$date = date('Y-m-d+h:i:s', time());
  $slug = get_option('artist_id');
    // Get the data from Set.fm JSON API
    //$featured_set = wp_remote_get("http://www.set.fm/api/artists.json?show_from_slug=$slug");
    /*$json = wp_remote_get("http://www.set.fm/api/livesets.json?artist_id=$artist_id&end_datetime_boundary=$date");
    $jsonFuture = wp_remote_get("http://www.set.fm/api/livesets.json?artist_id=$artist_id&start_datetime_boundary=$date");
    $past_sets = json_decode($json['body'], true);
    $future_sets = json_decode($jsonFuture['body'], true); */
    //$numSets = 3;
    //$numPastSets = $past_sets.size;
    //if($numPastSets < "3")
   		//$numSets = 5 - $numPastSets + 1;
    //$sets = array_slice($future_sets,$future_sets.size-$numSets,$future_sets.size-1) + $past_sets;  
  	/*		foreach($sets as $set){
  				
			   //echo '<option value=$set["when"]>$set["when"]</option>';
			  
			}*/
}

function register_page_options() { 
    register_setting( 'setfm_settings_group', 'setfm_settings_options', 'validate_setting' ); // option group, option name, sanitize cb 
    add_settings_section( 'section', 'Theme Options', array( $this, 'display_section' ), 'setfm-plugin' ); // id, title, display cb, page
    //add_settings_field( 'title_field', 'Blog Title', 'title_settings_field', 'setfm-plugin', 'section' ); // id, title, display cb, page, section
    //add_settings_field( 'bg_field', 'Background Color', 'bg_settings_field' , 'setfm-plugin', 'section' ); // id, title, display cb, page, section     
    //add_settings_field( 'style_field', 'Style', 'style_settings_field' , 'setfm-plugin', 'section' ); // id, title, display cb, page, section     
    add_settings_field( 'theme_field', 'Theme', 'theme_settings_field', 'setfm-plugin', 'section' ); // id, title, display cb, page, section     
    add_settings_field( 'featured_field', 'Set Card Display', 'featured_settings_field', 'setfm-plugin', 'section' ); // id, title, display cb, page, section     
    add_settings_field( 'list_display_field', 'Set List Display', 'list_display_settings_field', 'setfm-plugin', 'section' ); // id, title, display cb, page, section     
    add_settings_field( 'width_field', 'Widget Width', 'width_settings_field', 'setfm-plugin', 'section' ); // id, title, display cb, page, section     
    add_settings_field( 'border_field', 'Border Radius', 'border_settings_field', 'setfm-plugin', 'section' ); // id, title, display cb, page, section     
    //add_settings_field( 'button_color_field', 'Button Color', 'button_color_settings_field', 'setfm-plugin', 'section' ); // id, title, display cb, page, section     
    add_settings_field( 'redeem_field', 'Code Redemption', 'redeem_settings_field', 'setfm-plugin', 'section' ); // id, title, display cb, page, section     
    
}


function validate_setting($input) {
   // Create our array for storing the validated options
    $output = array();
     
    // Loop through each of the incoming options
    foreach( $input as $key => $value ) {
         
        // Check to see if the current option has a value. If so, process it.
        if( isset( $input[$key] ) ) {
         
            // Strip all HTML and PHP tags and properly handle quoted strings
            $val = strip_tags( stripslashes( $input[ $key ] ) );
            if($key == "width_field"){
              if(is_numeric($val)){
                if($val > 200 && $val < 800){
                  $output[$key] = $val;
                }
                else{
                  add_settings_error('width_field',esc_attr( 'settings_updated' ),"Invalid width.",'error');         
                }
              }
              elseif($val == ""){
                $output[$key] = 220;
              }
              else{
                $output[$key] = 220;
                add_settings_error('width_field',esc_attr( 'settings_updated' ),"Width must be numeric.",'error');        
              }
            }

            elseif($key == "border_field"){
              if(is_numeric($val)){
                if($val >= 0 && $val < 30){
                  $output[$key] = $val;
                }
                else{
                  add_settings_error('border_field',esc_attr( 'settings_updated' ),"Invalid border radius.",'error');         
                }
              }
              elseif($val == ""){
                $output[$key] = 5;
              }
              else{
                $output[$key] = 5;
                add_settings_error('border_field',esc_attr( 'settings_updated' ),"Border radius must be numeric.",'error');        
              }
            }
            elseif($key == "list_display_field"){
              if(is_numeric($val)){
                if($val >= 0 && $val < 10){
                  $output[$key] = $val;
                }
                else{
                  add_settings_error('list_display_field',esc_attr( 'settings_updated' ),"Invalid list size.",'error');         
                }
              }
              elseif($val == ""){
                $output[$key] = 3;
              }
              else{
                $output[$key] = 3;
                add_settings_error('list_display_field',esc_attr( 'settings_updated' ),"List size must be numeric.",'error');        
              }
            }
            else{
              $output[$key] = strip_tags( stripslashes( $input[ $key ] ) );
            }
             
        } // end if
         
    } // end foreach

  return $output;
}

function display_section() {
  echo '<p>Main description of this section here.</p>';
}


function list_display_settings_field() { 
  $options = get_option('setfm_settings_options'); 
  $val = $options[list_display_field];
  echo '<input type="text" name="setfm_settings_options[list_display_field]" value="' . $val . '" />';
  echo '(1-10)<br>';
  echo 'Leave Blank to only show main set card.';
}   
 
function title_settings_field() { 
  $val = ( isset( $this->options['title'] ) ) ? $this->options['title'] : '';
  echo '<input type="text" name="settings_options[title]" value="' . $val . '" />';
}   
 
function bg_settings_field() {      
  $options = get_option('setfm_settings_options'); 
  echo '<input type="text" value="'.$options[bg] .'" name="setfm_settings_options[bg]" data-default-color="#effeff" class="color-field" />';
}
function style_settings_field() {        
  $options = get_option('setfm_settings_options'); 
  if($options[style_field] == "card" || $options[style_field] == ""){
    echo '<input type="radio" name="setfm_settings_options[style_field]" value="card" checked>Card<br>';
    echo '<input type="radio" name="setfm_settings_options[style_field]" value="wide">Wide Banner';
  }
  else{
    echo '<input type="radio" name="setfm_settings_options[style_field]" value="card">Card<br>';
    echo '<input type="radio" name="setfm_settings_options[style_field]" value="wide" checked>Wide Banner';
  }
}
function theme_settings_field() {     
  $options = get_option('setfm_settings_options'); 
  if($options[theme_field] == "light" || $options[theme_field] == ""){
    echo '<input type="radio" name="setfm_settings_options[theme_field]" value="light" checked>Light<br>';
    echo '<input type="radio" name="setfm_settings_options[theme_field]" value="dark">Dark';
  }
  else{
    echo '<input type="radio" name="setfm_settings_options[theme_field]" value="light">Light<br>';
    echo '<input type="radio" name="setfm_settings_options[theme_field]" value="dark" checked>Dark'; 
  }
}

function featured_settings_field() {     
  $options = get_option('setfm_settings_options'); 
  if($options[featured_field] == "featured"){
    echo '<input type="radio" name="setfm_settings_options[featured_field]" value="featured" checked>Featured Set<br>';
    echo '<input type="radio" name="setfm_settings_options[featured_field]" value="latest">Latest Set';
  }
  else{
    echo '<input type="radio" name="setfm_settings_options[featured_field]" value="featured">Featured Set<br>';
    echo '<input type="radio" name="setfm_settings_options[featured_field]" value="latest" checked>Latest Set'; 
  }
}

function width_settings_field() {     
  $options = get_option('setfm_settings_options');
  echo '<input type="text" value="'. $options[width_field] . '" name="setfm_settings_options[width_field]" />';
  echo '(No unit or %)<br>';
  echo 'Leave Blank to use the default.';
}

function border_settings_field() {     
  $options = get_option('setfm_settings_options');
  echo '<input type="text" value="'.$options[border_field].'" name="setfm_settings_options[border_field]" />';
  echo '<br>Leave Blank to use the default.';

}
function button_color_settings_field() {     
  $options = get_option('setfm_settings_options');
  echo '<input type="text" name="setfm_settings_options[button_color_field]" value="'.$options[button_color_field].'" data-default-color="#effeff" class="color-field" />';  
}
function redeem_settings_field() {     
  $options = get_option('setfm_settings_options');  
  echo '<input type="checkbox" class="checkbox" name="setfm_settings_options[redeem_field]"'. checked($options[redeem_field], 'on', false) .'>Show Code Redemption Button<br>';
}

?>