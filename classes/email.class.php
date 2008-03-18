<?php

defined( '_ACM_VALID' ) or die( 'Direct Access to this location is not allowed.' );

class email {

	var $login = null;
	var $url = null;
	var $code = null;

	function get_email ($login)
	{
		global $MYSQL;
		$MYSQL->connect();
		$sql = "SELECT email FROM accounts WHERE login = '" . $login . "' LIMIT 1;";
		$q = $MYSQL->query($sql);
		$r = @mysql_fetch_array($q);
		return $r['email'];
	}

	function send_email ($title, $message)
	{
		global $email_from,$server_name,$error,$use_smtp,$smtp_address,$smtp_port,$smtp_login,$smtp_password,$smtp_domain;

		$email = $this->get_email($this->login);

		$entity_b = array ('[\[IP\]]','[\[ID\]]','[\[EMAIL_SUPPORT\]]','[\[URL\]]','[\[CODE\]]','[\[SERVER\]]');
		$entity_p = array ($_SERVER['REMOTE_ADDR'], $this->login, $email_from, $this->url, $this->code, $server_name,'');
		$title = preg_replace($entity_b, $entity_p, $title);
		$message = preg_replace($entity_b, $entity_p, $message);
		
				

		if($use_smtp){
			$smtp = new SMTP($smtp_address, $smtp_login, $smtp_password, $smtp_port, $smtp_domain, 0);
			$smtp->set_from($server_name, $email_from);
			$smtp->Priority = 3;
			$smtp->ContentType = 'txt';
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

	function emailing($account, $mode) {
		global $vm;
		switch($mode) {
			default:
			break;
			case 'created_account_validation':
				$this->login = $account->login;
				$this->code = $account->code;
				$this->url = $url = "http://" . $_SERVER['HTTP_HOST'] . rtrim(dirname($_SERVER['PHP_SELF']), '/\\'). "/?action=activation&amp;key=".$this->code;;
				$this->send_email($vm['_email_title_verif'], $vm['_email_message_verif']);
			break;
			case 'created_account_activation':
				$this->login = $account->login;
				$this->send_email($vm['_email_title_ok'], $vm['_email_message_ok']);
			break;
			case 'forget_password_validation':
				$this->login = $account->login;
				$this->code = $account->code;
				$this->url = $url = "http://" . $_SERVER['HTTP_HOST'] . rtrim(dirname($_SERVER['PHP_SELF']), '/\\'). "/?action=forgot_pwd_email&amp;login=".$this->login."&amp;key=".$this->code;;
				$this->send_email($vm['_email_title_change_pwd'], $vm['_email_message_change_pwd']);
			break;
			case 'password_reseted':
				$this->login = $account->login;
				$this->code = $account->code;
				$this->send_email($vm['_email_title_change_pwd_ok'], $vm['_email_message_change_pwd_ok']);
			break;
		}
	}
}
?>