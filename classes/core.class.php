<?php

defined( '_ACM_VALID' ) or die( 'Direct Access to this location is not allowed.' );



class core {

	function core() {
		$this->account = new account();
		$this->secure_post();
	}

	function index() {
		if($this->account->is_logged())
			$this->show_account();
		else
			$this->show_login();
	}

	function show_auth() {

	}

	function loggout() {
		global $valid, $vm;
		$this->account->loggout();
		$valid = $vm['_logout'];
		$this->index();
	}

	function login() {
		global $error, $vm;

		if(empty($_POST['Luser']) || empty($_POST['Lpwd']))
		{
			$error = $vm['_no_id_no_pwd'];
		}else{

			$this->secure_post();

			if(!$this->account->auth($_POST['Luser'], $_POST['Lpwd'], $_POST['Limage']))
				$error .= $vm['_wrong_auth'];
		}

		$this->index();
	}

	function show_login() {
		global $template, $vm, $error, $valid, $id_limit, $pwd_limit, $act_img;
		$template->assign('vm', array(
		    'exist_account'		=> $vm['_exist_account'],
		    'account_length'		=> $id_limit,
		    'password_length'	=> $pwd_limit,
		    'account'			=> $vm['_account'],
		    'password'			=> $vm['_password'],
		    'login_button'		=> $vm['_login_button'],
		    'forgot_password'	=> $vm['_forgot_password'],
		    'new_account'		=> $vm['_new_account'],
		    'new_account_text'	=> $vm['_new_account_text'],
		    'create_button'		=> $vm['_create_button']
		));
		if($act_img) {
			$template->assign('image', 'image');
		}
		if($error != '') {
			$template->assign('error', $error);
		}
		if($valid != '') {
			$template->assign('valid', $valid);
		}
		$template->display('form.tpl');
	}

	function show_account() {
		global $template, $vm, $error, $valid;
		
		$template->assign('vm', array(
		    'account_text'		=> $vm['_chg_pwd_text'],
		    'chg_pwd'			=> $vm['_chg_pwd'],
		    'logout_link'		=> $vm['_logout_link']
		));
		if($error != '') {
			$template->assign('error', $error);
		}
		if($valid != '') {
			$template->assign('valid', $valid);
		}
		if($this->account->can_chg_email()) {
			$template->assign('email', $vm['_chg_email']);
		}
		$template->display('account.tpl');
	}

	function create() {
		global $valid, $error, $vm;

		if($this->account->create($_POST['Luser'], $_POST['Lpwd'], $_POST['Lpwd2'], $_POST['Lemail'], $_POST['Limage'])) {
			$valid = $vm['_account_created'];
			$this->show_login();
		}
		else
		{
			$this->show_create(true);
		}
	}

	function show_ack(){
		global $template,$vm;
		$template->assign('vm', array(
		    'terms_and_condition'		=> $vm['_TERMS_AND_CONDITION'],
		    'return'					=> $vm['_return'],
		    'accept_button'				=> $vm['_accept_button']
		));
		$template->display('ack.tpl');
	}

	function show_create($acka = false) {
		global $template, $vm, $error, $act_img, $id_limit, $pwd_limit,$ack_cond;

		$ack = (@$_POST['ack'] == 'ack') ? true : false;
		$ack = ($acka) ? true : $ack;

		if($ack_cond && !$ack) {
			$this->show_ack();
			return false;
		}
		
		$template->assign('vm', array(
		    'new_account'			=> $vm['_new_account'],
		    'new_account_text'		=> $vm['_new_account_text2'],
		    'account_length'		=> $id_limit,
		    'password_length'		=> $pwd_limit,
		    'account'				=> $vm['_account'],
		    'password'				=> $vm['_password'],
		    'password2'				=> $vm['_password2'],
		    'email'					=> $vm['_email'],
		    'image_control_desc'	=> $vm['_image_control_desc'],
		    'return'				=> $vm['_return'],
		    'create_button'			=> $vm['_create_button'],
		    'post_id'				=> @$_POST['Luser'],
		    'post_email'			=> @$_POST['Lemail']
		));
		if($act_img) {
			$template->assign('image', 'image');
		}
		if($error != '') {
			$template->assign('error', $error);
		}
		$template->display('create.tpl');
	}

	function show_forget() {
		global $template, $vm, $error, $act_img, $id_limit;
		$template->assign('vm', array(
		    'forgot_pwd'			=> $vm['_forgot_pwd'],
		    'forgot_pwd_text'		=> $vm['_forgot_pwd_text'],
		    'account_length'		=> $id_limit,
		    'account'				=> $vm['_account'],
		    'email'					=> $vm['_email'],
		    'image_control_desc'	=> $vm['_image_control_desc'],
		    'return'				=> $vm['_return'],
		    'forgot_button'			=> $vm['_forgot_button'],
		    'post_id'				=> @$_POST['Luser'],
		    'post_email'			=> @$_POST['Lemail']
		));
		if($act_img) {
			$template->assign('image', 'images');
		}
		if($error != '') {
			$template->assign('error', $error);
		}
		$template->display('forgot_pwd.tpl');
	}

