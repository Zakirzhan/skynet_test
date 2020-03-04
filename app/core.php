<?php 

ini_set('error_reporting',E_ALL);

ini_set('display_errors',1);

ini_set('display_startup_errors',1);

header("Access-Control-Allow-Orgin: *");

header("Access-Control-Allow-Methods: *");

header('Content-type: application/json; Charset=utf8');

//добавляем конфиг
require_once('config.php');

//добавляем класс базы данных
require_once('app/models/MyDB.class.php');
require_once('app/models/MyDB_Helper.class.php');
$mydb = new MyDB_Helper();

require_once('router.php');

?>