<?php
	namespace App\Controllers;
	use App\Models\{User, Genom};

	class UserController extends Controller{
		public function index($request, $response){}
		public function getUsers($request, $response){	
			$user = User::orderBy('user_id', 'desc')->get();
			$users = [];
			foreach ($user as $u){
				$users[] = [
						'user_id'=>$u->user_id,
						'email'=>$u->email,
						'http_referer'=>$u->HTTP_REFERER,
						'remote_addr'=>$u->REMOTE_ADDR,
						'country'=>$u->country,	
						'city'=>$u->city,		
						'date'=>$u->date,		
				];
			}
			return $response->withHeader('Content-type', 'application/json')->withJson($users, 201);
		}
		public function getUserById($request, $response, $args){
			$data = [];
			$user_id = (int)$args['user_id'];	
			$user = User::where('user_id', $user_id)->get();
			$userData = [];
			foreach ($user as $u){
				$userData [] = [
						'user_id'=>$u->user_id,
						'email'=>$u->email,
						'http_referer'=>$u->HTTP_REFERER,
						'remote_addr'=>$u->REMOTE_ADDR,
						'country'=>$u->country,	
						'city'=>$u->city,		
						'date'=>$u->date,		
				];
			}
			$genoms = Genom::where('user_id', $user_id)->get();
			$genomsData = [];
			foreach($genoms as $g){
				$genomsData[] = [
					'genom_id' => $g->id,
					'date' => $g->date,
					'name' => $g->name,	
					'time' => $g->time_read,
					'status' => $g->status,
				]; 
			}
			$data = [
				'genoms'=>$genomsData,
				'user'=>$userData ,
			];
			return $response->withHeader('Content-type', 'application/json')->withJson($data, 201);
		}
	}