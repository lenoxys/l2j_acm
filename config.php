<?php

defined( '_ACM_VALID' ) or die( 'Direct Access to this location is not allowed.' );

define ('DEBUG', true);								// Enable debug mode ; for set on replace false by true
define ('LOG', false);									// Enable log mode ; for set off replace true by false

//	#	Login Server Configuration

$ls_host		= 'localhost';							// login host database DB
$ls_user		= 'root';								// login user
$ls_pass		= '';									// login password
$ls_db			= 'l2jdb_login';								// login database name

//	#	Game Server Configuration

$id = 1;												// Id of the game server
$gs_host[$id]	= 'localhost';							// game host database DB
$gs_user[$id]	= 'root';								// game user
$gs_pass[$id]	= '';									// game password
$gs_db[$id]		= 'l2jdb';								// game database name



//Copy line under this text for add a new world. The id need to be same as the id registred into gameservers table.

$id = 6;												// Id of the game server
$gs_host[$id]	= 'localhost';							// game host database DB
$gs_user[$id]	= 'root';								// game user
$gs_pass[$id]	= '';									// game password
$gs_db[$id]		= 'l2jdb2';								// game database name



//	#	Web Configuration

$server_name	= 'Private Server';						// server name
$email_from		= 'support@host.com';					// Specify an adress email source

$act_img		= true;									// Activate image verification ; set false if you want desactivate
$act_email		= true;									// Activate email verification ; set false if you want desactivate

$acc_limit		= false;								// How many account can be registered ; set false for unlimited

$same_email		= false;								// Allowed same email for different account ; set false if you want prohibit

$id_limit		= 15;									// Limit id characters
$pwd_limit		= 15;									// Limit pwd characters

$language		= 'english';							// language use by Account Manager :: name of language file in language folder

$can_chg_email	= false;								// User can change email ; set false if you want prohibit (If there are no email registered. Option is avaible same if you have set prohibited)

$ack_cond		= false;								// User must accept before register :: conditions can be edited on language file

$template		= 'kamael';								// template directory

$interlude		= false;								// If you are using interlude database set to true

//#####################
//# Characters Config #
//#####################

// Don't use those settings for now keep to false please

$accserv['allow_char_mod']				= true;							// Enable characters mod
$accserv['time_account_services']		= 7;							// Need to wait 7 days after change gender before to change another time for the same or another character

$accserv['allow_name']					= true;						// Enable Account services feature (change name) for offline character
$accserv['name_regex']					= '`^[[:alnum:]]{3,16}$`';		// allow alphanumeric character in char name (3-16 char.)

$accserv['allow_sex']					= false;						// Enable Account services feature (change gender) for offline character
$accserv['item_female_only']			= array(8559,8913,8917);		// Check female-use only items before to change gender
$accserv['item_male_only']				= array(8923);					// Check male-use only items before to change gender

$accserv['allow_fix']					= false;							// Enable fixing feature for offline character
$accserv['time_fix']					= 24;							// Need to wait 24 hours before to use fix or unstuck against

$accserv['allow_unstuck']				= false;							// Enable unstuck feature for offline character
$accserv['coord_static']				= false;						// Use static coords when using unstuck and fix feature
$accserv['coord_default']				= array(0,0,0);					// When coord static enable tp player to the coords (x,y,z)

//###################
//# Advanced Config #
//###################

$smtp['use']		= false;							// Set to true if you want use an smtp server
$smtp['address']	= 'smtp.server.com';				// Address of the smtp server
$smtp['port']		= 25;								// Port of the smtp server
$smtp['login']		= '';								// login of the smtp server if you need authentication
$smtp['password']	= '';								// password of the smtp server if you need authentication
$smtp['domain']		= '';								// domain of your host

$id_regex	= '`^[[:alnum:]]{4,'.$id_limit.'}$`';		// allow alphanumeric character in login name and login character min needed is 4
$pwd_regex	= '`^[[:alnum:]@\\\/]{4,'.$pwd_limit.'}$`';	//allow alphanumeric character and \ / @ in password and pwd character min needed is 4
$accesslevel = ($interlude) ? 'access_level' : 'accessLevel';

?>