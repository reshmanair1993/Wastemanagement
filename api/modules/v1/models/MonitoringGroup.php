<?php

namespace  api\modules\v1\models;

use Yii;

/**
 * This is the model class for table "monitoring_group".
 *
 * @property int $id
 * @property int $name
 * @property int $status
 * @property string $created_at
 * @property string $modified_at
 */
class MonitoringGroup extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'monitoring_group';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'status'], 'integer'],
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
            'name' => Yii::t('app', 'Name'),
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
