**Kecik Framework**
===================

Merupakan framework dengan satu file system yang sangat sederhana, jadi ini bukan merupakan sebuah framework yang kompleks, tapi anda dapat membangun dan mengembangkan framework ini untuk menjadi sebuah framework yang kompleks. Framework ini mendukung **MVC** sederhana dimana anda masih harus mengcustom beberapa code untuk mendapatkan MVC yang kompleks, untuk Model hanya sebatas men-generate perintah SQL untuk **`INSERT`, `UPDATE`** dan **`DELETE`** saja, jadi untuk code pengeksekusian SQL nya tersebut silakan dibuat sendiri dengan bebas mau menggunakan library database manapun. Framework ini juga mendukung Composer, jadi bisa memudahkan anda untuk menambahkan sebuah library dari composer. 

```
Nama 	: Framework Kecik
Pembuat : Dony Wahyu Isp
Versi 	: 1.1.0
Kota 	: Palembang
```

**Cara Cepat Memulai**

[**Langkah Pertama**](#langkah-pertama) | [**Langkah Kedua**](#langkah-kedua) | [**Langkah Ketiga**](#langkah-ketiga) | [**Langkah Keempat**](#langkah-keempat) 

**Lebih Dalam**

[**Mengenal Lebih Dalam**](#mengenal-lebih-dalam-lagi) | [**Header**](#header) |  [**Route**](#route) | [**Config**](#config) | [**Assets**](#assets) | [**Request**](#request) | [**MVC**](#mvc) | [**Controller**](#controller) | [**Middleware**](#middleware) | [**Model**](#model) | [**View**](#view) | [**Url**](#url) | [**Template**](#template) 

----

Langkah Pertama
---------------------
[top](#kecik-framework)

Install composer pada sistem operasi anda, jika belum terinstall anda dapat mendownload melalui link Composer, setelah melakukan download dan installasi, selanjutnya anda perlu membuat file composer.json dengan isi file berikut ini. 

```javascript
{
    "require": {
        "dnaextrim/kecik": "1.1.*@dev"
    }
}
```
Selanjutnya jalankan perintah berikut ini pada console/cmd
```shell
composer install		
```
Tunggu beberapa menit hingga semua berjalan tanpa error.

Langkah Kedua
------------------
[top](#kecik-framework)

Buatlah sebuah file index.php atau apapun dengan tuliskan code dibawah ini:
```php
<?php
require_once "Kecik/Kecik.php";
// atau untuk composer
require_once "vendor/autoload.php";
```
**``require "Kecik\Kecik.php"``** untuk memasukan file system framework ke project yang ingin kita buat.
Lalu coba jalankan, jika hanya menampilkan halaman kosong tanpa pesan error berarti sudah berhasil. 

Untuk cara penggunaan composer tidak akan dibahas disini, anda dapat mempelajarinya dari dokumentasi yang disedia di website composer, baik secara online maupun offline. 

Langkah Ketiga
-------------------
[top](#kecik-framework)

Buat variabel dari Class Framework Kecik seperti dibawah ini
```php
$app = new Kecik\Kecik();	
```
Lalu coba jalankan kembali, jika tidak terdapat error berarti anda sudah sukses sampai tahap ini. 


Langkah Keempat
----------------------
[top](#kecik-framework)

Langkah selanjutnya adalah membuat Route untuk index dan menjalankan framework, berikut code nya:
```php
$app->get('/', function() {
	return 'Hello Kecik';
});

$app->run();
			
```
Setelah code ditulis coba jalankan, maka akan tampil tulisan **"Hello Kecik"** itu berarti anda telah berhasil membuat tampilan untuk route index/halaman utama project anda.

Tampilan kesuluruhan code:
```php
<?php
//require_once "Kecik/Kecik.php";
// atau untuk composer
require_once "vendor/autoload.php"

$app = new Kecik\Kecik();

$app->get('/', function() {
	return 'Hello Kecik';
});

$app->run();
```

----

**Mengenal Lebih Dalam Lagi**
-------------------------------------------------------------
Header
----------
[top](#kecik-framework)

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

Route yang terdapat pada framework kecik saat ini adalah get, post, put, delete, options, dan patch, dimana get, post, put, delete, options, dan patch adalah sumber request dan artinya route tersebut hanya akan diproses pada request yang sesuai. Untuk penggunaannya terdapat beberapa, dan paling sederhana adalah tanpa menggunakan Controller, variabel eksternal dan template, seperti berikut ini:
```php
$app->get('/', function() {
	return 'Hello Kecik';
});
```
Dengan menggunakan parameter:
```php
$app->get('hello/:nama', function ($nama) {
	return 'Hello '.$nama;
});
```
Parameter pada route menggunakan ``:`` pada bagian depannya, sedangkan untuk parameter yang bersifat optional bisa menggunakan ``(:)``

> **contoh:** hello/(:nama)

Dengan menggunakan Controller:
```php
$app->get('selamat_datang/:nama', new Controller\Welcome($app), function ($controller, $nama)  {
	return $controller->index($nama);
});
```		
atau
```php
$app->get('selamat_datang/:nama', function($nama) {
	$controller = new Controller\Welcome($this);
	return $controller->index($nama);
});
```
Pastikan sebelumnya sudah membuat Controller yang ingin digunakan pada route tersebut.

Dengan menggunakan Template:
```php
$app->get('hello/:nama', function ($nama) {
	return 'Hello '.$nama;
})->template('template_kecik');

$app->get('selamat_datang/:nama', new Controller\Welcome($app), function ($controller, $nama) {
	return $controller->index($nama);
})->template('template_kecik');

$app->get('welcome/:nama', function($nama) {
	$controller = new Controller\Welcome($this);
	return $controller->index($nama);
})->template('template_kecik');
```

####**Group**
Kecik Framework juga mendukung pengelompokan route.
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
HTML mendukung method `POST` dan `GET`, dan jika kita ingin menggunakan method sepert `PUT`, `DELETE`, `OPTIONS`, dan `PATCH` kita dapat menggunakannya dengan cara melakukan **`Override`**

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

> **Catatan:** Berlaku juga pada penggunaan post, put, delete, options, dan patch, untuk menggunakan controller dan template ada beberapa tahap yang perlu dipersiapkan

####**is()**
Untuk mendapatkan nilai route saat ini
```php
<a href="<?php $this->url->to('home') ?>" <?php echo ($this->route->is() == 'home')? 'class="active"': '' ?>>Home</a>
```

####**isPost()**
Untuk mengecheck apakah metode request adalah `POST`, jika benar maka nilai adalah `TRUE` jika salah maka nilai adalah `FALSE`
```php
if ($this->route->isPost() == FALSE)
	$this->header(404);
```

####**isGet()**
Untuk mengecheck apakah metode request adalah `GET`, jika benar maka nilai adalah `TRUE` jika salah maka nilai adalah `FALSE`
```php
if ($this->route->isGET() == FALSE)
	$this->header(404);
```

####**isPut()**
Untuk mengecheck apakah metode request adalah `PUT`, jika benar maka nilai adalah `TRUE` jika salah maka nilai adalah `FALSE`
```php
if ($this->route->isPut() == FALSE)
	$this->header(404);
```

####**isDelete()**
Untuk mengecheck apakah metode request adalah `DELETE`, jika benar maka nilai adalah `TRUE` jika salah maka nilai adalah `FALSE`
```php
if ($this->route->isDelete() == FALSE)
	$this->header(404);
```

####**isPatch()**
Untuk mengecheck apakah metode request adalah `PATCH`, jika benar maka nilai adalah `TRUE` jika salah maka nilai adalah `FALSE`
```php
if ($this->route->isPatch() == FALSE)
	$this->header(404);
```

####**isOptions()**
Untuk mengecheck apakah metode request adalah `OPTIONS`, jika benar maka nilai adalah `TRUE` jika salah maka nilai adalah `FALSE`
```php
if ($this->route->isOptions() == FALSE)
	$this->header(404);
```

####**isAjax()**
Untuk mengecheck apakah metode request adalah `AJAX`, jika benar maka nilai adalah `TRUE` jika salah maka nilai adalah `FALSE`
```php
if ($this->route->isAjax() == FALSE)
	$this->header(404);
```


**Pertama:**

Setting path atau lokasi untuk assets, applikasi(MVC), dan template, berikut cara setting:
```php
$app->config->set('path.assets', 'assets');
$app->config->set('path.mvc', 'app');
$app->config->set('path.template', 'templates');
```
**Kedua:**

Buatlah folder/direktori berdasarkan settingan path sebelumnya.

**Ketiga:**

Untuk folder/direktori assets dan applikasi pastikan didalamnya terdapat sub folder/direktori
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
---------
[top](#kecik-framework)

Untuk project yang besar dan tidak sederhana kita memerlukan beberapa setting/konfigurasi, untuk melakukan setting/konfigurasi framework ini juga dilengkapi config, baik untuk menyetting ataupun untuk membaca settingan

####**set()**

Gunakan fungsi set pada config untuk melakukan settingan nilai/menambah settingan
```php
set($key, $value)
```	
> paramater **``$key``** merupakan parameter kunci untuk sebuah settingan
> 
> paramater **``$value``** merupakan parameter nilai dari sebuah settingan

**Contoh:**
```php
$app->config->set('path.assets', 'assets');
```

####**get()**

Gunakan fungsi get untuk mendapatkan nilai dari suatu settingan
```php
get($key)
```

> parameter **``$key``** merupakan parameter kunci untuk sebuah settingan yang ingin diambil nilainya

**Contoh:**
```php
$asset_path = $app->config->get('path.assets');
```

## **Konfigurasi Saat Instance Kecik Dibuat**
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
---------
[top](#kecik-framework)

Assets sangat diperlukan dalam mempermudah pekerjaan kita untuk menambahkan atau menghilangkan assets seperti css, js dan images, sangat berguna juga untuk membuat template, dan assets juga bisa disesuaikan bedasarkan controller yang digunakan. Assets css dan js memiliki struktur yang sama sedangkan untuk images berbeda.
####**add()**

Fungsi ini digunakan untuk menambahkan sebuah file assets baik css maupun js.
```php
add($file='')
```

> paramater **``$file``** berisikan nama file assets yang ingin diload, tuliskan tanpa menggunakan extension

**Contoh:**
```php
$app->assets->css->add('boostrap');
$app->assets->js->add('jquery.min');
```

####**delete()**

Fungsi ini digunakan untuk menghapus sebuah file assets yang ingin diload baik css maupun js.
```php
delete($file='')
```
> paramater **``$file``** berisikan nama file assets yang ingin diload, tuliskan tanpa menggunakan extension

**Contoh:**
```php
$app->assets->css->delete('boostrap');
$app->assets->js->delete('jquery.min');
```

####**render()**

Fungsi ini digunakan untu merender sebuah daftar asset atau salah satu asset yang ingin diload baik css maupun js
```php
render($file='')
```		

> paramater **``$file``** berisikan nama file assets yang ingin diload, tuliskan tanpa menggunakan extension

**Contoh:**
```php
echo $app->assets->css->render();
echo $app->assets->js->render();
// atau spesifik render
echo $app->assets->css->render('boostrap');
echo $app->assets->js->render('boostrap.min');
```

####**images()**

Fungsi ini digunakan untuk mendapatkan link file assets untuk gambar.
```php
images($file)
```

> paramater **``$file``** berisikan nama file assets gambar yang ingin digunakan.

**Contoh:**
```php
<img src="<?php echo $app->assets->images('kecik.jpg'); ?>" />
```

####**url()**

Fungsi ini digunakan untuk mendapatkan link file assets untuk gambar.
```php
url()
```

> paramater **``$file``** berisikan nama file assets gambar yang ingin digunakan.

**Contoh:**
```php
echo $app->assets->url()."fonts/";
```

Request
-----------
[top](#kecik-framework)

Request merupakan bentuk lain dari penggunaan ``$_GET``, ``$_POST`` dan ``$_SERVER``

####**get()**

Anda dapat menggunakan fungsi get untuk mendapatkan nilai dari ``$_GET``
```php
get($var='')
```

> paramater **``$var``** berisikan nama dari variabel get

**Contoh:**
```php
print_r($this->request->get());
$x = $this->request->get('x');
```

#### **post()**

Anda dapat menggunakan fungsi post untuk mendapatkan nilai dari ``$_POST``
```php
post($var='')
```

> paramater **``$var``** berisikan nama dari variabel post

**Contoh:**
```php
print_r($this->request->post());
$x = $this->request->post('x');
```

#### **put()**

Anda dapat menggunakan fungsi post untuk mendapatkan nilai dari method `put`
```php
put($var='')
```

> paramater **``$var``** berisikan nama dari variabel post

**Contoh:**
```php
print_r($this->request->post());
$x = $this->request->post('x');
```

#### **delete()**

Anda dapat menggunakan fungsi post untuk mendapatkan nilai dari method `delete`
```php
delete($var='')
```

> paramater **``$var``** berisikan nama dari variabel post

**Contoh:**
```php
print_r($this->request->delete());
$x = $this->request->delete('x');
```

#### **options()**

Anda dapat menggunakan fungsi post untuk mendapatkan nilai dari method `options`
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

Anda dapat menggunakan fungsi post untuk mendapatkan nilai dari method `patch`
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
Anda dapat menggunakan fungsi post untuk mendapatkan nilai dari method `file`
```php
file($file)
```

> paramater **``$file``** berisikan nama dari variabel FILES

**Contoh:**
```php
$x = $this->request->file('photo')->move($source, $destination);
```

####**server()**

Anda dapat menggunakan fungsi server untuk mendapatkan nilai dari ``$_SERVER``
```php
server($var='')
```		

> paramater **``$var``** berisikan nama dari variabel server

**Contoh:**
```php
print_r($this->request->server());
$host = $this->request->server('HTTP_HOST');
```

----

**MVC**
------------


Framework ini juga mendukung MVC sederhana, dimana route akan memanggil Controller dan Controller akan memanggil Model atau/dan View.

Controller
---------------
[top](#kecik-framework)

Untuk membuat controller caranya cukup mudah, kita tinggal membuat file dengan nama sesuai dengan nama controllernya dan disimpan pada direktory yang sudah disetting sebelumnya melalui config, berikut ini code sederhana sebuah controller
```php
<?php
// file welcome.php
namespace Controller;

use Kecik\Controller;

class Welcome extends Controller{

	public function __construct($app) {
		parent::__construct($app);
	}
}
```

Cara menggunakan controller tersebut pada route adalah sebagai berikut
```php
$app->get('/', new Controller\Welcome($app), function($controller) {

});
```

- **Menggunakan Parameter**
Untuk menggunakan parameter pada controller caranya juga cukup mudah, tinggal tambah parameter pada constructor dan pada saat controller dibuat tinggal masukan parameter contructornya
```php
<?php
// file welcome.php
namespace Controller;

use Kecik\Controller;

class Welcome extends Controller{
	var $dbcon;

	public function __construct($app, $dbcon) {
		parent::__construct($app);
		$this->dbcon = $dbcon;
	}
}
```

Selanjutnya cara menggunakannya pada route sebagai berikut:
```php
$app->get('/', new Controller\Welcome($app, $dbcon), function($controller) {

});
```

- **Menggunakan Method/Fungsi**
Untuk menggunakan method/fungsi pada controller juga cukup mudah caranya, tinggal di panggil pada bagian callback route. Berikut ini cara penulisan code controller mennggunakan method/fungsi.
```php
<?php
// file welcome.php
namespace Controller;

use Kecik\Controller;

class Welcome extends Controller{
	var $dbcon;

	public function __construct($app, $dbcon) {
		parent::__construct($app);
		$this->dbcon = $dbcon;
	}

	public function index() {
		return 'Kecik berkata: Controler->index()';
	}
}
```

Selanjutnya cara menggunakan method atau fungsi tersebut pada route adalah sebagai berikut.
```php
$app->get('/', new Controller\Welcome($app, $dbcon), function($controller) {
	return $controller->index();
});
```

- **Menggunakan parameter pada Method/Fungsi**
Untuk memberikan paramter pada Method/Fungsi dalam controller dapat kita berikan pada saat pemanggilan method pada callback route, berikut ini contoh code controller dengan method/fungsi berparameter.
```php
<?php
// file welcome.php
namespace Controller;

use Kecik\Controller;

class Welcome extends Controller{
	var $dbcon;

	public function __construct($app, $dbcon) {
		parent::__construct($app);
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

Cara menggunakannya pada route dengan cara sebagai berikut.
```php
$app->get('/hello/:nama', new Controller\Welcome($app, $dbcon), function($controller, $nama) {
	return $controller->index($nama);
});
```

Middleware
-----------------
[top](#kecik-framework)
Middleware merupakan fungsi-fungsi yang akan dijalankan sebelum/setelah callback pada route di eksekusi.
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

$app->get('middleware', array($mw1, mw2), function() {
	return 'is Response Middleware Route';
}, array($mw3, $mw4));
```

Model
---------
[top](#kecik-framework)

Untuk membuat model caranya cukup mudah, kita tinggal membuat file dengan nama sesuai dengan nama modelnya dan disimpan pada direktory yang sudah disetting sebelumnya melalui config, berikut ini code sederhana sebuah model
```php
<?php
//file data.php
namespace Model;

use Kecik\Model;

class Data extends Model {

	protected static $table = 'data';

	public function __construct($id='') {
		parent::__construct($id);
	}
}
```

Cara penggunaan Model pada controller adalah sebagai berikut.
```php
<?php
// file welcome.php
namespace Controller;

use Kecik\Controller;

class Welcome extends Controller{
	var $dbcon;

	public function __construct($app, $dbcon) {
		parent::__construct($app);
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
			$model->nama = $this->request->post('nama');
			$model->email = $this->request->post('email');
		$sql = $model->save();
	}

	public function update($id) {
		$model = new \Model\Data(array('id'=>$id));
			$model->nama = $this->request->post('nama');
			$model->email = $this->request->post('email');
		$sql = $model->save();
	}

	public function delete($id) {
		$model = new \Model\Data(array('id'=>$id));
		$sql = $model->delete();
	}
}
```
 
 View
--------
[top](#kecik-framework)

Untuk membuat view juga cukup mudah, karena disini anda tidak perlu membuat class/objek, tapi cukup file php biasa saja yang akan dipanggil oleh controller, berikut code view.
```php
<!-- file welcome.php -->
<?php echo 'Ini dari View' ?>
```

Cara menggunakan file view dari controller adalah sebagai berikut.
```php
<?php
// file welcome.php
namespace Controller;

use Kecik\Controller;

class Welcome extends Controller{

	public function __construct($app) {
		parent::__construct($app);
	}

	public function welcome() {
		return $this->view('welcome');
	}
}
```

Cara mengirimkan variable ke view adalah sebagai berikut
```php
<?php
// file welcome.php
namespace Controller;

use Kecik\Controller;

class Welcome extends Controller{

	public function __construct($app) {
		parent::__construct($app);
	}

	public function welcome($nama) {
		return $this->view('welcome', array('nama'=>$nama));
	}
}
```

---
Url
----
[top](#kecik-framework)

Url sangat diperlukan dalam mempermudah pekerjaan kita untuk mengambil nilai seperti protocol, base path, base url, bahkan untuk melakukan redirect ataupun membuat link ke route lain..

####**protocol()**
Untuk mendapatkan nilai protocol
```php
echo $this->url->protocol();
```
####**basePath()**
Untuk mendapatkan nilai Path/Direktori Dasar
```php
echo $this->url->basePath();
```
####**baseUrl()**
Untuk mendapatkan nilai dasar Url
```php
echo $this->url->baseUrl();
```
####**redirect($route)**
Untuk melakukan redirect ke route lain
```php
$this->url->redirect('login');
```
####**to($route)**
Untuk mencetak Url dengan route;
```php
<a href="<?php $this->to('home') ?>">HOME</a>
```
####**linkTo($route)**
Untuk mendapatkan nilai Url dengan Route
```php
<a href="<?php echo $this->linkTo('home') ?>">Home</a>
```

---

Template
---------------
[top](#kecik-framework)

Untuk membuat template pada framework ini juga cukup mudah, anda tinggal membuat file template pada direktori yang telah anda setting sebelumnya pada config. Berikut ini adalah contoh sederhana dari code template
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

> Tanda **`{{`** dan **`}}`** hanya tag pengganti untuk tag **`<?php`** dan **`>`** untuk mencetak anda dapat menggunakan **`{{=`** ini sama dengan **`<?php echo `** sedangkan jika ingin menggunakan tanda **`{{`** dan **`}}`** untuk keperluan AngularJS anda bisa menggunakan tanda **`\`** didepan nya, contoh: **`\{{`** atau **`\}}`**, ini hanya untuk kebutuhan template engine sederhana saja, tapi anda tetap bisa menggunakan tag php
> 
> Sedangkan **`@response`**  atau **`@yield`** adalah untuk meletakan hasil output dari controller.
> Sedangkan **`@css`** atau  **`@js`** adalah untuk me render assets css atau js.
> 
> Tag-tag tersebut hanya digunakan pada template saja, tidak berlaku pada View
Cara menggunakan template tersebut pada route adalah sebagai berikut.
```php
<?php
$app->get('welcome/:nama', new Controller\Welcome($app), function ($controller, $nama) {
	return $controller->welcome($nama);
})->template('template');
```

Mengganti Template (Replace Template)
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

