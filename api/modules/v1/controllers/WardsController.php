<?php
namespace api\modules\v1\controllers;
use yii\rest\ActiveController;
use yii\data\ActiveDataProvider;
use  api\modules\v1\models\Ward;
use yii\filters\VerbFilter;
use yii\filters\auth\HttpBearerAuth;

class WardsController extends ActiveController
{
     public $modelClass = '\api\modules\v1\models\Ward';
     public function actions() {
             $actions = parent::actions();
             $unsetActions = ['create','update','index','delete'];
             foreach($unsetActions as $action) {
               unset($actions[$action]);
             }

             return $actions;
     }  
	 // public function behaviors()
  //   {
  //       return [
  //           'verbs' => [
  //               'class' => VerbFilter::className(),
  //               'actions' => [
  //                   'delete' => ['POST'],
  //               ],
  //           ],
  //           'auth' => [
  //               'class' => HttpBearerAuth::className(),
  //           ]
  //       ];
  //   }
     public function actionIndex($lsgi_id=null){
       $query = Ward::getAllQuery();
       if($lsgi_id) {
         $query->andWhere(['lsgi_id' => $lsgi_id])
                ->andWhere(['status'=>1]);
       }
       $dataProvider =  new ActiveDataProvider([
         'query' => $query,
         'pagination' => false

       ]);
       $models = $dataProvider->getModels();
       $ret = [];
       foreach($models as $model) {
         $ret[] = [
           'id' => $model->id,
           'name' => $model->name,
           'code' => $model->code,
           'lsgi_id' => $model->lsgi_id,
           'sort_order' => $model->sort_order
         ];

       }
       return $ret;

     }

}
