<?php
namespace Controller;

use Kecik\Controller;

class Welcome extends Controller{
	var $app;
	var $dbcon;

	public function __construct($app, $dbcon) {
		parent::__construct();
		$this->app = $app;
		$this->dbcon = $dbcon;
	}

	public function index() {
		$this->view('index');
	}

	public function Data() {
		$this->view('data', array('app'=>$this->app, 'dbcon'=>$this->dbcon));
	}

	public function Form($id='') {
		if ($id=='') 
			$url = $this->app->url->linkto('index.php/save');
		else
			$url = $this->app->url->linkto('index.php/update/'.$id);

		$this->view('form', array('id'=>$id, 'url'=>$url));
	}

	public function save() {
		$input = $this->app->input;

		$model = new \Model\Data();
			$model->nama = $input->post('nama');
			$model->email = $input->post('email');
		$sql = $model->save();
		mysqli_query($this->dbcon, $sql);
		
		$this->app->url->redirect('index.php/data');
	}

	public function update($id) {
		$input = $this->app->input;

		$model = new \Model\Data(array('id'=>$id));
			$model->nama = $input->post('nama');
			$model->email = $input->post('email');
		$sql = $model->save();
		mysqli_query($this->dbcon, $sql);

		$this->app->url->redirect('index.php/data');
	}

	public function delete($id) {
		$model = new \Model\Data(array('id'=>$id));
		$sql = $model->delete();
		mysqli_query($this->dbcon, $sql);

		$this->app->url->redirect('index.php/data');
	}
}