<?php
/**
 * Kecik Framework - Sebuah Framework dengan satu file system
 *
 * @author 		Dony Wahyu Isp
 * @copyright 	2015 Dony Wahyu Isp
 * @link 		http://github.io/kecik
 * @license		MIT
 * @version 	1.0-alpha2
 * @package		Kecik
 *
 *----------------------------------------
 * INDEX CODE
 *----------------------------------------
 * Keterangan								Baris Code
 * + Controller Class ......................... 46
 *   - Custom Constructor ..................... 53
 *   - Custom Fungsi .......................... 58
 * + Model Class .............................. 80
 * 	 - Custom Code save ....................... 107
 *   - Custom Code  delete .................... 129
 * 	 - Custom Fungsi Model .................... 138
 * 	 - Custom Code Inisialisasi Model ......... 167
 * + Config Class ............................. 211
 * + AssetsBase Class ......................... 270
 * + Assets Class ............................. 360
 * + Url Class ................................ 400
 * + Route Class .............................. 480
 * + Input Class .............................. 681
 * + Kecik Class .............................. 731
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
 * @since 		1.0-alpha
 **/
if (!class_exists('Controller')) {
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
		protected function view($file, $param=array()) {
			extract($param);
			include Config::get('path.mvc').'/views/'.$file.'.php';
		}
	}
}

/**
 * Model
 * @package 	Kecik
 * @author 		Dony Wahyu Isp
 * @since 		1.0-alpha1
 **/
if (!class_exists('Model')) {
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
					$sql ="INSERT INTO `$this->table` ($this->fields) VALUES ($this->values)";
				// Untuk mengupdate record
				} else {
					$sql ="UPDATE `$this->table` SET $this->updateVar $this->_where";
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
				$updateVar[] = "$fields[$id] = $values[$id]";
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
}


