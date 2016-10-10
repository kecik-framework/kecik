<?php
/**
 * Created by PhpStorm.
 * User: dnaextrim
 * Date: 10/10/2016
 * Time: 9:08 PM
 */

namespace Kecik;


class View
{
    private static $instance = NULL;

    public static function init()
    {
        if ( is_null(self::$instance) ) {
            self::$instance = new self;
        }
    }

    public static function render($controller, $view, $params = [])
    {
        extract($params);

        if ( ! is_array($view) ) {
            $path = explode('\\', get_class($controller));

            if ( count($path) > 2 ) {
                $view_path = '';

                for ( $i = 0; $i < count($path) - 2; $i++ ) {
                    $view_path .= strtolower($path[ $i ]) . '/';
                }

                $view_path = Config::get('path.mvc') . '/' . $view_path;
            } else {
                $view_path = Config::get('path.mvc');
            }

            if ( php_sapi_name() == 'cli' ) {
                $view_path = Config::get('path.basepath') . '/' . $view_path;
            }
        } else {
            $view_path = Config::get('path.mvc');

            if ( isset( $file[1] ) ) {
                $view_path .= '/' . $file[0];
                $file = $file[1];
            } else {
                $file = $file[0];
            }

            if ( php_sapi_name() == 'cli' ) {
                $view_path = Config::get('path.basepath') . '/' . $view_path;
            }
        }

        ob_start();
        include $view_path . '/Views/' . $view . '.php';

        return ob_get_clean();
    }
}