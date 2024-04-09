<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\FormModel;
use App\Models\QuestionModel;
// use CodeIgniter\HTTP\Files\UploadedFile;
use App\Libraries\Utility;

class Form extends BaseController
{

	use ResponseTrait;

	public function index()
	{
		$params = $this->request->getGet();
		$model = new FormModel();

		if (isset($params['form_id'])) {
			$form = $model->getWhere(['form_id' => $params['form_id']])->getRow();
			if ($form) {

				if (!is_null($form->question_list) && $form->question_list != '[]') {
					$form->renamed = json_decode($form->renamed, TRUE);
					$form->conditional_logic = $conditional_logic = json_decode($form->conditional_logic, TRUE);
					$question_ids = json_decode($form->question_list);

					$utility = new Utility();
					$form->question_list = $utility->ids_to_question_list($question_ids, $form->renamed);


					foreach ($form->question_list as $question) {
						$url = base_url('form/conditional-logic/remove');
						$question_id = $question->question_id;
						$form_id = $form->form_id;
						$dom = NULL;
						if (isset($conditional_logic['qn' . $question->question_id])) {
							$dom = '<ul class="unstyled-list">';
							foreach ($conditional_logic['qn' . $question->question_id] as $key => $value) {
								if (isset($value['hide'])) {
									//pass the form_id, question_id as data-elements
									$dom .= '<li>Selecting <strong>"' . $key . '"</strong> will hide ' . count($value['hide']) . ' Questions | <a href="' . $url . '" class="remove-conditions text-danger" data-form-element="'.$form_id.'" data-question-element="'.$question_id.'">Remove</a></li>';
								} elseif (isset($value['prefill'])) {
									$dom .= '<li>Selecting <strong>"' . $key . '"</strong> will prefill ' . count($value['prefill']) . ' Questions | <a href="' . $url . '" class="remove-conditions text-danger" data-form-element="'.$form_id.'" data-question-element="'.$question_id.'">Remove</a></li>';
								}
							}
							$dom .= '</ul>';

						}
						$question->conditions = $dom;
					}

				} else {
					$form->question_list = NULL;
				}

				if (isset($params['settings']) && $params['settings'] = 1) {
					$titles = count(json_decode($form->title_fields, true)['entry_title'] ?? []);
					$sub_titles = json_decode($form->title_fields, true)['entry_sub_title'] ?? [];
					if ($titles) {
						$title_fields = json_decode($form->title_fields);
						$title_fields_ids_list = implode(", ", $title_fields->entry_title);
						$sub_title_fields_ids_list = implode(", ", $title_fields->entry_sub_title);

						$question_model = new QuestionModel();

						$entry_title = $question_model->whereIn('question_id', $title_fields->entry_title)->orderBy('FIELD(question_id, ' . $title_fields_ids_list . ')')->get()->getResult();
						foreach ($entry_title as $fill) {
							$fill->answer_values = json_decode($fill->answer_values);
						}

						$entry_sub_title = $question_model->whereIn('question_id', $title_fields->entry_sub_title)->orderBy('FIELD(question_id, ' . $sub_title_fields_ids_list . ')')->get()->getResult();
						foreach ($entry_sub_title as $fill) {
							$fill->answer_values = json_decode($fill->answer_values);
						}

						$form->title_fields = array('entry_title' => $entry_title, 'entry_sub_title' => $entry_sub_title);

					} else {
						$form->title_fields = array('entry_title' => [], 'entry_sub_title' => []);
					}

					if ($form->followup_prefill) {
						$followup_prefill = json_decode($form->followup_prefill);

						if (count($followup_prefill)) {
							$followup_prefill_ids_list = implode(", ", $followup_prefill);
							$form->followup_prefill = $question_model->whereIn('question_id', json_decode($form->followup_prefill))->orderBy('FIELD(question_id, ' . $followup_prefill_ids_list . ')')->get()->getResult();
						} else {
							$form->followup_prefill = [];
						}

						foreach ($form->followup_prefill as $prefill) {
							$prefill->answer_values = json_decode($prefill->answer_values);
						}
					} else {
						$form->followup_prefill = [];
					}
				} else {
					$form->title_fields = $form->title_fields ? json_decode($form->title_fields) : [];
					$form->followup_prefill = $form->followup_prefill ? json_decode($form->followup_prefill) : [];
				}

			} else {
				return $this->respondNoContent('No Form found with id ' . $params['form_id']);
			}
			$data = $form;

		} elseif (isset($params['published'])) {
			$data = $model->getWhere(['is_publish' => $params['published']])->getResult();
			foreach ($data as $form) {

				if (!is_null($form->question_list) && $form->question_list != '[]') {
					$form->title_fields = json_decode($form->title_fields, TRUE);
					$form->renamed = json_decode($form->renamed, TRUE);
					$form->conditional_logic = $conditional_logic = json_decode($form->conditional_logic, TRUE);
					$question_ids = json_decode($form->question_list);

					$utility = new Utility();
					$form->question_list = $utility->ids_to_question_list($question_ids, $form->renamed);

					foreach ($form->question_list as $question) {
						$dom = NULL;
						if (isset($conditional_logic['qn' . $question->question_id])) {
							$dom = '<ul>';
							foreach ($conditional_logic['qn' . $question->question_id] as $key => $value) {
								if (isset($value['hide'])) {
									$dom .= '<li>Selecting <strong>"' . $key . '"</strong> will hide ' . count($value['hide']) . ' Questions</li>';
								} elseif (isset($value['prefill'])) {
									// print_r($value['prefill']);
									$dom .= '<li>Selecting <strong>"' . $key . '"</strong> will hide ' . count($value['prefill']) . ' Questions</li>';
								}
							}
							$dom .= '</ul>';

						}
						$question->conditions = $dom;
					}

				} else {
					$form->question_list = NULL;
				}

			}

		} else {
			$data = $model->findAll();
		}

		if ($data) {
			$response = [
				'status' => 201,
				'error' => null,
				'data' => $data
			];

			return $this->respond($response);
		} else {
			return $this->failNotFound('No Data Found with id ' . $form_id);
		}
	}



