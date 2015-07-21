<?php
/*///////////////////////////////////////////////////////////////
 /** ID: | /-- ID: Indonesia
 /** EN: | /-- EN: English
 ///////////////////////////////////////////////////////////////*/

/**
 * ID: Kecik Framework - Sebuah Framework dengan satu file system
 * EN: Kecik Framework - The Framework with single file system 
 *
 * @author 		Dony Wahyu Isp
 * @copyright 	2015 Dony Wahyu Isp
 * @link 		http://github.com/kecik-framework/kecik
 * @license		MIT
 * @version 	1.1.0
 * @package		Kecik
 *
 *-----------------------------------------------------------
 * INDEX CODE
 *-----------------------------------------------------------
 * Keterangan | Description					Baris Code | Line
 * + Controller Class .............................. 54
 *   - Custom Constructor .......................... 62
 *   - Custom Fungsi ............................... 69
 * + Model Class ................................... 93
 * 	 - Custom Code save ............................ 123
 *   - Custom Code  delete ......................... 147
 * 	 - Custom Fungsi Model ......................... 157
 * 	 - Custom Code Inisialisasi Model .............. 167
 * + Config Class .................................. 263
 * + AssetsBase Class .............................. 323
 * + Assets Class .................................. 444
 * + Url Class ..................................... 493
 * + Route Class ................................... 578
 * + Request Class ................................. 990
 * + Kecik Class ................................... 1098
 **/

namespace Kecik;

/**
 * ID: Autoload untuk composer
 * EN: Autoload for Composer
 **/
//require 'vendor/autoload.php';

/**
 * Controller
 * @package 	Kecik
 * @author 		Dony Wahyu Isp
 * @since 		1.0.1-alpha
 **/
if (!class_exists('Kecik\Controller')) {
	class Controller {
		protected $request = '';
		protected $url = '';
		protected $assets = '';
		protected $config = '';
		//protected $container = '';
		//protected $db = '';

		/**
		 * Construtor Controller
		 **/
		public function __construct() {
			//** ID: Silakan tambah inisialisasi controller sendiri disini
			//** EN: Please add your initialitation of controller in this

			//-- ID: Akhir tambah inisialisasi sendiri
			//-- EN: End add your initialitation

			$app = Kecik::getInstance();
			$this->request = $app->request;
			$this->url = $app->url;
			$this->assets = $app->assets;
			$this->config = $app->config;
			if (isset($app->container))
				$this->container = $app->container;
			if (isset($app->db))
				$this->db = $app->db;
			if (isset($app->session))
				$this->session = $app->session;
			if (isset($app->cookie))
				$this->cookie = $app->cookie;
			if (isset($app->language))
				$this->language = $app->language;
		}

		//** ID: Silakan tambah fungsi controller sendiri disini
		//** EN: Please add your function/method of controller in this

		//-- ID: Akhir tambah fungsi sendiri
		//-- EN: End add your function/method

		/**
		 * view
		 * ID: Funngsi untuk menampilkan view
		 * EN: Function for displaying view
		 * @param string $file
		 * @param array $param
		 **/
		protected function view($file, $param=array()) {
			if (php_sapi_name() == 'cli')
				$mvc_path = Config::get('path.basepath').Config::get('path.mvc');
			else
				$mvc_path = Config::get('path.mvc');

			/*ob_start();
			extract($param);
			$myfile = fopen(Config::get('path.mvc').'/views/'.$file.'.php', "r");
			$view = fread($myfile,filesize(Config::get('path.mvc').'/views/'.$file.'.php'));
			fclose($myfile);
			//$view = file_get_contents( Config::get('path.mvc').'/views/'.$file.'.php' );
			eval('?>'.$view);
			$result = ob_get_clean();
			*/
			extract($param);
			include $mvc_path.'/views/'.$file.'.php';
		}
	}
}

/**
 * Model
 * @package 	Kecik
 * @author 		Dony Wahyu Isp
 * @since 		1.0.1-alpha
 **/
