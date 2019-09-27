<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use backend\models\Account;
use yii\filters\VerbFilter;
use backend\models\Customer;
use yii\data\ActiveDataProvider;
use backend\models\CustomerSearch;
use backend\models\Ward;
use backend\models\Payment;
use backend\models\PaymentRequest;
use backend\models\AccountService;
use backend\models\ServiceRequest;
use backend\models\Service;
use backend\models\WasteQuality;
use backend\models\ServiceRequestSearch;
use backend\models\GreenActionUnit;
use yii\filters\AccessControl;
use backend\components\AccessPermission;
/**
 * OfficeTypeController implements the CRUD actions for OfficeType model.
 */
class ReportsController extends Controller
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

    /**
     * Lists all OfficeType models.
     * @return mixed
     */
    public function actionDoorClose()
    {
    $post = yii::$app->request->post();
      $get = yii::$app->request->get();
      $params  = array_merge($post,$get);
      $vars = [ 
    
    'name',
    'keyword', 
    'district', 
    'ward', 
    'door', 
    'lsgi',
    'from',
    'to' 
    ];
    $newParams = [];
    $session = Yii::$app->session;
    foreach($vars as $param) {
      ${$param} = isset($params[$param])?$params[$param]:null;
      $newParams[$param] = ${$param};
      if(${$param} !== null) {
      $session->set($param,${$param});
      }
    } 
    $keyword = Yii::$app->session->get('keyword');
    $district = Yii::$app->session->get('district');
    $ward = Yii::$app->session->get('ward');
    // $door = Yii::$app->session->get('door');
    $lsgi = Yii::$app->session->get('lsgi');
    $from = Yii::$app->session->get('from');
    $to   = Yii::$app->session->get('to');
    
    $from              = $from ? \Yii::$app->formatter->asDatetime($from, "php:Y-m-d H:i:s") : '';
    $to                = $to ? \Yii::$app->formatter->asDatetime($to, "php:Y-m-d 23:59:59") : '';
    $modelCustomer = new Customer;
    $searchModel   = new CustomerSearch();
    // $dataProvider  = $searchModel->getAllQuery(Yii::$app->request->queryParams, $keyword, $ward, $lsgi, $district, $door, $from, $to);
    $dataProvider  = $searchModel->getAllQuery(Yii::$app->request->queryParams, $keyword, $ward, $lsgi, $district, $from, $to);
    $modelUser  = Yii::$app->user->identity;
    $associations = Yii::$app->rbac->getAssociations($modelUser->id);
        return $this->render('door-close-list', [
            'searchModel'   => $searchModel,
            'dataProvider'  => $dataProvider,
            'modelCustomer' => $modelCustomer,
            'associations'  => $associations,
        ]);
    }

    /**
     * @return mixed
     */
    public function actionSurveyCount()
    {
        $keyword = null;
        $from = null;
        $to = null;
        $agency = null;
        $ward = null;
        if (isset($_POST['name']))
        {
            $keyword = $_POST['name'];
        }
        if (isset($_POST['agency']))
        {
            $agency = $_POST['agency'];
        }
        if (isset($_POST['ward']))
        {
            $ward = $_POST['ward'];
        }
         if (isset($_POST['from']))
        {
            $from = $_POST['from'];
                $from              = $from ? \Yii::$app->formatter->asDatetime($from, "php:Y-m-d 00:00:00") : '';

        }
        if (isset($_POST['to']))
        {
            $to = $_POST['to'];
                $to                = $to ? \Yii::$app->formatter->asDatetime($to, "php:Y-m-d 23:59:59") : '';

        }
        // $session = Yii::$app->session;
        // $session->set('start', $from);
        // $session->set('end', $to);
  
        $modelAccount = new Account;
        $dataProvider = new ActiveDataProvider(
            [
                'query'      => Account::getAllQuerySurvey($keyword,$agency)->andWhere(['role' => 'surveyor']),
                'pagination' => false,
                'sort'       => ['defaultOrder' => ['id' => SORT_DESC]]
            ]);
        

        return $this->render('survey-count', [
            'dataProvider' => $dataProvider,
            'modelAccount' => $modelAccount,
            'from'         =>$from,
            'to'           =>$to]);
    }

    /**
     * @return mixed
     */
    public function actionWasteManagementType()
    {
      $post = yii::$app->request->post();
      $get = yii::$app->request->get();
      $params  = array_merge($post,$get);
      $vars = [ 
    
    'name',
    'keyword', 
    'district', 
    'ward', 
    'door', 
    'lsgi', 
    'non_bio_waste', 
    'bio_waste',
    'from',
    'to' 
    ];
    $newParams = [];
    $session = Yii::$app->session;
    foreach($vars as $param) {
      ${$param} = isset($params[$param])?$params[$param]:null;
      $newParams[$param] = ${$param};
      if(${$param} !== null) {
      $session->set($param,${$param});
      }
    } 
    $keyword = Yii::$app->session->get('keyword');
    $district = Yii::$app->session->get('district');
    $ward = Yii::$app->session->get('ward');
    $door = Yii::$app->session->get('door');
    $lsgi = Yii::$app->session->get('lsgi');
    $bioWaste = Yii::$app->session->get('bio_waste');
    $nonBioWaste = Yii::$app->session->get('non_bio_waste');
    $from          = Yii::$app->session->get('from');
    $to            = Yii::$app->session->get('to');
    
    $from              = $from ? \Yii::$app->formatter->asDatetime($from, "php:Y-m-d H:i:s") : '';
    $to                = $to ? \Yii::$app->formatter->asDatetime($to, "php:Y-m-d 23:59:59") : '';
        $modelCustomer = new Customer;
        $searchModel   = new CustomerSearch();
        $dataProvider  = $searchModel->getAllQueryType(Yii::$app->request->queryParams, $keyword, $ward, $lsgi, $district, $door, $bioWaste, $nonBioWaste, $from, $to);
        $modelUser  = Yii::$app->user->identity;
        $associations = Yii::$app->rbac->getAssociations($modelUser->id);
        return $this->render('waste-management-type', [
            'searchModel'   => $searchModel,
            'dataProvider'  => $dataProvider,
            'modelCustomer' => $modelCustomer,
            'associations'  => $associations
        ]);
    }
    public function actionWardWiseCount()
    {
        $keyword = null;
        $from = null;
        $to = null;
        $ward = null;
        if (isset($_POST['name']))
        {
            $keyword = $_POST['name'];
        }
        if (isset($_POST['ward']))
        {
            $ward = $_POST['ward'];
        }
         if (isset($_POST['from']))
        {
            $from = $_POST['from'];
                $from              = $from ? \Yii::$app->formatter->asDatetime($from, "php:Y-m-d 00:00:00") : '';

        }
        if (isset($_POST['to']))
        {
            $to = $_POST['to'];
                $to                = $to ? \Yii::$app->formatter->asDatetime($to, "php:Y-m-d 23:59:00") : '';

        }
        $session = Yii::$app->session;
        $session->set('start', $from);
        $session->set('end', $to);
  
        $modelWard = new Ward;
        $dataProvider = new ActiveDataProvider(
            [
                'query'      => $modelWard->getAllQuery($keyword,$from, $to,$ward),
                'pagination' => false,
                'sort'       => ['defaultOrder' => ['id' => SORT_DESC]]
            ]);
        
        $modelUser  = Yii::$app->user->identity;
        $associations = Yii::$app->rbac->getAssociations($modelUser->id);
        return $this->render('ward-wise-count', [
            'dataProvider' => $dataProvider,
            'modelWard' => $modelWard,
            'from'         =>$from,
            'to'           =>$to,
            'associations'           =>$associations,
            ]);
    }

    public function actionCollection($set = null)
    {
      $post = yii::$app->request->post();
      $get = yii::$app->request->get();
      $params  = array_merge($post,$get);
        $keyword=null;
        $ward=null;
        $lsgi=null; 
        $supervisor=null; 
        $gt=null; 
        $association=null; 
        $from=null;
        $to=null;
      $vars = [ 
    
    'name',
    'keyword', 
    'ward', 
    'supervisor', 
    'lsgi', 
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
    $supervisor = Yii::$app->session->get('supervisor');
    $lsgi = Yii::$app->session->get('lsgi');
    $gt = Yii::$app->session->get('gt');
    $association = Yii::$app->session->get('association');
    $from          = Yii::$app->session->get('from');
    $to            = Yii::$app->session->get('to');
    
    $from              = $from ? \Yii::$app->formatter->asDatetime($from, "php:Y-m-d H:i:s") : '';
    $to                = $to ? \Yii::$app->formatter->asDatetime($to, "php:Y-m-d 23:59:59") : '';
}
// print_r($association);die();
        $modelPayment = new Payment;
        $collectionQuery  = $modelPayment->getAllQueryCollection($keyword, $ward, $lsgi, $supervisor, $gt, $association, $from, $to);
        $dataProvider = new ActiveDataProvider([
        'query' => $collectionQuery,
      ]);
        $modelUser  = Yii::$app->user->identity;
        $associations = Yii::$app->rbac->getAssociations($modelUser->id);
        return $this->render('collection', [
            'dataProvider'  => $dataProvider,
            'modelPayment' => $modelPayment,
            'associations'  => $associations
        ]);
    }
    public function actionSubscriptions($set = null)
    {
      $post = yii::$app->request->post();
      $get = yii::$app->request->get();
      $params  = array_merge($post,$get);
        $keyword=null;
        $ward=null;
        $lsgi=null; 
        $supervisor=null; 
        $gt=null; 
        $association=null; 
        $from=null;
        $to=null;
        $service=null;
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
    $keyword = Yii::$app->session->get('name');
    $ward = Yii::$app->session->get('ward');
    $service = Yii::$app->session->get('service');
    $supervisor = Yii::$app->session->get('supervisor');
    $lsgi = Yii::$app->session->get('lsgi');
    $gt = Yii::$app->session->get('gt');
    $association = Yii::$app->session->get('association');
    $from          = Yii::$app->session->get('from');
    $to            = Yii::$app->session->get('to');
    
    $from              = $from ? \Yii::$app->formatter->asDatetime($from, "php:Y-m-d 00:00:00") : '';
    $to                = $to ? \Yii::$app->formatter->asDatetime($to, "php:Y-m-d 23:59:00") : '';
}
// print_r($association);die();
        $modelAccountService = new AccountService;
        $collectionQuery  = $modelAccountService->getAllQuerySubscription($keyword, $ward, $lsgi, $supervisor, $gt, $association, $from, $to,$service);
        $dataProvider = new ActiveDataProvider([
        'query' => $collectionQuery,
      ]);
        $modelUser  = Yii::$app->user->identity;
        $associations = Yii::$app->rbac->getAssociations($modelUser->id);
        return $this->render('subscriptions', [
            'dataProvider'  => $dataProvider,
            'modelAccountService' => $modelAccountService,
            'associations'  => $associations
        ]);
    }
    public function actionComplaints()
    {
        $post   = yii::$app->request->post();
        $get    = yii::$app->request->get();
        $params = array_merge($post, $get);
        $vars   = [

            'name',
            'service',
            'gt',
            'from',
            'to',
            'status',
            'ward',
        ];
        $newParams = [];
        $session   = Yii::$app->session;
        foreach ($vars as $param)
        {
            ${
                $param}          = isset($params[$param]) ? $params[$param] : null;
            $newParams[$param] = ${
                $param};
        }
         $keyword      = null;
        $service         = null;
        $status         = null;
        $from         = null;
        $to         = null;
        $gt         = null;
        $ward         = null;
        $post         = Yii::$app->request->post();
        if (isset($post['service']))
        {
            $service = $post['service'];
        }
        if (isset($post['status']))
        {
            $status = $post['status'];
        }
        if (isset($post['ward']))
        {
            $ward = $post['ward'];
        }
        if (isset($post['gt']))
        {
            $gt = $post['gt'];
        }
        if (isset($_POST['name']))
        {
            $keyword = $_POST['name'];
        }
        if (isset($_POST['from']))
        {
            $from = $_POST['from'];
        }
        if (isset($_POST['to']))
        {
            $to = $_POST['to'];
        }
        $type=2;

        $modelServiceRequest = new ServiceRequest;
        $searchModel = new ServiceRequestSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,$keyword,$service,$gt,$from,$to,$type,$status,$ward,$newParams);

        return $this->render('complaints', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'modelServiceRequest' => $modelServiceRequest,
        ]);
    }
    public function actionCollectionSummary($set = null)
    {
      $post = yii::$app->request->post();
      $get = yii::$app->request->get();
      $params  = array_merge($post,$get);
        $keyword=null;
        $ward=null;
        $lsgi=null; 
        $supervisor=null; 
        $gt=null; 
        $association=null; 
        $from=null;
        $to=null;
      $vars = [ 
    
    'name',
    'keyword', 
    'ward', 
    'supervisor', 
    'lsgi', 
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
    $supervisor = Yii::$app->session->get('supervisor');
    $lsgi = Yii::$app->session->get('lsgi');
    $gt = Yii::$app->session->get('gt');
    $association = Yii::$app->session->get('association');
    $from          = Yii::$app->session->get('from');
    $to            = Yii::$app->session->get('to');
    
    $from              = $from ? \Yii::$app->formatter->asDatetime($from, "php:Y-m-d H:i:s") : '';
    $to                = $to ? \Yii::$app->formatter->asDatetime($to, "php:Y-m-d H:i:s") : '';
}
        $session = Yii::$app->session;
        $session->set('start', $from);
        $session->set('end', $to);
        $modelPayment = new Payment;
        $dataProvider = new ActiveDataProvider(
            [
                'query'      => $modelPayment->getAllQueryCollectionSummary($keyword, $ward, $lsgi, $supervisor, $gt, $association, $from, $to),
                'pagination' => false,
                'sort'       => ['defaultOrder' => ['id' => SORT_DESC]]
            ]);
        
        $modelUser  = Yii::$app->user->identity;
        $associations = Yii::$app->rbac->getAssociations($modelUser->id);
        return $this->render('collection-summary', [
            'dataProvider' => $dataProvider,
            'modelPayment' => $modelPayment,
            'from'         =>$from,
            'to'           =>$to,
            'associations'           =>$associations,
            ]);
    }

