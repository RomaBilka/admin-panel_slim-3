<?php
	namespace App\Controllers;
	use App\Models\Message;

	class MessageController extends Controller{
		public function index($request, $response){	}
		
		public function getMessages($request, $response){	
			$message = Message::orderBy('message_id', 'desc')->get();
			$messages = [];
			foreach ($message as $m){
				$messages[] = [
						'message_id' => $m->message_id,
						'email' => $m->email,
						'name' => $m->name,
						'theme' => $this->theme((int)$m->theme),
						'text' => substr($m->text, 0, 100),	
						'user_id' => (( $m->user_id > 0)?$m->user_id:0),		
						'http_x_real_ip' => $m->HTTP_X_REAL_IP,		
						'date' => $m->date,		
				];
			}
			return $response->withHeader('Content-type', 'application/json')->withHeader('Access-Control-Allow-Origin', '*')->withJson($messages, 201);
		}
		public function getMessageById($request, $response, $args){
			$message_id = (int)$args['message_id'];
			$message = $this->getMessageById($message_id);
			return $response->withHeader('Content-type', 'application/json')->withHeader('Access-Control-Allow-Origin', '*')->withJson($message, 201);
		}
		private function getMessage($message_id){
			$message = Message::where('message_id', $message_id)->get();
			foreach ($message as $m){
				$temp = [
						'message_id' => $m->message_id,
						'email' => $m->email,
						'name' => $m->name,
						'theme' => $this->theme((int)$m->theme),
						'text' => $m->text,	
						'answer' => $m->answer,	
						'user_id' => (( $m->user_id > 0)?$m->user_id:0),		
						'http_x_real_ip' => $m->HTTP_X_REAL_IP,		
						'date' => $m->date,		
				];
			}
			return $temp;
		}
		public function sendAnswer($request, $response, $args){
			$message_id = (int)$args['message_id'];
			$answer = $request->getParsedBody()['answer'];
			$genom = Message::where('message_id','=', (int)$message_id)->update([
				'answer' => $answer
			]);
			$this->sendMail($answer, $this->getMessageById($message_id));
			
		}
		private function sendMail($answer=string, $data=array()){
			$this->mail->From = $this->container['settings']['email'];
			$this->mail->FromName = 'Trinity';
			$this->mail->addAddress($data['email'], $data['name']);     // Add a recipient
			$this->mail->isHTML(true);                                  // Set email format to HTML
			$this->mail->Subject = 'Answer Triniry';
			$this->mail->Body    = $answer;
			if(!$this->mail->send()) {
				echo 'Message could not be sent.';
				echo 'Mailer Error: ' . $this->mail->ErrorInfo;
			} else {
				echo 'Message has been sent';
			}
		}
		private function theme($theme_id){
			switch ($theme_id) {
				case 1:
					return 'Питання';
				case 2:
					return 'Проблеми';
				case 3:
					return 'Пропозиція';
				case 4:
					return 'Баг';
				default:
				   return 'Невідома';
			}
		}
		
	}