<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\ChartModel;
use MongoDB\Client as MongoDB;


class Chart extends BaseController
{

	use ResponseTrait;

	public function index()
	{
		$params = $this->request->getGet();
		$model = new ChartModel();

		$client = new MongoDB();
		$collection = $client->aws->entries;

		if (isset($params['chart_id'])) {
			$chart = (array) $model->getWhere(['chart_id' => $params['chart_id']])->getRow();

			$form_list = json_decode($chart['form_list']);
			$form_entries = 0;
			foreach ($form_list as $form_id) {
				$count_documents = $collection->countDocuments(['form_id' => $form_id]);
				$form_entries += $count_documents;
			}
			$chart['actual'] = $form_entries;
			$chart['difference'] = $chart['target'] - $form_entries;
			$data = $chart;

		} else {
			$data = $model->findAll();

			if ($data) {
				$chart_list = [];
				foreach ($data as $chart) {
					$form_list = json_decode($chart['form_list']);
					$form_entries = 0;
					foreach ($form_list as $form_id) {
						$count_documents = $collection->countDocuments(['form_id' => $form_id]);
						$form_entries += $count_documents;
					}

					$chart['actual'] = $form_entries;
					$chart['difference'] = $chart['target'] - $form_entries;
					$chart_list[] = $chart;
				}
				$data = $chart_list;
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
			return $this->failNotFound('No Data Found with id '.$id);
		}
	}



	// create

	public function create()
	{
		$params = $this->request->getPost();
		$model = new ChartModel();

		$id = $model->insert($params);
		if($id){
			$data = $model->getWhere(['chart_id' => $id])->getRow();
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
			return $this->failNotFound('No Data Found with id '.$id);
		}
	}



	// update

	public function update()
	{
		$params = $this->request->getPost();
		$model = new ChartModel();

		$data = $model->update($params['chart_id'], $params);
		if($model->update($params['chart_id'], $params)){
			$data = $model->getWhere(['chart_id' => $params['chart_id']])->getRow();
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
			return $this->failNotFound('No Data Found with id '.$params['chart_id']);
		}
	}



	// delete

	public function delete()
	{
		$params = $this->request->getPost();
		$model = new ChartModel();

		$data = $model->find($params['chart_id']);
		if($data){
			$model->delete($params['chart_id']);
			$response = [
				'status'   => 200,
				'error'    => null,
				'messages' => [
					'success' => 'Data Deleted'
				]
			];

			return $this->respondDeleted($response);

		} else {
			return $this->failNotFound('No Data Found with id '.$params['chart_id']);
		}
	}






}
