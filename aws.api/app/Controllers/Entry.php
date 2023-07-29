<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\FormModel;
use App\Models\QuestionModel;
use MongoDB\Client as MongoDB;

use App\Libraries\Utility;

class Entry extends BaseController
{

	use ResponseTrait;




	public function test()
	{
		ini_set('memory_limit', '3000M');
		// ini_set('memory_limit','1024M');
		$utility = new Utility();
		$params = $this->request->getGet();

		$client = new MongoDB();
		$collection = $client->aws->entries;

		$query['form_id'] = $params['form_id'];
		// {"created": {"$gt" : ISODate("2016-04-09T08:28:47") }},
		//$query['responses.created_at'] > ISODate("2021-02-18");

		if (isset($params['region_id']) && $params['region_id'] != 0) {
			/*				$district_list = $utility->region_district_array($params['region_id']);
					 $query['responses.qn4'] = ['$in' => $district_list];
					 */
		}



		$project = [

			'projection' => [
				'_id' => 0,
				'responses' => ['$slice' => 1]
				// 'responses' => ['$sort' => -1, '$slice' => 1 ]
			],
			'limit' => 10000
		];

		$data = $collection->find($query, $project)->toArray();
		//print json_encode($data);
		$data = json_decode(json_encode($data), TRUE);



		$total = sizeof($data = $collection->find($query, ['_id' => 1])->toArray());

		//return $this->respond($data);
		// Get form title ids
		$form_titles = $utility->form_titles($params['form_id']);

		// Cleaning values to only return needed data

		// return $this->respond($data);
		$new_data = [];
		foreach ($data as $entry) {
			//			if($entry['created_at'] > '2020-05-02 12:43:12'){

			$title_str = '';
			foreach ($form_titles['title'] as $item) {
				if (gettype($entry['responses'][0]['qn' . $item]) == 'array') {
					$title_str .= $entry['responses'][0]['qn' . $item][0];
				} else {
					$title_str .= $entry['responses'][0]['qn' . $item];
				}
			}
			$entry['title'] = $title_str != '' ? $title_str : 'Unknown Title';

			$sub_title_str = '';
			foreach ($form_titles['sub_title'] as $item) {
				if (gettype($entry['responses'][0]['qn' . $item]) == 'array') {
					$sub_title_str .= $entry['responses'][0]['qn' . $item][0];
				} else {
					$sub_title_str .= $entry['responses'][0]['qn' . $item];
				}
			}
			$entry['sub_title'] = $sub_title_str != '' ? $sub_title_str : 'Unknown Sub Title';


			$user_map = $utility->mobile_user_mapper();
			// Fetch first creator information


			$entry['creator_id'] = $user_map[$entry['responses'][0]['creator_id'] ?? "72"];

			// Fetch last creator information
			// if (count($entry['responses']) > 1) {
			//      $last_entry = end($entry['responses']);
			//      $entry['creator_id'] = $user_map[$entry[$last_entry['creator_id']]];
			// } else {
			//      $entry['last_creator'] = NULL;

			$new_data[] = $entry;
			//								}
		}

		$response = [
			'status' => 200,
			'total' => $total,
			'data' => $new_data
		];
		return $this->respond($response);



	}


