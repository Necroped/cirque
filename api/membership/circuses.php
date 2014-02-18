<?php
	header( "Content-Type: application/json" );
	require_once "../../lib/spdo.class.php";
	require_once "../../lib/functions.php";

	if ( isset( $_GET['key'] ) && $_GET['key'] === sha1( "odyssee" ) ):
		$dbh = SPDO::getInstance();
		$stmt = $dbh->prepare( "select * from circus where genuine = 1;" );
		$stmt->execute();
		if( $circuses = $stmt->fetchAll( PDO::FETCH_ASSOC ) ):
			$response = array( "error" => false );
			$response = array_merge( $response, array( "circuses" => $circuses ) );
			array_walk_recursive( $response, 'conv' );
		else:
			$response = array( "error" => true, "stack_trace" => "no_match" );
		endif;
		$stmt->closeCursor();
	else:
		$response = array( "error" => true, "stack_trace" => "api key" );
	endif;

	print json_encode( $response );