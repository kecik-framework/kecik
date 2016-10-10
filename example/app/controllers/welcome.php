<?php
namespace Controllers;

use Kecik\Controller;
use Kecik\Request;
use Kecik\Url;
use Models\Data;

/**
 * Class Welcome
 *
 * @package Controller
 */
class Welcome extends Controller
{
    public $dbcon;

    /**
     * Welcome constructor.
     *
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
        return $this->view('index');
    }

    /**
     *
     */
    public function Data()
    {
        return $this->view('data', [ 'dbcon' => $this->dbcon ]);
    }

    /**
     * @param string $id
     *
     * @return string
     */
    public function Form($id = '')
    {
        if ( $id == '' ) {
            $url = Url::linkTo('save');
        } else {
            $url = Url::linkTo('update/' . $id);
        }

        return $this->view('form', [ 'dbcon' => $this->dbcon, 'id' => $id, 'url' => $url ]);
    }

    /**
     *
     */
    public function save()
    {
        $model = new Data();
        $model->nama = Request::post('nama');
        $model->email = Request::post('email');
        $sql = $model->save();
        mysqli_query($this->dbcon, $sql);

        Url::redirect('data');
    }

    /**
     * @param $id
     */
    public function update($id)
    {
        $model = new Data([ 'id' => $id ]);
        $model->nama = Request::post('nama');
        $model->email = Request::post('email');
        $sql = $model->save();
        mysqli_query($this->dbcon, $sql);

        Url::redirect('data');
    }

    /**
     * @param $id
     */
    public function delete($id)
    {
        $model = new Data([ 'id' => $id ]);
        $sql = $model->delete();
        mysqli_query($this->dbcon, $sql);

        Url::redirect('data');
    }
}