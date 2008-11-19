<?php

defined( '_ACM_VALID' ) or die( 'Direct Access to this location is not allowed.' );

class email{

	var $email;
	
	private static $instance;

	private function __construct() {}
	
	public function __clone() {
		trigger_error('Clone is not allowed.', E_USER_ERROR);
	}

	public static function OP() {
		if (!isset(self::$instance)) {
			$c = __CLASS__;
			self::$instance = new $c;
		}
		return self::$instance;
	}
	
	function get_email ()
	{
		if(!empty($this->account->email))
			return $this->account->email;
		
		$sql = "SELECT email FROM accounts WHERE login = '" . $this->account->login . "' LIMIT 1;";
		return $this->account->MYSQL->result($sql);
	}

	function send_email ($title, $message)
	{
		global $email_from,$server_name,$error,$smtp;

		$email = $this->get_email();

		$entity_b = array ('[\[IP\]]','[\[ID\]]','[\[EMAIL_SUPPORT\]]','[\[URL\]]','[\[CODE\]]','[\[SERVER\]]');
		$entity_p = array ($_SERVER['REMOTE_ADDR'], $this->account->login, $email_from, $this->url, $this->account->code, $server_name,'');
		$title = preg_replace($entity_b, $entity_p, $title);
		$message = preg_replace($entity_b, $entity_p, $message);

		if($smtp['use']){
			$smtp = new SMTP($smtp['address'], $smtp['login'], $smtp['password'], $smtp['port'], $smtp['domain'], 0);
			$smtp->set_from($server_name, $email_from);
			$smtp->Priority = 3;
			if($smtp->smtp_mail($email, $title, $message)) {
				$error = $smtp->erreur;
				return false;
			}
		}else{
			$from  = "From:".$email_from."\n"."MIME-version: 1.0\n"."Content-type: text/html; charset= iso-8859-1\n";
			if(!@mail($email, $title, $message, $from))
				return false;
		}

		return true;
	}

	function operator($account, $mode) {
		global $vm;
		$this->account = $account;
		switch($mode) {
			default:
			break;
			case 'created_account_validation':
				$this->url = $url = "http://" . $_SERVER['HTTP_HOST'] . rtrim(dirname($_SERVER['PHP_SELF']), '/\\'). "/?action=activation&amp;key=".$this->account->code;
				$this->send_email($vm['_email_title_verif'], $vm['_email_message_verif']);
			break;
			case 'created_account_activation':
				$this->send_email($vm['_email_title_ok'], $vm['_email_message_ok']);
			break;
			case 'forget_password_validation':
				$this->url = $url = "http://" . $_SERVER['HTTP_HOST'] . rtrim(dirname($_SERVER['PHP_SELF']), '/\\'). "/?action=forgot_pwd_email&amp;login=".$this->account->login."&amp;key=".$this->account->code;
				$this->send_email($vm['_email_title_change_pwd'], $vm['_email_message_change_pwd']);
			break;
			case 'password_reseted':
				$this->send_email($vm['_email_title_change_pwd_ok'], $vm['_email_message_change_pwd_ok']);
			break;
			case 'email_validation':
				$this->url = $url = "http://" . $_SERVER['HTTP_HOST'] . rtrim(dirname($_SERVER['PHP_SELF']), '/\\'). "/?action=email_validation&amp;login=".$this->account->login."&amp;key=".$this->account->code;
				$this->send_email($vm['_email_title_verif'], $vm['_email_message_verif']);
			break;
			case 'modified_email_activation':
				$this->send_email($vm['_email_title_change_email_ok'], $vm['_email_message_change_email_ok']);
			break;
		}
	}
}
?>