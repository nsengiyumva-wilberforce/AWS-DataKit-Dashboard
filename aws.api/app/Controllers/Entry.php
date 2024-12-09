<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\FormModel;
use App\Models\QuestionModel;
use MongoDB\Client as MongoDB;
use Exception;
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
				$photo_mobile_path = explode('/', $latest_followup['photo'] ?? null);
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

			if (property_exists($entry->responses[0], 'qn111')) {
				$entry['title'] = $entry->responses[0]->qn111;
			}

			if (property_exists($entry->responses[0], 'qn334')) {
				$entry['title'] = $entry->responses[0]->qn334;
			}
		}


		$photo_mobile_path = explode('/', $baseline['photo'] ?? null);
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

		//if (isset($params['creator_id'])) {
		//	$query['responses.creator_id'] = $params['creator_id'];
		//}

		if (isset($params['project'])) {
			$query['responses.qn148'] = $params['project'];
		}

		if (isset($params['set_followup_interval'])) {
			// echo date('Y-m-d H:i:s', strtotime('-7 days'));
			$last_date = date('Y-m-d H:i:s', strtotime('-' . $params['set_followup_interval'] . ' days'));
			$query['updated_at'] = array('$lte' => $last_date);
		}

		$date = '2023-11-01 00:00:00';
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
		ini_set('memory_limit', '-1');

		$utility = new Utility();
		$params = $this->request->getGet();

		$client = new MongoDB();
		$collection = $client->aws->entries;

		$user_map = $utility->mobile_user_mapper();

		$query = [
			'form_id' => $params['form_id'],
		];

		// Add region filter
		if (isset($params['region_id']) && $params['region_id'] != 0) {
			$district_list = $utility->region_district_array($params['region_id']);
			$query['responses.qn4'] = ['$in' => $district_list];
		}

		// Add date filter
		if (isset($params['dates'])) {
			$start_date_received = explode('-', $params['dates'])[0];
			$end_date_received = explode('-', $params['dates'])[1];

			$date1 = explode('/', $start_date_received);
			$start_date = trim($date1[2]).'-'.trim($date1[0]).'-'.trim($date1[1]);

			$date2 = explode('/', $end_date_received);
			$end_date = trim($date2[2]).'-'.trim($date2[0]).'-'.trim($date2[1]);

		
			$query['responses.created_at'] = [
				'$gte' => $start_date,
				'$lte' => $end_date
			];
		} 
		// Pagination parameters
		$start = isset($params['start']) ? (int) $params['start'] : 0; // Using start instead of page
		$perPage = isset($params['length']) ? (int) $params['length'] : 10; // Using length for perPage
		$skip = $start; // Skip is the start offset
		$draw = isset($params['draw']) ? (int) $params['draw'] : 1;

		// Calculate total records
		$totalQuery = $collection->countDocuments(['form_id' => $params['form_id']]);
		$filteredQuery = $collection->countDocuments($query);

		// Build aggregation pipeline for data
		$pipeline = [
			// Match stage for filtering
			['$match' => $query],

			// Project stage for shaping the documents
			[
				'$project' => [
					'_id' => 0,
					'response_id' => 1,
					'responses' => 1,
					'created_at' => 1,
					'active' => 1,
					'updated_at' => 1
				],
			],

			// Sort stage to order by updated_at in descending order
			[
				'$sort' => [
					'updated_at' => -1, // Sort by updated_at field in descending order (latest first)
				],
			],

			// Pagination stage
			['$skip' => $skip],
			['$limit' => $perPage],
		];

		// Fetch data
		$data = $collection->aggregate($pipeline)->toArray();

		// Process data
		$form_titles = $utility->form_titles($params['form_id']);
		$dataGenetration = [];  // Initialize the array

		$new_data = array_map(function ($entry) use ($form_titles, $user_map, &$dataGenetration) {
			$number_of_responses = count($entry['responses']);
			$title_str = '';
			foreach ($form_titles['title'] as $item) {
				$title_str .= $entry['responses'][0]['qn' . $item] ?? 'Unknown Title';
			}
			$entry['title'] = $title_str;

			$sub_title_str = '';
			foreach ($form_titles['sub_title'] as $item) {
				$sub_title_str .= $entry['responses'][0]['qn' . $item] ?? 'Unknown Sub Title';
			}
			$entry['sub_title'] = $sub_title_str;

			$entry['district'] = $entry['responses'][0]['qn4'] ?? null;
			$entry['sub_county'] = $entry['responses'][0]['qn7'] ?? null;
			$entry['parish'] = $entry['responses'][0]['qn8'] ?? null;
			$entry['village'] = $entry['responses'][0]['qn9'] ?? null;

			$entry['number_of_responses'] = $number_of_responses;
			//get the last creator
			if ($number_of_responses > 1) {
				$last_follower = $entry['responses'][$number_of_responses - 1][0]['creator_id'] ?? $entry['responses'][$number_of_responses - 1]['creator_id'];
				$entry['last_follower'] = $user_map[$last_follower];
			}
			$entry['creator_id'] = $user_map[$entry['responses'][0]['creator_id']] ?? 'Unknown';

			// Prepare the data for the table row
			$item = [
				'title' => $entry['title'],
				'sub_title' => $entry['sub_title'],
				'creator_id' => $entry['creator_id'],
				'location' => $entry['district'] . ', ' . $entry['sub_county'] . ', ' . $entry['parish'] . ', ' . $entry['village'],
				'last_follower' => $entry['last_follower']??'',
				'updated_at' => date('M j, Y', strtotime($entry['updated_at'] ?? $entry['created_at'])),
				'response_id' => $entry['response_id'],
			];

			// Append item to the dataGenetration array
			$dataGenetration[] = $item;

			return $entry;
		}, $data);

		// Response data
		$response = [
			'status' => 200,
			'data' => $dataGenetration,
			'current_page' => $start / $perPage + 1, // Calculate current page
			'per_page' => $perPage,
			'draw' => $draw,
			'recordsTotal' => $filteredQuery, // Total records matching the form_id
			'recordsFiltered' => $filteredQuery, // Total records after applying filters
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

		$startdate = $params['year'] . '-01-01 00:00:00';
		$enddate = $params['year'] . '-12-30 23:59:59';

		$data = $collection->aggregate(
			[
				[
					'$match' => [
						'form_id' => $params['form_id'],
						'responses.entity_type' => 'baseline',
						'$and' => [
							['responses.created_at' => ['$gt' => $startdate]],
							['responses.created_at' => ['$lt' => $enddate]]
						]
					]
				],
				['$unwind' => ['path' => '$responses']],
				['$unwind' => ['path' => '$responses']],
				['$project' => ['response_id' => '$response_id', 'responses' => '$responses', 'coordinates_array' => ['$split' => ['$responses.coordinates', ',']]]],
				['$project' => ['_id' => 0, 'response_id' => '$response_id', 'project' => '$responses.qn148', 'title' => '$responses.qn152', 'sub_title' => '$responses.qn9', 'coordinates' => ['lat' => ['$toDouble' => ['$arrayElemAt' => ['$coordinates_array', 0]]], 'lon' => ['$toDouble' => ['$arrayElemAt' => ['$coordinates_array', 1]]]]]],
			]
		)->toArray();

		$response = [
			'status' => 200,
			'data' => $data
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
		try {
			ini_set('memory_limit', '512M');
			// ini_set('memory_limit','1024M');
			$utility = new Utility();
			$params = $this->request->getGet();

			$client = new MongoDB();
			$collection = $client->aws->entries;

			$aggregation = [];

			$aggregation[] = ['$match' => ['form_id' => $params['form_id']]];
			$aggregation[] = ['$unwind' => '$responses'];
			$aggregation[] = ['$unwind' => '$responses'];


			if ($params['region_id'] != "all") {
				$orRegionArray = [];
				$district_list = $utility->region_district_array($params['region_id']);
				foreach ($district_list as $district) {
					//push $district to $orRegionArray array with key responses.qn4
					array_push($orRegionArray, ['responses.qn4' => $district]);
				}

				$aggregation[] = ['$match' => ['responses.entity_type' => $params['entry_data'], '$or' => $orRegionArray]];
			} else {
				//get records per region

			}

			if ($params['project'] != "all") {
				$projects = [['responses.qn148' => $params['project']]];
				$aggregation[] = [
					'$match' => [
						'$or' => $projects,
						'$and' => [['responses.created_at' => ['$gt' => $params['startdate']]], ['responses.created_at' => ['$lt' => $params['enddate']]]]
					]
				];
			} else {
				$aggregation[] = [
					'$match' => [
						'$and' => [['responses.created_at' => ['$gt' => $params['startdate']]], ['responses.created_at' => ['$lt' => $params['enddate']]]]
					]
				];
			}

			$aggregation[] = ['$group' => ['_id' => ['response_id' => '$response_id', 'created_at' => '$responses.created_at'], 'responses' => ['$push' => ['response_id' => '$response_id', 'created_at' => '$created_at', 'responses' => '$responses', 'active' => '$active', 'district' => '$district']]]];
			$aggregation[] = ['$replaceWith' => ['document' => ['$arrayElemAt' => ['$responses', 0]]]];
			$aggregation[] = ['$project' => ['_id' => 0, 'response_id' => '$document.response_id', 'form_id' => '$document.form_id', 'responses' => '$document.responses', 'created_at' => '$document.created_at', 'updated_at' => '$response.updated_at']];

			//add allowDiskUse to true to allow large data processing
			$entry_list = $collection->aggregate($aggregation, ["allowDiskUse" => true]);

			$data['headers'] = $utility->question_mapper($params['form_id']);
			//check if region_id is not all
			if ($params['region_id'] != "all") {
				$data['region'] = $this->get_region_name($params['region_id']);
			} else {
				$data['region'] = "All Regions";
			}
			$data['entries'] = $entry_list->toArray();

			$response = [
				'status' => 200,
				'data' => $data
			];

			return $this->respond($response);
		} catch (Exception $e) {
			$response = [
				'status' => 500,
				'data' => $e->getMessage()
			];
			return $this->fail($response);
		}
	}
	public function get_region_name($region_id)
	{
		$region = $this->db->table('region')->where('region_id', $region_id)->get()->getRow();
		return $region->name;
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

		$aggregation = [];

		$aggregation[] = ['$match' => ['form_id' => $params['form_id']]];
		$aggregation[] = ['$unwind' => '$responses'];
		$aggregation[] = ['$unwind' => '$responses'];

		if ($params['project'] != "all") {
			$projects = [['responses.qn148' => $params['project']]];

			$aggregation[] = [
				'$match' => [
					'responses.entity_type' => $params['data_type'],
					'$or' => $projects,
					'$and' => [
						['responses.created_at' => ['$gt' => $params['startdate']]],
						['responses.created_at' => ['$lt' => $params['enddate']]]
					]
				]
			];

		} else {
			$aggregation[] = [
				'$match' => [
					'responses.entity_type' => $params['data_type'],
					'$and' => [
						['responses.created_at' => ['$gt' => $params['startdate']]],
						['responses.created_at' => ['$lt' => $params['enddate']]]
					]
				]
			];
		}


		$aggregation[] = ['$group' => ['_id' => ['response_id' => '$response_id', 'created_at' => '$responses.created_at'], 'responses' => ['$push' => ['response_id' => '$response_id', 'created_at' => '$created_at', 'responses' => '$responses', 'active' => '$active', 'district' => '$district']]]];
		$aggregation[] = ['$replaceWith' => ['document' => ['$arrayElemAt' => ['$responses', 0]]]];

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
		// $aggregation[] = ['$addFields' => ['document.responses.qn155' => 'Male', 'expression' => ['$cond' => ['if' => ['$eq' => ['$document.responses.qn155', null]], 'then' => 'Male', 'else' =>'$document.responses.qn155']]]];
		$aggregation[] = ['$project' => ['response_id' => '$document.response_id', 'form_id' => '$document.form_id', 'title' => '$document.title', 'sub_title' => '$document.sub_title', 'district' => '$document.' . $group_stage_query, 'responses' => ['$objectToArray' => ['qn154' => ['$toInt' => '$document.responses.qn154'], 'qn155' => '$document.responses.qn155', 'qn412' => '$document.responses.qn412', 'qn157' => ['$toInt' => '$document.responses.qn157'], 'qn159' => ['$toInt' => '$document.responses.qn159'], 'qn219' => '$document.responses.qn219', 'qn162' => '$document.responses.qn162', 'qn167' => '$document.responses.qn167', 'qn168' => '$document.responses.qn168', 'qn174' => '$document.responses.qn174', 'qn171' => '$document.responses.qn171', 'qn173' => '$document.responses.qn173', 'qn221' => '$document.responses.qn221', 'qn179' => '$document.responses.qn179', 'qn181' => '$document.responses.qn181', 'qn223' => '$document.responses.qn223', 'qn183' => '$document.responses.qn183', 'qn184' => '$document.responses.qn184', 'qn189' => '$document.responses.qn189', 'qn191' => '$document.responses.qn191', 'qn193' => '$document.responses.qn193', 'qn194' => '$document.responses.qn194', 'qn217' => '$document.responses.qn217', 'qn225' => '$document.responses.qn225', 'qn197' => '$document.responses.qn197', 'qn206' => '$document.responses.qn206', 'qn414' => '$document.responses.qn414', 'qn424' => '$document.responses.qn424', 'qn416' => '$document.responses.qn416', 'qn418' => ['$toInt' => '$document.responses.qn418'], 'qn420' => '$document.responses.qn420', 'qn421' => '$document.responses.qn421', 'qn198' => ['$toInt' => '$document.responses.qn198'], 'qn201' => ['$toInt' => '$document.responses.qn201']]]]];
		$aggregation[] = ['$match' => ['$or' => $orArray]];
		$aggregation[] = ['$unwind' => ['path' => '$responses']];
		$aggregation[] = ['$unwind' => ['path' => '$responses.v']];
		$aggregation[] = ['$group' => ['_id' => ['district' => '$district', 'k' => '$responses.k', 'responses' => '$responses.v'], 'sum' => ['$sum' => ['$cond' => ['if' => ['$isNumber' => '$responses.v'], 'then' => '$responses.v', 'else' => 0]]], 'count' => ['$sum' => 1]]];
		$aggregation[] = ['$group' => ['_id' => ['district' => '$_id.district', 'question' => '$_id.k'], 'entries' => ['$push' => ['k' => '$_id.responses', 'v' => '$count']], 'Total' => ['$sum' => '$sum']]];
		$aggregation[] = ['$addFields' => ['entries' => ['$cond' => [['$gt' => ['$Total', 0]], ['Total' => '$Total'], '$entries']]]];
		$aggregation[] = ['$unwind' => ['path' => '$entries']];
		$aggregation[] = ['$match' => ['$expr' => ['$or' => [['$eq' => [['$type' => '$entries.k'], 'string']], ['$and' => [['$eq' => [['$type' => '$entries.Total'], 'int']], ['$ne' => ['$entries.Total', 0]]]]]]]];
		$aggregation[] = ['$group' => ['_id' => ['district' => '$_id.district', 'question' => '$_id.question'], 'entries' => ['$push' => ['k' => '$entries.k', 'v' => '$entries.v']], 'Total' => ['$sum' => '$Total']]];
		$aggregation[] = ['$addFields' => ['entries' => ['$cond' => [['$gt' => ['$Total', 0]], ['Total' => '$Total'], '$entries']]]];
		$aggregation[] = ['$addFields' => ['entries' => ['$cond' => ['if' => ['$isArray' => '$entries'], 'then' => ['$arrayToObject' => '$entries'], 'else' => '$entries']]]];
		$aggregation[] = ['$group' => ['_id' => '$_id.district', 'entries' => ['$count' => (object) []], 'aggregate' => ['$push' => ['k' => '$_id.question', 'v' => '$entries']]]];
		$aggregation[] = ['$project' => ['_id' => 0, 'name' => '$_id', 'entries' => '$entries', 'aggregate' => ['$arrayToObject' => '$aggregate']]];
		//handle case for all projects in a
		$header = $utility->form_subheader_mapper($params['form_id']);
		$answer_counter = $header['answer_counter'];
		$entry_list = $collection->aggregate($aggregation)->toArray();

		foreach ($entry_list as $key => $entry) {
			$new_object = []; // Create an empty array to store the new objects
			$new_object['name'] = $entry['name'];
			if (isset($entry->aggregate->qn155->Male)) {
				$males = $entry->aggregate->qn155->Male;
			} else {
				$males = 0;
			}
			if (isset($entry->aggregate->qn155->Female)) {
				$females = $entry->aggregate->qn155->Female;
			} else {
				$females = 0;
			}
			$new_object['entries'] = $males + $females;
			$new_object['aggregate'] = $answer_counter;
			$new_array[] = $new_object; // Push the new object to the $new_array

			foreach ($answer_counter as $key_1 => $value) {
				foreach ($entry->aggregate as $key_2 => $value_2) {
					if ($key_1 == $key_2) {
						foreach ($value as $key_3 => $value_3) {
							foreach ($value_2 as $key_4 => $value_4) {
								if ($key_3 == $key_4) {
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
			$params['responses'][0]->updated_at = date('Y-m-d H:i:s');

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

		// Save the binary data to a file
		$file_path = WRITEPATH . 'uploads/' . $params['filename'];
		file_put_contents($file_path, $decoded);


		// Check if 'photo_file' field exists in the responses array at the specified index
		if (!isset($entry['responses'][$index][0]['photo_file'])) {
			// If not, update the document structure to include 'photo_file' in the responses array
			$entry->responses[$index][0]->photo_file = $params['filename'];
		}

		// Update the document
		$updateResult = $collection->updateOne(
			['response_id' => $params['response_id']],
			['$set' => ['updated_at' => date('Y-m-d H:i:s'), 'responses' => $entry->responses]]
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

	public function create_last_entry_photo_test()
	{
		$params = $this->request->getPost();

		$client = new MongoDB();
		$collection = $client->aws->entries;
		$entry = $collection->findOne(['response_id' => $params['response_id']]);
		$responses_count = count($entry->responses);
		$index = $responses_count - 1;

		// convert file form base64 and upload it
		$base64_string = $params['photo_base64'];
		// $base64_string = trim($base64_string);

		$base64_string = str_replace('data:image/jpeg;base64,', '', $base64_string);
		$base64_string = str_replace('[removed]', '', $base64_string);
		$base64_string = str_replace(' ', '+', $base64_string);

		$file_path = WRITEPATH . 'uploads/' . $params['filename'];
		$decoded = base64_decode($base64_string);

		file_put_contents($file_path, $decoded);

		// Save the binary data to a file
		$file_path = WRITEPATH . 'uploads/' . $params['filename'];
		file_put_contents($file_path, $decoded);


		// Check if 'photo_file' field exists in the responses array at the specified index
		if (!isset($entry['responses'][$index][0]['photo_file'])) {
			// If not, update the document structure to include 'photo_file' in the responses array
			$entry->responses[$index][0]->photo_file = $params['filename'];
		}

		// Update the document
		$updateResult = $collection->updateOne(
			['response_id' => $params['response_id']],
			['$set' => ['updated_at' => date('Y-m-d H:i:s'), 'responses' => $entry->responses]]
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


	public function update_rejected_entry()
	{
		$params = $this->request->getPost();

		$client = new MongoDB();
		$collection = $client->aws->entries;

		//get the response at the specified index and set rejection_status to "rejected"
		$updateResult = $collection->updateOne(
			['response_id' => $params['response_id']],
			['$set' => ['updated_at' => date('Y-m-d H:i:s'), 'responses.' . $params['index'] => json_decode($params['response'])]]
		);

		if ($updateResult->getModifiedCount() > 0) {
			$data = $collection->findOne(['response_id' => $params['response_id']]);
			$response = [
				'status' => 201,
				'error' => null,
				'messages' => [
					'success' => 'Entry rejected successfully'
				],
				'data' => $data
			];
			return $this->respond($response);
		} else {
			return $this->failNotFound('No Data Found with id');
		}
	}

	public function rejected_photo_update()
	{
		$params = $this->request->getPost();

		$client = new MongoDB();
		$collection = $client->aws->entries;
		$entry = $collection->findOne(['response_id' => $params['response_id']]);
		//just upload the photo and replace the existing photo
		// convert file form base64 and upload it
		$base64_string = $params['photo_base64'];
		$base64_string = trim($base64_string);

		$base64_string = str_replace('data:image/jpeg;base64,', '', $base64_string);
		$base64_string = str_replace('[removed]', '', $base64_string);
		$base64_string = str_replace(' ', '+', $base64_string);

		$imageName = explode("/", $entry->responses[$params['index']]->photo);
		$file_path = WRITEPATH . 'uploads/' . end($imageName);

		if (file_exists($file_path)) {
			unlink($file_path);
		}

		//upload the new photo with the name of the old photo
		// $file_path = WRITEPATH . 'uploads/' . $entry->responses[$params['index']]->photo_file;

		$decoded = base64_decode($base64_string);
		$file = file_put_contents($file_path, $decoded);

		// check if the photo was uploaded successfully and return a success message
		if ($file) {
			$response = [
				'status' => 201,
				'error' => null,
				'messages' => [
					'success' => 'Photo Saved'
				],
				'data' => $entry
			];
			return $this->respond($response);
		} else {
			return $this->failNotFound('No Data Found with id ' . $params['entry_form_id']);
		}

	}


	public function reject_entry()
	{
		$params = $this->request->getPost();

		$client = new MongoDB();
		$collection = $client->aws->entries;

		//get the response at the specified index and set rejection_status to "rejected"
		$updateResult = $collection->updateOne(
			['response_id' => $params['response_id']],
			['$set' => ['updated_at' => date('Y-m-d H:i:s'), 'responses.' . $params['index'] . '.rejection_status' => 'rejected']]
		);

		if ($updateResult->getModifiedCount() > 0) {
			// $data = $collection->findOne(['entry_form_id' => $params['entry_form_id']]);
			$data = $collection->findOne(['response_id' => $params['response_id']]);
			$response = [
				'status' => 201,
				'error' => null,
				'messages' => [
					'success' => 'Entry rejected successfully'
				],
				'data' => $data
			];
			return $this->respond($response);
		} else {
			return $this->failNotFound('No Data Found with id');
		}
	}

	public function rejected_entries()
	{
		$utility = new Utility();

		$params = $this->request->getGet();

		$user_id = $params['user_id'];

		$client = new MongoDB();
		$collection = $client->aws->entries;

		$aggregation = [];

		$aggregation[] = [
			'$match' => [
				'$or' => [
					['responses.creator_id' => $user_id],
					['responses.creator_id' => (int) $user_id],
				],
				'responses.rejection_status' => 'rejected'
			]
		];

		$aggregation[] = ['$project' => ['_id' => 0, 'response_id' => '$response_id', 'form_id' => '$form_id', 'title' => ['$arrayElemAt' => ['$responses.qn152', 0]], 'sub_title' => ['$arrayElemAt' => ['$responses.qn9', 0]], 'responses' => ['$arrayElemAt' => ['$responses', 0]]]];

		$rejected_entries = $collection->aggregate($aggregation)->toArray();
		$newData = [];
		foreach ($rejected_entries as $entry) {
			$form_titles = $utility->form_titles($entry['form_id']);
			$title_str = '';
			foreach ($form_titles['title'] as $item) {

				$title_str .= $entry['responses']['qn' . $item];

			}
			$entry['title'] = $title_str != '' ? $title_str : 'Unknown Title';

			$sub_title_str = '';
			foreach ($form_titles['sub_title'] as $item) {

				$sub_title_str .= $entry['responses']['qn' . $item];

			}
			$entry['sub_title'] = $sub_title_str != '' ? $sub_title_str : 'Unknown Sub Title';


			$newData[] = $entry;
		}

		$response = [
			'status' => 200,
			'error' => null,
			'messages' => [
				'success' => 'Rejected entries retrieved successfully'
			],
			'data' => $newData
		];

		return $this->respond($response);
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

	public function group_by_region()
	{
		ini_set('memory_limit', '512M');
		// ini_set('memory_limit','1024M');
		$utility = new Utility();
		$params = $this->request->getGet();

		$client = new MongoDB();
		$collection = $client->aws->entries;
		if (isset($params['startdate']) && isset($params['enddate'])) {
			$startdate = $params['startdate'];
			$enddate = $params['enddate'];
		} else {
			$startdate = '2022-11-01T00:00:00.000Z';
			$enddate = '2023-06-30T00:00:00.000Z';
		}

		$aggregation = [];

		$aggregation[] = ['$match' => ['form_id' => $params['form_id']]];
		$aggregation[] = ['$unwind' => '$responses'];
		$aggregation[] = ['$unwind' => '$responses'];

		$central = $this->db->table('district_view')->where('region_id', 1)->get()->getResult();
		$centralArray = array_map(function ($value) {
			return $value;
		}, array_column($central, 'name'));

		$southWestern = $this->db->table('district_view')->where('region_id', 3)->get()->getResult();
		$southWesternArray = array_map(function ($value) {
			return $value;
		}, array_column($southWestern, 'name'));

		$eastern = $this->db->table('district_view')->where('region_id', 2)->get()->getResult();
		$easternArray = array_map(function ($value) {
			return $value;
		}, array_column($eastern, 'name'));

		$westNile = $this->db->table('district_view')->where('region_id', 4)->get()->getResult();
		$westNileArray = array_map(function ($value) {
			return $value;
		}, array_column($westNile, 'name'));


		$entry_list = $collection->aggregate(
			[
				['$match' => ['form_id' => $params['form_id']]],
				['$unwind' => ['path' => '$responses']],
				['$unwind' => ['path' => '$responses']],
				[
					'$match' => [
						'responses.entity_type' => $params['data_type'],
						'$and' => [
							['responses.created_at' => ['$gt' => $startdate]],
							['responses.created_at' => ['$lt' => $enddate]]
						]
					]
				],
				['$group' => ['_id' => ['response_id' => '$response_id', 'created_at' => '$responses.created_at'], 'responses' => ['$push' => ['response_id' => '$response_id', 'created_at' => '$created_at', 'responses' => '$responses', 'active' => '$active', 'district' => '$district']]]],
				['$replaceWith' => ['document' => ['$arrayElemAt' => ['$responses', 0]]]],
				[
					'$addFields' => [
						'document.region' => [
							'$cond' => [
								['$in' => ['$document.responses.qn4', $centralArray]],
								'Central',
								[
									'$cond' => [
										['$in' => ['$document.responses.qn4', $easternArray]],
										'Eastern',
										[
											'$cond' => [
												['$in' => ['$document.responses.qn4', $southWesternArray]],
												'South Western',
												[
													'$cond' => [
														['$in' => ['$document.responses.qn4', $westNileArray]],
														'West Nile',
														'Other'
													]
												]
											]
										]
									]
								]
							]
						]
					]
				],
				['$group' => ['_id' => '$document.region', 'count' => ['$count' => (object) []]]],
				['$project' => ['_id' => 0, 'region' => '$_id', 'count' => '$count']],
				['$sort' => ['region' => 1]]
			]
		);

		$data['entries'] = $entry_list->toArray();

		$response = [
			'status' => 200,
			'data' => $data
		];

		return $this->respond($response);
	}

	public function group_by_latrine_coverage()
	{
		ini_set('memory_limit', '512M');
		// ini_set('memory_limit','1024M');
		$params = $this->request->getGet();

		$client = new MongoDB();
		$collection = $client->aws->entries;
		if (isset($params['startdate']) && isset($params['enddate'])) {
			$startdate = $params['startdate'];
			$enddate = $params['enddate'];
		} else {
			$startdate = '2022-11-01T00:00:00.000Z';
			$enddate = '2023-06-30T00:00:00.000Z';
		}

		$latrine_coverage = $collection->aggregate(
			[
				['$match' => ['form_id' => '11']],
				['$unwind' => ['path' => '$responses']],
				['$unwind' => ['path' => '$responses']],
				[
					'$match' => [
						'responses.entity_type' => $params['data_type'],
						'$and' => [
							['responses.created_at' => ['$gt' => $startdate]],
							['responses.created_at' => ['$lt' => $enddate]]
						]
					]
				],
				['$group' => ['_id' => ['response_id' => '$response_id', 'created_at' => '$responses.created_at'], 'responses' => ['$push' => ['response_id' => '$response_id', 'created_at' => '$created_at', 'responses' => '$responses', 'active' => '$active', 'district' => '$district']]]],
				['$replaceWith' => ['document' => ['$arrayElemAt' => ['$responses', 0]]]],
				['$project' => ['response_id' => '$document.response_id', 'form_id' => '$document.form_id', 'title' => '$document.title', 'sub_title' => '$document.sub_title', 'district' => '$document.responses.qn4', 'responses' => ['qn219' => '$document.responses.qn219']]],
				['$group' => ['_id' => '$responses.qn219', 'count' => ['$count' => (object) []]]],
				['$sort' => ['_id' => -1]]
			]
		)->toArray();

		$data['entries'] = $latrine_coverage;
		$response = [
			'status' => 200,
			'data' => $data
		];
		return $this->respond($response);
	}

	public function group_by_sanitation_category()
	{
		ini_set('memory_limit', '512M');
		// ini_set('memory_limit','1024M');
		$params = $this->request->getGet();

		$client = new MongoDB();
		$collection = $client->aws->entries;
		if (isset($params['startdate']) && isset($params['enddate'])) {
			$startdate = $params['startdate'];
			$enddate = $params['enddate'];
		} else {
			$startdate = '2022-11-01T00:00:00.000Z';
			$enddate = '2023-06-30T00:00:00.000Z';
		}

		$latrine_coverage = $collection->aggregate(
			[
				['$match' => ['form_id' => '11']],
				['$unwind' => ['path' => '$responses']],
				['$unwind' => ['path' => '$responses']],
				[
					'$match' => [
						'responses.entity_type' => $params['data_type'],
						'$and' => [
							['responses.created_at' => ['$gt' => $startdate]],
							['responses.created_at' => ['$lt' => $enddate]]
						]
					]
				],
				['$group' => ['_id' => ['response_id' => '$response_id', 'created_at' => '$responses.created_at'], 'responses' => ['$push' => ['response_id' => '$response_id', 'created_at' => '$created_at', 'responses' => '$responses', 'active' => '$active', 'district' => '$district']]]],
				['$replaceWith' => ['document' => ['$arrayElemAt' => ['$responses', 0]]]],
				['$project' => ['response_id' => '$document.response_id', 'form_id' => '$document.form_id', 'title' => '$document.title', 'sub_title' => '$document.sub_title', 'district' => '$document.responses.qn4', 'responses' => ['qn162' => '$document.responses.qn162']]],
				['$group' => ['_id' => '$responses.qn162', 'count' => ['$count' => (object) []]]],
				['$sort' => ['_id' => -1]]
			]
		)->toArray();

		$data['entries'] = $latrine_coverage;
		$response = [
			'status' => 200,
			'data' => $data
		];
		return $this->respond($response);
	}

	public function group_by_duration_of_water_collection()
	{
		ini_set('memory_limit', '512M');
		// ini_set('memory_limit','1024M');
		$params = $this->request->getGet();

		$client = new MongoDB();
		$collection = $client->aws->entries;
		if (isset($params['startdate']) && isset($params['enddate'])) {
			$startdate = $params['startdate'];
			$enddate = $params['enddate'];
		} else {
			$startdate = '2022-11-01T00:00:00.000Z';
			$enddate = '2023-06-30T00:00:00.000Z';
		}

		$latrine_coverage = $collection->aggregate(
			[
				['$match' => ['form_id' => '11']],
				['$unwind' => ['path' => '$responses']],
				['$unwind' => ['path' => '$responses']],
				[
					'$match' => [
						'responses.entity_type' => $params['data_type'],
						'$and' => [
							['responses.created_at' => ['$gt' => $startdate]],
							['responses.created_at' => ['$lt' => $enddate]]
						]
					]
				],
				['$group' => ['_id' => ['response_id' => '$response_id', 'created_at' => '$responses.created_at'], 'responses' => ['$push' => ['response_id' => '$response_id', 'created_at' => '$created_at', 'responses' => '$responses', 'active' => '$active', 'district' => '$district']]]],
				['$replaceWith' => ['document' => ['$arrayElemAt' => ['$responses', 0]]]],
				['$project' => ['response_id' => '$document.response_id', 'form_id' => '$document.form_id', 'title' => '$document.title', 'sub_title' => '$document.sub_title', 'district' => '$document.responses.qn4', 'responses' => ['qn197' => '$document.responses.qn197']]],
				['$group' => ['_id' => '$responses.qn197', 'count' => ['$count' => (object) []]]],
				['$sort' => ['_id' => -1]]
			]
		)->toArray();

		$data['entries'] = $latrine_coverage;
		$response = [
			'status' => 200,
			'data' => $data
		];
		return $this->respond($response);
	}

	public function group_by_water_treatment()
	{
		ini_set('memory_limit', '512M');
		// ini_set('memory_limit','1024M');
		$params = $this->request->getGet();

		$client = new MongoDB();
		$collection = $client->aws->entries;

		if (isset($params['startdate']) && isset($params['enddate'])) {
			$startdate = $params['startdate'];
			$enddate = $params['enddate'];
		} else {
			$startdate = '2022-11-01T00:00:00.000Z';
			$enddate = '2023-06-30T00:00:00.000Z';
		}

		$latrine_coverage = $collection->aggregate(
			[
				['$match' => ['form_id' => '11']],
				['$unwind' => ['path' => '$responses']],
				['$unwind' => ['path' => '$responses']],
				[
					'$match' => [
						'responses.entity_type' => $params['data_type'],
						'$and' => [
							['responses.created_at' => ['$gt' => $startdate]],
							['responses.created_at' => ['$lt' => $enddate]]
						]
					]
				],
				['$group' => ['_id' => ['response_id' => '$response_id', 'created_at' => '$responses.created_at'], 'responses' => ['$push' => ['response_id' => '$response_id', 'created_at' => '$created_at', 'responses' => '$responses', 'active' => '$active', 'district' => '$district']]]],
				['$replaceWith' => ['document' => ['$arrayElemAt' => ['$responses', 0]]]],
				['$project' => ['response_id' => '$document.response_id', 'form_id' => '$document.form_id', 'title' => '$document.title', 'sub_title' => '$document.sub_title', 'district' => '$document.responses.qn4', 'responses' => ['qn189' => '$document.responses.qn189']]],
				['$group' => ['_id' => '$responses.qn189', 'count' => ['$count' => (object) []]]],
				['$sort' => ['_id' => -1]]
			]
		)->toArray();

		$data['entries'] = $latrine_coverage;
		$response = [
			'status' => 200,
			'data' => $data
		];
		return $this->respond($response);
	}

	public function group_by_family_savings()
	{
		ini_set('memory_limit', '512M');
		// ini_set('memory_limit','1024M');
		$params = $this->request->getGet();

		$client = new MongoDB();
		$collection = $client->aws->entries;

		if (isset($params['startdate']) && isset($params['enddate'])) {
			$startdate = $params['startdate'];
			$enddate = $params['enddate'];
		} else {
			$startdate = '2022-11-01T00:00:00.000Z';
			$enddate = '2023-06-30T00:00:00.000Z';
		}

		$latrine_coverage = $collection->aggregate(
			[
				['$match' => ['form_id' => '11']],
				['$unwind' => ['path' => '$responses']],
				['$unwind' => ['path' => '$responses']],
				[
					'$match' => [
						'responses.entity_type' => $params['data_type'],
						'$and' => [
							['responses.created_at' => ['$gt' => $startdate]],
							['responses.created_at' => ['$lt' => $enddate]]
						]
					]
				],
				['$group' => ['_id' => ['response_id' => '$response_id', 'created_at' => '$responses.created_at'], 'responses' => ['$push' => ['response_id' => '$response_id', 'created_at' => '$created_at', 'responses' => '$responses', 'active' => '$active', 'district' => '$district']]]],
				['$replaceWith' => ['document' => ['$arrayElemAt' => ['$responses', 0]]]],
				['$project' => ['response_id' => '$document.response_id', 'form_id' => '$document.form_id', 'title' => '$document.title', 'sub_title' => '$document.sub_title', 'district' => '$document.responses.qn4', 'responses' => ['qn420' => '$document.responses.qn420']]],
				['$group' => ['_id' => '$responses.qn420', 'count' => ['$count' => (object) []]]],
				['$sort' => ['_id' => -1]]
			]
		)->toArray();

		$data['entries'] = $latrine_coverage;
		$response = [
			'status' => 200,
			'data' => $data
		];
		return $this->respond($response);
	}

	public function group_by_region_and_districts()
	{
		// ini_set('memory_limit','1024M');
		$params = $this->request->getGet();

		$client = new MongoDB();
		$collection = $client->aws->entries;

		if (isset($params['startdate']) && isset($params['enddate'])) {
			$startdate = $params['startdate'];
			$enddate = $params['enddate'];
		} else {
			$startdate = '2022-11-01T00:00:00.000Z';
			$enddate = '2023-06-30T00:00:00.000Z';
		}

		$central = $this->db->table('district_view')->where('region_id', 1)->get()->getResult();
		$centralArray = array_map(function ($value) {
			return $value;
		}, array_column($central, 'name'));

		$southWestern = $this->db->table('district_view')->where('region_id', 3)->get()->getResult();
		$southWesternArray = array_map(function ($value) {
			return $value;
		}, array_column($southWestern, 'name'));

		$eastern = $this->db->table('district_view')->where('region_id', 2)->get()->getResult();
		$easternArray = array_map(function ($value) {
			return $value;
		}, array_column($eastern, 'name'));

		$westNile = $this->db->table('district_view')->where('region_id', 4)->get()->getResult();
		$westNileArray = array_map(function ($value) {
			return $value;
		}, array_column($westNile, 'name'));

		$aggregate = $collection->aggregate(
			[
				['$match' => ['form_id' => $params['form_id']]],
				['$unwind' => ['path' => '$responses']],
				['$unwind' => ['path' => '$responses']],
				[
					'$match' => [
						'$and' => [
							['responses.created_at' => ['$gt' => $startdate]],
							['responses.created_at' => ['$lt' => $enddate]]
						]
					]
				],
				['$group' => ['_id' => ['response_id' => '$response_id', 'created_at' => '$responses.created_at'], 'responses' => ['$push' => ['response_id' => '$response_id', 'created_at' => '$created_at', 'responses' => '$responses', 'active' => '$active', 'district' => '$district']]]],
				['$replaceWith' => ['document' => ['$arrayElemAt' => ['$responses', 0]]]],
				[
					'$addFields' => [
						'document.region' => [
							'$cond' => [
								['$in' => ['$document.responses.qn4', $centralArray]],
								'Central',
								[
									'$cond' => [
										['$in' => ['$document.responses.qn4', $easternArray]],
										'Eastern',
										[
											'$cond' => [
												['$in' => ['$document.responses.qn4', $southWesternArray]],
												'South Western',
												[
													'$cond' => [
														['$in' => ['$document.responses.qn4', $westNileArray]],
														'West Nile',
														'Other'
													]
												]
											]
										]
									]
								]
							]
						]
					]
				],
				['$group' => ['_id' => ['region' => '$document.region', 'district' => '$document.responses.qn4'], 'baseline' => ['$sum' => ['$cond' => [['$eq' => ['$document.responses.entity_type', 'baseline']], 1, 0]]], 'followup' => ['$sum' => ['$cond' => [['$eq' => ['$document.responses.entity_type', 'followup']], 1, 0]]]]],
				['$group' => ['_id' => '$_id.region', 'districts' => ['$push' => ['name' => '$_id.district', 'value' => '$baseline', 'followup' => '$followup']]]],
				['$project' => ['_id' => 0, 'name' => '$_id', 'data' => '$districts']]
			]
		)->toArray();

		$response = [
			'status' => 200,
			'data' => $aggregate
		];

		return $this->respond($response);
	}
}

