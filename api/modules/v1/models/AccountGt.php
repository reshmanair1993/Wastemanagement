<?php

namespace api\modules\v1\models;

use Yii;

/**
 * This is the model class for table "memo_penalty".
 *
 * @property int $id
 * @property int $memo_type_id
 * @property int $lsgi_id
 * @property double $amount
 * @property int $status
 * @property string $created_at
 * @property string $modified_At
 */
class AccountGt extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'account_authority';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['account_id_customer', 'account_id_gt', 'status'], 'integer'],
            [['created_at', 'modified_At'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'account_id_gt' => 'Account id gt',
            'account_id_customer' => 'account id customer',
            'status' => 'Status',
            'created_at' => 'Created At',
            'modified_At' => 'Modified  At',
        ];
    }
    public static function getAllQuery()
    {
      $query = static::find()->where(['status'=>1])->orderBy(['id' => SORT_DESC]);
      return $query;
    }
    public function getFkAccountSup()
    {
        return $this->hasOne(Account::className(), ['id' => 'account_id_supervisor']);
    }
}
