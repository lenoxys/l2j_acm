<?php

if(!file_exists('./install.php'))
	exit('<center><font color="#FF0000"><strong>lol trying to check some _install.php. nice try :]</strong></font></center>'."\n\r");

?><!doctype html>
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
require ('./config.php');

function check_mysql() {
	$ls = CONFIG::g()->login_server;
	$connection = @mysqli_connect($ls['hostname'], $ls['user'], $ls['password']);
	echo '<li> connected ? <font ';
	echo ($connection) ? 'color="#00FF00">OK' : 'color="#FF0000">KO';
	echo '</font></li>'."\n\r";
	if ($connection) {
		mysqli_close($connection);
	}
}

function check_db() {
	$ls = CONFIG::g()->login_server;
	$connection = @mysqli_connect($ls['hostname'], $ls['user'], $ls['password'], $ls['database']);
	echo '<li> connected ? <font ';
	echo ($connection) ? 'color="#00FF00">OK' : 'color="#FF0000">KO';
	echo '</font></li>'."\n\r";
	if ($connection) {
		mysqli_close($connection);
	}
}

function check_tables() {
	$ls = CONFIG::g()->login_server;
	$connection = @mysqli_connect($ls['hostname'], $ls['user'], $ls['password'], $ls['database']);
	
	$tables_req = array('accounts', 'account_data');
	$tables = array();
	
	if ($connection) {
		$sql = 'SHOW TABLES';
		$result = @mysqli_query($connection, $sql);
		while ($row = @mysqli_fetch_row($result)) {
			$tables[] = $row[0];
		}
		mysqli_close($connection);
	}
	
	foreach ($tables_req as $tab) {
		echo '<li>' . $tab . ' exist ? <font ';
		echo (in_array($tab, $tables)) ? 'color="#00FF00">OK' : 'color="#FF0000">KO';
		echo '</font></li>'."\n\r";
	}
}

function check_fields() {
	$ls = CONFIG::g()->login_server;
	$connection = @mysqli_connect($ls['hostname'], $ls['user'], $ls['password'], $ls['database']);
	
	$table = 'accounts';
	$fields_req = array('email', 'created_time');
	$fields = array();
	
	if ($connection) {
		$sql = 'SHOW COLUMNS FROM ' . $table;
		$result = @mysqli_query($connection, $sql);
		while ($row = @mysqli_fetch_row($result)) {
			$fields[] = htmlspecialchars($row[0]);
		}
		mysqli_close($connection);
	}
	
	foreach ($fields_req as $field) {
		echo '<li>' . $field . ' exist ? <font ';
		echo (in_array($field, $fields)) ? 'color="#00FF00">OK' : 'color="#FF0000">KO';
		echo '</font></li>'."\n\r";
	}
}

function check_libs() {
	$libs = array('mysqli', 'gd', 'openssl');
	foreach ($libs as $lib)
		check_lib($lib);
}

function check_lib($lib_name) {
	echo '<li>'.$lib_name.' extension : <font ';
	echo (extension_loaded($lib_name)) ? 'color="#00FF00">OK' : 'color="#FF0000">KO';
	echo '</font></li>'."\n\r";
}

function check_folder(){
	echo '<li>cache folder : <font ';
	echo (is_writable('./cache/')) ? 'color="#00FF00">OK' : 'color="#FF0000">KO';
	echo '</font></li>';
}

function check_version() {
	echo '<li>PHP version : '.phpversion().' <font ';
	echo (phpversion() >= 8) ? 'color="#00FF00">OK' : 'color="#FF0000">KO';
	echo '</font></li>';
}

switch(htmlentities(@$_GET['m'] ?? '')) {
	case 'check':
	
	echo '<br />Checking php version :'."\n\r";
	
	echo '<ul>'."\n\r";
	check_version();
	echo '</ul>'."\n\r";
	
	echo '<br />Checking extensions loaded :'."\n\r";
	
	echo '<ul>'."\n\r";
	check_libs();
	echo '</ul>'."\n\r";
	
	echo '<br />Checking mysql connection :'."\n\r";
	echo '<ul>'."\n\r";
	check_mysql();
	echo '</ul>'."\n\r";
	
	echo '<br />Checking db connection :'."\n\r";
	echo '<ul>'."\n\r";
	check_db();
	echo '</ul>'."\n\r";
	
	echo '<br />Checking tables :'."\n\r";
	echo '<ul>'."\n\r";
	check_tables();
	echo '</ul>'."\n\r";
	
	echo '<br />Checking fields in accounts table :'."\n\r";
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
