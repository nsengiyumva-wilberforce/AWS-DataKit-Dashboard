<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ajax extends CI_Controller {


	// private $api_base_url = 'http://127.0.0.1/aws-api/';
	// private $api_base_url = 'http://127.0.0.1/aws-api/index.php/app/';
	// private $api_base_url = 'http://116.203.142.9/aws-api-bak/index.php/app/';
		public function new_question($form_id)
	{
		//CHECK FOR SESSION
		// if (!$this->session->has_userdata('logged_in')) { redirect('login'); }

		$url = API_BASE_URLS.'get-answer-types?format=json';
		$result = $this->custom->run_curl_get($url);
		$obj_array = json_decode($result);
		$answer_types = $obj_array->data;
		$data['answer_types'] = $answer_types;

		$data['form_action'] = 'ajax/create-question/'.$form_id;
		$this->load->view('ajax-pages/question-form', $data);
	}


	public function edit_question()
	{
		                $url = API_BASE_URLS.'get-answer-types?format=json';
                $result = $this->custom->run_curl_get($url);
                $obj_array = json_decode($result);
                $answer_types = $obj_array->data;
                $data['answer_types'] = $answer_types;
		$this->load->view('ajax-pages/question-form', $data);
	
	}

	public function new_library_question(){
		 $url = API_BASE_URLS.'get-answer-types?format=json';
                $result = $this->custom->run_curl_get($url);
                $obj_array = json_decode($result);
                $answer_types = $obj_array->data;
                $data['answer_types'] = $answer_types;

                $data['form_action'] = 'ajax/create-library-question/';
                $this->load->view('ajax-pages/question-form', $data);
	}

	public function edit_library_question_form($question_id)
	{
		 $url = API_BASE_URLS.'get-answer-types?format=json';
                $result = $this->custom->run_curl_get($url);
                $obj_array = json_decode($result);
                $answer_types = $obj_array->data;
		$data['answer_types'] = $answer_types;

		 $url = API_BASE_URLS.'get-library-question?question_id='.$question_id;
                $result = $this->custom->run_curl_get($url);
                $obj_array = json_decode($result);
                $question = $obj_array->data;
		$data['question'] = $question;

		$url = API_BASE_URLS.'get-app-list?format=json';
		$result = json_decode($this->custom->run_curl_get($url));
		$data['app_list'] = $result->data;

		$data['form_action'] = 'ajax/update-library-question/';
		$this->load->view('ajax-pages/library-question-form-card', $data);
	}
	public function create_library_question(){
		                //CHECK FOR SESSION
                // if (!$this->session->has_userdata('logged_in')) { redirect('login'); }

                $params = $this->input->post(NULL, TRUE);

               // $params['form_id'] = $form_id;
                if ($params['answer_type_id'] == 7) {
                        $params['answer_values'] = array('db_table' => $params['answer_values'][0]);
		}

                $params['answer_values'] = isset($params['answer_values']) ? json_encode($params['answer_values']) : NULL ;
		
                $url = API_BASE_URLS.'create-library-question';
                $result = json_decode($this->custom->run_curl_post($url, $params));
                if ($result->status) {
                        $result->data->question_number = '';
                        $data = $result->data;
                        $this->load->view('ajax-pages/library-question-card-view', $data);
                } else {
                        echo $result->message;
                }
	}


	public function answer_values()
	{
		$this->load->view('ajax-pages/answer-values');
	}


	public function question_card()
	{
		$this->load->view('ajax-pages/question-card');
	}



	public function update_question($question_id)
	{
		//CHECK FOR SESSION
		// if (!$this->session->has_userdata('logged_in')) { redirect('login'); }

		$params = $this->input->post(NULL, TRUE);

		$params['question_id'] = $question_id;
		if ($params['answer_type_id'] == 7) {
			$params['answer_values'] = array('db_table' => $params['answer_values'][0]);
		}
		$params['answer_values'] = isset($params['answer_values']) ? json_encode($params['answer_values']) : NULL ;
		// print_r($params); die();

		$url = API_BASE_URLS.'update-question';
		$result = json_decode($this->custom->run_curl_post($url, $params));
		// print_r($result); die();

		if ($result->status) {
			$result->data->question_number = '';
			$data = $result->data;
			$this->load->view('ajax-pages/question-card', $data);
		} else {
			echo $result->message;
		}
	}


		public function create_question($form_id)
	{
		//CHECK FOR SESSION
		// if (!$this->session->has_userdata('logged_in')) { redirect('login'); }

		$params = $this->input->post(NULL, TRUE);

		//var_dump($params);	
		$params['form_id'] = $form_id;
		if ($params['answer_type_id'] == 7) {
			$params['answer_values'] = array('db_table' => $params['answer_values'][0]);
		}
		//$params['answer_values'] = isset($params['answer_values']) ? json_encode($params['answer_values']) : NULL ;

		if(isset($params['answer_values'])){
			$params['answer_values'] = json_encode($params['answer_values']);
		}
		$url = API_BASE_URL.'question/add';
		$result = json_decode($this->custom->run_curl_post($url, $params));
		if ($result->status) {
			$result->data->question_number = '';
			$data = $result->data;
			$this->load->view('ajax-pages/question-card', $data);
		} else {
			echo $result->message;
		}
		}

	  public function create_question_from_library($form_id)
        	{
                //CHECK FOR SESSION
                // if (!$this->session->has_userdata('logged_in')) { redirect('login'); }

		$params = $this->input->post(NULL, TRUE);

		$params['form_id'] = $form_id;
		$question_url = API_BASE_URL.'question_library?question_id='.$params['question_id'];
		$get_question = $this->custom->run_curl_get($question_url);
		$question_result = json_decode($get_question);
		$question = $question_result->data;
		
		$params['answer_type_id'] = $question->answer_type_id;
		$params['answer_values'] = json_encode($question->answer_values);
		$params['question'] = $question->question;
		$params['question_id'] = $question->question_id;

                $url = API_BASE_URL.'add-library-question';
		$result = json_decode($this->custom->run_curl_post($url, $params));
		if ($result->status) {
                        $result->data->question_number = '';
                        $data = $result->data;
                        $this->load->view('ajax-pages/question-card', $data);
                } else {
                        echo $result->message;
                }
        }


	public function create_form()
	{
		//CHECK FOR SESSION
		// if (!$this->session->has_userdata('logged_in')) { redirect('login'); }

		$params = $this->input->post(NULL, TRUE);

		$params['format'] = 'json';
		$params['title'] = $params['title'] ?? 'Untitled Form';
		$params['question_list'] = '[]';
		$params['title_fields'] = '[]';
		$params['is_geotagged'] = $params['is_geotagged'] ?? null;
		$params['is_photograph'] = $params['is_photograph'] ?? null;
		$params['is_followup'] = $params['is_followup'] ?? null;
		$params['followup_prefill'] = '[]';
		$params['is_publish'] = null;

		$url = API_BASE_URL.'form/add';
		$result = $this->custom->run_curl_post($url, $params);
		$form = json_decode($result);

		if ($form->status) {
			redirect('form-builder/'.$form->data->form_id);
		} else {
			echo $form->message;
		}
	}

	public function logic_question($form_id, $question_id)
	{
		//CHECK FOR SESSION
		if (!$this->session->has_userdata('logged_in')) {
			redirect('login');
		}

		//get the form details
		$url = API_BASE_URL . 'forms?form_id=' . $form_id;
		$result = $this->custom->run_curl_get($url);
		$obj_array = json_decode($result);
		$prefill_list = $obj_array->data->question_list;

		//get answer values
		$url = API_BASE_URL . 'questions?question_id=' . $question_id;
		$result = $this->custom->run_curl_get($url);
		$obj_array = json_decode($result);

		$data['form_id'] = $form_id;
		$data['question'] = $obj_array->data;
		$data['question_list'] = $prefill_list;
		$this->load->view('modals/ajax-logic-question', $data);
	}

	public function create_conditional_logic()
	{
		//CHECK FOR SESSION
		if (!$this->session->has_userdata('logged_in')) {
			redirect('login');
		}

		$params = $this->input->post(NULL, TRUE);

		$data['form_id'] = $params['form_id'];
		$data['question_id'] = $params['question_id'];
		$data['answer'] = $params['answer'];
		$data['action'] = $params['action'];
		if (isset($params['prefill_question_ids']) && $params['action'] == 'prefill') {
			//join prefill_question_ids and prefill_question_answers as prefill_question_ids value => prefill_question_answers value
			$prefill_question_ids = $params['prefill_question_ids'];
			$prefill_question_answers = $params['prefill_question_answers'];
			$prefill_list = [];

			foreach ($prefill_question_ids as $key => $value) {
				$prefill_list['qn'.$value] = $prefill_question_answers[$key];
			}

			$data['question_ids'] = json_encode($prefill_list);
		} else if (isset($params['question_ids']) && $params['action'] == 'hide') {
			$hidden_fields = $params['question_ids'];

			$hide = [];
			foreach ($hidden_fields as $key => $value) {
				$hide[] = 'qn'.$value;
			}
			$data['question_ids'] = json_encode($hide);
		}
		$url = API_BASE_URL . 'form/conditional-logic/add';
		$result = $this->custom->run_curl_post($url, $data);
		echo $result;

	}

	public function edit_question_form($question_id)
	{
		//CHECK FOR SESSION
		// if (!$this->session->has_userdata('logged_in')) { redirect('login'); }
		$url = API_BASE_URLS.'get-app-list?format=json';
		$result = json_decode($this->custom->run_curl_get($url));
		$data['app_list'] = $result->data;

		$url = API_BASE_URLS.'get-answer-types?format=json';
		$result = $this->custom->run_curl_get($url);
		$obj_array = json_decode($result);
		$answer_types = $obj_array->data;
		$data['answer_types'] = $answer_types;

		$url = API_BASE_URLS.'get-question?question_id='.$question_id.'&format=json';
		$result = $this->custom->run_curl_get($url);
		$obj_array = json_decode($result);
		$question = $obj_array->data;
		$data['question'] = $question;

		$data['form_action'] = 'ajax/update-question/'.$question_id;
		$this->load->view('ajax-pages/question-form', $data);
	}

	public function update_form_question_order()
	{
		$params = $this->input->post(NULL, TRUE);
		$url = API_BASE_URLS.'update-form-question-order';
		$result = $this->custom->run_curl_post($url, $params);
		echo $result;
	}

        public function delete_question()
        {
                //CHECK FOR SESSION
                // if (!$this->session->has_userdata('logged_in')) { redirect('login'); }

                $params = $this->input->post(NULL, TRUE);
                $url = API_BASE_URLS.'hard-delete-question';
                $result = $this->custom->run_curl_post($url, $params);
                $obj_array = json_decode($result);
                if ($obj_array->status) {
                        echo $result;
                } else {
                        echo $result->message;
                }
        }


	public function delete_library_question()
	{
		//CHECK FOR SESSION
		// if (!$this->session->has_userdata('logged_in')) { redirect('login'); }

		$params = $this->input->post(NULL, TRUE);
		$url = API_BASE_URLS.'hard-delete-question';
		$result = $this->custom->run_curl_post($url, $params);
		$obj_array = json_decode($result);
		if ($obj_array->status) {
			echo $result;
		} else {
			echo $result->message;
		}
	}










	public function form_list_regions()
	{
		$url = API_BASE_URL.'get-regions?format=json';
		$result = json_decode($this->custom->run_curl_get($url));
		$obj_array = json_decode($result);
		$regions = $obj_array->data;
	}

	public function form_list_districts($region_id)
	{
		$url = API_BASE_URLS.'get-districts?region_id='.$region_id.'&format=json';
//echo $url; 		
$result = json_decode($this->custom->run_curl_get($url));
//echo json_encode($result);

		$list = '<option value="">----</option>';
		foreach ($result->data as $district) {
			$list .= '<option value="'.$district->district_id.'">'.$district->name.'</option>';
		}
		echo $list;

	}

	public function form_list_sub_counties($district_id)
	{
		$url = API_BASE_URLS.'get-sub-counties?district_id='.$district_id.'format=json';
		$result = json_decode($this->custom->run_curl_get($url));

		$list = '<option value="">----</option>';
		foreach ($result->data as $sub_county) {
			$list .= '<option value="'.$sub_county->sub_county_id.'">'.$sub_county->name.'</option>';
		}
		echo $list;	
	}

	public function form_list_parishes($sub_county_id)
	{
		$url = API_BASE_URLS.'get-parishes?sub_county_id='.$sub_county_id.'format=json';
		$result = json_decode($this->custom->run_curl_get($url));

		$list = '<option value="">----</option>';
		foreach ($result->data as $parish) {
			$list .= '<option value="'.$parish->parish_id.'">'.$parish->name.'</option>';
		}
		echo $list;
	}

	// public function form_list_villages($region_id)
	// {
	// 	$url = API_BASE_URL.'get-village?village_id='.$village_id.'&format=json';
	// 	$result = $this->custom->run_curl_get($url);
	// 	$obj_array = json_decode($result);
	// 	$village = $obj_array->data;
	// }



	public function app_list()
	{
		$url = API_BASE_URLS.'get-app-list?format=json';
		$result = json_decode($this->custom->run_curl_get($url));
		$data['app_list'] = $result->data;
		$this->load->view('ajax-pages/answer-app-list', $data);
	}















	public function delete_project($project_id)
	{
		$params['project_id'] = $project_id;
		$url = API_BASE_URLS . 'hard-delete-project/' . $params['project_id'];
		$result = $this->custom->run_curl_post($url, $params);
		redirect('projects');
	}

	public function delete_organisation($organisation_id)
	{
		$params['organisation_id'] = $organisation_id;
		$url = API_BASE_URLS . 'hard-delete-organisation/' . $params['organisation_id'];
		$result = $this->custom->run_curl_post($url, $params);
		redirect('organisations');
	}

	public function delete_region($region_id)
	{
		$params['region_id'] = $region_id;
		$url = API_BASE_URLS . 'soft-delete-region/' . $params['region_id'];
		$result = $this->custom->run_curl_post($url, $params);
		redirect('regions');
	}

	public function delete_district($district_id)
	{
		$params['district_id'] = $district_id;
		$url = API_BASE_URLS . 'soft-delete-district/' . $params['district_id'];
		$result = $this->custom->run_curl_post($url, $params);
		redirect('districts');
	}

	public function delete_sub_county($sub_county_id)
	{
		$params['sub_county_id'] = $sub_county_id;
		$url = API_BASE_URLS . 'soft-delete-sub_county/' . $params['sub_county_id'];
		$result = $this->custom->run_curl_post($url, $params);
		redirect('sub-counties');
	}

	public function delete_parish($parish_id)
	{
		$params['parish_id'] = $parish_id;
		$url = API_BASE_URLS . 'soft-delete-parish/' . $params['parish_id'];
		$result = $this->custom->run_curl_post($url, $params);
		redirect('parishes');
	}

	public function delete_village($village_id)
	{
		$params['village_id'] = $village_id;
		$url = API_BASE_URLS . 'soft-delete-village/' . $params['village_id'];
		$result = $this->custom->run_curl_post($url, $params);
		redirect('villages');
	}
	public function delete_response($response_id)
	{
		$params['response_id'] = $response_id;
		$url = API_BASE_URL.'entry/delete';
		$result = $this->custom->run_curl_post($url, $params);
		return $result;
		// redirect('form-entries/'.$form_id);
	}

	public function delete_chart($chart_id)
	{
	        $params['chart_id'] = $chart_id;
                $url = API_BASE_URL.'chart/delete';
                $result = $this->custom->run_curl_post($url, $params);
                return $result;
	}



















	public function ajax_form_entries()
	{
		//CHECK FOR SESSION
		// if (!$this->session->has_userdata('logged_in')) { redirect(); }

		$params = $this->input->post(NULL, TRUE);
		$form_id = $params['form_id'];

		if (isset($params['region_id'])) {
			$query_param = $params['region_id'] != 0 ? '&region_id='.$params['region_id'] : '';
		} else {
			$query_param = $_SESSION['region_id'] != 0 ? '&region_id='.$_SESSION['region_id'] : '';
		}

		if(isset($params['year'])){
			$year=$params['year'];

			$url = API_BASE_URL.'entry/getRegionalEntries?year='.$year.'&form_id='.$form_id.$query_param.'&format=json';
		}else{

			$url = API_BASE_URL.'entry/getRegionalEntries?form_id='.$form_id.$query_param.'&format=json';
		}
		// $this->custom->print($params,true);
		// $this->custom->print($this->custom->run_curl_get($url),true);
		$result = json_decode($this->custom->run_curl_get($url));

		$data['entries'] = $result->data ?? [];
		$data['form_id'] = $form_id;

		// $this->custom->print($data,true);


		$table = $this->load->view('pages/ajax-row-form-entries', $data, TRUE);
		echo $table;
	}





	public function raw_data_report()
	{
		try{
		$params = $this->input->post(NULL, TRUE);
		$query_data['region_id'] = $params['region_id'];
		$query_data['form_id'] = $params['form_id'];
		$query_data['entry_data'] = $params['entry_data'];
		$query_data['project'] = $params['project']; 

		$dates = explode('-', $params['dates']);
		$query_data['startdate'] = $this->custom->date_maker(trim($dates[0]));
		$query_data['enddate'] = $this->custom->date_maker(trim($dates[1]));

		$url = API_BASE_URL.'report/entries?'.http_build_query($query_data);
		$result = json_decode($this->custom->run_curl_get($url));

		//check if status code is not 400
		if ($result && property_exists($result, 'status') && $result->status >= 200 && $result->status < 300) {
			$data['entries'] = $result->data;
		} else {
			$data['entries'] = [];
		}
		
		$table = $this->load->view('pages/ajax-row-data-report', $data, TRUE);

		echo $table;
		}catch(Exception $e){
			log_message('error', 'Error in raw_data_report: ' . $e->getMessage());
			echo 'An error occurred. Please try again later.';
		}
	}

	public function ajax_insights()
	{
		$params = $this->input->post(NULL, TRUE);

		$dates = explode('-', $params['dates']);
		$startdate = $this->custom->date_maker(trim($dates[0]));
		$enddate = $this->custom->date_maker(trim($dates[1]));
		$baseline_url = API_BASE_URL . 'entries/group-by-region?data_type=baseline&form_id=11&startdate=' . $startdate . '&enddate=' . $enddate;
		$baseline_result = json_decode($this->custom->run_curl_get($baseline_url));
		$baseline_data = $baseline_result->data->entries;
		$baseline_region = [];
		foreach ($baseline_data as $key => $value) {
			array_push($baseline_region, $value->count);
		}

		$followup_url = API_BASE_URL . 'entries/group-by-region?data_type=followup&form_id=11&startdate=' . $startdate . '&enddate=' . $enddate;
		$followup_result = json_decode($this->custom->run_curl_get($followup_url));
		$followup_data = $followup_result->data->entries;
		$followup_region = [];
		foreach ($followup_data as $key => $value) {
			array_push($followup_region, $value->count);
		}

		$baseline_url = API_BASE_URL . 'entries/group-by-latrine-coverage?data_type=baseline&form_id=11&startdate=' . $startdate . '&enddate=' . $enddate;
		$baseline_result = json_decode($this->custom->run_curl_get($baseline_url));
		$baseline_data = $baseline_result->data->entries;
		$baseline_latrine_coverage = [];
		$baseline_keys = [];
		foreach ($baseline_data as $key => $value) {
			array_push($baseline_latrine_coverage, $value->count);
		}

		$followup_url = API_BASE_URL . 'entries/group-by-latrine-coverage?data_type=followup&form_id=11&startdate=' . $startdate . '&enddate=' . $enddate;
		$followup_result = json_decode($this->custom->run_curl_get($followup_url));
		$followup_data = $followup_result->data->entries;
		$followup_latrine_coverage = [];
		foreach ($followup_data as $key => $value) {
			array_push($followup_latrine_coverage, $value->count);
		}

		$baseline_url = API_BASE_URL . 'entries/group_by_sanitation_category?data_type=baseline&form_id=11&startdate=' . $startdate . '&enddate=' . $enddate;
		$baseline_result = json_decode($this->custom->run_curl_get($baseline_url));
		$baseline_data = $baseline_result->data->entries;
		$baseline_sanitation_category = [];
		$baseline_keys = [];
		foreach ($baseline_data as $key => $value) {
			array_push($baseline_sanitation_category, $value->count);
		}

		$followup_url = API_BASE_URL . 'entries/group_by_sanitation_category?data_type=followup&form_id=11&startdate=' . $startdate . '&enddate=' . $enddate;
		$followup_result = json_decode($this->custom->run_curl_get($followup_url));
		$followup_data = $followup_result->data->entries;
		$followup_sanitation_category = [];
		foreach ($followup_data as $key => $value) {
			array_push($followup_sanitation_category, $value->count);
		}

		$baseline_url = API_BASE_URL . 'entries/group-by-duration-of-water-collection?data_type=baseline&form_id=11&startdate=' . $startdate . '&enddate=' . $enddate;
		$baseline_result = json_decode($this->custom->run_curl_get($baseline_url));
		$baseline_data = $baseline_result->data->entries;
		$baseline_water_collection = [];
		$baseline_keys = [];
		foreach ($baseline_data as $key => $value) {
			array_push($baseline_water_collection, $value->count);
		}

		$followup_url = API_BASE_URL . 'entries/group-by-duration-of-water-collection?data_type=followup&form_id=11&startdate=' . $startdate . '&enddate=' . $enddate;
		$followup_result = json_decode($this->custom->run_curl_get($followup_url));
		$followup_data = $followup_result->data->entries;
		$followup_water_collection = [];
		foreach ($followup_data as $key => $value) {
			array_push($followup_water_collection, $value->count);
		}

		$baseline_url = API_BASE_URL . 'entries/group-by-water-treatment?data_type=baseline&form_id=11&startdate=' . $startdate . '&enddate=' . $enddate;
		$baseline_result = json_decode($this->custom->run_curl_get($baseline_url));
		$baseline_data = $baseline_result->data->entries;
		$baseline_water_treatment = [];
		$baseline_keys = [];
		foreach ($baseline_data as $key => $value) {
			array_push($baseline_water_treatment, $value->count);
		}

		$followup_url = API_BASE_URL . 'entries/group-by-water-treatment?data_type=followup&form_id=11&startdate=' . $startdate . '&enddate=' . $enddate;
		$followup_result = json_decode($this->custom->run_curl_get($followup_url));
		$followup_data = $followup_result->data->entries;
		$followup_water_treatment = [];
		foreach ($followup_data as $key => $value) {
			array_push($followup_water_treatment, $value->count);
		}

		$baseline_url = API_BASE_URL . 'entries/group-by-family-savings?data_type=baseline&form_id=11&startdate=' . $startdate . '&enddate=' . $enddate;
		$baseline_result = json_decode($this->custom->run_curl_get($baseline_url));
		$baseline_data = $baseline_result->data->entries;
		$baseline_family_savings = [];
		$baseline_keys = [];
		foreach ($baseline_data as $key => $value) {
			array_push($baseline_family_savings, $value->count);
		}

		$followup_url = API_BASE_URL . 'entries/group-by-family-savings?data_type=followup&form_id=11&startdate=' . $startdate . '&enddate=' . $enddate;
		$followup_result = json_decode($this->custom->run_curl_get($followup_url));
		$followup_data = $followup_result->data->entries;
		$followup_family_savings = [];
		foreach ($followup_data as $key => $value) {
			array_push($followup_family_savings, $value->count);
		}

		$region_and_district_url = API_BASE_URL . 'entries/group-by-region-and-districts?form_id=11&startdate=' . $startdate . '&enddate=' . $enddate;
		$region_and_district_result = json_decode($this->custom->run_curl_get($region_and_district_url))->data;

		$data['baseline_region'] = json_encode($baseline_region);
		$data['followup_region'] = json_encode($followup_region);
		$data['baseline_latrine_coverage'] = json_encode($baseline_latrine_coverage);
		$data['followup_latrine_coverage'] = json_encode($followup_latrine_coverage);
		$data['baseline_sanitation_category'] = json_encode($baseline_sanitation_category);
		$data['followup_sanitation_category'] = json_encode($followup_sanitation_category);
		$data['baseline_water_collection'] = json_encode($baseline_water_collection);
		$data['followup_water_collection'] = json_encode($followup_water_collection);
		$data['baseline_water_treatment'] = json_encode($baseline_water_treatment);
		$data['followup_water_treatment'] = json_encode($followup_water_treatment);
		$data['baseline_family_savings'] = json_encode($baseline_family_savings);
		$data['followup_family_savings'] = json_encode($followup_family_savings);
		$data['region_and_district'] = json_encode($region_and_district_result);


		$graphs = $this->load->view('pages/ajax-insights', $data, TRUE);
		echo $graphs;
	}



	public function ajax_entries_report($form_id, $entry_data, $startdate, $enddate)
	{
		//CHECK FOR SESSION
		// if (!$this->session->has_userdata('logged_in')) { redirect('login'); }

		$url = API_BASE_URL.'get-raw-responses-table?form_id='.$form_id.'&entry_data='.$entry_data.'&startdate='.$startdate.'&enddate='.$enddate.'&format=json';
		ini_set('memory_limit','256M');
		$result = json_decode($this->custom->run_curl_get($url));
		$data['entries'] = $result->data ?? [];
		// $this->custom->print($data); die();
		$table = $this->load->view('pages/ajax-row-data-report', $data, TRUE);
		// return $table;
		echo $table;
	}


	public function ajax_agg_entries_report()
	{
		//CHECK FOR SESSION
		// if (!$this->session->has_userdata('logged_in')) { redirect('login'); }
		$params = $this->input->post(NULL, TRUE);
		// $this->custom->print($params, true);
		// $dates = explode('-', $params['dates']);
		// $startdate = $this->custom->date_maker(trim($dates[0]));
		// $enddate = $this->custom->date_maker(trim($dates[1]));
		// $form_id = $params['form_id'];
		// $field = $params['field'];
		// $field_id = $params['field_id'];
		// $group_by = $params['group_by'];
		// $data_type = $params['data_type'];
		// $project_id = $params['project_id'] ?? 'all';
		// if ($params['region_id'] != 'all') { $query_data['region_id'] = $params['region_id']; }

		// $url = API_BASE_URL.'get-agg-data-responses?form_id='.$form_id.'&field='.$field.'&field_id='.$field_id.'&group_by='.$group_by.'&startdate='.$startdate.'&enddate='.$enddate.'&data_type='.$data_type.'&project_id='.$project_id.'&format=json';
		// $this->custom->print($url, true);

		$query_data['form_id'] = $params['form_id'];
		$query_data['field'] = $params['field'];
		$query_data['field_id'] = $params['field_id'];
		$query_data['group_by'] = $params['group_by'];
		$query_data['data_type'] = $params['data_type'];
		$query_data['project'] = $params['project'] ?? 'all';
		// if ($params['project'] != 'all') { $query_data['project'] = $params['project']; }

		$dates = explode('-', $params['dates']);
		$query_data['startdate'] = $this->custom->date_maker(trim($dates[0]));
		$query_data['enddate'] = $this->custom->date_maker(trim($dates[1]));

		$url = API_BASE_URL.'aggregated-report/entries?'.http_build_query($query_data);

		ini_set('memory_limit','256M');
		$result = json_decode($this->custom->run_curl_get($url));

		// $this->custom->print($result); die();
		$data['data'] = $result->data ?? [];
		// $this->load->view('pages/ajax-row-data-agg-report', $data);
		$table = $this->load->view('pages/ajax-row-data-agg-report', $data, TRUE);
		echo $table;
	}

		public function use_question_library($form_id){
			//CHECK FOR SESSION
		// if (!$this->session->has_userdata('logged_in')) { redirect('login'); }
		$url = API_BASE_URLS.'get-answer-types?format=json';
		//$url = API_BASE_URL.'answer-types';
		//$url = API_BASE_URL.'library-questions';
		$result = $this->custom->run_curl_get($url);
		$obj_array = json_decode($result);
		$answer_types = $obj_array->data;

		$questions_url = API_BASE_URL.'question?format=json';
		$questions_result = $this->custom->run_curl_get($questions_url);
		$questions = json_decode($questions_result)->data;

		$data['answer_types'] = $answer_types;
		$data['form_id'] = $form_id;
		$data['question_list'] = $questions;

		$data['form_action'] = 'ajax/create-question-from-library/'.$form_id;
		$this->load->view('ajax-pages/add-question-from-library-card.php', $data);
	}
}
