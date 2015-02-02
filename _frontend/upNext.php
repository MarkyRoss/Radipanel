<?php
	// Include the required glob.php file
	require_once( "../_inc/glob.php" );

	// Grab today's date using PHP date()
	$today_date = date( 'N' );

	// Grab the current hour
	$now_hour = date( 'H' );

	// Now we have the current hour, we add one to get the next hour
	$next_hour = $now_hour + 1;

	if ( $next_hour == 24 ) {

		// It's midnight on the next day
		$next_date = $today_date + 1;
		$next_hour = "0";
	}
	else {

		// It's the same day
		$next_date = $today_date;
	}

	// Now we find out who's currently on ;)
	$now_query = $db->query( "SELECT * FROM timetable WHERE day = '{$today_date}' AND time = '{$now_hour}'" );
	// And who's next!
	$next_query = $db->query( "SELECT * FROM timetable WHERE day = '{$next_date}' AND time = '{$next_hour}'" );
	
	// Now create an array of the data, both now and next
	$now_query_array = $db->assoc( $now_query );
	$next_query_array = $db->assoc( $next_query );

	// Now to make the hours friendly
	if( $now_hour == 0 ) {
							
		$now_hour = "00:00";
							
	}
	elseif( $now_hour == 24 ) {
							
		$now_hour = "00:00";
							
	}
	else {

		$now_hour = "{$now_hour}:00";

	}

	if( $next_hour == 0 ) {
								
		$next_hour = "00:00";
							
	}
	elseif( $next_hour == 24 ) {
								
		$next_hour = "00:00";
							
	}
	else {

		$next_hour = "{$next_hour}:00";

	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">

	<head>

		<title>radiPanel: Coming Up Next</title>

		<style type="text/css" media="screen">

			body {

				background: #ddeef6;
				padding: 0;
				margin: 0;

			}

			body, a, input, select, textarea {

				font-family: Verdana, Tahoma, Arial;
				font-size: 11px;
				color: #333;
				text-decoration: none;

			}

			a:hover {
			
				text-decoration: underline;
			
			}

			form {

				padding: 0;
				margin: 0;

			}

			.wrapper {

				background-color: #fcfcfc;
				width: 350px;
				margin: auto;
				padding: 5px;
				margin-top: 15px;

			}

			.title {

				padding: 5px;	
				margin-bottom: 5px;
				font-size: 14px;
				font-weight: bold;
				background-color: #eee;
				color: #444;

			}

			.content {

				padding: 5px;

			}

			.good, .bad {

				padding: 5px;	
				margin-bottom: 5px;

			}

			.good strong, .bad strong {

				font-size: 12px;
				font-weight: bold;

			}

			.good {

				background-color: #d9ffcf;
				border-color: #ade5a3;
				color: #1b801b;

			}

			.bad {

				background-color: #ffcfcf;
				border-color: #e5a3a3;
				color: #801b1b;

			}

			input, select, textarea {

				border: 1px #e0e0e0 solid;
				border-bottom-width: 2px;
				padding: 3px;

			}

			input {

				width: 170px;

			}

			input.button {

				width: auto;
				cursor: pointer;
				background: #eee;

			}

			select {

				width: 176px;

			}

			textarea {

				width: 288px;

			}

			label {

				display: block;
				padding: 3px;

			}

		</style>

	</head>

	<body>

		<div class="wrapper">

			<div class="title">

				Coming Up Next
			
			</div>

			<div class="content">

				<p><strong>Now Playing:</strong> 
					<?php
						// Now we find out if someone is currently on
						if ( $now_query_array['dj'] == "" ) {
							
							// There wasn't, so we tell the user
							echo "No DJ has been scheduled for " . $now_hour . "";

						}
						else {
	
							// Now we give them the bad news, someone is online :(
							// So now to find out who they are
							
							$who_are_they_now = $db->query( "SELECT username FROM users WHERE id='{$now_query_array['dj']}'" );
							$who_are_they_now = mysql_result( $who_are_they_now, 0, "username");
							echo "DJ " . $who_are_they_now;
							echo " (" . $now_hour . ")";
						
						}
					?>
				</p>

				<p><strong>Coming Up Next:</strong> 
					<?php
						if ( $next_query_array['dj'] == "" ) {

							// There wasn't, so we tell the user
							echo "No DJ has been scheduled for " . $next_hour . "";
						}
						else {
							
							// Now we give them the bad news, someone is online :(
							// So now to find out who they are
							$who_are_they_next = $db->query( "SELECT username FROM users WHERE id='{$next_query_array['dj']}'" );
							$who_are_they_next = mysql_result( $who_are_they_next, 0, "username");
							echo "DJ " . $who_are_they_next;
							echo " (" . $next_hour . ")";
						
						}
					?>
				</p>

			</div>

		</div>

	</body>
</html>
