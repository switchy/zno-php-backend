<?php
namespace console\controllers;

use Yii;
use yii\console\Controller;
use yii\console\ExitCode;
use yii\helpers\VarDumper;

class ImportController extends Controller
{
    const FS_SPOOL_ALIAS = '@runtime/spool';

    public function init()
    {
        $tableName = 'answers_on_task';
        if (Yii::$app->db->getTableSchema($tableName, true) === null) {
            Yii::$app->db->createCommand("
                create table {$tableName} (
                    id_task VARCHAR(20) NOT NULL,
                    idx INTEGER NULL,
                    answer VARCHAR(20) NOT NULL
                );
            ")->execute();
            Yii::$app->db->createCommand()
                ->createIndex('idx_answers_on_task', 'answers_on_task', ['id_task'], false);
        }
        parent::init();
    }

    public function actionAnswers()
    {
        $files = \yii\helpers\FileHelper::findFiles(Yii::getAlias(self::FS_SPOOL_ALIAS), [
            'only' => ['answers-*.csv'],
            'recursive' => false
        ]);

        foreach($files as $csvFile) {
            if (!file_exists($csvFile)) {
                Yii::error($msg = sprintf('CSV file %s is not exists!', $csvFile), __METHOD__);
                $this->stderr($msg);
                return ExitCode::DATAERR;
            }

            $fCsv = fopen($csvFile, 'r');
            $idx = 0;
            $taskId = null;
            $scCur = null; $scPrev = null;
            $yrCur = null; $yrPrev = null;
            $qnCur = null; $qnPrev = null;
            $dbData = [];
            while($csvData = fgetcsv($fCsv, 0, ':')) {
                array_walk($csvData , function(&$val) {
                    $val = trim($val);
                });
                $idx++;
                $taskId = null;
                if ($idx == 0) {
                    $yrCur  = $csvData[0];
                    $scCur = mb_strtolower($csvData[1]);
                    $qnCur = $csvData[2];
                    
                } else {
                    if (!$yrPrev && strlen($csvData[0])) {
                        $yrCur = $csvData[0];
                    }
                    if (!$scPrev && strlen($csvData[1])) {
                        $scCur = mb_strtolower($csvData[1]);
                    }
                    if (!$qnPrev && strlen($csvData[2])) {
                        $qnCur = $csvData[2];
                    }
                }

                $taskId = strtr('i{y}{s}{n}', [
                    '{y}' => $yrCur,
                    '{s}' => $scCur,
                    '{n}' => $qnCur,
                ]);

                if (!array_key_exists($taskId, $dbData)) {
                    $dbData[$taskId] = [];
                }
                $idx = $csvData[3];
                if (!strlen($idx)) {
                    $idx = null;
                }

                $dbData[$taskId][] = [$taskId, $idx, $csvData[4]];
                $this->stdout($taskId . PHP_EOL);
            }
            $tableName = 'answers_on_task';
            foreach($dbData as $idTask => $rows) {
                Yii::$app->db->createCommand()
                    ->delete($tableName, ['id_task' => $idTask])
                    ->execute();
                Yii::$app->db->createCommand()
                    ->batchInsert($tableName, ['id_task', 'idx', 'answer'], $rows)
                    ->execute();
            }
            fclose($fCsv);
        }

        return ExitCode::OK;
    }
}

# vim: syntax=php ts=4 sw=4 sts=4 sr et