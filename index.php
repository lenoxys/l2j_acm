<?php
session_start();

define ('_ACM_VALID', 1);

require './config.php';
require './classes/mysql.class.php';
require './classes/smtp.class.php';
require './classes/email.class.php';
require './classes/core.class.php';
require './classes/account.class.php';
require './classes/template.class.php';

if(DEBUG) echo '<div style="color: #000;background: #FFF;"><h1>DEBUG MODE ON</h1><br />';

if(file_exists('./language/'.$language.'.php'))
	require './language/'.$language.'.php';
else
	require './language/english.php';

$action = (!empty($_GET['action'])) ? $_GET['action'] : null;
$action = (!empty($_POST['action'])) ? $_POST['action'] : $action;

$action = htmlentities($action);
$action = htmlspecialchars($action);

$MYSQL = new MYSQL;
$MYSQL->connect();

$email_class = new EMAIL;

//------------------------------------------------------------------
// Display
//------------------------------------------------------------------

$template = new Template('./template/');
$template->set_filenames(array(
	'index' => 'index.tpl'
));

$template->assign_vars(array(
    'vm_title' => $vm['_title'],
    'vm_title_page'  => $vm['_title_page']
));

$core = new CORE();

switch ($action) {
	default:
		$core->index();
	break;
	case 'login':
		$core->login();
	break;
	case 'create':
		$core->show_create();
	break;
	case 'forgot_pwd':
		$core->show_forget();
	break;
	case 'forgot_pwd_form':
		$core->forgot_pwd();
	break;
	case 'forgot_pwd_email':
		$core->forgot_pwd2();
	break;
	case 'logged_out':
		$core->loggout();
	break;
	case 'chg_pwd':
		$core->show_chg_pwd();
	break;
	case 'chg_pwd_form':
		$core->change_pwd();
	break;
	case 'registration':
		$core->create();
	break;
	case 'activation':
		$core->activation();
	break;
	case 'chg_email':
		$core->show_chg_email();
	break;
	case 'chg_email_form':
		$core->change_email();
	break;
}
if(DEBUG) echo '</div>';

$template->assign_var_from_handle('CONTENT', 'content');

$template->pparse('index');

$MYSQL->close();

?>
