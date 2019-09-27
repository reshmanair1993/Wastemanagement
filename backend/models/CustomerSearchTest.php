<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\SqlDataProvider;
use yii\data\ActiveDataProvider;
use backend\models\Customer;

/**
 * CustomerSearch represents the model behind the search form of `backend\models\Customer`.
 */
class CustomerSearchTest extends Customer
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
    public function search($params,$keyword=null,$ward=null,$lsgi=null,$district=null,$door=null,$surveyor=null,$from= null,$to=null,$customerId=null,$code=null,$association=null,$no_association=null,$building_type=null)
    {
        $unit = null;
        $agency = null;
        $supervisor = null;
        $gt   = null;
        $modelUser  = Yii::$app->user->identity;
        $userRole  = $modelUser->role;
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
        if($userRole=='supervisor'&&isset($modelUser->id))
        {
            $supervisor = $modelUser->id;
        }
        if($userRole=='admin-hks')
        {
            $door = 1;
            $building_type = 1;
        }
        $qry = "SELECT lead_person_name as lead_person_name, customer.id as id FROM customer left join account on account.customer_id=customer.id";
        if($keyword!=null)
        {
            $qry.=" and lead_person_name like :keyword";
        }
        if($ward!=null)
        {
            $qry.=" and customer.ward_id=:ward";
        }
         if($customerId!=null||$gt!=null||$userRole=='supervisor'&&isset($modelUser->id))
        {
            if($customerId!=null){
                $qry.=" and account.id=:customerId";
                }
                if($gt!=null||$supervisor!=null)
                {
                    $qry.=" left join account_authority on account_authority.account_id_customer=account.id and account_authority.status=1 ";
                    if($gt!=null){
                        $qry.=" and account_authority.account_id_gt=:gt";
                }
                if($supervisor!=null){
                     $qry.=" and account_authority.account_id_supervisor=:supervisor";
                }
                }
                
        }
        if($surveyor!=null)
        {
            $qry.=" and customer.creator_account_id=:surveyor";
        }
        if($door!=null)
        {
            $qry.=" and door_status=:door";
        }
        if($building_type)
        {
            $qry.=" and customer.building_type_id=:building_type";
        }
        if($unit!=null)
        {
            $modelUnit = GreenActionUnit::find()->where(['id'=>$unit])->andWhere(['status'=>1])->one();
            if($modelUnit)
            {
                $category = $modelUnit->residence_category_id;
            }
            else
            {
                $category = null;
            }
            $qr.=" left join green_action_unit_ward on green_action_unit_ward.ward_id=customer.ward_id left join green_action_unit on green_action_unit.id=green_action_unit_ward.green_action_unit_id left join building_type on building_type.id=customer.building_type_id and building_type.residence_category_id=:category and green_action_unit_ward.green_action_unit_id=:unit and green_action_unit.id=:unit group by customer.id";
        }
        if($agency)
        {
            $qry.=" left join survey_agency_ward on survey_agency_ward.ward_id=customer.ward_id and survey_agency_ward.survey_agency_id=:agency";
        }
        if($district!=null||$lsgi!=null)
        {
            $qry.=" left join ward on ward.id=customer.ward_id left join lsgi on lsgi.id=ward.lsgi_id";
            if($lsgi!=null)
            {
                $qry.=" and lsgi.id=:lsgi";
            }
            if($district!=null){
                $qry.=" left join lsgi_block on lsgi_block.id=lsgi.block_id left join assembly_constituency on assembly_constituency.id=lsgi_block.assembly_constituency_id left join district on district.id=assembly_constituency.district_id and district.id=:district";
        }
        }
        if($code!=null){
        if($code==1)
        {
            $qry.=" left join qr_code on qr_code.customer_id=customer.id and qr_code.status=:1";
        }
        if($code==0)
        {
            $qry.=" left join qr_code on qr_code.customer_id=customer.id and qr_code.customer_id is null";
        }
    }
        if($from!=null)
        {
            $qry.=" customer.created_at>=:from";
        }
        if($to!=null)
        {
            $qry.=" customer.created_at<=:to";
        }
        if($association>0)
        {
            $qry.=" customer.residential_association_id=:association";
        }
        if($association==-1)
        {
            $qry.=" customer.residential_association_id is null";
        }
        $qry.=" where account.status=1 and customer.status=1 group by customer.id order by customer.id";
        $command =  Yii::$app->db->createCommand($qry);
           if($keyword!=null)
           {
           $command->bindParam(':keyword',$keyword);
           }
           if($ward!=null)
        {
            $command->bindParam(':ward',$ward);
        }
         if($customerId!=null||$gt!=null||$userRole=='supervisor'&&isset($modelUser->id))
        {
            if($customerId!=null){
                $command->bindParam(':customerId',$customerId);
                }
                if($gt!=null||$supervisor!=null)
                {
                    if($gt!=null){
                         $command->bindParam(':gt',$gt);
                }
                if($supervisor!=null){
                     $command->bindParam(':supervisor',$supervisor);
                }
                }
                
        }
        if($surveyor!=null)
        {
             $command->bindParam(':surveyor',$surveyor);
        }
        if($door!=null)
        {
             $command->bindParam(':door',$door);
        }
        if($building_type)
        {
             $command->bindParam(':building_type',$building_type);
        }
        if($unit!=null)
        {
             $command->bindParam(':unit',$unit);
             $command->bindParam(':category',$category);
        }
        if($agency)
        {
             $command->bindParam(':agency',$agency);
        }
        if($district!=null||$lsgi!=null)
        {
            if($lsgi!=null)
            {
                 $command->bindParam(':lsgi',$lsgi);
            }
            if($district!=null){
                $command->bindParam(':district',$district);
        }
        }
        
        if($association>0)
        {
             $command->bindParam(':association',$association);
        }
           if(isset($from)&&$from!=null)
           {
            $command->bindParam(':from',$from);
           }
           if(isset($to)&&$to!=null)
           {
            $command->bindParam(':to',$to);
           }
           $command = $command->getRawSql();
        $dataProvider = new SqlDataProvider([
            'sql' => $command,
        ]); 
        //     $dataProvider = new ActiveDataProvider([
        //     'query' => $command,
        // ]);
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
}
