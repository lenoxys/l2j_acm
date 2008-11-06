<?php

defined( '_ACM_VALID' ) or die( 'Direct Access to this location is not allowed.' );

/**
 *	Lineage II Login class
 */

class login {

	
	private static $instance;

	private function __construct() {
		
	}
	
	public function __clone() {
		trigger_error('Clone is not allowed.', E_USER_ERROR);
	}

	public static function singleton() {
		if (!isset(self::$instance)) {
			$c = __CLASS__;
			self::$instance = new $c;
		}
		return self::$instance;
	}
	
	
}
?>