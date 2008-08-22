<?php

defined( '_ACM_VALID' ) or die( 'Direct Access to this location is not allowed.' );

class character extends account{

	var $charId, $char_name;

	function character($charId = null) {
		$this->MYSQL_GS = new MYSQL_GS();
		$this->charId = $charId;
	}
	
	function allow_fix() {
		global $allow_fix, $allow_fix_time;
		
		if(!$allow_fix)
			return false;
			
		$sql = 'SELECT COUNT(account_name) FROM `account_data` WHERE `account_name` = '.$this->charId.' AND `value` > '.(time()-($allow_fix_time * 3600));
		
		if($this->MYSQL_GS->result($sql) > '0')
			return false;
		
		return true;
	}
	
	function fix() {
	
		if (!$this->allow_fix)
			return false;
		
		$sql = 'UPDATE `characters` SET `x`=-84318, `y`=244579, `z`=-3730 WHERE `charId`='.$this->charId;
		$this->MYSQL_GS->query($sql);
		$sql = 'DELETE FROM `character_shortcuts` WHERE `charId`='.$this->charId;
		$this->MYSQL_GS->query($sql);
		$sql = 'UPDATE `items` SET `loc`="INVENTORY" WHERE `owner_id`='.$this->charId;
		$this->MYSQL_GS->query($sql);
		$sql = "REPLACE INTO `account_data` (account_name, var, value) VALUES ('".$this->charId."' , 'last_fix', '".time()."');";
		$this->MYSQL_GS->query($sql);
		return true;
	}
	
}
?>