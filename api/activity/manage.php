<?php
	header( "Content-Type: application/json" );
	require_once "../../lib/spdo.class.php";
	require_once "../../lib/functions.php";

	if ( isset( $_GET['user'] ) && is_numeric( $_GET['user'] )
	&& isset( $_GET['key'] )
	&& $_GET['key'] === sha1( "odyssee" ) ):
		$dbh = SPDO::getInstance();
		$stmt = $dbh->prepare( "SELECT circus FROM manage WHERE user = :user;" );
		$stmt->bindParam( ":user", $_GET['user'], PDO::PARAM_INT );
		$stmt->execute();
		if ( $manage = $stmt->fetch( PDO::FETCH_NUM ) ):
			$response = array(
				"error" => false,
				"circus" => $manage[0]
			);
		else:
			$response = array(
				"error" => false,
				"circus" => 0
			);
		endif;
		$stmt->closeCursor();
	else:
		$response = array( "error" => true, "stack_trace" => "wrong params" );
	endif;

	print json_encode( $response );