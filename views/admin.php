<div id="myForm">
    <label for="<?php echo $this->get_field_id('setfm_user'); ?>">Email:</label></br>
    <input type="text" id="<?php echo $this->get_field_id('setfm_user'); ?>" name="<?php echo $this->get_field_name('setfm_user'); ?>"/></br>
    <label for="<?php echo $this->get_field_id('setfm_pass'); ?>">Password:</label></br>
    <input type="password" id="<?php echo $this->get_field_id('setfm_pass'); ?>" name="<?php echo $this->get_field_name('setfm_pass'); ?>"/></br>
    <input type="submit" value="Login" class="button button-primary" id="<?php echo $this->get_field_id('setfm_user');?>_button"/>
</div>

<div class="wrapper">  
  <fieldset>
  <select id="<?php echo $this->get_field_id('artist_slug'); ?>" name="<?php echo $this->get_field_name('artist_slug'); ?>"  style="display: none;" >
  </select>
  </fieldset>
</div>
<!--<div class="wrapper">  
  <fieldset>  
    <input type="text" id="<?php echo $this->get_field_id('artist_slug'); ?>" name="<?php echo $this->get_field_name('artist_slug'); ?>" value="<?php echo $instance['artist_slug']; ?>"/></br>
</fieldset>
</div>-->

<script type="text/javascript">
var user = "#<?php echo $this->get_field_id('setfm_user'); ?>";
var pass = "#<?php echo $this->get_field_id('setfm_pass'); ?>";

jQuery(document).ready(function(){
  jQuery(document).on('click', "#<?php echo $this->get_field_id('setfm_user');?>_button", function(e){
    e.preventDefault();
    validate(jQuery(user).val(), jQuery(pass).val());
  });
    
  function validate(username,  password){
    jQuery.ajax({
        type: "POST",   
        url: "http://set.fm/api/sessions.json?email="+username+"&password="+password,   
        dataType: 'json',
        success : function(data) {
          if(data.success == true){
            jQuery('div#myForm').hide();
            var auth_token = data.auth_token;
            jQuery.ajax({
                type: "GET",   
                url: "http://set.fm/api/artists.json?auth_token="+auth_token,
                dataType: 'json',    
                success : function(json) {  
                  updateDropdown(json);
                }
            })
          }
          else
            alert("Invalid username and password");
        },
        error : function(data){
            alert("Error connecting to www.set.fm");
        }
    });
  }
  function updateDropdown(items) {
    jQuery('#<?php echo $this->get_field_id("artist_slug");?>').show(); 
    for(var i=0; i < items.length; i++){
      var obj = items[i];
      jQuery("#<?php echo $this->get_field_id('artist_slug'); ?>").append('<option value="' + obj.id + '"name="'+ obj.name+'">'+ obj.name + '</option>');        
    }    
  }
});

</script>


