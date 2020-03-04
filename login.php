<?php 
define("REQUESTS", $_SERVER['REQUEST_URI']);

//добавляем конфиг
require_once('app/config.php');

//добавляем класс сессии
require_once('app/models/Session.class.php');
Session::start();

//добавляем класс базы данных
require_once('app/models/MyDB.class.php');
require_once('app/models/MyDB_Helper.class.php');

$mydb = new MyDB_Helper();	
 
 ini_set('error_reporting',E_ALL);
ini_set('display_errors',1);
ini_set('display_startup_errors',1);


//добавляем класс сессии
require_once('app/models/Validation.class.php');
$validation = new Validation($mydb);



if(!empty($_GET['do']) && $method = $_GET['do']) {

	if($method != 'logout' && !empty($_GET['email']) && !empty($_GET['password'])){
					
		$email = urldecode($_GET['email']);
		$password = md5(urldecode($_GET['password']));


	}
	switch ($method) {
		// функция логинизации
		case 'authorize':

				$title_page = 'Страница авторизации'; 

 				if(!empty($email) && !empty($password)) {

 						$validation->loginValidation($email,$password); 
 						die();
 				}

			break;

		// функция выхода из сайта
		case 'logout':

			SESSION::destroy();
		    header("location: ".APP_BASE_URL);
		    die();

			break;

		// функция регистрации
		case 'register':
				$title_page = 'Регистрация нового пользователя';

				if(!empty($email) && !empty($password)){
					
					$validation->registrationValidation($email,$password); 
					die();
				}



			break;	
	}

}


 ?>