if (!class_exists('Kecik\Model')) {

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
		 * ID: Fungsi untuk menambah atau mengupdate record (Insert/Update)
		 * EN: Function for adding/updating record (Insert/Update)
		 * @return string SQL Query
		 **/
		public function save() {
			$this->setFieldsValues();

			if ($this->table != '') {
				//** ID: Untuk menambah record | EN: For adding record
				if ($this->add == TRUE) {
					$sql ="INSERT INTO $this->table ($this->fields) VALUES ($this->values)";
				//** ID: Untuk mengupdate record | EN: For updating record
				} else {
					$sql ="UPDATE $this->table SET $this->updateVar $this->_where";
				}

				//** ID: silakan tambah code database sendiri disini
				//** EN: please add your database code in this

				//-- ID: Akhir tambah code database sendiri
				//-- EN: End of add your database code
			}

			return (isset($sql))?$sql:'';
		}

		/**
		 * delete
		 * ID: Fungsi untuk menghapus record
		 * EN: Function for deleting record
		 * @return string SQL Query
		 **/
		public function delete() {
			$this->setFieldsValues();

			if ($this->table != '') {
				if ($this->_where != '') {
					$sql = "DELETE FROM $this->table $this->_where";
				}

				//** ID: silakan tambah code database sendiri disini
				//** EN: please add your database code in this

				//-- ID: AKhir tambah code database sendiri
				//-- EN: End of add your database code
			}

			return (isset($sql))?$sql:'';
		}

		//** ID: Silakan tambah fungsi model sendiri disini
		//** EN: Please add your function/method of model in this

		//-- ID: Akhir tambah fungsi sendiri
		//-- EN: End of your function/method

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
							$and[] = "$field$value";
						else
							$and[] = "$field='$value'";
					}
					$this->_where .= implode(' AND ', $and);
				} else {
					$this->_where .= "id='".$id."'";
				}

				$this->add = FALSE;

				//** ID: Silakan tambah inisialisasi model sendiri disini
				//** EN: Please add your initialitation of model in this

				//-- EN: Akhir tambah inisialisasi model sendiri
				//-- EN: End of your initialitation model
			}
		}

		/**
		 * setFieldValues
		 * ID: Fungsi untuk menyetting Variable Fields dan Values
		 * EN: Function/Method for setting fields and values variable
		 **/
		private function setFieldsValues() {
			$fields = array_keys($this->_field);
			while(list($id, $field) = each($fields))
				$fields[$id] = "$fields[$id]";
			
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
 * @since 		1.0.1-alpha
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
		self::$config = [
			'path.assets' => '',
			'path.templates' => '',
			'mod_rewrite' =>FALSE,
			'index' => '',
			'template.open_tag' => '{{',
			'template.close_tag' => '}}'
		];
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
	 * @return 	string ID: nilai dari key config | EN: value of key config
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
 * @since 		1.0.1-alpha
 **/
class AssetsBase {
	/**
	 * @var array
	 **/
	var $assets;
	
	/**
	 * @var array
	 **/
	var $attr;

	/**
	 * @var string
	 **/
	var $type;

	/**
	 * @var ID: Objek dari Url | EN: Object of Url
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
		$this->attr[$type] = array();
	}

	/**
	 * add
	 * @param string $file
	 **/
	public function add($file, $attr=array()) {
		if (!in_array($file, $this->assets[$this->type])) {
			$this->assets[$this->type][] = $file;
			$this->attr[$this->type][] = $attr;
		}
	}

	/**
	 * delete
	 * @param $file
	 **/
	public function delete($file) {
		$key = array_search($file, $this->assets[$this->type]);
		unset($this->assets[$this->type][$key]);
		unset($this->attr[$this->type][$key]);
	}

	/**
	 * render
	 * @param string $file optional
	 **/
	public function render($file='') {
		reset($this->assets[$this->type]);
		//reset($this->attr[$this->type]);
		
		$attr = '';

		if ($this->type == 'js') {
			if ($file != '') {
				$key = array_search($file, $this->assets[$this->type]);
				while(list($at, $val) = each($this->attr[$this->type][$key]))
					$attr .= $at.'="'.$val.'" ';
				if ($key)
					return '<script type="text/javascript" src="'.$this->baseurl.Config::get('path.assets')."/$this->type/".$this->assets[$this->type][$key].'.'.$this->type.'" '.$attr.'></script>'."\n";
			} else {
				$render = '';
				while(list($key, $value) = each($this->assets[$this->type])) {
					$attr = '';
					while(list($at, $val) = each($this->attr[$this->type][$key]))
						$attr .= $at.'="'.$val.'" ';
					$render .= '<script type="text/javascript" src="'.$this->baseurl.Config::get('path.assets')."/$this->type/".$value.'.'.$this->type.'" '.$attr.'></script>'."\n";
				}

				return $render;
			}

		} elseif ($this->type == 'css') {
			if ($file != '') {
				$key = array_search($file, $this->assets[$this->type]);
				while(list($at, $val) = each($this->attr[$this->type][$key]))
					$attr .= $at.'="'.$val.'" ';
				if ($key)
					return '<link rel="stylesheet" href="'.$this->baseurl.Config::get('path.assets')."/$this->type/".$this->assets[$this->type][$key].'.'.$this->type.'" '.$attr.' />'."\n";
			} else {
				$render = '';
				while(list($key, $value) = each($this->assets[$this->type])) {
					$attr = '';
					while(list($at, $val) = each($this->attr[$this->type][$key]))
						$attr .= $at.'="'.$val.'" ';
					$render .= '<link rel="stylesheet" href="'.$this->baseurl.Config::get('path.assets')."/$this->type/".$value.'.'.$this->type.'" '.$attr.' />'."\n";
				}

				return $render;
			}
		}
	} 

	/**
	 * url
	 * Get assets URL
	 * @return string
	 **/
	public function url() {
		return $this->baseurl.Config::get('path.assets')."/$this->type/";
	}
}

/**
 * Assets
 * 
 * @package 	Kecik
 * @author 		Dony Wahyu Isp
 * @since 		1.0.1-alpha
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
	public function __construct(Url $url) {
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

	/**
	 * url
	 * Get assets URL
	 * @return string
	 **/
	public function url() {
		return $this->baseUrl.Config::get('path.assets').'/';
	}
}

