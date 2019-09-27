<?php

namespace api\modules\v1\models;
use yii\db\Expression;
use yii\behaviors\TimestampBehavior;

use Yii;

/**
 * This is the model class for table "customer".
 *
 * @property int $id
 * @property int $building_type_id
 * @property string $building_name
 * @property string $building_number
 * @property string $association_name
 * @property string $association_number
 * @property string $lead_person_name
 * @property string $lead_person_phone
 * @property string $address
 * @property string $building_owner_name
 * @property string $building_owner_phone
 * @property int $trading_type_id
 * @property int $shop_type_id
 * @property int $has_bio_waste
 * @property int $has_non_bio_waste
 * @property int $has_disposible_waste
 * @property double $lat
 * @property double $lng
 * @property int $fee_collection_interval_id
 * @property int $has_bio_waste_management_facility
 * @property int $bio_waste_management_facility_operational
 * @property int $bio_waste_management_facility_repair_help_needed
 * @property int $bio_waste_collection_method_id
 * @property int $bio_waste_collection_needed
 * @property int $non_bio_waste_collection_method_id
 * @property int $has_terrace_farming_interest
 * @property int $terrace_farming_help_type_id
 * @property int $creator_account_id
 * @property string $created_at
 * @property string $modified_at
 * @property int $status

 */
