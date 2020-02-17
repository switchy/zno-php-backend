<?php

namespace api\controllers\restapi;

use Yii;
use yii\base\Action;
use yii\db\Query;

class SaveTaskCats extends Action
{
    /*
     * Example:
     *   >>> |   curl -X POST -H "Content-Type: application/json" --data '{"task" : "iTest", "cats": [1, 2, 3]}' "http://10.97.181.16:8888/restapi/save-task-cats"
     *
     *   <<< |   {"success":true,"code":200,"result":[{"id": 1, "name" : "Наголос"},...]}
     *
     * @return array
     */
    public function run()
    {
        $params = Yii::$app->controller->requestParams;
        $doSave = true;

        if (!isset($params['cats']) || !is_array($params['cats'])) {
            $doSave = false;
        }

        if (!isset($params['task']) || !is_scalar($params['task'])) {
            $doSave = false;
        }

        if (!$doSave) {
            throw \yii\web\BadRequestHttpException("Params is wrong");
        }

        $tableName = 'task_on_cats';
        $dataTm = (new Query())
            ->select('tm')
            ->from($tableName)
            ->where(['id_task' => $params['task']]);

        $newTm = time();
        foreach($params['cats'] as $idCat) {
            $sql = Yii::$app->db->createCommand()
                ->insert($tableName, [
                    'id_task' => $params['task'],
                    'id_cat'  => $idCat,
                    'tm'      => $newTm
                ])
                ->getRawSql();
            $sql = str_ireplace('insert', 'insert or replace', $sql);

            Yii::$app->db->createCommand($sql)
                ->execute();
        }

        if (!is_null($dataTm)) {
            Yii::$app->db->createCommand()
                ->delete($tableName, [
                   'and',
                    ['id_task' => $params['task']],
                    ['!=', 'tm', $dataTm]
                ])
                ->execute();
        }

        return true;
    }
}

# vim: syntax=php ts=4 sw=4 sts=4 sr et
