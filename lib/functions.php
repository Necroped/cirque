<?php
	/**
	 * relPathToUri
	 *
	 * @author Antoine De Gieter
	 * @param $path: string
	 *		relative path to the file you want to reach
	 * @return string
	 * 		the uri to the file according to the file 
	 * 		from which you called the function
	 *
	 */
	function relPathToUri( $path ) {
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