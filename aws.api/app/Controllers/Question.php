<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\QuestionModel;
use App\Models\FormModel;
use App\Config\Validation;
// use CodeIgniter\HTTP\Files\UploadedFile;


class Question extends BaseController
{

	use ResponseTrait;

	public function index()
	{
		$params = $this->request->getGet();
		$model = new QuestionModel();

		if (isset($params['question_id'])) {
			$data = $this->db->table('question')->where('question_id', $params['question_id'])->get()->getRow();
			$data->answer_values = json_decode($data->answer_values);

			if (isset($params['form_id'])) {
				$form = $this->db->table('question_form')->where('form_id', $params['form_id'])->get()->getRow();
				$form->renamed = json_decode($form->renamed, TRUE);
				if (!is_null($form->renamed) && isset($form->renamed['qn'.$data->question_id])) {
					$data->question = $form->renamed['qn'.$data->question_id];
				}
			}
		} else {
			$data = $this->db->table('question')->get()->getResult();
			foreach ($data as $question) {
				$question->answer_values = json_decode($question->answer_values);
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
		$model = new QuestionModel();

		$builder = $this->db->table('question');
		$builder->where('question', $params['question']);
		$builder->where('answer_type_id', $params['answer_type_id']);
		$builder->where('form_id', $params['form_id']);
		$count_results = $builder->countAllResults();

		if ($count_results == 0) {
			if ($params['answer_type_id'] == 7) { // If not applist, 
				if ($params['answer_values'] == '{"db_table":"app_district"}') { $question_id = 4; }
				elseif ($params['answer_values'] == '{"db_table":"app_sub_county"}') { $question_id = 7; } 
				elseif ($params['answer_values'] == '{"db_table":"app_parish"}') { $question_id = 8; }
				elseif ($params['answer_values'] == '{"db_table":"app_village"}') { $question_id = 9; }
				elseif ($params['answer_values'] == '{"db_table":"app_project"}') { $question_id = 148; }
				elseif ($params['answer_values'] == '{"db_table":"app_organisation"}') { $question_id = 2; }
			} else {
				$question_id = $model->insert($params);
			}

			if(isset($question_id)) {
				$data = $this->db->table('question')->where('question_id', $question_id)->get()->getRow();
				$data->answer_values = json_decode($data->answer_values);

				// Add question to form list
				$form_model = new FormModel();
				$form = $form_model->find($params['form_id']);
				$question_list = json_decode($form['question_list']);
				$question_list[] = $question_id;
				$form_data['question_list'] = json_encode($question_list);

				// For renamed app list field
				if ($params['answer_type_id'] == 7) {
					$renamed_list = is_null($form['renamed']) ? [] : json_decode($form['renamed'], TRUE);
					$renamed_list['qn'.$question_id] = $params['question'];
					$form_data['renamed'] = json_encode($renamed_list);

					// Rename Question
					$data->question = $params['question'];
				}

				$form_model->update($params['form_id'], $form_data);
				                         //$answer_types = $this->db->table('answer_type')->where('answer_type_id', $data->answer_type_id)->get()->getRow();
                        switch ($params['answer_type_id']) {
                                case '2':
                                        $data->answer_type = "checkbox";
                                        break;
                                case '3':
                                        $data->answer_type = "radio";
                                        break;

                                default:
                                        $data->answer_type = "any";
                                        break;
                        }
				$response = [
					'status'   => 201,
					'error'    => null,
					'messages' => [
						'success' => 'Data Saved'
					],
					'data' => $data
				];
				return $this->respondCreated($response);
			} else {
				return $this->failNotFound('No Data Found');
			}
		} else {
			$response = [
				'status'   => 401,
				'error'    => 'Data already exists'
			];
			return $this->respond($response);
		}
	}

	public function add_question_from_the_library(){
	                $params = $this->request->getPost();
			$model = new QuestionModel();
	                $data = $this->db->table('question')->where('question_id', $params['question_id'])->get()->getRow();
                        $data->answer_values = json_decode($data->answer_values);
			//$answer_types = $this->db->table('answer_type')->where('answer_type_id', $data->answer_type_id)->get()->getRow();
			switch ($params['answer_type_id']) {
				case '2':
					$data->answer_type = "checkbox";
					break;
				case '3':
					$data->answer_type = "radio";
					break;
				
				default:
					$data->answer_type = "any";
					break;
			}
			 // Add question to form list
                                $form_model = new FormModel();
                                $form = $form_model->find($params['form_id']);
                                $question_list = json_decode($form['question_list']);
                                $question_list[] = $params['question_id'];
                                $form_data['question_list'] = json_encode($question_list);

				// For renamed app list field
                                if ($params['answer_type_id'] == 7) {
                                        $renamed_list = is_null($form['renamed']) ? [] : json_decode($form['renamed'], TRUE);
                                        $renamed_list['qn'.$params['question_id']] = $params['question'];
                                        $form_data['renamed'] = json_encode($renamed_list);

                                        // Rename Question
                                        $data->question = $params['question'];
                                }

                                $form_model->update($params['form_id'], $form_data);
                                $response = [
                                        'status'   => 201,
                                        'error'    => null,
                                        'messages' => [
                                                'success' => 'Data Saved'
                                        ],
                                        'data' => $data
                                ];
                                return $this->respondCreated($response);

	}



	// update

	public function update()
	{
		$params = $this->request->getPost();
		$model = new QuestionModel();

		if($model->update($params['question_id'], $params)){
			$data = $this->db->table('view_question')->where('question_id', $params['question_id'])->get()->getRow();
			$data->answer_values = json_decode($data->answer_values);
			
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
			return $this->failNotFound('No Data Found with id '.$params['answer_type_id']);
		}
	}



	// delete

	public function delete()
	{
		$params = $this->request->getPost();
		$model = new QuestionModel();

		$data = $model->find($params['question_id']);
		if($data){
			$model->delete($params['question_id']);
			$response = [
				'status'   => 200,
				'error'    => null,
				'messages' => [
					'success' => 'Data Deleted'
				]
			];

			return $this->respondDeleted($response);

		} else {
			return $this->failNotFound('No Data Found with id '.$params['answer_type_id']);
		}
	}






}
