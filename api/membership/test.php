<?php
	header("Content-Type: text/plain");
	
	echo "http://" . $_SERVER['HTTP_HOST'] . relativePathToUrl( "../../global/img/upload/users/" ) . 500 . ".jpg";
	echo PHP_EOL;

	function relativePathToUrl( $path ) {
		$uri = explode( '/', $_SERVER['REQUEST_URI'] );
		$path = explode( "/", $path );
		unset( $uri[count( $uri ) - 1] );
		foreach( $path as $dir ):
			if ( $dir === ".." ):
				unset( $uri[count( $uri ) - 1] );
			elseif ( $dir === "." ):
				continue;
			else:
				$uri[] = $dir;
			endif;
		endforeach;
		$uri = implode( "/", $uri );
		return $uri;
	}