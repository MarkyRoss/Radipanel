<?php

	if( !preg_match( "/index.php/i", $_SERVER['PHP_SELF'] ) ) { die(); }

?>
<div class="box">

	<div class="square title">
		<strong>Manage adverts</strong>
	</div>

	<?php

		$query = $db->query( "SELECT * FROM adverts" );
		$num   = $db->num( $query );

		$j = "a";

                if ( $db->num( $query ) == "0" ) {

                    echo "<div class=\"square bad\" style=\"margin-bottom: 0px;\">";
                    echo "<strong>Sorry</strong>";
                    echo "<br />";
                    echo "No adverts have currently been configured.";
                    echo "</div>";

                }
                
		while( $array = $db->assoc( $query ) ) {

			echo "<div class=\"row {$j}\" id=\"advert_{$array['id']}\">";

			echo "<a href=\"#\" onclick=\"Radi.deleteAdvert('{$array['id']}');\">";
			echo "<img src=\"_img/minus.png\" alt=\"Delete\" align=\"right\" />";
			echo "</a>";

			echo "<a href=\"mgmt.addAdvert?id={$array['id']}\">";
			echo "<img src=\"_img/pencil.png\" alt=\"Edit\" align=\"right\" />";
			echo "</a>";

			echo $array['name'];

			echo "</div>";

			$j++;

			if( $j == "c" ) {

				$j = "a";

			}

		}

	?>

</div>