	function forgot_pwd() {
		global $vm, $error, $valid;

		if($this->account->forgot_pwd($_POST['Luser'], $_POST['Lemail'], @$_POST['Limage'])) {
			$valid = $vm['_password_request'];
			$this->index();
		}else{
			$this->show_forget();
		}

		return true;
	}

	function forgot_pwd2() {
		global $vm, $error, $valid;

		if($this->account->forgot_pwd2($_GET['login'], $_GET['key'])) {
			$valid = $vm['_password_reseted'];
			$this->index();
		}else{
			$error = $vm['_control'];
			$this->show_forget();
		}

		return true;
	}

	function change_pwd() {
		global $valid, $error, $vm;

		if(!$this->account->verif()) {
			$error = $vm['_WARN_NOT_LOGGED'];
			$this->index();
			return;
		}

		$account = unserialize($_SESSION['acm']);

		if($this->account->edit_password($_POST['Lpwdold'], $_POST['Lpwd'], $_POST['Lpwd2'])) {
			$valid = $vm['_change_pwd_valid'];
			$this->show_account();
		}
		else
		{
			$this->show_chg_pwd();
		}
	}

	function show_chg_pwd() {
		global $error, $vm;
		
		if(!$this->account->verif()) {
			$error = $vm['_WARN_NOT_LOGGED'];
			$this->index();
			return;
		}

		global $template, $pwd_limit;

		$template->assign('vm', array(
		    'chg_pwd'				=> $vm['_chg_pwd'],
		    'chg_pwd_text'			=> $vm['_chg_pwd_text'],
		    'password_length'		=> $pwd_limit,
		    'passwordold'			=> $vm['_passwordold'],
		    'password'				=> $vm['_password'],
		    'password2'				=> $vm['_password2'],
		    'return'				=> $vm['_return'],
		    'chg_button'			=> $vm['_chg_button']
		));
		
		if($error != '') {
			$template->assign('error', $error);
		}
		
		$template->display('chg_pwd.tpl');
	}

	function change_email() {
		global $valid, $error, $vm;

		if(!$this->account->verif()) {
			$error = $vm['_WARN_NOT_LOGGED'];
			$this->index();
			return;
		}

		if(!$this->account->can_chg_email()) {
			$this->index();
			return;
		}

		$this->account = unserialize($_SESSION['acm']);

		if($this->account->edit_email($_POST['Lpwd'], $_POST['Lemail'], $_POST['Lemail2'])) {
			$valid = $vm['_change_email_valid'];
			$this->show_account();
		}
		else
		{
			$this->show_chg_email();
		}
	}

	function show_chg_email() {
		global $error, $vm, $can_chg_email;
		
		if(!$this->account->verif()) {
			$error = $vm['_WARN_NOT_LOGGED'];
			$this->index();
			return;
		}

		if(!$this->account->can_chg_email()) {
			$this->index();
			return;
		}

		global $template, $pwd_limit;
		
		$template->assign('vm', array(
		    'chg_pwd'				=> $vm['_chg_email'],
		    'chg_pwd_text'			=> $vm['_chg_email_text'],
		    'password_length'		=> $pwd_limit,
		    'password'				=> $vm['_password'],
		    'email'					=> $vm['_email'],
		    'email2'				=> $vm['_email2'],
		    'return'				=> $vm['_return'],
		    'chg_button'			=> $vm['_chg_button']
		));
		
		if($error != '') {
			$template->assign('error', $error);
		}
		
		$template->display('chg_email.tpl');

	}

	function activation() {
		global $vm, $valid, $error;

		if(!$this->account->valid_account(htmlentities($_GET['key'])))
			$error = $vm['_activation_control'];
		else
			$valid = $vm['_account_actived'];

		$this->index();

		return;
	}

	function secure_post() {
		global $id_limit, $pwd_limit;

		if (!$_POST) return;

		$_POST = array_map('htmlentities', $_POST);
		$_POST = array_map('htmlspecialchars', $_POST);

		foreach($_POST as $key => $value) {
			if ($key == 'Luser')
				$_POST[$key] = substr($value, 0, $id_limit);

			if ($key == 'Lpwd')
				$_POST[$key] = substr($value, 0, $id_limit);
		}
		
		return;
	}

	function gen_img_cle($num = 5) {
		$key = '';
		$chaine = "ABCDEF123456789";
		for ($i=0;$i<$num;$i++) $key.= $chaine[rand()%strlen($chaine)];
		$_SESSION['code'] = $key;
	}
}
?>