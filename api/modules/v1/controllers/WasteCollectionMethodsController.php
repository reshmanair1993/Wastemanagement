<?php
namespace api\modules\v1\controllers;
use yii\rest\ActiveController;
use yii\filters\VerbFilter;
use yii\filters\auth\HttpBearerAuth;
use yii\data\ActiveDataProvider;
use api\modules\v1\models\WasteCollectionMethod;
use api\modules\v1\models\WasteCategory;
use api\modules\v1\models\BuildingType;

class WasteCollectionMethodsController extends ActiveController
{
     public $modelClass = '\api\modules\v1\models\WasteCollectionMethod';
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
	 public function actionIndex($type=null,$building_type=null,$facility_availability=null) {
	   $types = ['bio-waste' => 'Bio waste','non-bio-waste' => 'Non bio waste','bio-medical'=>'Bio medical waste'];
	   $ret = [];

	   if(isset($types[$type])&&isset($building_type)) {
		   $type = $types[$type];
		   $modelWasteCategory = WasteCategory::getAllQuery()->andWhere(['name' => $type ])->one();
		   $modelBuildingType = BuildingType::getAllQuery()->andWhere(['name' => $building_type ])->one();
		   if($modelWasteCategory&&$modelBuildingType) {
			   $categoryId = $modelWasteCategory->id;
			   $query = WasteCollectionMethod::getAllQuery()->andWhere(['waste_category_id'=>$categoryId])
			   ->leftjoin('waste_collection_method_building_type','waste_collection_method_building_type.waste_collection_method_id=waste_collection_method.id')
			   ->andWhere(['waste_collection_method_building_type.building_type_id'=>$modelBuildingType->id])
			   ;
			   if($facility_availability==null){
			   	$query->andWhere(['waste_collection_method.facility_provided_by_system'=>1]);
			   }	
			   if($facility_availability=='provided'){
			   	$query->andWhere(['waste_collection_method.facility_provided_by_system'=>1]);
			   }
			   elseif($facility_availability=='not-provided')
			   {
			   		$query->andWhere(['waste_collection_method.facility_provided_by_system'=>0]);
			   }
			   // elseif($facility_availability=='all')
			   // {
			   // 		$query->orWhere(['waste_collection_method.facility_provided_by_system'=>0])
			   // 		->orWhere(['waste_collection_method.facility_provided_by_system'=>1]);
			   // } 
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
				   'collection_available' => $model->collection_available?$model->collection_available:0
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
