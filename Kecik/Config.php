<?php
/**
 * Config
 * @package     Kecik
 * @author      Dony Wahyu Isp
 * @since       1.0.1-alpha
 **/
namespace Kecik;

/**
 * Class Config
 * @package Kecik
 */
class Config {

	private static $config;

	/**
	 * Initialize for Config Class
	 */
	public static function init() {
		self::$config = array(
			'path.assets'        => '',
			'path.templates'     => '',
			'mod_rewrite'        => FALSE,
			'index'              => '',
			'template.open_tag'  => '{{',
			'template.close_tag' => '}}'
		);
	}

	/**
	 * Set configuration
	 *
	 * @param $key
	 * @param $value
	 */
	public static function set( $key, $value ) {
		self::$config[ strtolower( $key ) ] = $value;
	}

	/**
	 * Get configuration
	 *
	 * @param $key
	 *
	 * @return string
	 */
	public static function get( $key ) {

		if ( isset( self::$config[ strtolower( $key ) ] ) ) {
			return self::$config[ strtolower( $key ) ];
		} else {
			return '';
		}

	}

	/**
	 * Delete configuration
	 *
	 * @param $key
	 */
	public static function delete( $key ) {

		if ( isset( self::$config[ strtolower( $key ) ] ) ) {
			unset( self::$config[ strtolower( $key ) ] );
		}

	}

}

Config::init();
