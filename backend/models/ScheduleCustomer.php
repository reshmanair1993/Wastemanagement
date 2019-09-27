<?php

namespace backend\models;

use Yii;
use yii\db\Expression;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "selection_method_service".
 *
 * @property int $id
 * @property int $service_id
 * @property int $waste_collection_method_id
 * @property int $status
 * @property string $created_at
 * @property string $modified_at
 */
class ScheduleCustomer extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'schedule_customer';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['schedule_id', 'account_id_customer'], 'required'],
            [['schedule_id', 'account_id_customer', 'status'], 'integer'],
            [['created_at', 'modified_at'], 'safe'],
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
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'modified_at' => Yii::t('app', 'Modified At'),
        ];
    }
    public static function getAllQuery() {
     $query = static::find()->where(['status'=>1]);
     return $query;
   }
    public function getFkAccount()
    {
        return $this->hasOne(Account::className(), ['id' => 'account_id_customer']);
    }
}
