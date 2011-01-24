<?php

defined( '_ACM_VALID' ) or die( 'Direct Access to this location is not allowed.' );

class MyException extends Exception {
	
	public function __construct($message=NULL, $code=0){
		parent::__construct($message, $code);
	}
	
	public function __toString() {
		return "[{$this->code}]: {$this->message}\n";
	}
}

class CONFIG {

	private $acm_version = '0.9.8';

	private $login_server = array('hostname'=>'localhost','user'=>'root','password'=>'','database'=>'l2jdb');

	private $game_servers = array();

	private $core_server_name = 'Private Server';
	private $core_act_img = true;
	private $core_spam_try = 5;
	private $core_spam_time = 30;
	private $core_act_email = true;
	private $core_acc_limit = false;
	private $core_same_email = false;
	private $core_id_limit = 15;
	private $core_pwd_limit = 15;
	private $core_email_limit = 255;
	private $core_language = 'english';
	private $core_can_chg_email = false;
	private $core_ack_cond = false;
	private $core_template = 'kamael';
	private $core_interlude = false;

	private $service_allow = false;
	private $service_allow_with_karma = true;
	private $service_name = false;
	private $service_name_regex = '`^[[:alnum:]]{3,16}$`';
	private $service_sex = false;
	private $service_sex_time = 7;
	private $service_sex_item_female = array(8559,8913,8917);
	private $service_sex_item_male = array(8923);
	private $service_fix = false;
	private $service_fix_time = 24;
	private $service_unstuck = false;
	private $service_unstuck_static = false;
	private $service_unstuck_default = array(0,0,0);

	private $email_from = 'support@host.com';
	private $email_smtp_use = false;
	private $email_smtp_address = 'smtp.server.com';
	private $email_smtp_port = 25;
	private $email_smtp_login = '';
	private $email_smtp_password = '';
	private $email_smtp_domain = '';
	
	private $adv_id_regex	= '`^[[:alnum:]]{4,15}$`';		// allow alphanumeric character in login name and login character min needed is 4 and max 15
	private $adv_pwd_regex	= '`^[[:alnum:]@\\\/]{4,15}$`';	//allow alphanumeric character and \ / @ in password and pwd character min needed is 4 and max 15


	private static $instance;

	private function __construct(){}
	
	public function __clone() {
		trigger_error('Clone is not allowed.', E_USER_ERROR);
	}

	public static function g() {
		if (!isset(self::$instance)) {
			$c = __CLASS__;
			self::$instance = new $c;
		}
		return self::$instance;
	}
	
	private function settings () {
	}
	
	// Test if config value is a boolean
	public function cb($var, $val) {
		try {
			if(!is_bool($val))
				throw new MyException('('.gettype($val).')'.var_export($val, 1).' set on '.$var.' must be a bolean');
			$this->$var = $val;
		} catch (MyException $e) {
			echo $e;
		}
	}
	
	// Test if config value is a boolean or an integer
	public function cbi($var, $val) {
		try {
			if(!(is_bool($val) || is_int($val)))
				throw new MyException('('.gettype($val).')'.var_export($val, 1).' set on '.$var.' must be an integer or a bolean');
			$this->$var = $val;
		} catch (MyException $e) {
			echo $e;
		}
	}
	
	// Test if config value is an integer
	public function ci($var, $val) {
		try {
			if(!is_int($val))
				throw new MyException('('.gettype($val).')'.var_export($val, 1).' set on '.$var.' must be an integer');
			$this->$var = $val;
		} catch (MyException $e) {
			echo $e;
		}
	}
	
	// Test if config value is a string
	public function cs($var, $val) {
		try {
			if(!is_string($val))
				throw new MyException('('.gettype($val).')'.var_export($val, 1).' set on '.$var.' must be a string');
			$this->$var = $val;
		} catch (MyException $e) {
			echo $e;
		}
	}
	
	// Test if config value is an array
	public function ca($var, $val) {
		try {
			if(!is_array($val))
				throw new MyException('('.gettype($val).')'.var_export($val, 1).' set on '.$var.' must be an array');
			$this->$var = $val;
		} catch (MyException $e) {
			echo $e;
		}
	}
	
	// Test if config value is an email
	public function ce($var, $val) {
		try {
			if(!preg_match('/^([a-z0-9])(([-a-z0-9._])*([a-z0-9]))*\@([a-z0-9])' . '(([a-z0-9-])*([a-z0-9]))+' . '(\.([a-z0-9])([-a-z0-9_-])?([a-z0-9])+)+$/i', $val))
				throw new MyException('('.gettype($val).')'.var_export($val, 1).' set on '.$var.' must be an email');
			$this->$var = $val;
		} catch (MyException $e) {
			echo $e;
		}
	}
	
	public function add_game_server($value) {
		$this->game_servers[$value['id']] = $value;
	}
	
	public function select_game_server($id) {
		if(empty($this->game_servers[$id]))
			return NULL;
		return $this->game_servers[$id];
	}
	
	public function __get($var) {
		return $this->$var;
	}
	
	public function accessLevel() {
		return ($this->core_interlude) ? 'access_level' : 'accessLevel';
	}
	
	public function regex ($mode){
		return ($mode) ? $this->adv_id_regex : $this->adv_pwd_regex;
	}

}

?>