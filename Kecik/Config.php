<?php
/**
 * Config
 *
 * @package     Kecik
 * @author      Dony Wahyu Isp
 * @since       1.0.1-alpha
 **/
namespace Kecik;

use Closure;

/**
 * Class Config
 *
 * @package Kecik
 */
class Config
{

    private static $config;
    private static $instance;

    /**
     * initialize method
     */
    public static function init()
    {
        self::$config = [
            'path.assets'        => '',
            'path.templates'     => '',
            'mod_rewrite'        => FALSE,
            'index'              => '',
            'template.open_tag'  => '<[',
            'template.close_tag' => ']>'
        ];
    }

    public static function apply(Closure $callback)
    {
        if ( ! self::$instance ) {
            self::$instance = new self;
        }

        $callback = $callback->bindTo(self::$instance);
        $callback();
    }

    /**
     * @param $key
     * @param $value
     */
    public static function set($key, $value)
    {
        self::$config[ strtolower($key) ] = $value;
    }

    /**
     * @param $key
     *
     * @return string
     */
    public static function get($key)
    {

        if ( isset( self::$config[ strtolower($key) ] ) ) {
            return self::$config[ strtolower($key) ];
        } else {
            return '';
        }

    }

    /**
     * @param $key
     */
    public static function delete($key)
    {
        if ( isset( self::$config[ strtolower($key) ] ) ) {
            unset( self::$config[ strtolower($key) ] );
        }
    }

    public static function all()
    {
        return self::$config;
    }
}

Config::init();
