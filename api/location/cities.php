<?php
	header( "Content-Type: application/json" );
	require_once "../../lib/spdo.class.php";
	require_once "../../lib/functions.php";

	$dbh = SPDO::getInstance();
	$stmt = $dbh->prepare( "SELECT * FROM city;" );
	$stmt->execute();
	if ( $cities = $stmt->fetchAll( PDO::FETCH_ASSOC ) ):
		$response = array( "error" => false );
		$response = array_merge( $response, array( "cities" => $cities ) );
		array_walk_recursive( $response, 'conv' );
	else:
		$response = array( "error" => true, "stack_trace" => "no match" );
	endif;
	$stmt->closeCursor();

	print json_encode( $response );