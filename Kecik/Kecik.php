<?php
/**
 * Kecik Framework - Sebuah Framework dengan satu file system
 *
 * @author 		Dony Wahyu Isp
 * @copyright 	2015 Dony Wahyu Isp
 * @link 		http://github.io/kecik
 * @license		GPL
 * @version 	1.0.0alpha
 * @package		Kecik
 *
 *----------------------------------------
 * INDEX CODE
 *----------------------------------------
 * Keterangan								Baris Code
 * + Controller Class ......................... 47
 *   - Custom Constructor ..................... 53
 *   - Custom Fungsi .......................... 59
 * + Model Class .............................. 81
 * 	 - Custom Code save ....................... 108
 *   - Custom Code  delete .................... 130
 * 	 - Custom Fungsi Model .................... 139
 * 	 - Custom Code Inisialisasi Model ......... 168
 * + Config Class ............................. 212
 * + AssetsBase Class ......................... 271
 * + Assets Class ............................. 361
 * + Url Class ................................ 401
 * + Route Class .............................. 472
 * + Input Class .............................. 673
 * + Kecik Class .............................. 723
 * + Manual ................................... 885
 **/

namespace Kecik;

/**
 * Autoload untuk composer
 **/
//require 'vendor/autoload.php';

/**
 * Controller
 * @package 	Kecik
 * @author 		Dony Wahyu Isp
 * @since 		1.0.0alpha
 **/
class Controller {

	/**
	 * Construtor Controller
	 **/
	public function __construct() {
		//Silakan tambah inisialisasi controller sendiri disini

		//-- Akhir tambah inisialisasi sendiri
	}

	//Silakan tambah fungsi controller sendiri disini


	//-- Akhir tambah fungsi sendiri

	/**
	 * view
	 * Funngsi untuk menampilkan view
	 * @param string $file
	 * @param array $param
	 **/
	protected function view($file, $param) {
		extract($param);
		include Config::get('path.app').'/views/'.$file.'.php';
	}
}

/**
 * Model
 * @package 	Kecik
 * @author 		Dony Wahyu Isp
 * @since 		1.0.0alpha
 **/
class Model {
	protected $_field = array();
	protected $_where;
	protected $add = TRUE;
	protected $table = '';
	protected $fields = array();
	protected $values = array();
	protected $updateVar = array();

	/**
	 * save
	 * Fungsi untuk menambah atau mengupdate record (Insert/Update)
	 * @return string SQL Query
	 **/
	public function save() {
		$this->setFieldsValues();

		if ($this->table != '') {
			// Untuk menambah record
			if ($this->add == TRUE) {
				$sql ="INSERT INTO $this->table ($this->fields) VALUES ($this->values)";
			// Untuk mengupdate record
			} else {
				$sql ="UPDATE $this->table SET $this->updateVar $this->_where";
			}

			//silakan tambah code database sendiri disini


			//-- Akhir tambah code database sendiri
		}

		return (isset($sql))?$sql:'';
	}

	/**
	 * delete
	 * Fungsi untuk menghapus record
	 * @return string SQL Query
	 **/
	public function delete() {
		$this->setFieldsValues();

		if ($this->table != '') {
			if ($this->_where != '') {
				$sql = "DELETE FROM $this->table $this->_where";
			}

			//silakan tambah code database sendiri disini


			//-- AKhir tambah code database sendiri
		}

		return (isset($sql))?$sql:'';
	}

	//Silakan tambah fungsi model sendiri disini


	//-- Akhir tambah fungsi sendiri


	/**
	 * Model Constructor
	 * @param mixed $id
	 **/
	public function __construct($id='') {
		$this->_where = '';
		if ($id != '') {
			if (is_array($id)) {
				$and = array();
				while(list($field, $value) = each($id)) {

					if (preg_match('/<|>|!=/', $value))
						$and[] = "`$field`$value";
					else
						$and[] = "`$field`='$value'";
				}
				$this->_where .= implode(' AND ', $and);
			} else {
				$this->_where .= "`id`='".$id."'";
			}

			$this->add = FALSE;

			//Silakan tambah inisialisasi model sendiri disini

			//-- Akhir tambah inisialisasi model sendiri
		}
	}

	/**
	 * setFieldValues
	 * Fungsi untuk menyetting Variable Fields dan Values
	 **/
	private function setFieldsValues() {
		$fields = array_keys($this->_field);
		while(list($id, $field) = each($fields))
			$fields[$id] = "`$fields[$id]`";
		
		$this->fields = implode(',', $fields);

		$values = array_values($this->_field);
		$updateVar = array();
		while (list($id, $value) = each($values)){
			$values[$id] = "'$values[$id]'";
			$updateVar[] = "$fields[$id] = '$values[$id]'";
		}
		$this->values = implode(',', $values);
		$this->updateVar = implode(',', $updateVar);

		$this->_where = ($this->_where != '')?' WHERE '.$this->_where:'';
	}

	public function __set($var, $value) {
		$this->_field[$var] = addslashes($value);
	}

	public function __get($var) {
		return stripslashes($this->_field[$var]);
	}
} 


/**
 * Config
 * @package 	Kecik
 * @author 		Dony Wahyu Isp
 * @since 		1.0.0alpha
 **/
class Config {
	/**
	 * @var array 
	 **/
	private static $config;

	/**
	 * init
	 **/
	public static function init() {
		self::$config = array(
			'path.assets' => '',
			'path.templates' => '',

		);
	}

	/**
	 * set
	 * @param string $key
	 * @param string $value
	 **/
	public static function set($key, $value) {
		self::$config[strtolower($key)] = $value;
	}

	/**
	 * get
	 * @param 	string $key
	 * @return 	string nilai dari key config
	 **/
	public static function get($key) {
		if (isset(self::$config[strtolower($key)]))
			return self::$config[strtolower($key)];
		else
			return '';
	}

	/**
	 * delete
	 * @param string $key
	 **/
	public static function delete($key) {
		if (isset(self::$config[strtolower($key)]))
			unset(self::$config[strtolower($key)]);
	}
}

Config::init();
//--


/**
 * AssetsBase
 * 
 * @package 	Kecik
 * @author 		Dony Wahyu Isp
 * @since 		1.0.0alpha
 **/
class AssetsBase {
	/**
	 * @var array
	 **/
	var $assets;
	
	/**
	 * @var string
	 **/
	var $type;

	/**
	 * @var object of Url
	 **/
	var $baseurl;

	/**
	 * __construct
	 * @param object url
	 * @param string type
	 **/
	public function __construct($baseUrl, $type) {
		$this->baseurl = $baseUrl;
		$this->type = strtolower($type);
		$this->assets[$type] = array();
	}

	/**
	 * add
	 * @param string $file
	 **/
	public function add($file) {
		if (!in_array($file, $this->assets[$this->type]))
			$this->assets[$this->type][] = $file;
	}

	/**
	 * delete
	 * @param $file
	 **/
	public function delete($file) {
		$key = array_search($file, $this->assets[$this->type]);
		unset($this->assets[$this->type][$key]);
	}

	/**
	 * render
	 * @param string $file optional
	 **/
	public function render($file='') {
		reset($this->assets[$this->type]);
		
		if ($this->type == 'js') {
			if ($file != '') {
				$key = array_search($file, $this->assets[$this->type]);
				if ($key)
					return '<script type="text/javascript" src="'.$this->baseurl.Config::get('path.assets')."/$this->type/".$this->assets[$this->type][$key].'.'.$this->type.'"></script>'."\n";
			} else {
				$render = '';
				while(list($key, $value) = each($this->assets[$this->type])) {
					$render .= '<script type="text/javascript" src="'.$this->baseurl.Config::get('path.assets')."/$this->type/".$value.'.'.$this->type.'"></script>'."\n";
				}

				return $render;
			}

		} elseif ($this->type == 'css') {
			if ($file != '') {
				$key = array_search($file, $this->assets[$this->type]);
				if ($key)
					return '<link rel="stylesheet" href="'.$this->baseurl.Config::get('path.assets')."/$this->type/".$this->assets[$this->type][$key].'.'.$this->type.'" />'."\n";
			} else {
				$render = '';
				while(list($key, $value) = each($this->assets[$this->type])) {
					$render .= '<link rel="stylesheet" href="'.$this->baseurl.Config::get('path.assets')."/$this->type/".$value.'.'.$this->type.'" />'."\n";
				}

				return $render;
			}
		}
	} 
}

/**
 * Assets
 * 
 * @package 	Kecik
 * @author 		Dony Wahyu Isp
 * @since 		1.0.0alpha
 **/
class Assets {
	/**
	 * @var object $css, $js
	 **/
	var $css, $js;

	/**
	 * @var object $url
	 **/
	var $baseUrl;

	/**
	 * __contruct
	 * @param object $url
	 **/
	public function __construct($url) {
		$this->baseUrl = $url->baseUrl();
		$this->css = new AssetsBase($this->baseUrl, 'css');
		$this->js = new AssetsBase($this->baseUrl, 'js'); 
	}

	/**
	 * images
	 * @param string $file
	 * @return string
	 **/
	public function images($file) {
		return $this->baseUrl.Config::get('path.assets').'/images/'.$file;
	}
}

//--


