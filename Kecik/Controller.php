<?php
/**
 * Controller
 *
 * @package     Kecik
 * @author      Dony Wahyu Isp
 * @since       1.0.1-alpha
 **/
namespace Kecik;

if ( ! class_exists('Kecik\Controller') ) {
    /**
     * Class Controller
     *
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
         * @param       $file
         * @param array $param
         *
         * @return string
         */
        protected function view($file, $param = [])
        {
            return View::render($file, $param, $this);
        }
    }
}