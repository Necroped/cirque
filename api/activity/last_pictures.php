<?php
	header( "Content-Type: application/json" );
	require_once "../../lib/spdo.class.php";
	require_once "../../lib/functions.php";

	if ( isset( $_GET['event'] ) && is_numeric( $_GET['event'] )
	&& isset( $_GET['key'] )
	&& $_GET['key'] === sha1( "odyssee" ) ):
		$from = isset( $_GET['from'] ) ? $_GET['from'] : 0;
		$limit = isset( $_GET['limit'] ) ? $_GET['limit'] : 20;
		$dbh = SPDO::getInstance();
		$stmt = $dbh->prepare( "SELECT * FROM picture WHERE event = :event ORDER BY date DESC LIMIT :from, :limit;" );
		$stmt->bindParam( ":event", $_GET['event'], PDO::PARAM_INT );
		$stmt->bindParam( ":from", $_GET['from'], PDO::PARAM_INT );
		$stmt->bindParam( ":limit", $_GET['limit'], PDO::PARAM_INT );
		$stmt->execute();
		$response = array( "error" => false );
		while ( $picture = $stmt->fetch( PDO::FETCH_ASSOC ) ):
			$response = array_merge( $response, $picture );
			array_walk_recursive( $response, 'conv' );
		endwhile;
		$stmt->closeCursor();
	else:
		$response = array( "error" => true, "stack_trace" => "id error" );
	endif;

	print json_encode( $response );