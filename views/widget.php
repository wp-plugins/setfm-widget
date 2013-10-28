<?php
function artist_sets($artist_id, $update = true, $hyperlinks = true, $encode_utf8 = false) {
   error_reporting(E_ALL ^ E_NOTICE);
   // Get the data from Set.fm JSON API
   
   $json = wp_remote_get("http://www.set.fm/api/livesets.json?artist_id=$artist_id");
   if ( is_wp_error($json) ) {
     echo 'wp error';
   }
   // Decode JSON into array
   $sets = json_decode($json['body'], true);
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

       //echo '<li><span6><img src="http://lorempixum.com/100/100/nature/2";/>'. $date;
       /*echo '<li><span4>'. $date . '</span4><span6 float:center>';
       if($venue != "")
       {
         echo '<b>' . $venue . '</b>';
       }
       echo "</span6><span4 float:right><a href=$url target='_blank' class='btn btn-primary'>".'$' . number_format($price/100, 2, '.', '') ."</a></span4>";
       echo '</li><hr>';*/
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
   echo '<b><a href="">Set.fm</a></b>';
   //echo '</br><img src="http://set.fm/assets/logos/set-fm-logo-july-24.png" width="100"/>';
 }
?>
 