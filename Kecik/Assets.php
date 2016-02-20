<?php
/**
 * AssetsBase
 *
 * @package     Kecik
 * @author      Dony Wahyu Isp
 * @since       1.0.1-alpha
 **/
namespace Kecik;

use Kecik\Kecik;
use Kecik\Url;

/**
 * Class Assets
 * @package Kecik
 */
class Assets
{

    /**
     * @var AssetsBase
     */
    public $css, $js;

    /**
     * @var string
     */
    private $BaseUrl;

    /**
     * Assets constructor.
     *
     * @param \Kecik\Url $url
     */
    public function __construct(Url $url)
    {
        $this->BaseUrl = $url->BaseUrl();
        $this->css = new AssetsBase($this->BaseUrl, 'css');
        $this->js = new AssetsBase($this->BaseUrl, 'js');
    }

    /**
     * @param        $file
     * @param string $version
     *
     * @return string
     */
    public function images($file, $version = '')
    {
        if (empty($version)) {
            $version = '?ver=' . Kecik::$version;
        }

        return $this->BaseUrl . Config::get('path.assets') . '/images/' . $file . $version;
    }

    /**
     * @param string $file
     * @param string $version
     *
     * @return string
     */
    public function url($file = '', $version = '')
    {
        if (empty($version)) {
            $version = '?ver=' . Kecik::$version;
        }
        if (!empty($file)) {
            return $this->BaseUrl . Config::get('path.assets') . '/' . $file . $version;
        } else {
            return $this->BaseUrl . Config::get('path.assets') . '/';
        }
    }

}

/**
 * Class AssetsBase
 * @package Kecik
 */
class AssetsBase
{

    public $assets;
    public $attr;
    public $versions;

    /**
     * @var string
     */
    public $type;

    private $BaseUrl;

    /**
     * AssetsBase constructor.
     *
     * @param $BaseUrl
     * @param $type
     */
    public function __construct($BaseUrl, $type)
    {
        $this->BaseUrl = $BaseUrl;
        $this->type = strtolower($type);
        $this->assets[$type] = array();
        $this->attr[$type] = array();
        $this->versions[$type] = array();
    }

    /**
     * @param        $file
     * @param string $version
     * @param array $attr
     */
    public function add($file, $version = "", $attr = array())
    {
        if (count($attr) <= 0 && is_array($version)) {
            $attr = $version;
            $version = "";
        }
        if (empty($version)) {
            $version = '?ver=' . Kecik::$version;
        } else {
            $version = '?ver=' . $version;
        }
        if (!in_array($file, $this->assets[$this->type])) {
            $this->assets[$this->type][] = $file;
            $this->attr[$this->type][] = $attr;
            $this->versions[$this->type][] = $version;
        }
    }

    /**
     * @param $file
     */
    public function delete($file)
    {
        $key = array_search($file, $this->assets[$this->type]);
        unset($this->assets[$this->type][$key]);
        unset($this->attr[$this->type][$key]);
    }

    /**
     * @param string $file
     *
     * @return string
     */
    public function render($file = '')
    {
        reset($this->assets[$this->type]);
        //reset($this->attr[$this->type]);
        $attr = '';
        if ($this->type == 'js') {
            if ($file != '') {
                $key = array_search($file, $this->assets[$this->type]);
                $version = $this->versions[$this->type][$key];
                $asset = $this->assets[$this->type][$key];
                if (preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i", $asset)) {
                    $asset_url = $asset;
                } else {
                    $asset_url = $this->BaseUrl . Config::get('path.assets') . "/$this->type/" . $asset . '.' .
                        $this->type . $version;
                }
                while (list($at, $val) = each($this->attr[$this->type][$key])) {
                    $attr .= $at . '="' . $val . '" ';
                }
                if ($key) {
                    return '<script type="text/javascript" src="' . $asset_url . '" ' . $attr . '></script>' . "\n";
                }
            } else {
                $render = '';
                while (list($key, $value) = each($this->assets[$this->type])) {
                    $asset = $this->assets[$this->type][$key];
                    $version = $this->versions[$this->type][$key];
                    if (preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i", $asset)) {
                        $asset_url = $asset;
                    } else {
                        $asset_url = $this->BaseUrl . Config::get('path.assets') . "/$this->type/" . $asset . '.' .
                            $this->type . $version;
                    }
                    $attr = '';
                    while (list($at, $val) = each($this->attr[$this->type][$key])) {
                        $attr .= $at . '="' . $val . '" ';
                    }
                    $render .= '<script type="text/javascript" src="' . $asset_url . '" ' . $attr . '></script>' . "\n";
                }

                return $render;
            }

        } elseif ($this->type == 'css') {
            if ($file != '') {
                $key = array_search($file, $this->assets[$this->type]);
                $asset = $this->assets[$this->type][$key];
                $version = $this->versions[$this->type][$key];
                if (preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i", $asset)) {
                    $asset_url = $asset;
                } else {
                    $asset_url = $this->BaseUrl . Config::get('path.assets') . "/$this->type/" . $asset . '.' .
                        $this->type . $version;
                }
                while (list($at, $val) = each($this->attr[$this->type][$key])) {
                    $attr .= $at . '="' . $val . '" ';
                }
                if ($key) {
                    return '<link rel="stylesheet" href="' . $asset_url . '" ' . $attr . ' />' . "\n";
                }
            } else {
                $render = '';
                while (list($key, $value) = each($this->assets[$this->type])) {
                    $attr = '';
                    $asset = $this->assets[$this->type][$key];
                    $version = $this->versions[$this->type][$key];
                    if (preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i", $asset)) {
                        $asset_url = $asset;
                    } else {
                        $asset_url = $this->BaseUrl . Config::get('path.assets') . "/$this->type/" . $asset . '.' .
                            $this->type . $version;
                    }
                    while (list($at, $val) = each($this->attr[$this->type][$key])) {
                        $attr .= $at . '="' . $val . '" ';
                    }
                    $render .= '<link rel="stylesheet" href="' . $asset_url . '" ' . $attr . ' />' . "\n";
                }

                return $render;
            }
        }
    }

    /**
     * @param string $file
     * @param string $version
     *
     * @return string
     */
    public function url($file = '', $version = '')
    {
        if (empty($version)) {
            $version = '?ver=' . Kecik::$version;
        }
        if (!empty($file)) {
            return $this->BaseUrl . Config::get('path.assets') . "/{$this->type}/{$file}{$version}";
        } else {
            return $this->BaseUrl . Config::get('path.assets') . "/{$this->type}/";
        }

    }

}
