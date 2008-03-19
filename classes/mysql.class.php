<?php

defined( '_ACM_VALID' ) or die( 'Direct Access to this location is not allowed.' );

class mysql {

	function mysql() {
	}

	function connect () {
		global $host, $user, $pass, $db, $error, $vm;
		if(!@mysql_connect ($host,$user,$pass)) {
			$error = $vm['_error_db_connect'];
			return false;
		}
		if(!@mysql_select_db ($db)) {
			$error = $vm['_error_db_select'];
			return false;
		}
		return true;
	}

	function query ($q) {
		return @mysql_query ($q);
	}

	function result ($q) {
		return @mysql_result (@mysql_query($q), 0);
	}

	function close () {
		@mysql_close ();
	}
}
?>