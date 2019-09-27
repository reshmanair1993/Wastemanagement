<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use backend\models\Account;
use yii\filters\VerbFilter;
use backend\models\Customer;
use yii\data\ActiveDataProvider;
use backend\models\EscalationSettings;
use backend\models\Ward;
use backend\models\Payment;
use backend\models\AccountService;
use backend\models\ServiceRequest;
use backend\models\Service;
use backend\models\ServiceRequestSearch;
use yii\filters\AccessControl;
use backend\components\AccessPermission;
use yii\data\ArrayDataProvider;
/**
 * OfficeTypeController implements the CRUD actions for OfficeType model.
 */
class EscalationsController extends Controller
{
    /**
     * {@inheritdoc}
     */
    // public function behaviors()
    // {
    //     return [
    //        'access' => [
    //            'class' => AccessControl::className(),
    //            'only' => ['index','door-close','survey-count','waste-management-type','ward-wise-count'],
    //            'ruleConfig' => [
    //                    'class' => AccessPermission::className(),
    //                ],
    //            'rules' => [
    //                [
    //                    'actions' => ['index'],
    //                    'allow' => true,
    //                    'permissions' => ['reports-index'],
    //                ],
    //                [
    //                    'actions' => ['survey-count'],
    //                    'allow' => true,
    //                    'permissions' => ['reports-survey-count'],
    //                ],
    //                [
    //                    'actions' => ['waste-management-type'],
    //                    'allow' => true,
    //                    'permissions' => ['reports-waste-management-type'],
    //                ],
    //                [
    //                    'actions' => ['ward-wise-count'],
    //                    'allow' => true,
    //                    'permissions' => ['reports-ward-wise-count'],
    //                ],
    //            ],
    //            'denyCallback' => function($rule, $action) {
    //                return $this->goHome();
    //            }
    //        ],
    //    ];
    // }
    public function behaviors()
    {
        return [
            'verbs' => [
                'class'   => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST']
                ]
            ]
        ];
    }

    public function actionComplaints($set=null)
    {
        $post = yii::$app->request->post();
      $get = yii::$app->request->get();
      $params  = array_merge($post,$get);
        $keyword=null;
        $ward=null;
        $lsgi=null; 
        $unit=null; 
        $supervisor=null; 
        $status=null; 
        $gt=null; 
        $association=null; 
        $from=null;
        $to=null;
        $agency=null;
        $service=null;
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
        if(isset($associations['district_id']))
        {
            $district = $associations['district_id'];
        }
        if(isset($associations['ward_id'])&&!$ward)
        {
            $ward = $associations['ward_id'];
            $ward = json_decode($ward);
    if($ward)
    {
        $ward  = implode(',', $ward);
    }
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
      $vars = [ 
    
    'name',
    'keyword', 
    'ward', 
    'supervisor', 
    'lsgi', 
    'service', 
    'gt', 
    'association',
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
    $keyword = Yii::$app->session->get('keyword');
    $ward = Yii::$app->session->get('ward');
    $service = Yii::$app->session->get('service');
    $supervisor = Yii::$app->session->get('supervisor');
    $lsgi = Yii::$app->session->get('lsgi');
    $gt = Yii::$app->session->get('gt');
    $association = Yii::$app->session->get('association');
    $from          = Yii::$app->session->get('from');
    $to            = Yii::$app->session->get('to');
    
    $from              = $from ? \Yii::$app->formatter->asDatetime($from, "php:Y-m-d H:i:s") : '';
    $to                = $to ? \Yii::$app->formatter->asDatetime($to, "php:Y-m-d 23:59:00") : '';
}
        $qry = "SELECT service_request.requested_datetime,service_request.id,service_request.account_id_customer,service.name,service_assignment.account_id_gt FROM `service_request` LEFT JOIN `service` ON service.id=service_request.service_id LEFT JOIN `service_assignment` ON service_assignment.service_request_id=service_request.id LEFT JOIN `lsgi` ON lsgi.id=service_request.lsgi_id LEFT JOIN `escalation_setttings` ON lsgi.id=escalation_setttings.lsgi_id LEFT JOIN account on account.id=service_request.account_id_customer LEFT JOIN customer on customer.id=account.customer_id LEFT JOIN ward on ward.id=customer.ward_id LEFT JOIN green_action_unit_ward on green_action_unit_ward.ward_id=customer.ward_id LEFT JOIN account_authority on account_authority.account_id_customer=account.id  WHERE (`service_request`.`status`=1) AND (`service_assignment`.`servicing_status_option_id` IS NULL) AND (`service`.`type`=2) and  TIMESTAMPDIFF(MINUTE,service_request.created_at,NOW()) > escalation_setttings.service_escalation_min and escalation_setttings.status=1 and service_assignment.status=1 and account.status=1
       ";
    if($keyword||$ward||$unit||$lsgi)
    {
        $qry.= " and customer.status=1";
        if($ward)
        {
           $qry.= " and customer.ward_id IN (:ward)" ;
        }
         if($lsgi!=null)
            {
                $qry.= " and ward.status = 1 and lsgi.status=1 and lsgi.id=:lsgi";
            }
         if($unit!=null)
        {
            $qry.=" and green_action_unit_ward.green_action_unit_id=:unit";
        }

    }
    if($service)
    {
         $qry.=" and service.id=:service";

    }
    if($userRole!='super-admin')
        $qry.=" AND (`escalation_setttings`.`role`=:role)";

    if($userRole=='supervisor')
        $qry.=" and account_authority.account_id_supervisor=:userId";

     $qry.=" GROUP BY service_request.id  
     ORDER BY service_request.id DESC  ";
        $command =  Yii::$app->db->createCommand($qry);
        if($userRole!='super-admin')
         $command->bindParam(':role',$userRole);
        if($ward)
        $command->bindParam(':ward',$ward);
     if($lsgi)
        $command->bindParam(':lsgi',$lsgi);
     if($unit)
        $command->bindParam(':unit',$unit);
    if($service)
        $command->bindParam(':service',$service);
      $userId = $modelUser->id;
    if($userRole=='supervisor')
        $command->bindParam(':userId',$userId);

        $customersList = $command->queryAll();
        $modelServiceRequest = new ServiceRequest;
        $dataProvider = new ArrayDataProvider([
        'allModels' =>$customersList,
          ]);
          // $dataProvider->pagination = 50;
           return $this->render('complaints', [
            'dataProvider' => $dataProvider,
            'modelServiceRequest' => $modelServiceRequest,
        ]);
    }
    
//     public function actionService($set=null)
//     {
//        $post = yii::$app->request->post();
//       $get = yii::$app->request->get();
//       $params  = array_merge($post,$get);
//         $keyword=null;
//         $ward=null;
//         $lsgi=null; 
//         $unit=null; 
//         $supervisor=null; 
//         $status=null; 
//         $gt=null; 
//         $association=null; 
//         $from=null;
//         $to=null;
//         $agency=null;
//         $service=null;
//          $modelUser  = Yii::$app->user->identity;
//              $userRole = $modelUser->role;
//         $associations = Yii::$app->rbac->getAssociations($modelUser->id);
//         if(isset($associations['lsgi_id']))
//         {
//             $lsgi = $associations['lsgi_id'];
//         }
//         if(isset($associations['district_id']))
//         {
//             $district = $associations['district_id'];
//         }
//         if(isset($associations['district_id']))
//         {
//             $district = $associations['district_id'];
//         }
//         if(isset($associations['ward_id']))
//         {
//             $ward = $associations['ward_id'];
//             $ward = json_decode($ward);
//         }
//         if(isset($associations['hks_id']))
//         {
//             $unit = $associations['hks_id'];
//         }
//         if(isset($associations['gt_id']))
//         {
//             $gt = $associations['gt_id'];
//         }
//         if(isset($associations['survey_agency_id']))
//         {
//             $agency = $associations['survey_agency_id'];
//         }
//       $vars = [ 
    
//     'name',
//     'keyword', 
//     'ward', 
//     'supervisor', 
//     'lsgi', 
//     'service', 
//     'gt', 
//     'association',
//     'from',
//     'to' 
//     ];
//     $newParams = [];
//      if($set==null)
//         {
//             $session = Yii::$app->session;
//             $session->destroy();
//         }
//         else{
//     $session = Yii::$app->session;
//     foreach($vars as $param) {
//       ${$param} = isset($params[$param])?$params[$param]:null;
//       $newParams[$param] = ${$param};
//       if(${$param} !== null) {
//       $session->set($param,${$param});
//       }
//     } 
//     $keyword = Yii::$app->session->get('keyword');
//     $ward = Yii::$app->session->get('ward');
//     $service = Yii::$app->session->get('service');
//     $supervisor = Yii::$app->session->get('supervisor');
//     $lsgi = Yii::$app->session->get('lsgi');
//     $gt = Yii::$app->session->get('gt');
//     $association = Yii::$app->session->get('association');
//     $from          = Yii::$app->session->get('from');
//     $to            = Yii::$app->session->get('to');
    
//     $from              = $from ? \Yii::$app->formatter->asDatetime($from, "php:Y-m-d H:i:s") : '';
//     $to                = $to ? \Yii::$app->formatter->asDatetime($to, "php:Y-m-d 23:59:00") : '';
// }
//         $qry = "SELECT service_request.id,service_request.account_id_customer,service.name,service_assignment.account_id_gt FROM `service_request` LEFT JOIN `service` ON service.id=service_request.service_id LEFT JOIN `service_assignment` ON service_assignment.service_request_id=service_request.id LEFT JOIN `lsgi` ON lsgi.id=service_request.lsgi_id LEFT JOIN `escalation_setttings` ON lsgi.id=escalation_setttings.lsgi_id ";
//     if($keyword||$ward||$unit||$lsgi)
//     {
//         $qry.= "LEFT JOIN account on account.id=service_request.account_id_customer LEFT JOIN customer on customer.id=account.customer_id and customer.status=1 and account.status=1";
//         if($ward)
//         {
//            $qry.= " and customer.ward_id=:ward" ;
//         }
//          if($lsgi!=null)
//             {
//                 $qry.= "LEFT JOIN ward on ward.id=customer.ward_id LEFT JOIN lsgi on lsgi.id=ward.lsgi_id and ward.status = 1 and lsgi.status=1 and lsgi.id=:lsgi";
//             }
//          if($unit!=null)
//         {
//             $qry.="LEFT JOIN green_action_unit_ward on green_action_unit_ward.ward_id=customer.ward_id and green_action_unit_ward.green_action_unit_id'=:unit";
//         }

//     }
//     if($service)
//     {
//          $qry.=" and service.id=:service";

//     }
//     if($userRole!='super-admin')
//         $qry.=" AND (`escalation_setttings`.`role`=:role)";

//     $qry.=" WHERE (`service_request`.`status`=1) AND (`service_assignment`.`servicing_status_option_id` IS NULL) AND (`service`.`type`=1) and  NOW()-service_request.created_at > escalation_setttings.service_escalation_min*60 and escalation_setttings.status=1 and service_assignment.status=1
//      ORDER BY service_request.id DESC  ";
//         $command =  Yii::$app->db->createCommand($qry);
//         if($userRole!='super-admin')
//          $command->bindParam(':role',$userRole);
//         if($ward)
//         $command->bindParam(':ward',$ward);
//      if($lsgi)
//         $command->bindParam(':lsgi',$lsgi);
//      if($unit)
//         $command->bindParam(':unit',$unit);
//     if($service)
//         $command->bindParam(':service',$service);

//         $customersList = $command->queryAll();
//         $modelServiceRequest = new ServiceRequest;
//         $dataProvider = new ArrayDataProvider([
//         'allModels' =>$customersList,
//           ]);
//           // $dataProvider->pagination = 50;
//            return $this->render('service', [
//             'dataProvider' => $dataProvider,
//             'modelServiceRequest' => $modelServiceRequest,
//         ]);
//     }
    public function actionService($set=null)
    {
        $post = yii::$app->request->post();
      $get = yii::$app->request->get();
      $params  = array_merge($post,$get);
        $keyword=null;
        $ward=null;
        $lsgi=null; 
        $unit=null; 
        $supervisor=null; 
        $status=null; 
        $gt=null; 
        $association=null; 
        $from=null;
        $to=null;
        $agency=null;
        $service=null;
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
        if(isset($associations['district_id']))
        {
            $district = $associations['district_id'];
        }
        if(isset($associations['ward_id'])&&!$ward)
        {
            $ward = $associations['ward_id'];
            $ward = json_decode($ward);
    if($ward)
    {
        $ward  = implode(',', $ward);
    }
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
      $vars = [ 
    
    'name',
    'keyword', 
    'ward', 
    'supervisor', 
    'lsgi', 
    'service', 
    'gt', 
    'association',
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
    $keyword = Yii::$app->session->get('keyword');
    $wardIds = Yii::$app->session->get('ward');
    $service = Yii::$app->session->get('service');
    $supervisor = Yii::$app->session->get('supervisor');
    $lsgi = Yii::$app->session->get('lsgi');
    $gt = Yii::$app->session->get('gt');
    $association = Yii::$app->session->get('association');
    $from          = Yii::$app->session->get('from');
    $to            = Yii::$app->session->get('to');
    
    $from              = $from ? \Yii::$app->formatter->asDatetime($from, "php:Y-m-d H:i:s") : '';
    $to                = $to ? \Yii::$app->formatter->asDatetime($to, "php:Y-m-d 23:59:00") : '';
}
        $qry = "SELECT service_request.requested_datetime,service_request.id,service_request.account_id_customer,service.name,service_assignment.account_id_gt FROM `service_request` LEFT JOIN `service` ON service.id=service_request.service_id LEFT JOIN `service_assignment` ON service_assignment.service_request_id=service_request.id LEFT JOIN `lsgi` ON lsgi.id=service_request.lsgi_id LEFT JOIN `escalation_setttings` ON lsgi.id=escalation_setttings.lsgi_id LEFT JOIN account on account.id=service_request.account_id_customer LEFT JOIN customer on customer.id=account.customer_id LEFT JOIN ward on ward.id=customer.ward_id LEFT JOIN green_action_unit_ward on green_action_unit_ward.ward_id=customer.ward_id LEFT JOIN account_authority on account_authority.account_id_customer=account.id WHERE (`service_request`.`status`=1) AND (`service_assignment`.`servicing_status_option_id` IS NULL) AND (`service`.`type`=1) and  TIMESTAMPDIFF(MINUTE,service_request.created_at,NOW())> escalation_setttings.service_escalation_min and escalation_setttings.status=1 and service_assignment.status=1 and account.status=1 and account_authority.status=1
    ";
    if($keyword||$ward||$unit||$lsgi)
    {
        $qry.= " and customer.status=1";
        if($ward)
        {
           $qry.= " and customer.ward_id IN (:ward)" ;
        }
         if($lsgi!=null)
            {
                $qry.= " and ward.status = 1 and lsgi.status=1 and lsgi.id=:lsgi";
            }
         if($unit!=null)
        {
            $qry.=" and green_action_unit_ward.green_action_unit_id=:unit";
        }

    }
    if($service)
    {
         $qry.=" and service.id=:service";

    }
   if($userRole=='supervisor')
        $qry.=" and account_authority.account_id_supervisor=:userId";

    if($userRole!='super-admin')
        $qry.=" AND (`escalation_setttings`.`role`=:role)";
     $qry.=" GROUP BY service_request.id  
     ORDER BY service_request.id DESC  ";
        $command =  Yii::$app->db->createCommand($qry);
        if($userRole!='super-admin')
         $command->bindParam(':role',$userRole);
        if($ward)
        $command->bindParam(':ward',$ward);
     if($lsgi)
        $command->bindParam(':lsgi',$lsgi);
     if($unit)
        $command->bindParam(':unit',$unit);
    if($service)
        $command->bindParam(':service',$service);
    $userId = $modelUser->id;
    if($userRole=='supervisor')
        $command->bindParam(':userId',$userId);
        $customersList = $command->queryAll();
        $modelServiceRequest = new ServiceRequest;
        $dataProvider = new ArrayDataProvider([
        'allModels' =>$customersList,
          ]);
          // $dataProvider->pagination = 50;
           return $this->render('service', [
            'dataProvider' => $dataProvider,
            'modelServiceRequest' => $modelServiceRequest,
        ]);
    }
   
}