//--


/**
 * Url
 * @package Kecik
 * @author Dony Wahyu Isp
 * @since 1.0.1-alpha
 **/
class Url {
	/**
	 * @var string $_protocol, $_base_url, $_base_path, $_index
	 **/
	private $_protocol, $_base_url, $_base_path, $_index;

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

		if ( Config::get('mod_rewrite') === FALSE ) {
			$this->_index = basename($_SERVER["SCRIPT_FILENAME"], '.php').'.php/';
			Config::set('index', $this->_index);
		}
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
		header('Location: '.$this->_base_url.$this->_index.$link);
		exit();
	}

	/**
	 * to
	 * @param string $link
	 * @return echo link
	 **/
	public function to($link) {
		echo $this->_base_url.$this->_index.$link;
	}

	/**
	 * linkto
	 * @param string $link
	 * @return string
	 **/
	public function linkTo($link) {
		return $this->_base_url.$this->_index.$link;
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
	/**
	 * @var string $_paramStr
	 **/
	public static $_paramsStr = '';

	/**
	 * @var string $_destination
	 **/
	public static $_destination = '';
	
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

	public static $HTTP_RESPONSE = [
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
    ];

	public function __construct() {
		
	}

	/**
	 * Init
	 * ID: Untuk inisialisasi Kelas Route
	 * EN: For initialitation Route Class
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

		$index = basename($_SERVER["SCRIPT_FILENAME"], '.php').'.php';
		Config::set('index', $index);
		
		if ( strpos($pathinfo['dirname'], '/'.$index) > 0 )
			$strlimit = strpos($pathinfo['dirname'], '/'.$index);
		elseif ($pathinfo['dirname'] == '/'.$index)
			$strlimit = 0;
		else
			$strlimit = strlen($pathinfo['dirname']);
		
		if (php_sapi_name() == 'cli-server') 
		    self::$BASEURL = self::$PROTOCOL.$_SERVER['HTTP_HOST'].'/';
		else if (php_sapi_name() == 'cli') {
			self::$_params= $_SERVER['argv'];
		    chdir(self::$BASEPATH);
		    self::$BASEURL = self::$BASEPATH;
		} else {
			//** ID: Terkadang terdapat masalah bagian base url, kamu dapat mengedit bagian ini. Biasanya masalah pada $pathinfo['dirname']
			//** EN: Sometimes have a problem in base url section, you can editi this section. normally at $pathinfo['dirname']
			self::$BASEURL = self::$PROTOCOL.$_SERVER['HTTP_HOST'].substr( $pathinfo['dirname'], 0, $strlimit );
			if ( substr(self::$BASEURL, -1,1) != '/')
				self::$BASEURL .= '/';
		}

		if (php_sapi_name() == 'cli') {
		 	$result_segment = $_SERVER['argv'];
            array_shift($result_segment);

            self::$_params = $result_segment;
            self::$_realparams = self::$_params;
            self::$_paramsStr = implode('/', $result_segment);
        } else {
		    $path = str_replace( self::$PROTOCOL.$_SERVER['HTTP_HOST'].'/', '', self::$BASEURL );
	        
	        $path = str_replace($path, '', $_SERVER['REQUEST_URI']);
	        if (substr($path, 0, 1) == '/' ) $path=substr($path, 1);

	        $segments = explode('/', $path);
	        
	        if ( $segments[count($segments)-1] == '' && count($segments) > 1 ) unset($segments[count($segments)-1]);
	         
	        $result_segment = array();
	        while(list($key, $seg) = each($segments)) {
	            if ($segments[$key] != $index && $seg != '' )
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
	 * is
	 * @return string ID: pattern route yang cocok | EN: current pattern route
	 **/
	public function is($route='') {
		if ($route == '')
			return self::$_destination;
		else {
			if (self::$_destination == $route)
				return TRUE;
			else
				return FALSE;
		}
	}

	/**
	 * isPost
	 * ID: Untuk check apakah request method adalah Post
	 * EN: For checking request method is Post
	 * @return Bool
	 **/
	public function isPost() {
		if (isset($_SERVER['REQUEST_METHOD'])) {
			if ($_SERVER['REQUEST_METHOD'] == 'POST' && !isset($_POST['_METHOD'])) 
				return TRUE;
			else
				return FALSE;
		} else
			return FALSE;
	}

	/**
	 * isGet
	 * ID: Untuk check apakah request method adalah Get
	 * EN: For checking request method is Get
	 * @return Bool
	 **/
	public function isGet() {
		if (isset($_SERVER['REQUEST_METHOD'])) {
			if ($_SERVER['REQUEST_METHOD'] == 'GET' && !isset($_POST['_METHOD'])) 
				return TRUE;
			else
				return FALSE;
		} else
			return TRUE;
	}

	/**
	 * isPut
	 * ID: Untuk check apakah request method adalah Put
	 * EN: For checking request method is Put
	 * @return Bool
	 **/
	public function isPut() {
		if (isset($_SERVER['REQUEST_METHOD'])) {
			if ($_SERVER['REQUEST_METHOD'] == 'PUT' || (isset($_POST['_METHOD']) && $_POST['_METHOD'] == 'PUT')) {
				parse_str(file_get_contents("php://input"), $vars);
				if (isset($vars['_METHOD'])) unset($vars['_METHOD']);			
        		$GLOBALS['_PUT'] = $_PUT = $vars;
				return TRUE;
			} else
				return FALSE;
		} else 
			return FALSE;


	}

	/**
	 * isDelete
	 * ID: Untuk check apakah request method adalah Delete
	 * EN: For checking request method is Delete
	 * @return Bool
	 **/
	public function isDelete() {
		if (isset($_SERVER['REQUEST_METHOD'])) {
			if ($_SERVER['REQUEST_METHOD'] == 'DELETE' || (isset($_POST['_METHOD']) && $_POST['_METHOD'] == 'DELETE')) {
				parse_str(file_get_contents("php://input"), $vars);
				if (isset($vars['_METHOD'])) unset($vars['_METHOD']);	
        		$GLOBALS['_DELETE'] = $_DELETE = $vars;
				return TRUE;
			} else
				return FALSE;
		} else 
			return FALSE;
	}

	/**
	 * isPatch
	 * ID: Untuk check apakah request method adalah Patch
	 * EN: For checking request method is Patch
	 * @return Bool
	 **/
	public function isPatch() {
		if (isset($_SERVER['REQUEST_METHOD'])) {
			if ($_SERVER['REQUEST_METHOD'] == 'PATCH' || (isset($_POST['_METHOD']) && $_POST['_METHOD'] == 'PATCH')) {
				parse_str(file_get_contents("php://input"), $vars);
				if (isset($vars['_METHOD'])) unset($vars['_METHOD']);	
        		$GLOBALS['_PATCH'] = $_PATCH = $vars;
				return TRUE;
			} else
				return FALSE;
		} else 
			return FALSE;
	}

	/**
	 * isOptions
	 * ID: Untuk check apakah request method adalah Options
	 * EN: For checking request method is Options
	 * @return Bool
	 **/
	public function isOptions() {
		if (isset($_SERVER['REQUEST_METHOD'])) {
			if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS' || (isset($_POST['_METHOD']) && $_POST['_METHOD'] == 'OPTIONS')) {
				parse_str(file_get_contents("php://input"), $vars);
				if (isset($vars['_METHOD'])) unset($vars['_METHOD']);
        		$GLOBALS['_OPTIONS'] = $_OPTIONS = $vars;
				return TRUE;
			} else
				return FALSE;
		} else 
			return FALSE;
	}

	/**
	 * isAjax
	 * ID: Untuk check apakah request method adalah AJAX
	 * EN: For checking request method is AJAX
	 * @return Bool
	 **/
	public function isAjax() {
		if (filter_input(INPUT_SERVER, 'HTTP_X_REQUESTED_WITH') === 'xmlhttprequest')
		   return TRUE;
		else
			return FALSE;
	}
}

