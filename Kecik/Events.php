<?php
/**
 * Created by PhpStorm.
 * User: DWIsprananda
 * Date: 9/15/2016
 * Time: 1:23 PM
 */

namespace Kecik;


class Events
{
    const BEFORE = 1;
    const AFTER  = 2;

    private static $instance = null;
    
    public static function init()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self;
        }
    }

    public static function register($eventType, $event, $callback)
    {

    }

}

Events::init();