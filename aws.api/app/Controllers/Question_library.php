<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\QuestionLibraryModel;
// use CodeIgniter\HTTP\Files\UploadedFile;


class Question_library extends BaseController
{

	use ResponseTrait;

	public function index()
	{
		$params = $this->request->getGet();
		$model = new QuestionLibraryModel();

		if (isset($params['question_id'])) {
			$data = $this->db->table('question')->where('question_id', $params['question_id'])->get()->getRow();
            $data->answer_values = json_decode($data->answer_values);
		} else {
			$data = $this->db->table('view_question')->get()->getResult();
            foreach ($data as $question) {
                $question->answer_values = json_decode($question->answer_values);
            }
		}

		if($data){
			$response = [
				'status'   => 200,
				'data' => $data
			];
			
			return $this->respond($response);
		}else{
			return $this->failNotFound();
		}
	}



	// create

	public function create()
	{
		$params = $this->request->getPost();
		$model = new QuestionLibraryModel();

		$builder = $this->db->table('question_library');
		$builder->where('question', $params['question']);
		$builder->where('answer_type_id', $params['answer_type_id']);
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

			if($question_id) {
                $data = $this->db->table('view_question_library')->where('question_id', $question_id)->get()->getRow();
                $data->answer_values = json_decode($data->answer_values);

				$response = [
					'status'   => 201,
					'data' => $data
				];
				return $this->respondCreated($response);
			} else {
				return $this->failNotFound('No Data Found');
			}
		} else {
			$response = [
				'status'   => 401,
				'error'    => 'Question already exists'
			];
			return $this->respond($response);
		}
	}



	// update

	public function update()
	{
		$params = $this->request->getPost();
		$model = new QuestionLibraryModel();

		if($model->update($params['question_id'], $params)){
            $data = $this->db->table('view_question_library')->where('question_id', $params['question_id'])->get()->getRow();
            $data->answer_values = json_decode($data->answer_values);

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
			return $this->failNotFound('No Data Found with id '.$params['answer_type_id']);
		}
	}



	// delete

	public function delete()
	{
		$params = $this->request->getPost();
		$model = new QuestionLibraryModel();

		$data = $model->find($params['question_id']);
		if($data){
			$model->delete($params['question_id']);
			$response = [
				'status'   => 201,
				'error'    => null,
				'messages' => [
					'success' => 'Data Deleted'
				]
			];

			return $this->respondDeleted($response);

		} else {
			return $this->failNotFound();
		}
	}






}
