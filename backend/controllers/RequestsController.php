<?php

namespace backend\controllers;

use Yii;
use backend\models\ServiceRequest;
use backend\models\ServiceAssignment;
use backend\models\Account;
use backend\models\Person;
use backend\models\Customer;
use backend\models\Service;
use backend\models\ServiceAssignmentSearch;
use backend\models\ServiceRequestSearch;
use backend\models\Image;
use backend\models\Ward;
use backend\models\GreenActionUnitWard;
use backend\models\BuildingType;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use backend\components\AccessPermission;
use yii\data\ArrayDataProvider;
/**
 * ServiceRequestsController implements the CRUD actions for ServiceRequest model.
 */
class RequestsController extends Controller
{
     public function behaviors()
    {
        return [
           'access' => [
               'class' => AccessControl::className(),
               'only' => ['complaints','create','update','view','delete-request','view-complaints','add-gt','add-status'],
               'ruleConfig' => [
                       'class' => AccessPermission::className(),
                   ],
               'rules' => [
                   [
                       'actions' => ['complaints'],
                       'allow' => true,
                       'permissions' => ['requests-complaints'],
                   ],
                   [
                       'actions' => ['create'],
                       'allow' => true,
                       'permissions' => ['requests-create'],
                   ],
                   [
                       'actions' => ['update'],
                       'allow' => true,
                       'permissions' => ['requests-update'],
                   ],
                   [
                       'actions' => ['delete-request'],
                       'allow' => true,
                       'permissions' => ['requests-delete-request'],
                   ],
                   [
                       'actions' => ['view-complaints'],
                       'allow' => true,
                       'permissions' => ['requests-view-complaints'],
                   ],
                   [
                       'actions' => ['add-gt'],
                       'allow' => true,
                       'permissions' => ['requests-add-gt'],
                   ],
                   [
                       'actions' => ['add-status'],
                       'allow' => true,
                       'permissions' => ['requests-add-status'],
                   ],
               ],
               'denyCallback' => function($rule, $action) {
                   return $this->goHome();
               }
           ],
       ];
    }

