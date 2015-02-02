<?php
	// Include the required glob.php file
	require_once( "../_inc/glob.php" );

	// This is the location of the feed
	$feed = "http://pipes.yahoo.com/pipes/pipe.run?_id=201ce12e3d9dd0ced4304b8275c5458b&_render=json";
	
	// And the location of the JSON file
	$json_cache = ( "../_inc/chart_cache.json" );
	
	// First, we check for the presence of a cache file
	$cache_file = file_exists( $json_cache );
	
	if ( $cache_file ) {
	
		// Now we see how old the cache file is with a little bit of PHP magic
		$yesterday = strtotime( "yesterday" );

		// Find out how old the file is using filemtime()
		$cache_age = filemtime( $json_cache );

		// And run the check
		if( $cache_age < $yesterday ) {
		
			// It is older than yesterday, so we remove the old cache file
			@unlink( $json_cache );
			
			// And fetch the new one
			$session = curl_init($feed);
			curl_setopt($session, CURLOPT_HEADER, false);
			curl_setopt($session, CURLOPT_RETURNTRANSFER, true);

			// Execute cURL and store the results in $result
			$result = curl_exec($session);
			// And close cURL
			curl_close($session);

			// Then we write the JSON data to a file, located in _inc/chart_cache.json
			$cacheFile = $json_cache;
			$handle = fopen( $cacheFile, 'w' );
		
			// And write the data we downloaded to it
			fwrite( $handle, $result );
			fclose( $handle );
			
		}
		else {
		
			// The file is fine and exists, so we use that instead ;)
			$result = file_get_contents( $json_cache );
		
		}
	
	}
	else {
	
		// And set all the parameters for the cURL session in $session
		$session = curl_init($feed);
		curl_setopt($session, CURLOPT_HEADER, false);
		curl_setopt($session, CURLOPT_RETURNTRANSFER, true);

		// Execute cURL and store the results in $result
		$result = curl_exec($session);
		// And close cURL
		curl_close($session);

		// Then we write the JSON data to a file, located in _inc/chart_cache.json
		$cacheFile = $json_cache;
		$handle = fopen( $cacheFile, 'w' );
		
		// And write the data we downloaded to it
		fwrite( $handle, $result );
		fclose( $handle );
		
	}
		
	// Then, using json_decode we serialise the JSON feed into $array
	$array = json_decode($result);
		
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">

	<head>

		<title>radiPanel: UK Singles Chart</title>

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

				UK Singles Chart
			
			</div>

			<div class="content">
			<?php
			
			// Now, using a foreach() array, we deliver the rendered content.			
			foreach ($array->{'value'}->{'items'} as $array) {

				// First, we convert to Capital Case
				$array->{'title'} = ucwords(strtolower( $array->{'title'} ));
				
				// Then we replace the , with by (to part track and artist)
				$array->{'title'} = str_replace( "," , " by", $array->{'title'} );
				
				// Then we replace Ft. with feat.
				$array->{'title'} = str_replace( "Ft." , " feat.", $array->{'title'} );
				
				// And return the content!
				echo '<div>' . $array->{'title'} . '</a></div>';

			}

			?>
			<br />
			<!-- To comply with the Billboard Acceptable Usage Limitiations, it is not recommended you remove this statement -->
			<div><small>Data for the UK Singles Chart provided for <a href="http://www.billboard.com/" target="_blank"><small><strong>Billboard</strong></small></a> by <a href="http://www.theofficialcharts.com/" target="_blank"><small><strong>The Official Chart Company</strong></small></a> and used under the terms of the Billboard Acceptable Usage limitations.</small></div>		
			</div>

		</div>

	</body>
</html>
