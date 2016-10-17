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
require_once "Middleware.php";
require_once "Events.php";
require_once "Url.php";
require_once "Request.php";
require_once "Response.php";
require_once "Assets.php";
require_once "Template.php";
require_once "View.php";

/**
 * End Load require
 */

/**
 * Class Kecik
 *
 * @package Kecik
 */
class Kecik
{

    public static $version = "2.0.1";
    public        $route, $url, $config, $assets, $request;

    private $callable;
    private $middleware = [ 'before' => [], 'after' => [] ];

    private static $FullRender = '';
    private static $header     = [];
    private static $group      = '';

    private $GroupFunc;
    private $LibrariesEnabled = [];

    private static $instance = NULL;

    private $before, $after;

    /**
     * @param $class
     */
    public function autoload($class)
    {
        $ClassArray = explode('\\', $class);

        if ( count($ClassArray) > 1 ) {

            if ( php_sapi_name() == 'cli' ) {
                $MvcPath = Config::get('path.basepath') . Config::get('path.mvc');
            } else {
                $MvcPath = Config::get('path.mvc');
            }

            //** if count $ClassArray = 3 is HMVC
            if ( count($ClassArray) >= 3 ) {
                $HmvcPath = '';

                for ( $i = 0; $i < count($ClassArray) - 2; $i++ ) {
                    $HmvcPath .= $ClassArray[ $i ] . '/';
                }

                //**                          Module                   Controllers/Models                           Class
                $FileLoad = $MvcPath . '/' . $HmvcPath . $ClassArray[ count(
                                                                          $ClassArray
                                                                      ) - 2 ] . '/' .
                            $ClassArray[ count($ClassArray) - 1 ] . '.php';
            } else {
                //**                          Controller/Models              Class
                $FileLoad = $MvcPath . '/' . $ClassArray[0] . '/' . $ClassArray[1] . '.php';
            }

            if ( file_exists($FileLoad) ) {
                include $FileLoad;
            }

        }

    }

    /**
     * Kecik constructor.
     *
     * @param array $config
     */
    public static function init()
    {
        self::$instance = new self;

        if ( Config::get('path.basepath') == '' ) {
            Config::set('path.basepath', getcwd() . '/');
        }

        if ( isset( $_SERVER['SERVER_PROTOCOL'] ) ) {
            self::$header[] = $_SERVER['SERVER_PROTOCOL'] . ' ' . Route::$HttpResponse[200];
        }

        Route::init();
        Url::setBasePath(Config::get('path.basepath'));
        Assets::init();
//        self::$instance->assets    = new Assets(self::$instance->url);
        Request::init();
//        self::$instance->request   = new Request();

        //** ID: Memasukan Libary/Pustaka berdasarkan config | EN: Load Dynamic Libraries from config
        $libraries = Config::get('libraries');

        if ( is_array($libraries) && count($libraries) > 0 ) {

            while ( list( $library, $params ) = each($libraries) ) {
                $ClsLibrary = 'Kecik\\' . $library;

                if ( class_exists($ClsLibrary) ) {

                    if ( isset( $params['enable'] ) && $params['enable'] === TRUE ) {
                        $library = strtolower($library);

                        //** ID: Untuk Library/Pustaka tanpa parameter
                        //** EN: For Library without parameter
                        if ( ! isset( $params['config'] ) && ! isset( $params['params'] ) ) {

                            //** ID: Untuk Library/Pustaka DIC | EN: For DIC Library
                            if ( $library == 'dic' ) {
                                self::$instance->container = new DIC();
                                self::$instance->LibrariesEnabled[] = [ 'DIC', 'container' ];
                            } elseif ( $library == 'mvc' ) {
                                self::$instance->LibrariesEnabled[] = [ 'MVC' ];

                                if ( isset( self::$instance->db ) ) {
                                    MVC::setDB(self::$instance->db);
                                }

                            } else { // ID: Untuk Library/Pustaka lain | EN: Other Library
                                self::$instance->$library = new $ClsLibrary();
                                self::$instance->LibrariesEnabled[] = [ $library, $library ];
                            }

                            //** ID: Untuk Library/Pustaka dengan parameter Kelas Kecik
                            //** EN: For Library with parameter of Kecik CLass
                        } elseif ( isset( $params['config'] ) ) {
                            //** ID: Buat variabel config
                            //** EN: Create config variable

                            while ( list( $key, $value ) = each($params['config']) ) {
                                self::$instance->config->set($library . '.' . $key, $value);
                            }

                            //** ID: untuk Library/Pustaka Database | EN: For Database Library
                            if ( $library == 'database' ) {
                                self::$instance->db = new Database();
                                self::$instance->LibrariesEnabled[] = [ 'Database', 'db' ];

                                if ( class_exists('MVC') ) {
                                    MVC::setDB(self::$instance->db);
                                }

                            } else { //** ID: untuk Library/Pustaka lain | EN: For Other library
                                self::$instance->$library = new $ClsLibrary();
                                self::$instance->LibrariesEnabled[] = [ $library, $library ];
                            }

                            //** ID: Untuk Library/Pustaka tanpa parameter Kelas Kecik
                            //** EN: For Library without parameter of Kecik CLass
                        } elseif ( isset( $params['params'] ) ) {
                            self::$instance->$library = new $ClsLibrary($params['params']);
                            self::$instance->LibrariesEnabled[] = [ $library, $library ];
                        }

                    }

                }

            }

        }

        //-- ID: Akhir untuk memasukan library/pustaka secara dinamis
        //-- EN: End Load Dynamic Library
        spl_autoload_register([ self::$instance, 'autoload' ], TRUE, TRUE);

    }

