<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\FormModel;
use App\Models\QuestionModel;
use MongoDB\Client as MongoDB;

class Report extends BaseController
{

	use ResponseTrait;

	public function index()
	{
		ini_set('memory_limit','512M');
		$params = $this->request->getGet();

        $client = new MongoDB();
        $collection = $client->aws->entries;
		if (isset($params['entry_form_id'])) {
            $data = $collection->findOne(['entry_form_id' => $params['entry_form_id']]);
			$data->media_directory = base_url('writable/uploads/');

		} elseif (isset($params['response_id'])) {
            $data = $collection->findOne(['response_id' => $params['response_id']]);
			$data->media_directory = base_url('writable/uploads/');

		} elseif(isset($params['form_id'])) {

            $query['form_id'] = $params['form_id'];

            if (isset($params['entity_type'])) {
                $query['responses.entity_type'] = $params['entity_type'];
                $emb_doc_filter['entity_type'] = $params['entity_type'];
            }

            if (isset($params['start_date']) && isset($params['end_date'])) {
                $query['responses.created_at'] = array('$gte' => $params['start_date'], '$lte' => $params['end_date']);
                $emb_doc_filter['created_at'] = array('$gte' => $params['start_date'], '$lte' => $params['end_date']);
            }

            if (isset($params['creator_id'])) {
                $query['responses.creator_id'] = $params['creator_id'];
                $emb_doc_filter['creator_id'] = $params['creator_id'];
            }


            $project = array(
                'projection' => array(
                    'entry_form_id' => 1,
                    'form_id' => 1,
                    'created_at' => 1,
                    'updated_at' => 1,
                    'responses' => isset($emb_doc_filter) ? array('$elemMatch' => $emb_doc_filter) : 1
                )
            );

			// echo '<pre>';
			// print_r($query);
			// echo '<br>';
			// print_r($project); exit;

            $data = $collection->find($query, $project)->toArray();

        } else {
            $data = $collection->find()->toArray();
		}

		if($data){
			return $this->respond($data);
		} else {
			return $this->failNotFound('No Data Found with id ');
		}
	}
















}