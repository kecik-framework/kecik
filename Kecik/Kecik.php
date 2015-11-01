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
 **/

namespace Kecik;

/**
 * Load All require
 */
require_once("Controller.php");
require_once("Model.php");
require_once("Config.php");
require_once("Route.php");
require_once("Url.php");
require_once("Request.php");
require_once("Assets.php");
/**
 * End Load require
 */

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
	private $middleware = array('before'=>array(), 'after'=>array());
	/**
	 * @var Bool $routedStatus
	 **/
	private $routedStatus = FALSE;

	/**
	 * @var string $fullrender
	 **/
	private static $fullrender = '';

	/**
	 * @var array $header
	 */
	private static $header = array();

	/**
	 * @var string $group routing group
	 */
	private static $group = '';
	/**
	 * @var Closure $group_func
	 */
	private $group_func;

	/**
	 * @var array $libraries_enabled all loaded libraries
	 */
	private $libraries_enabled = array();

	/**
	 * @var null
	 */
	private static $instance = null;

	/**
	 * @var Closure $before, $after aplication event
	 */
	private $before, $after;

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
		self::$instance = $this;
		
		//** Config
		$this->config = new Config();

		if (is_array($config) && count($config)) {
			while(list($key, $value) = each($config))
				$this->config->set($key, $value);
		}
		//-- End Config

		if ($this->config->get('path.basepath') == '')
			$this->config->set('path.basepath', getcwd().'/');
		
		if (isset($_SERVER["SERVER_PROTOCOL"]))
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
							if ($library == 'dic') {
								$this->container = new DIC();
								$this->libraries_enabled[] = array('DIC','container');
							} elseif ($library == 'mvc') {
								$this->libraries_enabled[] = array('MVC');
								if (isset($this->db))
									MVC::setDB($this->db);
							} else { // ID: Untuk Library/Pustaka lain | EN: Other Library
								$this->$library = new $clsLibrary();
								$this->libraries_enabled[] = array($library, $library);
							}
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
								$this->libraries_enabled[] = array('Database', 'db');
								if (class_exists('MVC'))
									MVC::setDB($this->db);
							}
							else { //** ID: untuk Library/Pustaka lain | EN: For Other library
								$this->$library = new $clsLibrary();
								$this->libraries_enabled[] = array($library, $library);
							}
						//** ID: Untuk Library/Pustaka tanpa parameter Kelas Kecik
						//** EN: For Library without parameter of Kecik CLass
						} elseif (isset($params['params'])) {
							$this->$library = new $clsLibrary($params['params']);
							$this->libraries_enabled[] = array($library, $library);
						}
					}

				}
			}
		}
		//-- ID: Akhir untuk memasukan library/pustaka secara dinamis
		//-- EN: End Load Dynamic Library

		spl_autoload_register(array($this, 'autoload'), true, true);

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
		if (!empty(self::$group)) {
			$args[0] = ($args[0] == '/')?'':$args[0];
			$args[0] = self::$group.$args[0];
		}
		
		array_push(Route::$_list, $args[0]);
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
		if (!empty(self::$group)) {
			$args[0] = ($args[0] == '/')?'':$args[0];
			$args[0] = self::$group.$args[0];
		}

		array_push(Route::$_list, $args[0]);
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
		if (!empty(self::$group)) {
			$args[0] = ($args[0] == '/')?'':$args[0];
			$args[0] = self::$group.$args[0];
		}

		array_push(Route::$_list, $args[0]);
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
		if (!empty(self::$group)) {
			$args[0] = ($args[0] == '/')?'':$args[0];
			$args[0] = self::$group.$args[0];
		}

		array_push(Route::$_list, $args[0]);
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
		if (!empty(self::$group)) {
			$args[0] = ($args[0] == '/')?'':$args[0];
			$args[0] = self::$group.$args[0];
		}

		array_push(Route::$_list, $args[0]);
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
		if (!empty(self::$group)) {
			$args[0] = ($args[0] == '/')?'':$args[0];
			$args[0] = self::$group.$args[0];
		}

		array_push(Route::$_list, $args[0]);
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


	public function pattern($patern) {

	}


	public function before(\Closure $callback) {
		$this->before = $callback;
	}

	public function after(\Closure $callback) {
		$this->after = $callback;
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

		return $this;
	}

	/**
	 * run
	 **/
	public function run($yield=null) {
		if (php_sapi_name() == 'cli-server')  {
			if( is_file(route::$BASEPATH.str_replace('/', DIRECTORY_SEPARATOR, $_SERVER['REQUEST_URI'])) && file_exists( route::$BASEPATH.str_replace('/', DIRECTORY_SEPARATOR, $_SERVER['REQUEST_URI'] ) ) && substr(strtolower($_SERVER['REQUEST_URI']), -4) != '.php' ) {
				readfile(route::$BASEPATH.str_replace('/', DIRECTORY_SEPARATOR, $_SERVER['REQUEST_URI'] ));
				return TRUE;
			}
		}
		
		if (is_callable($yield)) {
			$this->callable = \Closure::bind($yield, $this, get_class());
		}

		if (self::$fullrender != '') {
			if (is_callable($this->callable)) {
				if (is_callable($this->before)) {
					$before = $this->before;
					$before($this->request);
				}

				//** Run Middleware Before
				while(list($idx_mw, $middleware) = each($this->middleware['before']))
					$middleware($this, $this->request);

				ob_start();
				$response = call_user_func_array($this->callable, $this->route->getParams());
				$result = ob_get_clean();

				if ( is_callable($this->after) ) {
					$after = $this->after;
					$response = $after($this->request, $response);
				}

				$response = (empty($response) || is_bool($response))? $result: $response.$result;
				if(count(self::$header) > 0 && php_sapi_name() != 'cli') {
					while(list($idx_header, $headerValue) = each(self::$header))
						header($headerValue);
				}
				
				//** Run Middleware After
				while(list($idx_mw, $middleware) = each($this->middleware['after']))
					$middleware($this, $this->request);
				
				//** Replace Tag
				echo self::$fullrender = $this->render($this->config->get('path.basepath').$this->config->get('path.template').'/'.self::$fullrender.'.php', $response);
				/*ob_start();
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
				*/
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
				if (is_callable($this->before)) {
					$before = $this->before;
					$before($this->request);
				}

				//** Run Middleware Before
				while(list($idx_mw, $middleware) = each($this->middleware['before']))
					$middleware($this, $this->request);

				ob_start();
				$response = call_user_func_array($this->callable, $this->route->getParams());
				$result = ob_get_clean();

				if ( is_callable($this->after) ) {
					$after = $this->after;
					$response = $after($this->request, $response);
				}

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
					echo $this->render($this->config->get('path.template').'/'.$this->config->get('error.404').'.php');
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
			echo $this->render($this->config->get('path.template').'/'.$this->config->get("error.$code").'.php');
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

	public function getLibrariesEnabled() {
		return $this->libraries_enabled;
	}

	public function render($file, $response="") {
		ob_start();
			include($file);
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

		if (!empty($response))
			self::$fullrender = str_replace(['@yield', '@response'], [$response, $response], self::$fullrender);
		//-- END Replace Tag
		ob_start();
			eval('?>'.self::$fullrender);
		self::$fullrender = ob_get_clean();
		return self::$fullrender;
	}
}
?>