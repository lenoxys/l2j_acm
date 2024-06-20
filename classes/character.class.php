<?php

defined( '_ACM_VALID' ) or die( 'Direct Access to this location is not allowed.' );

class character {

	private $charId, $char_name, $worldId, $base_class, $sex, $accesslevel, $x, $y, $region, $online, $clanid, $level;

	function __construct($charId, $worldId) {
		
		if(!CONFIG::g()->service_allow)
			exit('Access to this private class have been restricted by the admin');
		
		if(CONFIG::g()->core_interlude)
			exit('Accounts Services can\'t be used with interlude server');
		
		$this->charId = $charId;
		$this->worldId = $worldId;
		
		if(!$this->is_owner()) {
			$this->charId = null;
			$this->worldId = null;
			return false;
		}
		
		$this->load();

		return true;
	}
	
	function getId() {
		return $this->charId;
	}
	
	function getWorldId() {
		return $this->worldId;
	}
	
	function getName() {
		return $this->char_name;
	}
	
	function getGender() {
		return $this->sex;
	}
	
	function getClassId() {
		return $this->classid;
	}
	
	function getLevel() {
		return $this->level;
	}
	
	function is_owner () {
		
		$sql = 'SELECT COUNT(charId) FROM `characters` 
						WHERE `account_name` = "'.(ACCOUNT::load()->getLogin()).'" AND `charId` = "'.$this->charId.'";';
		
		if(MYSQL::g($this->worldId)->result($sql) == '0')
			return false;
		
		return true;
	}
	
	function load() {
		$sql = "SET NAMES '".CONFIG::g()->core_iso_type."'";
		$rslt = MYSQL::g($this->worldId)->query($sql);
		$sql = 'SELECT `char_name`, `base_class`, `sex`, `accesslevel`, `x`, `y`, `online`, `clanid`, `level`, `karma` FROM `characters` WHERE `charId` = "'.$this->charId.'" LIMIT 1;';
		$rslt = MYSQL::g($this->worldId)->query($sql);
		$row = @mysqli_fetch_object($rslt);
		
		$this->char_name	= $row->char_name;
		$this->base_class	= (int)$row->base_class;
		$this->sex			= $row->sex;
		$this->accesslevel	= $row->accesslevel;
		$this->x			= $row->x;
		$this->y			= $row->y;
		$this->online		= $row->online;
		$this->clanid		= $row->clanid;
		$this->level		= $row->level;
		$this->karma		= $row->karma;
		
		return true;
	}
	
	function reload($chp) {
		$sql = 'SELECT `'.$chp.'` FROM `characters` WHERE `charId` = "'.$this->charId.'" LIMIT 1;';
		$rslt = MYSQL::g($this->worldId)->query($sql);
		$row = @mysqli_fetch_object($rslt);
		
		$this->$chp	= $row->$chp;
		
		return true;
	}
	
	function is_online() {
		$this->reload('online');
		
		if($this->online == 1)
			return true;
		
		return false;
	}
	
	function is_ban () {
		$this->reload('accesslevel');
		
		if ($this->accesslevel < 0)
			return true;
		
		return false;
	}
	
	function is_hero () {
		
		$sql = 'SELECT COUNT(charId) FROM `heroes` 
						WHERE `charId` = "'.$this->charId.'";';
		
		if(MYSQL::g($this->worldId)->result($sql) == '0')
			return false;
		
		return true;
	}
	
	function have_clan () {
		$this->reload('clanid');
		
		if ($this->clanid == 0)
			return false;
		
		return true;
	}
	
	function allow_with_karma() {
		if(CONFIG::g()->service_allow_with_karma)
			return true;
		
		if($this->karma != 0)
			return false;
		
		return true;
	}
	
