<?php
/*///////////////////////////////////////////////////////////////
 /** ID: | /-- ID: Indonesia
 /** EN: | /-- EN: English
 ///////////////////////////////////////////////////////////////*/
/**
 * ID: Kecik Framework - Sebuah Framework dengan satu file system
 * EN: Kecik Framework - The Framework with single file system
 *
 * @author      Dony Wahyu Isp
 * @copyright   2015 Dony Wahyu Isp
 * @link        http://github.com/kecik-framework/kecik
 * @license     MIT
 * @version     2.0.1
 * @package     Kecik
 *
 **/
namespace Kecik;

/**
 * Load All require
 */
use Closure;

require_once "Controller.php";
require_once "Model.php";
require_once "Config.php";
require_once "Route.php";
require_once "Url.php";
require_once "Request.php";
require_once "Assets.php";
/**
 * End Load require
 */

/**
 * Class Kecik
 * @package Kecik
 */
class Kecik
{

    public static $version = "2.0.1";
    public $route, $url, $config, $assets, $request;

    private $callable;
    private $middleware = array('before' => array(), 'after' => array());
    private $RoutedStatus = FALSE;

    private static $FullRender = '';
    private static $header = array();
    private static $group = '';

    private $GroupFunc;
    private $LibrariesEnabled = array();

    private static $instance = null;

    private $before, $after;

    /**
     * @param $class
     */
    public function autoload($class)
    {
        $ClassArray = explode('\\', $class);
        if (count($ClassArray) > 1) {
            if (php_sapi_name() == 'cli') {
                $MvcPath = $this->config->get('path.basepath') . $this->config->get('path.mvc');
            } else {
                $MvcPath = $this->config->get('path.mvc');
            }
            //** if count $ClassArray = 3 is HMVC
            if (count($ClassArray) >= 3) {
                $HmvcPath = '';
                for ($i = 0; $i < count($ClassArray) - 2; $i++) {
                    $HmvcPath .= $ClassArray[$i] . '/';
                }
                //**                          Module                   Controllers/Models                           Class
                $FileLoad = $MvcPath . '/' . $HmvcPath . $ClassArray[count($ClassArray) - 2] . '/' . $ClassArray[count($ClassArray) - 1] . '.php';
            } else {
                //**                          Controller/Models              Class
                $FileLoad = $MvcPath . '/' . $ClassArray[0] . '/' . $ClassArray[1] . '.php';
            }
            if (file_exists($FileLoad)) {
                include $FileLoad;
            }

        }

    }

