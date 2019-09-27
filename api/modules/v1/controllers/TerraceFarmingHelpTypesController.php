<?php
namespace api\modules\v1\controllers;
use yii\rest\ActiveController;
use yii\filters\VerbFilter;
use yii\filters\auth\HttpBearerAuth;
use yii\data\ActiveDataProvider; 
use yii\web\Controller;
use api\modules\v1\models\TerraceFarmingHelpType;

class TerraceFarmingHelpTypesController extends Controller
{ 
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
	public function actions() {
             $actions = parent::actions();
             $unsetActions = ['create','update','delete','list','index'];
             foreach($unsetActions as $action) {
               unset($actions[$action]);
             }

             return $actions;
     }
	 public function actionIndex() {
		 
	   $query = TerraceFarmingHelpType::getAllQuery();	 
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
