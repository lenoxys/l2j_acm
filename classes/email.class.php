<?php

defined( '_ACM_VALID' ) or die( 'Direct Access to this location is not allowed.' );

class email{

	private $email;
	
	private static $instance;

	private function __construct() {
		$this->email_smtp_use = CONFIG::g()->email_smtp_use;
		
		if($this->email_smtp_use) {
			$this->email_smtp_address = CONFIG::g()->email_smtp_address;
			$this->email_smtp_port = CONFIG::g()->email_smtp_port;
			$this->email_smtp_login = CONFIG::g()->email_smtp_login;
			$this->email_smtp_password = CONFIG::g()->email_smtp_password;
			$this->email_smtp_domain = CONFIG::g()->email_smtp_domain;
		}
		
		$this->email_from = CONFIG::g()->email_from;
		$this->server_name = CONFIG::g()->core_server_name;
	}
	
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
	
	private function get_email ()
	{
		$sql = sprintf("SELECT `email` FROM `accounts` WHERE `login` = '%s' LIMIT 1;",
				MYSQL::g()->escape_string($this->login)
			);
		
		return MYSQL::g()->result($sql);
	}

	private function send_email ($title, $message)
	{
	
		if(is_null($this->email))
			$this->email = $this->get_email();
	
		// Create the content of email
		$entity_b = array ('[\[IP\]]','[\[ID\]]','[\[EMAIL_SUPPORT\]]','[\[URL\]]','[\[CODE\]]','[\[SERVER\]]');
		$entity_p = array ($_SERVER['REMOTE_ADDR'], $this->login, $this->email_from, @$this->url, @$this->code, $this->server_name);
		$title = preg_replace($entity_b, $entity_p, $title);
		$message = preg_replace($entity_b, $entity_p, $message);
		
		// 2 ways for sending email by php mail function and by socket (smtp.class.php)

		if($this->email_smtp_use){
			$smtp = new SMTP($this->email_smtp_address, $this->email_smtp_login, $this->email_smtp_password, $this->email_smtp_port, $this->email_smtp_domain, 0);
			$smtp->set_from($this->server_name, $this->email_from);
			$smtp->Priority = 3;
			$smtp->ISO = CONFIG::g()->core_iso_type;
			if($smtp->smtp_mail($this->email, $title, $message)) {
				if(!empty($smtp->erreur))
					MSG::add_error($smtp->erreur);
				return false;
			}
		}else{
			$from  = 'From:'.$this->email_from."\n"."MIME-version: 1.0\n"."Content-type: text/html; charset= ".CONFIG::g()->core_iso_type."\n";
			if(!@mail($this->email, $title, $message, $from))
				return false;
		}

		return true;
	}
	
	private function get_url($val) {
		return 'http://' . $_SERVER['HTTP_HOST'] . rtrim(dirname($_SERVER['PHP_SELF']), '/\\'). '/?action='.$val;
	}

	public function operator($login, $mode, $code = NULL, $email = NULL) {
		$this->login = $login;
		
		if(!is_null($code))
			$this->code = $code;
		
		if(!is_null($email)) {
			DEBUG::add('Use specific mail');
			$this->email = $email;
		}
			
		switch($mode) {
			default:
			break;
			case 'created_account_validation':
				$this->url = $this->get_url('activation&amp;key='.$this->code);
				$this->send_email(LANG::i18n('_email_title_verif'), LANG::i18n('_email_message_verif'));
			break;
			case 'created_account_activation':
				$this->send_email(LANG::i18n('_email_title_ok'), LANG::i18n('_email_message_ok'));
			break;
			case 'forget_password_validation':
				$this->url = $this->get_url('forgot_pwd_email&amp;login='.$this->login.'&amp;key='.$this->code);
				$this->send_email(LANG::i18n('_email_title_change_pwd'), LANG::i18n('_email_message_change_pwd'));
			break;
			case 'password_reseted':
				$this->send_email(LANG::i18n('_email_title_change_pwd_ok'), LANG::i18n('_email_message_change_pwd_ok'));
			break;
			case 'email_validation':
				$this->url = $this->get_url('email_validation&amp;login='.$this->login.'&amp;key='.$this->code);
				$this->send_email(LANG::i18n('_email_title_verif'), LANG::i18n('_email_message_verif'));
			break;
			case 'modified_email_activation':
				$this->send_email(LANG::i18n('_email_title_change_email_ok'), LANG::i18n('_email_message_change_email_ok'));
			break;
		}
	}
}
?>