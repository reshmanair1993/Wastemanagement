<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Customer;

/**
 * CustomerSearch represents the model behind the search form of `backend\models\Customer`.
 */
class CustomerSearch extends Customer
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'ward_id', 'building_type_id', 'door_status', 'trading_type_id', 'shop_type_id', 'has_bio_waste', 'has_non_bio_waste', 'has_disposible_waste', 'fee_collection_interval_id', 'has_bio_waste_management_facility', 'bio_waste_management_facility_operational', 'bio_waste_management_facility_repair_help_needed', 'bio_waste_collection_method_id', 'bio_waste_collection_needed', 'non_bio_waste_collection_method_id', 'has_terrace_farming_interest', 'terrace_farming_help_type_id', 'creator_account_id', 'people_count', 'house_adult_count', 'house_children_count', 'status'], 'integer'],
            [['building_name', 'building_number', 'association_name', 'association_number', 'lead_person_name', 'lead_person_phone', 'address', 'building_owner_name', 'building_owner_phone', 'created_at', 'modified_at'], 'safe'],
            [['lat', 'lng'], 'number'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */ 


    public function search($params,$keyword=null,$ward=null,$lsgi=null,$district=null,$door=null,$surveyor=null,$from= null,$to=null,$customerId=null,$code=null,$association=null,$no_association=null,$building_type=null,$qrcode=null)
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
        $query = Customer::find()
        ->leftjoin('account','account.customer_id=customer.id')
        ->leftjoin('building_type','customer.building_type_id=building_type.id')
        ->where(['customer.status'=>1])
        ->andWhere(['customer.is_public_customer'=>0])
            ->andWhere(['account.status'=>1])->orderby('id DESC')->groupby('customer.id');
             if($qrcode!=null)
        {
             $query->leftjoin('qr_code','qr_code.id=customer.qr_code_id')
             ->andWhere(['qr_code.value'=>$qrcode]);
        }
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
           $query->andWhere(['not', ['customer.qr_code_id' => null]]);
           
        }
        if($code==0)
        {
           $query->andWhere(['is','customer.qr_code_id',null]); 
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
        if($association!=null){
        if($association>0)
        {
            $query->andWhere(['customer.residential_association_id'=>$association]);
        }
        else
        {
            // print_r("expression");die();
            $query->andWhere(['customer.residential_association_id'=>null]);
        }
    }

        // add conditions that should always apply here

        // $dataProvider = new ActiveDataProvider([
        //     'query' => $query,
        // ]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination'=>[
                    'pageSize'=>25,
            ],

        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'ward_id' => $this->ward_id,
            'building_type_id' => $this->building_type_id,
            'door_status' => $this->door_status,
            'trading_type_id' => $this->trading_type_id,
            'shop_type_id' => $this->shop_type_id,
            'has_bio_waste' => $this->has_bio_waste,
            'has_non_bio_waste' => $this->has_non_bio_waste,
            'has_disposible_waste' => $this->has_disposible_waste,
            'lat' => $this->lat,
            'lng' => $this->lng,
            'fee_collection_interval_id' => $this->fee_collection_interval_id,
            'has_bio_waste_management_facility' => $this->has_bio_waste_management_facility,
            'bio_waste_management_facility_operational' => $this->bio_waste_management_facility_operational,
            'bio_waste_management_facility_repair_help_needed' => $this->bio_waste_management_facility_repair_help_needed,
            'bio_waste_collection_method_id' => $this->bio_waste_collection_method_id,
            'bio_waste_collection_needed' => $this->bio_waste_collection_needed,
            'non_bio_waste_collection_method_id' => $this->non_bio_waste_collection_method_id,
            'has_terrace_farming_interest' => $this->has_terrace_farming_interest,
            'terrace_farming_help_type_id' => $this->terrace_farming_help_type_id,
            'creator_account_id' => $this->creator_account_id,
            'people_count' => $this->people_count,
            'house_adult_count' => $this->house_adult_count,
            'house_children_count' => $this->house_children_count,
            'created_at' => $this->created_at,
            'modified_at' => $this->modified_at,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'building_name', $this->building_name])
            ->andFilterWhere(['like', 'building_number', $this->building_number])
            ->andFilterWhere(['like', 'association_name', $this->association_name])
            ->andFilterWhere(['like', 'association_number', $this->association_number])
            ->andFilterWhere(['like', 'lead_person_name', $this->lead_person_name])
            ->andFilterWhere(['like', 'lead_person_phone', $this->lead_person_phone])
            ->andFilterWhere(['like', 'address', $this->address])
            ->andFilterWhere(['like', 'building_owner_name', $this->building_owner_name])
            ->andFilterWhere(['like', 'building_owner_phone', $this->building_owner_phone]);

        $query->select(['customer.id as id','customer.lead_person_name','customer.lead_person_phone',
        'customer.ward_id',
        'customer.door_status','customer.created_at', 
        'customer.creator_account_id', 
        'customer.building_number','customer.shop_name',
        'customer.address','customer.residential_association_id',
        'customer.association_name','customer.association_number','customer.lat','customer.lng',
        'building_type.name as building_type_name']);

        return $dataProvider;
    }
     public function getAllQuery($params,$keyword=null,$ward=null,$lsgi=null,$district=null,$from= null,$to=null)
    {
    //      $modelUser  = Yii::$app->user->identity;
    // $userRole = $modelUser->role;
    //  $unit = null;
    //     $agency = null;
    // if($userRole=='admin-lsgi'&&isset($modelUser->lsgi_id))
    //     {
    //         $lsgi = $modelUser->lsgi_id;
    //     }
    // elseif($userRole=='admin-hks'&&isset($modelUser->green_action_unit_id))
    // {
    //   $unit = $modelUser->green_action_unit_id;
    // }
    // elseif($userRole=='coordinator')
    // {
    //      $agency = $modelUser->survey_agency_id;
    //   // $unit = $modelUser->green_action_unit_id;
    // }
        $unit = null;
        $agency = null;
        $gt   = null;
        $modelUser  = Yii::$app->user->identity;
        $associations = Yii::$app->rbac->getAssociations($modelUser->id);
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
    $query = new \yii\db\Query;
    $query = Customer::find()->where(['customer.status'=>1])
    ->andWhere(['door_status'=>0])
    ->orderby('id DESC');
    if($keyword!=null)
        {
            $query->andFilterWhere(['like', 'lead_person_name', $keyword]);
        }
        if($ward!=null)
        {
            $query->andWhere(['customer.ward_id'=>$ward]);
        }
        if($lsgi!=null&&$district==null)
        {
            $query->leftjoin('ward','ward.id=customer.ward_id')
            ->leftjoin('lsgi','lsgi.id=ward.lsgi_id')
            ->andWhere(['lsgi.id'=>$lsgi]);
        }
         if($district!=null&&$lsgi!=null)
        {
            $query->leftjoin('ward','ward.id=customer.ward_id')
            ->leftjoin('lsgi','lsgi.id=ward.lsgi_id')
            ->leftjoin('lsgi_block','lsgi_block.id=lsgi.block_id')
            ->leftjoin('assembly_constituency','assembly_constituency.id=lsgi_block.assembly_constituency_id')
            ->leftjoin('district','district.id=assembly_constituency.district_id')
            ->andWhere(['district.id'=>$district]);
        }
        if($district!=null&&$lsgi==null)
        {
            $query->leftjoin('ward','ward.id=customer.ward_id')
            ->leftjoin('lsgi','lsgi.id=ward.lsgi_id')
            ->leftjoin('lsgi_block','lsgi_block.id=lsgi.block_id')
            ->leftjoin('assembly_constituency','assembly_constituency.id=lsgi_block.assembly_constituency_id')
            ->leftjoin('district','district.id=assembly_constituency.district_id')
            ->andWhere(['district.id'=>$district]);
        }
         if($unit!=null)
        {

            $query->leftjoin('green_action_unit_ward','green_action_unit_ward.ward_id=customer.ward_id')
            ->andWhere(['green_action_unit_ward.green_action_unit_id'=>$unit]);
        }
         if($agency)
        {

            $query->leftjoin('survey_agency_ward','survey_agency_ward.ward_id=customer.ward_id')
            ->andWhere(['survey_agency_ward.survey_agency_id'=>$agency]);
        }

        if($from!=null)
        {
            $query->andWhere(['>=', 'customer.created_at', $from]);
        }
        if($to!=null)
        {
            $query->andWhere(['<=', 'customer.created_at', $to]);
        }
    $page = isset($_GET['page'])?$_GET['page']:1;
    $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
            'pageSize' => 50,
              'page' => $page-1,
                'params' => [
                'name' => $keyword,
                'ward' => $ward,
                'lsgi' => $lsgi,
                'district' => $district,
                'from'=>$from,
                'to'=>$to
              ]
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }
        return $dataProvider;

    }
     public function getAllQueryType($params,$keyword=null,$ward=null,$lsgi=null,$district=null,$door=null,$bioWaste= null,$nonBioWaste=null,$from= null,$to=null)
    {
    $modelUser = Yii::$app->user->identity;
    $userRole  = $modelUser->role;
    $unit = null;
        $agency = null;
        $gt   = null;
        $associations = Yii::$app->rbac->getAssociations($modelUser->id);
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
    $query = new \yii\db\Query;
    $query = Customer::find()->where(['customer.status'=>1])
    ->orderby('id DESC');
    if ($unit)
        {
            $query->leftJoin('green_action_unit_ward', 'customer.ward_id=green_action_unit_ward.ward_id')
            ->leftJoin('green_action_unit', 'green_action_unit.id=green_action_unit_ward.green_action_unit_id')
            ->leftJoin('account','account.green_action_unit_id=green_action_unit.id')
                          ->andWhere(['green_action_unit.id' => $unit])
            ->groupby('customer.id');
        }
    if($keyword!=null)
        {
            $query->andFilterWhere(['like', 'lead_person_name', $keyword]);
        }
        if($bioWaste!=null)
        {
            $query->andWhere(['bio_waste_collection_method_id'=>$bioWaste]);
        }
        if($nonBioWaste!=null)
        {
            $query->andWhere(['non_bio_waste_collection_method_id'=>$nonBioWaste]);
        }
        if($ward!=null)
        {
            $query->andWhere(['customer.ward_id'=>$ward]);
        }
        if($door!=null)
        {
            $query->andWhere(['door_status'=>$door]);
        }
        // if($lsgi!=null)
        // {
        //     $query->leftjoin('ward','ward.id=customer.ward_id')
        //     ->leftjoin('lsgi','lsgi.id=ward.lsgi_id')
        //     ->andWhere(['lsgi.id'=>$lsgi]);
        // }
        //  if($district!=null)
        // {
        //     $query->leftjoin('ward','ward.id=customer.ward_id')
        //     ->leftjoin('lsgi','lsgi.id=ward.lsgi_id')
        //     ->leftjoin('block','block.id=lsgi.block_id')
        //     ->leftjoin('assembly_constituency','assembly_constituency.id=block.assembly_constituency_id')
        //     ->leftjoin('district','district.id=assembly_constituency.district_id')
        //     ->andWhere(['district.id'=>$district]);
        // }
         if($lsgi!=null&&$district==null)
        {
            $query->leftjoin('ward','ward.id=customer.ward_id')
            ->leftjoin('lsgi','lsgi.id=ward.lsgi_id')
            ->andWhere(['lsgi.id'=>$lsgi]);
        }
         if($district!=null&&$lsgi!=null)
        {
            $query->leftjoin('ward','ward.id=customer.ward_id')
            ->leftjoin('lsgi','lsgi.id=ward.lsgi_id')
            ->leftjoin('lsgi_block','lsgi_block.id=lsgi.block_id')
            ->leftjoin('assembly_constituency','assembly_constituency.id=lsgi_block.assembly_constituency_id')
            ->leftjoin('district','district.id=assembly_constituency.district_id')
            ->andWhere(['district.id'=>$district]);
        }
        if($district!=null&&$lsgi==null)
        {
            $query->leftjoin('ward','ward.id=customer.ward_id')
            ->leftjoin('lsgi','lsgi.id=ward.lsgi_id')
            ->leftjoin('lsgi_block','lsgi_block.id=lsgi.block_id')
            ->leftjoin('assembly_constituency','assembly_constituency.id=lsgi_block.assembly_constituency_id')
            ->leftjoin('district','district.id=assembly_constituency.district_id')
            ->andWhere(['district.id'=>$district]);
        }
         if($from!=null)
        {
            $query->andWhere(['>=', 'customer.created_at', $from]);
        }
        if($to!=null)
        {
            $query->andWhere(['<=', 'customer.created_at', $to]);
        }

     $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }
        return $dataProvider;

    }
    public function searchPublicCustomers($params,$keyword=null,$ward=null,$lsgi=null,$district=null,$door=null,$surveyor=null,$from= null,$to=null,$customerId=null,$code=null,$association=null,$no_association=null,$building_type=null)
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
        $query = Customer::find()
        ->leftjoin('account','account.customer_id=customer.id')
        ->where(['customer.status'=>1])
        ->andWhere(['customer.is_public_customer'=>1])
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
            'pagination'=>[
                    'pageSize'=>25,
            ],

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
