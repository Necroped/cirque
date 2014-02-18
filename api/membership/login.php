<?php
	header( "Content-Type: application/json" );
	require_once "../../lib/spdo.class.php";

	if ( isset( $_GET['username'] ) && isset( $_GET['password'] )
	&& isset( $_GET['key'] )
	&& $_GET['key'] === sha1( "odyssee" ) ):
		$dbh = SPDO::getInstance();
		$stmt = $dbh->prepare( "SELECT count(*) FROM user 
			WHERE username = :username AND password = sha1(:password);" );
		$stmt->bindParam( "username", $_GET['username'], PDO::PARAM_STR );
		$stmt->bindParam( "password", $_GET['password'], PDO::PARAM_STR );
		$stmt->execute();
		if ( $connect = $stmt->fetch( PDO::FETCH_NUM ) ):
			$response = array( "error" => false );
			if ( !$connect[0] )
				$response["connected"] = false;
			else
				$response["connected"] = true;
		else:
			$response = array( "error" => true, "stack_trace" => "unexpected error" );
		endif;
		$stmt->closeCursor();
	else:
		$response = array( "error" => true, "stack_trace" => "empty" );
	endif;

	print json_encode( $response );