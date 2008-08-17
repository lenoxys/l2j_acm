<?php

#		Script for clean unverified accounts. Be sure to execute sql syntaxe before to use this script.

$hours		= 0;		// How many		hours		before to delete unverified accounts.
$days		= 1;		// ... 			days		...
$months		= 0;		// ... 			months		...
$years		= 0;		// ... 			years		...

#		---------------------------------------------------------------------------------------------------

if(file_exists('./clean.php'))
	exit('Please rename this file for increase security !');


define ('_ACM_VALID', 1);

require './config.php';
require './classes/mysql.class.php';

$s = time();

$sql = ' FROM `accounts` WHERE `accessLevel` = -1 AND `created_time` < "'.date("Y-m-d H:i:s", mktime(date("H")-$hours, date("i"), 0, date("m")-$months, date("d")-$days, date("Y")-$years)).'";';

$MYSQL = new MYSQL;
$MYSQL->connect();
$r = $MYSQL->result('SELECT COUNT(`login`)'.$sql);
print 'DELETING ACCOUNT...<br />';
$p = $MYSQL->query('DELETE'.$sql);
sleep(1);
echo 'DELETING LINKS IN ACCOUNT_DATA TABLE...<br /><br />';
$MYSQL->query('DELETE FROM `account_data` WHERE `account_data`.`account_name` NOT IN (SELECT login FROM `accounts`);');
sleep(1);
$MYSQL->query('OPTIMIZE TABLE `accounts`, `account_data`;');
$MYSQL->close();

echo ($p) ? $r.' account(s) has been cleaned.' : 'Nothing cleaned. If you see this message you will need to check : 
<li>your configuration</li>
<li>rights gived to the acm</li>
<li><strong>Don\'t forget to patch the account table with this script: <br />
ALTER TABLE `accounts` ADD `created_time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP;<br />
UPDATE `l2jdb`.`accounts` SET `created_time` = NOW();</strong></li>';

$e = time();

echo '<br />Done in '.($e-$s).' seconds.';

?>