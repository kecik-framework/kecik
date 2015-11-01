<?php
/**
 * Url
 * @package Kecik
 * @author Dony Wahyu Isp
 * @since 1.0.1-alpha
 **/
namespace Kecik;

class Url {
    /**
     * @var string $_protocol, $_base_url, $_base_path, $_index
     **/
    private $_protocol, $_base_url, $_base_path, $_index;

    /**
     * @var object $_route
     **/
    private $_route;
    private $_app;
    
    /**
     * __construct
     **/
    public function __construct($protocol, $baseUrl, $basePath) {
        $this->_protocol = $protocol;
        $this->_base_url = $baseUrl;
        $this->_base_path = $basePath;

        if ( Config::get('mod_rewrite') === FALSE ) {
            $this->_index = basename($_SERVER["SCRIPT_FILENAME"], '.php').'.php/';
            Config::set('index', $this->_index);
        }
    }

    /**
     * protocol
     * @return string;
     **/
    public function protocol() {
        return $this->_protocol;
    }

    /**
     * basePath
     * @return string
     **/
    public function basePath() {
        return $this->_base_path;
    }

    /**
     * baseUrl
     * @return string
     **/
    public function baseUrl() {
        return $this->_base_url;
    }

    /**
     * redirect
     * @param string link
     **/
    public function redirect($link) {
        if ($link == '/') $link='';
        header('Location: '.$this->_base_url.$this->_index.$link);
        exit();
    }

    /**
     * to
     * @param string $link
     * @return echo link
     **/
    public function to($link) {
        if ($link == '/') $link='';
        echo $this->_base_url.$this->_index.$link;
    }

    /**
     * linkto
     * @param string $link
     * @return string
     **/
    public function linkTo($link) {
        if ($link == '/') $link='';
        return $this->_base_url.$this->_index.$link;
    }
}
//--