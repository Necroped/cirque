<?php
	/**
	 * Front controller
	 * @author Antoine De Gieter
	 */

	include_once "config/config.inc";

	if ( isset( $_GET['page'] ) && in_array( $_GET['page'], $allowedPages ) ):
		include_once "controllers/" . $_GET['page'] . ".php";
	endif;
