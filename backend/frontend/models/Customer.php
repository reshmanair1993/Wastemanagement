<?php
namespace frontend\models;

use Yii;
use yii\db\Expression;
use yii\behaviors\TimestampBehavior;
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
class Customer extends \yii\db\ActiveRecord
{
    /**
     * @var mixed
     */
    public $district_id, $block_id, $assembly_constituency_id, $lsgi_id,$code,$no_association;
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
            [['ward_id', 'building_type_id', 'door_status', 'trading_type_id', 'shop_type_id', 'has_bio_waste', 'has_non_bio_waste', 'has_disposible_waste', 'fee_collection_interval_id', 'has_bio_waste_management_facility', 'bio_waste_management_facility_operational', 'bio_waste_management_facility_repair_help_needed', 'bio_waste_collection_method_id', 'bio_waste_collection_needed', 'non_bio_waste_collection_method_id', 'has_terrace_farming_interest', 'terrace_farming_help_type_id', 'creator_account_id', 'people_count', 'house_adult_count', 'house_children_count', 'status','image_id','has_public_toilet','public_toilet_count','public_toilet_count_men','public_toilet_count_women'], 'integer'],
            [['address'], 'string'],
            // [['lat', 'lng'], 'number'],
            [['created_at', 'modified_at','image_id','building_sub_type','lat', 'lng'], 'safe'],
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
        $count  = 0;
        $customers =Customer::find()->where(['creator_account_id'=>$id])->andWhere(['building_type_id'=>1])->andWhere(['status'=>1]);
        // $customers =Customer::find()->where(['creator_account_id'=>$id])->andWhere(['building_type_id'=>1])->andWhere(['status'=>1])->all();
        if($from!=null)
        {
            $customers->andWhere(['>=', 'customer.created_at', $from]);
        }
        if($to!=null)
        {
            $customers->andWhere(['<=', 'customer.created_at', $to]);
        }
        $customers=$customers->all();
        if ($customers)
        {
            $count = count($customers);
        }

