<?php
	header( "Content-Type: application/json" );
	require_once "../../lib/spdo.class.php";
	require_once "../../lib/functions.php";

	if ( isset( $_GET['circus'] ) && is_numeric( $_GET['circus'] )
	&& isset( $_GET['key'] )
	&& $_GET['key'] === sha1( "odyssee" ) ):
		$dbh = SPDO::getInstance();
		$stmt = $dbh->prepare( "SELECT * FROM event WHERE circus = :circus;" );
		$stmt->bindParam( ":circus", $_GET['circus'], PDO::PARAM_INT );
		$stmt->execute();
		$response = array( "error" => false );
		while ( $event = $stmt->fetch( PDO::FETCH_ASSOC ) ):
			$response = array_merge( $response, $event );
			array_walk_recursive( $response, 'conv' );
		endwhile;
		$stmt->closeCursor();
	else:
		$response = array( "error" => true, "stack_trace" => "id error" );
	endif;

	print json_encode( $response );