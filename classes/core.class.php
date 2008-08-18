<?php

defined( '_ACM_VALID' ) or die( 'Direct Access to this location is not allowed.' );



class core {

	function core() {
	}

	function index() {
		if(ACCOUNT::is_logged())
			CORE::show_account();
		else
			CORE::show_login();
	}

	function show_auth() {

	}

	function loggout() {
		global $valid, $vm;
		ACCOUNT::loggout();
		$valid = $vm['_logout'];
		CORE::index();
	}

	function login() {
		global $error, $vm;

		if(empty($_POST['Luser']) || empty($_POST['Lpwd']))
		{
			$error = $vm['_no_id_no_pwd'];
		}else{

			CORE::secure_post();

			if(!ACCOUNT::auth($_POST['Luser'], $_POST['Lpwd'], $_POST['Limage']))
				$error .= $vm['_wrong_auth'];
		}

		CORE::index();
	}

	function show_login() {
		global $template, $vm, $error, $valid, $id_limit, $pwd_limit, $act_img;
		$template->set_filenames(array(
			'content' => 'form.tpl'
		));
		$template->assign_vars(array(
		    'vm_exist_account'		=> $vm['_exist_account'],
		    'vm_account_length'		=> $id_limit,
		    'vm_password_length'	=> $pwd_limit,
		    'vm_account'			=> $vm['_account'],
		    'vm_password'			=> $vm['_password'],
		    'vm_login_button'		=> $vm['_login_button'],
		    'vm_forgot_password'	=> $vm['_forgot_password'],
		    'vm_new_account'		=> $vm['_new_account'],
		    'vm_new_account_text'	=> $vm['_new_account_text'],
		    'vm_create_button'		=> $vm['_create_button']
		));
		if($act_img) {
			$template->assign_block_vars('image', null);
		}
		if($error != '') {
			$template->assign_block_vars('error',array(
				'ERROR' => $error
			));
		}
		if($valid != '') {
			$template->assign_block_vars('valid',array(
				'VALID' => $valid
			));
		}
	}

	function show_account() {
		global $template, $vm, $error, $valid;
		$template->set_filenames(array(
			'content' => 'account.tpl'
		));
		$template->assign_vars(array(
		    'vm_account_text'		=> $vm['_chg_pwd_text'],
		    'vm_chg_pwd'			=> $vm['_chg_pwd'],
		    'vm_logout_link'		=> $vm['_logout_link']
		));
		if($error != '') {
			$template->assign_block_vars('error',array(
				'ERROR' => $error
			));
		}
		if($valid != '') {
			$template->assign_block_vars('valid',array(
				'VALID' => $valid
			));
		}
		if(ACCOUNT::can_chg_email()) {
			$template->assign_block_vars('email',array(
				'vm_chg_email' => $vm['_chg_email']
			));
		}
	}

	function create() {
		global $valid, $error, $vm;

		CORE::secure_post();

		$account = new account();

		if($account->create($_POST['Luser'], $_POST['Lpwd'], $_POST['Lpwd2'], $_POST['Lemail'], $_POST['Limage'])) {
			$valid = $vm['_account_created'];
			CORE::show_login();
		}
		else
		{
			CORE::show_create(true);
		}
	}

	function show_ack(){
		global $template,$vm;
		$template->set_filenames(array(
			'content' => 'ack.tpl'
		));
		$template->assign_vars(array(
		    'vm_terms_and_condition'	=> $vm['_TERMS_AND_CONDITION'],
		    'vm_return'					=> $vm['_return'],
		    'vm_accept_button'		=> $vm['_accept_button']
		));
		$_COOKIE['ack'] = '';
	}

	function show_create($acka = false) {
		global $template, $vm, $error, $act_img, $id_limit, $pwd_limit,$ack_cond;

		CORE::secure_post();

		$ack = (@$_POST['ack'] == 'ack') ? true : false;
		$ack = ($acka) ? true : $ack;

		if($ack_cond && !$ack) {
			CORE::show_ack();
			return false;
		}

		$template->set_filenames(array(
			'content' => 'create.tpl'
		));
		$template->assign_vars(array(
		    'vm_new_account'		=> $vm['_new_account'],
		    'vm_new_account_text'	=> $vm['_new_account_text2'],
		    'vm_account_length'		=> $id_limit,
		    'vm_password_length'	=> $pwd_limit,
		    'vm_account'			=> $vm['_account'],
		    'vm_password'			=> $vm['_password'],
		    'vm_password2'			=> $vm['_password2'],
		    'vm_email'				=> $vm['_email'],
		    'vm_image_control_desc'	=> $vm['_image_control_desc'],
		    'vm_return'				=> $vm['_return'],
		    'vm_create_button'		=> $vm['_create_button'],
		    'post_id'				=> @$_POST['Luser'],
		    'post_email'			=> @$_POST['Lemail']
		));
		if($act_img) {
			$template->assign_block_vars('image', null);
		}
		if($error != '') {
			$template->assign_block_vars('error',array(
				'ERROR' => $error
			));
		}
	}