    /**
     * Kecik constructor.
     *
     * @param array $config
     */
    public function __construct($config = array())
    {
        self::$instance = $this;
        //** Config
        $this->config = new Config();
        if (is_array($config) && count($config)) {
            while (list($key, $value) = each($config)) {
                $this->config->set($key, $value);
            }
        }
        //-- End Config
        if ($this->config->get('path.basepath') == '') {
            $this->config->set('path.basepath', getcwd() . '/');
        }
        if (isset($_SERVER['SERVER_PROTOCOL'])) {
            self::$header[] = $_SERVER['SERVER_PROTOCOL'] . ' ' . Route::$HttpResponse[200];
        }
        $this->route = new Route();
        Route::init();
        Route::$BasePath = $this->config->get('path.basepath');
        $this->url = new Url(Route::$protocol, Route::$BaseUrl, Route::$BasePath);
        $this->assets = new Assets($this->url);
        $this->request = new Request();
        //** ID: Memasukan Libary/Pustaka berdasarkan config | EN: Load Dynamic Libraries from config
        $libraries = $this->config->get('libraries');
        if (is_array($libraries) && count($libraries) > 0) {
            while (list($library, $params) = each($libraries)) {
                $ClsLibrary = 'Kecik\\' . $library;
                if (class_exists($ClsLibrary)) {
                    if (isset($params['enable']) && $params['enable'] === TRUE) {
                        $library = strtolower($library);
                        //** ID: Untuk Library/Pustaka tanpa parameter
                        //** EN: For Library without parameter
                        if (!isset($params['config']) && !isset($params['params'])) {
                            //** ID: Untuk Library/Pustaka DIC | EN: For DIC Library
                            if ($library == 'dic') {
                                $this->container = new DIC();
                                $this->LibrariesEnabled[] = array('DIC', 'container');
                            } elseif ($library == 'mvc') {
                                $this->LibrariesEnabled[] = array('MVC');
                                if (isset($this->db)) {
                                    MVC::setDB($this->db);
                                }
                            } else { // ID: Untuk Library/Pustaka lain | EN: Other Library
                                $this->$library = new $ClsLibrary();
                                $this->LibrariesEnabled[] = array($library, $library);
                            }
                            //** ID: Untuk Library/Pustaka dengan parameter Kelas Kecik
                            //** EN: For Library with parameter of Kecik CLass
                        } elseif (isset($params['config'])) {
                            //** ID: Buat variabel config
                            //** EN: Create config variable
                            while (list($key, $value) = each($params['config'])) {
                                $this->config->set($library . '.' . $key, $value);
                            }
                            //** ID: untuk Library/Pustaka Database | EN: For Database Library
                            if ($library == 'database') {
                                $this->db = new Database();
                                $this->LibrariesEnabled[] = array('Database', 'db');
                                if (class_exists('MVC')) {
                                    MVC::setDB($this->db);
                                }
                            } else { //** ID: untuk Library/Pustaka lain | EN: For Other library
                                $this->$library = new $ClsLibrary();
                                $this->LibrariesEnabled[] = array($library, $library);
                            }
                            //** ID: Untuk Library/Pustaka tanpa parameter Kelas Kecik
                            //** EN: For Library without parameter of Kecik CLass
                        } elseif (isset($params['params'])) {
                            $this->$library = new $ClsLibrary($params['params']);
                            $this->LibrariesEnabled[] = array($library, $library);
                        }

                    }

                }

            }

        }
        //-- ID: Akhir untuk memasukan library/pustaka secara dinamis
        //-- EN: End Load Dynamic Library
        spl_autoload_register(array($this, 'autoload'), TRUE, TRUE);

    }

    /**
     * @return Kecik|null
     */
    public static function getInstance()
    {
        return self::$instance;
    }

