<?php

namespace backend\models;

use Yii;
use yii\db\Expression;
use yii\behaviors\TimestampBehavior;
/**
 * This is the model class for table "service_request".
 *
 * @property int $id
 * @property int $service_id
 * @property int $acoount_id_customer
 * @property int $account_id_gt
 * @property int $account_id_completed_by
 * @property string $requested_date
 * @property string $servicing_date
 * @property string $remark
 * @property int $status
 * @property string $created_at
 * @property string $modified_at
 */
class CameraService extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'camera_service';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['image_id', 'status','sort_order'], 'integer'],
            [['name', 'created_at', 'modified_at'], 'safe'],
            [['name'], 'string'],
            [['name'], 'required'],

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
            'image_id' => Yii::t('app', 'Image'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'modified_at' => Yii::t('app', 'Modified At'),
        ];
    }
    public function deleteCameraService($id)
     {
    $connection = Yii::$app->db;
    $connection->createCommand()->update('camera_service', ['status' => 0], 'id=:id')->bindParam(':id',$id)->execute();
    return true;
 }
 public static function getAllQuery()
 {
   $query = static::find()->where(['status'=>1])->orderBy(['id' => SORT_DESC]);
   return $query;
 }
}
