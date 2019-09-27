<?php
namespace api\modules\v1\controllers;
use yii\rest\ActiveController;
use yii\data\ActiveDataProvider;
use  api\modules\v1\models\BuildingType;
use  api\modules\v1\models\BuildingTypeSubTypes;
use yii\filters\VerbFilter;
use yii\filters\auth\HttpBearerAuth;

class BuildingTypeController extends ActiveController
{
     public $modelClass = '\api\modules\v1\models\BuildingType';
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
       $query = BuildingType::getAllQuery();
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
     public function actionBuildingTypeSubTypes($building_type=null) {
     $ret = [];

     if(isset($building_type)) {
       $modelBuildingType = BuildingType::getAllQuery()->andWhere(['name' => $building_type ])->one();
       if($modelBuildingType) {
         $query = BuildingTypeSubTypes::getAllQuery()
         ->andWhere(['building_type_sub_types.building_type_id'=>$modelBuildingType->id])
         ->andWhere(['building_type_sub_types.status'=>1]);   
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