/**
 * Config
 * @package 	Kecik
 * @author 		Dony Wahyu Isp
 * @since 		1.0-alpha
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
 * @since 		1.0-alpha
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
 * @since 		1.0-alpha
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
 * @since 1.0-alpha1
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
		header('Location: '.$this->_base_url.$link);
	}

	/**
	 * to
	 * @param string $link
	 * @return echo link
	 **/
	public function to($link) {
		echo $this->_base_url.$link;
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
 * @since 		1.0-alpha1
 **/
class Route {
	public static $_paramsStr = '';

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
		elseif ($pathinfo['dirname'] == '/index.php')
			$strlimit = 0;
		else
			$strlimit = strlen($pathinfo['dirname']);

		if (php_sapi_name() == 'cli-server') 
		    self::$BASEURL = self::$PROTOCOL.$_SERVER['HTTP_HOST'].'/';
		else if (php_sapi_name() == 'cli') {
			self::$_params= $_SERVER['argv'];
		    chdir(dirname(__FILE__));
		    self::$BASEURL = dirname(__FILE__).'\\';
		} else {
		    self::$BASEURL = self::$PROTOCOL.$_SERVER['HTTP_HOST'].substr( $pathinfo['dirname']."/", 0, $strlimit+1 );
		}

		if (php_sapi_name() == 'cli') {
		 	$result_segment = $_SERVER['argv'];
            array_shift($result_segment);

            self::$_params = $result_segment;
            self::$_realparams = self::$_params;

        } else {
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

	        self::$_paramsStr = implode('/', $result_segment);
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

	/**
	 * setParamsStr
	 * @param string $params
	 **/
	public function setParamStr($params) {
		self::$_paramsStr = $params;
	}

	/**
	 * getParamStr
	 **/
	public function getParamStr() {
		return self::$_paramsStr;
	}

	/**
	 * isPost
	 * Untuk check apakah request method adalah Post
	 * @return Bool
	 **/
	public function isPost() {
		if ($_SERVER['REQUEST_METHOD'] == 'POST') 
			return TRUE;
		else
			return FALSE;
	}

	/**
	 * isGet
	 * Untuk check apakah request method adalah Get
	 * @return Bool
	 **/
	public function isGet() {
		if ($_SERVER['REQUEST_METHOD'] == 'GET') 
			return TRUE;
		else
			return FALSE;
	}

	/**
	 * isPut
	 * Untuk check apakah request method adalah Put
	 * @return Bool
	 **/
	public function isPut() {
		if ($_SERVER['REQUEST_METHOD'] == 'PUT') 
			return TRUE;
		else
			return FALSE;
	}

	/**
	 * isAjax
	 * Untuk check apakah request method adalah AJAX
	 * @return Bool
	 **/
	public function isAjax() {
		if (filter_input(INPUT_SERVER, 'HTTP_X_REQUESTED_WITH') === 'xmlhttprequest')
		   return TRUE;
		else
			return FALSE;
	}
}

Route::init();
//--

/**
 * Input
 * 
 * @package 	Kecik
 * @author 		Dony Wahyu Isp
 * @since 		1.0-alpha
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
 * @since 		1.0-alpha4
 **/
class Kecik {
	/**
	 * @var object $route, $url, $config, $assets
	 **/
	var $route, $url, $config, $assets, $input;

	/**
	 * @var Closure Object $callable
	 **/
	private $callable;
	/**
	 * @var Bool $routedStatus
	 **/
	private $routedStatus = FALSE;

	/**
	 * @var string $fullrender
	 **/
	private static $fullrender = '';

	/**
	 * autoload
	 * autoload untuk MVC
	 * @param string $class
	 **/
	public function autoload($class) {
		$class_array = explode('\\', $class);
		if (count($class_array)>1) {
			$file_load = $this->config->get('path.mvc').'/'.strtolower($class_array[0]).'s/'.strtolower($class_array[1]).'.php';
			if (file_exists($file_load))
				include $file_load;
		}
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

	/**
	 * setCallable
	 * untuk setting paramater fungsi pada get atau post
	 * @param array $args
	 **/
	private function setCallable($args) {

		$route = array_shift($args);
		$real_params = array();

		if (!is_callable($args[0])) {
			$controller = array_shift($args);
			$real_params['controller'] = $controller;
		}

		if ($route == '/' && count( $this->route->_getParams() ) <= 0 ) {
			$this->callable = array_pop($args);
			$this->routedStatus = TRUE;
		} else {
			$route_pattern = str_replace('/', '\\/', $route);
			//convert route kedalam pattern parameter optional
			$route_pattern = preg_replace('/\\\\\\/\\(:\\w+\\)/', '(\\/\\\\w+){0,}', $route_pattern, -1);
			//convert route kedalam pattern parameter wajib
			$route_pattern = preg_replace('/:\\w+/', '\\w+', $route_pattern, -1);
			
			if ($route != '/' && preg_match('/^'.$route_pattern.'/', $this->route->getParamStr(), $matches, PREG_OFFSET_CAPTURE) ) {

				$this->callable = array_pop($args);
				$this->routedStatus = TRUE;

				$p = explode('/', $route);

				while(list($key, $value) = each($p)) {
					
					if (substr(trim($value, '/'), 0, 1) == ':') {
						$real_params[$value] = $this->route->_getParams($key);
					} elseif (substr(trim($value, '/'), 0, 2) == '(:' && substr(trim($value, '/'), -1, 1) == ')') {
						if ($this->route->_getParams($key) != NULL)
							$real_params[$value] = $this->route->_getParams($key);
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
		if (!$this->route->isGet()) return $this;

		if (is_callable($this->callable) ) {
			$this->routedStatus = FALSE;
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
		if (!$this->route->isPost()) return $this;

		if (is_callable($this->callable) ) {
			$this->routedStatus = FALSE;
			return $this;
		}

		self::$fullrender = '';
		$args = func_get_args();

		$this->setCallable($args);
		return $this;
	}

	/**
	 * template
	 * Untuk menerapkan sebuah template
	 * @param string template
	 **/
	public function template($template) {
		if ($this->routedStatus) {
			$tpl = file_get_contents($this->config->get('path.template').'/'.$template.'.php');
			self::$fullrender = str_replace(array('{{', '}}'), array('<?php', '?>'), $tpl);
			self::$fullrender = str_replace(array('@controller'), array('<?php call_user_func_array($this->callable, $this->route->getParams()) ?>'), self::$fullrender);
		}
	}

	/**
	 * run
	 **/
	public function run() {
		if (php_sapi_name() == 'cli-server')  {
			if( is_file(route::$BASEPATH.str_replace('/', DIRECTORY_SEPARATOR, $_SERVER['REQUEST_URI'])) && file_exists( route::$BASEPATH.str_replace('/', DIRECTORY_SEPARATOR, $_SERVER['REQUEST_URI'] ) ) && substr(strtolower($_SERVER['REQUEST_URI']), -4) != '.php' ) {
				readfile(route::$BASEPATH.str_replace('/', DIRECTORY_SEPARATOR, $_SERVER['REQUEST_URI'] ));
				return TRUE;
			}
		}
		
		if (self::$fullrender != '') {
			if (is_callable($this->callable)) {
				eval('?>'.self::$fullrender);
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

	/**
	 * error
	 * Untuk menampilkan error http response
	 * @param integer $code
	 **/
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
