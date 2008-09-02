<?php

defined( '_ACM_VALID' ) or die( 'Direct Access to this location is not allowed.' );

class mysql {
	
	var $host, $user, $pass, $db;

	function mysql($host, $user, $pass, $db) {
		$this->host	= $host;
		$this->user	= $user;
		$this->pass	= $pass;
		$this->db	= $db;
	}

	function connect () {
		global $error, $vm;
		if(!@mysql_connect ($this->host,$this->user,$this->pass)) {
			$error = $vm['_error_db_connect'];
			return false;
		}
		if(!@mysql_select_db ($this->db)) {
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

class mysql_ls extends mysql{
	function mysql_ls() {
		global $ls_host, $ls_user, $ls_pass, $ls_db;
		$this->mysql($ls_host, $ls_user, $ls_pass, $ls_db);
	}
}

class mysql_gs extends mysql{
	function mysql_gs($gs_host, $gs_user, $gs_pass, $gs_db) {
		$this->mysql($gs_host, $gs_user, $gs_pass, $gs_db);
	}
}

?>