**Kecik Framework**
===================

Merupakan framework dengan satu file system yang sangat sederhana, jadi ini bukan merupakan sebuah framework yang kompleks, tapi anda dapat membangun dan mengembangkan framework ini untuk menjadi sebuah framework yang kompleks. Framework ini mendukung **MVC** sederhana dimana anda masih harus mengcustom beberapa code untuk mendapatkan MVC yang kompleks, untuk Model hanya sebatas men-generate perintah SQL untuk **`INSERT`, `UPDATE` dan `DELETE`** saja, jadi untuk code pengeksekusian SQL nya tersebut silakan dibuat sendiri dengan bebas mau menggunakan library database manapun. Framework ini juga mendukung Composer, jadi bisa memudahkan anda untuk menambahkan sebuah library dari composer. 

```
Nama 	: Framework Kecik
Pembuat 	: Dony Wahyu Isp
Versi 	: 1.0.1alpha
Kota 	: Palembang
```

**Cara Cepat Memulai**
[**Langkah Pertama**](#langkah-pertama) | [**Langkah Kedua**](#langkah-kedua) | [**Langkah Ketiga**](#langkah-ketiga) | [**Langkah Keempat**](#langkah-keempat) 

**Lebih Dalam**
[**Mengenal Lebih Dalam**](#mengenal-lebih-dalam-lagi) | [**Route**](#route) | [**Config**](#config) | [**Assets**](#assets) | [**Input**](#input) | [**MVC**](#mvc) | [**Controller**](#controller) | [**Model**](#model) | [**View**](#view) | [**Template**](#template) 

----

Langkah Pertama
---------------------
[top](#kecik-framework)

Install composer pada sistem operasi anda, jika belum terinstall anda dapat mendownload melalui link Composer, setelah melakukan download dan installasi, selanjutnya anda perlu membuat file composer.json dengan isi file berikut ini. 

```
{
    "require": {
        "dnaextrim/kecik": "1.0-alpha"
    }
}
```
Selanjutnya jalankan perintah berikut ini pada console/cmd
```
composer install		
```
Tunggu beberapa menit hingga semua berjalan tanpa error.

Langkah Kedua
------------------
[top](#kecik-framework)

Buatlah sebuah file index.php atau apapun dengan tuliskan code dibawah ini:
```
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
```
$app = new Kecik\Kecik();	
```
Lalu coba jalankan kembali, jika tidak terdapat error berarti anda sudah sukses sampai tahap ini. 


Langkah Keempat
----------------------
[top](#kecik-framework)

Langkah selanjutnya adalah membuat Route untuk index dan menjalankan framework, berikut code nya:
```
$app->get('/', function() {
	echo 'Hello Kecik';
});

$app->run();
			
```
Setelah code ditulis coba jalankan, maka akan tampil tulisan **"Hello Kecik"** itu berarti anda telah berhasil membuat tampilan untuk route index/halaman utama project anda.

Tampilan kesuluruhan code:
```
<?php
$manual = FALSE;
require "system.php";

$app = new Kecik\Kecik();

$app->get('/', function() {
	echo 'Hello Kecik';
});

$app->run();
			
```

----

**Mengenal Lebih Dalam Lagi**
-------------------------------------------------------------
Route
---------
[top](#kecik-framework)

Route yang terdapat pada framework kecik saat ini adalah get dan post, tapi untuk sementara ini belum memiliki perbedaan, untuk penggunaannya terdapat beberapa, dan paling sederhana adalah tanpa menggunakan Controller, variabel eksternal dan template, seperti berikut ini:
```
$app->get('/', function() {
	echo 'Hello Kecik';
});
```
Dengan menggunakan parameter:
```
$app->get('hello/:nama', function ($nama) {
	echo 'Hello '.$nama;
});
```
Parameter pada route menggunakan ``:`` pada bagian depannya, sedangkan untuk parameter yang bersifat optional bisa menggunakan ``(:)``

>**contoh:** hello/(:nama)

Dengan menggunakan Controller:
```
$app->get('selamat_datang/:nama', new Controller\Welcome($app), function ($controller, $nama) use ($app) {
	$controller->index($nama);
});
```		

Pastikan sebelumnya sudah membuat Controller yang ingin digunakan pada route tersebut.

Dengan menggunakan Template:
```
$app->get('hello/:nama', function ($nama) {
	echo 'Hello '.$nama;
})->template('template_kecik');

$app->get('selamat_datang/:nama', new Controller\Welcome($app), function ($controller, $nama) use ($app) {
	$controller->index($nama);
})->template('template_kecik');
```

>**Catatan:** Berlaku juga pada penggunaan post, untuk menggunakan controller dan template ada beberapa tahap yang perlu dipersiapkan

**Pertama:**

Setting path atau lokasi untuk assets, applikasi(MVC), dan template, berikut cara setting:
```
$app->config->set('path.assets', 'assets');
$app->config->set('path.app', 'app');
$app->config->set('path.template', 'templates');
```
**Kedua:**

Buatlah folder/direktory berdasarkan settingan path sebelumnya.
Ketiga:

Untuk folder/direktori assets dan applikasi pastikan didalamnya terdapat sub folder/direktori
```
+-- Assets
|   +-- css
|   +-- js
|   +-- images

+--App
|  +-- controllers
|  +-- models
|  +-- views
```

Config
-------
[top](#kecik-framework)

Untuk project yang besar dan tidak sederhana kita memerlukan beberapa setting/konfigurasi, untuk melakukan setting/konfigurasi framework ini juga dilengkapi config, baik untuk menyetting ataupun untuk membaca settingan

####**set()**

Gunakan fungsi set pada config untuk melakukan settingan nilai/menambah settingan
```
set($key, $value)
```	

>paramater **``$key``** merupakan parameter kunci untuk sebuah settingan
paramater **``$value``** merupakan parameter nilai dari sebuah settingan

**Contoh:**
```
$app->config->set('path.assets', 'assets');
```

####**get()**

Gunakan fungsi get untuk mendapatkan nilai dari suatu settingan
```
get($key)
```

>parameter **``$key``** merupakan parameter kunci untuk sebuah settingan yang ingin diambil nilainya

**Contoh:**
```
$asset_path = $app->config->get('path.assets');
```
		
Assets
-------
[top](#kecik-framework)

Assets sangat diperlukan dalam mempermudah pekerjaan kita untuk menambahkan atau menghilangkan assets seperti css, js dan images, sangat berguna juga untuk membuat template, dan assets juga bisa disesuaikan bedasarkan controller yang digunakan. Assets css dan js memiliki struktur yang sama sedangkan untuk images berbeda.
####**add()**

Fungsi ini digunakan untuk menambahkan sebuah file assets baik css maupun js.
```
add($file='')
```

>paramater **``$file``** berisikan nama file assets yang ingin diload, tuliskan tanpa menggunakan extension

**Contoh:**
```
$app->assets->css->add('boostrap');
$app->assets->js->add('jquery.min');
```

####**delete()**

Fungsi ini digunakan untuk menghapus sebuah file assets yang ingin diload baik css maupun js.
```
delete($file='')
```
>paramater **``$file``** berisikan nama file assets yang ingin diload, tuliskan tanpa menggunakan extension

**Contoh:**
```
$app->assets->css->delete('boostrap');
$app->assets->js->delete('jquery.min');
```

####**render()**

Fungsi ini digunakan untu merender sebuah daftar asset atau salah satu asset yang ingin diload baik css maupun js
```
render($file='')
```		

>paramater **``$file``** berisikan nama file assets yang ingin diload, tuliskan tanpa menggunakan extension

**Contoh:**
```
echo $app->assets->css->render();
echo $app->assets->js->render();
// atau spesifik render
echo $app->assets->css->render('boostrap');
echo $app->assets->js->render('boostrap.min');
```

####**images()**

Fungsi ini digunakan untuk mendapatkan link file assets untuk gambar.
```
images($file)
```

>paramater **``$file``** berisikan nama file assets gambar yang ingin digunakan.

**Contoh:**
```
<img src="<?php echo $app->assets->images('kecik.jpg'); ?>" />
```

Input
------
[top](#kecik-framework)

Input merupakan bentuk lain dari penggunaan ``$_GET``, ``$_POST`` dan ``$_SERVER``

####**get()**

Anda dapat menggunakan fungsi get untuk mendapatkan nilai dari ``$_GET``
```
get($var='')
```

>paramater **``$var``** berisikan nama dari variabel get

**Contoh:**
```
print_r($this->input->get());
$x = $this->input->get('x');
```

#### **post()**

Anda dapat menggunakan fungsi post untuk mendapatkan nilai dari ``$_POST``
```
post($var='')
```

>paramater **``$var``** berisikan nama dari variabel post

**Contoh:**
```
print_r($this->input->post());
$x = $this->input->post('x');
```

####**server()**

Anda dapat menggunakan fungsi server untuk mendapatkan nilai dari ``$_SERVER``
```
server($var='')
```		

>paramater **``$var``** berisikan nama dari variabel server

**Contoh:**
```
print_r($this->input->server());
$host = $this->input->server('HTTP_HOST');
```

----

**MVC**
---------------------------------------


Framework ini juga mendukung MVC sederhana, dimana route akan memanggil Controller dan Controller akan memanggil Model atau/dan View.

Controller
------------
[top](#kecik-framework)

Untuk membuat controller caranya cukup mudah, kita tinggal membuat file dengan nama sesuai dengan nama controllernya dan disimpan pada direktory yang sudah disetting sebelumnya melalui config, berikut ini code sederhana sebuah controller
```
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

Cara menggunakan controller tersebut pada route adalah sebagai berikut
```
$app->get('/', new Controller\Welcome(), function($controller) {

});
```

- **Menggunakan Parameter**
Untuk menggunakan parameter pada controller caranya juga cukup mudah, tinggal tambah parameter pada constructor dan pada saat controller dibuat tinggal masukan parameter contructornya
```
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

Selanjutnya cara menggunakannya pada route sebagai berikut:
```
$app->get('/', new Controller\Welcome($dbcon), function($controller) {

});
```

- **Menggunakan Method/Fungsi**
Untuk menggunakan method/fungsi pada controller juga cukup mudah caranya, tinggal di panggil pada bagian callback route. Berikut ini cara penulisan code controller mennggunakan method/fungsi.
```
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
		echo 'Kecik berkata: Controler->index()';
	}
}
```

Selanjutnya cara menggunakan method atau fungsi tersebut pada route adalah sebagai berikut.
```
$app->get('/', new Controller\Welcome($dbcon), function($controller) {
	$controller->index();
});
```

- **Menggunakan parameter pada Method/Fungsi**
Untuk memberikan paramter pada Method/Fungsi dalam controller dapat kita berikan pada saat pemanggilan method pada callback route, berikut ini contoh code controller dengan method/fungsi berparameter.
```
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
		echo 'Kecik berkata: Controler->index()';
	}

	public function hello($nama) {
		echo "Hello, $nama";
	}
}
```

Cara menggunakannya pada route dengan cara sebagai berikut.
```
$app->get('/hello/:nama', new Controller\Welcome($dbcon), function($controller, $nama) {
	$controller->index($nama);
});
```

Model
-------
[top](#kecik-framework)

Untuk membuat model caranya cukup mudah, kita tinggal membuat file dengan nama sesuai dengan nama modelnya dan disimpan pada direktory yang sudah disetting sebelumnya melalui config, berikut ini code sederhana sebuah model
```
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

