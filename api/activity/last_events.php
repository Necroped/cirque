<?php
	header( "Content-Type: application/json" );
	require_once "../../lib/spdo.class.php";
	require_once "../../lib/functions.php";

	# $_GET['circus'] = 1;
	# $_GET['key'] = sha1("odyssee");

	if ( isset( $_GET['circus'] ) && is_numeric( $_GET['circus'] )
	&& isset( $_GET['key'] )
	&& $_GET['key'] === sha1( "odyssee" ) ):
		$dbh = SPDO::getInstance();
		$stmt = $dbh->prepare( "SELECT id FROM event WHERE circus = :circus ORDER BY fromDate DESC;" );
		$stmt->bindParam( ":circus", $_GET['circus'], PDO::PARAM_INT );
		$stmt->execute();
		$response = array( "error" => false );
		$events = array();
		$events['events'] = $stmt->fetchAll( PDO::FETCH_ASSOC );
		$response = array_merge( $response, $events );
		array_walk_recursive( $response, 'conv' );
		$stmt->closeCursor();
	else:
		$response = array( "error" => true, "stack_trace" => "id error" );
	endif;

	print json_encode( $response );