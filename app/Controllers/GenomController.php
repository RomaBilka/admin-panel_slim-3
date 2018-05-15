<?php
	namespace App\Controllers;
	use App\Models\{Genom, Species, Genus, Division, GenomsCodon, GenNucleotidProcent, GenNucleotid, GenIndexes, GenData, GenCodonProcent, GenCodon, GenAminoProcent, GenAmino};

	class GenomController extends Controller{
		public function index($request, $response, $args){
			$lastGenoms = Genom::query()->leftjoin('user', 'genom.user_id', '=', 'user.user_id')->orderBy('id', 'desc')->get();
			$genoms = [];
			foreach ($lastGenoms as $genom){
				$genoms[] = [
						'id_genom' => $genom->id,
						'user_id' => $genom->user_id,
						'date' => $genom->date,
						'email' => $genom->email,
						'name' => $genom->name,	
						'time' => $genom->time_read,		
				];
			}
			return $response->withHeader('Content-type', 'application/json')->withHeader('Access-Control-Allow-Origin', '*')->withJson($genoms, 201);
		}
		public function getGenomById($request, $response, $args){
			$genom_id = (int)$args['genom_id'];	
			$genom = Genom::query()
							->leftjoin('user', 'genom.user_id', '=', 'user.user_id')
							->orderBy('id', 'desc')
							->where('genom.id', $genom_id)
							->get();

			foreach ($genom as $g){
				$genom_data = [
						'id_genom' => $g->id,
						'user_id' => $g->user_id,
						'date' => $g->date,
						'email' => $g->email,
						'locus' => $g->locus,
						'taxId' => $g->taxId,
						'division_id' => $g->division_id,
						'genus_id' => $g->genus_id,
						'species_id' => $g->species_id,
						'name' => $g->name,	
						'time' => $g->time_read,		
						'title' => $g->title,		
						'flag_CAI' => $g->flag_CAI,		
						'status' => $g->status,
					];
			}
		
			return $response->withHeader('Content-type', 'application/json')->withHeader('Access-Control-Allow-Origin', '*')->withJson($genom_data, 201);
	
		}

		public function updateGenom($request, $response, $args){
			$genom_id = (int)$args['genom_id'];
			$data = json_decode($request->getParsedBody()['data'],1);
			$genom = Genom::where('id','=', (int)$data['genom_id'])->update([
				'name' => $data['name'],
				'locus' => $data['locus'],
				'division_id' => $data['division_id'],
				'genus_id'  => $data['genus_id'],
				'species_id'  => $data['species_id'],
				'title'  => $data['title'],
				'flag_CAI'  => $data['flag_CAI'],
				'status' => $data['status']
			]);
			
		}
		public function deleteGenom($request, $response, $args){
			 $t = Genom::where('id', '=', (int)$args['genom_id'])->delete();
			 $t = GenomsCodon::where('id_genom', '=', (int)$args['genom_id'])->delete();
			 $t = GenNucleotidProcent::where('id_genom', '=', (int)$args['genom_id'])->delete();
			 $t = GenNucleotid::where('id_genom', '=', (int)$args['genom_id'])->delete();
			 $t = GenIndexes::where('id_genom', '=', (int)$args['genom_id'])->delete();
			 $t = GenData::where('id_genom', '=', (int)$args['genom_id'])->delete();
			 $t = GenCodonProcent::where('id_genom', '=', (int)$args['genom_id'])->delete();
			 $t = GenCodon::where('id_genom', '=', (int)$args['genom_id'])->delete();
			 $t = GenAminoProcent::where('id_genom', '=', (int)$args['genom_id'])->delete();
			 $t = GenAmino::where(id_genom)->delete();
		}
		public function genomEditPageData($request, $response){
			//Species
			$species = Species::get();
			$species_data = [];
			foreach($species as $s){
				$species_data[] = [
								'species_id' => $s->species_id,
								'species' => $s->species,
							];
			}
			//Division
			$division = Division::get();
			$division_data = [];
			foreach($division as $d){
				$division_data[] = [
								'division_id' => $d->division_id,
								'division' => $d->division,
							];
			}
			//Genus
			$genus = Genus::get();
			$genus_data = [];
			foreach($genus as $g){
				$genus_data[] = [
								'genus_id' => $g->genus_id,
								'genus' => $g->genus,
							];
			}
			$data = [
				'species'  => $species_data,
				'division' => $division_data,
				'genus'    => $genus_data
			];
			
			return $response->withHeader('Content-type', 'application/json')->withJson($data, 201);
		}
	}