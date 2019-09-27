<?php

namespace backend\models;

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
class AccountFee extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'account_fee';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['account_id_customer', 'service_request_id', 'status'], 'integer'],
            [['amount_paid'], 'required'],
            [['amount_paid', 'amount_pending'], 'number'],
            [['date', 'created_at', 'modified_at'], 'safe'],
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
            'amount_paid' => Yii::t('app', 'Amount Paid'),
            'amount_pending' => Yii::t('app', 'Amount Pending'),
            'date' => Yii::t('app', 'Date'),
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
    public function deleteFee($id)
    {
       $connection = Yii::$app->db;
       $connection->createCommand()->update('account_fee', ['status' => 0], 'id=:id')->bindParam(':id',$id)->execute();
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
}
