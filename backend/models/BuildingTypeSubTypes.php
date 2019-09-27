<?php

namespace backend\models;

use Yii;
use yii\db\Expression;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "building_type_sub_types".
 *
 * @property int $id
 * @property string $name
 * @property int $building_type_id
 * @property int $status
 * @property string $created_at
 * @property string $modified_at
 */
class BuildingTypeSubTypes extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'building_type_sub_types';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            // [['name', 'building_type_id'], 'required'],
            [['name'], 'required'],
            [['building_type_id', 'status'], 'integer'],
            [['created_at', 'modified_at','sort_order'], 'safe'],
            [['name'], 'string', 'max' => 500],
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
            'building_type_id' => Yii::t('app', 'Building Type ID'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'modified_at' => Yii::t('app', 'Modified At'),
        ];
    }
     public function deleteType($id)
    {
       $connection = Yii::$app->db;
       $connection->createCommand()->update('building_type_sub_types', ['status' => 0], 'id=:id')->bindParam(':id',$id)->execute();
       return true;
    }
    public function getType()
    {
        $types =  BuildingTypeSubTypes::find()->where(['status'=> 1])->all();
        return $types;
    }
}
