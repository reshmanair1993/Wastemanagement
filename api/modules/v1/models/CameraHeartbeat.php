<?php

namespace api\modules\v1\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
/**
 * This is the model class for table "memo_penalty".
 *
 * @property int $id
 * @property int $memo_type_id
 * @property int $lsgi_id
 * @property double $amount
 * @property int $status
 * @property string $created_at
 * @property string $modified_At
 */
class CameraHeartbeat extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'camera_heartbeat';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['camera_id','status','camera_active'], 'integer'],
            [['created_at', 'modified_At','timestamp'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'camera_id' => 'Camera id',
            'timestamp' => 'Timestamp',
            'status' => 'Status',
            'created_at' => 'Created At',
            'modified_At' => 'Modified  At',
        ];
    }
    public function behaviors()
    {
       return [
           [

           'class' => TimestampBehavior::className(),
           'createdAtAttribute' => 'created_at',
           'updatedAtAttribute' => 'modified_at',
           'value' => new Expression('NOW()'),
               // if you're using datetime instead of UNIX timestamp:
               // 'value' => new Expression('NOW()'),
           ],
       ];
    }
    public static function getAllQuery()
    {
      $query = static::find()->where(['status'=>1])->orderBy(['id' => SORT_DESC]);
      return $query;
    }
}
