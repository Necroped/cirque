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

	/**
	 * conv
	 *
	 * @author Antoine De Gieter
	 * @param &$value: string
	 * 		value that is going to be converted into utf8
	 *
	 */
	function conv( &$value, $key ) {
		if ( is_string( $value ) )
			$value = iconv( 'windows-1252', 'utf-8', $value );
	} 

	/**
	 * validateDate
	 * 
	 * @author Antoine De Gieter
	 * @param $date
	 *
	 * @param [[$format]]
	 *
	 * @return
	 *
	 */
	function validateDate( $date, $format = 'Y-m-d H:i:s' ) {
		$d = DateTime::createFromFormat($format, $date);
		return $d && $d->format( $format ) === $date;
	}