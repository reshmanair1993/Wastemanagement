<?php
namespace backend\models;

use Yii;
use yii\db\Expression;
use yii\behaviors\TimestampBehavior;
use yii\data\ActiveDataProvider;
/**
 * This is the model class for table "account_service_request".
 *
 * @property int $id
 * @property int $account_id
 * @property int $service_id
 * @property int $request_type
 * @property int $status
 * @property int $is_approved
 * @property string $requested_at
 * @property string $approval_status_changed_at
 * @property int $account_id_requested_by
 * @property int $account_id_approved_by
 * @property string $created_at
 * @property string $modified_at
 */
class AccountServiceRequest extends \yii\db\ActiveRecord
{
   public $type,$residential_association_id,$estimated_qty_kg,$slab;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'account_service_request';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
             [['account_id', 'service_id', 'request_type'], 'required'],
            [['account_id', 'service_id', 'request_type', 'status', 'is_approved', 'account_id_requested_by', 'account_id_approved_by'], 'integer'],
            [['requested_at', 'approval_status_changed_at', 'created_at', 'modified_at','sub_service','service_estimate','account_id_pre_approved_by','is_pre_approved','pre_verification_remarks','pre_verification_needed','is_agreement_done','reason_for_disable','estimated_qty_kg','type','slab'], 'safe']
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
            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id'                         => Yii::t('app', 'ID'),
            'account_id'                 => Yii::t('app', 'Customer'),
            'service_id'                 => Yii::t('app', 'Service'),
            'request_type'               => Yii::t('app', 'Request Type'),
            'status'                     => Yii::t('app', 'Status'),
            'is_approved'                => Yii::t('app', 'Is Approved'),
            'requested_at'               => Yii::t('app', 'Requested At'),
            'approval_status_changed_at' => Yii::t('app', 'Approval Status Changed At'),
            'account_id_requested_by'    => Yii::t('app', 'Account Id Requested By'),
            'account_id_approved_by'     => Yii::t('app', 'Account Id Approved By'),
            'created_at'                 => Yii::t('app', 'Created At'),
            'modified_at'                => Yii::t('app', 'Modified At')
        ];
    }

    /**
     * @return mixed
     */
    public function getFkAccount()
    {
        return $this->hasOne(Account::className(), ['id' => 'account_id']);
    }

    /**
     * @return mixed
     */
    public function getFkAccountGt()
    {
        return $this->hasOne(Account::className(), ['id' => 'account_id_requested_by']);
    }

    /**
     * @return mixed
     */
    public function getFkService()
    {
        return $this->hasOne(Service::className(), ['id' => 'service_id']);
    }

    /**
     * @return mixed
     */
    public function getFkAccountApproved()
    {
        return $this->hasOne(Account::className(), ['id' => 'account_id_approved_by']);
    }

    /**
     * @return mixed
     */
    // public function toggleStatusApproved()
    // {
    //     $modelUser    = Yii::$app->user->identity;
    //     $userId       = $modelUser->id;
    //     $modelService = Service::find()->where(['id' => $this->service_id])->andWhere(['status' => 1])->one();
    //     if ($modelService)
    //     {
    //         if ($modelService->is_package == 1)
    //         {
    //             $servicesList = ServicePackageService::find()->where(['service_id' => $modelService->id])->andWhere(['status' => 1])->all();
    //             if ($servicesList)
    //             {
    //                 foreach ($servicesList as $key => $value)
    //                 {
    //                     if ($this->request_type == 1)
    //                     {
    //                         $modelAccountService             = new AccountService;
    //                         $modelAccountService->account_id = $this->account_id;
    //                         $modelAccountService->service_id = $value->service_id_service;
    //                         $modelAccountService->status     = 1;
    //                         $modelAccountService->package_id = $modelService->id;
    //                         $modelAccountService->save(false);
    //                     }
    //                     elseif ($this->request_type == 0)
    //                     {
    //                         $modelAccountService = AccountService::find()->where(['account_id' => $this->account_id])->andWhere(['service_id' => $value->service_id_service])->andWhere(['status' => 1])->one();
    //                         if ($modelAccountService)
    //                         {
    //                             $modelAccountService->status = 0;
    //                             $modelAccountService->save(false);
    //                         }
    //                     }
    //                 }
    //             }
    //         }
    //         else
    //         {
    //             if ($this->request_type == 1)
    //             {
    //                 $modelAccountService             = new AccountService;
    //                 $modelAccountService->account_id = $this->account_id;
    //                 $modelAccountService->service_id = $this->service_id;
    //                 $modelAccountService->status     = 1;
    //             }
    //             elseif ($this->request_type == 0)
    //             {
    //                 $modelAccountService = AccountService::find()->where(['account_id' => $this->account_id])->andWhere(['service_id' => $this->service_id])->andWhere(['status' => 1])->one();
    //                 if ($modelAccountService)
    //                 {
    //                     $modelAccountService->status = 0;
    //                 }
    //             }
    //             $modelAccountService->save(false);
    //         }
    //         $this->is_approved                = 1;
    //         $this->approval_status_changed_at = date('Y-m-d H:i:s');
    //         $this->account_id_approved_by     = $userId;
    //         $this->save(false);
    //     }
    // }

    /**
     * @param $id
     */
    public function deleteRequest($id)
    {
        $connection = Yii::$app->db;
        $connection->createCommand()->update('deactivation_request', ['status' => 0], 'id=:id')->bindParam(':id', $id)->execute();

        return true;
    }
     public function toggleStatusApproved()
    {
        $modelUser    = Yii::$app->user->identity;
        $userId       = $modelUser->id;
        $modelService = Service::find()->where(['id' => $this->service_id])->andWhere(['status' => 1])->one();
        if ($modelService)
        {
            if ($modelService->is_package == 1)
            {
                if ($this->request_type == 1&&($this->service_estimate||$this->sub_service))
            {
                $disableModels = AccountService::find()->where(['account_id'=>$this->account_id])->andWhere(['status'=>1])->all();
                foreach ($disableModels as $disableModel) {
                    $disableModel->status = 0;
                    $disableModel->save(false);
                }
            }
                $servicesList = unserialize($this->sub_service);
                if ($servicesList)
                {
                    foreach ($servicesList as  $value)
                    {
                        if ($this->request_type == 1)
                        {
                            $modelAccountService             = new AccountService;
                            $modelAccountService->account_id = $this->account_id;
                            $modelAccountService->service_id = $value;
                            $modelAccountService->status     = 1;
                            $modelAccountService->package_id = $modelService->id;
                            $modelAccountService->save(false);
                        }
                        elseif ($this->request_type == 0)
                        {

                            $modelAccountService = AccountService::find()->where(['account_id' => $this->account_id])->andWhere(['service_id' => $value])->andWhere(['status' => 1])->one();
                            if ($modelAccountService)
                            {
                                $modelAccountService->status = 0;
                                $modelAccountService->save(false);
                            }
                        }
                    }
                }
                else
                {
                    $servicesList = ServicePackageService::find()->where(['service_id' => $modelService->id])->andWhere(['status' => 1])->all();
                     if ($servicesList)
                    {
                    foreach ($servicesList as $key => $value)
                    {
                      if ($this->request_type == 0)
                        {
                            $modelAccountService = AccountService::find()->where(['account_id' => $this->account_id])->andWhere(['service_id' => $value->service_id_service])->andWhere(['status' => 1])->one();
                            if ($modelAccountService)
                            {
                                $modelAccountService->status = 0;
                                $modelAccountService->save(false);
                            }
                        }  
                    }
                }

                }
            }
            else
            {
                if ($this->request_type == 1)
                {
                    $modelAccountService             = new AccountService;
                    $modelAccountService->account_id = $this->account_id;
                    $modelAccountService->service_id = $this->service_id;
                    $modelAccountService->status     = 1;
                }
                elseif ($this->request_type == 0)
                {
                    $modelAccountService = AccountService::find()->where(['account_id' => $this->account_id])->andWhere(['service_id' => $this->service_id])->andWhere(['status' => 1])->one();
                    if ($modelAccountService)
                    {
                        $modelAccountService->status = 0;
                    }
                }
                $modelAccountService->save(false);
            }
        }
        elseif($this->sub_service&&!$this->service_estimate)
        {
            $servicesList = unserialize($this->sub_service);
                if ($servicesList)
                {
                    foreach ($servicesList as  $value)
                    {
                        if ($this->request_type == 1)
                        {
                            $modelAccountService             = new AccountService;
                            $modelAccountService->account_id = $this->account_id;
                            $modelAccountService->service_id = $value;
                            $modelAccountService->status     = 1;
                            $modelAccountService->save(false);
                        }
                        elseif ($this->request_type == 0)
                        {

                            $modelAccountService = AccountService::find()->where(['account_id' => $this->account_id])->andWhere(['service_id' => $value])->andWhere(['status' => 1])->one();
                            if ($modelAccountService)
                            {
                                $modelAccountService->status = 0;
                                $modelAccountService->save(false);
                            }
                        }
                    }
                }
        }
        elseif($this->service_estimate)
        {
             $serviceEstimateList = unserialize($this->service_estimate);
                if ($serviceEstimateList)
                {
                    foreach ($serviceEstimateList as  $key => $value)
                    {
                        if ($this->request_type == 1)
                        {
                            $modelAccountService             = new AccountService;
                            $modelAccountService->account_id = $this->account_id;
                            $modelAccountService->service_id = $value['id'];
                            $modelAccountService->estimated_qty_kg = $value['estimated_qty_kg'];
                            $modelAccountService->status     = 1;
                            // $modelAccountService->package_id = $modelService->id;
                            $modelAccountService->save(false);
                        }
                        elseif ($this->request_type == 0)
                        {

                            $modelAccountService = AccountService::find()->where(['account_id' => $this->account_id])->andWhere(['service_id' => $value['id']])->andWhere(['estimated_qty_kg' => $value['estimated_qty_kg']])->andWhere(['status' => 1])->one();
                            if ($modelAccountService)
                            {
                                $modelAccountService->status = 0;
                                $modelAccountService->save(false);
                            }
                        }
                    }
                }
        }
            $this->is_approved                = 1;
            $this->approval_status_changed_at = date('Y-m-d H:i:s');
            $this->account_id_approved_by     = $userId;
            if($this->save(false))
            {
                if($this->request_type == 1){
                $modelAccount = Account::find()->where(['status'=>1])->andWhere(['id'=>$this->account_id])->one();
                if($modelAccount)
                {
                    $role = $modelAccount->role;
                    if($role=='customer')
                    {
                        $modelCustomer = Customer::find()->where(['id'=>$modelAccount->customer_id])->andWhere(['status'=>1])->one();
                        if($modelCustomer)
                        {
                $authKey = Yii::$app->params['authKeyMsg'];
                 $phone = $modelCustomer->lead_person_phone;
                 // $phone = '9847640775';
                // $content = "Request for service approved.\n
                $username = $modelAccount->username;
                $modelAccount->password_hash = Yii::$app->security->generatePasswordHash($username);
                $modelAccount->save(false);
                $password = $modelAccount->username;
                //    Get started:https://play.google.com/store/apps/details?id=com.trois.user.greenapp ";
                $content ="Welcome to Green Trivandrum,smart waste management initiative of Thiruvananthapuram Municipal Corporation. Your Customer ID is ". $username." and password is ".$password. ".You can login using customer id or registered mobile number. You can download Green Trivandrum app from play store. https://play.google.com/store/apps/details?id=com.tvm.user.greenapp";
                   $key = 'account_id';
                   $countryCode = '91';
                   $senderId = 'WMSMGMT';
                   Yii::$app->message->sendSMS($authKey,"WMSMGMT","91",$phone,$content);
                }
                }
                }

            }
        }
    }
    public function getCustomer($id)
    {
         print_r($id);die();
        $customer =  Customer::find()
        ->leftjoin('account','account.customer_id=customer.id')
        ->leftjoin('account_authority','account_authority.account_id_customer=account.id')
        ->where(['status'=> 1])
        ->andWhere(['account_authority.account_id_supervisor'=>$id])
        ->all();
        print_r($customer);die();
        return $customer;
    }
    public function getServices($subServices)
    {
        $name =null;
        $servicesList = unserialize($subServices);
                if ($servicesList)
                {
                    foreach ($servicesList as  $value)
                    {
                        $modelService = Service::find()->where(['id'=>$value])->andWhere(['status'=>1])->one();
                        if($modelService)
                        {
                            $name = $name.','.$modelService->name;
                        }
                    }
                }
                return trim($name,",");
    }
    public function getRequestedDetails($id)
    {
        $name = '';
        $modelAccount = Account::find()->where(['status'=>1])->andWhere(['id'=>$id])->one();
        if($modelAccount)
        {
            $role = $modelAccount->role;
            if($role=='customer')
            {
                $modelCustomer = Customer::find()->where(['id'=>$modelAccount->customer_id])->andWhere(['status'=>1])->one();
                if($modelCustomer)
                {
                    $name = $modelCustomer->lead_person_name;
                }
            }else
            {
                $modelPerson = Person::find()->where(['id'=>$modelAccount->person_id])->andWhere(['status'=>1])->one();
                if($modelPerson)
                {
                    $name = $modelPerson->first_name;
                }
            }
        }
        return $name;
    }
    public function toggleStatusFirstApproved()
    {
        $modelUser = Yii::$app->user->identity;
        $userId    = $modelUser->id;
        $this->is_pre_approved                = 1;
        $this->approval_status_changed_at = date('Y-m-d H:i:s');
        $this->account_id_pre_approved_by     = $userId;
        $this->save(false);

        return $this->is_pre_approved;
    }
    public function toggleStatusApprovedAgreement()
    {
        $modelUser = Yii::$app->user->identity;
        $userId    = $modelUser->id;
        $this->is_agreement_done                = 1;
            if($this->save(false))
            {
                $number = rand (1000,9999);               
                $modelAccount = Account::find()->where(['status'=>1])->andWhere(['id'=>$this->account_id])->one();
                if($modelAccount)
                {
                    $role = $modelAccount->role;
                    if($role=='customer')
                    {
                        $modelCustomer = Customer::find()->where(['id'=>$modelAccount->customer_id])->andWhere(['status'=>1])->one();
                        if($modelCustomer)
                        {
                            $modelCustomer->service_secret_otp = $number;
                            $modelCustomer->save(false);
                $authKey = Yii::$app->params['authKeyMsg'];
                 $phone = $modelCustomer->lead_person_phone;
                 // $phone = '9847640775';
                $content = "Request for service approved.Username : $modelAccount->username.Code : $number.Get started:https://play.google.com/store/apps/details?id=com.trois.user.greenapp ";
                   $key = 'account_id';
                   $countryCode = '91';
                   $senderId = 'WMSMGMT';
                   // Yii::$app->message->sendSMS($authKey,"WMSMGMT","91",$phone,$content);
                }
                }
                }

            }

        return $this->is_agreement_done;
    }
    public function toggleStatusFirstDisApproved()
    {
        $modelUser = Yii::$app->user->identity;
        $userId    = $modelUser->id;
        // // $this->is_pre_approved                = -1;
        // // $this->approval_status_changed_at = date('Y-m-d H:i:s');
        // // $this->account_id_pre_approved_by     = $userId;

        // $this->save(false);

        // return $this->is_pre_approved;
        $this->status                = 0;
        // $this->approval_status_changed_at = date('Y-m-d H:i:s');
        // $this->account_id_approved_by     = $userId;
        $this->save(false);

        return $this->status;
    }
    public function toggleStatusDisApprovedNonResidential()
    {
        $modelUser = Yii::$app->user->identity;
        $userId    = $modelUser->id;
        $this->status                = 0;
        // $this->approval_status_changed_at = date('Y-m-d H:i:s');
        // $this->account_id_approved_by     = $userId;
        $this->save(false);

        return $this->status;
    }
    public function toggleStatusApprovedNonResidential()
    {
         $advanceAmount = 0;
        $modelUser    = Yii::$app->user->identity;
        $userId       = $modelUser->id;
        if($this->service_estimate)
        {
             $serviceEstimateList = unserialize($this->service_estimate);

               foreach ($serviceEstimateList as $key => $value) {
          if($value['slab']==null||$value['slab']==0&&$value['estimated_qty_kg']!=null){
          $modelLsgiServiceSlabFee = LsgiServiceSlabFee::find()->where(['service_id'=>$value['id']])
          // ->andWhere(['collection_interval'=>$value['collection_interval']])
          ->andWhere(['is','slab_id',null])->andWhere(['status'=>1])->one();
        }
        else
        {
           $modelLsgiServiceSlabFee = LsgiServiceSlabFee::find()
              ->where(['lsgi_service_slab_fee.collection_interval'=>$value['collection_interval']])
              ->andWhere(['<','lsgi_service_slab_fee.start_value',$value['estimated_qty_kg']])
              ->andWhere(['>','lsgi_service_slab_fee.end_value',$value['estimated_qty_kg']])
              ->andWhere(['lsgi_service_slab_fee.service_id'=>$value['id']])
              ->andWhere(['lsgi_service_slab_fee.slab_id'=>$value['slab']])
              ->one();
        }
          if($modelLsgiServiceSlabFee)
          {
            if($modelLsgiServiceSlabFee->use_for_per_kg_rate==1)
            {
              $advanceAmount =  $value['estimated_qty_kg']*$modelLsgiServiceSlabFee->amount*45;
            }
            else
            {
              $advanceAmount = $modelLsgiServiceSlabFee->amount*1.5;
            }
             if($value['type']==1){
            $modelPaymentRequest = new PaymentRequest;
            $modelPaymentRequest->account_id_customer = $this->account_id;
            $modelPaymentRequest->service_id = $value['id'];
            $modelPaymentRequest->amount = $advanceAmount;
            $modelPaymentRequest->requested_date = date('Y-m-d');
            $modelPaymentRequest->account_service_request_id = $this->id;
            $modelPaymentRequest->save(false);
          }
          else
          {
            $modelPaymentRequest = PaymentRequest::find()->where(['service_id'=>$value['id']])->andWhere(['status'=>1])->andWhere(['is_closed'=>0])->andWhere(['account_id_customer'=>$this->account_id])->orderby('id DESC')->one();
            $modelPaymentRequest->status = 0;
           $modelPaymentRequest->save(false);
          }
        }
        }
                if ($serviceEstimateList)
                {
                    // print_r($serviceEstimateList);die();
                    foreach ($serviceEstimateList as  $key => $value)
                    {
                        if ($value['type'] == 1)
                        {
                            $modelAccountService             = new AccountService;
                            $modelAccountService->account_id = $this->account_id;
                            $modelAccountService->service_id = $value['id'];
                            $modelAccountService->estimated_qty_kg = $value['estimated_qty_kg'];
                            $modelAccountService->collection_interval = $value['collection_interval'];
                            $modelAccountService->slab_id = isset($value['slab'])?$value['slab']:'';
                            $modelAccountService->status     = 1;
                            $modelAccountService->save(false);
                        }
                        elseif ($value['type'] == 0 &&$value['estimated_qty_kg'])
                        {

                            $modelAccountService = AccountService::find()->where(['account_id' => $this->account_id])->andWhere(['service_id' => $value['id']])->andWhere(['estimated_qty_kg' => $value['estimated_qty_kg']])->andWhere(['status' => 1])->one();
                            if ($modelAccountService)
                            {
                                $modelAccountService->status = 0;
                                $modelAccountService->save(false);
                            }
                        }
                    }
                }
        }
            $this->is_approved                = 1;
            $this->approval_status_changed_at = date('Y-m-d H:i:s');
            $this->account_id_approved_by     = $userId;
            if($this->save(false))
            {
                $number = rand (1000,9999);
                 
                $modelAccount = Account::find()->where(['status'=>1])->andWhere(['id'=>$this->account_id])->one();
                if($modelAccount)
                {
                    $role = $modelAccount->role;
                    if($role=='customer')
                    {
                        $modelCustomer = Customer::find()->where(['id'=>$modelAccount->customer_id])->andWhere(['status'=>1])->one();
                        if($modelCustomer)
                        {
                            $modelCustomer->service_secret_otp = $number;
                            $modelCustomer->save(false);
                $authKey = Yii::$app->params['authKeyMsg'];
                 $phone = $modelCustomer->lead_person_phone;
                // $phone = '9847640775';
                // $content = "Request for service approved.Username : $modelAccount->username.Code : $number.Get started:https://play.google.com/store/apps/details?id=com.trois.user.greenapp ";
                 $content = "Dear customer;
Your application for services has been approved
The process of agreement will be initiated soon, Kindly do the payment for services .Code : $number";
                   $key = 'account_id';
                   $countryCode = '91';
                   $senderId = 'WMSMGMT';
                   // Yii::$app->message->sendSMS($authKey,"WMSMGMT","91",$phone,$content);
                   // Yii::$app->message->sendMessage($key,$this->account_id,$content);
$value = $this->account_id;
$modelPushMessage = new PushMessage;
$modelPushMessage->account_id = $value;
$modelPushMessage->message = 'Request for service approved';
$modelPushMessage->save(false);
// $result = Yii::$app->message->sendMessage($key,$value,$modelPushMessage->message);
                }
                }
                }

            }
    }
    public function searchNonResidential($params,$keyword=null,$ward=null,$lsgi=null,$district=null,$door=null,$surveyor=null,$from= null,$to=null,$customerId=null,$code=null,$association=null,$no_association=null,$building_type=null,$qrcode=null)
    {
        // print_r($qrcode);die();
       $unit = null;
        $agency = null;
        $supervisor = null;
        $gt   = null;
        $modelUser  = Yii::$app->user->identity;
        $userRole  = $modelUser->role;
        $associations = Yii::$app->rbac->getAssociations($modelUser->id);
        // print_r($associations);die();
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
        if($userRole=='supervisor'&&isset($modelUser->id))
        {
            $supervisor = $modelUser->id;
        }
        $query = Customer::find()
        // ->select('customer.id as id,customer.lead_person_name as lead_person_name,customer.created_at as created_at')
       // ->leftjoin('account','account.customer_id=customer.id')
       // ->leftjoin('account_service_request','account_service_request.account_id=account.id')
       ->where(['customer.status'=>1])
       ->andWhere(['!=','customer.building_type_id',1])
       // ->andWhere(['is','account_service_request.account_id',null])
        ->orderby('customer.id DESC');
        if($keyword!=null)
        {
            $query->andFilterWhere(['like', 'lead_person_name', $keyword]);
        }
        if($ward!=null)
        {
             $query->andWhere(['customer.ward_id'=>$ward]);
        }
         if($qrcode!=null)
        {
             $query->leftjoin('qr_code','qr_code.id=customer.qr_code_id')
             ->andWhere(['qr_code.value'=>$qrcode]);
        }
        if($customerId!=null||$gt!=null||$userRole=='supervisor'&&isset($modelUser->id))
        {
            $query->leftjoin('account','account.customer_id=customer.id')
            ->andWhere(['account.status'=>1]);
            if($customerId!=null){
                $query->andWhere(['account.id'=>$customerId]);
                }
                if($gt!=null||$supervisor!=null)
                {
                    $query->leftJoin('account_authority','account_authority.account_id_customer=account.id')
                    ->andWhere(['account_authority.status'=>1]);
                    if($gt!=null){
                    $query->andWhere(['account_authority.account_id_gt'=>$gt]);
                }
                if($supervisor!=null){
                    $query->andWhere(['account_authority.account_id_supervisor'=>$supervisor]);
                }
                }
                
        }
        if($surveyor!=null)
        {
            $query->andWhere(['creator_account_id'=>$surveyor]);
        }
        if($door!=null)
        {
            $query->andWhere(['door_status'=>$door]);
        }
        if($building_type)
        {
            $query->andWhere(['customer.building_type_id'=>$building_type]);
        }
        if($unit!=null)
        {
            $modelUnit = GreenActionUnit::find()->where(['id'=>$unit])->andWhere(['status'=>1])->one();
            // print_r($modelUnit);die();
            if($modelUnit)
            {
                $category = $modelUnit->residence_category_id;
            }
            else
            {
                $category = null;
            }

            $query->leftjoin('green_action_unit_ward','green_action_unit_ward.ward_id=customer.ward_id')
            ->leftjoin('green_action_unit','green_action_unit.id=green_action_unit_ward.green_action_unit_id')
            ->leftjoin('building_type','building_type.id=customer.building_type_id')
            ->andWhere(['building_type.residence_category_id'=>$category])
            ->andWhere(['green_action_unit_ward.green_action_unit_id'=>$unit])
            ->andWhere(['green_action_unit.id'=>$unit])
            ->groupby('customer.id');
        }
        if($agency)
        {

            $query->leftjoin('survey_agency_ward','survey_agency_ward.ward_id=customer.ward_id')
            ->andWhere(['survey_agency_ward.survey_agency_id'=>$agency]);
        }
        if($district!=null||$lsgi!=null)
        {
            $query->leftjoin('ward','ward.id=customer.ward_id')
            ->leftjoin('lsgi','lsgi.id=ward.lsgi_id');
            if($lsgi!=null)
            {
                $query-> andWhere(['lsgi.id'=>$lsgi]);
            }
            if($district!=null){
            $query->leftjoin('lsgi_block','lsgi_block.id=lsgi.block_id')
            ->leftjoin('assembly_constituency','assembly_constituency.id=lsgi_block.assembly_constituency_id')
            ->leftjoin('district','district.id=assembly_constituency.district_id')
            ->andWhere(['district.id'=>$district]);
        }
        }
        if($from!=null)
        {
            $query->andWhere(['>=', 'customer.created_at', $from]);
        }
        if($to!=null)
        {
            $query->andWhere(['<=', 'customer.created_at', $to]);
        }
        if($association>0)
        {
            $query->andWhere(['residential_association_id'=>$association]);
        }
        if($association==-1)
        {
            $query->andWhere(['residential_association_id'=>null]);
        }

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        return $dataProvider;
    }
    public function getImage($id)
    {
      $modelImage = Image::find()->where(['id' => $id,'status'=>1])->one();
      if($modelImage)
        return $modelImage;
    }

}
