<?php
  function artist_sets($artist_id, $update = true, $hyperlinks = true, $encode_utf8 = false) {
    error_reporting(E_ALL ^ E_NOTICE);
    $date = date('Y-m-d+h:i:s', time());
    // Get the data from Set.fm JSON API
    $json = wp_remote_get("http://www.set.fm/api/livesets.json?artist_id=$artist_id&end_datetime_boundary=$date");
    //$jsonFuture = wp_remote_get("http://localhost:3000/api/livesets.json?artist_id=$artist_id&start_datetime_boundary=$date");
    //$jsonArtist = wp_remote_get("http://www.set.fm/api/artists.json?artist_id=$artist_id");
    $jsonArtist = wp_remote_get("http://www.set.fm/api/artists/$artist_id");
    
    $past_sets = json_decode($json['body'], true);
    //$future_sets = json_decode($jsonFuture['body'], true); 
    $artist = json_decode($jsonArtist['body'], true);
    $options = get_option('setfm_settings_options'); 
    $useTheme = false;
    if ( is_wp_error($json) or /*is_wp_error($jsonFuture) or*/ is_wp_error($jsonArtist)) {
       echo 'wp error';
   }

    if($options[list_display_field] == "")
      singleSetStyle($date, $artist_id, $past_sets,$artist);
    else
      cardStyle($date, $artist_id, $past_sets,$artist, $options[list_display_field]);    
  }
  function cardStyle($date, $artist_id, $past_sets, $artist, $listSize){
      $numSets = (int)$listSize;
      $numPastSets = $past_sets.size;
      //if($numPastSets < "3")
        //$numSets = 5 - $numPastSets + 1;
      $sets = $past_sets;//array_slice($future_sets,$future_sets.size-$numSets,$future_sets.size-1) + $past_sets;
      $options = get_option('setfm_settings_options'); 
      $width = $options[width_field];
      $border = $options[border_field];
      if($options[theme_field] == "light"){
        if($width > 100 && $width < 1000){
          if($border >= 0 && $border < 20)
            echo '<div id="setfm-widget" class="setfmlight widget list" style="border-radius:' . $border . 'px;max-width:'.$width.'px;">';
          else
            echo '<div id="setfm-widget" class="setfmlight widget list" style=max-width:'.$width.'px;">';        
        }
        else
          echo '<div id="setfm-widget" class="setfmlight widget list">'; 
      }
      else{
        if($width > 100 && $width < 1000){
          if($border >= 0 && $border < 20)
            echo '<div id="setfm-widget" class="widget list" style="border-radius:' . $border  . 'px;max-width:'.$width.'px;">';                
          else
            echo '<div id="setfm-widget" class="widget list" style="max-width:'.$width.'px;">';
        }
        else
          echo '<div id="setfm-widget" class="widget list">';
      }
      echo '<div class="setfm-list">';
      echo  '<div class="setfm-list-card">';      
    
      if($options[featured_field] == "featured" && $artist['best_liveset']){        
        $set_unique_id = $artist['best_liveset'];        
        $featured_set = wp_remote_get("http://www.set.fm/api/livesets/show_from_unique_id?unique_id=$set_unique_id");
        $featured_set_json = json_decode($featured_set['body'], true);      
        array_unshift($sets, $featured_set_json);
      }
      if (is_array($sets))
     {
      $name = $sets[0]['artist']['name'];
       $i = 0;
       foreach($sets as $set){
         if($i == $numSets)
           break;         
         $date = $set['when'];
         $price = $set['price_cents_usd'];
         $url = $set['canonical_url'];
         $image = $set['artist']['banner_image'];
         $venue = "";
        
         if($set['venue'] != "")
           $venue = $set['venue']['name'];
         else
           $venue = "Uknown Venue";
         if($i == 0){          
          echo '<img src="'.$image.'">';
          echo '<div class="setfm-info">';
          echo '<h3>'.$name.'</h3>';
          echo '<p class="venue">Live at '.$venue.'</p>';
          echo '<div class="player-div">';
          echo '<a class="player play">Play</a>';
          echo '<audio class="sample-player">';        
          echo '<source src="' . $set["sampleclip"]["url"] .'" type="audio/mpeg">';              
          echo '</audio>';         
          echo '</div>';
          echo '</div>';
          echo '<a class="dwn-btn">Download Now</a>';
          echo '</div>';
          echo '<div class="sets">';

        }
        else{
          echo '<a href="'. $url .'" class="set-item">';
          echo '<div class="play">play</div>';
          echo '<p class="set-date">'.$date.'</p>';         
          echo '<p class="set-venue">' . $venue;
          $abbr = getAbbr($set['venue']['address']['state']);
          $state = $set['venue']['address']['state'];
          if($abbr) $state = $abbr; 
          if($set['venue']['address']) echo ' | '.$set['venue']['address']['city'].','. $state .'</p>';
          else echo'</p>';
          echo '</a>';
        }
        $i++;
       }            
      echo '</div>';
     }
      
      echo '<a class="more-sets" href="http://www.set.fm/artists/'.$artist['slug'].'">+ see more</a>';
      if(checked($options[redeem_field], 'on', false) ){
        echo '<div class="setfm-redeem">';
        echo '<a href="http://set.fm/redeem">Redeem Purchase</a>';
        echo '</div>';
      }
      echo '<div class="setfm-foot">';
      echo '<a class="set-link" href="http://set.fm">Set.fm</a>';
      echo '</div>';
      echo '</div>';
      echo '</div>';
  }

  function getAbbr($state){    
    $states = [ 'Alabama'=> 'AL', 'Alaska'=> 'AK', 'Arizona'=> 'AZ','Arkansas'=> 'AR','California'=> 'CA','Colorado'=> 'CO','Connecticut'=> 'CT','Delaware'=> 'DE','District of Columbia'=> 'DC','Florida'=> 'FL','Georgia'=> 'GA','Hawaii'=> 'HI','Idaho'=> 'ID','Illinois'=> 'IL','Indiana'=> 'IN','Iowa'=> 'IA','Kansas'=> 'KS','Kentucky'=> 'KY','Louisiana'=> 'LA','Maine'=> 'ME','Maryland'=> 'MD','Massachusetts'=> 'MA','Michigan'=> 'MI','Minnesota'=> 'MN','Mississippi'=> 'MS','Missouri'=> 'MO','Montana'=> 'MT','Nebraska'=> 'NE','Nevada'=> 'NV','New Hampshire'=> 'NH','New Jersey'=> 'NJ','New Mexico'=> 'NM','New York'=> 'NY','North Carolina'=> 'NC','North Dakota'=> 'ND','Ohio'=> 'OH','Oklahoma'=> 'OK','Oregon'=> 'OR','Pennsylvania'=> 'PA','Rhode Island'=> 'RI','South Carolina'=> 'SC','South Dakota'=> 'SD','Tennessee'=> 'TN','Texas'=> 'TX','Utah'=> 'UT','Vermont'=> 'VT','Virginia'=> 'VA','Washington'=> 'WA','West Virginia'=> 'WV','Wisconsin'=> 'WI','Wyoming'=> 'WY'];
    return $states[$state];
  }
  
  function singleSetStyle($date, $artist_id, $past_sets, $artist){      
      $set = $past_sets[0];
      $name = $set['artist']['name'];
      $date = $set['when'];
      $price = $set['price_cents_usd'];
      $url = $set['canonical_url'];
      $image = $set['artist']['banner_image'];
      if($set['venue'] != "")
        $venue = $set['venue']['name'];
      else
        $venue = "Uknown Venue";
      $options = get_option('setfm_settings_options'); 
    
    $width = $options[width_field];
    $border = $options[border_field];
    if($options[theme_field] == "light"){
      if($width > 100 && $width < 1000){
        if($border >= 0 && $border < 20)
          echo '<div id="setfm-widget" class="setfmlight" style="border-radius:' . $border . 'px;max-width:'.$width.'px;">';
        else
          echo '<div id="setfm-widget" class="setfmlight" style=max-width:'.$width.'px;">';
      }
      else
        echo '<div id="setfm-widget" class="setfmlight">';    
    }
    else{
      if($width > 100 && $width < 1000){
        if($border >= 0 && $border < 20)
          echo '<div id="setfm-widget" style="border-radius:' . $border  . 'px;max-width:'.$width.'px;">';                
        else
          echo '<div id="setfm-widget" style="max-width:'.$width.'px;">';
      }
      else
        echo '<div id="setfm-widget">';
    }
    ?>
      <div class="setfm-card">
        <?php 
        echo '<img src="'. $image .'">';        
        if($set["sampleclip"]["url"] != ""){
        echo '<div class="player-div">';
          echo '<a class="player play">Play</a>';
          //echo '<a class="pause">Pause</a>';          
          echo '<audio class="sample-player">';        
          echo '<source src="' . $set["sampleclip"]["url"] .'" type="audio/mpeg">';              
          echo '</audio>';         
        echo '</div>';
        }
        ?>
      </div>
      <div class="setfm-info">
        <?php 
         echo'<h3>'. $name .'</h3>';
         echo '<p class="venue">Live at ' . $venue .'</p>';
        ?>
        <?php
        echo '<a class="dwn-btn" href="'.$set['canonical_url'].'">Download Now</a>';
        echo '<a class="more-sets" href=http://www.set.fm/artists/'.$artist['slug'].'>+ see more</a>';?>
      </div>
      <?php 
      if(checked($options[redeem_field], 'on', false) ){
        echo '<div class="setfm-redeem">';
        echo '<a href="http://set.fm/redeem">Redeem Purchase</a>';
        echo '</div>';
      }
      ?>
      <div class="setfm-foot">
        <a class="set-link" href="http://set.fm">Set.fm</a>
      </div>
    </div>
    <?php
  }

  function themeStyle($date, $artist_id,$past_sets, $future_sets, $artist){
    $numSets = 3;
     $numPastSets = $past_sets.size;
     if($numPastSets < "3")
       $numSets = 5 - $numPastSets + 1;
     $sets = array_slice($future_sets,$future_sets.size-$numSets,$future_sets.size-1) + $past_sets;
     echo '<h3 class="widget-title">Live Recordings</h3>';
     //Create unordered list
     echo '<ul>';
     
     if ($artist_id == '') {
        echo '<li>Not configured</li>';
        return;
      }
     if (is_array($sets))
     {
      $name = $sets[0]['artist']['name'];
       $i = 0;
       foreach($sets as $set){
         if($i == 5)
           break;
         $date = $set['when'];
         $price = $set['price_cents_usd'];
         $url = $set['canonical_url'];
         $venue = "";
        
         if($set['venue'] != "")
           $venue = $set['venue']['name'];
         else
           $venue = "Uknown Venue";
         echo '<li><span7>';
         if($venue != "")
         {
           echo '<a href="'. $url . '"><div class="setName"><b>' . $venue . '</b></div></a>';
         }
         echo '<div class="setDetail">'. $date . '</div></span7>';
         echo "<span3><a href=$url/purchase target='_blank' class='btn'>" .  ($price == 0 ? "Free" : ( '$' . number_format($price/100, 2, '.', ''))) ."</a></span3>";
         echo '</li>';
         //echo '<hr>';
         $i++;
       }
     }
     //Close list
     echo '</ul>';
     echo '<b><a href="htttp://www.set.fm">Set.fm</a></b>';
  } 
?>
 