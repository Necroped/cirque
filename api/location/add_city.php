<?php
	header( "Content-Type: application/json" );
	require_once "../../lib/spdo.class.php";
	require_once "../../lib/functions.php";

	define( "API_KEY", sha1( "odyssee" ) );

	if ( isset( $_GET['city'] ) 
	&& !empty( $_GET['city'] )
	&& isset( $_GET['country'] ) 
	&& !empty( $_GET['country'] )
	&& isset( $_GET['key'] )
	&& $_GET['key'] === sha1( "odysee" ) ):
		$dbh = SPDO::getInstance();
		$city = $_GET['city'];
		$countryId = $_GET['country'];
		$stmt = $dbh->prepare( "select name from country where id = :id;" );
		$stmt->bindParam( ":id", $countryId, PDO::PARAM_INT );
		$stmt->execute();
		$country = $stmt->fetch( PDO::FETCH_ASSOC );
		$stmt->closeCursor();
		$country = $country['name'];

		$json = file_get_contents( "http://maps.googleapis.com/maps/api/geocode/json?address=$city+$country&sensor=true" );
		$data = json_decode( $json );
		
		$lat = $data->results[0]->geometry->location->lat;
		$lng = $data->results[0]->geometry->location->lng;

		$name = $data->results[0]->address_components[0]->long_name;

		$stmt = $dbh->prepare( "INSERT INTO city (name, country, latitude, longitude)
			VALUES (:name, :country, :latitude, :longitude);" );
		$stmt->bindParam( "name", $name, PDO::PARAM_STR );
		$stmt->bindParam( "country", $countryId, PDO::PARAM_INT );
		$stmt->bindParam( "latitude", $lat, PDO::PARAM_STR );
		$stmt->bindParam( "longitude", $lng, PDO::PARAM_STR );
		if ( $stmt->execute() ):
			$result = array(
				"error" => false,
				"city" => $name,
				"country" => $countryId,
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