//     public function actionSubscriptionSummary($set = null)
//     {
//       $post = yii::$app->request->post();
//       $get = yii::$app->request->get();
//       $params  = array_merge($post,$get);
//         $keyword=null;
//         $ward=null;
//         $lsgi=null; 
//         $supervisor=null; 
//         $gt=null; 
//         $association=null; 
//         $from=null;
//         $to=null;
//       $vars = [ 
    
//     'name',
//     'keyword', 
//     'ward', 
//     'supervisor', 
//     'lsgi', 
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
//     $supervisor = Yii::$app->session->get('supervisor');
//     $lsgi = Yii::$app->session->get('lsgi');
//     $gt = Yii::$app->session->get('gt');
//     $association = Yii::$app->session->get('association');
//     $from          = Yii::$app->session->get('from');
//     $to            = Yii::$app->session->get('to');
    
//     $from              = $from ? \Yii::$app->formatter->asDatetime($from, "php:Y-m-d H:i:s") : '';
//     $to                = $to ? \Yii::$app->formatter->asDatetime($to, "php:Y-m-d H:i:s") : '';
// }
//         $session = Yii::$app->session;
//         $session->set('start', $from);
//         $session->set('end', $to);
//         $modelAccountService = new AccountService;
//         $dataProvider = new ActiveDataProvider(
//             [
//                 'query'      => $modelAccountService->getAllQuerySubscriptionSummary($keyword, $ward, $lsgi, $supervisor, $gt, $association, $from, $to),
//                 'pagination' => false,
//                 'sort'       => ['defaultOrder' => ['id' => SORT_DESC]]
//             ]);
//         $modelService = new Service;
//         $serviceDataprovider = new ActiveDataProvider(
//             [
//                 'query'      => $modelService->getAllQuery(),
//                 'pagination' => false,
//                 'sort'       => ['defaultOrder' => ['id' => SORT_DESC]]
//             ]);
        
