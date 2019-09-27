<?php
namespace api\modules\v1\controllers;
use yii\rest\ActiveController;
use yii\data\ActiveDataProvider;
use  api\modules\v1\models\AdministrationType;
use yii\filters\VerbFilter;
use yii\filters\auth\HttpBearerAuth;

class AdministrationTypesController extends ActiveController
{
     public $modelClass = '\api\modules\v1\models\AdministrationType';

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
       $query = AdministrationType::getAllQuery();
       $dataProvider =  new ActiveDataProvider([
         'query' => $query

       ]);
       $models = $dataProvider->getModels();
       $ret = [];
       foreach($models as $model) {
         $ret[] = [
           'id' => $model->id,
           'name' => $model->name,
           'sort_order' => $model->sort_order,
         ];

       }
       return $ret;
     }
}
