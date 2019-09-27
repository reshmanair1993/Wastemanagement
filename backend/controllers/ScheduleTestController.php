<?php

namespace backend\controllers;

use Yii;
use backend\models\ScheduleTest;
use backend\models\Ward;
use backend\models\ScheduleSearchTest;
use backend\models\ScheduleWard;
use backend\models\ServicePackageService;
use backend\models\Service;
use backend\models\ScheduleCustomerTest;
use backend\models\Customer;
use backend\models\Account;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ArrayDataProvider;
use yii\filters\AccessControl;
use backend\components\AccessPermission;
use yii\helpers\Json; 
use yii\data\ActiveDataProvider;
/**
 * ScheduleController implements the CRUD actions for Schedule model.
 */
class ScheduleTestController extends Controller
{
    /**
     * {@inheritdoc}
     */
      public function behaviors()
    {
        return [
           'access' => [
               'class' => AccessControl::className(),
               'only' => ['index','create','update','view','delete-schedule','add-hks-schedule'],
               'ruleConfig' => [
                       'class' => AccessPermission::className(),
                   ],
               'rules' => [
                   [
                       'actions' => ['index'],
                       'allow' => true,
                       'permissions' => ['schedule-index'],
                   ],
                   [
                       'actions' => ['create'],
                       'allow' => true,
                       'permissions' => ['schedule-create'],
                   ],
                   [
                       'actions' => ['update'],
                       'allow' => true,
                       'permissions' => ['schedule-update'],
                   ],
                   [
                       'actions' => ['delete'],
                       'allow' => true,
                       'permissions' => ['schedule-delete'],
                   ],
                   [
                       'actions' => ['view'],
                       'allow' => true,
                       'permissions' => ['schedule-view'],
                   ],
                   [
                       'actions' => ['delete-schedule'],
                       'allow' => true,
                       'permissions' => ['schedule-delete-schedule'],
                   ],
                   [
                       'actions' => ['add-hks-schedule'],
                       'allow' => true,
                       'permissions' => ['schedule-add-hks-schedule'],
                   ],
               ],
               'denyCallback' => function($rule, $action) {
                   return $this->goHome();
               }
           ],
       ];
    }