	function show_forget() {
		global $template, $vm, $error, $act_img, $id_limit;
		$template->set_filenames(array(
			'content' => 'forgot_pwd.tpl'
		));
		$template->assign_vars(array(
		    'vm_forgot_pwd'			=> $vm['_forgot_pwd'],
		    'vm_forgot_pwd_text'	=> $vm['_forgot_pwd_text'],
		    'vm_account_length'		=> $id_limit,
		    'vm_account'			=> $vm['_account'],
		    'vm_email'				=> $vm['_email'],
		    'vm_image_control_desc'	=> $vm['_image_control_desc'],
		    'vm_return'				=> $vm['_return'],
		    'vm_forgot_button'		=> $vm['_forgot_button'],
		    'post_id'				=> @$_POST['Luser'],
		    'post_email'			=> @$_POST['Lemail']
		));
		if($act_img) {
			$template->assign_block_vars('image', null);
		}
		if($error != '') {
			$template->assign_block_vars('error',array(
				'ERROR' => $error
			));
		}
	}

	function forgot_pwd() {
		global $vm, $error, $valid;

		CORE::secure_post();

		$account = new account();

		if($account->forgot_pwd($_POST['Luser'], $_POST['Lemail'], @$_POST['Limage'])) {
			$valid = $vm['_password_request'];
			CORE::index();
		}else{
			CORE::show_forget();
		}

		return true;
	}

	function forgot_pwd2() {
		global $vm, $error, $valid;

		CORE::secure_post();

		$account = new account();

		if($account->forgot_pwd2($_GET['login'], $_GET['key'])) {
			$valid = $vm['_password_reseted'];
			CORE::index();
		}else{
			$error = $vm['_control'];
			CORE::show_forget();
		}

		return true;
	}

	function change_pwd() {
		global $valid, $error, $vm;

		if(!ACCOUNT::verif()) {
			$error = $vm['_WARN_NOT_LOGGED'];
			CORE::index();
			return;
		}

		CORE::secure_post();

		$account = unserialize($_SESSION['acm']);

		if($account->edit_password($_POST['Lpwdold'], $_POST['Lpwd'], $_POST['Lpwd2'])) {
			$valid = $vm['_change_pwd_valid'];
			CORE::show_account();
		}
		else
		{
			CORE::show_chg_pwd();
		}
	}

	function show_chg_pwd() {
		global $error, $vm;
		if(!ACCOUNT::verif()) {
			$error = $vm['_WARN_NOT_LOGGED'];
			CORE::index();
			return;
		}

		global $template, $vm, $error, $pwd_limit;
		$template->set_filenames(array(
			'content' => 'chg_pwd.tpl'
		));
		$template->assign_vars(array(
		    'vm_chg_pwd'			=> $vm['_chg_pwd'],
		    'vm_chg_pwd_text'		=> $vm['_chg_pwd_text'],
		    'vm_password_length'	=> $pwd_limit,
		    'vm_passwordold'		=> $vm['_passwordold'],
		    'vm_password'			=> $vm['_password'],
		    'vm_password2'			=> $vm['_password2'],
		    'vm_return'				=> $vm['_return'],
		    'vm_chg_button'			=> $vm['_chg_button']
		));
		if($error != '') {
			$template->assign_block_vars('error',array(
				'ERROR' => $error
			));
		}
	}

	function change_email() {
		global $valid, $error, $vm;

		if(!ACCOUNT::verif()) {
			$error = $vm['_WARN_NOT_LOGGED'];
			CORE::index();
			return;
		}

		if(!ACCOUNT::can_chg_email()) {
			CORE::index();
			return;
		}

		CORE::secure_post();

		$account = unserialize($_SESSION['acm']);

		if($account->edit_email($_POST['Lpwd'], $_POST['Lemail'], $_POST['Lemail2'])) {
			$valid = $vm['_change_email_valid'];
			CORE::show_account();
		}
		else
		{
			CORE::show_chg_email();
		}
	}

	function show_chg_email() {
		global $error, $vm, $can_chg_email;
		if(!ACCOUNT::verif()) {
			$error = $vm['_WARN_NOT_LOGGED'];
			CORE::index();
			return;
		}

		if(!ACCOUNT::can_chg_email()) {
			CORE::index();
			return;
		}

		global $template, $vm, $error, $pwd_limit;
		$template->set_filenames(array(
			'content' => 'chg_email.tpl'
		));
		$template->assign_vars(array(
		    'vm_chg_pwd'			=> $vm['_chg_pwd'],
		    'vm_chg_pwd_text'		=> $vm['_chg_pwd_text'],
		    'vm_password_length'	=> $pwd_limit,
		    'vm_password'			=> $vm['_password'],
		    'vm_email'				=> $vm['_email'],
		    'vm_email2'				=> $vm['_email2'],
		    'vm_return'				=> $vm['_return'],
		    'vm_chg_button'			=> $vm['_chg_button']
		));
		if($error != '') {
			$template->assign_block_vars('error',array(
				'ERROR' => $error
			));
		}
	}

	function activation() {
		global $vm, $valid, $error;

		$account = new account();

		if(!$account->valid_account(htmlentities($_GET['key'])))
			$error = $vm['_activation_control'];
		else
			$valid = $vm['_account_actived'];

		CORE::index();

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
	}

	function gen_img_cle($num = 5) {
		$key = '';
		$chaine = "ABCDEF123456789";
		for ($i=0;$i<$num;$i++) $key.= $chaine[rand()%strlen($chaine)];
		$_SESSION['code'] = $key;
	}
}
?>