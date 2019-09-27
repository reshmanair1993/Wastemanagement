<?php
namespace api\modules\v1\controllers;
use yii\rest\ActiveController;
use yii\data\ActiveDataProvider;
use  api\modules\v1\models\DumpingEventType;
use  api\modules\v1\models\DumpingEvent;
use  api\modules\v1\models\Image;
use  api\modules\v1\models\Account;
use yii\filters\VerbFilter;
use yii\filters\auth\HttpBearerAuth;
use yii\web\UploadedFile;
use Yii;

class DumpingEventsController extends ActiveController
{
     public $modelClass = '\api\modules\v1\models\DumpingEventType';

     public function actions() {
             $actions = parent::actions();
             $unsetActions = ['create','update','delete','index'];
             foreach($unsetActions as $action) {
               unset($actions[$action]);
             }

             return $actions;
     }
	 public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
            'auth' => [
                'class' => HttpBearerAuth::className(),
            ]
        ];
    }
      public function actionIncidentTypes($language=null){
        $query = DumpingEventType::getAllQuery()->orderby('id ASC');;
        $dataProvider =  new ActiveDataProvider([
          'query' => $query
        ]);
        $models = $dataProvider->getModels();
        $ret = [];
        foreach($models as $model) {
            if(isset($language)&&$language=='ml')
            {
                $name = $model->malayalam_name?$model->malayalam_name:$model->name;
            }
            else
            {
                $name = $model->name;
            }
          $ret[] = [
            'id' => $model->id,
            'name' => $name,
            // 'malayalam_name' => $model->malayalam_name,
          ];

        }
        return $ret;
      }
      public function actionIncident()
    {
        $image_base    = isset(Yii::$app->params['dumping_base_url']) ? Yii::$app->params['dumping_base_url'] : null;
        $params = Yii::$app->request->post();

        if(!isset($params['incident_type_id'])) 
        {
        $msg = ['Incident type is mandatory'];
        $error = ['incident_type_id'=>$msg];
        $ret = ['errors' =>$error];
        return $ret;
        }
        if(!isset($params['lat'])) 
        {
        $msg = ['Latitude is mandatory'];
        $error = ['lat'=>$msg];
        $ret = ['errors' =>$error];
        return $ret;
        }
        if(!isset($params['lng'])) 
        {
        $msg = ['Longitude is mandatory'];
        $error = ['lng'=>$msg];
        $ret = ['errors' =>$error];
        return $ret;
        }
        if(isset($params['incident_type_id'])) 
        {
            $modelIncidentType = DumpingEventType::find()->where(['id'=>$params['incident_type_id']])->andWhere(['status'=>1])->one();
            if(!$modelIncidentType)
            {
               $msg = ['Invalid incident type'];
        $error = ['incident_type_id'=>$msg];
        $ret = ['errors' =>$error];
        return $ret; 
            }
        }

        $ret       = [];
        $modelImage = new Image;
        $customer_id = Yii::$app->user->id;
        while (true)
        {
            if ($customer_id)
            {
                $modelDumpingEvent = new DumpingEvent;
                $customer = Account::find()->where(['id'=>$customer_id])->andWhere(['status'=>1])->one();
                if ($customer)
                {
                    $modelImage->load($params);
                    $modelImage->uploaded_files = UploadedFile::getInstanceByName('image');
                    $newPhotoOk = $modelImage->validate();
                    $newPhotoId = null;
                    if ($newPhotoOk) {
                    $dumping_base_urls    = isset(Yii::$app->params['dumping_base_urls']) ? Yii::$app->params['dumping_base_urls'] : null;
                    $newPhotoId = $modelImage->uploadAndSave($modelImage->uploaded_files,$dumping_base_urls);
                    }
                    $modelDumpingEvent->image_id = $newPhotoId;
                    $modelDumpingEvent->account_id_customer       = $customer_id;
                    $modelDumpingEvent->remarks  = isset($params['remarks']) ? $params['remarks'] : '';
                    $modelDumpingEvent->lat  = isset($params['lat']) ? $params['lat'] : '';
                    $modelDumpingEvent->lng  = isset($params['lng']) ? $params['lng'] : '';
                    $modelDumpingEvent->location_name  = isset($params['location_name']) ? $params['location_name'] : '';
                    $modelDumpingEvent->incident_type_id  = isset($params['incident_type_id']) ? $params['incident_type_id'] : '';
                    $modelDumpingEvent->save(false);
                    $image = null;
                    if(isset($modelDumpingEvent->fkImage))
                    {
                        $modelImage = $modelDumpingEvent->fkImage;
                        $image = $modelImage->uri_full;
                    }
                    $ret = [
                        'customer_id' => $modelDumpingEvent->account_id_customer,
                        'lat' => floatval($modelDumpingEvent->lat),
                        'lng' => floatval($modelDumpingEvent->lng),
                        'incident_type_id' => $modelDumpingEvent->incident_type_id,
                        'location_name' => $modelDumpingEvent->location_name,
                        'image' => $image_base.$image,
                       
                    ];
                }
                else
                {
                    $msg   = ['Incorrect account id'];
                    $error = ['account_id' => $msg];
                    $ret   = ['errors' => $error];
                }
            }
            else
            {
                $msg   = ['Account id is mandatory'];
                $error = ['account_id' => $msg];
                $ret   = ['errors' => $error];
            }
            break;
        }

        return $ret;
    }

}
