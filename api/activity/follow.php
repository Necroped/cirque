<?php
	header( "Content-Type: application/json" );
	require_once "../../lib/spdo.class.php";
	require_once "../../lib/functions.php";

	if ( isset( $_GET['user'] ) && is_numeric( $_GET['user'] )
	&& isset( $_GET['circus'] ) && is_numeric( $_GET['circus'] )
	&& isset( $_GET['key'] )
	&& $_GET['key'] === sha1( "odyssee" ) ):
		$dbh = SPDO::getInstance();
		$stmt = $dbh->prepare( "SELECT COUNT(*) FROM followCircus WHERE user = :user AND circus = :circus;" );
		$stmt->bindParam( ":user", $_GET['user'], PDO::PARAM_INT );
		$stmt->bindParam( ":circus", $_GET['circus'], PDO::PARAM_INT );
		$stmt->execute();
		$following = current( $stmt->fetch( PDO::FETCH_NUM ) );
		$stmt->closeCursor();
		if ( $following ):
			$stmt = $dbh->prepare( "DELETE FROM followCircus WHERE user = :user AND circus = :circus;" );
			$stmt->bindParam( ":user", $_GET['user'], PDO::PARAM_INT );
			$stmt->bindParam( ":circus", $_GET['circus'], PDO::PARAM_INT );
			$stmt->execute();
		else:
			$stmt = $dbh->prepare( "INSERT INTO followCircus (user, circus) VALUES (:user, :circus);" );
			$stmt->bindParam( ":user", $_GET['user'], PDO::PARAM_INT );
			$stmt->bindParam( ":circus", $_GET['circus'], PDO::PARAM_INT );
			$stmt->execute();
		endif;
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