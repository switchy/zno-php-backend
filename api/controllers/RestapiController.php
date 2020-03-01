<?php

namespace api\controllers;

use Yii;
use yii\rest\Controller;

class RestapiController extends Controller
{
    public $serializer = 'yii\rest\Serializer';

    public $enableCsrfValidation = false;

    public $requestParams;

    public function beforeAction($action)
    {
        Yii::$app->response->format  = \yii\web\Response::FORMAT_JSON;
        Yii::$app->response->charset = 'UTF-8';

        if (Yii::$app->request->isPost) {
            $raw_body = Yii::$app->request->getRawBody();

            if (!strlen($raw_body)) {
                throw new \yii\web\BadRequestHttpException("Required params is not found!");
            }

            try {
                $json_body = \yii\helpers\Json::decode($raw_body);
            } catch (\Exception $ex) {
            }

            if (!is_array($json_body)) {
                throw new \yii\web\BadRequestHttpException("Required params is empty!");
            }

            $this->requestParams = $json_body;
        }

        Yii::$app->response->on(yii\web\Response::EVENT_BEFORE_SEND, function($event){
           $response = $event->sender;
           if (!$response->isSuccessful) {
               $response->formatters[\yii\web\Response::FORMAT_JSON] = [
               'class' => 'yii\web\JsonResponseFormatter',
               'prettyPrint' => true,
               ];
           }
           $response->data = [
                'success' => $response->isSuccessful,
                'code'    => $response->statusCode,
                'result'  => $response->data,
           ];
        });

        Yii::$app->request->parsers['application/json'] = 'yii\web\JsonParser';
        return parent::beforeAction($action);
    }

    public function actions()
    {
        return [
            'get-ping' => [
                'class' => 'api\controllers\restapi\GetPing'
            ],                          
            'get-cats' => [
                'class' => 'api\controllers\restapi\GetCats'
            ],
            'get-tasks-with-cats' => [
                'class' => 'api\controllers\restapi\GetTasksWithCats'
            ],
            'get-tasks-with-answers' => [
                'class' => 'api\controllers\restapi\GetTasksWithAnswers'
            ],
            'save-task-cats' => [
                'class' => 'api\controllers\restapi\SaveTaskCats'
            ]

        ];
    }

    public function behaviors()
    {

        $behaviors = parent::behaviors();

        // add CORS filter
        $behaviors['corsFilter'] = [
            'class' => '\yii\filters\Cors',
            'cors' => [
                'Origin' => [
                    'http://localhost:3000', //development
                    'http://127.0.0.1:3000', //development
                    'http://localhost', //development
                ],
                'Access-Control-Request-Method' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'HEAD', 'OPTIONS'],
                'Access-Control-Allow-Credentials' => true,
            ],

        ];

        unset($behaviors['authenticator']);

        $behaviors['contentNegotiator'] = [
            'class' => 'yii\filters\ContentNegotiator',
            'formats' => [
                'application/json' => \yii\web\Response::FORMAT_JSON,
            ],
        ];

        $behaviors['verbs'] = [
            'class' => 'yii\filters\VerbFilter',
            'actions' => [
                'save-task-cats' => ['post'],
            ],
        ];

        return $behaviors;
    }

    /*
     * Test current datetime in some formats.
     *
     * Response: time three formats.
     * Example: 
     *   >>> |   curl -H "Content-Type: application/json"  "http://192.168.1.208:9999/restapi/test-current-date"
     *
     *   <<< |   {"success":true,"code":200,"result":{"unixtm":1488529722,"human_time":"2017-03-03 10:28:42","iso_date":"20170303T10:28:42"}}
     * 
     * @resturn array
     */
    public  function actionTestCurrentDate()
    {
        return [
            'unixtm'     => time(),
            'human_time' => strftime("%Y-%m-%d %H:%M:%S", time()),
            'iso_date'   => strftime("%Y%m%dT%H:%M:%S", time())
        ];
    }

}
