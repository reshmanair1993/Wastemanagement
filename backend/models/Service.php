<?php

namespace backend\models;

use Yii;
use yii\db\Expression;
use yii\behaviors\TimestampBehavior;
use yii\data\ActiveDataProvider;
/**
 * This is the model class for table "service".
 *
 * @property int $id
 * @property string $name
 * @property int $status
 * @property string $created_at
 * @property string $modified_at
 */
class Service extends \yii\db\ActiveRecord
{
  public $services;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'service';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['status','is_package','is_quantity_entering_enabled','is_non_residential','customer_type','is_residential','is_cityzen'], 'integer'],
            [['created_at', 'modified_at','waste_collection_method','sort_order','waste_category_id','ask_waste_quality','type','image_id','is_special_service','ask_waste_quantity','services','customer_type','is_residential','is_cityzen'], 'safe'],
            [['name','name_ml'], 'string', 'max' => 500],
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
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'modified_at' => Yii::t('app', 'Modified At'),
            'name_ml' => Yii::t('app', 'Malayalam Translation'),
            'is_cityzen' => Yii::t('app', 'Is Citizen'),
        ];
    }
     public function deleteService($id)
    {
       $connection = Yii::$app->db;
       $connection->createCommand()->update('service', ['status' => 0], 'id=:id')->bindParam(':id',$id)->execute();
       return true;
    }
    public function getServices()
    {
        $service =  Service::find()->where(['status'=> 1])->all();
        return $service;
    }
    public function getServiceList()
    {
        $service =  Service::find()->where(['status'=> 1])->andWhere(['type'=>1])->andWhere(['!=','is_package',1])->all();
        return $service;
    }
    public function getServiceListQuantity()
    {
        $service =  Service::find()
        ->leftjoin('servicing_status_option','servicing_status_option.service_id=service.id')
        ->where(['service.status'=> 1])
        ->andWhere(['type'=>1])
        ->andWhere(['!=','is_package',1])
        ->andWhere(['servicing_status_option.ask_waste_quantity'=>1])
        ->all();
        return $service;
    }
    public function getServiceListQuality()
    {
        $service =  Service::find()
        ->leftjoin('servicing_status_option','servicing_status_option.service_id=service.id')
        ->where(['service.status'=> 1])
        ->andWhere(['type'=>1])
        ->andWhere(['!=','is_package',1])
        ->andWhere(['servicing_status_option.ask_waste_quality'=>1])
        ->all();
        return $service;
    }
    public function getServicesPackage()
    {
        $service =  Service::find()->where(['status'=> 1])->andWhere(['type'=>1])->andWhere(['is_package'=>1])->all();
        return $service;
    }
    public function getServicesPackageCustomer()
    {
            $modelAccount = Account::find()->where(['customer_id'=>Yii::$app->user->identity->id])->andWhere(['status'=>1])->one();
        $account_id  = $modelAccount->id;
        $query = Service::getAllQuery()->andWhere(['service.status' => 1])->andWhere(['type'=>1]);
        $modelAccountService = AccountService::find()->select('service_id,account_service.account_id')->where(['account_id' => $account_id])->andWhere(['account_service.status' => 1]);
        $serviceIdExcluded = [];
       $dataAll = $modelAccountService->all();
       foreach ($dataAll as $value) {
            $serviceIdExcluded[] = $value->service_id;

       }

       $modelAccountServicePackage = AccountService::find()->select('service_id,account_service.account_id,account_service.package_id')->where(['account_id' => $account_id])->andWhere(['account_service.status' => 1])->andWhere(['>','package_id',0]);
       $dataAll = $modelAccountServicePackage->all();
       foreach ($dataAll as $value) {
            $serviceIdExcluded[] = $value->package_id;

       }
        $dataProvider = new ActiveDataProvider([
            'query'      => $query,
            'pagination' => false

        ]);
        $models = $dataProvider->getModels();
        $newArray    = [];
        foreach ($models as $model)
        {
            $image     = null;
            $serviceId = $model->id;
            if (in_array($serviceId, $serviceIdExcluded))
            {
                continue;
            }else
            {
              $newArray[] = $serviceId;
            }
          }
           foreach ($newArray as $id => $value) {
      $post = Service::find()->where(['id'=>$value])->one();
          $out[] = ['id' => $post['id'], 'name' => $post['name']];
        }
        // $service =  Service::find()->where(['status'=> 1])->andWhere(['type'=>1])->andWhere(['is_package'=>1])->all();
        return $out;
    }
    public function getComplaints()
    {
        $service =  Service::find()->where(['status'=> 1])->andWhere(['type'=>2])->all();
        return $service;
    }
    public function getGt()
    {
        $gt =  Person::find()
                ->select('account.id as id,person.first_name as first_name')
                ->where(['account.status' => 1])
                ->leftjoin('account', 'account.person_id=person.id')
                ->andWhere(['account.role' => 'green-technician'])
                ->all();
        return $gt;

    }
    public function getProfileUrl()
  {
    $logoUrl = isset(Yii::$app->params['defaultPhoto'])?(Yii::$app->params['image_base'].Yii::$app->params['defaultPhoto']):'';
    $fkLogoUrl = $this->fkImage;
    if($fkLogoUrl){
      $logoUrl = $fkLogoUrl->fullUrl();
    }
    return $logoUrl;
  }
  public function getFkImage()
  {
    return $this->hasOne(Image::className(), ['id' => 'image_id']);
  }
  public function getCustomer($id)
    {
        $customer =  Customer::find()
        ->leftjoin('account','account.customer_id=customer.id')
        ->leftjoin('account_authority','account_authority.account_id_customer=account.id')
        ->where(['customer.status'=> 1])
        ->andWhere(['account_authority.status'=> 1])
        ->andWhere(['account.status'=> 1])
        ->andWhere(['account_authority.account_id_supervisor'=>$id])
        ->all();
      
        return $customer;
    }
    public function getAllQuery() {
        return static::find()->where(['service.status'=>1])->andWhere(['is_package'=>1]);
        // ->andWhere(['service.is_public'=>1])
    }
    public function getAllQueryItem($service=null) {
        $query = static::find()
        ->where(['service.status'=>1])
        ->andWhere(['!=','is_package',1])
        ->andWhere(['type'=>1])
        ->orderby('id DESC');;
        if($service)
        {
            $query->andWhere(['id'=>$service]);
        }
        return $query;
        
    }
    public function getCountCompleted($id = null,$from=null,$to=null,$type=null,$ward=null)
    {
        $supervisor = null;
        $unit = null;
        $agency = null;
        $lsgi = null;
        $gt   = null;
        $district   = null;
        $modelUser  = Yii::$app->user->identity;
        $userRole = $modelUser->role;
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
        $count  = 0;
        $customers = ServiceRequest::find()
        ->leftjoin('account','service_request.account_id_customer=account.id')
        ->leftjoin('customer','customer.id=account.customer_id')
        ->leftjoin('service_assignment','service_assignment.service_request_id=service_request.id')
        ->leftjoin('service','service.id=service_request.service_id')
        ->where(['service.id'=>$id])
        ->andWhere(['>','service_assignment.servicing_status_option_id',0])
        ->andWhere(['customer.status'=>1])
        ->andWhere(['service.type'=>$type])
        ->andWhere(['account.status'=>1])
        ->andWhere(['service_request.status'=>1]);
        if($userRole=='supervisor'&&isset($modelUser->id))
    {
      $supervisor = $modelUser->id;
      // $unit = $modelUser->green_action_unit_id;
    }
    if($supervisor)
    {
      $customers
      ->leftjoin('account_authority','account_authority.account_id_customer=service_request.account_id_customer')
      ->andWhere(['account_authority.account_id_supervisor'=>$modelUser->id]);
    }
        if($ward||$unit||$lsgi)
        {
            if($lsgi!=null)
            {
              $customers->leftjoin('ward','ward.id=customer.ward_id')
            ->leftjoin('lsgi','lsgi.id=ward.lsgi_id')
            -> andWhere(['lsgi.id'=>$lsgi]);
            }
            if($ward!=null)
            {
              // $query->andWhere(['customer.ward_id'=>$ward]);
              $customers->andWhere(['service_request.ward_id'=>$ward]);
            }
            if($unit!=null)
        {
            $customers->leftjoin('green_action_unit_ward','green_action_unit_ward.ward_id=customer.ward_id')
            ->andWhere(['green_action_unit_ward.green_action_unit_id'=>$unit]);
        }
            
        }
        if($from!=null)
        {
            $customers->andWhere(['>=', 'service_assignment.servicing_datetime', $from]);
        }
        if($to!=null)
        {
            $customers->andWhere(['<=', 'service_assignment.servicing_datetime', $to]);
        }
        $customers=$customers->all();
        if ($customers)
        {
            $count = count($customers);
        }

        return $count;
    }
    public function getCountPending($id = null,$from=null,$to=null,$type=null,$ward=null)
    {
        $supervisor = null;
        $unit = null;
        $agency = null;
        $lsgi = null;
        // $ward = null;
        $gt   = null;
        $district   = null;
        $modelUser  = Yii::$app->user->identity;
        $userRole = $modelUser->role;
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
        $count  = 0;
        $customers = ServiceRequest::find()
        ->leftjoin('account','service_request.account_id_customer=account.id')
        ->leftjoin('customer','customer.id=account.customer_id')
        ->leftjoin('service_assignment','service_assignment.service_request_id=service_request.id')
        ->leftjoin('service','service.id=service_request.service_id')
        ->where(['service.id'=>$id])
        ->andWhere(['service_assignment.servicing_status_option_id'=>null])
        ->andWhere(['customer.status'=>1])
        ->andWhere(['service.type'=>$type])
        ->andWhere(['account.status'=>1])
        ->andWhere(['service_request.status'=>1]);
        if($userRole=='supervisor'&&isset($modelUser->id))
    {
      $supervisor = $modelUser->id;
      // $unit = $modelUser->green_action_unit_id;
    }
    if($supervisor)
    {
      $customers
      ->leftjoin('account_authority','account_authority.account_id_customer=service_request.account_id_customer')
      ->andWhere(['account_authority.account_id_supervisor'=>$modelUser->id]);
    }
        if($ward||$unit||$lsgi)
        {
            if($lsgi!=null)
            {
              $customers->leftjoin('ward','ward.id=customer.ward_id')
            ->leftjoin('lsgi','lsgi.id=ward.lsgi_id')
            -> andWhere(['lsgi.id'=>$lsgi]);
            }
            if($ward!=null)
            {
              // $query->andWhere(['customer.ward_id'=>$ward]);
              $customers->andWhere(['service_request.ward_id'=>$ward]);
            }
            if($unit!=null)
        {
            $customers->leftjoin('green_action_unit_ward','green_action_unit_ward.ward_id=customer.ward_id')
            ->andWhere(['green_action_unit_ward.green_action_unit_id'=>$unit]);
        }
            
        }
        if($from!=null)
        {
            $customers->andWhere(['>=', 'service_assignment.created_at', $from]);
        }
        if($to!=null)
        {
            $customers->andWhere(['<=', 'service_assignment.created_at', $to]);
        }
        $customers=$customers->all();
        if ($customers)
        {
            $count = count($customers);
        }

        return $count;
    }
}
