<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\UserModel;
use MongoDB\Client as MongoDB;
use App\Libraries\Utility;

// use CodeIgniter\HTTP\Files\UploadedFile;

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: GET, OPTIONS");
class User extends BaseController
{

	use ResponseTrait;

	public function index()
	{
		$params = $this->request->getGet();
		$model = $this->db->table('user_view');

		if (isset($params['user_id'])) {
			$data = $model->where('user_id', $params['user_id'])->get()->getRow();
			unset($data->password);
		} else {
			$data = $model->get()->getResult();
			foreach ($data as $user) {
				unset($user->password);
			}
		}

		if($data){
			$response = [
				'status'   => 200,
				'data' => $data
			];
			return $this->respond($response);
		}else{
			return $this->failNotFound('No Data Found with id '.$params['user_id']);
		}
	}





	public function data_submission()
	{
		// ini_set('memory_limit','256M');
		$params = $this->request->getGet();

		$utility = new Utility();
		$user_map = $utility->mobile_user_mapper();

        $client = new MongoDB();
        $collection = $client->aws->entries;

		$submissions = $collection->aggregate(
			[
				['$unwind' => "$"."responses"],
				['$match' => ["responses.created_at" => ['$gte' => $params['startdate'], '$lte' => $params['enddate']]]], 
				['$group' => ['_id' => "$"."responses.creator_id", 'commits' => [ '$sum' => 1 ]]]
			]
		)->toArray();

		foreach ($submissions as $submission) {
			$submission['name'] = $user_map[$submission->_id];
		}

		$response = [
			'status'   => 201,
			'data' => $submissions
		];

		return $this->respond($response);
	}




	// create

	public function create()
	{
		$params = $this->request->getPost();
		$model = new UserModel();

		$id = $model->insert($params);
		if($id){
			$data = $model->getWhere(['user_id' => $id])->getRow();
			$response = [
				'status'   => 201,
				'messages' => [
					'success' => 'Data Saved'
				],
				'data' => $data
			];
			return $this->respondCreated($response);
		}else{
			return $this->failNotFound('No Data Found with id '.$id);
		}
	}



	// update

	public function update()
	{
		$params = $this->request->getPost();
		$model = new UserModel();

		$data = $model->update($params['user_id'], $params);
		if($model->update($params['user_id'], $params)){
			$data = $model->getWhere(['user_id' => $params['user_id']])->getRow();
			$response = [
				'status'   => 200,
				'messages' => [
					'success' => 'Data Updated'
				],
				'data' => $data
			];

			return $this->respond($response);

		} else {
			return $this->failNotFound('No Data Found with id '.$params['user_id']);
		}
	}



	// delete

	public function delete()
	{
		$params = $this->request->getPost();
		$model = new UserModel();

		$data = $model->find($params['user_id']);
		if($data){
			$model->delete($params['user_id']);
			$response = [
				'status'   => 200,
				'messages' => [
					'success' => 'Data Deleted'
				]
			];

			return $this->respondDeleted($response);

		} else {
			return $this->failNotFound('No Data Found with id '.$params['user_id']);
		}
	}





	public function authenticate()
	{
		$params = $this->request->getPost();
		//$model = $this->db->table('user_view');
		$model = new UserModel();
		$user = $model->where('email', $params['username'])->get()->getRow();

		if ($user) {
			if ($user->password == $params['password']) {
				// unset($user->password);
				$response = [
					'status'   => 200,
					'data' => $user
				];
				return $this->respond($response);
			} else {
				return $this->failNotFound('Wrong username and password combination');
			}			
		} else {
			return $this->failNotFound('Account does not exist');
		}
	}












	public function change_password()
	{
		$params = $this->request->getPost();
		$model = new UserModel();

		if ($params['new_password'] == $params['confirm_password']) {
			$user = $model->getWhere(['user_id' => $params['user_id']])->getRow();
			if ($params['old_password'] == $user->password) {

				$user_data['password'] = $params['new_password'];
				if($model->update($params['user_id'], $user_data)){
					$data = $model->getWhere(['user_id' => $params['user_id']])->getRow();
					$response = [
						'status'   => 200,
						'error'    => null,
						'messages' => [
							'success' => 'Password Updated'
						],
						'data' => $data
					];
					return $this->respond($response);

				} else {
					return $this->failNotFound('No Data Found with id '.$params['user_id']);
				}

			} else {
				return $this->fail('Old Password is incorrect');
			}

		} else {
			return $this->fail('Passwords dont match');
		}
	}



}
