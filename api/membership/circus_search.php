<?php
	header( "Content-Type: application/json" );
	require_once "../../lib/spdo.class.php";
	require_once "../../lib/functions.php";

	if ( isset( $_GET['name'] ) && !empty( $_GET['name'] ) ):
		$dbh = SPDO::getInstance();
		$stmt = $dbh->prepare( "SELECT id, name, country, 
			description, picture FROM circus WHERE name like :name;");
		$name = "%".$_GET['name']."%";
		$stmt->bindParam( "name", $name, PDO::PARAM_STR );
		$stmt->execute();
		if ( $circuses = $stmt->fetchAll( PDO::FETCH_ASSOC ) ):
			$response = array( "error" => false );
			foreach ( $circuses as &$circus ):
				if ( (int)$circus["picture"] === 0 ):
					$circus["picture"] = "http://" . $_SERVER['HTTP_HOST'] .
						relPathToUri( "../../global/img/upload/circuses/no.jpg" );
				else:
					$circus["picture"] = "http://" . $_SERVER['HTTP_HOST'] .
						relPathToUri( "../../global/img/upload/circuses/" . $circus["id"] . ".jpg" );
				endif;
			endforeach;
			$response["circuses"] = $circuses;
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
		$response = array( "error" => true, "stack_trace" => "empty" );
	endif;
	print json_encode( $response );