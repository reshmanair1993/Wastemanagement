<?php
namespace backend\controllers;

use Yii;
use yii\helpers\Json;
use yii\web\Controller;
use backend\models\Ward;
use backend\models\Image;
use yii\web\UploadedFile;
use backend\models\Account;
use backend\models\Customer;
use backend\models\NewCust;
use backend\models\CustomerNew;
use backend\models\Payment;
use backend\models\AccountService;
use backend\models\QrCodes;
use yii\filters\AccessControl;
use backend\models\CustomerSearch;
use backend\models\CustomerSearchTest;
use backend\models\AccountAuthority;
use backend\models\AccountSlabService;
use yii\web\NotFoundHttpException;
use yii\data\ActiveDataProvider;
use backend\components\AccessPermission;
use backend\components\AccessRule;
// ini_set("memory_limit","5000M");
// ini_set("max_execution_time","500");
// ini_set("pcre.backtrack_limit", "50000000");
/**
 * CustomersController implements the CRUD actions for Customer model.
 */
class CustomersController extends Controller
{
    /**
     * {@inheritdoc}
     */
    // public function behaviors()
    // {
    //     return [
    //         'access' => [
    //             'class'        => AccessControl::className(),
    //             'only'         => ['index', 'create', 'update', 'view', 'view-details'],
    //             'rules'        => [
    //                 [
    //                     'actions' => ['index', 'create', 'update', 'view', 'view-details'],
    //                     'allow'   => true,
    //                     'roles'   => ['@']
    //                 ]
    //             ],
    //             'denyCallback' => function (
    //                 $rule,
    //                 $action
    //             )
    //             {
    //                 return $this->goHome();
    //             }
    //         ]
    //     ];
    // }
    public function behaviors()
    {
        return [
            'access' => [
                'class'        => AccessControl::className(),
                'only'         => ['index', 'create', 'update', 'view', 'view-details','profile'],
                'ruleConfig' => [
                        'class' => AccessPermission::className(),
                    ],
                'rules'        => [
                    [
                        'actions' => ['index'],
                        'allow'   => true,
                        'permissions' => ['Customers-index']
                    ],
                    [
                        'actions' => ['view-details'],
                        'allow'   => true,
                        'permissions' => ['Customers-view-details']
                    ],
                    [
                        'actions' => ['view'],
                        'allow'   => true,
                        'permissions' => ['Customers-view']
                    ],
                    [
                        'actions' => ['update'],
                        'allow'   => true,
                        'permissions' => ['Customers-update']
                    ],
                    [
                        'actions' => ['delete-customer'],
                        'allow'   => true,
                        'permissions' => ['Customers-delete-customer']
                    ],
                    [
                        'actions' => ['set-user-password'],
                        'allow'   => true,
                        'permissions' => ['Customers-set-user-password']
                    ],
                    [
                        'actions' => ['profile'],
                        'allow'   => true,
                        'permissions' => ['Customers-profile']
                    ],
                    [
                        'actions' => ['edit'],
                        'allow'   => true,
                        'permissions' => ['Customers-edit']
                    ],
                     [
                        'actions' => ['plan-enabled-list'],
                        'allow'   => true,
                        'permissions' => ['Customers-plan-enabled-list']
                    ],
                     [
                        'actions' => ['public-customer-list'],
                        'allow'   => true,
                        'permissions' => ['Customers-public-customer-list']
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
     * Lists all Customer models.
     * @return mixed
     */
    public function actionIndex($set = null)
    {
        // print_r(phpinfo());die();
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
         $qrcode            = Yii::$app->session->get('qrcode');
        $association            = Yii::$app->session->get('association');
        $no_association            = Yii::$app->session->get('no_association');
        $building_type            = Yii::$app->session->get('building_type');
        $from              = $from ? \Yii::$app->formatter->asDatetime($from, "php:Y-m-d 00:00:00") : '';
        $to                = $to ? \Yii::$app->formatter->asDatetime($to, "php:Y-m-d 23:59:59") : '';
      }
        $modelCustomer = new Customer;
        $searchModel   = new CustomerSearch();
        $dataProvider  = $searchModel->search(Yii::$app->request->queryParams, $keyword, $ward, $lsgi, $district, $door, $surveyor, $from, $to,$customerId,$code,$association,$no_association,$building_type,$qrcode);
        $dataProvider->pagination->pageSize = 150;
	$modelUser  = Yii::$app->user->identity;
        $associations = Yii::$app->rbac->getAssociations($modelUser->id);
        return $this->render('index', [
            'searchModel'   => $searchModel,
            'dataProvider'  => $dataProvider,
            'modelCustomer' => $modelCustomer,
            'associations' => $associations
        ]);
    }

    /**
     * Displays a single Customer model.
     * @param  integer               $id
     * @throws NotFoundHttpException if the model cannot be found
     * @return mixed
     */
    public function actionView($id)
    {
        // Yii::$app->cache->flush();
        $model        = $this->findModel($id);
        $modelImage   = new Image;
        $modelAccount = Account::find()->where(['customer_id' => $model->id])->one();
        $modelAccountFee = new Payment;
        $dataProvider = new ActiveDataProvider(
        [
           'query' => $modelAccountFee->getAllQuery()
           ->leftJoin('payment_request','payment_request.id=payment.payment_request_id')
           ->andWhere(['payment_request.account_id_customer' => $modelAccount->id,'payment_request.status' => '1']),
           'pagination' => false,
           'sort'=> ['defaultOrder' => ['id' => SORT_DESC]]
        ]);
        $modelAccountService = new AccountService;
        $serviceDataProvider = new ActiveDataProvider(
        [
           'query' => $modelAccountService->getAllQuery()
           ->andWhere(['account_service.account_id' => $modelAccount->id,'account_service.status' => '1']),
           'pagination' => false,
           'sort'=> ['defaultOrder' => ['id' => SORT_DESC]]
        ]);

        $modelAccountAuthority = new AccountAuthority;
        $authorityDataProvider = new ActiveDataProvider(
        [
           'query' => $modelAccountAuthority->getAllQuery()
           ->andWhere(['account_authority.account_id_customer' => $modelAccount->id,'account_authority.status' => '1']),
           'pagination' => false,
           'sort'=> ['defaultOrder' => ['id' => SORT_DESC]]
        ]);
        $modelAccountSlabService = new AccountSlabService;
        $slabAccountServiceDataProvider = new ActiveDataProvider(
        [
           'query' => $modelAccountSlabService->getAllQuery()->andWhere(['account_id_customer'=>$modelAccount->id]),
           'pagination' => false,
           'sort'=> ['defaultOrder' => ['id' => SORT_DESC]]
        ]);

        $params       = [
            'model'        => $model,
            'modelImage'   => $modelImage,
            'modelAccount' => $modelAccount,
            'modelAccountFee' => $modelAccountFee,
            'dataProvider' => $dataProvider,
            'modelAccountService' => $modelAccountService,
            'serviceDataProvider' => $serviceDataProvider,
            'modelAccountAuthority' => $modelAccountAuthority,
            'authorityDataProvider' => $authorityDataProvider,
            'modelAccountSlabService' => $modelAccountSlabService,
            'slabAccountServiceDataProvider' => $slabAccountServiceDataProvider,
        ];

        return $this->render('view', [
            'params' => $params
        ]);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function actionViewDetails($id)
    {
        // Yii::$app->cache->flush();
        $model        = $this->findModel($id);
        $modelImage   = new Image;
        $modelAccount = Account::find()->where(['customer_id' => $model->id])->one();
        $modelQrCode = QrCodes::find()->where(['account_id'=>$modelAccount->id])->andWhere(['status'=>1])->one();
        $params       = [
            'model'        => $model,
            'modelImage'   => $modelImage,
            'modelAccount' => $modelAccount,
            'modelQrCode'=>$modelQrCode,
        ];

        return $this->render('detail', [
            'params' => $params
        ]);
    }

    /**
     * Creates a new Customer model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Customer();

        if ($model->load(Yii::$app->request->post()) && $model->save())
        {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model
        ]);
    }

    /**
     * Updates an existing Customer model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param  integer               $id
     * @throws NotFoundHttpException if the model cannot be found
     * @return mixed
     */
    public function actionUpdate($id)
    {
        // print_r(Yii::$app->request->post());die();
        $modelImage = new Image;
        $model      = $this->findModel($id);
        $ward       = $model->ward_id;
        if ($model->load(Yii::$app->request->post()) && $modelImage->load(Yii::$app->request->post()))
        {
            $modelImage->uploaded_files = UploadedFile::getInstance($modelImage, 'uploaded_files');
            $imageId                    = $modelImage->uploadAndSave();
            if ($imageId)
            {
                $model->image_id = $imageId;
            }

            if (!$model->ward_id)
            {
                $model->ward_id = $ward;
            }
            $model->save(false);

            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model
        ]);
    }

    /**
     * Deletes an existing Customer model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param  integer               $id
     * @throws NotFoundHttpException if the model cannot be found
     * @return mixed
     */
    public function actionDeleteCustomer($id)
    {
        $modelCustomer = new Customer;
        $modelCustomer->deleteCustomer($id);
    }
     public function actionDeleteSlab($id)
    {
        $modelCustomer = new AccountSlabService;
        $modelCustomer->deleteSlab($id);
    }
    public function actionDeleteFee($id)
    {
        $modelCustomer = new AccountFee;
        $modelCustomer->deleteFee($id);
    }
      public function actionDeleteService($id)
    {
        $modelAccountService = new AccountService;
        $modelAccountService->deleteService($id);
    }
    public function actionDeleteGt($id)
    {
        $model = new AccountAuthority;
        $model->deleteGt($id);
    }

    /**
     * Finds the Customer model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param  integer               $id
     * @throws NotFoundHttpException if the model cannot be found
     * @return Customer              the loaded model
     */
    protected function findModel($id)
    {
        if (($model = Customer::findOne($id)) !== null)
        {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    public function actionGetCustomers()
    {
        $out = [];
        if (isset($_POST['depdrop_parents']))
        {
            $parents   = $_POST['depdrop_parents'];
            $ward      = Ward::find()->where(['id' => $parents[0]])->andWhere(['status' => 1])->one();
            $customers = Customer::find()
                ->select('account.id as id,customer.lead_person_name as lead_person_name')
                ->leftjoin('account', 'account.customer_id=customer.id')
                ->where(['account.status' => 1])
                ->andWhere(['customer.ward_id' => $ward['id']])
                ->andWhere(['customer.status' => 1])
                ->all();

            foreach ($customers as $id => $post)
            {
                $out[] = ['id' => $post['id'], 'name' => $post['lead_person_name']];
            }
            echo Json::encode(['output' => $out, 'selected' => '']);
        }
    }
     public function actionSetUserPassword($id=null)
    {
      $modelAccount = new Account;
      $modelAccount->setScenario('reset-user-password');
      $post = Yii::$app->request->post();
      while(true) {
       $proceed = $modelAccount->load(Yii::$app->request->post()) && $modelAccount->validate();
       $modelCustomer= Customer::find()->where(['id'=>$id])->one();
       $modelAcc = Account::find()->where(['customer_id'=>$modelCustomer->id])->one();
       if(!$proceed)
        break;
       $password = $post['Account']['password'];

       $modelAcc->password_hash = Yii::$app->security->generatePasswordHash($password);
       // $modelAcc->hashPassword();
       $modelAcc->save(false);
       // $2y$13$iT3C9.YbQOam2i4uh2.f6.IwhiCGC3MAYV8mo9rjftvXDAtkD/t5q

       break;
      }
      $modelAccount->password = null;
      $modelAccount->confirm_password = null;
      $params = [
        'modelAccount'=> $modelAccount,
        'id'=>$id,
      ];
      return $this->render('change-password', ['params'=> $params]);
    }
    public function actionAddGt($id)
    {
        $model        = $this->findModel($id);
        $modelImage   = new Image;
        $modelAccount = Account::find()->where(['customer_id' => $model->id])->one();
        $modelAccountFee = new Payment;
        // $accountAuthorityData = AccountAuthority::find()->where(['account_id_customer'=>$modelAccount->id])->andWhere(['status'=>1])->one();
        // if($accountAuthorityData){
        //   $modelAccountAuthority     = $accountAuthorityData;
        // }
        // else
        // {
           $modelAccountAuthority     = new AccountAuthority;
        // }
      
        if ($modelAccountAuthority->load(Yii::$app->request->post())&&$modelAccountAuthority->validate() ) {
          $modelAccountAuthority->account_id_customer = $modelAccount->id;
          $modelAccountAuthority->save(false);
        }
        $dataProvider = new ActiveDataProvider(
        [
           'query' => $modelAccountFee->getAllQuery()
           ->leftJoin('payment_request','payment_request.id=payment.payment_request_id')
           ->andWhere(['payment_request.account_id_customer' => $modelAccount->id,'payment_request.status' => '1']),
           'pagination' => false,
           'sort'=> ['defaultOrder' => ['id' => SORT_DESC]]
        ]);
        $modelAccountService = new AccountService;
        $serviceDataProvider = new ActiveDataProvider(
        [
           'query' => $modelAccountService->getAllQuery()
           ->andWhere(['account_service.account_id' => $modelAccount->id,'account_service.status' => '1']),
           'pagination' => false,
           'sort'=> ['defaultOrder' => ['id' => SORT_DESC]]
        ]);

        $modelAccountAuthority = new AccountAuthority;
        $authorityDataProvider = new ActiveDataProvider(
        [
           'query' => $modelAccountAuthority->getAllQuery()
           ->andWhere(['account_authority.account_id_customer' => $modelAccount->id,'account_authority.status' => '1']),
           'pagination' => false,
           'sort'=> ['defaultOrder' => ['id' => SORT_DESC]]
        ]);

        $params       = [
            'model'        => $model,
            'modelImage'   => $modelImage,
            'modelAccount' => $modelAccount,
            'modelAccountFee' => $modelAccountFee,
            'dataProvider' => $dataProvider,
            'modelAccountService' => $modelAccountService,
            'serviceDataProvider' => $serviceDataProvider,
            'modelAccountAuthority' => $modelAccountAuthority,
            'authorityDataProvider' => $authorityDataProvider,
        ];

        return $this->render('view', [
            'params' => $params
        ]);
    }
    public function actionAddServiceSlab($id)
    {
        // Yii::$app->cache->flush();
        $model        = $this->findModel($id);
        $modelImage   = new Image;
        $modelAccount = Account::find()->where(['customer_id' => $model->id])->one();
        $modelAccountFee = new Payment;
        $dataProvider = new ActiveDataProvider(
        [
           'query' => $modelAccountFee->getAllQuery()
           ->leftJoin('payment_request','payment_request.id=payment.payment_request_id')
           ->andWhere(['payment_request.account_id_customer' => $modelAccount->id,'payment_request.status' => '1']),
           'pagination' => false,
           'sort'=> ['defaultOrder' => ['id' => SORT_DESC]]
        ]);
        $modelAccountService = new AccountService;
        $serviceDataProvider = new ActiveDataProvider(
        [
           'query' => $modelAccountService->getAllQuery()
           ->andWhere(['account_service.account_id' => $modelAccount->id,'account_service.status' => '1']),
           'pagination' => false,
           'sort'=> ['defaultOrder' => ['id' => SORT_DESC]]
        ]);

        $modelAccountAuthority = new AccountAuthority;
        $authorityDataProvider = new ActiveDataProvider(
        [
           'query' => $modelAccountAuthority->getAllQuery()
           ->andWhere(['account_authority.account_id_customer' => $modelAccount->id,'account_authority.status' => '1']),
           'pagination' => false,
           'sort'=> ['defaultOrder' => ['id' => SORT_DESC]]
        ]);
        $modelAccountSlabService = new AccountSlabService;
        $slabAccountServiceDataProvider = new ActiveDataProvider(
        [
           'query' => $modelAccountSlabService->getAllQuery(),
           'pagination' => false,
           'sort'=> ['defaultOrder' => ['id' => SORT_DESC]]
        ]);
         if ($modelAccountSlabService->load(Yii::$app->request->post())) {
            $modelAccountSlabService->save();
        }
        $params       = [
            'model'        => $model,
            'modelImage'   => $modelImage,
            'modelAccount' => $modelAccount,
            'modelAccountFee' => $modelAccountFee,
            'dataProvider' => $dataProvider,
            'modelAccountService' => $modelAccountService,
            'serviceDataProvider' => $serviceDataProvider,
            'modelAccountAuthority' => $modelAccountAuthority,
            'authorityDataProvider' => $authorityDataProvider,
            'modelAccountSlabService' => $modelAccountSlabService,
            'slabAccountServiceDataProvider' => $slabAccountServiceDataProvider,
        ];

        return $this->render('view', [
            'params' => $params
        ]);
    }
     public function actionProfile()
    {
        $modelUser = Yii::$app->user->identity->id;
        $modelAcc = Account::find()->where(['id' => $modelUser])->one();
        $model        = $this->findModel($modelAcc->customer_id);
        $modelImage   = new Image;
        $modelAccount = Account::find()->where(['customer_id' => $model->id])->one();
        $modelQrCode = QrCodes::find()->where(['account_id'=>$modelAccount->id])->andWhere(['status'=>1])->one();
        $params       = [
            'model'        => $model,
            'modelImage'   => $modelImage,
            'modelAccount' => $modelAccount,
            'modelQrCode'=>$modelQrCode,
        ];

        return $this->render('profile', [
            'params' => $params
        ]);
    }
    public function actionEdit($id)
    {
        // print_r(Yii::$app->request->post());die();
        $modelImage = new Image;
        $model      = $this->findModel($id);
        $ward       = $model->ward_id;
         $modelAccount = Account::find()->where(['customer_id' => $model->id])->one();
         $modelAccountFee = new Payment;
        $dataProvider = new ActiveDataProvider(
        [
           'query' => $modelAccountFee->getAllQuery()
           ->leftJoin('payment_request','payment_request.id=payment.payment_request_id')
           ->andWhere(['payment_request.account_id_customer' => $modelAccount->id,'payment_request.status' => '1']),
           'pagination' => false,
           'sort'=> ['defaultOrder' => ['id' => SORT_DESC]]
        ]);
        if ($model->load(Yii::$app->request->post()))
        {

            $model->save(false);

            return $this->redirect(['profile']);
        }

        return $this->render('edit', [
            'model' => $model,
            'modelAccountFee' => $modelAccountFee,
            'dataProvider' => $dataProvider,
        ]);
    }
    public function actionList($set = null)
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
            'to'
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
        $from              = $from ? \Yii::$app->formatter->asDatetime($from, "php:Y-m-d 00:00:00") : '';
        $to                = $to ? \Yii::$app->formatter->asDatetime($to, "php:Y-m-d 23:59:59") : '';
      }
        $modelCustomer = new Customer;
        $searchModel   = new CustomerSearchTest();
        $dataProvider  = $searchModel->search(Yii::$app->request->queryParams, $keyword, $ward, $lsgi, $district, $door, $surveyor, $from, $to,$customerId,$code,$association,$no_association,$building_type);
        $modelUser  = Yii::$app->user->identity;
        $associations = Yii::$app->rbac->getAssociations($modelUser->id);
        return $this->render('list', [
            'searchModel'   => $searchModel,
            'dataProvider'  => $dataProvider,
            'modelCustomer' => $modelCustomer,
            'associations' => $associations
        ]);
    }
       public function actionPublicCustomerList($set = null)
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
            'to'
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
        $from              = $from ? \Yii::$app->formatter->asDatetime($from, "php:Y-m-d H:i:s") : '';
        $to                = $to ? \Yii::$app->formatter->asDatetime($to, "php:Y-m-d 23:59:59") : '';
      }
        $modelCustomer = new Customer;
        $searchModel   = new CustomerSearch();
        $dataProvider  = $searchModel->searchPublicCustomers(Yii::$app->request->queryParams, $keyword, $ward, $lsgi, $district, $door, $surveyor, $from, $to,$customerId,$code,$association,$no_association,$building_type);
        $modelUser  = Yii::$app->user->identity;
        $associations = Yii::$app->rbac->getAssociations($modelUser->id);
        return $this->render('public-customer-list', [
            'searchModel'   => $searchModel,
            'dataProvider'  => $dataProvider,
            'modelCustomer' => $modelCustomer,
            'associations' => $associations
        ]);
    }
    public function actionPlanEnabledList($set = null)
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
        $modelAccountService = new AccountService;
        $collectionQuery  = $modelAccountService->getAllQuerySubscription($keyword, $ward, $lsgi, $supervisor, $gt, $association, $from, $to,$service);
        $dataProvider = new ActiveDataProvider([
        'query' => $collectionQuery,
      ]);
        $modelUser  = Yii::$app->user->identity;
        $associations = Yii::$app->rbac->getAssociations($modelUser->id);
        return $this->render('plan-enabled-list', [
            'dataProvider'  => $dataProvider,
            'modelAccountService' => $modelAccountService,
            'associations'  => $associations
        ]);
    }
   public function actionSetQrCode($id=null)
    {
        $modelCustomer= Customer::find()->where(['id'=>$id])->one();
      $post = Yii::$app->request->post();
      $modelAccount = Account::find()->where(['customer_id'=>$id])->one();
      while(true) {
       $proceed = $modelCustomer->load(Yii::$app->request->post()) && $modelCustomer->validate();
       if(!$proceed)
        break;
       $code = $post['Customer']['qr_code_value'];
       $modelQrCode = QrCodes::find()->where(['value'=>$post['Customer']['qr_code_value']])->one();
       if($modelQrCode)
       {
        $modelCustomer->qr_code = $modelQrCode->id;
        $modelQrCode->customer_id = $modelCustomer->id;
        $modelQrCode->account_id = $modelAccount->id;
        $modelQrCode->save(false);
       }
       
       $modelCustomer->save(false);

       break;
      }
      $modelCustomer->qr_code_value = null;
      $params = [
        'modelCustomer'=> $modelCustomer,
        'id'=>$id,
      ];
      return $this->render('set-qr-code', ['params'=> $params]);
    }
    public function actionChangeUsername()
    {
      $modelCustomerNew = NewCust::find()->where(['status'=>0])
      // ->andWhere(['>=','id',1])
      // ->andWhere(['<=','id',500])
      ->limit(800)
      ->all();
      foreach ($modelCustomerNew as $key => $value) {
       $modelAccount = Account::find()->where(['status'=>1])->andWhere(['customer_id'=>$value->customer_id])->one();
       if($modelAccount)
      {
        $username = str_replace(' ', '', $modelAccount->username);
        $modelAccount->username = $username;
        $modelAccount->password_hash = Yii::$app->security->generatePasswordHash($username);
        $modelAccount->save(false);
        $value->status = 1;
        $value->username  = $username;
        $value->save(false);
      }

      }
    }
}
