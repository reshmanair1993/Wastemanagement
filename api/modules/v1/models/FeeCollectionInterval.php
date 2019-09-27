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
            [['created_at', 'modified_at'], 'safe'],
            [['name'], 'string', 'max' => 250],
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
      return static::find(['status'=>1])->orderby('fee_collection_interval.sort_order ASC');
    }
    public static function findByName($name) {
      $qry = static::getAllQuery()->andWhere(['name'=>$name]);
      return $qry;
    }
     public function deleteFeeCollectionInterval($id)
    {
       $connection = Yii::$app->db;
       $connection->createCommand()->update('fee_collection_interval', ['status' => 0], 'id=:id')->bindParam(':id',$id)->execute();
       return true;
    }
}