    /**
     * @param $args
     */
    private function setCallable($args)
    {
        $route = array_shift($args);
        $RealParams = array();
        //Before Middleware
        if (is_array($args[0]) && isset($args[0][0])) {
            $this->middleware['before'] = array_shift($args);
        }
        if (!is_callable($args[0]) && !is_string($args[0]) && !is_array($args[0])) {
            $controller = array_shift($args);
            $RealParams['controller'] = $controller;
        }
        //if ($route == '/' && count( $this->route->_getParams() ) <= 0 ) {
        if (preg_match(
            '/(^\\/$)|(^\\/(\\?(\\w|\\d|\\=|\\&|\\-|\\.|_|\\/){0,}){0,}$)/',
            $route . $this->route->getParamStr(),
            $matches, PREG_OFFSET_CAPTURE
        )) {
            //$this->callable = array_pop($args);
            $callFunc = array_pop($args);
            $params = array();
            if (is_array($callFunc)) {
                $keys = array_keys($callFunc);
                $params = $callFunc[$keys[0]];
                if (!is_array($params)) {
                    $params = array($params);
                }
                $callFunc = $keys[0];
            }
            $callFunc = $this->createCallbackFromString($callFunc, $params);
            $this->callable = Closure::bind($callFunc, $this, get_class());
            $this->RoutedStatus = TRUE;
        } else {
            $RoutePattern = str_replace('/', '\\/', $route);
            //** ID: Konversi route kedalam pattern parameter optional
            //** EN: Convert route in optional parameter pattern
            $RoutePattern = preg_replace('/\\\\\\/\\(:\\w+\\)/', '(\\/\\\\w+){0,}', $RoutePattern, -1);
            //** ID: Konversi route kedalam pattern parameter wajib
            //** EN: Cover route in required parameter pattern
            $RoutePattern = preg_replace('/:\\w+/', '([\\w+|\\=|\\-|\\_]){1,}', $RoutePattern, -1);
            $RoutePattern = str_replace('\\/\\w++', '(((\/){0,}\\w+){0,})', $RoutePattern);
            if ($route != '/' &&
                preg_match(
                    '/(^' . $RoutePattern . '$)|((^' . $RoutePattern . ')+(\\?(\\w|\\d|\\=|\\&|\\-|\\.|_|\\/){0,}){0,}$)/',
                    $this->route->getParamStr(),
                    $matches,
                    PREG_OFFSET_CAPTURE
                )
            ) {
                $callFunc = array_shift($args);
                $params = array();
                if (is_array($callFunc)) {
                    $keys = array_keys($callFunc);
                    $params = $callFunc[$keys[0]];
                    if (!is_array($params)) {
                        $params = array($params);
                    }
                    $callFunc = $keys[0];
                }
                $callFunc = $this->createCallbackFromString($callFunc, $params);
                //$this->callable = array_pop($args);
                $this->callable = Closure::bind($callFunc, $this, get_class());
                $this->RoutedStatus = TRUE;
                $p = explode('/', $route);
                while (list($key, $value) = each($p)) {
                    if (substr(trim($value), -1) == '+') {
                        if (isset($matches[2][0]) && !empty($matches[2][0])) {
                            $RealParams[$value] = explode('/', substr($matches[2][0], 1));
                        } elseif (isset($matches[7][0]) && !empty($matches[7][0])) {
                            $RealParams[$value] = explode('/', substr($matches[7][0]), 1);
                        } else {
                            $RealParams[$value] = array();
                        }

                    } elseif (substr(trim($value, '/'), 0, 1) == ':') {
                        $getpos = (strpos($this->route->_getParams($key), '?') > 0) ? strpos($this->route->_getParams($key), '?') : strlen($this->route->_getParams($key));
                        $RealParams[$value] = substr($this->route->_getParams($key), 0, $getpos);
                    } elseif (substr(trim($value, '/'), 0, 2) == '(:' && substr(trim($value, '/'), -1, 1) == ')') {
                        if ($this->route->_getParams($key) != null) {
                            $getpos = (strpos($this->route->_getParams($key), '?') > 0) ? strpos($this->route->_getParams($key), '?') : strlen($this->route->_getParams($key));
                            $RealParams[$value] = substr($this->route->_getParams($key), 0, $getpos);
                        }

                    }

                }

            }

        }
        Route::$destination = $route;
        $this->route->setParams($RealParams);
        //print_r($args);
        //After Middleware
        if (count($args) > 0 && is_array($args[0])) {
            $this->middleware['after'] = array_shift($args);
        }
    }

    /**
     * @return $this
     */
    public function get()
    {
        $this->RoutedStatus = FALSE;
        if (!$this->route->isGet()) {
            return $this;
        }
        if (is_callable($this->callable)) {
            //$this->RoutedStatus = FALSE;
            return $this;
        }
        $this->middleware = array('before' => array(), 'after' => array());
        self::$FullRender = '';
        $args = func_get_args();
        if (!empty(self::$group)) {
            if ($args[0] == '/') {
                $args[0] = substr(self::$group, 0, -1);
            } else {
                $args[0] = self::$group . $args[0];
            }

        }
        array_push(Route::$list, $args[0]);
        $this->setCallable($args);

        return $this;
    }

