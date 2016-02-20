<?php
namespace Controller;

use Kecik\Controller;

/**
 * Class Welcome
 * @package Controller
 */
class Welcome extends Controller
{
    public $dbcon;

    /**
     * Welcome constructor.
     * @param $dbcon
     */
    public function __construct($dbcon)
    {
        parent::__construct();
        $this->dbcon = $dbcon;
    }

    /**
     *
     */
    public function index()
    {
        $this->view('index');
    }

    /**
     *
     */
    public function Data()
    {
        $this->view('data');
    }

    /**
     * @param string $id
     */
    public function Form($id = '')
    {
        if ($id == '') {
            $url = $this->url->linkTo('index.php/save');
        } else {
            $url = $this->url->linkTo('index.php/update/' . $id);
        }

        $this->view('form', array('id' => $id, 'url' => $url));
    }

    /**
     *
     */
    public function save()
    {
        $model = new \Model\Data();
        $model->nama = $this->request->post('nama');
        $model->email = $this->request->post('email');
        $sql = $model->save();
        mysqli_query($this->dbcon, $sql);

        $this->url->redirect('index.php/data');
    }

    /**
     * @param $id
     */
    public function update($id)
    {
        $model = new \Model\Data(array('id' => $id));
        $model->nama = $this->request->post('nama');
        $model->email = $this->request->post('email');
        $sql = $model->save();
        mysqli_query($this->dbcon, $sql);

        $this->url->redirect('index.php/data');
    }

    /**
     * @param $id
     */
    public function delete($id)
    {
        $model = new \Model\Data(array('id' => $id));
        $sql = $model->delete();
        mysqli_query($this->dbcon, $sql);

        $this->url->redirect('index.php/data');
    }
}