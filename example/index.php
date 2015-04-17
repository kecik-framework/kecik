<?php
// Untuk composer
//require_once "vendor/autoload.php";
// Untuk CLI SERVER atau php server.php
//require_once "Kecik.php";
require_once "../Kecik/Kecik.php";

$app = new \Kecik\Kecik();

$app->config->set('path.assets', 'assets');
$app->config->set('path.mvc', 'app');
$app->config->set('path.template', 'templates');
$app->config->set('error.404', '404');

$app->assets->css->add('bootstrap.min');
$app->assets->css->add('bootstrap-theme.min');
$app->assets->css->add('starter-template');
$app->assets->js->add('jquery.min');
$app->assets->js->add('bootstrap.min');

$dbcon = @mysqli_connect(
    'localhost',
    'root',
    '',
    'kecik'
);

if ( mysqli_connect_errno($dbcon) ) {
    header('X-Error-Message: Fail Connecting', true, 500);
    die("Failed to connect to MySQL: " . mysqli_connect_error());
}


$app->get('/', new Controller\Welcome($app, $dbcon), function($controller) {
	return $controller->index();
})->template('template_kecik');

$app->get('data', new Controller\Welcome($app, $dbcon), function($controller) {
	return $controller->Data();
})->template('template_kecik');

$app->get('tambah', new Controller\Welcome($app, $dbcon), function ($controller) {
	return $controller->Form();
})->template('template_kecik');

$app->get('edit/:id', new Controller\Welcome($app, $dbcon), function ($controller, $id) {
	return $controller->Form($id);
})->template('template_kecik');

$app->get('delete/:id', new Controller\Welcome($app, $dbcon), function($controller, $id) {
	$controller->delete($id);
});

$app->post('save', new Controller\Welcome($app, $dbcon), function ($controller) {
	$controller->save();
});

$app->post('update/:id', new Controller\Welcome($app, $dbcon), function ($controller, $id) {
	$controller->update($id);
});


$app->run();

mysqli_close($dbcon);