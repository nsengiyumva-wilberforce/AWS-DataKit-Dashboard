<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\AppParishModel;
// use CodeIgniter\HTTP\Files\UploadedFile;


class App_parish extends BaseController
{

	use ResponseTrait;

	public function index()
	{
		$params = $this->request->getGet();
		$model = $this->db->table('parish_view');

		if (isset($params['parish_id'])) {
			$data = $model->where('parish_id', $params['parish_id'])->get()->getRow();
		} elseif (isset($params['region_id'])) {
			$data = $model->where('region_id', $params['region_id'])->get()->getResult();
		} elseif (isset($params['district_id'])) {
			$data = $model->where('district_id', $params['district_id'])->get()->getResult();
		} elseif (isset($params['sub_county_id'])) {
			$data = $model->where('sub_county_id', $params['sub_county_id'])->get()->getResult();
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
			return $this->failNotFound('No Data Found');
		}
	}



	// create

	public function create()
	{
		$params = $this->request->getPost();
		$model = new AppParishModel();

		$parish_id = $model->insert($params);
		if($parish_id){
			$data = $model->getWhere(['parish_id' => $parish_id])->getRow();
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
			return $this->failNotFound('No Data Found with parish_id '.$parish_id);
		}
	}



	// update

	public function update()
	{
		$params = $this->request->getPost();
		$model = new AppParishModel();

		$data = $model->update($params['parish_id'], $params);
		if($model->update($params['parish_id'], $params)){
			$data = $model->getWhere(['parish_id' => $params['parish_id']])->getRow();
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
			return $this->failNotFound('No Data Found with parish_id '.$params['parish_id']);
		}
	}



	// delete

	public function delete()
	{
		$params = $this->request->getPost();
		$model = new AppParishModel();

		$data = $model->find($params['parish_id']);
		if($data){
			$model->delete($params['parish_id']);
			$response = [
				'status'   => 200,
				'error'    => null,
				'messages' => [
					'success' => 'Data Deleted'
				]
			];

			return $this->respondDeleted($response);

		} else {
			return $this->failNotFound('No Data Found with parish_id '.$params['parish_id']);
		}
	}






}
