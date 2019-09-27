<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use backend\models\Account;
use yii\filters\VerbFilter;
use backend\models\Customer;
use backend\models\GreenActionUnit;
use backend\models\ServiceRequest;
use yii\data\ActiveDataProvider;
use api\modules\v1\models\ServiceAssignment;
use yii\filters\AccessControl;
use backend\components\AccessPermission;
use backend\models\AccountService;
ini_set("memory_limit","5000M");
/**
 * CameraController implements the CRUD actions for Camera model.
 */
class WmsDashboardController extends Controller
{
    /**
     * @inheritdoc
     */
     public function behaviors()
    {
        return [
           'access' => [
               'class' => AccessControl::className(),
               'only' => ['index','create','update','view'],
               'ruleConfig' => [
                       'class' => AccessPermission::className(),
                   ],
               'rules' => [
                   [
                       'actions' => ['index'],
                       'allow' => true,
                       'permissions' => ['wms-dashboard-index'],
                   ],
                   
               ],
               'denyCallback' => function($rule, $action) {
                   return $this->goHome();
               }
           ],
       ];
    }

    /**
     * Lists all Camera models.
     * @return mixed
     */
    public function actionIndex()
    {
        $lsgi     = null;
        $district = null;
        $ward     = null;
        $unit     = null;
        $gt       = null;
        $agency   = null;

         $pendingComplaintsCustomer= null;
         $totalComplaintsCustomer= null;
         $pendingServiceCustomer= null;
         $totalServiceCustomer= null;
           
        $modelUser    = Yii::$app->user->identity;
        $userRole     = $modelUser->role;
        $associations = Yii::$app->rbac->getAssociations($modelUser->id);
        if (isset($associations['lsgi_id']))
        {
            $lsgi = $associations['lsgi_id'];
        }
        if (isset($associations['district_id']))
        {
            $district = $associations['district_id'];
        }
        if (isset($associations['ward_id']))
        {
            $ward = $associations['ward_id'];
            $ward = json_decode($ward);
        }
        if (isset($associations['hks_id']))
        {
            $unit = $associations['hks_id'];
        }
        if (isset($associations['gt_id']))
        {
            $gt = $associations['gt_id'];
        }
        if (isset($associations['survey_agency_id']))
        {
            $agency = $associations['survey_agency_id'];
        }

        $modelAccountSupervisor = Account::getAllQuery()->andWhere(['role' => 'supervisor']);
        if ($unit||$ward)
        {
            $modelAccountSupervisor->leftJoin('green_action_unit', 'green_action_unit.id=account.green_action_unit_id')
                                   ->leftJoin('green_action_unit_ward', 'green_action_unit.id=green_action_unit_ward.green_action_unit_id');
                                   
                                   if($unit)
                           {
                            $modelAccountSupervisor->andWhere(['green_action_unit.id' => $unit]);
                           }
                           // if($ward)
                           // {
                           //  $modelAccountSupervisor->andWhere(['green_action_unit_ward.ward_id' => $ward]);
                           // }
                           if($ward)
                           {
                             $modelAccountSupervisor->leftjoin('account_ward','account_ward.account_id=account.id')
                            ->andWhere(['account_ward.ward_id' => $ward]);
                           }
        }

        $modelAccountSupervisor = $modelAccountSupervisor->all();
        $supervisorCount        = count($modelAccountSupervisor);

        $modelAccountGt = Account::find()->where(['account.status'=>1])->andWhere(['role' => 'green-technician']);
        if($unit||$ward)
        {
            $modelAccountGt
            ->leftJoin('green_action_unit', 'green_action_unit.id=account.green_action_unit_id')
                           ->leftJoin('green_action_unit_ward', 'green_action_unit.id=green_action_unit_ward.green_action_unit_id');
                           if($unit)
                           {
                            $modelAccountGt->andWhere(['account.green_action_unit_id' => $unit]);
                           }
                           // if($ward)
                           // {
                           //  $modelAccountGt->andWhere(['green_action_unit_ward.ward_id' => $ward]);
                           // }
                           if($ward)
                           {
                             $modelAccountGt->leftjoin('account_ward','account_ward.account_id=account.id')
                            ->andWhere(['account_ward.ward_id' => $ward]);
                           }
                           
        }

        $modelAccountGt = $modelAccountGt->all();
        // print_r($modelAccountGt);die();
        $gtCount        = count($modelAccountGt);

        $modelAccountSurveyor = Account::getAllQuery()->andWhere(['role' => 'surveyor']);
        if ($unit)
        {
            $modelAccountSurveyor->leftJoin('green_action_unit', 'green_action_unit.id=account.green_action_unit_id')
                                 ->leftJoin('green_action_unit_ward', 'green_action_unit.id=green_action_unit_ward.green_action_unit_id')
                                 ->andWhere(['green_action_unit.id' => $unit]);
        }

        $modelAccountSurveyor = $modelAccountSurveyor->all();
        $surveyorCount        = count($modelAccountSurveyor);

        $modelCustomer = Customer::find()->select([
            'customer.id'])->where(['customer.status' => 1]);
         if($unit)
        // if ($unit)
        {
           $modelUnit = GreenActionUnit::find()->where(['id'=>$unit])->andWhere(['status'=>1])->one();
            if($modelUnit)
            {
                $category = $modelUnit->residence_category_id;
            }
            else
            {
                $category = null;
            }
            $modelCustomer->leftJoin('green_action_unit_ward', 'customer.ward_id=green_action_unit_ward.ward_id')
                          ->leftJoin('green_action_unit', 'green_action_unit.id=green_action_unit_ward.green_action_unit_id')
                          // ->leftJoin('account', 'account.green_action_unit_id=green_action_unit.id')
                          ->leftjoin('building_type','building_type.id=customer.building_type_id')
            ->andWhere(['building_type.residence_category_id'=>$category])
            ->andWhere(['green_action_unit_ward.green_action_unit_id'=>$unit])
                          ->andWhere(['green_action_unit.id' => $unit])
                          ->groupby('customer.id');
        
        }
     if ($ward)
    {
          $modelCustomer->andWhere(['customer.ward_id'=>$ward]);
        }
        if ($userRole == 'supervisor')
        {
            $modelCustomer
                ->leftJoin('account', 'account.customer_id=customer.id')
                ->leftJoin('account_authority', 'account_authority.account_id_customer=account.id')
                ->andWhere(['account_authority.account_id_supervisor' => $modelUser->id])
                ->andWhere(['account_authority.status' => 1])
                ->andWhere(['account.status' => 1])
                ->groupby('customer.id');
        }
        if($userRole=='admin-hks')
        {
          $modelCustomer->andWhere(['door_status'=>1])
                        ->andWhere(['building_type_id'=>1]);
        }

        $modelCustomer = $modelCustomer->all();
        $customerCount = count($modelCustomer);

        $query = ServiceAssignment::getAllQuery()
        ->leftJoin('service_request', 'service_request.id=service_assignment.service_request_id')
        ->leftJoin('service', 'service_request.service_id=service.id')
        ->andWhere(['service.type' => 1])
        ->andWhere(['service.status' => 1])
        ->andWhere(['service_request.status' => 1])
         ->andWhere(['service_assignment.servicing_status_option_id' => null])
        ;
       // SELECT count(*) from service_request LEFT JOIN service_assignment on service_assignment.service_request_id=service_request.id LEFT JOIN service on service.id= service_request.service_id where service_assignment.status=1 and service_request.status=1 and service_assignment.servicing_status_option_id is null and service.status=1
        if ($unit||$ward)
        {
            $modelUnit = GreenActionUnit::find()->where(['id'=>$unit])->andWhere(['status'=>1])->one();
            if($modelUnit)
            {
                $category = $modelUnit->residence_category_id;
            }
            else
            {
                $category = null;
            }

            $query->leftJoin('green_action_unit_ward', 'service_request.ward_id=green_action_unit_ward.ward_id');
                  if($unit)
            {
                  $query->leftjoin('green_action_unit','green_action_unit.id=green_action_unit_ward.green_action_unit_id')
                  ->leftJoin('account', 'account.id=service_request.account_id_customer')
                  ->leftJoin('customer', 'customer.id=account.customer_id')
                          ->leftjoin('building_type','building_type.id=customer.building_type_id')
            ->andWhere(['building_type.residence_category_id'=>$category])
         ->andWhere(['green_action_unit_ward.green_action_unit_id'=>$unit])
                          ->andWhere(['green_action_unit.id' => $unit]);
                }if($ward)
                {
                  $query->andWhere(['green_action_unit_ward.ward_id' => $ward]);
                }
        }
        // $dataProvider = new ActiveDataProvider([
        //     'query' => $query
        // ]);
        // $models         = $dataProvider->getModels();
         $pendingService = 0;
        // foreach ($models as $model)
        // {
        //     // if ($model->servicing_status_option_id == null)
        //     // {
        //         $pendingService = $pendingService + 1;
        //     // }
        // }
         $query=$query->all();
        if ($query)
        {
            $pendingService = count($query);
        }
        $pendingquery = ServiceAssignment::getAllQuery()->leftJoin('service_request', 'service_request.id=service_assignment.service_request_id')->leftJoin('service', 'service_request.service_id=service.id')
                                                        ->andWhere(['service.type' => 2])
                                                         ->andWhere(['service.status' => 1])
        ->andWhere(['service_request.status' => 1])
      ->andWhere(['service_assignment.servicing_status_option_id' => null])
        ;
        // if ($unit)
        // {

        //     $pendingquery->leftJoin('green_action_unit_ward', 'service_request.ward_id=green_action_unit_ward.ward_id')
        //                  ->andWhere(['green_action_unit_ward.green_action_unit_id' => $unit]);
        // }
        if ($unit||$ward)
        {
            $pendingquery->leftJoin('green_action_unit_ward', 'service_request.ward_id=green_action_unit_ward.ward_id');
                  if($unit)
            {
                  $pendingquery->andWhere(['green_action_unit_ward.green_action_unit_id' => $unit]);
                }if($ward)
                {
                  $pendingquery->andWhere(['service_request.ward_id' => $ward]);
                }
        }
        // $pendingDataProvider = new ActiveDataProvider([
        //     'query' => $pendingquery
        // ]);
        $pendingComplaints = 0;
        // $models            = $pendingDataProvider->getModels();
        // foreach ($models as $model)
        // {
        //     if ($model->servicing_status_option_id == null)
        //     {
        //         $pendingComplaints = $pendingComplaints + 1;
        //     }
        // }
        $pendingquery=$pendingquery->all();
        if ($pendingquery)
        {
            $pendingComplaints = count($pendingquery);
        }
        if($userRole=='customer')
        {
           $query = ServiceAssignment::getAllQuery()->leftJoin('service_request', 'service_request.id=service_assignment.service_request_id')
           ->leftJoin('service', 'service_request.service_id=service.id')
            ->andWhere(['service.type' => 1])
            ->andWhere(['service_request.account_id_customer' => $modelUser->id])
            ->andWhere(['service.type' => 1])
            ;
            $dataProvider = new ActiveDataProvider([
            'query' => $query
        ]);
        $models         = $dataProvider->getModels();
        $totalServiceCustomer = 0;
        foreach ($models as $model)
        {
            $totalServiceCustomer =  $totalServiceCustomer+1;
        }
        $pendingServiceCustomer = 0;
        foreach ($models as $model)
        {
            if ($model->servicing_status_option_id == null)
            {
                $pendingServiceCustomer = $pendingServiceCustomer + 1;
            }
        }

        $pendingqueryCustomer = ServiceAssignment::getAllQuery()->leftJoin('service_request', 'service_request.id=service_assignment.service_request_id')
           ->leftJoin('service', 'service_request.service_id=service.id')
            ->andWhere(['service.type' => 1])
            ->andWhere(['service_request.account_id_customer' => $modelUser->id])
            ->andWhere(['service.type' => 2])
            ;
            $pendingCustomerDataProvider = new ActiveDataProvider([
            'query' => $pendingqueryCustomer
        ]);
        $models         = $pendingCustomerDataProvider->getModels();
        $totalComplaintsCustomer = 0;
        foreach ($models as $model)
        {
            $totalComplaintsCustomer =  $totalComplaintsCustomer+1;
        }
        $pendingComplaintsCustomer = 0;
        foreach ($models as $model)
        {
            if ($model->servicing_status_option_id == null)
            {
                $pendingComplaintsCustomer = $pendingComplaintsCustomer + 1;
            }
        }
        }








//         $modelCustomerPackageEnabled ="SELECT `customer`.`id` FROM `customer` LEFT JOIN `account` ON account.customer_id=customer.id LEFT JOIN `account_service` ON account_service.account_id=account.id  ";
//                if($unit)
//         {
//            $modelUnit = GreenActionUnit::find()->where(['id'=>$unit])->andWhere(['status'=>1])->one();
//             if($modelUnit)
//             {
//                 $category = $modelUnit->residence_category_id;
//             }
//             else
//             {
//                 $category = null;
//             }
//             $modelCustomerPackageEnabled.=" left join green_action_unit_ward on customer.ward_id=green_action_unit_ward.ward_id left join green_action_unit on green_action_unit_ward.green_action_unit_id=green_action_unit.id left join building_type on building_type.id=customer.building_type_id " ;        
        
//         }
//         if ($userRole == 'supervisor')
//         {
//             $modelCustomerPackageEnabled.=" left join account_authority on account_authority.account_id_customer=account.id ";
//         }
        
//         $modelCustomerPackageEnabled.=" WHERE customer.status=1 AND (`account_service`.`package_id` > 0) AND (`account_service`.`status`=1) AND (`account`.`status`=1) ";
//         if ($ward)
//         {
//           $modelCustomerPackageEnabled.=" and customer.ward_id in (:ward)";
//         }
//         if($unit)
//         {
//           $modelCustomerPackageEnabled.=" and building_type.residence_category_id=:category and green_action_unit_ward.green_action_unit_id=:unit and green_action_unit.id=:unit";
//         }
//         if($userRole=='supervisor')
//         {
//           $modelCustomerPackageEnabled.=" and account_authority.account_id_supervisor=:id and account_authority.status =1 and account.status=1";
//         }
//         $modelCustomerPackageEnabled.=" GROUP BY `account_service`.`account_id`, `account_service`.`package_id`";
// // print_r(sizeof($modelCustomerPackageEnabled));die();
//         $command =  Yii::$app->db->createCommand($modelCustomerPackageEnabled);
//         $id = $modelUser->id;
//         $wardNew = null;
//           if(isset($ward))
//            {
//             foreach ($ward as $key => $value) {
//              $wardNew = $wardNew.$value.',';
//             }
//             $wardList = rtrim($wardNew,',');
//            $command->bindParam(':ward',$wardList);
//            }
//            if(isset($unit))
//            {
//             $command->bindParam(':unit',$unit);
//             $command->bindParam(':category',$category);
//            }
//            if($userRole == 'supervisor')
//            {
//             $command->bindParam(':id',$id);
//            }
//           $modelCustomerPackageEnabled = $command->queryAll(); 
//         $packageEnabledCustomerCount = count($modelCustomerPackageEnabled);

         // $modelCustomerPackageEnabled = Customer::find()->select([
         //    'customer.id'])->where(['customer.status' => 1])->leftJoin('account', 'account.customer_id=customer.id')->leftjoin('account_service','account_service.account_id=account.id')->andWhere(['>','account_service.package_id',0])->andWhere(['account_service.status'=>1])->andWhere(['account.status'=>1])->groupby('account_service.account_id,account_service.package_id');
        $modelCustomerPackageEnabled =AccountService::find()
        ->leftjoin('account','account.id=account_service.account_id')
        ->leftjoin('customer','customer.id=account.customer_id')
        ->where(['account_service.status'=>1])
        ->andWhere(['account.status'=>1])
        ->andWhere(['customer.status'=>1])
        ->andWhere(['>','account_service.package_id',1])
        ->orderby('account_service.id ASC')
       ->groupby('account_service.account_id,account_service.package_id')
        ;
         if($unit)
        {
           $modelUnit = GreenActionUnit::find()->where(['id'=>$unit])->andWhere(['status'=>1])->one();
            if($modelUnit)
            {
                $category = $modelUnit->residence_category_id;
            }
            else
            {
                $category = null;
            }
            $modelCustomerPackageEnabled->leftJoin('green_action_unit_ward', 'customer.ward_id=green_action_unit_ward.ward_id')
                          ->leftJoin('green_action_unit', 'green_action_unit.id=green_action_unit_ward.green_action_unit_id')
                          // ->leftJoin('account', 'account.green_action_unit_id=green_action_unit.id')
                          ->leftjoin('building_type','building_type.id=customer.building_type_id')
            ->andWhere(['building_type.residence_category_id'=>$category])
            ->andWhere(['green_action_unit_ward.green_action_unit_id'=>$unit])
                          ->andWhere(['green_action_unit.id' => $unit])
                          ->groupby('customer.id');
        
        }
     if ($ward)
    {
          $modelCustomerPackageEnabled->andWhere(['customer.ward_id'=>$ward]);
        }
        if ($userRole == 'supervisor')
        {
            $modelCustomerPackageEnabled
                ->leftJoin('account_authority', 'account_authority.account_id_customer=account.id')
                ->andWhere(['account_authority.account_id_supervisor' => $modelUser->id])
                ->andWhere(['account_authority.status' => 1])
                ->andWhere(['account.status' => 1])
                ->groupby('customer.id');
        }

        $modelCustomerPackageEnabled = $modelCustomerPackageEnabled->all();
        $packageEnabledCustomerCount = count($modelCustomerPackageEnabled);

        return $this->render('index', [
            'supervisorCount'   => $supervisorCount,
            'gtCount'           => $gtCount,
            'surveyorCount'     => $surveyorCount,
            'customerCount'     => $customerCount,
            'pendingService'    => $pendingService,
            'pendingComplaints' => $pendingComplaints,
            'pendingComplaintsCustomer' => $pendingComplaintsCustomer,
            'totalComplaintsCustomer' => $totalComplaintsCustomer,
            'pendingServiceCustomer' => $pendingServiceCustomer,
            'totalServiceCustomer' => $totalServiceCustomer,
            'packageEnabledCustomerCount' => $packageEnabledCustomerCount,
            'userRole'          => $userRole
        ]);
    }

    /**
     * @param  $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id)
        ]);
    }

       public function actionViewReports($set=null){
      $post = yii::$app->request->post();
      $get = yii::$app->request->get();
      $params  = array_merge($post,$get);
        $ward=null;
        $from=null;
        $to=null;
      $vars = [ 
    'ward',
    'from',
    'to' 
    ];
    $newParams = [];
     if($set==null)
        {
            $session = Yii::$app->session;
            $session->destroy();
        }
        else{
    $session = Yii::$app->session;
    foreach($vars as $param) {
      ${$param} = isset($params[$param])?$params[$param]:null;
      $newParams[$param] = ${$param};
      if(${$param} !== null) {
      $session->set($param,${$param});
      }
    } 
    $ward = Yii::$app->session->get('ward');
    $from          = Yii::$app->session->get('from');
    $to            = Yii::$app->session->get('to');
    
    $from              = $from ? \Yii::$app->formatter->asDatetime($from, "php:Y-m-d 00:00:00") : '';
    $to                = $to ? \Yii::$app->formatter->asDatetime($to, "php:Y-m-d 23:59:59") : '';
}
      return $this->render('list', [
            'ward'  => $ward,
            'from' => $from,
            'to'  => $to,
        ]);
     }
}
