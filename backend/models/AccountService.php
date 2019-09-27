<?php

namespace backend\models;

use Yii;
use yii\db\Expression;
use yii\behaviors\TimestampBehavior;
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
  public $amount,$service,$pre_verification_remarks,$reason_for_disable;
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
            [['account_id', 'service_id', 'status','package_id','estimated_qty_kg','slab_id'], 'integer'],
            [['created_at', 'modified_at','collection_interval'], 'safe'],
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
            'account_id' => Yii::t('app', 'Account ID'),
            'service_id' => Yii::t('app', 'Service ID'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'modified_at' => Yii::t('app', 'Modified At'),
        ];
    }
    public static function getAllQuery() {
     $query = static::find()->where(['account_service.status'=>1]);
     return $query;
   }
    public function getServiceName($id)
    {
        $name  = null;
        $modelService =  $this->fkService;
        if($modelService){
            $name = $modelService->name;  
        }
      return $name;
    }
     public function getFkService()
    {
        return $this->hasOne(Service::className(), ['id' => 'service_id']);
    }
    public function getFkAccount()
    {
        return $this->hasOne(Account::className(), ['id' => 'account_id']);
    }
    public function getFkPackage()
    {

        return $this->hasOne(Service::className(), ['id' => 'package_id']);
    }
    public function getFkCollectionInterval()
    {
        return $this->hasOne(NonResidentialWasteCollectionInterval::className(), ['id' => 'collection_interval']);
    }
    public function deleteService($id)
    {
        $connection = Yii::$app->db;
        $connection->createCommand()->update('account_service', ['status' => 0], 'id=:id')->bindParam(':id', $id)->execute();

        return true;
    }
    public static function getAllQuerySubscription($keyword=null, $ward=null, $lsgi=null, $supervisor=null, $gt=null, $association=null, $from=null, $to=null,$service=null)
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
        $query = AccountService::find()
        ->leftjoin('account','account.id=account_service.account_id')
        ->leftjoin('customer','customer.id=account.customer_id')
        ->where(['account_service.status'=>1])
        ->andWhere(['account.status'=>1])
        ->andWhere(['customer.status'=>1])
        ->andWhere(['>','account_service.package_id',1])
        ->orderby('account_service.id ASC')
       ->groupby('account_service.account_id,account_service.package_id')
        ;
        if($ward||$keyword||$gt!=null||$supervisor!=null||$userRole=='supervisor'&&isset($modelUser->id)||$association!=null||$lsgi||$unit)
        {
           
            if($ward||$keyword||$association||$lsgi||$unit)
            {
        
            if($ward)
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
            ->leftjoin('lsgi','lsgi.id=ward.lsgi_id');
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
            $query->andWhere(['>=', 'account_service.created_at', $from]);
        }
        if($to!=null)
        {
            $query->andWhere(['<=', 'account_service.created_at', $to]);
        }
        if($service)
        {
          $query->andWhere(['account_service.package_id'=>$service]);
        }
        return $query;
    }
    public function getGt($customerId)
    {
      $gtName = null;
            $modelUser  = Yii::$app->user->identity;
            $supervisor = $modelUser->id;
            $modelAccountAuthority = AccountAuthority::find()->where(['account_id_customer'=>$customerId])->andWhere(['status'=>1])->one();
            if($modelAccountAuthority&&$modelAccountAuthority->account_id_gt){
              $modelAccount =  Account::find()->where(['id'=>$modelAccountAuthority->account_id_gt])->andWhere(['status'=>1])->one();
              if($modelAccount)
              {
                $gt =  Person::find()->where(['id'=>$modelAccount->person_id])->andWhere(['status'=>1])->one();
                if($gt)
                {
                  $gtName = $gt->first_name;
                }
              }
            }
            return $gtName;
  }
  public static function getAllQuerySubscriptionSummary($keyword=null, $ward=null, $lsgi=null, $supervisor=null, $gt=null, $association=null, $from=null, $to=null) {
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
   public function getCount($id = null,$from=null,$to=null,$service_id)
    {
        $count  = 0;
        $customers =AccountService::find()
        ->leftjoin('account','account.id=account_service.account_id')
        ->leftjoin('customer','customer.id=account.customer_id')
        ->leftjoin('ward','ward.id=customer.ward_id')
        ->where(['customer.status'=>1])
        ->andWhere(['account.status'=>1])
        ->andWhere(['ward.status'=>1])
        ->andWhere(['account_service.status'=>1])
        ->andWhere(['customer.ward_id'=>$id])
        ->andWhere(['account_service.package_id'=>$service_id])
        ->groupby('account_service.account_id,account_service.package_id')
        ;
        if($from!=null)
        {
            $customers->andWhere(['>=', 'account_service.created_at', $from]);
        }
        if($to!=null)
        {
            $customers->andWhere(['<=', 'account_service.created_at', $to]);
        }
        $customers=$customers->all();
        if ($customers)
        {
            $count = count($customers);
        }

        return $count;
    }
    public function getCountDisabled($id = null,$from=null,$to=null)
    {
        $count  = 0;
        $customers =AccountService::find()
        ->leftjoin('account','account.id=account_service.account_id')
        ->leftjoin('customer','customer.id=account.customer_id')
        ->where(['customer.status'=>1])
        ->andWhere(['account.status'=>1])
        ->andWhere(['account_service.status'=>0])
        ->andWhere(['customer.ward_id'=>$id])
        ->andWhere(['>','account_service.package_id',0])
        ->groupby('account_service.account_id','account_service.package_id','account_service.service_id')
        ;
        if($from!=null)
        {
            $customers->andWhere(['>=', 'account_service.created_at', $from]);
        }
        if($to!=null)
        {
            $customers->andWhere(['<=', 'account_service.created_at', $to]);
        }
        $customers=$customers->all();
        if ($customers)
        {
            $count = count($customers);
        }

        return $count;
    }
      public static function getAllQuerySubscriptionDisabled($keyword=null, $ward=null, $lsgi=null, $supervisor=null, $gt=null, $association=null, $from=null, $to=null,$service=null)
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
        $query = AccountService::find()
        ->where(['account_service.status'=>0])
        ->andWhere(['>','account_service.package_id',1])
        ->orderby('account_service.id ASC')
        ->groupby('account_service.account_id');
        if($ward||$keyword||$gt!=null||$supervisor!=null||$userRole=='supervisor'&&isset($modelUser->id)||$association!=null||$lsgi||$unit)
        {
            $query->leftjoin('account','account.id=account_service.account_id');
            if($ward||$keyword||$association||$lsgi||$unit)
            {
                $query->leftjoin('customer','customer.id=account.customer_id');
            if($ward)
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
            ->leftjoin('lsgi','lsgi.id=ward.lsgi_id');
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
            $query->andWhere(['>=', 'account_service.created_at', $from]);
        }
        if($to!=null)
        {
            $query->andWhere(['<=', 'account_service.created_at', $to]);
        }
        if($service)
        {
          $query->andWhere(['account_service.package_id'=>$service]);
        }
        return $query;
    }

}
