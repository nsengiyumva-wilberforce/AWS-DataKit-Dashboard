<?php

namespace App\Controllers;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use MongoDB\Client as MongoDB;

class Util extends BaseController
{
	use ResponseTrait;

	public function answer_type()
	{
		$params = $this->request->getGet();
		$model = $this->db->table('answer_type');

		if (isset($params['answer_type_id'])) {
			$data = $model->where('answer_type_id', $params['answer_type_id'])->get()->getRow();
		} else {
			$data = $model->get()->getResult();
		}

		if($data){
			$response = [
				'status'   => 200,
				'error'    => null,
				'data' => $data
			];
			return $this->respond($response);
		}else{
			return $this->failNotFound('No Data Found with id ');
		}
	}

	public function admin_roles()
	{
		$params = $this->request->getGet();
		$model = $this->db->table('admin_role');

		if (isset($params['role_id'])) {
			$data = $model->where('role_id', $params['role_id'])->get()->getRow();
		} else {
			$data = $model->get()->getResult();
		}

		if($data){
			$response = [
				'status'   => 200,
				'error'    => null,
				'data' => $data
			];
			return $this->respond($response);
		}else{
			return $this->failNotFound('No Data Found with id ');
		}
	}




	public function app_lists()
	{
		$data['region']				= $this->db->table('region')->where('active', 1)->get()->getResult();
		$data['app_village']		= $this->db->table('village_view')->where('active', 1)->get()->getResult();
		$data['app_parish']			= $this->db->table('parish_view')->where('active', 1)->get()->getResult();
		$data['app_sub_county']		= $this->db->table('sub_county_view')->where('active', 1)->get()->getResult();
		$data['app_district']		= $this->db->table('district_view')->where('active', 1)->get()->getResult();
		$data['app_project']		= $this->db->table('app_project')->where('active', 1)->get()->getResult();
		$data['app_organisation']	= $this->db->table('app_organisation')->where('active', 1)->get()->getResult();

		$response = [
			'status'   => 200,
			'data' => $data
		];		
		return $this->respond($response);
	}


	public function app_tables()
	{
		// $query = $this->db->query("SELECT TABLE_NAME AS app_list FROM information_schema.tables WHERE table_name LIKE 'app_%' AND table_schema = 'aws'");
		$query = $this->db->query("SELECT * FROM information_schema.tables WHERE table_name LIKE 'app_%' AND table_schema = 'aws'");
		$tables = $query->getResult();

		$table_list = [];
		foreach ($tables as $table) {
			$table_list[] = $table->TABLE_NAME;
		}

		$response = [
			'status'   => 200,
			'error'    => null,
			'data' => $table_list
		];		
		return $this->respond($response);
	}


	public function overview_counter()
	{
		$client = new MongoDB();
		$collection = $client->aws->entries;
		$data['entries'] = $collection->countDocuments();
		$data['forms'] = $this->db->query('SELECT * FROM question_form')->getNumRows();
		$data['mobile_users'] = $this->db->query('SELECT * FROM user')->getNumRows();
		$data['projects'] = $this->db->query('SELECT * FROM app_project')->getNumRows();

		$response = [
			'status'   => 200,
			'error'    => null,
			'data' => $data
		];		
		return $this->respond($response);
	}






	public function user_region_areas()
	{
		$params = $this->request->getGet();

		$user = $this->db->table('user')->where('user_id', $params['user_id'])->get()->getRow();
		if ($user) {
			// $areas = $this->app_model->get_region_areas($user->region_id);
			$data['region']				= $this->db->table('region')->where('region_id', $user->region_id)->get()->getResult();
			$data['app_village']		= $this->db->table('village_view')->where('region_id', $user->region_id)->get()->getResult();
			$data['app_parish']			= $this->db->table('parish_view')->where('region_id', $user->region_id)->get()->getResult();
			$data['app_sub_county']		= $this->db->table('sub_county_view')->where('region_id', $user->region_id)->get()->getResult();
			$data['app_district']		= $this->db->table('district_view')->where('region_id', $user->region_id)->get()->getResult();

			$response = [
				'status'   => 200,
				'data' => $data
			];		
			return $this->respond($response);

		} else {
			return $this->failNotFound();
		}

	}










}
