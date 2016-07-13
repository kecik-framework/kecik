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
class Url {
	private $_protocol;
	private $_base_url;
	private $_base_path;
	private $_index;

	/**
	 * Url constructor.
	 *
	 * @param $protocol
	 * @param $baseUrl
	 * @param $basePath
	 */
	public function __construct( $protocol, $baseUrl, $basePath ) {
		$this->_protocol  = $protocol;
		$this->_base_url  = $baseUrl;
		$this->_base_path = $basePath;

		if ( Config::get( 'mod_rewrite' ) === FALSE ) {
			$this->_index = basename( $_SERVER["SCRIPT_FILENAME"], '.php' ) . '.php/';
			Config::set( 'index', $this->_index );
		}

	}

	/**
	 * Get protocol
	 *
	 * @return mixed
	 */
	public function protocol() {
		return $this->_protocol;
	}

	/**
	 * Get base path of public project
	 *
	 * @return mixed
	 */
	public function basePath() {
		return $this->_base_path;
	}

	/**
	 * Get base Url of public project
	 *
	 * @return mixed
	 */
	public function baseUrl() {
		return $this->_base_url;
	}

	/**
	 * redirect to another route
	 *
	 * @param $link
	 */
	public function redirect( $link ) {
		header( 'Location: ' . $this->linkTo($link) );
		exit();
	}

	/**
	 * print of url route
	 *
	 * @param $link
	 */
	public function to( $link ) {
		echo $this->linkTo( $link );
	}

	/**
	 * Create Url route
	 *
	 * @param $link
	 *
	 * @return string
	 */
	public function linkTo( $link ) {

		if ( $link == '/' ) {
			$link = '';
		}

		return $this->_base_url . $this->_index . $link;
	}
}
//--