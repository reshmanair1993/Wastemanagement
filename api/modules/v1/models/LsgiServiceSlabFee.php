<?php

namespace api\modules\v1\models;

use Yii;
use yii\db\Expression;
use yii\behaviors\TimestampBehavior;
/**
 * This is the model class for table "lsgi_service_fee".
 *
 * @property int $id
 * @property int $lsgi_id
 * @property int $service_id
 * @property double $amount
 * @property int $status
 * @property string $created_at
 * @property string $modified_at
 */
class LsgiServiceSlabFee extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'lsgi_service_slab_fee';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['service_id', 'amount','slab_name'], 'required'],
            [['lsgi_id', 'service_id', 'status'], 'integer'],
            [['amount'], 'number'],
            [['slab_name'], 'string'],
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
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'lsgi_id' => Yii::t('app', 'Lsgi ID'),
            'service_id' => Yii::t('app', 'Service'),
            'amount' => Yii::t('app', 'Rate Per KG'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'modified_at' => Yii::t('app', 'Modified At'),
        ];
    }
    public static function getAllQuery() {
     $query = static::find()->where(['status'=>1]);
     // if($lsgi)
     // {
     //    $query->andWhere(['lsgi_service_fee.lsgi_id'=>$lsgi]);
     // }
     return $query;
   }
   public function getServices()
    {
        $service =  Service::find()->where(['status'=> 1])->andWhere(['type'=>1])->all();
        return $service;
    }
    
    public function getServiceName($id)
    {
        $name  = null;
        $serviceName =  Service::find()->where(['id'=> $id])->one();
        if($serviceName){
            $name = $serviceName->name;  
        }
      return $name;
    }
    
    public function deleteFee($id)
    {
       $connection = Yii::$app->db;
       $connection->createCommand()->update('lsgi_service_slab_fee', ['status' => 0], 'id=:id')->bindParam(':id',$id)->execute();
       return true;
    }
}
