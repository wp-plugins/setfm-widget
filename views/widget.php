<?php
function artist_sets($artist_id, $update = true, $hyperlinks = true, $encode_utf8 = false) {
   error_reporting(E_ALL ^ E_NOTICE);
   $date = date('Y-m-d+h:i:s', time());
   // Get the data from Set.fm JSON API
   $json = wp_remote_get("http://www.set.fm/api/livesets.json?artist_id=$artist_id&end_datetime_boundary=$date");
   $jsonFuture = wp_remote_get("http://www.set.fm/api/livesets.json?artist_id=$artist_id&start_datetime_boundary=$date");

   if ( is_wp_error($json) or is_wp_error($jsonFuture)) {
     echo 'wp error';
   }
   // Decode JSON into array
   $past_sets = json_decode($json['body'], true);
   $future_sets = json_decode($jsonFuture['body'], true);
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
 