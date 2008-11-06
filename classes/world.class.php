<?php

defined( '_ACM_VALID' ) or die( 'Direct Access to this location is not allowed.' );

/**
 *	Lineage II World class
 */

class world extends account {

	var $id, $name, $gs_host, $gs_user, $gs_pass, $gs_db;

/**
 *	Construct a new world
 *	@param $id
 *			ID of the world selected
 */
	function world($id) {
		global $MYSQL_LS, $allow_char_mod;
		
		if(!$allow_char_mod)
			exit('Access to this private class have been restricted by the admin');
		
		$this->id = $id;
		$this->set_name();
		$this->get_config();
		$this->MYSQL_GS = new MYSQL_GS($this->id);
	}

/**
 *	Get mysql identity for the world
 */
	function get_config() {
		global $gs_host, $gs_user, $gs_pass, $gs_db;
		$this->gs_host = $gs_host[$this->id];
		$this->gs_user = $gs_user[$this->id];
		$this->gs_pass = $gs_pass[$this->id];
		$this->gs_db = $gs_db[$this->id];
	}

/**
 *	Get world name
 *		return world name
 */
	function get_name(){
		return $this->name;
	}

/**
 *	Set world name
 *		return nothing
 */
	function set_name(){
		$this->name = $this->get_name_world();
	}

/**
 *	Get worlds list registred into login server
 *		return world list
 */
	function load_worlds () {
		global $MYSQL_LS, $gs_host;
		
		DEBUG::add('Getting Worlds list');
		$sql = ' SELECT `server_id` FROM `gameservers`;';
		$rslt = $MYSQL_LS->query($sql);
		
		$worlds = array();
		while ($row = mysql_fetch_object($rslt)) {
			if(!empty($gs_host[$row->server_id]))
				$worlds[] = new world($row->server_id);
			else
				DEBUG::add('World n°'.$row->server_id.' had not configuration !');
		}
		
		return $worlds;
	}

/**
 *	Get name world by id
 *		return name world
 */	
	function get_name_world () {
		$name = array();
		$name['1']="Bartz";
		$name['2']="Sieghardt";
		$name['3']="Kain";
		$name['4']="Lionna";
		$name['5']="Erica";
		$name['6']="Gustin";
		$name['7']="Devianne";
		$name['8']="Hindemith";
		$name['9']="Teon (EURO)";
		$name['10']="Franz (EURO)";
		$name['11']="Luna (EURO)";
		$name['12']="Sayha";
		$name['13']="Aria";
		$name['14']="Phoenix";
		$name['15']="Ceriel";
		$name['16']="Fehyshar";
		$name['17']="Elhwynna";
		$name['18']="Ellikia";
		$name['19']="Shikken";
		$name['20']="Scryde";
		$name['21']="Frikios";
		$name['22']="Ophylia";
		$name['23']="Shakdun";
		$name['24']="Tarziph";
		$name['25']="Aria";
		$name['26']="Esenn";
		$name['27']="Elcardia";
		$name['28']="Yiana";
		$name['29']="Seresin";
		$name['30']="Tarkai";
		$name['31']="Khadia";
		$name['32']="Roien";
		$name['33']="Gallint";
		$name['34']="Cedric";
		$name['35']="Nerufa";
		$name['36']="Asterios";
		$name['37']="Orfen";
		$name['38']="Mitrael";
		$name['39']="Thifiel";
		$name['40']="Lithra";
		$name['41']="Lockirin";
		$name['42']="Kakai";
		$name['43']="Cadmus";
		$name['44']="Athebaldt";
		$name['45']="Blackbird";
		$name['46']="Ramsheart";
		$name['47']="Esthus";
		$name['48']="Vasper";
		$name['49']="Lancer";
		$name['50']="Ashton";
		$name['51']="Waytrel";
		$name['52']="Waltner";
		$name['53']="Tahnford";
		$name['54']="Hunter";
		$name['55']="Dewell";
		$name['56']="Rodemaye";
		$name['57']="Ken Rauhel";
		$name['58']="Ken Abigail";
		$name['59']="Ken Orwen";
		$name['60']="Van Holter";
		$name['61']="Desperion";
		$name['62']="Einhovant";
		$name['63']="Schuneimann";
		$name['64']="Faris";
		$name['65']="Tor";
		$name['66']="Carneiar";
		$name['67']="Dwyllios";
		$name['68']="Baium";
		$name['69']="Hallate";
		$name['70']="Zaken";
		$name['71']="Core";
		return $name[$this->id];
	}

/**
 * Verify 
 *
 *
 *
 *
 */
	function verif_tag($login, $tag, $value){
		$sql = "SELECT COUNT(account_name) FROM `account_data` WHERE " .
				"`account_name` = '".$login."' " .
				"AND `var` = '".$tag."' " .
				"AND `value` = '".$value."' LIMIT 1;";
		
		DEBUG::add('Check the tag on account_data');

		if($this->MYSQL->result($sql) != 1)
			return false;

		return true;
	}

/**
 * Getting all characters in the world linked with the account gived in parameter
 * 
 * @param $login : account name
 * 
 * 		return list of their chars
 */
	function get_chars($login) {
		$chars = array();
		
		$sql = 'SELECT `charId` FROM `characters` WHERE `account_name` = "'.$login.'";';
		
		$this->MYSQL_GS->connect();
		$rslt = $this->MYSQL_GS->query($sql);
		
		while ($row = mysql_fetch_object($rslt)) {
			$char = new character($row->charId, $login, $this);
			$chars[] = $char;
		}
		
		$this->MYSQL_GS->close();
		
		return $chars;
	}

}
?>