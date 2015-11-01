<?php

namespace Kecik;

/**
 * Request
 * 
 * @package     Kecik
 * @author      Dony Wahyu Isp
 * @since       1.0-alpha
 **/
class Request {
    public function __construct() {

    }

    /**
     * get
     * @param string $var
     * @return mixed
     **/
    public function get() {
        $args = func_get_args();

        $var = (count($args) > 0)?$args[0]:'';

        if (isset($args[1]))
            $_GET[$var] = $args[1];

        if (!isset($args[0]))
            return $_GET;
        else
            return (isset($_GET[$var]))? $_GET[$var] : NULL;
    }

    /**
     * post
     * @param string $var
     * @return mixed
     **/
    public function post() {
        $args = func_get_args();

        $var = (count($args) > 0)?$args[0]:'';

        if (isset($args[1]))
            $_POST[$var] = $args[1];

        if (!isset($args[0]))
            return $_POST;
        else
            return (isset($_POST[$var]))? $_POST[$var] : NULL;
    }

    /**
     * put
     * @param string $var
     * @return mixed
     **/
    public function put() {
        $args = func_get_args();

        $var = (count($args) > 0)?$args[0]:'';

        if (isset($args[1]))
            $GLOBALS['_PUT'][$var] = $args[1];

        if (!isset($args[0]))
            return (isset($GLOBALS['_PUT']))? $GLOBALS['_PUT']:NULL;
        else
            return (isset($GLOBALS['_PUT'][$var]))? $GLOBALS['_PUT'][$var] : NULL;
    }

    /**
     * delete
     * @param string $var
     * @return mixed
     **/
    public function delete() {
        $args = func_get_args();

        $var = (count($args) > 0)?$args[0]:'';

        if (isset($args[1]))
            $GLOBALS['_DELETE'][$var] = $args[1];

        if (!isset($args[0]))
            return (isset($GLOBALS['_DELETE']))? $GLOBALS['_DELETE']:NULL;
        else
            return (isset($GLOBALS['_DELETE'][$var]))? $GLOBALS['_DELETE'][$var] : NULL;
    }

    /**
     * patch
     * @param string $var
     * @return mixed
     **/
    public function patch() {
        $args = func_get_args();

        $var = (count($args) > 0)?$args[0]:'';

        if (isset($args[1]))
            $GLOBALS['_PATCH'][$var] = $args[1];

        if (!isset($args[0]))
            return (isset($GLOBALS['_PATCH']))? $GLOBALS['_PATCH']:NULL;
        else
            return (isset($GLOBALS['_PATCH'][$var]))? $GLOBALS['_PATCH'][$var] : NULL;
    }

    /**
     * options
     * @param string $var
     * @return mixed
     **/
    public function options() {
        $args = func_get_args();

        $var = (count($args) > 0)?$args[0]:'';

        if (isset($args[1]))
            $GLOBALS['_OPTIONS'][$var] = $args[1];

        if (!isset($args[0]))
            return (isset($GLOBALS['_OPTIONS']))? $GLOBALS['_OPTIONS']:NULL;
        else
            return (isset($GLOBALS['_OPTIONS'][$var]))? $GLOBALS['_OPTIONS'][$var] : NULL;
    }

    /**
     * file
     * @param string $var
     * @return mixed
     **/
    public function file($file) {
        $file = $_FILES[$file];
        return new UploadFile($file);
    }

    /**
     * server
     * @param string $var
     * @return mixed
     **/
    public function server($var='') {
        if ($var == '')
            return $_SERVER;
        else
            return (isset($_SERVER[$var]))? $_SERVER[$var] : NULL;
    }
}

/**
 * UploadFile
 * 
 * @package     Kecik
 * @author      Dony Wahyu Isp
 * @since       1.0-alpha
 **/
class UploadFile extends \SplFileInfo {
    private $file;

    public function __construct($file) {
        if (isset($file['name'])) {
            parent::__construct($file['name']);
            $this->file = $file;
        }
    }

    public function move($destination, $newName='') {
        $source = $this->file['tmp_name'];

        if ($destination != '' && substr($destination, -1) != '/')
            $destination .= '/';

        if (!empty($newName)) 
            $target = $destination.$newName;
        else
            $target = $destination.$this->file['name'];

        if (!@move_uploaded_file($source, $target)) {
            $error = error_get_last();
            throw new FileException(sprintf('Could not move the file "%s" to "%s" (%s)', $this->getPathname(), $target, strip_tags($error['message'])));
        }
        @chmod($target, 0666 & ~umask());
        return $target;

    }
    
    public function __tostring() {
        return (isset($this->file['name']))? $this->file['name']:NULL;
    }
}