	function allow_fix($unstuck = false) {
	
		if(is_null($this->charId)){
			MSG::add_error(LANG::i18n('_error_select_char'));
			return false;
		}
		
		if(!$this->allow_with_karma()){
			MSG::add_error(LANG::i18n('_allow_with_karma'));
			return false;
		}
		
		if($unstuck) {
			if(!CONFIG::g()->service_unstuck) {
				MSG::add_error(LANG::i18n('_allow_unstuck'));
				return false;
			}
		} else {
			if(!CONFIG::g()->service_fix) {
				MSG::add_error(LANG::i18n('_allow_fix'));
				return false;
			}
		}
		
		DEBUG::add('Look if tag is here');
		
		$last = ($unstuck) ? 'unstuck' : 'fix';
		
		$sql = 'SELECT COUNT(account_name) FROM `account_data` WHERE `var` = "last_'.$last.'" AND `account_name` = '.$this->charId.' AND `value` > '.(time()-(CONFIG::g()->service_fix_time * 3600));
		
		if(MYSQL::g($this->worldId)->result($sql) > '0') {
			MSG::add_error(sprintf(LANG::i18n('_allow_time'), CONFIG::g()->service_fix_time, $last));
			return false;
		}
		
		DEBUG::add('Look if player is online');
		
		if($this->is_online()) {
			MSG::add_error(LANG::i18n('_char_online'));
			return false;
		}
		
		return true;
	}
	
	function fix() {
	
		if (!$this->allow_fix())
			return false;
		
		$t = $this->get_nearest_town();
		
		
		DEBUG::add('Fix position of character');
		$sql = 'UPDATE `characters` SET `x`='.$t[0].', `y`='.$t[1].', `z`='.$t[2].' WHERE `charId`='.$this->charId.';';
		MYSQL::g($this->worldId)->query($sql);
		
		DEBUG::add('Fix shortcuts of character');
		$sql = 'DELETE FROM `character_shortcuts` WHERE `charId`='.$this->charId.';';
		MYSQL::g($this->worldId)->query($sql);
		
		DEBUG::add('Fix inventory of character');
		$sql = 'UPDATE `items` SET `loc`="INVENTORY" WHERE `owner_id`='.$this->charId.';';
		MYSQL::g($this->worldId)->query($sql);
		
		DEBUG::add('Fix buffs of character');
		$sql = 'DELETE FROM character_skills_save WHERE charId = '.$this->charId.';';
		MYSQL::g($this->worldId)->query($sql);
		
		DEBUG::add('Add a tag for prevent abus');
		$sql = "REPLACE INTO `account_data` (account_name, var, value) VALUES ('".$this->charId."' , 'last_fix', '".time()."');";
		MYSQL::g($this->worldId)->query($sql);
		
		return true;
	}
	
	function unstuck () {
	
		if (!$this->allow_fix(true))
			return false;
		
		$t = $this->get_nearest_town();
		
		DEBUG::add('Fix position of character');
		$sql = 'UPDATE `characters` SET `x`='.$t[0].', `y`='.$t[1].', `z`='.$t[2].' WHERE `charId`='.$this->charId.';';
		MYSQL::g($this->worldId)->query($sql);
		
		DEBUG::add('Add a tag for prevent abus');
		$sql = "REPLACE INTO `account_data` (account_name, var, value) VALUES ('".$this->charId."' , 'last_unstuck', '".time()."');";
		MYSQL::g($this->worldId)->query($sql);
		
		return true;
	}
	
	function name($new_name) {
		
		if( !$this->can_change_name($new_name) )
			return false;
		
		DEBUG::add('Change name of the character');
		$sql = 'UPDATE `characters` SET `char_name` = '.$new_name.' WHERE `charId`='.$this->charId.';';
		MYSQL::g($this->worldId)->query($sql);
		
		DEBUG::add('Add a tag for prevent abus');
		$sql = "REPLACE INTO `account_data` (account_name, var, value) VALUES ('".$this->charId."' , 'previous_name', '".$this->char_name."');";
		MYSQL::g($this->worldId)->query($sql);
		
		$this->char_name = $new_name;
			
		return true;
	}
	
