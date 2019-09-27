<?php

namespace backend\models;

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
            [['service_id', 'amount'], 'required'],
            [['lsgi_id', 'service_id', 'status','use_for_per_kg_rate'], 'integer'],
            [['amount','corporation_percentage','service_provider_percentage'], 'number'],
            [['slab_name'], 'string'],
            [['created_at', 'modified_at','start_value','end_value','collection_interval','slab_id','corporation_percentage','service_provider_percentage'], 'safe'],
            [['service_id'],'new_and_unique'],
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
            'use_for_per_kg_rate' => Yii::t('app', 'Used for Per Kg Rate'),
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
    public function new_and_unique($attribute,$params)
    {
        $err  = LsgiServiceSlabFee::find()->where(['service_id'=>$this->service_id])->andWhere(['slab_name'=>$this->slab_name])->andWhere(['status'=>1])->one();
        if($err)
        $this->addError($attribute,'Slab is already taken for this service ');
    }
     public function getFkSlab()
    {
        return $this->hasOne(Slab::className(), ['id' => 'slab_id']);
    }
    public function getFkCollectionInterval()
    {
        return $this->hasOne(NonResidentialWasteCollectionInterval::className(), ['id' => 'collection_interval']);
    }
}
