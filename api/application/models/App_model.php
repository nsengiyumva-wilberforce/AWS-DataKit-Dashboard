<?php
class App_model extends CI_Model {

	public function get_form($form_id)
	{
		$this->db->select('*');
		$this->db->from('question_form');
		$this->db->where('form_id', $form_id);
		$this->db->where('active', 1);
		$query = $this->db->get();
		return $query->row();
	}

	public function get_forms()
	{
		$this->db->select('*');
		$this->db->from('question_form');
		$this->db->where('active', 1);
		$query = $this->db->get();
		return $query->result();
	}

	public function get_published_forms()
	{
		$this->db->select('*');
		$this->db->from('question_form');
		$this->db->where('is_publish', 1);
		$this->db->where('active', 1);
		$query = $this->db->get();
		return $query->result();
	}

	public function get_responses()
	{
		$this->db->select('*');
		$this->db->from('response');
		$this->db->where('active', 1);
		$query = $this->db->get();
		return $query->result();
	}


	public function get_filtered_responses($form_id = NULL, $response = NULL, $startdate = NULL, $enddate = NULL, $region_id = NULL, $project = NULL, $table = NULL)
	{
		ini_set('memory_limit','512M');
		$this->db->select('*');

		if (is_null($table)) {
			$this->db->from('response_with_location_view');
		} elseif ($table == 'parish_location_view') {
			$this->db->from('response_with_parish_location_view');
		} elseif ($table == 'sub_county_location_view') {
			$this->db->from('response_with_sub_county_location_view');
		} elseif ($table == 'district_location_view') {
			$this->db->from('response_with_district_location_view');
		}

		if (!is_null($form_id)) {
			$this->db->where('form_id', $form_id);
		}

		if ($response == 'baseline') {
			if (!is_null($startdate) && !is_null($enddate)) {
				$this->db->group_start();
				$this->db->where('DATE(date_created) >=', $startdate);
				$this->db->where('DATE(date_created) <=', $enddate);
				$this->db->group_end();
			}

			if (!is_null($project)) {
				$this->db->group_start();
				$this->db->where('JSON_EXTRACT(json_response, "$.qn148") = ', $project);
				$this->db->or_where('JSON_EXTRACT(json_response, "$.qn_148") = ', $project);
				$this->db->group_end();
			}

		}
		elseif ($response == 'followup') {
			$this->db->where('recent_followup IS NOT NULL');
			if (!is_null($startdate) && !is_null($enddate)) {
				$this->db->group_start();
				$this->db->where('DATE(date_modified) >=', $startdate);
				$this->db->where('DATE(date_modified) <=', $enddate);
				$this->db->group_end();
			}

			if (!is_null($project)) {
				$this->db->group_start();
				$this->db->where('JSON_EXTRACT(recent_followup, "$.qn148") = ', $project);
				$this->db->or_where('JSON_EXTRACT(recent_followup, "$.qn_148") = ', $project);
				$this->db->group_end();
			}

		}

		if (!is_null($region_id)) {
			$this->db->where('region_id', $region_id);
		}




		$this->db->where('active', 1);
		$query = $this->db->get();
		return $query->result();
	}

	public function get_form_responses($form_id = null)
	{
		$this->db->select('*');
		$this->db->from('response_with_location_view');
		if (!is_null($form_id)) {
			$this->db->where('form_id', $form_id);
		}		
		$this->db->where('active', 1);
		$query = $this->db->get();
		return $query->result();
	}

	public function get_form_map_responses($form_id = null, $project = false)
	{
		ini_set('memory_limit','512M');
		if ($project) {
			$this->db->select('response_id, district, sub_county, parish, village, entry_form_id, first_name, last_name, title, sub_title, JSON_UNQUOTE(JSON_EXTRACT(json_response, "$.coordinates")) AS coordinates');
		} else {
			$this->db->select('response_id, district, sub_county, parish, village, entry_form_id, first_name, last_name, title, sub_title, JSON_UNQUOTE(JSON_EXTRACT(json_response, "$.coordinates")) AS coordinates, JSON_EXTRACT(json_response, "$.qn147") AS project, JSON_UNQUOTE(JSON_EXTRACT(json_response, "$.qn148")) AS project');
		}
				
		$this->db->from('response_with_location_view');
		if (!is_null($form_id)) {
			$this->db->where('form_id', $form_id);
		}		
		$this->db->where('active', 1);
		$query = $this->db->get();
		return $query->result();
	}

