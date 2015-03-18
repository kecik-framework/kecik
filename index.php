<?php
$manual = FALSE;
require_once __DIR__ . "/../vendor/autoload.php";

$app = new Kecik\Kecik();

$app->config->set('path.assets', 'assets');
$app->config->set('path.app', 'app');
$app->config->set('path.template', 'templates');
$app->config->set('error.404', '404');
$app->assets->css->add('boostrap');
$app->assets->js->add('jquery.min');
$app->assets->js->add('boostrap.min');
//echo $app->assets->js->render('boostrap.min');
//echo $app->assets->images('kecik.jpg');

$app->get('/', function() {
	echo 'Hello Kecik';
});

$app->get('hello/:nama', function ($nama) {
	echo 'Hello '.$nama;
})->template('template_kecik');

$app->get('selamat_datang/:nama', new Controller\Welcome($app), function ($controller, $nama) {
	$controller->index($nama);
})->template('template_kecik');

$_POST['x'] = 'Kecik';
$app->post('post/:name', function ($name) use ($app) {
	echo 'POST: '.$app->input->post('x');
});

$app->run();