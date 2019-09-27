<?php

namespace api\modules\v1\models;

use Yii;
use yii\db\Expression;
use yii\behaviors\TimestampBehavior;
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
class Payment extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'payment';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['account_id_gt', 'payment_request_id', 'status'], 'integer'],
            [['amount'], 'required'],
            [['amount'], 'number'],
            [['paid_at', 'created_at', 'modified_at','type','account_id_supervisor'], 'safe'],
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
            'amount' => Yii::t('app', 'Amount'),
            'created_at' => Yii::t('app', 'Created At'),
            'modified_at' => Yii::t('app', 'Modified At'),
            'status' => Yii::t('app', 'Status'),
        ];
    }
     public function getFkAccount()
    {
        return $this->hasOne(Account::className(), ['id' => 'account_id_gt']);
    }
    public function getFkPaymentRequest()
    {
        return $this->hasOne(PaymentRequest::className(), ['id' => 'payment_request_id']);
    }
    public function deleteRequest($id)
    {
       $connection = Yii::$app->db;
       $connection->createCommand()->update('payment', ['status' => 0], 'id=:id')->bindParam(':id',$id)->execute();
       return true;
    }
    public static function getAllQuery() {
     $query = static::find()->where(['payment.status'=>1]);
     return $query;
   }
   public function getServiceName($id)
    {
        $name  = null;
        $modelServiceRequest =  $this->fkPaymentRequest->fkServiceRequest;
        if($modelServiceRequest){
            $name = $modelServiceRequest->fkService->name;  
        }
      return $name;
    }
    public function getTotalAmount($id)
    {

        $amount  = 0;
        $modelAccount = Account::find()->where(['customer_id'=>$id])->andWhere(['status'=>1])->one();
        if($modelAccount)
        {
            $modelRequests = PaymentRequest::find()->where(['account_id_customer'=>$modelAccount->id])->andWhere(['is_closed'=>0])->all();
                foreach ($modelRequests as $key => $value) {
                    $amount = $amount + $value->amount;
                }
        }
      return $amount;
    }
    public function getPaidAmount($id)
    {

        $amount  = 0;
        $modelAccount = Account::find()->where(['customer_id'=>$id])->andWhere(['status'=>1])->one();
        if($modelAccount)
        {
            $modelRequests = PaymentRequest::find()->where(['account_id_customer'=>$modelAccount->id])->andWhere(['is_closed'=>0])->all();
                foreach ($modelRequests as $key => $modelRequest) {
                    $modelPayment = Payment::find()->where(['payment_request_id'=>$modelRequest->id])->andWhere(['status'=>1])->all();
                    foreach ($modelPayment as $key => $value) {
                        $amount = $amount + $value->amount;
                    }
                }
        }
      return $amount;
    }
    public static function sendConfirmation($accountId,$amount) {
        $modelAccount = Account::find()->where(['status'=>1])->andWhere(['id'=>$accountId])->one();
        $modelCustomer = Customer::find()->where(['status'=>1])->andWhere(['id'=>$modelAccount->customer_id])->one();
        $customerName = $modelCustomer->lead_person_name?$modelCustomer->lead_person_name:'Customer';
        $key = 'account_id';
        $value = $modelAccount->id;
        $modelPushMessage = new PushMessage;
        $modelPushMessage->account_id = $value;
        $modelPushMessage->message = 'Dear '.$customerName . 'Your payment successfully transfered. Amount is'. $amount ;
        $modelPushMessage->save(false);
        $result = Yii::$app->message->sendMessage($key,$value,$modelPushMessage->message);

        $authKey = Yii::$app->params['authKeyMsg'];
                 $phone = $modelCustomer->lead_person_phone;
                // $phone = '9847640775';
                $content = 'Dear $customerName Your payment successfully transfered. Amount is $amount';
                   $countryCode = '91';
                   $senderId = 'WMSMGMT';
                   // Yii::$app->message->sendSMS($authKey,"WMSMGMT","91",$phone,$content);

    }
    public static function sendFailedMessage($accountId) {
        $modelAccount = Account::find()->where(['status'=>1])->andWhere(['id'=>$accountId])->one();
        $modelCustomer = Customer::find()->where(['status'=>1])->andWhere(['id'=>$modelAccount->customer_id])->one();
        $customerName = $modelCustomer->lead_person_name?$modelCustomer->lead_person_name:'Customer';

        $authKey = Yii::$app->params['authKeyMsg'];
                 $phone = $modelCustomer->lead_person_phone;
                // $phone = '9847640775';
                $content = 'Insufficient balance. Please recharge your account';
                   $countryCode = '91';
                   $senderId = 'WMSMGMT';
                   // Yii::$app->message->sendSMS($authKey,"WMSMGMT","91",$phone,$content);

    }
}