//Route::init();
//--

class UploadFile extends \SplFileInfo {
	private $file;

	public function __construct($file) {
		if (isset($file['tmp_name'])) {
			parent::__construct($file['tmp_name']);
			$this->file = $file;
		}
	}

	public function move($destination, $newName='') {
		$source = $this->file['tmp_name'];

		if ($destination != '' && substr($destination, -1) != '/')
			$destination .= '/';

		if (!empty($newName)) 
			$target = $destination.$newName;
		else
			$target = $destination.$this->file['name'];

        if (!@move_uploaded_file($source, $target)) {
            $error = error_get_last();
            throw new FileException(sprintf('Could not move the file "%s" to "%s" (%s)', $this->getPathname(), $target, strip_tags($error['message'])));
        }
        @chmod($target, 0666 & ~umask());
        return $target;

	}
	
	public function __tostring() {
		return (isset($this->file['name']))? $this->file['name']:NULL;
	}
}

/**
 * Request
 * 
 * @package 	Kecik
 * @author 		Dony Wahyu Isp
 * @since 		1.0-alpha
 **/
class Request {
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
	 * put
	 * @param string $var
	 * @return mixed
	 **/
	public function put($var='') {
		if ($var == '')
			return (isset($GLOBALS['_PUT']))? $GLOBALS['_PUT']:NULL;
		else
			return (isset($GLOBALS['_PUT'][$var]))? $GLOBALS['_PUT'][$var] : NULL;
	}

