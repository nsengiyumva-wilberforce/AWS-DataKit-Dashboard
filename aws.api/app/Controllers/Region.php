<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\RegionModel;
// use CodeIgniter\HTTP\Files\UploadedFile;


class Region extends BaseController
{

	use ResponseTrait;

	public function index()
	{
		$params = $this->request->getGet();
		$model = new RegionModel();

		if (isset($params['region_id'])) {
			$data = $model->getWhere(['region_id' => $params['region_id']])->getRow();
		} else {
			$data = $model->findAll();
		}

		if($data){
			$response = [
				'status'   => 200,
				'error'    => null,
				'data' => $data
			];
			return $this->respond($response);
		}else{
			return $this->failNotFound('No Data Found with id '.$id);
		}
	}



	// create

	public function create()
	{
		$params = $this->request->getPost();
		$model = new RegionModel();

		$region_id = $model->insert($params);
		if($region_id){
			$data = $model->getWhere(['region_id' => $region_id])->getRow();
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
			return $this->failNotFound('No Data Found with region_id '.$region_id);
		}
	}



	// update

	public function update()
	{
		$params = $this->request->getPost();
		$model = new RegionModel();

		$data = $model->update($params['region_id'], $params);
		if($model->update($params['region_id'], $params)){
			$data = $model->getWhere(['region_id' => $params['region_id']])->getRow();
			$response = [
				'status'   => 201,
				'error'    => null,
				'messages' => [
					'success' => 'Data Updated'
				],
				'data' => $data
			];
			return $this->respond($response);
		} else {
			return $this->failNotFound('No Data Found with region_id '.$params['region_id']);
		}
	}



	// delete

	public function delete()
	{
		$params = $this->request->getPost();
		$model = new RegionModel();

		$data = $model->find($params['region_id']);
		if($data){
			$model->delete($params['region_id']);
			$response = [
				'status'   => 200,
				'error'    => null,
				'messages' => [
					'success' => 'Data Deleted'
				]
			];

			return $this->respondDeleted($response);

		} else {
			return $this->failNotFound('No Data Found with region_id '.$params['region_id']);
		}
	}






}
