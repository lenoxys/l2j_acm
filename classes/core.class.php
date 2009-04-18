<?php

defined( '_ACM_VALID' ) or die( 'Direct Access to this location is not allowed.' );



class core {

	function core() {
		$this->account = ACCOUNT::load();
		$this->secure_post();
	}

	function index() {
		if($this->account->is_logged())
			$this->show_account();
		else
			$this->show_login();
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
		global $template, $vm, $error, $valid, $allow_char_mod;
		
		$template->assign('vm', array(
			'title_page'		=> $vm['_title_page'],
		    'account_text'		=> $vm['_chg_pwd_text']
		));
		
		$modules = array();
		
		$modules[] = array('name'=>$vm['_chg_pwd'], 'link'=>'?action=show_chg_pwd');
		
		if ($this->allow_char_mod())
			$modules[] = array('name'=>$vm['_select_worlds'], 'link'=>'?action=show_worlds');
		
		if ($this->account->can_chg_email())
			$modules[] = array('name'=>$vm['_chg_email'], 'link'=>'?action=show_chg_email');
		
		$modules[] = array('name'=>$vm['_logout_link'], 'link'=>'?action=loggout');
		
		$template->assign('modules', $modules);
		
		$template->register_block('dynamic', 'smarty_block_dynamic', false);
		
		if($error != '') {
			$template->assign('error', $error);
		}
		if($valid != '') {
			$template->assign('valid', $valid);
		}
		$template->display('account.tpl');
	}

