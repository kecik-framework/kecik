<?php
// Untuk composer
//require_once "vendor/autoload.php";
// Untuk CLI SERVER atau php server.php
//require_once "Kecik.php";
require_once "../Kecik/Kecik.php";

use Kecik\Assets;
use Kecik\Config;
use Kecik\Kecik;
use Kecik\Route;


$config = array(
    'path.basepath' => __DIR__ . '/'
);

$dbcon = NULL;

Kecik::run(function () use (&$dbcon) {
    Config::apply(function() {
        $this->set('path.basepath', __DIR__ . '/');
        $this->set('path.assets', 'assets');
        $this->set('path.mvc', 'app');
        $this->set('path.template', 'templates');
        $this->set('error.404', '404');
    });
    
    Assets::$css->add('bootstrap.min');
    Assets::$css->add('bootstrap-theme.min');
    Assets::$css->add('starter-template');
    Assets::$js->add('jquery.min');
    Assets::$js->add('bootstrap.min');

    $this->before(function() use(&$dbcon) {
        $dbcon = @mysqli_connect(
            'localhost',
            'root',
            '',
            'kecik'
        );
    
        if (mysqli_connect_errno($dbcon)) {
            header('X-Error-Message: Fail Connecting', true, 500);
            die("Failed to connect to MySQL: " . mysqli_connect_error());
        }
    });
    
    Route::get(
        '/',
        function () use ($dbcon) {
            $controller = new Controllers\Welcome($dbcon);
            return $controller->index();
        }
    ); //->template('template_kecik');
    
    Route::get(
        'data',
        function () use ($dbcon) {
            $controller = new Controllers\Welcome($dbcon);
            return $controller->Data();
        }
    ); //->template('template_kecik');
    
    Route::get(
        'tambah',
        function () use ($dbcon) {
            $controller = new Controllers\Welcome($dbcon);
            return $controller->Form();
        }
    ); //->template('template_kecik');
    
    Route::get(
        'edit/:id',
        function ($id) use ($dbcon) {
            $controller = new Controllers\Welcome($dbcon);
            return $controller->Form($id);
        }
    ); //->template('template_kecik');
    
    Route::get(
        'delete/:id',
        function ($id) use ($dbcon) {
            $controller = new Controllers\Welcome($dbcon);
            $controller->delete($id);
        }
    );
    
    Route::post(
        'save',
        function () use ($dbcon) {
            $controller = new Controllers\Welcome($this, $dbcon);
            $controller->save();
        }
    );
    
    Route::post(
        'update/:id',
        function ($id) use ($dbcon) {
            $controller = new Controllers\Welcome($dbcon);
            $controller->update($id);
        }
    );
    
});



mysqli_close($dbcon);