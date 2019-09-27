<?php

namespace api\modules\v1\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "account_service_request".
 *
 * @property int $id
 * @property int $account_id
 * @property int $service_id
 * @property int $request_type
 * @property int $status
 * @property int $is_approved
 * @property string $requested_at
 * @property string $approval_status_changed_at
 * @property int $account_id_requested_by
 * @property int $account_id_approved_by
 * @property string $created_at
 * @property string $modified_at
 */
class AccountServiceRequest extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'account_service_request';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['account_id', 'service_id', 'request_type'], 'required'],
            [['account_id', 'service_id', 'request_type', 'status', 'is_approved', 'account_id_requested_by', 'account_id_approved_by'], 'integer'],
            [['requested_at', 'approval_status_changed_at', 'created_at', 'modified_at','sub_service','service_estimate','remarks'], 'safe'],
        ];
    }
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'modified_at',
                'value' => new Expression('NOW()'),
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
            'account_id' => Yii::t('app', 'Account ID'),
            'service_id' => Yii::t('app', 'Service ID'),
            'request_type' => Yii::t('app', 'Request Type'),
            'status' => Yii::t('app', 'Status'),
            'is_approved' => Yii::t('app', 'Is Approved'),
            'requested_at' => Yii::t('app', 'Requested At'),
            'approval_status_changed_at' => Yii::t('app', 'Approval Status Changed At'),
            'account_id_requested_by' => Yii::t('app', 'Account Id Requested By'),
            'account_id_approved_by' => Yii::t('app', 'Account Id Approved By'),
            'created_at' => Yii::t('app', 'Created At'),
            'modified_at' => Yii::t('app', 'Modified At'),
        ];
    }
     public function getCustomerName($id)
    {
        $name  = null;
        $account =  Account::find()->where(['id'=> $id])->one();
        if($account){
            $customer = Customer::find()->where(['id'=>$account->customer_id])->andWhere(['status'=>1])->one();
            if($customer)
            $name = $customer->lead_person_name;
        }
      return $name;
    }
    public function getFkAccount()
    {
      return $this->hasOne(Account::classname(), ['id'=> 'account_id']);
    }
}
