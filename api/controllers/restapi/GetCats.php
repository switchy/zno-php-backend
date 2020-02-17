<?php

namespace api\controllers\restapi;

use Yii;
use yii\base\Action;
use yii\db\Query;

class GetCats extends Action
{
    /*
     * Example:
     *   >>> |   curl -H "Content-Type: application/json" "http://10.97.181.16:8888/restapi/get-cats"
     *
     *   <<< |   {"success":true,"code":200,"result":[{"id": 1, "name" : "Наголос"},...]}
     *
     * @return array
     */
    public function run()
    { 

        $res = (new Query)
            ->select(['id', 'name'])
            ->from('g_cat')
            ->all(Yii::$app->db);

        return $res;
    }
}

# vim: syntax=php ts=4 sw=4 sts=4 sr et
