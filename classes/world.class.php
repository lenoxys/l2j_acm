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
		
		$this->id = mysqli_real_escape_string($id);
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
		while ($row = @mysqli_fetch_object($rslt)) {
			$w = (CONFIG::g()->select_game_server($row->server_id));
			if(!empty($w))
				$worlds[] = new world($row->server_id);
			else
				DEBUG::add('World n�'.$row->server_id.' had not configuration !');
		}
		
		return $worlds;
	}

/**
 *	Get name world by id
 *		return name world
 */	
	public function get_name_world ($id) {
		$id = MYSQL::g()->escape_string($id);
		$dom = new DOMDocument;
		$dom->load(CONFIG::g()->service_server_name);
		return iconv('utf-8',CONFIG::g()->core_iso_type,$dom->getElementsByTagName('server')->item(($id-1))->getAttribute("name"));
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
		
		$sql = sprintf("SELECT `charId`, `char_name` FROM `characters` WHERE `account_name` = '%s';",
				MYSQL::g()->escape_string(ACCOUNT::load()->getLogin())
			);
		
		$rslt = MYSQL::g($this->id)->query($sql);
		
		while ($row = @mysqli_fetch_object($rslt)) {
			$char = new character ($row->charId, $this->id);
			$this->char_list[] = $char;
		}
	}

}
?>