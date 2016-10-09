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
 *
 * @package Kecik
 */
class Route
{
    private static $instance = NULL;

    private static $paramsStr    = '';
    private static $queryStr     = '';
    private static $destination  = '';
    private static $params       = [];
    private static $realParams   = [];
    public static  $list         = [];
    private static $routedStatus = FALSE;
    private static $group        = [];
    private static $groupLevel   = 0;

    private static $callable = NULL;

    /**
     * @var array
     */
    public static $HttpResponse
        = [
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
        if ( is_null(self::$instance) ) {
            self::$instance = new self;
        }

        if ( php_sapi_name() == 'cli' ) {
            $basePath = str_replace('/', DIRECTORY_SEPARATOR, dirname(__FILE__) . '/');
        } else {
            $basePath = str_replace('/', DIRECTORY_SEPARATOR, realpath(dirname(__FILE__)) . "/");
        }

        if ( isset( $_SERVER['HTTPS'] ) ||
             ( isset( $_SERVER['SERVER_PORT'] ) && $_SERVER['SERVER_PORT'] == 443 ) ||
             ( isset( $_SERVER['HTTP_X_FORWARDED_PORT'] ) && $_SERVER['HTTP_X_FORWARDED_PORT'] == 443 )
        ) {
            $protocol = "https://";
        } else {
            $protocol = "http://";
        }

        $PathInfo = pathinfo($_SERVER['PHP_SELF']);

        $index = basename($_SERVER["SCRIPT_FILENAME"], '.php') . '.php';
        Config::set('index', $index);

        if ( strpos($PathInfo['dirname'], '/' . $index) > 0 ) {
            $StrLimit = strpos($PathInfo['dirname'], '/' . $index);
        } elseif ( $PathInfo['dirname'] == '/' . $index ) {
            $StrLimit = 0;
        } else {
            $StrLimit = strlen($PathInfo['dirname']);
        }

        if ( php_sapi_name() == 'cli-server' ) {
            $baseUrl = $protocol . $_SERVER['HTTP_HOST'] . '/';
        } else if ( php_sapi_name() == 'cli' ) {
            self::$params = $_SERVER['argv'];
            chdir($basePath);
            $baseUrl = $basePath;
        } else {

            //** ID: Terkadang terdapat masalah bagian base url, kamu dapat mengedit bagian ini. Biasanya masalah pada $PathInfo['dirname']
            //** EN: Sometimes have a problem in base url section, you can editi this section. normally at $PathInfo['dirname']
            $baseUrl = $protocol . $_SERVER['HTTP_HOST'] . substr($PathInfo['dirname'], 0, $StrLimit);

            if ( substr($baseUrl, -1, 1) != '/' ) {
                $baseUrl .= '/';
            }

            Url::init($protocol, $baseUrl, $basePath);

        }

        if ( php_sapi_name() == 'cli' ) {
            $ResultSegment = $_SERVER['argv'];
            array_shift($ResultSegment);

            self::$params = $ResultSegment;
            self::$realParams = self::$params;
            self::$paramsStr = implode('/', $ResultSegment);
        } else {
            $routeStr = str_replace($_SERVER['SCRIPT_NAME'], '', $_SERVER['PHP_SELF']);
            $segments = explode('/', $routeStr);

            if ( isset( $segments[0] ) && empty( $segments[0] ) ) {
                unset( $segments[0] );
            }

            if ( empty( $segments[ count($segments) - 1 ] ) ) {
                unset( $segments[ count($segments) - 1 ] );
            }

            $segments = array_values($segments);

            self::$queryStr = '?' . $_SERVER['QUERY_STRING'];
            self::$paramsStr = implode('/', $segments);
            self::$params = $segments;
            self::$realParams = self::$params;
        }

        unset( $StrLimit );
        unset( $PathInfo );

    }

    /**
     * @return mixed
     */
    private static function registerRoute($type, $route)
    {
        if ( ! isset( Route::$list[$type] ) ) {
            Route::$list[$type] = [];
        }

        array_push(Route::$list[$type], $route);
    }

