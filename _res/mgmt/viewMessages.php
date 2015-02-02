<?php

    if( !preg_match( "/index.php/i", $_SERVER['PHP_SELF'] ) ) { die(); }
    
    if( isset( $_GET['clear_messages'] ) ) {
        
            $db->query( "TRUNCATE TABLE mgmt_messages" );
    
            echo "<div class=\"square good\" align=\"left\">";
            echo "<strong>Success</strong>";
            echo "<br />";
            echo "All management messages have been deleted.";
            echo "</div>";

    }

    $query = $db->query( "SELECT * FROM mgmt_messages ORDER BY id DESC LIMIT 5" );
    
    while( $array = $db->assoc( $query ) ) {
    
        $query2 = $db->query( "SELECT * FROM users WHERE id = '{$array['user']}'" );
        $array2 = $db->assoc( $query2 );
    
?>
<div class="box">

    <div class="square title">
        <strong>Message from <?php echo $array2['username']; ?></strong>
    </div>

    <div class="content">

        &quot;<?php echo $array['message']; ?>&quot;

    </div>
    
</div>
    <?php
    }
    
    if( $user->hasGroup( '4' ) or $user->hasGroup( '5' ) ) {
    ?>
    <div>
    <form style="display: inline; float: right;" action="?clear_messages" method="post" onsubmit="if( !confirm('Are you sure you want to delete all management messages?') ) { return false; }">
    
        <p><input type="submit" class="button red" value="Clear all messages" /></p>
    
    </form>
    </div>
    <?php } ?>
</div>
