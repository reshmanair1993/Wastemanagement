<?php

namespace  api\modules\v1\models;

use Yii;

/**
 * This is the model class for table "incident_meta".
 *
 * @property int $id
 * @property int $incident_key
 * @property string $value
 * @property int $incident_id
 * @property int $status
 * @property string $created_at
 * @property string $modified_at
 */
class IncidentMeta extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'incident_meta';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['incident_key', 'incident_id', 'status'], 'integer'],
            [['created_at', 'modified_at'], 'safe'],
            [['value'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'incident_key' => Yii::t('app', 'Incident Key'),
            'value' => Yii::t('app', 'Value'),
            'incident_id' => Yii::t('app', 'Incident ID'),
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