	/**
	 * delete
	 * @param string $var
	 * @return mixed
	 **/
	public function delete($var='') {
		if ($var == '')
			return (isset($GLOBALS['_DELETE']))? $GLOBALS['_DELETE']:NULL;
		else
			return (isset($GLOBALS['_DELETE'][$var]))? $GLOBALS['_DELETE'][$var] : NULL;
	}

	/**
	 * patch
	 * @param string $var
	 * @return mixed
	 **/
	public function patch($var='') {
		if ($var == '')
			return (isset($GLOBALS['_PATCH']))? $GLOBALS['_PATCH']:NULL;
		else
			return (isset($GLOBALS['_PATCH'][$var]))? $GLOBALS['_PATCH'][$var] : NULL;
	}

	/**
	 * options
	 * @param string $var
	 * @return mixed
	 **/
	public function options($var='') {
		if ($var == '')
			return (isset($GLOBALS['_OPTIONS']))? $GLOBALS['_OPTIONS']:NULL;
		else
			return (isset($GLOBALS['_OPTIONS'][$var]))? $GLOBALS['_OPTIONS'][$var] : NULL;
	}

	/**
	 * file
	 * @param string $var
	 * @return mixed
	 **/
	public function file($file) {
		$file = $_FILES[$file];
		return new UploadFile($file);
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
	 * @var object $route, $url, $config, $assets, $request
	 **/
	var $route, $url, $config, $assets, $request;

	/**
	 * @var Closure Object $callable
	 **/
	private $callable;
	private $middleware = ['before'=>array(), 'after'=>array()];
	/**
	 * @var Bool $routedStatus
	 **/
	private $routedStatus = FALSE;

	/**
	 * @var string $fullrender
	 **/
	private static $fullrender = '';

	private static $header = array();

	private static $group = '';
	private $group_func;

	private static $instance = null;
	/**
	 * autoload
	 * ID: autoload untuk MVC
	 * EN: autoload for MVC
	 * @param string $class
	 **/
	public function autoload($class) {
		$class_array = explode('\\', $class);
		if (count($class_array)>1) {
			if (php_sapi_name() == 'cli')
				$mvc_path = $this->config->get('path.basepath').$this->config->get('path.mvc');
			else
				$mvc_path = $this->config->get('path.mvc');

			//** if count $class_array = 3 is HMVC
			if (count($class_array) >= 3) {
				$hmvc_path = '';
				for($i=0; $i<count($class_array)-2; $i++) 
					$hmvc_path .= $class_array[$i].'/';
				//**                          Module                   Controllers/Models                           Class
				$file_load = $mvc_path.'/'.$hmvc_path.strtolower($class_array[count($class_array)-2]).'s/'.strtolower($class_array[count($class_array)-1]).'.php';
			} else  //**                          Controller/Models              Class
				$file_load = $mvc_path.'/'.strtolower($class_array[0]).'s/'.strtolower($class_array[1]).'.php';
			
			if (file_exists($file_load))
				include $file_load;
		}
	}

	/**
	 * __construct
	 * @param array $config optional
	 **/
	public function __construct($config=array()) {
		//** Config
		$this->config = new Config();

		if (is_array($config) && count($config)) {
			while(list($key, $value) = each($config))
				$this->config->set($key, $value);
		}
		//-- End Config

		if ($this->config->get('path.basepath') == '')
			$this->config->set('path.basepath', getcwd().'/');
		

		self::$header[] = $_SERVER["SERVER_PROTOCOL"].' '.Route::$HTTP_RESPONSE[200];
		

		$this->route = new Route();
		Route::init();
		Route::$BASEPATH = $this->config->get('path.basepath');
		$this->url = new Url(Route::$PROTOCOL, Route::$BASEURL, Route::$BASEPATH);
		$this->assets = new Assets($this->url);
		$this->request = new Request();

		//** ID: Memasukan Libary/Pustaka berdasarkan config | EN: Load Dynamic Libraries from config
		$libraries = $this->config->get('libraries');
		if (is_array($libraries) && count($libraries) > 0 ) {
			while(list($library, $params) = each($libraries)) {
				$clsLibrary = 'Kecik\\'.$library;
				if (class_exists($clsLibrary)) {
					if (isset($params['enable']) && $params['enable'] === TRUE) {
						$library = strtolower($library);

						//** ID: Untuk Library/Pustaka tanpa parameter
						//** EN: For Library without parameter
						if (!isset($params['config']) && !isset($params['params'])) {
							//** ID: Untuk Library/Pustaka DIC | EN: For DIC Library
							if ($library == 'dic')
								$this->container = new DIC();
							elseif ($library == 'mvc') {
								if (isset($this->db))
									MVC::setDB($this->db);
							} else // ID: Untuk Library/Pustaka lain | EN: Other Library
								$this->$library = new $clsLibrary();
						//** ID: Untuk Library/Pustaka dengan parameter Kelas Kecik
						//** EN: For Library with parameter of Kecik CLass
						} elseif (isset($params['config'])) {
							//** ID: Buat variabel config
							//** EN: Create config variable
							while (list($key, $value) = each($params['config']) )
								$this->config->set($library.'.'.$key, $value);
							//** ID: untuk Library/Pustaka Database | EN: For Database Library
							if ($library == 'database') {
								$this->db = new Database();
								if (class_exists('MVC'))
									MVC::setDB($this->db);
							}
							else //** ID: untuk Library/Pustaka lain | EN: For Other library
								$this->$library = new $clsLibrary();
						//** ID: Untuk Library/Pustaka tanpa parameter Kelas Kecik
						//** EN: For Library without parameter of Kecik CLass
						} elseif (isset($params['params'])) {
							$this->$library = new $clsLibrary($params['params']);

						}
					}

				}
			}
		}
		//-- ID: Akhir untuk memasukan library/pustaka secara dinamis
		//-- EN: End Load Dynamic Library

		spl_autoload_register(array($this, 'autoload'), true, true);

		self::$instance = $this;
	}

	public static function getInstance() {
		return self::$instance;
	}

	/**
	 * setCallable
	 * ID: untuk setting paramater fungsi pada get atau post
	 * EN: For setting function parameter at get or post
	 * @param array $args
	 **/
	private function setCallable($args) {
		$route = array_shift($args);
		$real_params = array();

		//Before Middleware
		if (is_array($args[0])) {
			$this->middleware['before'] = array_shift($args);
		}

		if (!is_callable($args[0])) {
			$controller = array_shift($args);
			$real_params['controller'] = $controller;
		}

		//if ($route == '/' && count( $this->route->_getParams() ) <= 0 ) {
		if (preg_match('/(^\\/$)|(^\\/(\\?(\\w|\\d|\\=|\\&|\\-|\\.|_|\\/){0,}){0,}$)/', $route.$this->route->getParamStr(), $matches, PREG_OFFSET_CAPTURE) ) {
			//$this->callable = array_pop($args);
			$this->callable = \Closure::bind(array_pop($args), $this, get_class());
			$this->routedStatus = TRUE;
		} else {
			$route_pattern = str_replace('/', '\\/', $route);
			//** ID: Konversi route kedalam pattern parameter optional
			//** EN: Convert route in optional parameter pattern
			$route_pattern = preg_replace('/\\\\\\/\\(:\\w+\\)/', '(\\/\\\\w+){0,}', $route_pattern, -1);
			//** ID: Konversi route kedalam pattern parameter wajib
			//** EN: Cover route in required parameter pattern
			$route_pattern = preg_replace('/:\\w+/', '([\\w+|\\=|\\-|\\_]){1,}', $route_pattern, -1);
			$route_pattern = str_replace('\\/\\w++', '(((\/){0,}\\w+){0,})', $route_pattern);
			
			if ($route != '/' && preg_match('/(^'.$route_pattern.'$)|((^'.$route_pattern.')+(\\?(\\w|\\d|\\=|\\&|\\-|\\.|_|\\/){0,}){0,}$)/', $this->route->getParamStr(), $matches, PREG_OFFSET_CAPTURE) ) {
				//$this->callable = array_pop($args);
				$this->callable = \Closure::bind(array_shift($args), $this, get_class());
				$this->routedStatus = TRUE;

				$p = explode('/', $route);

				while(list($key, $value) = each($p)) {
					
					if (substr(trim($value), -1) == '+') {
						if (isset($matches[2][0]) && !empty($matches[2][0]))
							$real_params[$value] = explode('/', substr($matches[2][0], 1));
						elseif (isset($matches[7][0]) && !empty($matches[7][0]))
							$real_params[$value] = explode('/', substr($matches[7][0]), 1);
						else
							$real_params[$value] = array();
					} elseif (substr(trim($value, '/'), 0, 1) == ':') {
						$getpos = (strpos($this->route->_getParams($key), '?') > 0)?strpos($this->route->_getParams($key), '?'):strlen($this->route->_getParams($key));
						$real_params[$value] = substr($this->route->_getParams($key), 0, $getpos);
					} elseif (substr(trim($value, '/'), 0, 2) == '(:' && substr(trim($value, '/'), -1, 1) == ')') {
						if ($this->route->_getParams($key) != NULL) {
							$getpos = (strpos($this->route->_getParams($key), '?') > 0)?strpos($this->route->_getParams($key), '?'):strlen($this->route->_getParams($key));
							$real_params[$value] = substr($this->route->_getParams($key), 0, $getpos);
						}
					}
				}
			}
			
		}
		
		Route::$_destination = $route;
		$this->route->setParams($real_params);

		//print_r($args);
		//After Middleware
		if (count($args) > 0 && is_array($args[0])) {
			$this->middleware['after'] = array_shift($args);
		}
	}

	/**
	 * get
	 * @param multi parameters
	 **/
	public function get() {
		$this->routedStatus = FALSE;
		if (!$this->route->isGet()) return $this;

		$this->middleware = ['before'=>array(), 'after'=>array()];
		if (is_callable($this->callable) ) {
			//$this->routedStatus = FALSE;
			return $this;
		}

		self::$fullrender = '';
		$args = func_get_args();
		if (!empty(self::$group)) $args[0] = self::$group.$args[0];
		
		$this->setCallable($args);
		return $this;
	}

	/**
	 * post
	 * @param multi paramaters
	 **/
	public function post() {
		$this->routedStatus = FALSE;
		if (!$this->route->isPost()) return $this;

		$this->middleware = ['before'=>array(), 'after'=>array()];
		if (is_callable($this->callable) ) {
			//$this->routedStatus = FALSE;
			return $this;
		}

		self::$fullrender = '';
		$args = func_get_args();
		if (!empty(self::$group)) $args[0] = self::$group.$args[0];

		$this->setCallable($args);
		return $this;
	}

	/**
	 * put
	 * @param multi paramaters
	 **/
	public function put() {
		$this->routedStatus = FALSE;
		if (!$this->route->isPut()) return $this;

		$this->middleware = ['before'=>array(), 'after'=>array()];
		if (is_callable($this->callable) ) {
			//$this->routedStatus = FALSE;
			return $this;
		}

		self::$fullrender = '';
		$args = func_get_args();
		if (!empty(self::$group)) $args[0] = self::$group.$args[0];
		$this->setCallable($args);
		return $this;
	}

	/**
	 * delete
	 * @param multi paramaters
	 **/
	public function delete() {
		$this->routedStatus = FALSE;
		if (!$this->route->isDelete()) return $this;

		$this->middleware = ['before'=>array(), 'after'=>array()];
		if (is_callable($this->callable) ) {
			//$this->routedStatus = FALSE;
			return $this;
		}

		self::$fullrender = '';
		$args = func_get_args();
		if (!empty(self::$group)) $args[0] = self::$group.$args[0];
		$this->setCallable($args);
		return $this;
	}

	/**
	 * patch
	 * @param multi paramaters
	 **/
	public function patch() {
		$this->routedStatus = FALSE;
		if (!$this->route->isPatch()) return $this;

		$this->middleware = ['before'=>array(), 'after'=>array()];
		if (is_callable($this->callable) ) {
			//$this->routedStatus = FALSE;
			return $this;
		}

		self::$fullrender = '';
		$args = func_get_args();
		if (!empty(self::$group)) $args[0] = self::$group.$args[0];
		$this->setCallable($args);
		return $this;
	}

	/**
	 * options
	 * @param multi paramaters
	 **/
	public function options() {
		$this->routedStatus = FALSE;
		if (!$this->route->isOptions()) return $this;

		$this->middleware = ['before'=>array(), 'after'=>array()];
		if (is_callable($this->callable) ) {
			//$this->routedStatus = FALSE;
			return $this;
		}

		self::$fullrender = '';
		$args = func_get_args();
		if (!empty(self::$group)) $args[0] = self::$group.$args[0];
		$this->setCallable($args);
		return $this;
	}

	public function group() {
		if (is_callable($this->callable) ) {
			//$this->routedStatus = FALSE;
			return $this;
		}

		self::$fullrender = '';
		$args = func_get_args();
		
		self::$group .= $args[0].'/';

		if (is_callable($args[1])) {
			$this->group_func = \Closure::bind($args[1], $this, get_class());
			call_user_func_array($this->group_func, array());
		}
		
		self::$group = '';	
	}
	/**
	 * template
	 * ID: Untuk menerapkan sebuah template
	 * EN: For implement of template
	 * @param string template
	 **/
	public function template($template, $replace=FALSE) {
		if ($this->routedStatus || $replace === TRUE) 
			self::$fullrender = $template;
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
				//** Run Middleware Before
				while(list($idx_mw, $middleware) = each($this->middleware['before']))
					$middleware($this, $this->request);

				ob_start();
				$response = call_user_func_array($this->callable, $this->route->getParams());
				$result = ob_get_clean();

				$response = (empty($response) || is_bool($response))? $result: $response.$result;
				if(count(self::$header) > 0 && php_sapi_name() != 'cli') {
					while(list($idx_header, $headerValue) = each(self::$header))
						header($headerValue);
				}
				
				//** Run Middleware After
				while(list($idx_mw, $middleware) = each($this->middleware['after']))
					$middleware($this, $this->request);
				
				//** Replace Tag
				ob_start();
					include $this->config->get('path.basepath').$this->config->get('path.template').'/'.self::$fullrender.'.php';
				self::$fullrender = ob_get_clean();

				$config = $this->config;
				self::$fullrender = preg_replace_callback(array(
					'/(\\\)?'.addslashes($this->config->get('template.open_tag')).'=?'.'/', 
					'/(\\\)?'.addslashes($this->config->get('template.close_tag')).'/'
				), function($s) use ($config) {
				    if (isset($s[0])) {
				        if (isset($s[1]) && $s[1] == '\\')
				            return substr($s[0], 1);
				        elseif ($s[0] == $this->config->get('template.open_tag'))
				            return '<?php ';
				        elseif ($s[0] == '{{=')
            				return '<?php echo ';
				        elseif ($s[0] == $this->config->get('template.close_tag'))
				            return '?>';
				    }
				}, self::$fullrender);

				self::$fullrender = str_replace(
					[
						'@js', 
						'@css'
					], 
					[
						$this->assets->js->render(), 
						$this->assets->css->render()
					], self::$fullrender);

				self::$fullrender = str_replace(['@yield', '@response'], [$response, $response], self::$fullrender);
				//-- END Replace Tag
				eval('?>'.self::$fullrender);

				//echo $result;
			} else {
				if(php_sapi_name() != 'cli')
					header($_SERVER["SERVER_PROTOCOL"].' '.Route::$HTTP_RESPONSE[404]);

				if ($this->config->get('error.404') != '') {
					include($this->config->get('path.template').'/'.$this->config->get('error.404').'.php');
				} else
					die(Route::$HTTP_RESPONSE[404]); 
			}
			self::$fullrender = '';
		} else {
			if (is_callable($this->callable)) {
				//** Run Middleware Before
				while(list($idx_mw, $middleware) = each($this->middleware['before']))
					$middleware($this, $this->request);

				ob_start();
				$response = call_user_func_array($this->callable, $this->route->getParams());
				$result = ob_get_clean();
				
				$response = (empty($response) || is_bool($response))? $result: $response.$result;
				if(count(self::$header) > 0 && php_sapi_name() != 'cli') {
					while(list($idx_header, $headerValue) = each(self::$header))
						header($headerValue);
				}

				//** Run Middleware After
				while(list($idx_mw, $middleware) = each($this->middleware['after'])) 
					$middleware($this, $this->request);
				
				echo $response;
				//echo $result;
			} else {
				if(php_sapi_name() != 'cli')
					header($_SERVER["SERVER_PROTOCOL"].' '.Route::$HTTP_RESPONSE[404]);
				
				if ($this->config->get('error.404') != '') {
					include($this->config->get('path.template').'/'.$this->config->get('error.404').'.php');
				} else
					die(Route::$HTTP_RESPONSE[404]); 
			}
		}
	}

	/**
	 * error
	 * ID: Untuk menampilkan error http response
	 * EN: For displaying error http response
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

	public function header($code=200) {
		if (!is_array($code)) $code = [$code];
		self::$header = array();
		while(list($key, $value) = each($code)) {
			if (is_int($value))
				self::$header[] = $_SERVER["SERVER_PROTOCOL"].' '.Route::$HTTP_RESPONSE[$value];
			else
				self::$header[] = $value;
		}
	}
}
?>