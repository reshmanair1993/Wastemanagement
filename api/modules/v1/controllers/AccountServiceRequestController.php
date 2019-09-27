<?php
namespace api\modules\v1\controllers;

use Yii;
use yii\filters\VerbFilter;
use yii\rest\ActiveController;
use yii\filters\auth\HttpBearerAuth;
use api\modules\v1\models\AccountService;
use api\modules\v1\models\Service;
use api\modules\v1\models\Account;
use api\modules\v1\models\Customer;
use backend\models\GreenActionUnitWard;
use backend\models\GreenActionUnit;
use api\modules\v1\models\ResidenceCategory;
use api\modules\v1\models\AccountServiceRequest;
use api\modules\v1\models\ServicePackageService;
use yii\data\ActiveDataProvider;
use  api\modules\v1\models\Log;
use api\modules\v1\models\LsgiServiceSlabFee;
use api\modules\v1\models\NonResidentialWasteCollectionInterval;
use api\modules\v1\models\Slab;
class AccountServiceRequestController extends ActiveController
{
    /**
     * @var string
     */
    public $modelClass = '\api\modules\v1\models\AccountServiceRequest';

    /**
     * @return mixed
     */
    public function actions()
    {
        $actions      = parent::actions();
        $unsetActions = ['create', 'update', 'index', 'delete'];
        foreach ($unsetActions as $action)
        {
            unset($actions[$action]);
        }

        return $actions;
    }

    public function behaviors()
    {
        return [
            'verbs' => [
                'class'   => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST']
                ]
            ],
            'auth'  => [
                'class' => HttpBearerAuth::className()
            ]
        ];
    }
