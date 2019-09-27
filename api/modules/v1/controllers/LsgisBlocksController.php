<?php
namespace api\modules\v1\controllers;
use yii\rest\ActiveController;
use yii\data\ActiveDataProvider;
use  api\modules\v1\models\LsgiBlock;
use yii\filters\VerbFilter;
use yii\filters\auth\HttpBearerAuth;

class LsgisBlocksController extends ActiveController
{
     public $modelClass = '\api\modules\v1\models\LsgiBlock';
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
     public function actionIndex($assembly_constituency_id=null){
       $query = LsgiBlock::getAllQuery();
       if($assembly_constituency_id) {
         $query->andWhere(['assembly_constituency_id' => $assembly_constituency_id]);
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
           'code' => $model->code,
           'assembly_constituency_id' => $model->assembly_constituency_id,
           'sort_order' => $model->sort_order
         ];

       }
       return $ret;

     }

}
