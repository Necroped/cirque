<?php
	header( "Content-Type: application/json" );
	require_once "../../lib/spdo.class.php";
	require_once "../../lib/functions.php";

	if ( isset( $_GET['id'] ) && is_numeric( $_GET['id'] ) ):
		$dbh = SPDO::getInstance();
		$stmt = $dbh->prepare( "SELECT * FROM city WHERE id = :id;" );
		$stmt->bindParam( "id", $_GET['id'], PDO::PARAM_INT );
		$stmt->execute();
		if ( $city = $stmt->fetch( PDO::FETCH_ASSOC ) ):
			$response = array( "error" => false );
			$response = array_merge( $response, $city );
			array_walk_recursive( $response, 'conv' );
		else:
			$response = array( "error" => true, "stack_trace" => "no match" );
		endif;
		$stmt->closeCursor();
	else:
		$response = array( "error" => true, "stack_trace" => "id error" );
	endif;

	print json_encode( $response );