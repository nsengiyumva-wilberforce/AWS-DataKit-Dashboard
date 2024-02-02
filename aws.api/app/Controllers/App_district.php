<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\AppDistrictModel;
// use CodeIgniter\HTTP\Files\UploadedFile;


class App_district extends BaseController
{

	use ResponseTrait;

	public function index()
	{
		$params = $this->request->getGet();
		$model = $this->db->table('district_view');

		if (isset($params['district_id'])) {
			$data = $model->where('district_id', $params['district_id'])->get()->getRow();
		} elseif (isset($params['region_id'])) {
			$data = $model->where('region_id', $params['region_id'])->get()->getResult();
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
			return $this->failNotFound('No Data Found');
		}
	}



	// create

	public function create()
	{
		$params = $this->request->getPost();
		$model = new AppDistrictModel();

		$district_id = $model->insert($params);
		if($district_id){
			$data = $model->getWhere(['district_id' => $district_id])->getRow();
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
			return $this->failNotFound('No Data Found with district_id '.$district_id);
		}
	}



	// update

	public function update()
	{
		$params = $this->request->getPost();
		$model = new AppDistrictModel();

		if($model->update($params['district_id'], $params)){
			$data = $model->getWhere(['district_id' => $params['district_id']])->getRow();
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
			return $this->failNotFound('No Data Found with district_id '.$params['district_id']);
		}
	}



	// delete

	public function delete()
	{
		$params = $this->request->getPost();
		$model = new AppDistrictModel();

		$data = $model->find($params['district_id']);
		if($data){
			$model->delete($params['district_id']);
			$response = [
				'status'   => 201,
				'error'    => null,
				'messages' => [
					'success' => 'Data Deleted'
				]
			];

			return $this->respondDeleted($response);

		} else {
			return $this->failNotFound('No Data Found with district_id '.$params['district_id']);
		}
	}






}
