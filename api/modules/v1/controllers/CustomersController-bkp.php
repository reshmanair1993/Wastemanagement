<?php
namespace api\modules\v1\controllers;
use yii\rest\ActiveController;
use yii\data\ActiveDataProvider;
use  api\modules\v1\models\Customer;
use  api\modules\v1\models\Log;
use  api\modules\v1\models\BuildingType;
use  api\modules\v1\models\TradingType;
use  api\modules\v1\models\ShopType;
use  api\modules\v1\models\FeeCollectionInterval;
use  api\modules\v1\models\WasteCollectionMethod;
use  api\modules\v1\models\WasteCategory;
use  api\modules\v1\models\OfficeType;
use  api\modules\v1\models\Account;
use  api\modules\v1\models\PublicPlaceType;
use  api\modules\v1\models\TerraceFarmingHelpType;
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
     public function log($str) {
     	$modelLog =  new Log;
     	$modelLog->message = $str;
     	$modelLog->save(false); 
     }
     public function actionCreate() {
       $post = Yii::$app->request->post();
       $postJson = json_encode($post);
       $this->log($postJson);
       $modelCustomer = new Customer;
       $bioWasteId = null;
       $nonBioWasteId = null;
       $fields = [
        'ward_id','building_type','building_name','building_number','association_name',
        'association_number','customer_name','customer_phone','address','building_owner_name',
        'building_owner_phone','trading_type','shop_type','has_bio_waste','has_non_bio_waste',
        'has_disposible_waste','lat','lng','fee_collection_interval','has_bio_waste_management_facility',
        'bio_waste_management_facility_operational','bio_waste_management_facility_repair_help_needed',
        'bio_waste_collection_method','bio_waste_collection_method','non_bio_waste_collection_method','has_terrace_farming_interest',
        'terrace_farming_help_type','bio_waste_collection_needed','door_status','house_people_count','house_adult_count','house_children_count','market_visiters_count','auditorium_seating','auditorium_bookings_per_month','flat_house_count','public_place_type','public_gathering','is_programmes_happening','public_place_area','office_type','office_contact_person','office_contact_person_designation','daily_collection_needed_bio','shop_name','licence_no','employee_count'

       ];
       foreach($fields as $key ) {
          if(isset($post[$key])){
            $val = $post[$key];
            $modelCustomer->$key = $val;
          }

       }
       if(  $modelCustomer->validate()) {
         $categories = WasteCategory::getAllQuery()->all();
         foreach($categories as $category) {
           if($category->name == 'Bio waste') {
             $bioWasteId = $category->id;
           }
           if($category->name == 'Non bio waste') {
             $nonBioWasteId = $category->id;
           }

         }


         if($modelCustomer->building_type) {
           $modelBuildingType = BuildingType::findByName($modelCustomer->building_type)->one();
           if($modelBuildingType){
              $modelCustomer->building_type_id = $modelBuildingType->id;
           }
          }

          if($modelCustomer->public_place_type) {
           $modelPublicPlaceType = PublicPlaceType::findByName($modelCustomer->public_place_type)->one();
           if($modelPublicPlaceType){
              $modelCustomer->public_place_type_id = $modelPublicPlaceType->id;
           }
          }
          if($modelCustomer->office_type) {
           $modelOfficeType = OfficeType::findByName($modelCustomer->office_type)->one();
           if($modelOfficeType){
              $modelCustomer->office_type_id = $modelOfficeType->id;
           }
          }

          if($modelCustomer->trading_type) {
            $modelTradingType = TradingType::findByName($modelCustomer->trading_type)->one();
            if($modelTradingType){
              $modelCustomer->trading_type_id = $modelTradingType->id;
            }
         else
           {
            $modelTradingType = new TradingType;
            $modelTradingType->name = $modelCustomer->trading_type;
            $modelTradingType->is_public = 0;
            $modelTradingType->save();
            $modelCustomer->trading_type_id = $modelTradingType->id;
           }
          }

          if($modelCustomer->shop_type) {
            $modelShopType = ShopType::findByName($modelCustomer->shop_type)->one();
            if($modelShopType)
              $modelCustomer->shop_type_id = $modelShopType->id;
          }

          if($modelCustomer->bio_waste_collection_method) {
            $modelWasteCollectionMethod = WasteCollectionMethod::findByName($modelCustomer->bio_waste_collection_method,$bioWasteId)->one();
            if($modelWasteCollectionMethod) {
              $modelCustomer->bio_waste_collection_method_id = $modelWasteCollectionMethod->id;
            }
            else
           {
            $modelWasteCollectionMethod = new WasteCollectionMethod;
            $modelWasteCollectionMethod->name = $modelCustomer->bio_waste_collection_method;
            $modelWasteCollectionMethod->waste_category_id = $bioWasteId;
            $modelWasteCollectionMethod->is_public = 0;
            $modelWasteCollectionMethod->save();
            $modelCustomer->bio_waste_collection_method_id = $modelWasteCollectionMethod->id;
           }
          }

          if($modelCustomer->non_bio_waste_collection_method) {
            $modelWasteCollectionMethod = WasteCollectionMethod::findByName($modelCustomer->non_bio_waste_collection_method,$nonBioWasteId)->one();
            if($modelWasteCollectionMethod){
              $modelCustomer->non_bio_waste_collection_method_id = $modelWasteCollectionMethod->id;
            }
            else
           {
            $modelWasteCollectionMethod = new WasteCollectionMethod;
            $modelWasteCollectionMethod->name = $modelCustomer->non_bio_waste_collection_method;
            $modelWasteCollectionMethod->waste_category_id = $nonBioWasteId;
            $modelWasteCollectionMethod->is_public = 0;
            $modelWasteCollectionMethod->save();
            $modelCustomer->non_bio_waste_collection_method_id = $modelWasteCollectionMethod->id;
           }


          }

          if($modelCustomer->fee_collection_interval) {
            $modelFeeCollectionInterval = FeeCollectionInterval::findByName($modelCustomer->fee_collection_interval )->one();
            if($modelFeeCollectionInterval)
              $modelCustomer->fee_collection_interval_id = $modelFeeCollectionInterval->id;
          }


          if($modelCustomer->terrace_farming_help_type) {
            $modelTerraceFarmingHelpType = TerraceFarmingHelpType::findByName($modelCustomer->terrace_farming_help_type )->one();
            if($modelTerraceFarmingHelpType)
              $modelCustomer->terrace_farming_help_type_id = $modelTerraceFarmingHelpType->id;
          }


          $modelCustomer->lead_person_name = $modelCustomer->customer_name;
          $modelCustomer->lead_person_phone = $modelCustomer->customer_phone;

          $modelCustomer->creator_account_id = Yii::$app->user->identity->id;

          $modelCustomer->status = 1;
          $modelCustomer->save(false);
          $modelAccount = new Account;
          $modelAccount->username = $modelCustomer->lead_person_name;
          $modelAccount->customer_id = $modelCustomer->id;
          $modelAccount->role                 = "customer";
          $modelAccount->person_id = 0;
          $modelAccount->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
          $modelAccount->save(false);
          $ret = [
			"customer_id" => $modelAccount->id
		  ];
       } else {
         $ret = $modelCustomer->errors;
       }
	   


       return $ret;

     }
      public function actionIndex($user_id = null,$page = 1,$per_page = 30){
        $userId = null;
        if(isset(Yii::$app->user->identity)){
          $userId =Yii::$app->user->identity->id;
        }


        $query = Customer::getAllQuery();
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
