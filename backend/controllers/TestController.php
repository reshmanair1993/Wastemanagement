<?php

namespace backend\controllers;

use Yii;
use backend\models\BuildingType;
use backend\models\BuildingTypeSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use backend\models\BuildingTypeSubTypes;
use backend\components\AccessPermission;
use backend\models\BuildingTypeSubTypesSearch;
use yii\filters\AccessControl;

/**
 * BuildingTypesController implements the CRUD actions for BuildingType model.
 */
class TestController extends Controller
{
    /**
     * @inheritdoc
     */
    

    /**
     * Lists all BuildingType models.
     * @return mixed
     */
    public function actionIndex()
    {
      $result =   Yii::$app->onesignal->notifications->add([
		    'contents' => ["en" => 'Test message Test'],
		    'filters' => [
                 [
                     'field' => 'tag',
                     'key' => 'account_id',
                     'value' => 34691
                 ]
		    ],
		    // 'included_segments'=>['All']
		    'include_player_ids' => [],
		]);
		
		print_r($result);die();
    } 
    public function actionMessage()
    {
        Yii::$app->message->sendSMS("283326AYHR8CW2Rakl5d19b46c","GRNTVM","91",'9847640775',"We are testing the SMS facility");
    }  

public function actionBackup()
    {
        $DBUSER="troisDba";
        $DBPASSWD="coCsZTMl0YiztoI9";
        $DATABASE="waste_management";
        $host="localhost";

        $filename = "backup-" . date("d-m-Y") . ".sql";
        $path  = Yii::$app->params['back_up_url'];
        $file_path = $path.$filename;
        $full_path = Yii::getAlias($file_path);
        exec("mysqldump --user={$DBUSER} --password={$DBPASSWD} --host={$host} {$DATABASE} --result-file={$full_path} 2>&1", $output);
        var_dump($output);
    }  
}