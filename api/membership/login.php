<?php
	header( "Content-Type: application/json" );
	require_once "../../lib/spdo.class.php";

	if ( isset( $_GET['username'] ) && isset( $_GET['password'] )
	&& isset( $_GET['key'] )
	&& $_GET['key'] === sha1( "odyssee" ) ):
		$dbh = SPDO::getInstance();
		$stmt = $dbh->prepare( "SELECT id FROM user 
			WHERE username = :username AND password = sha1(:password);" );
		$stmt->bindParam( "username", $_GET['username'], PDO::PARAM_STR );
		$stmt->bindParam( "password", $_GET['password'], PDO::PARAM_STR );
		$stmt->execute();
		if ( $id = $stmt->fetch( PDO::FETCH_NUM ) ):
			$response = array( "error" => false );
			$response["connected"] = $id[0];
		else:
			$response = array( "error" => true, "stack_trace" => "wrong username or password" );
		endif;
		$stmt->closeCursor();
	else:
		$response = array( "error" => true, "stack_trace" => "empty" );
	endif;

	print json_encode( $response );