<?php
	namespace App\Models;
	use Illuminate\Database\Eloquent\Model;
	//use Illuminate\Database\Capsule\Manager as DB;
	class Genom extends Model{
		protected $table = 'genom';
		public $timestamps = false;
		
		public function getGenoms($start=0, $limit=5, $sort='asc'){ 
			//$genom = new Genom ;
		/*	return DB::table($genom->table)
			->join('user', $genom->table.'.user_id', '=', 'user.user_id')
			->orderBy('id', 'desc')
			->take($limit)
			->get();*/
			//return $this->hasOne('App\User');
			return self::query()->leftjoin('user', 'genom.user_id', '=', 'user.user_id')->orderBy('id', 'desc')->take($limit)->get();
			//return Genom::leftjoin('user', $genom->table.'.user_id', '=', 'user.user_id')->orderBy('id', 'desc')->take($limit)->get();

		}
	}