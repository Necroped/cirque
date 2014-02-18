<?php
	header( "Content-Type: application/json" );
	require_once "../../lib/spdo.class.php";
	require_once "../../lib/functions.php";

	if ( isset( $_GET['id'] ) && is_numeric( $_GET['id'] )
	&& isset( $_GET['key'] )
	&& $_GET['key'] === sha1( "odyssee" ) ):

		$dbh = SPDO::getInstance();
		$stmt = $dbh->prepare( "delete from user where id = :id" );
		$stmt->bindParam( ":id", $_GET['id'], PDO::PARAM_INT );
		$stmt->execute();
		$stmt->closeCursor();

		if ( file_exists( "../../global/img/upload/users/" . $_GET['id'] . ".jpg" ) ):
			//unset( "../../global/img/upload/users/" . $_GET['id'] . ".jpg" );
		endif;

		$response = array( "error" => false );
		
	else:

		$response = array( "error" => true, "stack_trace" => "id error / api key error" );

	endif;

	print json_encode( $response );
