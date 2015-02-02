<?php
    
    // Include the required glob.php file
    require_once( "../_inc/glob.php" );

    // Now we fetch how many adverts we have running
    $query = $db->query( "SELECT * FROM adverts WHERE active='1'" );
    $num = $db->num( $query );

    // Now we check if we have no adverts
    if ( $num == "0") {

        die();
        
    }

    // From all the active adverts, we use rand to select one
    $advert_id = rand( 0, $num );

    // Now we have one selected, we gather all the information we require to build the advert HTML
    $query = $db->query( "SELECT * FROM adverts WHERE id='{$advert_id}'");

    // And turn it into an array
    $array = $db->arr( $query );

    // Now we check if they have reached their maximum impressions (if it is set)
    if ( $array['max_impressions'] == "0" ) {

        // Maximum impressions for this advert are disabled so we can use it
        // We also update the impressions counter
        $array['impressions'] = $array['impressions'] + 1;
        $db->query( "UPDATE adverts SET impressions = {$array['impressions']} WHERE id = '{$advert_id}'" );

    }
    elseif ( $array['impressions'] >= $array['max_impressions'] ) {

        // They have used all their impressions for this advertising run, so we select another advert, with no impressions limit
        $query = $db->query( "SELECT * FROM adverts WHERE max_impressions = '0' LIMIT 1" );
        $num = $db->num( $query );

        // Check if there are none
        if ( $num == "0" ) {

            die();
            
        }
        // From all the active adverts, we use rand to select one
        $advert_id = rand( 0, $num );

        // Now we have one selected, we gather all the information we require to build the advert HTML
        $query = $db->query( "SELECT * FROM adverts WHERE id='{$advert_id}'");

        // And turn it into an array
        $array = $db->arr( $query );
    }
    elseif ( $array['impressions'] <= $array['max_impressions'] ) {

        // They have more than enough impressions remaining, so we update their counter ;)
        $array['impressions'] = $array['impressions'] + 1;
        $db->query( "UPDATE adverts SET impressions = {$array['impressions']} WHERE id = '{$advert_id}'" );
    }

    // And now we build the HTML, valid of course ;)
    echo "<a href=\"{$array['url']}\" target=\"_blank\"><img src=\"{$array['imgurl']}\"></a>";
?>
