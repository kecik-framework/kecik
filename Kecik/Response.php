<?php
/**
 * Created by PhpStorm.
 * User: DWIsprananda
 * Date: 9/15/2016
 * Time: 12:49 PM
 */

namespace Kecik;


class Response
{
    private static $instance = NULL;
    private static $result   = '';

    /**
     * @param int $code
     */
    public function header($code = 200)
    {
        if ( ! is_array($code) ) {
            $code = [ $code ];
        }
        self::$header = [];

        while ( list( $key, $value ) = each($code) ) {

            if ( is_int($value) ) {
                self::$header[] = $_SERVER["SERVER_PROTOCOL"] . ' ' . Route::$HttpResponse[ $value ];
            } else {
                self::$header[] = $value;
            }

        }

    }

    /**
     * @param $code
     */
    public function error($code)
    {
        header($_SERVER["SERVER_PROTOCOL"] . Route::$HttpResponse[ $code ]);

        if ( $this->config->get("error.$code") != '' ) {
            echo $this->render($this->config->get('path.template') . '/' . $this->config->get("error.$code") . '.php');
        } else {
            die( Route::$HttpResponse[ $code ] );
        }

    }

    public static function init()
    {
        if ( is_null(self::$instance) ) {
            self::$instance = new self;
        }
    }

    public static function set($result)
    {
        self::$result = $result;
    }

    public static function get()
    {
        $func = self::$result;
        ob_start();
        $return = call_user_func_array($func, Route::getParams());
        $printed = ob_get_clean();

        return [$return, $printed];
    }
}

Response::init();