<?php
namespace backend\models;

use Yii;
use yii\db\Expression;
use yii\behaviors\TimestampBehavior;
use yii\data\ActiveDataProvider;
/**
 * This is the model class for table "customer".
 *
 * @property int $id
 * @property int $ward_id
 * @property int $building_type_id
 * @property int $door_status
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
 * @property int $house_people_count
 * @property int $house_adult_count
 * @property int $house_children_count
 * @property string $created_at
 * @property string $modified_at
 * @property int $status
 */
class CustomerNew extends \yii\db\ActiveRecord
{
    /**
     * @var mixed
     */
    // public $district_id, $block_id, $assembly_constituency_id, $lsgi_id,$code,$no_association;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'customer';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ward_id', 'building_type_id', 'door_status', 'trading_type_id', 'shop_type_id', 'has_bio_waste', 'has_non_bio_waste', 'has_disposible_waste', 'fee_collection_interval_id', 'has_bio_waste_management_facility', 'bio_waste_management_facility_operational', 'bio_waste_management_facility_repair_help_needed', 'bio_waste_collection_method_id', 'bio_waste_collection_needed', 'non_bio_waste_collection_method_id', 'has_terrace_farming_interest', 'terrace_farming_help_type_id', 'creator_account_id', 'people_count', 'house_adult_count', 'house_children_count', 'status','image_id','has_public_toilet','public_toilet_count','public_toilet_count_men','public_toilet_count_women','residential_association_id'], 'integer'],
            [['address'], 'string'],
            // [['lat', 'lng'], 'number'],
            [['created_at', 'modified_at','image_id','building_sub_type','lat', 'lng','customer_id','service_secret_otp'], 'safe'],
            [['building_name', 'building_number', 'association_name', 'association_number', 'lead_person_name', 'lead_person_phone', 'building_owner_name', 'building_owner_phone'], 'string', 'max' => 255]
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
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id'                                               => Yii::t('app', 'ID'),
            'ward_id'                                          => Yii::t('app', 'Ward ID'),
            'building_type_id'                                 => Yii::t('app', 'Building Type '),
            'door_status'                                      => Yii::t('app', 'Door Status'),
            'building_name'                                    => Yii::t('app', 'Building Name'),
            'building_number'                                  => Yii::t('app', 'Building Number'),
            'association_name'                                 => Yii::t('app', 'Association Name'),
            'association_number'                               => Yii::t('app', 'Association Number'),
            'lead_person_name'                                 => Yii::t('app', 'Lead Person Name'),
            'lead_person_phone'                                => Yii::t('app', 'Lead Person Phone'),
            'address'                                          => Yii::t('app', 'Address'),
            'building_owner_name'                              => Yii::t('app', 'Building Owner Name'),
            'building_owner_phone'                             => Yii::t('app', 'Building Owner Phone'),
            'trading_type_id'                                  => Yii::t('app', 'Trading Type'),
            'shop_type_id'                                     => Yii::t('app', 'Shop Type'),
            'has_bio_waste'                                    => Yii::t('app', 'Has Bio Waste'),
            'has_non_bio_waste'                                => Yii::t('app', 'Has Non Bio Waste'),
            'has_disposible_waste'                             => Yii::t('app', 'Has Disposible Waste'),
            'lat'                                              => Yii::t('app', 'Lat'),
            'lng'                                              => Yii::t('app', 'Lng'),
            'fee_collection_interval_id'                       => Yii::t('app', 'Fee Collection Interval'),
            'has_bio_waste_management_facility'                => Yii::t('app', 'Has Bio Waste Management Facility'),
            'bio_waste_management_facility_operational'        => Yii::t('app', 'Bio Waste Management Facility Operational'),
            'bio_waste_management_facility_repair_help_needed' => Yii::t('app', 'Bio Waste Management Facility Repair Help Needed'),
            'bio_waste_collection_method_id'                   => Yii::t('app', 'Bio Waste Collection Method'),
            'bio_waste_collection_needed'                      => Yii::t('app', 'Bio Waste Collection Needed'),
            'non_bio_waste_collection_method_id'               => Yii::t('app', 'Non Bio Waste Collection Method'),
            'has_terrace_farming_interest'                     => Yii::t('app', 'Has Terrace Farming Interest'),
            'terrace_farming_help_type_id'                     => Yii::t('app', 'Terrace Farming Help Type'),
            'creator_account_id'                               => Yii::t('app', 'Creator Account'),
            'people_count'                               => Yii::t('app', 'House People Count'),
            'house_adult_count'                                => Yii::t('app', 'House Adult Count'),
            'house_children_count'                             => Yii::t('app', 'House Children Count'),
            'created_at'                                       => Yii::t('app', 'Created At'),
            'modified_at'                                      => Yii::t('app', 'Modified At'),
            'status'                                           => Yii::t('app', 'Status'),
            'fkWard.name'                                      => Yii::t('app', 'Ward'),
            'fkBuildingType.name'                              => Yii::t('app', 'Building Type')
        ];
    }

    /**
     * @return mixed
     */
    public function getFkWard()
    {
        return $this->hasOne(Ward::className(), ['id' => 'ward_id']);
    }
    public function getFkAdministrationType()
    {
        return $this->hasOne(AdministrationType::className(), ['id' => 'administration_type']);
    }
    public function getFkAssociation()
    {
        return $this->hasOne(ResidentialAssociation::className(), ['id' => 'residential_association_id']);
    }
     public function getFkAccount()
    {
        return $this->hasOne(Account::className(), ['id' => 'creator_account_id']);
    }
    public function getFkCustomerAccount()
    {
        return $this->hasOne(Account::className(), ['customer_id' => 'id']);
    }

    // public function getFkBuildingType()
    // {
    //     return $this->hasOne(BuildingType::className(), ['id' => 'building_type_id']);
    // }
    /**
     * @param $id
     */
    public function deleteCustomer($id)
    {
        $modelCustomer= Customer::find()->where(['id'=>$id])->one();
        if($modelCustomer)
        {
            $modelWardCustomerCount = WardCustomerCount::find()->where(['ward_id'=>$modelCustomer->ward_id])->andWhere(['ward_customer_count_new.date'=>date($modelCustomer->created_at)])->one();
            if($modelWardCustomerCount)
            {
                if($modelCustomer->building_type_id==1)
                {
                    $modelWardCustomerCount->house_count = $modelWardCustomerCount->house_count-1;
                }elseif($modelCustomer->building_type_id==2)
                {
                    $modelWardCustomerCount->shop_count = $modelWardCustomerCount->shop_count-1;
                }elseif($modelCustomer->building_type_id==3)
                {
                    $modelWardCustomerCount->flat_count = $modelWardCustomerCount->flat_count-1;
                }
                elseif($modelCustomer->building_type_id==5)
                {
                    $modelWardCustomerCount->hospital_count = $modelWardCustomerCount->hospital_count-1;
                }
                elseif($modelCustomer->building_type_id==6)
                {
                    $modelWardCustomerCount->public_place_count = $modelWardCustomerCount->public_place_count-1;
                }
                elseif($modelCustomer->building_type_id==7)
                {
                    $modelWardCustomerCount->office_count = $modelWardCustomerCount->office_count-1;
                }
                elseif($modelCustomer->building_type_id==9)
                {
                    $modelWardCustomerCount->auditorium_count = $modelWardCustomerCount->auditorium_count-1;
                }
                elseif($modelCustomer->building_type_id==10)
                {
                    $modelWardCustomerCount->market_count = $modelWardCustomerCount->market_count-1;
                }
                elseif($modelCustomer->building_type_id==11)
                {
                    $modelWardCustomerCount->religious_institution_count = $modelWardCustomerCount->religious_institution_count-1;
                }
                $modelWardCustomerCount->save(false);
            }
            $modelSurveyCustomerCount = SurveyCustomerCount::find()->where(['account_id'=>$modelCustomer->creator_account_id])->andWhere(['survey_customer_count.date'=>date($modelCustomer->created_at)])->one();
            if($modelSurveyCustomerCount)
            {
                if($modelCustomer->building_type_id==1)
                {
                    $modelSurveyCustomerCount->house_count = $modelSurveyCustomerCount->house_count-1;
                }elseif($modelCustomer->building_type_id==2)
                {
                    $modelSurveyCustomerCount->shop_count = $modelSurveyCustomerCount->shop_count-1;
                }elseif($modelCustomer->building_type_id==3)
                {
                    $modelSurveyCustomerCount->flat_count = $modelSurveyCustomerCount->flat_count-1;
                }
                elseif($modelCustomer->building_type_id==5)
                {
                    $modelSurveyCustomerCount->hospital_count = $modelSurveyCustomerCount->hospital_count-1;
                }
                elseif($modelCustomer->building_type_id==6)
                {
                    $modelSurveyCustomerCount->public_place_count = $modelSurveyCustomerCount->public_place_count-1;
                }
                elseif($modelCustomer->building_type_id==7)
                {
                    $modelSurveyCustomerCount->office_count = $modelSurveyCustomerCount->office_count-1;
                }
                elseif($modelCustomer->building_type_id==9)
                {
                    $modelSurveyCustomerCount->auditorium_count = $modelSurveyCustomerCount->auditorium_count-1;
                }
                elseif($modelCustomer->building_type_id==10)
                {
                    $modelSurveyCustomerCount->market_count = $modelSurveyCustomerCount->market_count-1;
                }
                elseif($modelCustomer->building_type_id==11)
                {
                    $modelSurveyCustomerCount->religious_institution_count = $modelSurveyCustomerCount->religious_institution_count-1;
                }
                $modelSurveyCustomerCount->save(false);
            }
        }
        $connection = Yii::$app->db;
        $connection->createCommand()->update('customer', ['status' => 0], 'id=:id')->bindParam(':id', $id)->execute();

        return true;
    }

    /**
     * @return mixed
     */
    public function getCustomers()
    {
        $customers = Customer::find()->where(['status' => 1])->all();

        return $customers;
    }

    /**
     * @return mixed
     */
    public function getFkBuildingType()
    {
        return $this->hasOne(BuildingType::className(), ['id' => 'building_type_id']);
    }
     public function getFkBuildingSubType()
    {
        return $this->hasOne(BuildingTypeSubTypes::className(), ['id' => 'building_sub_type']);
    }

    /**
     * @return mixed
     */
    public function getBuildingType()
    {
        $ret               = null;
        $modelBuildingType = $this->fkBuildingType;
        if ($modelBuildingType)
        {
            $ret = $modelBuildingType->name;
        }

        return $ret;
    }

    /**
     * @return mixed
     */
    public function getFkTradingType()
    {
        return $this->hasOne(TradingType::className(), ['id' => 'trading_type_id']);
    }

    /**
     * @return mixed
     */
    public function getTradingType()
    {
        $ret              = null;
        $modelTradingType = $this->fkTradingType;
        if ($modelTradingType)
        {
            $ret = $modelTradingType->name;
        }

        return $ret;
    }

    /**
     * @return mixed
     */
    public function getFkShopType()
    {
        return $this->hasOne(ShopType::className(), ['id' => 'shop_type_id']);
    }

    /**
     * @return mixed
     */
    public function getShopType()
    {
        $ret           = null;
        $modelShopType = $this->fkShopType;
        if ($modelShopType)
        {
            $ret = $modelShopType->name;
        }

        return $ret;
    }

    /**
     * @return mixed
     */
    public function getFkFeeCollectionInterval()
    {
        return $this->hasOne(FeeCollectionInterval::className(), ['id' => 'fee_collection_interval_id']);
    }

    /**
     * @return mixed
     */
    public function getFeeCollectionInterval()
    {
        $ret                        = null;
        $modelFeeCollectionInterval = $this->fkFeeCollectionInterval;
        if ($modelFeeCollectionInterval)
        {
            $ret = $modelFeeCollectionInterval->name;
        }

        return $ret;
    }

    /**
     * @return mixed
     */
    public function getFkBioWasteCollectionMethod()
    {
        return $this->hasOne(WasteCollectionMethod::className(), ['id' => 'bio_waste_collection_method_id']);
    }
    public function getFkBioMedicalWasteCollectionMethod()
    {
        return $this->hasOne(WasteCollectionMethod::className(), ['id' => 'bio_medical_waste_collection_method']);
    }

    /**
     * @return mixed
     */
    public function getBioWasteCollectionMethod()
    {
        $ret                           = null;
        $modelBioWasteCollectionMethod = $this->fkBioWasteCollectionMethod;
        if ($modelBioWasteCollectionMethod)
        {
            $ret = $modelBioWasteCollectionMethod->name;
        }

        return $ret;
    }

    /**
     * @return mixed
     */
    public function getFkNonBioWasteCollectionMethod()
    {
        return $this->hasOne(WasteCollectionMethod::className(), ['id' => 'non_bio_waste_collection_method_id']);
    }

    /**
     * @return mixed
     */
    public function getNonBioWasteCollectionMethod()
    {
        $ret                              = null;
        $modelNonBioWasteCollectionMethod = $this->fkNonBioWasteCollectionMethod;
        if ($modelNonBioWasteCollectionMethod)
        {
            $ret = $modelNonBioWasteCollectionMethod->name;
        }

        return $ret;
    }

    /**
     * @return mixed
     */
    public function getFkTerraceFarmingHelpType()
    {
        return $this->hasOne(TerraceFarmingHelpType::className(), ['id' => 'terrace_farming_help_type_id']);
    }

    /**
     * @return mixed
     */
    public function getTerraceFarmingHelpType()
    {
        $ret                         = null;
        $modelTerraceFarmingHelpType = $this->fkTerraceFarmingHelpType;
        if ($modelTerraceFarmingHelpType)
        {
            $ret = $modelTerraceFarmingHelpType->name;
        }

        return $ret;
    }

    /**
     * @return mixed
     */
    public function getFkPublicPlaceType()
    {
        return $this->hasOne(PublicPlaceType::className(), ['id' => 'public_place_type_id']);
    }
    public function getFkPublicGatheringMethod()
    {
        return $this->hasOne(PublicGatheringMethods::className(), ['id' => 'public_gathering_method']);
    }

    /**
     * @return mixed
     */
    public function getPublicPlaceType()
    {
        $ret                  = null;
        $modelPublicPlaceType = $this->fkPublicPlaceType;
        if ($modelPublicPlaceType)
        {
            $ret = $modelPublicPlaceType->name;
        }

        return $ret;
    }

    /**
     * @return mixed
     */
    public function getFkOfficeType()
    {
        return $this->hasOne(OfficeType::className(), ['id' => 'office_type_id']);
    }

    /**
     * @return mixed
     */
    public function getOfficeType()
    {
        $ret             = null;
        $modelOfficeType = $this->fkOfficeType;
        if ($modelOfficeType)
        {
            $ret = $modelOfficeType->name;
        }

        return $ret;
    }

    /**
     * @param  $id
     * @return mixed
     */
    public function qrCodeSet($id)
    {
        $model = QrCode::find()
            ->leftjoin('account', 'account.id=qr_code.account_id')
            ->leftjoin('customer', 'customer.id=account.customer_id')
            ->andWhere(['qr_code.status' => 1])
            ->andWhere(['account.customer_id' => $id])
            ->one();

        return $model;
    }

    /**
     * @param  $ward
     * @return mixed
     */
    public function getLsgis($ward)
    {
        $name = null;
        $ward = Ward::find()->where(['id' => $ward])->one();
        if ($ward)
        {
            $lsgi = Lsgi::find()->where(['id' => $ward->lsgi_id])->one();
            if ($lsgi)
            {
                $name = $lsgi->name;
            }
        }

        return $name;
    }

    /**
     * @param  $ward
     * @return mixed
     */
    public function getBlock($ward)
    {
        $name  = null;
        $wards = Ward::find()->where(['status' => 1])->andWhere(['id' => $ward])->one();
        if ($wards)
        {
            $lsgi = Lsgi::find()->where(['id' => $wards->lsgi_id])->one();
            if ($lsgi)
            {
                $block = LsgiBlock::find()->where(['id' => $lsgi->block_id])->one();
                if ($block)
                {
                    $name = $block->name;
                }
            }
        }

        return $name;
    }

    /**
     * @param  $ward
     * @return mixed
     */
    public function getConstituency($ward)
    {
      $name = null;
        $wards = Ward::find()->where(['status' => 1])->andWhere(['id' => $ward])->one();
        if ($wards)
        {
            $lsgi = Lsgi::find()->where(['id' => $wards->lsgi_id])->one();
            if ($lsgi)
            {
                $block = LsgiBlock::find()->where(['id' => $lsgi->block_id])->one();
                if ($block)
                {
                    $assembly_constituency = AssemblyConstituency::find()->where(['id' => $block->assembly_constituency_id])->one();
                    if ($assembly_constituency)
                    {
                        $name = $assembly_constituency->name;
                    }
                }
            }
        }

        return $name;
    }

    /**
     * @param  $customer
     * @return mixed
     */
    public function getDistrict($ward)
    {
      $name = null;
        $wards = Ward::find()->where(['status' => 1])->andWhere(['id' => $ward])->one();
        if ($wards)
        {
            $lsgi = Lsgi::find()->where(['id' => $wards->lsgi_id])->one();
            if ($lsgi)
            {
                $block = LsgiBlock::find()->where(['id' => $lsgi->block_id])->one();
                if ($block)
                {
                    $assembly_constituency = AssemblyConstituency::find()->where(['id' => $block->assembly_constituency_id])->one();
                    if ($assembly_constituency)
                    {
                        $district = District::find()->where(['id' => $assembly_constituency->district_id])->one();
                        $name     = $district->id;
                    }
                }
            }
        }

        return $name;
    }

    /**
     * @return mixed
     */
    public function getDistricts()
    {
        $district = District::find()->where(['status' => 1])->all();

        return $district;
    }

    /**
     * @param  $customer
     * @return mixed
     */
    public function getWard($ward = null)
    {
        $name  = null;
        $wards = Ward::find()->where(['status' => 1])->andWhere(['id' => $ward])->one();
        if ($wards)
        {
            $name = $wards->name;
        }

        return $name;
    }
    public function getProfileUrl()
  {
    $logoUrl = isset(Yii::$app->params['defaultImage'])?(Yii::$app->params['base_url'].Yii::$app->params['defaultImage']):'';
    $fkLogoUrl = $this->fkImage;
    if($fkLogoUrl){
      $logoUrl = $fkLogoUrl->fullUrl();
    }
    return $logoUrl;
  }
   public function getFkImage()
  {
    return $this->hasOne(Image::className(), ['id' => 'image_id']);
  }
   public function getHouseCount($id = null,$from=null,$to=null)
    {     
             // $qry ="UPDATE survey_customer_count set survey_customer_count.house_count=(SELECT count(*) FROM customer WHERE survey_customer_count.status=1 and customer.status=1 and customer.building_type_id=1 and customer.creator_account_id=:id and date(customer.created_at)=survey_customer_count.date) where survey_customer_count.account_id=:id";
       //  $qry ="UPDATE survey_customer_count_test set survey_customer_count_test.house_count=(SELECT survey_customer_count_test.date,count(*) FROM customer LEFT JOIN survey_customer_count_test on survey_customer_count_test.account_id=customer.creator_account_id WHERE survey_customer_count_test.status=1 and customer.status=1 and customer.building_type_id=1 and customer.creator_account_id=:id and date(customer.created_at)=survey_customer_count_test.date GROUP by survey_customer_count_test.date)where survey_customer_count_test.account_id=:id";
       //  $command1 =  Yii::$app->db->createCommand($qry)
       // ->bindParam(':id',$id);
       // $ret = $command1->execute();
       $count =0 ;
        $query = "SELECT sum(survey_customer_count.house_count)  as count FROM survey_customer_count WHERE survey_customer_count.account_id=:id" ;
         if($from!=null)
        {
            $query.= " and survey_customer_count.date >= :from";
        }
        if($to!=null)
        {
            $query.= " and survey_customer_count.date <= :to";
        }
       $command =  Yii::$app->db->createCommand($query)
       ->bindParam(':id',$id)
       ;
       if($from)
       {
        $command->bindParam(':from',$from);
       }
       if($to)
       {
        $command->bindParam(':to',$to);
       }
       $ret = $command->queryOne();

       $count = $ret['count'];
if($count>0) 
{
    $count = $count;
}
else
{
    $count =0;
}
         return isset($count)?$count:0;
    }
    public function getFlatCount($id = null,$from=null,$to=null)
    {
       
       //   $qry ="UPDATE survey_customer_count set survey_customer_count.flat_count=(SELECT count(*) FROM customer WHERE survey_customer_count.status=1 and customer.status=1 and customer.building_type_id=3 and customer.creator_account_id=:id and date(customer.created_at)=survey_customer_count.date) where survey_customer_count.account_id=:id";
       //  $command1 =  Yii::$app->db->createCommand($qry)
       // ->bindParam(':id',$id);
       // $ret = $command1->execute();
       $count =0 ;
        $query = "SELECT sum(survey_customer_count.flat_count)  as count FROM survey_customer_count WHERE survey_customer_count.account_id=:id" ;
         if($from!=null)
        {
            $query.= " and survey_customer_count.date >= :from";
        }
        if($to!=null)
        {
            $query.= " and survey_customer_count.date <= :to";
        }
       $command =  Yii::$app->db->createCommand($query)
       ->bindParam(':id',$id)
       ;
       if($from)
       {
        $command->bindParam(':from',$from);
       }
       if($to)
       {
        $command->bindParam(':to',$to);
       }
       $ret = $command->queryOne();

       $count = $ret['count'];
if($count>0) 
{
    $count = $count;
}
else
{
    $count =0;
}
         return isset($count)?$count:0;
    }
    public function getShopCount($id = null,$from=null,$to=null)
    {
      
       // $qry ="UPDATE survey_customer_count set survey_customer_count.shop_count=(SELECT count(*) FROM customer WHERE survey_customer_count.status=1 and customer.status=1 and customer.building_type_id=2 and customer.creator_account_id=:id and date(customer.created_at)=survey_customer_count.date) where survey_customer_count.account_id=:id";
       //  $command1 =  Yii::$app->db->createCommand($qry)
       // ->bindParam(':id',$id);
       // $ret = $command1->execute();
       $count =0 ;
        $query = "SELECT sum(survey_customer_count.shop_count)  as count FROM survey_customer_count WHERE survey_customer_count.account_id=:id" ;
         if($from!=null)
        {
            $query.= " and survey_customer_count.date >= :from";
        }
        if($to!=null)
        {
            $query.= " and survey_customer_count.date <= :to";
        }
       $command =  Yii::$app->db->createCommand($query)
       ->bindParam(':id',$id)
       ;
       if($from)
       {
        $command->bindParam(':from',$from);
       }
       if($to)
       {
        $command->bindParam(':to',$to);
       }
       $ret = $command->queryOne();

       $count = $ret['count'];
if($count>0) 
{
    $count = $count;
}
else
{
    $count =0;
}
         return isset($count)?$count:0;
    }
     public function getHospitalCount($id = null,$from=null,$to=null)
    {
      
       // $qry ="UPDATE survey_customer_count set survey_customer_count.hospital_count=(SELECT count(*) FROM customer WHERE survey_customer_count.status=1 and customer.status=1 and customer.building_type_id=5 and customer.creator_account_id=:id and date(customer.created_at)=survey_customer_count.date) where survey_customer_count.account_id=:id";
       //  $command1 =  Yii::$app->db->createCommand($qry)
       // ->bindParam(':id',$id);
       // $ret = $command1->execute();
       $count =0 ;
        $query = "SELECT sum(survey_customer_count.hospital_count)  as count FROM survey_customer_count WHERE survey_customer_count.account_id=:id" ;
         if($from!=null)
        {
            $query.= " and survey_customer_count.date >= :from";
        }
        if($to!=null)
        {
            $query.= " and survey_customer_count.date <= :to";
        }
       $command =  Yii::$app->db->createCommand($query)
       ->bindParam(':id',$id)
       ;
       if($from)
       {
        $command->bindParam(':from',$from);
       }
       if($to)
       {
        $command->bindParam(':to',$to);
       }
       $ret = $command->queryOne();

       $count = $ret['count'];
if($count>0) 
{
    $count = $count;
}
else
{
    $count =0;
}
         return isset($count)?$count:0;
    }
    public function getPublicPlaceCount($id = null,$from=null,$to=null)
    {
      
      // $qry ="UPDATE survey_customer_count set survey_customer_count.public_place_count=(SELECT count(*) FROM customer WHERE survey_customer_count.status=1 and customer.status=1 and customer.building_type_id=6 and customer.creator_account_id=:id and date(customer.created_at)=survey_customer_count.date) where survey_customer_count.account_id=:id";
      //   $command1 =  Yii::$app->db->createCommand($qry)
      //  ->bindParam(':id',$id);
      //  $ret = $command1->execute();
       $count =0 ;
        $query = "SELECT sum(survey_customer_count.public_place_count)  as count FROM survey_customer_count WHERE survey_customer_count.account_id=:id" ;
         if($from!=null)
        {
            $query.= " and survey_customer_count.date >= :from";
        }
        if($to!=null)
        {
            $query.= " and survey_customer_count.date <= :to";
        }
       $command =  Yii::$app->db->createCommand($query)
       ->bindParam(':id',$id)
       ;
       if($from)
       {
        $command->bindParam(':from',$from);
       }
       if($to)
       {
        $command->bindParam(':to',$to);
       }
       $ret = $command->queryOne();

       $count = $ret['count'];
if($count>0) 
{
    $count = $count;
}
else
{
    $count =0;
}
         return isset($count)?$count:0;
    }
     public function getReligiousCount($id = null,$from=null,$to=null)
    {
     // $qry ="UPDATE survey_customer_count set survey_customer_count.religious_institution_count=(SELECT count(*) FROM customer WHERE survey_customer_count.status=1 and customer.status=1 and customer.building_type_id=11 and customer.creator_account_id=:id and date(customer.created_at)=survey_customer_count.date) where survey_customer_count.account_id=:id";
     //    $command1 =  Yii::$app->db->createCommand($qry)
     //   ->bindParam(':id',$id);
     //   $ret = $command1->execute();
       $count =0 ;
        $query = "SELECT sum(survey_customer_count.religious_institution_count)  as count FROM survey_customer_count WHERE survey_customer_count.account_id=:id" ;
         if($from!=null)
        {
            $query.= " and survey_customer_count.date >= :from";
        }
        if($to!=null)
        {
            $query.= " and survey_customer_count.date <= :to";
        }
       $command =  Yii::$app->db->createCommand($query)
       ->bindParam(':id',$id)
       ;
       if($from)
       {
        $command->bindParam(':from',$from);
       }
       if($to)
       {
        $command->bindParam(':to',$to);
       }
       $ret = $command->queryOne();

       $count = $ret['count'];
if($count>0) 
{
    $count = $count;
}
else
{
    $count =0;
}
         return isset($count)?$count:0;
    }
    public function getOfficeCount($id = null,$from=null,$to=null)
    {
      
       //  $qry ="UPDATE survey_customer_count set survey_customer_count.office_count=(SELECT count(*) FROM customer WHERE survey_customer_count.status=1 and customer.status=1 and customer.building_type_id=7 and customer.creator_account_id=:id and date(customer.created_at)=survey_customer_count.date) where survey_customer_count.account_id=:id";
       //  $command1 =  Yii::$app->db->createCommand($qry)
       // ->bindParam(':id',$id);
       // $ret = $command1->execute();
       $count =0 ;
        $query = "SELECT sum(survey_customer_count.office_count)  as count FROM survey_customer_count WHERE survey_customer_count.account_id=:id" ;
         if($from!=null)
        {
            $query.= " and survey_customer_count.date >= :from";
        }
        if($to!=null)
        {
            $query.= " and survey_customer_count.date <= :to";
        }
       $command =  Yii::$app->db->createCommand($query)
       ->bindParam(':id',$id)
       ;
       if($from)
       {
        $command->bindParam(':from',$from);
       }
       if($to)
       {
        $command->bindParam(':to',$to);
       }
       $ret = $command->queryOne();

       $count = $ret['count'];
if($count>0) 
{
    $count = $count;
}
else
{
    $count =0;
}
         return isset($count)?$count:0;
    }
    public function getAuditoriumCount($id = null,$from=null,$to=null)
    {
      
      // $qry ="UPDATE survey_customer_count set survey_customer_count.auditorium_count=(SELECT count(*) FROM customer WHERE survey_customer_count.status=1 and customer.status=1 and customer.building_type_id=9 and customer.creator_account_id=:id and date(customer.created_at)=survey_customer_count.date) where survey_customer_count.account_id=:id";
      //   $command1 =  Yii::$app->db->createCommand($qry)
      //  ->bindParam(':id',$id);
      //  $ret = $command1->execute();
       $count =0 ;
        $query = "SELECT sum(survey_customer_count.auditorium_count)  as count FROM survey_customer_count WHERE survey_customer_count.account_id=:id" ;
         if($from!=null)
        {
            $query.= " and survey_customer_count.date >= :from";
        }
        if($to!=null)
        {
            $query.= " and survey_customer_count.date <= :to";
        }
       $command =  Yii::$app->db->createCommand($query)
       ->bindParam(':id',$id)
       ;
       if($from)
       {
        $command->bindParam(':from',$from);
       }
       if($to)
       {
        $command->bindParam(':to',$to);
       }
       $ret = $command->queryOne();

       $count = $ret['count'];
if($count>0) 
{
    $count = $count;
}
else
{
    $count =0;
}
         return isset($count)?$count:0;
    }
     public function getMarketCount($id = null,$from=null,$to=null)
    {
     
       //  $qry ="UPDATE survey_customer_count set survey_customer_count.market_count=(SELECT count(*) FROM customer WHERE survey_customer_count.status=1 and customer.status=1 and customer.building_type_id=10 and customer.creator_account_id=:id and date(customer.created_at)=survey_customer_count.date) where survey_customer_count.account_id=:id";
       //  $command1 =  Yii::$app->db->createCommand($qry)
       // ->bindParam(':id',$id);
       // $ret = $command1->execute();
       $count =0 ;
        $query = "SELECT sum(survey_customer_count.market_count)  as count FROM survey_customer_count WHERE survey_customer_count.account_id=:id" ;
         if($from!=null)
        {
            $query.= " and survey_customer_count.date >= :from";
        }
        if($to!=null)
        {
            $query.= " and survey_customer_count.date <= :to";
        }
       $command =  Yii::$app->db->createCommand($query)
       ->bindParam(':id',$id)
       ;
       if($from)
       {
        $command->bindParam(':from',$from);
       }
       if($to)
       {
        $command->bindParam(':to',$to);
       }
       $ret = $command->queryOne();

       $count = $ret['count'];
if($count>0) 
{
    $count = $count;
}
else
{
    $count =0;
}
         return isset($count)?$count:0;
    }
    public function getFkWasteCollectionInterval()
    {
        return $this->hasOne(WasteCollectionInterval::className(), ['id' => 'waste_collection_interval_id']);
    }
    //  public function getWardHouseCount($id = null,$from=null,$to=null)
    // {
    //      $query = "SELECT COUNT(*)  as count FROM customer left join account on account.customer_id=customer.id WHERE ward_id = :id AND building_type_id = 1 and customer.status=1 and account.status=1" ;
    //      if($from!=null)
    //     {
    //         $query.= " and customer.created_at >= :from";
    //     }
    //     if($to!=null)
    //     {
    //         $query.= " and customer.created_at <= :to";
    //     }
    //    $command =  Yii::$app->db->createCommand($query)
    //    ->bindParam(':id',$id)
    //    ;
    //    if($from)
    //    {
    //     $command->bindParam(':from',$from);
    //    }
    //    if($to)
    //    {
    //     $command->bindParam(':to',$to);
    //    }
    //    $ret = $command->queryOne();
    //    $count = $ret['count'];


    //     return $count;
    // }
    // public function getWardFlatCount($id = null,$from=null,$to=null)
    // {
    //     $count  = 0;
    //     $query = "SELECT COUNT(*)  as count FROM customer left join account on account.customer_id=customer.id WHERE ward_id = :id AND building_type_id = 3 and customer.status=1 and account.status=1" ;
    //      if($from!=null)
    //     {
    //         $query.= " and customer.created_at >= :from";
    //     }
    //     if($to!=null)
    //     {
    //         $query.= " and customer.created_at <= :to";
    //     }
    //    $command =  Yii::$app->db->createCommand($query)
    //    ->bindParam(':id',$id)
    //    ;
    //    if($from)
    //    {
    //     $command->bindParam(':from',$from);
    //    }
    //    if($to)
    //    {
    //     $command->bindParam(':to',$to);
    //    }
    //    $ret = $command->queryOne();
    //    $count = $ret['count'];


    //     return $count;
    // }
    // public function getWardShopCount($id = null,$from=null,$to=null)
    // {
    //     $count  = 0;
    //     $query = "SELECT COUNT(*)  as count FROM customer left join account on account.customer_id=customer.id WHERE ward_id = :id AND building_type_id = 2 and customer.status=1 and account.status=1" ;
    //      if($from!=null)
    //     {
    //         $query.= " and customer.created_at >= :from";
    //     }
    //     if($to!=null)
    //     {
    //         $query.= " and customer.created_at <= :to";
    //     }
    //    $command =  Yii::$app->db->createCommand($query)
    //    ->bindParam(':id',$id)
    //    ;
    //    if($from)
    //    {
    //     $command->bindParam(':from',$from);
    //    }
    //    if($to)
    //    {
    //     $command->bindParam(':to',$to);
    //    }
    //    $ret = $command->queryOne();
    //    $count = $ret['count'];


    //     return $count;
    // }
    //  public function getWardHospitalCount($id = null,$from=null,$to=null)
    // {
    //     $count  = 0;
    //    $query = "SELECT COUNT(*)  as count FROM customer left join account on account.customer_id=customer.id WHERE ward_id = :id AND building_type_id = 5 and customer.status=1 and account.status=1" ;
    //      if($from!=null)
    //     {
    //         $query.= " and customer.created_at >= :from";
    //     }
    //     if($to!=null)
    //     {
    //         $query.= " and customer.created_at <= :to";
    //     }
    //    $command =  Yii::$app->db->createCommand($query)
    //    ->bindParam(':id',$id)
    //    ;
    //    if($from)
    //    {
    //     $command->bindParam(':from',$from);
    //    }
    //    if($to)
    //    {
    //     $command->bindParam(':to',$to);
    //    }
    //    $ret = $command->queryOne();
    //    $count = $ret['count'];


    //     return $count;
    // }
    // public function getWardPublicPlaceCount($id = null,$from=null,$to=null)
    // {
    //     $count  = 0;
    //     $query = "SELECT COUNT(*)  as count FROM customer left join account on account.customer_id=customer.id WHERE ward_id = :id AND building_type_id = 6 and customer.status=1 and account.status=1" ;
    //      if($from!=null)
    //     {
    //         $query.= " and customer.created_at >= :from";
    //     }
    //     if($to!=null)
    //     {
    //         $query.= " and customer.created_at <= :to";
    //     }
    //    $command =  Yii::$app->db->createCommand($query)
    //    ->bindParam(':id',$id)
    //    ;
    //    if($from)
    //    {
    //     $command->bindParam(':from',$from);
    //    }
    //    if($to)
    //    {
    //     $command->bindParam(':to',$to);
    //    }
    //    $ret = $command->queryOne();
    //    $count = $ret['count'];


    //     return $count;
    // }

    //  public function getWardReligiousCount($id = null,$from=null,$to=null)
    // {
    //     $count  = 0;
    //     $query = "SELECT COUNT(*)  as count FROM customer left join account on account.customer_id=customer.id WHERE ward_id = :id AND building_type_id = 11 and customer.status=1 and account.status=1" ;
    //      if($from!=null)
    //     {
    //         $query.= " and customer.created_at >= :from";
    //     }
    //     if($to!=null)
    //     {
    //         $query.= " and customer.created_at <= :to";
    //     }
    //    $command =  Yii::$app->db->createCommand($query)
    //    ->bindParam(':id',$id)
    //    ;
    //    if($from)
    //    {
    //     $command->bindParam(':from',$from);
    //    }
    //    if($to)
    //    {
    //     $command->bindParam(':to',$to);
    //    }
    //    $ret = $command->queryOne();
    //    $count = $ret['count'];


    //     return $count;
    // }

    // public function getWardOfficeCount($id = null,$from=null,$to=null)
    // {
    //     $count  = 0;
    //     $query = "SELECT COUNT(*)  as count FROM customer left join account on account.customer_id=customer.id WHERE ward_id = :id AND building_type_id = 7 and customer.status=1 and account.status=1" ;
    //      if($from!=null)
    //     {
    //         $query.= " and customer.created_at >= :from";
    //     }
    //     if($to!=null)
    //     {
    //         $query.= " and customer.created_at <= :to";
    //     }
    //    $command =  Yii::$app->db->createCommand($query)
    //    ->bindParam(':id',$id)
    //    ;
    //    if($from)
    //    {
    //     $command->bindParam(':from',$from);
    //    }
    //    if($to)
    //    {
    //     $command->bindParam(':to',$to);
    //    }
    //    $ret = $command->queryOne();
    //    $count = $ret['count'];


    //     return $count;
    // }
    // public function getWardAuditoriumCount($id = null,$from=null,$to=null)
    // {
    //     $count  = 0;
    //    $query = "SELECT COUNT(*)  as count FROM customer left join account on account.customer_id=customer.id WHERE ward_id = :id AND building_type_id = 9 and customer.status=1 and account.status=1" ;
    //      if($from!=null)
    //     {
    //         $query.= " and customer.created_at >= :from";
    //     }
    //     if($to!=null)
    //     {
    //         $query.= " and customer.created_at <= :to";
    //     }
    //    $command =  Yii::$app->db->createCommand($query)
    //    ->bindParam(':id',$id)
    //    ;
    //    if($from)
    //    {
    //     $command->bindParam(':from',$from);
    //    }
    //    if($to)
    //    {
    //     $command->bindParam(':to',$to);
    //    }
    //    $ret = $command->queryOne();
    //    $count = $ret['count'];


    //     return $count;
    // }
    //  public function getWardMarketCount($id = null,$from=null,$to=null)
    // {
    //     $count  = 0;
    //     $query = "SELECT COUNT(*)  as count FROM customer left join account on account.customer_id=customer.id WHERE ward_id = :id AND building_type_id = 10 and customer.status=1 and account.status=1" ;
    //      if($from!=null)
    //     {
    //         $query.= " and customer.created_at >= :from";
    //     }
    //     if($to!=null)
    //     {
    //         $query.= " and customer.created_at <= :to";
    //     }
    //    $command =  Yii::$app->db->createCommand($query)
    //    ->bindParam(':id',$id)
    //    ;
    //    if($from)
    //    {
    //     $command->bindParam(':from',$from);
    //    }
    //    if($to)
    //    {
    //     $command->bindParam(':to',$to);
    //    }
    //    $ret = $command->queryOne();
    //    $count = $ret['count'];


    //     return $count;
    // }
         public function getWardHouseCount($id = null,$from=null,$to=null)
    {
            $qry ="UPDATE ward_customer_count_new set ward_customer_count_new.house_count=(SELECT count(*) FROM customer WHERE ward_customer_count_new.status=1 and customer.status=1 and customer.building_type_id=1 and customer.ward_id=:id and date(customer.created_at)=ward_customer_count_new.date) where ward_customer_count_new.ward_id=:id";
        $command1 =  Yii::$app->db->createCommand($qry)
       ->bindParam(':id',$id);
       $ret = $command1->execute();
      $count =0 ;
        $query = "SELECT sum(ward_customer_count_new.house_count)  as count FROM ward_customer_count_new WHERE ward_customer_count_new.ward_id=:id" ;
         if($from!=null)
        {
            $query.= " and ward_customer_count_new.date >= :from";
        }
        if($to!=null)
        {
            $query.= " and ward_customer_count_new.date <= :to";
        }
       $command =  Yii::$app->db->createCommand($query)
       ->bindParam(':id',$id)
       ;
       if($from)
       {
        $command->bindParam(':from',$from);
       }
       if($to)
       {
        $command->bindParam(':to',$to);
       }
       $ret = $command->queryOne();

       $count = $ret['count'];
if($count>0) 
{
    $count = $count;
}
else
{
    $count =0;
}
         return isset($count)?$count:0;
    }
    public function getWardFlatCount($id = null,$from=null,$to=null)
    {
        $qry ="UPDATE ward_customer_count_new set ward_customer_count_new.flat_count=(SELECT count(*) FROM customer WHERE ward_customer_count_new.status=1 and customer.status=1 and customer.building_type_id=3 and customer.ward_id=:id and date(customer.created_at)=ward_customer_count_new.date) where ward_customer_count_new.ward_id=:id";
        $command1 =  Yii::$app->db->createCommand($qry)
       ->bindParam(':id',$id);
       $ret = $command1->execute();
        $count =0 ;
        $query = "SELECT sum(ward_customer_count_new.flat_count)  as count FROM ward_customer_count_new WHERE ward_customer_count_new.ward_id=:id" ;
         if($from!=null)
        {
            $query.= " and ward_customer_count_new.date >= :from";
        }
        if($to!=null)
        {
            $query.= " and ward_customer_count_new.date <= :to";
        }
       $command =  Yii::$app->db->createCommand($query)
       ->bindParam(':id',$id)
       ;
       if($from)
       {
        $command->bindParam(':from',$from);
       }
       if($to)
       {
        $command->bindParam(':to',$to);
       }
       $ret = $command->queryOne();

       $count = $ret['count'];
if($count>0) 
{
    $count = $count;
}
else
{
    $count =0;
}
         return isset($count)?$count:0;
    }
    public function getWardShopCount($id = null,$from=null,$to=null)
    {

        $qry ="UPDATE ward_customer_count_new set ward_customer_count_new.shop_count=(SELECT count(*) FROM customer WHERE ward_customer_count_new.status=1 and customer.status=1 and customer.building_type_id=2 and customer.ward_id=:id and date(customer.created_at)=ward_customer_count_new.date)  where ward_customer_count_new.ward_id=:id ";
        $command1 =  Yii::$app->db->createCommand($qry)
       ->bindParam(':id',$id);
       $ret = $command1->execute();
        $count  = 0;
        $count =0 ;
        $query = "SELECT sum(ward_customer_count_new.shop_count)  as count FROM ward_customer_count_new WHERE ward_customer_count_new.ward_id=:id" ;
         if($from!=null)
        {
            $query.= " and ward_customer_count_new.date >= :from";
        }
        if($to!=null)
        {
            $query.= " and ward_customer_count_new.date <= :to";
        }
       $command =  Yii::$app->db->createCommand($query)
       ->bindParam(':id',$id)
       ;
       if($from)
       {
        $command->bindParam(':from',$from);
       }
       if($to)
       {
        $command->bindParam(':to',$to);
       }
       $ret = $command->queryOne();

       $count = $ret['count'];
if($count>0) 
{
    $count = $count;
}
else
{
    $count =0;
}
         return isset($count)?$count:0;
    }
     public function getWardHospitalCount($id = null,$from=null,$to=null)
    {
        
        $qry ="UPDATE ward_customer_count_new set ward_customer_count_new.hospital_count=(SELECT count(*) FROM customer WHERE ward_customer_count_new.status=1 and customer.status=1 and customer.building_type_id=5 and customer.ward_id=:id and date(customer.created_at)=ward_customer_count_new.date) where ward_customer_count_new.ward_id=:id";
        $command1 =  Yii::$app->db->createCommand($qry)
       ->bindParam(':id',$id);
       $ret = $command1->execute();
        $count  = 0;
       $query = "SELECT sum(ward_customer_count_new.hospital_count)  as count FROM ward_customer_count_new WHERE ward_customer_count_new.ward_id=:id" ;
         if($from!=null)
        {
            $query.= " and ward_customer_count_new.date >= :from";
        }
        if($to!=null)
        {
            $query.= " and ward_customer_count_new.date <= :to";
        }
       $command =  Yii::$app->db->createCommand($query)
       ->bindParam(':id',$id)
       ;
       if($from)
       {
        $command->bindParam(':from',$from);
       }
       if($to)
       {
        $command->bindParam(':to',$to);
       }
       $ret = $command->queryOne();

       $count = $ret['count'];
if($count>0) 
{
    $count = $count;
}
else
{
    $count =0;
}
         return isset($count)?$count:0;
    }
    public function getWardPublicPlaceCount($id = null,$from=null,$to=null)
    {
      
        $qry ="UPDATE ward_customer_count_new set ward_customer_count_new.public_place_count=(SELECT count(*) FROM customer WHERE ward_customer_count_new.status=1 and customer.status=1 and customer.building_type_id=6 and customer.ward_id=:id and date(customer.created_at)=ward_customer_count_new.date) where ward_customer_count_new.ward_id=:id";
        $command1 =  Yii::$app->db->createCommand($qry)
       ->bindParam(':id',$id);
       $ret = $command1->execute();
       $count =0 ;
        $query = "SELECT sum(ward_customer_count_new.public_place_count)  as count FROM ward_customer_count_new WHERE ward_customer_count_new.ward_id=:id" ;
         if($from!=null)
        {
            $query.= " and ward_customer_count_new.date >= :from";
        }
        if($to!=null)
        {
            $query.= " and ward_customer_count_new.date <= :to";
        }
       $command =  Yii::$app->db->createCommand($query)
       ->bindParam(':id',$id)
       ;
       if($from)
       {
        $command->bindParam(':from',$from);
       }
       if($to)
       {
        $command->bindParam(':to',$to);
       }
       $ret = $command->queryOne();

       $count = $ret['count'];
if($count>0) 
{
    $count = $count;
}
else
{
    $count =0;
}
         return isset($count)?$count:0;
    }

     public function getWardReligiousCount($id = null,$from=null,$to=null)
    {
        $qry ="UPDATE ward_customer_count_new set ward_customer_count_new.religious_institution_count=(SELECT count(*) FROM customer WHERE ward_customer_count_new.status=1 and customer.status=1 and customer.building_type_id=11 and customer.ward_id=:id and date(customer.created_at)=ward_customer_count_new.date) where ward_customer_count_new.ward_id=:id";
        $command1 =  Yii::$app->db->createCommand($qry)
       ->bindParam(':id',$id);
       $ret = $command1->execute();
       $count =0 ;
       $query = "SELECT sum(ward_customer_count_new.religious_institution_count)  as count FROM ward_customer_count_new WHERE ward_customer_count_new.ward_id=:id" ;
         if($from!=null)
        {
            $query.= " and ward_customer_count_new.date >= :from";
        }
        if($to!=null)
        {
            $query.= " and ward_customer_count_new.date <= :to";
        }
       $command =  Yii::$app->db->createCommand($query)
       ->bindParam(':id',$id)
       ;
       if($from)
       {
        $command->bindParam(':from',$from);
       }
       if($to)
       {
        $command->bindParam(':to',$to);
       }
       $ret = $command->queryOne();

       $count = $ret['count'];
if($count>0) 
{
    $count = $count;
}
else
{
    $count =0;
}
         return isset($count)?$count:0;
    }

    public function getWardOfficeCount($id = null,$from=null,$to=null)
    {
        $qry ="UPDATE ward_customer_count_new set ward_customer_count_new.office_count=(SELECT count(*) FROM customer WHERE ward_customer_count_new.status=1 and customer.status=1 and customer.building_type_id=7 and customer.ward_id=:id and date(customer.created_at)=ward_customer_count_new.date) where ward_customer_count_new.ward_id=:id";
        $command1 =  Yii::$app->db->createCommand($qry)
       ->bindParam(':id',$id);
       $ret = $command1->execute();
        $count =0 ;
        $query = "SELECT sum(ward_customer_count_new.office_count)  as count FROM ward_customer_count_new WHERE ward_customer_count_new.ward_id=:id" ;
         if($from!=null)
        {
            $query.= " and ward_customer_count_new.date >= :from";
        }
        if($to!=null)
        {
            $query.= " and ward_customer_count_new.date <= :to";
        }
       $command =  Yii::$app->db->createCommand($query)
       ->bindParam(':id',$id)
       ;
       if($from)
       {
        $command->bindParam(':from',$from);
       }
       if($to)
       {
        $command->bindParam(':to',$to);
       }
       $ret = $command->queryOne();

       $count = $ret['count'];
if($count>0) 
{
    $count = $count;
}
else
{
    $count =0;
}
         return isset($count)?$count:0;
    }
    public function getWardAuditoriumCount($id = null,$from=null,$to=null)
    {
        $qry ="UPDATE ward_customer_count_new set ward_customer_count_new.auditorium_count=(SELECT count(*) FROM customer WHERE ward_customer_count_new.status=1 and customer.status=1 and customer.building_type_id=9 and customer.ward_id=:id and date(customer.created_at)=ward_customer_count_new.date) where ward_customer_count_new.ward_id=:id";
        $command1 =  Yii::$app->db->createCommand($qry)
       ->bindParam(':id',$id);
       $ret = $command1->execute();
        $count  = 0;
        $query = "SELECT sum(ward_customer_count_new.auditorium_count)  as count FROM ward_customer_count_new WHERE ward_customer_count_new.ward_id=:id" ;
         if($from!=null)
        {
            $query.= " and ward_customer_count_new.date >= :from";
        }
        if($to!=null)
        {
            $query.= " and ward_customer_count_new.date <= :to";
        }
       $command =  Yii::$app->db->createCommand($query)
       ->bindParam(':id',$id)
       ;
       if($from)
       {
        $command->bindParam(':from',$from);
       }
       if($to)
       {
        $command->bindParam(':to',$to);
       }
       $ret = $command->queryOne();

       $count = $ret['count'];
if($count>0) 
{
    $count = $count;
}
else
{
    $count =0;
}
         return isset($count)?$count:0;
    }
     public function getWardMarketCount($id = null,$from=null,$to=null)
    {
        $qry ="UPDATE ward_customer_count_new set ward_customer_count_new.market_count=(SELECT count(*) FROM customer WHERE ward_customer_count_new.status=1 and customer.status=1 and customer.building_type_id=10 and customer.ward_id=:id and date(customer.created_at)=ward_customer_count_new.date) where ward_customer_count_new.ward_id=:id";
        $command1 =  Yii::$app->db->createCommand($qry)
       ->bindParam(':id',$id);
       $ret = $command1->execute();
       $count =0 ;
        $query = "SELECT sum(ward_customer_count_new.market_count)  as count FROM ward_customer_count_new WHERE ward_customer_count_new.ward_id=:id" ;
         if($from!=null)
        {
            $query.= " and ward_customer_count_new.date >= :from";
        }
        if($to!=null)
        {
            $query.= " and ward_customer_count_new.date <= :to";
        }
       $command =  Yii::$app->db->createCommand($query)
       ->bindParam(':id',$id)
       ;
       if($from)
       {
        $command->bindParam(':from',$from);
       }
       if($to)
       {
        $command->bindParam(':to',$to);
       }
       $ret = $command->queryOne();

       $count = $ret['count'];
if($count>0) 
{
    $count = $count;
}
else
{
    $count =0;
}
         return isset($count)?$count:0;
    }
    public function getServiceDetails()
    {
        $serviceList = [];
        $service = null;
        if($this->fkCustomerAccount)
        {
            $accountService = AccountService::find()->where(['status'=>1])
            ->andWhere(['>','package_id',0])->andWhere(['account_id'=>$this->fkCustomerAccount->id])->all();
            if($accountService){
            foreach ($accountService as $key => $value) {
                $serviceList[] = $value->package_id;
            }
        }
        $accountServicePackage = AccountService::find()->where(['status'=>1])
            ->andWhere(['package_id'=>null])->andWhere(['account_id'=>$this->fkCustomerAccount->id])->all();
            if($accountServicePackage){
            foreach ($accountServicePackage as $key => $value) {
                $serviceList[] = $value->service_id;
            }
        }
        $serviceList = array_map("unserialize", array_unique(array_map("serialize", $serviceList)));
        $service = null;
        foreach ($serviceList as $key => $value) {
            $modelService= Service::find()->where(['id'=>$value])->andWhere(['status'=>1])->one();
            if($modelService)
            {
                $service = $service .$modelService->name.',';
            }
        }
        }
       return trim($service,',');
    }
    public function getGt($ward_id)
    {
      
        $gt =  Person::find()
                ->select('account.id as id,person.first_name as first_name')
                ->where(['account.status' => 1])
                ->leftjoin('account', 'account.person_id=person.id')
                ->leftjoin('account_ward','account_ward.account_id=account.id')
                ->andWhere(['account_ward.ward_id' => $ward_id])
                ->andWhere(['account.role' => 'green-technician'])
                ->all();

        return $gt;

    }
    public function getGtList($ward_id,$accountId)
    {
        $modelWard = Ward::find()->where(['id'=>$ward_id])->andWhere(['status'=>1])->one();
        $serviceList = [];
        $modelAccountService= AccountService::find()->where(['account_id'=>$accountId])->andWhere(['status'=>1])->all();
        foreach ($modelAccountService as $value) {
           $serviceList[] = $value->service_id;
        }
        $gt =  Person::find()
                ->select('account.id as id,person.first_name as first_name')
                ->where(['account.status' => 1])
                ->leftjoin('account', 'account.person_id=person.id')
                ->leftjoin('green_action_unit','green_action_unit.id=account.green_action_unit_id')
                ->leftjoin('green_action_unit_service','green_action_unit_service.green_action_unit_id=green_action_unit.id')
                ->andWhere(['green_action_unit.lsgi_id' => $modelWard->lsgi_id])
                ->andWhere(['green_action_unit_service.service_id' => $serviceList])
                ->andWhere(['account.status' => 1])
                ->andWhere(['person.status' => 1])
                ->andWhere(['green_action_unit_service.status' => 1])
                ->andWhere(['green_action_unit.status' => 1])
                ->andWhere(['account.role' => 'green-technician'])
                ->all();

        return $gt;

    }

    public function gtName($accountId)
    {
        $name  = null;
        $gt = AccountAuthority::find()->where(['account_id_customer'=>$accountId])->andWhere(['status'=>1])->one();
        if ($gt)
        {
            $name = $gt->account_id_gt;
        }

        return $name;
    }
    // public function getFormattedCustomerId($customerId)
    // {
    //     $modelCustomer = static::find()->where(['id'=>$customerId])->andWhere(['status'=>1])->one();
    //     $code = null;
    //     if($modelCustomer){
    //         $ward = isset($modelCustomer->fkWard->code)?$modelCustomer->fkWard->code:'';
    //         $lsgi = isset($modelCustomer->fkWard->fkLsgi->code)?$modelCustomer->fkWard->fkLsgi->code:'';
    //         $code = $lsgi.$ward.$modelCustomer->id;
    //     }
    //     return $code;
    // }
    public function getFormattedCustomerId($customerId)
    {
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
    public function getResidentailAssociation($id)
    {
        $ret              = null;
        $modelResidentailAssociation = ResidentialAssociation::find()->where(['id'=>$id])->andWhere(['status'=>1])->one();
        if ($modelResidentailAssociation)
        {
            $ret = $modelResidentailAssociation->name?$modelResidentailAssociation->name:null;
        }

        return $ret;
    }
        public function searchNew($params,$keyword=null,$ward=null,$lsgi=null,$district=null,$door=null,$surveyor=null,$from= null,$to=null,$customerId=null,$code=null,$association=null,$no_association=null,$building_type=null)
    {
        // print_r($buiding_type);die();
       $unit = null;
        $agency = null;
        $supervisor = null;
        $gt   = null;
        $modelUser  = Yii::$app->user->identity;
        $userRole  = $modelUser->role;
        $associations = Yii::$app->rbac->getAssociations($modelUser->id);
        // print_r($associations);die();
        if(isset($associations['lsgi_id']))
        {
            $lsgi = $associations['lsgi_id'];
        }
        if(isset($associations['district_id']))
        {
            $district = $associations['district_id'];
        }
        if(isset($associations['district_id']))
        {
            $district = $associations['district_id'];
        }
        if(isset($associations['ward_id'])&&!$ward)
        {
            $ward = $associations['ward_id'];
            $ward = json_decode($ward);
        }
        if(isset($associations['hks_id']))
        {
            $unit = $associations['hks_id'];
        }
        if(isset($associations['gt_id']))
        {
            $gt = $associations['gt_id'];
        }
        if(isset($associations['survey_agency_id']))
        {
            $agency = $associations['survey_agency_id'];
        }
        if($userRole=='supervisor'&&isset($modelUser->id))
        {
            $supervisor = $modelUser->id;
        }
        if($userRole=='admin-hks')
        {
            $door = 1;
            $building_type = 1;
        }
        $query = CustomerNew::find()
        ->leftjoin('account','account.customer_id=customer.id')
        ->where(['customer.status'=>1])
            ->andWhere(['account.status'=>1])->orderby('id DESC')->groupby('customer.id');
        if($keyword!=null)
        {
            $query->andFilterWhere(['like', 'lead_person_name', $keyword]);
        }
        if($ward!=null)
        {
             $query->andWhere(['customer.ward_id'=>$ward]);
        }
        if($customerId!=null||$gt!=null||$userRole=='supervisor'&&isset($modelUser->id))
        {
            if($customerId!=null){
                $query->andWhere(['account.id'=>$customerId]);
                }
                if($gt!=null||$supervisor!=null)
                {
                    $query->leftJoin('account_authority','account_authority.account_id_customer=account.id')
                    ->andWhere(['account_authority.status'=>1]);
                    if($gt!=null){
                    $query->andWhere(['account_authority.account_id_gt'=>$gt]);
                }
                if($supervisor!=null){
                    $query->andWhere(['account_authority.account_id_supervisor'=>$supervisor]);
                }
                }
                
        }
        if($surveyor!=null)
        {
            $query->andWhere(['creator_account_id'=>$surveyor]);
        }
        if($door!=null)
        {
            $query->andWhere(['door_status'=>$door]);
        }
        if($building_type)
        {
            $query->andWhere(['customer.building_type_id'=>$building_type]);
        }
        if($unit!=null)
        {
            $modelUnit = GreenActionUnit::find()->where(['id'=>$unit])->andWhere(['status'=>1])->one();
            // print_r($modelUnit);die();
            if($modelUnit)
            {
                $category = $modelUnit->residence_category_id;
            }
            else
            {
                $category = null;
            }

            $query->leftjoin('green_action_unit_ward','green_action_unit_ward.ward_id=customer.ward_id')
            ->leftjoin('green_action_unit','green_action_unit.id=green_action_unit_ward.green_action_unit_id')
            ->leftjoin('building_type','building_type.id=customer.building_type_id')
            ->andWhere(['building_type.residence_category_id'=>$category])
            ->andWhere(['green_action_unit_ward.green_action_unit_id'=>$unit])
            ->andWhere(['green_action_unit.id'=>$unit])
            ->groupby('customer.id');
        }
        if($agency)
        {

            $query->leftjoin('survey_agency_ward','survey_agency_ward.ward_id=customer.ward_id')
            ->andWhere(['survey_agency_ward.survey_agency_id'=>$agency]);
        }
        if($district!=null||$lsgi!=null)
        {
            $query->leftjoin('ward','ward.id=customer.ward_id')
            ->leftjoin('lsgi','lsgi.id=ward.lsgi_id');
            if($lsgi!=null)
            {
                $query-> andWhere(['lsgi.id'=>$lsgi]);
            }
            if($district!=null){
            $query->leftjoin('lsgi_block','lsgi_block.id=lsgi.block_id')
            ->leftjoin('assembly_constituency','assembly_constituency.id=lsgi_block.assembly_constituency_id')
            ->leftjoin('district','district.id=assembly_constituency.district_id')
            ->andWhere(['district.id'=>$district]);
        }
        }
        if($code!=null){
        if($code==1)
        {
           $query->leftjoin('qr_code','qr_code.customer_id=customer.id')
           ->andWhere(['qr_code.status'=>1]);
        }
        if($code==0)
        {
            $query->leftjoin('qr_code','qr_code.customer_id=customer.id')
           // ->andWhere(['qr_code.status'=>1]);
           ->andWhere(['is', 'qr_code.customer_id', null]);
        }
    }
        if($from!=null)
        {
            $query->andWhere(['>=', 'customer.created_at', $from]);
        }
        if($to!=null)
        {
            $query->andWhere(['<=', 'customer.created_at', $to]);
        }
        if($association>0)
        {
            $query->andWhere(['customer.residential_association_id'=>$association]);
        }
        if($association==-1)
        {
            $query->andWhere(['residential_association_id'=>null]);
        }

        // add conditions that should always apply here

        // $dataProvider = new ActiveDataProvider([
        //     'query' => $query,
        // ]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            // 'pagination'=>[
            //         'pageSize'=>25,
            // ],

        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        return $dataProvider;
    }
}
