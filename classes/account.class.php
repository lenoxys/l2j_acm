<?php

defined( '_ACM_VALID' ) or die( 'Direct Access to this location is not allowed.' );

class account extends login{

	var $login, $password;
	
	private static $instance;

	private function __construct() {
		global $MYSQL_LS;
		$this->MYSQL = $MYSQL_LS;
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

	public function load() {
		if(empty($_SESSION['acm']))			// Check if user is logged
			return ACCOUNT::singleton();
		
		return unserialize($_SESSION['acm']);
	}

	function getLogin() {
		return $this->login;
	}

	function setLogin($login) {
		$this->login = $login;
	}

	function create ($login, $pwd, $repwd, $email, $img) {
		global $vm, $error, $act_email;

		if(!$this->verif_limit_create()) {
			$error = $vm['_REGWARN_LIMIT_CREATING'];
			return false;
		}

		if($login == '') {
			$error = $vm['_REGWARN_UNAME1'];
			return false;
		}

		if(!$this->verif_char($login, true)) {
			$error = $vm['_REGWARN_UNAME2'];
			return false;
		}

		if($this->is_login_exist($login)) {
			$error = $vm['_REGWARN_INUSE'];
			return false;
		}

		if($pwd != $repwd) {
			$error = $vm['_REGWARN_VPASS2'];
			return false;
		}

		if(!$this->verif_char($pwd)) {
			$error = $vm['_REGWARN_VPASS1'];
			return false;
		}

		if(!$this->verif_email($email)) {
			$error = $vm['_REGWARN_MAIL'];
			return false;
		}

		if($this->is_email_exist($email)) {
			$error = $vm['_REGWARN_EMAIL_INUSE'];
			return false;
		}

		if(!$this->verif_img($img)) {
			$error = $vm['_image_control'];
			return false;
		}

		$this->login = $login;
		$this->code = $this->gen_img_cle(10);

		$sql = "INSERT INTO `accounts` (`login`,`password`,`lastactive`,`accessLevel`,`lastIP`,`email`) VALUES " .
				"('".$login."', '".$this->l2j_encrypt($pwd)."', '".time()."', '-1', '".$_SERVER['REMOTE_ADDR']."', '".$email."');";

		DEBUG::add('Create a new user on the accounts table with -1 on accessLevel');

		$this->MYSQL->query($sql);

		if(!$this->is_login_exist($login)) {
			$error = $vm['_creating_acc_prob'];
			return false;
		}

		DEBUG::add('Insert the activation key on account_data for checking email');
		$sql = "REPLACE INTO account_data (account_name, var, value) VALUES ('".$login."' , 'activation_key', '".$this->code."');";
		$this->MYSQL->query($sql);

		if(!$act_email)
			$this->valid_account($this->code);
		else
			EMAIL::OP()->operator($this, 'created_account_validation');

		return true;
	}

	function get_number_acc() {
		DEBUG::add('Get the amounth of account on accounts table');
		$sql = "SELECT COUNT(login) FROM `accounts`";
		return $this->MYSQL->result($sql);
	}

	function verif_limit_create () {
		global $acc_limit;

		if ($acc_limit == false)
			return true;

		if ($this->get_number_acc() >= $acc_limit)
			return false;

		return true;
	}

	function verif_char($pwd, $mode = false) {
		global $id_regex, $pwd_regex;

		$regex = ($mode) ? $id_regex : $pwd_regex;

		if (!preg_match($regex , $pwd))
			return false;

		return true;
	}

	function verif_email($email) {

		if (!ereg("^[^@ ]+@[^@ ]+\.[^@ \.]+$", $email))
			return false;

		return true;
	}

	function verif_img($key) {
		global $act_img;

		DEBUG::add('Check if the image verification is needed');

		if (!$act_img) {
			DEBUG::add(' -> No need image verification');
			return true;
		}

		DEBUG::add('Check if the image verification is correct');
		
		if ($key != $_SESSION['code']) {
			DEBUG::add('<li> key gived: '.$key.'</li><li> key needed: '.$_SESSION['code'].'</li>');
			return false;
		}

		return true;
	}

	function is_login_exist($login) {
		$sql = 'SELECT COUNT(login) ' .
				'FROM accounts ' .
					'WHERE login = "'.$login.'" LIMIT 1;';

		DEBUG::add('Check if the login still exist');


		if($this->MYSQL->result($sql) == '0')
			return false;

		return true;
	}

	function is_email_exist($email) {
		global $same_email;

		if($same_email)				// if we allow account with same email
			return false;

		$sql = 'SELECT COUNT(login) ' .
				'FROM accounts ' .
					'WHERE email = "'.$email.'" LIMIT 1;';

		DEBUG::add('Check if the email still exist');


		if($this->MYSQL->result($sql) == '0')
			return false;

		return true;
	}

	function valid_key($key) {
		$sql = "SELECT COUNT(account_data) FROM `account_data` WHERE `var` = 'activation_key' AND `value` = '".$key."' LIMIT 1;";
		DEBUG::add('Check if there are an activation key on account_data');
		if ($this->MYSQL->result($sql) === '0')
			return false;
		$sql = "SELECT account_name FROM `account_data` WHERE `var` = 'activation_key' AND `value` = '".$key."' LIMIT 1;";
		DEBUG::add('Get the account name linked with the activation key');
		return $this->MYSQL->result($sql);
	}

	function valid_account($key) {

		if (!($login = $this->valid_key($key)))
			return false;

		$sql = "UPDATE `accounts` SET `accessLevel` = '0' WHERE `login` = '".$login."' LIMIT 1;";
		DEBUG::add('Update accessLevel to 0');
		$this->MYSQL->query($sql);

		$sql = "DELETE FROM `account_data` WHERE `account_name` = '".$login."' AND `var` = 'activation_key' AND `value` = '".$key."' LIMIT 1;";
		DEBUG::add('Delete activation key from account_data table');
		$this->MYSQL->query($sql);

		if ($this->valid_key($key))
			return false;

		$this->login = $login;

		EMAIL::OP()->operator($this, 'created_account_activation');

		return true;
	}

	function auth ($login, $password, $img) {
		global $error, $vm;

		if(!$this->verif_img($img)) {
			$error = $vm['_image_control']. '<br />';
			return false;
		}

		$this->login = htmlentities($login);
		$this->password = htmlentities($password);

		$this->password = $this->l2j_encrypt($this->password);

		$sql = 'SELECT COUNT(login) ' .
				'FROM accounts ' .
					'WHERE login = "'.$this->login.'" ' .
						'AND password = "'.$this->password.'" ' .
						'AND accessLevel >= 0 LIMIT 1;';
		DEBUG::add('Check if login and password match on account table');

		if($this->MYSQL->result($sql) != 1)
			return false;
			
		$this->update_last_active();

		$_SESSION['acm'] = serialize($this);
		
		return true;
	}
	
	function update_last_active() {
	
		DEBUG::add('Update last connexion of the account');
		$sql = "UPDATE `accounts` SET `lastactive` = '" . time() . "',
				 `lastIP` = '" . $_SERVER['REMOTE_ADDR'] . "'
				 WHERE `login` = '" . $this->login . "' LIMIT 1;";
		$this->MYSQL->query($sql);
		
	}

	function change_pwd($pwd) {
	
		DEBUG::add('Update password of the account');
		$sql = "UPDATE `accounts` SET `password` = '" . $this->l2j_encrypt($pwd) . "',
				 `lastIP` = '" . $_SERVER['REMOTE_ADDR'] . "'
				 WHERE `login` = '" . $this->login . "' LIMIT 1;";
		$this->MYSQL->query($sql);

		$this->code = $pwd;
		EMAIL::OP()->operator($this, 'password_reseted');
	}

	function forgot_pwd($login, $email, $img = null)
	{
		global $error, $vm;

		if(!$this->verif_img($img)) {
			$error = $vm['_image_control'];
			return false;
		}
		
		DEBUG::add('Check if there are a login name match with an email');
		$sql = "SELECT COUNT(login) FROM `accounts` WHERE `login` = '".$login."' AND `email` = '".$email."'";
		
		if($this->MYSQL->result($sql) != 1) {
			$error = $vm['_wrong_auth'];
			return false;
		}

		$this->setLogin($login);
		$this->code = $this->gen_img_cle(5);

		DEBUG::add('Insert a random key and send it to the email for authenticate user');
		$sql = "REPLACE INTO account_data (account_name, var, value) VALUES('".$this->login."' , 'forget_pwd', '".$this->code."')";
		$this->MYSQL->query($sql);

		EMAIL::OP()->operator($this, 'forget_password_validation');

		return true;
	}

	function forgot_pwd2($login, $key)
	{
		global $vm, $error;

		if(!$this->verif_tag($login, 'forget_pwd', $key)) {
			$error = $vm['_activation_control'];
			return false;
		}

		DEBUG::add('User has been authenticated. Delete the ask');
		$sql = "DELETE FROM `account_data` WHERE `account_name` = '".$login."' AND `var` = 'forget_pwd' AND `value` = '".$key."' LIMIT 1;";
		$this->MYSQL->query($sql);

		$this->setLogin($login);
		
		$pwd = $this->gen_img_cle(10);
		$this->change_pwd($pwd);

		return true;
	}

	function verif_tag($login, $tag, $value){
		$sql = "SELECT COUNT(account_name) FROM `account_data` WHERE " .
				"`account_name` = '".$login."' " .
				"AND `var` = '".$tag."' " .
				"AND `value` = '".$value."' LIMIT 1;";
		DEBUG::add('Check the tag on account_data');


		if($this->MYSQL->result($sql) != 1)
			return false;

		return true;
	}

	function edit_password ($pass,$newpass,$renewpass)
	{
		global $vm, $error;

		if($this->password != $this->l2j_encrypt($pass)) {
			$error = $vm['_REGWARN_VPASS1'];
			return false;
		}

		if(!$this->verif_char($newpass)) {
			$error = $vm['_REGWARN_VPASS1'];
			return false;
		}

		if ($newpass != $renewpass) {
			$error = $vm['_REGWARN_VPASS2'];
			return false;
		}

		$this->change_pwd($newpass);

		$_SESSION['acm'] = serialize(new account($this->login, $this->l2j_encrypt($newpass)));

		return true;
	}
	
	function can_chg_email() {
		global $can_chg_email;
		
		if($this->get_email() == '')
			return true;
		
		if(!$can_chg_email)
			return false;

		return true;
	}

	function change_email($email) {

		$sql = "UPDATE `accounts` SET `email` = '" . $email . "',
				 `lastIP` = '" . $_SERVER['REMOTE_ADDR'] . "'
				 WHERE `login` = '" . $this->login . "' LIMIT 1;";

		DEBUG::add('Update the email on accounts table');

		$this->MYSQL->query($sql);

		return true;
	}

	function get_email ()
	{
		DEBUG::add('Get the email of the user');
		$sql = "SELECT email FROM accounts WHERE login = '" . $this->login . "' LIMIT 1;";
		return $this->MYSQL->result($sql);
	}

	function valid_email($login, $key) {
		$sql = "SELECT COUNT(var) FROM `account_data` WHERE `account_name` = '".$login."' AND `value` = '".$key."' LIMIT 1;";
		DEBUG::add('Check if there are an activation key on account_data');
		if ($this->MYSQL->result($sql) === '0')
			return false;
		$sql = "SELECT var FROM `account_data` WHERE `account_name` = '".$login."' AND `value` = '".$key."' LIMIT 1;";
		DEBUG::add('Get the account name linked with the activation key');
		return $this->MYSQL->result($sql);
	}

	function email_validation($login, $key) {

		if (!($email = $this->valid_email($login, $key)))
			return false;

		$sql = "DELETE FROM `account_data` WHERE `account_name` = '".$login."' AND `value` = '".$key."' LIMIT 1;";
		DEBUG::add('Delete activation key from account_data table');
		$this->MYSQL->query($sql);

		if ($this->valid_key($login, $key))
			return false;

		$this->change_email($email);

		EMAIL::OP()->operator($this, 'created_account_activation');

		return true;
	}

	function edit_email ($pass,$email,$reemail)
	{
		global $vm, $error;

		if($this->password != $this->l2j_encrypt($pass)) {
			$error = $vm['_REGWARN_VPASS1'];
			return false;
		}

		if(!$this->verif_email($email)) {
			$error = $vm['_REGWARN_MAIL'];
			return false;
		}

		if($this->is_email_exist($email)) {
			$error = $vm['_REGWARN_EMAIL_INUSE'];
			return false;
		}

		if ($email != $reemail) {
			$error = $vm['_REGWARN_VEMAIL1'];
			return false;
		}
		
		$this->code = $this->gen_img_cle(10);

		DEBUG::add('Insert the activation key on account_data for checking email');
		$sql = "REPLACE INTO account_data (account_name, var, value) VALUES ('".$this->login."' , '".$email."', '".$this->code."');";
		$this->MYSQL->query($sql);
		
		$this->email = $email;
		
		EMAIL::OP()->operator($this, 'email_validation');

		return true;
	}

	function is_logged () {
		return (!empty($_SESSION['acm'])) ? true : false;
	}

	function loggout () {
		$_SESSION = array();
		session_destroy();
		return true;
	}

	function verif () {

		if(!$this->is_logged())			// Check if user is logged
			return false;

		$account = unserialize($_SESSION['acm']);

		$sql = 'SELECT COUNT(login) ' .
				'FROM accounts ' .
					'WHERE login = "'.$account->login.'" ' .
						'AND password = "'.$account->password.'" ' .
						'AND accessLevel >= 0 LIMIT 1;';

		DEBUG::add('Verify if the user is correctly logged');


		if($this->MYSQL->result($sql) != 1)	// Check if user session data are right
			return false;

		return true;
	}

	function gen_img_cle($num = 5) {
		$key = '';
		$chaine = "ABCDEF123456789";
		for ($i=0;$i<$num;$i++) $key.= $chaine[rand()%strlen($chaine)];
		return $key;
	}

	// ----------------------------------------------------------------
	// Copyright to ACM manager
		function l2j_encrypt ($pass) {return base64_encode(pack("H*", sha1(utf8_encode($pass))));}
	// ----------------------------------------------------------------
}
?>