<?php
	header( "Content-Type: application/json" );
	require_once "../../lib/spdo.class.php";
	require_once "../../lib/functions.php";

	if ( isset( $_GET['id'] ) && is_numeric( $_GET['id'] ) ):
		$dbh = SPDO::getInstance();
		$stmt = $dbh->prepare( "SELECT * FROM user WHERE id = :id;" );
		$stmt->bindParam( "id", $_GET['id'], PDO::PARAM_INT );
		$stmt->execute();
		if ( $user = $stmt->fetch( PDO::FETCH_ASSOC ) ):
			$response = array( "error" => false );
			$response = array_merge( $response, $user );
			array_walk_recursive( $response, function( &$value, $key ) {
				if ( is_string( $value ) ) {
					$value = iconv( 'windows-1252', 'utf-8', $value );
				}
			});
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