    /**
     * Lists all Schedule models.
     * @return mixed
     */
    public function actionIndex()
    {
         $keyword      = null;
        $lsgi         = null;
        $district         = null;
        $page         = null;
        $post         = Yii::$app->request->post();
        if (isset($post['lsgi']))
        {
            $lsgi = $post['lsgi'];
        }
        if (isset($post['district']))
        {
            $district = $post['district'];
        }
        if (isset($_POST['name']))
        {
            $keyword = $_POST['name'];
        }
        $model = new ScheduleTest();
        $searchModel = new ScheduleSearchTest();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,$lsgi,$district,$keyword);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'model' => $model,
        ]);
    }

    /**
     * Displays a single Schedule model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $modelCustomer = new ScheduleCustomerTest;
        $dataProvider = new ActiveDataProvider(
        [
           'query' => ScheduleCustomerTest::getAllQuery()->andWhere(['schedule_id' => $id,'status' => '1']),
           'pagination' => false,
           'sort'=> ['defaultOrder' => ['id' => SORT_DESC]]
        ]);
        $params = [
            'model'  =>$model,
            'modelCustomer'  =>$modelCustomer,
            'dataProvider'  =>$dataProvider,
        ];

        return $this->render('view', [
            'params' => $params,
        ]);
    }

    /**
     * Creates a new Schedule model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $ward         = null;
        $service         = null;
        $gt         = null;
        $association         = null;
        $model = new ScheduleTest();
        $post         = Yii::$app->request->post();
        
        if (isset($post['ward']))
        {
            $ward = $post['ward'];
        }
        if (isset($post['service']))
        {
            $service = $post['service'];
        }
        if (isset($_POST['gt']))
        {
            $gt = $_POST['gt'];
        }
        if (isset($post['association']))
        {
            $association = $post['association'];
        }
        if($ward&&$service&&$gt):
           if (isset($post['ScheduleTest']['customer_id'])&&isset($post['ScheduleTest']['type'])&&$post['ScheduleTest']['customer_id']!=null&&$post['ScheduleTest']['type']!=null&&sizeof($post['ScheduleTest']['customer_id']) > 0&&$post['ScheduleTest']['customer_id'][0]>0) {
            // print_r($post['ScheduleTest']['customer_id']);die();
            $modelWard = Ward::find()->where(['id'=>$ward])->andWhere(['status'=>1])->one();
            $modelSchedule = new ScheduleTest;
        $ScheduleCustomer = new ScheduleCustomerTest();
        if ($modelSchedule->load(Yii::$app->request->post())) {
            $post = Yii::$app->request->post();
            $list = $post['ScheduleTest']['customer_id']?$post['ScheduleTest']['customer_id']:'';
            $list = explode(",",$list[0]);
            // print_r($list);die();
            $modelUser = Yii::$app->user->identity;
            $modelAccountGt = Account::find()->where(['id'=>$gt])->andWhere(['status'=>1])->one();
            $userId = $modelUser->id;
            $modelSchedule->account_id_creator= $userId;
            $modelSchedule->ward_id= $ward;
            $modelSchedule->service_id= $service;
            $modelSchedule->account_id_gt= $gt;
            $modelSchedule->green_action_unit_id= $modelUser->green_action_unit_id?$modelUser->green_action_unit_id:$modelAccountGt->green_action_unit_id;
            $modelSchedule->lsgi_id= $modelUser->lsgi_id?$modelUser->lsgi_id:$modelWard->lsgi_id;
            $modelSchedule->date= $modelSchedule->date ? \Yii::$app->formatter->asDatetime($modelSchedule->date , "php:Y-m-d") : '';
            $modelSchedule->save(false);
            if($post['ScheduleTest']['customer_id']):
            foreach ($list as  $value) {
                $ScheduleCustomer = new ScheduleCustomerTest();
                $ScheduleCustomer->account_id_customer = $value;
                $ScheduleCustomer->schedule_id = $modelSchedule->id;
                $ScheduleCustomer->save(false);
            }
            endif;
             return $this->redirect(['index']);
        }
      }
           
      else
      {
        if(isset($post['association'])&&$post['association']!=null){
         $qry = "SELECT DISTINCT customer.lead_person_name,customer.id as customer_id,account.id as account_id,customer.ward_id as ward_id,customer.building_type_id as building_type_id,account_authority.account_id_customer as account_id_customer,building_type.name as building_type_name,customer.building_number as building_number,customer.address as address, customer.association_name as association_name, customer.association_number as association_number,customer.residential_association_id as residential_association_id FROM customer LEFT JOIN account ON account.customer_id = customer.id 
                    LEFT JOIN account_service on account_service.account_id=account.id
                    LEFT JOIN building_type ON building_type.id = customer.building_type_id LEFT JOIN account_authority ON account.id = account_authority.account_id_customer 
--                   LEFT JOIN service_request on service_request.account_id_customer=account.id
-- LEFT JOIN service_assignment on service_assignment.service_request_id=service_request.id
                     WHERE customer.ward_id = :wards and account_service.service_id=:serviceId and account_authority.account_id_gt =:gt and account_authority.status =1  and account_service.status =1  and customer.status =1 and customer.residential_association_id=:association  
                     and (account.id,account_service.service_id) NOT IN (SELECT account_id_customer,service_request_test.service_id FROM service_request_test LEFT JOIN service_assignment_test on service_assignment_test.service_request_id=service_request_test.id where service_assignment_test.servicing_status_option_id is null)
                     -- and (service_assignment.servicing_status_option_id>0 or service_assignment.servicing_status_option_id=null or service_request.id=null)
                     ORDER BY customer.created_at DESC";

                  $command =  Yii::$app->db->createCommand($qry);
                  $command->bindParam(':wards',$ward);
                  $command->bindParam(':serviceId',$service);
                  $command->bindParam(':gt',$gt);
                  $command->bindParam(':association',$association);
                  $customersList = $command->queryAll();
                }
                  else
                  {
                     $qry = "SELECT DISTINCT customer.lead_person_name,customer.id as customer_id,account.id as account_id,customer.ward_id as ward_id,customer.building_type_id as building_type_id,account_authority.account_id_customer as account_id_customer,building_type.name as building_type_name,customer.building_number as building_number,customer.address as address, customer.association_name as association_name, customer.association_number as association_number,customer.residential_association_id as residential_association_id FROM customer LEFT JOIN account ON account.customer_id = customer.id 
                    LEFT JOIN account_service on account_service.account_id=account.id
                    LEFT JOIN building_type ON building_type.id = customer.building_type_id LEFT JOIN account_authority ON account.id = account_authority.account_id_customer 
--                    LEFT JOIN service_request on service_request.account_id_customer=account.id
-- LEFT JOIN service_assignment on service_assignment.service_request_id=service_request.id
                     WHERE customer.ward_id = :wards and account_service.service_id=:serviceId and account_authority.account_id_gt =:gt and account_authority.status =1  and account_service.status =1  and customer.status =1 and (account.id,account_service.service_id) NOT IN (SELECT account_id_customer,service_request_test.service_id FROM service_request_test LEFT JOIN service_assignment_test on service_assignment_test.service_request_id=service_request_test.id where service_assignment_test.servicing_status_option_id is null)
                     -- and (service_assignment.servicing_status_option_id>0 or service_assignment.servicing_status_option_id=null or service_request.id=null)  
                      ORDER BY customer.created_at DESC";

                  $command =  Yii::$app->db->createCommand($qry);
                  $command->bindParam(':wards',$ward);
                  $command->bindParam(':serviceId',$service);
                  $command->bindParam(':gt',$gt);	
			//echo $command->getRawSql();
                  $customersList = $command->queryAll();
                  }
                  
      }
      
    // }
    else:
      $customersList = null;
    endif;
    $dataProvider = new ArrayDataProvider([
        'allModels' =>$customersList,
          ]);
          $dataProvider->pagination = false;
        return $this->render('create', [
            'dataProvider' => $dataProvider,
            'ward' => $ward,
            'gt' => $gt,
            'service' => $service,
            'model' => $model,
        ]);
     
    }

    /**
     * Updates an existing Schedule model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $lsgi = $model->lsgi_id;
        $ward = $model->ward_id;
        $gt = $model->account_id_gt;
        $hks = $model->green_action_unit_id;
        $model->date= $model->date ? \Yii::$app->formatter->asDatetime($model->date , "php:d-m-Y") : '';
        $customer = [];
        $modelScheduleCustomer = ScheduleCustomerTest::find()->where(['schedule_id'=>$id])->andWhere(['status'=>1])->all();
        foreach ($modelScheduleCustomer as $key => $value) {
            $customer[] = $value->account_id_customer;
        }
        $model->customer_id  = $customer;
        if ($model->load(Yii::$app->request->post())) {
            $post = Yii::$app->request->post();
            $list = $post['Schedule']['customer_id']?$post['Schedule']['customer_id']:'';
            if(!$model->lsgi_id)
            {
                $model->lsgi_id = $lsgi;
            }
            if(!$model->ward_id)
            {
                $model->ward_id = $ward;
            }
            if(!$model->account_id_gt)
            {
                $model->account_id_gt = $gt;
            }
            if(!$model->green_action_unit_id)
            {
                $model->green_action_unit_id = $hks;
            }
             $model->date= $model->date ? \Yii::$app->formatter->asDatetime($model->date , "php:Y-m-d") : '';
            $model->save(false);
             if($list):
            // ScheduleCustomer::deleteAll(['schedule_id'=>$model->id]);
              $connection = Yii::$app->db;
            $sid = $model->id;
      $connection->createCommand()->update('schedule_customer', ['status' => 0], 'schedule_id=:id')->bindParam(':id',$sid)->execute();
            foreach ($list as  $value) {
                $ScheduleCustomer = new ScheduleCustomerTest();
                $ScheduleCustomer->account_id_customer = $value;
                $ScheduleCustomer->schedule_id = $model->id;
                $ScheduleCustomer->save(false);
            }
            endif;
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Schedule model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Schedule model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Schedule the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ScheduleTest::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
    public function actionDeleteSchedule($id)
    {
        $model = new ScheduleTest;
        $model->deleteSchedule($id);
    }
    public function actionWardAjax(){
        
        $arr = [];
        if(Yii::$app->request->isAjax):
            $data = Yii::$app->request->Post();
            $cat = $data['cat'];//echo $cat;exit;
            $arr = \yii\helpers\ArrayHelper::map(Ward::find()->where(['status'=>1])->andWhere(['lsgi_id'=>$cat])->all(), 'id', 'name');
            
        endif;
        yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $arr;
    }
    public function actionGtAjax(){
        
        $arr = [];
        if(Yii::$app->request->isAjax):
            $data = Yii::$app->request->Post();
            $gt = $data['gt'];//echo $cat;exit;
            $ward = $data['ward'];//echo $cat;exit;
            $service = $data['service'];//echo $cat;exit;
           
           $modelServicePackageService = ServicePackageService::find()->where(['service_id_service'=>$service])->andWhere(['status'=>1])->one();
            if($modelServicePackageService)
            {
                $modelService = Service::find()->where(['id'=>$modelServicePackageService->service_id])->andWhere(['status'=>1])->one();
                if($modelService&&$modelService->is_package==1)
                {
                     $arr = \yii\helpers\ArrayHelper::map(\backend\models\Customer::find()->where(['customer.status'=>1])->leftJoin('account','account.customer_id=customer.id')
          ->leftJoin('account_service','account.id=account_service.account_id')
          ->leftJoin('service_package_service','account_service.service_id=service_package_service.service_id')
          ->leftJoin('account_authority','account.id=account_authority.account_id_customer')
          ->andWhere(['customer.ward_id'=>$ward])
          ->andWhere(['account_service.service_id'=>$modelService->id])
          ->andWhere(['account_authority.account_id_gt'=>$gt])
          ->all(), 'id', 'lead_person_name');
       
                }
            }else{

            $arr = \yii\helpers\ArrayHelper::map(\backend\models\Customer::find()->where(['customer.status'=>1])->leftJoin('account','account.customer_id=customer.id')
          ->leftJoin('account_service','account.id=account_service.account_id')
          ->leftJoin('account_authority','account.id=account_authority.account_id_customer')
          ->andWhere(['customer.ward_id'=>$ward])
          ->andWhere(['account_service.service_id'=>$service])
          ->andWhere(['account_authority.account_id_gt'=>$gt])
          ->all(), 'id', 'lead_person_name');
      
    }
            
            
        endif;
        yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $arr;
       
    }
     public function actionAddHksSchedule()
    {
       $modelSchedule = new ScheduleTest;
        $ScheduleCustomer = new ScheduleCustomerTest();
        if ($modelSchedule->load(Yii::$app->request->post())) {
            $post = Yii::$app->request->post();
            $list = $post['Schedule']['customer_id']?$post['Schedule']['customer_id']:'';
            $modelUser = Yii::$app->user->identity;
            $userId = $modelUser->id;
            $modelSchedule->account_id_creator= $userId;
            $modelSchedule->date= $modelSchedule->date ? \Yii::$app->formatter->asDatetime($modelSchedule->date , "php:Y-m-d") : '';
            $modelSchedule->save(false);
            if($post['Schedule']['customer_id']):
            foreach ($list as  $value) {
                $ScheduleCustomer = new ScheduleCustomerTest();
                $ScheduleCustomer->account_id_customer = $value;
                $ScheduleCustomer->schedule_id = $modelSchedule->id;
                $ScheduleCustomer->save(false);
            }
            endif;
             return $this->redirect(['index']);
        }
      
      return $this->render('create', [
            'model' => $modelSchedule,
        ]);
    }
     public function actionGetGt() {
       $out = [];
        if (isset($_POST['depdrop_parents'])) {
        $parents = $_POST['depdrop_parents'];
        $ward = Ward::find()->where(['id'=>$parents[0]])->andWhere(['status'=>1])->one();
        $gt= Person::find()
            ->select('account.id as id,person.first_name as first_name')
            ->where(['account.status'=> 1])
            ->leftjoin('account','account.person_id=person.id')
            ->leftjoin('green_action_unit','account.green_action_unit_id=green_action_unit.id')
            ->leftjoin('green_action_unit_ward','green_action_unit_ward.green_action_unit_id=green_action_unit.id')
            // ->leftjoin('account','account.green_action_unit_id=green_action_unit.id')
            ->andWhere(['green_action_unit_ward.ward_id'=>$ward->id])
            ->andWhere(['account.role'=>'green-technician'])
            ->all();
        foreach ($gt as $id => $post) {
            $out[] = ['id' => $post['id'], 'name' => $post['first_name']];
 }
 echo Json::encode(['output' => $out, 'selected' => '']);
    }
  }
    public function actionRun($date=null) {
      
      $date= !$date?date('Y-m-d'):$date;
      echo  "Timezone :". date_default_timezone_get()." <br />";
      Yii::$app->cron1->scheduleServiceRequestsWards($date);
      echo "<br />Schedule ran for date ".$date;
    }
}
