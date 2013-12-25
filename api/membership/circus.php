<?php
	header( "Content-Type: application/json" );
	require_once "../../lib/spdo.class.php";
	require_once "../../lib/functions.php";

	if ( isset( $_GET['id'] ) && is_numeric( $_GET['id'] ) ):
		$dbh = SPDO::getInstance();
		$stmt = $dbh->prepare( "SELECT * FROM circus WHERE id = :id;" );
		$stmt->bindParam( "id", $_GET['id'], PDO::PARAM_INT );
		$stmt->execute();
		if ( $circus = $stmt->fetch( PDO::FETCH_ASSOC ) ):
			$response = array( "error" => false );
			if ( (int)$circus["picture"] === 0 ):
				$circus["picture"] = "http://" . $_SERVER['HTTP_HOST'] .
					relPathToUri( "../../global/img/upload/circuses/no.jpg" );
			else:
				$circus["picture"] = "http://" . $_SERVER['HTTP_HOST'] .
					relPathToUri( "../../global/img/upload/circuses/" . $circus["id"] . ".jpg" );
			endif;
			$response = array_merge( $response, $circus );
			array_walk_recursive( $response, function( &$value, $key ) {
				if ( is_string( $value ) ) {
					$value = iconv( 'windows-1252', 'utf-8', $value );
				}
			});
		else:
			$response = array( "error" => true, "stack_trace" => "no match" );
		endif;
		$stmt->closeCursor();
	else:
		$response = array( "error" => true, "stack_trace" => "id error" );
	endif;

	print json_encode( $response );