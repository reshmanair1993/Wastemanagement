<?php

namespace api\modules\v1\models;

use Yii;
use yii\db\Expression;
use yii\behaviors\TimestampBehavior;
/**
 * This is the model class for table "selection_method_service".
 *
 * @property int $id
 * @property int $service_id
 * @property int $waste_collection_method_id
 * @property int $status
 * @property string $created_at
 * @property string $modified_at
 */
class ServicePackageService extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'service_package_service';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['service_id'], 'required'],
            [['service_id', 'service_id_service', 'status'], 'integer'],
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
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'service_id' => Yii::t('app', 'Service ID'),
            'service_id_service' => Yii::t('app', 'Services'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'modified_at' => Yii::t('app', 'Modified At'),
        ];
    }
     public function getFkService()
    {
        return $this->hasOne(Service::className(), ['id' => 'service_id_service']);
    }
    public function getAllQuery() {
        return static::find()->where(['service_package_service.status'=>1]);
    }
}
