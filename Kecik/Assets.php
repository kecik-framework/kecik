<?php
/**
 * AssetsBase
 * 
 * @package     Kecik
 * @author      Dony Wahyu Isp
 * @since       1.0.1-alpha
 **/
namespace Kecik;

/**
 * Assets
 * 
 * @package     Kecik
 * @author      Dony Wahyu Isp
 * @since       1.0.1-alpha
 **/
class Assets {
    /**
     * @var object $css, $js
     **/
    var $css, $js;

    /**
     * @var object $url
     **/
    var $baseUrl;

    /**
     * __contruct
     * @param object $url
     **/
    public function __construct(Url $url) {
        $this->baseUrl = $url->baseUrl();
        $this->css = new AssetsBase($this->baseUrl, 'css');
        $this->js = new AssetsBase($this->baseUrl, 'js'); 
    }

    /**
     * images
     * @param string $file
     * @return string
     **/
    public function images($file) {
        return $this->baseUrl.Config::get('path.assets').'/images/'.$file;
    }

    /**
     * url
     * Get assets URL
     * @return string
     **/
    public function url() {
        return $this->baseUrl.Config::get('path.assets').'/';
    }
}

class AssetsBase {
    /**
     * @var array
     **/
    var $assets;
    
    /**
     * @var array
     **/
    var $attr;

    /**
     * @var string
     **/
    var $type;

    /**
     * @var ID: Objek dari Url | EN: Object of Url
     **/
    var $baseurl;

    /**
     * __construct
     * @param object url
     * @param string type
     **/
    public function __construct($baseUrl, $type) {
        $this->baseurl = $baseUrl;
        $this->type = strtolower($type);
        $this->assets[$type] = array();
        $this->attr[$type] = array();
    }

    /**
     * add
     * @param string $file
     **/
    public function add($file, $attr=array()) {
        if (!in_array($file, $this->assets[$this->type])) {
            $this->assets[$this->type][] = $file;
            $this->attr[$this->type][] = $attr;
        }
    }

    /**
     * delete
     * @param $file
     **/
    public function delete($file) {
        $key = array_search($file, $this->assets[$this->type]);
        unset($this->assets[$this->type][$key]);
        unset($this->attr[$this->type][$key]);
    }

    /**
     * render
     * @param string $file optional
     **/
    public function render($file='') {
        reset($this->assets[$this->type]);
        //reset($this->attr[$this->type]);
        
        $attr = '';

        if ($this->type == 'js') {
            if ($file != '') {
                $key = array_search($file, $this->assets[$this->type]);
                while(list($at, $val) = each($this->attr[$this->type][$key]))
                    $attr .= $at.'="'.$val.'" ';
                if ($key)
                    return '<script type="text/javascript" src="'.$this->baseurl.Config::get('path.assets')."/$this->type/".$this->assets[$this->type][$key].'.'.$this->type.'" '.$attr.'></script>'."\n";
            } else {
                $render = '';
                while(list($key, $value) = each($this->assets[$this->type])) {
                    $attr = '';
                    while(list($at, $val) = each($this->attr[$this->type][$key]))
                        $attr .= $at.'="'.$val.'" ';
                    $render .= '<script type="text/javascript" src="'.$this->baseurl.Config::get('path.assets')."/$this->type/".$value.'.'.$this->type.'" '.$attr.'></script>'."\n";
                }

                return $render;
            }

        } elseif ($this->type == 'css') {
            if ($file != '') {
                $key = array_search($file, $this->assets[$this->type]);
                while(list($at, $val) = each($this->attr[$this->type][$key]))
                    $attr .= $at.'="'.$val.'" ';
                if ($key)
                    return '<link rel="stylesheet" href="'.$this->baseurl.Config::get('path.assets')."/$this->type/".$this->assets[$this->type][$key].'.'.$this->type.'" '.$attr.' />'."\n";
            } else {
                $render = '';
                while(list($key, $value) = each($this->assets[$this->type])) {
                    $attr = '';
                    while(list($at, $val) = each($this->attr[$this->type][$key]))
                        $attr .= $at.'="'.$val.'" ';
                    $render .= '<link rel="stylesheet" href="'.$this->baseurl.Config::get('path.assets')."/$this->type/".$value.'.'.$this->type.'" '.$attr.' />'."\n";
                }

                return $render;
            }
        }
    } 

    /**
     * url
     * Get assets URL
     * @return string
     **/
    public function url() {
        return $this->baseurl.Config::get('path.assets')."/$this->type/";
    }
}

//--