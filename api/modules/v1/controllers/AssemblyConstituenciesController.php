<?php
namespace api\modules\v1\controllers;
use yii\rest\ActiveController;
use yii\data\ActiveDataProvider;
use  api\modules\v1\models\AssemblyConstituency;
use yii\filters\VerbFilter;
use yii\filters\auth\HttpBearerAuth;

class AssemblyConstituenciesController extends ActiveController
{
     public $modelClass = '\api\modules\v1\models\AssemblyConstituency';
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
     public function actionIndex($district_id=null){
       $query = AssemblyConstituency::getAllQuery();
       if($district_id) {
         $query->andWhere(['district_id' => $district_id]);
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
           'loksabha_constituency_id' => $model->parliament_constituency_id_2,
           'rajyasabha_constituency_id' => $model->parliament_constituency_id_1,
           'sort_order' => $model->sort_order
         ];

       }
       return $ret;

     }

}