    /**
     * @return $this
     */
    public function post()
    {
        $this->RoutedStatus = FALSE;
        if (!$this->route->isPost()) {
            return $this;
        }
        if (is_callable($this->callable)) {
            //$this->RoutedStatus = FALSE;
            return $this;
        }
        $this->middleware = array('before' => array(), 'after' => array());
        self::$FullRender = '';
        $args = func_get_args();
        if (!empty(self::$group)) {
            if ($args[0] == '/') {
                $args[0] = substr(self::$group, 0, -1);
            } else {
                $args[0] = self::$group . $args[0];
            }

        }
        array_push(Route::$list, $args[0]);
        $this->setCallable($args);

        return $this;
    }

    /**
     * @return $this
     */
    public function put()
    {
        $this->RoutedStatus = FALSE;
        if (!$this->route->isPut()) {
            return $this;
        }
        if (is_callable($this->callable)) {
            //$this->RoutedStatus = FALSE;
            return $this;
        }
        $this->middleware = array('before' => array(), 'after' => array());
        self::$FullRender = '';
        $args = func_get_args();
        if (!empty(self::$group)) {
            if ($args[0] == '/') {
                $args[0] = substr(self::$group, 0, -1);
            } else {
                $args[0] = self::$group . $args[0];
            }

        }
        array_push(Route::$list, $args[0]);
        $this->setCallable($args);

        return $this;
    }

    /**
     * @return $this
     */
    public function delete()
    {
        $this->RoutedStatus = FALSE;
        if (!$this->route->isDelete()) {
            return $this;
        }
        if (is_callable($this->callable)) {
            //$this->RoutedStatus = FALSE;
            return $this;
        }
        $this->middleware = array('before' => array(), 'after' => array());
        self::$FullRender = '';
        $args = func_get_args();
        if (!empty(self::$group)) {
            if ($args[0] == '/') {
                $args[0] = substr(self::$group, 0, -1);
            } else {
                $args[0] = self::$group . $args[0];
            }

        }
        array_push(Route::$list, $args[0]);
        $this->setCallable($args);

        return $this;
    }

    /**
     * @return $this
     */
    public function patch()
    {
        $this->RoutedStatus = FALSE;
        if (!$this->route->isPatch()) {
            return $this;
        }
        if (is_callable($this->callable)) {
            //$this->RoutedStatus = FALSE;
            return $this;
        }
        $this->middleware = array('before' => array(), 'after' => array());
        self::$FullRender = '';
        $args = func_get_args();
        if (!empty(self::$group)) {
            if ($args[0] == '/') {
                $args[0] = substr(self::$group, 0, -1);
            } else {
                $args[0] = self::$group . $args[0];
            }

        }
        array_push(Route::$list, $args[0]);
        $this->setCallable($args);

        return $this;
    }

    /**
     * @return $this
     */
    public function options()
    {
        $this->RoutedStatus = FALSE;
        if (!$this->route->isOptions()) {
            return $this;
        }
        if (is_callable($this->callable)) {
            //$this->RoutedStatus = FALSE;
            return $this;
        }
        $this->middleware = array('before' => array(), 'after' => array());
        self::$FullRender = '';
        $args = func_get_args();
        if (!empty(self::$group)) {
            if ($args[0] == '/') {
                $args[0] = substr(self::$group, 0, -1);
            } else {
                $args[0] = self::$group . $args[0];
            }

        }
        array_push(Route::$list, $args[0]);
        $this->setCallable($args);

        return $this;
    }

    /**
     * @return $this
     */
    public function group()
    {
        if (is_callable($this->callable)) {
            //$this->RoutedStatus = FALSE;
            return $this;
        }
        self::$FullRender = '';
        $args = func_get_args();
        self::$group .= $args[0] . '/';
        if (is_callable($args[1])) {
            $this->GroupFunc = Closure::bind($args[1], $this, get_class());
            call_user_func_array($this->GroupFunc, array());
        }
        self::$group = '';
    }

    public function pattern($patern)
    {

    }

    /**
     * @param Closure $callback
     */
    public function before(Closure $callback)
    {
        $this->before = $callback;
    }

    /**
     * @param Closure $callback
     */
    public function after(Closure $callback)
    {
        $this->after = $callback;
    }

