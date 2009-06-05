<?php

defined( '_ACM_VALID' ) or die( 'Direct Access to this location is not allowed.' );

class account{

	private $login = NULL;
	private $password = NULL;
	private $lastactive = NULL;
	private $accessLevel = NULL;
	private $ip = NULL;
	private $lastServer = NULL;
	private $email = NULL;
	private $created_time = NULL;
	
	private static $instance;

	private function __construct() {}
	
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
	
	public function save() {
		$_SESSION['acm'] = serialize($this);
	}

	public function getLogin() {
		return $this->login;
	}

	public function getEmail() {
		return $this->email;
	}

	public function setLogin($login) {
		$this->login = $login;
	}

	public function create ($login, $pwd, $repwd, $email, $img = null) {

		if(!$this->verif_img($img)) {
			MSG::add_error(LANG::i18n('_image_control'));
			return false;
		}
		
		if($login == '') {
			MSG::add_error(LANG::i18n('_REGWARN_UNAME1'));
			return false;
		}

		if(!$this->verif_char($login, true)) {
			MSG::add_error(LANG::i18n('_REGWARN_UNAME2'));
			return false;
		}

		if(!$this->verif_char($pwd)) {
			MSG::add_error(LANG::i18n('_REGWARN_VPASS1'));
			return false;
		}

		if($login == $pwd) {
			MSG::add_error(LANG::i18n('_REGWARN_UNAME3'));
			return false;
		}

		if($pwd != $repwd) {
			MSG::add_error(LANG::i18n('_REGWARN_VPASS2'));
			return false;
		}

		if(!$this->verif_limit_create()) {
			MSG::add_error(LANG::i18n('_REGWARN_LIMIT_CREATING'));
			return false;
		}

		if($this->is_login_exist($login)) {
			MSG::add_error(LANG::i18n('_REGWARN_INUSE'));
			return false;
		}

		if(!$this->verif_email($email)) {
			MSG::add_error(LANG::i18n('_REGWARN_MAIL'));
			return false;
		}

		if($this->is_email_exist($email)) {
			MSG::add_error(LANG::i18n('_REGWARN_EMAIL_INUSE'));
			return false;
		}

		$code = $this->gen_img_cle(10);

		$sql = "INSERT INTO `accounts` (`login`,`password`,`lastactive`,`".CONFIG::g()->accessLevel()."`,`lastIP`,`email`) VALUES " .
				"('".$login."', '".$this->l2j_encrypt($pwd)."', '".time()."', '-1', '".$_SERVER['REMOTE_ADDR']."', '".$email."');";

		DEBUG::add('Create a new user on the accounts table with -1 on accesslevel');

		MYSQL::g()->query($sql);

		if(!$this->is_login_exist($login)) {
			MSG::add_error(LANG::i18n('_creating_acc_prob'));
			return false;
		}

		DEBUG::add('Insert the activation key on account_data for checking email');
		$sql = "REPLACE INTO account_data (account_name, var, value) VALUES ('".$login."' , 'activation_key', '".$code."');";
		MYSQL::g()->query($sql);

		if(!CONFIG::g()->core_act_email) {
			$this->valid_account($code);
			MSG::add_valid(LANG::i18n('_account_created_act'));
		}else{
			MSG::add_valid(LANG::i18n('_account_created_noact'));
			EMAIL::OP()->operator($login, 'created_account_validation', $code);
		}

		return true;
	}

	private function get_number_acc() {
		DEBUG::add('Get the amounth of account on accounts table');
		$sql = "SELECT COUNT(login) FROM `accounts`";
		return MYSQL::g()->result($sql);
	}

	private function verif_limit_create () {

		if (CONFIG::g()->core_acc_limit == false)
			return true;

		if ($this->get_number_acc() >= CONFIG::g()->core_acc_limit)
			return false;

		return true;
	}

	private function verif_char($string, $mode = false) {

		$regex = CONFIG::g()->regex($mode);

		if (!preg_match($regex , $string))
			return false;

		return true;
	}

