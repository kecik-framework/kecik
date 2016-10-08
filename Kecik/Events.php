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
    private static $instance = null;
    
    public static function init()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self;
        }
    }
}

Events::init();