    /**
     * @param      $template
     * @param bool $replace
     *
     * @return $this
     */
    public function template($template, $replace = FALSE)
    {
        if ($this->RoutedStatus || $replace === TRUE) {
            self::$FullRender = $template;
        }

        return $this;
    }

    /**
     * @param null $yield
     *
     * @return bool
     */
    public function run($yield = null)
    {
        if (php_sapi_name() == 'cli-server') {
            if (is_file(route::$BasePath . str_replace('/', DIRECTORY_SEPARATOR, $_SERVER['REQUEST_URI'])) &&
                file_exists(route::$BasePath . str_replace('/', DIRECTORY_SEPARATOR, $_SERVER['REQUEST_URI'])) &&
                substr(strtolower($_SERVER['REQUEST_URI']), -4) != '.php'
            ) {
                readfile(route::$BasePath . str_replace('/', DIRECTORY_SEPARATOR, $_SERVER['REQUEST_URI']));

                return TRUE;
            }

        }
        if (is_callable($yield)) {
            $this->callable = Closure::bind($yield, $this, get_class());
        }
        if (self::$FullRender != '') {
            if (is_callable($this->callable)) {
                if (is_callable($this->before)) {
                    $before = $this->before;
                    $before($this->request);
                }
                //** Run Middleware Before
                while (list($idx_mw, $middleware) = each($this->middleware['before'])) {
                    $middleware($this, $this->request);
                }
                ob_start();
                $response = call_user_func_array($this->callable, $this->route->getParams());
                if (!is_string($response) && !is_numeric($response)) {
                    $response = json_encode($response);
                }
                $result = ob_get_clean();
                if (is_callable($this->after)) {
                    $after = $this->after;
                    $response = $after($this->request, $response);
                }
                $response = (empty($response) || is_bool($response)) ? $result : $response . $result;
                if (count(self::$header) > 0 && php_sapi_name() != 'cli') {
                    while (list($idx_header, $headerValue) = each(self::$header)) {
                        header($headerValue);
                    }

                }
                //** Run Middleware After
                while (list($idx_mw, $middleware) = each($this->middleware['after'])) {
                    $middleware($this, $this->request);
                }
                //** Replace Tag
                echo self::$FullRender = $this->render(
                    $this->config->get('path.basepath') . $this->config->get('path.template') . '/' .
                    self::$FullRender . '.php',
                    $response
                );

            } else {
                if (php_sapi_name() != 'cli') {
                    header($_SERVER["SERVER_PROTOCOL"] . ' ' . Route::$HttpResponse[404]);
                }
                if ($this->config->get('error.404') != '') {
                    include $this->config->get('path.template') . '/' . $this->config->get('error.404') . '.php';
                } else {
                    die(Route::$HttpResponse[404]);
                }

            }
            self::$FullRender = '';
        } else {
            if (is_callable($this->callable)) {
                if (is_callable($this->before)) {
                    $before = $this->before;
                    $before($this->request);
                }
                //** Run Middleware Before
                while (list($idx_mw, $middleware) = each($this->middleware['before'])) {
                    $middleware($this, $this->request);
                }
                ob_start();
                $response = call_user_func_array($this->callable, $this->route->getParams());
                if (!is_string($response) && !is_numeric($response)) {
                    $response = json_encode($response);
                }
                $result = ob_get_clean();
                if (is_callable($this->after)) {
                    $after = $this->after;
                    $response = $after($this->request, $response);
                }
                $response = (empty($response) || is_bool($response)) ? $result : $response . $result;
                if (count(self::$header) > 0 && php_sapi_name() != 'cli') {
                    while (list($idx_header, $headerValue) = each(self::$header)) {
                        header($headerValue);
                    }

                }
                //** Run Middleware After
                while (list($idx_mw, $middleware) = each($this->middleware['after'])) {
                    $middleware($this, $this->request);
                }
                echo $response;
                //echo $result;
            } else {
                if (php_sapi_name() != 'cli') {
                    header($_SERVER["SERVER_PROTOCOL"] . ' ' . Route::$HttpResponse[404]);
                }
                if ($this->config->get('error.404') != '') {
                    echo $this->render($this->config->get('path.template') . '/' . $this->config->get('error.404') . '.php');
                } else {
                    die(Route::$HttpResponse[404]);
                }

            }

        }

    }