	// create

	public function create()
	{
		$params = $this->request->getPost();
		$model = new FormModel();

		$form_id = $model->insert($params);
		if($form_id){
			$data = $model->getWhere(['form_id' => $form_id])->getRow();
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
			return $this->failNotFound('No Data Found with form_id '.$form_id);
		}
	}

	public function create_conditional_logic()
	{
		$params = $this->request->getPost();
		$model = new FormModel();

		if ($data = (object) $model->find($params['form_id'])) {
			$conditional_logic = is_null($data->conditional_logic) ? [] : json_decode($data->conditional_logic, TRUE);

			if (!isset($conditional_logic['qn'.$params['question_id']]))
				$conditional_logic['qn'.$params['question_id']] = [];

			if (!isset($conditional_logic['qn'.$params['question_id']][$params['answer']] ))
				$conditional_logic['qn'.$params['question_id']][$params['answer']] = [];

			$conditional_logic['qn'.$params['question_id']][$params['answer']][$params['action']] = json_decode($params['question_ids']);
			
			$new_params['form_id'] = $params['form_id'];
			$new_params['conditional_logic'] = json_encode($conditional_logic);

			if($model->update($new_params['form_id'], $new_params)) {
				$response = [
					'status'   => 201,
					'data' => $conditional_logic
				];
				return $this->respond($response);
			} else {
				return $this->failNotFound('No Data was saved');
			}

		} else {
			return $this->failNotFound('No Data Found with form_id '.$params['form_id']);
		}
	}


	public function delete_conditional_logic()
	{
		$params = $this->request->getPost();
		$model = new FormModel();

		$form = $model->find($params['form_id']);
		if($form) {
			if ($conditional_logic = json_decode($form['conditional_logic'], TRUE)) {
				if (isset($conditional_logic['qn'.$params['question_id']])) {
					unset($conditional_logic['qn'.$params['question_id']]);
				}

				$new_params['conditional_logic'] = json_encode($conditional_logic);
				if($model->update($params['form_id'], $new_params)) {
					$response = [
						'status'   => 201,
						'data' => $conditional_logic
					];
					return $this->respond($response);
				} else {
					return $this->failNotFound('No Data was saved');
				}

			} else {
				return $this->failNotFound();
			}

		} else {
			return $this->failNotFound();
		}
	}






	// update

	public function update()
	{
		$params = $this->request->getPost();
		$model = new FormModel();

		$data = $model->update($params['form_id'], $params);
		if($model->update($params['form_id'], $params)){
			$data = $model->getWhere(['form_id' => $params['form_id']])->getRow();
			$response = [
				'status'   => 201,
				'error'    => null,
				'messages' => [
					'success' => 'Data Updated'
				],
				'data' => $data
			];

			return $this->respond($response);

		} else {
			return $this->failNotFound('No Data Found with form_id '.$params['form_id']);
		}
	}




	public function update_form_question_order()
	{
		$params = $this->request->getPost();
		$model = new FormModel();

		$form_data['question_list'] = $params['question_list'] ?? null;
		if($model->update($params['form_id'], $form_data)){
			$data = $model->getWhere(['form_id' => $params['form_id']])->getRow();
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
			return $this->failNotFound('Changes not saved');
		}
	}












	// delete

	public function delete()
	{
		$params = $this->request->getPost();
		$model = new FormModel();

		$data = $model->find($params['form_id']);
		if($data){
			$model->delete($params['form_id']);
			$response = [
				'status'   => 200,
				'error'    => null,
				'messages' => [
					'success' => 'Data Deleted'
				]
			];

			return $this->respondDeleted($response);

		} else {
			return $this->failNotFound('No Data Found with form_id '.$params['form_id']);
		}
	}






}