	public function index()
	{
		ini_set('memory_limit', '512M');
		$utility = new Utility();
		$params = $this->request->getGet();

		$client = new MongoDB();
		$collection = $client->aws->entries;

		if (isset($params['entry_form_id'])) {
			$entry = $collection->findOne(['entry_form_id' => $params['entry_form_id']]);
			$entry->media_directory = base_url('writable/uploads/');
			$data = [
				'status' => 200,
				'data' => $entry
			];

		} elseif (isset($params['response_id'])) {
			$entry = $collection->findOne(['response_id' => $params['response_id']]);
			// $entry->media_directory = base_url('writable/uploads/');

			// Get form title ids
			$form_titles = $utility->form_titles($entry->form_id);

			$title_str = '';
			$sub_title_str = '';

			foreach ($form_titles['title'] as $item) {
				$title_str .= $entry['responses'][0]['qn' . $item];
			}

			foreach ($form_titles['sub_title'] as $item) {
				$sub_title_str .= $entry['responses'][0]['qn' . $item];
			}

			$entry->title = $title_str ?? 'Title';
			$entry->sub_title = $sub_title_str ?? 'Sub Title';


			$user_map = $utility->mobile_user_mapper();
			// Fetch creator information
			for ($i = 0; $i < count($entry['responses']); $i++) {
				$entry['responses'][$i]['creator'] = $user_map[$entry['responses'][$i]['creator_id']];
			}

			$data = [
				'status' => 200,
				'data' => $entry
			];

		} elseif (isset($params['form_id'])) {

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
					'_id' => 0,
					// 'entry_form_id' => 1,
					'response_id' => 1,
					'form_id' => 1,
					'created_at' => 1,
					'updated_at' => 1,
					'responses' => isset($emb_doc_filter) ? array('$elemMatch' => $emb_doc_filter) : 1
				)
			);
			$data = $collection->find($query, $project)->toArray();

		} else {
			$data = $collection->find()->toArray();
		}

		if ($data) {
			return $this->respond($data);
		} else {
			return $this->failNotFound('No Data Found with id ');
		}
	}


	public function getEntry()
	{
		$utility = new Utility();
		$user_map = $utility->mobile_user_mapper();

		$params = $this->request->getGet();

		$client = new MongoDB();
		$collection = $client->aws->entries;
		$entry = $collection->findOne(['response_id' => $params['response_id']]);
		if (isset($params['index'])) {
			if (isset($entry->responses[$params['index']])) {
				$entry->response = $entry->responses[$params['index']];
				$entry->response->creator = $user_map[$entry->response->creator_id];
				unset($entry->responses);
			} else {
				return $this->failNotFound('No Data Found with index ' . $params['index']);
			}
		} else {
			foreach ($entry->responses as $response) {
				$response->creator = $user_map[$response->creator_id ?? "72"];
			}
		}

		$model = new FormModel();
		$form = $model->getWhere(['form_id' => $entry->form_id])->getRow();

		$form->question_list = json_decode($form->question_list);

		// $qn_ids = array();
		$model = $this->db->table('question');
		$qn_ids = (array) $form->question_list;
		for ($i = 0; $i < count($qn_ids); $i++) {
			$questions[$i] = $model->where('question_id', $qn_ids[$i])->get()->getRow();
		}
		foreach ($questions as $qn) {
			$qn_data['qn' . $qn->question_id] = $qn->question;
			$qn_data['qn' . $qn->question_id] = $qn->question;
		}
		$number_of_responses = count($entry->responses);
		$baseline = (array) $entry->responses[0];
		$latest_followup = (array) $entry->responses[$number_of_responses - 1];
		$followups = [];
		foreach ($qn_data as $key => $value) {
			if (isset($qn_data[$key]) && isset($baseline[$key])) {
				$comp['baseline'][] = array('question' => $qn_data[$key], 'response' => $baseline[$key]);
			}
			if (isset($qn_data[$key]) && isset($latest_followup[0][$key])) {
				$comp['followup'][0][] = array('question' => $qn_data[$key], 'response' => $latest_followup[0][$key]);
				$photo_mobile_path = explode('/', $latest_followup[0]['photo']);
				$filename = end($photo_mobile_path);
				$comp['followup'][0]['photo'] = $filename;
				$comp['has_an_array'] = 1;
			}
			if (isset($qn_data[$key]) && isset($latest_followup[$key])) {
				$comp['followup'][] = array('question' => $qn_data[$key], 'response' => $latest_followup[$key]);
				$photo_mobile_path = explode('/', $latest_followup['photo']);
				$filename = end($photo_mobile_path);
				$comp['followup']['photo'] = $filename;
				$comp['has_an_array'] = 0;
			}
			if (isset($latest_followup[0]['creator_id'])) {
				$comp['followup'][0]['followup_creator'] = $user_map[$latest_followup[0]['creator_id']];
			}

			if (isset($followup['creator_id'])) {
				$comp['followup']['followup_creator'] = $user_map[$latest_followup['creator_id']];
			}
		}

		//Generate a list of followups
		for ($i = 1; $i < $number_of_responses; $i++) {
			$followup = (array) $entry->responses[$i];
			foreach ($qn_data as $key => $value) {
				if (isset($qn_data[$key]) && isset($followup[0][$key])) {
					$comp['followups'][$i][] = array('question' => $qn_data[$key], 'response' => $followup[0][$key]);
					$photo_mobile_path = explode('/', $followup[0]['photo']);
					$filename = end($photo_mobile_path);
					$comp['followups'][$i]['photo'] = $filename;
				}
				if (isset($qn_data[$key]) && isset($followup[$key])) {
					$comp['followups'][$i][] = array('question' => $qn_data[$key], 'response' => $followup[$key]);
					$photo_mobile_path = explode('/', $followup['photo']);
					$filename = end($photo_mobile_path);
					$comp['followups'][$i]['photo'] = $filename;
				}
				if (isset($followup[0]['creator_id'])) {
					$comp['followups'][$i]['followup_creator'] = $user_map[$followup[0]['creator_id']];
				}

				if (isset($followup['creator_id'])) {
					$comp['followups'][$i]['followup_creator'] = $user_map[$followup['creator_id']];
				}
			}
		}
		if (!property_exists($entry, 'title')) {
			if (property_exists($entry->responses[0], 'qn10')) {
				$entry['title'] = $entry->responses[0]->qn10;
			}

			if (property_exists($entry->responses[0], 'qn65')) {
				$entry['title'] = $entry->responses[0]->qn65;
			}

			if (property_exists($entry->responses[0], 'qn457')) {
				$entry['title'] = $entry->responses[0]->qn457;
			}

			if (property_exists($entry->responses[0], 'qn152')) {
				$entry['title'] = $entry->responses[0]->qn152;
			}
		}


		$photo_mobile_path = explode('/', $baseline['photo']);
		$filename = end($photo_mobile_path);
		$comp['baseline']['photo'] = $filename;
		$data = $entry;
		$data['comp'] = $comp;
		$data['followup_count'] = $number_of_responses - 1;
		$data['baseline']['photo_file'] = $baseline['photo'] ?? null;
		$data['media_directory'] = "https://dashboard.africawatersolutions.org/aws.api/writable/uploads/";
		if ($data) {
			$response = [
				'status' => 200,
				'data' => $data
			];
			return $this->respond($response);
		} else {
			return $this->failNotFound('No Data Found with id ');
		}
	}



	public function downloadable_region_entries()
	{
		ini_set('memory_limit', '512M');
		$utility = new Utility();
		$params = $this->request->getGet();

		$client = new MongoDB();
		$collection = $client->aws->entries;

		$last_date = NULL;

		if (isset($params['region_id'])) {
			$district_list = $utility->region_district_array($params['region_id']);
			$query['responses.qn4'] = ['$in' => $district_list];
		}

		if (isset($params['form_id'])) {
			$query['form_id'] = $params['form_id'];
			// Get Followup interval
			$form = $this->db->table('question_form')->where('form_id', $params['form_id'])->get()->getRow();
			if (!is_null($form->followup_interval)) {
				$followup_interval = $form->followup_interval;
				$last_date = date('Y-m-d H:i:s', strtotime('-' . $followup_interval . ' days'));
				$query['updated_at'] = array('$lte' => $last_date);
			}
		}

		if (isset($params['creator_id'])) {
			$query['responses.creator_id'] = $params['creator_id'];
		}

		if (isset($params['project'])) {
			$query['responses.qn148'] = $params['project'];
		}

		if (isset($params['set_followup_interval'])) {
			// echo date('Y-m-d H:i:s', strtotime('-7 days'));
			$last_date = date('Y-m-d H:i:s', strtotime('-' . $params['set_followup_interval'] . ' days'));
			$query['updated_at'] = array('$lte' => $last_date);
		}

		$date = '2022-11-01 12:00:00';
		$query['responses.created_at'] = array('$gte' => $date);

		// $emb_doc_filter['created_at'] = array('$gte' => $params['start_date'], '$lte' => $params['end_date']);
		// echo json_encode($query); exi
		$project = array(
			'projection' => array(
				'_id' => 0,
				'response_id' => 1,
				'form_id' => 1,
				'created_at' => 1,
				'updated_at' => 1,
				// 'responses' => ['$sort' => -1, '$slice' => 1]
				'responses' => 1
				// 'responses' => $emb_doc_filter
			)
		);
		$data = $collection->find($query, $project)->toArray();

		// Get form title ids
		$form_titles = $utility->form_titles($params['form_id']);

		// Cleaning values to only return needed data
		$new_data = [];

		foreach ($data as $entry) {
			$title_str = '';
			foreach ($form_titles['title'] as $item) {
				if (gettype($entry['responses'][0]['qn' . $item]) == 'array') {
					$title_str .= $entry['responses'][0]['qn' . $item][0];
				} else {
					$title_str .= $entry['responses'][0]['qn' . $item];
				}
			}
			$entry['title'] = $title_str != '' ? $title_str : 'Unknown Title';

			$sub_title_str = '';
			foreach ($form_titles['sub_title'] as $item) {
				if (gettype($entry['responses'][0]['qn' . $item]) == 'array') {
					$sub_title_str .= $entry['responses'][0]['qn' . $item][0];
				} else {
					$sub_title_str .= $entry['responses'][0]['qn' . $item];
				}
			}
			$entry['sub_title'] = $sub_title_str != '' ? $sub_title_str : 'Unknown Sub Title';

			if (isset($entry['responses'][0]['qn4'])) {
				$entry['district'] = $entry['responses'][0]['qn4'];
			}
			if (isset($entry['responses'][0]['qn7'])) {
				$entry['sub_county'] = $entry['responses'][0]['qn7'];
			}
			if (isset($entry['responses'][0]['qn8'])) {
				$entry['parish'] = $entry['responses'][0]['qn8'];
			}
			if (isset($entry['responses'][0]['qn9'])) {
				$entry['village'] = $entry['responses'][0]['qn9'];
			}

			$user_map = $utility->mobile_user_mapper();
			// Fetch first creator information


			$entry['creator_id'] = $user_map[$entry['responses'][0]['creator_id'] ?? "72"];

			$new_data[] = $entry;

		}





		if ($data) {
			$response = [
				'status' => 200,
				'data' => $new_data
			];
			return $this->respond($response);
		} else {
			return $this->failNotFound();
		}

		// 	echo json_encode($query); exit;
	}


	public function getRegionalEntries()
	{
		ini_set('memory_limit', '3000M');
		$utility = new Utility();
		$params = $this->request->getGet();

		$client = new MongoDB();
		$collection = $client->aws->entries;

		$user_map = $utility->mobile_user_mapper();

		$query['form_id'] = $params['form_id'];

		if (isset($params['region_id']) && $params['region_id'] != 0) {
			$district_list = $utility->region_district_array($params['region_id']);
			$query['responses.qn4'] = ['$in' => $district_list];
		}

		if (isset($params['start_date']) && isset($params['end_date'])) {
			$query['responses.created_at'] = array('$gte' => $params['start_date'], '$lte' => $params['end_date']);
		}

		if (!isset($params['year'])) {
			$date = '2023-01-01 12:43:12';
			$query['responses.created_at'] = array('$gte' => $date);

		} else {
			$date = $params['year'] . '-01-01 12:43:12';
			$date_to = $params['year'] . '-12-30 12:43:12';
			$query['responses.created_at'] = array('$gte' => $date, '$lte' => $date_to);
		}

		$project = [
			'projection' => [
				'_id' => 0,
				//'responses' => ['$slice' => 1]
			],
		];

		$data = $collection->find($query, $project)->toArray();
		$data = json_decode(json_encode($data), TRUE);

		// Get form title ids
		$form_titles = $utility->form_titles($params['form_id']);

		// Cleaning values to only return needed data
		$new_data = [];
		foreach ($data as $entry) {
			$number_of_responses = count($entry['responses']);
			$title_str = '';
			foreach ($form_titles['title'] as $item) {
				if (gettype($entry['responses'][0]['qn' . $item]) == 'array') {
					$title_str .= $entry['responses'][0]['qn' . $item][0];
				} else {
					$title_str .= $entry['responses'][0]['qn' . $item];
				}
			}
			$entry['title'] = $title_str != '' ? $title_str : 'Unknown Title';

			$sub_title_str = '';
			foreach ($form_titles['sub_title'] as $item) {
				if (gettype($entry['responses'][0]['qn' . $item]) == 'array') {
					$sub_title_str .= $entry['responses'][0]['qn' . $item][0];
				} else {
					$sub_title_str .= $entry['responses'][0]['qn' . $item];
				}
			}
			$entry['sub_title'] = $sub_title_str != '' ? $sub_title_str : 'Unknown Sub Title';

			if (isset($entry['responses'][0]['qn4'])) {
				$entry['district'] = $entry['responses'][0]['qn4'];
			}
			if (isset($entry['responses'][0]['qn7'])) {
				$entry['sub_county'] = $entry['responses'][0]['qn7'];
			}
			if (isset($entry['responses'][0]['qn8'])) {
				$entry['parish'] = $entry['responses'][0]['qn8'];
			}
			if (isset($entry['responses'][0]['qn9'])) {
				$entry['village'] = $entry['responses'][0]['qn9'];
			}

			$entry['number_of_responses'] = $number_of_responses;
			//get the last creator
			if ($number_of_responses > 1) {
				$last_follower = $entry['responses'][$number_of_responses - 1][0]['creator_id'] ?? $entry['responses'][$number_of_responses - 1]['creator_id'];
				$entry['last_follower'] = $user_map[$last_follower];
			}
			$user_map = $utility->mobile_user_mapper();
			// Fetch first creator information


			$entry['creator_id'] = $user_map[$entry['responses'][0]['creator_id'] ?? "72"];

			$new_data[] = $entry;
		}

		$response = [
			'status' => 200,

			//'total'   =>$total,
			'data' => $new_data
		];
		return $this->respond($response);



	}

















	public function form_entry_geodata()
	{
		ini_set('memory_limit', '512M');
		// ini_set('memory_limit','1024M');
		$utility = new Utility();
		$params = $this->request->getGet();

		$client = new MongoDB();
		$collection = $client->aws->entries;

		$query = [
			'form_id' => $params['form_id'],
			'responses.entity_type' => 'baseline'
		];

		$project = [
			'projection' => [
				'_id' => 0,
				'responses' => ['$slice' => 1]
			],
			'limit' => 8000
		];
		$data = $collection->find($query, $project)->toArray();

		// Form titles
		$form_titles = $utility->form_titles($params['form_id']);
		// Cleaning values to only return needed data
		$marker_list = [];
		foreach ($data as $entry) {

			$coordinates_str = $entry['responses'][0]['coordinates'];
			if (isset($coordinates_str)) {
				$new_data['response_id'] = $entry['response_id'];
				// $new_data['entry_form_id'] = $entry['entry_form_id'];
				$new_data['project'] = $entry['responses'][0]['qn148'] ?? 'NIL';

				$title_str = '';
				foreach ($form_titles['title'] as $item) {
					$title_str .= $entry['responses'][0]['qn' . $item];
				}
				$new_data['title'] = $title_str ?? 'Title';

				$sub_title_str = '';
				foreach ($form_titles['sub_title'] as $item) {
					$sub_title_str .= $entry['responses'][0]['qn' . $item];
				}
				$new_data['sub_title'] = $sub_title_str ?? 'Sub Title';

				$coordinates = explode(',', $coordinates_str);
				$new_data['coordinates'] = ['lat' => $coordinates[0], 'lon' => $coordinates[1]];
				$marker_list[] = $new_data;
			}
		}

		$response = [
			'status' => 200,
			'data' => $marker_list
		];
		return $this->respond($response);
	}


	public function form_entries()
	{
		ini_set('memory_limit', '512M');
		// ini_set('memory_limit','1024M');
		$utility = new Utility();
		$params = $this->request->getGet();
		$client = new MongoDB();
		$collection = $client->aws->entries;

		$query['form_id'] = $params['form_id'];
		if (isset($params['region_id']) && $params['region_id'] != 0) {
			$district_list = $utility->region_district_array($params['region_id']);
			$query['responses.qn4'] = ['$in' => $district_list];
		}

		if (isset($params['start_date']) && isset($params['end_date'])) {
			$query['responses.created_at'] = array('$gte' => $params['start_date'], '$lte' => $params['end_date']);
		}

		$project = [
			'projection' => [
				'_id' => 0,
				'responses' => ['$slice' => 1]
				// 'responses' => ['$sort' => -1, '$slice' => 1 ]
			]
		];

		$data = $collection->find($query, $project)->toArray();
		$data = json_decode(json_encode($data), TRUE);

		// Get form title ids
		$form_titles = $utility->form_titles($params['form_id']);

		// Cleaning values to only return needed data
		$new_data = [];
		foreach ($data as $entry) {
			$title_str = '';
			foreach ($form_titles['title'] as $item) {
				if (gettype($entry['responses'][0]['qn' . $item]) == 'array') {
					$title_str .= $entry['responses'][0]['qn' . $item][0];
				} else {
					$title_str .= $entry['responses'][0]['qn' . $item];
				}
			}
			$entry['title'] = $title_str != '' ? $title_str : 'Unknown Title';

			$sub_title_str = '';
			foreach ($form_titles['sub_title'] as $item) {
				if (gettype($entry['responses'][0]['qn' . $item]) == 'array') {
					$sub_title_str .= $entry['responses'][0]['qn' . $item][0];
				} else {
					$sub_title_str .= $entry['responses'][0]['qn' . $item];
				}
			}
			$entry['sub_title'] = $sub_title_str != '' ? $sub_title_str : 'Unknown Sub Title';

			if (isset($entry['responses'][0]['qn4'])) {
				$entry['district'] = $entry['responses'][0]['qn4'];
			}
			if (isset($entry['responses'][0]['qn7'])) {
				$entry['sub_county'] = $entry['responses'][0]['qn7'];
			}
			if (isset($entry['responses'][0]['qn8'])) {
				$entry['parish'] = $entry['responses'][0]['qn8'];
			}
			if (isset($entry['responses'][0]['qn9'])) {
				$entry['village'] = $entry['responses'][0]['qn9'];
			}

			$user_map = $utility->mobile_user_mapper();
			// Fetch first creator information
			$entry['first_creator'] = $user_map[$entry['responses'][0]['creator_id']];

			// Fetch last creator information
			if (count($entry['responses']) > 1) {
				$last_entry = end($entry['responses']);
				$entry['first_creator'] = $user_map[$entry[$last_entry['creator_id']]];
			} else {
				$entry['last_creator'] = NULL;
			}

			$new_data[] = $entry;
		}

		$response = [
			'status' => 200,
			'data' => $new_data
		];
		return $this->respond($response);
	}






	public function compiled_entry()
	{
		// ini_set('memory_limit','512M');
		$utility = new Utility();
		$params = $this->request->getGet();

		$client = new MongoDB();
		$collection = $client->aws->entries;

		$entry = $collection->findOne(['response_id' => $params['response_id']]);
		// $entry = json_decode(json_encode($entry), TRUE);

		if ($entry) {
			$entry->media_directory = base_url('writable/uploads/');

			// Get form title ids
			$form_titles = $utility->form_titles($entry->form_id);

			$title_str = '';
			$sub_title_str = '';

			foreach ($form_titles['title'] as $item) {
				$title_str .= $entry['responses'][0]['qn' . $item];
			}

			foreach ($form_titles['sub_title'] as $item) {
				$sub_title_str .= $entry['responses'][0]['qn' . $item];
			}

			$entry->title = $title_str ?? 'Unknown Title';
			$entry->sub_title = $sub_title_str ?? 'Unknown Sub Title';

			// ====================================================

			$user_map = $utility->mobile_user_mapper();
			$compilation = $utility->question_mapper($entry->form_id);

			$compiled_entry = [];
			foreach ($entry->responses as $response) {
				$compiled_response = [];
				foreach ($response as $key => $value) {
					if (isset($compilation[$key])) {
						$compiled_response[] = array('question' => $compilation[$key], 'response' => $value);
					}
				}

				$clean['compilation'] = $compiled_response;
				if (isset($response['photo_file']))
					$clean['photo_file'] = $response['photo_file'];
				if (isset($response['coordinates']))
					$clean['coordinates'] = $response['coordinates'];
				if (isset($response['creator_id']))
					$clean['creator_id'] = $response['creator_id'];
				if (isset($response['creator_id']))
					$clean['creator'] = $user_map[$response['creator_id']];
				if (isset($response['entity_type']))
					$clean['entity_type'] = $response['entity_type'];
				if (isset($response['created_at']))
					$clean['created_at'] = $response['created_at'];
				$compiled_entry[] = $clean;
			}

			$entry->responses = (object) $compiled_entry;

			$data = [
				'status' => 200,
				'data' => $entry
			];
		}

		if ($data) {
			return $this->respond($data);
		} else {
			return $this->failNotFound('No Data Found with id ');
		}
	}

	public function form_entries_report()
	{
		ini_set('memory_limit', '512M');
		// ini_set('memory_limit','1024M');
		$utility = new Utility();
		$params = $this->request->getGet();

		$client = new MongoDB();
		$collection = $client->aws->entries;

		$query['form_id'] = $params['form_id'];

		if (isset($params['region_id'])) {
			$district_list = $utility->region_district_array($params['region_id']);
			$query['responses.qn4'] = ['$in' => $district_list];
		}

		if (isset($params['project'])) {
			$query['responses.qn148'] = $params['project'];
			// $emb_doc_filter['project'] = $params['project'];
		}

		if (isset($params['startdate']) && isset($params['enddate'])) {
			if ($params['entry_data'] == "baseline")
				$query['responses.created_at'] = ['$gte' => $params['startdate'], '$lte' => $params['enddate']];

			if ($params['entry_data'] == "followup") {
				$query['$or'] = [];

				for ($i = 1; $i <= 6; $i++) {
					$query['$or'][] = ["responses.$i.created_at" => ['$gte' => $params['startdate'], '$lte' => $params['enddate']]];
				}
			}

			//$emb_doc_filter['created_at'] = array('$gte' => $params['startdate'], '$lte' => $params['enddate']);
		}

		$project = array(
			'projection' => array(
				'_id' => 0,
				'response_id' => 1,
				'responses' => 1,
				'entry_form_id' => 1,
				'responses' => ($params["entry_data"] == "baseline") ? (array('$slice' => array(0, 1))) : (array('$slice' => array(1, 6)))
				// 'responses' =>  ['$elemMatch' => ['created_at' => ['$gte' => $params['startdate'], '$lte' => $params['enddate']]]]
			)
		);






		$data['headers'] = $utility->question_mapper($params['form_id']);
		$data['entries'] = $collection->find($query, $project)->toArray();
		$data['entry_data'] = $params['entry_data'];


		if ($params['entry_data'] == "followup") {
			$newEntries = []; // Create a new array to hold the updated entries

			foreach ($data['entries'] as $entry) {
				$newResponses = []; // Create a new array to hold the updated responses for each entry

				foreach ($entry->responses as $response) {
					if (($response[0]['created_at'] > $params['startdate']) && ($response[0]['created_at'] < $params['enddate'])) {
						$newResponses[] = $response; // Add the response to the new array if it satisfies the condition
					}
				}

				$entry->responses = $newResponses; // Replace the responses of the current entry with the updated responses
				$newEntries[] = $entry; // Add the updated entry to the new array
			}

			$data['entries'] = $newEntries; // Replace the original entries with the updated entries
		}

		$response = [
			'status' => 200,
			'data' => $data
		];

		return $this->respond($response);
	}
	public function form_entries_aggregated_report()
	{
		ini_set('memory_limit', '512M');
		// ini_set('memory_limit','1024M');
		$utility = new Utility();
		$params = $this->request->getGet();
		$params['region_id'] = $params['field_id'];

		$client = new MongoDB();
		$collection = $client->aws->entries;

		if ($params['group_by'] == 'village') {
			$results = $this->db->table('village_view')->where('region_id', $params['region_id'])->get()->getResult();
			$orArray = array_map(function ($value) {
				return ["district" => $value];
			}, array_column($results, 'name'));
			$group_stage_query = "responses.qn9";
		} elseif ($params['group_by'] == 'parish') {
			$results = $this->db->table('parish_view')->where('region_id', $params['region_id'])->get()->getResult();
			$orArray = array_map(function ($value) {
				return ["district" => $value];
			}, array_column($results, 'name'));
			$group_stage_query = "responses.qn8";
		} elseif ($params['group_by'] == 'sub_county') {
			$results = $this->db->table('sub_county_view')->where('region_id', $params['region_id'])->get()->getResult();
			$orArray = array_map(function ($value) {
				return ["district" => $value];
			}, array_column($results, 'name'));
			$group_stage_query = "responses.qn7";
		} elseif ($params['group_by'] == 'district') {
			$results = $this->db->table('district_view')->where('region_id', $params['region_id'])->get()->getResult();
			$orArray = array_map(function ($value) {
				return ["district" => $value];
			}, array_column($results, 'name'));
			$group_stage_query = "responses.qn4";
		}
		$header = $utility->form_subheader_mapper($params['form_id']);
		$answer_counter = $header['answer_counter'];
		$entry_list = $collection->aggregate(
			[
				['$match' => ['form_id' => $params['form_id']]],
				['$unwind' => ['path' => '$responses']],
				['$unwind' => ['path' => '$responses']],
				[
					'$match' => [
						'responses.entity_type' => $params['data_type'],
						'responses.qn148' => $params['project'],
						'$and' => [
							['responses.created_at' => ['$gt' => $params['startdate']]],
							['responses.created_at' => ['$lt' => $params['enddate']]]
						]
					]
				],
				['$group' => ['_id' => ['response_id' => '$response_id', 'created_at' => '$responses.created_at'], 'responses' => ['$push' => ['response_id' => '$response_id', 'created_at' => '$created_at', 'responses' => '$responses', 'active' => '$active', 'district' => '$district']]]],
				['$replaceWith' => ['document' => ['$arrayElemAt' => ['$responses', 0]]]],
				['$project' => ['response_id' => '$document.response_id', 'form_id' => '$document.form_id', 'title' => '$document.title', 'sub_title' => '$document.sub_title', 'district' => '$document.' . $group_stage_query, 'responses' => ['$objectToArray' => ['qn154' => ['$toInt' => '$document.responses.qn154'], 'qn155' => '$document.responses.qn155', 'qn412' => '$document.responses.qn412', 'qn157' => ['$toInt' => '$document.responses.qn157'], 'qn159' => ['$toInt' => '$document.responses.qn159'], 'qn219' => '$document.responses.qn219', 'qn162' => '$document.responses.qn162', 'qn167' => '$document.responses.qn167', 'qn168' => '$document.responses.qn168', 'qn174' => '$document.responses.qn174', 'qn171' => '$document.responses.qn171', 'qn173' => '$document.responses.qn173', 'qn221' => '$document.responses.qn221', 'qn179' => '$document.responses.qn179', 'qn181' => '$document.responses.qn181', 'qn223' => '$document.responses.qn223', 'qn183' => '$document.responses.qn183', 'qn184' => '$document.responses.qn184', 'qn189' => '$document.responses.qn189', 'qn191' => '$document.responses.qn191', 'qn193' => '$document.responses.qn193', 'qn194' => '$document.responses.qn194', 'qn217' => '$document.responses.qn217', 'qn225' => '$document.responses.qn225', 'qn197' => '$document.responses.qn197', 'qn206' => '$document.responses.qn206', 'qn414' => '$document.responses.qn414', 'qn424' => '$document.responses.qn424', 'qn416' => '$document.responses.qn416', 'qn418' => ['$toInt' => '$document.responses.qn418'], 'qn420' => '$document.responses.qn420', 'qn421' => '$document.responses.qn421', 'qn198' => ['$toInt' => '$document.responses.qn198'], 'qn201' => ['$toInt' => '$document.responses.qn201']]]]],
				['$match' => ['$or' => $orArray]],
				['$unwind' => ['path' => '$responses']],
				['$unwind' => ['path' => '$responses.v']],
				['$group' => ['_id' => ['district' => '$district', 'k' => '$responses.k', 'responses' => '$responses.v'], 'sum' => ['$sum' => ['$cond' => ['if' => ['$isNumber' => '$responses.v'], 'then' => '$responses.v', 'else' => 0]]], 'count' => ['$sum' => 1]]],
				['$group' => ['_id' => ['district' => '$_id.district', 'question' => '$_id.k'], 'entries' => ['$push' => ['k' => '$_id.responses', 'v' => '$count']], 'Total' => ['$sum' => '$sum']]],
				['$addFields' => ['entries' => ['$cond' => [['$gt' => ['$Total', 0]], ['Total' => '$Total'], '$entries']]]],
				['$unwind' => ['path' => '$entries']],
				['$match' => ['$expr' => ['$or' => [['$eq' => [['$type' => '$entries.k'], 'string']], ['$and' => [['$eq' => [['$type' => '$entries.Total'], 'int']], ['$ne' => ['$entries.Total', 0]]]]]]]],
				['$group' => ['_id' => ['district' => '$_id.district', 'question' => '$_id.question'], 'entries' => ['$push' => ['k' => '$entries.k', 'v' => '$entries.v']], 'Total' => ['$sum' => '$Total']]],
				['$addFields' => ['entries' => ['$cond' => [['$gt' => ['$Total', 0]], ['Total' => '$Total'], '$entries']]]],
				['$addFields' => ['entries' => ['$cond' => ['if' => ['$isArray' => '$entries'], 'then' => ['$arrayToObject' => '$entries'], 'else' => '$entries']]]],
				['$group' => ['_id' => '$_id.district', 'entries' => ['$count' => (object) []], 'aggregate' => ['$push' => ['k' => '$_id.question', 'v' => '$entries']]]],
				['$project' => ['_id' => 0, 'name' => '$_id', 'entries' => '$entries', 'aggregate' => ['$arrayToObject' => '$aggregate']]]
			]
		)->toArray();
		
		foreach ($entry_list as $key => $entry) {
			$new_object = []; // Create an empty array to store the new objects
			$new_object['name'] = $entry['name'];
			$new_object['entries'] = $entry->aggregate->qn155->Male+$entry->aggregate->qn155->Female;
			$new_object['aggregate'] = $answer_counter;
			$new_array[] = $new_object; // Push the new object to the $new_array

			foreach($answer_counter as $key_1=>$value)
			{
				foreach ($entry->aggregate as $key_2 => $value_2) {
					if($key_1==$key_2){
						foreach($value as $key_3=>$value_3){
							foreach($value_2 as $key_4 => $value_4){
								if($key_3 == $key_4){
									$new_array[$key]['aggregate'][$key_1][$key_3] = $value_4;
								}
							}
						}
					}
				}
			}
		}
		$data['main_header'] = $header['main_header'];
		$data['sub_header'] = $header['sub_header'];
		$data['data_rows'] = $new_array;
		$response = [
			'status' => 200,
			'data' => $data
		];
		return $this->respond($response);
	}




	// create entry

	public function create()
	{
		$params = $this->request->getPost();
		if (count($params)) {
			$params['responses'] = array(json_decode($params['responses']));

			$client = new MongoDB();
			$collection = $client->aws->entries;

			// Check if entry exists
			$query = array('response_id' => $params['response_id']);
			$count = $collection->count($query);
			if ($count == 0) {
				// Insert entry
				$insertOneResult = $collection->insertOne($params);
				if ($insertOneResult->getInsertedCount() > 0) {
					$data = $collection->findOne(['_id' => $insertOneResult->getInsertedId()]);
					$response = [
						'status' => 201,
						'messages' => [
							'success' => 'Data Saved'
						],
						'data' => $data
					];
					return $this->respondCreated($response);
				} else {

				}
			} else {
				return $this->fail('Entry already exists');
			}
		} else {
			return $this->fail('Commit was unsuccessful');
		}
	}

	// create entry followup
	public function create_entry_followup()
	{
		$params = $this->request->getPost();
		$params['responses'] = array(json_decode($params['responses']));

		$client = new MongoDB();
		$collection = $client->aws->entries;
		$updateResult = $collection->updateOne(
			// [ 'entry_form_id' => $params['entry_form_id'] ],
			['response_id' => $params['response_id']],
			['$set' => ['updated_at' => date('Y-m-d H:i:s')], '$push' => ['responses' => $params['responses']]]
		);

		if ($updateResult->getModifiedCount() > 0) {
			$data = $collection->findOne(['response_id' => $params['response_id']]);
			$response = [
				'status' => 201,
				'messages' => [
					'success' => 'Data Saved'
				],
				'data' => $data
			];
			return $this->respond($response);
		} else {
			return $this->failNotFound('No Data Found with id ' . $params['entry_form_id']);
		}

	}


	// create entry photo (base64)

	public function create_last_entry_photo()
	{
		$params = $this->request->getPost();

		$client = new MongoDB();
		$collection = $client->aws->entries;
		$entry = $collection->findOne(['response_id' => $params['response_id']]);
		$responses_count = count($entry->responses);
		$index = $responses_count - 1;

		// convert file form base64 and upload it
		$base64_string = $params['photo_base64'];
		$base64_string = trim($base64_string);

		$base64_string = str_replace('data:image/jpeg;base64,', '', $base64_string);
		$base64_string = str_replace('[removed]', '', $base64_string);
		$base64_string = str_replace(' ', '+', $base64_string);

		$file_path = WRITEPATH . 'uploads/' . $params['filename'];
		$decoded = base64_decode($base64_string);
		file_put_contents($file_path, $decoded);

		// update response by adding filename
		$updateResult = $collection->updateOne(
			['response_id' => $params['response_id']],
			['$set' => ['updated_at' => date('Y-m-d H:i:s'), 'responses.' . $index . '.photo_file' => $params['filename']]]
		);

		if ($updateResult->getModifiedCount() > 0) {
			try {
				// $data = $collection->findOne(['entry_form_id' => $params['entry_form_id']]);
				$data = $collection->findOne(['response_id' => $params['response_id']]);
				$response = [
					'status' => 200,
					'messages' => [
						'success' => 'Photo Saved'
					],
					'data' => $data
				];
				return $this->respond($response);
			} catch (\Exception $e) {
				return $this->fail($e->getMessage());
			}
		} else {
			return $this->failNotFound('No Data Found with id ' . $params['entry_form_id']);
		}
	}



	// update entry

	public function update()
	{
		$params = $this->request->getPost();

		$client = new MongoDB();
		$collection = $client->aws->entries;
		$updateResult = $collection->updateOne(
			// [ 'entry_form_id' => $params['entry_form_id'] ],
			['response_id' => $params['response_id']],
			['$set' => ['updated_at' => date('Y-m-d H:i:s'), 'responses.' . $params['index'] => json_decode($params['response'])]] // .0 is the index of the response being updated $params['response_index']

			// [ '$set' => [ 'name' => 'Brunos on Astoria' ]]
			// [ '$set' => [ 'responses.0' => $params['responses'] ]] // .0 is the index of the response being updated $params['response_index']
		);

		if ($updateResult->getModifiedCount() > 0) {
			// $data = $collection->findOne(['entry_form_id' => $params['entry_form_id']]);
			$data = $collection->findOne(['response_id' => $params['response_id']]);
			$response = [
				'status' => 201,
				'error' => null,
				'messages' => [
					'success' => 'Data Saved'
				],
				'data' => $data
			];
			return $this->respond($response);
		} else {
			return $this->failNotFound('No Data Found with id');
		}

		// { $push: { scores: { $each: [ 90, 92, 85 ] } } }

		// db.test.update(
		//    { _id: 1 },
		//    { $addToSet: { letters: [ "c", "d" ] } }
		// )

	}



	// delete

	public function delete()
	{
		$params = $this->request->getPost();

		$client = new MongoDB();
		$collection = $client->aws->entries;

		$query = array('response_id' => $params['response_id']);
		$count = $collection->count($query);
		if ($count > 0) {
			// $collection->remove(['response_id' => $params['response_id']], ['justOne' => TRUE]);
			$status = $collection->deleteOne(['response_id' => $params['response_id']]);
			if ($status) {
				$response = [
					'status' => 200,
					'error' => null,
					'messages' => [
						'success' => 'Data Deleted'
					]
				];
				return $this->respondDeleted($response);
			} else {
				return $this->fail('Entry was not deleted');
			}
		} else {
			return $this->failNotFound('No Entry Found');
		}
	}



	public function send_to_mongo($start_date = NULL, $end_date = NULL)
	{
		ini_set('memory_limit', '1024M');

		$client = new MongoDB();
		$collection = $client->aws->entries;

		$builder = $this->db->table('response');

		if (!is_null($start_date)) {
			// $builder->where('date_created >=' . $start_date);
			$builder->where('DATE(date_created) >= DATE("' . $start_date . '")');
		}

		if (!is_null($end_date)) {
			// $builder->where('date_created <=' . $end_date);
			$builder->where('DATE(date_created) <= DATE("' . $end_date . '")');
		}

		// $count = $builder->countAllResults();
		// echo 'Rows Found: '.$count."\n";
		// exit;

		// $sql = $builder->getCompiledSelect();
		// echo $sql."\n";
		// exit;

		$entries = $builder->get()->getResult();
		// print_r($entries); exit;


		foreach ($entries as $entry) {
			// Check if entry exists
			$query = array('response_id' => $entry->entry_form_id);
			$count = $collection->count($query);
			if ($count == 0) {
				$entry->json_response = str_replace('qn_', 'qn', $entry->json_response);
				$entry->json_followup = str_replace('qn_', 'qn', $entry->json_followup);

				$data = [];
				$baseline_entry = json_decode($entry->json_response);
				$baseline_entry->creator_id = $entry->creator_id;
				$baseline_entry->entity_type = 'baseline';
				$baseline_entry->created_at = $entry->date_created;
				$data[] = $baseline_entry;

				if (!is_null($entry->json_followup) && $entry->json_followup != '[]') {
					if ($followup_entry = json_decode($entry->json_followup)) {
						foreach ($followup_entry as $followup) {
							if (count((array) $followup)) {
								if (isset($followup->qn3))
									$followup->created_at = date('Y-m-d H:i:s', strtotime($followup->qn3));
								if (isset($followup->qn115))
									$followup->created_at = date('Y-m-d H:i:s', strtotime($followup->qn115));
								if (isset($followup->qn133))
									$followup->created_at = date('Y-m-d H:i:s', strtotime($followup->qn133));
								if (isset($followup->qn228))
									$followup->created_at = date('Y-m-d H:i:s', strtotime($followup->qn228));
								if (isset($followup->qn229))
									$followup->created_at = date('Y-m-d H:i:s', strtotime($followup->qn229));
								if (isset($followup->qn296))
									$followup->created_at = date('Y-m-d H:i:s', strtotime($followup->qn296));
								if (isset($followup->qn297))
									$followup->created_at = date('Y-m-d H:i:s', strtotime($followup->qn297));
								if (isset($followup->qn340))
									$followup->created_at = date('Y-m-d H:i:s', strtotime($followup->qn340));
								if (isset($followup->qn351))
									$followup->created_at = date('Y-m-d H:i:s', strtotime($followup->qn351));
								if (isset($followup->qn352))
									$followup->created_at = date('Y-m-d H:i:s', strtotime($followup->qn352));
								if (isset($followup->qn405))
									$followup->created_at = date('Y-m-d H:i:s', strtotime($followup->qn405));
								if (isset($followup->qn406))
									$followup->created_at = date('Y-m-d H:i:s', strtotime($followup->qn406));
								if (isset($followup->qn425))
									$followup->created_at = date('Y-m-d H:i:s', strtotime($followup->qn425));
								if (isset($followup->qn426))
									$followup->created_at = date('Y-m-d H:i:s', strtotime($followup->qn426));
								if (isset($followup->qn455))
									$followup->created_at = date('Y-m-d H:i:s', strtotime($followup->qn455));
								if (isset($followup->qn497))
									$followup->created_at = date('Y-m-d H:i:s', strtotime($followup->qn497));
								if (gettype($followup) == 'object')
									$followup->entity_type = 'followup';
								$data[] = $followup;
							}
						}
					}
				}

				$entry_data = [];
				// $entry_data['response_id'] = $entry->response_id;
				// $entry_data['entry_form_id'] = $entry->entry_form_id;
				$entry_data['response_id'] = $entry->entry_form_id;
				$entry_data['form_id'] = $entry->form_id;
				$entry_data['responses'] = array_values(array_unique($data, SORT_REGULAR));
				$entry_data['created_at'] = $entry->date_created;
				$entry_data['updated_at'] = $entry->date_modified;
				$entry_data['active'] = $entry->active;

				$final_list[] = $entry_data;
				$insertOneResult = $collection->insertOne($entry_data);
				$data[] = $entry->entry_form_id . " on: " . $entry_data['created_at'] . " has been inserted\n";
			} else {
				$data[] = $entry->entry_form_id . ' already exists';
			}
		}

		// $data = $collection->find()->toArray();
		return $this->respond($data);
	}




}