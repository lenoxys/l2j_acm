<?php

defined( '_ACM_VALID' ) or die( 'Direct Access to this location is not allowed.' );

class mysql {

	private static $_instances = array();
	
	private $_m, $_host, $_user, $_pass, $_db;

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
		
		if(empty($this->pass))
			DEBUG::add('Your configuration file contains settings ('.$this->_user.' with no password) that correspond to the default MySQL privileged account.'.
			'Your MySQL server is running with this default, is open to intrusion, and you really should fix this security hole.', 'red');
			
		$this->connect();
	}

	private function connect () {
		if(!@mysql_connect ($this->_host,$this->_user,$this->_pass)) {
			MSG::add_error(LANG::i18n('_error_db_connect'));
			return false;
		}
		if(!@mysql_select_db ($this->_db)) {
			MSG::add_error(LANG::i18n('_error_db_select'));
			return false;
		}
		return true;
	}

	public function query ($q) {
		DEBUG::add($this->_db.'->'.$q);
		LOGDAEMON::add($this->_db.'->'.$q);
		$rslt = @mysql_query ($q);
		DEBUG::add('Records: '.@mysql_affected_rows());
		return $rslt;
	}

	public function result ($q) {
		DEBUG::add($this->_db.'->'.$q);
		LOGDAEMON::add($this->_db.'->'.$q);
		$rslt = @mysql_result (@mysql_query ($q), 0);
		DEBUG::add('Result: '.gettype($rslt).'('.var_export($rslt, true).')');
		return $rslt;
	}

	public function __destruct () {
		@mysql_close ();
	}
}

?>