    /**
     * @param $route
     * @param $callback
     * @param $params
     *
     * @return null
     */
    private static function apply($route, $callback, $params)
    {

        self::callbackCheck($callback);

        $callback = ( self::findCallback($params) ) ? : $callback;

        self::parser($route, $callback);

        return self::$instance;
    }

    /**
     * @param $params
     *
     * @return callable
     */
    private static function findCallback($params)
    {
        if ( count($params) > 0 ) {
            foreach ( $params as $param ) {
                if ( is_callable($param) ) {
                    $callback = $param;
                }
            }

            return $callback;
        }

        return FALSE;
    }

    /**
     * @param $callback
     *
     * @return bool
     * @throws \Exception
     */
    private static function callbackCheck($callback)
    {

        if ( ! is_object($callback) && ! is_callable($callback) && ! is_string($callback) ) {
            Throw new \Exception("Callback parameters types must be Closure or Object or String");
        }

        if ( is_string($callback) ) {
            $pos = strpos($callback, '@');

            if ( is_string($callback) && ( ! $pos || $pos == 0 ) ) {
                Throw new \Exception("Callback String format must be like Controller@Method");
            }
        }

        return TRUE;
    }

    /**
     * @param int $key
     *
     * @return array|null
     */
    public function _getParams($key = -1)
    {
        if ( $key >= 0 ) {

            if ( isset( self::$params[ $key ] ) ) {
                return self::$params[ $key ];
            } else {
                return NULL;
            }

        } else {
            return self::$params;
        }

    }
    
    /**
     * @return bool
     */
    public static function status()
    {
        return self::$routedStatus;
    }

    /**
     * @param int $key
     *
     * @return array|null
     */
    public static function getParams($key = -1)
    {
        if ( $key >= 0 ) {

            if ( isset( self::$realParams[ $key ] ) ) {
                return self::$realParams[ $key ];
            } else {
                return NULL;
            }

        } else {
            return self::$realParams;
        }

    }

    /**
     * @param        $key
     * @param string $value
     */
    public static function setParams($key, $value = '')
    {
        //if (!isset($this->params)) $this->params = array();

        if ( is_array($key) ) {
            self::$realParams = $key;
        } else {
            self::$realParams[ $key ] = $value;
        }

    }

    /**
     * @param $params
     */
    public static function setParamStr($params)
    {
        self::$paramsStr = $params;
    }

    /**
     * @return string
     */
    public static function getParamStr()
    {
        return self::$paramsStr;
    }
    
    /**
     * @param string $route
     *
     * @return bool|string
     */
    public static function is($route = '')
    {

        if ( $route == '' ) {
            return self::$destination;
        } else {

            if ( self::$destination == $route ) {
                return TRUE;
            } else {
                return FALSE;
            }

        }

    }
    
