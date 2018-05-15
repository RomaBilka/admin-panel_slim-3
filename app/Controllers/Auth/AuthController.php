<?php
namespace App\Controllers\Auth;
use App\Controllers\Controller;


class AuthController extends Controller{

	public function signOut($request, $response){
		$this->auth->logout();
	}
	
	public function signIn($request, $response){
		$auth = $this->auth->attempt(
			$request->getParsedBody()['login'],
			$request->getParsedBody()['password']
		);
	
		if(!$auth){
			return $response->withHeader('Content-type', 'application/json')->withJson(['error'=>'error login or password'], 201);
		}
		return $response->withHeader('Content-type', 'application/json')->withJson(['token'=>$auth], 201);
	}

}

