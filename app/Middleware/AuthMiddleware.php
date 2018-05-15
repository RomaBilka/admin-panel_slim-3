<?php
namespace App\Middleware;

class AuthMiddleware extends Middleware{

	public function __invoke($request, $response, $next){
		
		if(!$this->container->auth->check()){
			return $response->withHeader('Content-type', 'application/json')->withJson(['error authentication'], 201);
		}
		$response = $next($request, $response);
		return $response;
	}
}