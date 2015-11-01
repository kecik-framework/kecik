<?php
/**
 * Config
 * @package     Kecik
 * @author      Dony Wahyu Isp
 * @since       1.0.1-alpha
 **/
namespace Kecik;

class Config {
    /**
     * @var array 
     **/
    private static $config;

    /**
     * init
     **/
    public static function init() {
        self::$config = [
            'path.assets' => '',
            'path.templates' => '',
            'mod_rewrite' =>FALSE,
            'index' => '',
            'template.open_tag' => '{{',
            'template.close_tag' => '}}'
        ];
    }

    /**
     * set
     * @param string $key
     * @param string $value
     **/
    public static function set($key, $value) {
        self::$config[strtolower($key)] = $value;
    }

    /**
     * get
     * @param   string $key
     * @return  string ID: nilai dari key config | EN: value of key config
     **/
    public static function get($key) {
        if (isset(self::$config[strtolower($key)]))
            return self::$config[strtolower($key)];
        else
            return '';
    }

    /**
     * delete
     * @param string $key
     **/
    public static function delete($key) {
        if (isset(self::$config[strtolower($key)]))
            unset(self::$config[strtolower($key)]);
    }
}

Config::init();
//--