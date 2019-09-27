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
class CameraServicingStatusOption extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'camera_servicing_status_option';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['service_id', 'status','image_id'], 'integer'],
            [['name', 'created_at', 'modified_at'], 'safe'],
            [['value'], 'string'],

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
            'value' => Yii::t('app', 'Value'),
            'service_id' => Yii::t('app', 'Service'),
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
}
