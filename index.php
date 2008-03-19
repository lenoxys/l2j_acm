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

switch ($action) {
	default:
		CORE::index();
	break;
	case 'login':
		CORE::login();
	break;
	case 'create':
		CORE::show_create();
	break;
	case 'forgot_pwd':
		CORE::show_forget();
	break;
	case 'forgot_pwd_form':
		CORE::forgot_pwd();
	break;
	case 'forgot_pwd_email':
		CORE::forgot_pwd2();
	break;
	case 'logged_out':
		CORE::loggout();
	break;
	case 'chg_pwd':
		CORE::show_chg_pwd();
	break;
	case 'chg_pwd_form':
		CORE::change_pwd();
	break;
	case 'registration':
		CORE::create();
	break;
	case 'activation':
		CORE::activation();
	break;
	case 'chg_email':
		CORE::show_chg_email();
	break;
	case 'chg_email_form':
		CORE::change_email();
	break;
}
if(DEBUG) echo '</div>';

$template->assign_var_from_handle('CONTENT', 'content');

$template->pparse('index');

$MYSQL->close();

?>
