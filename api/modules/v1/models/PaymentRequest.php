<?php

namespace api\modules\v1\models;
use yii\db\Expression;
use yii\behaviors\TimestampBehavior;
use Yii;

/**
 * This is the model class for table "account_fee".
 *
 * @property int $id
 * @property int $account_id_customer
 * @property int $service_request_id
 * @property double $amount_paid
 * @property double $amount_pending
 * @property string $date
 * @property string $created_at
 * @property string $modified_at
 * @property int $status
 */
class PaymentRequest extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'payment_request';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['account_id_customer', 'service_request_id', 'status','is_closed'], 'integer'],
            [['amount'], 'required'],
            [['amount'], 'number'],
            [['requested_date', 'created_at', 'modified_at'], 'safe'],
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
            'account_id_customer' => Yii::t('app', 'Account Id Customer'),
            'service_request_id' => Yii::t('app', 'Service Request ID'),
            'amount' => Yii::t('app', 'Amount'),
            'requested_date' => Yii::t('app', 'Date'),
            'created_at' => Yii::t('app', 'Created At'),
            'modified_at' => Yii::t('app', 'Modified At'),
            'status' => Yii::t('app', 'Status'),
        ];
    }
     public function getFkAccount()
    {
        return $this->hasOne(Account::className(), ['id' => 'account_id_customer']);
    }
    public function getFkServiceRequest()
    {
        return $this->hasOne(ServiceRequest::className(), ['id' => 'service_request_id']);
    }
    public function deleteRequest($id)
    {
       $connection = Yii::$app->db;
       $connection->createCommand()->update('payment_request', ['status' => 0], 'id=:id')->bindParam(':id',$id)->execute();
       return true;
    }
    public static function getAllQuery() {
     $query = static::find()->where(['status'=>1]);
     return $query;
   }
   public function getServiceName($id)
    {
        $name  = null;
        $modelServiceRequest =  $this->fkServiceRequest;
        if($modelServiceRequest){
            $name = $modelServiceRequest->fkService->name;  
        }
      return $name;
    }
    public function getAmountPaid($id)
    {

        $amount  = 0;
                    $modelPayment = Payment::find()->where(['payment_request_id'=>$id])->andWhere(['status'=>1])->all();
                    if($modelPayment){
                    foreach ($modelPayment as $key => $value) {
                        $amount = $amount + $value->amount;
                    }
                }
      return $amount;
    }
    public function getAmount($id,$userId)
    {
      
      $amount  = 0;
        $modelServiceRequest = ServiceRequest::find()->where(['id'=>$id])->andWhere(['status'=>1])->one();
            if($modelServiceRequest)
            {
              $modelService = $modelServiceRequest->fkService;
              if($modelService)
              {
                $modelAccount = Account::find()->where(['id'=>$userId])->one();
                if($modelAccount)
                {
                  $modelCustomer = $modelAccount->fkCustomer;
                  if($modelCustomer)
                  {
                    $modelWard = $modelCustomer->fkWard;
                    if($modelWard)
                    {
                      $modelLsgi = $modelWard->fkLsgi;
                      if($modelLsgi)
                      {
                        $modelLsgiServiceFee = LsgiServiceFee::find()->where(['service_id'=>$modelService->id])->andWhere(['lsgi_id'=>$modelWard->lsgi_id])->andWhere(['payment_collection_type'=>2])->andWhere(['status'=>1])->one();
                        if($modelLsgiServiceFee)
                        {
                          $amount = $modelLsgiServiceFee->amount;
                        }
                        else
                        {
                          if($modelService->type==1)
                          {
                            $amount = $modelLsgi->default_service_rate?$modelLsgi->default_service_rate:0;

                          }
                          elseif($modelService->type==2)
                          {
                            $amount = $modelLsgi->default_complaint_rate?$modelLsgi->default_complaint_rate:0;
                          }
                          else
                          {
                            $amount = 0;
                          }
                          
                        }
                      }
                    }
                  }
                }
              }
            }
      return $amount;
    }
    public function getPackageAmount($id,$userId)
    {
      
      $amount  = 0;
        $modelService = Service::find()->where(['id'=>$id])->andWhere(['status'=>1])->one();
              if($modelService)
              {
                $modelAccount = Account::find()->where(['id'=>$userId])->one();
                if($modelAccount)
                {
                  $modelCustomer = $modelAccount->fkCustomer;
                  if($modelCustomer)
                  {
                    $modelWard = $modelCustomer->fkWard;
                    if($modelWard)
                    {
                      $modelLsgi = $modelWard->fkLsgi;
                      if($modelLsgi)
                      {
                        $modelLsgiServiceFee = LsgiServiceFee::find()->where(['service_id'=>$modelService->id])->andWhere(['lsgi_id'=>$modelWard->lsgi_id])->andWhere(['status'=>1])->one();
                        if($modelLsgiServiceFee)
                        {
                          $amount = $modelLsgiServiceFee->amount;
                        }
                        else
                        {
                          if($modelService->type==1)
                          {
                            $amount = $modelLsgi->default_service_rate?$modelLsgi->default_service_rate:0;

                          }
                          elseif($modelService->type==2)
                          {
                            $amount = $modelLsgi->default_complaint_rate?$modelLsgi->default_complaint_rate:0;
                          }
                          else
                          {
                            $amount = 0;
                          }
                          
                        }
                      }
                    }
                  }
                }
              }
      return $amount;
    }
     public function getSlabAmount($id,$userId,$lsgi=null)
    {
      $amount = 0;
      $modelAccountSlabService = AccountSlabService::find()->where(['service_id'=>$id])->andWhere(['account_id_customer'=>$userId])->andWhere(['status'=>1])->one();
      if($modelAccountSlabService)
      {
        $modelLsgiServiceSlabFee = LsgiServiceSlabFee::find()->where(['id'=>$modelAccountSlabService->slab_id])->andWhere(['status'=>1])->one();
        if($modelLsgiServiceSlabFee)
        {
          $amount = $modelLsgiServiceSlabFee->amount;
        }else
        {
          $modelLsgi = Lsgi::find()->where(['id'=>$lsgi])->andWhere(['status'=>1])->one();
          if($modelLsgi&&$modelLsgi->default_slab_rate)
          {
            $amount = $modelLsgi->default_slab_rate;
          }
          else
          {
            $amount = 0;
          }
        }
      }else
      {
        $modelAccount = Account::find()->where(['id'=>$userId])->andWhere(['status'=>1])->one();
        if($modelAccount)
        {
          $modelCustomer = Customer::find()->where(['id'=>$modelAccount->customer_id])->andwhere(['status'=>1])->one();
          if(isset($modelCustomer->fkBuildingType->fkCategory->rate_type)&&$modelCustomer->fkBuildingType->fkCategory->rate_type==1)
          {
            $modelLsgi = Lsgi::find()->where(['id'=>$lsgi])->andWhere(['status'=>1])->one();
          if($modelLsgi&&$modelLsgi->default_slab_rate)
          {
            $amount = $modelLsgi->default_slab_rate;
          }
          else
          {
            $amount = 0;
          }

          }
        }
      }
      return $amount;
    }

     public function getSlabAmountNonResidential($id,$userId,$lsgi=null,$qty=null)
    {
      $amount = 0;
      $modelAccountService = AccountService::find()->where(['service_id'=>$id])->andWhere(['account_id'=>$userId])->andWhere(['status'=>1])->one();
      if($modelAccountService)
      {
        $modelLsgiServiceSlabFee = LsgiServiceSlabFee::find()
        ->where(['service_id'=>$modelAccountService->service_id])
        ->andWhere(['lsgi_service_slab_fee.collection_interval'=>$modelAccountService->collection_interval])
        ->andWhere(['<','lsgi_service_slab_fee.start_value',$qty])
        ->andWhere(['>','lsgi_service_slab_fee.end_value',$qty])
        ->andWhere(['lsgi_service_slab_fee.slab_id'=>$modelAccountService->slab_id])
        ->andWhere(['status'=>1])->one();
        if($modelLsgiServiceSlabFee)
        {
          $amount = $modelLsgiServiceSlabFee->amount * $qty ;
        }else
        {
          $modelLsgi = Lsgi::find()->where(['id'=>$lsgi])->andWhere(['status'=>1])->one();
          if($modelLsgi&&$modelLsgi->default_slab_rate)
          {
            $amount = $modelLsgi->default_slab_rate * $qty;
          }
          else
          {
            $amount = 0;
          }
        }
      }
      return $amount;
    }

}
