<?php

namespace backend\models;

use Yii;
use yii\db\Expression;
use yii\behaviors\TimestampBehavior;
/**
 * This is the model class for table "fee_collection_interval".
 *
 * @property int $id
 * @property string $name
 * @property int $status
 * @property string $created_at
 * @property string $modified_at
 */
class FeeCollectionInterval extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'fee_collection_interval';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['status'], 'integer'],
            [['created_at', 'modified_at','building_type','sort_order'], 'safe'],
            [['name'], 'string', 'max' => 250],
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
            'name' => Yii::t('app', 'Name'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'modified_at' => Yii::t('app', 'Modified At'),
        ];
    }
     public function deleteFeeCollectionInterval($id)
    {
       $connection = Yii::$app->db;
       $connection->createCommand()->update('fee_collection_interval', ['status' => 0], 'id=:id')->bindParam(':id',$id)->execute();
       return true;
    }
    public function getInterval()
    {
        $types =  FeeCollectionInterval::find()->where(['status'=> 1])->all();
        return $types;
    }
}