Cara penggunaan Model pada controller adalah sebagai berikut.
```
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
		echo 'Kecik berkata: Controler->index()';
	}

	public function hello($nama) {
		echo "Hello, $nama";
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

Untuk membuat view juga cukup mudah, karena disini anda tidak perlu membuat class/objek, tapi cukup file php biasa saja yang akan dipanggil oleh controller, berikut code view.
```
<!-- file welcome.php -->
<?php echo 'Ini dari View' ?>
```

Cara menggunakan file view dari controller adalah sebagai berikut.
```
<?php
// file welcome.php
namespace Controller;

use Kecik\Controller;

class Welcome extends Controller{

	public function __construct() {
		parent::__construct();
	}

	public function welcome() {
		$this->view('welcome');
	}
}
```

Cara mengirimkan variable ke view adalah sebagai berikut
```
<?php
// file welcome.php
namespace Controller;

use Kecik\Controller;

class Welcome extends Controller{

	public function __construct() {
		parent::__construct();
	}

	public function welcome($nama) {
		$this->view('welcome', array('nama'=>$nama));
	}
}
```

---

**Template**
---------------
[top](#kecik-framework)

Untuk membuat template pada framework ini juga cukup mudah, anda tinggal membuat file template pada direktori yang telah anda setting sebelumnya pada config. Berikut ini adalah contoh sederhana dari code template
```
<!-- file template.php -->
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8" />
		<title>Simple Template</title>

		<meta name="description" content="overview &amp; stats" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		
		{{ echo $this->assets->css->render() }}
	</head>
	<body>

    <div class="container">

        @controller

    </div>

		{{ echo $this->assets->js->render() }}
	</body>
</html>
```

>Tanda **`{{`** dan **`}}`** hanya tag pengganti untuk tag **`<?php`** dan **`>`** ini hanya untuk kebutuhan template engine sederhana saja, tapi anda tetap bisa menggunakan tag php
Sedangkan **`@controller`** adalah untuk meletakan hasil output dari controller.

Cara menggunakan template tersebut pada route adalah sebagai berikut.
```
<?php
$app->get('welcome/:nama', new Controller\Welcome(), function ($controller, $nama) {
	$controller->welcome($nama);
})->template('template');
```


