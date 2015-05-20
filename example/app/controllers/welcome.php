<?php
namespace Controller;

use Kecik\Controller;

class Welcome extends Controller{
	var $app;
	var $dbcon;

	public function __construct($app, $dbcon) {
		parent::__construct($app);
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
			$url = $this->url->linkTo('index.php/save');
		else
			$url = $this->url->linkTo('index.php/update/'.$id);

		$this->view('form', array('id'=>$id, 'url'=>$url));
	}

	public function save() {
		$model = new \Model\Data();
			$model->nama = $this->request->post('nama');
			$model->email = $this->request->post('email');
		$sql = $model->save();
		mysqli_query($this->dbcon, $sql);
		
		$this->url->redirect('index.php/data');
	}

	public function update($id) {
		$model = new \Model\Data(array('id'=>$id));
			$model->nama = $this->request->post('nama');
			$model->email = $this->request->post('email');
		$sql = $model->save();
		mysqli_query($this->dbcon, $sql);

		$this->url->redirect('index.php/data');
	}

	public function delete($id) {
		$model = new \Model\Data(array('id'=>$id));
		$sql = $model->delete();
		mysqli_query($this->dbcon, $sql);

		$this->url->redirect('index.php/data');
	}
}