    /**
     * @param $route
     * @param $callback
     */
    private static function parser($route, $callback)
    {
        $params = self::getParamStr();

        if ( substr($params, 0, 1) == '/' ) {
            $params = substr($params, 1);
        }

        if ( preg_match(
            '/(^\\/(\?.*)?$)/',
            $route . $params . self::$queryStr,
            $matches, PREG_OFFSET_CAPTURE
        ) ) {

            if ( is_callable($callback) ) {
                $callFunc = $callback;
            } elseif ( is_string($callback) ) {
                $callFunc = self::createCallbackFromString($callback, $params);
            }
            Response::set($callFunc->bindTo(Kecik::getInstance(), get_class()));
            self::$routedStatus = TRUE;
        } else {
            $routePattern = str_replace('/', '\\/', $route);

            //** ID: Konversi route kedalam pattern parameter optional
            //** EN: Convert route in optional parameter pattern
            $routePattern = preg_replace('/\\\\\\/\\(:\\w+\\)/', '(\\/\\\\w+)?', $routePattern, -1);
            //** ID: Konversi route kedalam pattern parameter wajib
            //** EN: Cover route in required parameter pattern
            $routePattern = preg_replace('/:\\w+/', '\w+', $routePattern, -1);

            if ( $route != '/' && preg_match(
                    '/^' . $routePattern . '(\\/)?(\\?.*)*$/',
                    $params . self::$queryStr,
                    $matches,
                    PREG_OFFSET_CAPTURE
                )
            ) {

                $callFunc = self::$instance->createCallbackFromString($callback);

                Response::set($callFunc->bindTo(Kecik::getInstance(), get_class()));
                self::$routedStatus = TRUE;
                $p = explode('/', $route);

                while ( list( $key, $value ) = each($p) ) {

                    if ( substr(trim($value), -1) == '+' ) {

                        if ( isset( $matches[2][0] ) && ! empty( $matches[2][0] ) ) {
                            $realParams[ $value ] = explode('/', substr($matches[2][0], 1));
                        } elseif ( isset( $matches[7][0] ) && ! empty( $matches[7][0] ) ) {
                            $realParams[ $value ] = explode('/', substr($matches[7][0]), 1);
                        } else {
                            $realParams[ $value ] = [];
                        }

                    } elseif ( substr(trim($value, '/'), 0, 1) == ':' ) {
                        $getpos = ( strpos(self::_getParams($key), '?') > 0 ) ? strpos(
                            self::_getParams($key), '?'
                        ) : strlen(self::_getParams($key));
                        $realParams[ $value ] = substr(self::_getParams($key), 0, $getpos);
                    } elseif ( substr(trim($value, '/'), 0, 2) == '(:' && substr(trim($value, '/'), -1, 1) == ')' ) {

                        if ( self::_getParams($key) != NULL ) {
                            $getpos = ( strpos(self::_getParams($key), '?') > 0 ) ? strpos(
                                self::_getParams($key), '?'
                            ) : strlen(self::_getParams($key));
                            $realParams[ $value ] = substr(self::_getParams($key), 0, $getpos);
                        }

                    }

                }

                self::$routedStatus = TRUE;
            }

        }

        Route::$destination = $route;
        if ( isset( $realParams ) ) {
            Route::setParams($realParams);
        }

    }
    
    /**
     * @param $callFunc
     *
     * @return \Closure
     * @throws \Exception
     */
    private function createCallbackFromString($callFunc)
    {
        if ( is_string($callFunc) && self::callbackCheck($callFunc) ) {
            $controllerParts = explode('@', $callFunc);
            $controllerParts[0] = explode('\\', $controllerParts[0]);
            $hmvc = '';
            $controllerPaths = count($controllerParts[0]);

            if ( $controllerPaths > 1 ) {

                foreach ( $controllerParts[0] as $idx => $controllerPart ) {

                    if ( $idx == ( $controllerPaths - 1 ) ) {
                        break;
                    }

                    $hmvc .= '\\' . $controllerPart;
                }

            }

            $controller = $hmvc . '\Controllers\\' . $controllerParts[0][ $controllerPaths - 1 ];

            if ( class_exists($controller) ) {
                $callFunc = function () use ($controller, $controllerParts) {

                    $c = new \ReflectionClass($controller);
                    $c = $c->newInstanceArgs(self::getParams());

                    return call_user_func_array([ $c, $controllerParts[1] ], self::getParams());
                };

                return $callFunc;
            }

            Throw new \Exception('Not Found Controller!!!');
        }

        return $callFunc;
    }


    /**
     * @param       $route
     * @param       $callback
     * @param array ...$params
     *
     * @return $this
     */
    public function any($route, $callback, ...$params)
    {
        $this->RoutedStatus = FALSE;

        if ( ! Request::isGet() ) {
            return $this;
        }

        if ( is_callable($this->callable) ) {
            //$this->RoutedStatus = FALSE;
            return $this;
        }

        $this->middleware = [ 'before' => [], 'after' => [] ];
        self::$FullRender = '';
        $args = func_get_args();

        if ( ! empty( self::$group ) ) {

            if ( $args[0] == '/' ) {
                $args[0] = substr(self::$group, 0, -1);
            } else {
                $args[0] = self::$group . $args[0];
            }

        }

        array_push(Route::$list, $route);
        $this->setCallable($callback);

        return $this;
    }
    
