<?php
	header( "Content-Type: application/json" );
	require_once "../../lib/spdo.class.php";
	require_once "../../lib/functions.php";

	if ( isset( $_GET['circus'] ) && is_numeric( $_GET['circus'] )
	&& isset( $_GET['key'] )
	&& $_GET['key'] === sha1( "odyssee" ) ):
		$dbh = SPDO::getInstance();
		$stmt = $dbh->prepare( "SELECT COUNT(*) FROM followCircus WHERE circus = :circus;" );
		$stmt->bindParam( ":circus", $_GET['circus'], PDO::PARAM_INT );
		$stmt->execute();
		$count = current( $stmt->fetch( PDO::FETCH_NUM ) );
		$response = array( 
			"error" => false,
			"count" => $count
		);
		$stmt->closeCursor();
	else:
		$response = array( "error" => true, "stack_trace" => "id error" );
	endif;

	print json_encode( $response );