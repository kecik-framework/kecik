<?php

namespace Kecik;
    
/**
 * Request
 *
 * @package     Kecik
 * @author      Dony Wahyu Isp
 * @since       1.0-alpha
 **/

/**
 * Class Request
 * @package Kecik
 */
class Request
{
    
    private static $instance;
    
    /**
     * @return mixed|null
     */
    public static function get( $var = NULL, $value = NULL )
    {
        if ( !is_null($value) ) {
            $_GET[ $var ] = $value;
        }
        
        if ( is_null($var) ) {
            return $_GET;
        } else {
            return ( isset( $_GET[ $var ] ) ) ? $_GET[ $var ] : NULL;
        }
        
    }
    
    /**
     * @return mixed|null
     */
    public static function post( $var = NULL, $value = NULL )
    {
        if ( !is_null($value) ) {
            $_POST[ $var ] = $value;
        }
        
        if ( is_null($var) ) {
            return $_POST;
        } else {
            return ( isset( $_POST[ $var ] ) ) ? $_POST[ $var ] : NULL;
        }
        
    }
    
    /**
     * @return mixed|null
     */
    public static function put( $var = NULL, $value = NULL )
    {
        
        if ( !is_null($value) ) {
            $GLOBALS[ '_PUT' ][ $var ] = $value;
        }
        
        if ( is_null($var) ) {
            return ( isset( $GLOBALS[ '_PUT' ] ) ) ? $GLOBALS[ '_PUT' ] : NULL;
        } else {
            return ( isset( $GLOBALS[ '_PUT' ][ $var ] ) ) ? $GLOBALS[ '_PUT' ][ $var ] : NULL;
        }
        
    }
    
    /**
     * @return mixed|null
     */
    public static function delete( $var = NULL, $value = NULL )
    {
        
        if ( !is_null($value) ) {
            $GLOBALS[ '_DELETE' ][ $var ] = $value;
        }
        
        if ( is_null($var) ) {
            return ( isset( $GLOBALS[ '_DELETE' ] ) ) ? $GLOBALS[ '_DELETE' ] : NULL;
        } else {
            return ( isset( $GLOBALS[ '_DELETE' ][ $var ] ) ) ? $GLOBALS[ '_DELETE' ][ $var ] : NULL;
        }
        
    }
    
    /**
     * @return mixed|null
     */
    public static function patch( $var = NULL, $value = NULL )
    {
        
        if ( !is_null($value) ) {
            $GLOBALS[ '_PATCH' ][ $var ] = $value;
        }
        
        if ( is_null($var) ) {
            return ( isset( $GLOBALS[ '_PATCH' ] ) ) ? $GLOBALS[ '_PATCH' ] : NULL;
        } else {
            return ( isset( $GLOBALS[ '_PATCH' ][ $var ] ) ) ? $GLOBALS[ '_PATCH' ][ $var ] : NULL;
        }
        
    }
    
    /**
     * @return mixed|null
     */
    public static function options( $var = NULL, $value = NULL )
    {
        if ( !is_null( $value ) ) {
            $GLOBALS[ '_OPTIONS' ][ $var ] = $value;
        }
        
        if ( is_null( $var ) ) {
            return ( isset( $GLOBALS[ '_OPTIONS' ] ) ) ? $GLOBALS[ '_OPTIONS' ] : NULL;
        } else {
            return ( isset( $GLOBALS[ '_OPTIONS' ][ $var ] ) ) ? $GLOBALS[ '_OPTIONS' ][ $var ] : NULL;
        }
        
    }
    
    /**
     * @param $file
     *
     * @return UploadFile
     */
    public static function file( $file )
    {
        $file = $_FILES[ $file ];
        
        return new UploadFile($file);
    }
    
    /**
     * @param string $var
     *
     * @return mixed|null
     */
    public static function server( $var = null )
    {
        
        if ( is_null($var) ) {
            return $_SERVER;
        } else {
            return ( isset( $_SERVER[ $var ] ) ) ? $_SERVER[ $var ] : NULL;
        }
        
        
    }
    
    /**
     * @return bool
     */
    public static function isPost()
    {
        
        if ( isset( $_SERVER[ 'REQUEST_METHOD' ] ) ) {
            
            if ( $_SERVER[ 'REQUEST_METHOD' ] == 'POST' && !isset( $_POST[ '_METHOD' ] ) ) {
                return TRUE;
            } else {
                return FALSE;
            }
            
        } else {
            return FALSE;
        }
        
    }
    
    /**
     * @return bool
     */
    public static function isGet()
    {
        
        if ( isset( $_SERVER[ 'REQUEST_METHOD' ] ) ) {
            
            if ( $_SERVER[ 'REQUEST_METHOD' ] == 'GET' && !isset( $_POST[ '_METHOD' ] ) ) {
                return TRUE;
            } else {
                return FALSE;
            }
            
        } else {
            return TRUE;
        }
        
    }
    