    /**
     * @param array $method
     * @param       $route
     * @param       $callback
     * @param array ...$params
     *
     * @return $this
     */
    public function match(Array $method, $route, $callback, ...$params)
    {
        $this->RoutedStatus = FALSE;

        if ( ! Request::isGet() ) {
            return $this;
        }

        if ( is_callable($this->callable) ) {
            //$this->RoutedStatus = FALSE;
            return $this;
        }

        $this->middleware = [ 'before' => [], 'after' => [] ];
        self::$FullRender = '';
        $args = func_get_args();

        if ( ! empty( self::$group ) ) {

            if ( $args[0] == '/' ) {
                $args[0] = substr(self::$group, 0, -1);
            } else {
                $args[0] = self::$group . $args[0];
            }

        }

        array_push(Route::$list, $route);
        $this->setCallable($callback);

        return $this;
    }
    
    
    /**
     * @param       $route
     * @param       $callback
     * @param array ...$params
     *
     * @return null
     */
    public static function get($route, $callback, ...$params)
    {
        $route = self::routeGroup($route);
        self::registerRoute('GET', $route);

        if ( ! Request::isGet() ) {
            return self::$instance;
        }

        return self::apply($route, $callback, $params);
    }
    
    /**
     * @param       $route
     * @param       $callback
     * @param array ...$params
     *
     * @return null
     */
    public static function post($route, $callback, ...$params)
    {
        $route = self::routeGroup($route);
        self::registerRoute('POST', $route);

        if ( ! Request::isPost() ) {
            return self::$instance;
        }

        return self::apply($route, $callback, $params);
    }
    
    /**
     * @param       $route
     * @param       $callback
     * @param array ...$params
     *
     * @return null
     */
    public static function put($route, $callback, ...$params)
    {
        $route = self::routeGroup($route);
        self::registerRoute('PUT', $route);

        if ( ! Request::isPut() ) {
            return self::$instance;
        }

        return self::apply($route, $callback, $params);
    }
    
    /**
     * @param       $route
     * @param       $callback
     * @param array ...$params
     *
     * @return null
     */
    public static function delete($route, $callback, ...$params)
    {
        $route = self::routeGroup($route);
        self::registerRoute('DELETE', $route);

        if ( ! Request::isDelete() ) {
            return self::$instance;
        }

        return self::apply($route, $callback, $params);
    }
    
    /**
     * @param       $route
     * @param       $callback
     * @param array ...$params
     *
     * @return null
     */
    public static function patch($route, $callback, ...$params)
    {
        $route = self::routeGroup($route);
        self::registerRoute('PATCH', $route);

        if ( ! Request::isPatch() ) {
            return self::$instance;
        }

        return self::apply($route, $callback, $params);
    }
    
    /**
     * @param       $route
     * @param       $callback
     * @param array ...$params
     *
     * @return null
     */
    public static function options($route, $callback, ...$params)
    {
        $route = self::routeGroup($route);
        self::registerRoute('OPTIONS', $route);

        if ( !Request::isOptions() ) {
            return self::$instance;
        }

        return self::apply($route, $callback, $params);
    }
    
    /**
     * @param $route
     * @param $callback
     *
     * @return null
     */
    public static function group($route, $callback)
    {

        self::$groupLevel++;
        if ( self::$routedStatus ) {
            return self::$instance;
        }

        self::$group[self::$groupLevel] = $route.'/';
        if ( is_callable($callback) ) {
            $groupFunc = $callback->bindTo(self::$instance, get_class());
            $groupFunc();
        }


        unset(self::$group[self::$groupLevel]);
        self::$groupLevel--;
    }
    
    /**
     * @param $route
     *
     * @return string
     */
    private static function routeGroup($route)
    {

        if ( ! empty( self::$group ) ) {
            $group = implode('', self::$group);

            if ( $route == '/' ) {
                $route = substr($group, 0, -1);

                return $route;
            } else {
                $route = $group . $route;

                return $route;
            }

        }

        return $route;
    }

}

//Route::init();
//--