class Customer extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'customer';
    }
    public
      // $parent_account_id,
      $building_type,
      $office_type,
      $public_place_type,
      $customer_name,
      $customer_phone,
      $trading_type,
      $shop_type,
      $account_id,
      $fee_collection_interval,
      $bio_waste_collection_method,
      $non_bio_waste_collection_method,
      // $image_id,
      $terrace_farming_help_type;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['building_type_id', 'trading_type_id', 'shop_type_id', 'fee_collection_interval_id',
              'bio_waste_collection_method_id', 'non_bio_waste_collection_method_id',
               'terrace_farming_help_type_id', 'creator_account_id', 'status','ward_id','public_place_type_id','office_type_id',
               'has_public_toilet','public_toilet_count','public_toilet_count_men','public_toilet_count_women',
             ], 'integer'],
            [['address'], 'string'],
            [['lat', 'lng'], 'number'],
            [[
              'building_type','parent_account_id','daily_bio_waste_quantity','image_id',
              'customer_name','customer_phone','trading_type','shop_type',
              'fee_collection_interval','bio_waste_collection_method',
              'created_at', 'modified_at' ,'bio_waste_collection_needed','door_status',

              'non_bio_waste_collection_method','terrace_farming_help_type','bio_waste_collection_needed',
			  'people_count','house_adult_count','house_children_count','market_visiters_count','seating_capacity','monthly_booking_count','house_count','public_place_type','public_gathering_method','is_programmes_happening','public_place_area','office_type','office_contact_person','office_contact_person_designation','daily_collection_needed_bio','shop_name','licence_no','employee_count','space_available_for_bio_waste_management_facility','help_needed_for_bio_waste_management_facility_construction','building_in_use','has_space_for_non_bio_waste_management_facility','space_available_for_non_bio_waste_management_facility','has_interest_for_allotting_space_for_non_bio_management_facility','has_interest_in_bio_waste_management_facility','green_protocol_system_implemented','bio_medical_waste_collection_facility','has_bio_medical_incinerator',
        'bio_medical_waste_collection_method','building_area','has_public_program_option','lead_person_designation','administration_type','public_program_count','has_non_bio_waste_management_facility','building_sub_type','account_id','has_interest_in_system_provided_bio_facility','  waste_collection_interval_id',
        'has_public_toilet','public_toilet_count','public_toilet_count_men','public_toilet_count_women','residential_association_id','asset_number','is_public_customer','sign_up','qr_code_id'
            ], 'safe'],
            [['building_name', 'building_number','daily_bio_waste_quantity', 'association_name', 'association_number',
              'lead_person_name', 'lead_person_phone', 'building_owner_name', 'building_owner_phone','asset_number'
            ], 'string', 'max' => 255],
            // [['has_bio_waste', 'has_non_bio_waste', 'has_disposible_waste', 'has_bio_waste_management_facility',
            //   'bio_waste_management_facility_operational', 'bio_waste_management_facility_repair_help_needed',
            //   'bio_waste_collection_needed', 'has_terrace_farming_interest'
            // ], 'string', 'max' => 255],
        ];
    }
 public function behaviors() {
    return [
      [
        'class' => TimestampBehavior::className(),
        'createdAtAttribute' => 'created_at',
        'updatedAtAttribute' => 'modified_at',
        'value' => new Expression('NOW()')
      ]
    ];

 }
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'building_type_id' => Yii::t('app', 'Building Type ID'),
            'building_name' => Yii::t('app', 'Building Name'),
            'building_number' => Yii::t('app', 'Building Number'),
            'association_name' => Yii::t('app', 'Association Name'),
            'association_number' => Yii::t('app', 'Association Number'),
            'lead_person_name' => Yii::t('app', 'Lead Person Name'),
            'lead_person_phone' => Yii::t('app', 'Lead Person Phone'),
            'address' => Yii::t('app', 'Address'),
            'building_owner_name' => Yii::t('app', 'Building Owner Name'),
            'building_owner_phone' => Yii::t('app', 'Building Owner Phone'),
            'trading_type_id' => Yii::t('app', 'Trading Type ID'),
            'shop_type_id' => Yii::t('app', 'Shop Type ID'),
            'has_bio_waste' => Yii::t('app', 'Has Bio Waste'),
            'has_non_bio_waste' => Yii::t('app', 'Has Non Bio Waste'),
            'daily_bio_waste_quantity' => Yii::t('app','Daily Bio Waste Quantity'),
            'has_disposible_waste' => Yii::t('app', 'Has Disposible Waste'),
            'lat' => Yii::t('app', 'Lat'),
            'lng' => Yii::t('app', 'Lng'),
            'fee_collection_interval_id' => Yii::t('app', 'Fee Collection Interval ID'),
            'has_bio_waste_management_facility' => Yii::t('app', 'Has Bio Waste Management Facility'),
            'bio_waste_management_facility_operational' => Yii::t('app', 'Bio Waste Management Facility Operational'),
            'bio_waste_management_facility_repair_help_needed' => Yii::t('app', 'Bio Waste Management Facility Repair Help Needed'),
            'bio_waste_collection_method_id' => Yii::t('app', 'Bio Waste Collection Method ID'),
            'bio_waste_collection_needed' => Yii::t('app', 'Bio Waste Collection Needed'),
            'non_bio_waste_collection_method_id' => Yii::t('app', 'Non Bio Waste Collection Method ID'),
            'has_terrace_farming_interest' => Yii::t('app', 'Has Terrace Farming Interest'),
            'terrace_farming_help_type_id' => Yii::t('app', 'Terrace Farming Help Type ID'),
            'creator_account_id' => Yii::t('app', 'Creator Account ID'),
            'created_at' => Yii::t('app', 'Created At'),
            'modified_at' => Yii::t('app', 'Modified At'),
            'status' => Yii::t('app', 'Status'),
            'has_public_toilet' => Yii::t('app', 'Has public toilet'),
            'public_toilet_count' => Yii::t('app', 'Public toilet count'),
            'public_toilet_count_men' => Yii::t('app', 'Public toilet count men'),
            'public_toilet_count_women' => Yii::t('app', 'Publi toilet count women'),
        ];
    }
    public static function getAllQuery()
    {
      return static::find()->where(['customer.status'=>1])->orderBy(['id' => SORT_DESC]);
    }
    public function getFkBuildingType()
     {
       return $this->hasOne(BuildingType::className(), ['id' => 'building_type_id']);
     }
     public function getFkBuildingTypeSubType()
     {
       return $this->hasOne(BuildingTypeSubTypes::className(), ['id' => 'building_sub_type']);
     }
     public function getBuildingType()
     {
       $ret = null;
       $modelBuildingType = $this->fkBuildingType;
       if($modelBuildingType){
         $ret = $modelBuildingType->name;
       }
       return $ret;
     }
     public function getFkTradingType()
     {
       return $this->hasOne(TradingType::className(), ['id' => 'trading_type_id']);
     }
     public function getTradingType()
     {
       $ret = null;
       $modelTradingType = $this->fkTradingType;
       if($modelTradingType){
         $ret = $modelTradingType->name;
       }
       return $ret;
     }
     public function getFkShopType()
     {
       return $this->hasOne(ShopType::className(), ['id' => 'shop_type_id']);
     }
     public function getShopType()
     {
       $ret = null;
       $modelShopType = $this->fkShopType;
       if($modelShopType){
         $ret = $modelShopType->name;
       }
       return $ret;
     }
     // public function getFkImageUrl()
     // {
     //   return $this->hasOne(Image::className(), ['id' => 'image_id']);
     // }
     // public function getImageUrl()
     // {
     //   $ret = null;
     //   $modelImage = $this->fkImageUrl;
     //   if($modelImage){
     //     $ret = $modelImage->uri_full;
     //   }
     //   return $ret;
     // }
      public function getFkFeeCollectionInterval()
     {
       return $this->hasOne(FeeCollectionInterval::className(), ['id' => 'fee_collection_interval_id']);
     }
     public function getFkAccount()
     {
       return $this->hasOne(Account::className(), ['customer_id' => 'id']);
     }
     public function getFeeCollectionInterval()
     {
       $ret = null;
       $modelFeeCollectionInterval = $this->fkFeeCollectionInterval;
       if($modelFeeCollectionInterval){
         $ret = $modelFeeCollectionInterval->name;
       }
       return $ret;
     }
     public function getFkBioWasteCollectionMethod()
     {
       return $this->hasOne(WasteCollectionMethod::className(), ['id' => 'bio_waste_collection_method_id']);
     }
     public function getBioWasteCollectionMethod()
     {
       $ret = null;
       $modelBioWasteCollectionMethod = $this->fkBioWasteCollectionMethod;
       if($modelBioWasteCollectionMethod){
         $ret = $modelBioWasteCollectionMethod->name;
       }
       return $ret;
     }
     public function getFkNonBioWasteCollectionMethod()
     {
       return $this->hasOne(WasteCollectionMethod::className(), ['id' => 'non_bio_waste_collection_method_id']);
     }
     public function getNonBioWasteCollectionMethod()
     {
       $ret = null;
       $modelNonBioWasteCollectionMethod = $this->fkNonBioWasteCollectionMethod;
       if($modelNonBioWasteCollectionMethod){
         $ret = $modelNonBioWasteCollectionMethod->name;
       }
       return $ret;
     }
     public function getFkTerraceFarmingHelpType()
     {
       return $this->hasOne(TerraceFarmingHelpType::className(), ['id' => 'terrace_farming_help_type_id']);
     }
     public function getTerraceFarmingHelpType()
     {
       $ret = null;
       $modelTerraceFarmingHelpType = $this->fkTerraceFarmingHelpType;
       if($modelTerraceFarmingHelpType){
         $ret = $modelTerraceFarmingHelpType->name;
       }
       return $ret;
     }
     public function getFkPublicPlaceType()
     {
       return $this->hasOne(PublicPlaceType::className(), ['id' => 'public_place_type_id']);
     }
     public function getPublicPlaceType()
     {
       $ret = null;
       $modelPublicPlaceType = $this->fkPublicPlaceType;
       if($modelPublicPlaceType){
         $ret = $modelPublicPlaceType->name;
       }
       return $ret;
     }
     public function getFkOfficeType()
     {
       return $this->hasOne(OfficeType::className(), ['id' => 'office_type_id']);
     }
      public function getFkAssociation()
     {
       return $this->hasOne(ResidentialAssociation::className(), ['id' => 'residential_association_id']);
     }
     public function getOfficeType()
     {
       $ret = null;
       $modelOfficeType = $this->fkOfficeType;
       if($modelOfficeType){
         $ret = $modelOfficeType->name;
       }
       return $ret;
     }
     public function qrCodeSet($id)
    {
         $model =     QrCode::find()
                      ->leftjoin('account','account.id=qr_code.account_id')
                      ->leftjoin('customer','customer.id=account.customer_id')
                      ->andWhere(['qr_code.status'=>1])
                      ->andWhere(['account.customer_id'=>$id])
                      ->one();
                      return $model;
                  }
    //  public static function generate($phone,$customer_id) {
    //   $params = Yii::$app->params['twilio'];
    //   $customer = 'TMC-'.$customer_id;
    //   $to = '+91'.$phone;
    //   $content = "$customer is is your ID";
    //   try {
    //     $from = $params['sender_id'];
    //     Yii::$app->twilio->sendSms($to,$from, $content);
    //   } catch(\Exception $ex) {
    //     $from = $params['number'];
    //     Yii::$app->twilio->sendSms($to,$from, $content);

    //   }

    // }
    public static function generate($phone,$customerId,$userName,$password) {
      $no ='000';
        $modelCustomer = static::find()->where(['id'=>$customerId])->andWhere(['status'=>1])->one();
        $code = null;
        if($modelCustomer){
            $ward = isset($modelCustomer->fkWard->ward_no)?$modelCustomer->fkWard->ward_no:'';
            $lsgi = isset($modelCustomer->fkWard->fkLsgi->code)?$modelCustomer->fkWard->fkLsgi->code:'';
            if($modelCustomer->customer_id)
            {
                $len = strlen($modelCustomer->customer_id);
                if($len==1)
                {
                    $length = '000';
                }
                elseif($len==2)
                {
                    $length = '00';
                }elseif($len==3)
                {
                    $length = '0';
                }
                else
                {
                    $length = '';
                }
                if($ward)
            {
                $leng = strlen($ward);
                if($leng==1)
                {
                    $no = '00';
                }
                elseif($leng==2)
                {
                    $no = '0';
                }
                else
                {
                    $no = '';
                }
                
            }
                $code = $lsgi.$no.$ward.$length.$modelCustomer->customer_id;
            }

            
        }
      // $params = Yii::$app->params['twilio'];
      // $to = '+91'.$phone;
      // $content = "$code is your ID.\n
      // username : $userName\n
      // password : $password\n
      //  Get started:https://play.google.com/store/apps/details?id=com.trois.user.greenapp ";
      // try {
      //   $from = $params['sender_id'];
      //   Yii::$app->twilio->sendSms($to,$from, $content);
      // } catch(\Exception $ex) {
      //   $from = $params['number'];
      //   Yii::$app->twilio->sendSms($to,$from, $content);

      // }
        $authKey = Yii::$app->params['authKeyMsg'];
      $phone = $phone;
      // $content = "$code is your ID.Username : $userName. Password : $password. Get started:https://play.google.com/store/apps/details?id=com.trois.user.greenapp ";
      $content ="Welcome to Green Trivandrum, your first step in becoming a part of smart waste management initiative of the Thiruvanthapuram Municipal Corporation .Your Customer ID is $code and your password is $password. You can login to the application using you customer id or registered mobile number. You can download the Green Trivandrum app from the play store account of Thiruvanthapuram Municipal Corporation using the below link.
        Link:https://play.google.com/store/apps/details?id=com.trois.user.greenapp
        .For queries please contact 9496434503 or mail to mycitybeautifulcity@gmail.com";
                   $key = 'account_id';
                   $countryCode = '91';
                   $senderId = 'WMSMGMT';
                   Yii::$app->message->sendSMS($authKey,"WMSMGMT","91",$phone,$content);

    }
    public static function generateCustomerId($customerId) {
      $no ='000';
        $modelCustomer = static::find()->where(['id'=>$customerId])->andWhere(['status'=>1])->one();
        $code = null;
        if($modelCustomer){
            $ward = isset($modelCustomer->fkWard->ward_no)?$modelCustomer->fkWard->ward_no:'';
            $lsgi = isset($modelCustomer->fkWard->fkLsgi->code)?$modelCustomer->fkWard->fkLsgi->code:'';
            if($modelCustomer->customer_id)
            {
                $len = strlen($modelCustomer->customer_id);
                if($len==1)
                {
                    $length = '000';
                }
                elseif($len==2)
                {
                    $length = '00';
                }elseif($len==3)
                {
                    $length = '0';
                }
                else
                {
                    $length = '';
                }
                if($ward)
            {
                $leng = strlen($ward);
                if($leng==1)
                {
                    $no = '00';
                }
                elseif($leng==2)
                {
                    $no = '0';
                }
                else
                {
                    $no = '';
                }
                
            }
                $code = $lsgi.$no.$ward.$length.$modelCustomer->customer_id;
            }

            
        }
      return $code;

    }
    public function getBuildingSubType()
     {
       $ret = null;
       $modelBuildingSubType = $this->fkBuildingTypeSubType;
       if($modelBuildingSubType){
         $ret = $modelBuildingSubType->name;
       }
       return $ret;
     }
     public function getAdministrationType()
     {
       $ret = null;
       $modelAdministrationType = $this->fkAdministrationType;
       if($modelAdministrationType){
         $ret = $modelAdministrationType->name;
       }
       return $ret;
     }
      public function getPublicGatheringMethod()
     {
       $ret = null;
       $modelPublicGatheringMethod = $this->fkPublicGatheringMethod;
       if($modelPublicGatheringMethod){
         $ret = $modelPublicGatheringMethod->name;
       }
       return $ret;
     }
      public function getFkAdministrationType()
     {
       return $this->hasOne(AdministrationType::className(), ['id' => 'administration_type']);
     }
     public function getFkPublicGatheringMethod()
     {
       return $this->hasOne(PublicGatheringMethod::className(), ['id' => 'public_gathering_method']);
     }
     public function getFkBioMedicalWasteCollectionMethod()
     {
       return $this->hasOne(WasteCollectionMethod::className(), ['id' => 'bio_medical_waste_collection_method']);
     }
     public function getBioMedicalWasteCollectionMethod()
     {
       $ret = null;
       $modelBioMedicalWasteCollectionMethod = $this->fkBioMedicalWasteCollectionMethod;
       if($modelBioMedicalWasteCollectionMethod){
         $ret = $modelBioMedicalWasteCollectionMethod->name;
       }
       return $ret;
     }
     public function getFkImageUrl()
      {
        return $this->hasOne(Image::className(), ['id' => 'image_id']);
      }
      public function getImageUrl()
      {
        $ret = null;
        $modelImage = $this->fkImageUrl;
        if($modelImage){
          $ret = $modelImage->uri_full;
        }
        return $ret;
      }
      public function getFkWard()
      {
        return $this->hasOne(Ward::className(), ['id' => 'ward_id']);
      }
      public function getWard()
      {
        $ret = null;
        $modelWard = $this->fkWard;
        if($modelWard){
          $ret = $modelWard->name;
        }
        return $ret;
      }
      public function getAssociation()
      {
        $ret = null;
        $modelAssociation = $this->fkAssociation;
        if($modelAssociation){
          $ret = $modelAssociation->name;
        }
        return $ret;
      }
      public function getLsgi()
      {
        $ret = null;
        $modelWard = $this->fkWard;
        if($modelWard){
          $modelLsgi = $modelWard->fkLsgi;
          if($modelLsgi)
          {
            $modelLsgiBlock = $modelLsgi->fkLsgiBlock;
            if($modelLsgiBlock)
            {
               $ret = $modelLsgiBlock->name;
            }
          }
        }
        return $ret;
      }
      public function getPendingCount($id=null)
      {
        $count = null;
       $modelRequest =  ServiceRequest::find()->where(['account_id_customer'=>$id])->all();
        if($modelRequest){
          $count =0;
         foreach ($modelRequest as $value) {
          $modelAssignment = $value->fkServiceAssignment;
           if($modelAssignment)
            {
              if($modelAssignment->servicing_status_option_id==null)
                $count = $count+1;
            }
         }
        }
        return $count;
      }
       public function getResolvedCount($id=null)
      {
        $count = null;
       $modelRequest =  ServiceRequest::find()->where(['account_id_customer'=>$id])->all();
        if($modelRequest){
          $count =0;
         foreach ($modelRequest as $value) {
          $modelAssignment = $value->fkServiceAssignment;
           if($modelAssignment)
            {
              if($modelAssignment->servicing_status_option_id!=null)
                $count = $count+1;
            }
         }
        }
        return $count;
      }
      public function getFkRequest()
      {
        return $this->hasMany(ServiceRequest::className(), ['account_id_customer' => 'id']);
      }
      public function getFormattedCustomerId($customerId)
    {
        $modelCustomer = static::find()->where(['id'=>$customerId])->andWhere(['status'=>1])->one();
        $code = null;
        if($modelCustomer){
            $ward = isset($modelCustomer->fkWard->code)?$modelCustomer->fkWard->code:'';
            $lsgi = isset($modelCustomer->fkWard->fkLsgi->code)?$modelCustomer->fkWard->fkLsgi->code:'';
            $code = $lsgi.$ward.$modelCustomer->id;
        }
        return $code;
    }
}
