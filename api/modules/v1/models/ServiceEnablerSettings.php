<?php

namespace api\modules\v1\models;

use Yii;
use yii\db\Expression;
use yii\behaviors\TimestampBehavior;
/**
 * This is the model class for table "service_enabler_settings".
 *
 * @property int $id
 * @property int $service_id
 * @property string $customer_field
 * @property string $customer_field_value
 * @property int $status
 * @property string $created_at
 * @property string $modified_at
 */
class ServiceEnablerSettings extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'service_enabler_settings';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['service_id', 'customer_field', 'customer_field_value'], 'required'],
            [['service_id', 'status'], 'integer'],
            [['created_at', 'modified_at'], 'safe'],
            [['customer_field', 'customer_field_value'], 'string', 'max' => 500],
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
            'service_id' => Yii::t('app', 'Service ID'),
            'customer_field' => Yii::t('app', 'Customer Field'),
            'customer_field_value' => Yii::t('app', 'Customer Field Value'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'modified_at' => Yii::t('app', 'Modified At'),
        ];
    }
   
    public function getCustomerFieldValue($customerField=null,$customerFieldValue=null){
        $value = null;
        if($customerField=='bio_waste_collection_method_id'||$customerField=='non_bio_waste_collection_method_id'||$customerField=='bio_medical_waste_collection_method')
        {
            $method = WasteCollectionMethod::find()->where(['id'=>$customerFieldValue])->one();
            if($method)
            {
                $value = $method->name;
            }

        }
        else
        {
            if($customerFieldValue==1)
            {
                $value ='Yes';
            }elseif($customerFieldValue==0)
            {
                $value ='No';
            }

        }
        return $value;
    }
     public function deleteServiceEnabler($id)
    {
        $connection = Yii::$app->db;
        $connection->createCommand()->update('service_enabler_settings', ['status' => 0], 'id=:id')->bindParam(':id', $id)->execute();

        return true;
    }
    public static function getAllQuery()
    {
      return static::find(['status'=>1]);
    }
    public function getFkService()
    {
        return $this->hasOne(Service::className(), ['id' => 'service_id']);
    }
}
