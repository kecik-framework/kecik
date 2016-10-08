<?php
/**
 * Url
 *
 * @package Kecik
 * @author  Dony Wahyu Isp
 * @since   1.0.1-alpha
 **/
namespace Kecik;

/**
 * Class Url
 *
 * @package Kecik
 */
class Url
{
    private static $instance;

    private static $protocol, $baseUrl, $basePath, $index;

    /**
     * Url constructor.
     *
     * @param $protocol
     * @param $baseUrl
     * @param $basePath
     */
    public static function init($protocol, $baseUrl, $basePath)
    {
        self::$protocol = $protocol;
        self::$baseUrl = $baseUrl;
        self::$basePath = $basePath;

        if ( Config::get('mod_rewrite') === FALSE ) {
            self::$index = basename($_SERVER["SCRIPT_FILENAME"], '.php') . '.php/';
            Config::set('index', self::$index);
        }

    }

    /**
     * @return mixed
     */
    public static function protocol()
    {
        return self::$protocol;
    }

    /**
     * @return mixed
     */
    public static function basePath()
    {
        return self::$basePath;
    }

    public static function setBasePath($path)
    {
        self::$basePath = $path;
    }

    /**
     * @return mixed
     */
    public static function baseUrl()
    {
        return self::$baseUrl;
    }

    /**
     * @param $link
     */
    public static function redirect($link)
    {
        if ( $link == '/' ) {
            $link = '';
        }

        header('Location: ' . self::$baseUrl . self::$index . $link);
        exit();
    }

    /**
     * @param $link
     */
    public static function to($link)
    {
        if ( $link == '/' ) {
            $link = '';
        }

        echo self::$baseUrl . self::$index . $link;
    }

    /**
     * @param $link
     *
     * @return string
     */
    public static function linkTo($link)
    {
        if ( $link == '/' ) {
            $link = '';
        }

        return self::$baseUrl . self::$index . $link;
    }
}
//--