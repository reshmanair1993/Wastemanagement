<?php

namespace backend\models;

use Yii;
use yii\db\Expression;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\data\ActiveDataProvider;
/**
 * This is the model class for table "service_status".
 *
 * @property int $id
 * @property int $service_id
 * @property int $account_id
 * @property string $remark
 * @property int $remark_status 1.Completed 2. Not Completed 3. Deligated
 * @property string $created_at
 * @property string $modified_at
 */
class CustomerSelfService extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'customer_self_service';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['account_id'], 'required'],
            [['created_at', 'modified_at','qty','lat','lng'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'created_at' => Yii::t('app', 'Created At'),
            'modified_at' => Yii::t('app', 'Modified At'),
        ];
    }
    public function behaviors()
    {
        return [
            [

                'class'              => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'modified_at',
                'value'              => new Expression('NOW()')
                // if you're using datetime instead of UNIX timestamp:
                // 'value' => new Expression('NOW()'),
            ]
        ];
    }
    public function search($params,$keyword=null,$service=null,$from=null,$to=null,$mrc=null)
    {
        $from_date =$from?\Yii::$app->formatter->asDatetime($from, "php:Y-m-d 00:00:00"):'';
       $to_date = $to?\Yii::$app->formatter->asDatetime($to, "php:Y-m-d 23:00:00"):'';
        $query = CustomerSelfService::find()->where(['customer_self_service.status'=>1])
        ->leftJoin('service','service.id=customer_self_service.service_id')
        ->orderby('customer_self_service.id DESC');
        if($keyword)
        {
            $query->leftjoin('account','account.id=customer_self_service.account_id')
            ->leftjoin('customer','customer.id=account.customer_id')
            ->andFilterWhere(['like', 'customer.lead_person_name', $keyword]);
        }
        if($service)
        {
            $query
            ->andWhere(['service.id'=>$service]);
        }
        if($mrc)
        {
            $query->leftjoin('mrc','mrc.id=customer_self_service.mrc_id')
                    ->andWhere(['mrc.id'=>$mrc])
                    ->andWhere(['mrc.status'=>1]);
        }
        
      if($from_date){
        $query->andWhere(['>=', 'customer_self_service.created_at',$from_date]);
      }
      if($to_date){
        $query->andWhere(['<=', 'customer_self_service.created_at',$to_date]);
      }
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
          'pageSize' => 50, 
        ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        return $dataProvider;
    }
    public function deleteSelfService($id)
    {
       $connection = Yii::$app->db;
       $connection->createCommand()->update('customer_self_service', ['status' => 0], 'id=:id')->bindParam(':id',$id)->execute();
       return true;
    }
    public function getFkService()
        {
                return $this->hasOne(Service::className(), ['id' => 'service_id']);
        }
    public function getFkAccount()
        {
                return $this->hasOne(Account::className(), ['id' => 'account_id']);
        }
    public function getFkMrc()
        {
                return $this->hasOne(Mrc::className(), ['id' => 'mrc_id']);
        }
        public function getCustomer($id)
    {
        // print_r($id);die();
        $name  = null;
        $account = Account::find()->where(['id'=>$id])->one();
        if($account)
        {
        $customer =  Customer::find()->where(['id'=> $account->customer_id])->one();
        if($customer){
            $name = $customer->lead_person_name; 
            return $name; 
        }  
        }
        
      return $name;
    }
}