	function can_change_name ($new_name = null, $test = null) {
		
		if( !CONFIG::g()->service_name) {	// Check if the admin allow account services
			MSG::add_error(LANG::i18n('_acc_serv_off'));
			return false;
		}
	
		if(is_null($this->charId)){
			MSG::add_error(LANG::i18n('_error_select_char'));
			return false;
		}
		
		$sql = 'SELECT COUNT(account_name) FROM `account_data` 
						WHERE `var` = "previous_name" 
							AND `account_name` = "'.$this->charId.'";';
		
		if(MYSQL::g($this->worldId)->result($sql) > '0') {		// Check if character has already changed him name.
			MSG::add_error(LANG::i18n('_acc_serv_name_error1'));
			return false;
		}
		
		if(is_null($new_name)) {		// Check if the new name is the same than currently
			MSG::add_error(LANG::i18n('_acc_serv_name_error2'));
			return false;
		}
		
		if($new_name == $this->char_name) {		// Check if the new name is the same than currently
			MSG::add_error(LANG::i18n('_acc_serv_name_error3'));
			return false;
		}
		
		if( $this->is_ban() ) {				// Check if the character is banned
			MSG::add_error(LANG::i18n('_acc_serv_ban'));
			return false;
		}
		
		if (!preg_match(CONFIG::g()->service_name_regex , $new_name)) {				// Check if new name is a valid name
			MSG::add_error(LANG::i18n('_acc_serv_name_error4'));
			return false;
		}
		
		$sql = 'SELECT COUNT(char_name) FROM `characters` 
						WHERE `var` = "previous_name" 
							AND `char_name` = "'.$new_name.'";';
		
		if($this->clanid != '0') {		// Check if character is in clan.
			MSG::add_error(LANG::i18n('_acc_serv_name_error5'));
			return false;
		}
		
		if(!$this->is_hero()) {		// Check if character is hero.
			MSG::add_error(LANG::i18n('_acc_serv_name_error6'));
			return false;
		}
		
		return true;
	}
	
	function sex() {
		global $accserv;
		
		if(!$this->can_change_gender())
			return false;
		
		$items = ($this->sex == 0) ? CONFIG::g()->service_sex_item_male : CONFIG::g()->service_sex_item_female;		// Check which items list by gender
		foreach ($items as $id) {												// Foreach items listed set in inventory if they exist.
			$sql = 'UPDATE `items` SET `loc` = "INVENTORY" WHERE `owner_id` = '.$this->charId.' AND `item_id` = '.$id.';';
			MYSQL::g($this->worldId)->query($sql);
		}
		
		$this->sex = ($this->sex == 1) ? 0 : 1;
		
		DEBUG::add('Change gender of the character');
		$sql = 'UPDATE `characters` SET `sex` = '.$this->sex.', `face` = 0, `hairStyle` = 0,`hairColor` = 0 WHERE `charId`='.$this->charId.';';
		MYSQL::g($this->worldId)->query($sql);
		
		DEBUG::add('Add a tag for prevent abus');
		$sql = "REPLACE INTO `account_data` (account_name, var, value) VALUES ('".$this->charId."' , 'last_gender_change', '".time()."');";
		MYSQL::g($this->worldId)->query($sql);
		
		return true;
	}
	
	function can_change_gender ($test = null) {
		
		if( !CONFIG::g()->service_sex) {	// Check if the admin allow account services
			MSG::add_error(LANG::i18n('_acc_serv_off'));
			return false;
		}
	
		if(is_null($this->charId)){
			MSG::add_error(LANG::i18n('_error_select_char'));
			return false;
		}
		
		$sql = 'SELECT COUNT(account_name) FROM `account_data` 
						WHERE `var` = "last_gender_change" 
							AND `account_name` = "'.$this->charId.'" 
							AND `value` > "'.(time()-(CONFIG::g()->service_sex_time * 24 * 3600)).'";';
		
		if(MYSQL::g($this->worldId)->result($sql) > '0') {
			MSG::add_error(LANG::i18n('_acc_serv_gender_time'));
			return false;
		}
		
		if( $this->is_online() ) {				// Check if the character is online
			MSG::add_error(LANG::i18n('_acc_serv_offline'));
			return false;
		}
		
		if( $this->is_ban() ) {				// Check if the character is banned
			MSG::add_error(LANG::i18n('_acc_serv_ban'));
			return false;
		}
		
		if( $this->base_class >= 123 && $this->base_class <= 136 ) {          // Check if the character is kamael
			MSG::add_error(LANG::i18n('_acc_serv_gender_kamael'));
			return false;
		}
		
		return true;
	}
	
