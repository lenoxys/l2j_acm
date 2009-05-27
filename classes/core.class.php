<?php

defined( '_ACM_VALID' ) or die( 'Direct Access to this location is not allowed.' );



class core {

	function core() {
		$this->account = ACCOUNT::load();
		$this->secure_post();
	}

	function index() {
		if($this->account->verif())
			$this->show_account();
		else
			$this->show_login();
	}

	function loggout() {
		$this->account->loggout();
		MSG::add_valid(LANG::i18n('_logout'));
		$this->index();
	}

	function login() {

		if(empty($_POST['Luser']) || empty($_POST['Lpwd']))
		{
			MSG::add_error(LANG::i18n('_no_id_no_pwd'));
		}else{

			$this->secure_post();

			if(!$this->account->auth($_POST['Luser'], $_POST['Lpwd'], @$_POST['Limage']))
				MSG::add_error(LANG::i18n('_wrong_auth'));
		}

		$this->index();
	}

	function show_login() {
		global $id_limit, $pwd_limit, $act_img;
		SmartyObject::getInstance()->assign('vm', array(
		    'exist_account'		=> LANG::i18n('_exist_account'),
		    'account_length'		=> $id_limit,
		    'password_length'	=> $pwd_limit,
		    'account'			=> LANG::i18n('_account'),
		    'password'			=> LANG::i18n('_password'),
		    'login_button'		=> LANG::i18n('_login_button'),
		    'forgot_password'	=> LANG::i18n('_forgot_password'),
		    'new_account'		=> LANG::i18n('_new_account'),
		    'new_account_text'	=> LANG::i18n('_new_account_text'),
		    'create_button'		=> LANG::i18n('_create_button')
		));
		if($act_img) {
			SmartyObject::getInstance()->assign('image', 'image');
		}
		SmartyObject::getInstance()->setTemplate('form.tpl');
	}

	function show_account() {
		
		SmartyObject::getInstance()->assign('vm', array(
			'title_page'		=> LANG::i18n('_title_page'),
		    'account_text'		=> LANG::i18n('_chg_pwd_text')
		));
		
		$modules = array();
		
		$modules[] = array('name'=>LANG::i18n('_chg_pwd'), 'link'=>'?action=show_chg_pwd');
		
		if ($this->allow_char_mod())
			$modules[] = array('name'=>LANG::i18n('_accounts_services'), 'link'=>'?action=acc_serv');
		
		if ($this->account->can_chg_email())
			$modules[] = array('name'=>LANG::i18n('_chg_email'), 'link'=>'?action=show_chg_email');
		
		$modules[] = array('name'=>LANG::i18n('_logout_link'), 'link'=>'?action=loggout');
		
		SmartyObject::getInstance()->assign('modules', $modules);
		
		SmartyObject::getInstance()->register_block('dynamic', 'smarty_block_dynamic', false);
		SmartyObject::getInstance()->setTemplate('account.tpl');
	}

	function registration() {

		if($this->account->create($_POST['Luser'], $_POST['Lpwd'], $_POST['Lpwd2'], $_POST['Lemail'], @$_POST['Limage'])) {
			$this->show_login();
		}else{
			$this->show_create(true);
		}
	}

	function show_ack(){
		SmartyObject::getInstance()->assign('vm', array(
		    'terms_and_condition'		=> LANG::i18n('_TERMS_AND_CONDITION'),
		    'return'					=> LANG::i18n('_return'),
		    'accept_button'				=> LANG::i18n('_accept_button')
		));
		SmartyObject::getInstance()->setTemplate('ack.tpl');
	}

