<?php

defined( '_ACM_VALID' ) or die( 'Direct Access to this location is not allowed.' );

/**
 *	Lineage II World class
 */

class world {

	private $id, $name, $char_list;

/**
 *	Construct a new world
 *	@param $id
 *			ID of the world selected
 */
	public function __construct($id) {
		
		if(!CONFIG::g()->service_allow)
			exit('Access to this private class have been restricted by the admin');
		
		if(CONFIG::g()->core_interlude)
			exit('Accounts Services can\'t be used with interlude server');
		
		$this->id = $id;
		$this->set_name();
		$this->load_chars();
	}

/**
 *	Get world id
 *		return world id
 */
	public function get_id(){
		return $this->id;
	}

/**
 *	Get world name
 *		return world name
 */
	public function get_name(){
		return $this->name;
	}

/**
 *	Set world name
 *		return nothing
 */
	private function set_name(){
		$this->name = $this->get_name_world($this->id);
	}

/**
 *	Get world characters list
 *		return world characters list
 */
	public function get_chars(){
		return $this->char_list;
	}

/**
 *	Get worlds list registred into login server
 *		return world list
 */
	public function load_worlds () {
		
		DEBUG::add('Getting Worlds list');
		$sql = 'SELECT `server_id` FROM `gameservers`;';
		$rslt = MYSQL::g()->query($sql);
		
		$worlds = array();
		while ($row = @mysql_fetch_object($rslt)) {
			$w = (CONFIG::g()->select_game_server($row->server_id));
			if(!empty($w))
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
	public function get_name_world ($id) {
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
		return $name[$id];
	}

/**
 * Getting all characters in the world linked with the account gived in parameter
 * 
 * @param $login : account name
 * 
 * 		return list of their chars
 */
	function load_chars() {
		$this->char_list = array();
		
		$sql = 'SELECT `charId`, `char_name` FROM `characters` WHERE `account_name` = "'.(ACCOUNT::load()->getLogin()).'";';
		
		$rslt = MYSQL::g($this->id)->query($sql);
		
		while ($row = @mysql_fetch_object($rslt)) {
			$char = new character ($row->charId, $this->id);
			$this->char_list[] = $char;
		}
	}

}
?>