<?php

namespace api\modules\v1\models;

use Yii;

/**
 * This is the model class for table "fee_collection_interval".
 *
 * @property int $id
 * @property string $name
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
            [['name', 'building_type_id'], 'required'],
            [['building_type_id', 'status'], 'integer'],
            [['created_at', 'modified_at'], 'safe'],
            [['name'], 'string', 'max' => 500],
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

    public static function getAllQuery()
    {
      return static::find()->where(['status'=>1]);
    }
    public static function findByName($name) {
      $qry = static::getAllQuery()->andWhere(['name'=>$name]);
      return $qry;
    }
    
}
