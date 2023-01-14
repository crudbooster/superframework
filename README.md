# Super Framework
The lightweight and fastest PHP framework from the creator of CRUDBooster

# Why Super Framework?
Kita mengadopsi beberapa pola pada framework laravel dan sekaligus merangkum apa saja yang paling essensial (terutama bagi kami) dalam development web. Sehingga kami dapat memaksimalkan performa kecepatan dari framework ini.

Daftar Isi
=================
* [Instalasi](#instalasi)
* [Memulai](#memulai)
  * [Konfigurasi Environment](#konfigurasi-environment-env)
  * [Struktur Folder](#struktur-folder)  
* [Controller & Routing](#controller--routing)
  * [Membuat Controller](#membuat-controller)
  * [Routing](#routing)  
    * [Routing Class](#routing-class)
    * [Routing Method](#routing-method)
    * [Routing Argument](#routing-dengan-argument)
* [CLI](#cli-super)
* [File System](#file-system)
* [Session](#session)
* [Cache](#cache)
* [Request](#request)
* [Response](#response)
* [Validation](#validation)
* [Database ORM / Query Builder](#database-orm)
* [Helper](#helper)
* [MRS Pattern](#model-repository-service)
* [Cron Job / Scheduler](#cron-job--scheduler)
* [Useful Libraries](#useful-libraries)
* [Contact](#contact)

# Instalasi

### Syarat Kebutuhan Sistem
Sebelum melakukan instalasi pastikan sistem Anda sudah memenuhi persyaratan berikut ini:
- php 7.3 >= | 7.4 >=
- Web server Apache / Nginx
- MySQL / MariaDB / Postgre / SQL Server / SQLite
- Composer
- PDO

### Opsional, namun direkomendasikan :
- php Zend OPCache Extension

### Perintah Instalasi
Buka terminal / command prompt pada folder yang telah Anda siapkan, dan jalankan perintah berikut:
```bash
$ composer create-project superframework/superframework my_new_super
```

Jika kamu mengalami kegagalan instalasi karena issue `platform checking` php version tidak sesuai padahal php kamu sudah kompatibel. Kamu bisa tambahkan parameter `--ignore-platform-reqs`
```bash 
$ composer create-project superframework/superframework my_new_super --ignore-platform-reqs
```
[[↑ Kembali ke daftar isi ↑]](#daftar-isi)

# Memulai
## Konfigurasi Environment (.env)
Silahkan copy file `.env.example` menjadi `.env`. Apabila OS Anda tidak dapat melakukannya, Anda dapat menggunakan perintah :
```bash
$ cp .env.example .env 
```
Kemudian atur file .env tersebut.
```bash
# Bagian ini Anda dapat menggantinya dengan nama proyek
APP_NAME="PHP Super Framework" 

# Bagian ini dapat diganti dengan mysql, pgsql, sqlsrv, sqlite
DB_CONNECTION=mysql

# DB_HOST biarkan localhost
DB_HOST=localhost

# DB_PORT sesuaikan dengan port servis database Anda, untuk mySQL defaultnya adalah 3306
DB_PORT=3306

# DB_DATABASE isi dengan nama database proyek Anda
DB_DATABASE=super

# DB_USERNAME isi dengan user database 
DB_USERNAME=root

# DB_PASSWORD isi dengan password database
DB_PASSWORD=

# Apabila Anda ingin error ditampilkan pada browser, maka isi nilai berikut dengan true
DISPLAY_ERRORS=false

# Apabila Anda ingin merekam setiap kejadian error pada siste, 
# maka isi nilai berikut dengan true. Maka nanti ketika terjadi error tersimpan pada folder /logs
LOGGING_ERRORS=false
```
[[↑ Kembali ke daftar isi ↑]](#daftar-isi)
## Struktur Folder
Sebelum Anda lanjut membuat aplikasi dengan framework ini, Anda perlu mengerti terlebih dahulu struktur folder pada framework SuperFramework ini.

```bash
/app
    /Helpers 
    /Lang 
    /Migrations 
    /Models 
    /Modules
/bootstrap
/configs
/logs 
/public 
/tasks 
/vendor 
```
[[↑ Kembali ke daftar isi ↑]](#daftar-isi)
### /app
Folder `app` berisi bisnis logika pada aplikasi Anda. Disini akan berisi semua file controller, helper, model, dan lain-lain.

### /bootstrap
Folder `bootstrap` berisi file cache yang digenerasi oleh sistem. Secara praktik Anda tidak perlu menambah / mengurangi apapun yang telah digenerasi pada folder ini. 

### /configs
Folder `configs` berisi file pengaturan dasar pada aplikasi. Anda dapat menyesuaikan beberapa pengaturan disini, namun beberapa sudah diarahkan ke file `.env`

### /logs
Folder `logs` berisi file `.log` hasil rekaman kejadian - kejadian pada sistem aplikasi Anda, seperti error dan debug.

### /public
Folder `public` ini digunakan untuk file yang dijalankan pertama kali oleh aplikasi. Berisi file `index.php` serta tempat Anda mengisinya dengan file-file asset css/js.

### /tasks
Folder `tasks` ini berisi file schedule yang akan dijalankan pada cronjob. Anda dapat menduplikasi file task yang ada, dan menyesuaikan sesuai kebutuhan cron job yang baru.

### /vendor
Folder `vendor` ini berisi berbagai macam library yang dibutuhkan pada sistem framework ini. Anda tidak perlu mengubah / menambahnya secara manual, karena sudah dikontrol dan dimanajemen oleh Composer.

[[↑ Kembali ke daftar isi ↑]](#daftar-isi)

# Controller & Routing

## Membuat Controller
Buka tool editor favorit Anda, dan buat file pada 
```bash 
app/Modules/Main/Controllers/TestController.php
```
Kemudian isikan file controller tersebut dengan format sebagai berikut : 
```php 
<?php

namespace App\Modules\Main\Controllers;

use SuperFrameworkEngine\Foundation\Controller;

/**
 * Class TestController
 * @route test
 */
class TestController extends Controller {

    /**
     * @return false|string
     * @route /
     * @throws \Exception
     */
    public function index()
    {
        echo "Ini controller percobaan";
    }
}
```
Lalu kemudian save file tersebut.

`# Pastikan Anda membuat nama file controller persis seperti perintah diatas`

Buka terminal pada root folder proyek Anda. Dan jalankan perintah berikut ini : 
```bash 
$ php super compile
```
Perintah diatas digunakan untuk menyimpan perubahan konfigurasi, routing, dan class lainnya.

Jika sudah, Anda dapat mencobanya pada browser: 
```bash 
example.com/test
```
atau jika menggunakan localhost
```bash 
localhost/projek_anda/public/test
```
[[↑ Kembali ke daftar isi ↑]](#daftar-isi)

## Routing
Routing pada superframework diatur langsung pada setiap class controller. 
### Routing Class
Routing class adalah routing yang diatur diatas class name. Dengan routing class Anda dapat membuat sebuah awalan routing pada class controller tersebut.
```php 
<?php

namespace App\Modules\Main\Controllers;

use SuperFrameworkEngine\Foundation\Controller;

/**
 * @route test
 */
class TestController
```
Maka akan menghasilkan :
```bash 
example.com/test
```
[[↑ Kembali ke daftar isi ↑]](#daftar-isi)
### Routing Method
Routing method berarti Anda mendefinisikan routing pada setiap method. 
```php 
    /**
     * @route welcome
     */
    public function welcome()
    {
        echo "Ini controller percobaan";
    }
```
Maka jika digabung dengan routing class tadi akan menghasilkan: 
```bash 
example.com/test/welcome
```
[[↑ Kembali ke daftar isi ↑]](#daftar-isi)

### Routing Dengan Argument
Anda dapat memasukkan argument pada url dan mencocokan pada method Anda dengan cara sebagai berikut:
```php 
    /**
     * @route welcome/{argument1}/{argument2}
     */
    public function welcome($argument1, $argument2)
    {
        dd($argument1, $argument2);
    }
```

Jangan lupa untuk menjalankan perintah berikut sebelum mendapatkan perubahan : 
```bash 
$ php super compile
```
[[↑ Kembali ke daftar isi ↑]](#daftar-isi)


# POST / GET
Pada superframework Anda tidak membutuhkan mendeklarasikan secara manual pada setiap routing. Jadi setiap routing dapat berjalan sebagai GET maupun POST. Apabila Anda ingin memvalidasi method yang dikirimkan oleh user, maka Anda harus menggunakan helper khusus seperti contoh berikut : 
```php 
    /**
     * @route submit
     */
    public function submit()
    {
        if(request_method_is_post()) {
            // Lanjut bisnis logik Anda
        }
        
        // Atau 
        if(request_method_is_get()) {
            // Lanjut eksekusi setelah get
        }
    }
```
[[↑ Kembali ke daftar isi ↑]](#daftar-isi)

# CLI (super)
Seperti layaknya framework lain seperti laravel mempunyai `artisan` pada super framework juga mempunyainya dengan nama `super`. Cara menggunakannya sebagai berikut:
```bash
php super [command]
```

| Command | Description |
| ------- | ----------- |
| compile | Untuk menyimpan perubahan route dan konfigurasi |
| make:migration {table} | Untuk membuat migration |
| migrate | Untuk menjalankan migrasi |
| make:model {table} | Untuk membuat file model |

[[↑ Kembali ke daftar isi ↑]](#daftar-isi)

# File System
Anda dapat melakukan upload file dengan helper FileSystem berikut

| Helper | Deskripsi |
| ------------ | ----------- |
| FileSystem::uploadImageByUrl($url, $newFileName) | To upload an image from url. Output is absolute URL of file E.g: /uploads/2019-01-01/filename.jpg |
| FileSystem::uploadBase64($base64Data, $newFileName, $extension) | To upload a file from base64 data. Output is absolute URL of file E.g: /uploads/2019-01-01/filename.docx |
| FileSystem::uploadImage($inputName, $newFileName) | To upload an image from input file. Output is absolute URL of file E.g: /uploads/2019-01-01/filename.jpg |
| FileSystem::uploadFile($inputName, $newFileName) | To upload a file from input file. Output is absolute URL of file E.g: /uploads/2019-01-01/filename.jpg |


Sebelum memanggil fungsi diatas, pasang use berikut ini diatas class controller.
```php 
use SuperFrameworkEngine\App\UtilFileSystem;
```

[[↑ Kembali ke daftar isi ↑]](#daftar-isi)

# Session
Untuk menggunakan session, silahkan gunakan helper berikut ini :

| Helper Name | Description |
| ------------ | ----------- |
| session(["key"=>"value"]) | To set a session with array |
| session("key") | To retrieve session by a key |
| session_forget($key) | To forget a session |
| session_flash($dataArray) | Put a flash session |

[[↑ Kembali ke daftar isi ↑]](#daftar-isi)

# Cache
Untuk menggunakan cache, silahkan gunakan helper berikut:

| Helper Name | Description |
| ------------ | ----------- |
| cache($key, $value, $tag = "general", $cache_in_minutes = 60) | To make a cache by key and value, you can also set the cache duration in minutes |
| cache($key) | To get the cache value by a key |
| cache_forget($key) | To forget a cache |
| cache_tag_forget($tag="general") | To forget cache by tag |

[[↑ Kembali ke daftar isi ↑]](#daftar-isi)

# Request
Jika pada PHP native Anda mengenal $_GET, $_POST, $_REQUEST, pada framework ini telah dibungkus ulang menjadi helper berikut

| Helper Name | Description |
| ------------ | ----------- |
| request_method_is_post() | To check the request is post (boolean) |
| request_method_is_get() | To check the request is get (boolean) |
| request_int($key) | To get request that should be integer |
| request_string($key) | To get request that should be string | 
| request_float($key) | To get request that should be float |
| request_email($key) | To get request that should be a valid email |
| request_url($key) | To get request that should be a valid URL |
| request() | Get all requests |
| request($key) | To get request with key |

[[↑ Kembali ke daftar isi ↑]](#daftar-isi)

# Response
Untuk menampilkan output controller dapat berupa json maupun view blade.

| Helper Name | Description |
| ------------ | ----------- |
| json($array) | To return the json response by an array |
| view($view_name, $data = []) | To return a view that  you create in {module}/Views/{view_name}.php. You can assign the data array on second parameter |

View pada superframework mengadopsi kehebatan "blade" yang Ada pada Laravel. Maka
bagi Anda pengguna Laravel pasti sudah terbiasa menggunakan blade ini.
Anda dapat membaca dokumentasi lebih banyak pada tautan ini [Blade](https://laravel.com/docs/8.x/blade)

[[↑ Kembali ke daftar isi ↑]](#daftar-isi)


# Validation
Anda dapat memvalidasi request user dengan class berikut ini 
```php 
Validator::make($requestData, $rules);
```
Atau lebih lengkap : 
```php 
// Pastikan Anda menambahkan baris ini pada bagian baris use class controller 
use SuperFrameworkEngine\App\UtilValidator\Validator;
use SuperFrameworkEngine\Exceptions\ValidatorException;

// Pada method Anda dapat memanggilnya sebagai berikut
try {
    Validator::make(request(),[
        'title'=>'required'
    ]);
    
    // Code milik Anda selanjutnya
    
} catch(ValidatorException $e) {
    redirect_back(['message'=>$e->getMessage(),'type'=>'warning']);
}
```
Berikut rule yang dapat Anda gunakan:
`required`, `email`, `url`, `int`, `unique:{table}`,`exists:{table},{field}`

[[↑ Kembali ke daftar isi ↑]](#daftar-isi)

# Database ORM
Untuk membuat query pada superframework Anda dapat menggunakan DATABASE ORM bawaan ini.

| Name | Description |
| ----- | ----- |
| DB("table")->all($limit) | To get all table data (in array), and you can pass the limit |
| DB("table")->get($limit = 10, $offset = 0, $paging = false) | This is alias of all() function |
| DB("table")->find($id) | To get the single record (in array) with a primary key value |
| DB("table")->where("status = 'Active'")->all($limit) | To get all table data with a condition |
| DB("table")->where("status = ?",[$status])->all() | To make a condition with bind |
| DB("table")->where("status = 'Active' AND price > 100000")->all($limit) | To get all table data with a multiple conditions. So you can write any condition in here, because this is a raw condition actually |
| DB("table")->whereIsset($keyword,"name like ?",["%".$keyword."%"])->get() | To make a condition where query. Where condition only applied if keyword is set |
| DB("table")->select("id","name","status","age as umur")->all() | To set the select of query | 
| DB("table")->addSelect("id")->addSelect("age as umur")->addSelect("price")->all() | or Sometime you want to add more select in the next query, just add this method chain, before calling all() / find() |
| DB("table")->limit($limit)->offset($offset)->all() | To get all table data with limit and offset |
| DB("table")->orderBy("id DESC")->all() | To get all table data with order by |
| DB("table")->groupBy("id, status")->all() | To get all table data with a group by fields |
| DB("table")->having("price > 10")->all() | To get al table data with having |
| DB("table")->join("categories ON categories.id = categories_id", $join_type = "LEFT JOIN")->addSelect("table.*")->addSelectTable("categories")->all() | To get all data with a join, second param you can pass type of join (INNER JOIN, LEFT JOIN, RIGHT JOIN, OUTER JOIN) *mysql. AddSelectTable is to make all selection to the specific table with format "table_{field}" |
| DB("table")->with("categories")->all() | You can use this join alias, if you can make sure that the foreign key is meet the naming convention ( {table}_id ) | 
| DB("table")->insert($data_array) | To insert to the table with an array data | 
| DB("table")->where("id = ?",[$id])->update($data_array) | To update the record data |
| DB("table")->where("id = ?",[$id])->delete() | To delete record with a condition | 
| DB("table")->delete($id) | To delete record with primary key value | 
| DB("table")->delete() | To delete all record data |
| DB("table")->count() | To count records | 
| DB("table")->sum("total") | To summarize records | 
| DB("table")->avg("age") | To average records |
| DB("table")->min("id") | To Get minimum id | 
| DB("table")->max("id") | To get maximum id |
| DB("table")->paginate($limit) | To make a pagination |

Lebih detail cara kerja fungsi ini Anda bisa merujuk ke file `vendor/fherryfherry/super-framework-engine/src/App/UtilORM/ORM.php` namun jangan mengubah file ini secara langsung karena perubahan Anda akan hilang jika Anda update.

[[↑ Kembali ke daftar isi ↑]](#daftar-isi)

# Helper
Berikut ini helper yang tersedia pada superframework 

| Helper Name | Description |
| ------------ | ----------- |
| get_current_url($param = [], $withQuery = false) | To get current url without query param. To modify query, set `$param` with array. To include query param set `$withQuery` to `true`|
| get_current_url(["q"=>"test"]) | To get current url and modify query "q" with "test" |'
| config("key", $default = null) | To retrieve config by a key (from Configs/config.php)| 
| base_url($path = "") | To get the base url of your project, and you can set the suffix path |
| base_path($path = "") | To get a root absolute path of project |
| admin_url($path = "") | To make an admin url instead you use url(), you can use this |
| asset($path = "") | To make url that pointing to public directory |
| public_path($path = "") | To get a root public absolute path of project |
| url($path = "") | To make url that pointing from index web |
| logging($content, $type = "error") | To make a log |
| random_string($length = 6) | To make a random string |
| csrf_input() | To add hidden html input about CSRF Token |
| csrf_token() | To add csrf token |
| dd($array1, $var1 [, $array_or_var]) | To debug the array or variable and exit the process |
| str_slug($text) | To make a slug url |
| return redirect($path = "",["message"=>"lipsum","type"="warning]) | To redirect a page to some page. In second argument is optional, but you can set if you want give a flash message. |
| return redirect_back(["message"=>"lipsum","type"="warning]) | To redirect a page to previous page. In first argument is optional, but you can set if you want give a flash message. |

# Collection
Anda bisa menggunakan fungsi `simple_collect` untuk mengolah suatu array menjadi collection sehingga memudahkan untuk memodifikasi, filtrasi dan lain-lain pada suatu array.

```php
$data = simple_collect([1,2,3,4])

// Menghitung total array
$result = $data->count();

// Mendapatkan array 
$result = $data->get();

// Menghitung summary by key
$result = $data->sum($key = "key");

// Menghitung rata-rata by key
$result = $data->avg($key = "key");

// Memberikan kondisi sama dengan dan mendapatkan 1 data
$result = $data->whereEqual("key","value")->first();

// Memberikan kondisi tidak sama dengan dan mendapatkan 1 data
$result = $data->whereNotEqual("key","value")->first();

// Memberikan kondisi in array dan mendapatkan data array
$result = $data->whereIn("key",[1,2,3,4])->get();

// Memberikan kondisi not in array dan mendapatkan data array
$result = $data->whereNotIn("key",[1,2,3,4])->get();

// Memberikan kondisi like
$result = $data->whereLike("key","value")->get();

// Memberikan kondisi negtive like
$result = $data->whereNotLike("key","value")->get();

// Memberikan kondisi lebih besar dari
$result = $data->whereGreaterThan("key",5)->get();

// Memberikan kondisi lebih kecil dari
$result = $data->whereLessThan("key",5)->get();

// Memberikan kondisi lebih besar dari sama dengan
$result = $data->whereGreaterThanEq("key",5)->get();

// Memberikan kondisi lebih kecil dari sama dengan
$result = $data->whereLessThanEq("key",5)->get();

```
[[↑ Kembali ke daftar isi ↑]](#daftar-isi)

# Firebase Cloud Message (FCM)
Anda bisa menggunakan class berikut untuk mengirim FCM

```php 
// Tambahkan import ini diatas nama class
use SuperFrameworkEngine\App\UtilFirebase\FCM;

// ===== Detail Penggunaan Class =====
$msg = new FCM();
$msg->title("Judul Pesan");
$msg->message("Deskripsi pesan");

// Untuk menambahkan data lain
$msg->data([
 "data1"=>"value1"
]); 

// Kirim fcm
$msg->send();
```

[[↑ Kembali ke daftar isi ↑]](#daftar-isi)

# Model, Repository, Service
Ini adalah sebuah pattern development. Kami menganjurkan untuk selalu menggunakan pattern ini ketika Anda membuat query pada database.
## Model
Berisi attribut-attribute sesuai dengan kolom yang ada pada tabel.

## Repository
Anda dapat membuat seluruh query pada aplikasi Anda menggunakan repository ini.

## Service
Anda dapat membuat query yang memiliki logika khusus pada class service ini.

```php 
// Anda bisa menggunakan model class untuk query database

// Dari Model
$data = Users::query()->where("id=?",[$id])->find();

// Dari Repository
$data = UsersRepository::query()->where("id=?",[$id])->find();

// Dari Service
$data = UsersService::query()->where("id=?",[$id])->find();
```

 Anda cukup menambahkan `query()` pada chain yang pertama. 

[[↑ Kembali ke daftar isi ↑]](#daftar-isi)

# Cron Job / Scheduler
Fitur cron job / scheduler pada superframework menggunakan library Crunz/Schedule. 
Anda dapat membuat sebuah file php baru di folder `/tasks` dengan isian seperti berikut : 
```php 
<?php

use Crunz\Schedule;

$schedule = new Schedule();

# Pada bagian ini Anda dapat menuliskan perintah command line
$task = $schedule->run(PHP_BINARY. ' super {command}');

# Pada bagian ini Anda dapat memberikan timeline waktu kapan cron ini akan dijalankan
# daily, hourly, everyThreeHours, dll
$task->daily()->description("Run feed content");

return $schedule;
```
Lalu Anda harus menambahkan perintah ini pada sistem `crontab` pada linux Anda.
```bash
* * * * * * cd /path/html/project/ && /var/bin/php super schedule:run 
```

[[↑ Kembali ke daftar isi ↑]](#daftar-isi)

# FAQ
**Install projek pada web server Nginx, ke sub folder, hasilnya 404 Not Found**

Biasanya kita harus menulis syntax rewrite khusus pada virtual host nginx. Tidak semua syntax yang Anda dapatkan akan langsung bekerja, biasanya muncul 404 Not Found. Cobalah solusi dari stackoverflow ini : 
[https://stackoverflow.com/a/61013994/934326](https://stackoverflow.com/a/61013994/934326)

# Useful Libraries
Berikut ini adalah library tambahan yang sangat berguna untuk menunjang pengembangan aplikasi Anda. Anda dapat menggabungkannya dengan *superframework*.
1. **CRUD Generator** - a crud generator for superframework
   [https://github.com/fherryfherry/crud-generator](https://github.com/fherryfherry/crud-generator)
1. **Simple HTML DOM Wrapper** - PHP Dom
   [https://github.com/Wikia/simplehtmldom](https://github.com/Wikia/simplehtmldom)
1. **PHPMailer** - Email sender
    [https://github.com/PHPMailer/PHPMailer](https://github.com/PHPMailer/PHPMailer)
1. **Imagine** - Image manipulation  
    [https://imagine.readthedocs.io/en/latest/index.html](https://imagine.readthedocs.io/en/latest/index.html)
1. **Snappy** - PDF Generation
    [https://github.com/KnpLabs/snappy](https://github.com/KnpLabs/snappy)
1. **PHPSpreadsheet** - Spreadsheet XLS Generation
    [https://phpspreadsheet.readthedocs.io/en/latest/](https://phpspreadsheet.readthedocs.io/en/latest/)
1. **Spout** - XLS fast read and write
    [https://opensource.box.com/spout/getting-started/](https://opensource.box.com/spout/getting-started/)
1. **DOMPDF** - PDF Generation
    [https://github.com/dompdf/dompdf](https://github.com/dompdf/dompdf)

[[↑ Kembali ke daftar isi ↑]](#daftar-isi)

## Support & Donation
Hi thanks for using my open source project, you could support me via :
[https://saweria.co/ferryariawan](https://saweria.co/ferryariawan)
or via [https://buymeacoffee.com/ferryariawan](https://buymeacoffee.com/ferryariawan)

# Contact
Laporan keamanan / celah / security dapat Anda kirimkan ke *ferdevelop15@gmail.com*

[[↑ Kembali ke daftar isi ↑]](#daftar-isi)
