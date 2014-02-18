<?php
	header( "Content-Type: application/json" );
	require_once "../../lib/spdo.class.php";
	require_once "../../lib/functions.php";

	if ( isset( $_GET['name'] ) 
	&& !empty( $_GET['name'] )
	&& isset( $_GET['country'] ) 
	&& !empty( $_GET['country'] )
	&& isset( $_GET['description'] ) 
	&& !empty( $_GET['description'] )
	&& isset( $_GET['user'] )
	&& !empty( $_GET['user'] )
	&& isset( $_GET['key'] )
	&& $_GET['key'] === sha1( "odyssee" ) ):
		$dbh = SPDO::getInstance();
		$name = $_GET['name'];
		$country = $_GET['country'];
		$description = $_GET['description'];
		$user = $_GET['user'];

		$stmt = $dbh->prepare( "INSERT INTO circus (name, country, description)
			VALUES (:name, :country, :description);" );
		$stmt->bindParam( ":name", $name, PDO::PARAM_STR );
		$stmt->bindParam( ":country", $country, PDO::PARAM_INT );
		$stmt->bindParam( ":description", $description, PDO::PARAM_STR );
		if ( $stmt->execute() ):
			$result = array(
				"error" => false,
				"name" => $name,
				"country" => $country,
				"description" => $description
			);
			$stmt->closeCursor();
			$circus = $dbh->lastInsertId();
			$stmt = $dbh->prepare( "insert into manage (user, circus) values (:user, :circus);" );
			$stmt->bindParam( ":user", $user, PDO::PARAM_INT );
			$stmt->bindParam( ":circus", $circus, PDO::PARAM_INT );
			$stmt->execute();
			$stmt->closeCursor();
		else:
			$result = array(
				"error" => true,
				"stack_trace" => "wrong params"
			);
			$stmt->closeCursor();
		endif;
	else:
		$result = array(
				"error" => true,
				"stack_trace" => "bad key / wrong params"
			);
	endif;
	print json_encode( $result );