	// 121, 122, 147, 148

	public function get_form_responses_summary($form_id = null, $region_id = null)
	{
		$this->db->select('response_id, district, sub_county, parish, village, entry_form_id, first_name, last_name, fu_first_name, fu_last_name, title, sub_title, date_created, date_modified');
		$this->db->from('response_with_location_view');
		if (!is_null($form_id)) {
			$this->db->where('form_id', $form_id);
		}
		if (!is_null($region_id)) {
			$this->db->where('region_id', $region_id);
		}
		$this->db->where('active', 1);
		$query = $this->db->get();
		return $query->result();
	}


	public function get_form_responses_summary_parish($form_id = null, $region_id = null)
	{
		$this->db->select('response_id, district, sub_county, parish, entry_form_id, first_name, last_name, fu_first_name, fu_last_name, title, sub_title, date_created, date_modified');
		$this->db->from('response_with_parish_location_view');
		if (!is_null($form_id)) {
			$this->db->where('form_id', $form_id);
		}
		if (!is_null($region_id)) {
			$this->db->where('region_id', $region_id);
		}
		$this->db->where('active', 1);
		$query = $this->db->get();
		return $query->result();
	}


	public function get_form_responses_summary_sub_county($form_id = null, $region_id = null)
	{
		$this->db->select('response_id, district, sub_county, entry_form_id, first_name, last_name, fu_first_name, fu_last_name, title, sub_title, date_created, date_modified');
		$this->db->from('response_with_sub_county_location_view');
		if (!is_null($form_id)) {
			$this->db->where('form_id', $form_id);
		}
		if (!is_null($region_id)) {
			$this->db->where('region_id', $region_id);
		}
		$this->db->where('active', 1);
		$query = $this->db->get();
		return $query->result();
	}


	public function get_form_responses_summary_district($form_id = null, $region_id = null)
	{
		$this->db->select('response_id, district, entry_form_id, first_name, last_name, fu_first_name, fu_last_name, title, sub_title, date_created, date_modified');
		$this->db->from('response_with_district_location_view');
		if (!is_null($form_id)) {
			$this->db->where('form_id', $form_id);
		}
		if (!is_null($region_id)) {
			$this->db->where('region_id', $region_id);
		}
		$this->db->where('active', 1);
		$query = $this->db->get();
		return $query->result();
	}














