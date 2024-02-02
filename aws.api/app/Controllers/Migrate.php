<?php
namespace App\Controllers;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\FormModel;
use App\Models\QuestionModel;
use MongoDB\Client as MongoDB;
use App\Libraries\Utility;
class Migrate extends BaseController
{

        use ResponseTrait;

        public function index()
        {
                ini_set('memory_limit','512M');
                $utility = new Utility();
                $params = $this->request->getGet();

        $client = new MongoDB();
        $collection = $client->aws->entries;
echo "te works";

}

}
?>
