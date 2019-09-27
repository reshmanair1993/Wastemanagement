<?php

namespace api\modules\v1\models;

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
class ServiceRequest extends \yii\db\ActiveRecord
{
    public $district_id,$block_id,$assembly_constituency_id,$customer_id;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'service_request';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['service_id', 'account_id_customer'], 'required'],
            [['service_id', 'account_id_customer', 'account_id_gt', 'account_id_completed_by', 'status'], 'integer'],
            [['requested_datetime', 'servicing_date', 'created_at', 'modified_at','marked_rating_value','total_rating_value','is_cancelled','ward_id','service_id_package','time_of_completion_points_calculated'], 'safe'],
            [['remark'], 'string'],
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
    // public function getFkService()
    //     {
    //             return $this->hasOne(Service::className(), ['id' => 'service_id']);
    //     }
        public function getFkGreenTechnician()
        {
                return $this->hasOne(Account::className(), ['id' => 'account_id_gt']);
        }
        public function getCustomer($id)
    {
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
       $connection->createCommand()->update('service_request', ['status' => 0], 'id=:id')->bindParam(':id',$id)->execute();
       $modelServiceAssignment = ServiceAssignment::find()->where(['service_request_id'=>$id])->one();
       if($modelServiceAssignment)
       {
        $modelServiceAssignment->status=0;
        $modelServiceAssignment->save(false);
       }
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
    public static function getAllQuery()
    {
      return static::find()->where(['service_request.status'=>1])->orderBy(['id' => SORT_DESC]);
    }
     public function getQuantity($id)
    {
        $val  = null;
        $service = Service::find()->where(['id'=>$id])->one();
        if($service)
        {
            $val = $service->ask_waste_quantity;
        }
      return $val;
    }
    public function getQuality($id)
    {
        $val  = null;
        $service = Service::find()->where(['id'=>$id])->one();
        if($service)
        {
            $val = $service->ask_waste_quality;
        }
      return $val;
    }
    public function getFkAccountCustomer()
     {
       return $this->hasOne(Account::className(), ['id' => 'account_id_customer'])->andWhere(['status'=>1]);
     }
      public function getFkService()
     {
       return $this->hasOne(Service::className(), ['id' => 'service_id'])->andWhere(['status'=>1]);
     }
     public function getFkLsgi()
     {
       return $this->hasOne(Lsgi::className(), ['id' => 'lsgi_id'])->andWhere(['status'=>1]);
     }
      public function getFkServiceAssignment()
     {
       return $this->hasOne(ServiceAssignment::className(), ['service_request_id' => 'id'])->andWhere(['status'=>1]);
     }
     public static function confirmation($service,$accountId) {
        $modelService = Service::find()->where(['status'=>1])->andWhere(['id'=>$service])->one();
        $serviceName = $modelService->name;
        $modelAccount = Account::find()->where(['status'=>1])->andWhere(['id'=>$accountId])->one();
        $modelCustomer = Customer::find()->where(['status'=>1])->andWhere(['id'=>$modelAccount->customer_id])->one();
        $customerName = $modelCustomer->lead_person_name?$modelCustomer->lead_person_name:'Customer';
        $key = 'account_id';
         $date = date('d/m/Y');
        $value = $modelAccount->id;
        $modelPushMessage = new PushMessage;
        $modelPushMessage->account_id = $value;
        // $modelPushMessage->message = 'Dear '.$customerName . ' Schedule corresponding to '. $serviceName .' is completed';
        $modelPushMessage->message = 'Your' . $serviceName . 'is completed successfully on '.$date.'. In case of any issue please raise the complaint through the Green Trivandrum app. ';
        $modelPushMessage->save(false);
        $result = Yii::$app->message->sendMessage($key,$value,$modelPushMessage->message);

    }
    public static function complaint($service,$accountId) {
        $modelService = Service::find()->where(['status'=>1])->andWhere(['id'=>$service])->one();
        $serviceName = $modelService->name;
        $modelAccount = Account::find()->where(['status'=>1])->andWhere(['id'=>$accountId])->one();
        $modelCustomer = Customer::find()->where(['status'=>1])->andWhere(['id'=>$modelAccount->customer_id])->one();
      $customerName = $modelCustomer->lead_person_name?$modelCustomer->lead_person_name:'Customer';
      $key = 'account_id';
        $value = $modelAccount->id;
        $modelPushMessage = new PushMessage;
        $modelPushMessage->account_id = $value;
        // $modelPushMessage->message = 'Dear '.$customerName . ' your complaint recieved successfully. We will contact you soon';
        $modelPushMessage->message = 'Your complaint of '.$serviceName.' is successfully registered. Will resolve the complaints at the earliest.';
        $modelPushMessage->save(false);
        $result = Yii::$app->message->sendMessage($key,$value,$modelPushMessage->message);

    }
    public static function service($service,$accountId) {
        $modelService = Service::find()->where(['status'=>1])->andWhere(['id'=>$service])->one();
        $serviceName = $modelService->name;
        $modelAccount = Account::find()->where(['status'=>1])->andWhere(['id'=>$accountId])->one();
        $modelCustomer = Customer::find()->where(['status'=>1])->andWhere(['id'=>$modelAccount->customer_id])->one();
      $params = Yii::$app->params['twilio'];
      
        $to = '+91'.$modelCustomer->lead_person_phone;
       // $to = '+919847640775';
      $customerName = $modelCustomer->lead_person_name?$modelCustomer->lead_person_name:'Customer';
      // $customerName = 'Customer';
      $content = "Dear $customerName your request recieved successfully. We will contact you soon";
      try {
        $from = $params['sender_id'];
        Yii::$app->twilio->sendSms($to,$from, $content);
      } catch(\Exception $ex) {
        $from = $params['number'];
        Yii::$app->twilio->sendSms($to,$from, $content);

      }

    }
    public static function doorLock($service,$accountId) {
        $modelService = Service::find()->where(['status'=>1])->andWhere(['id'=>$service])->one();
        $serviceName = $modelService->name;
        $modelAccount = Account::find()->where(['status'=>1])->andWhere(['id'=>$accountId])->one();
        $modelCustomer = Customer::find()->where(['status'=>1])->andWhere(['id'=>$modelAccount->customer_id])->one();
        $customerName = $modelCustomer->lead_person_name?$modelCustomer->lead_person_name:'Customer';
        $key = 'account_id';
        $value = $modelAccount->id;
        $modelPushMessage = new PushMessage;
        $modelPushMessage->account_id = $value;
        $modelPushMessage->message = 'Dear '.$customerName . ' Schedule corresponding to'. $serviceName .' is completed. We mark door status as locked';
        $modelPushMessage->save(false);
        $result = Yii::$app->message->sendMessage($key,$value,$modelPushMessage->message);

    }
}