//         $modelUser  = Yii::$app->user->identity;
//         $associations = Yii::$app->rbac->getAssociations($modelUser->id);
//         return $this->render('subscription-summary-new', [
//             'dataProvider' => $dataProvider,
//             'serviceDataprovider' => $serviceDataprovider,
//             'modelAccountService' => $modelAccountService,
//             'from'         =>$from,
//             'to'           =>$to,
//             'associations'           =>$associations,
//             'modelService'           =>$modelService,
//             ]);
//     }
       public function actionSubscriptionSummary($set = null)
    {
      $post = yii::$app->request->post();
      $get = yii::$app->request->get();
      $params  = array_merge($post,$get);
        $keyword=null;
        $ward=null;
        $lsgi=null; 
        $supervisor=null; 
        $gt=null; 
        $association=null; 
        $from=null;
        $to=null;
      $vars = [ 
    
    'name',
    'keyword', 
    'ward', 
    'supervisor', 
    'lsgi', 
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
    $supervisor = Yii::$app->session->get('supervisor');
    $lsgi = Yii::$app->session->get('lsgi');
    $gt = Yii::$app->session->get('gt');
    $association = Yii::$app->session->get('association');
    $from          = Yii::$app->session->get('from');
    $to            = Yii::$app->session->get('to');
    
    // $from              = $from ? \Yii::$app->formatter->asDatetime($from, "php:Y-m-d 00:00:00") : '';
    // $to                = $to ? \Yii::$app->formatter->asDatetime($to, "php:Y-m-d 23:59:00") : '';
     $from              = $from ? \Yii::$app->formatter->asDatetime($from, "php:Y-m-d 00:00:00") : '';
    $to                = $to ? \Yii::$app->formatter->asDatetime($to, "php:Y-m-d 23:50:00") : '';
}

        $session = Yii::$app->session;
        $session->set('start', $from);
        $session->set('end', $to);
        $modelAccountService = new AccountService;
        $dataProvider = new ActiveDataProvider(
            [
                'query'      => $modelAccountService->getAllQuerySubscriptionSummary($keyword, $ward, $lsgi, $supervisor, $gt, $association, $from, $to),
                'pagination' => false,
                'sort'       => ['defaultOrder' => ['id' => SORT_DESC]]
            ]);
        $modelService = new Service;
        $serviceDataprovider = new ActiveDataProvider(
            [
                'query'      => $modelService->getAllQuery(),
                'pagination' => false,
                'sort'       => ['defaultOrder' => ['id' => SORT_DESC]]
            ]);
        
        $modelUser  = Yii::$app->user->identity;
        $associations = Yii::$app->rbac->getAssociations($modelUser->id);
        return $this->render('subscription-summary-new', [
            'dataProvider' => $dataProvider,
            'serviceDataprovider' => $serviceDataprovider,
            'modelAccountService' => $modelAccountService,
            'from'         =>$from,
            'to'           =>$to,
            'associations'           =>$associations,
            'modelService'           =>$modelService,
            ]);
    }
    public function actionSubscriptionDisabled($set = null)
    {
      $post = yii::$app->request->post();
      $get = yii::$app->request->get();
      $params  = array_merge($post,$get);
        $keyword=null;
        $ward=null;
        $lsgi=null; 
        $supervisor=null; 
        $gt=null; 
        $association=null; 
        $from=null;
        $to=null;
        $service=null;
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
// print_r($association);die();
        $modelAccountService = new AccountService;
        $collectionQuery  = $modelAccountService->getAllQuerySubscriptionDisabled($keyword, $ward, $lsgi, $supervisor, $gt, $association, $from, $to,$service);
        $dataProvider = new ActiveDataProvider([
        'query' => $collectionQuery,
      ]);
        $modelUser  = Yii::$app->user->identity;
        $associations = Yii::$app->rbac->getAssociations($modelUser->id);
        return $this->render('subscription-disabled', [
            'dataProvider'  => $dataProvider,
            'modelAccountService' => $modelAccountService,
            'associations'  => $associations
        ]);
    }
    public function actionSubscriptionDisabledSummary($set = null)
    {
      $post = yii::$app->request->post();
      $get = yii::$app->request->get();
      $params  = array_merge($post,$get);
        $keyword=null;
        $ward=null;
        $lsgi=null; 
        $supervisor=null; 
        $gt=null; 
        $association=null; 
        $from=null;
        $to=null;
      $vars = [ 
    
    'name',
    'keyword', 
    'ward', 
    'supervisor', 
    'lsgi', 
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
    $supervisor = Yii::$app->session->get('supervisor');
    $lsgi = Yii::$app->session->get('lsgi');
    $gt = Yii::$app->session->get('gt');
    $association = Yii::$app->session->get('association');
    $from          = Yii::$app->session->get('from');
    $to            = Yii::$app->session->get('to');
    
    $from              = $from ? \Yii::$app->formatter->asDatetime($from, "php:Y-m-d H:i:s") : '';
    $to                = $to ? \Yii::$app->formatter->asDatetime($to, "php:Y-m-d H:i:s") : '';
}
        $session = Yii::$app->session;
        $session->set('start', $from);
        $session->set('end', $to);
        $modelAccountService = new AccountService;
        $dataProvider = new ActiveDataProvider(
            [
                'query'      => $modelAccountService->getAllQuerySubscriptionSummary($keyword, $ward, $lsgi, $supervisor, $gt, $association, $from, $to),
                'pagination' => false,
                'sort'       => ['defaultOrder' => ['id' => SORT_DESC]]
            ]);
        $modelService = new Service;
        $serviceDataprovider = new ActiveDataProvider(
            [
                'query'      => $modelService->getAllQuery(),
                'pagination' => false,
                'sort'       => ['defaultOrder' => ['id' => SORT_DESC]]
            ]);
        
        $modelUser  = Yii::$app->user->identity;
        $associations = Yii::$app->rbac->getAssociations($modelUser->id);
        return $this->render('subscription-disabled-summary', [
            'dataProvider' => $dataProvider,
            'serviceDataprovider' => $serviceDataprovider,
            'modelAccountService' => $modelAccountService,
            'from'         =>$from,
            'to'           =>$to,
            'associations'           =>$associations,
            'modelService'           =>$modelService,
            ]);
    }
    public function actionPlanCount($set = null)
    {
      $post = yii::$app->request->post();
      $get = yii::$app->request->get();
      $params  = array_merge($post,$get);
        $keyword=null;
        $ward=null;
        $lsgi=null; 
        $supervisor=null; 
        $gt=null; 
        $association=null; 
        $from=null;
        $to=null;
        $service=null;
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
// print_r($association);die();
        $modelAccountService = new AccountService;
        $collectionQuery  = $modelAccountService->getAllQuerySubscription($keyword, $ward, $lsgi, $supervisor, $gt, $association, $from, $to,$service);
        $dataProvider = new ActiveDataProvider([
        'query' => $collectionQuery,
      ]);
        $modelUser  = Yii::$app->user->identity;
        $associations = Yii::$app->rbac->getAssociations($modelUser->id);
        return $this->render('plan-count', [
            'dataProvider'  => $dataProvider,
            'modelAccountService' => $modelAccountService,
            'associations'  => $associations
        ]);
    }
    public function actionComplaintsSummary($set = null)
    {
      $post = yii::$app->request->post();
      $get = yii::$app->request->get();
      $params  = array_merge($post,$get);
        $keyword=null;
        $ward=null;
        $lsgi=null; 
        $supervisor=null; 
        $gt=null; 
        $association=null; 
        $from=null;
        $to=null;
      $vars = [ 
    
    'name',
    'keyword', 
    'ward', 
    'supervisor', 
    'lsgi', 
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
    $supervisor = Yii::$app->session->get('supervisor');
    $lsgi = Yii::$app->session->get('lsgi');
    $gt = Yii::$app->session->get('gt');
    $association = Yii::$app->session->get('association');
    $from          = Yii::$app->session->get('from');
    $to            = Yii::$app->session->get('to');
    
    $from              = $from ? \Yii::$app->formatter->asDatetime($from, "php:Y-m-d H:i:s") : '';
    $to                = $to ? \Yii::$app->formatter->asDatetime($to, "php:Y-m-d H:i:s") : '';
}
        $session = Yii::$app->session;
        $session->set('start', $from);
        $session->set('end', $to);
        $modelServiceRequest = new ServiceRequest;
        $dataProvider = new ActiveDataProvider(
            [
                'query'      => $modelServiceRequest->getAllQuerySummary($keyword, $ward, $lsgi, $supervisor, $gt, $association, $from, $to),
                'pagination' => false,
                'sort'       => ['defaultOrder' => ['id' => SORT_DESC]]
            ]);
        
        $modelUser  = Yii::$app->user->identity;
        $associations = Yii::$app->rbac->getAssociations($modelUser->id);
        return $this->render('complaint-summary', [
            'dataProvider' => $dataProvider,
            'modelServiceRequest' => $modelServiceRequest,
            'from'         =>$from,
            'to'           =>$to,
            'associations'           =>$associations,
            ]);
    }
    public function actionComplaintResolution($set=null)
    {
        $post = yii::$app->request->post();
      $get = yii::$app->request->get();
      $params  = array_merge($post,$get);
        $keyword=null;
        $ward=null;
        $lsgi=null; 
        $supervisor=null; 
        $gt=null; 
        $association=null; 
        $from=null;
        $to=null;
        $service=null;
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
        $type=2;

        $modelServiceRequest = new ServiceRequest;
        $complaintQuery  = $modelServiceRequest->getAllQueryCompleted($keyword, $ward, $lsgi, $supervisor, $gt, $association, $from, $to,$service,$type);
        $dataProvider = new ActiveDataProvider([
        'query' => $complaintQuery,
      ]);
        return $this->render('complaint-resolution', [
            'dataProvider' => $dataProvider,
            'modelServiceRequest' => $modelServiceRequest,
        ]);
    }
     public function actionComplaintResolutionSummary($set = null)
    {
      $post = yii::$app->request->post();
      $get = yii::$app->request->get();
      $params  = array_merge($post,$get);
        $keyword=null;
        $ward=null;
        $lsgi=null; 
        $supervisor=null; 
        $gt=null; 
        $association=null; 
        $from=null;
        $to=null;
      $vars = [ 
    
    'name',
    'keyword', 
    'ward', 
    'supervisor', 
    'lsgi', 
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
    $supervisor = Yii::$app->session->get('supervisor');
    $lsgi = Yii::$app->session->get('lsgi');
    $gt = Yii::$app->session->get('gt');
    $association = Yii::$app->session->get('association');
    $from          = Yii::$app->session->get('from');
    $to            = Yii::$app->session->get('to');
    
    $from              = $from ? \Yii::$app->formatter->asDatetime($from, "php:Y-m-d H:i:s") : '';
    $to                = $to ? \Yii::$app->formatter->asDatetime($to, "php:Y-m-d H:i:s") : '';
}
        $session = Yii::$app->session;
        $session->set('start', $from);
        $session->set('end', $to);
        $modelServiceRequest = new ServiceRequest;
        $dataProvider = new ActiveDataProvider(
            [
                'query'      => $modelServiceRequest->getAllQuerySummary($keyword, $ward, $lsgi, $supervisor, $gt, $association, $from, $to),
                'pagination' => false,
                'sort'       => ['defaultOrder' => ['id' => SORT_DESC]]
            ]);
        
        $modelUser  = Yii::$app->user->identity;
        $associations = Yii::$app->rbac->getAssociations($modelUser->id);
        return $this->render('complaint-resolution-summary', [
            'dataProvider' => $dataProvider,
            'modelServiceRequest' => $modelServiceRequest,
            'from'         =>$from,
            'to'           =>$to,
            'associations'           =>$associations,
            ]);
    }
    public function actionServiceCompletion($set=null)
    {
       $post = yii::$app->request->post();
      $get = yii::$app->request->get();
      $params  = array_merge($post,$get);
        $keyword=null;
        $ward=null;
        $lsgi=null; 
        $supervisor=null; 
        $gt=null; 
        $association=null; 
        $from=null;
        $to=null;
        $service=null;
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
        $type=1;
        $status =1;
        $modelServiceRequest = new ServiceRequest;
        $complaintQuery  = $modelServiceRequest->getAllQueryCompleted($keyword, $ward, $lsgi, $supervisor, $gt, $association, $from, $to,$service,$type,$status);
        $dataProvider = new ActiveDataProvider([
        'query' => $complaintQuery,
      ]);
        return $this->render('service', [
            'dataProvider' => $dataProvider,
            'modelServiceRequest' => $modelServiceRequest,
        ]);
    }
    public function actionServiceCompletionSummary($set = null)
    {
      $post = yii::$app->request->post();
      $get = yii::$app->request->get();
      $params  = array_merge($post,$get);
        $keyword=null;
        $ward=null;
        $lsgi=null; 
        $supervisor=null; 
        $gt=null; 
        $association=null; 
        $from=null;
        $to=null;
      $vars = [ 
    
    'name',
    'keyword', 
    'ward', 
    'supervisor', 
    'lsgi', 
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
    $supervisor = Yii::$app->session->get('supervisor');
    $lsgi = Yii::$app->session->get('lsgi');
    $gt = Yii::$app->session->get('gt');
    $association = Yii::$app->session->get('association');
    $from          = Yii::$app->session->get('from');
    $to            = Yii::$app->session->get('to');
    
    $from              = $from ? \Yii::$app->formatter->asDatetime($from, "php:Y-m-d H:i:s") : '';
    $to                = $to ? \Yii::$app->formatter->asDatetime($to, "php:Y-m-d H:i:s") : '';
}
        $session = Yii::$app->session;
        $session->set('start', $from);
        $session->set('end', $to);
        $modelServiceRequest = new ServiceRequest;
        $dataProvider = new ActiveDataProvider(
            [
                'query'      => $modelServiceRequest->getAllQuerySummary($keyword, $ward, $lsgi, $supervisor, $gt, $association, $from, $to),
                'pagination' => false,
                'sort'       => ['defaultOrder' => ['id' => SORT_DESC]]
            ]);
        
        $modelUser  = Yii::$app->user->identity;
        $associations = Yii::$app->rbac->getAssociations($modelUser->id);
        return $this->render('service-completion-summary', [
            'dataProvider' => $dataProvider,
            'modelServiceRequest' => $modelServiceRequest,
            'from'         =>$from,
            'to'           =>$to,
            'associations'           =>$associations,
            ]);
    }

    public function actionServicePending($set=null)
    {
       $post = yii::$app->request->post();
      $get = yii::$app->request->get();
      $params  = array_merge($post,$get);
        $keyword=null;
        $ward=null;
        $lsgi=null; 
        $supervisor=null; 
        $gt=null; 
        $association=null; 
        $from=null;
        $to=null;
        $service=null;
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
        $type=1;
        $status = 2;
        $modelServiceRequest = new ServiceRequest;
        $complaintQuery  = $modelServiceRequest->getAllQueryCompleted($keyword, $ward, $lsgi, $supervisor, $gt, $association, $from, $to,$service,$type,$status);
        $dataProvider = new ActiveDataProvider([
        'query' => $complaintQuery,
      ]);
        return $this->render('service-pending', [
            'dataProvider' => $dataProvider,
            'modelServiceRequest' => $modelServiceRequest,
        ]);
    }
    public function actionServicePendingSummary($set = null)
    {
      $post = yii::$app->request->post();
      $get = yii::$app->request->get();
      $params  = array_merge($post,$get);
        $keyword=null;
        $ward=null;
        $lsgi=null; 
        $supervisor=null; 
        $gt=null; 
        $association=null; 
        $from=null;
        $to=null;
      $vars = [ 
    
    'name',
    'keyword', 
    'ward', 
    'supervisor', 
    'lsgi', 
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
    $supervisor = Yii::$app->session->get('supervisor');
    $lsgi = Yii::$app->session->get('lsgi');
    $gt = Yii::$app->session->get('gt');
    $association = Yii::$app->session->get('association');
    $from          = Yii::$app->session->get('from');
    $to            = Yii::$app->session->get('to');
    
    $from              = $from ? \Yii::$app->formatter->asDatetime($from, "php:Y-m-d H:i:s") : '';
    $to                = $to ? \Yii::$app->formatter->asDatetime($to, "php:Y-m-d H:i:s") : '';
}
        $session = Yii::$app->session;
        $session->set('start', $from);
        $session->set('end', $to);
        $modelServiceRequest = new ServiceRequest;
        $dataProvider = new ActiveDataProvider(
            [
                'query'      => $modelServiceRequest->getAllQuerySummary($keyword, $ward, $lsgi, $supervisor, $gt, $association, $from, $to),
                'pagination' => false,
                'sort'       => ['defaultOrder' => ['id' => SORT_DESC]]
            ]);
        
        $modelUser  = Yii::$app->user->identity;
        $associations = Yii::$app->rbac->getAssociations($modelUser->id);
        return $this->render('service-pending-summary', [
            'dataProvider' => $dataProvider,
            'modelServiceRequest' => $modelServiceRequest,
            'from'         =>$from,
            'to'           =>$to,
            'associations'           =>$associations,
            ]);
    }
    public function actionCustomerPlan($set = null)
    {
      $post = yii::$app->request->post();
      $get = yii::$app->request->get();
      $params  = array_merge($post,$get);
        $keyword=null;
        $ward=null;
        $lsgi=null; 
        $supervisor=null; 
        $gt=null; 
        $association=null; 
        $from=null;
        $to=null;
        $service=null;
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
// print_r($association);die();
        $modelAccountService = new AccountService;
        $collectionQuery  = $modelAccountService->getAllQuerySubscription($keyword, $ward, $lsgi, $supervisor, $gt, $association, $from, $to,$service);
        $dataProvider = new ActiveDataProvider([
        'query' => $collectionQuery,
      ]);
        $modelUser  = Yii::$app->user->identity;
        $associations = Yii::$app->rbac->getAssociations($modelUser->id);
        return $this->render('customer-plan', [
            'dataProvider'  => $dataProvider,
            'modelAccountService' => $modelAccountService,
            'associations'  => $associations
        ]);
    }
    public function actionCustomerPlanSummary($set = null)
    {
      $post = yii::$app->request->post();
      $get = yii::$app->request->get();
      $params  = array_merge($post,$get);
        $keyword=null;
        $ward=null;
        $lsgi=null; 
        $supervisor=null; 
        $gt=null; 
        $association=null; 
        $from=null;
        $to=null;
      $vars = [ 
    
    'name',
    'keyword', 
    'ward', 
    'supervisor', 
    'lsgi', 
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
    $supervisor = Yii::$app->session->get('supervisor');
    $lsgi = Yii::$app->session->get('lsgi');
    $gt = Yii::$app->session->get('gt');
    $association = Yii::$app->session->get('association');
    $from          = Yii::$app->session->get('from');
    $to            = Yii::$app->session->get('to');
    
    $from              = $from ? \Yii::$app->formatter->asDatetime($from, "php:Y-m-d H:i:s") : '';
    $to                = $to ? \Yii::$app->formatter->asDatetime($to, "php:Y-m-d H:i:s") : '';
}
        $session = Yii::$app->session;
        $session->set('start', $from);
        $session->set('end', $to);
        $modelAccountService = new AccountService;
        $dataProvider = new ActiveDataProvider(
            [
                'query'      => $modelAccountService->getAllQuerySubscriptionSummary($keyword, $ward, $lsgi, $supervisor, $gt, $association, $from, $to),
                'pagination' => false,
                'sort'       => ['defaultOrder' => ['id' => SORT_DESC]]
            ]);
        $modelService = new Service;
        $serviceDataprovider = new ActiveDataProvider(
            [
                'query'      => $modelService->getAllQuery(),
                'pagination' => false,
                'sort'       => ['defaultOrder' => ['id' => SORT_DESC]]
            ]);
        
        $modelUser  = Yii::$app->user->identity;
        $associations = Yii::$app->rbac->getAssociations($modelUser->id);
        return $this->render('customer-plan-summary-new', [
            'dataProvider' => $dataProvider,
            'serviceDataprovider' => $serviceDataprovider,
            'modelAccountService' => $modelAccountService,
            'from'         =>$from,
            'to'           =>$to,
            'associations'           =>$associations,
            'modelService'           =>$modelService,
            ]);
    }
     public function actionItemwiseServiceCompletion($set = null)
    {
      $post = yii::$app->request->post();
      $get = yii::$app->request->get();
      $params  = array_merge($post,$get);
        $keyword=null;
        $ward=null;
        $lsgi=null; 
        $supervisor=null; 
        $gt=null; 
        $association=null; 
        $from=null;
        $to=null;
        $service=null;
      $vars = [ 
    
    'name',
    'keyword', 
    'ward', 
    'supervisor', 
    'lsgi', 
    'gt', 
    'association',
    'service',
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
    $supervisor = Yii::$app->session->get('supervisor');
    $lsgi = Yii::$app->session->get('lsgi');
    $gt = Yii::$app->session->get('gt');
    $association = Yii::$app->session->get('association');
    $from          = Yii::$app->session->get('from');
    $to            = Yii::$app->session->get('to');
    $service            = Yii::$app->session->get('service');
    
    $from              = $from ? \Yii::$app->formatter->asDatetime($from, "php:Y-m-d H:i:s") : '';
    $to                = $to ? \Yii::$app->formatter->asDatetime($to, "php:Y-m-d H:i:s") : '';
}
        $session = Yii::$app->session;
        $session->set('start', $from);
        $session->set('end', $to);
        $session->set('wardId', $ward);
        // print_r(Yii::$app->session->get('wardId'));die();
        $modelService = new Service;
        $serviceDataprovider = new ActiveDataProvider(
            [
                'query'      => $modelService->getAllQueryItem($service,$ward),
                'pagination' => false,
                'sort'       => ['defaultOrder' => ['id' => SORT_DESC]]
            ]);
        $modelUser  = Yii::$app->user->identity;
        $associations = Yii::$app->rbac->getAssociations($modelUser->id);
        return $this->render('itemwise-service-completion', [
            'dataProvider' => $serviceDataprovider,
            'from'         =>$from,
            'to'           =>$to,
            'associations'           =>$associations,
            'modelService'           =>$modelService,
            ]);
    }
    public function actionItemwiseServicePending($set = null)
    {
      $post = yii::$app->request->post();
      $get = yii::$app->request->get();
      $params  = array_merge($post,$get);
        $keyword=null;
        $ward=null;
        $lsgi=null; 
        $supervisor=null; 
        $gt=null; 
        $association=null; 
        $from=null;
        $to=null;
        $service=null;
      $vars = [ 
    
    'name',
    'keyword', 
    'ward', 
    'supervisor', 
    'lsgi', 
    'gt', 
    'association',
    'service',
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
    $supervisor = Yii::$app->session->get('supervisor');
    $lsgi = Yii::$app->session->get('lsgi');
    $gt = Yii::$app->session->get('gt');
    $association = Yii::$app->session->get('association');
    $from          = Yii::$app->session->get('from');
    $to            = Yii::$app->session->get('to');
    $service            = Yii::$app->session->get('service');
    
    $from              = $from ? \Yii::$app->formatter->asDatetime($from, "php:Y-m-d H:i:s") : '';
    $to                = $to ? \Yii::$app->formatter->asDatetime($to, "php:Y-m-d H:i:s") : '';
}
        $session = Yii::$app->session;
        $session->set('start', $from);
        $session->set('end', $to);
        $session->set('wardId', $ward);
        $modelService = new Service;
        $serviceDataprovider = new ActiveDataProvider(
            [
                'query'      => $modelService->getAllQueryItem($service),
                'pagination' => false,
                'sort'       => ['defaultOrder' => ['id' => SORT_DESC]]
            ]);
        $modelUser  = Yii::$app->user->identity;
        $associations = Yii::$app->rbac->getAssociations($modelUser->id);
        return $this->render('itemwise-service-pending', [
            'dataProvider' => $serviceDataprovider,
            'from'         =>$from,
            'to'           =>$to,
            'associations'           =>$associations,
            'modelService'           =>$modelService,
            ]);
    }public function actionHksCustomerRating($set=null)
    {
       $post = yii::$app->request->post();
      $get = yii::$app->request->get();
      $params  = array_merge($post,$get);
        $keyword=null;
        $ward=null;
        $unit=null;
        $lsgi=null; 
        $supervisor=null; 
        $gt=null; 
        $association=null; 
        $from=null;
        $to=null;
        $service=null;
      $vars = [ 
    
    'name',
    'keyword', 
    'ward', 
    'unit', 
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
    $unit            = Yii::$app->session->get('unit');
    
    $from              = $from ? \Yii::$app->formatter->asDatetime($from, "php:Y-m-d H:i:s") : '';
    $to                = $to ? \Yii::$app->formatter->asDatetime($to, "php:Y-m-d 23:59:00") : '';
}
        $type=1;
        $status =1;
        $modelServiceRequest = new ServiceRequest;
        $ratingQuery  = $modelServiceRequest->getAllQueryRating($keyword, $ward, $lsgi, $supervisor, $gt, $association, $from, $to,$service,$type,$status,$unit);
        $dataProvider = new ActiveDataProvider([
        'query' => $ratingQuery,
      ]);
        return $this->render('hks-customer-rating', [
            'dataProvider' => $dataProvider,
            'modelServiceRequest' => $modelServiceRequest,
        ]);
    }
    public function actionWasteQualityCollected()
    {
        $post   = yii::$app->request->post();
        $get    = yii::$app->request->get();
        $params = array_merge($post, $get);
        $vars   = [

            'name',
            'service',
            'gt',
            'from',
            'to',
            'status',
            'ward',
        ];
        $newParams = [];
        $session   = Yii::$app->session;
        foreach ($vars as $param)
        {
            ${
                $param}          = isset($params[$param]) ? $params[$param] : null;
            $newParams[$param] = ${
                $param};
        }
         $keyword      = null;
        $service         = null;
        $status         = null;
        $from         = null;
        $to         = null;
        $gt         = null;
        $ward         = null;
        $post         = Yii::$app->request->post();
        if (isset($post['service']))
        {
            $service = $post['service'];
        }
        if (isset($post['status']))
        {
            $status = $post['status'];
        }
        if (isset($post['ward']))
        {
            $ward = $post['ward'];
        }
        if (isset($post['gt']))
        {
            $gt = $post['gt'];
        }
        if (isset($_POST['name']))
        {
            $keyword = $_POST['name'];
        }
        if (isset($_POST['from']))
        {
            $from = $_POST['from'];
        }
        if (isset($_POST['to']))
        {
            $to = $_POST['to'];
        }
        $type=1;
        $waste_type = 1;

        $modelServiceRequest = new ServiceRequest;
        $searchModel = new ServiceRequestSearch();
        $dataProvider = $searchModel->collectedQuality(Yii::$app->request->queryParams,$keyword,$service,$gt,$from,$to,$type,$status,$ward,$waste_type);

        return $this->render('waste-quality-collected', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'modelServiceRequest' => $modelServiceRequest,
        ]);
    }
    public function actionWasteQuantityCollected()
    {
        $post   = yii::$app->request->post();
        $get    = yii::$app->request->get();
        $params = array_merge($post, $get);
        $vars   = [

            'name',
            'service',
            'gt',
            'from',
            'to',
            'status',
            'ward',
        ];
        $newParams = [];
        $session   = Yii::$app->session;
        foreach ($vars as $param)
        {
            ${
                $param}          = isset($params[$param]) ? $params[$param] : null;
            $newParams[$param] = ${
                $param};
        }
         $keyword      = null;
        $service         = null;
        $status         = null;
        $from         = null;
        $to         = null;
        $gt         = null;
        $ward         = null;
        $post         = Yii::$app->request->post();
        if (isset($post['service']))
        {
            $service = $post['service'];
        }
        if (isset($post['status']))
        {
            $status = $post['status'];
        }
        if (isset($post['ward']))
        {
            $ward = $post['ward'];
        }
        if (isset($post['gt']))
        {
            $gt = $post['gt'];
        }
        if (isset($_POST['name']))
        {
            $keyword = $_POST['name'];
        }
        if (isset($_POST['from']))
        {
            $from = $_POST['from'];
        }
        if (isset($_POST['to']))
        {
            $to = $_POST['to'];
        }
        $type=1;
        $waste_type = 2;

        $modelServiceRequest = new ServiceRequest;
        $searchModel = new ServiceRequestSearch();
        $dataProvider = $searchModel->collectedQuality(Yii::$app->request->queryParams,$keyword,$service,$gt,$from,$to,$type,$status,$ward,$waste_type);
        $quantityCollected = $modelServiceRequest->getQuantityCollected($keyword,$service,$gt,$from,$to,$type,$status,$ward,$waste_type);
        return $this->render('waste-quantity-collected', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'modelServiceRequest' => $modelServiceRequest,
            'quantityCollected' => $quantityCollected,
        ]);
    }
    public function actionWasteQualityCollectedSummary($set = null)
    {
      $post = yii::$app->request->post();
      $get = yii::$app->request->get();
      $params  = array_merge($post,$get);
        $keyword=null;
        $ward=null;
        $lsgi=null; 
        $supervisor=null; 
        $gt=null; 
        $association=null; 
        $from=null;
        $to=null;
      $vars = [ 
    
    'name',
    'keyword', 
    'ward', 
    'supervisor', 
    'lsgi', 
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
    $supervisor = Yii::$app->session->get('supervisor');
    $lsgi = Yii::$app->session->get('lsgi');
    $gt = Yii::$app->session->get('gt');
    $association = Yii::$app->session->get('association');
    $from          = Yii::$app->session->get('from');
    $to            = Yii::$app->session->get('to');
    
    $from              = $from ? \Yii::$app->formatter->asDatetime($from, "php:Y-m-d H:i:s") : '';
    $to                = $to ? \Yii::$app->formatter->asDatetime($to, "php:Y-m-d H:i:s") : '';
}
        $session = Yii::$app->session;
        $session->set('start', $from);
        $session->set('end', $to);
       $modelQuality = new WasteQuality;
        $dataProvider = new ActiveDataProvider(
            [
                'query'      => $modelQuality->getAllQuery($ward),
                'pagination' => false,
                'sort'       => ['defaultOrder' => ['id' => SORT_DESC]]
            ]);
        $modelUser  = Yii::$app->user->identity;
        $associations = Yii::$app->rbac->getAssociations($modelUser->id);
        return $this->render('waste-quality-collected-summary', [
            'dataProvider' => $dataProvider,
            'modelQuality' => $modelQuality,
            'from'         =>$from,
            'to'           =>$to,
            'associations'           =>$associations,
            ]);
    }
     public function actionOutstandingAmount($set = null)
    {
      $post = yii::$app->request->post();
      $get = yii::$app->request->get();
      $params  = array_merge($post,$get);
        $keyword=null;
        $ward=null;
        $lsgi=null; 
        $supervisor=null; 
        $gt=null; 
        $association=null; 
        $from=null;
        $to=null;
      $vars = [ 
    
    'name',
    'keyword', 
    'ward', 
    'supervisor', 
    'lsgi', 
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
    $supervisor = Yii::$app->session->get('supervisor');
    $lsgi = Yii::$app->session->get('lsgi');
    $gt = Yii::$app->session->get('gt');
    $association = Yii::$app->session->get('association');
    $from          = Yii::$app->session->get('from');
    $to            = Yii::$app->session->get('to');
    
    $from              = $from ? \Yii::$app->formatter->asDatetime($from, "php:Y-m-d H:i:s") : '';
    $to                = $to ? \Yii::$app->formatter->asDatetime($to, "php:Y-m-d 23:59:59") : '';
}
// print_r($association);die();
        $modelPayment = new PaymentRequest;
        $pendingQuery  = $modelPayment->getAllQueryPending($keyword, $ward, $lsgi, $supervisor, $gt, $association, $from, $to);
        $dataProvider = new ActiveDataProvider([
        'query' => $pendingQuery,
      ]);
        $modelUser  = Yii::$app->user->identity;
        $associations = Yii::$app->rbac->getAssociations($modelUser->id);
        return $this->render('out-standing-amount', [
            'dataProvider'  => $dataProvider,
            'modelPayment' => $modelPayment,
            'associations'  => $associations
        ]);
    }
    public function actionOutstandingAmountSummary($set = null)
    {
      $post = yii::$app->request->post();
      $get = yii::$app->request->get();
      $params  = array_merge($post,$get);
        $keyword=null;
        $ward=null;
        $lsgi=null; 
        $supervisor=null; 
        $gt=null; 
        $association=null; 
        $from=null;
        $to=null;
      $vars = [ 
    
    'name',
    'keyword', 
    'ward', 
    'supervisor', 
    'lsgi', 
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
    $supervisor = Yii::$app->session->get('supervisor');
    $lsgi = Yii::$app->session->get('lsgi');
    $gt = Yii::$app->session->get('gt');
    $association = Yii::$app->session->get('association');
    $from          = Yii::$app->session->get('from');
    $to            = Yii::$app->session->get('to');
    
    $from              = $from ? \Yii::$app->formatter->asDatetime($from, "php:Y-m-d H:i:s") : '';
    $to                = $to ? \Yii::$app->formatter->asDatetime($to, "php:Y-m-d H:i:s") : '';
}
        $session = Yii::$app->session;
        $session->set('start', $from);
        $session->set('end', $to);
        $modelPayment = new PaymentRequest;
        $dataProvider = new ActiveDataProvider(
            [
                'query'      => $modelPayment->getAllQueryOutstandingSummary($keyword, $ward, $lsgi, $supervisor, $gt, $association, $from, $to),
                'pagination' => false,
                'sort'       => ['defaultOrder' => ['id' => SORT_DESC]]
            ]);
        
        $modelUser  = Yii::$app->user->identity;
        $associations = Yii::$app->rbac->getAssociations($modelUser->id);
        return $this->render('out-standing-amount-summary', [
            'dataProvider' => $dataProvider,
            'modelPayment' => $modelPayment,
            'from'         =>$from,
            'to'           =>$to,
            'associations'           =>$associations,
            ]);
    }
     public function actionWardWiseCountNew()
    {
        $keyword = null;
        $from = null;
        $to = null;
        $ward = null;
        if (isset($_POST['name']))
        {
            $keyword = $_POST['name'];
        }
        if (isset($_POST['ward']))
        {
            $ward = $_POST['ward'];
        }
         if (isset($_POST['from']))
        {
            $from = $_POST['from'];
                $from              = $from ? \Yii::$app->formatter->asDatetime($from, "php:Y-m-d H:i:s") : '';

        }
        if (isset($_POST['to']))
        {
            $to = $_POST['to'];
                $to                = $to ? \Yii::$app->formatter->asDatetime($to, "php:Y-m-d H:i:s") : '';

        }
        $session = Yii::$app->session;
        $session->set('start', $from);
        $session->set('end', $to);
  
        $modelWard = new Ward;
        $dataProvider = new ActiveDataProvider(
            [
                'query'      => $modelWard->getAllQuery($keyword,$from, $to,$ward),
                'pagination' => false,
                'sort'       => ['defaultOrder' => ['id' => SORT_DESC]]
            ]);
        
        $modelUser  = Yii::$app->user->identity;
        $associations = Yii::$app->rbac->getAssociations($modelUser->id);
        return $this->render('ward-wise-count-new', [
            'dataProvider' => $dataProvider,
            'modelWard' => $modelWard,
            'from'         =>$from,
            'to'           =>$to,
            'associations'           =>$associations,
            ]);
    }
     public function actionAmountPaid($set = null)
    {
      $post = yii::$app->request->post();
      $get = yii::$app->request->get();
      $params  = array_merge($post,$get);
        $keyword=null;
        $ward=null;
        $lsgi=null; 
        $supervisor=null; 
        $gt=null; 
        $association=null; 
        $from=null;
        $to=null;
      $vars = [ 
    
    'name',
    'keyword', 
    'ward', 
    'supervisor', 
    'lsgi', 
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
    $supervisor = Yii::$app->session->get('supervisor');
    $lsgi = Yii::$app->session->get('lsgi');
    $gt = Yii::$app->session->get('gt');
    $association = Yii::$app->session->get('association');
    $from          = Yii::$app->session->get('from');
    $to            = Yii::$app->session->get('to');
    
    $from              = $from ? \Yii::$app->formatter->asDatetime($from, "php:Y-m-d H:i:s") : '';
    $to                = $to ? \Yii::$app->formatter->asDatetime($to, "php:Y-m-d 23:59:59") : '';
}
 $session = Yii::$app->session;
        $session->set('start', $from);
        $session->set('end', $to);
// print_r($association);die();
        $modelPayment = new PaymentRequest;
      //   $paidQuery  = $modelPayment->getAllQueryPaid($keyword, $ward, $lsgi, $supervisor, $gt, $association, $from, $to);
      //   $dataProvider = new ActiveDataProvider([
      //   'query' => $paidQuery,
      // ]);
        $modelHks = new GreenActionUnit;
        $dataProvider = new ActiveDataProvider(
            [
                'query'      => $modelHks->getAllQuery($keyword,$from, $to,$ward),
                'pagination' => false,
                'sort'       => ['defaultOrder' => ['id' => SORT_DESC]]
            ]);
        
        $modelUser  = Yii::$app->user->identity;
        $associations = Yii::$app->rbac->getAssociations($modelUser->id);
        return $this->render('paid-amount', [
            'dataProvider'  => $dataProvider,
            'modelPayment' => $modelPayment,
            'associations'  => $associations
        ]);
    }

}