    /**
     * @return bool
     */
    public static function isPut()
    {
        if ( isset( $_SERVER[ 'REQUEST_METHOD' ] ) ) {
            
            if ( $_SERVER[ 'REQUEST_METHOD' ] == 'PUT' || ( isset( $_POST[ '_METHOD' ] ) && $_POST[ '_METHOD' ] == 'PUT' ) ) {
                parse_str(file_get_contents("php://input"), $vars);
                
                if ( isset( $vars[ '_METHOD' ] ) ) {
                    unset( $vars[ '_METHOD' ] );
                }
                
                $GLOBALS[ '_PUT' ] = $_PUT = $vars;
                
                return TRUE;
            } else {
                return FALSE;
            }
            
        } else
            return FALSE;
        
        
    }
    
    /**
     * @return bool
     */
    public static function isDelete()
    {
        if ( isset( $_SERVER[ 'REQUEST_METHOD' ] ) ) {
            
            if ( $_SERVER[ 'REQUEST_METHOD' ] == 'DELETE' || ( isset( $_POST[ '_METHOD' ] ) && $_POST[ '_METHOD' ] == 'DELETE' ) ) {
                parse_str(file_get_contents("php://input"), $vars);
                
                if ( isset( $vars[ '_METHOD' ] ) ) {
                    unset( $vars[ '_METHOD' ] );
                }
                
                $GLOBALS[ '_DELETE' ] = $_DELETE = $vars;
                
                return TRUE;
            } else {
                return FALSE;
            }
            
        } else {
            return FALSE;
        }
        
    }
    
    /**
     * @return bool
     */
    public static function isPatch()
    {
        if ( isset( $_SERVER[ 'REQUEST_METHOD' ] ) ) {
            
            if ( $_SERVER[ 'REQUEST_METHOD' ] == 'PATCH' || ( isset( $_POST[ '_METHOD' ] ) && $_POST[ '_METHOD' ] == 'PATCH' ) ) {
                parse_str(file_get_contents("php://input"), $vars);
                
                if ( isset( $vars[ '_METHOD' ] ) ) {
                    unset( $vars[ '_METHOD' ] );
                }
                
                $GLOBALS[ '_PATCH' ] = $_PATCH = $vars;
                
                return TRUE;
            } else {
                return FALSE;
            }
            
        } else {
            return FALSE;
        }
        
    }
    
    /**
     * @return bool
     */
    public static function isOptions()
    {
        if ( isset( $_SERVER[ 'REQUEST_METHOD' ] ) ) {
            
            if ( $_SERVER[ 'REQUEST_METHOD' ] == 'OPTIONS' || ( isset( $_POST[ '_METHOD' ] ) && $_POST[ '_METHOD' ] == 'OPTIONS' ) ) {
                parse_str(file_get_contents("php://input"), $vars);
                
                if ( isset( $vars[ '_METHOD' ] ) ) {
                    unset( $vars[ '_METHOD' ] );
                }
                
                $GLOBALS[ '_OPTIONS' ] = $_OPTIONS = $vars;
                
                return TRUE;
            } else {
                return FALSE;
            }
            
        } else {
            return FALSE;
        }
        
    }
    
    /**
     * @return bool
     */
    public static function isAjax()
    {
        
        if ( filter_input(INPUT_SERVER, 'HTTP_X_REQUESTED_WITH') === 'xmlhttprequest' ) {
            return TRUE;
        } else {
            return FALSE;
        }
        
    }
    
    public static function init() {
        if (is_null(self::$instance)) {
            self::$instance;
        }
    }
}


/**
 * Class UploadFile
 * @package Kecik
 */
class UploadFile extends \SplFileInfo
{
    private $file;
    
    /**
     * UploadFile constructor.
     *
     * @param $file
     */
    public function __construct( $file )
    {
        
        if ( isset( $file[ 'name' ] ) ) {
            parent::__construct($file[ 'name' ]);
            $this->file = $file;
        }
        
    }
    
    /**
     * @param        $destination
     * @param string $newName
     *
     * @return string
     * @throws FileException
     */
    public function move( $destination, $newName = '' )
    {
        $source = $this->file[ 'tmp_name' ];
        
        if ( $destination != '' && substr($destination, -1) != '/' ) {
            $destination .= '/';
        }
        
        if ( !empty( $newName ) ) {
            $target = $destination . $newName;
        } else {
            $target = $destination . $this->file[ 'name' ];
        }
        
        if ( !@move_uploaded_file($source, $target) ) {
            $error = error_get_last();
            
            throw new FileException(
                sprintf(
                    'Could not move the file "%s" to "%s" (%s)',
                    $this->getPathname(),
                    $target,
                    strip_tags($error[ 'message' ])
                )
            );
        }
        
        @chmod($target, 0666 & ~umask());
        
        return $target;
        
    }
    
    /**
     * @return string|null
     */
    public function __tostring()
    {
        return ( isset( $this->file[ 'name' ] ) ) ? $this->file[ 'name' ] : NULL;
    }
    
}


Request::init();