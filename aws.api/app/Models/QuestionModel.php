<?php

namespace App\Models;

use CodeIgniter\Model;

class QuestionModel extends Model
{
    protected $table      = 'question';
    protected $primaryKey = 'question_id';

    // protected $returnType = 'array';
    protected $useSoftDeletes = true;

    protected $allowedFields = ['question', 'form_id', 'answer_type_id', 'answer_values', 'is_conditional', 'question_condition', 'active'];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

      //protected $validationRules    = [
     //'anser_values' => 'permit_empty'
      //];
    // protected $validationMessages = [];
    // protected $skipValidation     = false;


    protected $beforeInsert = ['beforeInsert'];
    // protected $beforeUpdate = ['beforeUpdate'];
    // protected $beforeDelete = ['beforeDelete'];

    protected function beforeInsert(array $data): array
    {
        $data['data']['active'] = 1;
        return $data;
    }

}
