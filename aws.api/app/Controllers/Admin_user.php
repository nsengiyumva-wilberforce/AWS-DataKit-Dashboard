<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\AdminUserModel;
// use CodeIgniter\HTTP\Files\UploadedFile;


class Admin_user extends BaseController
{

	use ResponseTrait;

	public function index()
	{
		$params = $this->request->getGet();
		$model = $this->db->table('admin_user_view');

		if (isset($params['user_id'])) {
			$data = $model->where('user_id', $params['user_id'])->get()->getRow();
			$data->permission_list = json_decode($data->permission_list);
		} else {
			$data = $model->get()->getResult();
			foreach ($data as $admin) {
				$admin->permission_list = json_decode($admin->permission_list);
			}
		}

		if($data){
			$response = [
				'status'   => 200,
				'error'    => null,
				'messages' => [
					'success' => 'Data Saved'
				],
				'data' => $data
			];
			return $this->respond($response);
		}else{
			return $this->failNotFound('No Data Found with id '.$params['user_id']);
		}
	}



	// create

	public function create()
	{
		$params = $this->request->getPost();
		$model = new AdminUserModel();

		$user_id = $model->insert($params);
		if($user_id){
			$data = $model->getWhere(['user_id' => $user_id])->getRow();
			$response = [
				'status'   => 201,
				'error'    => null,
				'messages' => [
					'success' => 'Data Saved'
				],
				'data' => $data
			];
			return $this->respondCreated($response);
		}else{
			return $this->failNotFound('No Data Found with user_id '.$user_id);
		}
	}



	// update

	public function update()
	{
		$params = $this->request->getPost();
		$model = new AdminUserModel();

		$data = $model->update($params['user_id'], $params);
		if($model->update($params['user_id'], $params)){
			$data = $model->getWhere(['user_id' => $params['user_id']])->getRow();
			$response = [
				'status'   => 200,
				'error'    => null,
				'messages' => [
					'success' => 'Data Updated'
				],
				'data' => $data
			];

			return $this->respond($response);

		} else {
			return $this->failNotFound('No Data Found with user_id '.$params['user_id']);
		}
	}



	// delete

	public function delete()
	{
		$params = $this->request->getPost();
		$model = new AdminUserModel();

		$data = $model->find($params['user_id']);
		if($data){
			$model->delete($params['user_id']);
			$response = [
				'status'   => 200,
				'error'    => null,
				'messages' => [
					'success' => 'Data Deleted'
				]
			];

			return $this->respondDeleted($response);

		} else {
			return $this->failNotFound('No Data Found with user_id '.$params['user_id']);
		}
	}



	public function authenticate()
	{
		$params = $this->request->getPost();
		$model = $this->db->table('admin_user_view');

		$user = $model->where('email', $params['username'])->get()->getRow();
		if ($user) {
			if ($user->password == $params['password']) {
				$response = [
					'status'   => 200,
					'error'    => null,
					'messages' => [
						'success' => 'Password Updated'
					],
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
		$model = new AdminUserModel();

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
