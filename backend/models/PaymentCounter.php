<?php
namespace backend\models;

use Yii;
use yii\db\Expression;
use yii\behaviors\TimestampBehavior;
use yii\data\ActiveDataProvider;
/**
 * This is the model class for table "payment_counter".
 *
 * @property int $id
 * @property string $name
 * @property int $lsgi_id
 * @property int $status
 * @property string $created_at
 * @property string $modified_at
 */
class PaymentCounter extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'payment_counter';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['lsgi_id', 'status'], 'integer'],
            [['created_at', 'modified_at'], 'safe'],
            [['name'], 'string', 'max' => 255],
            [['name'],'required'],
            [['name'],'unique']
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
            'id' => 'ID',
            'name' => 'Name',
            'lsgi_id' => 'Lsgi',
            'status' => 'Status',
            'created_at' => 'Created At',
            'modified_at' => 'Modified At',
        ];
    }
    public function getLsgi($id){
      $modelLsgi= Lsgi::find()->where(['id' => $id,'status'=>1])->one();
      if($modelLsgi){
        return $modelLsgi;
      }
    }
    public static function getAllQuery()
    {
      $query = static::find()->where(['status'=>1])->orderBy(['id' => SORT_DESC]);
      return $query;
    }
    public function deletePaymentCounter($id)
    {
    $connection = Yii::$app->db;
    $connection->createCommand()->update('payment_counter', ['status' => 0], 'id=:id')->bindParam(':id',$id)->execute();
    return true;
  }
  public function getPaymentCounter()
  {
      $modelUser = Yii::$app->user->identity;
      $userRole  = $modelUser->role;

      if($userRole == 'admin-lsgi'){
        $paymentCounter =  PaymentCounter::find()->where(['lsgi_id'=> $modelUser->lsgi_id, 'status'=> 1])->all();
      }
      else {
        $paymentCounter =  PaymentCounter::find()->where(['status'=> 1])->all();
      }
      return $paymentCounter;
  }
}
