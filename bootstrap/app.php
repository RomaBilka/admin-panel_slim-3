<?php
session_start();
require __DIR__ .'/../vendor/autoload.php';  
require __DIR__ .'\..\vendor\PHPMailer\phpmailer\src\Exception.php';
require __DIR__ .'\..\vendor\PHPMailer\phpmailer\src\PHPMailer.php';
require __DIR__ .'\..\vendor\PHPMailer\phpmailer\src\SMTP.php';
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


  
$app = new \Slim\App([
	'settings'=> [
		'displayErrorDetails'=>true,
		'email'=>'trinity@test.ua',
		'db' => [
				'driver' => 'mysql',
				'host'=>'localhost',
				'database'=>'trinity',
				'username'=>'root',
				'password'=>'',
				'charset'=>'utf8',
				'colaction'=>'utf8_unicode_ci',
				'prefix'=>''
			]
	]
]);
$container = $app->getContainer();

$capsule = new \Illuminate\Database\Capsule\Manager;
$capsule->addConnection($container['settings']['db']);
$capsule->setAsGlobal();
$capsule->bootEloquent();

$container['db'] = function($container) use ($capsule){
	return $capsule;
};

$container['mail'] = function($container){
	$mail = new PHPMailer(true);
	return $mail;
};

$container['view'] = function($container){
	$view = new \Slim\Views\Twig(__DIR__ .'/../resources/views',[
			'cashe' => false,
	]);
	
	$view->addExtension(new \Slim\Views\TwigExtension(
		$container->router,
		$container->request->getURI()
	));
	return $view;
};

$container['HomeController'] = function($container){
	return new \App\Controllers\HomeController($container);
};
$container['GenomController'] = function($container){
	return new \App\Controllers\GenomController($container);
};
$container['UserController'] = function($container){
	return new \App\Controllers\UserController($container);
};
$container['MessageController'] = function($container){
	return new \App\Controllers\MessageController($container);
};
$container['auth'] = function($container){
	return new \App\Auth\Auth;
};
$container['AuthController'] = function($container){
	return new \App\Controllers\Auth\AuthController($container);
};

require __DIR__ .'/../app/routes.php';
