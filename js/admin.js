/*var user = "<?php echo $this->get_field_id('setfm_user'); ?>";
jQuery("#testbutton").click(function(e){
  user = jQuery(user);
  var pass = "";
  e.preventDefault();
  validate(user, pass);
});
//var user;
//var pass;
/*
jQuery(document).on('click', "#testbutton", function(e){
  e.preventDefault();
  var user = jQuery("#setfm_user").val();
  var pass = jQuery('<?php echo $this->get_field_id("setfm_user"); ?>');
  var vals = jQuery('div#myForm');
  validate(user, pass);
});*/

/*
jQuery(document).on('keyup','#setfm_user', function() {
  user = jQuery(this).val();
});
jQuery(document).on('keyup','#setfm_pass', function() {
  pass = jQuery(this).val();
});

function validate(user,  pass){
  jQuery.ajax({
      type: "POST",   
      url: "http://set.fm/api/sessions.json?email="+user+"&password="+pass,   
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
  jQuery("select#"+'<?php echo $this->get_field_id("artist_slug");?>').show(); 
  for(var i=0; i < items.length; i++){
    var obj = items[i];
    jQuery("select#<?php echo $this->get_field_id('artist_slug'); ?>").append('<option value="' + obj.slug + '">'+ obj.name + '</option>');        
  }    
}

jQuery( "select#artist_slug" ).change(function() {
  //alert("changed to:" + $(this).val());
});*/

