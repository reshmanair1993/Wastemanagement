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
use  api\modules\v1\models\WasteCollectionInterval;
use  api\modules\v1\models\WasteCategory;
use  api\modules\v1\models\OfficeType;
use  api\modules\v1\models\Account;
use  api\modules\v1\models\Image;
use  api\modules\v1\models\PublicPlaceType;
use  api\modules\v1\models\PublicGatheringMethod;
use  api\modules\v1\models\BuildingTypeSubTypes;
use  api\modules\v1\models\TerraceFarmingHelpType;
use  api\modules\v1\models\AdministrationType;
use  api\modules\v1\models\AccountService;
use  api\modules\v1\models\ServiceEnablerSettings;
use  api\modules\v1\models\LsgiServiceFee;
use yii\web\UploadedFile;
use yii\filters\VerbFilter;
use yii\filters\auth\HttpBearerAuth;
use api\modules\v1\models\SurveyCustomerCount;
use api\modules\v1\models\WardCustomerCount;
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
     public function assignAuthRole($roleName,$accountId){
      $auth = Yii::$app->authManager;
      $role = $auth->getRole($roleName);
      $modelRole = Account::find()->where(['role'=>$roleName])->andWhere(['status'=>1])->one();
      if(!$modelRole){
        $modelRole = new Account;
        $modelRole->name = $roleName;
        $modelRole->save(false);
      }
      if(!$role){
        $role = $auth->createRole($roleName);
        $auth->add($role);
      }
      $roles = \Yii::$app->authManager->getRolesByUser($accountId);
      if(!isset($roles[$roleName]))
        $auth->assign($role,$accountId);
    }
  //    public function actionCreate($account_id=null) {
  //     $wardId = null;
  //      $post = Yii::$app->request->post();
  //      $photo = $_FILES;
  //      $merged_array = array_merge($post,$photo);
  //      $postJson = json_encode($merged_array);
  //      $this->log($postJson);
  //      $modelImage = new Image;
  //      $image = null;
  //      $images = UploadedFile::getInstanceByName('photo');
  //      // $modelImage = $this->getProfilePic();
  //      $modelImageSaveId = $modelImage->uploadAndSave($images);
  //      $modelSurveyor = Account::find()->where(['id'=>Yii::$app->user->identity->id])->one();
  //      // if($modelSurveyor&&$modelSurveyor->role=='surveyor'){
  //      if($account_id)
  //      {
  //       $modelAccount = Account::find()->where(['id'=>$account_id])->one();
  //       if($modelAccount){
  //         $modelCustomer = Customer::find()->where(['id'=>$modelAccount->customer_id])->one();
  //         $image = $modelCustomer->image_id?$modelCustomer->image_id:"";
  //         $wardId = $modelCustomer->ward_id;
  //       }
  //      }
  //      else{
  //         $modelCustomer = new Customer;
  //      }
  //      $bioWasteId = null;
  //      $nonBioWasteId = null;
  //      $bioMedicalWasteId = null;
  //      $fields = [
  //       'ward_id','building_type','parent_account_id','image_id','building_name','building_number','association_name',
  //       'association_number','customer_name','customer_phone','address','building_owner_name',
  //       'building_owner_phone','trading_type','shop_type','has_bio_waste','has_non_bio_waste',
  //       'has_disposible_waste','lat','lng','fee_collection_interval','has_bio_waste_management_facility',
  //       'bio_waste_management_facility_operational','bio_waste_management_facility_repair_help_needed',
  //       'bio_waste_collection_method','non_bio_waste_collection_method','has_terrace_farming_interest',
  //       'terrace_farming_help_type','bio_waste_collection_needed','door_status','people_count','house_adult_count','house_children_count','market_visiters_count','seating_capacity','monthly_booking_count','house_count','public_place_type','public_gathering_method','is_programmes_happening','public_place_area','office_type','office_contact_person','office_contact_person_designation','daily_collection_needed_bio','shop_name','licence_no','employee_count','space_available_for_bio_waste_management_facility','help_needed_for_bio_waste_management_facility_construction','building_in_use','has_space_for_non_bio_waste_management_facility','space_available_for_non_bio_waste_management_facility','has_interest_for_allotting_space_for_non_bio_management_facility','has_interest_in_bio_waste_management_facility','green_protocol_system_implemented','bio_medical_waste_collection_facility','has_bio_medical_incinerator',
  //       'bio_medical_waste_collection_method','building_area','has_public_program_option','lead_person_designation','administration_type','public_program_count','has_non_bio_waste_management_facility','building_sub_type','daily_bio_waste_quantity','has_interest_in_system_provided_bio_facility','waste_collection_interval_id','has_public_toilet','public_toilet_count','public_toilet_count_men','public_toilet_count_women','residential_association_id',

  //      ];
  //      foreach($fields as $key ) {
  //         if(isset($post[$key])){
  //           $val = $post[$key];
  //           $modelCustomer->$key = $val;
  //         }

  //      }
  //      if($modelCustomer->validate()&& $modelCustomer->building_type ) {
  //       // && $modelCustomer->building_type
  //        $categories = WasteCategory::getAllQuery()->all();
  //        foreach($categories as $category) {
  //          if($category->name == 'Bio waste') {
  //            $bioWasteId = $category->id;
  //          }
  //          if($category->name == 'Non bio waste') {
  //            $nonBioWasteId = $category->id;
  //          }
  //          if($category->name == 'Bio medical waste') {
  //            $bioMedicalWasteId = $category->id;
  //          }

  //        }
  //        if($modelCustomer->ward_id&&!$account_id)
  //        {
  //         $qry1 = "SELECT max(customer_id) as customer_id FROM `customer` where status=1 and ward_id=:ward_id";
  //         $ward_id = $modelCustomer->ward_id;
  //         $command1 =  Yii::$app->db->createCommand($qry1);
  //         $command1->bindParam(':ward_id',$ward_id);
  //         $data1 = $command1->queryAll();
  //         $maxCount = $data1[0];
  //         $max = $maxCount['customer_id'];
  //         if($max):
  //         $modelCustomer->customer_id = $max+1; 
  //       else:
  //         $modelCustomer->customer_id = 1;
  //       endif;
  //        }elseif($modelCustomer->ward_id&&$account_id&&$wardId)
  //        {
  //           if($wardId!=$modelCustomer->ward_id)
  //           {
  //              $qry1 = "SELECT max(customer_id) as customer_id FROM `customer` where status=1 and ward_id=:ward_id";
  //         $ward_id = $modelCustomer->ward_id;
  //         $command1 =  Yii::$app->db->createCommand($qry1);
  //         $command1->bindParam(':ward_id',$ward_id);
  //         $data1 = $command1->queryAll();
  //         $maxCount = $data1[0];
  //         $max = $maxCount['customer_id'];
  //         if($max):
  //         $modelCustomer->customer_id = $max+1; 
  //       else:
  //         $modelCustomer->customer_id = 1;
  //       endif;
  //           }
  //        }
  //       if($modelCustomer->public_gathering_method) {
  //           $modelPublicGatheringMethod = PublicGatheringMethod::findByName($modelCustomer->public_gathering_method)->one();
  //           if($modelPublicGatheringMethod){
  //             $modelCustomer->public_gathering_method = $modelPublicGatheringMethod->id;
  //           }
  //        else
  //          {
  //           $modelPublicGatheringMethod = new PublicGatheringMethod;
  //           $modelPublicGatheringMethod->name = $modelCustomer->public_gathering_method;
  //           $modelPublicGatheringMethod->save(false);
  //           $modelCustomer->public_gathering_method = $modelPublicGatheringMethod->id;
  //          }
  //         }
  //        if($modelCustomer->administration_type) {
  //           $modelAdministrationType = AdministrationType::findByName($modelCustomer->administration_type)->one();
  //           if($modelAdministrationType){
  //             $modelCustomer->administration_type = $modelAdministrationType->id;
  //           }
  //        else
  //          {
  //           $modelAdministrationType = new AdministrationType;
  //           $modelAdministrationType->name = $modelCustomer->administration_type;
  //           $modelAdministrationType->save();
  //           $modelCustomer->administration_type = $modelAdministrationType->id;
  //          }
  //         }
  //         if($modelCustomer->waste_collection_interval_id) {
  //           $modelWasteCollectionInterval = WasteCollectionInterval::findByName($modelCustomer->waste_collection_interval_id)->one();
  //           if($modelWasteCollectionInterval){
  //             $modelCustomer->waste_collection_interval_id = $modelWasteCollectionInterval->id;
  //           }
  //        else
  //          {
  //           $modelWasteCollectionInterval = new WasteCollectionInterval;
  //           $modelWasteCollectionInterval->name = $modelCustomer->waste_collection_interval_id;
  //           $modelWasteCollectionInterval->save();
  //           $modelCustomer->waste_collection_interval_id = $modelWasteCollectionInterval->id;
  //          }
  //         }


  //        if($modelCustomer->building_type) {
  //          $modelBuildingType = BuildingType::findByName($modelCustomer->building_type)->one();
  //          if($modelBuildingType){
  //             $modelCustomer->building_type_id = $modelBuildingType->id;
  //          }
  //         }
  //         if($modelCustomer->building_type_id==5||$modelCustomer->building_type_id==9||$modelCustomer->building_type_id==10)
  //         {
  //           $modelCustomer->door_status = 1;
  //         }
  //         if($modelCustomer->building_sub_type) {
  //          $modelBuildingTypeSubTypes = BuildingTypeSubTypes::findByName($modelCustomer->building_sub_type)->one();
  //          if($modelBuildingTypeSubTypes){
  //             $modelCustomer->building_sub_type = $modelBuildingTypeSubTypes->id;
  //          }
  //         }

  //         if($modelCustomer->public_place_type) {
  //          $modelPublicPlaceType = PublicPlaceType::findByName($modelCustomer->public_place_type)->one();
  //          if($modelPublicPlaceType){
  //             $modelCustomer->public_place_type_id = $modelPublicPlaceType->id;
  //          }
  //         }
  //         if($modelCustomer->office_type) {
  //          $modelOfficeType = OfficeType::findByName($modelCustomer->office_type)->one();
  //          if($modelOfficeType){
  //             $modelCustomer->office_type_id = $modelOfficeType->id;
  //          }
  //         }

  //         if($modelCustomer->trading_type) {
  //           $modelTradingType = TradingType::findByName($modelCustomer->trading_type)->one();
  //           if($modelTradingType){
  //             $modelCustomer->trading_type_id = $modelTradingType->id;
  //           }
  //        else
  //          {
  //           $modelTradingType = new TradingType;
  //           $modelTradingType->name = $modelCustomer->trading_type;
  //           $modelTradingType->is_public = 0;
  //           $modelTradingType->save();
  //           $modelCustomer->trading_type_id = $modelTradingType->id;
  //          }
  //         }

  //         if($modelCustomer->shop_type) {
  //           $modelShopType = ShopType::findByName($modelCustomer->shop_type)->one();
  //           if($modelShopType)
  //             $modelCustomer->shop_type_id = $modelShopType->id;
  //         }

  //         if($modelCustomer->bio_waste_collection_method) {
  //           $modelWasteCollectionMethod = WasteCollectionMethod::findByName($modelCustomer->bio_waste_collection_method,$bioWasteId)->one();
  //           if($modelWasteCollectionMethod) {
  //             $modelCustomer->bio_waste_collection_method_id = $modelWasteCollectionMethod->id;
  //           }
  //           else
  //          {
  //           $modelWasteCollectionMethod = new WasteCollectionMethod;
  //           $modelWasteCollectionMethod->name = $modelCustomer->bio_waste_collection_method;
  //           $modelWasteCollectionMethod->waste_category_id = $bioWasteId;
  //           $modelWasteCollectionMethod->is_public = 0;
  //           $modelWasteCollectionMethod->save();
  //           $modelCustomer->bio_waste_collection_method_id = $modelWasteCollectionMethod->id;
  //          }
  //         }
  //         if($modelCustomer->non_bio_waste_collection_method) {
  //           $modelWasteCollectionMethod = WasteCollectionMethod::findByName($modelCustomer->non_bio_waste_collection_method,$nonBioWasteId)->one();
  //           if($modelWasteCollectionMethod) {
  //             $modelCustomer->non_bio_waste_collection_method_id = $modelWasteCollectionMethod->id;
  //           }
  //           else
  //          {
  //           $modelWasteCollectionMethod = new WasteCollectionMethod;
  //           $modelWasteCollectionMethod->name = $modelCustomer->non_bio_waste_collection_method;
  //           $modelWasteCollectionMethod->waste_category_id = $nonBioWasteId;
  //           $modelWasteCollectionMethod->is_public = 0;
  //           $modelWasteCollectionMethod->save();
  //           $modelCustomer->non_bio_waste_collection_method_id = $modelWasteCollectionMethod->id;
  //          }
  //         }
  //         if($modelCustomer->bio_medical_waste_collection_method) {
  //           $modelWasteCollectionMethod = WasteCollectionMethod::findByName($modelCustomer->bio_medical_waste_collection_method,$bioMedicalWasteId)->one();
  //           if($modelWasteCollectionMethod) {
  //             $modelCustomer->bio_medical_waste_collection_method = $modelWasteCollectionMethod->id;
  //           }
  //           else
  //          {
  //           $modelWasteCollectionMethod = new WasteCollectionMethod;
  //           $modelWasteCollectionMethod->name = $modelCustomer->bio_medical_waste_collection_method;
  //           $modelWasteCollectionMethod->waste_category_id = $bioMedicalWasteId;
  //           $modelWasteCollectionMethod->is_public = 0;
  //           $modelWasteCollectionMethod->save();
  //           $modelCustomer->bio_medical_waste_collection_method = $modelWasteCollectionMethod->id;
  //          }
  //         }

  //         if($modelCustomer->fee_collection_interval) {
  //           $modelFeeCollectionInterval = FeeCollectionInterval::findByName($modelCustomer->fee_collection_interval )->one();
  //           if($modelFeeCollectionInterval)
  //             $modelCustomer->fee_collection_interval_id = $modelFeeCollectionInterval->id;
  //         }


  //         if($modelCustomer->terrace_farming_help_type) {
  //           $modelTerraceFarmingHelpType = TerraceFarmingHelpType::findByName($modelCustomer->terrace_farming_help_type )->one();
  //           if($modelTerraceFarmingHelpType)
  //             $modelCustomer->terrace_farming_help_type_id = $modelTerraceFarmingHelpType->id;
  //         }


  //         $modelCustomer->lead_person_name = $modelCustomer->customer_name;
  //         $modelCustomer->lead_person_phone = $modelCustomer->customer_phone;
  //         if(isset($modelImageSaveId)&&$modelImageSaveId!=null){
  //         $modelCustomer->image_id = $modelImageSaveId;
  //       }
  //       else
  //       {
  //         $modelCustomer->image_id = $image;
  //       }
  //       $modelUser = Yii::$app->user->identity;
  //       if($modelUser->role=='surveyor')
  //         $modelCustomer->creator_account_id = Yii::$app->user->identity->id;

  //         $modelCustomer->status = 1;
  //         $qry ="SELECT customer.building_number as building_number,customer.lead_person_name as lead_person_name,customer.lead_person_phone as lead_person_phone from customer where customer.creator_account_id=:creator and status=1 and customer.created_at>= DATE_SUB(NOW(),INTERVAL 5 MINUTE) and customer.lead_person_name=:name and customer.lead_person_phone=:phone and customer.building_number=:building_number";
  //         $creator = Yii::$app->user->identity->id;
  //         $name = $modelCustomer->lead_person_name;
  //         $phone = $modelCustomer->lead_person_phone;
  //         $building_number = $modelCustomer->building_number;
  //         $command =  Yii::$app->db->createCommand($qry);
  //       $command->bindParam(':creator',$creator);
  //       $command->bindParam(':name',$name);
  //       $command->bindParam(':phone',$phone);
  //       $command->bindParam(':building_number',$building_number);
  //       $customersList = $command->queryAll();

  //       if(!sizeof($customersList)>0){
  //       $modelCustomer->save(false);
  //     }
  //     else
  //     {
  //       $msg = ['Duplicate entries not allowed'];
  //       $error = ['lead_person_name'=>$msg];
  //       $ret = ['errors' =>$error];
  //       return $ret;
  //     }
  //         if(!$account_id){
  //         $modelAccount = new Account;
  //       }
  //         $modelAccount->parent_id = $modelCustomer->parent_account_id;
  //         if(isset($modelImageSaveId)&&$modelImageSaveId!=null){
  //         $modelAccount->image_id = $modelImageSaveId;
  //       }
  //       else
  //       {
  //         $modelAccount->image_id = $image;
  //       }
  //       $modelAccount->username = Customer::generateCustomerId($modelCustomer->id);
  //         // $modelAccount->username = $modelCustomer->lead_person_name.$modelCustomer->id;
  //        $password = '12345678';
  //        $modelAccount->password_hash = Yii::$app->security->generatePasswordHash($password);
  //         $modelAccount->customer_id = $modelCustomer->id;
  //         $modelAccount->role                 = "customer";
  //         $modelAccount->person_id = 0;
  //         $modelAccount->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
  //         $modelAccount->save(false);
  //         $this->assignAuthRole('customer',$modelAccount->id);
  //          if(!$account_id){
  //         // Customer::generate($modelCustomer->lead_person_phone,$modelCustomer->id,$modelAccount->username,$password);
  //       }
  //         if(!$account_id):
  //         $modelServiceEnablerSettings = ServiceEnablerSettings::getAllQuery()->andWhere(['status' => 1]);
  //         $serviceEnablerSettingsDataProvider = new ActiveDataProvider([
  //           'query'      => $modelServiceEnablerSettings,
  //           'pagination' => false

  //       ]);
  //       $serviceEnablerSettingsModels = $serviceEnablerSettingsDataProvider->getModels();
  //       if(sizeof($serviceEnablerSettingsModels)>0){
  //       foreach ($serviceEnablerSettingsModels as $serviceEnablerSettingsModel) {
  //         $enableField = $serviceEnablerSettingsModel->customer_field;
  //         if($modelCustomer[$enableField]==$serviceEnablerSettingsModel->customer_field_value){
  //             $modelAccountService = new AccountService;
  //             $modelAccountService->service_id = $serviceEnablerSettingsModel->service_id;
  //             $modelAccountService->account_id = $modelAccount->id;
  //             // $modelAccountService->save(false);

  //       }
  //   }
  // }
  // $modelBuildingType = BuildingType::find()->where(['id'=>$modelCustomer->building_type_id])->andWhere(['status'=>1])->one();
  // if($modelBuildingType->residence_category_id)
  // {
  //   $lsgi = isset($modelCustomer->fkWard->lsgi_id)?$modelCustomer->fkWard->lsgi_id:null;
  //   $modelLsgiServiceFee = LsgiServiceFee::find()->where(['lsgi_id'=>$lsgi])->andWhere(['residence_category_id'=>$modelBuildingType->residence_category_id])->andWhere(['status'=>1])->all();
  //   if($modelLsgiServiceFee)
  //   {
  //     foreach ($modelLsgiServiceFee as $key => $value) {
  //       $modelAccountService = new AccountService;
  //       $accountServiceData = AccountService::find()->where(['service_id'=>$value->service_id])->andWhere(['account_id'=>$modelAccount->id])->andWhere(['status'=>1])->one();
  //       if(!$accountServiceData){
  //       $modelAccountService->service_id = $value->service_id;
  //       $modelAccountService->account_id = $modelAccount->id;
  //       // $modelAccountService->save(false);
  //     }
  //     }
  //   }
  // }
  // endif;
  //         // Customer::generate($modelCustomer->lead_person_phone,$modelCustomer->id);
  //         $ret = [
		// 	      "account_id" => $modelAccount->id
		//       ];
  //      } else {
  //       $postJson = json_encode($modelCustomer->errors);
  //       $this->log($postJson);
  //       $msg = ['Building type is mandatory'];
  //       $error = ['building_type'=>$msg];
  //       $ret = ['errors' =>$error];
  //        // $ret = $modelCustomer->errors;
  //      }
  //      // $modelImage = new Image;
  //      // print_r($modelImage);exit;
  //    // }
  //    // else
  //    // {
  //    //    $msg = ['Permission denied'];
  //    //    $error = ['username'=>$msg];
  //    //    $ret = ['errors' =>$error];
  //    // }
  //      return $ret;

  //    }
     public function actionCreate($account_id=null) {
      $wardId = null;
      $doorStatus = null;
      $currentDate = date('Y-m-d');
       $post = Yii::$app->request->post();
       $photo = $_FILES;
       $merged_array = array_merge($post,$photo);
       $postJson = json_encode($merged_array);
       // $this->log($postJson);
       $modelImage = new Image;
       $image = null;
       $images = UploadedFile::getInstanceByName('photo');
       // $modelImage = $this->getProfilePic();
       $modelImageSaveId = $modelImage->uploadAndSave($images);
       $modelSurveyor = Account::find()->where(['id'=>Yii::$app->user->identity->id])->one();
       // if($modelSurveyor&&$modelSurveyor->role=='surveyor'){
       if($account_id)
       {
        $modelAccount = Account::find()->where(['id'=>$account_id])->one();
        if($modelAccount){
          $modelCustomer = Customer::find()->where(['id'=>$modelAccount->customer_id])->one();
          $image = $modelCustomer->image_id?$modelCustomer->image_id:"";
          $wardId = $modelCustomer->ward_id;
          $doorStatus = $modelCustomer->door_status;
        }
       }
       else{
          $modelCustomer = new Customer;
       }
       $bioWasteId = null;
       $nonBioWasteId = null;
       $bioMedicalWasteId = null;
       $fields = [
        'ward_id','building_type','parent_account_id','image_id','building_name','building_number','association_name',
        'association_number','customer_name','customer_phone','address','building_owner_name',
        'building_owner_phone','trading_type','shop_type','has_bio_waste','has_non_bio_waste',
        'has_disposible_waste','lat','lng','fee_collection_interval','has_bio_waste_management_facility',
        'bio_waste_management_facility_operational','bio_waste_management_facility_repair_help_needed',
        'bio_waste_collection_method','non_bio_waste_collection_method','has_terrace_farming_interest',
        'terrace_farming_help_type','bio_waste_collection_needed','door_status','people_count','house_adult_count','house_children_count','market_visiters_count','seating_capacity','monthly_booking_count','house_count','public_place_type','public_gathering_method','is_programmes_happening','public_place_area','office_type','office_contact_person','office_contact_person_designation','daily_collection_needed_bio','shop_name','licence_no','employee_count','space_available_for_bio_waste_management_facility','help_needed_for_bio_waste_management_facility_construction','building_in_use','has_space_for_non_bio_waste_management_facility','space_available_for_non_bio_waste_management_facility','has_interest_for_allotting_space_for_non_bio_management_facility','has_interest_in_bio_waste_management_facility','green_protocol_system_implemented','bio_medical_waste_collection_facility','has_bio_medical_incinerator',
        'bio_medical_waste_collection_method','building_area','has_public_program_option','lead_person_designation','administration_type','public_program_count','has_non_bio_waste_management_facility','building_sub_type','daily_bio_waste_quantity','has_interest_in_system_provided_bio_facility','waste_collection_interval_id','has_public_toilet','public_toilet_count','public_toilet_count_men','public_toilet_count_women','residential_association_id',

       ];
       foreach($fields as $key ) {
          if(isset($post[$key])){
            $val = $post[$key];
            $modelCustomer->$key = $val;
          }

       }
       if($modelCustomer->validate()&& $modelCustomer->building_type ) {
        // && $modelCustomer->building_type
        $modelCustomer->building_type = ucfirst($modelCustomer->building_type);
         $categories = WasteCategory::getAllQuery()->all();
         foreach($categories as $category) {
           if($category->name == 'Bio waste') {
             $bioWasteId = $category->id;
           }
           if($category->name == 'Non bio waste') {
             $nonBioWasteId = $category->id;
           }
           if($category->name == 'Bio medical waste') {
             $bioMedicalWasteId = $category->id;
           }

         }
         if($modelCustomer->ward_id&&!$account_id)
         {
          $qry1 = "SELECT max(customer_id) as customer_id FROM `customer` where status=1 and ward_id=:ward_id and is_public_customer=0";
          $ward_id = $modelCustomer->ward_id;
          $command1 =  Yii::$app->db->createCommand($qry1);
          $command1->bindParam(':ward_id',$ward_id);
          $data1 = $command1->queryAll();
          $maxCount = $data1[0];
          $max = $maxCount['customer_id'];
          if($max):
          $modelCustomer->customer_id = $max+1; 
        else:
          $modelCustomer->customer_id = 1;
        endif;
        
        $modelWardCustomerCount = WardCustomerCount::find()->where(['ward_id'=>$modelCustomer->ward_id])->andWhere(['status'=>1])->andWhere(['date'=>$currentDate])->one();
          if($modelWardCustomerCount){
            $modelWardCustomerCount->count = $modelWardCustomerCount->count + 1;
            if($modelCustomer->building_type=='House'){
              $modelWardCustomerCount->house_count = $modelWardCustomerCount->house_count + 1;
            }elseif($modelCustomer->building_type=='Shop'){
              $modelWardCustomerCount->shop_count = $modelWardCustomerCount->shop_count + 1;
            }
            elseif($modelCustomer->building_type=='Flat'){
              $modelWardCustomerCount->flat_count = $modelWardCustomerCount->flat_count + 1;
            }
            elseif($modelCustomer->building_type=='Hospital'){
              $modelWardCustomerCount->hospital_count = $modelWardCustomerCount->hospital_count + 1;
            }
            elseif($modelCustomer->building_type=='Public place'){
              $modelWardCustomerCount->public_place_count = $modelWardCustomerCount->public_place_count + 1;
            }
            elseif($modelCustomer->building_type=='Office'){
              $modelWardCustomerCount->office_count = $modelWardCustomerCount->office_count + 1;
            }
            elseif($modelCustomer->building_type=='Auditorium'){
              $modelWardCustomerCount->auditorium_count = $modelWardCustomerCount->auditorium_count + 1;
            }
            elseif($modelCustomer->building_type=='Market'){
              $modelWardCustomerCount->market_count = $modelWardCustomerCount->market_count + 1;
            }
            elseif($modelCustomer->building_type=='Religious institution'){
              $modelWardCustomerCount->religious_institution_count = $modelWardCustomerCount->religious_institution_count + 1;
            }
            $modelWardCustomerCount->save(false);
          }
          else
          {
            $modelWardCustomerCount = new WardCustomerCount;
            $modelWardCustomerCount->count = 1;
            $modelWardCustomerCount->date = $currentDate;
             if($modelCustomer->building_type=='House'){
              $modelWardCustomerCount->house_count = $modelWardCustomerCount->house_count + 1;
            }elseif($modelCustomer->building_type=='Shop'){
              $modelWardCustomerCount->shop_count = $modelWardCustomerCount->shop_count + 1;
            }
            elseif($modelCustomer->building_type=='Flat'){
              $modelWardCustomerCount->flat_count = $modelWardCustomerCount->flat_count + 1;
            }
            elseif($modelCustomer->building_type=='Hospital'){
              $modelWardCustomerCount->hospital_count = $modelWardCustomerCount->hospital_count + 1;
            }
            elseif($modelCustomer->building_type=='Public place'){
              $modelWardCustomerCount->public_place_count = $modelWardCustomerCount->public_place_count + 1;
            }
            elseif($modelCustomer->building_type=='Office'){
              $modelWardCustomerCount->office_count = $modelWardCustomerCount->office_count + 1;
            }
            elseif($modelCustomer->building_type=='Auditorium'){
              $modelWardCustomerCount->auditorium_count = $modelWardCustomerCount->auditorium_count + 1;
            }
            elseif($modelCustomer->building_type=='Market'){
              $modelWardCustomerCount->market_count = $modelWardCustomerCount->market_count + 1;
            }
            elseif($modelCustomer->building_type=='Religious institution'){
              $modelWardCustomerCount->religious_institution_count = $modelWardCustomerCount->religious_institution_count + 1;
            }
            $modelWardCustomerCount->ward_id = $modelCustomer->ward_id;
            $modelWardCustomerCount->save(false);
          }
         }elseif($modelCustomer->ward_id&&$account_id&&$wardId)
         {
            if($wardId!=$modelCustomer->ward_id)
            {
               $qry1 = "SELECT max(customer_id) as customer_id FROM `customer` where status=1 and ward_id=:ward_id and is_public_customer=0";
          $ward_id = $modelCustomer->ward_id;
          $command1 =  Yii::$app->db->createCommand($qry1);
          $command1->bindParam(':ward_id',$ward_id);
          $data1 = $command1->queryAll();
          $maxCount = $data1[0];
          $max = $maxCount['customer_id'];
          if($max):
          $modelCustomer->customer_id = $max+1; 
        else:
          $modelCustomer->customer_id = 1;
        endif;
        $modelWardCustomerCount = WardCustomerCount::find()->where(['ward_id'=>$modelCustomer->ward_id])->andWhere(['status'=>1])->andWhere(['date'=>$currentDate])->one();
          if($modelWardCustomerCount){
            $modelWardCustomerCount->count = $modelWardCustomerCount->count + 1;
            if($modelCustomer->building_type=='House'){
              $modelWardCustomerCount->house_count = $modelWardCustomerCount->house_count + 1;
            }elseif($modelCustomer->building_type=='Shop'){
              $modelWardCustomerCount->shop_count = $modelWardCustomerCount->shop_count + 1;
            }
            elseif($modelCustomer->building_type=='Flat'){
              $modelWardCustomerCount->flat_count = $modelWardCustomerCount->flat_count + 1;
            }
            elseif($modelCustomer->building_type=='Hospital'){
              $modelWardCustomerCount->hospital_count = $modelWardCustomerCount->hospital_count + 1;
            }
            elseif($modelCustomer->building_type=='Public place'){
              $modelWardCustomerCount->public_place_count = $modelWardCustomerCount->public_place_count + 1;
            }
            elseif($modelCustomer->building_type=='Office'){
              $modelWardCustomerCount->office_count = $modelWardCustomerCount->office_count + 1;
            }
            elseif($modelCustomer->building_type=='Auditorium'){
              $modelWardCustomerCount->auditorium_count = $modelWardCustomerCount->auditorium_count + 1;
            }
            elseif($modelCustomer->building_type=='Market'){
              $modelWardCustomerCount->market_count = $modelWardCustomerCount->market_count + 1;
            }
            elseif($modelCustomer->building_type=='Religious institution'){
              $modelWardCustomerCount->religious_institution_count = $modelWardCustomerCount->religious_institution_count + 1;
            }
            $modelWardCustomerCount->save(false);
          }
          else
          {
            $modelWardCustomerCount = new WardCustomerCount;
            $modelWardCustomerCount->count = 1;
            $modelWardCustomerCount->date = $currentDate;
            if($modelCustomer->building_type=='House'){
              $modelWardCustomerCount->house_count = $modelWardCustomerCount->house_count + 1;
            }elseif($modelCustomer->building_type=='Shop'){
              $modelWardCustomerCount->shop_count = $modelWardCustomerCount->shop_count + 1;
            }
            elseif($modelCustomer->building_type=='Flat'){
              $modelWardCustomerCount->flat_count = $modelWardCustomerCount->flat_count + 1;
            }
            elseif($modelCustomer->building_type=='Hospital'){
              $modelWardCustomerCount->hospital_count = $modelWardCustomerCount->hospital_count + 1;
            }
            elseif($modelCustomer->building_type=='Public place'){
              $modelWardCustomerCount->public_place_count = $modelWardCustomerCount->public_place_count + 1;
            }
            elseif($modelCustomer->building_type=='Office'){
              $modelWardCustomerCount->office_count = $modelWardCustomerCount->office_count + 1;
            }
            elseif($modelCustomer->building_type=='Auditorium'){
              $modelWardCustomerCount->auditorium_count = $modelWardCustomerCount->auditorium_count + 1;
            }
            elseif($modelCustomer->building_type=='Market'){
              $modelWardCustomerCount->market_count = $modelWardCustomerCount->market_count + 1;
            }
            elseif($modelCustomer->building_type=='Religious institution'){
              $modelWardCustomerCount->religious_institution_count = $modelWardCustomerCount->religious_institution_count + 1;
            }
            $modelWardCustomerCount->ward_id = $modelCustomer->ward_id;
            $modelWardCustomerCount->save(false);
          } 
          $modelWardCustomerCountNew = WardCustomerCount::find()->where(['ward_id'=>$wardId])->andWhere(['status'=>1])->andWhere(['date'=>$currentDate])->one();
          if($modelWardCustomerCountNew){
            $modelWardCustomerCountNew->count = $modelWardCustomerCountNew->count - 1;
            if($modelCustomer->building_type=='House'){
              $modelWardCustomerCountNew->house_count = $modelWardCustomerCountNew->house_count - 1;
            }elseif($modelCustomer->building_type=='Shop'){
              $modelWardCustomerCountNew->shop_count = $modelWardCustomerCountNew->shop_count - 1;
            }
            elseif($modelCustomer->building_type=='Flat'){
              $modelWardCustomerCountNew->flat_count = $modelWardCustomerCountNew->flat_count - 1;
            }
            elseif($modelCustomer->building_type=='Hospital'){
              $modelWardCustomerCountNew->hospital_count = $modelWardCustomerCountNew->hospital_count - 1;
            }
            elseif($modelCustomer->building_type=='Public place'){
              $modelWardCustomerCountNew->public_place_count = $modelWardCustomerCountNew->public_place_count - 1;
            }
            elseif($modelCustomer->building_type=='Office'){
              $modelWardCustomerCountNew->office_count = $modelWardCustomerCountNew->office_count - 1;
            }
            elseif($modelCustomer->building_type=='Auditorium'){
              $modelWardCustomerCountNew->auditorium_count = $modelWardCustomerCountNew->auditorium_count - 1;
            }
            elseif($modelCustomer->building_type=='Market'){
              $modelWardCustomerCountNew->market_count = $modelWardCustomerCountNew->market_count - 1;
            }
            elseif($modelCustomer->building_type=='Religious institution'){
              $modelWardCustomerCountNew->religious_institution_count = $modelWardCustomerCountNew->religious_institution_count - 1;
            }
            $modelWardCustomerCountNew->save(false);
          }   
        }
         }
        if($modelCustomer->public_gathering_method) {
            $modelPublicGatheringMethod = PublicGatheringMethod::findByName($modelCustomer->public_gathering_method)->one();
            if($modelPublicGatheringMethod){
              $modelCustomer->public_gathering_method = $modelPublicGatheringMethod->id;
            }
         else
           {
            $modelPublicGatheringMethod = new PublicGatheringMethod;
            $modelPublicGatheringMethod->name = $modelCustomer->public_gathering_method;
            $modelPublicGatheringMethod->save(false);
            $modelCustomer->public_gathering_method = $modelPublicGatheringMethod->id;
           }
          }
         if($modelCustomer->administration_type) {
            $modelAdministrationType = AdministrationType::findByName($modelCustomer->administration_type)->one();
            if($modelAdministrationType){
              $modelCustomer->administration_type = $modelAdministrationType->id;
            }
         else
           {
            $modelAdministrationType = new AdministrationType;
            $modelAdministrationType->name = $modelCustomer->administration_type;
            $modelAdministrationType->save();
            $modelCustomer->administration_type = $modelAdministrationType->id;
           }
          }
          if($modelCustomer->waste_collection_interval_id) {
            $modelWasteCollectionInterval = WasteCollectionInterval::findByName($modelCustomer->waste_collection_interval_id)->one();
            if($modelWasteCollectionInterval){
              $modelCustomer->waste_collection_interval_id = $modelWasteCollectionInterval->id;
            }
         else
           {
            $modelWasteCollectionInterval = new WasteCollectionInterval;
            $modelWasteCollectionInterval->name = $modelCustomer->waste_collection_interval_id;
            $modelWasteCollectionInterval->save();
            $modelCustomer->waste_collection_interval_id = $modelWasteCollectionInterval->id;
           }
          }


         if($modelCustomer->building_type) {
           $modelBuildingType = BuildingType::findByName($modelCustomer->building_type)->one();
           if($modelBuildingType){
              $modelCustomer->building_type_id = $modelBuildingType->id;
           }
          }
          if($modelCustomer->building_type_id==5||$modelCustomer->building_type_id==9||$modelCustomer->building_type_id==10)
          {
            $modelCustomer->door_status = 1;
          }
          if($modelCustomer->building_sub_type) {
           $modelBuildingTypeSubTypes = BuildingTypeSubTypes::findByName($modelCustomer->building_sub_type)->one();
           if($modelBuildingTypeSubTypes){
              $modelCustomer->building_sub_type = $modelBuildingTypeSubTypes->id;
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
            if($modelWasteCollectionMethod) {
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
          if($modelCustomer->bio_medical_waste_collection_method) {
            $modelWasteCollectionMethod = WasteCollectionMethod::findByName($modelCustomer->bio_medical_waste_collection_method,$bioMedicalWasteId)->one();
            if($modelWasteCollectionMethod) {
              $modelCustomer->bio_medical_waste_collection_method = $modelWasteCollectionMethod->id;
            }
            else
           {
            $modelWasteCollectionMethod = new WasteCollectionMethod;
            $modelWasteCollectionMethod->name = $modelCustomer->bio_medical_waste_collection_method;
            $modelWasteCollectionMethod->waste_category_id = $bioMedicalWasteId;
            $modelWasteCollectionMethod->is_public = 0;
            $modelWasteCollectionMethod->save();
            $modelCustomer->bio_medical_waste_collection_method = $modelWasteCollectionMethod->id;
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
          if(isset($modelImageSaveId)&&$modelImageSaveId!=null){
          $modelCustomer->image_id = $modelImageSaveId;
        }
        else
        {
          $modelCustomer->image_id = $image;
        }
        $modelUser = Yii::$app->user->identity;
        if($modelUser->role=='surveyor'){
          $modelCustomer->creator_account_id = Yii::$app->user->identity->id;
          if(!$account_id){
          $modelSurveyCustomerCount = SurveyCustomerCount::find()->where(['account_id'=>Yii::$app->user->identity->id])->andWhere(['status'=>1])->andWhere(['date'=>$currentDate])->one();
          if($modelSurveyCustomerCount){
            $modelSurveyCustomerCount->count = $modelSurveyCustomerCount->count + 1;
            if($modelCustomer->building_type=='House'){
              $modelSurveyCustomerCount->house_count = $modelSurveyCustomerCount->house_count + 1;
            }elseif($modelCustomer->building_type=='Shop'){
              $modelSurveyCustomerCount->shop_count = $modelSurveyCustomerCount->shop_count + 1;
            }
            elseif($modelCustomer->building_type=='Flat'){
              $modelSurveyCustomerCount->flat_count = $modelSurveyCustomerCount->flat_count + 1;
            }
            elseif($modelCustomer->building_type=='Hospital'){
              $modelSurveyCustomerCount->hospital_count = $modelSurveyCustomerCount->hospital_count + 1;
            }
            elseif($modelCustomer->building_type=='Public place'){
              $modelSurveyCustomerCount->public_place_count = $modelSurveyCustomerCount->public_place_count + 1;
            }
            elseif($modelCustomer->building_type=='Office'){
              $modelSurveyCustomerCount->office_count = $modelSurveyCustomerCount->office_count + 1;
            }
            elseif($modelCustomer->building_type=='Auditorium'){
              $modelSurveyCustomerCount->auditorium_count = $modelSurveyCustomerCount->auditorium_count + 1;
            }
            elseif($modelCustomer->building_type=='Market'){
              $modelSurveyCustomerCount->market_count = $modelSurveyCustomerCount->market_count + 1;
            }
            elseif($modelCustomer->building_type=='Religious institution'){
              $modelSurveyCustomerCount->religious_institution_count = $modelSurveyCustomerCount->religious_institution_count + 1;
            }
            $modelSurveyCustomerCount->save(false);
          }
          else
          {
            $modelSurveyCustomerCount = new SurveyCustomerCount;
            $modelSurveyCustomerCount->count = 1;
            $modelSurveyCustomerCount->date = $currentDate;
            if($modelCustomer->building_type=='House'){
              $modelSurveyCustomerCount->house_count = $modelSurveyCustomerCount->house_count + 1;
            }elseif($modelCustomer->building_type=='Shop'){
              $modelSurveyCustomerCount->shop_count = $modelSurveyCustomerCount->shop_count + 1;
            }
            elseif($modelCustomer->building_type=='Flat'){
              $modelSurveyCustomerCount->flat_count = $modelSurveyCustomerCount->flat_count + 1;
            }
            elseif($modelCustomer->building_type=='Hospital'){
              $modelSurveyCustomerCount->hospital_count = $modelSurveyCustomerCount->hospital_count + 1;
            }
            elseif($modelCustomer->building_type=='Public place'){
              $modelSurveyCustomerCount->public_place_count = $modelSurveyCustomerCount->public_place_count + 1;
            }
            elseif($modelCustomer->building_type=='Office'){
              $modelSurveyCustomerCount->office_count = $modelSurveyCustomerCount->office_count + 1;
            }
            elseif($modelCustomer->building_type=='Auditorium'){
              $modelSurveyCustomerCount->auditorium_count = $modelSurveyCustomerCount->auditorium_count + 1;
            }
            elseif($modelCustomer->building_type=='Market'){
              $modelSurveyCustomerCount->market_count = $modelSurveyCustomerCount->market_count + 1;
            }
            elseif($modelCustomer->building_type=='Religious institution'){
              $modelSurveyCustomerCount->religious_institution_count = $modelSurveyCustomerCount->religious_institution_count + 1;
            }
            $modelSurveyCustomerCount->account_id = Yii::$app->user->identity->id;
            $modelSurveyCustomerCount->save(false);
          }
        }
        }
          $modelCustomer->status = 1;
          $qry ="SELECT customer.building_number as building_number,customer.lead_person_name as lead_person_name,customer.lead_person_phone as lead_person_phone from customer where customer.creator_account_id=:creator and status=1 and customer.created_at>= DATE_SUB(NOW(),INTERVAL 5 MINUTE) and customer.lead_person_name=:name and customer.lead_person_phone=:phone and customer.building_number=:building_number";
          $creator = Yii::$app->user->identity->id;
          $name = $modelCustomer->lead_person_name;
          $phone = $modelCustomer->lead_person_phone;
          $building_number = $modelCustomer->building_number;
          $command =  Yii::$app->db->createCommand($qry);
        $command->bindParam(':creator',$creator);
        $command->bindParam(':name',$name);
        $command->bindParam(':phone',$phone);
        $command->bindParam(':building_number',$building_number);
        $customersList = $command->queryAll();
        // print_r($customersList);die();
        if(!sizeof($customersList)>0){
          // print_r("expression");die();
          if($account_id)
          {
            if($doorStatus==0&&$modelCustomer->door_status==1)
            {
              $modelCustomer->created_at = date('Y-m-d H:i:s');
            }
          }
        $modelCustomer->save(false);
      }
      else
      {
        $msg = ['Duplicate entries not allowed'];
        $error = ['lead_person_name'=>$msg];
        $ret = ['errors' =>$error];
        return $ret;
      }
          if(!$account_id){
          $modelAccount = new Account;
        }
          $modelAccount->parent_id = $modelCustomer->parent_account_id;
          if(isset($modelImageSaveId)&&$modelImageSaveId!=null){
          $modelAccount->image_id = $modelImageSaveId;
        }
        else
        {
          $modelAccount->image_id = $image;
        }
        $modelAccount->username = Customer::generateCustomerId($modelCustomer->id);
          // $modelAccount->username = $modelCustomer->lead_person_name.$modelCustomer->id;
         $password = Customer::generateCustomerId($modelCustomer->id);
         $modelAccount->password_hash = Yii::$app->security->generatePasswordHash($password);
          $modelAccount->customer_id = $modelCustomer->id;
          $modelAccount->role                 = "customer";
          $modelAccount->person_id = 0;
          $modelAccount->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
          $modelAccount->save(false);
          $this->assignAuthRole('customer',$modelAccount->id);
           if(!$account_id&&$modelCustomer->building_type_id==1){
          // Customer::generate($modelCustomer->lead_person_phone,$modelCustomer->id,$modelAccount->username,$password);
        }
          if(!$account_id):
          $modelServiceEnablerSettings = ServiceEnablerSettings::getAllQuery()->andWhere(['status' => 1]);
          $serviceEnablerSettingsDataProvider = new ActiveDataProvider([
            'query'      => $modelServiceEnablerSettings,
            'pagination' => false

        ]);
        $serviceEnablerSettingsModels = $serviceEnablerSettingsDataProvider->getModels();
        if(sizeof($serviceEnablerSettingsModels)>0){
        foreach ($serviceEnablerSettingsModels as $serviceEnablerSettingsModel) {
          $enableField = $serviceEnablerSettingsModel->customer_field;
          if($modelCustomer[$enableField]==$serviceEnablerSettingsModel->customer_field_value){
              $modelAccountService = new AccountService;
              $modelAccountService->service_id = $serviceEnablerSettingsModel->service_id;
              $modelAccountService->account_id = $modelAccount->id;
              // $modelAccountService->save(false);

        }
    }
  }
  $modelBuildingType = BuildingType::find()->where(['id'=>$modelCustomer->building_type_id])->andWhere(['status'=>1])->one();
  if($modelBuildingType->residence_category_id)
  {
    $lsgi = isset($modelCustomer->fkWard->lsgi_id)?$modelCustomer->fkWard->lsgi_id:null;
    $modelLsgiServiceFee = LsgiServiceFee::find()->where(['lsgi_id'=>$lsgi])->andWhere(['residence_category_id'=>$modelBuildingType->residence_category_id])->andWhere(['status'=>1])->all();
    if($modelLsgiServiceFee)
    {
      foreach ($modelLsgiServiceFee as $key => $value) {
        $modelAccountService = new AccountService;
        $accountServiceData = AccountService::find()->where(['service_id'=>$value->service_id])->andWhere(['account_id'=>$modelAccount->id])->andWhere(['status'=>1])->one();
        if(!$accountServiceData){
        $modelAccountService->service_id = $value->service_id;
        $modelAccountService->account_id = $modelAccount->id;
        // $modelAccountService->save(false);
      }
      }
    }
  }
  endif;
          // Customer::generate($modelCustomer->lead_person_phone,$modelCustomer->id);
          $ret = [
            "account_id" => $modelAccount->id
          ];
       } else {
        $postJson = json_encode($modelCustomer->errors);
        $this->log($postJson);
        $msg = ['Building type is mandatory'];
        $error = ['building_type'=>$msg];
        $ret = ['errors' =>$error];
         // $ret = $modelCustomer->errors;
       }
       // $modelImage = new Image;
       // print_r($modelImage);exit;
     // }
     // else
     // {
     //    $msg = ['Permission denied'];
     //    $error = ['username'=>$msg];
     //    $ret = ['errors' =>$error];
     // }
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
            'daily_bio_waste_quantity' =>$model->daily_bio_waste_quantity,
            // 'account_id'=>$modelAccount->id,
            // 'house_count'=>$model->flat_house_count,
            // 'space_available_for_bio_waste_management_facility'=>$model->space_available_for_bio_waste_management_facility,
            // 'help_needed_for_bio_waste_management_facility_construction'=>$model->help_needed_for_bio_waste_management_facility_construction,
            // 'building_in_use'=>$model->building_in_use,
            // 'has_space_for_non_bio_waste_management_facility'=>$model->has_space_for_non_bio_waste_management_facility,
            // 'space_available_for_non_bio_waste_management_facility'=>$model->space_available_for_non_bio_waste_management_facility,
            // 'has_interest_for_allotting_space_for_non_bio_management_facility'=>$model->has_interest_for_allotting_space_for_non_bio_management_facility,
            // 'has_interest_in_bio_waste_management_facility'=>$model->has_interest_in_bio_waste_management_facility,
            // 'office_type'=>$model->office_type_id,
          ];

        }
        return $ret;
      }
      public function actionTest($non_bio_waste_collection_method=null)
      {
        Yii::$app->cache->flush();
      // $post = Yii::$app->request->post();
      // foreach($post as $param => $val) ${$param} = $val;
      // $non_bio_waste_collection_method = isset($non_bio_waste_collection_method)?$non_bio_waste_collection_method:'';
      //   $modelWasteCollectionMethod = WasteCollectionMethod::findByName($non_bio_waste_collection_method,3)->one();
      //   print_r($modelWasteCollectionMethod);die();
      }
       public function actionUpdateProfile() {
      $ret = [];

      while(true) {
        $id = Yii::$app->user->id;
        $modelUser = Account::findOne($id);
        $modelCustomer = $modelUser->fkCustomer;
        // print_r($modelCustomer);die();
        if(!$modelCustomer) break;

        $name = $modelCustomer->lead_person_name;
        $address = $modelCustomer->address;
        $phone1 = $modelCustomer->lead_person_phone;
        $params = Yii::$app->request->post();
        $modelImage = new Image;
        $photo = $_FILES;
        $images = UploadedFile::getInstanceByName('photo');
        $modelImageSaveId = $modelImage->uploadAndSave($images);
         $image = $modelCustomer->image_id?$modelCustomer->image_id:"";
        $name = isset($params['name'])?$params['name']:$name;
        $address = isset($params['address'])?$params['address']:$address;
        $phone1 = isset($params['phone'])?$params['phone']:$phone1;
        $modelCustomer->lead_person_name = $name;
        $modelCustomer->lead_person_phone = $phone1;
        $modelCustomer->address = $address;
        $personOk = $modelCustomer->validate();
        if(!($personOk))
        break;
        if(isset($modelImageSaveId)&&$modelImageSaveId!=null){
          $modelCustomer->image_id = $modelImageSaveId;
        }
        else
        {
          $modelCustomer->image_id = $image;
        }
        if(isset($modelImageSaveId)&&$modelImageSaveId!=null){
          $modelUser->image_id = $modelImageSaveId;
          $modelUser->save(false);
        }
        else
        {
          $modelUser->image_id = $image;
          $modelUser->save(false);
        }
        $modelCustomer->save(false);
        $ret = [
          'empId'=> $modelUser->id,
          'name'=> $modelCustomer->lead_person_name,
          'phone'=> $modelCustomer->lead_person_phone
        ];
        break;
      }
      $errors =$modelCustomer->errors;

      if($errors) $ret = $errors;
      return $ret;
    }
    public function actionGetProfile() {
    $base_url    = isset(Yii::$app->params['base_url']) ? Yii::$app->params['base_url'] : null;
      $ret = [];
      $id = Yii::$app->user->id;
      $name = null;
      $phone1 = null;
      $ward = null;
      $address = null;
      $image = null;

      $modelUser = Account::findOne($id);
      $modelCustomer = $modelUser->fkCustomer;
      if($modelCustomer) {
        $name = isset($modelCustomer->lead_person_name)?$modelCustomer->lead_person_name:null;
        $phone1 = isset($modelCustomer->lead_person_phone)?$modelCustomer->lead_person_phone:null;
        $ward = isset($modelCustomer->ward_id)?$modelCustomer->getWard():null;
        $address = $modelCustomer->address;
        $image = $modelUser->image_id?$modelUser->getImageUrl():null;
      }
      return $ret = [
      'base_url'=>$base_url,
          'account_id'=> $modelUser->id,
          'username'=> $modelUser->username,
          'name'=> $name,
          'phone'=> $phone1,
          'address'=> $address,
          'house_name'=> $modelCustomer->building_name,
          'ward'=> $modelCustomer->fkWard?$modelCustomer->fkWard->name:null,
          'block'=> $modelCustomer->getLsgi(),
          'profilePic'=> $image,
           'ward_id'=> isset($modelCustomer->ward_id)?$modelCustomer->ward_id:null,
          'residential_association_id'=> isset($modelCustomer->residential_association_id)?$modelCustomer->residential_association_id:null,
          'association_number'=>isset($modelCustomer->association_number)?$modelCustomer->association_number:'',
          'association_name'=>isset($modelCustomer->residential_association_id)?$modelCustomer->fkAssociation->name:'',
        ];
    }
    public function actionSurveysCount($account_id=null)
    {
      if($account_id){
        $modelUser = Account::find()->where(['id'=>$account_id])->andWhere(['status'=>1])->one();
        if($modelUser){
          $userId = $modelUser->id;
        $query     = Customer::getAllQuery();
        $query->andWhere(['parent_account_id' => $userId]);
        $count = (int) $query->count();

        return [
            'count' => $count
        ];
      }
      else
      {
        $msg   = ['Invalid account id'];
        $error = ['account_id' => $msg];
        $ret   = ['errors' => $error];
        return $ret;
      }
    }
      else
      {
        $msg   = ['Account id is mandatory'];
        $error = ['account_id' => $msg];
        $ret   = ['errors' => $error];
        return $ret;
      }
    }

}
