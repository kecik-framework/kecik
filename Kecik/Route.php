<?php
/**
 * Route
 *
 * @package     Kecik
 * @author      Dony Wahyu Isp
 * @since       1.0-alpha1
 **/
namespace Kecik;

/**
 * Class Route
 * @package Kecik
 */
class Route
{

    public static $ParamsStr = '';
    public static $destination = '';
    public static $params = array();
    public static $RealParams = array();
    public static $list = array();
    public static $BaseUrl;
    public static $BasePath;
    public static $protocol;

    /**
     * @var array
     */
    public static $HttpResponse = array(
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

    /**
     * Route constructor.
     */
    public function __construct()
    {

    }

    /**
     *
     */
    public static function init()
    {
        if (php_sapi_name() == 'cli') {
            self::$BasePath = str_replace('/', DIRECTORY_SEPARATOR, dirname(__FILE__) . '/');
        } else {
            self::$BasePath = str_replace('/', DIRECTORY_SEPARATOR, realpath(dirname(__FILE__)) . "/");
        }

        if (isset($_SERVER['HTTPS']) ||
            (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443) ||
            (isset($_SERVER['HTTP_X_FORWARDED_PORT']) && $_SERVER['HTTP_X_FORWARDED_PORT'] == 443)
        ) {
            self::$protocol = "https://";
        } else {
            self::$protocol = "http://";
        }

        $PathInfo = pathinfo($_SERVER['PHP_SELF']);

        $index = basename($_SERVER["SCRIPT_FILENAME"], '.php') . '.php';
        Config::set('index', $index);

        if (strpos($PathInfo['dirname'], '/' . $index) > 0) {
            $StrLimit = strpos($PathInfo['dirname'], '/' . $index);
        } elseif ($PathInfo['dirname'] == '/' . $index) {
            $StrLimit = 0;
        } else {
            $StrLimit = strlen($PathInfo['dirname']);
        }

        if (php_sapi_name() == 'cli-server') {
            self::$BaseUrl = self::$protocol . $_SERVER['HTTP_HOST'] . '/';
        } else if (php_sapi_name() == 'cli') {
            self::$params = $_SERVER['argv'];
            chdir(self::$BasePath);
            self::$BaseUrl = self::$BasePath;
        } else {

            //** ID: Terkadang terdapat masalah bagian base url, kamu dapat mengedit bagian ini. Biasanya masalah pada $PathInfo['dirname']
            //** EN: Sometimes have a problem in base url section, you can editi this section. normally at $PathInfo['dirname']
            self::$BaseUrl = self::$protocol . $_SERVER['HTTP_HOST'] . substr($PathInfo['dirname'], 0, $StrLimit);

            if (substr(self::$BaseUrl, -1, 1) != '/') {
                self::$BaseUrl .= '/';
            }

        }

        if (php_sapi_name() == 'cli') {
            $ResultSegment = $_SERVER['argv'];
            array_shift($ResultSegment);

            self::$params = $ResultSegment;
            self::$RealParams = self::$params;
            self::$ParamsStr = implode('/', $ResultSegment);
        } else {
            $path = str_replace(self::$protocol . $_SERVER['HTTP_HOST'] . '/', '', self::$BaseUrl);

            if (strpos($_SERVER['REQUEST_URI'], $index)) {
                $_SERVER['REQUEST_URI'] = substr(
                    $_SERVER['REQUEST_URI'],
                    strpos($_SERVER['REQUEST_URI'], '/' . $index) + strlen($index) + 1
                );
            }

            $path = str_replace($path, '', $_SERVER['REQUEST_URI']);

            if (substr($path, 0, 1) == '/') {
                $path = substr($path, 1);
            }

            $segments = explode('/', $path);

            if ($segments[count($segments) - 1] == '' && count($segments) > 1) {
                unset($segments[count($segments) - 1]);
            }

            $ResultSegment = array();

            while (list($key, $seg) = each($segments)) {

                if ($segments[$key] != $index && $seg != '') {
                    array_push($ResultSegment, urldecode($seg));
                }
            }

            self::$ParamsStr = implode('/', $ResultSegment);
            self::$params = $ResultSegment;
            self::$RealParams = self::$params;
        }

        unset($StrLimit);
        unset($PathInfo);

    }

    /**
     * @param int $key
     * @return array|null
     */
    public function _getParams($key = -1)
    {
        if ($key >= 0) {

            if (isset(self::$params[$key])) {
                return self::$params[$key];
            } else {
                return NULL;
            }

        } else {
            return self::$params;
        }
    }

    /**
     * @param int $key
     * @return array|null
     */
    public function getParams($key = -1)
    {
        if ($key >= 0) {

            if (isset(self::$RealParams[$key])) {
                return self::$RealParams[$key];
            } else {
                return NULL;
            }

        } else
            return self::$RealParams;
    }

    /**
     * @param $key
     * @param string $value
     */
    public function setParams($key, $value = '')
    {
        //if (!isset($this->params)) $this->params = array();

        if (is_array($key)) {
            self::$RealParams = $key;
        } else {
            self::$RealParams[$key] = $value;
        }

    }

    /**
     * @param $params
     */
    public function setParamStr($params)
    {
        self::$ParamsStr = $params;
    }

    /**
     * @return string
     */
    public function getParamStr()
    {
        return self::$ParamsStr;
    }

    /**
     * @param string $route
     * @return bool|string
     */
    public function is($route = '')
    {

        if ($route == '') {
            return self::$destination;
        } else {

            if (self::$destination == $route) {
                return TRUE;
            } else {
                return FALSE;
            }

        }

    }

    /**
     * @return bool
     */
    public function isPost()
    {
        if (isset($_SERVER['REQUEST_METHOD'])) {

            if ($_SERVER['REQUEST_METHOD'] == 'POST' && !isset($_POST['_METHOD'])) {
                return TRUE;
            } else {
                return FALSE;
            }

        } else {
            return FALSE;
        }
    }

    /**
     * @return bool
     */
    public function isGet()
    {
        if (isset($_SERVER['REQUEST_METHOD'])) {

            if ($_SERVER['REQUEST_METHOD'] == 'GET' && !isset($_POST['_METHOD'])) {
                return TRUE;
            } else {
                return FALSE;
            }

        } else {
            return TRUE;
        }
    }

    /**
     * @return bool
     */
    public function isPut()
    {
        if (isset($_SERVER['REQUEST_METHOD'])) {

            if ($_SERVER['REQUEST_METHOD'] == 'PUT' || (isset($_POST['_METHOD']) && $_POST['_METHOD'] == 'PUT')) {
                parse_str(file_get_contents("php://input"), $vars);

                if (isset($vars['_METHOD'])) {
                    unset($vars['_METHOD']);
                }

                $GLOBALS['_PUT'] = $_PUT = $vars;
                return TRUE;
            } else {
                return FALSE;
            }
        } else
            return FALSE;


    }

    /**
     * @return bool
     */
    public function isDelete()
    {
        if (isset($_SERVER['REQUEST_METHOD'])) {

            if ($_SERVER['REQUEST_METHOD'] == 'DELETE' || (isset($_POST['_METHOD']) && $_POST['_METHOD'] == 'DELETE')) {
                parse_str(file_get_contents("php://input"), $vars);

                if (isset($vars['_METHOD'])) {
                    unset($vars['_METHOD']);
                }

                $GLOBALS['_DELETE'] = $_DELETE = $vars;
                return TRUE;

            } else {
                return FALSE;
            }

        } else {
            return FALSE;
        }
    }

    /**
     * @return bool
     */
    public function isPatch()
    {
        if (isset($_SERVER['REQUEST_METHOD'])) {

            if ($_SERVER['REQUEST_METHOD'] == 'PATCH' || (isset($_POST['_METHOD']) && $_POST['_METHOD'] == 'PATCH')) {
                parse_str(file_get_contents("php://input"), $vars);

                if (isset($vars['_METHOD'])) {
                    unset($vars['_METHOD']);
                }

                $GLOBALS['_PATCH'] = $_PATCH = $vars;
                return TRUE;
            } else {
                return FALSE;
            }

        } else {
            return FALSE;
        }
    }

    /**
     * @return bool
     */
    public function isOptions()
    {
        if (isset($_SERVER['REQUEST_METHOD'])) {

            if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS' || (isset($_POST['_METHOD']) && $_POST['_METHOD'] == 'OPTIONS')) {
                parse_str(file_get_contents("php://input"), $vars);

                if (isset($vars['_METHOD'])) {
                    unset($vars['_METHOD']);
                }

                $GLOBALS['_OPTIONS'] = $_OPTIONS = $vars;
                return TRUE;
            } else {
                return FALSE;
            }

        } else {
            return FALSE;
        }
    }

    /**
     * @return bool
     */
    public function isAjax()
    {
        if (filter_input(INPUT_SERVER, 'HTTP_X_REQUESTED_WITH') === 'xmlhttprequest') {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /**
     * @return array
     */
    public function get()
    {
        return self::$list;
    }

}

//Route::init();
//--