<?php
/**
 * Controller
 * @package     Kecik
 * @author      Dony Wahyu Isp
 * @since       1.0.1-alpha
 **/
namespace Kecik;

if (! class_exists('Kecik\Controller')) {
    class Controller 
    {
        protected $request = '';
        protected $url = '';
        protected $assets = '';
        protected $config = '';
        protected $route = '';
        //protected $container = '';
        //protected $db = '';

        /**
         * Construtor Controller
         **/
        public function __construct() 
        {
            //** ID: Silakan tambah inisialisasi controller sendiri disini
            //** EN: Please add your initialitation of controller in this

            //-- ID: Akhir tambah inisialisasi sendiri
            //-- EN: End add your initialitation

            $app = Kecik::getInstance();
            $this->request = $app->request;
            $this->url = $app->url;
            $this->assets = $app->assets;
            $this->config = $app->config;
            $this->route = $app->route;

            $libraries = $app->getLibrariesEnabled();

            while(list($idx, $library) = each($libraries)) {

                if (isset($library[1]) && !empty($library[1])) {
                    $lib = $library[1];
                    $this->$lib = $app->$lib;
                }

            }

            /*if (isset($app->container))
                $this->container = $app->container;
            if (isset($app->db))
                $this->db = $app->db;
            if (isset($app->session))
                $this->session = $app->session;
            if (isset($app->cookie))
                $this->cookie = $app->cookie;
            if (isset($app->language))
                $this->language = $app->language;*/
        }

        //** ID: Silakan tambah fungsi controller sendiri disini
        //** EN: Please add your function/method of controller in this

        //-- ID: Akhir tambah fungsi sendiri
        //-- EN: End add your function/method

        /**
         * view
         * ID: Funngsi untuk menampilkan view
         * EN: Function for displaying view
         * @param string $file
         * @param array $param
         **/
        protected function view($file, $param=array()) 
        {
            extract($param);
        
            if (! is_array($file)) {
                $path =  explode('\\', get_class($this));

                if (count($path) > 2) {
                    $view_path = '';

                    for($i = 0; $i < count($path)-2; $i++) {
                        $view_path .= strtolower($path[$i]).'/';
                    }   

                    $view_path = Config::get('path.mvc').'/'.$view_path;
                } else {
                    $view_path = Config::get('path.mvc');
                }

                if (php_sapi_name() == 'cli') {
                    $view_path = Config::get('path.basepath').'/'.$view_path;
                }
            } else {
                $view_path = Config::get('path.mvc');

                if (isset($file[1])) {
                    $view_path .= '/'.$file[0];
                    $file = $file[1];
                } else {
                    $file = $file[0];
                }

                if (php_sapi_name() == 'cli') {
                    $view_path = Config::get('path.basepath').'/'.$view_path; 
                }
            }
            
            ob_start();
                include $view_path.'/views/'.$file.'.php';
            return ob_get_clean();
        }
    }
}