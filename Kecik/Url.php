<?php
/**
 * Url
 * @package Kecik
 * @author Dony Wahyu Isp
 * @since 1.0.1-alpha
 **/
namespace Kecik;

/**
 * Class Url
 * @package Kecik
 */
class Url
{
    private $_protocol, $_base_url, $_base_path, $_index;
    private $_route;
    private $_app;

    /**
     * Url constructor.
     * @param $protocol
     * @param $baseUrl
     * @param $basePath
     */
    public function __construct($protocol, $baseUrl, $basePath)
    {
        $this->_protocol = $protocol;
        $this->_base_url = $baseUrl;
        $this->_base_path = $basePath;

        if (Config::get('mod_rewrite') === FALSE) {
            $this->_index = basename($_SERVER["SCRIPT_FILENAME"], '.php') . '.php/';
            Config::set('index', $this->_index);
        }

    }

    /**
     * @return mixed
     */
    public function protocol()
    {
        return $this->_protocol;
    }

    /**
     * @return mixed
     */
    public function basePath()
    {
        return $this->_base_path;
    }

    /**
     * @return mixed
     */
    public function baseUrl()
    {
        return $this->_base_url;
    }

    /**
     * @param $link
     */
    public function redirect($link)
    {
        if ($link == '/') {
            $link = '';
        }

        header('Location: ' . $this->_base_url . $this->_index . $link);
        exit();
    }

    /**
     * @param $link
     */
    public function to($link)
    {
        if ($link == '/') {
            $link = '';
        }

        echo $this->_base_url . $this->_index . $link;
    }

    /**
     * @param $link
     * @return string
     */
    public function linkTo($link)
    {
        if ($link == '/') {
            $link = '';
        }

        return $this->_base_url . $this->_index . $link;
    }
}
//--