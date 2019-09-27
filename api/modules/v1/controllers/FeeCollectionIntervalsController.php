<?php
namespace api\modules\v1\controllers;
use yii\rest\ActiveController;
use yii\data\ActiveDataProvider;
use  api\modules\v1\models\FeeCollectionInterval;
use  api\modules\v1\models\BuildingType;
use yii\filters\VerbFilter;
use yii\filters\auth\HttpBearerAuth;

class FeeCollectionIntervalsController extends ActiveController
{
     public $modelClass = '\api\modules\v1\models\FeeCollectionInterval';

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
     // public function actionIndex($building_type=null){
     //   $query = FeeCollectionInterval::getAllQuery();
     //   $dataProvider =  new ActiveDataProvider([
     //     'query' => $query

     //   ]);
     //   $models = $dataProvider->getModels();
     //   $ret = [];
     //   foreach($models as $model) {
     //     $ret[] = [
     //       'id' => $model->id,
     //       'name' => $model->name,
     //     ];

     //   }
     //   return $ret;
     // }
     public function actionIndex($building_type=null) {
     $ret = [];

     if(isset($building_type)) {
       $modelBuildingType = BuildingType::getAllQuery()->andWhere(['name' => $building_type ])->one();
       if($modelBuildingType) {
         $query = FeeCollectionInterval::getAllQuery()
         ->leftjoin('fee_collection_interval_building_type','fee_collection_interval_building_type.fee_collection_interval_id=fee_collection_interval.id')
         ->andWhere(['fee_collection_interval_building_type.building_type_id'=>$modelBuildingType->id])
         ->andWhere(['fee_collection_interval.status'=>1]);   
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
       }
       else
       {
        $msg = ['Invalid Building Type'];
        $error = ['building_type'=>$msg];
        $ret = ['errors' =>$error];
       }
     }
       return $ret;
   }
}
