<?php

namespace api\controllers\restapi;

use Yii;
use yii\base\Action;
use yii\db\Query;

class GetTasksWithCats extends Action
{
    /*
     * Example:
     *   >>> |   curl -H "Content-Type: application/json" "http://10.97.181.16:8888/restapi/get-tasks-with-cats"
     *
     *   <<< |   {"success":true,"code":200,"result":{"i2019пт1":["1","3","5"],"i2019пт2":["16","20"],"iTest":["1","2","3"]}}
     *
     * @return array
     */
    public function run()
    {

        $tableName = 'task_on_cats';
        $tasks = (new Query)
            ->select(['id_task'])
            ->distinct()
            ->from($tableName)
            ->column(Yii::$app->db);

        $out = [];
        $cmd = (new Query)
            ->select(['id_cat'])
            ->from($tableName)
            ->where('id_task = :id')
            ->createCommand();
        $cmd->prepare();

        foreach($tasks as $taskId) {
            $out[$taskId] = $cmd
                ->bindValue(':id', $taskId)
                ->queryColumn();
        }

        return $out;
    }
}

# vim: syntax=php ts=4 sw=4 sts=4 sr et