	function show_create($acka = false) {
		global $act_img, $id_limit, $pwd_limit, $ack_cond;

		$ack = (@$_POST['ack'] == 'ack') ? true : false;
		$ack = ($acka) ? true : $ack;

		if($ack_cond && !$ack) {
			$this->show_ack();
			return false;
		}
		
		SmartyObject::getInstance()->assign('vm', array(
		    'new_account'			=> LANG::i18n('_new_account'),
		    'new_account_text'		=> LANG::i18n('_new_account_text2'),
		    'account_length'		=> $id_limit,
		    'password_length'		=> $pwd_limit,
		    'account'				=> LANG::i18n('_account'),
		    'password'				=> LANG::i18n('_password'),
		    'password2'				=> LANG::i18n('_password2'),
		    'email'					=> LANG::i18n('_email'),
		    'image_control_desc'	=> LANG::i18n('_image_control_desc'),
		    'return'				=> LANG::i18n('_return'),
		    'create_button'			=> LANG::i18n('_create_button'),
		    'post_id'				=> @$_POST['Luser'],
		    'post_email'			=> @$_POST['Lemail']
		));
		if($act_img) {
			SmartyObject::getInstance()->assign('image', 'image');
		}
		SmartyObject::getInstance()->setTemplate('create.tpl');
	}

	function show_forget() {
		global $act_img, $id_limit;
		SmartyObject::getInstance()->assign('vm', array(
		    'forgot_pwd'			=> LANG::i18n('_forgot_pwd'),
		    'forgot_pwd_text'		=> LANG::i18n('_forgot_pwd_text'),
		    'account_length'		=> $id_limit,
		    'account'				=> LANG::i18n('_account'),
		    'email'					=> LANG::i18n('_email'),
		    'image_control_desc'	=> LANG::i18n('_image_control_desc'),
		    'return'				=> LANG::i18n('_return'),
		    'forgot_button'			=> LANG::i18n('_forgot_button'),
		    'post_id'				=> @$_POST['Luser'],
		    'post_email'			=> @$_POST['Lemail']
		));
		if($act_img) {
			SmartyObject::getInstance()->assign('image', 'images');
		}
		SmartyObject::getInstance()->setTemplate('forgot_pwd.tpl');
	}

	function forgot_pwd() {

		if($this->account->forgot_pwd($_POST['Luser'], $_POST['Lemail'], @$_POST['Limage'])) {
			MSG::add_valid(LANG::i18n('_password_request'));
			$this->index();
		}else{
			$this->show_forget();
		}

		return true;
	}

	function forgot_pwd_email() {

		if($this->account->forgot_pwd2($_GET['login'], $_GET['key'])) {
			MSG::add_valid(LANG::i18n('_password_reseted'));
			$this->index();
		}else{
			MSG::add_error(LANG::i18n('_control'));
			$this->show_forget();
		}

		return true;
	}

	function chg_pwd_form() {

		if(!$this->account->verif()) {
			MSG::add_error(LANG::i18n('_WARN_NOT_LOGGED'));
			$this->index();
			return;
		}

		$account = unserialize($_SESSION['acm']);

		if($this->account->edit_password($_POST['Lpwdold'], $_POST['Lpwd'], $_POST['Lpwd2'])) {
			MSG::add_valid(LANG::i18n('_change_pwd_valid'));
			$this->show_account();
		}
		else
		{
			$this->show_chg_pwd();
		}
	}

	function show_chg_pwd() {
		
		if(!$this->account->verif()) {
			MSG::add_error(LANG::i18n('_WARN_NOT_LOGGED'));
			$this->index();
			return;
		}

		global $pwd_limit;

		SmartyObject::getInstance()->assign('vm', array(
		    'chg_pwd'				=> LANG::i18n('_chg_pwd'),
		    'chg_pwd_text'			=> LANG::i18n('_chg_pwd_text'),
		    'password_length'		=> $pwd_limit,
		    'passwordold'			=> LANG::i18n('_passwordold'),
		    'password'				=> LANG::i18n('_password'),
		    'password2'				=> LANG::i18n('_password2'),
		    'return'				=> LANG::i18n('_return'),
		    'chg_button'			=> LANG::i18n('_chg_button')
		));
		
		SmartyObject::getInstance()->setTemplate('chg_pwd.tpl');
	}

	function chg_email_form() {

		if(!$this->account->verif()) {
			MSG::add_error(LANG::i18n('_WARN_NOT_LOGGED'));
			$this->index();
			return;
		}

		if(!$this->account->can_chg_email()) {
			$this->index();
			return;
		}

		$this->account = unserialize($_SESSION['acm']);

		if($this->account->edit_email($_POST['Lpwd'], $_POST['Lemail'], $_POST['Lemail2'])) {
			MSG::add_valid(LANG::i18n('_change_email_valid'));
			$this->show_account();
		}
		else
		{
			$this->show_chg_email();
		}
	}

