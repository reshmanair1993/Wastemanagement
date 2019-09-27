<?php
namespace api\modules\v1\controllers;
use yii\rest\ActiveController;
use yii\filters\VerbFilter;
use yii\filters\auth\HttpBearerAuth;
use yii\data\ActiveDataProvider;
use api\modules\v1\models\WasteCollectionInterval;
use api\modules\v1\models\WasteCategory;
use api\modules\v1\models\BuildingType;

class WasteCollectionIntervalsController extends ActiveController
{
     public $modelClass = '\api\modules\v1\models\WasteCollectionInterval';
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
     public function actionIndex($type=null,$building_type=null) {
        // print_r("expression");die();
       $types = ['bio-waste' => 'Bio waste','non-bio-waste' => 'Non bio waste','bio-medical'=>'Bio medical waste'];
       $ret = [];

       if(isset($types[$type])&&isset($building_type)) {
         // print_r("expressin");die();
           $type = $types[$type];
           $modelWasteCategory = WasteCategory::getAllQuery()->andWhere(['name' => $type ])->one();
           $modelBuildingType = BuildingType::getAllQuery()->andWhere(['name' => $building_type ])->one();
           if($modelWasteCategory&&$modelBuildingType) {
               $categoryId = $modelWasteCategory->id;
              
               $query = WasteCollectionInterval::getAllQuery()->andWhere(['waste_category_id'=>$categoryId])
                ->leftjoin('building_type','building_type.id=waste_collection_interval.building_type_available')
                ->andWhere(['building_type.id'=>$modelBuildingType->id])
               ; 
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
                   // 'collection_available' => $model->collection_available?$model->collection_available:0
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
      public function actionList() {
       $ret = [];
               $query = WasteCollectionInterval::getAllQuery()
               ; 
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
