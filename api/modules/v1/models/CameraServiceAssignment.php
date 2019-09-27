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
class CameraServiceAssignment extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'camera_service_assignment';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_at', 'modified_at','account_id_technician','remarks','date','camera_servicing_status_option_id','lat_update_from','lng_update_from'], 'safe'],
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
     public static function getAllQuery()
    {
      return static::find()->where(['camera_service_assignment.status'=>1])->orderBy(['id' => SORT_DESC]);
    }
    public function getFkServiceStatus()
    {
        return $this->hasOne(CameraServicingStatusOption::className(), ['id' => 'camera_servicing_status_option_id'])->andWhere(['status'=>1]);
    }
    public function getFkCamera()
    {
        return $this->hasOne(Camera::className(), ['id' => 'camera_id'])->andWhere(['status'=>1]);
    }
    public function getFkService()
    {
        return $this->hasOne(CameraService::className(), ['id' => 'service_id'])->andWhere(['status'=>1]);
    }
}
