<?php
namespace App\Auth;
use App\Models\Admin;

class Auth{

	public function check(){
		$admin = Admin::Where('token', $_SERVER['HTTP_X_TOKEN_TRINITY'])->first();
		if(!$admin) return false;
		return true;
	}
	public function attempt($login, $password){
		$admin = Admin::Where('login', $login)->first();
		if(!$admin) return false;

		if(password_verify($password, $admin->password)){
			$token = md5(microtime().$_SERVER['HTTP_USER_AGENT']);
			$admin = Admin::where('login','=', $login)->update([
				'token' => $token]);
			return $token;
		}
		return false;
	}
	
	public function logout(){
		$admin = Admin::where('token','=', $_SERVER['X_TOKEN_TRINITY'])->update([
				'token' => '']);
	}
}