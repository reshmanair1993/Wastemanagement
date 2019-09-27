<?php
namespace api\modules\v1\controllers;
use Yii;
use yii\rest\ActiveController;
use yii\data\ActiveDataProvider;
use  api\modules\v1\models\WasteQuality;
use yii\filters\VerbFilter;
use yii\filters\auth\HttpBearerAuth;

class WasteQualitiesController extends ActiveController
{
     public $modelClass = '\api\modules\v1\models\WasteQuality';

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
     public function actionIndex(){
      $image_base = isset(Yii::$app->params['base_url_gt']) ? Yii::$app->params['base_url_gt'] : null;
       $query = WasteQuality::getAllQuery();
       $dataProvider =  new ActiveDataProvider([
         'query' => $query

       ]);
       $models = $dataProvider->getModels();
       $ret = [];
       foreach($models as $model) {
         $ret[] = [
           'id' => $model->id,
           'name' => $model->name,
         ];

       }
       return $ret;
     }
}