        return $count;
    }
    public function getFlatCount($id = null,$from=null,$to=null)
    {
        $count  = 0;
        $customers =Customer::find()->where(['creator_account_id'=>$id])->andWhere(['building_type_id'=>3])->andWhere(['status'=>1]);
        
        if($from!=null)
        {
            $customers->andWhere(['>=', 'customer.created_at', $from]);
        }
        if($to!=null)
        {
            $customers->andWhere(['<=', 'customer.created_at', $to]);
        }
        $customers=$customers->all();
        if ($customers)
        {
            $count = count($customers);
        }

        return $count;
    }
    public function getShopCount($id = null,$from=null,$to=null)
    {
        $count  = 0;
        $customers =Customer::find()->where(['creator_account_id'=>$id])->andWhere(['building_type_id'=>2])->andWhere(['status'=>1]);
        if($from!=null)
        {
            $customers->andWhere(['>=', 'customer.created_at', $from]);
        }
        if($to!=null)
        {
            $customers->andWhere(['<=', 'customer.created_at', $to]);
        }
        $customers=$customers->all();
        if ($customers)
        {
            $count = count($customers);
        }

        return $count;
    }
     public function getHospitalCount($id = null,$from=null,$to=null)
    {
        $count  = 0;
        $customers =Customer::find()->where(['creator_account_id'=>$id])->andWhere(['building_type_id'=>5])->andWhere(['status'=>1]);
        if($from!=null)
        {
            $customers->andWhere(['>=', 'customer.created_at', $from]);
        }
        if($to!=null)
        {
            $customers->andWhere(['<=', 'customer.created_at', $to]);
        }
        $customers=$customers->all();
        if ($customers)
        {
            $count = count($customers);
        }

        return $count;
    }
    public function getPublicPlaceCount($id = null,$from=null,$to=null)
    {
        $count  = 0;
        $customers =Customer::find()->where(['creator_account_id'=>$id])->andWhere(['building_type_id'=>6])->andWhere(['status'=>1]);
        if($from!=null)
        {
            $customers->andWhere(['>=', 'customer.created_at', $from]);
        }
        if($to!=null)
        {
            $customers->andWhere(['<=', 'customer.created_at', $to]);
        }
        $customers=$customers->all();
        if ($customers)
        {
            $count = count($customers);
        }

        return $count;
    }
     public function getReligiousCount($id = null,$from=null,$to=null)
    {
        $count  = 0;
        $customers =Customer::find()->where(['creator_account_id'=>$id])->andWhere(['building_type_id'=>11])->andWhere(['status'=>1]);
        if($from!=null)
        {
            $customers->andWhere(['>=', 'customer.created_at', $from]);
        }
        if($to!=null)
        {
            $customers->andWhere(['<=', 'customer.created_at', $to]);
        }
        $customers=$customers->all();
        if ($customers)
        {
            $count = count($customers);
        }

        return $count;
    }
    public function getOfficeCount($id = null,$from=null,$to=null)
    {
        $count  = 0;
        $customers =Customer::find()->where(['creator_account_id'=>$id])->andWhere(['building_type_id'=>7])->andWhere(['status'=>1]);
        if($from!=null)
        {
            $customers->andWhere(['>=', 'customer.created_at', $from]);
        }
        if($to!=null)
        {
            $customers->andWhere(['<=', 'customer.created_at', $to]);
        }
        $customers=$customers->all();
        if ($customers)
        {
            $count = count($customers);
        }

        return $count;
    }
    public function getAuditoriumCount($id = null,$from=null,$to=null)
    {
        $count  = 0;
        $customers =Customer::find()->where(['creator_account_id'=>$id])->andWhere(['building_type_id'=>9])->andWhere(['status'=>1]);
        if($from!=null)
        {
            $customers->andWhere(['>=', 'customer.created_at', $from]);
        }
        if($to!=null)
        {
            $customers->andWhere(['<=', 'customer.created_at', $to]);
        }
        $customers=$customers->all();
        if ($customers)
        {
            $count = count($customers);
        }

        return $count;
    }
     public function getMarketCount($id = null,$from=null,$to=null)
    {
        $count  = 0;
        $customers =Customer::find()->where(['creator_account_id'=>$id])->andWhere(['building_type_id'=>10])->andWhere(['status'=>1]);
        if($from!=null)
        {
            $customers->andWhere(['>=', 'customer.created_at', $from]);
        }
        if($to!=null)
        {
            $customers->andWhere(['<=', 'customer.created_at', $to]);
        }
        $customers=$customers->all();
        if ($customers)
        {
            $count = count($customers);
        }

        return $count;
    }
    public function getFkWasteCollectionInterval()
    {
        return $this->hasOne(WasteCollectionInterval::className(), ['id' => 'waste_collection_interval_id']);
    }
     public function getWardHouseCount($id = null,$from=null,$to=null)
    {
        $count  = 0;
        $customers =Customer::find()->where(['ward_id'=>$id])->andWhere(['building_type_id'=>1])->andWhere(['status'=>1]);
        // $customers =Customer::find()->where(['creator_account_id'=>$id])->andWhere(['building_type_id'=>1])->andWhere(['status'=>1])->all();
        if($from!=null)
        {
            $customers->andWhere(['>=', 'customer.created_at', $from]);
        }
        if($to!=null)
        {
            $customers->andWhere(['<=', 'customer.created_at', $to]);
        }
        $customers=$customers->all();
        if ($customers)
        {
            $count = count($customers);
        }

        return $count;
    }
    public function getWardFlatCount($id = null,$from=null,$to=null)
    {
        $count  = 0;
        $customers =Customer::find()->where(['ward_id'=>$id])->andWhere(['building_type_id'=>3])->andWhere(['status'=>1]);
         if($from!=null)
        {
            $customers->andWhere(['>=', 'customer.created_at', $from]);
        }
        if($to!=null)
        {
            $customers->andWhere(['<=', 'customer.created_at', $to]);
        }
        $customers=$customers->all();
        if ($customers)
        {
            $count = count($customers);
        }

        return $count;
    }
    public function getWardShopCount($id = null,$from=null,$to=null)
    {
        $count  = 0;
        $customers =Customer::find()->where(['ward_id'=>$id])->andWhere(['building_type_id'=>2])->andWhere(['status'=>1]);
         if($from!=null)
        {
            $customers->andWhere(['>=', 'customer.created_at', $from]);
        }
        if($to!=null)
        {
            $customers->andWhere(['<=', 'customer.created_at', $to]);
        }
        $customers=$customers->all();
        if ($customers)
        {
            $count = count($customers);
        }

        return $count;
    }
     public function getWardHospitalCount($id = null,$from=null,$to=null)
    {
        $count  = 0;
        $customers =Customer::find()->where(['ward_id'=>$id])->andWhere(['building_type_id'=>5])->andWhere(['status'=>1]);
         if($from!=null)
        {
            $customers->andWhere(['>=', 'customer.created_at', $from]);
        }
        if($to!=null)
        {
            $customers->andWhere(['<=', 'customer.created_at', $to]);
        }
        $customers=$customers->all();
        if ($customers)
        {
            $count = count($customers);
        }

        return $count;
    }
    public function getWardPublicPlaceCount($id = null,$from=null,$to=null)
    {
        $count  = 0;
        $customers =Customer::find()->where(['ward_id'=>$id])->andWhere(['building_type_id'=>6])->andWhere(['status'=>1]);
         if($from!=null)
        {
            $customers->andWhere(['>=', 'customer.created_at', $from]);
        }
        if($to!=null)
        {
            $customers->andWhere(['<=', 'customer.created_at', $to]);
        }
        $customers=$customers->all();
        if ($customers)
        {
            $count = count($customers);
        }

        return $count;
    }

     public function getWardReligiousCount($id = null,$from=null,$to=null)
    {
        $count  = 0;
        $customers =Customer::find()->where(['ward_id'=>$id])->andWhere(['building_type_id'=>11])->andWhere(['status'=>1]);
         if($from!=null)
        {
            $customers->andWhere(['>=', 'customer.created_at', $from]);
        }
        if($to!=null)
        {
            $customers->andWhere(['<=', 'customer.created_at', $to]);
        }
        $customers=$customers->all();
        if ($customers)
        {
            $count = count($customers);
        }

        return $count;
    }

    public function getWardOfficeCount($id = null,$from=null,$to=null)
    {
        $count  = 0;
        $customers =Customer::find()->where(['ward_id'=>$id])->andWhere(['building_type_id'=>7])->andWhere(['status'=>1]);
         if($from!=null)
        {
            $customers->andWhere(['>=', 'customer.created_at', $from]);
        }
        if($to!=null)
        {
            $customers->andWhere(['<=', 'customer.created_at', $to]);
        }
        $customers=$customers->all();
        if ($customers)
        {
            $count = count($customers);
        }

        return $count;
    }
    public function getWardAuditoriumCount($id = null,$from=null,$to=null)
    {
        $count  = 0;
        $customers =Customer::find()->where(['ward_id'=>$id])->andWhere(['building_type_id'=>9])->andWhere(['status'=>1]);
         if($from!=null)
        {
            $customers->andWhere(['>=', 'customer.created_at', $from]);
        }
        if($to!=null)
        {
            $customers->andWhere(['<=', 'customer.created_at', $to]);
        }
        $customers=$customers->all();
        if ($customers)
        {
            $count = count($customers);
        }

        return $count;
    }
     public function getWardMarketCount($id = null,$from=null,$to=null)
    {
        $count  = 0;
        $customers =Customer::find()->where(['ward_id'=>$id])->andWhere(['building_type_id'=>10])->andWhere(['status'=>1]);
         if($from!=null)
        {
            $customers->andWhere(['>=', 'customer.created_at', $from]);
        }
        if($to!=null)
        {
            $customers->andWhere(['<=', 'customer.created_at', $to]);
        }
        $customers=$customers->all();
        if ($customers)
        {
            $count = count($customers);
        }

        return $count;
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
            ->andWhere(['<','package_id',0])->andWhere(['account_id'=>$this->fkCustomerAccount->id])->all();
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
       return $service;
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
}
