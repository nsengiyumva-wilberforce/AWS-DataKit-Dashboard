<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\AppProjectModel;
// use CodeIgniter\HTTP\Files\UploadedFile;


class App_project extends BaseController
{

	use ResponseTrait;

	public function index()
	{
		$params = $this->request->getGet();
		$model = $this->db->table('app_project');

		if (isset($params['project_id'])) {
			$data = $model->where('project_id', $params['project_id'])->get()->getRow();
		} else {
			$data = $model->get()->getResult();
		}

		if($data){
			$response = [
				'status'   => 201,
				'error'    => null,
				'data' => $data
			];
			return $this->respond($response);
		}else{
			return $this->failNotFound('No Data Found with project_id '.$project_id);
		}
	}



	// create

	public function create()
	{
		$params = $this->request->getPost();
		$model = new AppprojectModel();

		$project_id = $model->insert($params);
		if($project_id){
			$data = $model->getWhere(['project_id' => $project_id])->getRow();
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
			return $this->failNotFound('No Data Found with project_id '.$project_id);
		}
	}



	// update

	public function update()
	{
		$params = $this->request->getPost();
		$model = new AppprojectModel();

		$data = $model->update($params['project_id'], $params);
		if($model->update($params['project_id'], $params)){
			$data = $model->getWhere(['project_id' => $params['project_id']])->getRow();
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
			return $this->failNotFound('No Data Found with project_id '.$params['project_id']);
		}
	}



	// delete

	public function delete()
	{
		$params = $this->request->getPost();
		$model = new AppprojectModel();

		$data = $model->find($params['project_id']);
		if($data){
			$model->delete($params['project_id']);
			$response = [
				'status'   => 200,
				'error'    => null,
				'messages' => [
					'success' => 'Data Deleted'
				]
			];

			return $this->respondDeleted($response);

		} else {
			return $this->failNotFound('No Data Found with project_id '.$params['project_id']);
		}
	}






}
