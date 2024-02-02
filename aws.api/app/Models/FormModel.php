<?php

namespace App\Models;

use CodeIgniter\Model;

class FormModel extends Model
{
    protected $table      = 'question_form';
    protected $primaryKey = 'form_id';

    // protected $returnType = 'array';
    // protected $useSoftDeletes = true;

//    protected $allowedFields = ['category_id', 'title', 'question_list', 'title_fields', 'renamed', 'conditional_logic', 'is_geotagged', 'is_photograph', 'is_followup', 'followup_prefill', 'followup_interval', 'creator_id', 'is_publish'];
    protected $allowedFields = ['form_id', 'title', 'question_list', 'title_fields', 'renamed', 'conditional_logic', 'is_geotagged', 'is_photograph', 'is_followup', 'followup_prefill', 'followup_interval', 'creator_id', 'is_publish', 'active'];
    
    protected $useTimestamps = true;
    protected $createdField  = 'date_created';
    protected $updatedField  = 'date_modified';
    // protected $deletedField  = 'deleted_at';

    // protected $validationRules    = [];
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
