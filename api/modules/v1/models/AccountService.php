<?php

namespace api\modules\v1\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
/**
 * This is the model class for table "account_service".
 *
 * @property int $id
 * @property int $account_id
 * @property int $service_id
 * @property int $status
 * @property string $created_at
 * @property string $modified_at
 */
class AccountService extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'account_service';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['account_id', 'service_id'], 'required'],
            [['account_id', 'service_id', 'status'], 'integer'],
            [['created_at', 'modified_at'], 'safe'],
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
            'account_id' => Yii::t('app', 'Account ID'),
            'service_id' => Yii::t('app', 'Service ID'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'modified_at' => Yii::t('app', 'Modified At'),
        ];
    }
    public static function getAllQuery()
    {
      return static::find();
    }
    public function getFkService()
    {
        return $this->hasOne(Service::className(), ['id' => 'service_id']);
    }
     public function getFkServicePackage()
    {
        return $this->hasOne(Service::className(), ['id' => 'package_id']);
    }
    public function getServiceName()
    {
        $name  = null;
        if ($this->fkService)
        {
            $name = isset($this->fkService->name)?$this->fkService->name:null;
        }

        return $name;
    }
    public function getServiceNameMl()
    {
        $name  = null;
        if ($this->fkService)
        {
            $name = isset($this->fkService->name_ml)?$this->fkService->name_ml:$this->fkService->name;
        }

        return $name;
    }
    public function getServicePackageName()
    {
        $name  = null;
        if ($this->fkServicePackage)
        {
            $name = isset($this->fkServicePackage->name)?$this->fkServicePackage->name:null;
        }

        return $name;
    }
    public function getServicePackageNameMl()
    {
        $name  = null;
        if ($this->fkServicePackage)
        {
            $name = isset($this->fkServicePackage->name_ml)?$this->fkServicePackage->name_ml:$this->fkServicePackage->name;
        }

        return $name;
    }
}
