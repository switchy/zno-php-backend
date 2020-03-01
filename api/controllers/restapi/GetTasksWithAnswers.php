<?php

namespace api\controllers\restapi;

use Yii;
use yii\base\Action;
use yii\db\Expression;
use yii\db\Query;

class GetTasksWithAnswers extends Action
{
    /*
     * Example:
     *   >>> |   curl -H "Content-Type: application/json" "http://10.97.181.16:8888/restapi/get-tasks-with-answers"
     *
     *   <<< |   {"success":true,"code":200,"result":{"i2019ос24":{"1":"Г","2":"В","3":"Д","4":"А"},"i2016пт":["2","4","5"]}}
     *
     * @return array
     */
    public function run()
    {

        $tableName = 'answers_on_task';
        $tasks = (new Query)
            ->select(['id_task', new Expression('sum(length(idx)) as term')])
            ->distinct()
            ->from($tableName)
            ->groupBy('id_task')
            ->indexBy('id_task')
            ->all(Yii::$app->db);

        $cmd = (new Query)
            ->select(['idx', 'answer'])
            ->from($tableName)
            ->where('id_task = :id')
            ->createCommand();
        $cmd->prepare();

        $out = [];
        foreach($tasks as $taskId => $item) {
            $rows = $cmd
                ->bindValue(':id', $taskId)
                ->queryAll();

            $out[$taskId] = [];
            if (!strlen($item['term'])) {
                //Scenario as Array
                foreach($rows as $item) {
                    $out[$taskId][] = $item['answer'];
                }
            } else {
                //Scenario as Object
                foreach($rows as $item) {
                    $out[$taskId][$item['idx']] = $item['answer'];
                }
            }
        }

        return $out;
    }
}

# vim: syntax=php ts=4 sw=4 sts=4 sr et