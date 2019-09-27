<?php
namespace api\modules\v1\controllers;
use yii\rest\ActiveController;
use yii\data\ActiveDataProvider;
use  api\modules\v1\models\CameraInstallationCheckList;
use  api\modules\v1\models\Image;
use Yii;
use yii\filters\VerbFilter;
use yii\filters\auth\HttpBearerAuth;

class CameraInstallationController extends ActiveController
{
     public $modelClass = '\api\modules\v1\models\CameraInstallationCheckList';
     public function actions() {
             $actions = parent::actions();
             $unsetActions = ['create','update','index','delete'];
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
     public function actionGetCameraInstallationCheckList(){
       $query = CameraInstallationCheckList::getAllQuery();
       $image_base = isset(Yii::$app->params['base_url']) ? Yii::$app->params['base_url'] : null;
       $dataProvider =  new ActiveDataProvider([
         'query' => $query

       ]);
       $models = $dataProvider->getModels();
       $ret = [];
       foreach($models as $model) {
         $image_id = $model->image_id;
         $modelImage = Image::find()->where(['status'=>1,'id'=>$image_id])->one();
         $image = null;
         if($modelImage){
           $image = $modelImage->uri_full;
         }
         $ret[] = [
           'id' => $model->id,
           'name' => $model->name,
           'image' => $image,
         ];
         $return = [
           'image_base' => $image_base,
           'items' => $ret,
         ];
       }
       return $return;

     }


}
