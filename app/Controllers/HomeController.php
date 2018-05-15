<?php
	namespace App\Controllers;
	use Slim\Views\Twig as View;
	use App\Models\Genom;
	use App\Models\Message;

	class HomeController extends Controller{
		public function index($request, $response){
			$lastGenoms = Genom::query()->leftjoin('user', 'genom.user_id', '=', 'user.user_id')->orderBy('id', 'desc')->take(100)->get();
			$genoms = [];
			foreach ($lastGenoms as $genom){
				$genoms[] = [
						'genom_id' => $genom->id,
						'user_id' => $genom->user_id,
						'date' => $genom->date,
						'email' => $genom->email,
						'name' => $genom->name,	
						'time' => $genom->time_read,		
				];
			}
			$lastMessage = Message::query()->orderBy('message_id', 'desc')->take(100)->get();
			$messages = [];
			foreach ($lastMessage as $message){
				$messages[] = [
						'message_id' => $message->message_id,
						'date' => $message->date,
						'email' => $message->email,
						'theme' => $message->theme,		
						'text' => substr($message->text, 0, 100),		
				];
			}
			$data = [
					'genoms'  => $genoms,
					'messages'  => $messages,
			];

			return $response->withHeader('Content-type', 'application/json')->withHeader('Access-Control-Allow-Origin', '*')->withJson($data, 201);
		}
		
	}