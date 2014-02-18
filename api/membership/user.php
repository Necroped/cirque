<?php
	header( "Content-Type: application/json" );
	require_once "../../lib/spdo.class.php";
	require_once "../../lib/functions.php";

	if ( isset( $_GET['id'] ) && is_numeric( $_GET['id'] )
	&& isset( $_GET['key'] )
	&& $_GET['key'] === sha1( "odyssee" ) ):
		$dbh = SPDO::getInstance();
		$stmt = $dbh->prepare( "SELECT * FROM user WHERE id = :id;" );
		$stmt->bindParam( "id", $_GET['id'], PDO::PARAM_INT );
		$stmt->execute();
		if ( $user = $stmt->fetch( PDO::FETCH_ASSOC ) ):
			$response = array( "error" => false );
			$response = array_merge( $response, $user );
			array_walk_recursive( $response, 'conv' );
			$response["picture"] = "http://" . $_SERVER['HTTP_HOST'] .
				relPathToUri( "../../global/img/upload/users/" . $response["id"] . ".jpg" );
		else:
			$response = array( "error" => true, "stack_trace" => "no match" );
		endif;
		$stmt->closeCursor();
	else:
		$response = array( "error" => true, "stack_trace" => "id error" );
	endif;

	print json_encode( $response );