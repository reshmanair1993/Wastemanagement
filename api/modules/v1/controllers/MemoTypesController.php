<?php
namespace mvdapi\modules\v1\controllers;
use yii\rest\ActiveController;
use yii\data\ActiveDataProvider;
use  mvdapi\modules\v1\models\MemoType;
use yii\filters\VerbFilter;
use yii\filters\auth\HttpBearerAuth;

class MemoTypesController extends ActiveController
{
     public $modelClass = '\mvdapi\modules\v1\models\MemoType';

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
       $query = MemoType::getAllQuery();
       $dataProvider =  new ActiveDataProvider([
         'query' => $query

       ]);
       $models = $dataProvider->getModels();
       $ret = [];
       foreach($models as $model) {
         $ret[] = [
           'id' => $model->id,
           'name' => $model->name,
           'description' => $model->description,
           'title' => $model->title,
           'rule_url' => $model->rule_url,
           'other_legal_actions' => $model->other_legal_actions,
           'penalty' => $model->getPenalty()
           ,
         ];

       }
       return $ret;

     }
}
