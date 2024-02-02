<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class App extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
		$this->load->model('app_model');
    }


	public function index()
	{
		echo 'Get Form<br>';
		echo '<a href="http://127.0.0.1/aws-api/index.php/app/get-form?form_id=1&format=json">http://127.0.0.1/aws-api/index.php/app/get-form?form_id=1&format=json</a>';
	}


	public function admin_authenticate()
	{
		if ($this->input->method(TRUE) == 'POST') {

			$params = $this->input->post(NULL, TRUE);
			$data_format = $params['format'] ?? 'json' ;

			$user = $this->app_model->get_admin_user_by_credentials($params['username'], $params['password']);
			if ($user) {
				$data = $user;
				$return  = array('status' => true, 'message' => 'Valid Request', 'data' => $data);

			} else {
				$return  = array('status' => false, 'message' => 'Wrong Username or Password');
			}
			
		} else {
			$return  = array('status' => false, 'message' => 'Bad Request: Expecting POST');
		}
		$this->format->data($return, $data_format);
	}


	public function authenticate()
	{
		if ($this->input->method(TRUE) == 'POST') {
			$params = $this->input->post(NULL, TRUE);
			$data_format = $params['format'] ?? 'json' ;

			$user = $this->app_model->get_user_by_credentials($params['username'], $params['password']);
			if ($user) {
				$data = $user;
				$return  = array('status' => true, 'message' => 'Valid Request', 'data' => $data);

			} else {
				$return  = array('status' => false, 'message' => 'Wrong Username or Password');
			}
			
		} else {
			$return  = array('status' => false, 'message' => 'Bad Request: Expecting POST');
		}
		$this->format->data($return, $data_format);
	}

	public function dasboard_overview_counter()
	{
		if ($this->input->method(TRUE) == 'GET') {
			$params = $this->input->get(NULL, TRUE);
			$data_format = $params['format'] ?? 'json' ;

			$counter = $this->app_model->get_overview_counter();
			if ($counter) {
				$data = $counter;
				$return  = array('status' => true, 'message' => 'Valid Request', 'data' => $data);

			} else {
				$return  = array('status' => false, 'message' => 'No counters found');
			}
			
		} else {
			$return  = array('status' => false, 'message' => 'Bad Request: Expecting GET');
		}
		$this->format->data($return, $data_format);
	}


	public function get_users()
	{
		if ($this->input->method(TRUE) == 'GET') {
			$params = $this->input->get(NULL, TRUE);
			$data_format = $params['format'] ?? 'json' ;

			$users = $this->app_model->get_users();
			if ($users) {
				$data = $users;
				$return  = array('status' => true, 'message' => 'Valid Request', 'data' => $data);

			} else {
				$return  = array('status' => false, 'message' => 'No users found');
			}
			
		} else {
			$return  = array('status' => false, 'message' => 'Bad Request: Expecting GET');
		}
		$this->format->data($return, $data_format);
	}


	public function get_user()
	{
		if ($this->input->method(TRUE) == 'GET') {
			$params = $this->input->get(NULL, TRUE);
			$data_format = $params['format'] ?? 'json' ;

			$user = $this->app_model->get_user($params['user_id']);
			if ($user) {
				$data = $user;
				$return  = array('status' => true, 'message' => 'Valid Request', 'data' => $data);

			} else {
				$return  = array('status' => false, 'message' => 'This user doesnt exist');
			}
			
		} else {
			$return  = array('status' => false, 'message' => 'Bad Request: Expecting GET');
		}
		$this->format->data($return, $data_format);
	}

	public function get_user_region_areas()
	{
		if ($this->input->method(TRUE) == 'GET') {
			$params = $this->input->get(NULL, TRUE);
			$data_format = $params['format'] ?? 'json' ;

			$user = $this->app_model->get_user($params['user_id']);
			if ($user) {
				$areas = $this->app_model->get_region_areas($user->region_id);

				// print_r($areas);
				$region_ids = explode(',', $areas->region_id);
				$village_ids = explode(',', $areas->village_ids);
				$parish_ids = explode(',', $areas->parish_ids);
				$sub_county_ids = explode(',', $areas->sub_county_ids);
				$district_ids = explode(',', $areas->district_ids);

				$data['regions'] = $this->app_model->get_regions_by_ids($region_ids);
				$data['villages'] = $this->app_model->get_villages_by_ids($village_ids);
				$data['parishes'] = $this->app_model->get_parishes_by_ids($parish_ids);
				$data['sub_counties'] = $this->app_model->get_sub_counties_by_ids($sub_county_ids);
				$data['districts'] = $this->app_model->get_districts_by_ids($district_ids);

				$return  = array('status' => true, 'message' => 'Valid Request', 'data' => $data);

			} else {
				$return  = array('status' => false, 'message' => 'This user doesnt exist');
			}
			
		} else {
			$return  = array('status' => false, 'message' => 'Bad Request: Expecting GET');
		}
		$this->format->data($return, $data_format);
	}






	public function get_admin_users()
	{
		if ($this->input->method(TRUE) == 'GET') {
			$params = $this->input->get(NULL, TRUE);
			$data_format = $params['format'] ?? 'json' ;

			$users = $this->app_model->get_admin_users();
			if ($users) {
				$data = $users;
				$return  = array('status' => true, 'message' => 'Valid Request', 'data' => $data);

			} else {
				$return  = array('status' => false, 'message' => 'No users found');
			}
			
		} else {
			$return  = array('status' => false, 'message' => 'Bad Request: Expecting GET');
		}
		$this->format->data($return, $data_format);
	}


	public function get_admin_user()
	{
		if ($this->input->method(TRUE) == 'GET') {
			$params = $this->input->get(NULL, TRUE);
			$data_format = $params['format'] ?? 'json' ;

			$user = $this->app_model->get_admin_user($params['user_id']);
			if ($user) {
				$data = $user;
				$return  = array('status' => true, 'message' => 'Valid Request', 'data' => $data);

			} else {
				$return  = array('status' => false, 'message' => 'This user doesnt exist');
			}
			
		} else {
			$return  = array('status' => false, 'message' => 'Bad Request: Expecting GET');
		}
		$this->format->data($return, $data_format);
	}


	public function get_form()
	{
		if ($this->input->method(TRUE) == 'GET') {
			$params = $this->input->get(NULL, TRUE);
			$data_format = $params['format'] ?? null ;
			$form_id = $params['form_id'] ?? null ;

			$form = $this->app_model->get_form($params['form_id']);
			if ($form) {
				$question_list = json_decode($form->question_list);

				if (count($question_list)) {
					$questions = $this->app_model->get_form_questions($question_list);
					foreach ($questions as $question) {
						$answer_type = $this->app_model->get_answer_type($question->answer_type_id);
						$question->answer_type = $answer_type->machine_name;
						$question->answer_values = json_decode($question->answer_values);
					}
					$form->question_list = $questions ?? null;
				} else {
					$form->question_list = [];
				}

				if (isset($params['settings']) && $params['settings'] = true) {
					if (count(json_decode($form->title_fields, true))) {
						$title_fields = json_decode($form->title_fields);
						$entry_title = $this->app_model->get_form_questions($title_fields->entry_title);
						$entry_sub_title = $this->app_model->get_form_questions($title_fields->entry_sub_title);
						$form->title_fields = array('entry_title'=>$entry_title, 'entry_sub_title' => $entry_sub_title);
					} else {
						$form->title_fields = array('entry_title'=>[], 'entry_sub_title' => []);
					}

					if ($form->followup_prefill) {
						$form->followup_prefill = $this->app_model->get_form_questions(json_decode($form->followup_prefill));
					} else {
						$form->followup_prefill = [];
					}
				} else {
					$form->title_fields = $form->title_fields ? json_decode($form->title_fields) : [];
					$form->followup_prefill = $form->followup_prefill ? json_decode($form->followup_prefill) : [];
				}
				$data = $form;
				$return  = array('status' => true, 'message' => 'Valid Request', 'data' => $data);

			} else {
				$return  = array('status' => false, 'message' => 'Form of given id is not existant');
			}
			
		} else {
			$return  = array('status' => false, 'message' => 'Bad Request: Expecting GET');
		}
		$this->format->data($return, $data_format);
	}



	public function get_forms()
	{
		if ($this->input->method(TRUE) == 'GET') {
			$params = $this->input->get(NULL, TRUE);
			$data_format = $params['format'] ?? null ;

			$forms = $this->app_model->get_forms();
			if ($forms) {
				foreach ($forms as $form) {
					$question_list = json_decode($form->question_list);
					$questions = $this->app_model->get_form_questions($question_list);
					$list = $questions;
					foreach ($questions as $question) {
						$answer_type = $this->app_model->get_answer_type($question->answer_type_id);
						$question->answer_type = $answer_type->machine_name;
						$question->answer_values = json_decode($question->answer_values);
					}
					$form->question_list = $list ?? null;
					$form->title_fields = json_decode($form->title_fields);
					$form->followup_prefill = json_decode($form->followup_prefill);
				}
				$data = $forms;
				$return  = array('status' => true, 'message' => 'Valid Request', 'data' => $data);

			} else {
				$return  = array('status' => false, 'message' => 'Form of given id is not existant');
			}
			
		} else {
			$return  = array('status' => false, 'message' => 'Bad Request: Expecting GET');
		}
		$this->format->data($return, $data_format);
	}


	public function get_published_forms()
	{
		if ($this->input->method(TRUE) == 'GET') {
			$params = $this->input->get(NULL, TRUE);
			$data_format = $params['format'] ?? 'json' ;

			$forms = $this->app_model->get_published_forms();
			if ($forms) {
				foreach ($forms as $form) {
					$question_list = json_decode($form->question_list);
					$questions = $this->app_model->get_form_questions($question_list);
					$list = $questions;
					foreach ($questions as $question) {
						$answer_type = $this->app_model->get_answer_type($question->answer_type_id);
						$question->answer_type = $answer_type->machine_name;
						$question->answer_values = json_decode($question->answer_values);
					}
					$form->question_list = $list ?? null;
					$form->title_fields = json_decode($form->title_fields);
					$form->followup_prefill = json_decode($form->followup_prefill);
				}
				$data = $forms;
				$return  = array('status' => true, 'message' => 'Valid Request', 'data' => $data);

			} else {
				$return  = array('status' => false, 'message' => 'Form of given id is not existant');
			}
			
		} else {
			$return  = array('status' => false, 'message' => 'Bad Request: Expecting GET');
		}
		$this->format->data($return, $data_format);
	}


	public function get_questions()
	{
		if ($this->input->method(TRUE) == 'GET') {
			$params = $this->input->get(NULL, TRUE);
			$data_format = $params['format'] ?? null ;

			$questions = $this->app_model->get_questions();
			if ($questions) {

				foreach ($questions as $question) {
					$answer_type = $this->app_model->get_answer_type($question->answer_type_id);
					$question->answer_type = $answer_type->machine_name;
					$question->answer_values = json_decode($question->answer_values);
				}
				
				$data = $questions;
				$return  = array('status' => true, 'message' => 'Valid Request', 'data' => $data);

			} else {
				$return  = array('status' => false, 'message' => 'Form of given id is not existant');
			}
			
		} else {
			$return  = array('status' => false, 'message' => 'Bad Request: Expecting GET');
		}
		$this->format->data($return, $data_format);
	}



	public function get_question()
	{
		if ($this->input->method(TRUE) == 'GET') {
			$params = $this->input->get(NULL, TRUE);
			$data_format = $params['format'] ?? null ;

			$question = $this->app_model->get_question($params['question_id']);
			if ($question) {
				$answer_type = $this->app_model->get_answer_type($question->answer_type_id);
				$question->answer_type = $answer_type->machine_name;
				$question->answer_values = json_decode($question->answer_values);				
				$data = $question;
				
				$return  = array('status' => true, 'message' => 'Valid Request', 'data' => $data);

			} else {
				$return  = array('status' => false, 'message' => 'Question of given id is not existant');
			}
			
		} else {
			$return  = array('status' => false, 'message' => 'Bad Request: Expecting GET');
		}
		$this->format->data($return, $data_format);
	}


        public function get_library_question()
        {
                if ($this->input->method(TRUE) == 'GET') {
                        $params = $this->input->get(NULL, TRUE);
                        $data_format = $params['format'] ?? null ;

                        $question = $this->app_model->get_library_question($params['question_id']);
                        if ($question) {
                                $answer_type = $this->app_model->get_answer_type($question->answer_type_id);
                                $question->answer_type = $answer_type->machine_name;
                                $question->answer_values = json_decode($question->answer_values);
                                $data = $question;

                                $return  = array('status' => true, 'message' => 'Valid Request', 'data' => $data);

                        } else {
                                $return  = array('status' => false, 'message' => 'Question of given id is not existant');
                        }

                } else {
                        $return  = array('status' => false, 'message' => 'Bad Request: Expecting GET');
                }
                $this->format->data($return, $data_format);
	}


	public function get_projects()
	{
		if ($this->input->method(TRUE) == 'GET') {
			$params = $this->input->get(NULL, TRUE);
			$data_format = $params['format'] ?? 'json' ;

			$projects = $this->app_model->get_projects();
			if ($projects) {
				$data = $projects;
				$return  = array('status' => true, 'message' => 'Valid Request', 'data' => $data);

			} else {
				$return  = array('status' => false, 'message' => 'No projects found');
			}
			
		} else {
			$return  = array('status' => false, 'message' => 'Bad Request: Expecting GET');
		}
		$this->format->data($return, $data_format);
	}

	public function get_project()
	{
		if ($this->input->method(TRUE) == 'GET') {
			$params = $this->input->get(NULL, TRUE);
			$data_format = $params['format'] ?? 'json' ;

			$project = $this->app_model->get_project($params['project_id']);
			if ($project) {
				$data = $project;
				$return  = array('status' => true, 'message' => 'Valid Request', 'data' => $data);

			} else {
				$return  = array('status' => false, 'message' => 'No project found');
			}
			
		} else {
			$return  = array('status' => false, 'message' => 'Bad Request: Expecting GET');
		}
		$this->format->data($return, $data_format);
	}







	public function get_chart()
	{
		if ($this->input->method(TRUE) == 'GET') {
			$params = $this->input->get(NULL, TRUE);
			$data_format = $params['format'] ?? 'json' ;

			$chart = $this->app_model->get_chart($params['chart_id']);
			if ($chart) {
				$chart->actual = $this->app_model->get_count_entries_by_form_ids(json_decode($chart->form_list), $chart->start_date, $chart->end_date);
				if ($chart->target > $chart->actual) {
					$chart->difference = $chart->target - $chart->actual;
				} else {
					$chart->difference = 0;
				}
				$data = $chart;
				$return  = array('status' => true, 'message' => 'Valid Request', 'data' => $data);
			} else {
				$return  = array('status' => false, 'message' => 'No chart returned');
			}
			
		} else {
			$return  = array('status' => false, 'message' => 'Bad Request: Expecting GET');
		}
		$this->format->data($return, $data_format);
	}

	public function get_organisations()
	{
		if ($this->input->method(TRUE) == 'GET') {
			$params = $this->input->get(NULL, TRUE);
			$data_format = $params['format'] ?? 'json' ;

			$organisations = $this->app_model->get_organisations();
			if ($organisations) {
				$data = $organisations;
			} else {
				$data = [];
			}
			$return  = array('status' => true, 'message' => 'Valid Request', 'data' => $data);
			
		} else {
			$return  = array('status' => false, 'message' => 'Bad Request: Expecting GET');
		}
		$this->format->data($return, $data_format);
	}

	public function get_organisation()
	{
		if ($this->input->method(TRUE) == 'GET') {
			$params = $this->input->get(NULL, TRUE);
			$data_format = $params['format'] ?? 'json' ;

			$organisation = $this->app_model->get_organisation($params['organisation_id']);
			if ($organisation) {
				$data = $organisation;
				$return  = array('status' => true, 'message' => 'Valid Request', 'data' => $data);

			} else {
				$return  = array('status' => false, 'message' => 'No organisation found');
			}
			
		} else {
			$return  = array('status' => false, 'message' => 'Bad Request: Expecting GET');
		}
		$this->format->data($return, $data_format);
	}


	public function get_regions()
	{
		if ($this->input->method(TRUE) == 'GET') {
			$params = $this->input->get(NULL, TRUE);
			$data_format = $params['format'] ?? 'json' ;

			$regions = $this->app_model->get_regions();
			if ($regions) {
				$data = $regions;
				$return  = array('status' => true, 'message' => 'Valid Request', 'data' => $data);

			} else {
				$return  = array('status' => false, 'message' => 'No regions found');
			}
			
		} else {
			$return  = array('status' => false, 'message' => 'Bad Request: Expecting GET');
		}
		$this->format->data($return, $data_format);
	}

	public function get_region()
	{
		if ($this->input->method(TRUE) == 'GET') {
			$params = $this->input->get(NULL, TRUE);
			$data_format = $params['format'] ?? 'json' ;

			$region = $this->app_model->get_region($params['region_id']);
			if ($region) {
				$data = $region;
				$return  = array('status' => true, 'message' => 'Valid Request', 'data' => $data);

			} else {
				$return  = array('status' => false, 'message' => 'No region found');
			}
			
		} else {
			$return  = array('status' => false, 'message' => 'Bad Request: Expecting GET');
		}
		$this->format->data($return, $data_format);
	}


	public function get_districts()
	{
		if ($this->input->method(TRUE) == 'GET') {
			$params = $this->input->get(NULL, TRUE);
			$data_format = $params['format'] ?? 'json' ;

			if (isset($params['region_id'])) {
				$districts = $this->app_model->get_districts_by_region_id($params['region_id']);
			} else {
				$districts = $this->app_model->get_districts();
			}

			if ($districts) {
				$data = $districts;
				$return  = array('status' => true, 'message' => 'Valid Request', 'data' => $data);

			} else {
				$return  = array('status' => false, 'message' => 'No districts found');
			}
			
		} else {
			$return  = array('status' => false, 'message' => 'Bad Request: Expecting GET');
		}
		$this->format->data($return, $data_format);
	}

	public function get_district()
	{
		if ($this->input->method(TRUE) == 'GET') {
			$params = $this->input->get(NULL, TRUE);
			$data_format = $params['format'] ?? 'json' ;

			$district = $this->app_model->get_district($params['district_id']);
			if ($district) {
				$data = $district;
				$return  = array('status' => true, 'message' => 'Valid Request', 'data' => $data);

			} else {
				$return  = array('status' => false, 'message' => 'No district found');
			}
			
		} else {
			$return  = array('status' => false, 'message' => 'Bad Request: Expecting GET');
		}
		$this->format->data($return, $data_format);
	}

	public function get_sub_counties()
	{
		if ($this->input->method(TRUE) == 'GET') {
			$params = $this->input->get(NULL, TRUE);
			$data_format = $params['format'] ?? 'json' ;

			if (isset($params['district_id'])) {
				$sub_counties = $this->app_model->get_sub_counties_by_district_id($params['district_id']);
			} else {
				$sub_counties = $this->app_model->get_sub_counties();
			}

			if ($sub_counties) {
				$data = $sub_counties;
				$return  = array('status' => true, 'message' => 'Valid Request', 'data' => $data);

			} else {
				$return  = array('status' => false, 'message' => 'No sub counties found');
			}
			
		} else {
			$return  = array('status' => false, 'message' => 'Bad Request: Expecting GET');
		}
		$this->format->data($return, $data_format);
	}

	public function get_sub_county()
	{
		if ($this->input->method(TRUE) == 'GET') {
			$params = $this->input->get(NULL, TRUE);
			$data_format = $params['format'] ?? 'json' ;

			$sub_county = $this->app_model->get_sub_county($params['sub_county_id']);
			if ($sub_county) {
				$data = $sub_county;
				$return  = array('status' => true, 'message' => 'Valid Request', 'data' => $data);

			} else {
				$return  = array('status' => false, 'message' => 'No sub county found');
			}
			
		} else {
			$return  = array('status' => false, 'message' => 'Bad Request: Expecting GET');
		}
		$this->format->data($return, $data_format);
	}

	public function get_parishes()
	{
		if ($this->input->method(TRUE) == 'GET') {
			$params = $this->input->get(NULL, TRUE);
			$data_format = $params['format'] ?? 'json' ;

			if (isset($params['sub_county_id'])) {
				$parishes = $this->app_model->get_parishes_by_sub_county_id($params['sub_county_id']);
			} else {
				$parishes = $this->app_model->get_parishes();
			}

			// $parishes = $this->app_model->get_parishes();
			if ($parishes) {
				$data = $parishes;
				$return  = array('status' => true, 'message' => 'Valid Request', 'data' => $data);

			} else {
				$return  = array('status' => false, 'message' => 'No parishes found');
			}
			
		} else {
			$return  = array('status' => false, 'message' => 'Bad Request: Expecting GET');
		}
		$this->format->data($return, $data_format);
	}

	public function get_parish()
	{
		if ($this->input->method(TRUE) == 'GET') {
			$params = $this->input->get(NULL, TRUE);
			$data_format = $params['format'] ?? 'json' ;

			$parish = $this->app_model->get_parish($params['parish_id']);
			if ($parish) {
				$data = $parish;
				$return  = array('status' => true, 'message' => 'Valid Request', 'data' => $data);

			} else {
				$return  = array('status' => false, 'message' => 'No parish found');
			}
			
		} else {
			$return  = array('status' => false, 'message' => 'Bad Request: Expecting GET');
		}
		$this->format->data($return, $data_format);
	}

	public function get_villages()
	{
		if ($this->input->method(TRUE) == 'GET') {
			$params = $this->input->get(NULL, TRUE);
			$data_format = $params['format'] ?? 'json' ;

			$villages = $this->app_model->get_villages();
			if ($villages) {
				$data = $villages;
				$return  = array('status' => true, 'message' => 'Valid Request', 'data' => $data);

			} else {
				$return  = array('status' => false, 'message' => 'No villages found');
			}
			
		} else {
			$return  = array('status' => false, 'message' => 'Bad Request: Expecting GET');
		}
		$this->format->data($return, $data_format);
	}

	public function get_village()
	{
		if ($this->input->method(TRUE) == 'GET') {
			$params = $this->input->get(NULL, TRUE);
			$data_format = $params['format'] ?? 'json' ;

			$village = $this->app_model->get_village($params['village_id']);
			if ($village) {
				$data = $village;
				$return  = array('status' => true, 'message' => 'Valid Request', 'data' => $data);

			} else {
				$return  = array('status' => false, 'message' => 'No village found');
			}
			
		} else {
			$return  = array('status' => false, 'message' => 'Bad Request: Expecting GET');
		}
		$this->format->data($return, $data_format);
	}
















	public function get_app_list()
	{
		if ($this->input->method(TRUE) == 'GET') {
			$params = $this->input->get(NULL, TRUE);
			$data_format = $params['format'] ?? 'json' ;

			$app_list = $this->app_model->get_app_list();
			if ($app_list) {
				$data = [];
				foreach ($app_list as $list) {
					$data[] = $list->TABLE_NAME;
				}
				// $data = $app_list;
				$return  = array('status' => true, 'message' => 'Valid Request', 'data' => $data);

			} else {
				$return  = array('status' => false, 'message' => 'No app_list found');
			}
			
		} else {
			$return  = array('status' => false, 'message' => 'Bad Request: Expecting GET');
		}
		$this->format->data($return, $data_format);
	}



	public function get_app_lists()
	{
		if ($this->input->method(TRUE) == 'GET') {
			$params = $this->input->get(NULL, TRUE);
			$data_format = $params['format'] ?? 'json' ;

			$data['region'] = $this->app_model->get_regions() ?? [];
			$data['app_village'] = $this->app_model->get_villages() ?? [];
			$data['app_parish'] = $this->app_model->get_parishes() ?? [];
			$data['app_sub_county'] = $this->app_model->get_sub_counties() ?? [];
			$data['app_district'] = $this->app_model->get_districts() ?? [];
			$data['app_project'] = $this->app_model->get_projects() ?? [];
			$data['app_organisation'] = $this->app_model->get_organisations() ?? [];

			$return  = array('status' => true, 'message' => 'Valid Request', 'data' => $data);
			
		} else {
			$return  = array('status' => false, 'message' => 'Bad Request: Expecting GET');
		}
		$this->format->data($return, $data_format);
	}




	public function get_roles()
	{
		if ($this->input->method(TRUE) == 'GET') {
			$params = $this->input->get(NULL, TRUE);
			$data_format = $params['format'] ?? 'json' ;

			$roles = $this->app_model->get_admin_roles();
			if ($roles) {
				$data = $roles;
				$return  = array('status' => true, 'message' => 'Valid Request', 'data' => $data);

			} else {
				$return  = array('status' => false, 'message' => 'No regions found');
			}
			
		} else {
			$return  = array('status' => false, 'message' => 'Bad Request: Expecting GET');
		}
		$this->format->data($return, $data_format);
	}


	public function get_answer_types()
	{
		if ($this->input->method(TRUE) == 'GET') {
			$params = $this->input->get(NULL, TRUE);
			$data_format = $params['format'] ?? null ;

			$answer_types = $this->app_model->get_answer_types();
			if ($answer_types) {
				$data = $answer_types;
				$return  = array('status' => true, 'message' => 'Valid Request', 'data' => $data);

			} else {
				$return  = array('status' => false, 'message' => 'Form of given id is not existant');
			}
			
		} else {
			$return  = array('status' => false, 'message' => 'Bad Request: Expecting GET');
		}
		$this->format->data($return, $data_format);
	}

	public function get_fieldsets()
	{
		if ($this->input->method(TRUE) == 'GET') {
			$params = $this->input->get(NULL, TRUE);
			$data_format = $params['format'] ?? null ;

			$fieldsets = $this->app_model->get_fieldsets();
			if ($fieldsets) {

				$data = $fieldsets;
				$return  = array('status' => true, 'message' => 'Valid Request', 'data' => $data);

			} else {
				$return  = array('status' => false, 'message' => 'No responses returned');
			}
			
		} else {
			$return  = array('status' => false, 'message' => 'Bad Request: Expecting GET');
		}
		$this->format->data($return, $data_format);
	}



	public function get_charts()
	{
		if ($this->input->method(TRUE) == 'GET') {
			$params = $this->input->get(NULL, TRUE);
			$data_format = $params['format'] ?? null ;

			$charts = $this->app_model->get_charts();
			if ($charts) {
				foreach ($charts as $chart) {
					$chart->actual = $this->app_model->get_count_entries_by_form_ids(json_decode($chart->form_list), $chart->start_date, $chart->end_date);
					if ($chart->target > $chart->actual) {
						$chart->difference = $chart->target - $chart->actual;
					} else {
						$chart->difference = 0;
					}
					unset($chart->form_list);
					unset($chart->active);
				}
				$data = $charts;
				$return  = array('status' => true, 'message' => 'Valid Request', 'data' => $data);
			} else {
				$return  = array('status' => false, 'message' => 'No responses returned');
			}			
		} else {
			$return  = array('status' => false, 'message' => 'Bad Request: Expecting GET');
		}
		$this->format->data($return, $data_format);
	}



	public function get_forms_basic()
	{
		if ($this->input->method(TRUE) == 'GET') {
			$params = $this->input->get(NULL, TRUE);
			$data_format = $params['format'] ?? null ;

			$forms = $this->app_model->get_forms();
			if ($forms) {
				foreach ($forms as $form) {
					unset($form->question_list);
					$form->title_fields = json_decode($form->title_fields);
					$form->followup_prefill = json_decode($form->followup_prefill);
				}
				$data = $forms;
				$return  = array('status' => true, 'message' => 'Valid Request', 'data' => $data);

			} else {
				$return  = array('status' => false, 'message' => 'Form of given id is not existant');
			}
			
		} else {
			$return  = array('status' => false, 'message' => 'Bad Request: Expecting GET');
		}
		$this->format->data($return, $data_format);
	}




	public function get_responses()
	{
		if ($this->input->method(TRUE) == 'GET') {
			$params = $this->input->get(NULL, TRUE);
			$data_format = $params['format'] ?? null ;
			$responses = $this->app_model->get_responses($params['form_id']);
			if ($responses) {
				ini_set('memory_limit','512M');
				foreach ($responses as $response) {
					$response->response_list = json_decode($response->json_response);
				}
				$data = $responses;
				$return  = array('status' => true, 'message' => 'Valid Request', 'data' => $data);

			} else {
				$return  = array('status' => false, 'message' => 'No responses returned');
			}
			
		} else {
			$return  = array('status' => false, 'message' => 'Bad Request: Expecting GET');
		}
		$this->format->data($return, $data_format);
	}


	public function get_raw_responses_table()
	{
		if ($this->input->method(TRUE) == 'GET') {
			$params = $this->input->get(NULL, TRUE);
			$data_format = $params['format'] ?? NULL ;
			$params['form_id'] = $params['form_id'] ?? NULL;
			$params['entry_data'] = $params['entry_data'] ?? NULL;
			$params['startdate'] = $params['startdate'] ?? NULL;
			$params['enddate'] = $params['enddate'] ?? NULL;
			$params['project'] = $params['project'] ?? null;

			$form = $this->app_model->get_form($params['form_id']);
			if ($form) {
				$question_list = json_decode($form->question_list);
				
				if (count($question_list)) {
					// $table_header = {};
					$questions = $this->app_model->get_form_questions($question_list);
					foreach ($questions as $question) {
						unset($question->answer_type_id);
						unset($question->answer_values);
						unset($question->date_created);
						unset($question->active);
						$table_header['qn'.$question->question_id] = $question;
					}
					// $form->question_list = $questions ?? null;
					$form->question_list = $table_header ?? null;
				} else {
					$form->question_list = [];
				}

				// $responses = $this->app_model->get_responses();
				// $responses = $this->app_model->get_filtered_responses($params['form_id'], $params['entry_data'], $params['startdate'], $params['enddate']);
				if (isset($params['region_id'])) {
					// $responses = $this->app_model->get_filtered_responses($params['form_id'], $params['entry_data'], $params['startdate'], $params['enddate'], $params['region_id']);

					$village_responses = $this->app_model->get_filtered_responses($params['form_id'], $params['entry_data'], $params['startdate'], $params['enddate'], $params['region_id'], $params['project']);
					if ($village_responses) {
						$responses = $village_responses;
					} else {
						$parish_responses = $this->app_model->get_filtered_responses($params['form_id'], $params['entry_data'], $params['startdate'], $params['enddate'], $params['region_id'], $params['project'], 'parish_location_view');
						if ($parish_responses) {
							$responses = $parish_responses;
						} else {
							$sub_country_responses = $this->app_model->get_filtered_responses($params['form_id'], $params['entry_data'], $params['startdate'], $params['enddate'], $params['region_id'], $params['project'], 'sub_county_location_view');
							if ($sub_country_responses) {
								$responses = $sub_country_responses;
							} else {
								$district_responses = $this->app_model->get_filtered_responses($params['form_id'], $params['entry_data'], $params['startdate'], $params['enddate'], $params['region_id'], $params['project'], 'district_location_view');
								if ($district_responses) {
									$responses = $district_responses;
								} else {
									$responses = NULL;
								}
							}
						}
					}

				} else {
					$responses = $this->app_model->get_filtered_responses($params['form_id'], $params['entry_data'], $params['startdate'], $params['enddate'], NULL, $params['project']);
				}


				if ($responses) {
					ini_set('memory_limit','512M');
					foreach ($responses as $response) {
						if ($params['entry_data'] == 'baseline') {
							$response->json_response = str_replace('qn_', 'qn', $response->json_response);
							$response->entry = json_decode($response->json_response);
						} elseif ($params['entry_data'] == 'followup') {
							$response->recent_followup = str_replace('qn_', 'qn', $response->recent_followup);
							$response->entry = json_decode($response->recent_followup);
						}

						if (isset($response->entry)) {
							unset($response->entry->photo_file);
							unset($response->entry->photo);
							unset($response->entry->form_id);
							unset($response->entry->coordinates);
							unset($response->json_response);
							unset($response->json_followup);
							unset($response->recent_followup);
						}

					}
					$data['headers'] = $form->question_list;
					$data['responses'] = $responses;
					$return  = array('status' => true, 'message' => 'Valid Request', 'data' => $data);

				} else {
					$return  = array('status' => false, 'message' => 'No responses returned');
				}

			} else {
				$return  = array('status' => false, 'message' => 'Form of given id is not existant');
			}
		} else {
			$return  = array('status' => false, 'message' => 'Bad Request: Expecting GET');
		}
		$this->format->data($return, $data_format);
	}


	public function get_user_responses()
	{
		if ($this->input->method(TRUE) == 'GET') {
			$params = $this->input->get(NULL, TRUE);
			$data_format = $params['format'] ?? null ;

			$form_id = $params['form_id'] ?? null ;
			$responses = $this->app_model->get_user_responses($params['user_id'], $form_id);
			if ($responses) {
				foreach ($responses as $response) {
					$entry = explode('-', $response->entry_form_id);
					$response->rowid = $entry[3];
					$response->json_response = str_replace('qn_', 'qn', $response->json_response);
					$response->json_followup = str_replace('qn_', 'qn', $response->json_followup);
					$response->recent_followup = isset($response->recent_followup) ? str_replace('qn_', 'qn', $response->recent_followup) : [];
				}
				$data = $responses;
				$return  = array('status' => true, 'message' => 'Valid Request', 'data' => $data);

			} else {
				$return  = array('status' => false, 'message' => 'No responses returned');
			}
			
		} else {
			$return  = array('status' => false, 'message' => 'Bad Request: Expecting GET');
		}
		$this->format->data($return, $data_format);
	}


	public function get_region_responses()
	{
		if ($this->input->method(TRUE) == 'GET') {
			$params = $this->input->get(NULL, TRUE);
			$data_format = $params['format'] ?? 'json' ;
			$form_id = $params['form_id'] ?? null ;
			$followup = $params['followup'] ?? null ;
			$user_id = $params['user_id'] ?? null ;
			$mobile_format = $params['mobile_format'] ?? 0 ;
			$modified = $params['modified'] ?? null ;
			$project = $params['project'] ?? null;

			$responses = $this->app_model->get_region_responses($params['region_id'], $user_id, $form_id, $followup, $modified, $project);

			ini_set('memory_limit', '512M');
			if ($responses) {
				// if ($mobile_format) {
				// 	# code...
				// }
				foreach ($responses as $response) {
					$response->json_response = str_replace('qn_', 'qn', $response->json_response);
					$response->json_followup = str_replace('qn_', 'qn', $response->json_followup);
					$response->recent_followup = str_replace('qn_', 'qn', $response->recent_followup);
				}

				$return  = array('status' => true, 'message' => 'Valid Request', 'data' => $responses);
			} else {
				$return  = array('status' => false, 'message' => 'No responses returned');
			}			
		} else {
			$return  = array('status' => false, 'message' => 'Bad Request: Expecting GET');
		}
		$this->format->data($return, $data_format);
	}





	public function get_response()
	{
		if ($this->input->method(TRUE) == 'GET') {
			$params = $this->input->get(NULL, TRUE);
			$data_format = $params['format'] ?? null ;

			$response = $this->app_model->get_response($params['response_id']);
			if ($response) {
				$response->json_response = json_decode($response->json_response);
				$response->media_directory = base_url('uploads/');
				$data = $response;
				$return  = array('status' => true, 'message' => 'Valid Request', 'data' => $data);

			} else {
				$return  = array('status' => false, 'message' => 'No responses returned');
			}
			
		} else {
			$return  = array('status' => false, 'message' => 'Bad Request: Expecting GET');
		}
		$this->format->data($return, $data_format);
	}





	public function get_clean_response()
	{
		if ($this->input->method(TRUE) == 'GET') {
			$params = $this->input->get(NULL, TRUE);
			$data_format = $params['format'] ?? null ;

			$response_by_id = $this->app_model->get_response_by_entry_form_id($params['response_id']);
			if ($response_by_id) {
				$response = $response_by_id;
			} else {
				$village_response = $this->app_model->get_response($params['response_id']);
				if ($village_response) {
					$response = $village_response;
				} else {
				$parish_response = $this->app_model->get_response_parish($params['response_id']);
				if ($parish_response) {
					$response = $parish_response;
				} else {
					$sub_county_response = $this->app_model->get_response_sub_county($params['response_id']);
					if ($sub_county_response) {
						$response = $sub_county_response;
					} else {
						$district_response = $this->app_model->get_response_district($params['response_id']);
						if ($district_response) {
							$response = $district_response;
						} else {

							$response = NULL;
						}
					}
				}
				}
			}

			if ($response) {
				$response->json_response = json_decode($response->json_response);
				$response->recent_followup = json_decode($response->recent_followup ?? '[]');

				$form = $this->app_model->get_form($response->form_id);
				$form->question_list = json_decode($form->question_list);

				// $qn_ids = array();
				$qn_ids = (array) $form->question_list;
				$questions = $this->app_model->get_form_questions($qn_ids);
				foreach ($questions as $qn) {
					$qn_data['qn_'.$qn->question_id] = $qn->question;
					$qn_data['qn'.$qn->question_id] = $qn->question;
				}

				$baseline = (array)$response->json_response;
				$followup = (array)$response->recent_followup;

				foreach ($qn_data as $key => $value) {
					if (isset($qn_data[$key]) && isset($baseline[$key])) {
						$comp['baseline'][] = array('question' => $qn_data[$key] , 'response' => $baseline[$key]);
					}
					if (isset($qn_data[$key]) && isset($followup[$key])) {
						$comp['followup'][] = array('question' => $qn_data[$key] , 'response' => $followup[$key]);					
					}
					if (isset($followup['creator_id'])) {
						$comp['followup']['followup_creator'] = $this->custom->creator_name($followup['creator_id']);
					}					
				}

				if (isset($params['followups']) && $params['followups'] == 1) {
					$response->json_followup = json_decode($response->json_followup ?? '[]');
					if (count($response->json_followup)) {
						$i = 0;
						foreach ($response->json_followup as $json_followup) {
							$entry_followup = (array)$json_followup;
							foreach ($qn_data as $key => $value) {
								if (isset($qn_data[$key]) && isset($entry_followup[$key])) {
									$comp['followups'][$i][] = array('question' => $qn_data[$key] , 'response' => $entry_followup[$key]);
								}
							}

							if (isset($entry_followup['creator_id'])) {
								$comp['followups'][$i]['followup_creator'] = $this->custom->creator_name($entry_followup['creator_id']);
							}							

							if (isset($entry_followup['photo'])) {
								$photo_mobile_path = explode('/', $entry_followup['photo']);
								$filename = end($photo_mobile_path);
								$comp['followups'][$i]['photo'] = $filename;
								// $comp['followups'][$i]['photo'] = $entry_followup['photo'];
							}
							$i++;
						}
					} else {
						$comp['followups'] = array();
					}
				}

				$data['id'] = $response->response_id;
				$data['form_id'] = $response->form_id;
				$data['title'] = $response->title;
				$data['sub_title'] = $response->sub_title;
				$data['followup_count'] = $response->followup_count;
				$data['comp'] = $comp;
				$data['geo'] = $response->json_response->coordinates ?? null;
				$data['baseline']['photo_file'] = $response->json_response->photo_file ?? null;
				$data['followup']['photo_file'] = $response->recent_followup->photo_file ?? null;
				$data['baseline_creator'] = $response->first_name.' '.$response->last_name;
				// $data['followup_creator'] = $response->first_name.' '.$response->last_name;
				$data['media_directory'] = base_url('uploads/');
				$data['check_mongo'] = 0;
				$return  = array('status' => true, 'message' => 'Valid Request', 'data' => $data);

			} else {
				$return  = array('status' => false, 'message' => 'No responses returned');
			}
			
		} else {
			$return  = array('status' => false, 'message' => 'Bad Request: Expecting GET');
		}
		$this->format->data($return, $data_format);
	}


	

	public function get_table_data()
	{
		if ($this->input->method(TRUE) == 'GET') {
			$params = $this->input->get(NULL, TRUE);
			$data_format = $params['format'] ?? 'json' ;

			$table_data = $this->app_model->get_table_data($params['table_name']);
			if ($table_data) {
				$data = $table_data;
				$return  = array('status' => true, 'message' => 'Valid Request', 'data' => $data);
			} else {
				$return  = array('status' => false, 'message' => 'No table data returned');
			}			
		} else {
			$return  = array('status' => false, 'message' => 'Bad Request: Expecting GET');
		}
		$this->format->data($return, $data_format);
	}



	public function get_agg_data_responses()
	{
		if ($this->input->method(TRUE) == 'GET') {
			$params = $this->input->get(NULL, TRUE);
			$data_format = $params['format'] ?? 'json' ;

			$params['project'] = $params['project'] ?? NULL ;

			$form = $this->app_model->get_form($params['form_id']);
			if ($form) {
				$question_list = json_decode($form->question_list);
				$questions = $this->app_model->get_agg_form_questions($question_list);
				if ($questions) {
					// Iterate through question list to creater headers
					$sub_header = [];
					foreach ($questions as $qn) {
						$answer_values = (is_null($qn->answer_values) || $qn->answer_values === 'null' ) ? ['Total'] : (array) json_decode($qn->answer_values);
						$main_header[] = (object) array('title' => $qn->question, 'colspan' => count($answer_values) ? count($answer_values) : 1);
						$sub_header = array_merge($sub_header, $answer_values);
						// Set default counter values
						foreach ($answer_values as $value) {
							$answer_counter['qn'.$qn->question_id][$value] = 0;
						}
						// Aggregatable questions list
						$qn_filter[] = 'qn'.$qn->question_id;
					}
					// $this->custom->print_t($answer_counter, true);

					// Aggregating body data
					ini_set('memory_limit','512M');
					// $agg_responses = $this->app_model->get_area_responses($params['form_id'], $params['field'], $params['field_id'], $params['startdate'], $params['enddate'], $params['data_type']);

					$village_responses = $this->app_model->get_area_responses($params['form_id'], $params['field'], $params['field_id'], $params['startdate'], $params['enddate'], $params['data_type'], $params['project']);
					if ($village_responses) {
						$agg_responses = $village_responses;
					} else {
						$parish_responses = $this->app_model->get_area_responses($params['form_id'], $params['field'], $params['field_id'], $params['startdate'], $params['enddate'], NULL, $params['project'], 'parish_location_view');
						if ($parish_responses) {
							$agg_responses = $parish_responses;
						} else {
							$sub_country_responses = $this->app_model->get_area_responses($params['form_id'], $params['field'], $params['field_id'], $params['startdate'], $params['enddate'], NULL, $params['project'], 'sub_county_location_view');
							if ($sub_country_responses) {
								$agg_responses = $sub_country_responses;
							} else {
								$district_responses = $this->app_model->get_area_responses($params['form_id'], $params['field'], $params['field_id'], $params['startdate'], $params['enddate'], NULL, $params['project'], 'district_location_view');
								if ($district_responses) {
									$agg_responses = $district_responses;
								} else {
									$agg_responses = NULL;
								}
							}
						}
					}

					if ($agg_responses) {
						$group_by = $params['group_by'] ?? 'village';
						// $this->custom->print_t($agg_responses, true);

						foreach ($agg_responses as $entry) {
							// Define location group array
							if (!isset($data_entry[$entry->{$group_by}])) {
								$data_entry[$entry->{$group_by}]['data'] = $answer_counter;
								$data_entry[$entry->{$group_by}]['entry_count'] = 0;
							}
							// Build aggregated data rows
							if ($params['data_type'] == 'baseline') {
								$json_response = $entry->json_response ?? '{}';
							} elseif ($params['data_type'] == 'followup') {
								$json_response = $entry->recent_followup ?? '{}';
							}
							// echo 'Data: '.$json_response.'<br>';
							$all_entry_data = json_decode(str_replace('qn_', 'qn', $json_response), true);
							foreach ($qn_filter as $filter) {
								if (isset($all_entry_data[$filter])) {
									if (is_numeric($all_entry_data[$filter])) {
										if (isset($data_entry[$entry->{$group_by}]['data'][$filter]['Total'])) {
											$data_entry[$entry->{$group_by}]['data'][$filter]['Total'] += $all_entry_data[$filter];
										}										
									} else {
										if (is_array($all_entry_data[$filter]) || is_object($all_entry_data[$filter])) {
											foreach ($all_entry_data[$filter] as $item) {
												if (isset($data_entry[$entry->{$group_by}]['data'][$filter][$item])) {
													$data_entry[$entry->{$group_by}]['data'][$filter][$item] += 1;
												}												
											}
										} else {
											if (isset($data_entry[$entry->{$group_by}]['data'][$filter][$all_entry_data[$filter]])) {
												$data_entry[$entry->{$group_by}]['data'][$filter][$all_entry_data[$filter]] += 1;
											} else {
												# code...
											}
										}
									}
								}
							}
							$data_entry[$entry->{$group_by}]['entry_count']++;
						}
						// $this->custom->print_t($data_entry, true);
						$data['header']['main_header'] = $main_header;
						$data['header']['sub_header']  = $sub_header;
						$data['aggregated'] = $data_entry;
						$return  = array('status' => true, 'message' => 'Valid Request', 'data' => $data);
					} else {
						$return  = array('status' => false, 'message' => '0 entries found returned');
					}
				} else {
					$return  = array('status' => false, 'message' => 'No Question List found in Form');
				}
			} else {
				$return  = array('status' => false, 'message' => 'Form does not exist');
			}
		} else {
			$return  = array('status' => false, 'message' => 'Bad Request: Expecting GET');
		}
		$this->format->data($return, $data_format);
	}



	// public function get_agg_responses()
	// {
	// 	if ($this->input->method(TRUE) == 'GET') {
	// 		$params = $this->input->get(NULL, TRUE);
	// 		$data_format = $params['format'] ?? 'json' ;

	// 		$form = $this->app_model->get_form($params['form_id']);
	// 		if ($form) {

	// 			$question_list = json_decode($form->question_list);
	// 			$questions = $this->app_model->get_form_questions($question_list);

	// 			foreach ($questions as $question) {
	// 				$answer_type = $this->app_model->get_answer_type($question->answer_type_id);
	// 				$question->answer_type = $answer_type->machine_name;
	// 				$question->answer_values = json_decode($question->answer_values);

	// 				$qn_data['qn_'.$question->question_id] = $question->question;
	// 				$qn_data['qn'.$question->question_id] = $question->question;
	// 			}
				
	// 			$agg_responses = $this->app_model->get_area_responses($params['form_id'], $params['field'], $params['field_id'], $params['startdate'], $params['enddate']);
	// 			if ($agg_responses) {
	// 				$group_by = $params['group_by'] ?? 'village';
	// 				foreach ($agg_responses as $response) {
	// 					$response->json_response = json_decode($response->json_response);
	// 					$groups[$response->{$group_by}][] = $response->json_response;
	// 				}

	// 				$responses = $this->app_model->get_agg_responses($params['form_id']);
	// 				if ($responses) {
	// 					foreach ($responses as $response) {
	// 						$response->response_list = json_decode($response->json_response);
	// 					}


	// 					$qn = [];
	// 					foreach ($questions as $question) {
	// 						if ($question->answer_type == 'radio' || $question->answer_type == 'checkbox') {
	// 							foreach ($question->answer_values as $value) {
	// 								$qn['qn_'.$question->question_id][$value] = 0;
	// 							}
	// 						} elseif ($question->answer_type == 'number') {
	// 							$qn['qn_'.$question->question_id]['Totals'] = 0;
	// 						}
	// 					}
	// 					$reset_qn = $qn;


	// 					$agg = [];
	// 					foreach ($groups as $area => $responses) {
	// 						// reset qn
	// 						$qn = $reset_qn;
	// 						foreach ($responses as $response) {
	// 							foreach ($qn as $key => $value) {
	// 								$answers = (array) $response;
	// 								if (!array_key_exists('Totals', $qn[$key])) {
	// 									if (isset($answers[$key])) { // Check if index is defined
	// 										if (is_array($answers[$key])) {
	// 											foreach ($answers[$key] as $value2) {
	// 												$item_value = $qn[$key][$value2] ?? 0;
	// 												$qn[$key][$value2] = $item_value + 1;
	// 											}
	// 										} else {
	// 											$item_value = $qn[$key][$answers[$key]] ?? 0;
	// 											$qn[$key][$answers[$key]] = $item_value + 1;
	// 										}
	// 									} else {
	// 										// Do nothing for now
	// 									}
	// 								} else {
	// 									$item_value = $qn[$key]['Totals'];
	// 									if (isset($answers[$key])) {
	// 										$qn[$key]['Totals'] = $item_value + $answers[$key];
	// 									} else {
	// 										// Do nothing for now
	// 									}
	// 								}
	// 							}

	// 						}
	// 						// $agg[] = $qn;
	// 						// $agg[$area] = $qn;
	// 						// $agg[$area]['Entries'] = count($responses);
	// 						$entries = array('Entries' => array('Count' => count($responses)));
	// 						$agg[$area] = array_merge($entries, $qn);
	// 					}

	// 					$thead_titles[] = array('col_title' => 'Households', 'colspan' => 1);
	// 					$thead_answers[] = 'Entries';
	// 					foreach ($reset_qn as $key => $qn) {
	// 						$thead_titles[] = array('col_title' => $qn_data[$key], 'colspan' => count((array)$qn));
	// 						if (is_array($qn)) {
	// 							foreach ($qn as $key => $value) {
	// 								$thead_answers[] = $key;
	// 							}
	// 						} else {
	// 							$thead_answers[] = 'Total';
	// 						}
	// 					}

	// 					$data['thead_titles'] = $thead_titles;
	// 					$data['thead_answers'] = $thead_answers;
	// 					$data['tbody_responses'] = $agg;

	// 					$return  = array('status' => true, 'message' => 'Valid Request', 'data' => $data);

	// 				} else {
	// 					$return  = array('status' => false, 'message' => 'No responses returned');
	// 				}
	// 			} else {
	// 				$return  = array('status' => false, 'message' => 'No responses returned for this area');
	// 			}

	// 		} else {
	// 			$return  = array('status' => false, 'message' => 'Form not found');				
	// 		}
	// 	} else {
	// 		$return  = array('status' => false, 'message' => 'Bad Request: Expecting GET');
	// 	}
	// 	$this->format->data($return, $data_format);
	// }



	public function get_responses_geodata()
	{
		if ($this->input->method(TRUE) == 'GET') {
			$params = $this->input->get(NULL, TRUE);
			$data_format = $params['format'] ?? null ;
			$form_id = $params['form_id'] ?? null;

			// $responses = $this->app_model->get_form_responses($form_id);
			$responses = $this->app_model->get_form_map_responses($form_id, false);
			if ($responses) {
				ini_set('memory_limit','512M');
				foreach ($responses as $response) {
					$coords = explode(',', $response->coordinates);
					if (isset($coords[0]) && isset($coords[1])) {
						$response->coordinates = array('lat' => $coords[0],'lon' => $coords[1]);
					}
					$project = $response->project ?? 'projectless';
					$entry[$project][] = $response;
					// $response_list = json_decode($response->json_response);
					// $coords = explode(',', $response_list->coordinates);
					// if (isset($coords[0]) && isset($coords[1])) {
					// 	$response->coordinates = array('lat' => $coords[0],'lon' => $coords[1]);
					// 	unset($response->json_response);
					// 	unset($response->json_followup);
					// } else {
					// 	unset($response);
					// }
				}

				// $data = $responses;
				$data = array_values($entry);
				$return  = array('status' => true, 'message' => 'Valid Request', 'data' => $data);

			} else {
				$return  = array('status' => false, 'message' => 'No responses returned');
			}
			
		} else {
			$return  = array('status' => false, 'message' => 'Bad Request: Expecting GET');
		}
		$this->format->data($return, $data_format);
	}





	public function get_responses_summary()
	{
		if ($this->input->method(TRUE) == 'GET') {
			$params = $this->input->get(NULL, TRUE);
			$data_format = $params['format'] ?? null ;
			$form_id = $params['form_id'] ?? null;
			$region_id = $params['region_id'] ?? null;

			// $responses = $this->app_model->get_form_responses($form_id);
			// $responses = $this->app_model->get_form_responses_summary($form_id);

			if (!is_null($region_id)) {

				$village_responses = $this->app_model->get_form_responses_summary($form_id, $region_id);
				if ($village_responses) {
					$responses = $village_responses;
				} else {
					$parish_responses = $this->app_model->get_form_responses_summary_parish($form_id, $region_id);
					if ($parish_responses) {
						$responses = $parish_responses;
					} else {
						$sub_country_responses = $this->app_model->get_form_responses_summary_sub_county($form_id, $region_id);
						if ($sub_country_responses) {
							$responses = $sub_country_responses;
						} else {
							$district_responses = $this->app_model->get_form_responses_summary_district($form_id, $region_id);
							if ($district_responses) {
								$responses = $district_responses;
							} else {
								$responses = [];
							}
						}
					}
				}

			} else {
				$responses = $this->app_model->get_form_responses_summary($form_id);
			}
			
			if ($responses) {
				// ini_set('memory_limit','512M');
				$data = $responses;
				$return  = array('status' => true, 'message' => 'Valid Request', 'data' => $data);

			} else {
				$return  = array('status' => false, 'message' => 'No responses returned');
			}
			
		} else {
			$return  = array('status' => false, 'message' => 'Bad Request: Expecting GET');
		}
		$this->format->data($return, $data_format);
	}




	public function clean_responses_entry_ids()
	{
		if ($this->input->method(TRUE) == 'GET') {
			$params = $this->input->get(NULL, TRUE);
			$data_format = $params['format'] ?? null ;
			$form_id = $params['form_id'] ?? null;

			$responses = $this->app_model->get_form_responses($form_id);
			if ($responses) {

				$region['Central'] = 'C';
				$region['Eastern'] = 'E';
				$region['South Western'] = 'SW';
				$region['West Nile'] = 'WN';

				foreach ($responses as $response) {
					$entry = explode('-', $response->entry_form_id);
					if (count($entry) == 1) {
						$user = sprintf("%04d", $response->creator_id);
						$entry_form_id = 'AWS-'.$region[$response->creator_region].'-U'.$user.'-'.$response->entry_form_id;
						$data['entry_form_id'] = $entry_form_id;

						$result = $this->app_model->update_response($response->response_id, $data);
						$counter = 1;
						if ($result) {
							echo $counter.'. entry_form_id:'.$entry_form_id.' response_id:'.$response->response_id.'<br>';
							$counter = $counter + 1;
						}
					}
				}


			} else {
				$return  = array('status' => false, 'message' => 'No responses returned');
			}
			
		} else {
			$return  = array('status' => false, 'message' => 'Bad Request: Expecting GET');
		}
		$this->format->data($return, $data_format);
	}






	public function commit_form()
	{
		if ($this->input->method(TRUE) == 'POST') {
			$params = $this->input->post(NULL, TRUE);
			$data_format = $params['format'] ?? 'json' ;

			$rows = $this->app_model->check_response($params['entry_form_id'], $params['creator_id']);
			if ($rows == 0) {

				// $json_response = $params['json_response'];
				// if (isset($params['photo_data']['filename'])) {
				// 	// Get file format 
				// 	$split_filename = explode('.', $params['photo_data']['filename']);
				// 	// $format = end($split_filename);

				// 	$base64_string = $params['photo_data']['photo_base64'];
				// 	$base64_string = trim($base64_string);

				// 	// $base64_string = str_replace('data:image/'.$format.';base64,', '', $base64_string);
				// 	$base64_string = str_replace('data:image/jpeg;base64,', '', $base64_string);
				// 	$base64_string = str_replace('[removed]', '', $base64_string);
				// 	$base64_string = str_replace(' ', '+', $base64_string);

				// 	$file_path = './uploads/'.$params['photo_data']['filename'];
				// 	$decoded = base64_decode($base64_string);
				// 	file_put_contents($file_path, $decoded);

				// 	// echo '<img src="http://127.0.0.1/aws-api/uploads/'.$params['photo_data']['filename'].'">';
				// 	$json_response = json_decode($json_response);
				// 	$json_response->photo_file = $params['photo_data']['filename'];
				// 	$json_response = json_encode($json_response);
				// }

				$form_data['form_id'] = $params['form_id'];
				$form_data['entry_form_id'] = $params['entry_form_id'];
				$form_data['creator_id'] = $params['creator_id'];
				$form_data['title'] = $params['title'];
				$form_data['sub_title'] = $params['sub_title'];
				// $form_data['json_response'] = $json_response;
				$form_data['json_response'] = $params['json_response'];
				$form_data['json_followup'] = $params['json_followup'];
				$form_data['date_created'] = date('Y-m-d H:i:s');
				$form_data['date_modified'] = date('Y-m-d H:i:s');
				$form_data['active'] = 1;

				$response_id = $this->app_model->create_response($form_data);
				$data = array('response_id' => $response_id);
				$return  = array('status' => true, 'message' => 'Valid Request', 'data' => $data);
			} else {
				$return  = array('status' => false, 'message' => 'Entry already exists');
			}
			
		} else {
			$return  = array('status' => false, 'message' => 'Bad Request: Expecting POST');
		}
		$this->format->data($return, $data_format);
	}


	public function commit_base64_file()
	{
		if ($this->input->method(TRUE) == 'POST') {
			$params = $this->input->post(NULL, TRUE);
			$data_format = $params['format'] ?? 'json' ;

			if (isset($params['photo_base64'])) {
				$response = $this->app_model->get_response_by_entry_form_id($params['entry_form_id']);
				if ($response) {

					$base64_string = $params['photo_base64'];
					$base64_string = trim($base64_string);

					$base64_string = str_replace('data:image/jpeg;base64,', '', $base64_string);
					$base64_string = str_replace('[removed]', '', $base64_string);
					$base64_string = str_replace(' ', '+', $base64_string);

					$file_path = './uploads/'.$params['filename'];
					$decoded = base64_decode($base64_string);
					file_put_contents($file_path, $decoded);

					$this->media->resize_photo($file_path, 'large');

					// Update the json_response field with filename
					if ($params['target'] == 'json_response') {
						$json_response = json_decode($response->json_response);
						$json_response->photo_file = $params['filename'];
						$response->json_response = json_encode($json_response);
						$form_data['json_response'] = $response->json_response;	

					} else if ($params['target'] == 'json_followup') {
						$json_followup = json_decode($response->json_followup);
						$keys = array_keys($json_followup);
						$last_key = end($keys);
						$json_followup[$last_key]->photo_file = $params['filename'];
						$response->json_followup = json_encode($json_followup);
						$form_data['json_followup'] = $response->json_followup;	
					}

					$update_status = $this->app_model->app_update_response($params['entry_form_id'], $form_data);
					$data = array('response_status' => $update_status);
					$return  = array('status' => true, 'message' => 'Valid Request', 'data' => $data);

				} else {
					$return  = array('status' => false, 'message' => 'Entry doesnt exist');
				}

			} else {
				$return  = array('status' => false, 'message' => 'No encoded file string');
			}
			
		} else {
			$return  = array('status' => false, 'message' => 'Bad Request: Expecting POST');
		}
		$this->format->data($return, $data_format);			
	}



	public function commit_followup_form()
	{
		if ($this->input->method(TRUE) == 'POST') {
			$params = $this->input->post(NULL, TRUE);
			$data_format = $params['format'] ?? 'json' ;

			$response = $this->app_model->get_response_by_entry_form_id($params['entry_form_id']);
			if ($response) {
				// Add creator id to question json followup
				$followup = json_decode($params['json_followup']);
				$followup->creator_id = $params['fu_user_id'];
				// Add new followup to existing followup
				$json_followup = json_decode($response->json_followup, true);
				$json_followup[] = $followup;

				$form_data['fu_creator_id'] = $params['fu_user_id'];
				$form_data['json_followup'] = json_encode($json_followup);
				$form_data['date_modified'] = date('Y-m-d H:i:s');

				$update_status = $this->app_model->app_update_response($params['entry_form_id'], $form_data);
				$data = array('followup_status' => $update_status);
				$return  = array('status' => true, 'message' => 'Valid Request', 'data' => $data);
			} else {
				$return  = array('status' => false, 'message' => 'No responses returned');
			}

		} else {
			$return  = array('status' => false, 'message' => 'Bad Request: Expecting POST');
		}
		$this->format->data($return, $data_format);
	}






	public function edit_entry()
	{
		if ($this->input->method(TRUE) == 'POST') {
			$params = $this->input->post(NULL, TRUE);
			$data_format = $params['format'] ?? 'json' ;

			$response = $this->app_model->get_response($params['entry_id']);
			if ($response) {
				if ($params['target'] == 'baseline') {

					$json_response = json_decode($response->json_response, true);
					$response = (array) json_decode($params['form_data']);
					$json_response = array_replace($json_response, $response);
					$form_data['json_response'] = json_encode($json_response);

				} elseif ($params['target'] == 'followup') {

					$json_followup = json_decode($response->json_followup, true);
					$last_el = count($json_followup) - 1;
					$recent_followup = $json_followup[$last_el];
					$response = (array) json_decode($params['form_data']);
					$json_followup[$last_el] = array_replace($recent_followup, $response);
					$form_data['json_followup'] = json_encode($json_followup);

				}
				$form_data['date_modified'] = date('Y-m-d H:i:s');

				$update_status = $this->app_model->update_response($params['entry_id'], $form_data);
				// print_r($form_data); die();

				if ($update_status) {
					$return  = array('status' => true, 'message' => 'Valid Request', 'data' => $params);
				} else {
					$return  = array('status' => false, 'message' => 'Changes were not saved');
				}
			} else {
				$return  = array('status' => false, 'message' => 'No responses returned');
			}
		} else {
			$return  = array('status' => false, 'message' => 'Bad Request: Expecting POST');
		}
		$this->format->data($return, $data_format);
	}








	public function create_question()
	{
		if ($this->input->method(TRUE) == 'POST') {
			$params = $this->input->post(NULL, TRUE);
			$data_format = $params['format'] ?? 'json' ;

			if ($params['answer_type_id'] == 7) {
				if ($params['answer_values'] == '{"db_table":"app_district"}') { $question_id = 4; }
				elseif ($params['answer_values'] == '{"db_table":"app_sub_county"}') { $question_id = 7; } 
				elseif ($params['answer_values'] == '{"db_table":"app_parish"}') { $question_id = 8; }
				elseif ($params['answer_values'] == '{"db_table":"app_village"}') { $question_id = 9; }
				elseif ($params['answer_values'] == '{"db_table":"app_project"}') { $question_id = 148; }
				elseif ($params['answer_values'] == '{"db_table":"app_organisation"}') { $question_id = 2; }
			} else {
				$form_data['question'] = $params['question'];
				$form_data['answer_type_id'] = $params['answer_type_id'];
				$form_data['answer_values'] = empty($params['answer_values']) ? NULL : $params['answer_values'] ;
				$form_data['date_created'] = date('Y-m-d H:i:s');
				$form_data['active'] = 1;
				$question_id = $this->app_model->create_question($form_data);
			}

			if ($question_id) {
                                $question = $this->app_model->get_question($question_id);
				$answer_type = $this->app_model->get_answer_type($question->answer_type_id);
				$question->answer_type = $answer_type->machine_name;
				$question->answer_values = json_decode($question->answer_values);
				$data = $question;
				$return  = array('status' => true, 'message' => 'Valid Request', 'data' => $data);

				// Add question into form
				if (isset($params['form_id'])) {
					$this->add_question_to_form($params['form_id'], $question_id);
				}				

			} else {
				$return  = array('status' => false, 'message' => 'Question was not saved');
			}
		} else {
			$return  = array('status' => false, 'message' => 'Bad Request: Expecting POST');
		}
		$this->format->data($return, $data_format);
	}

	public function create_library_question(){
		                if ($this->input->method(TRUE) == 'POST') {
                       $params = $this->input->post(NULL, TRUE);
                        $data_format = $params['format'] ?? 'json' ;

                        if ($params['answer_type_id'] == 7) {
                                if ($params['answer_values'] == '{"db_table":"app_district"}') { $question_id = 4; }
                                elseif ($params['answer_values'] == '{"db_table":"app_sub_county"}') { $question_id = 7; }
                                elseif ($params['answer_values'] == '{"db_table":"app_parish"}') { $question_id = 8; }
                                elseif ($params['answer_values'] == '{"db_table":"app_village"}') { $question_id = 9; }
                                elseif ($params['answer_values'] == '{"db_table":"app_project"}') { $question_id = 148; }
                                elseif ($params['answer_values'] == '{"db_table":"app_organisation"}') { $question_id = 2; }
                        } else {
                                $form_data['question'] = $params['question'];
                                $form_data['answer_type_id'] = $params['answer_type_id'];
                                $form_data['answer_values'] = empty($params['answer_values']) ? NULL : $params['answer_values'] ;
                                $form_data['date_created'] = date('Y-m-d H:i:s');
                                $form_data['active'] = 1;
				$question_id = $this->app_model->create_library_question($form_data);
                        }

                        if ($question_id) {
                                $question = $this->app_model->get_library_question($question_id);
                                $answer_type = $this->app_model->get_answer_type($question->answer_type_id);
                                $question->answer_type = $answer_type->machine_name;
                                $question->answer_values = json_decode($question->answer_values);
                                $data = $question;
                                $return  = array('status' => true, 'message' => 'Valid Request', 'data' => $data);

			} else {
				$return  = array('status' => false, 'message' => 'Question was not saved');				
    			}
                } else {
                        $return  = array('status' => false, 'message' => 'Bad Request: Expecting POST');
                }
                $this->format->data($return, $data_format);

	}


	public function update_question()
	{
		if ($this->input->method(TRUE) == 'POST') {
			$params = $this->input->post(NULL, TRUE);
			$data_format = $params['format'] ?? 'json' ;

			$form_data['question'] = $params['question'];
			$form_data['answer_type_id'] = $params['answer_type_id'];
			$form_data['answer_values'] = empty($params['answer_values']) ? NULL : $params['answer_values'];

			$update_status = $this->app_model->update_question($params['question_id'], $form_data);
			if ($update_status) {
				$new_question = $this->app_model->get_question($params['question_id']);
				if ($new_question) {
					$return  = array('status' => true, 'message' => 'Valid Request', 'data' => $new_question);
				} else {
					$return  = array('status' => false, 'message' => 'Question does not exist');
				}
			} else {
				$return  = array('status' => false, 'message' => 'Question changes were not submitted');
			}
			
		} else {
			$return  = array('status' => false, 'message' => 'Bad Request: Expecting POST');
		}
		$this->format->data($return, $data_format);
	}



	public function create_form()
	{
		if ($this->input->method(TRUE) == 'POST') {
			$params = $this->input->post(NULL, TRUE);
			$data_format = $params['format'] ?? 'json' ;

			$form_data['category_id'] = $params['category_id'] ?? 1;
			$form_data['title'] = $params['title'];
			$form_data['question_list'] = $params['question_list'] ?? [];
			$form_data['title_fields'] = $params['title_fields'] ?? json_encode(array('entry_title'=>[], 'entry_sub_title'=>[]));
			$form_data['is_geotagged'] = $params['is_geotagged'];
			$form_data['is_photograph'] = $params['is_photograph'];
			$form_data['is_followup'] = $params['is_followup'];
			$form_data['followup_prefill'] = $params['followup_prefill'] ?? [];
			$form_data['date_created'] = date('Y-m-d H:i:s');
			$form_data['date_modified'] = date('Y-m-d H:i:s');
			$form_data['creator_id'] = 1;
			$form_data['is_publish'] = $params['is_publish'];
			$form_data['active'] = 1;

			$form_id = $this->app_model->create_form($form_data);
			if ($form_id) {
				$form = $this->app_model->get_form($form_id);
				$return  = array('status' => true, 'message' => 'Valid Request', 'data' => $form);
			} else {
				$return  = array('status' => false, 'message' => 'Form was not submitted');
			}
		} else {
			$return  = array('status' => false, 'message' => 'Bad Request: Expecting POST');
		}
		$this->format->data($return, $data_format);
	}



	public function create_user()
	{
		if ($this->input->method(TRUE) == 'POST') {
			$params = $this->input->post(NULL, TRUE);
			$data_format = $params['format'] ?? 'json' ;

			$form_data['first_name'] = $params['first_name'];
			$form_data['last_name'] = $params['last_name'];
			$form_data['email'] = $params['email'];
			$form_data['password'] = $params['password'];
			$form_data['role_id'] = $params['role_id'];
			$form_data['region_id'] = $params['region_id'];
			$form_data['date_created'] = date('Y-m-d H:i:s');
			$form_data['active'] = 1;

			$user_id = $this->app_model->create_user($form_data);
			if ($user_id) {
				$user = $this->app_model->get_user($user_id);
				$return  = array('status' => true, 'message' => 'Valid Request', 'data' => $user);
			} else {
				$return  = array('status' => false, 'message' => 'Form was not submitted');
			}
		} else {
			$return  = array('status' => false, 'message' => 'Bad Request: Expecting POST');
		}
		$this->format->data($return, $data_format);
	}

	public function update_user()
	{
		if ($this->input->method(TRUE) == 'POST') {
			$params = $this->input->post(NULL, TRUE);
			$data_format = $params['format'] ?? 'json' ;

			if (isset($params['first_name'])) { $form_data['first_name'] = $params['first_name']; }
			if (isset($params['last_name'])) { $form_data['last_name'] = $params['last_name']; }
			if (isset($params['email'])) { $form_data['email'] = $params['email']; }
			if (isset($params['password'])) { $form_data['password'] = $params['password']; }
			if (isset($params['role_id'])) { $form_data['role_id'] = $params['role_id']; }
			if (isset($params['region_id'])) { $form_data['region_id'] = $params['region_id']; }
			if (isset($params['active'])) { $form_data['active'] = $params['active']; }

			if (isset($params['new_password']) && $params['new_password'] == $params['confirm_password']) 
			{ 
				$form_data['password'] = $params['new_password']; 
			}


			$user_id = $this->app_model->update_user($params['user_id'], $form_data);
			if ($user_id) {
				$user = $this->app_model->get_user($user_id);
				$return  = array('status' => true, 'message' => 'Valid Request', 'data' => $user);
			} else {
				$return  = array('status' => false, 'message' => 'Form was not submitted');
			}
		} else {
			$return  = array('status' => false, 'message' => 'Bad Request: Expecting POST');
		}
		$this->format->data($return, $data_format);
	}

	public function change_password()
	{
		if ($this->input->method(TRUE) == 'POST') {
			$params = $this->input->post(NULL, TRUE);
			$data_format = $params['format'] ?? 'json' ;

			if ($params['new_password'] == $params['confirm_password']) {
				$form_data['old_password'] = $params['old_password'];
				$form_data['new_password'] = $params['new_password'];
				$form_data['confirm_password'] = $params['confirm_password'];

				$user = $this->app_model->get_user($params['user_id']);
				if ($params['old_password'] == $user->password) {
					$user_id = $this->app_model->update_user($params['user_id'], $form_data);
					if ($user_id) {
						$user = $this->app_model->get_user($user_id);
						$return  = array('status' => true, 'message' => 'Valid Request', 'data' => $user);
					} else {
						$return  = array('status' => false, 'message' => 'Form was not submitted');
					}
				} else {
					$return  = array('status' => false, 'message' => 'Old Password is incorrect');
				}

			} else {
				$return  = array('status' => false, 'message' => 'Passwords dont match');
			}

		} else {
			$return  = array('status' => false, 'message' => 'Bad Request: Expecting POST');
		}
		$this->format->data($return, $data_format);
	}


	public function update_form()
	{
		if ($this->input->method(TRUE) == 'POST') {
			$params = $this->input->post(NULL, TRUE);
			$data_format = $params['format'] ?? 'json' ;

			// $form_data['category_id'] = $params['category_id'] ?? 1;
			if(isset($params['title'])) {
				$form_data['title'] = $params['title'];
			}
			if(isset($params['question_list'])) {
				$form_data['question_list'] = $params['question_list'] ?? [];
			}
			if(isset($params['title_fields'])) {
				$form_data['title_fields'] = $params['title_fields'] ?? [];
			}
			if(isset($params['is_geotagged'])) {
				$form_data['is_geotagged'] = $params['is_geotagged'];
			}
			if(isset($params['is_photograph'])) {
				$form_data['is_photograph'] = $params['is_photograph'];
			}
			if(isset($params['is_followup'])) {
				$form_data['is_followup'] = $params['is_followup'];
			}
			if(isset($params['followup_prefill'])) {
				$form_data['followup_prefill'] = $params['followup_prefill'] ?? [];
			}
			if(isset($params['is_publish'])) {
				$form_data['is_publish'] = $params['is_publish'];
			}
			$form_data['date_modified'] = date('Y-m-d H:i:s');

			$update_status = $this->app_model->update_form($params['form_id'], $form_data);
			if ($update_status) {
				$form = $this->app_model->get_form($params['form_id']);
				$return  = array('status' => true, 'message' => 'Valid Request', 'data' => $form);
			} else {
				$return  = array('status' => false, 'message' => 'Form was not submitted');
			}
		} else {
			$return  = array('status' => false, 'message' => 'Bad Request: Expecting POST');
		}
		$this->format->data($return, $data_format);
	}


	public function update_form_question_order()
	{
		if ($this->input->method(TRUE) == 'POST') {
			$params = $this->input->post(NULL, TRUE);
			$data_format = $params['format'] ?? 'json' ;
			$form_data['question_list'] = $params['question_list'] ?? null;

			$form_id = $this->app_model->update_form($params['form_id'], $form_data);
			if ($form_id) {
				$form = $this->app_model->get_form($params['form_id']);
				$return  = array('status' => true, 'message' => 'Valid Request', 'data' => $form);
			} else {
				$return  = array('status' => false, 'message' => 'Reorder failed');
			}
		} else {
			$return  = array('status' => false, 'message' => 'Bad Request: Expecting POST');
		}
		$this->format->data($return, $data_format);
	}



	public function create_admin_user()
	{
		if ($this->input->method(TRUE) == 'POST') {
			$params = $this->input->post(NULL, TRUE);
			$data_format = $params['format'] ?? 'json' ;

			$form_data['first_name'] = $params['first_name'];
			$form_data['last_name'] = $params['last_name'];
			$form_data['email'] = $params['email'];
			$form_data['password'] = $params['password'];
			$form_data['region_id'] = $params['region_id'];
			$form_data['role_id'] = $params['role_id'];
			$form_data['date_created'] = date('Y-m-d H:i:s');
			$form_data['active'] = 1;

			$user_id = $this->app_model->create_admin_user($form_data);
			if ($user_id) {
				$user = $this->app_model->get_admin_user($user_id);
				$return  = array('status' => true, 'message' => 'Valid Request', 'data' => $user);
			} else {
				$return  = array('status' => false, 'message' => 'Form was not submitted');
			}
		} else {
			$return  = array('status' => false, 'message' => 'Bad Request: Expecting POST');
		}
		$this->format->data($return, $data_format);
	}

	public function update_admin_user()
	{
		if ($this->input->method(TRUE) == 'POST') {
			$params = $this->input->post(NULL, TRUE);
			$data_format = $params['format'] ?? 'json' ;

			if (isset($params['first_name'])) { $form_data['first_name'] = $params['first_name']; }
			if (isset($params['last_name'])) { $form_data['last_name'] = $params['last_name']; }
			if (isset($params['email'])) { $form_data['email'] = $params['email']; }
			if (isset($params['password'])) { $form_data['password'] = $params['password']; }
			if (isset($params['region_id'])) { $form_data['region_id'] = $params['region_id']; }
			if (isset($params['role_id'])) { $form_data['role_id'] = $params['role_id']; }
			if (isset($params['active'])) { $form_data['active'] = $params['active']; }

			if (isset($params['new_password']) && $params['new_password'] == $params['confirm_password']) 
			{ 
				$form_data['password'] = $params['new_password']; 
			}

			$user_id = $this->app_model->update_admin_user($params['user_id'], $form_data);
			if ($user_id) {
				$user = $this->app_model->get_admin_user($user_id);
				$return  = array('status' => true, 'message' => 'Valid Request', 'data' => $user);
			} else {
				$return  = array('status' => false, 'message' => 'Form was not submitted');
			}
		} else {
			$return  = array('status' => false, 'message' => 'Bad Request: Expecting POST');
		}
		$this->format->data($return, $data_format);
	}

	public function change_admin_password()
	{
		if ($this->input->method(TRUE) == 'POST') {
			$params = $this->input->post(NULL, TRUE);
			$data_format = $params['format'] ?? 'json' ;

			if ($params['new_password'] == $params['confirm_password']) {
				$form_data['old_password'] = $params['old_password'];
				$form_data['new_password'] = $params['new_password'];
				$form_data['confirm_password'] = $params['confirm_password'];

				$user = $this->app_model->get_user($params['user_id']);
				if ($params['old_password'] == $user->password) {
					$user_id = $this->app_model->update_admin_user($params['user_id'], $form_data);
					if ($user_id) {
						$user = $this->app_model->get_admin_user($user_id);
						$return  = array('status' => true, 'message' => 'Valid Request', 'data' => $user);
					} else {
						$return  = array('status' => false, 'message' => 'Form was not submitted');
					}
				} else {
					$return  = array('status' => false, 'message' => 'Old Password is incorrect');
				}

			} else {
				$return  = array('status' => false, 'message' => 'Passwords dont match');
			}

		} else {
			$return  = array('status' => false, 'message' => 'Bad Request: Expecting POST');
		}
		$this->format->data($return, $data_format);
	}




	public function create_project()
	{
		if ($this->input->method(TRUE) == 'POST') {
			$params = $this->input->post(NULL, TRUE);
			$data_format = $params['format'] ?? 'json' ;
			
			$form_data['name'] = $params['name'];
			$form_data['active'] = 1;

			$response_id = $this->app_model->create_project($form_data);
			$data = $response_id;
			$return  = array('status' => true, 'message' => 'Valid Request', 'data' => $data);
			
		} else {
			$return  = array('status' => false, 'message' => 'Bad Request: Expecting POST');
		}
		$this->format->data($return, $data_format);
	}


	public function create_organisation()
	{
		if ($this->input->method(TRUE) == 'POST') {
			$params = $this->input->post(NULL, TRUE);
			$data_format = $params['format'] ?? 'json' ;
			
			$form_data['name'] = $params['name'];
			$form_data['active'] = 1;

			$response_id = $this->app_model->create_organisation($form_data);
			$data = $response_id;
			$return  = array('status' => true, 'message' => 'Valid Request', 'data' => $data);
			
		} else {
			$return  = array('status' => false, 'message' => 'Bad Request: Expecting POST');
		}
		$this->format->data($return, $data_format);
	}


	public function create_chart()
	{
		if ($this->input->method(TRUE) == 'POST') {
			$params = $this->input->post(NULL, TRUE);
			$data_format = $params['format'] ?? 'json' ;
			
			$params['date_created'] = date('Y-m-d H:i:s');
			$params['active'] = 1;
 			$form_data = $params;
			// print_r($form_data); die();

			$chart_id = $this->app_model->create_chart($form_data);
			$data = $chart_id;
			$return  = array('status' => true, 'message' => 'Valid Request', 'data' => $data);
			
		} else {
			$return  = array('status' => false, 'message' => 'Bad Request: Expecting POST');
		}
		$this->format->data($return, $data_format);
	}


	public function create_region()
	{
		if ($this->input->method(TRUE) == 'POST') {
			$params = $this->input->post(NULL, TRUE);
			$data_format = $params['format'] ?? 'json' ;
			
			$form_data['name'] = $params['name'];
			$form_data['code'] = $params['code'];
			$form_data['active'] = 1;

			$response_id = $this->app_model->create_region($form_data);
			$data = $response_id;
			$return  = array('status' => true, 'message' => 'Valid Request', 'data' => $data);
			
		} else {
			$return  = array('status' => false, 'message' => 'Bad Request: Expecting POST');
		}
		$this->format->data($return, $data_format);
	}


	public function create_district()
	{
		if ($this->input->method(TRUE) == 'POST') {
			$params = $this->input->post(NULL, TRUE);
			$data_format = $params['format'] ?? 'json' ;

			$data_batch = [];
			$params['name'] = json_decode($params['name']);
			foreach ($params['name'] as $name) {
				$district['name'] = ucfirst(strtolower($name));
				$district['region_id'] = $params['region_id'];
				$district['active'] = 1;
				$data_batch[] = $district;
			}

			$response_id = $this->app_model->create_district_batch($data_batch);
			$data = $response_id;
			$return  = array('status' => true, 'message' => 'Valid Request', 'data' => $data);
			
		} else {
			$return  = array('status' => false, 'message' => 'Bad Request: Expecting POST');
		}
		$this->format->data($return, $data_format);
	}


	public function create_sub_county()
	{
		if ($this->input->method(TRUE) == 'POST') {
			$params = $this->input->post(NULL, TRUE);
			$data_format = $params['format'] ?? 'json' ;

			$data_batch = [];
			$params['name'] = json_decode($params['name']);
			foreach ($params['name'] as $name) {
				$sub_county['name'] = ucfirst(strtolower($name));
				$sub_county['district_id'] = $params['district_id'];
				$sub_county['active'] = 1;
				$data_batch[] = $sub_county;
			}

			$response_id = $this->app_model->create_sub_county_batch($data_batch);
			$data = $response_id;
			$return  = array('status' => true, 'message' => 'Valid Request', 'data' => $data);
			
		} else {
			$return  = array('status' => false, 'message' => 'Bad Request: Expecting POST');
		}
		$this->format->data($return, $data_format);
	}


	public function create_parish()
	{
		if ($this->input->method(TRUE) == 'POST') {
			$params = $this->input->post(NULL, TRUE);
			$data_format = $params['format'] ?? 'json' ;

			$data_batch = [];
			$params['name'] = json_decode($params['name']);
			foreach ($params['name'] as $name) {
				$parish['name'] = ucfirst(strtolower($name));
				$parish['sub_county_id'] = $params['sub_county_id'];
				$parish['active'] = 1;
				$data_batch[] = $parish;
			}

			$response_id = $this->app_model->create_parish_batch($data_batch);
			$data = $response_id;
			$return  = array('status' => true, 'message' => 'Valid Request', 'data' => $data);
			
		} else {
			$return  = array('status' => false, 'message' => 'Bad Request: Expecting POST');
		}
		$this->format->data($return, $data_format);
	}


	public function create_village()
	{
		if ($this->input->method(TRUE) == 'POST') {
			$params = $this->input->post(NULL, TRUE);
			$data_format = $params['format'] ?? 'json' ;

			$data_batch = [];
			$params['name'] = json_decode($params['name']);
			foreach ($params['name'] as $name) {
				$village['name'] = ucfirst(strtolower($name));
				$village['parish_id'] = $params['parish_id'];
				$village['active'] = 1;
				$data_batch[] = $village;
			}

			$rows_inserted = $this->app_model->create_village_batch($data_batch);
			$data = $rows_inserted.' items added';
			$return  = array('status' => true, 'message' => 'Valid Request', 'data' => $data);
			
		} else {
			$return  = array('status' => false, 'message' => 'Bad Request: Expecting POST');
		}
		$this->format->data($return, $data_format);
	}



	public function update_project()
	{
		if ($this->input->method(TRUE) == 'POST') {
			$params = $this->input->post(NULL, TRUE);
			$data_format = $params['format'] ?? 'json' ;			
			$form_data = $params;

			$response_id = $this->app_model->update_project($params['project_id'], $form_data);
			$data = $response_id;
			$return  = array('status' => true, 'message' => 'Valid Request', 'data' => $data);
			
		} else {
			$return  = array('status' => false, 'message' => 'Bad Request: Expecting POST');
		}
		$this->format->data($return, $data_format);
	}


	public function update_organisation()
	{
		if ($this->input->method(TRUE) == 'POST') {
			$params = $this->input->post(NULL, TRUE);
			$data_format = $params['format'] ?? 'json' ;			
			$form_data = $params;

			$response_id = $this->app_model->update_organisation($params['organisation_id'], $form_data);
			$data = $response_id;
			$return  = array('status' => true, 'message' => 'Valid Request', 'data' => $data);
			
		} else {
			$return  = array('status' => false, 'message' => 'Bad Request: Expecting POST');
		}
		$this->format->data($return, $data_format);
	}


	public function update_chart()
	{
		if ($this->input->method(TRUE) == 'POST') {
			$params = $this->input->post(NULL, TRUE);
			$data_format = $params['format'] ?? 'json' ;			
			$form_data = $params;

			$chart_id = $this->app_model->update_chart($params['chart_id'], $form_data);
			$data = $chart_id;
			$return  = array('status' => true, 'message' => 'Valid Request', 'data' => $data);
			
		} else {
			$return  = array('status' => false, 'message' => 'Bad Request: Expecting POST');
		}
		$this->format->data($return, $data_format);
	}


	public function update_region()
	{
		if ($this->input->method(TRUE) == 'POST') {
			$params = $this->input->post(NULL, TRUE);
			$data_format = $params['format'] ?? 'json' ;			
			$form_data = $params;

			$response_id = $this->app_model->update_region($params['region_id'], $form_data);
			$data = $response_id;
			$return  = array('status' => true, 'message' => 'Valid Request', 'data' => $data);
			
		} else {
			$return  = array('status' => false, 'message' => 'Bad Request: Expecting POST');
		}
		$this->format->data($return, $data_format);
	}


	public function update_district()
	{
		if ($this->input->method(TRUE) == 'POST') {
			$params = $this->input->post(NULL, TRUE);
			$data_format = $params['format'] ?? 'json' ;
			$form_data = $params;

			$response_id = $this->app_model->update_district($params['district_id'], $form_data);
			$data = $response_id;
			$return  = array('status' => true, 'message' => 'Valid Request', 'data' => $data);
			
		} else {
			$return  = array('status' => false, 'message' => 'Bad Request: Expecting POST');
		}
		$this->format->data($return, $data_format);
	}



	public function update_sub_county()
	{
		if ($this->input->method(TRUE) == 'POST') {
			$params = $this->input->post(NULL, TRUE);
			$data_format = $params['format'] ?? 'json' ;
			$form_data = $params;

			$response_id = $this->app_model->update_sub_county($params['sub_county_id'], $form_data);
			$data = $response_id;
			$return  = array('status' => true, 'message' => 'Valid Request', 'data' => $data);
			
		} else {
			$return  = array('status' => false, 'message' => 'Bad Request: Expecting POST');
		}
		$this->format->data($return, $data_format);
	}



	public function update_parish()
	{
		if ($this->input->method(TRUE) == 'POST') {
			$params = $this->input->post(NULL, TRUE);
			$data_format = $params['format'] ?? 'json' ;
			$form_data = $params;

			$response_id = $this->app_model->update_parish($params['parish_id'], $form_data);
			$data = $response_id;
			$return  = array('status' => true, 'message' => 'Valid Request', 'data' => $data);
			
		} else {
			$return  = array('status' => false, 'message' => 'Bad Request: Expecting POST');
		}
		$this->format->data($return, $data_format);
	}



	public function update_village()
	{
		if ($this->input->method(TRUE) == 'POST') {
			$params = $this->input->post(NULL, TRUE);
			$data_format = $params['format'] ?? 'json' ;
			$form_data = $params;

			$response_id = $this->app_model->update_village($params['village_id'], $form_data);
			$data = $response_id;
			$return  = array('status' => true, 'message' => 'Valid Request', 'data' => $data);
			
		} else {
			$return  = array('status' => false, 'message' => 'Bad Request: Expecting POST');
		}
		$this->format->data($return, $data_format);
	}











	public function hard_delete_project()
	{
		if ($this->input->method(TRUE) == 'POST') {
			$params = $this->input->post(NULL, TRUE);
			$data_format = $params['format'] ?? 'json' ;

			if($this->app_model->delete_project($params['project_id'])) {
				$return  = array('status' => true, 'message' => 'Record has been deleted');
			} else {
				$return  = array('status' => false, 'message' => 'Record was not deleted');
			}
		} else {
			$return  = array('status' => false, 'message' => 'Bad Request: Expecting POST');
		}
		$this->format->data($return, $data_format);
	}


	public function soft_delete_project()
	{
		if ($this->input->method(TRUE) == 'POST') {
			$params = $this->input->post(NULL, TRUE);
			$data_format = $params['format'] ?? 'json' ;			
			$form_data['active'] = 0;

			$response_id = $this->app_model->update_project($params['project_id'], $form_data);
			$data = $response_id;
			$return  = array('status' => true, 'message' => 'Valid Request', 'data' => $data);
			
		} else {
			$return  = array('status' => false, 'message' => 'Bad Request: Expecting POST');
		}
		$this->format->data($return, $data_format);
	}


	public function hard_delete_organisation()
	{
		if ($this->input->method(TRUE) == 'POST') {
			$params = $this->input->post(NULL, TRUE);
			$data_format = $params['format'] ?? 'json' ;

			if($this->app_model->delete_organisation($params['organisation_id'])) {
				$return  = array('status' => true, 'message' => 'Record has been deleted');
			} else {
				$return  = array('status' => false, 'message' => 'Record was not deleted');
			}
		} else {
			$return  = array('status' => false, 'message' => 'Bad Request: Expecting POST');
		}
		$this->format->data($return, $data_format);
	}


	public function soft_delete_organisation()
	{
		if ($this->input->method(TRUE) == 'POST') {
			$params = $this->input->post(NULL, TRUE);
			$data_format = $params['format'] ?? 'json' ;			
			$form_data['active'] = 0;

			$response_id = $this->app_model->update_organisation($params['organisation_id'], $form_data);
			$data = $response_id;
			$return  = array('status' => true, 'message' => 'Valid Request', 'data' => $data);
			
		} else {
			$return  = array('status' => false, 'message' => 'Bad Request: Expecting POST');
		}
		$this->format->data($return, $data_format);
	}




	public function soft_delete_region()
	{
		if ($this->input->method(TRUE) == 'POST') {
			$params = $this->input->post(NULL, TRUE);
			$data_format = $params['format'] ?? 'json' ;			
			$form_data['active'] = 0;

			$response_id = $this->app_model->update_region($params['region_id'], $form_data);
			$data = $response_id;
			$return  = array('status' => true, 'message' => 'Valid Request', 'data' => $data);
			
		} else {
			$return  = array('status' => false, 'message' => 'Bad Request: Expecting POST');
		}
		$this->format->data($return, $data_format);
	}


	public function soft_delete_district()
	{
		if ($this->input->method(TRUE) == 'POST') {
			$params = $this->input->post(NULL, TRUE);
			$data_format = $params['format'] ?? 'json' ;
			$form_data['active'] = 0;

			$response_id = $this->app_model->update_district($params['district_id'], $form_data);
			$data = $response_id;
			$return  = array('status' => true, 'message' => 'Valid Request', 'data' => $data);
			
		} else {
			$return  = array('status' => false, 'message' => 'Bad Request: Expecting POST');
		}
		$this->format->data($return, $data_format);
	}



	public function soft_delete_sub_county()
	{
		if ($this->input->method(TRUE) == 'POST') {
			$params = $this->input->post(NULL, TRUE);
			$data_format = $params['format'] ?? 'json' ;
			$form_data['active'] = 0;

			$response_id = $this->app_model->update_sub_county($params['sub_county_id'], $form_data);
			$data = $response_id;
			$return  = array('status' => true, 'message' => 'Valid Request', 'data' => $data);
			
		} else {
			$return  = array('status' => false, 'message' => 'Bad Request: Expecting POST');
		}
		$this->format->data($return, $data_format);
	}



	public function soft_delete_parish()
	{
		if ($this->input->method(TRUE) == 'POST') {
			$params = $this->input->post(NULL, TRUE);
			$data_format = $params['format'] ?? 'json' ;
			$form_data['active'] = 0;

			$response_id = $this->app_model->update_parish($params['parish_id'], $form_data);
			$data = $response_id;
			$return  = array('status' => true, 'message' => 'Valid Request', 'data' => $data);
			
		} else {
			$return  = array('status' => false, 'message' => 'Bad Request: Expecting POST');
		}
		$this->format->data($return, $data_format);
	}



	public function soft_delete_village()
	{
		if ($this->input->method(TRUE) == 'POST') {
			$params = $this->input->post(NULL, TRUE);
			$data_format = $params['format'] ?? 'json' ;
			$form_data['active'] = 0;

			$response_id = $this->app_model->update_village($params['village_id'], $form_data);
			$data = $response_id;
			$return  = array('status' => true, 'message' => 'Valid Request', 'data' => $data);
			
		} else {
			$return  = array('status' => false, 'message' => 'Bad Request: Expecting POST');
		}
		$this->format->data($return, $data_format);
	}


	public function soft_delete_response()
	{
		if ($this->input->method(TRUE) == 'POST') {
			$params = $this->input->post(NULL, TRUE);
			$data_format = $params['format'] ?? 'json' ;
			$form_data['active'] = 0;

			$response_id = $this->app_model->update_response($params['response_id'], $form_data);
			$data = $response_id;
			$return  = array('status' => true, 'message' => 'Valid Request', 'data' => $data);

		} else {
			$return  = array('status' => false, 'message' => 'Bad Request: Expecting POST');
		}
		$this->format->data($return, $data_format);
	}


	public function hard_delete_response()
	{
		if ($this->input->method(TRUE) == 'POST') {
			$params = $this->input->post(NULL, TRUE);
			$data_format = $params['format'] ?? 'json' ;

			if($this->app_model->delete_response($params['response_id'])) {
				$return  = array('status' => true, 'message' => 'Record has been deleted');
			} else {
				$return  = array('status' => false, 'message' => 'Record was not deleted');
			}

		} else {
			$return  = array('status' => false, 'message' => 'Bad Request: Expecting POST');
		}
		$this->format->data($return, $data_format);
	}


	public function delete_chart()
	{
		if ($this->input->method(TRUE) == 'POST') {
			$params = $this->input->post(NULL, TRUE);
			$data_format = $params['format'] ?? 'json' ;

			if($this->app_model->delete_chart($params['chart_id'])) {
				$return  = array('status' => true, 'message' => 'Chart has been deleted');
			} else {
				$return  = array('status' => false, 'message' => 'Chart was not deleted');
			}

		} else {
			$return  = array('status' => false, 'message' => 'Bad Request: Expecting POST');
		}
		$this->format->data($return, $data_format);
	}



















	public function delete_form()
	{
		if ($this->input->method(TRUE) == 'POST') {
			$params = $this->input->post(NULL, TRUE);
			$data_format = $params['format'] ?? 'json' ;

			$form = $this->app_model->get_form($params['form_id']);

			if ($form) {
				if ($this->app_model->delete_form($params['form_id'])) {
					$this->app_model->delete_questions(json_decode($form->question_ids));
					$return  = array('status' => true, 'message' => 'Form was deleted');
				} else {
					$return  = array('status' => false, 'message' => 'Form does not exist');
				}
			} else {
				$return  = array('status' => false, 'message' => 'Form was not submitted');
			}
		} else {
			$return  = array('status' => false, 'message' => 'Bad Request: Expecting POST');
		}
		$this->format->data($return, $data_format);
	}


	
	public function soft_delete_form()
	{
		if ($this->input->method(TRUE) == 'POST') {
			$params = $this->input->post(NULL, TRUE);
			$data_format = $params['format'] ?? 'json' ;

			$form_data['active'] = 0;

			$form_id = $this->app_model->update_form($params['form_id'], $form_data);
			if ($form_id) {
				$form = $this->app_model->get_form($form_id);
				$return  = array('status' => true, 'message' => 'Valid Request', 'data' => $form);
			} else {
				$return  = array('status' => false, 'message' => 'Form was not submitted');
			}
		} else {
			$return  = array('status' => false, 'message' => 'Bad Request: Expecting POST');
		}
		$this->format->data($return, $data_format);
	}


	


	public function soft_delete_question()
	{
		if ($this->input->method(TRUE) == 'POST') {
			$params = $this->input->post(NULL, TRUE);
			$data_format = $params['format'] ?? 'json' ;

			if (!in_array($params['question_id'], [4,7,8,9,148])) {
				$form_data['active'] = 0;
				$response_id = $this->app_model->update_question($params['question_id'], $form_data);
			} else {
				$response_id = $params['question_id'];
			}
			if ($response_id) {
				$question = $this->app_model->get_question($response_id);
				$return  = array('status' => true, 'message' => 'Valid Request', 'data' => $question);

				if (isset($params['form_id'])) {
					$this->remove_question_from_form($params['form_id'], $params['question_id']);
				}
			} else {
				$return  = array('status' => false, 'message' => 'Question changes were not submitted');
			}
			
		} else {
			$return  = array('status' => false, 'message' => 'Bad Request: Expecting POST');
		}
		$this->format->data($return, $data_format);
	}


	public function hard_delete_question()
	{
		if ($this->input->method(TRUE) == 'POST') {
			$params = $this->input->post(NULL, TRUE);
			$data_format = $params['format'] ?? 'json' ;

			if (!in_array($params['question_id'], [2,4,7,8,9,148])) {
				$status = $this->app_model->delete_question($params['question_id']);
			} else {
				$status = true;
			}

			if ($status) {
				if (isset($params['form_id'])) {
					$this->remove_question_from_form($params['form_id'], $params['question_id']);
				}
				$return  = array('status' => true, 'message' => 'Question deleted');
			} else {
				$return  = array('status' => false, 'message' => 'Question changes were not submitted');
			}
			
		} else {
			$return  = array('status' => false, 'message' => 'Bad Request: Expecting POST');
		}
		$this->format->data($return, $data_format);
	}


	public function soft_delete_user()
	{
		if ($this->input->method(TRUE) == 'POST') {
			$params = $this->input->post(NULL, TRUE);
			$data_format = $params['format'] ?? 'json' ;

			$user_data['active'] = 0;

			$user_id = $this->app_model->update_user($params['user_id'], $user_data);
			if ($user_id) {
				$user = $this->app_model->get_user($user_id);
				$return  = array('status' => true, 'message' => 'Valid Request', 'data' => $user);
			} else {
				$return  = array('status' => false, 'message' => 'User was not submitted');
			}
		} else {
			$return  = array('status' => false, 'message' => 'Bad Request: Expecting POST');
		}
		$this->format->data($return, $data_format);
	}


	public function soft_delete_admin_user()
	{
		if ($this->input->method(TRUE) == 'POST') {
			$params = $this->input->post(NULL, TRUE);
			$data_format = $params['format'] ?? 'json' ;

			$user_data['active'] = 0;

			$user_id = $this->app_model->update_admin_user($params['user_id'], $user_data);
			if ($user_id) {
				$user = $this->app_model->get_admin_user($user_id);
				$return  = array('status' => true, 'message' => 'Valid Request', 'data' => $user);
			} else {
				$return  = array('status' => false, 'message' => 'User was not submitted');
			}
		} else {
			$return  = array('status' => false, 'message' => 'Bad Request: Expecting POST');
		}
		$this->format->data($return, $data_format);
	}


	public function delete_user()
	{
		if ($this->input->method(TRUE) == 'POST') {
			$params = $this->input->post(NULL, TRUE);
			$data_format = $params['format'] ?? 'json' ;

			$status = $this->app_model->delete_user($params['user_id']);
			if ($status) {
				$return  = array('status' => true, 'message' => 'Mobile User deleted');
			} else {
				$return  = array('status' => false, 'message' => 'Mobile User was deleted');
			}
		} else {
			$return  = array('status' => false, 'message' => 'Bad Request: Expecting POST');
		}
		$this->format->data($return, $data_format);
	}


	public function delete_admin_user()
	{
		if ($this->input->method(TRUE) == 'POST') {
			$params = $this->input->post(NULL, TRUE);
			$data_format = $params['format'] ?? 'json' ;

			$status = $this->app_model->delete_admin_user($params['user_id']);
			if ($status) {
				$return  = array('status' => true, 'message' => 'Dashboard User deleted');
			} else {
				$return  = array('status' => false, 'message' => 'Dashboard User was deleted');
			}
		} else {
			$return  = array('status' => false, 'message' => 'Bad Request: Expecting POST');
		}
		$this->format->data($return, $data_format);
	}





















	public function add_question_to_form($form_id, $question_id)
	{
		$form = $this->app_model->get_form($form_id);
		$question_list = json_decode($form->question_list);
		$question_list[] = $question_id;
		$data['question_list'] = json_encode(array_unique($question_list));
		return $this->app_model->update_form($form_id, $data);
	}

	public function remove_question_from_form($form_id, $question_id)
	{
		$form = $this->app_model->get_form($form_id);
		$question_list = json_decode($form->question_list);
		if (($key = array_search($question_id, $question_list)) !== false) {
			unset($question_list[$key]);
		}

		$question_list = array_diff($question_list, array($question_id));
		$data['question_list'] = json_encode(array_values($question_list));

		// echo json_encode(array_values($question_list));





		return $this->app_model->update_form($form_id, $data);
	}




	public function server_disk_space()
	{
		if ($this->input->method(TRUE) == 'GET') {
			$params = $this->input->get(NULL, TRUE);
			$data_format = $params['format'] ?? 'json' ;

			$storage_data = array();
			exec("df -h", $storage_data);

			$output = preg_replace('/\s+/', ' ', $storage_data[0]);
			$header = explode(' ', $output);
			unset($storage_data[0]);

			$data = [];
			foreach ($storage_data as $storage) {
				$output = preg_replace('/\s+/', ' ', $storage);
				$storage_d = explode(' ', $output);

				for ($i=0; $i < count($storage_d); $i++) { 
					$d[$header[$i]] = $storage_d[$i];
				}
				$data[] = $d;
			}


			// if (isset($param['dir'])) {
			// 	$data['total'] = round(disk_total_space($param['dir']) / 1024 / 1024 / 1024,4);
			// 	$data['available'] = round(disk_free_space($param['dir']) / 1024 / 1024 / 1024,4);
			// } else if (isset($param['partition']) && $param['partition'] == 'root') {
			// 	$data['total'] = round(disk_total_space('/') / 1024 / 1024 / 1024,4);
			// 	$data['available'] = round(disk_free_space('/') / 1024 / 1024 / 1024,4);
			// } else if (isset($param['partition']) && $param['partition'] == 'upload') {
			// 	$data = array();
			// 	exec("df -h", $data);
			// 	// $data['total'] = round(disk_total_space('/mnt/AWS-volume') / 1024 / 1024 / 1024,4);
			// 	// $data['available'] = round(disk_free_space('/mnt/AWS-volume') / 1024 / 1024 / 1024,4);
			// } else {
			// 	$data['total'] = round(disk_total_space('/') / 1024 / 1024 / 1024,4);
			// 	$data['available'] = round(disk_free_space('/') / 1024 / 1024 / 1024,4);
			// }

			$return  = array('status' => true, 'message' => 'Valid Request', 'data' => $data);			
		} else {
			$return  = array('status' => false, 'message' => 'Bad Request: Expecting GET');
		}
		$this->format->data($return, $data_format);
	}



}
