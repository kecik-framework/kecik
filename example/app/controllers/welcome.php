<?php
namespace Controllers;

use Kecik\Controllers;
use Models\Data;

/**
 * Class Welcome
 * @package Controller
 */
class Welcome extends Controllers {
	public $dbcon;

	/**
	 * Welcome constructor.
	 *
	 * @param $dbcon
	 */
	public function __construct( $dbcon ) {
		parent::__construct();
		$this->dbcon = $dbcon;
	}

	/**
	 *
	 */
	public function index() {
		return $this->view( 'index' );
	}

	/**
	 *
	 */
	public function Data() {
		return $this->view( 'data' );
	}

	/**
	 * @param string $id
	 *
	 * @return string
	 */
	public function Form( $id = '' ) {
		if ( $id == '' ) {
			$url = $this->url->linkTo( 'index.php/save' );
		} else {
			$url = $this->url->linkTo( 'index.php/update/' . $id );
		}

		return $this->view( 'form', array( 'id' => $id, 'url' => $url ) );
	}

	/**
	 *
	 */
	public function save() {
		$model        = new Data();
		$model->name  = $this->request->post( 'name' );
		$model->email = $this->request->post( 'email' );
		$sql          = $model->save();
		var_dump($this->dbcon);
		mysqli_query( $this->dbcon, $sql );

		$this->url->redirect( 'index.php/data' );
	}

	/**
	 * @param $id
	 */
	public function update( $id ) {
		$model        = new Data( array( 'id' => $id ) );
		$model->name  = $this->request->post( 'name' );
		$model->email = $this->request->post( 'email' );
		$sql          = $model->save();
		mysqli_query( $this->dbcon, $sql );

		$this->url->redirect( 'index.php/data' );
	}

	/**
	 * @param $id
	 */
	public function delete( $id ) {
		$model = new Data( array( 'id' => $id ) );
		$sql   = $model->delete();
		mysqli_query( $this->dbcon, $sql );

		$this->url->redirect( 'index.php/data' );
	}
}