<?php

namespace backend\models;

use Yii;
use yii\db\Expression;
use yii\behaviors\TimestampBehavior;
/**
 * This is the model class for table "waste_quality".
 *
 * @property int $id
 * @property string $name
 * @property string $created_at
 * @property string $modified_at
 * @property int $status
 */
class WasteQuality extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'waste_quality';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'name'], 'required'],
            [['id', 'status'], 'integer'],
            [['created_at', 'modified_at'], 'safe'],
            [['name'], 'string', 'max' => 255],
            [['id'], 'unique'],
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
            'created_at' => Yii::t('app', 'Created At'),
            'modified_at' => Yii::t('app', 'Modified At'),
            'status' => Yii::t('app', 'Status'),
        ];
    }
    public function deleteQuality($id)
    {
       $connection = Yii::$app->db;
       $connection->createCommand()->update('waste_quality', ['status' => 0], 'id=:id')->bindParam(':id',$id)->execute();
       return true;
    }
    public function getAllQuery($ward=null) {
        $query = static::find()->where(['waste_quality.status'=>1]);
        if($ward)
        {
          $query->leftjoin('service_assignment','service_assignment.quality=waste_quality.id')
          ->leftjoin('service_request','service_request.id=service_assignment.service_request_id')
          ->andWhere(['service_request.ward_id'=>$ward]);
        }
        return $query;
    }
    public function getCount($id = null,$from=null,$to=null)
    {
      // print_r($id);die();
        $count  = 0;
        $customersDetails = ServiceAssignment::find()
        ->leftjoin('service_request','service_request.id=service_assignment.service_request_id')
        ->leftjoin('account','service_request.account_id_customer=account.id')
        ->leftjoin('customer','customer.id=account.customer_id')
        ->leftjoin('service','service.id=service_request.service_id')
        ->where(['service_assignment.quality'=>$id])
        ->andWhere(['customer.status'=>1])
        ->andWhere(['account.status'=>1])
        ->andWhere(['service_assignment.status'=>1])
        ->andWhere(['service_request.status'=>1]);
        if($from!=null)
        {
            $customersDetails->andWhere(['>=', 'service_request.requested_datetime', $from]);
        }
        if($to!=null)
        {
            $customersDetails->andWhere(['<=', 'service_request.requested_datetime', $to]);
        }
        $customersDetails=$customersDetails->all();
        if ($customersDetails)
        {
            $count = count($customersDetails);
        }

        return $count;
    }
}