/**
 * Url
 * @package Kecik
 * @author Dony Wahyu Isp
 * @since 1.0.0alpha
 **/
class Url {
	/**
	 * @var string $_protocol, $_base_url, $_base_path
	 **/
	private $_protocol, $_base_url, $_base_path;

	/**
	 * @var object $_route
	 **/
	private $_route;
	private $_app;
	
	/**
	 * __construct
	 **/
	public function __construct($protocol, $baseUrl, $basePath) {
		$this->_protocol = $protocol;
		$this->_base_url = $baseUrl;
		$this->_base_path = $basePath;
	}

	/**
	 * protocol
	 * @return string;
	 **/
	public function protocol() {
		return $this->_protocol;
	}

	/**
	 * basePath
	 * @return string
	 **/
	public function basePath() {
		return $this->_base_path;
	}

	/**
	 * baseUrl
	 * @return string
	 **/
	public function baseUrl() {
		return $this->_base_url;
	}

	/**
	 * redirect
	 * @param string link
	 **/
	public function redirect($link) {
		header('Location: '.$link);
	}

	/**
	 * linkto
	 * @param string $link
	 * @return string
	 **/
	public function linkto($link) {
		return $this->_base_url.$link;
	}
}
//--

/**
 * Route
 * 
 * @package 	Kecik
 * @author 		Dony Wahyu Isp
 * @since 		1.0.0alpha
 **/
class Route {
	/**
	 * @var array $_params for lock params
	 **/
	public static $_params = array();

	/**
	 * @var array $_realparams for callable
	 **/
	public static $_realparams = array();
	
	/**
	 * @var string $BASEURL
	 **/
	public static $BASEURL;
	
	/**
	 * @var string $BASEPATH
	 **/
	public static $BASEPATH;

	/**
	 * @var string PROTOCOL
	 **/
	public static $PROTOCOL;

	public static $HTTP_RESPONSE = array(
        //Informational 1xx
        100 => '100 Continue',
        101 => '101 Switching Protocols',
        //Successful 2xx
        200 => '200 OK',
        201 => '201 Created',
        202 => '202 Accepted',
        203 => '203 Non-Authoritative Information',
        204 => '204 No Content',
        205 => '205 Reset Content',
        206 => '206 Partial Content',
        226 => '226 IM Used',
        //Redirection 3xx
        300 => '300 Multiple Choices',
        301 => '301 Moved Permanently',
        302 => '302 Found',
        303 => '303 See Other',
        304 => '304 Not Modified',
        305 => '305 Use Proxy',
        306 => '306 (Unused)',
        307 => '307 Temporary Redirect',
        //Client Error 4xx
        400 => '400 Bad Request',
        401 => '401 Unauthorized',
        402 => '402 Payment Required',
        403 => '403 Forbidden',
        404 => '404 Not Found',
        405 => '405 Method Not Allowed',
        406 => '406 Not Acceptable',
        407 => '407 Proxy Authentication Required',
        408 => '408 Request Timeout',
        409 => '409 Conflict',
        410 => '410 Gone',
        411 => '411 Length Required',
        412 => '412 Precondition Failed',
        413 => '413 Request Entity Too Large',
        414 => '414 Request-URI Too Long',
        415 => '415 Unsupported Media Type',
        416 => '416 Requested Range Not Satisfiable',
        417 => '417 Expectation Failed',
        418 => '418 I\'m a teapot',
        422 => '422 Unprocessable Entity',
        423 => '423 Locked',
        426 => '426 Upgrade Required',
        428 => '428 Precondition Required',
        429 => '429 Too Many Requests',
        431 => '431 Request Header Fields Too Large',
        //Server Error 5xx
        500 => '500 Internal Server Error',
        501 => '501 Not Implemented',
        502 => '502 Bad Gateway',
        503 => '503 Service Unavailable',
        504 => '504 Gateway Timeout',
        505 => '505 HTTP Version Not Supported',
        506 => '506 Variant Also Negotiates',
        510 => '510 Not Extended',
        511 => '511 Network Authentication Required'
    );

	public function __construct() {
		
	}

	/**
	 * Init
	 * For init class Route
	 **/
	public static function init() {

		if (php_sapi_name() == 'cli') 
		    self::$BASEPATH = str_replace('/', DIRECTORY_SEPARATOR, dirname(__FILE__).'/');
		else
		    self::$BASEPATH  = str_replace('/', DIRECTORY_SEPARATOR, realpath( dirname( __FILE__ ) )."/");

		if ( isset($_SERVER['HTTPS']) )
		    self::$PROTOCOL = "https://";
		else
		    self::$PROTOCOL = "http://";

		$pathinfo = pathinfo($_SERVER['PHP_SELF']);

		if ( strpos($pathinfo['dirname'], '/index.php') > 0 )
			$strlimit = strpos($pathinfo['dirname'], '/index.php');
		else
			$strlimit = strlen($pathinfo['dirname']);

		if (php_sapi_name() == 'cli-server') 
		    self::$BASEURL = self::$PROTOCOL.$_SERVER['HTTP_HOST'].'/';
		else if (php_sapi_name() == 'cli') {
			self::$_params= $_SERVER['argv'];
		    chdir(dirname(__FILE__));
		    self::$BASEURL = dirname(__FILE__).'\\';
		} else {
		    self::$BASEURL = self::$PROTOCOL.$_SERVER['HTTP_HOST'].substr( $pathinfo['dirname'], 0, $strlimit )."/";

		    $path = str_replace( self::$PROTOCOL.$_SERVER['HTTP_HOST'].'/', '', self::$BASEURL );
            
            $path = str_replace($path, '', $_SERVER['REQUEST_URI']);
            if (substr($path, 0, 1) == '/' ) $path=substr($path, 1);
            
            $segments = explode('/', $path);
            
            if ( $segments[count($segments)-1] == '' && count($segments) > 1 ) unset($segments[count($segments)-1]);
             
            $result_segment = array();
            while(list($key, $seg) = each($segments)) {
                if ($segments[$key] != 'index.php' && $seg != '' )
                    array_push($result_segment, urldecode($seg));
            }

            self::$_params = $result_segment;
            self::$_realparams = self::$_params; 
		}

		unset($strlimit);
		unset($pathinfo);

	}

	/**
	 * _getParams
	 * @param integer $key
	 * @return mixed
	 **/
	public function _getParams($key=-1) {
		if ($key >= 0) {
			if (isset(self::$_params[$key]))
				return self::$_params[$key];
			else
				return NULL;
		} else
			return self::$_params;
	}

	/**
	 * getParams
	 * @param integer
	 * @return mixed
	 **/
	public function getParams($key=-1) {
		if ($key >= 0) {
			if (isset(self::$_realparams[$key]))
				return self::$_realparams[$key];
			else
				return NULL;
		} else
			return self::$_realparams;
	}

	/**
	 * setParams
	 * @param string $key
	 * @param string $value
	 **/
	public function setParams($key, $value='') {
		//if (!isset($this->_params)) $this->_params = array();

		if (is_array($key)) {
			self::$_realparams = $key;
		} else
			self::$_realparams[$key] = $value;
	}
}

Route::init();
//--

/**
 * Input
 * 
 * @package 	Kecik
 * @author 		Dony Wahyu Isp
 * @since 		1.0.0alpha
 **/
class Input {
	public function __construct() {

	}

	/**
	 * get
	 * @param string $var
	 * @return mixed
	 **/
	public function get($var='') {
		if ($var == '')
			return $_GET;
		else
			return (isset($_GET[$var]))? $_GET[$var] : NULL;
	}

	/**
	 * post
	 * @param string $var
	 * @return mixed
	 **/
	public function post($var='') {
		if ($var == '')
			return $_POST;
		else
			return (isset($_POST[$var]))? $_POST[$var] : NULL;
	}

	/**
	 * server
	 * @param string $var
	 * @return mixed
	 **/
	public function server($var='') {
		if ($var == '')
			return $_SERVER;
		else
			return (isset($_SERVER[$var]))? $_SERVER[$var] : NULL;
	}
}


/**
 * Kecik
 * 
 * @package 	Kecik
 * @author 		Dony Wahyu Isp
 * @since 		1.0.0alpha
 **/
class Kecik {
	/**
	 * @var object $route, $url, $config, $assets
	 **/
	var $route, $url, $config, $assets, $input;

	/**
	 * @var Closure Object $callable
	 **/
	var $callable;

	private static $fullrender = '';

	public function autoload($class) {
		$class_array = explode('\\', $class);
		include $this->config->get('path.app').'/'.strtolower($class_array[0]).'s/'.$class_array[1].'.php';
	}

	/**
	 * __construct
	 * @param array $config optional
	 **/
	public function __construct($config=array()) {
		$this->route = new Route();
		$this->url = new Url(Route::$PROTOCOL, Route::$BASEURL, Route::$BASEPATH);
		$this->config = new Config();
		$this->assets = new Assets($this->url);
		$this->input = new Input();

		spl_autoload_register(array($this, 'autoload'), true, true);

	}

