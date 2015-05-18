CHANGELOG
=========
1.1.0
-----
 * penambahan variabel config, url, assets, request, container dan db pada controller
 * perubahan $this->input menjadi $this->request
 * perubahan @controller menjadi @response or @yield
 * penambahan header
 * penambahan middleware Before/After
 * penambahan route group
 * penambahan method put, delete, options, patch
 * bug untuk route 64bit encode
 * penambahan cli
 * eksekusi template setelah eksekusi controller/route
 * penambahan parameter untuk replace template secara paksa
 * penambahan request file untuk menangani upload file
 

1.0.3-beta
-----
 * perbaikan bug pada route dengan parameter get
 * perbaikan bug pada autoload library/pustaka secara dinamis
 * perbaikan bug pada pattern route
 * penambahan logo
 * penambahan config for mod_rewrite
 * penambahan funngsi is pada route untuk mendapatkan route pattern sekarang
 * penambahan autoload library/pustaka
 * penambahan libary/pustaka DIC and Language


1.0.2-alpha
-----
 * perbaikan bug untuk server cli
 * perbaikan Autoload MVC
 * perubahan config path.app menjadi path.mvc
 * perubahan code setCallable, sekarang menggunakan pattern
 * route Post dan Get sudah berfungsi sebagaimana mestinya
 * penambahan server cli agar dapat berjalan tanpa apache
 * penambahan route isPost, isGet, isAjax

1.0.1-alpha
-----
 
 * perbaikan bug pada template
 * perbaikan fungsi url->redirect
 * perbaikan manual
 * pemisahan file manual
 * tambahan fungsi url->to()
 * penambahan example/contoh project sederhana 

1.0.0-alpha
-----

 * membuat core/system framework
 * membuat Route
 * membuat URL
 * membuat Input
 * membuat Assets
 * membuat Config
 * membuat MVC


