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
class PaymentCounterAccount extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'payment_counter_account';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['payment_counter_id','account_id', 'status'], 'integer'],
            [['created_at', 'modified_at'], 'safe'],
            [['payment_counter_id','account_id'], 'required'],
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
            'payment_counter_id' => 'Payment Counter',
            'account_id' => 'Account',
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
    public function getPaymentCounterAdmin($id)
    {
      $modelUsers = Account::find()->where(['id' => $id ,'status' => 1])->all();
      foreach ($modelUsers as $modelUser) {
        // $model = Account::find()->where(['id' => $modelUser->account_id,'status' => 1])->one();
        // if($model)
          return $modelUser->username;
      }
    }
    public function getPaymentCounter($id)
    {
      $modelPaymentCounters = PaymentCounter::find()->where(['id' => $id ,'status' => 1])->all();
      foreach ($modelPaymentCounters as $modelPaymentCounter) {
        // $model = Account::find()->where(['id' => $modelUser->account_id,'status' => 1])->one();
        // if($model)
          return $modelPaymentCounter->name;
      }
    }
    public function deletePaymentCounter($id)
    {
    $connection = Yii::$app->db;
    $connection->createCommand()->update('payment_counter', ['status' => 0], 'id=:id')->bindParam(':id',$id)->execute();
    return true;
  }
  public function deletePaymentCounterAccount($id)
  {
  $connection = Yii::$app->db;
  $connection->createCommand()->update('payment_counter_account', ['status' => 0], 'id=:id')->bindParam(':id',$id)->execute();
  return true;
}
}