	public function get_overview_counter()
	{
		ini_set('memory_limit','512M');
		$query = $this->db->query('SELECT
									(SELECT COUNT(*) FROM question_form) AS forms, 
									(SELECT COUNT(*) FROM response) AS entries, 
									(SELECT COUNT(*) FROM user) AS mobile_users, 
									(SELECT COUNT(*) FROM app_project) AS projects;
								');
		return $query->row();
	}

	public function get_user_responses($creator_id, $form_id = null)
	{
		$this->db->select('*');
		$this->db->from('response');
		$this->db->where('creator_id', $creator_id);
		if (!is_null($form_id)) {
			$this->db->where('form_id', $form_id);
		}		
		$this->db->where('active', 1);
		$query = $this->db->get();
		return $query->result();
	}

	public function get_region_responses($region_id, $user_id, $form_id = null, $followup = null, $modified = null, $project = null)
	{
		ini_set('memory_limit', '512M');
		$this->db->select('*');
		$this->db->from('response_with_location_view');
		$this->db->where('region_id', $region_id);
		if (!is_null($user_id)) {
			$this->db->where('creator_id !=', $user_id);
		}
		if (!is_null($form_id)) {
			$this->db->where('form_id', $form_id);
		}
		if (!is_null($followup) && $followup == 'empty') {
			$this->db->like('json_followup', '[]');
		}
		if (!is_null($modified)) {
			$this->db->where('date_modified < NOW() - INTERVAL ' . $modified . ' DAY');
			$this->db->where('date_modified >=', '2020-12-01');
		}
		if (!is_null($project)) {
			$this->db->group_start();
			$this->db->where('JSON_EXTRACT(json_response, "$.qn148") = ', $project);
			$this->db->or_where('JSON_EXTRACT(json_response, "$.qn_148") = ', $project);
			$this->db->group_end();
		}
		$this->db->where('is_followup', 1);
		$this->db->where('active', 1);
		$query = $this->db->get();
		return $query->result();
	}

	public function get_charts()
	{
		$this->db->select('*');
		$this->db->from('chart');
		$this->db->where('active', 1);
		$query = $this->db->get();
		return $query->result();
	}

	public function get_chart($chart_id)
	{
		$this->db->select('*');
		$this->db->from('chart');
		$this->db->where('chart_id', $chart_id);
		$this->db->where('active', 1);
		$query = $this->db->get();
		return $query->row();
	}

	public function get_count_entries_by_form_ids($form_ids, $startdate = null, $enddate = null)
	{
		ini_set('memory_limit','512M');

		$this->db->select('*');
		$this->db->from('response');
		$this->db->where_in('form_id', $form_ids);

		if (!is_null($startdate) && !is_null($enddate)) {
			$this->db->group_start();
			$this->db->where('DATE(date_created) >=', $startdate);
			$this->db->where('DATE(date_created) <=', $enddate);
			$this->db->group_end();
		}

		$this->db->where('active', 1);
		$query = $this->db->get();
		return $query->num_rows();
	}


	public function get_questions()
	{
		$this->db->select('*');
		$this->db->from('question');
		$this->db->where('active', 1);
		$query = $this->db->get();
		return $query->result();
	}

	public function get_question($question_id)
	{
		$this->db->select('*');
		$this->db->from('question');
		$this->db->where('question_id', $question_id);
		$this->db->where('active', 1);
		$query = $this->db->get();
		return $query->row();
	}

	public function get_library_question($question_id)
	{
		$this->db->select('*');
                $this->db->from('question_library');
                $this->db->where('question_id', $question_id);
                $this->db->where('active', 1);
                $query = $this->db->get();
                return $query->row();
	}

	public function get_answer_types()
	{
		$this->db->select('*');
		$this->db->from('answer_type');
		$this->db->where('active', 1);
		$query = $this->db->get();
		return $query->result();
	}

	public function get_response($response_id)
	{
		$this->db->select('*');
		$this->db->from('response_with_location_view');
		$this->db->where('response_id' , $response_id);
		$this->db->where('active', 1);
		$query = $this->db->get();
		return $query->row();
	}

	public function get_response_parish($response_id)
	{
		$this->db->select('*');
		$this->db->from('response_with_parish_location_view');
		$this->db->where('response_id' , $response_id);
		$this->db->where('active', 1);
		$query = $this->db->get();
		return $query->row();
	}

	public function get_response_sub_county($response_id)
	{
		$this->db->select('*');
		$this->db->from('response_with_sub_county_location_view');
		$this->db->or_where('response_id' , $response_id);
		$this->db->where('active', 1);
		$query = $this->db->get();
		return $query->row();
	}


	public function get_response_district($response_id)
	{
		$this->db->select('*');
		$this->db->from('response_with_district_location_view');
		$this->db->where('response_id' , $response_id);
		$this->db->where('active', 1);
		$query = $this->db->get();
		return $query->row();
	}

	public function get_response_by_entry_form_id($entry_form_id)
	{
		$this->db->select('*');
		$this->db->from('response_with_location_view');
		$this->db->where('entry_form_id', $entry_form_id);
		$this->db->where('active', 1);
		$query = $this->db->get();
		return $query->row();
	}

	public function get_response_by_entry_and_creator_id($entry_form_id, $creator_id)
	{
		$this->db->select('*');
		$this->db->from('response');
		$this->db->where('entry_form_id', $entry_form_id);
		$this->db->where('creator_id', $creator_id);
		$this->db->where('active', 1);
		$query = $this->db->get();
		return $query->row();
	}

	public function get_dynamic_agg_responses($field, $field_value, $form_id)
	{
		$this->db->select('*');
		$this->db->from('response_with_location_view');
		$this->db->where($field, $field_value);
		$this->db->where('form_id', $form_id);
		$this->db->where('active', 1);
		$query = $this->db->get();
		return $query->result();
	}


	public function get_table_data($table_name)
	{
		$this->db->select('*');
		$this->db->from($table_name);
		$this->db->where('active', 1);
		$query = $this->db->get();
		return $query->result();
	}

	public function get_agg_responses($form_id)
	{
		$this->db->select('*');
		$this->db->from('response_with_location_view');
		$this->db->where('form_id', $form_id);
		$this->db->where('active', 1);
		$query = $this->db->get();
		return $query->result();
	}






	public function get_fieldsets()
	{
		$this->db->select('*');
		$this->db->from('fieldset');
		$this->db->where('active', 1);
		$query = $this->db->get();
		return $query->result();
	}

	public function get_fieldset($fieldset_id)
	{
		$this->db->select('*');
		$this->db->from('fieldset');
		$this->db->where('fieldset_id', $fieldset_id);
		$this->db->where('active', 1);
		$query = $this->db->get();
		return $query->row();
	}

	public function get_answer_type($answer_type_id)
	{
		$this->db->select('*');
		$this->db->from('answer_type');
		$this->db->where('answer_type_id', $answer_type_id);
		$this->db->where('active', 1);
		$query = $this->db->get();
		return $query->row();
	}
	public function get_form_questions($question_ids)
	{
		if (count($question_ids)) {
			// Create order of questions
			$item_order = 'question_id,';
			foreach ($question_ids as $value) {
				$item_order .= $value.',';
			}
			$item_order = rtrim($item_order,',');
			$this->db->select('*');
			$this->db->from('question');
			$this->db->where_in('question_id', $question_ids);
			$this->db->where('active', 1);
			$this->db->order_by('field('.$item_order.')');
			$query = $this->db->get();
			return $query->result();
		} else {
			return [];
		}
	}


	public function get_agg_form_questions($question_ids)
	{
		if (count($question_ids)) {
			// Create order of questions
			$item_order = 'question_id,';
			foreach ($question_ids as $value) {
				$item_order .= $value.',';
			}
			$item_order = rtrim($item_order,',');
			$this->db->select('*');
			$this->db->from('question');
			$this->db->where_in('question_id', $question_ids);
			$this->db->where_in('answer_type_id', [2,3,4]);
			$this->db->where('active', 1);
			$this->db->order_by('field('.$item_order.')');
			$query = $this->db->get();
			return $query->result();
		} else {
			return [];
		}
	}


	public function check_response($entry_form_id, $creator_id)
	{
		$this->db->select('*');
		$this->db->from('response');
		$this->db->where('entry_form_id', $entry_form_id);
		$this->db->where('creator_id', $creator_id);
		$this->db->where('active', 1);
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function get_projects()
	{
		$this->db->select('*');
		$this->db->from('app_project');
		$this->db->where('active', 1);
		$query = $this->db->get();
		return $query->result();
	}

	public function get_project($project_id)
	{
		$this->db->select('*');
		$this->db->from('app_project');
		$this->db->where('project_id', $project_id);
		$this->db->where('active', 1);
		$query = $this->db->get();
		return $query->row();
	}

	public function get_projects_by_ids($project_ids)
	{
		$this->db->select('*');
		$this->db->from('app_project');
		$this->db->where_in('project_id', $project_ids);
		$this->db->where('active', 1);
		$query = $this->db->get();
		return $query->result();
	}

	public function get_organisations()
	{
		$this->db->select('*');
		$this->db->from('app_organisation');
		$this->db->where('active', 1);
		$query = $this->db->get();
		return $query->result();
	}

	public function get_organisation($organisation_id)
	{
		$this->db->select('*');
		$this->db->from('app_organisation');
		$this->db->where('organisation_id', $organisation_id);
		$this->db->where('active', 1);
		$query = $this->db->get();
		return $query->row();
	}

	public function get_organisations_by_ids($organisation_ids)
	{
		$this->db->select('*');
		$this->db->from('app_organisation');
		$this->db->where_in('organisation_id', $organisation_ids);
		$this->db->where('active', 1);
		$query = $this->db->get();
		return $query->result();
	}

	public function get_regions()
	{
		$this->db->select('*');
		$this->db->from('region');
		$this->db->where('active', 1);
		$query = $this->db->get();
		return $query->result();
	}

	public function get_region($region_id)
	{
		$this->db->select('*');
		$this->db->from('region');
		$this->db->where('region_id', $region_id);
		$this->db->where('active', 1);
		$query = $this->db->get();
		return $query->row();
	}

	public function get_regions_by_ids($region_ids)
	{
		$this->db->select('*');
		$this->db->from('region');
		$this->db->where_in('region_id', $region_ids);
		$this->db->where('active', 1);
		$query = $this->db->get();
		return $query->result();
	}

	public function get_villages()
	{
		$this->db->select('*');
		$this->db->from('village_view');
		$this->db->where('active', 1);
		$query = $this->db->get();
		return $query->result();
	}

	public function get_village($village_id)
	{
		$this->db->select('*');
		$this->db->from('village_view');
		$this->db->where('village_id', $village_id);
		$this->db->where('active', 1);
		$query = $this->db->get();
		return $query->row();
	}

	public function get_villages_by_ids($village_ids)
	{
		$this->db->select('*');
		$this->db->from('app_village');
		$this->db->where_in('village_id', $village_ids);
		$this->db->where('active', 1);
		$query = $this->db->get();
		return $query->result();
	}

	public function get_parishes()
	{
		$this->db->select('*');
		$this->db->from('parish_view');
		$this->db->where('active', 1);
		$query = $this->db->get();
		return $query->result();
	}

	public function get_parishes_by_sub_county_id($sub_county_id)
	{
		$this->db->select('*');
		$this->db->from('app_parish');
		$this->db->where_in('sub_county_id', $sub_county_id);
		$this->db->where('active', 1);
		$query = $this->db->get();
		return $query->result();
	}

	public function get_parish($parish_id)
	{
		$this->db->select('*');
		$this->db->from('parish_view');
		$this->db->where('parish_id', $parish_id);
		$this->db->where('active', 1);
		$query = $this->db->get();
		return $query->row();
	}

	public function get_parishes_by_ids($parish_ids)
	{
		$this->db->select('*');
		$this->db->from('app_parish');
		$this->db->where_in('parish_id', $parish_ids);
		$this->db->where('active', 1);
		$query = $this->db->get();
		return $query->result();
	}

	public function get_sub_counties()
	{
		$this->db->select('*');
		$this->db->from('sub_county_view');
		$this->db->where('active', 1);
		$query = $this->db->get();
		return $query->result();
	}

	public function get_sub_county($sub_county_id)
	{
		$this->db->select('*');
		$this->db->from('sub_county_view');
		$this->db->where('sub_county_id', $sub_county_id);
		$this->db->where('active', 1);
		$query = $this->db->get();
		return $query->row();
	}

	public function get_sub_counties_by_ids($sub_county_ids)
	{
		$this->db->select('*');
		$this->db->from('app_sub_county');
		$this->db->where_in('sub_county_id', $sub_county_ids);
		$this->db->where('active', 1);
		$query = $this->db->get();
		return $query->result();
	}

	public function get_sub_counties_by_district_id($district_id)
	{
		$this->db->select('*');
		$this->db->from('app_sub_county');
		$this->db->where_in('district_id', $district_id);
		$this->db->where('active', 1);
		$query = $this->db->get();
		return $query->result();
	}

	public function get_districts()
	{
		$this->db->select('*');
		$this->db->from('district_view');
		$this->db->where('active', 1);
		$query = $this->db->get();
		return $query->result();
	}

	public function get_district($district_id)
	{
		$this->db->select('*');
		$this->db->from('district_view');
		$this->db->where('district_id', $district_id);
		$this->db->where('active', 1);
		$query = $this->db->get();
		return $query->row();
	}	

	public function get_districts_by_ids($district_ids)
	{
		$this->db->select('*');
		$this->db->from('app_district');
		$this->db->where_in('district_id', $district_ids);
		$this->db->where('active', 1);
		$query = $this->db->get();
		return $query->result();
	}


	public function get_districts_by_region_id($region)
	{
		$this->db->select('*');
		$this->db->from('app_district');
		$this->db->where_in('region_id', $region);
		$this->db->where('active', 1);
		$query = $this->db->get();
		return $query->result();
	}














	public function get_admin_roles()
	{
		$this->db->select('*');
		$this->db->from('admin_role');
		$this->db->where('active', 1);
		$query = $this->db->get();
		return $query->result();
	}


	public function get_users()
	{
		$this->db->select('*');
		$this->db->from('user_view');
		// $this->db->where('active', 1);
		$query = $this->db->get();
		return $query->result();
	}


	public function get_user($user_id)
	{
		$this->db->select('*');
		$this->db->from('user_view');
		$this->db->where('user_id', $user_id);
		// $this->db->where('active', 1);
		$query = $this->db->get();
		return $query->row();
	}

	public function get_user_by_credentials($username, $password)
	{
		$this->db->select('*');
		$this->db->from('user_view');
		$this->db->where('email', $username);
		$this->db->where('password', $password);
		$this->db->where('active', 1);
		$query = $this->db->get();
		return $query->row();
	}

	public function get_admin_users()
	{
		$this->db->select('*');
		$this->db->from('admin_user_view');
		// $this->db->where('active', 1);
		$query = $this->db->get();
		return $query->result();
	}


	public function get_admin_user($user_id)
	{
		$this->db->select('*');
		$this->db->from('admin_user_view');
		$this->db->where('user_id', $user_id);
		// $this->db->where('active', 1);
		$query = $this->db->get();
		return $query->row();
	}

	public function get_admin_user_by_credentials($username, $password)
	{
		$this->db->select('*');
		$this->db->from('admin_user_view');
		$this->db->where('email', $username);
		$this->db->where('password', $password);
		$this->db->where('active', 1);
		$query = $this->db->get();
		return $query->row();
	}

	public function get_app_list()
	{
		$query = $this->db->query("SELECT * FROM information_schema.tables WHERE table_name LIKE 'app_%' AND table_schema = 'aws'");
		return $query->result();
	}

	public function get_region_areas($region_id)
	{
		$this->db->simple_query("SET SESSION group_concat_max_len = 1000000;");
		$query = $this->db->query("SELECT `region_id`, GROUP_CONCAT(DISTINCT village_id) AS village_ids, GROUP_CONCAT(DISTINCT parish_id) AS parish_ids, GROUP_CONCAT(DISTINCT sub_county_id) AS sub_county_ids, GROUP_CONCAT(DISTINCT district_id) AS district_ids FROM `village_view` where region_id = ".$region_id." GROUP BY `region_id`;");
		return $query->row();
	}

	public function get_aggregated_responses($form_id, $aggregate_field, $form = 'json_response')
	{
		$query = $this->db->query('SELECT GROUP_CONCAT(DISTINCT region_id) AS region_id, GROUP_CONCAT(DISTINCT district_id) AS district_id, GROUP_CONCAT(DISTINCT sub_county_id) AS sub_county_id, GROUP_CONCAT(DISTINCT parish_id) AS parish_id, GROUP_CONCAT(DISTINCT village_id) AS village_id, GROUP_CONCAT('.$form.') AS entry_list FROM `response_with_location_view` WHERE form_id = '.$form_id.' AND active = 1 GROUP BY '.$aggregate_field.';');
		return $query->result();
	}

	public function get_area_responses($form_id, $area_field = null, $field_id = null, $startdate = null, $enddate = null, $data_type = null, $project = null, $table = null)
	{
		ini_set('memory_limit', '1024M');
		$this->db->select('*');
		// $this->db->from('response_with_location_view');

		if (is_null($table)) {
			$this->db->from('response_with_location_view');
		} elseif ($table == 'parish_location_view') {
			$this->db->from('response_with_parish_location_view');
		} elseif ($table == 'sub_county_location_view') {
			$this->db->from('response_with_sub_county_location_view');
		} elseif ($table == 'district_location_view') {
			$this->db->from('response_with_district_location_view');
		}

		$this->db->where('form_id', $form_id);
		if (!is_null($area_field) && !is_null($field_id)) {
			$this->db->where($area_field, $field_id);
		}
		if (!is_null($startdate) && !is_null($enddate)) {
			$this->db->group_start();
			if ($data_type == 'baseline') {
				$this->db->where('DATE(date_created) >=', $startdate);
				$this->db->where('DATE(date_created) <=', $enddate);

				if (!is_null($project)) {
					$this->db->group_start();
					$this->db->where('JSON_EXTRACT(json_response, "$.qn148") = ', $project);
					$this->db->or_where('JSON_EXTRACT(json_response, "$.qn_148") = ', $project);
					$this->db->group_end();
				}
			} elseif ($data_type == 'followup') {
				$this->db->where('DATE(date_modified) >=', $startdate);
				$this->db->where('DATE(date_modified) <=', $enddate);

				if (!is_null($project)) {
					$this->db->group_start();
					$this->db->where('JSON_EXTRACT(recent_followup, "$.qn148") = ', $project);
					$this->db->or_where('JSON_EXTRACT(recent_followup, "$.qn_148") = ', $project);
					$this->db->group_end();
				}
			} else {
				$this->db->where('DATE(date_created) >=', $startdate);
				$this->db->where('DATE(date_created) <=', $enddate);

				if (!is_null($project)) {
					$this->db->group_start();
					$this->db->where('JSON_EXTRACT(json_response, "$.qn148") = ', $project);
					$this->db->or_where('JSON_EXTRACT(json_response, "$.qn_148") = ', $project);
					$this->db->group_end();
				}
			}
			$this->db->group_end();
		}
		$this->db->where('active', 1);
		$this->db->order_by($area_field, 'ASC');


		$query = $this->db->get();
		return $query->result();
	}


	public function create_chart($values)
	{
		$this->db->insert('chart', $values);
		return $this->db->insert_id();
	}

	public function create_response($values)
	{
		$this->db->insert('response', $values);
		return $this->db->insert_id();
	}

	public function create_question($values)
	{
		$this->db->insert('question', $values);
		return $this->db->insert_id();
	}

	public function create_library_question($values){
		$this->db->insert('question_library', $values);
		return $this->db->insert_id();
	}

	public function create_form($values)
	{
		$this->db->insert('question_form', $values);
		return $this->db->insert_id();
	}

	public function create_user($values)
	{
		$this->db->insert('user', $values);
		return $this->db->insert_id();
	}

	public function create_admin_user($values)
	{
		$this->db->insert('admin_user', $values);
		return $this->db->insert_id();
	}



	public function create_organisation($values)
	{
		$this->db->insert('app_organisation', $values);
		return $this->db->insert_id();
	}

	public function create_region($values)
	{
		$this->db->insert('region', $values);
		return $this->db->insert_id();
	}

	public function create_district($values)
	{
		$this->db->insert('app_district', $values);
		return $this->db->insert_id();
	}

	public function create_district_batch($batch_values)
	{
		return $this->db->insert_batch('app_district', $batch_values);
	}

	public function create_sub_county($values)
	{
		$this->db->insert('app_sub_county', $values);
		return $this->db->insert_id();
	}

	public function create_sub_county_batch($batch_values)
	{
		return $this->db->insert_batch('app_sub_county', $batch_values);
	}

	public function create_parish($values)
	{
		$this->db->insert('app_parish', $values);
		return $this->db->insert_id();
	}

	public function create_parish_batch($batch_values)
	{
		return $this->db->insert_batch('app_parish', $batch_values);
	}

	public function create_village($values)
	{
		$this->db->insert('app_village', $values);
		return $this->db->insert_id();
	}

	public function create_village_batch($batch_values)
	{
		return $this->db->insert_batch('app_village', $batch_values);
	}

	public function create_project($values)
	{
		$this->db->insert('app_project', $values);
		return $this->db->insert_id();
	}









	public function update_form($form_id, $values)
	{
		$this->db->where('form_id', $form_id);
		return $this->db->update('question_form', $values);
	}

	public function update_chart($chart_id, $values)
	{
		$this->db->where('chart_id', $chart_id);
		return $this->db->update('chart', $values);
	}
	public function update_question($question_id, $values)
	{
		$this->db->where('question_id', $question_id);
		return $this->db->update('question', $values);
	}

	public function update_response($response_id, $values)
	{
		$this->db->where('response_id', $response_id);
		return $this->db->update('response', $values);
	}

	public function app_update_response($entry_form_id, $values)
	{
		$this->db->where('entry_form_id', $entry_form_id);
		return $this->db->update('response', $values);
	}

	public function update_user($user_id, $values)
	{
		$this->db->where('user_id', $user_id);
		return $this->db->update('user', $values);
	}

	public function update_admin_user($user_id, $values)
	{
		$this->db->where('user_id', $user_id);
		return $this->db->update('admin_user', $values);
	}



	public function update_organisation($organisation_id, $values)
	{
		$this->db->where('organisation_id', $organisation_id);
		return $this->db->update('app_organisation', $values);
	}

	public function update_region($region_id, $values)
	{
		$this->db->where('region_id', $region_id);
		return $this->db->update('region', $values);
	}

	public function update_district($district_id, $values)
	{
		$this->db->where('district_id', $district_id);
		return $this->db->update('app_district', $values);
	}

	public function update_sub_county($sub_county_id, $values)
	{
		$this->db->where('sub_county_id', $sub_county_id);
		return $this->db->update('app_sub_county', $values);
	}

	public function update_parish($parish_id, $values)
	{
		$this->db->where('parish_id', $parish_id);
		return $this->db->update('app_parish', $values);
	}

	public function update_village($village_id, $values)
	{
		$this->db->where('village_id', $village_id);
		return $this->db->update('app_village', $values);
	}

	public function update_project($project_id, $values)
	{
		$this->db->where('project_id', $project_id);
		return $this->db->update('app_project', $values);
	}




	public function delete_chart($chart_id)
	{
		$this->db->where('chart_id', $chart_id);
		return $this->db->delete('chart');
	}

	public function delete_response($response_id)
	{
		$this->db->where('response_id', $response_id);
		return $this->db->delete('response');
	}

	public function delete_question($question_id)
	{
		$this->db->where('question_id', $question_id);
		return $this->db->delete('question_library');
	}

	public function delete_questions($question_ids)
	{
		$this->db->where_in('question_id', $question_ids);
		return $this->db->delete('question');
	}

	public function delete_organisation($organisation_id)
	{
		$this->db->where('organisation_id', $organisation_id);
		return $this->db->delete('app_organisation');
	}

	public function delete_project($project_id)
	{
		$this->db->where('project_id', $project_id);
		return $this->db->delete('app_project');
	}

	public function delete_form($form_id)
	{
		$this->db->where('form_id', $form_id);
		return $this->db->delete('question_form');
	}

	public function delete_user($user_id)
	{
		$this->db->where('user_id', $user_id);
		return $this->db->delete('user');
	}

	public function delete_admin_user($user_id)
	{
		$this->db->where('user_id', $user_id);
		return $this->db->delete('admin_user');
	}








}
