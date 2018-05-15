<?php
use \App\Middleware\AuthMiddleware;
$app->group('', function(){
	$this->get('/', 'HomeController:index');
	$this->get('/genoms[/{params:.*}]', 'GenomController:index');
	$this->get('/genom/{genom_id}', 'GenomController:getGenomById');
	$this->put('/genom/{genom_id}', 'GenomController:updateGenom');
	$this->delete('/genom/{genom_id}', 'GenomController:deleteGenom');
	$this->get('/genom-data', 'GenomController:genomEditPageData');
	$this->get('/users', 'UserController:getUsers');
	$this->get('/user/{user_id}', 'UserController:getUserById');
	$this->get('/messages', 'MessageController:getMessages');
	$this->get('/message/{message_id}', 'MessageController:getMessageById');
	$this->post('/message/{message_id}', 'MessageController:sendAnswer');
})->add(new AuthMiddleware($container));
$app->post('/auth/signin', 'AuthController:signIn');
