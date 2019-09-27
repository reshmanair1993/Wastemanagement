<?php

namespace  api\modules\v1\models;

use Yii;

/**
 * This is the model class for table "monitoring_group_camera".
 *
 * @property int $id
 * @property int $camera_id
 * @property int $monitoring_group_id
 * @property int $status
 * @property string $created_at
 * @property string $modified_at
 */
class MonitoringGroupCamera extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'monitoring_group_camera';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['camera_id', 'monitoring_group_id', 'status'], 'integer'],
            [['created_at', 'modified_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'camera_id' => Yii::t('app', 'Camera ID'),
            'monitoring_group_id' => Yii::t('app', 'Monitoring Group ID'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'modified_at' => Yii::t('app', 'Modified At'),
        ];
    }
    public static function getAllQuery()
    {
      return static::find(['status'=>1])->orderBy(['id' => SORT_DESC]);
    }
}
