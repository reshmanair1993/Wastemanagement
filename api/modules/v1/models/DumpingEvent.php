<?php

namespace api\modules\v1\models;

use Yii;
use yii\db\Expression;
use yii\data\ActiveDataProvider;
use yii\behaviors\TimestampBehavior;
/**
 * This is the model class for table "residential_association".
 *
 * @property int $id
 * @property string $name
 * @property double $penalty
 * @property int $status
 * @property string $created_at
 * @property string $modified_at
 */
class DumpingEvent extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'dumping_event';
    }
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'modified_at',
                'value' => new Expression('NOW()'),
            ]
        ];
    }
    /**
     * {@inheritdoc}
     */

    public function rules()
    {
        return [
            [[
              'status','account_id_customer','image_id','incident_type_id'
            ], 'integer'],
            [['created_at', 'modified_at','lat','lng','location_name'], 'safe'],
            [['remarks'], 'string'],
            [['account_id_customer'],'required'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'status' => 'Status',
            'created_at' => 'Created At',
            'modified_at' => 'Modified At',
        ];
    }

    public static function getAllQuery()
    {
      $query = static::find()->where(['status' => 1]);
      return $query;
    }
    public function deleteType($id){
      $connection = Yii::$app->db;
      $connection->createCommand()->update('dumping_event', ['status' => 0], 'id=:id')->bindParam(':id',$id)->execute();
       return true;
    }
    public function getFkImage()
  {
    return $this->hasOne(Image::className(), ['id' => 'image_id']);
  }
}
