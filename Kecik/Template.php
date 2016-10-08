<?php
/**
 * Created by PhpStorm.
 * User: DWIsprananda
 * Date: 9/15/2016
 * Time: 2:42 PM
 */

namespace Kecik;


class Template
{
    private static $instance = null;
    
    public static function render( $file )
    {
        self::setIntance();
    }
    
    public static function init()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self;
        }
    }
}

Template::init();