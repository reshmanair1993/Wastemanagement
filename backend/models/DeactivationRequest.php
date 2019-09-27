<?php

namespace backend\models;

use Yii;
use yii\db\Expression;
use yii\behaviors\TimestampBehavior;
/**
 * This is the model class for table "deactivation_request".
 *
 * @property int $id
 * @property int $account_id_customer
 * @property int $account_id_gt
 * @property int $account_id_requested_by
 * @property int $account_id_status_updated_by
 * @property string $requested_datetime
 * @property string $created_at
 * @property string $modified_at
 * @property int $status
 */
class DeactivationRequest extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'deactivation_request';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['account_id_customer', 'account_id_gt', 'account_id_requested_by', 'account_id_status_updated_by', 'status'], 'integer'],
            [['requested_datetime', 'created_at', 'modified_at','is_approve'], 'safe'],
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
            'account_id_gt' => Yii::t('app', 'Account Id Gt'),
            'account_id_requested_by' => Yii::t('app', 'Account Id Requested By'),
            'account_id_status_updated_by' => Yii::t('app', 'Account Id Status Updated By'),
            'requested_datetime' => Yii::t('app', 'Requested Datetime'),
            'created_at' => Yii::t('app', 'Created At'),
            'modified_at' => Yii::t('app', 'Modified At'),
            'status' => Yii::t('app', 'Status'),
            'fkAccountGt.fkPerson.first_name' => Yii::t('app', 'Green Technician'),
            'fkAccount.fkCustomer.lead_person_name' => Yii::t('app', 'Customer'),
        ];
    }
    public function getFkAccount()
    {
        return $this->hasOne(Account::className(), ['id' => 'account_id_customer']);
    }
     public function getFkAccountGt()
    {
        return $this->hasOne(Account::className(), ['id' => 'account_id_gt']);
    }
     public function toggleStatusbanned() {
        $modelAccount = $this->fkAccount;
        if($this->is_approve==1)
        {
            $this->is_approve =0;
            if($modelAccount)
            {
                $modelAccount->is_banned = 1;
                $modelAccount->save(false);
            }
        }
        else
        {
            $this->is_approve =1;
            if($modelAccount)
            {
                $modelAccount->is_banned = 0;
                $modelAccount->save(false);
            }
        }
      $this->save(false);
      return $this->is_approve;
    }
    public function deleteRequest($id)
    {
       $connection = Yii::$app->db;
       $connection->createCommand()->update('deactivation_request', ['status' => 0], 'id=:id')->bindParam(':id',$id)->execute();
       return true;
    }
}
