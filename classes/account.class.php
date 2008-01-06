<?php

defined( '_ACM_VALID' ) or die( 'Direct Access to this location is not allowed.' );

class account {

	var $login, $password;

	function account($login = null, $password = null) {
		$this->login = $login;
		$this->password = $password;
	}

	function getLogin() {
		return $this->login;
	}

	function setLogin($login) {
		$this->login = $login;
	}

	function create ($login, $pwd, $repwd, $email, $img) {
		global $email_class, $vm, $error, $act_email;

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
		$this->code = ACCOUNT::gen_img_cle(10);

		$sql = "INSERT INTO `accounts` (`login`,`password`,`lastactive`,`access_level`,`lastIP`,`email`) VALUES " .
				"('".$login."', '".ACCOUNT::l2j_encrypt($pwd)."', '".time()."', '-1', '".$_SERVER['REMOTE_ADDR']."', '".$email."');";
		MYSQL::query($sql);

		$sql = "INSERT INTO account_data (account_name, var, value) VALUES ('".$login."' , 'activation_key', '".$this->code."');";
		MYSQL::query($sql);

		if(!$act_email)
			$this->valid_account($this->code);
		else
			$email_class->emailing($this, 'created_account_validation');

		return true;
	}

	function get_number_acc() {
		$sql = "SELECT COUNT(login) FROM `accounts`";
		return MYSQL::result($sql);
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

		if (!$act_img)
			return true;

		if ($key != $_SESSION['code'])
			return false;

		return true;
	}

	function is_login_exist($login) {
		$sql = 'SELECT COUNT(login) ' .
				'FROM accounts ' .
					'WHERE login = "'.$login.'" LIMIT 1;';

		if(MYSQL::result($sql) == '0')
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

		if(MYSQL::result($sql) === '0')
			return false;

		return true;
	}

	function valid_key($key) {
		$sql = "SELECT COUNT(account_data) FROM `account_data` WHERE `var` = 'activation_key' AND `value` = '".$key."' LIMIT 1;";
		if (MYSQL::result($sql) === '0')
			return false;
		$sql = "SELECT account_name FROM `account_data` WHERE `var` = 'activation_key' AND `value` = '".$key."' LIMIT 1;";
		return MYSQL::result($sql);
	}

	function valid_account($key) {
		global $email_class;

		if (!($login = $this->valid_key($key)))
			return false;

		$sql = "UPDATE `accounts` SET `access_level` = '0' WHERE `login` = '".$login."' LIMIT 1;";
		MYSQL::query($sql);

		$sql = "DELETE FROM `account_data` WHERE `account_name` = '".$login."' AND `var` = 'activation_key' AND `value` = '".$key."' LIMIT 1;";
		MYSQL::query($sql);

		if ($this->valid_key($key))
			return false;

		$this->login = $login;

		$email_class->emailing($this, 'created_account_activation');

		return true;
	}

	function auth ($login, $password) {
		global $MYSQL;

		$login = htmlentities($login);
		$password = htmlentities($password);

		$password = ACCOUNT::l2j_encrypt($password);

		$MYSQL->connect();
		$sql = 'SELECT COUNT(login) ' .
				'FROM accounts ' .
					'WHERE login = "'.$login.'" ' .
						'AND password = "'.$password.'" ' .
						'AND access_level >= 0 LIMIT 1;';

		if($MYSQL->result($sql) != 1)
			return false;

		$_SESSION['acm'] = serialize(new account($login, $password));
		$MYSQL->close();
		return true;
	}

	function change_pwd($pwd) {
		global $MYSQL, $email_class;

		$MYSQL->connect();
		$sql = "UPDATE `accounts` SET `password` = '" . $this->l2j_encrypt($pwd) . "',
				 `lastIP` = '" . $_SERVER['REMOTE_ADDR'] . "'
				 WHERE `login` = '" . $this->login . "' LIMIT 1;";
		$MYSQL->query($sql);
		$MYSQL->close();

		$this->code = $pwd;
		$email_class->emailing($this, 'password_reseted');
	}

	function forgot_pwd($login, $email, $img = null)
	{
		global $error, $vm, $MYSQL, $email_class;

		if(!$this->verif_img($img)) {
			$error = $vm['_image_control'];
			return false;
		}

		$sql = "SELECT COUNT(account_name) FROM `account_data` WHERE `account_name` = '".$login."' AND `var` = 'forget_pwd'";

		if($MYSQL->result($sql) == 1) {
			$sql = "DELETE FROM `account_data` WHERE `account_name` = '".$login."' AND `var` = 'forget_pwd' LIMIT 1;";
			$MYSQL->query($sql);
		}

		$sql = "SELECT COUNT(login) FROM `accounts` WHERE `login` = '".$login."' AND `email` = '".$email."'";

		if($MYSQL->result($sql) != 1) {
			$error = $vm['_wrong_auth'];
			return false;
		}

		$this->setLogin($login);
		$this->code = $this->gen_img_cle(5);

		$sql = "INSERT INTO account_data (account_name, var, value) VALUES('".$this->login."' , 'forget_pwd', '".$this->code."')";
		$MYSQL->query($sql);

		$email_class->emailing($this, 'forget_password_validation');

		return true;
	}

	function forgot_pwd2($login, $key)
	{
		global $vm, $error, $MYSQL;
		$pwd = $this->gen_img_cle(10);

		if(!$this->verif_tag($login, 'forget_pwd', $key)) {
			$error = $vm['_activation_control'];
			return false;
		}

		$sql = "DELETE FROM `account_data` WHERE `account_name` = '".$login."' AND `var` = 'forget_pwd' AND `value` = '".$key."' LIMIT 1;";
		$MYSQL->query($sql);

		$this->setLogin($login);

		$this->change_pwd($pwd);

		return true;
	}

	function verif_tag($login, $tag, $value){
		global $MYSQL;
		$sql = "SELECT COUNT(account_name) FROM `account_data` WHERE " .
				"`account_name` = '".$login."' " .
				"AND `var` = '".$tag."' " .
				"AND `value` = '".$value."' LIMIT 1;";

		if($MYSQL->result($sql) != 1)
			return false;

		return true;
	}

	function edit_password ($oldpass,$newpass,$renewpass)
	{
		global $vm, $error;

		if($this->password != ACCOUNT::l2j_encrypt($oldpass)) {
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

		$_SESSION['acm'] = serialize(new account($this->login, ACCOUNT::l2j_encrypt($newpass)));

		//$this->send_email($this->lg_email_title_change_pwd, $this->lg_email_message_change_pwd);

		return true;
	}

	function is_logged () {
		return (!empty($_SESSION['acm'])) ? true : false;
	}

	function loggout () {
		$_SESSION['acm'] = array();
		return true;
	}

	function verif () {
		global $MYSQL;

		if(!ACCOUNT::is_logged())			// Check if user is logged
			return false;

		$account = unserialize($_SESSION['acm']);

		$MYSQL->connect();
		$sql = 'SELECT COUNT(login) ' .
				'FROM accounts ' .
					'WHERE login = "'.$account->login.'" ' .
						'AND password = "'.$account->password.'" ' .
						'AND access_level >= 0 LIMIT 1;';

		if($MYSQL->result($sql) != 1)	// Check is user session data are right
			return false;

		$MYSQL->close();
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