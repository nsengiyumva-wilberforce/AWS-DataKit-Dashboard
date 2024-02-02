<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Form extends CI_Controller {


	// private $api_base_url = 'http://127.0.0.1/aws-api/';
	// private $api_base_url = 'http://127.0.0.1/aws-api/index.php/app/';
	// private $api_base_url = 'http://116.203.142.9/aws-api-bak/index.php/app/';

	public function add_mobile_user()
	{
		//CHECK FOR SESSION
		if (!$this->session->has_userdata('logged_in')) { redirect(); }

		$params = $this->input->post(NULL, TRUE);
		$params['role_id'] = 1;
		$params['active'] = $params['active'] == 1 ? 1 : 0;
                $url = 'http://157.245.19.48/aws.api/public/user/add';
		//$url = 'http://127.0.0.1/aws/api/public/user/add';
		$result = json_decode($this->custom->run_curl_post($url, $params));
		redirect('mobile-user/'.$result->data->user_id);
		// $this->custom->print($params); die();
	}


	public function edit_mobile_user($user_id)
	{
		//CHECK FOR SESSION
		if (!$this->session->has_userdata('logged_in')) { redirect(); }

		$params = $this->input->post(NULL, TRUE);
		$params['user_id'] = $user_id;
		$params['role_id'] = 1;
		$params['active'] = (isset($params['active']) && ($params['active'] == 1)) ? 1 : 0;
		if ($params['password'] == '') unset($params['password']);
		// $this->custom->print($params); die();
                $url = 'http://157.245.19.48/aws.api/public/user/edit';
		//$url = API_BASE_URL.'user/edit';
		$result = $this->custom->run_curl_post($url, $params);
		// $this->custom->print($result); die();
		redirect('mobile-user/'.$user_id);
	}














	public function add_dashboard_user()
	{
		//CHECK FOR SESSION
		if (!$this->session->has_userdata('logged_in')) { redirect(); }
		$params = $this->input->post(NULL, TRUE);
		$params['region_id'] = $params['region_id'] != '' ? $params['region_id'] : NULL;
		$params['active'] = $params['active'] == 1 ? 1 : 0;

		$url = API_BASE_URL.'admin-user/add';
		$result = json_decode($this->custom->run_curl_post($url, $params));
		redirect('dashboard-user/'.$result->data->user_id);
		// $this->custom->print($params); die();
	}


	public function edit_dashboard_user($user_id)
	{
		//CHECK FOR SESSION
		if (!$this->session->has_userdata('logged_in')) { redirect(); }

		$params = $this->input->post(NULL, TRUE);
		$params['user_id'] = $user_id;
		// $params['role_id'] = 1;
		$params['region_id'] = $params['region_id'] != '' ? $params['region_id'] : NULL;
		$params['active'] = (isset($params['active']) && ($params['active'] == 1)) ? 1 : 0;
		if ($params['password'] == '') unset($params['password']);
		// $this->custom->print($params); die();

		$url = API_BASE_URL.'admin-user/edit';
		$result = $this->custom->run_curl_post($url, $params);
		// $this->custom->print($result); die();
		redirect('dashboard-users');

	}









	public function add_project()
	{
		$params = $this->input->post(NULL, TRUE);
		// $this->custom->print($params); die();
		$url = API_BASE_URL.'project/add';
		$result = $this->custom->run_curl_post($url, $params);
		// $this->custom->print($result); die();
		$project = json_decode($result);
		redirect('projects');
	}

	public function add_chart()
	{
		$params = $this->input->post(NULL, TRUE);

		$dates = explode(' - ', $params['dates']);
		$params['start_date'] = $this->custom->date_maker($dates[0]);
		$params['end_date'] = $this->custom->date_maker($dates[1]);
		$params['form_list'] = json_encode(array_unique(explode(', ', $params['forms'])));
		unset($params['dates']);
		unset($params['forms']);
		// $this->custom->print($params); die();
		$url = API_BASE_URL.'chart/add';
		$result = $this->custom->run_curl_post($url, $params);

				// $this->custom->print($this->custom->run_curl_post($url, $params)); die();

		$chart = json_decode($result);
		redirect();
	}

	public function add_indicator_chart()
	{
		$params = $this->input->post(NULL, TRUE);

		$dates = explode(' - ', $params['dates']);
		$params['start_date'] = $this->custom->date_maker($dates[0]);
		$params['end_date'] = $this->custom->date_maker($dates[1]);
		unset($params['dates']);
		// $this->custom->print($params); die();
		$url = API_BASE_URL.'indicator-chart/add';
		// $this->custom->print($this->custom->run_curl_post($url, $params)); die();
		$result = $this->custom->run_curl_post($url, $params);
		$chart = json_decode($result);
		redirect();
	}

	public function add_region()
	{
		$params = $this->input->post(NULL, TRUE);
		// $this->custom->print($params); die();
		$url = API_BASE_URL.'region/add';
		$result = $this->custom->run_curl_post($url, $params);
		$region = json_decode($result);
		redirect('regions');
	}

	public function add_organisation()
	{
		$params = $this->input->post(NULL, TRUE);
		// $this->custom->print($params); die();
		$url = API_BASE_URL.'organisation/add';
		$result = $this->custom->run_curl_post($url, $params);
		$organisation = json_decode($result);
		redirect('organisations');
	}

	public function add_district()
	{
		$params = $this->input->post(NULL, TRUE);
		$name_list = explode(',', $params['name_list']);

		$url = API_BASE_URL.'district/add';
		foreach ($name_list as $district) {
			$data['region_id'] = $params['region_id'];
			$data['name'] = trim($district);

			$result = $this->custom->run_curl_post($url, $data);
			$districts[] = json_decode($result);
		}
		redirect('districts');
	}

	public function add_sub_county()
	{
		$params = $this->input->post(NULL, TRUE);
		$name_list = explode(',', $params['name_list']);

		$url = API_BASE_URL.'sub-county/add';
		foreach ($name_list as $sub_country) {
			$data['district_id'] = $params['district_id'];
			$data['name'] = trim($sub_country);

			$result = $this->custom->run_curl_post($url, $data);
			$sub_counties[] = json_decode($result);
		}
		redirect('sub-counties');
	}

	public function add_parish()
	{
		$params = $this->input->post(NULL, TRUE);
		$name_list = explode(',', $params['name_list']);

		$url = API_BASE_URL.'parish/add';
		foreach ($name_list as $parish) {
			$data['sub_county_id'] = $params['sub_county_id'];
			$data['name'] = trim($parish);

			$result = $this->custom->run_curl_post($url, $data);
			$parishes[] = json_decode($result);
		}
		redirect('parishes');
	}

	public function add_village()
	{
		$params = $this->input->post(NULL, TRUE);
		$name_list = explode(',', $params['name_list']);

		$url = API_BASE_URL.'village/add';
		foreach ($name_list as $village) {
			$data['parish_id'] = $params['parish_id'];
			$data['name'] = trim($village);

			$result = $this->custom->run_curl_post($url, $data);
			$villages[] = json_decode($result);
		}
		redirect('villages');
	}




	public function update_chart($chart_id)
	{
		$params = $this->input->post(NULL, TRUE);
		$params['chart_id'] = $chart_id;

		$dates = explode(' - ', $params['dates']);
		$params['start_date'] = $this->custom->date_maker($dates[0]);
		$params['end_date'] = $this->custom->date_maker($dates[1]);
		$params['form_list'] = json_encode(array_unique(explode(', ', $params['forms'])));
		unset($params['dates']);
		unset($params['forms']);
		// $this->custom->print($params); die();
		$url = API_BASE_URL.'chart/edit';
		$result = $this->custom->run_curl_post($url, $params);
		// $this->custom->print($params); die();
		redirect();
	}

	public function update_indicator_chart($chart_id)
	{
		$params = $this->input->post(NULL, TRUE);
		$params['chart_id'] = $chart_id;

		$dates = explode(' - ', $params['dates']);
		$params['start_date'] = $this->custom->date_maker($dates[0]);
		$params['end_date'] = $this->custom->date_maker($dates[1]);
		unset($params['dates']);
		// $this->custom->print($params); die();
		$url = API_BASE_URL.'indicator-chart/edit';
		$result = $this->custom->run_curl_post($url, $params);
		// $this->custom->print($params); die();
		redirect();
	}

	public function update_organisation($organisation_id)
	{
		$params = $this->input->post(NULL, TRUE);
		$params['organisation_id'] = $organisation_id;
		// $this->custom->print($params); die();
		$url = API_BASE_URL.'organisation/edit';
		$result = $this->custom->run_curl_post($url, $params);
		// $this->custom->print($params); die();
		redirect('organisations');
	}

	public function update_region($region_id)
	{
		$params = $this->input->post(NULL, TRUE);
		$params['region_id'] = $region_id;
		// $this->custom->print($params); die();
		$url = API_BASE_URL.'region/edit';
		$result = $this->custom->run_curl_post($url, $params);
		// $this->custom->print($result); die();
		redirect('regions');
	}

	public function update_district($district_id)
	{
		$params = $this->input->post(NULL, TRUE);
		$params['district_id'] = $district_id;
		$params['name'] = $params['name_list'];
		// $this->custom->print($params); die();
		$url = API_BASE_URL.'district/edit';
		$result = $this->custom->run_curl_post($url, $params);
		// $this->custom->print($result); die();
		redirect('districts');
	}

	public function update_sub_county($sub_county_id)
	{
		$params = $this->input->post(NULL, TRUE);
		$params['sub_county_id'] = $sub_county_id;
		$params['name'] = $params['name_list'];
		// $this->custom->print($params); die();
		unset($params['region_id']);
		$url = API_BASE_URL.'sub-county/edit';
		$result = $this->custom->run_curl_post($url, $params);
		// $this->custom->print($result); die();
		redirect('sub-counties');
	}

	public function update_parish($parish_id)
	{
		$params = $this->input->post(NULL, TRUE);
		$params['parish_id'] = $parish_id;
		$params['name'] = $params['name_list'];
		unset($params['region_id']);
		unset($params['district_id']);
		// $this->custom->print($params); die();
		$url = API_BASE_URL.'parish/edit';
		$result = $this->custom->run_curl_post($url, $params);
		// $this->custom->print($params); die();
		redirect('parishes');
	}

	public function update_village($village_id)
	{
		$params = $this->input->post(NULL, TRUE);
		$params['village_id'] = $village_id;
		$params['name'] = $params['name_list'];
		unset($params['region_id']);
		unset($params['district_id']);
		unset($params['sub_county_id']);
		// $this->custom->print($params); die();
		$url = API_BASE_URL.'village/edit';
		$result = $this->custom->run_curl_post($url, $params);
		// $this->custom->print($params); die();
		redirect('villages');
	}






	public function update_project($project_id)
	{
		$params = $this->input->post(NULL, TRUE);
		$params['project_id'] = $project_id;
		// $this->custom->print($params); die();
		$url = API_BASE_URL.'project/edit';
		$result = $this->custom->run_curl_post($url, $params);
		// $this->custom->print($result); die();
		redirect('projects');
	}



	public function update_form($form_id)
	{
		$params = $this->input->post(NULL, TRUE);

		$data['form_id'] = $form_id;
		$data['title'] = $params['title'];
		$data['is_geotagged'] = $params['is_geotagged'] ?? 0;
		$data['is_photograph'] = $params['is_photograph'] ?? 0;
		$data['is_followup'] = $params['is_followup'] ?? 0;
		$data['followup_interval'] = $params['followup_interval'] ?? NULL;
		$entry_title = $params['entry_title'] ? explode(',', str_replace(' ', '', $params['entry_title'])) : [];
		$entry_subtitle = $params['entry_subtitle'] ? explode(',', str_replace(' ', '', $params['entry_subtitle'])) : [];
		$data['title_fields'] = json_encode(array('entry_title' => $entry_title, 'entry_sub_title' => $entry_subtitle));
		$followup_prefill = $params['followup_prefill'] ? explode(',', str_replace(' ', '', $params['followup_prefill'])) : [];
		$data['followup_prefill'] = json_encode($followup_prefill);
		$data['is_publish'] = $params['is_publish'] ?? 0;

		// $this->custom->print($data); die();
		$url = API_BASE_URL.'form/edit';
		$result = $this->custom->run_curl_post($url, $data);
		// $this->custom->print($result); die();
		redirect('form/'.$form_id);
	}






	public function edit_entry($entry_id)
	{
		//CHECK FOR SESSION
		if (!$this->session->has_userdata('logged_in')) { redirect(); }

		$params = $this->input->post(NULL, TRUE);
		$params['created_at'] = $params['created_at'] ?? date('Y-m-d H:i:s');

		$data['response_id'] = $entry_id;
		$data['index'] = $params['index'];

		unset($params['index']);

		$data['response'] = json_encode($params);
		// $this->custom->print($data); die();

		$url = API_BASE_URL.'entry/edit';
		$result = $this->custom->run_curl_post($url, $data);
		// print_r($result); exit;

		redirect('entry/'.$entry_id);
	}

	public function delete_form($form_id)
        {
                $params['form_id'] = $form_id;
                $url = API_BASE_URLS.'delete-form';
                $result = $this->custom->run_curl_post($url, $params);
                redirect('forms');
        }










}