	private function setCallable($args) {
		$route = array_shift($args);
		$real_params = array();

		if (!is_callable($args[0])) {
			$controller = array_shift($args);
			$real_params['controller'] = $controller;
		}

		if ($route == '/' && count( $this->route->_getParams() ) <= 0 ) {
			$this->callable = array_pop($args);
		} else {
			$p = explode('/', $route);

			while(list($key, $value) = each($p)) {
				
				if (substr(trim($value, '/'), 0, 1) == ':') {
					$real_params[$value] = $this->route->_getParams($key);
				} elseif (substr(trim($value, '/'), 0, 2) == '(:' && substr(trim($value, '/'), -1, 1) == ')') {
					if ($this->route->_getParams($key) != NULL)
						$real_params[$value] = $this->route->_getParams($key);
				} else {
					if ( in_array($value, $this->route->_getParams()) ) {
						$this->callable = array_pop($args);
					}
				}
			}
		}

		$this->route->setParams($real_params);

	}

	/**
	 * get
	 * @param multi parameters
	 **/
	public function get() {

		if (is_callable($this->callable) ) {
			return $this;
		}


		self::$fullrender = '';
		$args = func_get_args();

		$this->setCallable($args);
		return $this;
	}

	/**
	 * post
	 * @param multi paramaters
	 **/
	public function post() {
		if (is_callable($this->callable) ) {
			return $this;
		}

		self::$fullrender = '';
		$args = func_get_args();

		$this->setCallable($args);
		return $this;
	}

	public function template($template) {
		$tpl = file_get_contents($this->config->get('path.template').'/'.$template.'.php');
		self::$fullrender = str_replace(array('{{', '}}'), array('<?php', '?>'), $tpl);
		self::$fullrender = str_replace(array('@controller'), array('<?php call_user_func_array($this->callable, $this->route->getParams()) ?>'), self::$fullrender);
	}

	/**
	 * run
	 **/
	public function run() {
		
		if (self::$fullrender != '') {
			if (is_callable($this->callable)) {
				eval('?> '.self::$fullrender);
			} else {
				header($_SERVER["SERVER_PROTOCOL"].Route::$HTTP_RESPONSE[404]);
				if ($this->config->get('error.404') != '') {
					include($this->config->get('path.template').'/'.$this->config->get('error.404').'.php');
				} else
					die(Route::$HTTP_RESPONSE[404]); 
			}
			self::$fullrender = '';
		} else {
			if (is_callable($this->callable)) {
				call_user_func_array($this->callable, $this->route->getParams());
			} else {
				header($_SERVER["SERVER_PROTOCOL"].Route::$HTTP_RESPONSE[404]);
				if ($this->config->get('error.404') != '') {
					include($this->config->get('path.template').'/'.$this->config->get('error.404').'.php');
				} else
					die(Route::$HTTP_RESPONSE[404]); 
			}
		}
	}

	public function error($code) {
		header($_SERVER["SERVER_PROTOCOL"].Route::$HTTP_RESPONSE[$code]);
		if ($this->config->get("error.$code") != '') {
			include($this->config->get('path.template').'/'.$this->config->get("error.$code").'.php');
		} else
			die(Route::$HTTP_RESPONSE[$code]); 
	}

	/**
	 * stop
	 **/
	public function stop() {
		exit();
	}
}











