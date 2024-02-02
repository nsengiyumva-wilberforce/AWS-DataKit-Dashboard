<?php

namespace App\Models;

use CodeIgniter\Model;

class IndicatorChartModel extends Model
{
    protected $table      = 'indicator_chart';
    protected $primaryKey = 'chart_id';

    // protected $returnType = 'array';
    // protected $useSoftDeletes = true;

    protected $allowedFields = ['title', 'form_id', 'question_id', 'target', 'start_date', 'end_date'];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // protected $validationRules    = [];
    // protected $validationMessages = [];
    // protected $skipValidation     = false;


    // protected $beforeInsert = ['beforeInsert'];
    // protected $beforeUpdate = ['beforeUpdate'];
    // protected $beforeDelete = ['beforeDelete'];

    // protected function beforeInsert(array $data): array
    // {
    //     $data['data']['active'] = 1;
    //     return $data;
    // }

}