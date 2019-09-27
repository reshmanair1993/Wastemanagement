<?php

namespace backend\models;

use Yii;
use yii\db\Expression;
use yii\behaviors\TimestampBehavior;
/**
 * This is the model class for table "service_request".
 *
 * @property int $id
 * @property int $service_id
 * @property int $acoount_id_customer
 * @property int $account_id_gt
 * @property int $account_id_completed_by
 * @property string $requested_date
 * @property string $servicing_date
 * @property string $remark
 * @property int $status
 * @property string $created_at
 * @property string $modified_at
 */
class ServiceRequestTest extends \yii\db\ActiveRecord
{
    public $district_id,$block_id,$assembly_constituency_id,$request_status;
    public $name;
    public $supervisor;
    public $planned_date,$new_complaint;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'service_request_test';
    }

    /**
     * {@inheritdoc}
     */
     public function scenarios() {
       return [
         self::SCENARIO_DEFAULT => [
           'account_id_customer','service_id', 'account_id_customer', 'status','requested_datetime',
           'servicing_datetime', 'created_at', 'modified_at','remarks','planned_date'
         ],
         'create' => [
           'account_id_customer','service_id','planned_date',
         ],
       ];
     }
    public function rules()
    {
        return [
            [[ 'account_id_customer'], 'required'],
            [['service_id', 'account_id_customer', 'account_id_gt', 'account_id_completed_by', 'status'], 'integer'],
            [['requested_date', 'servicing_datetime', 'created_at', 'modified_at','name','supervisor','ward_id','lsgi_id','performance_point'], 'safe'],
            [['remark'], 'string'],
            [['account_id_customer','service_id','planned_date'],'required','on'=>'create'],
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
            'id' => Yii::t('app', 'ID'),
            'service_id' => Yii::t('app', 'Service'),
            'account_id_customer' => Yii::t('app', 'Customer'),
            'account_id_gt' => Yii::t('app', 'Green Technician'),
            'account_id_completed_by' => Yii::t('app', 'Completed By'),
            'requested_date' => Yii::t('app', 'Requested Date'),
            'servicing_date' => Yii::t('app', 'Servicing Date'),
            'remark' => Yii::t('app', 'Remark'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'modified_at' => Yii::t('app', 'Modified At'),
        ];
    }
    public function getFkService()
        {
                return $this->hasOne(Service::className(), ['id' => 'service_id']);
        }
    public function getFkAccount()
        {
                return $this->hasOne(Account::className(), ['id' => 'account_id_customer']);
        }
        public function getFkGreenTechnician()
        {
                return $this->hasOne(Account::className(), ['id' => 'account_id_gt']);
        }
        public function getCustomer($id)
    {
        // print_r($id);die();
        $name  = null;
        $account = Account::find()->where(['id'=>$id])->one();
        if($account)
        {
        $customer =  Customer::find()->where(['id'=> $account->customer_id])->one();
        if($customer){
            $name = $customer->lead_person_name;
            return $name;
        }
        }

      return $name;
    }
     public function deleteRequest($id)
    {
       $connection = Yii::$app->db;
       $connection->createCommand()->update('service_request_test', ['status' => 0], 'id=:id')->bindParam(':id',$id)->execute();
       return true;
    }
     public function getLsgis($customer)
    {
        $name =null;
        $account = Account::find()->where(['id'=>$customer])->one();
        if($account)
        {
            $customerData = Customer::find()->where(['id'=>$account->customer_id])->one();
            if($customerData)
            {
               $wards =  Ward::find()->where(['status'=> 1])->andWhere(['id'=>$customerData->ward_id])->one();
               if($wards)
               {
        $lsgi =  Lsgi::find()->where(['id'=> $wards->lsgi_id])->one();
        if($lsgi){
            $name = $lsgi->name;
        }
    }
    }
}

      return $name;
    }
     public function getUnit($id)
    {
        $name  = null;
        $unit =  GreenActionUnit::find()->where(['id'=> $id])->one();
        if($unit){
            $name = $unit->name;
        }
      return $name;
    }
    public function getSuperVisor($id)
    {
        $name  = null;
        $account =  Account::find()->where(['id'=> $id])->one();
        if($account){
            $person = Person::find()->where(['id'=>$account->person_id])->one();
            if($person)
            $name = $person->first_name;
        }
      return $name;
    }
     public function getBlock($customer)
    {
        $name =null;
        $account = Account::find()->where(['id'=>$customer])->one();
        if($account)
        {
            $customerData = Customer::find()->where(['id'=>$account->customer_id])->one();
            if($customerData)
            {
               $wards =  Ward::find()->where(['status'=> 1])->andWhere(['id'=>$customerData->ward_id])->one();
               if($wards)
               {
        $lsgi =  Lsgi::find()->where(['id'=> $wards->lsgi_id])->one();
        if($lsgi){
        $block =  LsgiBlock::find()->where(['id'=> $lsgi->block_id])->one();
        if($block){
            $name = $block->name;
        }
         }
     }
 }
}
      return $name;
    }
    public function getConstituency($customer)
    {
        $name = null;
         $account = Account::find()->where(['id'=>$customer])->one();
        if($account)
        {
            $customerData = Customer::find()->where(['id'=>$account->customer_id])->one();
            if($customerData)
            {
               $wards =  Ward::find()->where(['status'=> 1])->andWhere(['id'=>$customerData->ward_id])->one();
               if($wards)
               {
        $lsgi =  Lsgi::find()->where(['id'=> $wards->lsgi_id])->one();
        if($lsgi){
        $block =  LsgiBlock::find()->where(['id'=> $lsgi->block_id])->one();
        if($block){
            $assembly_constituency = AssemblyConstituency::find()->where(['id'=> $block->assembly_constituency_id])->one();
            if($assembly_constituency)
            {
                $name = $assembly_constituency->name;
            }
        }
    }
    }
}


    }

        return $name;
    }
     public function getDistrict($customer)
    {
        $name = null;
         $account = Account::find()->where(['id'=>$customer])->one();
        if($account)
        {
            $customerData = Customer::find()->where(['id'=>$account->customer_id])->one();
            if($customerData)
            {
               $wards =  Ward::find()->where(['status'=> 1])->andWhere(['id'=>$customerData->ward_id])->one();
               if($wards)
               {
        $lsgi =  Lsgi::find()->where(['id'=> $wards->lsgi_id])->one();
        if($lsgi){
        $block =  LsgiBlock::find()->where(['id'=> $lsgi->block_id])->one();
        if($block){
            $assembly_constituency = AssemblyConstituency::find()->where(['id'=> $block->assembly_constituency_id])->one();
            if($assembly_constituency)
            {
                $district = District::find()->where(['id'=> $assembly_constituency->district_id])->one();
                $name = $district->id;
            }

        }
    }}
}
    }
        return $name;
    }
     public function getDistricts()
    {
        $district =  District::find()->where(['status'=> 1])->all();
        return $district;
    }
     public function getWard($customer=null)
    {
        $name =null;
        $account = Account::find()->where(['id'=>$customer])->one();
        if($account)
        {
            $customerData = Customer::find()->where(['id'=>$account->customer_id])->one();
            if($customerData)
            {
               $wards =  Ward::find()->where(['status'=> 1])->andWhere(['id'=>$customerData->ward_id])->one();
               if($wards)
               {
                    $name = $wards->name;
               }
            }
        }

        return $name;
    }
    public function getCustomerData($customer)
    {
        $name  = null;
        $account = Account::find()->where(['id'=>$customer])->one();
        if($account)
        {
            $customerData = Customer::find()->where(['id'=>$account->customer_id])->one();
            if($customerData)
            {
            $name = $customerData->lead_person_name;
            }
        }
      return $name;
    }
     public function getStatus()
    {
        $status  = 'Not assigned';
        $modelServiceAssignment = $this->fkServiceAssignment;
        if($modelServiceAssignment)
        {
        if($modelServiceAssignment->servicing_status_option_id==null){
            $status = 'Pending' ;
        }
        else
        {
          if($modelServiceAssignment->fkServiceStatus)
                $status = $modelServiceAssignment->fkServiceStatus->value;
        }
        }

      return $status;
    }
    public function getQualityAssigned()
    {
        $quality  = null;
        $modelServiceAssignment = $this->fkServiceAssignment;
        if($modelServiceAssignment)
        {
        if(isset($modelServiceAssignment->quality)){
            if(isset($modelServiceAssignment->fkQuality->name))
          $quality = $modelServiceAssignment->fkQuality->name;
        }
        }

      return $quality;
    }
     public function getFkServiceAssignment()
        {
                return $this->hasOne(ServiceAssignmentTest::className(), ['service_request_id' => 'id'])->andWhere(['service_assignment_test.status'=>1]);
        }
    public function getGt($customerId)
    {
        $modelUser  = Yii::$app->user->identity;
        if($modelUser->role=='supervisor'){
            $supervisor = $modelUser->id;
            $gt =  Person::find()
                ->select('account.id as id,person.first_name as first_name')
                ->where(['account.status' => 1])
                ->leftjoin('account', 'account.person_id=person.id')
                // ->leftjoin('green_action_unit','green_action_unit.id=account.green_action_unit_id')
                // ->leftjoin('green_action_unit_ward','green_action_unit.id=green_action_unit_ward.green_action_unit_id')
                ->andWhere(['account.supervisor_id'=>$supervisor])
                ->andWhere(['account.role' => 'green-technician'])
                ->all();
            }
            else
            {      
        $accountData = Account::find()->where(['id'=>$customerId])->one();
        $customerData = Customer::find()->where(['id'=>$accountData->customer_id])->one();
        $gt =  Person::find()
                ->select('account.id as id,person.first_name as first_name')
                ->where(['account.status' => 1])
                ->leftjoin('account', 'account.person_id=person.id')
                // ->leftjoin('green_action_unit','green_action_unit.id=account.green_action_unit_id')
                // ->leftjoin('green_action_unit_ward','green_action_unit.id=green_action_unit_ward.green_action_unit_id')
                // ->leftjoin('account_ward','account_ward.account_id=account.id')
                // ->andWhere(['account_ward.ward_id'=>$customerData->ward_id])
                ->leftjoin('account_authority','account_authority.account_id_gt=account.id')
                ->andWhere(['account_authority.account_id_customer' => $accountData->id])
                ->andWhere(['account.role' => 'green-technician'])
                ->all();

            }

        return $gt;

    }
    public function getStatusOption($serviceId)
    {
        $status =  ServicingStatusOption::find()
                ->where(['servicing_status_option.status' => 1])
                ->andWhere(['servicing_status_option.service_id' => $serviceId])
                ->all();
                // print_r($gt);die();
        return $status;

    }
     public function getQuality()
    {
        $quality =  WasteQuality::find()
                ->where(['waste_quality.status' => 1])
                ->all();
                // print_r($gt);die();
        return $quality;

    }
    public function getComplaintsList()
    {
        $complaints =  Service::find()->where(['status'=> 1])->andWhere(['type'=>2])->all();
        return $complaints;
    }
    public function getServiceList($userId)
    {
         $service = Service::find()
        ->leftjoin('account_service','account_service.service_id=service.id')
        ->where(['account_service.account_id'=>$userId])
        ->andWhere(['account_service.status'=>1])
        ->andWhere(['service.status'=>1])
        ->all();
        return $service;
    }
     public static function getAllQuerySummary($keyword=null, $ward=null, $lsgi=null, $supervisor=null, $gt=null, $association=null, $from=null, $to=null) {
     $query = Ward::find()->where(['ward.status'=>1]);
     $lsgi = null;
     $unit = null;
        $agency = null;
    $modelUser  = Yii::$app->user->identity;
    $userRole = $modelUser->role;
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
    if($unit!=null)
        {

            $query->leftjoin('green_action_unit_ward','green_action_unit_ward.ward_id=ward.id')
            ->andWhere(['green_action_unit_ward.green_action_unit_id'=>$unit]);
        }
         if($agency)
        {

            $query->leftjoin('survey_agency_ward','survey_agency_ward.ward_id=ward.id')
            ->andWhere(['survey_agency_ward.survey_agency_id'=>$agency]);
        }
     if($ward)
     {
        $query->andWhere(['ward.id'=>$ward]);
     }
     if($lsgi)
     {
        $query->andWhere(['ward.lsgi_id'=>$lsgi]);
     }
     return $query;
   }
    public function getCount($id = null,$from=null,$to=null)
    {
        $count  = 0;
        $customers = ServiceRequestTest::find()
        ->leftjoin('account','service_request_test.account_id_customer=account.id')
        ->leftjoin('customer','customer.id=account.customer_id')
        ->leftjoin('service','service.id=service_request_test.service_id')
        ->where(['customer.ward_id'=>$id])
        ->andWhere(['customer.status'=>1])
        ->andWhere(['service.type'=>2])
        ->andWhere(['account.status'=>1])
        ->andWhere(['service_request_test.status'=>1]);
        if($from!=null)
        {
            $customers->andWhere(['>=', 'service_request_test.requested_datetime', $from]);
        }
        if($to!=null)
        {
            $customers->andWhere(['<=', 'service_request_test.requested_datetime', $to]);
        }
        $customers=$customers->all();
        if ($customers)
        {
            $count = count($customers);
        }

        return $count;
    }
    public function getCountCompleted($id = null,$from=null,$to=null,$type=null)
    {
        $count  = 0;
        $customers = ServiceRequest::find()
        ->leftjoin('account','service_request.account_id_customer=account.id')
        ->leftjoin('customer','customer.id=account.customer_id')
        ->leftjoin('service_assignment','service_assignment.service_request_id=service_request.id')
        ->leftjoin('service','service.id=service_request.service_id')
        ->where(['customer.ward_id'=>$id])
        ->andWhere(['>','service_assignment.servicing_status_option_id',0])
        ->andWhere(['customer.status'=>1])
        ->andWhere(['service.type'=>$type])
        ->andWhere(['account.status'=>1])
        ->andWhere(['service_request.status'=>1]);
        if($from!=null)
        {
            $customers->andWhere(['>=', 'service_request.requested_datetime', $from]);
        }
        if($to!=null)
        {
            $customers->andWhere(['<=', 'service_request.requested_datetime', $to]);
        }
        $customers=$customers->all();
        if ($customers)
        {
            $count = count($customers);
        }

        return $count;
    }
    public function getCountPending($id = null,$from=null,$to=null,$type=null)
    {
        $count  = 0;
        $customers = ServiceRequest::find()
        ->leftjoin('account','service_request.account_id_customer=account.id')
        ->leftjoin('customer','customer.id=account.customer_id')
        ->leftjoin('service_assignment','service_assignment.service_request_id=service_request.id')
        ->leftjoin('service','service.id=service_request.service_id')
        ->where(['customer.ward_id'=>$id])
        ->andWhere(['service_assignment.servicing_status_option_id'=>null])
        ->andWhere(['customer.status'=>1])
        ->andWhere(['service.type'=>$type])
        ->andWhere(['account.status'=>1])
        ->andWhere(['service_request.status'=>1]);
        if($from!=null)
        {
            $customers->andWhere(['>=', 'service_request.requested_datetime', $from]);
        }
        if($to!=null)
        {
            $customers->andWhere(['<=', 'service_request.requested_datetime', $to]);
        }
        $customers=$customers->all();
        if ($customers)
        {
            $count = count($customers);
        }

        return $count;
    }
     public function getAllQueryCompleted($keyword=null, $ward=null, $lsgi=null, $supervisor=null, $gt=null, $association=null, $from=null, $to=null,$service=null,$type=null,$status=null)
    {
        $unit = null;
        $agency = null;
        $modelUser  = Yii::$app->user->identity;
        $userRole = $modelUser->role;
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
        if(isset($associations['ward_id'])&&$ward==null)
        {
            $ward = $associations['ward_id'];
            $ward = json_decode($ward);
        }
        if(isset($associations['hks_id']))
        {
            $unit = $associations['hks_id'];
        }
        if(isset($associations['gt_id'])&&!$gt)
        {
            $gt = $associations['gt_id'];
        }
        if(isset($associations['survey_agency_id']))
        {
            $agency = $associations['survey_agency_id'];
        }
        $from_date =$from?\Yii::$app->formatter->asDatetime($from, "php:Y-m-d"):'';
       $to_date = $to?\Yii::$app->formatter->asDatetime($to, "php:Y-m-d"):'';
        $query = ServiceRequest::find()
        ->leftjoin('service_assignment','service_assignment.service_request_id=service_request.id')
        ->leftJoin('service','service.id=service_request.service_id')
        ->where(['service_request.status'=>1])
        // ->andWhere(['>','service_assignment.servicing_status_option_id',0])
        ->orderby('id DESC');
        if($modelUser->role=='customer')
        {
            $query->andWhere(['service_request.account_id_customer'=>$modelUser->id]);
        }
        if($service)
        {
            $query->andWhere(['service_request.service_id'=>$service]);
        }
        if($type)
        {
          $query->andWhere(['service.type'=>$type]);
        }
        if($userRole=='supervisor'&&isset($modelUser->id))
    {
      $supervisor = $modelUser->id;
      // $unit = $modelUser->green_action_unit_id;
    }
    if($supervisor||$gt)
    {
      $query
      ->leftjoin('account_authority','account_authority.account_id_customer=service_request.account_id_customer');
      if($supervisor)
      {
        $query->andWhere(['account_authority.account_id_supervisor'=>$supervisor]);
      }
      if($gt)
      {
        $query->andWhere(['account_authority.account_id_gt'=>$gt]);
      }
      
    }
        if($keyword||$ward||$unit||$lsgi||$association)
        {
            $query->leftjoin('account','account.id=service_request.account_id_customer')
            ->leftjoin('customer','customer.id=account.customer_id');
            if($association)
            {
                $query->andWhere(['customer.residential_association_id'=>$association]);
            }
            if($lsgi!=null)
            {
              $query->leftjoin('ward','ward.id=customer.ward_id')
            ->leftjoin('lsgi','lsgi.id=ward.lsgi_id')
            -> andWhere(['lsgi.id'=>$lsgi]);
            }
            if($keyword){
               $query->andFilterWhere(['like', 'customer.lead_person_name', $keyword]);
            }
            if($ward!=null)
            {
              $query->andWhere(['service_request.ward_id'=>$ward]);
            }
            if($unit!=null)
        {
            $query->leftjoin('green_action_unit_ward','green_action_unit_ward.ward_id=customer.ward_id')
            ->andWhere(['green_action_unit_ward.green_action_unit_id'=>$unit]);
        }
            
        }
        if($service)
        {
            $query
            ->andWhere(['service.id'=>$service]);
        }
        if(($from_date&&$to_date)){
        $query->andWhere(['>=', 'service_assignment.servicing_datetime',$from_date])
        ->andWhere(['<=', 'service_assignment.servicing_datetime',$to_date]);
      }
      if($from_date){
        $query->andWhere(['>=', 'service_assignment.servicing_datetime',$from_date]);
      }
      if($to_date){
        $query->andWhere(['<=', 'service_assignment.servicing_datetime',$to_date]);
      }
      if($status)
      {
        if($status==1)
        {
          $query
          ->andWhere(['>','service_assignment.servicing_status_option_id',0]);
        }
        if($status==2)
        {
          $query
          ->andWhere(['service_assignment.servicing_status_option_id'=>null]);
        }
    }

        return $query;
    }
     public function getAllQueryRating($keyword=null, $ward=null, $lsgi=null, $supervisor=null, $gt=null, $association=null, $from=null, $to=null,$service=null,$type=null,$status=null,$unit=null)
    {
        $agency = null;
        $modelUser  = Yii::$app->user->identity;
        $userRole = $modelUser->role;
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
        if(isset($associations['ward_id'])&&$ward==null)
        {
            $ward = $associations['ward_id'];
            $ward = json_decode($ward);
        }
        if(isset($associations['hks_id']))
        {
            $unit = $associations['hks_id'];
        }
        if(isset($associations['gt_id'])&&!$gt)
        {
            $gt = $associations['gt_id'];
        }
        if(isset($associations['survey_agency_id']))
        {
            $agency = $associations['survey_agency_id'];
        }
        $from_date =$from?\Yii::$app->formatter->asDatetime($from, "php:Y-m-d"):'';
       $to_date = $to?\Yii::$app->formatter->asDatetime($to, "php:Y-m-d"):'';
       // SELECT sum(marked_rating_value) as marked_value,sum(total_rating_value) as total_value,service.name from service_request left join service on service_request.service_id=service.id where marked_rating_value is not null and service_request.status=1 GROUP by(service.id)
        $query = ServiceRequest::find()
        ->select('sum(marked_rating_value) as marked_value,sum(total_rating_value) as total_rating,service.name,green_action_unit.name as unit_name')
        ->leftjoin('service','service.id=service_request.service_id')
        ->leftjoin('ward','ward.id=service_request.ward_id')
        ->leftjoin('green_action_unit_ward','ward.id=green_action_unit_ward.id')
        ->leftjoin('green_action_unit','green_action_unit.id=green_action_unit_ward.green_action_unit_id
            ')
        ->where(['service_request.status'=>1])
        ->groupBy('service.id');
        if($modelUser->role=='customer')
        {
            $query->andWhere(['service_request.account_id_customer'=>$modelUser->id]);
        }
        if($service)
        {
            $query->andWhere(['service_request.service_id'=>$service]);
        }
        if($type)
        {
          $query->andWhere(['service.type'=>$type]);
        }
        if($userRole=='supervisor'&&isset($modelUser->id))
    {
      $supervisor = $modelUser->id;
      // $unit = $modelUser->green_action_unit_id;
    }
    if($supervisor||$gt)
    {
      $query
      ->leftjoin('account_authority','account_authority.account_id_customer=service_request.account_id_customer');
      if($supervisor)
      {
        $query->andWhere(['account_authority.account_id_supervisor'=>$supervisor]);
      }
      if($gt)
      {
        $query->andWhere(['account_authority.account_id_gt'=>$gt]);
      }
      
    }
        if($keyword||$ward||$unit||$lsgi||$association)
        {
            $query->leftjoin('account','account.id=service_request.account_id_customer')
            ->leftjoin('customer','customer.id=account.customer_id');
            if($association)
            {
                $query->andWhere(['customer.residential_association_id'=>$association]);
            }
            if($lsgi!=null)
            {
              $query->leftjoin('ward','ward.id=customer.ward_id')
            ->leftjoin('lsgi','lsgi.id=ward.lsgi_id')
            -> andWhere(['lsgi.id'=>$lsgi]);
            }
            if($keyword){
               $query->andFilterWhere(['like', 'customer.lead_person_name', $keyword]);
            }
            if($ward!=null)
            {
              $query->andWhere(['service_request.ward_id'=>$ward]);
            }
            if($unit!=null)
        {
            $query->leftjoin('green_action_unit_ward','green_action_unit_ward.ward_id=customer.ward_id')
            ->andWhere(['green_action_unit_ward.green_action_unit_id'=>$unit]);
        }
            
        }
        if($service)
        {
            $query
            ->andWhere(['service.id'=>$service]);
        }
        if(($from_date&&$to_date)){
        $query->andWhere(['>=', 'service_assignment.servicing_datetime',$from_date])
        ->andWhere(['<=', 'service_assignment.servicing_datetime',$to_date]);
      }
      if($from_date){
        $query->andWhere(['>=', 'service_assignment.servicing_datetime',$from_date]);
      }
      if($to_date){
        $query->andWhere(['<=', 'service_assignment.servicing_datetime',$to_date]);
      }

        return $query;
    }
}
