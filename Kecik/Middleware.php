<?php
/**
 * Created by PhpStorm.
 * User: DWIsprananda
 * Date: 9/15/2016
 * Time: 11:20 AM
 */

namespace Kecik;


class Middleware
{

    private static $instance    = NULL;
    private static $middlewares = [ 'before' => [], 'after' => [] ];

    public static function setBefore(\Closure ...$funcs)
    {
        if ( ! Route::status() ) {
            foreach ( $funcs as $func ) {
                self::$middlewares['before'] = $func;
            }
        }

        return self::$instance;
    }

    public static function getBefore()
    {
        return self::$middlewares['before'];
    }

    public static function setAfter(\Closure ...$funcs)
    {
        if ( ! Route::status() ) {
            foreach ( $funcs as $func ) {
                self::$middlewares['after'] = $func;
            }
        }

        return self::$instance;
    }

    public static function getAfter()
    {
        return self::$middlewares['after'];
    }

    public static function init()
    {
        if ( is_null(self::$instance) ) {
            self::$instance = new self;
        }

        self::$middlewares = [ 'before' => [], 'after' => [] ];
    }


}

Middleware::init();