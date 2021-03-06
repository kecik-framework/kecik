**Kecik Framework**
===================

> **PayPal**: [![](https://www.paypalobjects.com/en_US/i/btn/btn_donate_LG.gif)](https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=dony_cavalera_md%40yahoo%2ecom&lc=US&item_name=Dony%20Wahyu%20Isp&no_note=0&currency_code=USD&bn=PP%2dDonationsBF%3abtn_donateCC_LG%2egif%3aNonHostedGuest)
> 
> **Rekening Mandiri**: 113-000-6944-858, **Atas Nama**: Dony Wahyu Isprananda

Is a framework with a very simple file system, so this is not a complex framework, but you can build and develop this framework to be a complex framework. This Framework support simple **MVC** where you still have to customize some code for get complex MVC, for Model just generate SQL Query for **`INSERT`, `UPDATE`** and **`DELETE`** only, so for code execution that SQL Query please make your self freely as you want or using whichever database libaries. This Framework also support Composer, so as to facilitate you for adding a library from composer. 

```
Name 	: Framework Kecik
Author  : Dony Wahyu Isp
Version : 1.1.0
Country	: Indonesian
City 	: Palembang
```

**Quickstart**

[**The First Step**](#the-first-step) | [**The Second Step**](#the-second-step) | [**The Third Step**](#the-third-step) | [**The Fourth Step**](#the-fourth-step) 

**More**

[**Know More In**](#know-more-in) | [**Header**](#header) |  [**Route**](#route) | [**Config**](#config) | [**Assets**](#assets) | [**Request**](#request) | [**MVC**](#mvc) | [**Controller**](#controller) | [**Middleware**](#middleware) | [**Model**](#model) | [**View**](#view) | [**Url**](#url) | [**Template**](#template) 

----

The First Step
---------------------
[top](#kecik-framework)

Install composer in your opration system, if not installed you can download it from Composer website, after download and initialitation, next you need make composer.json files with contents as follows. 

```javascript
{
    "require": {
        "kecik/kecik": "1.1.*@dev"
    }
}
```
next, run this command on console/cmd
```shell
composer install		
```
wait a minute until all run without error.

The Second Step
------------------
[top](#kecik-framework)

Create index.php files or anything, and enter the code below:
```php
<?php
require_once "Kecik/Kecik.php";
// or for composer
require_once "vendor/autoload.php";
```
**``require "Kecik\Kecik.php"``** for include system file of framework to the project that you want make.
then try run, if only displaying blank page without error message is mean successfull. 


for how to use composer will not be discussed here, you can learn from documentation from composer website, both online and offline. 

The Third Step
-------------------
[top](#kecik-framework)

Create variable from Kecik Class as below
```php
$app = new Kecik\Kecik();	
```
then try running back, if not get error is mean you have successfull in this step.


The Fourth Step
----------------------
[top](#kecik-framework)

The next step is make Route for index and run the framework, following code:
```php
$app->get('/', function() {
	return 'Hello Kecik';
});

$app->run();
			
```
Once the code is written try running, so you can see **"Hello Kecik"** that mean you have successfull make view for route index/main page for your project.

The overall appearance code:
```php
<?php
require_once "Kecik/Kecik.php";
// or for composer
//require_once "vendor/autoload.php"

$app = new Kecik\Kecik();

$app->get('/', function() {
	return 'Hello Kecik';
});

$app->run();
			
```

----

**Know More In**
-------------------------------------------------------------
Header
----------
[top](#kecik-framework)

Header use for do setting a response header
```php
$app->get('hello', function() {
	$this->header(200);
	return 'Hello Kecik';
});
```

----

Route
---------
[top](#kecik-framework)

Route in contained kecik framework current  is get and post, where get and post is request source and that mean is that route just will proccess on match request.For how to use, there are several ways, and very simple is without use Controller, external variable and template, as follow:
```php
$app->get('/', function() {
	return 'Hello Kecik';
});
```
With parameter:
```php
$app->get('hello/:name', function ($name) {
	return 'Hello '.$name;
});
```
Parameter in route use ``:`` at front section, while for optional parameter can use ``(:)``

> **example:** hello/(:name)

With Controller:
```php
$app->get('welcome/:name', new Controller\Welcome($app), function ($controller, $name) use ($app) {
	return $controller->index($name);
});
```		

Ensure that already makes Controller you want to use on that route.

With Template:
```php
$app->get('hello/:name', function ($name) {
	return 'Hello '.$name;
})->template('template_kecik');

$app->get('welcome/:name', new Controller\Welcome($app), function ($controller, $name) use ($app) {
	return $controller->index($name);
})->template('template_kecik');

$app->get('welcome/:name', function($name) {
	$controller = new Controller\Welcome($this);
	return $controller->index($name);
})->template('template_kecik');
```

####**Group**
Kecik Framework also supports grouping route.
```php
$app->group('book', function() {
	$this->post('insert', function() {
		$controller = new Controller\Book($this);
		return $controller->insert();
	});
	
	$this->get('get', function() {
		$controller = new Controller\Book($this);
		return $controller->get();
	});

	$this->post('update', function() {
		$controller = new Controller\Book($this);
		return $controller->update();
	});

	$this->post('delete', function() {
		$controller = new Controller\Book($this);
		return $controller->delete();
	});
	
	$this->post('find', function() {
		$controller = new Controller\Book($this);
		return $controller->find();
	});
});
```
HTML just support method ``POST`` and ``GET``, if we want using method like ``PUT``, ``DELETE``, ``OPTIONS``, and ``PATCH`` we can using do **`Override`**

```html
<form method="POST" action="<?php $this->url->to('login') ?>">
	<label>Username</label>
	<input type="text" name="username" />
	
	<label>Password</label>
	<input type="password" name="password" />
	
	<input type="hidden" name="_METHOD" value="PUT">
	
	<input type="submit" value="LOGIN" />
</form
```

> **Note:** Applies to the use of the post, put, delete, options, and patch to use the controller and templates there are several steps that need to be prepared

####**is()**
To get current value of the route
```php
<a href="<?php $this->url->to('home') ?>" <?php echo ($this->route->is() == 'home')? 'class="active"': '' ?>>Home</a>
```

####**isPost()**
To perform a check whether the request method is ``POST``, if true then the value is ``TRUE`` if one then the value is ``FALSE``.
```php
if ($this->route->isPost() == FALSE)
	$this->header(404);
```

####**isGet()**
To perform a check whether the request method is ``GET``, if true then the value is ``TRUE`` if one then the value is ``FALSE``.
```php
if ($this->route->isGET() == FALSE)
	$this->header(404);
```

####**isPut()**
To perform a check whether the request method is `PUT`, if true then the value is` TRUE` if one then the value is `FALSE`.
```php
if ($this->route->isPut() == FALSE)
	$this->header(404);
```

####**isDelete()**
To perform a check whether the request method is ``DELETE``, if true then the value is ``TRUE`` if one then the value is ``FALSE``.
```php
if ($this->route->isDelete() == FALSE)
	$this->header(404);
```

####**isPatch()**
To perform a check whether the request method is ``PATCH``, if true then the value is ``TRUE`` if one then the value is ``FALSE``.
```php
if ($this->route->isPatch() == FALSE)
	$this->header(404);
```

####**isOptions()**
To perform a check whether the request method is ``OPTIONS``, if true then the value is ``TRUE`` if one then the value is ``FALSE``.
```php
if ($this->route->isOptions() == FALSE)
	$this->header(404);
```

####**isAjax()**
To perform a check whether the request method is ``AJAX``, if true then the value is ``TRUE`` if one then the value is ``FALSE``.
```php
if ($this->route->isAjax() == FALSE)
	$this->header(404);
```

**First:**

Setting path or location for assets, application (MVC), and template, following way setting:
```php
$app->config->set('path.assets', 'assets');
$app->config->set('path.mvc', 'app');
$app->config->set('path.template', 'templates');
```
**Second:**

create a folder / directory by setting the path before.

**Third:**

For folder/directory assets and application sure in which there sub folder/direktori
```
+-- assets
|   +-- css
|   +-- js
|   +-- images

+--app
|  +-- controllers
|  +-- models
|  +-- views
```

Config
-------
[top](#kecik-framework)

For a big project and not simple we need some setting/configuration, for  setting/configuration this framework also equipped with config, either for setting or to read settings

####**set()**

Use set function from config to a set/add value
```php
set($key, $value)
```	
> paramater **``$key``** is parameter key for a setting
> 
> paramater **``$value``** is parameter value for a setting

**Example:**
```php
$app->config->set('path.assets', 'assets');
```

####**get()**

Use get function to get a value from a setting
```php
get($key)
```

> parameter **``$key``** is key parameter for a setting where to get value

**Example:**
```php
$asset_path = $app->config->get('path.assets');
```

## **Configuration When Making Kecik Instance**
```php
$config = [
	'path.assets'   => 'assets',
	'path.mvc'      => 'app',
	'path.template'	=> 'templates',
	'error.404'     => 'kecik_template/404',
	'mod_rewrite'	=> TRUE,
	
	'libraries' => [
		'DIC' => ['enable'=>TRUE],
		'Session' => [
			'enable' => TRUE,
			'config' => ['encrypt' => TRUE]
		],
		'Cookie' => [
			'enable' => TRUE,
			'config' => ['encrypt' => TRUE]
		],
		'Database' => [
			'enable' => TRUE,
			'config' => [
				'driver' => 'mysqli',
				'hostname' => 'localhost',
				'username' => 'root',
				'password' => '',
				'dbname' => 'kecik'
			]
		],
		'MVC' => ['enable' => TRUE],
		'Language' => [
			'enable' => TRUE,
			'params' => [
				'id' => 'language/lang_id.json',
				'us' => 'language/lang_us.json'
			]
		]
	]
];

$app = new Kecik\Kecik($config);

	$app->get('/', function() {
		return 'Hello Kecik';
	});
	
$app->run();
```
	
Assets
-------
[top](#kecik-framework)

Assets is importan for facilitate us work for add/delete assets as css, js and images, also very useful for a template, and assets juga can be adjusted by controller in used. Assets css and js have same structure while for images is diferrent.
####**add()**

This function use for add a assets file as css or js.
```php
add($file='')
```

> paramater **``$file``** contains the name of the file that want to load assets, write without using extension

**Example:**
```php
$app->assets->css->add('boostrap');
$app->assets->js->add('jquery.min');
```

####**delete()**

This cunction use to delete a assets file that want to load as css or js.
```php
delete($file='')
```
> paramater **``$file``** contains the name of the file that want to load assets, write without using extension

**Example:**
```php
$app->assets->css->delete('boostrap');
$app->assets->js->delete('jquery.min');
```

####**render()**

This function use to render a asset list or one asset that want to load as css or js
```php
render($file='')
```		

> paramater **``$file``** contains the name of the file that want to load assets, write without using extension

**Example:**
```php
echo $app->assets->css->render();
echo $app->assets->js->render();
// atau spesifik render
echo $app->assets->css->render('boostrap');
echo $app->assets->js->render('boostrap.min');
```

####**images()**

This function  use to get link from image assets file.
```php
images($file)
```

> paramater **``$file``** containt image assets file name that want to use.

**Example:**
```php
<img src="<?php echo $app->assets->images('kecik.jpg'); ?>" />
```

####**url()**

This function use for get link of assets file for images.
```php
url()
```

Request
-----------
[top](#kecik-framework)

Request is other use from  ``$_GET``, ``$_POST`` and ``$_SERVER``

####**get()**

You can use get function to get value from ``$_GET``.
```php
get($var='')
```

> paramater **``$var``** containt name from get variable

**Example:**
```php
print_r($this->request->get());
$x = $this->request->get('x');
```

#### **post()**

You can use `post` function to get value from ``$_POST``.
```php
post($var='')
```

> paramater **``$_var``** containt name from post variable

**Example:**
```php
print_r($this->request->post());
$x = $this->request->post('x');
```

#### **put()**

You can use `put` function to get value from ``PUT``.
```php
put($var='')
```

> parameters **``$var``** contains name from put variable.

**Example:**
```php
print_r($this->request->put());
$x = $this->request->put('x');
```

#### **delete()**

You can use `delete` function to get value from ``DELETE``.
```php
delete($var='')
```

> parameters **``$var``** contains name from delete variable.

**Contoh:**
```php
print_r($this->request->delete());
$x = $this->request->delete('x');
```

#### **options()**

You can use `options` function to get value from ``OPTIONS``.
```php
options($var='')
```

> paramater **``$var``** berisikan nama dari variabel post

**Contoh:**
```php
print_r($this->request->options());
$x = $this->request->options('x');
```

#### **patch()**

You can use `patch` function to get value from ``PATCH``.
```php
patch($var='')
```

> paramater **``$var``** berisikan nama dari variabel post

**Contoh:**
```php
print_r($this->request->patch());
$x = $this->request->patch('x');
```

####**file()**
You can use `file` function to get value from ``$_FILE``
```php
file($file)
```

> parameters **``$file``** contains the name of the variable ``FILES``

**Contoh:**
```php
$x = $this->request->file('photo')->move($source, $destination);
```

####**server()**

You can use server function to get value from  ``$_SERVER``
```php
server($var='')
```		

> paramater **``$var``** containt name from server variable

**Exmple:**
```php
print_r($this->request->server());
$host = $this->request->server('HTTP_HOST');
```

----

**MVC**
---------------------------------------


This Framework also support simple MVC, where route will call Controller and Controller will call Model or/and View.

Controller
------------
[top](#kecik-framework)

For make controller is simple, we just make file with name as controller name and save into directory that setting before via config, The following simple code a controller
```php
<?php
// file welcome.php
namespace Controller;

use Kecik\Controller;

class Welcome extends Controller{

	public function __construct() {
		parent::__construct();
	}
}
```

How to use the controller on the route is as follows:
```php
$app->get('/', new Controller\Welcome(), function($controller) {

});
```

- **With Parameter**
To use the controller parameters on the way is also quite easy, just add a parameter to the constructor and when the controller is live input parameter constructor.
```php
<?php
// file welcome.php
namespace Controller;

use Kecik\Controller;

class Welcome extends Controller{
	var $dbcon;

	public function __construct($dbcon) {
		parent::__construct();
		$this->dbcon = $dbcon;
	}
}
```

Next how to use at route as follow:
```php
$app->get('/', new Controller\Welcome($dbcon), function($controller) {

});
```

- **With Method/Function**
For use method/function in controller is simple, just call in callback section route. Here's how to write the code controller using the method / function.
```php
<?php
// file welcome.php
namespace Controller;

use Kecik\Controller;

class Welcome extends Controller{
	var $dbcon;

	public function __construct($dbcon) {
		parent::__construct();
		$this->dbcon = $dbcon;
	}

	public function index() {
		return 'Kecik berkata: Controler->index()';
	}
}
```

Next use method/function in route is as follow.
```php
$app->get('/', new Controller\Welcome($dbcon), function($controller) {
	return $controller->index();
});
```

- **With parameter in Method/Function**
For give paramter in Method/Fungsi in controller we can give at the time of the call method in callback route, The following code example controller with a method / function parameterized.
```php
<?php
// file welcome.php
namespace Controller;

use Kecik\Controller;

class Welcome extends Controller{
	var $dbcon;

	public function __construct($dbcon) {
		parent::__construct();
		$this->dbcon = $dbcon;
	}

	public function index() {
		return 'Kecik berkata: Controler->index()';
	}

	public function hello($nama) {
		return "Hello, $nama";
	}
}
```

How to use in route as follow.
```php
$app->get('/hello/:nama', new Controller\Welcome($dbcon), function($controller, $nama) {
	return $controller->index($nama);
});
```

Middleware
-----------------
[top](#kecik-framework)
Middleware is functions will running before/after callback execution on route.
```php
$mw1 = function() {
	echo 'is Middleware 1 [Before]';
};
$mw2 = function() {
	echo 'is Middleware 2 [Before]';
};
$mw3 = function() {
	echo 'is Middleware 3 [After]';
};

$mw4 = function() {
	echo 'is Middleware 4 [After]';
};

$app->get('middleware', array($mw1, $mw2), function() {
	return 'is Response Middleware Route';
}, array($mw3, $mw4));
```

Model
-------
[top](#kecik-framework)

For make model is simple, we just create a file with name as model name and save in directory that setting before via config, The following simple code model
```php
<?php
//file data.php
namespace Model;

use Kecik\Model;

class Data extends Model {

	protected $table = 'data';

	public function __construct($id='') {
		parent::__construct($id);
	}
}
```

How to use Model in controller are as follows.
```php
<?php
// file welcome.php
namespace Controller;

use Kecik\Controller;

class Welcome extends Controller{
	var $dbcon;

	public function __construct($dbcon) {
		parent::__construct();
		$this->dbcon = $dbcon;
	}

	public function index() {
		return 'Kecik berkata: Controler->index()';
	}

	public function hello($nama) {
		return "Hello, $nama";
	}

	public function insert() {
		$model = new \Model\Data();
			$model->nama = $_POST['nama'];
			$model->email = $_POST['email'];
		$sql = $model->save();
	}

	public function update($id) {
		$model = new \Model\Data(array('id'=>$id));
			$model->nama = $_POST['nama'];
			$model->email = $_POST['email'];
		$sql = $model->save();
	}

	public function delete($id) {
		$model = new \Model\Data(array('id'=>$id));
		$sql = $mode->delete();
	}
}
```
 
 View
------
[top](#kecik-framework)

For make view is also simple, because in this you don't need make class/object, but just plain php file will call by controller, the following code view.
```php
<!-- file welcome.php -->
<?php echo 'Ini dari View' ?>
```

How to use view file in controller are as follows.
```php
<?php
// file welcome.php
namespace Controller;

use Kecik\Controller;

class Welcome extends Controller{

	public function __construct() {
		parent::__construct();
	}

	public function welcome() {
		return $this->view('welcome');
	}
}
```

How to send variable to view are as follows:
```php
<?php
// file welcome.php
namespace Controller;

use Kecik\Controller;

class Welcome extends Controller{

	public function __construct() {
		parent::__construct();
	}

	public function welcome($nama) {
		return $this->view('welcome', array('nama'=>$nama));
	}
}
```

**HMVC**
-------------
Kecik Framework juga mendukung HMVC bahkan HMVC dengan struktur yang lebih dinamis.
Contoh Struktur HMVC:
```
+--app
|  +-- controllers --+
|  +-- models        |-- MVC
|  +-- views       --+
|  +-- module           --+
|      +-- controllers    |-- HMVC
|      +-- models         |
|      +-- views        --+
|  +-- tim1  ---------------------------------------+------------+
|      +-- module1                                  |            |
|          +-- controllers  --+                     |            |
|          +-- models         |-- MVC Tim1\Module1  |            |
|          +-- views        --+                     |-- Tim1     |
|      +-- module2                                  |            |
|          +-- controllers  --+                     |            |
|          +-- models         |-- MVC Tim1\Module2  |            |
|          +-- views        --+---------------------+            |-- HMVC Dinamis
|  +-- tim2  ---------------------------------------+            |
|      +-- module1                                  |            |
|          +-- controllers  --+                     |            |
|          +-- models         |-- MVC Tim2\Module1  |            |
|          +-- views        --+                     |-- Tim2     |
|      +-- module2                                  |            |
|          +-- controllers  --+                     |            |
|          +-- models         |-- MVC Tim2\Module2  |            |
|          +-- views        --+---------------------+------------+
```

**Controller dalam HMVC**
Untuk Controller pada HMVC penamaan namespace harus sama dengan struktur direktori MVC nya.
```php
<?php
namespace Module\Controller;

use Kecik\Controller;

class Welcome extends Controller {

	public function __construct() {
		parent::__contruct();
	}

	public function index() {
		return $this->view('index');
	}
}
```

atau untuk HMVC Dinamis
```php
<?php
namespace Tim1\Module\Controller;

use Kecik\Controller;

class Welcome extends Controller {

	public function __construct() {
		parent::__contruct();
	}

	public function index() {
		return $this->view('index');
	}
}
```

Cara menggunakan controller HMVC pada route
```php
$app->get('welcome', function() {
	$ctrl = new \Module\Controller\Welcome();
	return $ctrl->index();
});
```
Sedangkan untuk HMVC Dinamis
```php
$app->get('welcome', function() {
	$ctrl = new \Tim1\Module1\Controller\Welcome();
	return $ctrl->index();
});
```


**Model dalam HMVC**
Sama dengan Controller penamaan namespace harus sama dengan suktrur direktori MVC nya.
```php
<?php
//file data.php
namespace Module\Model;

use Kecik\Model;

class Data extends Model {

	protected static $table = 'data';

	public function __construct($id='') {
		parent::__construct($id);
	}
}
```
atau untuk HMVC Dinamis
```php
<?php
//file data.php
namespace Tim1\Module\Model;

use Kecik\Model;

class Data extends Model {

	protected static $table = 'data';

	public function __construct($id='') {
		parent::__construct($id);
	}
}
```

Dan cara pemanggilannya pada controller adalah
```php
<?php
namespace Module\Controller;

use Kecik\Controller;

class Welcome extends Controller {

	public function __construct() {
		parent::__contruct();
	}

	public function create() {
		$data = new \Module\Model\Data();
			$data->name = $this->request->post('name');
			$data->address = $this->request->post('address');
			$data->email = $this->request->post('email');
		$data->save();
	}
}
```

atau untuk HMVC Dinamis
```php
<?php
namespace Tim1\Module\Controller;

use Kecik\Controller;

class Welcome extends Controller {

	public function __construct() {
		parent::__contruct();
	}

	public function create() {
		$data = new \Tim1\Module\Model\Data();
			$data->name = $this->request->post('name');
			$data->address = $this->request->post('address');
			$data->email = $this->request->post('email');
		$data->save();
	}
}
```

Kita bisa juga menggunakan Model dari module lain ataupun dari model utama yang berada diluar direktori module

**View dalam HMVC**
Menggunakan file pada masing module tidak ada perbedaan dengan cara MVC, jadi kita tinggal memanggil nama file view nya tanpa
disertakan dengan ekstensi .php
```php
<?php
namespace Module\Controller;

use Kecik\Controller;

class Welcome extends Controller {

	public function __construct() {
		parent::__construct();
	}

	public function wellcome($nama) {
		// menggunakan view dari module lain
		return $this->view('wellcome', array('nama'=>$nama));
	}
}
```

Sedangkan jika kita ingin menggunakan view dari module lain atau mungkin pada view utama yang berada diluar direktori module,
kita cukup mengubah nilai pada parameter pertama menjadi array, dimana nilai index pertama adalah nama view jika ingin 
menggunakan view utama yang berada di luar direktori module, atau index pertama adalah nama module dan index kedua adalah
nama view dari module yang view nya ingin digunakan.
```php
$this->view(array('nama_view'));  // Menggunakan view utama
$this->view(array('nama_module', 'nama_view')); // Menggunakan view dari module lain
```

```php
<?php
namespace Module\Controller;

use Kecik\Controller;

class Welcome extends Controller {

	public function __construct() {
		parent::__construct();
	}

	public function wellcome($nama) {
		// menggunakan view dari module lain
		return $this->view(array('Module2', 'welcome'), array('nama'=>$nama));
	}
}
```

atau
```php
<?php
namespace Module\Controller;

use Kecik\Controller;

class Welcome extends Controller {

	public function __construct() {
		parent::__construct();
	}

	public function wellcome($nama) {
		// menggunakan view utama yang berada diluar module
		return $this->view(array('welcome'), array('nama'=>$nama));
	}
}
```

---
Url
----
[top](#kecik-framework)

Url is important for help jobs for get value like protocol, base path, base url, also redirect or make link to other route .

####**protocol()**
For get Protocol value
```php
echo $this->url->protocol();
```
####**basePath()**
For get Base Path value
```php
echo $this->url->basePath();
```
####**baseUrl()**
For Get Base Url value
```php
echo $this->url->baseUrl();
```
####**redirect($route)**
For redirect to other route
```php
$this->url->redirect('login');
```
####**to($route)**
For echo url with route
```php
<a href="<?php $this->to('home') ?>">HOME</a>
```
####**linkTo($route)**
For Get Url with Route value
```php
<a href="<?php echo $this->linkTo('home') ?>">Home</a>
```

---

**Template**
---------------
[top](#kecik-framework)

For make template in this framework is also simple, you just create template file in directory that you setting before via config. Here is a simple example of code templates
```html
<!-- file template.php -->
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8" />
		<title>Simple Template</title>

		<meta name="description" content="overview &amp; stats" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		
		@css
	</head>
	<body>

    <div class="container">

        @response

    </div>

		@js
	</body>
</html>
```

> Sign **`{{`** dan **`}}`** just subtitute tag **`<?php`** and **`>`** for echo you can use **`{{=`** it's same **`<?php echo `** while want using sign **`{{`** and **`}}`** for AngularJS you can use sign **`\`** after its, example **`\{{`** atau **`\}}`**, this just simple template engine, but you use php tags.
> 
> The **`@response`** atau **`@yield`** is to put the output of the controller.
>
> The **`@css`** or  **` @js`** is to apply the template rendering assets

How to use the template on the route is as follows.
```php
<?php
$app->get('welcome/:nama', new Controller\Welcome(), function ($controller, $nama) {
	return $controller->welcome($nama);
})->template('template');
```

Replace Template
```php
<?php
$app->get('admin', function() {
	if (!isset($_SESSION['login'])) {
		//** Replace Template
		$this->template('login', TRUE);
	} else {
		$controller = new Controller\Admin($this);
		return $controller->index();
	}
})->template('template');
```
