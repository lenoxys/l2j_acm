<?php

defined( '_ACM_VALID' ) or die( 'Direct Access to this location is not allowed.' );

	
function smarty_block_dynamic($param, $content, &$smarty) {
	return $content;
}
	
class Smarty2 extends Smarty {

	function Smarty2(){
		global $template;
		$this->Smarty();
		$this->template_dir = 'templates/'.$template;
		$this->compile_dir = 'cache';
		$this->caching = true;
		$this->force_compile = true;
	}
	
	function display($t) {
		global $error;
		DEBUG::add($error);
		DEBUG::publish();
		parent::display($t);
	}
}

class error {

	private $error_text;
	
	private static $instance;

	private function __construct() {
		
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

	public function add($txt) {
		$d = DEBUG::singleton();
		$d->error_text .= $txt.'<br />'."\n\r";
	}

	public function publish() {
		if(!DEBUG)
			return false;
		
		$d = DEBUG::singleton();
		
		return $d->error_text."\n\r";
	}
}


class debug {

	private $debug_text;
	
	private static $instance;

	private function __construct() {
		
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

	public function publish() {
		if(!DEBUG)
			return false;
		
		$d = DEBUG::singleton();
		
		echo '<div style="color: #000;background: #FFF;"><h1>DEBUG MODE ON</h1><br />'."\n\r";
		echo $d->debug_text."\n\r";
		echo '</div>'."\n\r";
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

?>