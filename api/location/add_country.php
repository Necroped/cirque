<?php
	header( "Content-Type: application/json" );
	require_once "../../lib/spdo.class.php";
	require_once "../../lib/functions.php";

	define( "API_KEY", sha1( "odyssee" ) );

	if ( isset( $_GET['country'] ) 
	&& !empty( $_GET['country'] )
	&& isset( $_GET['key'] )
	&& $_GET['key'] === sha1( "odyssee" ) ):
		$dbh = SPDO::getInstance();
		$country = $_GET['country'];
		$json = file_get_contents( "http://maps.googleapis.com/maps/api/geocode/json?address=$country&sensor=true" );
		$data = json_decode( $json );
		
		$lat = $data->results[0]->geometry->location->lat;
		$lng = $data->results[0]->geometry->location->lng;

		$name = $data->results[0]->address_components[0]->long_name;
		$short_name = $data->results[0]->address_components[0]->short_name;

		$stmt = $dbh->prepare( "INSERT INTO country (name, short_name, latitude, longitude)
			VALUES (:name, :short_name, :latitude, :longitude);" );
		$stmt->bindParam( "name", $name, PDO::PARAM_STR );
		$stmt->bindParam( "short_name", $short_name, PDO::PARAM_STR );
		$stmt->bindParam( "latitude", $lat, PDO::PARAM_STR );
		$stmt->bindParam( "longitude", $lng, PDO::PARAM_STR );
		if ( $stmt->execute() ):
			$result = array(
				"error" => false,
				"country" => $name,
				"short_name" => $short_name,
				"lat" => $lat,
				"lng" => $lng
			);
		else:
			$result = array(
				"error" => true,
				"stack_trace" => "wrong params"
			);
		endif;
		$stmt->closeCursor();
	else:
		$result = array(
				"error" => true,
				"stack_trace" => "bad key / wrong params"
			);
	endif;
	print json_encode( $result );