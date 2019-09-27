<?php

namespace backend\models;

use Yii;
use yii\db\Expression;
use yii\behaviors\TimestampBehavior;
/**
 * This is the model class for table "fee_collection_interval_building_type".
 *
 * @property int $id
 * @property int $fee_collection_interval_id
 * @property int $building_type_id
 * @property int $status
 * @property string $created_at
 * @property string $modified_at
 */
class FeeCollectionIntervalBuildingType extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'fee_collection_interval_building_type';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fee_collection_interval_id', 'building_type_id'], 'required'],
            [['fee_collection_interval_id', 'building_type_id', 'status'], 'integer'],
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
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'fee_collection_interval_id' => Yii::t('app', 'Fee Collection Interval ID'),
            'building_type_id' => Yii::t('app', 'Building Type ID'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'modified_at' => Yii::t('app', 'Modified At'),
        ];
    }
}
