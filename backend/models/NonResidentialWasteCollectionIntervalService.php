<?php

namespace backend\models;

use Yii;
use yii\db\Expression;
use yii\behaviors\TimestampBehavior;
/**
 * This is the model class for table "waste_collection_interval".
 *
 * @property int $id
 * @property string $name
 * @property int $waste_category_id
 * @property int $building_type_available
 * @property int $is_public
 * @property int $sort_order
 * @property int $status
 * @property string $created_at
 * @property string $modified_at
 */
class NonResidentialWasteCollectionIntervalService extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'non_residential_waste_collection_interval_service';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['status','service_id','non_residential_waste_collection_interval_id'], 'integer'],
            [['created_at', 'modified_at','service_id'], 'safe'],
            [['name'], 'string', 'max' => 255],
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
            'name' => Yii::t('app', 'Name'),
           
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'modified_at' => Yii::t('app', 'Modified At'),
        ];
    }
    public function deleteType($id)
    {
       $connection = Yii::$app->db;
       $connection->createCommand()->update('non_residential_waste_collection_interval', ['status' => 0], 'id=:id')->bindParam(':id',$id)->execute();
       return true;
    }
    
    public function getAllQuery() {
        return static::find(['status'=>1]);
    }
    public function getFkService()
    {
        return $this->hasOne(Service::className(), ['id' => 'service_id']);
    }
}
