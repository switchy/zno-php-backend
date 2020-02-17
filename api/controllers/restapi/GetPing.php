<?php

namespace api\controllers\restapi;

use Yii;
use yii\base\Action;

class GetPing extends Action
{
    public function run()
    {         
        return [
                'status' => 'success',
                'data' => array(array( 
                    "error_number" => 0,
                    "description"  => 'all right',
                    ))
        ];
    }
}
