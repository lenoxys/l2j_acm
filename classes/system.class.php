<?php

defined( '_ACM_VALID' ) or die( 'Direct Access to this location is not allowed.' );


	
function smarty_block_dynamic($param, $content, &$smarty) {
	return $content;
}

class SmartyObject extends Smarty {
	
	private static $instance;

	private function __construct(){
		$this->template_dir = 'templates/'.CONFIG::g()->core_template;
		$this->compile_dir = 'cache';
		$this->caching = false;
		$this->force_compile = true;
		parent::__construct();
	}
	
	public function __clone() {
		trigger_error('Clone is not allowed.', E_USER_ERROR);
	}

	public static function getInstance() {
		if (!isset(self::$instance)) {
			$c = __CLASS__;
			self::$instance = new $c;
		}
		return self::$instance;
	}
	
	public function setTemplate($template) {
		$this->template = $template;
	}
	
	public function assign($m, $p) {
		parent::assign($m, $p);
	}
	
	public function display() {
		DEBUG::publish($this);
		MSG::display($this);
		parent::display($this->template);
	}
}

class msg {

	private $error_text = null;
	private $valid_text = null;
	
	private static $instance;

	private function __construct() {}
	
	public function __clone() {
		trigger_error('Clone is not allowed.', E_USER_ERROR);
	}

	public static function singleton() {
		if (!isset(self::$instance)) {
			$c = __CLASS__;
			self::$instance = new $c;
		}
		return self::$instance;
	}

	public function add_error($txt) {
		$d = MSG::singleton();
		$d->error_text .= $txt.'<br />'."\n\r";
	}

	public function get_error() {
		$d = MSG::singleton();
		return $d->error_text;
	}

	public function add_valid($txt) {
		$d = MSG::singleton();
		$d->valid_text .= $txt.'<br />'."\n\r";
	}

	public function get_valid() {
		$d = MSG::singleton();
		return $d->valid_text;
	}

	public function display($t) {	
		$d = MSG::singleton();
		if(!empty($d->error_text)) {
			$t->assign('error', $d->error_text);
		}
		if(!empty($d->valid_text)) {
			$t->assign('valid', $d->valid_text);
		}
	}
}


class debug {

	private $debug_text;
	
	private static $instance;

	private function __construct() {
		$this->debug_text = ('ACM VERSION : '.CONFIG::g()->acm_version.'<br />'."\n\r");
	}
	
	public function __clone() {
		trigger_error('Clone is not allowed.', E_USER_ERROR);
	}

	public static function singleton() {
		if (!isset(self::$instance)) {
			$c = __CLASS__;
			self::$instance = new $c;
		}
		return self::$instance;
	}

	public function add($txt, $color = null) {
		$d = DEBUG::singleton();
		$txt = (is_null($color)) ? $txt : '<font style="color:'.$color.';"><b>'.$txt.'</b></font>';
		$d->debug_text .= $txt.'<br />'."\n\r";
	}

	public function publish($t) {
		if(!DEBUG)
			return false;
		
		$d = DEBUG::singleton();
		
		if(!empty($d->debug_text)) {
			echo '<div style="color: #000;background: #FFF;"><h1>DEBUG MODE ON</h1><br />'."\n\r"; 
			echo $d->debug_text."\n\r"; 
			echo '</div>'."\n\r";
		}
	}
}


class logdaemon {

	private $log_text, $path;
	
	private static $instance;

	private function __construct() {
		$this->path = './log/';
	}
	
	public function __clone() {
		trigger_error('Clone is not allowed.', E_USER_ERROR);
	}

	public static function l() {
		if (!isset(self::$instance)) {
			$c = __CLASS__;
			self::$instance = new $c;
		}
		return self::$instance;
	}

	public function add($txt) {
		if(!LOG)
			return false;
		
		$l = LOGDAEMON::l();
		$l->log_text = date("H:i:s") . ' ' . $_SERVER['REMOTE_ADDR'] . ' ' . $txt . "\r\n";
		$l->write();
	}

	private function write() {
		
		$filename = $this->path . date ("Y-m-d") . '.log';
		
		if (!$handle = fopen($filename, 'a')) {
			DEBUG::add('Error when openning log file');
			return false;
		}
		
		if (fwrite($handle, $this->log_text) === FALSE) {
			DEBUG::add('Error when writing in log file');
			return false;
		}
		
		fclose($handle);
		return true;
	}
}

class lang {

	private $defaultLanguage = 'english';
	private $currentLanguage = 'english';
	private $path = './language/';
	private $item = null;
	private $defItem = null;
	
	private static $instance;

	private function __construct() {
		$this->currentLanguage = CONFIG::g()->core_language;
		$this->loadFile();
		$this->nl2();
	}
	
	public function __clone() {
		trigger_error('Clone is not allowed.', E_USER_ERROR);
	}

	public static function getInstance() {
		if (!isset(self::$instance)) {
			$c = __CLASS__;
			self::$instance = new $c;
		}
		return self::$instance;
	}
	
	public function nl2 () {
		$this->item = array_map('nl2br', $this->item);
	}

	private function getCurrentLanguage($currentLanguage) {
		return $this->currentLanguage;
	}
	
	private function loadFile() {
		$this->newFile = $this->path.$this->currentLanguage.'.php';
		require ($this->newFile);
		$this->item = $vm;
	}
	
	private function loadDefaultFile() {
		if(is_null($this->defItem)) {
			$this->newFile = $this->path.$this->defaultLanguage.'.php';
			require ($this->newFile);
			$this->defItem = $vm;
		}
	}
	
	private function itemExist($itemName) {
		if(!empty($this->item[$itemName])) {
			return true;
		}else{
			$this->loadDefaultFile();
			return false;
		}
	}

	private function getItemValue($itemName) {
		if($this->itemExist($itemName)) {
			return $this->item[$itemName];
		}else{
			return $this->defItem[$itemName];
		}
	}

	public function i18n($itemName) {
		$l = LANG::getInstance();
		return $l->getItemValue($itemName);
	}
}

?>