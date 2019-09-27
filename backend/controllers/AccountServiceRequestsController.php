<?php

namespace backend\controllers;

use Yii;
use backend\models\ServiceRequest;
use backend\models\AccountService;
use backend\models\TestNew;
use backend\models\ServiceAssignment;
use backend\models\AccountServiceRequestSearch;
use backend\models\Person;
use backend\models\AccountAuthority;
use backend\models\AccountServiceRequest;
use backend\models\Customer;
use backend\models\CustomerSearch;
use backend\models\Service;
use backend\models\QrCode;
use yii\helpers\Json;
use backend\models\Account;
use backend\models\ServicePackageService;
use backend\models\ResidentialAssociation;
use backend\models\LsgiServiceSlabFee;
use backend\models\Slab;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use DateTime;
use yii\filters\AccessControl;
use backend\components\AccessPermission;
use yii\data\ActiveDataProvider;
use Yii\helpers\ArrayHelper;

/**
 * AccountServiceRequestsController implements the CRUD actions for AccountServiceRequest model.
 */
class AccountServiceRequestsController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class'        => AccessControl::className(),
                'only'         => ['index', 'create', 'update', 'view', 'view-details','customers-list'],
                'ruleConfig' => [
                        'class' => AccessPermission::className(),
                    ],
                'rules'        => [
                    [
                        'actions' => ['index'],
                        'allow'   => true,
                        'permissions' => ['account-service-requests-index']
                    ],
                    [
                        'actions' => ['create-account-service-requests'],
                        'allow'   => true,
                        'permissions' => ['account-service-requests-create-account-service-requests']
                    ],
                    [
                        'actions' => ['delete-deactivation-request'],
                        'allow'   => true,
                        'permissions' => ['account-service-requests-delete-deactivation-request',]
                    ],
                    [
                        'actions' => ['update'],
                        'allow'   => true,
                        'permissions' => ['account-service-requests-update',]
                    ],
                    [
                        'actions' => ['toggle-status-approved'],
                        'allow'   => true,
                        'permissions' => ['account-service-requests-toggle-status-approved',]
                    ],
                     [
                        'actions' => ['customers-list'],
                        'allow'   => true,
                        'permissions' => ['account-service-requests-customers-list',]
                    ],
                ],
                'denyCallback' => function (
                    $rule,
                    $action
                )
                {
                    return $this->goHome();
                }
            ]
        ];
    }

    /**
     * Lists all AccountServiceRequest models.
     * @return mixed
     */
    public function actionIndex($set=1)
    {
        $post   = yii::$app->request->post();
        $get    = yii::$app->request->get();
        $params = array_merge($post, $get);
        $vars   = [

            'name',
            'keyword',
            'district',
            'ward',
            'door',
            'lsgi',
            'surveyor',
            'from',
            'to',
            'type'
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
    $district = Yii::$app->session->get('district');
    $ward = Yii::$app->session->get('ward');
    $supervisor = Yii::$app->session->get('supervisor');
    $lsgi = Yii::$app->session->get('lsgi');
    $type = Yii::$app->session->get('type');
    $association = Yii::$app->session->get('association');
    $from          = Yii::$app->session->get('from');
    $to            = Yii::$app->session->get('to');
    
    $from              = $from ? \Yii::$app->formatter->asDatetime($from, "php:Y-m-d 00:00:00") : '';
    $to                = $to ? \Yii::$app->formatter->asDatetime($to, "php:Y-m-d 23:59:59") : '';
}



        // foreach ($vars as $param)
        // {
        //     ${
        //         $param}          = isset($params[$param]) ? $params[$param] : null;
        //     $newParams[$param] = ${
        //         $param};
        // }
        // $keyword       =isset($params['name'])?$params['name']:null;
        // $district      = isset($params['district'])?$params['district']:null;
        // $ward          = isset($params['ward'])?$params['ward']:null;
        // $lsgi          = isset($params['lsgi'])?$params['lsgi']:null;;
        // $type          = isset($params['type'])?$params['type']:null;;
        // $from          = isset($params['from'])?$params['from']:null;
        // $to            = isset($params['to'])?$params['to']:null;
        // $from              = $from ? \Yii::$app->formatter->asDatetime($from, "php:Y-m-d H:i:s") : '';
        // $to                = $to ? \Yii::$app->formatter->asDatetime($to, "php:Y-m-d 23:59:59") : '';
        $non_residential = 2;
        $searchModel = new AccountServiceRequestSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $keyword, $ward, $lsgi, $district, $from, $to,$type,$non_residential);
        $modelUser  = Yii::$app->user->identity;
        $associations = Yii::$app->rbac->getAssociations($modelUser->id);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'associations' => $associations,
        ]);
    }
    public function actionCreateAccountServiceRequests(){
      $lsgi = null;
      $district = null;
      $ward = null;
      $unit = null;
      $gt_id = null;
      $agency = null;
      $user = yii::$app->user->identity->role;
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
        if(isset($associations['ward_id']))
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
            $gt_id = $associations['gt_id'];
        }
        if(isset($associations['survey_agency_id']))
        {
            $agency = $associations['survey_agency_id'];
        }
      $modelServiceRequest = new AccountServiceRequest;
      $modelServiceAssignment = new ServiceAssignment;
      $modelAccount = Account::find()->where(['status'=>1,'role'=>'supervisor']);
      if($unit)
      {
        $modelAccount = $modelAccount->andWhere(['green_action_unit_id'=>$unit]);
      }

      $modelAccount = $modelAccount->all();
      $modelPerson = [];
      foreach ($modelAccount as $account) {
        $modelPerson[] = Person::find()->where(['status'=>1,'id'=>$account->person_id])->one();
      }
      // $modelCustomer = Customer::find()->where(['status'=>1])->all();
      $params = Yii::$app->request->post();
      $paramsOk = $params && $modelServiceRequest->load($params);
      if($paramsOk){
        // $modelServiceRequest->setScenario('create');
          $account = Account::find()->where(['status'=>1,'customer_id'=>$modelServiceRequest->account_id])->one();
          if($account){
            $modelServiceRequest->account_id = $account->id;
          }else{
            $modelServiceRequest->account_id = 0;
          }
          $modelServiceRequest->requested_at = date('Y-m-d H:i:s');
          $modelServiceRequest->account_id_requested_by = $modelUser->id;
          $modelServiceRequest->sub_service = serialize($modelServiceRequest->sub_service);
          if(Yii::$app->user->can('account-service-requests-toggle-status-approved')||$userRole=='super-admin'){
             $modelServiceRequest->toggleStatusApproved();
          }
          $modelServiceRequest->save(false);
          return $this->redirect('index');
      }
      return $this->render('create-account-service-requests', [
        'modelServiceRequest' => $modelServiceRequest,
        // 'modelCustomer' =>$modelCustomer,
        'modelAccount' => $modelAccount,
        'modelPerson' => $modelPerson,
      ]);
    }

    public function actionGetServiceCustomer(){
      $out = [];
      if (isset($_POST['depdrop_parents'])) {
        $parents = $_POST['depdrop_parents'];
        $modelAccount = Account::find()->where(['person_id'=>$parents[0]])->andWhere(['status'=>1])->one();
        $modelCustomer = Customer::find()
        ->select('account.id as id,customer.lead_person_name as lead_person_name')
        ->leftjoin('account','account.customer_id=customer.id')
        ->leftjoin('account_authority','account_authority.account_id_customer=account.id')
        ->where(['account_authority.account_id_supervisor'=>$modelAccount->id])
        ->andWhere(['account.status'=>1])
        ->andWhere(['customer.status'=>1])
        ->andWhere(['account_authority.status'=>1])
        ->all();
        foreach ($modelCustomer as $id => $post) {
          $out[] = ['id' => $post['id'], 'name' => $post['lead_person_name']];
        }
        echo Json::encode(['output' => $out, 'selected' => '']);
      }
    }
    public function actionGetServiceCustomerList(){
      $out = [];
      if (isset($_POST['depdrop_parents'])) {
        $parents = $_POST['depdrop_parents'];
        $modelResidentailAssociation = ResidentialAssociation::find()->where(['id'=>$parents[0]])->andWhere(['status'=>1])->one();
        $modelCustomer = Customer::find()
        ->select('account.id as id,customer.lead_person_name as lead_person_name')
        ->leftjoin('residential_association','residential_association.id=customer.residential_association_id')
        ->leftjoin('account','account.customer_id=customer.id')
        // ->leftjoin('account_authority','account_authority.account_id_customer=account.id')
        // ->where(['account_authority.account_id_supervisor'=>$modelAccount->id])
        ->where(['account.status'=>1])
        ->andWhere(['customer.status'=>1])
        ->andWhere(['customer.residential_association_id'=>$modelResidentailAssociation->id])
        // ->andWhere(['account_authority.status'=>1])
        ->all();
        foreach ($modelCustomer as $id => $post) {
          $out[] = ['id' => $post['id'], 'name' => $post['lead_person_name']];
        }
        echo Json::encode(['output' => $out, 'selected' => '']);
      }
    }

    public function actionGetResidentialAssociation(){
      $out = [];
      if (isset($_POST['depdrop_parents'])) {
        $parents = $_POST['depdrop_parents'];
        $modelAccount = Account::find()->where(['person_id'=>$parents[0]])->andWhere(['status'=>1])->one();
        // print_r($modelAccount);die();
        $modelResidentailAssociation = ResidentialAssociation::find()
        // ->select('account.id as id,customer.lead_person_name as lead_person_name')
        ->leftjoin('account_ward','account_ward.ward_id=residential_association.ward_id')
        ->where(['account_ward.account_id'=>$modelAccount->id])
        ->andWhere(['residential_association.status'=>1])
        ->andWhere(['account_ward.status'=>1])
        ->all();
        foreach ($modelResidentailAssociation as $id => $post) {
          $out[] = ['id' => $post['id'], 'name' => $post['name']];
        }
        echo Json::encode(['output' => $out, 'selected' => '']);
      }
    }

    public function actionGetServicesCustomer(){
      $out = [];
      if (isset($_POST['depdrop_parents'])) {
        $parents = $_POST['depdrop_parents'];
        $modelAccount = Account::find()->where(['person_id'=>$parents[0]])->andWhere(['status'=>1])->one();
        $modelCustomer = Customer::find()
        ->select('customer.id as id,customer.lead_person_name as lead_person_name')
        ->leftjoin('account','account.customer_id=customer.id')
        ->leftjoin('account_authority','account_authority.account_id_customer=account.id')
        ->where(['account_authority.account_id_supervisor'=>$modelAccount->id])
        ->andWhere(['account.status'=>1])
        ->andWhere(['customer.status'=>1])
        ->andWhere(['account_authority.status'=>1])
        ->all();
        foreach ($modelCustomer as $id => $post) {
          $out[] = ['id' => $post['id'], 'name' => $post['lead_person_name']];
        }
        echo Json::encode(['output' => $out, 'selected' => '']);
      }
    }

    public function actionGetSubService(){
      $out = [];
      if (isset($_POST['depdrop_parents'])) {
        $parents = $_POST['depdrop_parents'];
        $service = Service::find()->where(['id'=>$parents[0]])->andWhere(['status'=>1])->one();
        $modelSubService = Service::find()
        ->leftjoin('service_package_service','service.id=service_package_service.service_id_service')
        ->where(['service_package_service.service_id'=>$service->id])
        ->andWhere(['service_package_service.status'=>1])
        ->andWhere(['service.status'=>1])
        ->all();
        foreach ($modelSubService as $id => $post) {
          $out[] = ['id' => $post['id'], 'name' => $post['name']];
        }
        echo Json::encode(['output' => $out, 'selected' => '']);
      }
    }

    public function actionGetCustomerServices(){
      $out = [];
      if (isset($_POST['depdrop_parents'])) {
        $parents = $_POST['depdrop_parents'];
        $modelAccount = Account::find()->where(['customer_id'=>$parents[0]])->andWhere(['status'=>1])->one();
        $modelService = Service::find()
        ->leftjoin('account_service','account_service.service_id=service.id')
        ->where(['account_service.account_id'=>$modelAccount->id])
        ->andWhere(['account_service.status'=>1])
        ->andWhere(['service.status'=>1])
        ->all();
        foreach ($modelService as $id => $post) {
          $out[] = ['id' => $post['id'], 'name' => $post['name']];
        }
        echo Json::encode(['output' => $out, 'selected' => '']);
      }
    }
    public function actionGetCustomerPackage(){
      $out = [];
      if (isset($_POST['depdrop_parents'])) {
        $parents = $_POST['depdrop_parents'];
        $modelAccount = Account::find()->where(['customer_id'=>$parents[0]])->andWhere(['status'=>1])->one();
        $modelService = Service::find()
        ->leftjoin('account_service_request','account_service_request.service_id=service.id')
        // ->leftjoin('account_service','account_service.service_id=service.id')
        ->where(['account_service_request.status'=>null])
        ->andWhere(['service.is_package'=>1])
        ->andWhere(['service.status'=>1])
        ->all();
        foreach ($modelService as $id => $post) {
          $out[] = ['id' => $post['id'], 'name' => $post['name']];
        }
        echo Json::encode(['output' => $out, 'selected' => '']);
      }
    }

    public function actionGetGreenTechnician(){
      $out = [];
      if (isset($_POST['depdrop_parents'])) {
        $parents = $_POST['depdrop_parents'];
        $modelAccount = Account::find()->where(['person_id'=>$parents[0]])->andWhere(['status'=>1])->one();
        $modelPerson = Person::find()
        ->leftjoin('account','account.person_id=person.id')
        ->leftjoin('account_authority','account_authority.account_id_gt=account.id')
        ->where(['account_authority.account_id_supervisor'=>$modelAccount->id])
        ->andWhere(['account.status'=>1])
        ->andWhere(['person.status'=>1])
        ->andWhere(['account_authority.status'=>1])
        ->all();
        foreach ($modelPerson as $id => $post) {
          $out[] = ['id' => $post['id'], 'name' => $post['first_name']];
        }
        echo Json::encode(['output' => $out, 'selected' => '']);
      }
    }
    /**
     * Displays a single AccountServiceRequest model.
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
     * Creates a new AccountServiceRequest model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new AccountServiceRequest();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing AccountServiceRequest model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if($model->service_estimate)
        {
          $serviceEstimate = unserialize($model->service_estimate);
        }
        // print_r($serviceEstimate);die();
        if ($model->load(Yii::$app->request->post())) {
          $post = Yii::$app->request->post();
          if(isset($post['AccountServiceRequest']))
          {
            $model->service_estimate = serialize($post['AccountServiceRequest']);
            $serviceIds = [];
            foreach ($post['AccountServiceRequest'] as $key => $value) {
              $serviceIds[] = $value['id'];
            }
            $model->sub_service = serialize($serviceIds);
            $model->pre_verification_remarks = $post['AccountService']['pre_verification_remarks'];
            $model->pre_verification_needed = $post['AccountService']['pre_verification_remarks']?1:0;
            $model->save(false);
          }
            return $this->redirect(['non-residential']);
        }

        return $this->render('update', [
            'model' => $model,
            'serviceEstimate' => $serviceEstimate,
        ]);
    }
    public function actionUpdateServiceEnablingRequest($id)
    {
      $advanceAmount = 0;
        $model = $this->findModel($id);
         $modelAccount = Account::find()->where(['id' => $model->account_id])->one();
         $modelCustomer        = $this->findCustomerModel($modelAccount->customer_id);
         $modelQrCode = QrCode::find()->where(['account_id'=>$modelAccount->id])->andWhere(['status'=>1])->one();
        if($model->service_estimate)
        {
          $serviceEstimate = unserialize($model->service_estimate);
        }
        foreach ($serviceEstimate as $key => $value) {
          if($value['slab']==0&&$value['estimated_qty_kg']!=null){
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
              $advanceAmount =  $advanceAmount + ($value['estimated_qty_kg']*$modelLsgiServiceSlabFee->amount*45);
            }
            else
            {
              $advanceAmount =  $advanceAmount + $modelLsgiServiceSlabFee->amount*1.5;
            }
          }
        else
        {
          if($modelLsgiServiceSlabFee->use_for_per_kg_rate==1)
            {
              $advanceAmount =  $advanceAmount - ($value['estimated_qty_kg']*$modelLsgiServiceSlabFee->amount*45);
            }
            else
            {
              $advanceAmount =  $advanceAmount -$modelLsgiServiceSlabFee->amount*1.5;
            }
        }
        }
      }
        if ($model->load(Yii::$app->request->post())&&$modelCustomer->load(Yii::$app->request->post())&&$modelCustomer->validate()) {
          $post = Yii::$app->request->post();
          $modelCustomer->save(false);
          if(isset($post['AccountServiceRequest']))
          {
            $model->service_estimate = serialize($post['AccountServiceRequest']);
            if(isset($post['AccountService'])){
            $model->pre_verification_remarks = isset($post['AccountService']['pre_verification_remarks'])?$post['AccountService']['pre_verification_remarks']:null;
            $model->pre_verification_needed = isset($post['AccountService']['pre_verification_remarks'])?1:0;
            $model->reason_for_disable = isset($post['AccountService']['reason_for_disable'])?$post['AccountService']['reason_for_disable']:null;
          }
            $model->save(false);
          }
            return $this->redirect(['non-residential-service-enabling-request']);
        }

        return $this->render('update-service-enabling-request', [
            'model' => $model,
            'serviceEstimate' => $serviceEstimate,
            'modelCustomer' => $modelCustomer,
            'modelAccount' => $modelAccount,
            'advanceAmount' => $advanceAmount,
        ]);



        // $model = $this->findModel($id);
        // $modelAccount = Account::find()->where(['id' => $model->account_id])->one();
        //  $modelCustomer        = $this->findCustomerModel($modelAccount->customer_id);
        // $modelQrCode = QrCode::find()->where(['account_id'=>$modelAccount->id])->andWhere(['status'=>1])->one();
        //  $modelCustomer->setScenario('registration');
        // $post = Yii::$app->request->post();
        // if ($modelCustomer->load(Yii::$app->request->post())&&$modelCustomer->validate()) {
        //   $modelCustomer->save(false);
        //   // $modelAccountServiceRequest = new AccountServiceRequest;
        //   $model->service_id = $modelCustomer->service;
        //   $model->account_id = $modelAccount->id;
        //   $model->request_type = $modelCustomer->type;
        //   $model->qty            = $modelCustomer->daily_bio_waste_quantity;
        //   $model->collection_interval            = $modelCustomer->waste_collection_interval_id;
        //   $model->save(false);
        //   return $this->redirect(['non-residential-service-enabling-request']);
        // }
        // return $this->render('update-service-enabling-request', [
        //     'modelCustomer'        => $modelCustomer,
        //     'modelAccount' => $modelAccount,
        //     'modelQrCode'=>$modelQrCode,
        //     'model'=>$model,
        // ]);
    }
    public function actionUpdateAgreementPending($id)
    {
        $model = $this->findModel($id);
        if($model->service_estimate)
        {
          $serviceEstimate = unserialize($model->service_estimate);
        }
        // print_r($serviceEstimate);die();
        if ($model->load(Yii::$app->request->post())) {
          $post = Yii::$app->request->post();
          if(isset($post['AccountServiceRequest']))
          {
            $model->service_estimate = serialize($post['AccountServiceRequest']);
            if(isset($post['AccountService'])){
            $model->pre_verification_remarks = isset($post['AccountService']['pre_verification_remarks'])?$post['AccountService']['pre_verification_remarks']:null;
            $model->pre_verification_needed = isset($post['AccountService']['pre_verification_remarks'])?1:0;
          }
            $model->save(false);
          }
            return $this->redirect(['agreement-pending']);
        }

        return $this->render('update-agreement-pending', [
            'model' => $model,
            'serviceEstimate' => $serviceEstimate,
        ]);
    }
    public function actionAgreementCompletedDetail($id)
    {
        $model = $this->findModel($id);
        if($model->service_estimate)
        {
          $serviceEstimate = unserialize($model->service_estimate);
        }
        // print_r($serviceEstimate);die();
        // if ($model->load(Yii::$app->request->post())) {
        //   $post = Yii::$app->request->post();
        //   if(isset($post['AccountServiceRequest']))
        //   {
        //     $model->service_estimate = serialize($post['AccountServiceRequest']);
        //     if(isset($post['AccountService'])){
        //     $model->pre_verification_remarks = isset($post['AccountService']['pre_verification_remarks'])?$post['AccountService']['pre_verification_remarks']:null;
        //     $model->pre_verification_needed = isset($post['AccountService']['pre_verification_remarks'])?1:0;
        //   }
        //   }
        //     return $this->redirect(['agreement-completed']);
        // }

        return $this->render('agreement-completed-detail', [
            'model' => $model,
            'serviceEstimate' => $serviceEstimate,
        ]);
    }
    public function actionDeleteRequestService($id,$service,$qty)
    {
        $model = $this->findModel($id);
        $subServices = unserialize($model->sub_service);
        $serviceEstimate = unserialize($model->service_estimate);
        $subServicesList = [];
        foreach ($subServices as $key => $value) {
         if($value==$service)
         {
          continue;
         }
         else
         {
          $subServicesList[] = $value;
         }
        }
        $serviceEstimateList  = [];
        foreach ($serviceEstimate as $key => $value) {
         if($value['id']==$service && $value['estimated_qty_kg'])
         {
          continue;
         }else
         {
            $serviceEstimateList[] = [
            'id'=> $value['id'],
            'estimated_qty_kg'=> $value['estimated_qty_kg'],
            ];
         }
        }
       $model->service_estimate = serialize($serviceEstimateList);
       $model->sub_service = serialize($subServicesList);
       $model->save(false);
    }

    /**
     * Deletes an existing AccountServiceRequest model.
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
     * Finds the AccountServiceRequest model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return AccountServiceRequest the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = AccountServiceRequest::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
    protected function findCustomerModel($id)
    {
        if (($model = Customer::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
     public function actionToggleStatusApproved($id)
      {
        $modelAccountServiceRequest = $this->findModel($id);
        $status=$modelAccountServiceRequest->toggleStatusApproved();
        echo json_encode(['status'=> $status]);
    }
     public function actionToggleStatusApprovedAgreement($id)
      {
        $modelAccountServiceRequest = $this->findModel($id);
        $status=$modelAccountServiceRequest->toggleStatusApprovedAgreement();
        echo json_encode(['status'=> $status]);
    }
    public function actionToggleStatusApprovedNonResidential($id)
      {
        $modelAccountServiceRequest = $this->findModel($id);
        $status=$modelAccountServiceRequest->toggleStatusApprovedNonResidential();
        echo json_encode(['status'=> $status]);
    }
    public function actionToggleStatusDisApprovedNonResidential($id)
      {
        $modelAccountServiceRequest = $this->findModel($id);
        $status=$modelAccountServiceRequest->toggleStatusDisApprovedNonResidential();
        echo json_encode(['status'=> $status]);
    }
    public function actionToggleStatusFirstApproved($id)
      {
        $modelAccountServiceRequest = $this->findModel($id);
        $status=$modelAccountServiceRequest->toggleStatusFirstApproved();
        echo json_encode(['status'=> $status]);
    }
    public function actionToggleStatusFirstDisApproved($id)
      {
        $modelAccountServiceRequest = $this->findModel($id);
        $status=$modelAccountServiceRequest->toggleStatusFirstDisApproved();
        echo json_encode(['status'=> $status]);
    }
    public function actionDeleteDeactivationRequest($id)
    {
        $model = new DeactivationRequest;
        $model->deleteRequest($id);
    }
   public function actionGetServicesForAccount(){
      $newArray    = [];
      $out    = [];
      if (isset($_POST['depdrop_parents'])) {
        $parents = $_POST['depdrop_parents'];
        $modelAccount = Account::find()->where(['customer_id'=>$parents[0]])->andWhere(['status'=>1])->one();
        // print_r($parents[0]);die();
        $account_id  = $modelAccount->id;
        $query = Service::getAllQuery()->andWhere(['service.status' => 1])->andWhere(['type'=>1]);
        $modelAccountService = AccountService::find()->select('service_id,account_service.account_id')->where(['account_id' => $account_id])->andWhere(['account_service.status' => 1]);
        $serviceIdExcluded = [];
       $dataAll = $modelAccountService->all();
       foreach ($dataAll as $value) {
            $serviceIdExcluded[] = $value->service_id;

       }

       $modelAccountServicePackage = AccountService::find()->select('service_id,account_service.account_id,account_service.package_id')->where(['account_id' => $account_id])->andWhere(['account_service.status' => 1])->andWhere(['>','package_id',0]);
       $dataAll = $modelAccountServicePackage->all();
       foreach ($dataAll as $value) {
            $serviceIdExcluded[] = $value->package_id;

       }
        $dataProvider = new ActiveDataProvider([
            'query'      => $query,
            'pagination' => false

        ]);
        $models = $dataProvider->getModels();
        // print_r($serviceIdExcluded);die();
        $newArray    = [];
        foreach ($models as $model)
        {
            $image     = null;
            $serviceId = $model->id;
            if($parents[1]==1){
            if (in_array($serviceId, $serviceIdExcluded))
            {
                continue;
            }else
            {
              $newArray[] = $serviceId;
            }
          }
          else
          {
            if (in_array($serviceId, $serviceIdExcluded))
            {
              $newArray[] = $serviceId;
                
            }else
            {
              continue;
            }
          }
          }
       }
       foreach ($newArray as $id => $value) {
      $post = Service::find()->where(['id'=>$value])->one();
          $out[] = ['id' => $post['id'], 'name' => $post['name']];
        }
        echo Json::encode(['output' => $out, 'selected' => '']);
     }
     public function actionNonResidential()
    {
        $post   = yii::$app->request->post();
        $get    = yii::$app->request->get();
        $params = array_merge($post, $get);
        $vars   = [

            'name',
            'keyword',
            'district',
            'ward',
            'door',
            'lsgi',
            'surveyor',
            'from',
            'to',
            'type'
        ];
        $newParams = [];
        foreach ($vars as $param)
        {
            ${
                $param}          = isset($params[$param]) ? $params[$param] : null;
            $newParams[$param] = ${
                $param};
        }
        $keyword       =isset($params['name'])?$params['name']:null;
        $district      = isset($params['district'])?$params['district']:null;
        $ward          = isset($params['ward'])?$params['ward']:null;
        $lsgi          = isset($params['lsgi'])?$params['lsgi']:null;;
        $type          = isset($params['type'])?$params['type']:null;;
        $from          = isset($params['from'])?$params['from']:null;
        $to            = isset($params['to'])?$params['to']:null;
        $from              = $from ? \Yii::$app->formatter->asDatetime($from, "php:Y-m-d H:i:s") : '';
        $to                = $to ? \Yii::$app->formatter->asDatetime($to, "php:Y-m-d 23:59:59") : '';
        $non_residential = 1;
        $searchModel = new AccountServiceRequestSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $keyword, $ward, $lsgi, $district, $from, $to,$type,$non_residential);
        $modelUser  = Yii::$app->user->identity;
        $associations = Yii::$app->rbac->getAssociations($modelUser->id);
        return $this->render('non-residential-list', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'associations' => $associations,
        ]);
    }
     public function actionNonResidentialServiceEnablingRequest($set=null)
    {
        $post   = yii::$app->request->post();
        $get    = yii::$app->request->get();
        $params = array_merge($post, $get);
        $keyword = null;
         $ward= null;
          $lsgi = null;
           $district = null;
           $from = null;
           $to = null;
           $type = null;
           $non_residential= null;
           $status = null;
        $vars   = [

            'name',
            'keyword',
            'district',
            'ward',
            'door',
            'lsgi',
            'surveyor',
            'from',
            'to',
            'type',
            'status',
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
    $district = Yii::$app->session->get('district');
    $status = Yii::$app->session->get('status');
    $lsgi = Yii::$app->session->get('lsgi');
    $status = Yii::$app->session->get('status');
    $from          = Yii::$app->session->get('from');
    $to            = Yii::$app->session->get('to');
        $from              = $from ? \Yii::$app->formatter->asDatetime($from, "php:Y-m-d H:i:s") : '';
        $to                = $to ? \Yii::$app->formatter->asDatetime($to, "php:Y-m-d 23:59:59") : '';
      }
        $non_residential = 1;
        $type = 2;
        $searchModel = new AccountServiceRequestSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $keyword, $ward, $lsgi, $district, $from, $to,$type,$non_residential,$status);
        $modelUser  = Yii::$app->user->identity;
        $associations = Yii::$app->rbac->getAssociations($modelUser->id);
        return $this->render('non-residential-service-enabling-list', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'associations' => $associations,
        ]);
    }
    public function actionAgreementPending($set=null)
    {
        $post   = yii::$app->request->post();
        $get    = yii::$app->request->get();
        $params = array_merge($post, $get);
        $keyword = null;
         $ward= null;
          $lsgi = null;
           $district = null;
           $from = null;
           $to = null;
           $type = null;
           $non_residential= null;
           $status = null;
        $vars   = [

            'name',
            'keyword',
            'district',
            'ward',
            'door',
            'lsgi',
            'surveyor',
            'from',
            'to',
            'type',
            'status',
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
    $district = Yii::$app->session->get('district');
    $status = Yii::$app->session->get('status');
    $lsgi = Yii::$app->session->get('lsgi');
    $status = Yii::$app->session->get('status');
    $from          = Yii::$app->session->get('from');
    $to            = Yii::$app->session->get('to');
        $from              = $from ? \Yii::$app->formatter->asDatetime($from, "php:Y-m-d H:i:s") : '';
        $to                = $to ? \Yii::$app->formatter->asDatetime($to, "php:Y-m-d 23:59:59") : '';
      }
        $non_residential = 1;
        $type = 2;
        $agreement_status = 0;
        $searchModel = new AccountServiceRequestSearch();
        $dataProvider = $searchModel->agreement(Yii::$app->request->queryParams, $keyword, $ward, $lsgi, $district, $from, $to,$type,$non_residential,$status,$agreement_status);
        $modelUser  = Yii::$app->user->identity;
        $associations = Yii::$app->rbac->getAssociations($modelUser->id);
        return $this->render('agreement-pending', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'associations' => $associations,
        ]);
    }
    public function actionAgreementCompleted($set=null)
    {
        $post   = yii::$app->request->post();
        $get    = yii::$app->request->get();
        $params = array_merge($post, $get);
        $keyword = null;
         $ward= null;
          $lsgi = null;
           $district = null;
           $from = null;
           $to = null;
           $type = null;
           $non_residential= null;
           $status = null;
        $vars   = [

            'name',
            'keyword',
            'district',
            'ward',
            'door',
            'lsgi',
            'surveyor',
            'from',
            'to',
            'type',
            'status',
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
    $district = Yii::$app->session->get('district');
    $status = Yii::$app->session->get('status');
    $lsgi = Yii::$app->session->get('lsgi');
    $status = Yii::$app->session->get('status');
    $from          = Yii::$app->session->get('from');
    $to            = Yii::$app->session->get('to');
        $from              = $from ? \Yii::$app->formatter->asDatetime($from, "php:Y-m-d H:i:s") : '';
        $to                = $to ? \Yii::$app->formatter->asDatetime($to, "php:Y-m-d 23:59:59") : '';
      }
        $non_residential = 1;
        $type = 2;
        $agreement_status = 1;
        $searchModel = new AccountServiceRequestSearch();
        $dataProvider = $searchModel->agreement(Yii::$app->request->queryParams, $keyword, $ward, $lsgi, $district, $from, $to,$type,$non_residential,$status,$agreement_status);
        $modelUser  = Yii::$app->user->identity;
        $associations = Yii::$app->rbac->getAssociations($modelUser->id);
        return $this->render('agreement-completed', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'associations' => $associations,
        ]);
    }
    public function actionCustomersList($set = null)
    {
        $post   = yii::$app->request->post();
        $get    = yii::$app->request->get();
        $params = array_merge($post, $get);
         $keyword       = null;
        $customerId       = null;
        $district      = null;
        $ward          = null;
        $door          = null;
        $lsgi          = null;
        $surveyor      = null;
        $to            = null;
        $code            = null;
        $association            = null;
        $no_association            = null;
        $building_type            = null;
        $from              = null;
        $to   =null;
        $qrcode   =null;
        $vars   = [

            'name',
            'customer_id',
            'keyword',
            'district',
            'ward',
            'association',
            'no_association',
            'building_type',
            'door',
            'lsgi',
            'surveyor',
            'code',
            'from',
            'to',
            'qrcode'
        ];
        $newParams = [];
         if($set==null)
        {
            $session = Yii::$app->session;
            $session->destroy();
        }
        else{
        $session   = Yii::$app->session;
        foreach ($vars as $param)
        {
            ${
                $param}          = isset($params[$param]) ? $params[$param] : null;
            $newParams[$param] = ${
                $param};
            if (${
                $param} !== null)
            {
                $session->set($param, ${
                    $param});
            }
        }
        $keyword       = Yii::$app->session->get('name');
        $customerId       = Yii::$app->session->get('customer_id');
        $district      = Yii::$app->session->get('district');
        $ward          = Yii::$app->session->get('ward');
        $door          = Yii::$app->session->get('door');
        $lsgi          = Yii::$app->session->get('lsgi');
        $surveyor      = Yii::$app->session->get('surveyor');
        $from          = Yii::$app->session->get('from');
        $to            = Yii::$app->session->get('to');
        $code            = Yii::$app->session->get('code');
        $association            = Yii::$app->session->get('association');
        $no_association            = Yii::$app->session->get('no_association');
        $building_type            = Yii::$app->session->get('building_type');
        $qrcode            = Yii::$app->session->get('qrcode');
        $from              = $from ? \Yii::$app->formatter->asDatetime($from, "php:Y-m-d H:i:s") : '';
        $to                = $to ? \Yii::$app->formatter->asDatetime($to, "php:Y-m-d 23:59:59") : '';
      }
        $modelCustomer = new Customer;
        $searchModel   = new AccountServiceRequest();
        $dataProvider  = $searchModel->searchNonResidential(Yii::$app->request->queryParams, $keyword, $ward, $lsgi, $district, $door, $surveyor, $from, $to,$customerId,$code,$association,$no_association,$building_type,$qrcode);
        $modelUser  = Yii::$app->user->identity;
        $associations = Yii::$app->rbac->getAssociations($modelUser->id);
        return $this->render('customers-list', [
            'searchModel'   => $searchModel,
            'dataProvider'  => $dataProvider,
            'modelCustomer' => $modelCustomer,
            'associations' => $associations
        ]);
    }
    public function actionViewCustomer($id)
    {  $model = new AccountServiceRequest();
        $modelCustomer        = $this->findCustomerModel($id);
        $modelAccount = Account::find()->where(['customer_id' => $modelCustomer->id])->one();
        $modelQrCode = QrCode::find()->where(['account_id'=>$modelAccount->id])->andWhere(['status'=>1])->one();
        return $this->render('view-customer', [
            'modelCustomer'        => $modelCustomer,
            'modelAccount' => $modelAccount,
            'model' => $model,
            'modelQrCode'=>$modelQrCode,
        ]);
    }
    public function actionWasteCollectionRegistration($id)
    {
        $modelCustomer = $this->findCustomerModel($id);
        $modelAccount = Account::find()->where(['customer_id'=>$id])->andWhere(['status'=>1])->one();
        $model = new AccountServiceRequest;
        // $modelCustomer->setScenario('registration');
        $post = Yii::$app->request->post();
        // print_r($post);die();
        if ($modelCustomer->load(Yii::$app->request->post())&&$modelCustomer->validate()) {
          $modelCustomer->save(false);
          $model->service_id = 0;
          $model->account_id = $modelAccount->id;
          $model->request_type = 2;
          $model->requested_at            = date('Y-m-d H:i:s');
          $model->account_id_requested_by            = Yii::$app->user->identity->id;
          if(isset($post['AccountServiceRequest']))
                    {
                      $serviceEstimateArray = [];
                      foreach ($post['AccountServiceRequest'] as $key => $value) {
                       // if($value['estimated_qty_kg']!=null)
                        $serviceEstimateArray[] =$value;
                      }
                      $model->service_estimate = serialize($serviceEstimateArray);
                    }
          $model->save(false);
          return $this->redirect(['non-residential-service-enabling-request']);
        }

        return $this->render('view-customer', [
            'modelCustomer'        => $modelCustomer,
            'modelAccount' => $modelAccount,
            'model' => $model,
        ]);
    }
   
     public function actionSlabAjax(){
        
        $arr = [];
        if(Yii::$app->request->isAjax):
            $data = Yii::$app->request->Post();
           $qty = $data['qty'];
            $interval = $data['interval'];
            $service = $data['service'];
            $arr = Slab::find()->select('slab.id,slab.name')
              ->leftjoin('lsgi_service_slab_fee','lsgi_service_slab_fee.slab_id=slab.id')
              ->
                    where(['lsgi_service_slab_fee.collection_interval'=>$interval])->andWhere(['<','lsgi_service_slab_fee.start_value',$qty])->andWhere(['>','lsgi_service_slab_fee.end_value',$qty])
                    ->andWhere(['lsgi_service_slab_fee.service_id'=>$service])
                    ->one();
                    // print_r($arr);die();
                    $result =[];
                    $result[$arr['id']] = $arr['name'];
            
        endif;
        yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $result;
    }
    public function actionSendMessage()
    {
      $modelAccountService = TestNew::find()->where(['send'=>0])
      ->andWhere(['>=','id',6])
      // ->andWhere(['<=','id',5])
      ->all();
      // print_r(count($modelAccountService));die();
      foreach ($modelAccountService as $key => $value) {
       $modelAccount = Account::find()->where(['status'=>1])->andWhere(['id'=>$value->account_id])->one();
       if($modelAccount)
      {
        // print_r($modelAccount);die();
        $modelCustomer = $modelAccount->fkCustomer;
        if($modelCustomer)
        {
                $authKey = Yii::$app->params['authKeyMsg'];
                $phone = $modelCustomer->lead_person_phone;
                $username = $modelAccount->username;
                $modelAccount->password_hash = Yii::$app->security->generatePasswordHash($username);
                $modelAccount->save(false);
                $password = $modelAccount->username;
                $content ="Welcome to Green Trivandrum,smart waste management initiative of Thiruvananthapuram municipal corporation. Your Customer ID is ". $username." and password is ".$password. ".You can login using customer id or registered mobile number. You can download Green Trivandrum app from play store. https://play.google.com/store/apps/details?id=com.tvm.user.greenapp";
                   $key = 'account_id';
                   $countryCode = '91';
                   $senderId = 'WMSMGMT';
                   Yii::$app->message->sendSMS($authKey,"WMSMGMT","91",$phone,$content);
                   $value->send=1;
                   $value->save(false);


        }
      }

      }
    }
     
}
   