	function show_chg_email() {
		
		if(!$this->account->verif()) {
			MSG::add_error(LANG::i18n('_WARN_NOT_LOGGED'));
			$this->index();
			return;
		}

		if(!$this->account->can_chg_email()) {
			$this->index();
			return;
		}

		global $pwd_limit;
		
		SmartyObject::getInstance()->assign('vm', array(
		    'chg_pwd'				=> LANG::i18n('_chg_email'),
		    'chg_pwd_text'			=> LANG::i18n('_chg_email_text'),
		    'password_length'		=> $pwd_limit,
		    'password'				=> LANG::i18n('_password'),
		    'email'					=> LANG::i18n('_email'),
		    'email2'				=> LANG::i18n('_email2'),
		    'return'				=> LANG::i18n('_return'),
		    'chg_button'			=> LANG::i18n('_chg_button')
		));
		
		SmartyObject::getInstance()->setTemplate('chg_email.tpl');

	}

	function email_validation() {

		if($this->account->email_validation($_GET['login'], $_GET['key'])) {
			MSG::add_valid(LANG::i18n('_email_activated'));
		}else{
			MSG::add_error(LANG::i18n('_control'));
		}
		
		$this->index();

		return true;
	}
	
	function acc_serv(){
		if(!$this->allow_char_mod()) {
			MSG::add_error(LANG::i18n('_acc_serv_off'));
			$this->index();
			return;
		}
				
		global $accserv;
		
		SmartyObject::getInstance()->assign('vm', array(
			'select_item'			=> LANG::i18n('_accounts_services'),
			'return'				=> LANG::i18n('_return'),
		));
		
		$items = array();
		
		if($accserv['allow_fix'])
			$items[] = array('id' => 0, 'name' => LANG::i18n('_character_fix'), 'link' => '?action=char_fix_l');
		
		if($accserv['allow_unstuck'])
			$items[] = array('id' => 1, 'name' => LANG::i18n('_character_unstuck'), 'link' => '?action=char_unstuck_l');
		
		if($accserv['allow_sex'])
			$items[] = array('id' => 1, 'name' => LANG::i18n('_character_sex'), 'link' => '?action=char_sex_l');
		
		if($accserv['allow_name'])
			$items[] = array('id' => 1, 'name' => LANG::i18n('_character_name'), 'link' => '?action=char_name_l');
		
		SmartyObject::getInstance()->assign('items', $items);
		
		SmartyObject::getInstance()->register_block('dynamic', 'smarty_block_dynamic', false);
		
		SmartyObject::getInstance()->setTemplate('select.tpl');
	}
	
	function char_ufl($mod = null){
		
		global $accserv;
		
		if(is_null($mod)) {$this->index(); return;}
		
		if(!$this->allow_char_mod() || !$accserv['allow_'.$mod]) {
			MSG::add_error(LANG::i18n('_acc_serv_off'));
			$this->index();
			return;
		}
		
		unset($worlds);
		$worlds = WORLD::load_worlds(); // charging world
		
		SmartyObject::getInstance()->assign('vm', array(
			'select_item'			=> LANG::i18n('_character_'.$mod),
			'select_desc'			=> LANG::i18n('_character_'.$mod.'_desc'),
		    'return'				=> LANG::i18n('_return')
		));
		
		$items = array();
		
		foreach  ($worlds as $world) {
			foreach  ($world->get_chars() as $char) {
				$items[] = array('id' => $world->get_id(), 'name' => $world->get_name() . ' : ' .$char->getName(), 'link' => '?action=char_'.$mod.'&wid='.$world->get_id().'&cid='.$char->getId());
			}
		}
		
		if(empty($items))
			$items[] = array('id' => 0, 'name' => LANG::i18n('_any_character'), 'link' => '?action=acc_serv');
		
		SmartyObject::getInstance()->assign('items', $items);
		
		SmartyObject::getInstance()->register_block('dynamic', 'smarty_block_dynamic', false);
		
		SmartyObject::getInstance()->setTemplate('select.tpl');
	}
	
