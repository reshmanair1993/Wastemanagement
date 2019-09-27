<?php

namespace backend\models;

use Yii;
use yii\db\Expression;
use yii\behaviors\TimestampBehavior;
/**
 * This is the model class for table "account_fee".
 *
 * @property int $id
 * @property int $account_id_customer
 * @property int $service_request_id
 * @property double $amount_paid
 * @property double $amount_pending
 * @property string $date
 * @property string $created_at
 * @property string $modified_at
 * @property int $status
 */
class Payment extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'payment';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['account_id_gt', 'payment_request_id', 'status'], 'integer'],
            [['amount'], 'required'],
            [['amount'], 'number'],
            [['paid_at', 'created_at', 'modified_at','type'], 'safe'],
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
            'amount' => Yii::t('app', 'Amount'),
            'created_at' => Yii::t('app', 'Created At'),
            'modified_at' => Yii::t('app', 'Modified At'),
            'status' => Yii::t('app', 'Status'),
        ];
    }
     public function getFkAccount()
    {
        return $this->hasOne(Account::className(), ['id' => 'account_id_gt']);
    }
    public function getFkPaymentRequest()
    {
        return $this->hasOne(PaymentRequest::className(), ['id' => 'payment_request_id']);
    }
    public function deleteRequest($id)
    {
       $connection = Yii::$app->db;
       $connection->createCommand()->update('payment', ['status' => 0], 'id=:id')->bindParam(':id',$id)->execute();
       return true;
    }
    public static function getAllQuery() {
     $query = static::find()->where(['payment.status'=>1]);
     return $query;
   }
   public function getServiceName($id)
    {
        $name  = null;
        $modelServiceRequest =  $this->fkPaymentRequest->fkServiceRequest;
        if($modelServiceRequest){
            $name = $modelServiceRequest->fkService->name;  
        }
      return $name;
    }
    public function getTotalAmount($id)
    {

        $amount  = 0;
        $modelAccount = Account::find()->where(['customer_id'=>$id])->andWhere(['status'=>1])->one();
        if($modelAccount)
        {
            $modelRequests = PaymentRequest::find()->where(['account_id_customer'=>$modelAccount->id])->andWhere(['is_closed'=>0])->all();
                foreach ($modelRequests as $key => $value) {
                    $amount = $amount + $value->amount;
                }
        }
      return $amount;
    }
    public function getPaidAmount($id)
    {

        $amount  = 0;
        $modelAccount = Account::find()->where(['customer_id'=>$id])->andWhere(['status'=>1])->one();
        if($modelAccount)
        {
            $modelRequests = PaymentRequest::find()->where(['account_id_customer'=>$modelAccount->id])->andWhere(['is_closed'=>0])->all();
                foreach ($modelRequests as $key => $modelRequest) {
                    $modelPayment = Payment::find()->where(['payment_request_id'=>$modelRequest->id])->andWhere(['status'=>1])->all();
                    foreach ($modelPayment as $key => $value) {
                        $amount = $amount + $value->amount;
                    }
                }
        }
      return $amount;
    }

    public static function getAllQueryCollection($keyword=null, $ward=null, $lsgi=null, $supervisor=null, $gt=null, $association=null, $from=null, $to=null)
    {
         $unit = null;
        $agency = null;
        $modelUser  = Yii::$app->user->identity;
        $userRole  = $modelUser->role;
        $associations = Yii::$app->rbac->getAssociations($modelUser->id);
        if(isset($associations['lsgi_id']))
        {
            $lsgi = $associations['lsgi_id'];
        }
        if(isset($associations['district_id']))
        {
            $district = $associations['district_id'];
        }
        if(isset($associations['ward_id'])&&!$ward)
        {
            $ward = $associations['ward_id'];
            $ward = json_decode($ward);
        }
        if(isset($associations['hks_id']))
        {
            $unit = $associations['hks_id'];
        }
        if(isset($associations['gt_id']))
        {
            $gt = $associations['gt_id'];
        }
        if(isset($associations['survey_agency_id']))
        {
            $agency = $associations['survey_agency_id'];
        }
        if($userRole=='supervisor')
        {
            $supervisor = $modelUser->id;
        }
        $query = Payment::find()
        ->leftjoin('payment_request','payment.payment_request_id=payment_request.id')
        ->where(['payment.status'=>1])
        ->andWhere(['payment_request.status'=>1])
        ->orderby('payment.id ASC');
        if($ward||$keyword||$gt!=null||$supervisor!=null||$userRole=='supervisor'&&isset($modelUser->id)||$association!=null||$lsgi!=null||$unit)
        {
            $query->leftjoin('account','account.id=payment_request.account_id_customer')
            ->andWhere(['account.status'=>1])
            ;
            if($ward||$keyword||$association||$lsgi!=null||$unit)
            {
                $query->leftjoin('customer','customer.id=account.customer_id')
                ->andWhere(['customer.status'=>1]);
            if($ward!=null)
            {
                $query->andWhere(['customer.ward_id'=>$ward]);
            }
            if($association)
            {
                // print_r($association);die();
                $query->andWhere(['customer.residential_association_id'=>$association]);
            }
            if($keyword)
            {
                $query->andFilterWhere(['like', 'customer.lead_person_name', $keyword]);
            }
            if($lsgi!=null)
        {
            $query->leftjoin('ward','ward.id=customer.ward_id')
            ->leftjoin('lsgi','lsgi.id=ward.lsgi_id')
            ->andWhere(['ward.status'=>1])
            ->andWhere(['lsgi.status'=>1])
            ;
            if($lsgi!=null)
            {
                $query-> andWhere(['lsgi.id'=>$lsgi]);
            }
        }
        if($unit!=null)
        {

            $query->leftjoin('green_action_unit_ward','green_action_unit_ward.ward_id=customer.ward_id')
            ->andWhere(['green_action_unit_ward.green_action_unit_id'=>$unit]);
        }
        }
        if($gt!=null||$supervisor!=null)
        {
            $query->leftJoin('account_authority','account_authority.account_id_customer=account.id')
            ->andWhere(['account_authority.status'=>1]);
            if($gt!=null)
            {
            $query->andWhere(['account_authority.account_id_gt'=>$gt]);
            }
            if($supervisor!=null)
            {
            $query->andWhere(['account_authority.account_id_supervisor'=>$supervisor]);
            }
        }
         
        }
        
        if($from!=null)
        {
            $query->andWhere(['>=', 'payment.paid_at', $from]);
        }
        if($to!=null)
        {
            $query->andWhere(['<=', 'payment.paid_at', $to]);
        }
        return $query;
    }
     public function getCount($id = null,$from=null,$to=null)
    {
        $count  = 0;
        $customers = Payment::find()
        ->leftjoin('payment_request','payment.payment_request_id=payment_request.id')
        ->leftjoin('account','account.id=payment_request.account_id_customer')
        ->leftjoin('customer','customer.id=account.customer_id')
        ->where(['customer.ward_id'=>$id])
        ->andWhere(['customer.status'=>1])
        ->andWhere(['account.status'=>1])
        ->andWhere(['payment.status'=>1])
        ->andWhere(['payment_request.status'=>1]);
       
        // $customers =Customer::find()->where(['creator_account_id'=>$id])->andWhere(['building_type_id'=>1])->andWhere(['status'=>1])->all();
        if($from!=null)
        {
            $customers->andWhere(['>=', 'payment.paid_at', $from]);
        }
        if($to!=null)
        {
            $customers->andWhere(['<=', 'payment.paid_at', $to]);
        }
        $customers=$customers->all();
        $amount = 0;
        if ($customers)
        {
            foreach ($customers as $key => $value) {
                $amount = $amount+$value->amount;
            }
        }

        return $amount;
    }
     public static function getAllQueryCollectionSummary($keyword=null, $ward=null, $lsgi=null, $supervisor=null, $gt=null, $association=null, $from=null, $to=null) {
     $query = Ward::find()->where(['ward.status'=>1]);
     $lsgi = null;
     $unit = null;
        $agency = null;
    $modelUser  = Yii::$app->user->identity;
    $userRole = $modelUser->role;
        $gt   = null;
        $associations = Yii::$app->rbac->getAssociations($modelUser->id);
        if(isset($associations['lsgi_id']))
        {
            $lsgi = $associations['lsgi_id'];
        }
        if(isset($associations['district_id']))
        {
            $district = $associations['district_id'];
        }
        if(isset($associations['ward_id'])&&!$ward)
        {
            $ward = $associations['ward_id'];
            $ward = json_decode($ward);
        }
        if(isset($associations['hks_id']))
        {
            $unit = $associations['hks_id'];
        }
        if(isset($associations['gt_id']))
        {
            $gt = $associations['gt_id'];
        }
        if(isset($associations['survey_agency_id']))
        {
            $agency = $associations['survey_agency_id'];
        }
    if($unit!=null)
        {

            $query->leftjoin('green_action_unit_ward','green_action_unit_ward.ward_id=ward.id')
            ->andWhere(['green_action_unit_ward.green_action_unit_id'=>$unit]);
        }
         if($agency)
        {

            $query->leftjoin('survey_agency_ward','survey_agency_ward.ward_id=ward.id')
            ->andWhere(['survey_agency_ward.survey_agency_id'=>$agency]);
        }
     if($ward)
     {
        $query->andWhere(['ward.id'=>$ward]);
     }
     if($lsgi)
     {
        $query->andWhere(['ward.lsgi_id'=>$lsgi]);
     }
     return $query;
   }
}
