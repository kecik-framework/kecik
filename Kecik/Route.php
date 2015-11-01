<?php
/**
 * Route
 * 
 * @package     Kecik
 * @author      Dony Wahyu Isp
 * @since       1.0-alpha1
 **/
namespace Kecik;

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
    
    public static $_list = array();

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
            if (strpos($_SERVER['REQUEST_URI'], $index)) {
                $_SERVER['REQUEST_URI'] = substr($_SERVER['REQUEST_URI'], strpos($_SERVER['REQUEST_URI'], '/'.$index)+strlen($index)+1);
            }
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

    public function get() {
        return self::$_list;
    }

}

//Route::init();
//--