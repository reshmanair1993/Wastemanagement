<?php

namespace backend\controllers;

use Yii;
use backend\models\BuildingType;
use backend\models\BuildingTypeSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use backend\models\PushMessage;
use backend\components\AccessPermission;
use yii\filters\AccessControl;
use yii\data\ActiveDataProvider;
/**
 * BuildingTypesController implements the CRUD actions for BuildingType model.
 */
class MessagesController extends Controller
{
    /**
     * @inheritdoc
     */
    public function actionIndex()
    {
        $modelPushMessage = new PushMessage;
        $dataProvider = new ActiveDataProvider(
        [
           'query' => $modelPushMessage->getAllQuery()
           // // 'pagination' => [
           // 'pageSize'=>100
           // ],
           // 'sort'=> ['defaultOrder' => ['id' => SORT_DESC]]
        ]);
        return $this->render('index', [
            'modelPushMessage' => $modelPushMessage,
            'dataProvider' => $dataProvider,
        ]);
    }
    public function actionSend()
    {
      $modelPushMessage = new PushMessage;
      if ($modelPushMessage->load(Yii::$app->request->post())) {
        // print_r($modelPushMessage);die();
        if($modelPushMessage->type==1)
        {
            $key = 'lsgi_id';
            $value = $modelPushMessage->lsgi_id;
        }elseif($modelPushMessage->type==2)
        {
           $key = 'hks_id';
           $value = $modelPushMessage->hks_id; 
        }
        elseif($modelPushMessage->type==3)
        {
           $key = 'ward_id';
           $value = $modelPushMessage->ward_id; 
        }
        else
        {
           $key = 'account_id';
           $value = $modelPushMessage->account_id; 
        }
      $result = Yii::$app->message->sendMessage($key,$value,$modelPushMessage->message);  
      $modelPushMessage->save(false);
      return $this->redirect(['index']);
    }
    $modelPushMessage = new PushMessage;
    return $this->render('send-message', [
            'modelPushMessage' => $modelPushMessage,
        ]);  
}
}
