<?php

namespace api\modules\v1\models;

use Yii;

/**
 * This is the model class for table "service".
 *
 * @property int $id
 * @property string $name
 * @property int $status
 * @property string $created_at
 * @property string $modified_at
 */
class ServiceAssignment extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'service_assignment';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_at', 'modified_at','service_request_id','account_id_gt','remarks','servicing_datetime','servicing_status_option_id','quantity','quality','door_status','lat','lng'], 'safe'],
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
    public function getFkServiceRequest()
    {
        return $this->hasOne(ServiceRequest::className(), ['id' => 'service_request_id'])->andWhere(['status'=>1]);
    }
     public static function getAllQuery()
    {
      return static::find()->where(['service_assignment.status'=>1])->orderBy(['id' => SORT_DESC]);
    }
    public function getFkServiceStatus()
    {
        return $this->hasOne(ServicingStatusOption::className(), ['id' => 'servicing_status_option_id'])->andWhere(['status'=>1]);
    }
}
