<?php

defined( '_ACM_VALID' ) or die( 'Direct Access to this location is not allowed.' );

define ('DEBUG', false);								// Enable debug mode ; for set on replace false by true

//	#	Login Server Configuration

$ls_host	= 'localhost';								// login host database DB
$ls_user	= 'root';									// login user
$ls_pass	= '';										// login password
$ls_db		= 'l2jdb';									// login database name

//	#	Game Server Configuration

$gs_host	= 'localhost';								// game host database DB
$gs_user	= 'root';									// game user
$gs_pass	= '';										// game password
$gs_db		= 'l2jdb';									// game database name


$server_name	= 'Private Server';						// server name
$email_from		= 'support@host.com';					// Specify an adress email source

$act_img		= true;									// Activate image verification ; set false if you want desactivate
$act_email		= false;								// Activate email verification ; set false if you want desactivate

$acc_limit		= false;								// How many account can be registered ; set false for unlimited

$same_email		= false;								// Allowed same email for different account ; set false if you want prohibit

$id_limit		= 15;									// Limit id characters
$pwd_limit		= 15;									// Limit pwd characters

$language		= 'english';							// language use by Account Manager :: name of language file in language folder

$can_chg_email	= false;								// User can change email ; set false if you want prohibit (If there are no email registered. Option is avaible same if you have set prohibited)

$ack_cond		= false;								// User must accept before register :: conditions can be edited on language file

$tmp			= 'kamael';								// template directory

//#################
//#Advanced Config#
//#################

$use_smtp = false;										// Set to true if you want use an smtp server
$smtp_address = 'smtp.server.com';						// Address of the smtp server
$smtp_port = 25;										// Port of the smtp server
$smtp_login = '';										// login of the smtp server if you need authentication
$smtp_password = '';									// password of the smtp server if you need authentication
$smtp_domain = '';										// domain of your host

$id_regex	= '`^[[:alnum:]]{4,'.$id_limit.'}$`';		// allow alphanumeric character in login name and login character min needed is 4
$pwd_regex	= '`^[[:alnum:]@\\\/]{4,'.$pwd_limit.'}$`';	//allow alphanumeric character and \ / @ in password and pwd character min needed is 4

?>