	function get_nearest_town() {
		$this->mapRegionTable();
		$town_id = $this->getMapRegion($this->x,$this->y);
		return $this->get_spawn_town($town_id);
	}
	
	function mapRegionTable() {
		$sql = 'SELECT region, sec0, sec1, sec2, sec3, sec4, sec5, sec6, sec7, sec8, sec9, sec10,sec11,sec12,sec13,sec14,sec15 FROM mapregion;';
		
		$rslt = MYSQL::g($this->worldId)->query($sql);
		
		while ($row = @mysqli_fetch_row($rslt)) {
			$region = $row[0];
			for ($j = 0; $j < 16; $j++)
				$this->regions[$j][$region] = $row[($j + 2)];
		}
	}
	
	function getMapRegion($posX, $posY) {
		return $this->regions[$this->getMapRegionX($posX)][$this->getMapRegionY($posY)];
	}
	
	function getMapRegionX($posX) {
		return ($posX >> 15) + 9;
	}
	
	function getMapRegionY($posY) {
		return ($posY >> 15) + 10;
	}
	
	function get_spawn_town($townId) {
		
		if(CONFIG::g()->service_unstuck_static)
			return CONFIG::g()->service_unstuck_default;
		
		switch($townId) {
			case 0:
				$town_coord = array(-84176, 243382, -3126);		// Talking Island
			break;
			case 1:
				$town_coord = array(45525, 48376, -3059);		// Elven Village
			break;
			case 2:
				$town_coord = array(12181, 16675, -4580);		// DE Village
			break;
			case 3:
				$town_coord = array(-45232, -113603, -224);		// Orc Village
			break;
			case 4:
				$town_coord = array(115074, -178115, -880);		// Dwarven Village
			break;
			case 5:
				$town_coord = array(-14138, 122042, -2988);		// Gludio Castle Town
			break;
			case 6:
				$town_coord = array(-82856, 150901, -3128);		// Gludin Village
			break;
			case 7:
				$town_coord = array(18823, 145048, -3126);		// Dion Castle Town
			break;
			case 8:
				$town_coord = array(81236, 148638, -3469);		// Giran Castle Town
			break;
			case 9:
				$town_coord = array(80853, 54653, -1524);		// Town of Oren
			break;
			case 10:
				$town_coord = array(147391, 25967, -2012);		// Town of Aden
			break;
			case 11:
				$town_coord = array(117163, 76511, -2712);		// Hunter Village
			break;
			case 13:
				$town_coord = array(111381, 219064, -3543);		// Heine
			break;
			case 14:
				$town_coord = array(43894, -48330, -797);		// Rune Castle Town
			break;
			case 15:
				$town_coord = array(148558, -56030, -2781);		// Goddard
			break;
			case 16:
				$town_coord = array(87331, -142842, -1317);		// Schuttgart
			break;
			case 17:
				$town_coord = array(18823, 145048, -3126);		// Floran Village
			break;
			case 18:
				$town_coord = array(10468, -24569, -3645);		// Primeval Isle
			break;
			case 19:
				$town_coord = array(-118092, 46955, 360);		// Kamael Village
			break;
			case 21:
				$town_coord = array(-58752, -56898, -2032);		// Fantasy Isle
			break;
			case 24:
				$town_coord = array(-114462, -249619, -2986);	// GM Consultation service
			break;
			default:
				$town_coord = array(18823, 145048, -3126);		// Floran Village
			break;
		}
		
		return $town_coord;
	}
	
}
?>