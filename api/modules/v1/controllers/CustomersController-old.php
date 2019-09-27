<?php
namespace api\modules\v1\controllers;
use yii\rest\ActiveController;
use yii\data\ActiveDataProvider;
use  api\modules\v1\models\Customer;
use yii\filters\VerbFilter;
use yii\filters\auth\HttpBearerAuth;
use Yii;

class CustomersController extends ActiveController
{
     public $modelClass = '\api\modules\v1\models\Customer';
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
             $unsetActions = ['create','update','delete','index'];
             foreach($unsetActions as $action) {
               unset($actions[$action]);
             }

             return $actions;
     }
      public function actionIndex($state_id = null,$page = 1,$per_page = 30){
        $userId = null;
        if(isset(Yii::$app->user->identity)){
          $userId =Yii::$app->user->identity->id;
        }


        $query = Customer::getAllQuery();
        if($state_id) {
          $query->andWhere(['state_id' => $state_id]);
        }
        if($userId){
          $query->andWhere(['creator_account_id' => $userId]);
        }
        $dataProvider =  new ActiveDataProvider([
          'query' => $query,
          'pagination' => [
            'pageSize' => $per_page,
            'page' => $page - 1,
          ],

        ]);
        $models = $dataProvider->getModels();
        $ret = [];
        foreach($models as $model) {
          $ret[] = [
            'id' => $model->id,
            'building_type_id'  => $model->building_type_id,
            'building_name' => $model->building_name,
            'building_number' => $model->building_number,
            'association_name' => $model->association_name,
            'association_number' => $model->association_number,
            'lead_person_name' => $model->lead_person_name,
            'lead_person_phone' => $model->lead_person_phone,
            'address' => $model->address,
            'building_owner_name' => $model->building_owner_name,
            'building_owner_phone' => $model->building_owner_phone,
            'trading_type_id' => $model->trading_type_id,
            'shop_type_id' => $model->shop_type_id,
            'has_bio_waste' => $model->has_bio_waste,
            'has_non_bio_waste' => $model->has_non_bio_waste,
            'has_disposible_waste' => $model->has_disposible_waste,
            'lat' => $model->lat,
            'lng' => $model->lng,
            'fee_collection_interval_id' => $model->fee_collection_interval_id,
            'has_bio_waste_management_facility' => $model->has_bio_waste_management_facility,
            'bio_waste_management_facility_operational' => $model->bio_waste_management_facility_operational,
            'bio_waste_management_facility_repair_help_needed' => $model->bio_waste_management_facility_repair_help_needed,
            'bio_waste_collection_method_id' => $model->bio_waste_collection_method_id,
            'bio_waste_collection_needed' => $model->bio_waste_collection_needed,
            'non_bio_waste_collection_method_id' => $model->non_bio_waste_collection_method_id,
            'has_terrace_farming_interest' => $model->has_terrace_farming_interest,
            'terrace_farming_help_type_id' => $model->terrace_farming_help_type_id,
          ];

        }
        return $ret;
      }

}
