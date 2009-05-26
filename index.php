<?php

if(file_exists('./install.php'))
	echo('<div style="font-size: 20px; background-color: #FFF; color:#000;"><strong><center><br />Warning: The install file can be see. Please delete install.php before start ACM on your live server.<br /><br /></center></strong></div>');

session_start();

define ('_ACM_VALID', 1);

require './config.php';
require './libs/Smarty.class.php';
require './classes/system.class.php';
require './classes/mysql.class.php';
require './classes/smtp.class.php';
require './classes/email.class.php';
require './classes/core.class.php';
require './classes/login.class.php';
require './classes/account.class.php';
require './classes/world.class.php';
require './classes/character.class.php';

if(file_exists('./language/'.$language.'.php'))
	require './language/'.$language.'.php';
else
	require './language/english.php';

$action = (!empty($_GET['action'])) ? $_GET['action'] : 'index';
$action = (!empty($_POST['action'])) ? $_POST['action'] : $action;

$action = htmlentities($action);
$action = htmlspecialchars($action);

$MYSQL_LS = new MYSQL_LS;
$MYSQL_LS->connect();

//------------------------------------------------------------------
// Display
//------------------------------------------------------------------

$template = new Smarty2;

$template->assign('vm_title', $vm['_title']);
$template->assign('vm_title_page', $vm['_title_page']);

$core = new CORE();

if(method_exists($core, $action))
	$core->$action();
else
	$core->index();

$MYSQL_LS->close();

?>