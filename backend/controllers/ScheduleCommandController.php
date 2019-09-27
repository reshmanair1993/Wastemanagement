<?php

namespace backend\controllers;

use Yii;
use backend\models\Camera;
use backend\models\Account;
use backend\models\Person;
use backend\models\Ward;
use backend\models\DeviceTokenTest;
use backend\models\Lsgi;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use yii\helpers\ArrayHelper;
use opensooq\firebase\FirebaseNotifications;
use backend\models\ScheduleWard;
use backend\models\Schedule;
   use yii2tech\crontab\CronTab;
/**
 * CameraController implements the CRUD actions for Camera model.
 */
class ScheduleCommandController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Camera models.
     * @return mixed
     */
    public function actionIndex($wardId=6,$date='2019-02-20')
    { 
      Yii::$app->cron->scheduleServiceRequests($wardId,$date);
    }
    public function actionSchedule()
    { 
      $date= date('Y-m-d');
      Yii::$app->cron->scheduleServiceRequestsWards($date);
    }
    public function actionScheduleCron()
    { 
      $cronTab = new CronTab();
      $cronTab->setJobs([
          [
              'min' => '0',
              'hour' => '0',
              'command' => '0 0 * * * php /var/www/html/wastemanagement/backend/yii schedule',
          ],
          // [
          //     'line' => '0 0 * * * php /path/to/project/yii another-cron'
          // ]
      ]);
      $cronTab->apply();

      }
}
