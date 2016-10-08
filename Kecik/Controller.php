<?php
/**
 * Controller
 * @package     Kecik
 * @author      Dony Wahyu Isp
 * @since       1.0.1-alpha
 **/
namespace Kecik;

if (!class_exists('Kecik\Controller')) {
    /**
     * Class Controller
     * @package Kecik
     */
    class Controller
    {

        /**
         * Controller constructor.
         */
        public function __construct()
        {
            //** ID: Silakan tambah inisialisasi controller sendiri disini
            //** EN: Please add your initialitation of controller in this

            //-- ID: Akhir tambah inisialisasi sendiri
            //-- EN: End add your initialitation

        }

        //** ID: Silakan tambah fungsi controller sendiri disini
        //** EN: Please add your function/method of controller in this

        //-- ID: Akhir tambah fungsi sendiri
        //-- EN: End add your function/method

        /**
         * @param $file
         * @param array $param
         * @return string
         */
        protected function view($file, $param = array())
        {
            extract($param);

            if (!is_array($file)) {
                $path = explode('\\', get_class($this));

                if (count($path) > 2) {
                    $view_path = '';

                    for ($i = 0; $i < count($path) - 2; $i++) {
                        $view_path .= strtolower($path[$i]) . '/';
                    }

                    $view_path = Config::get('path.mvc') . '/' . $view_path;
                } else {
                    $view_path = Config::get('path.mvc');
                }

                if (php_sapi_name() == 'cli') {
                    $view_path = Config::get('path.basepath') . '/' . $view_path;
                }
            } else {
                $view_path = Config::get('path.mvc');

                if (isset($file[1])) {
                    $view_path .= '/' . $file[0];
                    $file = $file[1];
                } else {
                    $file = $file[0];
                }

                if (php_sapi_name() == 'cli') {
                    $view_path = Config::get('path.basepath') . '/' . $view_path;
                }
            }

            ob_start();
            include $view_path . '/Views/' . $file . '.php';
            return ob_get_clean();
        }
    }
}