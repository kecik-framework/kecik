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
class Assets 
{
    /**
     * @var object $css, $js
     **/
    public $css, $js;

    /**
     * @var object $url
     **/
    private $BaseUrl;

    /**
     * __contruct
     * @param object $url
     **/
    public function __construct(Url $url) 
    {
        $this->BaseUrl = $url->BaseUrl();
        $this->css = new AssetsBase($this->BaseUrl, 'css');
        $this->js = new AssetsBase($this->BaseUrl, 'js'); 
    }

    /**
     * images
     * @param string $file
     * @return string
     **/
    public function images($file) 
    {
        return $this->BaseUrl.Config::get('path.assets').'/images/'.$file;
    }

    /**
     * url
     * Get assets URL
     * @return string
     **/
    public function url() 
    {
        return $this->BaseUrl.Config::get('path.assets').'/';
    }
}

class AssetsBase 
{
    /**
     * @var array
     **/
    public $assets;
    
    /**
     * @var array
     **/
    public $attr;

    /**
     * @var string
     **/
    public $type;

    /**
     * @var ID: Objek dari Url | EN: Object of Url
     **/
    private $BaseUrl;

    /**
     * __construct
     * @param object url
     * @param string type
     **/
    public function __construct($BaseUrl, $type) 
    {
        $this->BaseUrl = $BaseUrl;
        $this->type = strtolower($type);
        $this->assets[$type] = array();
        $this->attr[$type] = array();
    }

    /**
     * add
     * @param string $file
     **/
    public function add($file, $attr = array()) 
    {
        if (! in_array($file, $this->assets[$this->type])) {
            $this->assets[$this->type][] = $file;
            $this->attr[$this->type][] = $attr;
        }
    }

    /**
     * delete
     * @param $file
     **/
    public function delete($file) 
    {
        $key = array_search($file, $this->assets[$this->type]);
        unset($this->assets[$this->type][$key]);
        unset($this->attr[$this->type][$key]);
    }

    /**
     * render
     * @param string $file optional
     **/
    public function render($file = '') 
    {
        reset($this->assets[$this->type]);
        //reset($this->attr[$this->type]);
        
        $attr = '';

        if ($this->type == 'js') {

            if ($file != '') {
                $key = array_search($file, $this->assets[$this->type]);
                
                while(list($at, $val) = each($this->attr[$this->type][$key])) {
                    $attr .= $at.'="'.$val.'" ';
                }

                if ($key) {
                    return '<script type="text/javascript" src="'.$this->BaseUrl.Config::get('path.assets')."/$this->type/".$this->assets[$this->type][$key].'.'.$this->type.'" '.$attr.'></script>'."\n";
                }
            } else {
                $render = '';
                
                while(list($key, $value) = each($this->assets[$this->type])) {
                    $attr = '';
                    
                    while(list($at, $val) = each($this->attr[$this->type][$key])) {
                        $attr .= $at.'="'.$val.'" ';
                    }

                    $render .= '<script type="text/javascript" src="'.$this->BaseUrl.Config::get('path.assets')."/$this->type/".$value.'.'.$this->type.'" '.$attr.'></script>'."\n";
                }

                return $render;
            }

        } elseif ($this->type == 'css') {
            if ($file != '') {
                $key = array_search($file, $this->assets[$this->type]);
                
                while(list($at, $val) = each($this->attr[$this->type][$key])) {
                    $attr .= $at.'="'.$val.'" ';
                }

                if ($key) {
                    return '<link rel="stylesheet" href="'.$this->BaseUrl.Config::get('path.assets')."/$this->type/".$this->assets[$this->type][$key].'.'.$this->type.'" '.$attr.' />'."\n";
                }
            } else {
                $render = '';
                
                while(list($key, $value) = each($this->assets[$this->type])) {
                    $attr = '';
                    
                    while(list($at, $val) = each($this->attr[$this->type][$key])) {
                        $attr .= $at.'="'.$val.'" ';
                    }

                    $render .= '<link rel="stylesheet" href="'.$this->BaseUrl.Config::get('path.assets')."/$this->type/".$value.'.'.$this->type.'" '.$attr.' />'."\n";
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
    public function url() 
    {
        return $this->BaseUrl.Config::get('path.assets')."/$this->type/";
    }
}

//--