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
        $account_id = null,$status=null,$type=null,$language=null
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
            //     if(isset($language)&&$language=='ml')
            // {
            //     $name = $modelServiceStatus->name_ml?$modelServiceStatus->name_ml:$model->value;
            // }
            //  else
            // {
            //     $name = $modelServiceStatus->value;
            // }
                $statusOptions = [
                                    'id'=>$modelServiceStatus->id,
                                    // 'name'=>$name,
                                    'name'=>$modelServiceStatus->value,
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
                'name' => $modelCustomer->lead_person_name,
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
          $image = null;
          $modelImage = $modelService->fkImage;
          if($modelImage)
            $image = $modelImage->uri_full;
        // if(isset($language)&&$language=='ml')
        //     {
        //         $name = $modelService->name_ml?$modelService->name_ml:$modelService->name;
        //     }
        //     else
        //     {
        //         $name = $modelService->name;
        //     }
                $ret[$accountId]['services'][] = [
                    'service_request_id' => $modelServiceRequest->id,
                    // 'service_name' => $name,
                    'service_name' => $modelService->name,
                    'service_id'         => $modelServiceRequest->service_id,
                    'image'              => $image,
                    'is_cancelled' => $modelServiceRequest->is_cancelled,
                    'status_option'=> $statusOptions,
                ];
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
                'name' => $modelCustomer->lead_person_name,
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
                 'name' => $modelCustomer->lead_person_name,
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
                 'name' => $modelCustomer->lead_person_name,
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
                        $modelPaymentRequest                      = new PaymentRequest;
                        $modelPaymentRequest->account_id_customer = $modelServiceRequest->account_id_customer;
                        $modelPaymentRequest->requested_date  = date('Y-m-d H:i:s');
                        $modelPaymentRequest->service_request_id = $modelServiceRequest->id;
                        $amount = $modelPaymentRequest->getSlabAmount($modelServiceRequest->service_id,$modelServiceRequest->account_id_customer,$modelServiceRequest->lsgi_id);
                        $modelPaymentRequest->amount = $amount * $modelServiceAssignment->quantity; 
                        $modelPaymentRequest->save(false);
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
             $customerDetails = [
                'account_id'=>$modelAccount->id,
                'customer_id' => $modelCustomer->id,
                'customer_id_formatted' => $modelCustomer->getFormattedCustomerId($modelCustomer->id),
                'name' => $modelCustomer->lead_person_name,
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
            'items'                     => $ret
        ];
    }
    public function actionDetails(
        $page = 1,
        $per_page = 30,
        $account_id = null,$status=null,$type=null,$language=null
    )
    {
        $ret = [];
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
            if($model->fkAccount->fkServiceRequest->fkServiceAssignment->servicing_status_option_id==null)
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
             $customerDetails = [
                'account_id'=>$modelAccount->id,
                'customer_id' => $model->id,
                'customer_id_formatted' => $model->getFormattedCustomerId($model->id),
                'name' => $model->lead_person_name,
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
            ];
            $ret[$accountId] = [
              'customer'=> $customerDetails,
              'services'=>[]
            ];
            
            $modelServiceRequests = ServiceRequest::find()->where(['account_id_customer'=>$modelAccount->id]);
            if($status&&$status=='completed'){
                $modelServiceRequests->leftJoin('service_assignment','service_assignment.service_request_id=service_request.id')
                ->andWhere(['>','service_assignment.servicing_status_option_id' ,0]);
            }
            if($status&&$status=='pending'){
                $modelServiceRequests->leftJoin('service_assignment','service_assignment.service_request_id=service_request.id')
                ->andWhere(['service_assignment.servicing_status_option_id'=>null]);
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
                $name = $modelServiceStatus->name_ml?$modelServiceStatus->name_ml:$model->value;
            }
             else
            {
                $name = $modelServiceStatus->value;
            }

                $statusOptions = [
                                    'id'=>$modelServiceStatus->id,
                                    'name'=>$name,
                                     // 'name'=>$modelServiceStatus->value,
                                    'ask_quantity'       => $modelServiceStatus->ask_waste_quantity?$modelServiceStatus->ask_waste_quantity:0,
                                    'ask_quality'        => $modelServiceStatus->ask_waste_quality?$modelServiceStatus->ask_waste_quality:0,
                        ];
            }
            // if(isset($language)&&$language=='ml')
            // {
            //     $name = $modelService->name_ml?$modelService->name_ml:$model->name;
            // }
            //  else
            // {
            //     $name = $modelService->name;
            // }
                $ret[$accountId]['services'][] = [
                    'service_request_id' => $modelServiceRequest->id,
                    // 'service_name' => $name,
                    'service_name' => $modelService->name,
                    'service_id'         => $modelServiceRequest->service_id,
                    'image'              => $image,
                    'is_cancelled' => $modelServiceRequest->is_cancelled,
                    // 'status_option'=> $statusOptions,
                    'status_option'=> $statusOptions,
                ];
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
}
