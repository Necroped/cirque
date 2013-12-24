<?php
	header( "Content-Type: application/json" );
	require_once "../../lib/spdo.class.php";

	if ( isset( $_GET['id'] ) && is_numeric( $_GET['id'] ) ):
		$dbh = SPDO::getInstance();
		$stmt = $dbh->prepare( "SELECT * FROM user WHERE id = :id" );
		$stmt->bindParam( "id", $_GET['id'], PDO::PARAM_INT );
		$stmt->execute();
		if ( $user = $stmt->fetch( PDO::FETCH_ASSOC ) ):
			$response = array( "error" => false );
			$response = array_merge( $response, $user );
			$response['username'] = utf8_encode( $response['username'] );
			$response['firstName'] = utf8_encode( $response['firstName'] );
			$response['lastName'] = utf8_encode( $response['lastName'] );
		else:
			$response = array( "error" => true, "stack_trace" => "no match" );
		endif;
	else:
		$response = array( "error" => true, "stack_trace" => "id error" );
	endif;

	print json_encode( $response );