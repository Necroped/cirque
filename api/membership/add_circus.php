<?php
	header( "Content-Type: application/json" );
	require_once "../../lib/spdo.class.php";
	require_once "../../lib/functions.php";

	define( "API_KEY", sha1( "odyssee" ) );

	if ( isset( $_GET['circus'] ) 
	&& !empty( $_GET['circus'] )
	&& isset( $_GET['country'] ) 
	&& !empty( $_GET['country'] )
	&& isset( $_GET['key'] )
	&& $_GET['key'] === sha1( "odysee" ) ):
		$dbh = SPDO::getInstance();
		$circus = $_GET['circus'];
		$countryId = $_GET['country'];
		$stmt = $dbh->prepare( "select name from country where id = :id;" );
		$stmt->bindParam( ":id", $countryId, PDO::PARAM_INT );
		$stmt->execute();
		$country = $stmt->fetch( PDO::FETCH_ASSOC );
		$stmt->closeCursor();
		$country = $country['name'];

		$stmt = $dbh->prepare( "INSERT INTO circus (name, country, description)
			VALUES (:name, :country, :description);" );
		$stmt->bindParam( ":name", $name, PDO::PARAM_STR );
		$stmt->bindParam( ":country", $countryId, PDO::PARAM_INT );
		$stmt->bindParam( ":description", $des, PDO::PARAM_STR );
		if ( $stmt->execute() ):
			$result = array(
				"error" => false,
				"circus" => $name,
				"country" => $countryId,
				"des" => $des
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
