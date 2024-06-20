<?php

defined( '_ACM_VALID' ) or die( 'Direct Access to this location is not allowed.' );

class mysql {

	private static $_instances = array();
	
	private $_m, $_host, $_user, $_pass, $_db, $db_inst;

	public static function g($id = NULL) {
		if (!isset(self::$_instances[$id])) {
			$c = __CLASS__;
			self::$_instances[$id] = new $c($id);
		}
		return self::$_instances[$id];
	}

	private function __construct($id) {
	
		if(is_null($id)) {
			$ls = CONFIG::g()->login_server;
			$this->_host	= $ls['hostname'];
			$this->_user	= $ls['user'];
			$this->_pass	= $ls['password'];
			$this->_db		= $ls['database'];
		}else{
			$gs = CONFIG::g()->select_game_server($id);
			$this->_host	= $gs['hostname'];
			$this->_user	= $gs['user'];
			$this->_pass	= $gs['password'];
			$this->_db		= $gs['database'];
		}
		
		if(empty($this->_pass))
			DEBUG::add('Your configuration file contains settings ('.$this->_user.' with no password) that correspond to the default MySQL privileged account.'.
			'Your MySQL server is running with this default, is open to intrusion, and you really should fix this security hole.', 'red');
			
		$this->connect();
	}

	private function connect () {
		$this->db_inst = @mysqli_connect ($this->_host,$this->_user,$this->_pass);
		if(!$this->db_inst) {
			MSG::add_error(LANG::i18n('_error_db_connect'));
			return false;
		}
		if(!@mysqli_select_db ($this->db_inst, $this->_db)) {
			MSG::add_error(LANG::i18n('_error_db_select'));
			return false;
		}
		return true;
	}

	public function query ($q) {
		DEBUG::add($this->_db.'->'.$q);
		LOGDAEMON::add($this->_db.'->'.$q);
		$rslt = @mysqli_query($this->db_inst, $q);
		DEBUG::add('Records: '.@mysqli_affected_rows($this->db_inst));
		return $rslt;
	}

	public function result ($q) {
		DEBUG::add($this->_db.'->'.$q);
		LOGDAEMON::add($this->_db.'->'.$q);
		$query_result = @mysqli_query($this->db_inst, $q);
		$rslt = @mysqli_fetch_row($query_result)[0];
		DEBUG::add('Result: '.gettype($rslt).'('.var_export($rslt, true).')');
		return $rslt;
	}
	
	public function escape_string($q) {
		return mysqli_real_escape_string($this->db_inst, $q);
	}

	public function __destruct () {
		@mysqli_close ($this->db_inst);
	}
}

?>