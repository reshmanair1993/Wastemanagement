<?php

namespace backend\models;

use Yii;
use yii\db\Expression;
use yii\behaviors\TimestampBehavior;
/**
 * This is the model class for table "complaint".
 *
 * @property int $id
 * @property string $title
 * @property int $account_id_customer
 * @property int $account_id_gt
 * @property int $account_id_completed_by
 * @property string $requested_date
 * @property string $servicing_date
 * @property string $remark
 * @property int $status
 * @property string $created_at
 * @property string $modified_at
 */
class Complaint extends \yii\db\ActiveRecord
{
    public $district_id,$block_id,$assembly_constituency_id,$lsgi_id,$ward_id;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'complaint';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'account_id_customer', 'remark'], 'required'],
            [['title', 'remark'], 'string'],
            [['account_id_customer', 'account_id_gt', 'account_id_completed_by', 'status'], 'integer'],
            [['requested_date', 'servicing_date', 'created_at', 'modified_at'], 'safe'],
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
            'title' => Yii::t('app', 'Title'),
            'account_id_customer' => Yii::t('app', 'Account Id Customer'),
            'account_id_gt' => Yii::t('app', 'Account Id Gt'),
            'account_id_completed_by' => Yii::t('app', 'Account Id Completed By'),
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
        }  
        }
        
      return $name;
    }
     public function deleteComplaint($id)
    {
       $connection = Yii::$app->db;
       $connection->createCommand()->update('complaint', ['status' => 0], 'id=:id')->bindParam(':id',$id)->execute();
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
        $name= null;
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
}
