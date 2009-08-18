<?php

if(!file_exists('./install.php'))
	exit('<center><font color="#FF0000"><strong>lol trying to check some _install.php. nice try :]</strong></font></center>'."\n\r");

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Install of ACM</title>
</head>
<body>
<?php

define ('_ACM_VALID', 1);

if(!file_exists('./config.php'))
	exit('<center><font color="#FF0000"><strong>config.php is missing. <br />We aren\'t able to check your installation.</strong></font></center>'."\n\r");

include ('./classes/config.class.php');
include ('./config.php');

function check_mysql() {
	$ls = CONFIG::g()->login_server;
	echo '<li> connected ? <font ';
	echo (@mysql_connect($ls['hostname'], $ls['user'], $ls['password'])) ? 'color="#00FF00">OK' : 'color="#FF0000">KO';
	echo '</font></li>'."\n\r";
}

function check_db() {
	$ls = CONFIG::g()->login_server;
	echo '<li> connected ? <font ';
	echo (@mysql_select_db($ls['database'])) ? 'color="#00FF00">OK' : 'color="#FF0000">KO';
	echo '</font></li>'."\n\r";

}

function check_tables() {
	$ls = CONFIG::g()->login_server;
	
	$tables_req = array('accounts', 'account_data');
	
	$sql = 'SHOW TABLES FROM ' . $ls['database'];
	$result = @mysql_query($sql);
	
	$tables = array();
	
	while ($row = @mysql_fetch_row($result))
		$tables[] = $row[0];
	
	foreach ($tables_req as $tab) {
		echo '<li>' . $tab . ' exist ? <font ';
		echo (in_array($tab, $tables)) ? 'color="#00FF00">OK' : 'color="#FF0000">KO';
		echo '</font></li>'."\n\r";
	}
}

function check_fields() {
	
	$table = 'accounts';
	$fields_req = array('email', 'created_time');
	
	$sql = 'SHOW COLUMNS FROM ' . $table;
	$result = @mysql_query($sql);
	
	$fields = array();
	
	while ($row = @mysql_fetch_row($result))
		$fields[] = htmlspecialchars($row[0]);
	
	foreach ($fields_req as $field) {
		echo '<li>' . $field . ' exist ? <font ';
		echo (array_search($field, $fields)) ? 'color="#00FF00">OK' : 'color="#FF0000">KO';
		echo '</font></li>'."\n\r";
	}
}

function check_libs() {
	$libs = array('mysql', 'gd', 'openssl');
	foreach ($libs as $lib)
		check_lib($lib);
}

function check_lib($lib_name) {
	echo '<li>'.$lib_name.' extension : <font ';
	echo (extension_loaded($lib_name)) ? 'color="#00FF00">OK' : 'color="#FF0000">KO';
	echo '</font></li>'."\n\r";
}

function check_folder(){
	$d = substr(sprintf('%o', fileperms('./cache')), -4);
	echo '<li>cache folder : <font ';
	echo ((($d == '0755' or $d == '0777')) ? 'color="#00FF00">OK' : 'color="#FF0000">KO');
	echo '</font></li>';
}

switch(htmlentities(@$_GET['m'])) {
	case 'check':
	
	echo '<br />Checking extensions loaded :'."\n\r";
	
	echo '<ul>'."\n\r";
	check_libs();
	echo '</ul>'."\n\r";
	
	echo '<br />Checking mysql connexion :'."\n\r";
	echo '<ul>'."\n\r";
	check_mysql();
	echo '</ul>'."\n\r";
	
	echo '<br />Checking db connexion :'."\n\r";
	echo '<ul>'."\n\r";
	check_db();
	echo '</ul>'."\n\r";
	
	echo '<br />Checking table connexion :'."\n\r";
	echo '<ul>'."\n\r";
	check_tables();
	echo '</ul>'."\n\r";
	
	echo '<br />Checking sql parsing on accounts table :'."\n\r";
	echo '<ul>'."\n\r";
	check_fields();
	echo '</ul>'."\n\r";
	
	echo '<br />Checking rights of the cache folder :'."\n\r";
	echo '<ul>'."\n\r";
	check_folder();
	echo '</ul>'."\n\r";
?>
	
<?php
	break;
	
	default:
?>

<form action="./install.php" method="get">
<input type="hidden" name="m" value="check" />
<input type="submit" value="Check UP" />
</form>

<?php
	break;
}
?>
</body>
</html>