public function log($str) {
      $modelLog =  new Log;
      $modelLog->message = $str;
      $modelLog->save(false);
     }
    /**
     * @return mixed
     */
    public function actionRequestAccountService($id=null)
    {
        $modelUser = Yii::$app->user->identity;
        $userId    = $modelUser->id;
        $ret       = [];
        $params    = Yii::$app->request->post();

        while (true)
        {
            if (!isset($params['account_id']))
            {
                $msg   = ['Account id is mandatory'];
                $error = ['account_id' => $msg];
                $ret   = ['errors' => $error];
                break;

                return $ret;
            }
            if (!isset($params['service_id']))
            {
                $msg   = ['Service id is mandatory'];
                $error = ['service_id' => $msg];
                $ret   = ['errors' => $error];
                break;

                return $ret;
            }
            if (!isset($params['type']))
            {
                $msg   = ['Type is mandatory'];
                $error = ['type' => $msg];
                $ret   = ['errors' => $error];
                break;

                return $ret;
            }
            if ($params)
            {
                if($params['type']==1){
                $modelAccountService = AccountService::find()->where(['account_id' => $params['account_id']])->andWhere(['service_id' => $params['service_id']])->andWhere(['status' => 1])->one();
                $modelAccountServiceRequest = AccountServiceRequest::find()->where(['account_id' => $params['account_id']])->andWhere(['service_id' => $params['service_id']])->andWhere(['status' => 1])->andWhere(['is_approved' => 0])->andWhere(['request_type'=>1])->one();
            }
            elseif($params['type']==0)
            {
                $modelAccountService = AccountService::find()->where(['account_id' => $params['account_id']])->andWhere(['service_id' => $params['service_id']])->andWhere(['status' => 0])->one(); 
                $modelAccountServiceRequest = AccountServiceRequest::find()->where(['account_id' => $params['account_id']])->andWhere(['service_id' => $params['service_id']])->andWhere(['status' => 1])->andWhere(['is_approved' => 0])->andWhere(['request_type'=>0])->one();
            }
                if ($modelAccountService||$modelAccountServiceRequest&&!$id)
                {
                    $msg   = ['Request corresponding to this account is invalid'];
                    $error = ['account_id' => $msg];
                    $ret   = ['errors' => $error];
                    break;

                    return $ret;
                }
                else
                {
                   $postJson = json_encode($params);
              $this->log($postJson);
              if(!$id){
                    $modelAccountServiceRequest                          = new AccountServiceRequest;
                  }else
                  {
                    $modelAccountServiceRequest = AccountServiceRequest::find()->where(['id'=>$id])->one();
                  }
                    $modelAccountServiceRequest->account_id              = isset($params['account_id']) ? $params['account_id'] : null;
                    $modelAccountServiceRequest->service_id              = isset($params['service_id']) ? $params['service_id'] : null;
                    $modelAccountServiceRequest->request_type            = isset($params['type']) ? $params['type'] : null;
                    $modelAccountServiceRequest->requested_at            = date('Y-m-d H:i:s');
                    $modelAccountServiceRequest->account_id_requested_by = $userId;
                    if(isset($params['sub_services']))
                    {
                      $newArray = [];
                      foreach ($params['sub_services'] as $key => $value) {
                        $newArray[] =intval($value);
                      }
                      $modelAccountServiceRequest->sub_service = serialize($newArray);
                    }

                    if(isset($params['service_estimate']))
                    {
                      $serviceEstimateArray = [];
                      foreach ($params['service_estimate'] as $key => $value) {
                        $serviceEstimateArray[] =$value;
                      }
                      $modelAccountServiceRequest->service_estimate = serialize($serviceEstimateArray);
                    }
                    if($modelUser->role=='supervisor')
                    {
                       $modelService = Service::find()->where(['id' => $params['service_id']])->andWhere(['status' => 1])->one();
        if ($modelService)
        {
            if ($modelService->is_package == 1)
            {
                if ($params['type'] == 1)
            {
                $disableModels = AccountService::find()->where(['account_id'=>$params['account_id']])->andWhere(['status'=>1])->all();
                foreach ($disableModels as $disableModel) {
                    $disableModel->status = 0;
                    $disableModel->save(false);
                }
            }
                $servicesList = $params['sub_services'];
                if ($servicesList)
                {
                    foreach ($servicesList as  $value)
                    {
                        if ($params['type'] == 1)
                        {
                            $modelAccountService             = new AccountService;
                            $modelAccountService->account_id = $params['account_id'];
                            $modelAccountService->service_id = $value;
                            $modelAccountService->status     = 1;
                            $modelAccountService->package_id = $modelService->id;
                            $modelAccountService->save(false);
                        }
                        elseif ($params['type'] == 0)
                        {

                            $modelAccountService = AccountService::find()->where(['account_id' => $params['account_id']])->andWhere(['service_id' => $value])->andWhere(['status' => 1])->one();
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
                      if ($params['type'] == 0)
                        {
                            $modelAccountService = AccountService::find()->where(['account_id' => $params['account_id']])->andWhere(['service_id' => $value->service_id_service])->andWhere(['status' => 1])->one();
                            if ($modelAccountService)
                            {
                                $modelAccountService->status = 0;
                                $modelAccountService->save(false);
                            }
                        }  
                    }
                }

                }
                if($params['type']==1)
                {

                  $modelAccount = Account::find()->where(['status'=>1])->andWhere(['id'=>$params['account_id']])->one();
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
              $username = $modelAccount->username;
             $modelAccount->password_hash = Yii::$app->security->generatePasswordHash($username);
                $modelAccount->save(false);
                $password = $modelAccount->username;
                $content ="Welcome to Green Trivandrum,smart waste management initiative of Thiruvananthapuram Municipal Corporation. Your Customer ID is ". $username." and password is ".$password. ".You can login using customer id or registered mobile number. You can download Green Trivandrum app from play store. https://play.google.com/store/apps/details?id=com.tvm.user.greenapp";
                   $key = 'account_id';
                   $countryCode = '91';
                   $senderId = 'WMSMGMT';
                    // Yii::$app->message->sendSMS($authKey,"GRNTVM","91",$phone,$content);
                    // Welcome to Green Trivandrum. Your Customer ID is ". $username." and password is ".$password. ".You can login using customer id or registered mobile number.You can download Green Trivandrum app from play store.https://play.google.com/store/apps/details?id=com.tvm.user.greenapp
                   Yii::$app->message->sendSMS($authKey,"WMSMGMT","91",$phone,$content);
                  }
                    }
                  }
                }
            }
            else
            {
                if ($params['type'] == 1)
                {
                    $modelAccountService             = new AccountService;
                    $modelAccountService->account_id = $params['account_id'];
                    $modelAccountService->service_id = $params['service_id'];
                    $modelAccountService->status     = 1;
                }
                elseif ($params['type'] == 0)
                {
                    $modelAccountService = AccountService::find()->where(['account_id' => $params['account_id']])->andWhere(['service_id' => $params['service_id']])->andWhere(['status' => 1])->one();
                    if ($modelAccountService)
                    {
                        $modelAccountService->status = 0;
                    }
                }
                $modelAccountService->save(false);
            }
            
            $modelAccountServiceRequest->is_approved                = 1;
            $modelAccountServiceRequest->approval_status_changed_at = date('Y-m-d H:i:s');
            $modelAccountServiceRequest->account_id_approved_by     = $userId;
            $modelAccountServiceRequest->save(false);
        }
    }
                    $modelAccountServiceRequest->save(false);
                    $ret = [
                        'id'         => $modelAccountServiceRequest->id,
                        'service_id' => $modelAccountServiceRequest->service_id,
                        'account_id' => $modelAccountServiceRequest->account_id,
                        'type'       => $modelAccountServiceRequest->request_type,
                        'pre_approval_status'       => $modelAccountServiceRequest->is_pre_approved
                    ];
                }
            }
            break;
        }

        return $ret;
    }
    public function actionServicesForAccount($enabled=null,$account_id=null,$language=null){
         $modelUser = Yii::$app->user->identity;
      if($modelUser->role=='green-technician'||$modelUser->role=='supervisor')
        {
          $language = 'ml';
        }
       if(isset($enabled)){
       if($enabled==1)
       {
         $query = AccountService::getAllQuery()
       ->leftJoin('account','account.id=account_service.account_id')
       ->leftJoin('customer','customer.id=account.customer_id')
       ->leftJoin('building_type','building_type.id=customer.building_type_id')
       ->leftjoin('lsgi_service_fee','lsgi_service_fee.residence_category_id=building_type.residence_category_id')
       ->andWhere(['account.status'=>1])
       ->andWhere(['customer.status'=>1])
       ->andWhere(['building_type.status'=>1])
       ->andWhere(['lsgi_service_fee.status'=>1])
       ->andWhere(['<','account_service.package_id',0])
       ;
        $query->andWhere(['account_service.status'=>1]);


        if($account_id)
       {
        $query->andWhere(['account_service.account_id'=>$account_id]);
       }
       $dataProvider =  new ActiveDataProvider([
         'query' => $query,
         'pagination'=>false,

       ]);
       $models = $dataProvider->getModels();
       $ret = [];
       foreach($models as $model) {
         if($model->is_package==1){
          if($language&&$language=='ml')
          {
            $name = $model->getServiceNameMl();
          }else
          {
            $name = $model->getServiceName();
          }

         $ret[] = [
           'account_id' => $model->account_id,
           'service_id' => $model->service_id,
           'service_name' => $name,
           'has_sub_service' => 0,
         ];

       }
     }
       $modelAccountServicePackage = AccountService::find()->select('service_id,account_service.account_id,account_service.package_id')->where(['account_id' => $account_id])->andWhere(['account_service.status' => 1])->andWhere(['>','package_id',0]);
       // $dataAll = $modelAccountServicePackage->all();
       // foreach ($dataAll as $value) {
       //      $ret[] = [
       //     'account_id' => $value->account_id,
       //     'service_id' => $value->package_id,
       //    'service_name' => $value->getServicePackageName(),
       //   ];

       // }
       $dataProviderNew =  new ActiveDataProvider([
         'query' => $modelAccountServicePackage,
         'pagination'=>false,

       ]);
       $modelsNew = $dataProviderNew->getModels();
       $ret = [];
       foreach($modelsNew as $model) {
        if($language&&$language=='ml')
          {
            $name = $model->getServicePackageNameMl();
          }else
          {
            $name = $model->getServicePackageName();
          }
         $ret[] = [
           'account_id' => $model->account_id,
           'service_id' => $model->package_id,
           'service_name' => $name,
           'has_sub_service' => 1,
         ];

       }

       }
       if($enabled==0)
       {
        $query = Service::getAllQuery()->andWhere(['service.status' => 1])->andWhere(['type'=>1])->orderby('sort_order ASC');;
        $modelAccountService = AccountService::find()->select('service_id,account_service.account_id')->where(['account_id' => $account_id])->andWhere(['account_service.status' => 1]);
        $serviceIdExcluded = [];
       $dataAll = $modelAccountService->all();
       foreach ($dataAll as $value) {
            $serviceIdExcluded[] = $value->service_id;

       }

       $modelAccountServicePackage = AccountService::find()->select('service_id,account_service.account_id,account_service.package_id')->where(['account_id' => $account_id])->andWhere(['account_service.status' => 1])->andWhere(['>','package_id',0]);
       $dataAll = $modelAccountServicePackage->all();
       // print_r($dataAll);die();
       foreach ($dataAll as $value) {
            $serviceIdExcluded[] = $value->package_id;

       }
        $dataProvider = new ActiveDataProvider([
            'query'      => $query,
            'pagination' => false

        ]);
        $models = $dataProvider->getModels();
        $ret    = [];
       // print_r($serviceIdExcluded);die();
        foreach ($models as $model)
        {
            $image     = null;
            $serviceId = $model->id;
            if (in_array($serviceId, $serviceIdExcluded))
            {
                continue;
            }
            if($model->is_package==1)
            {
              $has_sub_service = 1;
            }else
            {
              $has_sub_service =0;
            }
             if($model->is_package==1){
            $ret[] = [
           'account_id' => intval($account_id),
           'service_id' => $model->id,
           'service_name' => $model->name,
           'has_sub_service' => $has_sub_service,
            ];
        }
      }
       }
   }
       $ret = array_map("unserialize", array_unique(array_map("serialize", $ret)));
       $ret = array_values($ret);
       return $ret;
     }

     public function actionSubServices($service_id=null,$account_id,$language=null){
      $serviceIdsLists =[];
      $modelAccount = Account::find()->where(['status'=>1])->andWhere(['id'=>$account_id])->one();
      if($modelAccount)
        {
        $modelCustomer = $modelAccount->fkCustomer;
        }
        $modelUser = Yii::$app->user->identity;
        if($modelUser->role=='green-technician'||$modelUser->role=='supervisor')
        {
           $hks = $modelUser->green_action_unit_id;
          if($hks){
            $modelHks = GreenActionUnit::find()->where(['id'=>$hks])->andWhere(['status'=>1])->one();
            if($hks&&$modelHks&&isset($modelHks->residence_category_id))
            {
      $modelResidenceCategory = ResidenceCategory::find()->where(['id'=>$modelHks->residence_category_id])->andWhere(['status'=>1])->one();
        if($modelResidenceCategory&&$modelResidenceCategory->has_multiple_gt==1)
        {
            $list  = GreenActionUnitWard::find()->where(['ward_id'=>$modelCustomer->ward_id])->andWhere(['status'=>1])->andWhere(['green_action_unit_id'=>$hks])->all();
            foreach ($list as $key => $value) {
              if($value->service_id)
              {
                $serviceIdList = json_decode($value->service_id);
                foreach ($serviceIdList as $key => $value) {
                  // if(in_array($value, $serviceIds)){
                   $serviceIdsLists[]  = $value;
                // }
                }
              }
            }
             $ret  = [];
        if($serviceIdsLists){
          // print_r($serviceIdsLists);die();
        foreach ($serviceIdsLists as $serviceIdsListData)
        {
           $model      = Service::find()->where(['status'=>1])->andWhere(['id'=>$serviceIdsListData])->one();
          $serviceEnabled = AccountService::find()->where(['service_id'=>$model->id])->andWhere(['account_id'=>$account_id])->andWhere(['status'=>1])->one();
          if($serviceEnabled)
          {
            $subscribed  = 1;
          }
          else
          {
            $subscribed = 0;
          }
          if($language&&$language=='ml')
          {
            $name = $model->name_ml;
          }
          else{
            $name = $model->name;
          }
            $ret[] = [
                'id'   => $model->id,
                'name' => $name,
                'is_subscribed' => $subscribed,
            ];
        }
      }
      return $ret;

        }
        else{
        $query      = ServicePackageService::getAllQuery();
        if ($service_id)
        {
            $query->leftjoin('service','service_package_service.service_id_service=service.id')
            ->andWhere(['service_package_service.service_id' => $service_id]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query

        ]);
        $models = $dataProvider->getModels();
        $ret  = [];
        foreach ($models as $model)
        {
          $serviceEnabled = AccountService::find()->where(['service_id'=>$model->service_id_service])->andWhere(['account_id'=>$account_id])->andWhere(['status'=>1])->andWhere(['package_id'=>$service_id])->one();
          if($serviceEnabled)
          {
            $subscribed  = 1;
          }
          else
          {
            $subscribed = 0;
          }
          if($language&&$language=='ml')
          {
            $name = $model->fkService->name_ml;
          }
          else{
            $name = $model->fkService->name;
          }
            $ret[] = [
                'id'   => $model->service_id_service,
                'name' => $name,
                'is_subscribed' => $subscribed,
            ];
        }
        return $ret;

    }else
                {
                    $msg   = ['Service id mandatory'];
                    $error = ['service_id' => $msg];
                    $ret   = ['errors' => $error];
                    return $ret;
                }
                 
     }


      }
    }
    else{

        $query      = ServicePackageService::getAllQuery();
        if ($service_id)
        {
            $query->leftjoin('service','service_package_service.service_id_service=service.id')
            ->andWhere(['service_package_service.service_id' => $service_id]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query

        ]);
        $models = $dataProvider->getModels();
        $ret  = [];
        foreach ($models as $model)
        {
          $serviceEnabled = AccountService::find()->where(['service_id'=>$model->service_id_service])->andWhere(['account_id'=>$account_id])->andWhere(['status'=>1])->andWhere(['package_id'=>$service_id])->one();
          if($serviceEnabled)
          {
            $subscribed  = 1;
          }
          else
          {
            $subscribed = 0;
          }
          if($language&&$language=='ml')
          {
            $name = $model->fkService->name_ml;
          }
          else{
            $name = $model->fkService->name;
          }
            $ret[] = [
                'id'   => $model->service_id_service,
                'name' => $name,
                'is_subscribed' => $subscribed,
            ];
        }
        return $ret;

    }else
                {
                    $msg   = ['Service id mandatory'];
                    $error = ['service_id' => $msg];
                    $ret   = ['errors' => $error];
                    return $ret;
                }
                 
     }

    }
    else{
      
        $query      = ServicePackageService::getAllQuery();
        if ($service_id)
        {
            $query->leftjoin('service','service_package_service.service_id_service=service.id')
            ->andWhere(['service_package_service.service_id' => $service_id]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query

        ]);
        $models = $dataProvider->getModels();
        $ret  = [];
        foreach ($models as $model)
        {
          $serviceEnabled = AccountService::find()->where(['service_id'=>$model->service_id_service])->andWhere(['account_id'=>$account_id])->andWhere(['status'=>1])->andWhere(['package_id'=>$service_id])->one();
          if($serviceEnabled)
          {
            $subscribed  = 1;
          }
          else
          {
            $subscribed = 0;
          }
          if($language&&$language=='ml')
          {
            $name = $model->fkService->name_ml;
          }
          else{
            $name = $model->fkService->name;
          }
            $ret[] = [
                'id'   => $model->service_id_service,
                'name' => $name,
                'is_subscribed' => $subscribed,
            ];
        }
        return $ret;

    }else
                {
                    $msg   = ['Service id mandatory'];
                    $error = ['service_id' => $msg];
                    $ret   = ['errors' => $error];
                    return $ret;
                }
                 
     }
  }
  //    public function actionSubServices($service_id=null,$account_id,$language=null){
  //     $serviceIdsLists =[];
  //     $modelAccount = Account::find()->where(['status'=>1])->andWhere(['id'=>$account_id])->one();
  //     if($modelAccount)
  //       {
  //       $modelCustomer = $modelAccount->fkCustomer;
  //       }
  //       $modelUser = Yii::$app->user->identity;
  //       if($modelUser->role=='green-technician'||$modelUser->role=='supervisor')
  //       {
  //         $language = 'ml';
  //          $hks = $modelUser->green_action_unit_id;
  //         if($hks){
  //           $modelHks = GreenActionUnit::find()->where(['id'=>$hks])->andWhere(['status'=>1])->one();
  //           if($hks&&$modelHks&&isset($modelHks->residence_category_id))
  //           {
  //     $modelResidenceCategory = ResidenceCategory::find()->where(['id'=>$modelHks->residence_category_id])->andWhere(['status'=>1])->one();
  //       if($modelResidenceCategory&&$modelResidenceCategory->has_multiple_gt==1)
  //       {
  //           $list  = GreenActionUnitWard::find()->where(['ward_id'=>$modelCustomer->ward_id])->andWhere(['status'=>1])->andWhere(['green_action_unit_id'=>$hks])->all();
  //           foreach ($list as $key => $value) {
  //             if($value->service_id)
  //             {
  //               $serviceIdList = json_decode($value->service_id);
  //               foreach ($serviceIdList as $key => $value) {
  //                 // if(in_array($value, $serviceIds)){
  //                  $serviceIdsLists[]  = $value;
  //               // }
  //               }
  //             }
  //           }
  //            $ret  = [];
  //       if($serviceIdsLists){
  //         // print_r($serviceIdsLists);die();
  //       foreach ($serviceIdsLists as $serviceIdsListData)
  //       {
  //          $model      = Service::find()->where(['status'=>1])->andWhere(['id'=>$serviceIdsListData])->one();
  //         $serviceEnabled = AccountService::find()->where(['service_id'=>$model->id])->andWhere(['account_id'=>$account_id])->andWhere(['status'=>1])->one();
  //         if($serviceEnabled)
  //         {
  //           $subscribed  = 1;
  //         }
  //         else
  //         {
  //           $subscribed = 0;
  //         }
  //         if($language&&$language=='ml')
  //         {
  //           $name = $model->name_ml;
  //         }
  //         else{
  //           $name = $model->name;
  //         }
  //           $ret[] = [
  //               'id'   => $model->id,
  //               'name' => $name,
  //               'is_subscribed' => $subscribed,
  //           ];
  //       }
  //     }
  //     return $ret;

  //       }
  //       else{
  //       $query      = ServicePackageService::getAllQuery();
  //       if ($service_id)
  //       {
  //           $query->leftjoin('service','service_package_service.service_id_service=service.id')
  //           ->andWhere(['service_package_service.service_id' => $service_id]);
  //       $dataProvider = new ActiveDataProvider([
  //           'query' => $query

  //       ]);
  //       $models = $dataProvider->getModels();
  //       $ret  = [];
  //       foreach ($models as $model)
  //       {
  //         $serviceEnabled = AccountService::find()->where(['service_id'=>$model->service_id_service])->andWhere(['account_id'=>$account_id])->andWhere(['status'=>1])->andWhere(['package_id'=>$service_id])->one();
  //         if($serviceEnabled)
  //         {
  //           $subscribed  = 1;
  //         }
  //         else
  //         {
  //           $subscribed = 0;
  //         }
  //         if($language&&$language=='ml')
  //         {
  //           $name = $model->fkService->name_ml;
  //         }
  //         else{
  //           $name = $model->fkService->name;
  //         }
  //           $ret[] = [
  //               'id'   => $model->service_id_service,
  //               'name' => $name,
  //               'is_subscribed' => $subscribed,
  //           ];
  //       }
  //       return $ret;

  //   }else
  //               {
  //                   $msg   = ['Service id mandatory'];
  //                   $error = ['service_id' => $msg];
  //                   $ret   = ['errors' => $error];
  //                   return $ret;
  //               }
                 
  //    }


  //     }
  //   }
  //   else{
  //       $query      = ServicePackageService::getAllQuery();
  //       if ($service_id)
  //       {
  //           $query->leftjoin('service','service_package_service.service_id_service=service.id')
  //           ->andWhere(['service_package_service.service_id' => $service_id]);
  //       $dataProvider = new ActiveDataProvider([
  //           'query' => $query

  //       ]);
  //       $models = $dataProvider->getModels();
  //       $ret  = [];
  //       foreach ($models as $model)
  //       {
  //         $serviceEnabled = AccountService::find()->where(['service_id'=>$model->service_id_service])->andWhere(['account_id'=>$account_id])->andWhere(['status'=>1])->andWhere(['package_id'=>$service_id])->one();
  //         if($serviceEnabled)
  //         {
  //           $subscribed  = 1;
  //         }
  //         else
  //         {
  //           $subscribed = 0;
  //         }
  //         if($language&&$language=='ml')
  //         {
  //           $name = $model->fkService->name_ml;
  //         }
  //         else{
  //           $name = $model->fkService->name;
  //         }
  //           $ret[] = [
  //               'id'   => $model->service_id_service,
  //               'name' => $name,
  //               'is_subscribed' => $subscribed,
  //           ];
  //       }
  //       return $ret;

  //   }else
  //               {
  //                   $msg   = ['Service id mandatory'];
  //                   $error = ['service_id' => $msg];
  //                   $ret   = ['errors' => $error];
  //                   return $ret;
  //               }
                 
  //    }

  //   }
  // }
 public function actionServiceEnablingRequest($verification_status=null,$pre_approval_status=null,$re_verification_status=null,$approval_status=null,$page = 1,
        $per_page = 30){
      $modelUser = Yii::$app->user->identity;
      $userId    = $modelUser->id;
      $query = AccountServiceRequest::find()->where(['status'=>1])
      ->andWhere(['not', ['service_estimate' => null]])
      ->andWhere(['account_id_requested_by'=>$userId]);

      if($verification_status!=null&&$verification_status==1)
      {
        $query
          ->andWhere(['not', ['is_approved' => 1]])
          ->andWhere(['not', ['is_jhi_approved' => 1]])
          ->andWhere(['pre_verification_needed'=>0])
          ;
      }elseif($verification_status!=null&&$verification_status==0)
      {
         $query->andWhere(['pre_verification_needed'=>1])
         ->andWhere(['not', ['is_approved' => 1]]);
      }
       $dataProvider =  new ActiveDataProvider([
         'query' => $query,
         'pagination' => [
                'pageSize' => $per_page,
                'page'     => $page - 1
            ]

       ]);
       $models = $dataProvider->getModels();
       $ret = [];
       foreach($models as $model) {
        $image = isset($model->fkAccount->image_id)?$model->fkAccount->getImageUrl():'';
        $service_estimate = unserialize($model->service_estimate);
         $ret[] = [
           'id' => $model->id,
           'pre_approval_status' => $model->is_pre_approved,
           're_verification_status' => $model->pre_verification_needed,
           'approval_status' => $model->is_approved,
           'customer' =>[
           'id' =>isset($model->fkAccount->fkCustomer)?$model->fkAccount->fkCustomer->id:null,
           'account_id '=>$model->account_id,
           'name' => $model->getCustomerName($model->account_id),
           'ward' => isset($model->fkAccount->fkCustomer->fkWard->name)?$model->fkAccount->fkCustomer->fkWard->name:'',
           'address'=> isset($model->fkAccount->fkCustomer->fkWard->address)?$model->fkAccount->fkCustomer->fkWard->address:'',
             'block' => isset($model->fkAccount->fkCustomer->fkWard->fkLsgi->fkLsgiBlock->name)?$model->fkAccount->fkCustomer->fkWard->fkLsgi->fkLsgiBlock->name:'',
           'image'=> $image,
           // 'service_estimate'=>$service_estimate
           ]
         ];

       }
       $ret =[
       'image_base' =>  isset(Yii::$app->params['base_url']) ? Yii::$app->params['base_url'] : null,
       'items'=>$ret
       ];
       return $ret;
  }
   public function actionServiceEnablingRequestSingle($account_id=null){
    $subscribed =0;
    $qty= null;
      $interval =null;
        $type=null;
       $slab=null;
       $is_slab = 0;
      $query = AccountServiceRequest::find()->where(['status'=>1])
      ->andWhere(['account_id'=>$account_id])
       ->andWhere(['not', ['service_estimate' => null]])
      ;
       $dataProvider =  new ActiveDataProvider([
         'query' => $query

       ]);
       $models = $dataProvider->getModels();
       $ret = [];
       if($models){
       foreach($models as $model) {
           if($model->request_type==2){
        $modelService = Service::find()->where(['status'=>1])->andWhere(['type'=>1])->andWhere(['is_package'=>0])->andWhere(['is_non_residential'=>1]);
         $serviceDataProvider =  new ActiveDataProvider([
         'query' => $modelService

       ]);
       $modelServices = $serviceDataProvider->getModels();
        if($model->service_estimate)
        {
          $serviceEstimate = unserialize($model->service_estimate);
        }else
        {
          $serviceEstimate = null;

        }
        $slab = null;
        $serviceLists = [];
        $qty = null;
        $advanceAmount = 0;
        $advanceAmountNew = 0;
        foreach ($modelServices as $service) {
          $serviceEnabled = AccountService::find()->where(['service_id'=>$service->id])->andWhere(['account_id'=>$model->account_id])->andWhere(['status'=>1])->one();
           if($serviceEnabled)
          {
            $subscribed  = 1;
          }
          else
          {
            $subscribed = 0;
          }
          if($serviceEstimate){
          foreach ($serviceEstimate as $value) {
            // print_r($value);die();
            if($value['id']==$service->id)
            {
              $qty = $value['estimated_qty_kg'];
              $type = isset($value['type'])?$value['type']:'';
              $interval = isset($value['collection_interval'])?$value['collection_interval']:'';
               $slab = isset($value['slab'])?$value['slab']:'';
              break;
            }
            else
            {
              $qty = null;
              $type = isset($value['type'])?$value['type']:'';
              $interval = isset($value['collection_interval'])?$value['collection_interval']:'';
              $slab = isset($value['slab'])?$value['slab']:'';
            }
            if(isset($value['slab'])&&$value['slab']==0&&$value['estimated_qty_kg']!=null){
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
            if($value['type']==1){
            if($modelLsgiServiceSlabFee->use_for_per_kg_rate==1)
            {
              $advanceAmount =  $advanceAmount + ($value['estimated_qty_kg']*$modelLsgiServiceSlabFee->amount);
            }
            else
            {
              $advanceAmountNew =  $advanceAmountNew + $modelLsgiServiceSlabFee->amount;
            }
          }
        else
        {
          if($modelLsgiServiceSlabFee->use_for_per_kg_rate==1)
            {
              $advanceAmount =  $advanceAmount - ($value['estimated_qty_kg']*$modelLsgiServiceSlabFee->amount);
            }
            else
            {
              $advanceAmountNew =  $advanceAmountNew -$modelLsgiServiceSlabFee->amount;
            }
        }
        }
        $advanceAmount = $advanceAmount*45;
        $advanceAmountNew = $advanceAmountNew*1.5;
        $advanceAmount = $advanceAmount + $advanceAmountNew;
      }
        }
        if($interval!=null)
        {
          $collectionInterval = NonResidentialWasteCollectionInterval::find()->where(['id'=>$interval])->andWhere(['status'=>1])->one();
            $interval = [
            'id'=>isset($collectionInterval->id)?$collectionInterval->id:0,
            'name'=>isset($collectionInterval->name)?$collectionInterval->name:'',
            ];
        }
        else
        {
           $interval = [
            'id'=>0,
            'name'=>'',
            ];
        }
        if($slab!=null)
        {
          $modelSlab = Slab::find()->where(['id'=>$slab])->andWhere(['status'=>1])->one();
             $slab = [
            'id'=>isset($modelSlab->id)?$modelSlab->id:0,
            'name'=>isset($modelSlab->name)?$modelSlab->name:'',
            ];
            $is_slab = isset($modelSlab->id)?1:0;
        }
        else
        {
          $slab = [
            'id'=>0,
            'name'=>'',
            ];
            $is_slab =0;
        }
           $serviceLists[] = [
                'id'   => $service->id,
                'name' => $service->name,
                'is_subscribed' => $subscribed,
                'estimated_qty_kg' => $qty,
                'collection_interval' => $interval,
                'subscription_requested' => (int)$type,
                'is_slab' => $is_slab,
                'slab' => $slab,
            ];
        }
       

         $image = isset($model->fkAccount->image_id)?$model->fkAccount->getImageUrl():'';
         $ret = [
         'image_base' =>  isset(Yii::$app->params['base_url']) ? Yii::$app->params['base_url'] : null,
           'id' => $model->id,
           'pre_approval_status' => $model->is_pre_approved,
           're_verification_status' => $model->pre_verification_needed,
           'approval_status' => $model->is_approved,
           'customer' =>[
           'id' =>isset($model->fkAccount->fkCustomer)?$model->fkAccount->fkCustomer->id:null,
           'account_id '=>$model->account_id,
           'name' => $model->getCustomerName($model->account_id),
           'ward' => isset($model->fkAccount->fkCustomer->fkWard->name)?$model->fkAccount->fkCustomer->fkWard->name:'',
           'building_type' => isset($model->fkAccount->fkCustomer->fkBuildingType->name)?$model->fkAccount->fkCustomer->fkBuildingType->name:'',
           'block' => isset($model->fkAccount->fkCustomer->fkWard->fkLsgi->fkLsgiBlock->name)?$model->fkAccount->fkCustomer->fkWard->fkLsgi->fkLsgiBlock->name:'',
           'address'=> isset($model->fkAccount->fkCustomer->address)?$model->fkAccount->fkCustomer->address:'',
           'phone'=> isset($model->fkAccount->fkCustomer->lead_person_phone)?$model->fkAccount->fkCustomer->lead_person_phone:'',
           'latitude'=> isset($model->fkAccount->fkCustomer->lat)?$model->fkAccount->fkCustomer->lat:'',
           'longitude'=> isset($model->fkAccount->fkCustomer->lng)?$model->fkAccount->fkCustomer->lng:'',
           'image'=> $image,
           'advance_amount'=> $advanceAmount,
           'service_estimate'=>$serviceLists
           ]
         ];

}
       }
     }
     // else
     // {
     //    $model = new AccountServiceRequest;
     //    $modelService = Service::find()->where(['status'=>1])->andWhere(['type'=>1])->andWhere(['is_package'=>0])->andWhere(['is_quantity_entering_enabled'=>1]);
     //     $serviceDataProvider =  new ActiveDataProvider([
     //     'query' => $modelService

     //   ]);
     //   $modelServices = $serviceDataProvider->getModels();
        
     //      $serviceEstimate = null;

     //    $serviceLists = [];
     //    $qty = null;
     //    foreach ($modelServices as $service) {
     //      $serviceEnabled = AccountService::find()->where(['service_id'=>$service->id])->andWhere(['account_id'=>$account_id])->andWhere(['status'=>1])->one();
     //       if($serviceEnabled)
     //      {
     //        $subscribed  = 1;
     //      }
     //      else
     //      {
     //        $subscribed = 0;
     //      }
     //      if($serviceEstimate){
     //      foreach ($serviceEstimate as $value) {
     //        if($value['id']==$service->id)
     //        {
     //          $qty = $value['estimated_qty_kg'];
     //          $type = isset($value['type'])?$value['type']:$model->type;
     //          break;
     //        }
     //        else
     //        {
     //          $qty = null;
     //          $type = isset($value['type'])?$value['type']:$model->type;
     //        }
     //      }
     //    }
     //    else
     //    {
     //      $qty = null;
     //      $type = null;
     //    }
     //       $serviceLists[] = [
     //            'id'   => $service->id,
     //            'name' => $service->name,
     //            'is_subscribed' => $subscribed,
     //            'estimated_qty_kg' => $qty,
     //            'subscription_requested' => $type,
     //        ];
     //    }
       
     //    $modelAccount = Account::find()->where(['id'=>$account_id])->andWhere(['status'=>1])->one();
     //    if(!$modelAccount)
     //    {
     //      $msg   = ['Account id is not valid'];
     //            $error = ['account_id' => $msg];
     //            $ret   = ['errors' => $error];
     //            return $ret;
     //    }
     //     $image = isset($modelAccount->image_id)?$modelAccount->getImageUrl():'';
     //     $ret = [
     //     'image_base' =>  isset(Yii::$app->params['base_url']) ? Yii::$app->params['base_url'] : null,
     //       'id' => isset($model->id)?$model->id:null,
     //       'pre_approval_status' => isset($model->is_pre_approved)?$model->is_pre_approved:null,
     //       're_verification_status' => isset($model->pre_verification_needed)?$model->pre_verification_needed:null,
     //       'approval_status' => isset($model->is_approved)?$model->is_approved:null,
     //       'customer' =>[
     //       'id' =>isset($modelAccount->fkCustomer)?$modelAccount->fkCustomer->id:null,
     //       'account_id '=>isset($account_id)?$account_id:null,
     //       'name' => isset($modelAccount->fkCustomer)?$modelAccount->fkCustomer->lead_person_name:null,
     //       'ward' => isset($modelAccount->fkCustomer->fkWard->name)?$modelAccount->fkCustomer->fkWard->name:'',
     //       'block' => isset($modelAccount->fkCustomer->fkWard->fkLsgi->fkLsgiBlock->name)?$modelAccount->fkCustomer->fkWard->fkLsgi->fkLsgiBlock->name:'',
     //       'address'=> isset($modelAccount->fkCustomer->address)?$modelAccount->fkCustomer->address:'',
     //       'phone'=> isset($modelAccount->fkCustomer->lead_person_phone)?$modelAccount->fkCustomer->lead_person_phone:'',
     //       'latitude'=> isset($modelAccount->fkCustomer->lat)?$modelAccount->fkCustomer->lat:'',
     //       'longitude'=> isset($modelAccount->fkCustomer->lng)?$modelAccount->fkCustomer->lng:'',
     //       'image'=> $image,
     //       'service_estimate'=>$serviceLists
     //       ]
     //     ];

       
     // }
     return $ret;

  }
   public function actionUpdateAccountService($id=null)
    {
        $modelUser = Yii::$app->user->identity;
        $userId    = $modelUser->id;
        $ret       = [];
        $params    = Yii::$app->request->post();

        while (true)
        {
            if (!isset($params['account_id']))
            {
                $msg   = ['Account id is mandatory'];
                $error = ['account_id' => $msg];
                $ret   = ['errors' => $error];
                break;

                return $ret;
            }
           
            if ($params)
            {
                   $postJson = json_encode($params);
                 $this->log($postJson);
                    $modelAccountServiceRequest = AccountServiceRequest::find()->where(['account_id'=>$params['account_id']])
                    ->andWhere(['not', ['service_estimate' => null]])
                    ->andWhere(['status'=>1])
                    ->andWhere(['not', ['is_pre_approved' => 1]])
                    ->andWhere(['not', ['is_approved' => 1]])
                    ->orderBy(['id' => SORT_DESC])
                    ->one();
                    // print_r($modelAccountServiceRequest);die();
                    if(!$modelAccountServiceRequest){
                      $modelAccountServiceRequest                          = new AccountServiceRequest;
                    }
                    else
                    {
                      $modelAccountServiceRequest->is_jhi_approved = 1;
                      $modelAccountServiceRequest->is_pre_approved = 0;
                      $modelAccountServiceRequest->pre_verification_needed = 0;
                      $modelAccountServiceRequest->account_id_pre_approved_by = null;
                      $modelAccountServiceRequest->is_approved = 0;
                      $modelAccountServiceRequest->account_id_approved_by = null;
                    }
                    $modelAccountServiceRequest->remarks = isset($params['remarks'])?$params['remarks']:'';
                    $modelAccountServiceRequest->account_id              = isset($params['account_id']) ? $params['account_id'] : 0;
                    $modelAccountServiceRequest->service_id              = isset($params['service_id']) ? $params['service_id'] : 0;
                    $modelAccountServiceRequest->request_type            = isset($params['type']) ? $params['type'] : 2;
                    $modelAccountServiceRequest->requested_at            = date('Y-m-d H:i:s');
                    $modelAccountServiceRequest->account_id_requested_by = $userId;
                    if(isset($params['sub_services']))
                    {
                      $newArray = [];
                      foreach ($params['sub_services'] as $key => $value) {
                        $newArray[] =intval($value);
                      }
                      $modelAccountServiceRequest->sub_service = serialize($newArray);
                    }
                    if(isset($params['service_estimate']))
                    {
                      $serviceEstimateArray = [];
                      foreach ($params['service_estimate'] as $key => $value) {
                        $serviceEstimateArray[] =$value;
                      }
                      $modelAccountServiceRequest->service_estimate = serialize($serviceEstimateArray);
                    }
                    $modelAccountServiceRequest->save(false);
                    $ret = [
                        'id'         => $modelAccountServiceRequest->id,
                        // 'service_id' => $modelAccountServiceRequest->service_id,
                        'account_id' => $modelAccountServiceRequest->account_id,
                        // 'type'       => $modelAccountServiceRequest->request_type
                    ];
            }
            break;
        }

        return $ret;
    }
     public function actionSlab(){
       $query = LsgiServiceSlabFee::find()->where(['status'=>1])
       ->andWhere(['not', ['use_for_per_kg_rate' => 1]])
       ->andWhere(['status'=>1]);
       $dataProvider =  new ActiveDataProvider([
         'query' => $query

       ]);
       $models = $dataProvider->getModels();
       $ret = [];
       foreach($models as $model) {
         $ret[] = [
           'id' => $model->id,
           'name' => $model->slab_name,
         ];

       }
       return $ret;
     }
}
