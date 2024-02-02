<?php

namespace App\Models;

use CodeIgniter\Model;

class AppSubCountyModel extends Model
{
    protected $table      = 'app_sub_county';
    protected $primaryKey = 'sub_county_id';

    // protected $returnType = 'array';
    // protected $useSoftDeletes = true;

    protected $allowedFields = ['district_id', 'name', 'active'];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

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