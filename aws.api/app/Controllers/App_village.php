<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\AppVillageModel;
// use CodeIgniter\HTTP\Files\UploadedFile;


class App_village extends BaseController
{

	use ResponseTrait;

	public function index()
	{
		$params = $this->request->getGet();
		$model = $this->db->table('village_view');

		if (isset($params['village_id'])) {
			$data = $model->where('village_id', $params['village_id'])->get()->getRow();
		} elseif (isset($params['region_id'])) {
			$data = $model->where('region_id', $params['region_id'])->get()->getResult();
		} elseif (isset($params['district_id'])) {
			$data = $model->where('district_id', $params['district_id'])->get()->getResult();
		} elseif (isset($params['sub_county_id'])) {
			$data = $model->where('sub_county_id', $params['sub_county_id'])->get()->getResult();
		} elseif (isset($params['parish_id'])) {
			$data = $model->where('parish_id', $params['parish_id'])->get()->getResult();
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
		$model = new AppVillageModel();

		$village_id = $model->insert($params);
		if($village_id){
			$data = $model->getWhere(['village_id' => $village_id])->getRow();
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
			return $this->failNotFound('No Data Found with village_id '.$village_id);
		}
	}



	// update

	public function update()
	{
		$params = $this->request->getPost();
		$model = new AppVillageModel();

		$data = $model->update($params['village_id'], $params);
		if($model->update($params['village_id'], $params)){
			$data = $model->getWhere(['village_id' => $params['village_id']])->getRow();
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
			return $this->failNotFound('No Data Found with village_id '.$params['village_id']);
		}
	}



	// delete

	public function delete()
	{
		$params = $this->request->getPost();
		$model = new AppVillageModel();

		$data = $model->find($params['village_id']);
		if($data){
			$model->delete($params['village_id']);
			$response = [
				'status'   => 200,
				'error'    => null,
				'messages' => [
					'success' => 'Data Deleted'
				]
			];

			return $this->respondDeleted($response);

		} else {
			return $this->failNotFound('No Data Found with village_id '.$params['village_id']);
		}
	}






}