    /**
     * Lists all ServiceRequest models.
     * @return mixed
     */
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

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'modelServiceRequest' => $modelServiceRequest,
        ]);
    }

    /**
     * Displays a single ServiceRequest model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new ServiceRequest model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
     public function actionCreate()
    {
        $modelServiceRequest = new ServiceRequest();
        $modelAccount = Account::find()->where(['status'=>1,'role'=>'supervisor'])->all();
      $modelPerson = [];
      foreach ($modelAccount as $account) {
        $modelPerson[] = Person::find()->where(['status'=>1,'id'=>$account->person_id])->one();
      }
      $post = Yii::$app->request->post();
        if ($modelServiceRequest->load(Yii::$app->request->post())) {
          $modelCustomerAccount = Account::find()->where(['id'=>$modelServiceRequest->account_id_customer])->andWhere(['status'=>1])->one();
          if($modelCustomerAccount){
            $modelCustomer = Customer::find()->where(['id'=>$modelCustomerAccount->customer_id])->andWhere(['status'=>1])->one();
            if($modelCustomer){
              if(isset($post['ServiceRequest']['new_complaint'])&&$post['ServiceRequest']['new_complaint']!=null):
              $modelService = new Service;
              $modelService->name = $post['ServiceRequest']['new_complaint'];
              $modelService->type = 2;
              $modelService->save(false);
              $modelServiceRequest->service_id= $modelService->id;
              endif;
             $modelServiceRequest->ward_id  = $modelCustomer->ward_id;
             $modelServiceRequest->lsgi_id  = isset($modelCustomer->fkWard->fkLsgi->id)?$modelCustomer->fkWard->fkLsgi->id:null;
             $modelServiceRequest->requested_datetime  = date('Y-m-d H:i:s');
             $modelServiceRequest->save();
           }
         }
            return $this->redirect(['complaints']);
        }

        return $this->render('create', [
            'modelServiceRequest' => $modelServiceRequest,
            'modelPerson' => $modelPerson,
        ]);
    }

    /**
     * Updates an existing ServiceRequest model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $customer         = $model->account_id_customer;
        $model->requested_date =$model->requested_date?\Yii::$app->formatter->asDatetime($model->requested_date, "php:d-m-Y"):'';
        if ($model->load(Yii::$app->request->post()) ) {
             if (!$model->account_id_customer)
                {
                    $model->account_id_customer = $customer;
                }
            $model->requested_date =$model->requested_date?\Yii::$app->formatter->asDatetime($model->requested_date, "php:Y-m-d H:i:s"):'';
            $model->save();
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing ServiceRequest model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDeleteRequest($id)
    {
        $model = new ServiceRequest;
        $model->deleteRequest($id);
    }

    /**
     * Finds the ServiceRequest model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ServiceRequest the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ServiceRequest::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
    public function actionViewComplaints($id)
    {
      $model = $this->findModel($id);
      $modelServiceAssignment = new ServiceAssignment;
      $searchModel = new ServiceAssignmentSearch;
      $modelImage = new Image;
      // $dataProvider = $searchModel->search(Yii::$app->request->queryParams,$id);
      $params = [
        'model' => $model,
        'modelServiceAssignment' => $modelServiceAssignment,
        'modelImage' => $modelImage,
      ];
      return $this->render('view', [
            'params' => $params,
        ]);
    }
    public function actionAddGt($id)
    {
        $model = $this->findModel($id);
         $modelServiceAssignmentData = ServiceAssignment::find()->where(['service_request_id'=>$id])->andWhere(['status'=>1])->one();
      if($modelServiceAssignmentData)
      {
        $modelServiceAssignment = $modelServiceAssignmentData;
      }
      else
      {
        $modelServiceAssignment = new ServiceAssignment;
      }
        $modelImage = new Image;
        if ($modelServiceAssignment->load(Yii::$app->request->post()) ) {
            $modelServiceAssignment->service_request_id = $id;
            if($modelServiceAssignmentData){
            $modelServiceAssignment->remarks = $modelServiceAssignmentData->remarks;
            $modelServiceAssignment->servicing_datetime = $modelServiceAssignmentData->servicing_datetime;
            $modelServiceAssignment->servicing_status_option_id = $modelServiceAssignmentData->servicing_status_option_id;
            $modelServiceAssignment->quantity = $modelServiceAssignmentData->quantity;
            $modelServiceAssignment->quality = $modelServiceAssignmentData->quality;
            $modelServiceAssignment->lat_update_from = $modelServiceAssignmentData->lat_update_from;
            $modelServiceAssignment->lng_updated_from = $modelServiceAssignmentData->lng_updated_from;
            // $modelServiceAssignmentData->status = 0;
            // $modelServiceAssignmentData->save(false);
        }
        $modelServiceAssignment->save();
        }

         $params = [
        'model' => $model,
        'modelServiceAssignment' => $modelServiceAssignment,
        'modelImage' => $modelImage,
      ];
      return $this->render('view', [
            'params' => $params,
        ]);
    }
      public function actionAddStatus($id)
    {
        $model = $this->findModel($id);
         $modelServiceAssignmentData = ServiceAssignment::find()->where(['service_request_id'=>$id])->andWhere(['status'=>1])->one();
      if($modelServiceAssignmentData)
      {
        $modelServiceAssignment = $modelServiceAssignmentData;
      }
      else
      {
        $modelServiceAssignment = new ServiceAssignment;
      }
        $modelServiceAssignment->setScenario('add-status');
        $modelImage = new Image;
        if ($modelServiceAssignment->load(Yii::$app->request->post())&&$modelServiceAssignment->validate()) {
            $modelServiceAssignment->service_request_id = $id;
            $modelServiceAssignment->servicing_datetime = date('Y-m-d H:i:s');
            
            $modelServiceAssignment->save(false);
        }

         $params = [
        'model' => $model,
        'modelServiceAssignment' => $modelServiceAssignment,
        'modelImage' => $modelImage,
      ];
      return $this->render('view', [
            'params' => $params,
        ]);
    }
//     public function actionAddComplaint()
//     {
//       $modelServiceRequest = new ServiceRequest;
//       $customersList = null;
//         $hks         = null;
//         $unit         = null;
//         $supervisor         = null;
//         $service         = null;
//         $post         = Yii::$app->request->post();
//         if (isset($post['unit']))
//         {
//             $unit = $post['unit'];
//         }
//         if (isset($post['service']))
//         {
//             $service = $post['service'];
//         }elseif(isset($post['new_complaint']))
//         {
//           $service = $post['new_complaint'];
//         }

//          if (isset($post['supervisor']))
//         {
//             $supervisor = $post['supervisor'];
//         }
//           if (isset($post['ward']))
//         {
//             $ward = $post['ward'];
//         }
//         if (isset($post['association']))
//         {
//             $association = $post['association'];
//         }
//       $modelUser  = Yii::$app->user->identity;
//        // print_r($modelUser);die();
//       if($modelUser->role=='admin-hks'||$modelUser->role=='supervisor'){

//         $hks = $modelUser->green_action_unit_id;
//       }if($modelUser->role=='admin-lsgi'||$modelUser->role=='super-admin')
//       {
//          $hks = $unit;
//       }
//        if($modelUser->role=='supervisor'){
//         $supervisor = $modelUser->id;
//       }
//        if(!isset($post['ward'])){
//       $wards = [];
//       $modelWards = GreenActionUnitWard::find()
//       ->leftJoin('account','account.green_action_unit_id=green_action_unit_ward.green_action_unit_id')
//       ->where(['green_action_unit_ward.status'=>1])
//       ->andWhere(['account.status'=>1])
//       ->andWhere(['account.green_action_unit_id'=>$hks]);

//       if($modelUser->role=='supervisor'){
//         $supervisor = $modelUser->id;
//        $modelWards = $modelWards->leftJoin('account_authority','account_authority.account_id_supervisor=account.id')
//        ->andWhere(['account_authority.account_id_supervisor'=>$modelUser->id]);
//       }
//       $modelWards = $modelWards->all();
//       foreach ($modelWards as $key => $value) {
//             $wards[] = $value->ward_id;
//       }
//       $wards = array_unique($wards);
//        $wardId = '';
// foreach ($wards as $parent) {
//     $wardId .= $parent . ',';
// }

// $wardId = rtrim($wardId, ',');
// }
// else
// {
 
//   $wardId = $ward;
// }
//       $buildingType = [];
//       $modelBuildingType = BuildingType::find()
//       ->leftJoin('residence_category','residence_category.id=building_type.residence_category_id')
//       ->leftJoin('green_action_unit','green_action_unit.residence_category_id=residence_category.id')
//       ->where(['green_action_unit.status'=>1])
//       ->andWhere(['residence_category.status'=>1])
//       ->andWhere(['building_type.status'=>1])
//       ->andWhere(['green_action_unit.id'=>$hks])
//       ->all();
//       foreach ($modelBuildingType as $key => $value) {
//             $buildingType[] = $value->id;
//       }
//       $buildingType = array_unique($buildingType);

     
// $BuildingIds = '';
// foreach ($buildingType as $building) {
//     $BuildingIds .= $building . ',';
// }

// $BuildingIds = rtrim($BuildingIds, ',');
// if (isset($post['ServiceRequest']['customer_id'])&&$post['ServiceRequest']['customer_id'] > 0&&$service) {
//         $modelServiceRequest = new ServiceRequest;
//         if ($modelServiceRequest->load(Yii::$app->request->post())) {
//           $modelServiceRequest->account_id_customer= $post['ServiceRequest']['customer_id'];
//             $post = Yii::$app->request->post();
//            if(isset($post['ServiceRequest']['new_complaint'])&&$post['ServiceRequest']['new_complaint']!=null):
//               $modelService = new Service;
//               $modelService->name = $post['ServiceRequest']['new_complaint'];
//               $modelService->type = 2;
//               $modelService->save(false);
//               $modelServiceRequest->service_id= $modelService->id;
//               else:
//                 $modelServiceRequest->service_id = $service;
//               endif;
//               $modelCustomerAccount = Account::find()->where(['id'=>$modelServiceRequest->account_id_customer])->andWhere(['status'=>1])->one();
//               $modelCustomer = Customer::find()->where(['id'=>$modelCustomerAccount->customer_id])->andWhere(['status'=>1])->one();
//               // $modelServiceRequest->service_id  = $modelServiceRequest->service_id;
//               $modelServiceRequest->ward_id  = $modelCustomer->ward_id;
//               $modelServiceRequest->lsgi_id  = isset($modelCustomer->fkWard->fkLsgi->id)?$modelCustomer->fkWard->fkLsgi->id:null;
//               $modelServiceRequest->requested_datetime  = date('Y-m-d H:i:s');
//               $modelServiceRequest->save();
//             }
//      }else
//      {
//       if(isset($post['association'])&&$post['association']!=null){
//     $qry = "SELECT customer.lead_person_name,customer.id as customer_id,account.id as account_id,customer.ward_id as ward_id,customer.building_type_id as building_type_id,account_authority.account_id_customer as account_id_customer,building_type.name as building_type_name,customer.building_number as building_number,customer.address as address, customer.association_name as association_name, customer.association_number as association_number,customer.residential_association_id as residential_association_id FROM customer LEFT JOIN account ON account.customer_id = customer.id LEFT JOIN building_type ON building_type.id = customer.building_type_id LEFT JOIN account_authority ON account.id = account_authority.account_id_customer  LEFT JOIN account_service on account_service.account_id= account_authority.account_id_customer
//     WHERE ward_id IN (:wards) and building_type_id IN (:buildingType) and account_authority.account_id_supervisor=:supervisor and customer.residential_association_id=:association and account_authority.status =1 and customer.status=1 and account.id group by account.id
//     ";
//         $command =  Yii::$app->db->createCommand($qry);
//         $command->bindParam(':wards',$wardId);
//         $command->bindParam(':buildingType',$BuildingIds);
//         $command->bindParam(':supervisor',$supervisor);
//          $command->bindParam(':association',$association);
//          $customersList = $command->queryAll();
//        }
//      }
//         $dataProvider = new ArrayDataProvider([
//         'allModels' =>$customersList,
//           ]);
//           $dataProvider->pagination = false;
//         return $this->render('add-complaint', [
//             'dataProvider' => $dataProvider,
//             'hks' => $hks,
//             'supervisorId' => $supervisor,
//             'modelServiceRequest' => $modelServiceRequest,
//         ]);
//     }
    public function actionAddComplaint()
    {
      $modelServiceRequest = new ServiceRequest;
       $customersList = null;
        $hks         = null;
        $unit         = null;
        $supervisor         = null;
        $service         = null;
        $customer_id         = null;
        $name         = null;
        $post         = Yii::$app->request->post();
        if (isset($post['unit']))
        {
            $unit = $post['unit'];
        }
        if (isset($post['service']))
        {
            $service = $post['service'];
        }elseif(isset($post['new_complaint']))
        {
          $service = $post['new_complaint'];
        }

         if (isset($post['supervisor']))
        {
            $supervisor = $post['supervisor'];
        }
          if (isset($post['ward']))
        {
            $ward = $post['ward'];
        }
        if (isset($post['association']))
        {
            $association = $post['association'];
        }
         if (isset($post['customer_id']))
        {
            $customer_id = $post['customer_id'];
        }
        if (isset($post['name']))
        {
            $name = $post['name'];
        }
      $modelUser  = Yii::$app->user->identity;
      $qry ='';
if (isset($post['ServiceRequest']['account_id_customer'])&&$post['ServiceRequest']['account_id_customer'] > 0&&$service) {
        $modelServiceRequest = new ServiceRequest;
        if ($modelServiceRequest->load(Yii::$app->request->post())) {
            $post = Yii::$app->request->post();
           if(isset($post['ServiceRequest']['new_complaint'])&&$post['ServiceRequest']['new_complaint']!=null):
              $modelService = new Service;
              $modelService->name = $post['ServiceRequest']['new_complaint'];
              $modelService->type = 2;
              $modelService->save(false);
              $modelServiceRequest->service_id= $modelService->id;
              else:
                $modelServiceRequest->service_id = $service;
              endif;
              $modelCustomerAccount = Account::find()->where(['id'=>$modelServiceRequest->account_id_customer])->andWhere(['status'=>1])->one();
              $modelCustomer = Customer::find()->where(['id'=>$modelCustomerAccount->customer_id])->andWhere(['status'=>1])->one();
              // $modelServiceRequest->service_id  = $modelServiceRequest->service_id;
              $modelServiceRequest->ward_id  = $modelCustomer->ward_id;
              $modelServiceRequest->lsgi_id  = isset($modelCustomer->fkWard->fkLsgi->id)?$modelCustomer->fkWard->fkLsgi->id:null;
              $modelServiceRequest->requested_datetime  = date('Y-m-d H:i:s');
              $modelServiceRequest->save();
            }
     }else
     {
      if((isset($post['name'])&&$post['name']!=null)||(isset($post['customer_id'])&&$post['customer_id']!=null)){
     
     if(isset($post['name'])&&$post['name']!=null)
     {
      $qry = Customer::find()->where(['customer.status'=>1])->andFilterWhere(['like','lead_person_name',$post['name']]);
     }
     if(isset($post['customer_id'])&&$post['customer_id']!=null)
     {
      $code = str_split($post['customer_id'],6);
      $codeWard = str_split($code[0],3);    
      if($codeWard)
      {
        $modelWard = Ward::find()->where(['ward_no'=>$codeWard])->one();
      }
      else
      {
        $modelWard = null;
      }
      if($code&&$modelWard)
      $qry = Customer::find()->where(['customer_id'=>$code[1]])->andWhere(['ward_id'=>$modelWard->id]);
      if(!$qry)
      {
        $qry = Customer::find()->where(['customer.status'=>1])->andWhere(['lead_person_phone'=>$post['customer_id']]);
      }
     }
         $customersList = $qry->all();
       }
     }
        $dataProvider = new ArrayDataProvider([
        'allModels' =>$customersList,
          ]);
          $dataProvider->pagination = false;
        return $this->render('add-complaint', [
            'dataProvider' => $dataProvider,
            'hks' => $hks,
            'supervisorId' => $supervisor,
            'modelServiceRequest' => $modelServiceRequest,
        ]);
    }
}