	function char_unstuck_l() {
		$this->char_ufl('unstuck');
	}
	
	function char_fix_l() {
		$this->char_ufl('fix');
	}
	
	function char_sex_l() {
		$this->char_ufl('sex');
	}
	
	function char_name_l() {
		$this->char_ufl('name');
	}
	
	function char_uf($mod = null) {
		
		if(is_null($mod)) {$this->index(); return;}

		global $accserv;
		
		if(!$this->allow_char_mod() and !$accserv['allow_'.$mod]) {
			MSG::add_error(LANG::i18n('_acc_serv_off'));
			$this->index();
			return;
		}
		
		if(empty($_GET['wid']) || empty($_GET['cid'])) {
			MSG::add_error(LANG::i18n('_error_select_char'));
			$this->index();
			return;
		}
		
		$char = new character($_GET['cid'], $_GET['wid']);
		
		if(is_null($char->getId())) {
			MSG::add_error(LANG::i18n('_error_select_char'));
			$this->index();
			return;
		}
		
		SmartyObject::getInstance()->assign('vm', array(
			'select_item'	=> LANG::i18n('_character_'.$mod),
			'select_desc'	=> sprintf(LANG::i18n('_character_'.$mod.'_confirm'), $char->getName(), world::get_name_world($char->getWorldId()), LANG::i18n('_character_sex_'.$char->getGender()), LANG::i18n('_character_sex_'.((int)(!$char->getGender())))),
		    'return'		=> LANG::i18n('_return')
		));
		
		$items = array();
		$items[] = array('id' => 1, 'name' => LANG::i18n('_confirm'), 'link' => '?action=char_'.$mod.'_confirm&wid='.$char->getWorldId().'&cid='.$char->getId());
		$items[] = array('id' => 1, 'name' => LANG::i18n('_back'), 'link' => '?action=char_'.$mod.'_l');
		SmartyObject::getInstance()->assign('items', $items);
		
		SmartyObject::getInstance()->register_block('dynamic', 'smarty_block_dynamic', false);
		SmartyObject::getInstance()->setTemplate('select.tpl');
	}
	
	function char_unstuck() {
		$this->char_uf('unstuck');
	}
	
	function char_fix() {
		$this->char_uf('fix');
	}
	
	function char_sex() {
		$this->char_uf('sex');
	}
	
	function char_name() {
		$this->char_uf('name');
	}

	function char_ufc($mod = null) {
		
		if(is_null($mod)) {$this->index(); return;}
		
		global $accserv;
		
		if(!$this->allow_char_mod() or !$accserv['allow_'.$mod]) {
			MSG::add_error(LANG::i18n('_acc_serv_off'));
			$this->index();
			return;
		}
		
		if(empty($_GET['wid']) || empty($_GET['cid'])) {
			MSG::add_error(LANG::i18n('_error_select_char'));
			$this->index();
			return;
		}
		
		$char = new character($_GET['cid'], $_GET['wid']);

		if(!$char->$mod())
			MSG::add_error(LANG::i18n('_character_'.$mod.'_no'));
		else
			MSG::add_valid(LANG::i18n('_character_'.$mod.'_yes'));

		$this->index();

		return;
	}
	
	function char_unstuck_confirm() {
		$this->char_ufc('unstuck');
	}
	
	function char_fix_confirm() {
		$this->char_ufc('fix');
	}
	
	function char_sex_confirm() {
		$this->char_ufc('sex');
	}
	
	function char_name_confirm() {
		$this->char_ufc('name');
	}

	function activation() {

		if(!$this->account->valid_account(htmlentities($_GET['key'])))
			MSG::add_error(LANG::i18n('_activation_control'));
		else
			MSG::add_valid(LANG::i18n('_account_actived'));

		$this->index();

		return;
	}
	
	function allow_char_mod() {
		global $accserv;
		
		$accserv['allow_name'] = false;
		
		if(!$accserv['allow_char_mod'])
			return false;
		
		if(!$accserv['allow_fix'] && !$accserv['allow_unstuck'] && !$accserv['allow_name'] && !$accserv['allow_sex'])
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