	private function verif_email($email) {

		if (!ereg("^[^@ ]+@[^@ ]+\.[^@ \.]+$", $email))
			return false;

		return true;
	}

	private function verif_img($key) {

		DEBUG::add('Check if the image verification is needed');

		if (!CONFIG::g()->core_act_img) {
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

	private function is_login_exist($login) {
		$sql = 'SELECT COUNT(login) ' .
				'FROM accounts ' .
					'WHERE login = "'.$login.'" LIMIT 1;';

		DEBUG::add('Check if the login still exist');

		if(MYSQL::g()->result($sql) == '0')
			return false;

		return true;
	}

	private function is_email_exist($email) {

		if(CONFIG::g()->core_same_email)				// if we allow account with same email
			return false;

		$sql = 'SELECT COUNT(login) ' .
				'FROM accounts ' .
					'WHERE email = "'.$email.'" LIMIT 1;';

		DEBUG::add('Check if the email still exist');

		if(MYSQL::g()->result($sql) == '0')
			return false;

		return true;
	}

	private function valid_key($key) {
		DEBUG::add('Check if there are an activation key on account_data');
		$sql = "SELECT COUNT(account_data) FROM `account_data` WHERE `var` = 'activation_key' AND `value` = '".$key."' LIMIT 1;";
		
		if (MYSQL::g()->result($sql) === '0')
			return false;
		
		DEBUG::add('Get the account name linked with the activation key');
		$sql = "SELECT account_name FROM `account_data` WHERE `var` = 'activation_key' AND `value` = '".$key."' LIMIT 1;";
		
		return MYSQL::g()->result($sql);
	}

	public function valid_account($key) {
		
		if (!($login = $this->valid_key($key)))
			return false;

		DEBUG::add('Update accesslevel to 0');
		$sql = "UPDATE `accounts` SET `".CONFIG::g()->accessLevel()."` = '0' WHERE `login` = '".$login."' LIMIT 1;";
		MYSQL::g()->query($sql);

		DEBUG::add('Delete activation key from account_data table');
		$sql = "DELETE FROM `account_data` WHERE `account_name` = '".$login."' AND `var` = 'activation_key' AND `value` = '".$key."' LIMIT 1;";
		MYSQL::g()->query($sql);

		if ($this->valid_key($key))
			return false;

		EMAIL::OP()->operator($login, 'created_account_activation');

		return true;
	}

	public function auth ($login, $password, $img = null) {
		
		$_SESSION['sp'] = (!empty($_SESSION['sp'])) ? $_SESSION['sp'] : 0;

		if($_SESSION['sp'] > 5) {
			LOGDAEMON::l()->add('Warning : SPAMMING AUTHENTICATION');
			MSG::add_error('Warning : SPAMMING AUTHENTICATION'.'<br />');
			return false;
		}
		
		if(!$this->verif_img($img)) {
			MSG::add_error(LANG::i18n('_image_control'));
			return false;
		}
		
		DEBUG::add('sp='.$_SESSION['sp']);

		$this->login = htmlentities($login);
		$this->password = htmlentities($password);

		$this->password = $this->l2j_encrypt($this->password);

		$sql = 'SELECT COUNT(login) ' .
				'FROM accounts ' .
					'WHERE login = "'.$this->login.'" ' .
						'AND password = "'.$this->password.'" ' .
						'AND '.CONFIG::g()->accessLevel().' >= 0 LIMIT 1;';
		DEBUG::add('Check if login and password match on account table');
		
		LOGDAEMON::l()->add($sql);

		if(MYSQL::g()->result($sql) != 1) {
			$_SESSION['sp'] = (empty($_SESSION['sp'])) ? 1 : ($_SESSION['sp']+1);
			return false;
		}
		
		$this->ip = $_SERVER['REMOTE_ADDR'];
			
		$this->update_last_active();
		
		$this->email = $this->get_email();

		$this->save();
		
		return true;
	}
	
	private function update_last_active() {
	
		DEBUG::add('Update last connexion of the account');
		$sql = "UPDATE `accounts` SET `lastactive` = '" . time() . "',
				 `lastIP` = '" . $_SERVER['REMOTE_ADDR'] . "'
				 WHERE `login` = '" . $this->login . "' LIMIT 1;";
		MYSQL::g()->query($sql);
		
	}

	private function change_pwd($pwd) {
	
		DEBUG::add('Update password of the account');
		$sql = "UPDATE `accounts` SET `password` = '" . $this->l2j_encrypt($pwd) . "',
				 `lastIP` = '" . $_SERVER['REMOTE_ADDR'] . "'
				 WHERE `login` = '" . $this->login . "' LIMIT 1;";
		MYSQL::g()->query($sql);

		EMAIL::OP()->operator($this->login, 'password_reseted', $pwd);
	}

	public function forgot_pwd($login, $email, $img = null)
	{

		if(!$this->verif_img($img)) {
			MSG::add_error(LANG::i18n('_image_control'));
			return false;
		}
		
		DEBUG::add('Check if there are a login name match with an email');
		$sql = "SELECT COUNT(login) FROM `accounts` WHERE `login` = '".$login."' AND `email` = '".$email."'";
		
		if(MYSQL::g()->result($sql) != 1) {
			MSG::add_error(LANG::i18n('_wrong_auth'));
			return false;
		}

		$code = $this->gen_img_cle(5);

		DEBUG::add('Insert a random key and send it to the email for authenticate user');
		$sql = "REPLACE INTO account_data (account_name, var, value) VALUES('".$login."' , 'forget_pwd', '".$code."')";
		MYSQL::g()->query($sql);

		EMAIL::OP()->operator($login, 'forget_password_validation', $code);

		return true;
	}

	public function forgot_pwd2($login, $key)
	{

		if(!$this->verif_tag($login, 'forget_pwd', $key)) {
			MSG::add_error(LANG::i18n('_activation_control'));
			return false;
		}

		DEBUG::add('User has been authenticated. Delete the ask');
		$sql = "DELETE FROM `account_data` WHERE `account_name` = '".$login."' AND `var` = 'forget_pwd' AND `value` = '".$key."' LIMIT 1;";
		MYSQL::g()->query($sql);

		$this->login = $login;
		
		$pwd = $this->gen_img_cle(10);
		$this->change_pwd($pwd);

		return true;
	}

	private function verif_tag($login, $tag, $value){
		$sql = "SELECT COUNT(account_name) FROM `account_data` WHERE " .
				"`account_name` = '".$login."' " .
				"AND `var` = '".$tag."' " .
				"AND `value` = '".$value."' LIMIT 1;";
		DEBUG::add('Check the tag on account_data');


		if(MYSQL::g()->result($sql) != 1)
			return false;

		return true;
	}

	public function edit_password ($pass,$newpass,$renewpass)
	{

		if($this->password != $this->l2j_encrypt($pass)) {
			MSG::add_error(LANG::i18n('_REGWARN_VPASS1'));
			return false;
		}

		if($this->login == $newpass) {
			MSG::add_error(LANG::i18n('_REGWARN_UNAME3'));
			return false;
		}

		if(!$this->verif_char($newpass)) {
			MSG::add_error(LANG::i18n('_REGWARN_VPASS1'));
			return false;
		}

		if ($newpass != $renewpass) {
			MSG::add_error(LANG::i18n('_REGWARN_VPASS2'));
			return false;
		}

		$this->change_pwd($newpass);
		
		$this->auth($this->login, $newpass, $_SESSION['code']);

		return true;
	}
	
	public function can_chg_email() {
		
		if($this->email == '')
			return true;
		
		if(!CONFIG::g()->core_can_chg_email)
			return false;

		return true;
	}

	private function change_email($email) {

		DEBUG::add('Update the email on accounts table');
		$sql = "UPDATE `accounts` SET `email` = '" . $email . "',
				 `lastIP` = '" . $_SERVER['REMOTE_ADDR'] . "'
				 WHERE `login` = '" . $this->login . "' LIMIT 1;";
		MYSQL::g()->query($sql);
		
		$this->email = $email;
		return true;
	}

	private function get_email ()
	{
		DEBUG::add('Get the email of the user');
		$sql = "SELECT email FROM accounts WHERE login = '" . $this->login . "' LIMIT 1;";
		return MYSQL::g()->result($sql);
	}

	private function valid_email($login, $key) {
		$sql = "SELECT COUNT(var) FROM `account_data` WHERE `account_name` = '".$login."' AND `value` = '".$key."' LIMIT 1;";
		DEBUG::add('Check if there are an activation key on account_data');
		if (MYSQL::g()->result($sql) === '0')
			return false;
		$sql = "SELECT var FROM `account_data` WHERE `account_name` = '".$login."' AND `value` = '".$key."' LIMIT 1;";
		DEBUG::add('Get the account name linked with the activation key');
		return MYSQL::g()->result($sql);
	}

	public function email_validation($login, $key) {

		if (!($email = $this->valid_email($login, $key)))
			return false;

		$sql = "DELETE FROM `account_data` WHERE `account_name` = '".$login."' AND `value` = '".$key."' LIMIT 1;";
		DEBUG::add('Delete activation key from account_data table');
		MYSQL::g()->query($sql);

		if ($this->valid_key($login, $key))
			return false;
		
		EMAIL::OP()->operator($login, 'modified_email_activation',$email);		// warn the old email box
			
		$this->change_email($email);
		
		EMAIL::OP()->operator($login, 'modified_email_activation',$email);		// warn the new email box

		return true;
	}

	public function edit_email ($pass,$email,$reemail)
	{

		if($this->password != $this->l2j_encrypt($pass)) {
			MSG::add_error(LANG::i18n('_REGWARN_VPASS1'));
			return false;
		}

		if(!$this->verif_email($email)) {
			MSG::add_error(LANG::i18n('_REGWARN_MAIL'));
			return false;
		}

		if($this->is_email_exist($email)) {
			MSG::add_error(LANG::i18n('_REGWARN_EMAIL_INUSE'));
			return false;
		}

		if ($email != $reemail) {
			MSG::add_error(LANG::i18n('_REGWARN_VEMAIL1'));
			return false;
		}
		
		$code = $this->gen_img_cle(10);

		DEBUG::add('Insert the activation key on account_data for checking email');
		$sql = "REPLACE INTO account_data (account_name, var, value) VALUES ('".$this->login."' , '".$email."', '".$code."');";
		MYSQL::g()->query($sql);
		
		if(!CONFIG::g()->core_act_email) {
			$this->email_validation($this->login, $code);
		}else{
			EMAIL::OP()->operator($this->login, 'email_validation', $code, $email);
		}

		return true;
	}

	private function is_logged () {
		return (!empty($_SESSION['acm'])) ? true : false;
	}

	public function loggout () {
		$_SESSION = array();
		session_destroy();
		return true;
	}

	public function verif () {

		if(!$this->is_logged())			// Check if user is logged
			return false;
		
		if($this->ip != $_SERVER['REMOTE_ADDR']){	// Check if user ip is the same than the first time
			MSG::add_error(LANG::i18n('_logout'));
			$this->loggout();
			return false;
		}

		$account = $this->load();

		$sql = 'SELECT COUNT(login) ' .
				'FROM accounts ' .
					'WHERE login = "'.$account->login.'" ' .
						'AND password = "'.$account->password.'" ' .
						'AND '.CONFIG::g()->accessLevel().' >= 0 LIMIT 1;';

		DEBUG::add('Verify if the user is correctly logged');

		if(MYSQL::g()->result($sql) != 1)	{	// Check if user session data are right
			MSG::add_error(LANG::i18n('_logout'));
			$this->loggout();
			return false;
		}

		return true;
	}

	public function gen_img_cle($num = 5) {
		$key = '';
		$chaine = "ABCDEF123456789";
		for ($i=0;$i<$num;$i++) $key.= $chaine[rand()%strlen($chaine)];
		return $key;
	}

	// ----------------------------------------------------------------
	// Copyright to the first account manager
		public function l2j_encrypt ($pass) {return base64_encode(pack("H*", sha1(utf8_encode($pass))));}
	// ----------------------------------------------------------------
}
?>