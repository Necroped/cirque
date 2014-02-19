<?php
	header( "Content-Type: text/plain" );
	require_once "../../lib/spdo.class.php";
	require_once "../../lib/functions.php";

	if ( isset( $_GET['fromDate'] ) 
	&& !empty( $_GET['fromDate'] )
	&& validateDate( $_GET['fromDate'] ) )
	&& isset( $_GET['toDate'] ) 
	&& !empty( $_GET['toDate'] )
	&& validateDate( $_GET['toDate'] ) )
	&& isset( $_GET['city'] ) 
	&& !empty( $_GET['city'] )
	&& isset( $_GET['description'] )
	&& !empty( $_GET['description'] )
	&& isset( $_GET['circus'] )
	&& !empty( $_GET['circus'] )
	&& isset( $_GET['key'] )
	&& $_GET['key'] === sha1( "odyssee" ) ):
		$dbh = SPDO::getInstance();
		$fromDate = $_GET['fromDate'];
		$toDate = $_GET['toDate'];
		$city = $_GET['city'];
		$description = $_GET['description'];
		$circus = $_GET['circus'];

		$stmt = $dbh->prepare( "INSERT INTO event (fromDate, toDate, city, description, circus) VALUES (:fromDate, :toDate, :city, :description, :circus);" );
		$stmt->bindParam( ":fromDate", $fromDate, PDO::PARAM_STR );
		$stmt->bindParam( ":toDate", $password, PDO::PARAM_STR );
		$stmt->bindParam( ":city", $city, PDO::PARAM_INT );
		$stmt->bindParam( ":description", $description, PDO::PARAM_STR );
		$stmt->bindParam( ":circus", $circus, PDO::PARAM_INT );
		if ( $stmt->execute() ):
			$result = array( "error" => false );
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