global $manual;
if (!isset($manual)) {?>
	<html>
<head>
	<title>Cara Menggunakan Framework Kecik</title>

	<style type="text/css">
		h1,h2,h3,h4,hr{padding:0; margin:0;}
		section.step{margin-bottom: 50px}
		section#footer{font-weight: bold; font-size: 13px;}
		table{border: 0; margin: 0px; padding: 0px;}
		table.copy{font-weight: bold;}
		table.copy td.field{font-size: 15px; width:30px;}
		table.copy td.value{font-size: 18px;}
	</style>
</head>
<body>
	<h1>CARA MENGGUNAKAN FRAMEWORK KECIK</h1>
	<hr />
	<section id="description">
		<h2>Framework Kecik</h2>
		<p align="justify">
			Merupakan framework dengan satu file system yang sangat sederhana, jadi ini bukan merupakan sebuah framework yang 
			kompleks, tapi anda dapat membangun dan mengembangkan framework ini untuk menjadi sebuah framework yang kompleks.
			Framework ini mendukung MVC sederhana dimana anda masih harus mengcustom beberapa code untuk mendapatkan MVC yang
			kompleks, untuk Model hanya sebatas men-generate perintah SQL untuk INSERT, UPDATE dan DELETE saja, jadi untuk 
			code pengeksekusian SQL nya tersebut silakan dibuat sendiri dengan bebas mau menggunakan library database manapun.
			Framework ini juga mendukung Composer, jadi bisa memudahkan anda untuk menambahkan sebuah library dari composer.
		</p>
		<table class="copy">
			<tr>
				<td class="field">Nama</td> 
				<td class="value">: Framework Kecik</td>
			</tr>
			<tr>
				<td class="field">Pembuat</td>
				<td class="value">: Dony Wahyu Isp</td>
			</tr>
			<tr>
				<td class="field">Versi</td> 
				<td class="value">: 1.0.0alpha</td>
			<tr>
				<td class="field">Kota</td> 
				<td class="value">: Palembang</td>
			</tr>
		</table>
	</section>

	<br />
	<br />
	<hr />

	<section id="step1" class="step">
		<h3>Langkah Pertama:</h3>
		<p align="justify">
			Install composer pada sistem operasi anda, jika belum terinstall anda dapat mendownload melalui link
			<a href="https://getcomposer.org">Composer</a>, setelah melakukan download dan installasi, selanjutnya anda perlu
			membuat file <strong>composer.json</strong> dengan isi file berikut ini.
			<pre>
				<span style='color:#800080; '>{</span>
				    <span style='color:#800000; '>"</span><span style='color:#0000e6; '>require</span><span style='color:#800000; '>"</span><span style='color:#800080; '>:</span> <span style='color:#800080; '>{</span>
				        <span style='color:#800000; '>"</span><span style='color:#0000e6; '>monolog/monolog</span><span style='color:#800000; '>"</span><span style='color:#800080; '>:</span> <span style='color:#800000; '>"</span><span style='color:#0000e6; '>1.2.*</span><span style='color:#800000; '>"</span>
				    <span style='color:#800080; '>}</span>
				<span style='color:#800080; '>}</span>
			</pre>
			Selanjutnya jalankan perintah berikut ini pada console/cmd
			<pre>
				composer <span style='color:#800000; font-weight:bold; '>install</span>
			</pre>
			Tunggu beberapa menit hingga semua berjalan tanpa error.
		</p>
		<p>
			Untuk cara penggunaan composer tidak akan dibahas disini, anda dapat mempelajarinya dari dokumentasi yang disedia
			di website composer, baik secara online maupun offline.
		</p>
	</section>

	<section id="step2" class="step">
		<h3>Langkah Kedua:</h3>
		<p align="justify">
			Buatlah sebuah file index.php atau apapun dengan tuliskan code dibawah ini:
			<pre>
				<span style='color:#5f5035; background:#ffffe8; '>&lt;?php</span><span style='color:#000000; background:#ffffe8; '></span>
				<span style='color:#797997; background:#ffffe8; '>$manual</span><span style='color:#000000; background:#ffffe8; '> </span><span style='color:#808030; background:#ffffe8; '>=</span><span style='color:#000000; background:#ffffe8; '> FALSE</span><span style='color:#800080; background:#ffffe8; '>;</span><span style='color:#000000; background:#ffffe8; '></span>
				<span style='color:#800000; background:#ffffe8; font-weight:bold; '>require</span><span style='color:#000000; background:#ffffe8; '> </span><span style='color:#0000e6; background:#ffffe8; '>"system.php"</span><span style='color:#800080; background:#ffffe8; '>;</span><span style='color:#000000; background:#ffffe8; '></span>
			</pre>
			Variabel $manual di set FALSE agar tidak menampilkan cara pemakaian pada project yang kita buat.<br />
			Required "system.php" untuk memasukan file system framework ke project yang ingin kita buat. <br />
			Lalu coba jalankan, jika hanya menampilkan halaman kosong tanpa pesan error berarti sudah berhasil.
		</p>

	</section>

	<section id="step3" class="step">
		<h3>Langkah Ketiga:</h3>
		<p align="justify">
			Buat variabel dari Class Framework Kecik seperti dibawah ini
			<pre>
				<span style='color:#797997; background:#ffffe8; '>$app</span><span style='color:#000000; background:#ffffe8; '> </span><span style='color:#808030; background:#ffffe8; '>=</span><span style='color:#000000; background:#ffffe8; '> </span><span style='color:#800000; background:#ffffe8; font-weight:bold; '>new</span><span style='color:#000000; background:#ffffe8; '> Kecik\Kecik</span><span style='color:#808030; background:#ffffe8; '>(</span><span style='color:#808030; background:#ffffe8; '>)</span><span style='color:#800080; background:#ffffe8; '>;</span><span style='color:#000000; background:#ffffe8; '></span>
			</pre>
			Lalu coba jalankan kembali, jika tidak terdapat error berarti anda sudah sukses sampai tahap ini.
		</p>
	</section>

	<section id="step4" class="step">
		<h3>Langkah Keempat:</h3>
		<p align="justify">
			Langkah selanjutnya adalah membuat Route untuk index dan menjalankan framework, berikut code nya:
			<pre>
				<span style='color:#797997; background:#ffffe8; '>$app</span><span style='color:#808030; background:#ffffe8; '>-</span><span style='color:#808030; background:#ffffe8; '>></span><span style='color:#000000; background:#ffffe8; '>get</span><span style='color:#808030; background:#ffffe8; '>(</span><span style='color:#0000e6; background:#ffffe8; '>'/'</span><span style='color:#808030; background:#ffffe8; '>,</span><span style='color:#000000; background:#ffffe8; '> </span><span style='color:#800000; background:#ffffe8; font-weight:bold; '>function</span><span style='color:#808030; background:#ffffe8; '>(</span><span style='color:#808030; background:#ffffe8; '>)</span><span style='color:#000000; background:#ffffe8; '> </span><span style='color:#800080; background:#ffffe8; '>{</span><span style='color:#000000; background:#ffffe8; '></span>
				<span style='color:#000000; background:#ffffe8; '>	</span><span style='color:#800000; background:#ffffe8; font-weight:bold; '>echo</span><span style='color:#000000; background:#ffffe8; '> </span><span style='color:#0000e6; background:#ffffe8; '>'Hello Kecik'</span><span style='color:#800080; background:#ffffe8; '>;</span><span style='color:#000000; background:#ffffe8; '></span>
				<span style='color:#800080; background:#ffffe8; '>}</span><span style='color:#808030; background:#ffffe8; '>)</span><span style='color:#800080; background:#ffffe8; '>;</span><span style='color:#000000; background:#ffffe8; '></span>
				<span style='color:#000000; background:#ffffe8; '></span>
				<span style='color:#797997; background:#ffffe8; '>$app</span><span style='color:#808030; background:#ffffe8; '>-</span><span style='color:#808030; background:#ffffe8; '>></span><span style='color:#000000; background:#ffffe8; '>run</span><span style='color:#808030; background:#ffffe8; '>(</span><span style='color:#808030; background:#ffffe8; '>)</span><span style='color:#800080; background:#ffffe8; '>;</span><span style='color:#000000; background:#ffffe8; '></span>
			</pre>
			Setelah code ditulis coba jalankan, maka akan tampil tulisan <strong>"Hello Kecik"</strong> itu berarti anda telah berhasil
			membuat tampilan untuk route index/halaman utama project anda.
		</p>
		<p align="justify">
			Tampilan kesuluruhan code:
			<pre>
				<span style='color:#5f5035; background:#ffffe8; '>&lt;?php</span><span style='color:#000000; background:#ffffe8; '></span>
				<span style='color:#797997; background:#ffffe8; '>$manual</span><span style='color:#000000; background:#ffffe8; '> </span><span style='color:#808030; background:#ffffe8; '>=</span><span style='color:#000000; background:#ffffe8; '> FALSE</span><span style='color:#800080; background:#ffffe8; '>;</span><span style='color:#000000; background:#ffffe8; '></span>
				<span style='color:#800000; background:#ffffe8; font-weight:bold; '>require</span><span style='color:#000000; background:#ffffe8; '> </span><span style='color:#0000e6; background:#ffffe8; '>"system.php"</span><span style='color:#800080; background:#ffffe8; '>;</span><span style='color:#000000; background:#ffffe8; '></span>
				<span style='color:#000000; background:#ffffe8; '></span>
				<span style='color:#797997; background:#ffffe8; '>$app</span><span style='color:#000000; background:#ffffe8; '> </span><span style='color:#808030; background:#ffffe8; '>=</span><span style='color:#000000; background:#ffffe8; '> </span><span style='color:#800000; background:#ffffe8; font-weight:bold; '>new</span><span style='color:#000000; background:#ffffe8; '> Kecik\Kecik</span><span style='color:#808030; background:#ffffe8; '>(</span><span style='color:#808030; background:#ffffe8; '>)</span><span style='color:#800080; background:#ffffe8; '>;</span><span style='color:#000000; background:#ffffe8; '></span>
				<span style='color:#000000; background:#ffffe8; '></span>
				<span style='color:#797997; background:#ffffe8; '>$app</span><span style='color:#808030; background:#ffffe8; '>-</span><span style='color:#808030; background:#ffffe8; '>></span><span style='color:#000000; background:#ffffe8; '>get</span><span style='color:#808030; background:#ffffe8; '>(</span><span style='color:#0000e6; background:#ffffe8; '>'/'</span><span style='color:#808030; background:#ffffe8; '>,</span><span style='color:#000000; background:#ffffe8; '> </span><span style='color:#800000; background:#ffffe8; font-weight:bold; '>function</span><span style='color:#808030; background:#ffffe8; '>(</span><span style='color:#808030; background:#ffffe8; '>)</span><span style='color:#000000; background:#ffffe8; '> </span><span style='color:#800080; background:#ffffe8; '>{</span><span style='color:#000000; background:#ffffe8; '></span>
				<span style='color:#000000; background:#ffffe8; '>	</span><span style='color:#800000; background:#ffffe8; font-weight:bold; '>echo</span><span style='color:#000000; background:#ffffe8; '> </span><span style='color:#0000e6; background:#ffffe8; '>'Index'</span><span style='color:#800080; background:#ffffe8; '>;</span><span style='color:#000000; background:#ffffe8; '></span>
				<span style='color:#800080; background:#ffffe8; '>}</span><span style='color:#808030; background:#ffffe8; '>)</span><span style='color:#800080; background:#ffffe8; '>;</span><span style='color:#000000; background:#ffffe8; '></span>
				<span style='color:#000000; background:#ffffe8; '></span>
				<span style='color:#797997; background:#ffffe8; '>$app</span><span style='color:#808030; background:#ffffe8; '>-</span><span style='color:#808030; background:#ffffe8; '>></span><span style='color:#000000; background:#ffffe8; '>run</span><span style='color:#808030; background:#ffffe8; '>(</span><span style='color:#808030; background:#ffffe8; '>)</span><span style='color:#800080; background:#ffffe8; '>;</span><span style='color:#000000; background:#ffffe8; '></span>
			</pre>
		</p>
	</section>
	<hr />
	<section id="dep1" class="step">
		<h2>Mengenal Lebih Dalam Lagi Framework Kecik</h2>
		<section>
			<h3>* Route</h3>
			<p align="justify">
				Route yang terdapat pada framework kecik saat ini adalah get dan post, tapi untuk sementara ini belum memiliki
				perbedaan, untuk penggunaannya terdapat beberapa, dan paling sederhana adalah tanpa menggunakan Controller, variabel
				eksternal dan template, seperti berikut ini:
			<pre>
				<span style='color:#797997; background:#ffffe8; '>$app</span><span style='color:#808030; background:#ffffe8; '>-</span><span style='color:#808030; background:#ffffe8; '>></span><span style='color:#000000; background:#ffffe8; '>get</span><span style='color:#808030; background:#ffffe8; '>(</span><span style='color:#0000e6; background:#ffffe8; '>'/'</span><span style='color:#808030; background:#ffffe8; '>,</span><span style='color:#000000; background:#ffffe8; '> </span><span style='color:#800000; background:#ffffe8; font-weight:bold; '>function</span><span style='color:#808030; background:#ffffe8; '>(</span><span style='color:#808030; background:#ffffe8; '>)</span><span style='color:#000000; background:#ffffe8; '> </span><span style='color:#800080; background:#ffffe8; '>{</span><span style='color:#000000; background:#ffffe8; '></span>
				<span style='color:#000000; background:#ffffe8; '>	</span><span style='color:#800000; background:#ffffe8; font-weight:bold; '>echo</span><span style='color:#000000; background:#ffffe8; '> </span><span style='color:#0000e6; background:#ffffe8; '>'Hello Kecik'</span><span style='color:#800080; background:#ffffe8; '>;</span><span style='color:#000000; background:#ffffe8; '></span>
				<span style='color:#800080; background:#ffffe8; '>}</span><span style='color:#808030; background:#ffffe8; '>)</span><span style='color:#800080; background:#ffffe8; '>;</span><span style='color:#000000; background:#ffffe8; '></span>
			</pre>

				Dengan menggunakan parameter:
			<pre>
				<span style='color:#797997; background:#ffffe8; '>$app</span><span style='color:#808030; background:#ffffe8; '>-</span><span style='color:#808030; background:#ffffe8; '>></span><span style='color:#000000; background:#ffffe8; '>get</span><span style='color:#808030; background:#ffffe8; '>(</span><span style='color:#0000e6; background:#ffffe8; '>'hello/:nama'</span><span style='color:#808030; background:#ffffe8; '>,</span><span style='color:#000000; background:#ffffe8; '> </span><span style='color:#800000; background:#ffffe8; font-weight:bold; '>function</span><span style='color:#000000; background:#ffffe8; '> </span><span style='color:#808030; background:#ffffe8; '>(</span><span style='color:#797997; background:#ffffe8; '>$nama</span><span style='color:#808030; background:#ffffe8; '>)</span><span style='color:#000000; background:#ffffe8; '> </span><span style='color:#800080; background:#ffffe8; '>{</span><span style='color:#000000; background:#ffffe8; '></span>
				<span style='color:#000000; background:#ffffe8; '>	</span><span style='color:#800000; background:#ffffe8; font-weight:bold; '>echo</span><span style='color:#000000; background:#ffffe8; '> </span><span style='color:#0000e6; background:#ffffe8; '>'Hello '</span><span style='color:#808030; background:#ffffe8; '>.</span><span style='color:#797997; background:#ffffe8; '>$nama</span><span style='color:#800080; background:#ffffe8; '>;</span><span style='color:#000000; background:#ffffe8; '></span>
				<span style='color:#800080; background:#ffffe8; '>}</span><span style='color:#808030; background:#ffffe8; '>)</span><span style='color:#800080; background:#ffffe8; '>;</span><span style='color:#000000; background:#ffffe8; '></span>
			</pre>
				Parameter pada route menggunakan : pada bagian depannya, sedangkan untuk parameter yang bersifat optional bisa menggunakan (:)<br />
				contoh: hello/(:nama)

				<br />
				<br />
				Dengan menggunakan Controller:
			<pre>
				<span style='color:#797997; background:#ffffe8; '>$app</span><span style='color:#808030; background:#ffffe8; '>-</span><span style='color:#808030; background:#ffffe8; '>></span><span style='color:#000000; background:#ffffe8; '>get</span><span style='color:#808030; background:#ffffe8; '>(</span><span style='color:#0000e6; background:#ffffe8; '>'selamat_datang/:nama'</span><span style='color:#808030; background:#ffffe8; '>,</span><span style='color:#000000; background:#ffffe8; '> </span><span style='color:#800000; background:#ffffe8; font-weight:bold; '>new</span><span style='color:#000000; background:#ffffe8; '> Controller\Welcome</span><span style='color:#808030; background:#ffffe8; '>(</span><span style='color:#797997; background:#ffffe8; '>$app</span><span style='color:#808030; background:#ffffe8; '>)</span><span style='color:#808030; background:#ffffe8; '>,</span><span style='color:#000000; background:#ffffe8; '> </span><span style='color:#800000; background:#ffffe8; font-weight:bold; '>function</span><span style='color:#000000; background:#ffffe8; '> </span><span style='color:#808030; background:#ffffe8; '>(</span><span style='color:#797997; background:#ffffe8; '>$controller</span><span style='color:#808030; background:#ffffe8; '>,</span><span style='color:#000000; background:#ffffe8; '> </span><span style='color:#797997; background:#ffffe8; '>$nama</span><span style='color:#808030; background:#ffffe8; '>)</span><span style='color:#000000; background:#ffffe8; '> </span><span style='color:#800000; background:#ffffe8; font-weight:bold; '>use</span><span style='color:#000000; background:#ffffe8; '> </span><span style='color:#808030; background:#ffffe8; '>(</span><span style='color:#797997; background:#ffffe8; '>$app</span><span style='color:#808030; background:#ffffe8; '>)</span><span style='color:#000000; background:#ffffe8; '> </span><span style='color:#800080; background:#ffffe8; '>{</span><span style='color:#000000; background:#ffffe8; '></span>
				<span style='color:#000000; background:#ffffe8; '>	</span><span style='color:#797997; background:#ffffe8; '>$controller</span><span style='color:#808030; background:#ffffe8; '>-</span><span style='color:#808030; background:#ffffe8; '>></span><span style='color:#000000; background:#ffffe8; '>index</span><span style='color:#808030; background:#ffffe8; '>(</span><span style='color:#797997; background:#ffffe8; '>$nama</span><span style='color:#808030; background:#ffffe8; '>)</span><span style='color:#800080; background:#ffffe8; '>;</span><span style='color:#000000; background:#ffffe8; '></span>
				<span style='color:#800080; background:#ffffe8; '>}</span><span style='color:#808030; background:#ffffe8; '>)</span><span style='color:#800080; background:#ffffe8; '>;</span><span style='color:#000000; background:#ffffe8; '></span>
			</pre>
				Pastikan sebelumnya sudah membuat Controller yang ingin digunakan pada route tersebut.
				
				<br />
				<br />
				Dengan menggunakan Template:
			<pre>
				<span style='color:#797997; background:#ffffe8; '>$app</span><span style='color:#808030; background:#ffffe8; '>-</span><span style='color:#808030; background:#ffffe8; '>></span><span style='color:#000000; background:#ffffe8; '>get</span><span style='color:#808030; background:#ffffe8; '>(</span><span style='color:#0000e6; background:#ffffe8; '>'hello/:nama'</span><span style='color:#808030; background:#ffffe8; '>,</span><span style='color:#000000; background:#ffffe8; '> </span><span style='color:#800000; background:#ffffe8; font-weight:bold; '>function</span><span style='color:#000000; background:#ffffe8; '> </span><span style='color:#808030; background:#ffffe8; '>(</span><span style='color:#797997; background:#ffffe8; '>$nama</span><span style='color:#808030; background:#ffffe8; '>)</span><span style='color:#000000; background:#ffffe8; '> </span><span style='color:#800080; background:#ffffe8; '>{</span><span style='color:#000000; background:#ffffe8; '></span>
				<span style='color:#000000; background:#ffffe8; '>	</span><span style='color:#800000; background:#ffffe8; font-weight:bold; '>echo</span><span style='color:#000000; background:#ffffe8; '> </span><span style='color:#0000e6; background:#ffffe8; '>'Hello '</span><span style='color:#808030; background:#ffffe8; '>.</span><span style='color:#797997; background:#ffffe8; '>$nama</span><span style='color:#800080; background:#ffffe8; '>;</span><span style='color:#000000; background:#ffffe8; '></span>
				<span style='color:#800080; background:#ffffe8; '>}</span><span style='color:#808030; background:#ffffe8; '>)</span><span style='color:#808030; background:#ffffe8; '>-</span><span style='color:#808030; background:#ffffe8; '>></span><span style='color:#000000; background:#ffffe8; '>template</span><span style='color:#808030; background:#ffffe8; '>(</span><span style='color:#0000e6; background:#ffffe8; '>'template_kecik'</span><span style='color:#808030; background:#ffffe8; '>)</span><span style='color:#800080; background:#ffffe8; '>;</span><span style='color:#000000; background:#ffffe8; '></span>
				<span style='color:#000000; background:#ffffe8; '></span>
				<span style='color:#797997; background:#ffffe8; '>$app</span><span style='color:#808030; background:#ffffe8; '>-</span><span style='color:#808030; background:#ffffe8; '>></span><span style='color:#000000; background:#ffffe8; '>get</span><span style='color:#808030; background:#ffffe8; '>(</span><span style='color:#0000e6; background:#ffffe8; '>'selamat_datang/:nama'</span><span style='color:#808030; background:#ffffe8; '>,</span><span style='color:#000000; background:#ffffe8; '> </span><span style='color:#800000; background:#ffffe8; font-weight:bold; '>new</span><span style='color:#000000; background:#ffffe8; '> Controller\Welcome</span><span style='color:#808030; background:#ffffe8; '>(</span><span style='color:#797997; background:#ffffe8; '>$app</span><span style='color:#808030; background:#ffffe8; '>)</span><span style='color:#808030; background:#ffffe8; '>,</span><span style='color:#000000; background:#ffffe8; '> </span><span style='color:#800000; background:#ffffe8; font-weight:bold; '>function</span><span style='color:#000000; background:#ffffe8; '> </span><span style='color:#808030; background:#ffffe8; '>(</span><span style='color:#797997; background:#ffffe8; '>$controller</span><span style='color:#808030; background:#ffffe8; '>,</span><span style='color:#000000; background:#ffffe8; '> </span><span style='color:#797997; background:#ffffe8; '>$nama</span><span style='color:#808030; background:#ffffe8; '>)</span><span style='color:#000000; background:#ffffe8; '> </span><span style='color:#800000; background:#ffffe8; font-weight:bold; '>use</span><span style='color:#000000; background:#ffffe8; '> </span><span style='color:#808030; background:#ffffe8; '>(</span><span style='color:#797997; background:#ffffe8; '>$app</span><span style='color:#808030; background:#ffffe8; '>)</span><span style='color:#000000; background:#ffffe8; '> </span><span style='color:#800080; background:#ffffe8; '>{</span><span style='color:#000000; background:#ffffe8; '></span>
				<span style='color:#000000; background:#ffffe8; '>	</span><span style='color:#797997; background:#ffffe8; '>$controller</span><span style='color:#808030; background:#ffffe8; '>-</span><span style='color:#808030; background:#ffffe8; '>></span><span style='color:#000000; background:#ffffe8; '>index</span><span style='color:#808030; background:#ffffe8; '>(</span><span style='color:#797997; background:#ffffe8; '>$nama</span><span style='color:#808030; background:#ffffe8; '>)</span><span style='color:#800080; background:#ffffe8; '>;</span><span style='color:#000000; background:#ffffe8; '></span>
				<span style='color:#800080; background:#ffffe8; '>}</span><span style='color:#808030; background:#ffffe8; '>)</span><span style='color:#808030; background:#ffffe8; '>-</span><span style='color:#808030; background:#ffffe8; '>></span><span style='color:#000000; background:#ffffe8; '>template</span><span style='color:#808030; background:#ffffe8; '>(</span><span style='color:#0000e6; background:#ffffe8; '>'template_kecik'</span><span style='color:#808030; background:#ffffe8; '>)</span><span style='color:#800080; background:#ffffe8; '>;</span><span style='color:#000000; background:#ffffe8; '></span>
			</pre>

				<strong><i>Catatan:</i></strong>
				<hr />
				Berlaku juga pada penggunaan post, untuk menggunakan controller dan template ada beberapa tahap yang perlu dipersiapkan
				<h4>Pertama:</h4>
				<p align="justify">
					Setting path atau lokasi untuk assets, applikasi(MVC), dan template, berikut cara setting:
			<pre>
				<span style='color:#797997; background:#ffffe8; '>$app</span><span style='color:#808030; background:#ffffe8; '>-></span><span style='color:#797997; background:#ffffe8; '>config</span><span style='color:#808030; background:#ffffe8; '>-</span><span style='color:#808030; background:#ffffe8; '>></span><span style='color:#000000; background:#ffffe8; '>set</span><span style='color:#808030; background:#ffffe8; '>(</span><span style='color:#0000e6; background:#ffffe8; '>'path.assets'</span><span style='color:#808030; background:#ffffe8; '>,</span><span style='color:#000000; background:#ffffe8; '> </span><span style='color:#0000e6; background:#ffffe8; '>'assets'</span><span style='color:#808030; background:#ffffe8; '>)</span><span style='color:#800080; background:#ffffe8; '>;</span><span style='color:#000000; background:#ffffe8; '></span>
				<span style='color:#797997; background:#ffffe8; '>$app</span><span style='color:#808030; background:#ffffe8; '>-></span><span style='color:#797997; background:#ffffe8; '>config</span><span style='color:#808030; background:#ffffe8; '>-</span><span style='color:#808030; background:#ffffe8; '>></span><span style='color:#000000; background:#ffffe8; '>set</span><span style='color:#808030; background:#ffffe8; '>(</span><span style='color:#0000e6; background:#ffffe8; '>'path.app'</span><span style='color:#808030; background:#ffffe8; '>,</span><span style='color:#000000; background:#ffffe8; '> </span><span style='color:#0000e6; background:#ffffe8; '>'app'</span><span style='color:#808030; background:#ffffe8; '>)</span><span style='color:#800080; background:#ffffe8; '>;</span><span style='color:#000000; background:#ffffe8; '></span>
				<span style='color:#797997; background:#ffffe8; '>$app</span><span style='color:#808030; background:#ffffe8; '>-></span><span style='color:#797997; background:#ffffe8; '>config</span><span style='color:#808030; background:#ffffe8; '>-</span><span style='color:#808030; background:#ffffe8; '>></span><span style='color:#000000; background:#ffffe8; '>set</span><span style='color:#808030; background:#ffffe8; '>(</span><span style='color:#0000e6; background:#ffffe8; '>'path.template'</span><span style='color:#808030; background:#ffffe8; '>,</span><span style='color:#000000; background:#ffffe8; '> </span><span style='color:#0000e6; background:#ffffe8; '>'templates'</span><span style='color:#808030; background:#ffffe8; '>)</span><span style='color:#800080; background:#ffffe8; '>;</span><span style='color:#000000; background:#ffffe8; '></span>
			</pre>
				</p>
				<h4>Kedua:</h4>
				<p align="justify">
					Buatlah folder/direktory berdasarkan settingan path sebelumnya.
				</p>
				<h4>Ketiga:</h4>
				<p align="justify">
					Untuk folder/direktori assets dan applikasi pastikan didalamnya terdapat sub folder/direktori<br />
			<pre>
				+-- Assets
				  |-- css
				  |--js
				  |--images
			</pre>
			<pre>
				+--App
				  |--controllers
				  |--models
				  |--views
			</pre>
				</p>
			</p>
		</section>

		<section>
			<h3>* Config</h3>
			<p align="justify">
				Untuk project yang besar dan tidak sederhana kita memerlukan beberapa setting/konfigurasi, untuk melakukan
				setting/konfigurasi framework ini juga dilengkapi config, baik untuk menyetting ataupun untuk membaca settingan
			</p>
			<h4> - set()</h4>
			<p align="justify">
				Gunakan fungsi set pada config untuk melakukan settingan nilai/menambah settingan
		<pre>
			<span style='color:#000000; background:#ffffe8; '>set</span><span style='color:#808030; background:#ffffe8; '>(</span><span style='color:#797997; background:#ffffe8; '>$key</span><span style='color:#808030; background:#ffffe8; '>,</span><span style='color:#000000; background:#ffffe8; '> </span><span style='color:#797997; background:#ffffe8; '>$value</span><span style='color:#808030; background:#ffffe8; '>)</span><span style='color:#000000; background:#ffffe8; '></span>
		</pre>
				paramater <strong>$key</strong> merupakan parameter kunci untuk sebuah settingan<br />
				paramater <strong>$value</strong> merupakan parameter nilai dari sebuah settingan<br />
				
				<br />
				<strong><i>Contoh:</i></strong>
				<hr />
		<pre>
			<span style='color:#797997; background:#ffffe8; '>$app</span><span style='color:#808030; background:#ffffe8; '>-></span><span style='color:#797997; background:#ffffe8; '>config</span><span style='color:#808030; background:#ffffe8; '>-</span><span style='color:#808030; background:#ffffe8; '>></span><span style='color:#000000; background:#ffffe8; '>set</span><span style='color:#808030; background:#ffffe8; '>(</span><span style='color:#0000e6; background:#ffffe8; '>'path.assets'</span><span style='color:#808030; background:#ffffe8; '>,</span><span style='color:#000000; background:#ffffe8; '> </span><span style='color:#0000e6; background:#ffffe8; '>'assets'</span><span style='color:#808030; background:#ffffe8; '>)</span><span style='color:#800080; background:#ffffe8; '>;</span><span style='color:#000000; background:#ffffe8; '></span>
		</pre>
			</p>
		
			<h4> - get()</h4>
			<p align="justify">
				Gunakan fungsi get untuk mendapatkan nilai dari suatu settingan
		<pre>
			<span style='color:#000000; background:#ffffe8; '>get</span><span style='color:#808030; background:#ffffe8; '>(</span><span style='color:#797997; background:#ffffe8; '>$key</span><span style='color:#808030; background:#ffffe8; '>)</span><span style='color:#000000; background:#ffffe8; '></span>
		</pre>
				parameter <strong>$key</strong> merupakan parameter kunci untuk sebuah settingan yang ingin diambil nilainya<br />

				<br />
				<strong><i>Contoh:</i></strong>
				<hr />
		<pre>
			<span style='color:#797997; background:#ffffe8; '>$asset_path</span><span style='color:#000000; background:#ffffe8; '> </span><span style='color:#808030; background:#ffffe8; '>=</span><span style='color:#000000; background:#ffffe8; '> </span><span style='color:#797997; background:#ffffe8; '>$app</span><span style='color:#808030; background:#ffffe8; '>-></span><span style='color:#797997; background:#ffffe8; '>config</span><span style='color:#808030; background:#ffffe8; '>-</span><span style='color:#808030; background:#ffffe8; '>></span><span style='color:#000000; background:#ffffe8; '>get</span><span style='color:#808030; background:#ffffe8; '>(</span><span style='color:#0000e6; background:#ffffe8; '>'path.assets'</span><span style='color:#808030; background:#ffffe8; '>)</span><span style='color:#800080; background:#ffffe8; '>;</span><span style='color:#000000; background:#ffffe8; '></span>
		</pre>
			</p>

		</section>

		<section>
			<h3>* Assets</h3>
			<p align="justify">
				Assets sangat diperlukan dalam mempermudah pekerjaan kita untuk menambahkan atau menghilangkan assets seperti css,
				js dan images, sangat berguna juga untuk membuat template, dan assets juga bisa disesuaikan bedasarkan controller
				yang digunakan. Assets css dan js memiliki struktur yang sama sedangkan untuk images berbeda.
			</p>

			<h4> - add()</h4>
			<p align="justify">
				Fungsi ini digunakan untuk menambahkan sebuah file assets baik css maupun js.

		<pre>
			<span style='color:#000000; background:#ffffe8; '>add</span><span style='color:#808030; background:#ffffe8; '>(</span><span style='color:#797997; background:#ffffe8; '>$file</span><span style='color:#808030; background:#ffffe8; '>=</span><span style='color:#0000e6; background:#ffffe8; '>''</span><span style='color:#808030; background:#ffffe8; '>)</span><span style='color:#000000; background:#ffffe8; '></span>
		</pre>
				paramater <strong>$file</strong> berisikan nama file assets yang ingin diload, tuliskan tanpa menggunakan extension
				<br />
				<strong><i>Contoh:</i></strong>
				<hr />
		<pre>
			<span style='color:#797997; background:#ffffe8; '>$app</span><span style='color:#808030; background:#ffffe8; '>-></span><span style='color:#797997; background:#ffffe8; '>assets</span><span style='color:#808030; background:#ffffe8; '>-></span><span style='color:#797997; background:#ffffe8; '>css</span><span style='color:#808030; background:#ffffe8; '>-</span><span style='color:#808030; background:#ffffe8; '>></span><span style='color:#400000; background:#ffffe8; '>add</span><span style='color:#808030; background:#ffffe8; '>(</span><span style='color:#0000e6; background:#ffffe8; '>'boostrap'</span><span style='color:#808030; background:#ffffe8; '>)</span><span style='color:#800080; background:#ffffe8; '>;</span><span style='color:#000000; background:#ffffe8; '></span>
			<span style='color:#797997; background:#ffffe8; '>$app</span><span style='color:#808030; background:#ffffe8; '>-></span><span style='color:#797997; background:#ffffe8; '>assets</span><span style='color:#808030; background:#ffffe8; '>-></span><span style='color:#797997; background:#ffffe8; '>js</span><span style='color:#808030; background:#ffffe8; '>-</span><span style='color:#808030; background:#ffffe8; '>></span><span style='color:#400000; background:#ffffe8; '>add</span><span style='color:#808030; background:#ffffe8; '>(</span><span style='color:#0000e6; background:#ffffe8; '>'jquery.min'</span><span style='color:#808030; background:#ffffe8; '>)</span><span style='color:#800080; background:#ffffe8; '>;</span><span style='color:#000000; background:#ffffe8; '></span>
		</pre>
			</p>

			<h4> - delete()</h4>
			<p align="justify">
				Fungsi ini digunakan untuk menghapus sebuah file assets yang ingin diload baik css maupun js.
		<pre>
			<span style='color:#000000; background:#ffffe8; '>delete</span><span style='color:#808030; background:#ffffe8; '>(</span><span style='color:#797997; background:#ffffe8; '>$file</span><span style='color:#808030; background:#ffffe8; '>=</span><span style='color:#0000e6; background:#ffffe8; '>''</span><span style='color:#808030; background:#ffffe8; '>)</span><span style='color:#000000; background:#ffffe8; '></span>
		</pre>
				paramater <strong>$file</strong> berisikan nama file assets yang ingin diload, tuliskan tanpa menggunakan extension
				<br />
				<strong><i>Contoh:</i></strong>
				<hr />
		<pre>
			<span style='color:#797997; background:#ffffe8; '>$app</span><span style='color:#808030; background:#ffffe8; '>-></span><span style='color:#797997; background:#ffffe8; '>assets</span><span style='color:#808030; background:#ffffe8; '>-></span><span style='color:#797997; background:#ffffe8; '>css</span><span style='color:#808030; background:#ffffe8; '>-</span><span style='color:#808030; background:#ffffe8; '>></span><span style='color:#400000; background:#ffffe8; '>delete</span><span style='color:#808030; background:#ffffe8; '>(</span><span style='color:#0000e6; background:#ffffe8; '>'boostrap'</span><span style='color:#808030; background:#ffffe8; '>)</span><span style='color:#800080; background:#ffffe8; '>;</span><span style='color:#000000; background:#ffffe8; '></span>
			<span style='color:#797997; background:#ffffe8; '>$app</span><span style='color:#808030; background:#ffffe8; '>-></span><span style='color:#797997; background:#ffffe8; '>assets</span><span style='color:#808030; background:#ffffe8; '>-></span><span style='color:#797997; background:#ffffe8; '>js</span><span style='color:#808030; background:#ffffe8; '>-</span><span style='color:#808030; background:#ffffe8; '>></span><span style='color:#400000; background:#ffffe8; '>delete</span><span style='color:#808030; background:#ffffe8; '>(</span><span style='color:#0000e6; background:#ffffe8; '>'jquery.min'</span><span style='color:#808030; background:#ffffe8; '>)</span><span style='color:#800080; background:#ffffe8; '>;</span><span style='color:#000000; background:#ffffe8; '></span>
		</pre>
			</p>

			<h4> - render()</h4>
			<p align="justify">
				Fungsi ini digunakan untu merender sebuah daftar asset atau salah satu asset yang ingin diload baik css maupun js
		<pre>
			<span style='color:#000000; background:#ffffe8; '>render</span><span style='color:#808030; background:#ffffe8; '>(</span><span style='color:#797997; background:#ffffe8; '>$file</span><span style='color:#808030; background:#ffffe8; '>=</span><span style='color:#0000e6; background:#ffffe8; '>''</span><span style='color:#808030; background:#ffffe8; '>)</span><span style='color:#000000; background:#ffffe8; '></span>
		</pre>
				paramater <strong>$file</strong> berisikan nama file assets yang ingin diload, tuliskan tanpa menggunakan extension
				<br />
				<strong><i>Contoh:</i></strong>
				<hr />
		<pre>
			<span style='color:#800000; background:#ffffe8; font-weight:bold; '>echo</span><span style='color:#000000; background:#ffffe8; '> </span><span style='color:#797997; background:#ffffe8; '>$app</span><span style='color:#808030; background:#ffffe8; '>-></span><span style='color:#797997; background:#ffffe8; '>assets</span><span style='color:#808030; background:#ffffe8; '>-></span><span style='color:#797997; background:#ffffe8; '>css</span><span style='color:#808030; background:#ffffe8; '>-</span><span style='color:#808030; background:#ffffe8; '>></span><span style='color:#000000; background:#ffffe8; '>render</span><span style='color:#808030; background:#ffffe8; '>(</span><span style='color:#808030; background:#ffffe8; '>)</span><span style='color:#800080; background:#ffffe8; '>;</span><span style='color:#000000; background:#ffffe8; '></span>
			<span style='color:#800000; background:#ffffe8; font-weight:bold; '>echo</span><span style='color:#000000; background:#ffffe8; '> </span><span style='color:#797997; background:#ffffe8; '>$app</span><span style='color:#808030; background:#ffffe8; '>-></span><span style='color:#797997; background:#ffffe8; '>assets</span><span style='color:#808030; background:#ffffe8; '>-></span><span style='color:#797997; background:#ffffe8; '>js</span><span style='color:#808030; background:#ffffe8; '>-</span><span style='color:#808030; background:#ffffe8; '>></span><span style='color:#000000; background:#ffffe8; '>render</span><span style='color:#808030; background:#ffffe8; '>(</span><span style='color:#808030; background:#ffffe8; '>)</span><span style='color:#800080; background:#ffffe8; '>;</span><span style='color:#000000; background:#ffffe8; '></span>
			<span style='color:#696969; background:#ffffe8; '>// atau spesifik render</span><span style='color:#000000; background:#ffffe8; '></span>
			<span style='color:#800000; background:#ffffe8; font-weight:bold; '>echo</span><span style='color:#000000; background:#ffffe8; '> </span><span style='color:#797997; background:#ffffe8; '>$app</span><span style='color:#808030; background:#ffffe8; '>-></span><span style='color:#797997; background:#ffffe8; '>assets</span><span style='color:#808030; background:#ffffe8; '>-></span><span style='color:#797997; background:#ffffe8; '>css</span><span style='color:#808030; background:#ffffe8; '>-</span><span style='color:#808030; background:#ffffe8; '>></span><span style='color:#000000; background:#ffffe8; '>render</span><span style='color:#808030; background:#ffffe8; '>(</span><span style='color:#0000e6; background:#ffffe8; '>'boostrap'</span><span style='color:#808030; background:#ffffe8; '>)</span><span style='color:#800080; background:#ffffe8; '>;</span><span style='color:#000000; background:#ffffe8; '></span>
			<span style='color:#800000; background:#ffffe8; font-weight:bold; '>echo</span><span style='color:#000000; background:#ffffe8; '> </span><span style='color:#797997; background:#ffffe8; '>$app</span><span style='color:#808030; background:#ffffe8; '>-></span><span style='color:#797997; background:#ffffe8; '>assets</span><span style='color:#808030; background:#ffffe8; '>-></span><span style='color:#797997; background:#ffffe8; '>js</span><span style='color:#808030; background:#ffffe8; '>-</span><span style='color:#808030; background:#ffffe8; '>></span><span style='color:#000000; background:#ffffe8; '>render</span><span style='color:#808030; background:#ffffe8; '>(</span><span style='color:#0000e6; background:#ffffe8; '>'boostrap.min'</span><span style='color:#808030; background:#ffffe8; '>)</span><span style='color:#800080; background:#ffffe8; '>;</span><span style='color:#000000; background:#ffffe8; '></span>
		</pre>
			</p>

			<h4> - images()</h4>
			<p align="justify">
				Fungsi ini digunakan untuk mendapatkan link file assets untuk gambar.
		<pre>
			<span style='color:#000000; background:#ffffe8; '>images</span><span style='color:#808030; background:#ffffe8; '>(</span><span style='color:#797997; background:#ffffe8; '>$file</span><span style='color:#808030; background:#ffffe8; '>)</span><span style='color:#000000; background:#ffffe8; '></span>
		</pre>
				paramater <strong>$file</strong> berisikan nama file assets gambar yang ingin digunakan.
				<br />
				<strong><i>Contoh:</i></strong>
				<hr />
		<pre>
			<span style='color:#a65700; '>&lt;</span><span style='color:#800000; font-weight:bold; '>img</span><span style='color:#274796; '> </span><span style='color:#074726; '>src</span><span style='color:#808030; '>=</span><span style='color:#0000e6; '>"</span><span style='color:#5f5035; background:#ffffe8; '>&lt;?php</span><span style='color:#000000; background:#ffffe8; '> </span><span style='color:#800000; background:#ffffe8; font-weight:bold; '>echo</span><span style='color:#000000; background:#ffffe8; '> </span><span style='color:#797997; background:#ffffe8; '>$app</span><span style='color:#808030; background:#ffffe8; '>-></span><span style='color:#797997; background:#ffffe8; '>assets</span><span style='color:#808030; background:#ffffe8; '>-</span><span style='color:#808030; background:#ffffe8; '>></span><span style='color:#000000; background:#ffffe8; '>images</span><span style='color:#808030; background:#ffffe8; '>(</span><span style='color:#0000e6; background:#ffffe8; '>'kecik.jpg'</span><span style='color:#808030; background:#ffffe8; '>)</span><span style='color:#800080; background:#ffffe8; '>;</span><span style='color:#000000; background:#ffffe8; '> </span><span style='color:#5f5035; background:#ffffe8; '>?></span><span style='color:#0000e6; '>"</span><span style='color:#274796; '> </span><span style='color:#a65700; '>/></span>
		</pre>
			</p>
		</section>

		<section>
			<h3>* Input</h3>
			<p align="justify">
				Input merupakan bentuk lain dari penggunaan $_GET, $_POST dan $_SERVER
			</p>
			
			<h4> - get()</h4>
			<p align="justify">
				Anda dapat menggunakan fungsi get untuk mendapatkan nilai dari $_GET
		<pre>
			<span style='color:#000000; background:#ffffe8; '>get</span><span style='color:#808030; background:#ffffe8; '>(</span><span style='color:#797997; background:#ffffe8; '>$var</span><span style='color:#808030; background:#ffffe8; '>=</span><span style='color:#0000e6; background:#ffffe8; '>''</span><span style='color:#808030; background:#ffffe8; '>)</span><span style='color:#000000; background:#ffffe8; '></span>
		</pre>
				paramater <strong>$var</strong> berisikan nama dari variabel get
				<br />
				<strong><i>Contoh:</i></strong>
				<hr />
		<pre>
			<span style='color:#400000; background:#ffffe8; '>print_r</span><span style='color:#808030; background:#ffffe8; '>(</span><span style='color:#797997; background:#ffffe8; '>$</span><span style='color:#800000; background:#ffffe8; font-weight:bold; '>this</span><span style='color:#808030; background:#ffffe8; '>-></span><span style='color:#797997; background:#ffffe8; '>input</span><span style='color:#808030; background:#ffffe8; '>-</span><span style='color:#808030; background:#ffffe8; '>></span><span style='color:#000000; background:#ffffe8; '>get</span><span style='color:#808030; background:#ffffe8; '>(</span><span style='color:#808030; background:#ffffe8; '>)</span><span style='color:#808030; background:#ffffe8; '>)</span><span style='color:#800080; background:#ffffe8; '>;</span><span style='color:#000000; background:#ffffe8; '></span>
			<span style='color:#797997; background:#ffffe8; '>$x</span><span style='color:#000000; background:#ffffe8; '> </span><span style='color:#808030; background:#ffffe8; '>=</span><span style='color:#000000; background:#ffffe8; '> </span><span style='color:#797997; background:#ffffe8; '>$</span><span style='color:#800000; background:#ffffe8; font-weight:bold; '>this</span><span style='color:#808030; background:#ffffe8; '>-></span><span style='color:#797997; background:#ffffe8; '>input</span><span style='color:#808030; background:#ffffe8; '>-</span><span style='color:#808030; background:#ffffe8; '>></span><span style='color:#000000; background:#ffffe8; '>get</span><span style='color:#808030; background:#ffffe8; '>(</span><span style='color:#0000e6; background:#ffffe8; '>'x'</span><span style='color:#808030; background:#ffffe8; '>)</span><span style='color:#800080; background:#ffffe8; '>;</span><span style='color:#000000; background:#ffffe8; '></span>
		</pre>
			</p>

			<h4> - post()</h4>
			<p align="justify">
				Anda dapat menggunakan fungsi post untuk mendapatkan nilai dari $_POST
		<pre>
			<span style='color:#000000; background:#ffffe8; '>post</span><span style='color:#808030; background:#ffffe8; '>(</span><span style='color:#797997; background:#ffffe8; '>$var</span><span style='color:#808030; background:#ffffe8; '>=</span><span style='color:#0000e6; background:#ffffe8; '>''</span><span style='color:#808030; background:#ffffe8; '>)</span><span style='color:#000000; background:#ffffe8; '></span>
		</pre>	
				paramater <strong>$var</strong> berisikan nama dari variabel post
				<br />
				<strong><i>Contoh:</i></strong>
				<hr />
		<pre>
			<span style='color:#400000; background:#ffffe8; '>print_r</span><span style='color:#808030; background:#ffffe8; '>(</span><span style='color:#797997; background:#ffffe8; '>$</span><span style='color:#800000; background:#ffffe8; font-weight:bold; '>this</span><span style='color:#808030; background:#ffffe8; '>-></span><span style='color:#797997; background:#ffffe8; '>input</span><span style='color:#808030; background:#ffffe8; '>-</span><span style='color:#808030; background:#ffffe8; '>></span><span style='color:#000000; background:#ffffe8; '>post</span><span style='color:#808030; background:#ffffe8; '>(</span><span style='color:#808030; background:#ffffe8; '>)</span><span style='color:#808030; background:#ffffe8; '>)</span><span style='color:#800080; background:#ffffe8; '>;</span><span style='color:#000000; background:#ffffe8; '></span>
			<span style='color:#797997; background:#ffffe8; '>$x</span><span style='color:#000000; background:#ffffe8; '> </span><span style='color:#808030; background:#ffffe8; '>=</span><span style='color:#000000; background:#ffffe8; '> </span><span style='color:#797997; background:#ffffe8; '>$</span><span style='color:#800000; background:#ffffe8; font-weight:bold; '>this</span><span style='color:#808030; background:#ffffe8; '>-></span><span style='color:#797997; background:#ffffe8; '>input</span><span style='color:#808030; background:#ffffe8; '>-</span><span style='color:#808030; background:#ffffe8; '>></span><span style='color:#000000; background:#ffffe8; '>post</span><span style='color:#808030; background:#ffffe8; '>(</span><span style='color:#0000e6; background:#ffffe8; '>'x'</span><span style='color:#808030; background:#ffffe8; '>)</span><span style='color:#800080; background:#ffffe8; '>;</span><span style='color:#000000; background:#ffffe8; '></span>
		</pre>
			</p>

			<h4> - server()</h4>
			<p align="justify">
				Anda dapat menggunakan fungsi server untuk mendapatkan nilai dari $_SERVER
		<pre>
			<span style='color:#000000; background:#ffffe8; '>server</span><span style='color:#808030; background:#ffffe8; '>(</span><span style='color:#797997; background:#ffffe8; '>$var</span><span style='color:#808030; background:#ffffe8; '>=</span><span style='color:#0000e6; background:#ffffe8; '>''</span><span style='color:#808030; background:#ffffe8; '>)</span><span style='color:#000000; background:#ffffe8; '></span>
		</pre>
				paramater <strong>$var</strong> berisikan nama dari variabel server
				<br />
				<strong><i>Contoh:</i></strong>
				<hr />
		<pre>
			<span style='color:#400000; background:#ffffe8; '>print_r</span><span style='color:#808030; background:#ffffe8; '>(</span><span style='color:#797997; background:#ffffe8; '>$</span><span style='color:#800000; background:#ffffe8; font-weight:bold; '>this</span><span style='color:#808030; background:#ffffe8; '>-></span><span style='color:#797997; background:#ffffe8; '>input</span><span style='color:#808030; background:#ffffe8; '>-</span><span style='color:#808030; background:#ffffe8; '>></span><span style='color:#000000; background:#ffffe8; '>server</span><span style='color:#808030; background:#ffffe8; '>(</span><span style='color:#808030; background:#ffffe8; '>)</span><span style='color:#808030; background:#ffffe8; '>)</span><span style='color:#800080; background:#ffffe8; '>;</span><span style='color:#000000; background:#ffffe8; '></span>
			<span style='color:#797997; background:#ffffe8; '>$host</span><span style='color:#000000; background:#ffffe8; '> </span><span style='color:#808030; background:#ffffe8; '>=</span><span style='color:#000000; background:#ffffe8; '> </span><span style='color:#797997; background:#ffffe8; '>$</span><span style='color:#800000; background:#ffffe8; font-weight:bold; '>this</span><span style='color:#808030; background:#ffffe8; '>-></span><span style='color:#797997; background:#ffffe8; '>input</span><span style='color:#808030; background:#ffffe8; '>-</span><span style='color:#808030; background:#ffffe8; '>></span><span style='color:#000000; background:#ffffe8; '>server</span><span style='color:#808030; background:#ffffe8; '>(</span><span style='color:#0000e6; background:#ffffe8; '>'HTTP_HOST'</span><span style='color:#808030; background:#ffffe8; '>)</span><span style='color:#800080; background:#ffffe8; '>;</span><span style='color:#000000; background:#ffffe8; '></span>
		</pre>
			</p>
		</section>
	</section>

	<section id="footer">
		&copy;2015 Dony Wahyu Isp, Palembang
	</section>
</body>
</html>
<?php 
	}
unset($manual); 
?>