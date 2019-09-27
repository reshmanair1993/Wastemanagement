<?php
namespace api\modules\v1\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\rest\ActiveController;
use yii\data\ActiveDataProvider;
use api\modules\v1\models\Account;
use api\modules\v1\models\Customer;
use yii\filters\auth\HttpBearerAuth;
use api\modules\v1\models\ServiceRequest;
use api\modules\v1\models\ServiceAssignment;
use api\modules\v1\models\ServicingStatusOption;
use api\modules\v1\models\PaymentRequest;
use yii\filters\AccessControl;
use api\modules\v1\models\BuildingType;
use  api\modules\v1\models\Log;
use  api\modules\v1\models\EvaluationConfigWasteQuality;
use  api\modules\v1\models\EvaluationConfigCompletionTime;
use backend\models\GreenActionUnitWard;
use backend\models\GreenActionUnit;
use backend\models\ResidenceCategory;
use  api\modules\v1\models\CustomerSelfService;
use  api\modules\v1\models\InoculamBagsRequest;
use  api\modules\v1\models\LsgiServiceSlabFee;
use  api\modules\v1\models\AccountServiceRequest;
/**
 * AccountController implements the CRUD actions for Account model.
 */
class ServicesController extends ActiveController
{
    /**
     * @inheritdoc
     */
    public $modelClass = '\api\modules\v1\models\Services';
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
     public function log($str) {
        $modelLog =  new Log;
        $modelLog->message = $str;
        $modelLog->save(false);
     }
    public function behaviors()
    {
        return [
            'auth'  => [
                'class' => HttpBearerAuth::className()
            ],
            'access' => [
                'class' => AccessControl::className(),
                 'only' => ['index','status-options','service-status'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index','status-options','service-status'],
                        'roles' => ['green-technician','supervisor'],
                    ],
                ],
            ],
        ];
    }
    /**
     * @param $page
     * @param $per_page
     * @param $type
     * @return mixed
     */
    public function actionIndex(
        $page = 1,
        $per_page = 30,
        $account_id = null,$status=null,$type=null,$language='ml'
    )
    {
         $types      = ['service' => 1, 'complaint' => 2];
          $customerDetails = [];
        $image_base = isset(Yii::$app->params['base_url']) ? Yii::$app->params['base_url'] : null;
        $modelUser  = Yii::$app->user->identity;
        $userId     = $modelUser->id;
        // if($modelUser->role=='green-technician'){
        $query = ServiceAssignment::getAllQuery()->andWhere(['account_id_gt'=>$userId])->orderBy(['service_assignment.id' => SORT_DESC]);
    // }
    //     if($modelUser->role=='supervisor'){
    //     $query = ServiceAssignment::getAllQuery()
    //     ->leftJoin('account_authority','account_authority.account_id_gt=service_assignment.account_id_gt')
    //     ->andWhere(['account_authority.account_id_supervisor'=>$userId])
    //     ->orderBy(['service_assignment.id' => SORT_DESC]);
    // }
       if($status)
        {
        if($status=='completed')
         $query->andWhere(['>','service_assignment.servicing_status_option_id' ,0]);
        if($status=='pending')
         $query
     // ->leftJoin('service_request','service_request.id=service_assignment.service_request_id')
     ->andWhere(['service_assignment.servicing_status_option_id'=>null]);
     // ->groupBy('service_request.account_id_customer');
        }
        else
        {
            $query->andWhere(['service_assignment.servicing_status_option_id'=>null]);
        }
        if ($type)
        {
            $type = $types[$type];
        }
        if ($type&&!$account_id)
        {
            $query
            ->leftJoin('service_request','service_request.id=service_assignment.service_request_id')->leftJoin('service', 'service_request.service_id=service.id')
                  ->andWhere(['service.type' => $type]);
        }

      if ($type&&$account_id)
        {
            $query
            ->leftJoin('service_request','service_request.id=service_assignment.service_request_id')->leftJoin('service', 'service_request.service_id=service.id')
                  ->andWhere(['service.type' => $type])
                  ->andWhere(['service_request.account_id_customer'=>$account_id]);
        }
        if($account_id&&!$type)

            $query->leftJoin('service_request','service_request.id=service_assignment.service_request_id')
            ->andWhere(['service_request.account_id_customer'=>$account_id]);

        $dataProvider = new ActiveDataProvider([
            'query'      => $query,
            'pagination' => [
                'pageSize' => $per_page,
                'page'     => $page - 1
            ]

        ]);
        $models   = $dataProvider->getModels();
        $ret      = [];

        $pending = 0;
        $completed = 0;
        foreach ($models as $model)
        {
            if(!$account_id){
            if($model->servicing_status_option_id==null)
            {
                $pending = $pending+1;
            }
            else
            {
                $completed = $completed+1;
            }
        }
            else{
        $modelAccount         = Account::findOne($account_id);
          if($modelAccount){
            $modelCustomer = $modelAccount->fkCustomer;
                $pending =  $modelCustomer->getResolvedCount($modelAccount->id);
                $completed =  $modelCustomer->getPendingCount($modelAccount->id);
            }
        }
            $statusOptions = null;
            $modelServiceStatus = $model->fkServiceStatus;

            if($modelServiceStatus)
            {
                if(isset($language)&&$language=='ml')
            {
                $name = $modelServiceStatus->name_ml?$modelServiceStatus->name_ml:$model->value;
            }
             else
            {
                $name = $modelServiceStatus->value;
            }
                $statusOptions = [
                                    'id'=>$modelServiceStatus->id,
                                    'name'=>$name,
                                    'ask_quantity'       => $modelServiceStatus->ask_waste_quantity?$modelServiceStatus->ask_waste_quantity:0,
                                    'ask_quality'        => $modelServiceStatus->ask_waste_quality?$modelServiceStatus->ask_waste_quality:0,
                        ];
            }
            $modelServiceRequest = $model->fkServiceRequest;

            if(!$modelServiceRequest)
              continue;
            $accountId = $modelServiceRequest->account_id_customer;
            if(!isset($ret[$accountId])) {
            $modelAccount         = $modelServiceRequest->fkAccountCustomer;
            if(!$modelAccount)
              continue;
            $modelCustomer = $modelAccount->fkCustomer;
            if(!$modelCustomer)
              continue;

             $customerDetails = [
                'account_id'=>$modelAccount->id,
                'customer_id' => $modelCustomer->id,
                'customer_id_formatted' => $modelCustomer->getFormattedCustomerId($modelCustomer->id),
                'name' => ucfirst($modelCustomer->lead_person_name),
                'house_name' => $modelCustomer->building_name,
                'photo' => $modelCustomer->getImageUrl(),
                'phone' => $modelCustomer->lead_person_phone,
                'lat' => $modelCustomer->lat,
                'lng' => $modelCustomer->lng,
                'address' => $modelCustomer->address,
                'ward' => $modelCustomer->getWard(),
                'lsgi_block' => $modelCustomer->getLsgi(),
                'resolved_complaints_count' => $modelCustomer->getResolvedCount($modelAccount->id),
                'pending_complaints_count' => $modelCustomer->getPendingCount($modelAccount->id),
            ];
            $ret[$accountId] = [
              'customer'=> $customerDetails,
              'services'=>[]
            ];

      }

          $modelService = $modelServiceRequest->fkService;
          if(!$modelService)
            continue;

         $modelAccountServiceRequest = AccountServiceRequest::find()->where(['account_id'=>$modelAccount->id])->orderBy('id DESC')->one();
        // print_r($modelAccountServiceRequest);die();
        if($modelAccountServiceRequest)
        {
          $serviceEstimate = unserialize($modelAccountServiceRequest->service_estimate);
          $subServices = unserialize($modelAccountServiceRequest->sub_service);
        }
        else
        {
            $serviceEstimate = null;
            $subServices = null;
        }
        if($serviceEstimate&&$serviceEstimate!=null&&$subServices==null){
          foreach ($serviceEstimate as $value) {
            if($value['id'])
            {
              $qty = $value['estimated_qty_kg'];
            if($value['slab']==null&&$value['estimated_qty_kg']!=null){
          $modelLsgiServiceSlabFee = LsgiServiceSlabFee::find()->where(['service_id'=>$value['id']])->andWhere(['collection_interval'=>$value['collection_interval']])
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

          $image = null;
          $modelImage = $modelService->fkImage;
          if($modelImage)
            $image = $modelImage->uri_full;
        if(isset($language)&&$language=='ml')
            {
                $name = $modelService->name_ml?$modelService->name_ml:$modelService->name;
            }
            else
            {
                $name = $modelService->name;
            }
            if($value['id']==$modelService->id){
                $ret[$accountId]['services'][] = [
                    'service_request_id' => $modelServiceRequest->id,
                    'service_name' => $name,
                    'service_id'         => $modelServiceRequest->service_id,
                    'image'              => $image,
                    'is_cancelled' => $modelServiceRequest->is_cancelled,
                    'status_option'=> $statusOptions,
                     'unit_price' => isset($modelLsgiServiceSlabFee->amount)?$modelLsgiServiceSlabFee->amount:0,
                ];
                }
        }
        }
    }
        else
        {
           $image = null;
          $modelImage = $modelService->fkImage;
          if($modelImage)
            $image = $modelImage->uri_full;
        if(isset($language)&&$language=='ml')
            {
                $name = $modelService->name_ml?$modelService->name_ml:$modelService->name;
            }
            else
            {
                $name = $modelService->name;
            }
                $ret[$accountId]['services'][] = [
                    'service_request_id' => $modelServiceRequest->id,
                    'service_name' => $name,
                    'service_id'         => $modelServiceRequest->service_id,
                    'image'              => $image,
                    'is_cancelled' => $modelServiceRequest->is_cancelled,
                    'status_option'=> $statusOptions,
                   
                ];  
        }
        }

        $ret = array_values($ret);
        if(!$ret&&$account_id)
        {
          $modelAccount         = Account::findOne($account_id);
          if($modelAccount){
            $modelCustomer = $modelAccount->fkCustomer;
             $customerDetails = [
                'account_id'=>$modelAccount->id,
                'customer_id' => $modelCustomer->id,
                'customer_id_formatted' => $modelCustomer->getFormattedCustomerId($modelCustomer->id),
                'name' => ucfirst($modelCustomer->lead_person_name),
                'house_name' => $modelCustomer->building_name,
                'photo' => $modelCustomer->getImageUrl(),
                'phone' => $modelCustomer->lead_person_phone,
                'lat' => $modelCustomer->lat,
                'lng' => $modelCustomer->lng,
                'address' => $modelCustomer->address,
                'ward' => $modelCustomer->getWard(),
                'lsgi_block' => $modelCustomer->getLsgi(),
                'resolved_complaints_count' => $modelCustomer->getResolvedCount($modelAccount->id),
                'pending_complaints_count' => $modelCustomer->getPendingCount($modelAccount->id),
            ];
             $ret[] = [
              'customer'=> $customerDetails,
              'services'=>[]
            ];
            }
            else{
                $msg   = ['Invalid account id'];
                    $error = ['account_id' => $msg];
                    $ret   = ['errors' => $error];
                    return $ret;
            }
        }
        return $ret = [
            'image_base'                => $image_base,
            'resolved_complaints_count' => $completed,
            'pending_complaints_count'  => $pending,
            'items'                     => $ret
        ];
    }

    /**
     * @param $service_id
     * @return mixed
     */
     public function actionCustomerFilter(
         $page = 1,
         $per_page = 30,
         $account_id = null,$status=null,$type=null,$building_type = null
     )
     {
          $modelBuildingType = BuildingType::find()->where(['name' => $building_type])->one();
          if ($modelBuildingType) {
          $building_type_id = $modelBuildingType->id;
          }
          $types      = ['service' => 1, 'complaint' => 2];
           $customerDetails = [];
         $image_base = isset(Yii::$app->params['base_url']) ? Yii::$app->params['base_url'] : null;
         $modelUser  = Yii::$app->user->identity;
         $userId     = $modelUser->id;
         if($modelUser->role=='green-technician'){
         $query = ServiceAssignment::getAllQuery()->andWhere(['account_id_gt'=>$userId])->orderBy(['service_assignment.id' => SORT_DESC]);
     }
          if($modelUser->role=='supervisor'){
        $query = ServiceAssignment::getAllQuery()
        ->leftJoin('account_authority','account_authority.account_id_gt=service_assignment.account_id_gt')
        ->andWhere(['account_authority.account_id_supervisor'=>$userId])
        ->orderBy(['service_assignment.id' => SORT_DESC]);
    }
        if($status)
         {
         if($status=='completed')
          $query->andWhere(['>','service_assignment.servicing_status_option_id' ,0]);
         if($status=='pending')
          $query
      // ->leftJoin('service_request','service_request.id=service_assignment.service_request_id')
      ->andWhere(['service_assignment.servicing_status_option_id'=>null]);
      // ->groupBy('service_request.account_id_customer');
         }
         else
         {
             $query->andWhere(['service_assignment.servicing_status_option_id'=>null]);
         }
         if ($type)
         {
             $type = $types[$type];
         }
         if ($type&&!$account_id)
         {
             $query
             ->leftJoin('service_request','service_request.id=service_assignment.service_request_id')->leftJoin('service', 'service_request.service_id=service.id')
                   ->andWhere(['service.type' => $type]);
         }

       if ($type&&$account_id)
         {
             $query
             ->leftJoin('service_request','service_request.id=service_assignment.service_request_id')->leftJoin('service', 'service_request.service_id=service.id')
                   ->andWhere(['service.type' => $type])
                   ->andWhere(['service_request.account_id_customer'=>$account_id]);
         }
         if($account_id&&!$type)

             $query->leftJoin('service_request','service_request.id=service_assignment.service_request_id')
             ->andWhere(['service_request.account_id_customer'=>$account_id]);

         $dataProvider = new ActiveDataProvider([
             'query'      => $query,
             'pagination' => [
                 'pageSize' => $per_page,
                 'page'     => $page - 1
             ]

         ]);
         $models   = $dataProvider->getModels();
         $ret      = [];

         $pending = 0;
         $completed = 0;
         foreach ($models as $model)
         {
             if(!$account_id){
             if($model->servicing_status_option_id==null)
             {
                 $pending = $pending+1;
             }
             else
             {
                 $completed = $completed+1;
             }
         }
             else{
         $modelAccount         = Account::findOne($account_id);
           if($modelAccount){
             $modelCustomer = $modelAccount->fkCustomer;
                 $pending =  $modelCustomer->getResolvedCount($modelAccount->id);
                 $completed =  $modelCustomer->getPendingCount($modelAccount->id);
             }
         }
             $statusOptions = null;
             $modelServiceStatus = $model->fkServiceStatus;
             if($modelServiceStatus)
             {
                 $statusOptions = [
                                     'id'=>$modelServiceStatus->id,
                                     'name'=>$modelServiceStatus->value,
                         ];
             }
             $modelServiceRequest = $model->fkServiceRequest;

             if(!$modelServiceRequest)
               continue;
             $accountId = $modelServiceRequest->account_id_customer;
             if(!isset($ret[$accountId])) {
             $modelAccount         = $modelServiceRequest->fkAccountCustomer;
             if(!$modelAccount)
               continue;
             $modelCustomer = $modelAccount->fkCustomer;
             if(!$modelCustomer)
               continue;
              if($modelCustomer->building_type_id == $building_type_id){
              $customerDetails = [
                'buildng_type_id' => $modelCustomer->building_type_id,
                 'account_id'=>$modelAccount->id,
                 'customer_id' => $modelCustomer->id,
                 'customer_id_formatted' => $modelCustomer->getFormattedCustomerId($modelCustomer->id),
                 'name' => ucfirst($modelCustomer->lead_person_name),
                 'house_name' => $modelCustomer->building_name,
                 'photo' => $modelCustomer->getImageUrl(),
                 'phone' => $modelCustomer->lead_person_phone,
                 'lat' => $modelCustomer->lat,
                 'lng' => $modelCustomer->lng,
                 'address' => $modelCustomer->address,
                 'ward' => $modelCustomer->getWard(),
                 'lsgi_block' => $modelCustomer->getLsgi(),
                 'resolved_complaints_count' => $modelCustomer->getResolvedCount($modelAccount->id),
                 'pending_complaints_count' => $modelCustomer->getPendingCount($modelAccount->id),
             ];
             $ret[$accountId] = [
               'customer'=> $customerDetails,
               'services'=>[]
             ];
           }

       }
           $modelService = $modelServiceRequest->fkService;
           if(!$modelService)
             continue;
           $image = null;
           $modelImage = $modelService->fkImage;
           if($modelImage)
             $image = $modelImage->uri_full;
            if($modelCustomer->building_type_id == $building_type_id){
                 $ret[$accountId]['services'][] = [
                     'service_request_id' => $modelServiceRequest->id,
                     'service_name' => $modelService->name,
                     'service_id'         => $modelServiceRequest->service_id,
                     'image'              => $image,
                     // 'ask_quantity'       => $modelService->ask_waste_quantity?$modelService->ask_waste_quantity:0,
                     // 'ask_quality'        => $modelService->ask_waste_quality?$modelService->ask_waste_quality:0,
                     'is_cancelled' => $modelServiceRequest->is_cancelled,
                     'status_option'=> $statusOptions,
                 ];
         }}
         $ret = array_values($ret);
         if(!$ret&&$account_id)
         {
           $modelAccount         = Account::findOne($account_id);
           if($modelAccount){
             $modelCustomer = $modelAccount->fkCustomer;
              $customerDetails = [
                  // 'buildng_type_id' => $modelCustomer->building_type_id,
                 'account_id'=>$modelAccount->id,
                 'customer_id' => $modelCustomer->id,
                 'customer_id_formatted' => $modelCustomer->getFormattedCustomerId($modelCustomer->id),
                 'name' => ucfirst($modelCustomer->lead_person_name),
                 'house_name' => $modelCustomer->building_name,
                 'photo' => $modelCustomer->getImageUrl(),
                 'phone' => $modelCustomer->lead_person_phone,
                 'lat' => $modelCustomer->lat,
                 'lng' => $modelCustomer->lng,
                 'address' => $modelCustomer->address,
                 'ward' => $modelCustomer->getWard(),
                 'lsgi_block' => $modelCustomer->getLsgi(),
                 'resolved_complaints_count' => $modelCustomer->getResolvedCount($modelAccount->id),
                 'pending_complaints_count' => $modelCustomer->getPendingCount($modelAccount->id),
             ];
              $ret[] = [
               'customer'=> $customerDetails,
               'services'=>[]
             ];
           }
             else{
                 $msg   = ['Invalid account id'];
                     $error = ['account_id' => $msg];
                     $ret   = ['errors' => $error];
                     return $ret;
             }
         }
         $modelCustomer = $modelAccount->fkCustomer;
         return $ret = [
             'image_base'                => $image_base,
             'resolved_complaints_count' => $completed,
             'pending_complaints_count'  => $pending,
             'items'                     => $ret
         ];
     }

    public function actionStatusOptions($service_id = null,$language=null)
    {
         $image_base = isset(Yii::$app->params['base_url']) ? Yii::$app->params['base_url'] : null;
        $query      = ServicingStatusOption::getAllQuery();
        if ($service_id)
        {
            $query->andWhere(['service_id' => $service_id]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query

        ]);
        $models = $dataProvider->getModels();
        $items  = [];
        foreach ($models as $model)
        {
          $image = null;
          $modelImage = $model->fkImage;
          if($modelImage)
            $image = $modelImage->uri_full;
        if(isset($language)&&$language=='ml')
            {
                $name = $model->name_ml?$model->name_ml:$model->value;
            }
             else
            {
                $name = $model->value;
            }
            $items[] = [
                'id'   => $model->id,
                'name' => $name,
                'image'=> $image,
                'ask_quantity'       => $model->ask_waste_quantity?$model->ask_waste_quantity:0,
                'ask_quality'        => $model->ask_waste_quality?$model->ask_waste_quality:0,
            ];
        }

        return $ret = [
            'image_base' => $image_base,
            'items'      => $items
        ];
    }else
                {
                    $msg   = ['Service id mandatory'];
                    $error = ['service_id' => $msg];
                    $ret   = ['errors' => $error];
                    return $ret;
                }
    }

    /**
     * @return mixed
     */
    public function actionServiceStatus()
    {
        $modelUser = Yii::$app->user->identity;
        $userId    = $modelUser->id;
        $ret = [];

        while (true)
        {
            $params = Yii::$app->request->post();
             $postJson = json_encode($params);
       $this->log($postJson);
            if (isset($params['service_request_id']))
            {
                if (!isset($params['lat']))
                {
                  $msg   = ['Latitude is mandatory'];
                  $error = ['lat' => $msg];
                  $ret   = ['errors' => $error];
                  return $ret;
                }
                if (!isset($params['lng']))
                {
                  $msg   = ['Longitude is mandatory'];
                  $error = ['lng' => $msg];
                  $ret   = ['errors' => $error];
                  return $ret;
                }
                $modelServiceAssignment = ServiceAssignment::find()->where(['service_request_id' => $params['service_request_id']])->one();
                if ($modelServiceAssignment)
                {
                    if($modelServiceAssignment->servicing_status_option_id>0)
                    {
                        $msg   = ['Already Status changed'];
                    $error = ['servicing_status_option_id' => $msg];
                    $ret   = ['errors' => $error];
                    return $ret
;                    }
                    $modelServiceAssignment->servicing_status_option_id = $params['status_option_id'];
                    $modelServiceAssignment->quality                    = isset($params['quality_type_id'])?$params['quality_type_id']:null;
                    $modelServiceAssignment->quantity                   = isset($params['quantity'])?$params['quantity']:null;
                    $modelServiceAssignment->lat_update_from                    = $params['lat'];
                    $modelServiceAssignment->lng_updated_from                   = $params['lng'];
                    $modelServiceAssignment->door_status                   = isset($params['door_status'])?$params['door_status']:1;
                    $modelServiceAssignment->servicing_datetime = date('Y-m-d H:i:s');
                    $modelServiceAssignment->remarks                   = isset($params['remarks'])?$params['remarks']:null;
                    if($modelServiceAssignment->save(false))
                    {

                        $modelServiceRequest = ServiceRequest::find()->where(['id'=>$modelServiceAssignment->service_request_id])->andWhere(['status'=>1])->one();
                         $modelAccount = Account::find()->where(['id'=>$modelServiceRequest->account_id_customer])->andWhere(['status'=>1])->one();
                         if($modelServiceAssignment->door_status==0)
                         {
                            ServiceRequest::doorLock($modelServiceRequest->service_id,$modelServiceRequest->account_id_customer);
                         }
                          ServiceRequest::confirmation($modelServiceRequest->service_id,$modelServiceRequest->account_id_customer);
                        if($modelAccount)
                        {
                          $modelCustomer = Customer::find()->where(['id'=>$modelAccount->customer_id])->andwhere(['status'=>1])->one();
                          if(isset($modelCustomer->fkBuildingType->fkCategory->rate_type)&&$modelCustomer->fkBuildingType->fkCategory->rate_type==1)
                          {
                        
                        $modelPaymentRequest                      = new PaymentRequest;
                        $modelPaymentRequest->account_id_customer = $modelServiceRequest->account_id_customer;
                        $modelPaymentRequest->requested_date  = date('Y-m-d H:i:s');
                        $modelPaymentRequest->service_request_id = $modelServiceRequest->id;
                        $amount = $modelPaymentRequest->getSlabAmount($modelServiceRequest->service_id,$modelServiceRequest->account_id_customer,$modelServiceRequest->lsgi_id);
                        $modelPaymentRequest->amount = $amount * $modelServiceAssignment->quantity; 
                        $modelPaymentRequest->save(false);
                    }
                }
            }
                    // if($modelServiceAssignment->quality)
                    // {
                    //     $modelServiceRequest = ServiceRequest::find()->where(['id'=>$params['service_request_id']])->andWhere(['status'=>1])->one();
                    //     $modelEvaluationConfigWasteQuality = EvaluationConfigWasteQuality::find()->where(['quality_type_id'=>$modelServiceAssignment->quality])->andWhere(['status'=>1])->one();
                    //     if($modelEvaluationConfigWasteQuality&&$modelServiceRequest)
                    //     {
                    //         $modelHksWard = GreenActionUnitWard::find()->where(['ward_id'=>$modelServiceRequest->ward_id])->andWhere(['status'=>1])->one();
                    //         if($modelHksWard)
                    //         {
                    //            $modelHks = GreenActionUnit::find()->where(['id'=>$modelHksWard->green_action_unit_id])->andWhere(['status'=>1])->one(); 
                    //            if($modelHks)
                    //            {
                    //              $qry = "SELECT max(performance_point) as performance_point FROM `evaluation_config_waste_quality` where status=1";
                    //             $command =  Yii::$app->db->createCommand($qry);
                    //             $data = $command->queryAll();
                    //             $point = $data[0];
                    //             $performance_point_max = $point['performance_point'];
                    //             $modelHks->performance_point_earned = $modelHks->performance_point_earned+$modelEvaluationConfigWasteQuality->performance_point;
                    //             $modelHks->performance_point_total = $modelHks->performance_point_total+$performance_point_max;
                    //             $modelServiceRequest->performance_point = $modelServiceRequest->performance_point+$modelEvaluationConfigWasteQuality->performance_point; 
                    //             $modelServiceRequest->save(false);
                    //             $modelHks->save(false);
                    //            }
                    //         }
                           
                    //     }
                    // }






                     if($modelServiceAssignment->quality)
                    {
                        $modelServiceRequest = ServiceRequest::find()->where(['id'=>$params['service_request_id']])->andWhere(['status'=>1])->one();
                        $modelEvaluationConfigWasteQuality = EvaluationConfigWasteQuality::find()->where(['quality_type_id'=>$modelServiceAssignment->quality])->andWhere(['status'=>1])->one();
                        if($modelEvaluationConfigWasteQuality&&$modelServiceRequest)
                        {
                            $modelHksWard = GreenActionUnitWard::find()->where(['ward_id'=>$modelServiceRequest->ward_id])->andWhere(['status'=>1])->one();
                            if($modelHksWard)
                            {
                               $modelHks = GreenActionUnit::find()->where(['id'=>$modelHksWard->green_action_unit_id])->andWhere(['status'=>1])->one(); 
                               if($modelHks)
                               {
                                 $qry = "SELECT max(performance_point) as performance_point FROM `evaluation_config_waste_quality` where status=1";
                                $command =  Yii::$app->db->createCommand($qry);
                                $data = $command->queryAll();
                                $point = $data[0];
                                $performance_point_max = $point['performance_point'];
                                $modelHks->performance_point_earned = $modelHks->performance_point_earned+$modelEvaluationConfigWasteQuality->performance_point;
                                $modelHks->performance_point_total = $modelHks->performance_point_total+$performance_point_max;
                                $modelServiceRequest->performance_point = $modelServiceRequest->performance_point+$modelEvaluationConfigWasteQuality->performance_point; 
                                $modelServiceRequest->time_of_completion_points_calculated = 1;
                                

                                $qry1 = "SELECT max(performance_point) as performance_point FROM `evaluation_config_completion_time` where status=1";

                                 $command1 =  Yii::$app->db->createCommand($qry);
                                $data1 = $command1->queryAll();
                                $point1 = $data1[0];
                                // $start_date = date('Y-m-d H:i:s',strtotime($modelServiceRequest->requested_datetime));
                                // $since_start = $start_date->diff(date('Y-m-d H:i:s'));
                                // $minutes = $since_start->days * 24 * 60;
                                // $minutes += $since_start->h * 60;
                                // $minutes += $since_start->i;
                                 $start_date = date('Y-m-d H:i:s',strtotime($modelServiceRequest->requested_datetime));
                                $today = date('Y-m-d H:i:s');
                                $todayDate = date('Y-m-d H:i:s',strtotime($today));
                                // $since_start = $start_date->diff($todayDate);
                                // $minutes = $since_start->days * 24 * 60;
                                // $minutes += $since_start->h * 60;
                                // $minutes += $since_start->i;
                                 $minutes = (time()-strtotime($modelServiceRequest->requested_datetime)) / 60;

                                $modelEvaluationConfigCompletionTime = EvaluationConfigCompletionTime::find()
                                ->where(['>=','start_value_minutes',$minutes])
                                ->andWhere(['<=','end_value_minutes',$minutes])
                                ->andWhere(['status'=>1])->one();
                                if($modelEvaluationConfigCompletionTime)
                                {
                                    $performance_point_value = $modelEvaluationConfigCompletionTime->performance_point;
                                }
                                else
                                {
                                    $performance_point_value = $point1['performance_point'];
                                }
                                $performance_point_max = $point['performance_point'];
                                $modelHks->performance_point_earned = $modelHks->performance_point_earned+$performance_point_value;
                                $modelHks->performance_point_total = $modelHks->performance_point_total+$performance_point_max;
                                $modelServiceRequest->performance_point = $modelServiceRequest->performance_point+$performance_point_value; 
                                $modelServiceRequest->save(false);
                                $modelHks->save(false);
                               }
                            }

                           
                        }
                    }
                    $ret = [
                        'service_request_id' => $modelServiceAssignment->service_request_id,
                        'status_option_id'   => $modelServiceAssignment->servicing_status_option_id,
                        'quality_type_id'    => $modelServiceAssignment->quality,
                        'quantity'           => $modelServiceAssignment->quantity,
                        'lat'           => $modelServiceAssignment->lat_update_from,
                        'lng'           => $modelServiceAssignment->lng_updated_from
                    ];
                }
                else
                {
                    $msg   = ['Incorrect service request id'];
                    $error = ['service_request_id' => $msg];
                    $ret   = ['errors' => $error];
                }
            }
            else
            {
                $msg   = ['service request id is mandatory'];
                $error = ['service_request_id' => $msg];
                $ret   = ['errors' => $error];
            }
            break;
        }

        return $ret;
    }



    public function actionAccountId($account_id = null)
    {
       
        $residenceCategory = 1;
          $customerDetails = [];
        $image_base = isset(Yii::$app->params['base_url']) ? Yii::$app->params['base_url'] : null;
        $modelUser  = Yii::$app->user->identity;
        $userId     = $modelUser->id;
        if($account_id)
        {
          $modelAccount         = Account::findOne($account_id);
          if($modelAccount){
            $modelCustomer = $modelAccount->fkCustomer;
                $pending =  $modelCustomer->getResolvedCount($modelAccount->id);
                $completed =  $modelCustomer->getPendingCount($modelAccount->id);
                if(isset($modelCustomer->fkBuildingType->fkCategory))
                {
                    if($modelCustomer->fkBuildingType->fkCategory->has_multiple_gt==1)
                    {
                    $residenceCategory = 0;
                    }else
                    {
                        $residenceCategory = 1;
                    }
                }
             $customerDetails = [
                'account_id'=>$modelAccount->id,
                'customer_id' => $modelCustomer->id,
                'customer_id_formatted' => $modelCustomer->getFormattedCustomerId($modelCustomer->id),
                'name' => ucfirst($modelCustomer->lead_person_name),
                'house_name' => $modelCustomer->building_name,
                'photo' => $modelCustomer->getImageUrl(),
                'phone' => $modelCustomer->lead_person_phone,
                'lat' => $modelCustomer->lat,
                'lng' => $modelCustomer->lng,
                'address' => $modelCustomer->address,
                'ward' => $modelCustomer->getWard(),
                'lsgi_block' => $modelCustomer->getLsgi(),
                'association_name' => $modelCustomer->getAssociation(),
                'association_number' => $modelCustomer->association_number,
                'resolved_complaints_count' => $modelCustomer->getResolvedCount($modelAccount->id)?$modelCustomer->getResolvedCount($modelAccount->id):0,
                'pending_complaints_count' => $modelCustomer->getPendingCount($modelAccount->id)?$modelCustomer->getPendingCount($modelAccount->id):0,
                'is_residential_customer'=>$residenceCategory,
                'building_name'=>$modelCustomer->building_name,
                'building_type_id'=>$modelCustomer->building_type_id,
                'building_type'=>isset($modelCustomer->fkBuildingType->name)?$modelCustomer->fkBuildingType->name:'',
                'trading_type_id'=>$modelCustomer->trading_type_id,
                'trading_type'=>isset($modelCustomer->fkTradingType->name)?$modelCustomer->fkTradingType->name:'',
                
            ];
             $ret[] = [
              'customer'=> $customerDetails,
            ];
            }
            else{
                $msg   = ['Invalid account id'];
                    $error = ['account_id' => $msg];
                    $ret   = ['errors' => $error];
                    return $ret;
            }
        }else
        {
            $msg   = ['Account id mandatory'];
                    $error = ['account_id' => $msg];
                    $ret   = ['errors' => $error];
                    return $ret;
        }
        return $ret = [
            'image_base'                => $image_base,
            'resolved_complaints_count' => $completed?$completed:0,
            'pending_complaints_count'  => $pending?$pending:0,
            'items'                     => $ret,
            'has_residential_customer'=>$residenceCategory,
        ];
    }
    // public function actionDetails(
    //     $page = 1,
    //     $per_page = 30,
    //     $account_id = null,$status=null,$type=null,$language='ml'
    // )
    // {
    //     $ret = [];
    //      $types      = ['service' => 1, 'complaint' => 2];
    //       $customerDetails = [];
    //     $image_base = isset(Yii::$app->params['base_url']) ? Yii::$app->params['base_url'] : null;
    //     $modelUser  = Yii::$app->user->identity;
    //     $userId     = $modelUser->id;
    //     // if($modelUser->role=='green-technician'){
    //     $query = Customer::find()->where(['customer.status'=>1])
    //     ->leftJoin('account','account.customer_id=customer.id')
    //     ->leftJoin('service_request','service_request.account_id_customer=account.id')
    //     ->leftJoin('service_assignment','service_assignment.service_request_id=service_request.id')
    //     ->andWhere(['account.status'=>1])
    //     ->andWhere(['service_request.status'=>1])
    //     ->andWhere(['service_assignment.status'=>1])
    //     ->andWhere(['service_assignment.account_id_gt'=>$userId])
    //     ->orderBy(['service_assignment.id' => SORT_DESC])
    //     ->groupBy('customer.id');
    //    if($status=='completed'||$status=='pending')
    //     {
    //     if($status=='completed')
    //      $query->andWhere(['>','service_assignment.servicing_status_option_id' ,0]);
    //     if($status=='pending')
    //         // print_r("expression");die();
    //      $query
    //         ->andWhere(['service_assignment.servicing_status_option_id'=>null]);
    //     }
    //     else
    //     {
    //         $query->andWhere(['service_assignment.servicing_status_option_id'=>null]);
    //     }
    //     if ($type)
    //     {
    //         $type = $types[$type];
    //     }
    //     if ($type||$account_id)
    //     {
    //         $query->leftJoin('service', 'service_request.service_id=service.id');
    //         if($type){
    //             $query->andWhere(['service.type' => $type]);
    //           }
    //           if($account_id){
    //             $query->andWhere(['service_request.account_id_customer'=>$account_id]);
    //           }
    //     }
    //     $dataProvider = new ActiveDataProvider([
    //         'query'      => $query,
    //         'pagination' => [
    //             'pageSize' => $per_page,
    //             'page'     => $page - 1
    //         ]

    //     ]);
    //     $models   = $dataProvider->getModels();
    //     $ret      = [];

    //     $pending = 0;
    //     $completed = 0;
    //     foreach ($models as $model)
    //     {
    //        if(!$account_id){
    //         if($model->fkAccount->fkServiceRequest->fkServiceAssignment->servicing_status_option_id==null)
    //         {
    //             $pending = $pending+1;
    //         }
    //         else
    //         {
    //             $completed = $completed+1;
    //         }
    //     }
    //         else{
    //       $modelAccount         = Account::findOne($account_id);
    //       if($modelAccount){
    //             $pending =  $model->getResolvedCount($modelAccount->id);
    //             $completed =  $model->getPendingCount($modelAccount->id);
    //         }
    //     } 
         
    //         $modelServiceRequest = $model->fkAccount->fkServiceRequest;

    //         if(!$modelServiceRequest)
    //           continue;
    //         $accountId = $modelServiceRequest->account_id_customer;
    //         if(!isset($ret[$accountId])) {
    //         $modelAccount         = $model->fkAccount;
    //         if(!$modelAccount)
    //           continue;
    //          $customerDetails = [
    //             'account_id'=>$modelAccount->id,
    //             'customer_id' => $model->id,
    //             'customer_id_formatted' => $model->getFormattedCustomerId($model->id),
    //             'name' => $model->lead_person_name,
    //             'house_name' => $model->building_name,
    //             'photo' => $model->getImageUrl(),
    //             'phone' => $model->lead_person_phone,
    //             'lat' => $model->lat,
    //             'lng' => $model->lng,
    //             'address' => $model->address,
    //             'ward' => $model->getWard(),
    //             'lsgi_block' => $model->getLsgi(),
    //             'resolved_complaints_count' => $model->getResolvedCount($modelAccount->id),
    //             'pending_complaints_count' => $model->getPendingCount($modelAccount->id),
    //         ];
    //         $ret[$accountId] = [
    //           'customer'=> $customerDetails,
    //           'services'=>[]
    //         ];
            
    //         $modelServiceRequests = ServiceRequest::find()->where(['account_id_customer'=>$modelAccount->id]);
    //         if($status&&$status=='completed'){
    //             $modelServiceRequests->leftJoin('service_assignment','service_assignment.service_request_id=service_request.id')
    //             ->andWhere(['>','service_assignment.servicing_status_option_id' ,0]);
    //         }
    //         if($status&&$status=='pending'){
    //             $modelServiceRequests->leftJoin('service_assignment','service_assignment.service_request_id=service_request.id')
    //             ->andWhere(['service_assignment.servicing_status_option_id'=>null]);
    //         }
    //         // $modelServiceRequest = $modelServiceRequests->all();
    //         $newDataProvider = new ActiveDataProvider([
    //         'query'      => $modelServiceRequests,

    //     ]);
    //     $modelServiceRequests   = $newDataProvider->getModels();
    //         foreach ($modelServiceRequests as $modelServiceRequest) {
    //             // print_r($modelServiceRequest);die();
    //             $modelService = isset($modelServiceRequest->fkService)?$modelServiceRequest->fkService:null;
    //       if(!$modelService)
    //         continue;
    //       $image = null;
    //       $modelImage = $modelService->fkImage;
    //       if($modelImage)
    //         $image = $modelImage->uri_full;
    //        $statusOptions = null;
    //         $modelServiceStatus = isset($modelServiceRequest->fkServiceAssignment->fkServiceStatus)?$modelServiceRequest->fkServiceAssignment->fkServiceStatus:null;
    //         if($modelServiceStatus)
    //         {
    //             if(isset($language)&&$language=='ml')
    //         {
    //             $name = $modelServiceStatus->name_ml?$modelServiceStatus->name_ml:$modelServiceStatus->value;
    //         }
    //          else
    //         {
    //             $name = $modelServiceStatus->value;
    //         }

    //             $statusOptions = [
    //                                 'id'=>$modelServiceStatus->id,
    //                                 'name'=>$name,
    //                                 'ask_quantity'       => $modelServiceStatus->ask_waste_quantity?$modelServiceStatus->ask_waste_quantity:0,
    //                                 'ask_quality'        => $modelServiceStatus->ask_waste_quality?$modelServiceStatus->ask_waste_quality:0,
    //                     ];
    //         }
    //         if(isset($language)&&$language=='ml')
    //         {
    //             $name = $modelService->name_ml?$modelService->name_ml:$modelService->name;
    //         }
    //          else
    //         {
    //             $name = $modelService->name;
    //         }
    //             $ret[$accountId]['services'][] = [
    //                 'service_request_id' => $modelServiceRequest->id,
    //                 'service_name' => $name,
    //                 'service_id'         => $modelServiceRequest->service_id,
    //                 'image'              => $image,
    //                 'is_cancelled' => $modelServiceRequest->is_cancelled,
    //                 // 'status_option'=> $statusOptions,
    //                 'status_option'=> $statusOptions,
    //             ];
    //              }

    //   }
    //      $ret = array_values($ret);
    //         }
    //     return $ret = [
    //         'image_base'                => $image_base,
    //         'resolved_complaints_count' => $completed,
    //         'pending_complaints_count'  => $pending,
    //         'items'                     => $ret
    //     ];

    //  }
    public function actionDetails(
        $page = 1,
        $per_page = 30,
        $account_id = null,$status=null,$type=null,$language='ml'
    )
    {
        $ret = [];
        $residenceCategory = 1;
         $types      = ['service' => 1, 'complaint' => 2];
          $customerDetails = [];
        $image_base = isset(Yii::$app->params['base_url']) ? Yii::$app->params['base_url'] : null;
        $modelUser  = Yii::$app->user->identity;
        $userId     = $modelUser->id;
        // if($modelUser->role=='green-technician'){
        $query = Customer::find()->where(['customer.status'=>1])
        ->leftJoin('account','account.customer_id=customer.id')
        ->leftJoin('service_request','service_request.account_id_customer=account.id')
        ->leftJoin('service_assignment','service_assignment.service_request_id=service_request.id')
        ->andWhere(['account.status'=>1])
        ->andWhere(['service_request.status'=>1])
        ->andWhere(['service_assignment.status'=>1])
        ->andWhere(['service_assignment.account_id_gt'=>$userId])
        ->orderBy(['service_assignment.id' => SORT_DESC])
        ->groupBy('customer.id');
       if($status=='completed'||$status=='pending')
        {
        if($status=='completed')
         $query->andWhere(['>','service_assignment.servicing_status_option_id' ,0]);
        if($status=='pending')
            // print_r("expression");die();
         $query
            ->andWhere(['service_assignment.servicing_status_option_id'=>null]);
        }
        else
        {
            $query->andWhere(['service_assignment.servicing_status_option_id'=>null]);
        }
        if ($type)
        {
            $type = $types[$type];
        }
        if ($type||$account_id)
        {
            $query->leftJoin('service', 'service_request.service_id=service.id');
            if($type){
                $query->andWhere(['service.type' => $type]);
              }
              if($account_id){
                $query->andWhere(['service_request.account_id_customer'=>$account_id]);
              }
        }
        $dataProvider = new ActiveDataProvider([
            'query'      => $query,
            'pagination' => [
                'pageSize' => $per_page,
                'page'     => $page - 1
            ]

        ]);
        $models   = $dataProvider->getModels();
        $ret      = [];

        $pending = 0;
        $completed = 0;
        foreach ($models as $model)
        {
           if(!$account_id){
            if(isset($model->fkAccount->fkServiceRequest->fkServiceAssignment->servicing_status_option_id)){
            if($model->fkAccount->fkServiceRequest->fkServiceAssignment->servicing_status_option_id==null)
            {
                $pending = $pending+1;
            }
            else
            {
                $completed = $completed+1;
            }
        }else
        {
            $pending = $pending + 1;
        }
        }
            else{
          $modelAccount         = Account::findOne($account_id);
          if($modelAccount){
                $pending =  $model->getResolvedCount($modelAccount->id);
                $completed =  $model->getPendingCount($modelAccount->id);
            }
        } 
         
            $modelServiceRequest = $model->fkAccount->fkServiceRequest;

            if(!$modelServiceRequest)
              continue;
            $accountId = $modelServiceRequest->account_id_customer;
            if(!isset($ret[$accountId])) {
            $modelAccount         = $model->fkAccount;
            if(!$modelAccount)
              continue;
          if(isset($model->fkBuildingType->fkCategory))
                {
                    if($model->fkBuildingType->fkCategory->has_multiple_gt==1)
                    {
                    $residenceCategory = 0;
                    }else
                    {
                        $residenceCategory = 1;
                    }
                }
             $customerDetails = [
                'account_id'=>$modelAccount->id,
                'customer_id' => $model->id,
                'customer_id_formatted' => $model->getFormattedCustomerId($model->id),
                'name' => ucfirst($model->lead_person_name),
                'house_name' => $model->building_name,
                'photo' => $model->getImageUrl(),
                'phone' => $model->lead_person_phone,
                'lat' => $model->lat,
                'lng' => $model->lng,
                'address' => $model->address,
                'ward' => $model->getWard(),
                'lsgi_block' => $model->getLsgi(),
                'resolved_complaints_count' => $model->getResolvedCount($modelAccount->id),
                'pending_complaints_count' => $model->getPendingCount($modelAccount->id),
                 'association_name' => $model->getAssociation(),
                'association_number' => $model->association_number,
                'has_residential_customer'=>$residenceCategory,
                 'building_name'=>$model->building_name,
                 'shop_name'=>$model->shop_name,
                'building_type_id'=>$model->building_type_id,
                'building_type'=>isset($model->fkBuildingType->name)?$model->fkBuildingType->name:'',
                'trading_type_id'=>$model->trading_type_id,
                'trading_type'=>isset($model->fkTradingType->name)?$model->fkTradingType->name:'',
            ];
            $ret[$accountId] = [
              'customer'=> $customerDetails,
              'services'=>[]
            ];
            
            $modelServiceRequests = ServiceRequest::find()->where(['account_id_customer'=>$modelAccount->id]);
            if($status&&$status=='completed'){
                $modelServiceRequests->leftJoin('service_assignment','service_assignment.service_request_id=service_request.id')
                ->andWhere(['>','service_assignment.servicing_status_option_id' ,0])
                ->andWhere(['service_request.status'=>1]);
            }
            if($status&&$status=='pending'){
                $modelServiceRequests->leftJoin('service_assignment','service_assignment.service_request_id=service_request.id')
                ->andWhere(['service_assignment.servicing_status_option_id'=>null])
                ->andWhere(['service_request.status'=>1]);
            }
            // $modelServiceRequest = $modelServiceRequests->all();
            $newDataProvider = new ActiveDataProvider([
            'query'      => $modelServiceRequests,

        ]);
        $modelServiceRequests   = $newDataProvider->getModels();
            foreach ($modelServiceRequests as $modelServiceRequest) {
                // print_r($modelServiceRequest);die();
                $modelService = isset($modelServiceRequest->fkService)?$modelServiceRequest->fkService:null;
          if(!$modelService)
            continue;
          $image = null;
          $modelImage = $modelService->fkImage;
          if($modelImage)
            $image = $modelImage->uri_full;
           $statusOptions = null;
            $modelServiceStatus = isset($modelServiceRequest->fkServiceAssignment->fkServiceStatus)?$modelServiceRequest->fkServiceAssignment->fkServiceStatus:null;
            if($modelServiceStatus)
            {
                 if(isset($language)&&$language=='ml')
            {
                $name = $modelServiceStatus->name_ml?$modelServiceStatus->name_ml:$modelServiceStatus->value;
            }
             else
            {
                $name = $modelServiceStatus->value;
            }

                $statusOptions = [
                                    'id'=>$modelServiceStatus->id,
                                    'name'=>$name,
                                    'ask_quantity'       => $modelServiceStatus->ask_waste_quantity?$modelServiceStatus->ask_waste_quantity:0,
                                    'ask_quality'        => $modelServiceStatus->ask_waste_quality?$modelServiceStatus->ask_waste_quality:0,
                        ];
            }
 if($modelUser->role=='green-technician'||$modelUser->role=='supervisor'){

        $hks = $modelUser->green_action_unit_id;
      }
$modelHks = GreenActionUnit::find()->where(['id'=>$hks])->andWhere(['status'=>1])->one();
    if($hks&&$modelHks&&isset($modelHks->residence_category_id)){
      $modelResidenceCategory = ResidenceCategory::find()->where(['id'=>$modelHks->residence_category_id])->andWhere(['status'=>1])->one();
    if($modelResidenceCategory&&$modelResidenceCategory->has_multiple_gt==1){
        $modelserviceHks = GreenActionUnitService::find()->where(['status'=>1])->andWhere(['service_id'=>$modelService->id])->andWhere(['green_action_unit_id'=>$hks])->one();
        if($modelserviceHks){
             if(isset($language)&&$language=='ml')
            {
                $name = $modelService->name_ml?$modelService->name_ml:$modelService->name;
            }
             else
            {
                $name = $modelService->name;
            }
        $ret[$accountId]['services'][] = [
                    'service_request_id' => $modelServiceRequest->id,
                    'service_name' => $name,
                    'service_id'         => $modelServiceRequest->service_id,
                    'image'              => $image,
                    'is_cancelled' => $modelServiceRequest->is_cancelled,
                    // 'status_option'=> $statusOptions,
                    'status_option'=> $statusOptions,
                ];
            }
    }
    else
    {
         if(isset($language)&&$language=='ml')
            {
                $name = $modelService->name_ml?$modelService->name_ml:$modelService->name;
            }
             else
            {
                $name = $modelService->name;
            }
        $ret[$accountId]['services'][] = [
                    'service_request_id' => $modelServiceRequest->id,
                    'service_name' => $name,
                    'service_id'         => $modelServiceRequest->service_id,
                    'image'              => $image,
                    'is_cancelled' => $modelServiceRequest->is_cancelled,
                    // 'status_option'=> $statusOptions,
                    'status_option'=> $statusOptions,
                ];
    }
}
else
{
     if(isset($language)&&$language=='ml')
            {
                $name = $modelService->name_ml?$modelService->name_ml:$modelService->name;
            }
             else
            {
                $name = $modelService->name;
            }
        $ret[$accountId]['services'][] = [
                    'service_request_id' => $modelServiceRequest->id,
                    'service_name' => $name,
                    'service_id'         => $modelServiceRequest->service_id,
                    'image'              => $image,
                    'is_cancelled' => $modelServiceRequest->is_cancelled,
                    // 'status_option'=> $statusOptions,
                    'status_option'=> $statusOptions,
                ];
}
                // $ret[$accountId]['services'][] = [
                //     'service_request_id' => $modelServiceRequest->id,
                //     'service_name' => $modelService->name,
                //     'service_id'         => $modelServiceRequest->service_id,
                //     'image'              => $image,
                //     'is_cancelled' => $modelServiceRequest->is_cancelled,
                //     // 'status_option'=> $statusOptions,
                //     'status_option'=> $statusOptions,
                // ];
                 }

      }
         $ret = array_values($ret);
            }
        return $ret = [
            'image_base'                => $image_base,
            'resolved_complaints_count' => $completed,
            'pending_complaints_count'  => $pending,
            'items'                     => $ret
        ];

     }
     public function actionAddAssetNumber()
    {
        $ret     = [];
        $params = Yii::$app->request->post();
         $account_id = isset($params['account_id'])?$params['account_id']:'';
        $asset_number = isset($params['asset_number'])?$params['asset_number']:'';
        $customer_name = isset($params['customer_name'])?$params['customer_name']:'';
        $customer_phone = isset($params['customer_phone'])?$params['customer_phone']:'';
        if(!$account_id)
        {
            $msg   = ['Account id is mandatory'];
            $error = ['account_id' => $msg];
            $ret   = ['errors' => $error];
            return $ret; 
        }
        if(!$asset_number&&$asset_number==null)
        {
            $msg   = ['Asset number is mandatory'];
            $error = ['asset_number' => $msg];
            $ret   = ['errors' => $error];
            return $ret; 
        }
        else
        {
            $modelAccount = Account::find()->where(['id'=>$account_id])->andWhere(['status'=>1])->one();
            if($modelAccount)
            {
                $modelCustomer = Customer::find()->where(['id'=>$modelAccount->customer_id])->andWhere(['status'=>1])->one();
                if($modelCustomer)
                {
                    $modelCustomer->asset_number = $asset_number;
                    $modelCustomer->lead_person_name = $customer_name?$customer_name:$modelCustomer->lead_person_name;
                    $modelCustomer->lead_person_phone = $customer_phone?$customer_phone:$modelCustomer->lead_person_phone;
                    $modelCustomer->save(false);
                    $ret = [
                        'asset_number' => $modelCustomer->asset_number,
                        'account_id' => $account_id
                    ];
                    return $ret;
                }
                else
                {
                    $msg   = ['Account id is invalid'];
                    $error = ['account_id' => $msg];
                    $ret   = ['errors' => $error];
                    return $ret;
                }
            }else
            {
                $msg   = ['Account id is invalid'];
                $error = ['account_id' => $msg];
                $ret   = ['errors' => $error];
                return $ret; 
            }

        }
    }
    public function actionQtyEnabled($mrc_id=null,$language=null)
    {
        $modelUser = Yii::$app->user->identity;
        $userId    = $modelUser->id;
         $image_base = isset(Yii::$app->params['base_url']) ? Yii::$app->params['base_url'] : null;
        $query      = Service::find()
                        ->leftJoin('account_service','account_service.service_id=service_id')
                        ->where(['service.status'=>1])
                        ->andWhere(['service.type'=>1])
                        ->andWhere(['account_service.status'=>1])
                        ->andWhere(['service.is_package'=>0])
                        ->andWhere(['service.is_quantity_entering_enabled'=>1])
                        ->andWhere(['account_service.account_id'=>$userId]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query

        ]);
        $models = $dataProvider->getModels();
        $items  = [];
        foreach ($models as $model)
        {
          $image = null;
          $modelImage = $model->fkImage;
          if($modelImage)
            $image = $modelImage->uri_full;
        if(isset($language)&&$language=='ml')
            {
                $name = $model->name_ml?$model->name_ml:$model->name;
            }
             else
            {
                $name = $model->name;
            }
            $items[] = [
                'id'   => $model->id,
                'name' => $name,
                'image'=> $image,
                // 'ask_quantity'       => $model->ask_waste_quantity?$model->ask_waste_quantity:0,
                // 'ask_quality'        => $model->ask_waste_quality?$model->ask_waste_quality:0,
            ];
        }

        return $ret = [
            'image_base' => $image_base,
            'mrc_id' => $mrc_id,
            'items'      => $items
        ];
    }
    public function actionSelfService()
    {
        $modelUser = Yii::$app->user->identity;
        $userId    = $modelUser->id;
        $ret = [];

        while (true)
        {
            $params = Yii::$app->request->post();
            $postJson = json_encode($params);
            $this->log($postJson);
            if(isset($params['is_bags_needed'])&&$params['is_bags_needed']==1)
            {
                $is_bags_needed = 1;
            }
            else
            {
                $is_bags_needed = 0;
            }
            if (isset($params['mrc_id']))
            {
                if (!isset($params['lat']))
                {
                  $msg   = ['Latitude is mandatory'];
                  $error = ['lat' => $msg];
                  $ret   = ['errors' => $error];
                  return $ret;
                }
                if (!isset($params['lng']))
                {
                  $msg   = ['Longitude is mandatory'];
                  $error = ['lng' => $msg];
                  $ret   = ['errors' => $error];
                  return $ret;
                }
                // if (!isset($params['service_id']))
                // {
                //   $msg   = ['Service is mandatory'];
                //   $error = ['service_id' => $msg];
                //   $ret   = ['errors' => $error];
                //   return $ret;
                // }
                if (!isset($params['qty']))
                {
                  $msg   = ['Quantity is mandatory'];
                  $error = ['qty' => $msg];
                  $ret   = ['errors' => $error];
                  return $ret;
                }
                    if(!$is_bags_needed){
                    $modelCustomerSelfService = new CustomerSelfService;
                    $modelCustomerSelfService->service_id = $params['service_id'];
                    $modelCustomerSelfService->lat           = $params['lat'];
                    $modelCustomerSelfService->lng           = $params['lng'];
                    $modelCustomerSelfService->account_id           = $userId;
                    $modelCustomerSelfService->mrc_id           = $params['mrc_id'];
                    $modelCustomerSelfService->qty           = $params['qty'];
                    $modelCustomerSelfService->save(false);
                    $ret = [
                        'id' => $modelCustomerSelfService->id,
                        'mrc_id'   => $modelCustomerSelfService->mrc_id,
                        'quantity'           => $modelCustomerSelfService->qty,
                        'lat'           => $modelCustomerSelfService->lat,
                        'lng'           => $modelCustomerSelfService->lng,
                    ];
                }else
                {
                    $modelInoculamBagsRequest = new InoculamBagsRequest;
                    $modelInoculamBagsRequest->account_id_customer           = $userId;
                    $modelInoculamBagsRequest->mrc_id           = $params['mrc_id'];
                    $modelInoculamBagsRequest->qty           = $params['qty'];
                    $modelInoculamBagsRequest->requested_date           = date('Y-m-d H:i:s');
                    $modelInoculamBagsRequest->save(false);
                    $ret = [
                        'id' => $modelInoculamBagsRequest->id,
                        'mrc_id'   => $modelInoculamBagsRequest->mrc_id,
                        'quantity'           => $modelInoculamBagsRequest->qty,
                    ];
                }
                    return $ret;
                }
                else
            {
                $msg   = ['Mrc id is mandatory'];
                $error = ['mrc_id' => $msg];
                $ret   = ['errors' => $error];
            }
            break;
            }
        }
                public function actionNonResidentialStatus()
    {
        $modelUser = Yii::$app->user->identity;
        $userId    = $modelUser->id;
        $ret = [];
        while (true)
        {
            $params = Yii::$app->request->post();
            if (isset($params['service_request_id']))
            {
                if (!isset($params['lat']))
                {
                  $msg   = ['Latitude is mandatory'];
                  $error = ['lat' => $msg];
                  $ret   = ['errors' => $error];
                  return $ret;
                }
                if (!isset($params['lng']))
                {
                  $msg   = ['Longitude is mandatory'];
                  $error = ['lng' => $msg];
                  $ret   = ['errors' => $error];
                  return $ret;
                }
                if (!isset($params['code']))
                {
                  $msg   = ['Code is mandatory'];
                  $error = ['code' => $msg];
                  $ret   = ['errors' => $error];
                  return $ret;
                }
                $modelServiceAssignment = ServiceAssignment::find()->where(['service_request_id' => $params['service_request_id']])->one();
                if ($modelServiceAssignment)
                {
                    $modelServiceRequest = ServiceRequest::find()->where(['id'=>$modelServiceAssignment->service_request_id])->andWhere(['status'=>1])->one();
                    {
                        if($modelServiceRequest)
                        {
                            $modelAccount = Account::find()->where(['id'=>$modelServiceRequest->account_id_customer])->andWhere(['status'=>1])->one();
                            if($modelAccount)
                            {
                                $modelCustomer = $modelAccount->fkCustomer;
                                if($modelCustomer)
                                {
                                    $secretCode = isset($modelCustomer->service_secret_otp)?$modelCustomer->service_secret_otp:'';
                                    if($secretCode!=$params['code'])
                                    {
                                      $msg   = ['Code is invalid'];
                                      $error = ['code' => $msg];
                                      $ret   = ['errors' => $error];
                                      return $ret;  
                                    }
                                }
                            }
                        }
                    }
                    if($modelServiceAssignment->servicing_status_option_id>0)
                    {
                        $msg   = ['Already Status changed'];
                    $error = ['servicing_status_option_id' => $msg];
                    $ret   = ['errors' => $error];
                    return $ret
;                    }
                    $modelServiceAssignment->servicing_status_option_id = $params['status_option_id'];
                    $modelServiceAssignment->quality                    = $params['quality_type_id']?$params['quality_type_id']:null;
                    $modelServiceAssignment->quantity                   = $params['quantity']?$params['quantity']:null;
                    $modelServiceAssignment->lat_update_from                    = $params['lat'];
                    $modelServiceAssignment->lng_updated_from                   = $params['lng'];
                    $modelServiceAssignment->door_status                   = isset($params['door_status'])?$params['door_status']:1;
                    $modelServiceAssignment->servicing_datetime = date('Y-m-d H:i:s');
                    $modelServiceAssignment->remarks                   = isset($params['remarks'])?$params['remarks']:null;
                    
                    if($modelServiceAssignment->save(false))
                    {
                        $modelServiceRequest = ServiceRequest::find()->where(['id'=>$modelServiceAssignment->service_request_id])->andWhere(['status'=>1])->one();
                         $modelAccount = Account::find()->where(['id'=>$modelServiceRequest->account_id_customer])->andWhere(['status'=>1])->one();
                         if($modelServiceAssignment->door_status==0)
                         {
                            ServiceRequest::doorLock($modelServiceRequest->service_id,$modelServiceRequest->account_id_customer);
                         }
                         ServiceRequest::confirmation($modelServiceRequest->service_id,$modelServiceRequest->account_id_customer);
                        if($modelAccount)
                        {
                          $modelCustomer = Customer::find()->where(['id'=>$modelAccount->customer_id])->andwhere(['status'=>1])->one();
                          if(isset($modelCustomer->fkBuildingType->fkCategory->rate_type)&&$modelCustomer->fkBuildingType->fkCategory->rate_type==1)
                          {
                        
                        $modelPaymentRequest                      = new PaymentRequest;
                        $modelPaymentRequest->account_id_customer = $modelServiceRequest->account_id_customer;
                        $modelPaymentRequest->requested_date  = date('Y-m-d H:i:s');
                        $modelPaymentRequest->service_request_id = $modelServiceRequest->id;
                        $amount = $modelPaymentRequest->getSlabAmountNonResidential($modelServiceRequest->service_id,$modelServiceRequest->account_id_customer,$modelServiceRequest->lsgi_id,$modelServiceAssignment->quantity );
                        $modelPaymentRequest->amount = $amount * $modelServiceAssignment->quantity; 
                        $modelPaymentRequest->save(false);
                    }
                }
            }
                  
                     if($modelServiceAssignment->quality)
                    {
                        $modelServiceRequest = ServiceRequest::find()->where(['id'=>$params['service_request_id']])->andWhere(['status'=>1])->one();
                        $modelEvaluationConfigWasteQuality = EvaluationConfigWasteQuality::find()->where(['quality_type_id'=>$modelServiceAssignment->quality])->andWhere(['status'=>1])->one();
                        if($modelEvaluationConfigWasteQuality&&$modelServiceRequest)
                        {
                            $modelHksWard = GreenActionUnitWard::find()->where(['ward_id'=>$modelServiceRequest->ward_id])->andWhere(['status'=>1])->one();
                            if($modelHksWard)
                            {
                               $modelHks = GreenActionUnit::find()->where(['id'=>$modelHksWard->green_action_unit_id])->andWhere(['status'=>1])->one(); 
                               if($modelHks)
                               {
                                 $qry = "SELECT max(performance_point) as performance_point FROM `evaluation_config_waste_quality` where status=1";
                                $command =  Yii::$app->db->createCommand($qry);
                                $data = $command->queryAll();
                                $point = $data[0];
                                $performance_point_max = $point['performance_point'];
                                $modelHks->performance_point_earned = $modelHks->performance_point_earned+$modelEvaluationConfigWasteQuality->performance_point;
                                $modelHks->performance_point_total = $modelHks->performance_point_total+$performance_point_max;
                                $modelServiceRequest->performance_point = $modelServiceRequest->performance_point+$modelEvaluationConfigWasteQuality->performance_point; 
                                $modelServiceRequest->time_of_completion_points_calculated = 1;
                                

                                $qry1 = "SELECT max(performance_point) as performance_point FROM `evaluation_config_completion_time` where status=1";

                                 $command1 =  Yii::$app->db->createCommand($qry);
                                $data1 = $command1->queryAll();
                                $point1 = $data1[0];
                               
                                 $start_date = date('Y-m-d H:i:s',strtotime($modelServiceRequest->requested_datetime));
                                $today = date('Y-m-d H:i:s');
                                $todayDate = date('Y-m-d H:i:s',strtotime($today));
                               
                                 $minutes = (time()-strtotime($modelServiceRequest->requested_datetime)) / 60;

                                $modelEvaluationConfigCompletionTime = EvaluationConfigCompletionTime::find()
                                ->where(['>=','start_value_minutes',$minutes])
                                ->andWhere(['<=','end_value_minutes',$minutes])
                                ->andWhere(['status'=>1])->one();
                                if($modelEvaluationConfigCompletionTime)
                                {
                                    $performance_point_value = $modelEvaluationConfigCompletionTime->performance_point;
                                }
                                else
                                {
                                    $performance_point_value = $point1['performance_point'];
                                }
                                $performance_point_max = $point['performance_point'];
                                $modelHks->performance_point_earned = $modelHks->performance_point_earned+$performance_point_value;
                                $modelHks->performance_point_total = $modelHks->performance_point_total+$performance_point_max;
                                $modelServiceRequest->performance_point = $modelServiceRequest->performance_point+$performance_point_value; 
                                $modelServiceRequest->save(false);
                                $modelHks->save(false);
                               }
                            }

                           
                        }
                    }
                    $ret = [
                        'service_request_id' => $modelServiceAssignment->service_request_id,
                        'status_option_id'   => $modelServiceAssignment->servicing_status_option_id,
                        'quality_type_id'    => $modelServiceAssignment->quality,
                        'quantity'           => $modelServiceAssignment->quantity,
                        'lat'           => $modelServiceAssignment->lat_update_from,
                        'lng'           => $modelServiceAssignment->lng_updated_from
                    ];
                }
                else
                {
                    $msg   = ['Incorrect service request id'];
                    $error = ['service_request_id' => $msg];
                    $ret   = ['errors' => $error];
                }
            }
            else
            {
                $msg   = ['service request id is mandatory'];
                $error = ['service_request_id' => $msg];
                $ret   = ['errors' => $error];
            }
            break;
        }

        return $ret;
    }
}