    /**
     * @param $code
     */
    public function error($code)
    {
        header($_SERVER["SERVER_PROTOCOL"] . Route::$HttpResponse[$code]);
        if ($this->config->get("error.$code") != '') {
            echo $this->render($this->config->get('path.template') . '/' . $this->config->get("error.$code") . '.php');
        } else {
            die(Route::$HttpResponse[$code]);
        }
    }

    public function stop()
    {
        exit();
    }

    /**
     * @param int $code
     */
    public function header($code = 200)
    {
        if (!is_array($code)) {
            $code = [$code];
        }
        self::$header = array();
        while (list($key, $value) = each($code)) {
            if (is_int($value)) {
                self::$header[] = $_SERVER["SERVER_PROTOCOL"] . ' ' . Route::$HttpResponse[$value];
            } else {
                self::$header[] = $value;
            }

        }
    }

    /**
     * @return array
     */
    public function getLibrariesEnabled()
    {
        return $this->LibrariesEnabled;
    }

    /**
     * @param        $file
     * @param string $response
     *
     * @return string
     */
    public function render($file, $response = "")
    {
        ob_start();
        include $file;
        self::$FullRender = ob_get_clean();
        $config = $this->config;
        self::$FullRender = preg_replace_callback(
            array(
                '/(\\\)?' . addslashes($this->config->get('template.open_tag')) . '=?' . '/',
                '/(\\\)?' . addslashes($this->config->get('template.close_tag')) . '/'
            ),
            function ($s) use ($config) {
                if (isset($s[0])) {
                    if (isset($s[1]) && $s[1] == '\\') {
                        return substr($s[0], 1);
                    } elseif ($s[0] == $this->config->get('template.open_tag')) {
                        return '<?php ';
                    } elseif ($s[0] == '{{=') {
                        return '<?php echo ';
                    } elseif ($s[0] == $this->config->get('template.close_tag')) {
                        return '?>';
                    }

                }
            },
            self::$FullRender
        );
        self::$FullRender = str_replace(
            array('@js', '@css'),
            array($this->assets->js->render(), $this->assets->css->render()),
            self::$FullRender
        );
        if (!empty($response)) {
            self::$FullRender = str_replace(
                array('@yield', '@response'),
                array($response, $response),
                self::$FullRender
            );
        }
        //-- END Replace Tag
        ob_start();
        eval('?>' . self::$FullRender);
        self::$FullRender = ob_get_clean();

        return self::$FullRender;
    }

    /**
     * @param $callFunc
     * @param $params
     *
     * @return Closure
     */
    private function createCallbackFromString($callFunc, $params)
    {
        if (is_string($callFunc)) {
            $callFunc = function () use ($callFunc, $params) {
                $controllerParts = explode('@', $callFunc);
                $controllerParts[0] = explode('\\', $controllerParts[0]);
                $hmvc = '';
                $controllerPaths = count($controllerParts[0]);
                if ($controllerPaths > 1) {
                    foreach ($controllerParts[0] as $idx => $controllerPart) {
                        if ($idx == ($controllerPaths - 1)) {
                            break;
                        }
                        $hmvc .= '\\' . $controllerPart;
                    }

                }
                $controller = $hmvc . '\Controllers\\' . $controllerParts[0][$controllerPaths - 1];
                $c = new \ReflectionClass($controller);
                $c = $c->newInstanceArgs($params);

                return call_user_func_array(array($c, $controllerParts[1]), $this->route->getParams());
            };

            return $callFunc;

        }

        return $callFunc;
    }
}
