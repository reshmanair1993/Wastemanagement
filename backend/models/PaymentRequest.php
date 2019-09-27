<?php

namespace backend\models;
use yii\db\Expression;
use yii\behaviors\TimestampBehavior;
use Yii;

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
class PaymentRequest extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'payment_request';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['account_id_customer', 'service_request_id', 'status','is_closed','service_id','is_subscription_payment'], 'integer'],
            [['amount'], 'required'],
            [['amount'], 'number'],
            [['requested_date', 'created_at', 'modified_at','account_service_request_id'], 'safe'],
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
            'account_id_customer' => Yii::t('app', 'Account Id Customer'),
            'service_request_id' => Yii::t('app', 'Service Request ID'),
            'amount' => Yii::t('app', 'Amount'),
            'requested_date' => Yii::t('app', 'Date'),
            'created_at' => Yii::t('app', 'Created At'),
            'modified_at' => Yii::t('app', 'Modified At'),
            'status' => Yii::t('app', 'Status'),
        ];
    }
     public function getFkAccount()
    {
        return $this->hasOne(Account::className(), ['id' => 'account_id_customer']);
    }
    public function getFkPayment()
    {
        return $this->hasOne(Payment::className(), ['payment_request_id' => 'id']);
    }
    public function getFkServiceRequest()
    {
        return $this->hasOne(ServiceRequest::className(), ['id' => 'service_request_id']);
    }
    public function deleteRequest($id)
    {
       $connection = Yii::$app->db;
       $connection->createCommand()->update('payment_request', ['status' => 0], 'id=:id')->bindParam(':id',$id)->execute();
       return true;
    }
    public static function getAllQuery() {
     $query = static::find()->where(['status'=>1]);
     return $query;
   }
   public function getServiceName($id)
    {
        $name  = null;
        $modelServiceRequest =  $this->fkServiceRequest;
        if($modelServiceRequest){
            $name = $modelServiceRequest->fkService->name;  
        }
      return $name;
    }
    public function getAmountPaid($id)
    {

        $amount  = 0;
                    $modelPayment = Payment::find()->where(['payment_request_id'=>$id])->andWhere(['status'=>1])->all();
                    if($modelPayment){
                    foreach ($modelPayment as $key => $value) {
                        $amount = $amount + $value->amount;
                    }
                }
      return $amount;
    }
    public function getAllQueryPending($keyword=null, $ward=null, $lsgi=null, $supervisor=null, $gt=null, $association=null, $from=null, $to=null)
    {
        $query = PaymentRequest::find()->where(['payment_request.status'=>1])
        ->leftjoin('account','account.id=payment_request.account_id_customer')
        ->leftjoin('customer','customer.id=account.customer_id')
        ->andWhere(['customer.status'=>1])
        ->andWhere(['account.status'=>1])
        ->orderby('payment_request.id ASC')
        // ->groupBy('account.id')
        ;

        $unit = null;
        $agency = null;
       
        $district   = null;
        $modelUser  = Yii::$app->user->identity;
        $associations = Yii::$app->rbac->getAssociations($modelUser->id);
        if(isset($associations['lsgi_id']))
        {
            $lsgi = $associations['lsgi_id'];
        }
        if(isset($associations['district_id']))
        {
            $district = $associations['district_id'];
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
        if($unit&&!$ward)
        {
            $wardIds =[];
            $wards = GreenActionUnitWard::find()->where(['green_action_unit_id'=>$unit])->all();
            if($wards)
            {
                foreach ($wards as $key => $value) {
                    $wardIds[]= $value->ward_id;
                }
                $ward = $wardIds;

            }
        }
         if($association!=null)
        {
            // $query->andWhere(['ward_id'=>$ward]);
            $query->andWhere(['customer.residential_association_id'=>$association]);
        }
        if($ward!=null||$lsgi!=null||$district!=null||$keyword!=null)
        {
             // $query->leftjoin('account','account.id=payment_request.account_id_customer')
             // ->leftjoin('customer','customer.id=account.customer_id');
        if($keyword!=null)
        {
            $query->andFilterWhere(['like', 'customer.lead_person_name', $keyword]);
        }
        if($ward!=null)
        {
            // $query->andWhere(['ward_id'=>$ward]);
            $query->andWhere(['ward_id'=>$ward]);
        }
       
        if($lsgi!=null||$district!=null)
        {
            $query
            ->leftjoin('ward','ward.id=customer.ward_id')
            ->leftjoin('lsgi','lsgi.id=ward.lsgi_id');
            if($lsgi!=null)
            {
                $query->andWhere(['lsgi.id'=>$lsgi]);
            }
            if($district!=null)
        {
            $query
            ->leftjoin('lsgi_block','lsgi_block.id=lsgi.block_id')
            ->leftjoin('assembly_constituency','assembly_constituency.id=lsgi_block.assembly_constituency_id')
            ->leftjoin('district','district.id=assembly_constituency.district_id')
            ->andWhere(['district.id'=>$district]);
        }
        }
        }
        if($from!=null)
        {
            $query->andWhere(['>=', 'payment_request.requested_date', $from]);
        }
        if($to!=null)
        {
            $query->andWhere(['<=', 'payment_request.requested_date', $to]);
        }

        return $query;
    }
     public static function getAllQueryOutstandingSummary($keyword=null, $ward=null, $lsgi=null, $supervisor=null, $gt=null, $association=null, $from=null, $to=null) {
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
    public function getCount($id = null,$from=null,$to=null)
    {
       $amountRequest = 0;
        $amount = 0;
        $customers = Payment::find()
        ->leftjoin('payment_request','payment.payment_request_id=payment_request.id')
        ->leftjoin('account','account.id=payment_request.account_id_customer')
        ->leftjoin('customer','customer.id=account.customer_id')
        ->where(['customer.ward_id'=>$id])
        ->andWhere(['customer.status'=>1])
        ->andWhere(['account.status'=>1])
        ->andWhere(['payment.status'=>1])
        ->andWhere(['payment_request.status'=>1]);
        if($from!=null)
        {
            $customers->andWhere(['>=', 'payment.paid_at', $from]);
        }
        if($to!=null)
        {
            $customers->andWhere(['<=', 'payment.paid_at', $to]);
        }
        $customers=$customers->all();
       
        if ($customers)
        {
            foreach ($customers as $key => $value) {
                $amount = $amount+$value->amount;
            }
        }

         $customersList = PaymentRequest::find()
        // ->leftjoin('payment_request','payment.payment_request_id=payment_request.id')
        ->leftjoin('account','account.id=payment_request.account_id_customer')
        ->leftjoin('customer','customer.id=account.customer_id')
        ->where(['customer.ward_id'=>$id])
        ->andWhere(['customer.status'=>1])
        ->andWhere(['account.status'=>1])
        // ->andWhere(['payment.status'=>1])
        ->andWhere(['payment_request.status'=>1]);
        if($from!=null)
        {
            $customersList->andWhere(['>=', 'payment_request.created_at', $from]);
        }
        if($to!=null)
        {
            $customersList->andWhere(['<=', 'payment_request.created_at', $to]);
        }
        $customersList=$customersList->all();
        if ($customersList)
        {
            foreach ($customersList as $key => $value) {
                $amountRequest = $amountRequest+$value->amount;
            }
        }

        return $amountRequest - $amount;
    }
       public function getPaidAmountHks($id=null,$from=null,$to=null)
    {
        $query = PaymentRequest::find()->where(['payment_request.status'=>1])
        ->leftjoin('account','account.id=payment_request.account_id_customer')
        ->leftjoin('customer','customer.id=account.customer_id')
        ->andWhere(['customer.status'=>1])
        ->andWhere(['account.status'=>1])
        ->leftjoin('service','service.id=payment_request.service_id')
        ->leftjoin('green_action_unit_ward','green_action_unit_ward.ward_id=customer.ward_id')
        ->andWhere(['green_action_unit_ward.green_action_unit_id'=>$id])
        ->andWhere(['service.is_non_residential'=>1])
        ->orderby('payment_request.id ASC')
        ;

        $unit = null;
        $agency = null;
       
        $district   = null;
        $association   = null;
        $ward   = null;
        $lsgi   = null;
        $keyword   = null;
        $modelUser  = Yii::$app->user->identity;
        $associations = Yii::$app->rbac->getAssociations($modelUser->id);
        if(isset($associations['lsgi_id']))
        {
            $lsgi = $associations['lsgi_id'];
        }
        if(isset($associations['district_id']))
        {
            $district = $associations['district_id'];
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
    
        if($ward!=null)
        {
            $query->andWhere(['green_action_unit_ward.ward_id'=>$ward]);
        }

        if($from!=null)
        {
            $query->andWhere(['>=', 'payment_request.requested_date', $from]);
        }
        if($to!=null)
        {
            $query->andWhere(['<=', 'payment_request.requested_date', $to]);
        }
        $customersList=$query->all();
        $amountRequest =0;
        if ($customersList)
        {
            foreach ($customersList as $key => $value) {
                $amountRequest = $amountRequest+$value->amount;
            }
        }
        return $amountRequest;
    }
}
