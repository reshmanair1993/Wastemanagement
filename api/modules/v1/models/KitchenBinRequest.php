<?php

namespace api\modules\v1\models;

use Yii;
use yii\db\Expression;
use yii\behaviors\TimestampBehavior;
/**
 * This is the model class for table "service".
 *
 * @property int $id
 * @property string $name
 * @property int $status
 * @property string $created_at
 * @property string $modified_at
 */
class KitchenBinRequest extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'kitchen_bin_request';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['account_id_customer', 'account_id_requested_by','requested_at', 'approval_status','account_id_approval_status_updated_by','status','created_at','modified_at'], 'safe'],
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
    // public function getFkServiceRequest()
    // {
    //     return $this->hasOne(ServiceRequest::className(), ['id' => 'service_request_id'])->andWhere(['status'=>1]);
    // }
     public static function getAllQuery()
    {
      return static::find()->where(['status'=>1])->orderBy(['id' => SORT_DESC]);
    }
    // public function getFkServiceStatus()
    // {
    //     return $this->hasOne(ServicingStatusOption::className(), ['id' => 'servicing_status_option_id'])->andWhere(['status'=>1]);
    // }
}
