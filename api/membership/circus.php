<?php
	header( "Content-Type: application/json" );
	require_once "../../lib/spdo.class.php";

	if ( isset( $_GET['id'] ) && is_numeric( $_GET['id'] ) ):
		$dbh = SPDO::getInstance();
		$stmt = $dbh->prepare( "SELECT * FROM circus WHERE id = :id" );
		$stmt->bindParam( "id", $_GET['id'], PDO::PARAM_INT );
		$stmt->execute();
		if ( $circus = $stmt->fetch( PDO::FETCH_ASSOC ) ):
			$response = array( "error" => false );
			$response = array_merge( $response, $circus );
			$response['name'] = utf8_encode( $response['name'] );
			$response['description'] = utf8_encode( $response['description'] );
		else:
			$response = array( "error" => true, "stack_trace" => "no match" );
		endif;
	else:
		$response = array( "error" => true, "stack_trace" => "id error" );
	endif;

	print json_encode( $response );