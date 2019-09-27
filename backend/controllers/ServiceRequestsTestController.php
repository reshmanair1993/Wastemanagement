<?php

namespace backend\controllers;

use Yii;
use backend\models\ServiceRequestTest;
use backend\models\ServiceAssignmentTest;
use backend\models\ServiceAssignmentSearchTest;
use backend\models\ServiceRequestSearchTest;
use backend\models\ServicingStatusOption;
use backend\models\EvaluationConfigCustomerRating;
use backend\models\EvaluationConfigWasteQuality;
use backend\models\GreenActionUnit;
use backend\models\GreenActionUnitWard;
use backend\models\Image;
use backend\models\Person;
use backend\models\Account;
use backend\models\Customer;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use backend\components\AccessPermission;
/**
 * ServiceRequestsController implements the CRUD actions for ServiceRequest model.
 */
class ServiceRequestsTestController extends Controller
{
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
                       'permissions' => ['service-requests-index'],
                   ],
                   [
                       'actions' => ['create'],
                       'allow' => true,
                       'permissions' => ['service-requests-create'],
                   ],
                   [
                       'actions' => ['update'],
                       'allow' => true,
                       'permissions' => ['service-requests-update'],
                   ],
                   [
                       'actions' => ['delete'],
                       'allow' => true,
                       'permissions' => ['service-requests-delete'],
                   ],
                   [
                       'actions' => ['view'],
                       'allow' => true,
                       'permissions' => ['service-requests-view'],
                   ],
                   [
                       'actions' => ['bulk-assign-gt'],
                       'allow' => true,
                       'permissions' => ['service-requests-bulk-assign-gt'],
                   ],
                   [
                       'actions' => ['bulk-assign'],
                       'allow' => true,
                       'permissions' => ['service-requests-bulk-assign'],
                   ],
                   [
                       'actions' => ['add-status'],
                       'allow' => true,
                       'permissions' => ['service-requests-add-status'],
                   ],
                   [
                       'actions' => ['add-gt'],
                       'allow' => true,
                       'permissions' => ['service-requests-add-gt'],
                   ],
                   [
                       'actions' => ['view-request'],
                       'allow' => true,
                       'permissions' => ['service-requests-view-request'],
                   ],
                   [
                       'actions' => ['delete-request'],
                       'allow' => true,
                       'permissions' => ['service-requests-delete-request'],
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
    public function actionIndex()
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
        $post         = Yii::$app->request->post();
        if (isset($post['service']))
        {
            $service = $post['service'];
        }
        if (isset($post['status']))
        {
            $status = $post['status'];
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
        $ward=null;

        $modelServiceRequest = new ServiceRequestTest;
        $searchModel = new ServiceRequestSearchTest();
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
        $modelServiceRequest = new ServiceRequestTest();
        $modelAccount = Account::find()->where(['status'=>1,'role'=>'supervisor'])->all();
      $modelPerson = [];
      foreach ($modelAccount as $account) {
        $modelPerson[] = Person::find()->where(['status'=>1,'id'=>$account->person_id])->one();
      }
        if ($modelServiceRequest->load(Yii::$app->request->post())) {
          $modelCustomerAccount = Account::find()->where(['id'=>$modelServiceRequest->account_id_customer])->andWhere(['status'=>1])->one();
          if($modelCustomerAccount){
            $modelCustomer = Customer::find()->where(['id'=>$modelCustomerAccount->customer_id])->andWhere(['status'=>1])->one();
            if($modelCustomer){
             $modelServiceRequest->ward_id  = $modelCustomer->ward_id;
             $modelServiceRequest->lsgi_id  = isset($modelCustomer->fkWard->fkLsgi->id)?$modelCustomer->fkWard->fkLsgi->id:null;
             $modelServiceRequest->requested_datetime  = date('Y-m-d H:i:s');
             $modelServiceRequest->save();
           }
         }
            return $this->redirect(['index']);
        }

        return $this->render('add-new-request', [
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
        $model = new ServiceRequestTest;
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
        if (($model = ServiceRequestTest::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
    public function actionViewRequest($id)
    {
      $model = $this->findModel($id);
      $modelServiceAssignment = new ServiceAssignmentTest;
      $searchModel = new ServiceAssignmentSearchTest;
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
         $modelServiceAssignmentData = ServiceAssignmentTest::find()->where(['service_request_id'=>$id])->andWhere(['status'=>1])->one();
      if($modelServiceAssignmentData)
      {
        $modelServiceAssignment = $modelServiceAssignmentData;
      }
      else
      {
        $modelServiceAssignment = new ServiceAssignmentTest;
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
         $modelServiceAssignmentData = ServiceAssignmentTest::find()->where(['service_request_id'=>$id])->andWhere(['status'=>1])->one();
      if($modelServiceAssignmentData)
      {
        $modelServiceAssignment = $modelServiceAssignmentData;
      }
      else
      {
        $modelServiceAssignment = new ServiceAssignmentTest;
      }
      $modelServiceAssignment->setScenario('add-status');
        $modelImage = new Image;

        if ($modelServiceAssignment->load(Yii::$app->request->post())) {
            // print_r("expression");die();
            $modelServiceAssignment->service_request_id = $id;
            $modelServiceAssignment->servicing_datetime = date('Y-m-d H:i:s');
               // if($modelServiceAssignment->quality)
               //      {
               //          $modelServiceRequest = ServiceRequest::find()->where(['id'=>$id])->andWhere(['status'=>1])->one();
               //          $modelEvaluationConfigWasteQuality = EvaluationConfigWasteQuality::find()->where(['quality_type_id'=>$modelServiceAssignment->quality])->andWhere(['status'=>1])->one();
               //          if($modelEvaluationConfigWasteQuality&&$modelServiceRequest)
               //          {
               //              $modelHksWard = GreenActionUnitWard::find()->where(['ward_id'=>$modelServiceRequest->ward_id])->andWhere(['status'=>1])->one();
               //              if($modelHksWard)
               //              {
               //                 $modelHks = GreenActionUnit::find()->where(['id'=>$modelHksWard->green_action_unit_id])->andWhere(['status'=>1])->one(); 
               //                 if($modelHks)
               //                 {
               //                   $qry = "SELECT max(performance_point) as performance_point FROM `evaluation_config_waste_quality` where status=1";
               //                  $command =  Yii::$app->db->createCommand($qry);
               //                  $data = $command->queryAll();
               //                  $point = $data[0];
               //                  $performance_point_max = $point['performance_point'];
               //                  $modelHks->performance_point_earned = $modelHks->performance_point_earned+$modelEvaluationConfigWasteQuality->performance_point;
               //                  $modelHks->performance_point_total = $modelHks->performance_point_total+$performance_point_max;
               //                  $modelServiceRequest->performance_point = $modelServiceRequest->performance_point+$modelEvaluationConfigWasteQuality->performance_point; 
               //                  $modelServiceRequest->save(false);
               //                  $modelHks->save(false);
               //                 }
               //              }
                           
               //          }
               //      }
             // $modelServiceAssignment->servicing_datetime =$modelServiceAssignment->servicing_datetime?\Yii::$app->formatter->asDatetime($modelServiceAssignment->servicing_datetime, "php:Y-m-d H:i:s"):'';
            $modelServiceAssignment->save(false);
        }
        // else
        // {
        //     print_r($modelServiceAssignment->getErrors());die();
        // }

         $params = [
        'model' => $model,
        'modelServiceAssignment' => $modelServiceAssignment,
        'modelImage' => $modelImage,
      ];
      return $this->render('view', [
            'params' => $params,
        ]);
    }
    public function actionBulkAssign() {
    $modelServiceAssignment = new ServiceAssignmentTest;
    return $this->render('bulk-assign', ['model'=>$modelServiceAssignment]);
}
 public function actionBulkAssignGt()
    {
        $modelServiceAssignment = new ServiceAssignmentTest;
        $params = Yii::$app->request->post();
        $ok = $params && $modelServiceAssignment->load($params);
        $qry = "INSERT INTO service_assignment(account_id_gt,service_request_id) SELECT :gt,service_request.id as service_request_id from service_request  left join service_assignment on service_assignment.service_request_id=service_request.id where service_assignment.service_request_id is null and service_request.status = 1  and service_request.ward_id=:ward_id and (service_assignment.status=1 or service_assignment.status is null)";
         $command =  Yii::$app->db->createCommand($qry);
         $gt = $modelServiceAssignment->account_id_gt;
         $ward = $modelServiceAssignment->ward_id;
         $command->bindParam(':gt',$gt);
         $command->bindParam(':ward_id',$ward);
         $command->execute();
      return $this->redirect('index');
    }

    public function actionStatusAjax(){
        
        $arr = null;
        if(Yii::$app->request->isAjax):
            $data = Yii::$app->request->Post();
            $status = $data['status'];//echo $cat;exit;
            $statusData = ServicingStatusOption::find()
            // ->select('ask_waste_quality,ask_waste_quantity')
            ->where(['id'=>$status])->andWhere(['status'=>1])->one();
            if($statusData)
            {
              $arr = $statusData;
            }            
        endif;
        yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $arr;
    }
    public function actionRating($id)
    {
      $model = $this->findModel($id);
      $post = Yii::$app->request->post();
      if($post)
      {
        $model->marked_rating_value = $post['ServiceRequest']['marked_rating_value'];
        $model->total_rating_value = 5;
         $model->save(false);

        // if($model->marked_rating_value)
        //         {
        //                 $modelEvaluationConfigCustomerRating = EvaluationConfigCustomerRating::find()->where(['rating_value'=>$model->marked_rating_value])->andWhere(['status'=>1])->one();
        //                 if($modelEvaluationConfigCustomerRating&&$model)
        //                 {
        //                     $modelHksWard = GreenActionUnitWard::find()->where(['ward_id'=>$model->ward_id])->andWhere(['status'=>1])->one();
        //                     if($modelHksWard)
        //                     {
        //                        $modelHks = GreenActionUnit::find()->where(['id'=>$modelHksWard->green_action_unit_id])->andWhere(['status'=>1])->one(); 
        //                        if($modelHks)
        //                        {
        //                          $qry = "SELECT max(performance_point) as performance_point FROM `evaluation_config_customer_rating` where status=1";
        //                         $command =  Yii::$app->db->createCommand($qry);
        //                         $data = $command->queryAll();
        //                         $point = $data[0];
        //                         $performance_point_max = $point['performance_point'];
        //                         $modelHks->performance_point_earned = $modelHks->performance_point_earned+$modelEvaluationConfigCustomerRating->performance_point;
        //                         $modelHks->performance_point_total = $modelHks->performance_point_total+$performance_point_max;
        //                         $model->performance_point = $model->performance_point+$modelEvaluationConfigCustomerRating->performance_point; 
        //                         $model->save(false);
        //                         $modelHks->save(false);
        //                        }
        //                     }
                           
        //                 }
        //         }
        return $this->redirect('index');
      }
      $params = [
        'model' => $model,
      ];
      return $this->render('rating', [
            'model' => $model,
        ]);
    }
}