    /**
     * @return Kecik|null
     */
    public static function getInstance()
    {
        return self::$instance;
    }


    /**
     * @param Closure $callback
     */
    public function before(Closure $callback)
    {
        self::$instance->before = $callback;
    }

    /**
     * @param Closure $callback
     */
    public function after(Closure $callback)
    {
        self::$instance->after = $callback;
    }


    /**
     * @param      $template
     * @param bool $replace
     *
     * @return self::$instance
     */
    public function template($template, $replace = FALSE)
    {
        if ( self::$instance->RoutedStatus || $replace === TRUE ) {
//        if ((self::$instance->RoutedStatus && empty(self::$FullRender )) || $replace === TRUE) {
            self::$FullRender = $template;
        }

        return self::$instance;
    }

    /**
     * @param       $callback
     * @param array ...$params
     *
     * @return bool
     */
    public static function run($callback, ...$params)
    {
        $config = [];

        if ( count($params) ) {

            foreach ( $params as $param ) {
                if ( $param instanceof Config ) {
                    $config = Config::all();
                }

            }

        }

        self::init($config);

        if ( is_callable($callback) ) {
            $callback = $callback->bindTo(self::$instance);
            $callback();
        }

        if ( php_sapi_name() == 'cli-server' ) {

            if ( is_file(Url::basePath() . str_replace('/', DIRECTORY_SEPARATOR, $_SERVER['REQUEST_URI'])) &&
                 file_exists(Url::basePath() . str_replace('/', DIRECTORY_SEPARATOR, $_SERVER['REQUEST_URI'])) &&
                 substr(strtolower($_SERVER['REQUEST_URI']), -4) != '.php'
            ) {
                readfile(Url::basePath() . str_replace('/', DIRECTORY_SEPARATOR, $_SERVER['REQUEST_URI']));

                return TRUE;
            }

        }

        if ( Route::status() ) {

            if ( is_callable(self::$instance->before) ) {
                $before = self::$instance->before;
                $before();

            }


            //** Run Middleware Before
            foreach ( Middleware::getBefore() as $middleware ) {

                if ( $middleware() === FALSE ) {
                    self::stop();
                }

            }


            list( $response, $result ) = Response::get();

            if ( ! is_string($response) && ! is_numeric($response) ) {
                $response = json_encode($response);
            }


            if ( is_callable(self::$instance->after) ) {
                $after = self::$instance->after;
                $response = $after(self::$instance->request, $response);
            }

            $response = ( empty( $response ) || is_bool($response) ) ? $result : $response . $result;

            if ( count(self::$header) > 0 && php_sapi_name() != 'cli' ) {

                while ( list( $idx_header, $headerValue ) = each(self::$header) ) {
                    header($headerValue);
                }

            }

            echo $response;
            //echo $result;


            //** Run Middleware After
            foreach ( Middleware::getAfter() as $middleware ) {

                if ( $middleware() === FALSE ) {
                    self::stop();
                }

            }


        } else {

            if ( php_sapi_name() != 'cli' ) {
                header($_SERVER["SERVER_PROTOCOL"] . ' ' . Route::$HttpResponse[404]);
            }

            if ( Config::get('error.404') != '' ) {
                list( $result, $printed ) = Response::set(Template::render(Config::get('error.404')))->get();
                echo $result;
            } else {
                die( Route::$HttpResponse[404] );
            }

        }


    }

    public static function stop()
    {
        exit();
    }

    /**
     * @return array
     */
    public static function getLibrariesEnabled()
    {
        return self::$instance->LibrariesEnabled;
    }

    /**
     * @param        $file
     * @param string $response
     *
     * @return string
     */
    public static function render($file, $response = "")
    {
        ob_start();
        include $file;
        self::$FullRender = ob_get_clean();

        self::$FullRender = preg_replace_callback(
            [
                '/(\\\)?' . addslashes(Config::get('template.open_tag')) . '=?' . '/',
                '/(\\\)?' . addslashes(Config::get('template.close_tag')) . '/'
            ],
            function ($s) {

                if ( isset( $s[0] ) ) {

                    if ( isset( $s[1] ) && $s[1] == '\\' ) {
                        return substr($s[0], 1);
                    } elseif ( $s[0] == Config::get('template.open_tag') ) {
                        return '<?php ';
                    } elseif ( $s[0] == '{{=' ) {
                        return '<?php echo ';
                    } elseif ( $s[0] == Config::get('template.close_tag') ) {
                        return '?>';
                    }

                }
            },
            self::$FullRender
        );

        self::$FullRender = str_replace(
            [ '@js', '@css' ],
            [ Assets::$js->render(), Assets::$css->render() ],
            self::$FullRender
        );

        if ( ! empty( $response ) ) {

            self::$FullRender = str_replace(
                [ '@yield', '@response' ],
                [ $response, $response ],
                self::$FullRender
            );

        }
        //-- END Replace Tag

        ob_start();
        eval( '?>' . self::$FullRender );
        self::$FullRender = ob_get_clean();

        return self::$FullRender;
    }

}