	function registration() {
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

	function forgot_pwd_email() {
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

	function chg_pwd_form() {
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

	function chg_email_form() {
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

	function email_validation() {
		global $vm, $error, $valid;

		if($this->account->email_validation($_GET['login'], $_GET['key'])) {
			$valid = $vm['_email_activated'];
		}else{
			$error = $vm['_control'];
		}
		
		$this->index();

		return true;
	}
	
	function show_worlds(){
		global $allow_char_mod;
		
		if(!$this->allow_char_mod()) {
			$this->index();
			return;
		}
				
		global $template, $vm;
		
		$_SESSION['worlds'] = WORLD::load_worlds();
		
		$template->assign('vm', array(
			'select_item'			=> $vm['_select_worlds'],
			'return'				=> $vm['_return'],
		));
		
		$items = array();
		foreach  ($_SESSION['worlds'] as $world)
			$items[] = array('id' => $world->id, 'name' => $world->name, 'link' => '?action=show_chars&world_id='.$world->id);
		
		$template->assign('items', $items);
		
		$template->register_block('dynamic', 'smarty_block_dynamic', false);
		
		if($error != '') {
			$template->assign('error', $error);
		}
		
		$template->display('select.tpl');
	}
	
	function show_chars(){
		global $allow_char_mod, $vm;
		
		if(!$this->allow_char_mod()) {
			$this->index();
			return;
		}
		
		global $template;
		
		$world = new WORLD($_GET['world_id']);
		
		$this->account->chars = $world->get_chars($this->account->login);
		$_SESSION['acm'] = serialize($this->account);
		
		$template->assign('vm', array(
			'select_item'			=> $vm['_select_character'],
		    'return'				=> $vm['_return']
		));
		
		$items = array();
		foreach  ($this->account->chars as $char)
			$items[] = array('id' => $char[0], 'name' => $char[1], 'link' => '?action=show_char&id='.$char[0]);
		
		$template->assign('items', $items);
		
		$template->register_block('dynamic', 'smarty_block_dynamic', false);
		
		if($error != '') {
			$template->assign('error', $error);
		}
		
		$template->display('select.tpl');
	}
	
	function show_char(){
		
		if(!$this->allow_char_mod()) {
			$this->index();
			return;
		}
		
		global $template, $vm, $error, $valid, $allow_fix, $allow_unstuck, $allow_account_services;
		
		if(empty($_GET['id'])) {
			$error = 'Error when select your character';
			$this->index();
			return;
		}
		
		$this->char = $this->account->chars[$_GET['id']];
		
		if(empty($this->char)) {
			$error = 'Error when select your character';
			$this->index();
			return;
		}
		
		$this->char = new character($this->char[0], $this->account->login, $this->char[2]);
		
		if(!$this->char) {
			$error = 'Error when select your character';
			$this->index();
			return;
		}
		
		$_SESSION['acm_char'] = serialize($this->char);
		
		$template->assign('vm', array(
			'select_item'		=> $this->char->char_name,
		    'return'		=> $vm['_return']
		));
		
		$items = array();
		
		if($this->char->allow_fix())
			$items[] = array('id' => 0, 'name' => $vm['_character_fix'], 'link' => '?action=char_fix&id='.$this->char->charId);
		
		if($this->char->allow_fix(true))
			$items[] = array('id' => 1, 'name' => $vm['_character_unstuck'], 'link' => '?action=char_unstuck&id='.$this->char->charId);
		
		if($this->char->can_change_gender())
			$items[] = array('id' => 1, 'name' => $vm['_character_sex'], 'link' => '?action=char_sex&id='.$this->char->charId);
		
		if($this->char->can_change_name(null, true))
			$items[] = array('id' => 1, 'name' => $vm['_character_name'], 'link' => '?action=char_unstuck&id='.$this->char->charId);
		
		$template->assign('items', $items);
		
		$template->register_block('dynamic', 'smarty_block_dynamic', false);
		
		if($error != '') {
			$template->assign('error', $error);
		}
		if($valid != '') {
			$template->assign('valid', $valid);
		}
		$template->display('select.tpl');
	}

	function char_fix() {
		
		if(!$this->allow_char_mod()) {
			$this->index();
			return;
		}
		
		global $vm, $valid, $error;
		
		$this->char = unserialize($_SESSION['acm_char']);

		if(!$this->char->fix())
			$error = $vm['_character_fix_no'];
		else
			$valid = $vm['_character_fix_yes'];

		$this->index();

		return;
	}

	function char_unstuck() {
		
		if(!$this->allow_char_mod()) {
			$this->index();
			return;
		}
		
		global $vm, $valid, $error;
		
		$this->char = unserialize($_SESSION['acm_char']);

		if(!$this->char->unstuck())
			$error = $vm['_character_unstuck_no'];
		else
			$valid = $vm['_character_unstuck_yes'];

		$this->index();

		return;
	}

	function char_sex() {
		
		if(!$this->allow_char_mod() and !$allow_account_services) {
			$this->index();
			return;
		}
		
		global $allow_account_services;
		
		if(empty($_GET['id'])) {
			$error = 'Error when select your character';
			$this->index();
			return;
		}
		
		$this->char = $this->account->chars[$_GET['id']];
		
		if(empty($this->char)) {
			$error = 'Error when select your character';
			$this->index();
			return;
		}
		
		global $template, $vm;
		
		$this->char = unserialize($_SESSION['acm_char']);
		
		$this->after = ($this->char->sex == 1) ? 0 : 1;
		
		$p1 = $this->char->char_name;
		$p2 = $this->char->world->name;
		$p3 = $vm['_character_sex_'.$this->char->sex];
		$p4 = $vm['_character_sex_'.$this->after];
		
		$template->assign('vm', array(
			'select_item'		=> sprintf($vm['_character_sex_confirm'], $p1, $p2, $p3, $p4),
		    'return'		=> $vm['_return']
		));
		
		$items = array();
		
		$items[] = array('id' => 1, 'name' => $vm['_confirm'], 'link' => '?action=char_sex_confirm&id='.$this->char->charId);
		$items[] = array('id' => 1, 'name' => $vm['_back'], 'link' => '?action=show_char&id='.$this->char->charId);
		
		$template->assign('items', $items);
		
		$template->register_block('dynamic', 'smarty_block_dynamic', false);
		
		if($error != '') {
			$template->assign('error', $error);
		}
		if($valid != '') {
			$template->assign('valid', $valid);
		}
		$template->display('select.tpl');
	}

	function char_sex_confirm() {
		
		if(!$this->allow_char_mod()) {
			$this->index();
			return;
		}
		
		global $vm, $valid, $error;
		
		$this->char = unserialize($_SESSION['acm_char']);

		if(!$this->char->change_gender())
			$error .= $vm['_character_sex_no'];
		else
			$valid .= $vm['_character_sex_yes'];

		$this->index();

		return;
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
	
	function allow_char_mod() {
		global $allow_char_mod, $allow_account_services, $allow_fix, $allow_unstuck;
		
		if(!$allow_char_mod)
			return false;
		
		if(!$allow_fix && !$allow_unstuck && !$allow_account_services)
			return false;
		
		return true;
	}

	protected function secure_post() {
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