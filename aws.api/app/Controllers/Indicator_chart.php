<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\IndicatorChartModel;
use MongoDB\Client as MongoDB;


class Indicator_chart extends BaseController
{

	use ResponseTrait;

	public function index()
	{
		$params = $this->request->getGet();
		$model = new IndicatorChartModel();

		$client = new MongoDB();
		$collection = $client->aws->entries;

		if (isset($params['chart_id'])) {
			$chart = $model->getWhere(['chart_id' => $params['chart_id']])->getRow();

            // $submissions = $collection->aggregate(
            // 	[
            // 		['$unwind' => "$"."responses"],
            // 		['$match' => ["responses.created_at" => ['$gte' => $params['startdate'], '$lte' => $params['enddate']]]], 
            // 		['$group' => ['_id' => "$"."responses.creator_id", 'commits' => [ '$sum' => 1 ]]]
            // 	]
            // )->toArray();

            $query['form_id'] = $chart->form_id;
            // $query['responses.qn'.$chart->question_id] = $chart->question_id;
            
            if (isset($chart->start_date) && isset($chart->end_date)) {
                $query['responses.created_at'] = array('$gte' => $chart->start_date, '$lte' => $chart->end_date);
                $emb_doc_filter['created_at'] = array('$gte' => $chart->start_date, '$lte' => $chart->end_date);
            }

            $project = array(
                'projection' => array(
                    '_id' => 0,
                    'responses' => isset($emb_doc_filter) ? array('$elemMatch' => $emb_doc_filter) : 1
                )
            );
            $data = $collection->find($query, $project)->toArray();

            $chart_values = [];
            foreach ($data as $entry) {
                if (isset($entry->responses[0]['qn'.$chart->question_id])) {
                    $key = $entry->responses[0]['qn'.$chart->question_id];

                    if (!isset($chart_values[$key])) {
                        $chart_values[$key] = 0;
                    }
                    $chart_values[$key] += 1;
                }
            }

            $chart->chart_keys = array_keys($chart_values);
            $chart->chart_values = $chart_values;
			$data = $chart;

		} else {
			$data = $model->findAll();

                    // echo '<pre>';
                    // print_r($data); exit;


			if ($data) {
				$chart_list = [];
				foreach ($data as $chart) {
                    $chart = (object)$chart;
                    $query['form_id'] = $chart->form_id;
                    // $query['responses.qn'.$chart->question_id] = $chart->question_id;
                    
                    if (isset($chart->start_date) && isset($chart->end_date)) {
                        $query['responses.created_at'] = array('$gte' => $chart->start_date, '$lte' => $chart->end_date);
                        $emb_doc_filter['created_at'] = array('$gte' => $chart->start_date, '$lte' => $chart->end_date);
                    }

                    $project = array(
                        'projection' => array(
                            '_id' => 0,
                            'responses' => isset($emb_doc_filter) ? array('$elemMatch' => $emb_doc_filter) : 1
                        )
                    );
                    $data = $collection->find($query, $project)->toArray();

                    $chart_values = [];
                    foreach ($data as $entry) {
                        if (isset($entry->responses[0]['qn'.$chart->question_id])) {
                            $key = $entry->responses[0]['qn'.$chart->question_id];

                            if (!isset($chart_values[$key])) {
                                $chart_values[$key] = 0;
                            }
                            $chart_values[$key] += 1;
                        }
                    }

                    $chart->chart_keys = array_keys($chart_values);
                    $chart->chart_values = $chart_values;
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
		$model = new IndicatorChartModel();

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
		$model = new IndicatorChartModel();

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
		$model = new IndicatorChartModel();

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
