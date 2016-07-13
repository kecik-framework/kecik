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
/**
 * Class Request
 * @package Kecik
 */
class Request {

	/**
	 * Request constructor.
	 */
	public function __construct() {

	}

	/**
	 * Get/Set variable from Get Method
	 *
	 * @param string $name
	 * @param string $value
	 *
	 * @return mixed|null
	 */
	public static function get( $name = '', $value = '' ) {

		if ( ! empty( $value ) ) {
			$_GET[ $name ] = $value;
		}

		if ( empty( $name ) ) {
			return $_GET;
		} else {
			return ( isset( $_GET[ $name ] ) ) ? $_GET[ $name ] : NULL;
		}

	}

	/**
	 * Get/Set variable from Post Method
	 *
	 * @param string $name
	 * @param string $value
	 *
	 * @return mixed|null
	 */
	public static function post( $name = '', $value = '' ) {

		if ( ! empty( $value ) ) {
			$_POST[ $name ] = $value;
		}

		if ( empty( $name ) ) {
			return $_POST;
		} else {
			return ( isset( $_POST[ $name ] ) ) ? $_POST[ $name ] : NULL;
		}

	}

	/**
	 * Get/Set variable from Put Method
	 *
	 * @param string $name
	 * @param string $value
	 *
	 * @return mixed|null
	 */
	public static function put( $name = '', $value = '' ) {

		if ( ! empty( $value ) ) {
			$GLOBALS['_PUT'][ $name ] = $value;
		}

		if ( empty( $name ) ) {
			return ( isset( $GLOBALS['_PUT'] ) ) ? $GLOBALS['_PUT'] : NULL;
		} else {
			return ( isset( $GLOBALS['_PUT'][ $name ] ) ) ? $GLOBALS['_PUT'][ $name ] : NULL;
		}

	}

	/**
	 * Get/Set variable from Delete Method
	 *
	 * @param string $name
	 * @param string $value
	 *
	 * @return mixed|null
	 */
	public static function delete( $name = '', $value = '' ) {

		if ( ! empty( $value ) ) {
			$GLOBALS['_DELETE'][ $name ] = $value;
		}

		if ( empty( $name ) ) {
			return ( isset( $GLOBALS['_DELETE'] ) ) ? $GLOBALS['_DELETE'] : NULL;
		} else {
			return ( isset( $GLOBALS['_DELETE'][ $name ] ) ) ? $GLOBALS['_DELETE'][ $name ] : NULL;
		}

	}

	/**
	 * Get/Set variable from Patch Method
	 *
	 * @param string $name
	 * @param string $value
	 *
	 * @return mixed|null
	 */
	public static function patch( $name = '', $value = '' ) {

		if ( ! empty( $value ) ) {
			$GLOBALS['_PATCH'][ $name ] = $value;
		}

		if ( empty( $name ) ) {
			return ( isset( $GLOBALS['_PATCH'] ) ) ? $GLOBALS['_PATCH'] : NULL;
		} else {
			return ( isset( $GLOBALS['_PATCH'][ $name ] ) ) ? $GLOBALS['_PATCH'][ $name ] : NULL;
		}

	}

	/**
	 * Get/Set variable from Options Method
	 *
	 * @param string $name
	 * @param string $value
	 *
	 * @return mixed|null
	 */
	public static function options( $name = '', $value = '' ) {

		if ( ! empty( $value ) ) {
			$GLOBALS['_OPTIONS'][ $name ] = $value;
		}

		if ( empty( $name ) ) {
			return ( isset( $GLOBALS['_OPTIONS'] ) ) ? $GLOBALS['_OPTIONS'] : NULL;
		} else {
			return ( isset( $GLOBALS['_OPTIONS'][ $name ] ) ) ? $GLOBALS['_OPTIONS'][ $value ] : NULL;
		}

	}

	/**
	 * Get value of Upload file
	 *
	 * @param $file
	 *
	 * @return UploadFile
	 */
	public static function file( $file ) {
		$file = $_FILES[ $file ];

		return new UploadFile( $file );
	}

	/**
	 * Get variable from SERVER
	 *
	 * @param string $var
	 *
	 * @return mixed|null
	 */
	public static function server( $var = '' ) {

		if ( empty( $var ) ) {
			return $_SERVER;
		} else {
			return ( isset( $_SERVER[ $var ] ) ) ? $_SERVER[ $var ] : NULL;
		}


	}
}


/**
 * Class UploadFile
 * @package Kecik
 */
class UploadFile extends \SplFileInfo {
	private $file;

	/**
	 * UploadFile constructor.
	 *
	 * @param $file
	 */
	public function __construct( $file ) {

		if ( isset( $file['name'] ) ) {
			parent::__construct( $file['name'] );
			$this->file = $file;
		}

	}

	/**
	 * Move file from temporary to actual location
	 *
	 * @param        $destination
	 * @param string $newName
	 *
	 * @return string
	 * @throws FileException
	 */
	public function move( $destination, $newName = '' ) {
		$source = $this->file['tmp_name'];

		if ( $destination != '' && substr( $destination, - 1 ) != '/' ) {
			$destination .= '/';
		}

		if ( ! empty( $newName ) ) {
			$target = $destination . $newName;
		} else {
			$target = $destination . $this->file['name'];
		}

		if ( ! @move_uploaded_file( $source, $target ) ) {
			$error = error_get_last();

			throw new FileException(
				sprintf(
					'Could not move the file "%s" to "%s" (%s)',
					$this->getPathname(),
					$target,
					strip_tags( $error['message'] )
				)
			);
		}

		@chmod( $target, 0666 & ~umask() );

		return $target;

	}

	/**
	 * @return string|null
	 */
	public function __tostring() {
		return ( isset( $this->file['name'] ) ) ? $this->file['name'] : NULL;
	}
}
