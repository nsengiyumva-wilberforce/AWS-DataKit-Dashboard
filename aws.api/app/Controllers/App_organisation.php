<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\AppOrganisationModel;
// use CodeIgniter\HTTP\Files\UploadedFile;


class App_organisation extends BaseController
{

	use ResponseTrait;

	public function index()
	{
		$params = $this->request->getGet();
		$model = $this->db->table('app_organisation');

		if (isset($params['organisation_id'])) {
			$data = $model->where('organisation_id', $params['organisation_id'])->get()->getRow();
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
			return $this->failNotFound('No Data Found with organisation_id '.$params['organisation_id']);
		}
	}



	// create

	public function create()
	{
		$params = $this->request->getPost();
		$model = new AppOrganisationModel();

		$organisation_id = $model->insert($params);
		if($organisation_id){
			$data = $model->getWhere(['organisation_id' => $organisation_id])->getRow();
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
			return $this->failNotFound('No Data Found with organisation_id '.$organisation_id);
		}
	}



	// update

	public function update()
	{
		$params = $this->request->getPost();
		$model = new AppOrganisationModel();

		$data = $model->update($params['organisation_id'], $params);
		if($model->update($params['organisation_id'], $params)){
			$data = $model->getWhere(['organisation_id' => $params['organisation_id']])->getRow();
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
			return $this->failNotFound('No Data Found with organisation_id '.$params['organisation_id']);
		}
	}



	// delete

	public function delete()
	{
		$params = $this->request->getPost();
		$model = new AppOrganisationModel();

		$data = $model->find($params['organisation_id']);
		if($data){
			$model->delete($params['organisation_id']);
			$response = [
				'status'   => 200,
				'error'    => null,
				'messages' => [
					'success' => 'Data Deleted'
				]
			];

			return $this->respondDeleted($response);

		} else {
			return $this->failNotFound('No Data Found with organisation_id '.$params['organisation_id']);
		}
	}






}
