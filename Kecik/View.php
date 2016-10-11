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

    public static function render($view, $params = [], $controller = NULL)
    {

        if ( ! is_array($view) ) {
            $path = [];

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
        if ( ! is_null($controller) && ( $controller instanceof Controller ) ) {
            return self::$instance->callback($controller, $view_path, $view, $params);
        } else {
            extract($params);

            include $view_path . '/Views/' . $view . '.php';

            return ob_get_clean();
        }

    }

    private function callback($controller, $view_path, $view, $params)
    {
        $func = function ($view_path, $view, $params) {
            extract($params);

            ob_start();
            include $view_path . '/Views/' . $view . '.php';

            return ob_get_clean();
        };

        $func = $func->bindTo($controller);

        return $func($view_path, $view, $params);
    }

}

View::init();