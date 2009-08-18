<?php
session_start();

if(file_exists('./install.php'))
	echo('<div style="font-size: 20px; background-color: #FFF; color:#000;"><strong><center><br />Warning: The install file can be see. Please delete install.php before start ACM on your live server.<br /><br /></center></strong></div>');


define ('_ACM_VALID', 1);

require './classes/config.class.php';
require './config.php';
require './libs/Smarty.class.php';
require './classes/system.class.php';
require './classes/mysql.class.php';
require './classes/smtp.class.php';
require './classes/email.class.php';
require './classes/core.class.php';
require './classes/account.class.php';
require './classes/world.class.php';
require './classes/character.class.php';

if(SID != '') {
	SmartyObject::getInstance()->assign('session_id', '?'.SID);
	DEBUG::add(LANG::i18n('_cookie_prob'));
}

$action = (!empty($_GET['action'])) ? $_GET['action'] : 'index';
$action = (!empty($_POST['action'])) ? $_POST['action'] : $action;

$action = htmlentities($action);
$action = htmlspecialchars($action);

//------------------------------------------------------------------
// Display
//------------------------------------------------------------------

SmartyObject::getInstance()->assign('vm_title', LANG::i18n('_title'));
SmartyObject::getInstance()->assign('vm_title_page', LANG::i18n('_title_page'));

$core = new CORE();

if(method_exists($core, $action))
	$core->$action();
else
	$core->index();

SmartyObject::getInstance()->display();

?>