<?php
namespace api\modules\v1\controllers;
use yii\rest\ActiveController;
use yii\data\ActiveDataProvider;
use  api\modules\v1\models\Lsgi;
use yii\filters\VerbFilter;
use yii\filters\auth\HttpBearerAuth;

class LsgisController extends ActiveController
{
     public $modelClass = '\api\modules\v1\models\Lsgi';

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
     public function actionIndex($block_id=null){
       $query = Lsgi::getAllQuery();
       if($block_id) {
         $query->andWhere(['block_id' => $block_id]);
       }
       $dataProvider =  new ActiveDataProvider([
         'query' => $query

       ]);
       $models = $dataProvider->getModels();
       $ret = [];
       foreach($models as $model) {
         $ret[] = [
           'id' => $model->id,
           'name' => $model->name,
           'block_id' => $model->block_id,
           'sort_order' => $model->sort_order,
         ];

       }
       return $ret;

     }

}
