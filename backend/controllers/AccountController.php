<?php

namespace backend\controllers;

use Yii;
use backend\models\Account;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;
use backend\models\Person;
use backend\models\Ward;
use backend\models\GreenActionUnit;
use backend\models\AccountWard;
use backend\models\AuthAssignment;
use backend\models\AccessToken;
use backend\models\LoginHistory;

use yii\helpers\Json;
use common\models\LoginForm;
use yii\helpers\Url;
use backend\components\AccessPermission;
use yii\filters\AccessControl;
use backend\components\AccessRule;
/**
 * DistrictsController implements the CRUD actions for District model.
 */
class AccountController extends Controller
{
    /**
     * @inheritdoc
     */
    public $ROLE_GT = 'green-technician';
    // public function behaviors()
    // {
    //     return [
    //        'access' => [
    //            'class' => AccessControl::className(),
    //            'only' => ['index','admin-lsgi','admin-hks','supervisors','green-technicians','create','view','create-admin-lsgi','update-admin-lsgi','create-admin-hks','update-admin-hks','create-supervisor','update-supervisor','create-green-technician','view-green-technician'],
    //            'rules' => [
    //                 [
    //                    'actions' => ['index','admin-lsgi','admin-hks','supervisors','green-technicians','create','view','create-admin-lsgi','update-admin-lsgi','create-admin-hks','update-admin-hks','create-supervisor','update-supervisor','create-green-technician','view-green-technician'],
    //                    'allow' => true,
    //                    'roles' => ['@'],
    //                ],
    //            ],
    //            'denyCallback' => function($rule, $action) {
    //                return $this->goHome();
    //            }
    //        ],
    //    ];
    // }
     // public function behaviors()
     //  {
     //    return [
     //       'access' => [
     //           'class' => AccessControl::className(),
     //           'only' => ['index','admin-lsgi','admin-hks','supervisors','green-technicians','create','view','create-admin-lsgi','update-admin-lsgi','create-admin-hks','update-admin-hks','create-supervisor','update-supervisor','create-green-technician','view-green-technician','super-admin','create-super-admin','update-super-admin','coordinators','create-coordinator','update-coordinator'],
     //           // 'ruleConfig' => [
     //           //         'class' => AccessRule::className(),
     //           //     ],
     //           'rules' => [
     //               // [
     //               //     'actions' => ['users'],
     //               //     'allow' => true,
     //               //
     //               // ],
     //            [
     //                'allow' => true,
     //                'roles' => ['@'],
     //                // 'actions' => ['index','admin-lsgi','admin-hks','supervisors','green-technicians','create','view','create-admin-lsgi','update-admin-lsgi','create-admin-hks','update-admin-hks','create-supervisor','update-supervisor','create-green-technician','view-green-technician','super-admin','create-super-admin','update-super-admin','coordinators','create-coordinator','update-coordinator'],
     //                'permissions' => ['Account-index','Account-admin-lsgi','Account-admin-hks','Account-supervisors','Account-green-technicians','Account-create','Account-view','Account-create-admin-lsgi','Account-update-admin-lsgi','Account-create-admin-hks','Account-update-admin-hks','Account-create-supervisor','Account-update-supervisor','Account-create-green-technician','Account-view-green-technician','Account-super-admin','Account-create-super-admin','Account-update-super-admin','Account-coordinators','Account-create-coordinator','Account-update-coordinator'],
     //                // 'roles' => [
     //                //           'super-admin'
     //                //            ],
     //            ],
     //
     //           ],
     //           'denyCallback' => function($rule, $action) {
     //               return $this->goHome();
     //           }
     //       ],
     //   ];
     //  }
     public function behaviors()
     {
         return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index','admin-lsgi','admin-hks','supervisors','green-technicians','create','view','create-admin-lsgi','update-admin-lsgi','create-admin-hks','update-admin-hks','create-supervisor','update-supervisor','create-green-technician','view-green-technician','super-admin','create-super-admin','update-super-admin','coordinators','create-coordinator','update-coordinator'],
                'ruleConfig' => [
                        'class' => AccessPermission::className(),
                    ],
                'rules' => [
                    [
                        'actions' => ['index'],
                        'allow' => true,
                        'permissions' => ['Account-index'],
                    ],
                    [
                        'actions' => ['admin-lsgi'],
                        'allow' => true,
                        'permissions' => ['Account-admin-lsgi'],
                    ],
                    [
                        'actions' => ['view'],
                        'allow' => true,
                        'permissions' => ['Account-view'],
                    ],
                    [
                        'actions' => ['create'],
                        'allow' => true,
                        'permissions' => ['Account-create'],
                    ],
                    [
                        'actions' => ['create-admin-lsgi'],
                        'allow' => true,
                        'permissions' => ['Account-create-admin-lsgi'],
                    ],
                    [
                        'actions' => ['update-admin-lsgi'],
                        'allow' => true,
                        'permissions' => ['Account-update-admin-lsgi'],
                    ],
                    [
                        'actions' => ['update'],
                        'allow' => true,
                        'permissions' => ['Account-update'],
                    ],
                    [
                        'actions' => ['delete'],
                        'allow' => true,
                        'permissions' => ['Account-delete'],
                    ],
                    [
                        'actions' => ['delete-account'],
                        'allow' => true,
                        'permissions' => ['Account-delete-account'],
                    ],
                    [
                        'actions' => ['admin-hks'],
                        'allow' => true,
                        'permissions' => ['Account-admin-hks'],
                    ],
                    [
                        'actions' => ['create-admin-hks'],
                        'allow' => true,
                        'permissions' => ['Account-create-admin-hks'],
                    ],
                    [
                        'actions' => ['update-admin-hks'],
                        'allow' => true,
                        'permissions' => ['Account-update-admin-hks'],
                    ],
                    [
                        'actions' => ['supervisors'],
                        'allow' => true,
                        'permissions' => ['Account-supervisors'],
                    ],
                    [
                        'actions' => ['create-supervisor'],
                        'allow' => true,
                        'permissions' => ['Account-create-supervisor'],
                    ],
                    [
                        'actions' => ['update-supervisor'],
                        'allow' => true,
                        'permissions' => ['Account-update-supervisor'],
                    ],
                    [
                        'actions' => ['green-technicians'],
                        'allow' => true,
                        'permissions' => ['Account-green-technicians'],
                    ],
                    [
                        'actions' => ['create-green-technician'],
                        'allow' => true,
                        'permissions' => ['Account-create-green-technician'],
                    ],
                    [
                        'actions' => ['update-green-technician'],
                        'allow' => true,
                        'permissions' => ['Account-update-green-technician'],
                    ],
                    [
                        'actions' => ['delete-ward'],
                        'allow' => true,
                        'permissions' => ['Account-delete-ward'],
                    ],
                    [
                        'actions' => ['add-account-ward'],
                        'allow' => true,
                        'permissions' => ['Account-add-account-ward'],
                    ],
                    [
                        'actions' => ['view-green-technician'],
                        'allow' => true,
                        'permissions' => ['Account-view-green-technician'],
                    ],
                    [
                        'actions' => ['login'],
                        'allow' => true,
                        'permissions' => ['Account-login'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'permissions' => ['Account-logout'],
                    ],
                    [
                        'actions' => ['super-admin'],
                        'allow' => true,
                        'permissions' => ['Account-super-admin'],
                    ],
                    [
                        'actions' => ['create-super-admin'],
                        'allow' => true,
                        'permissions' => ['Account-create-super-admin'],
                    ],
                    [
                        'actions' => ['update-super-admin'],
                        'allow' => true,
                        'permissions' => ['Account-update-super-admin'],
                    ],
                    [
                        'actions' => ['coordinators'],
                        'allow' => true,
                        'permissions' => ['Account-coordinators'],
                    ],
                    [
                        'actions' => ['create-coordinator'],
                        'allow' => true,
                        'permissions' => ['Account-create-coordinator'],
                    ],
                    [
                        'actions' => ['update-coordinator'],
                        'allow' => true,
                        'permissions' => ['Account-update-coordinator'],
                    ],
                    [
                        'actions' => ['view-supervisor'],
                        'allow' => true,
                        'permissions' => ['Account-view-supervisor'],
                    ],
                ],
                'denyCallback' => function($rule, $action) {
                    return $this->goHome();
                }
            ],
        ];
     }

    /**
     * Lists all District models.
     * @return mixed
     */
    public function actionIndex()
    {
       $modelAccount = new Account;
        $keyword      = null;
        $lsgi         = null;
        $unit         = null;
        $page         = null;
        $lsgi         = null;
        $supervisor         = null;
        $post         = Yii::$app->request->post();
        if (isset($post['lsgi']))
        {
            $lsgi = $post['lsgi'];
        }
        if (isset($post['unit']))
        {
            $unit = $post['unit'];
        }
        if (isset($post['supervisor']))
        {
            $supervisor = $post['supervisor'];
        }
        if (isset($_POST['name']))
        {
            $keyword = $_POST['name'];
        }

        $dataProvider = new ActiveDataProvider(
            [
                'query'      => Account::getAllQuery($lsgi, $unit, $keyword,$supervisor)->andWhere(['role' => 'surveyor']),
                'pagination' => false,
                'sort'       => ['defaultOrder' => ['id' => SORT_DESC]]
            ]);
        $modelUser  = Yii::$app->user->identity;
        $associations = Yii::$app->rbac->getAssociations($modelUser->id);
        return $this->render('index',['dataProvider' => $dataProvider,'modelAccount'=>$modelAccount,'associations'=>$associations]);
    }
    public function actionAdminLsgi()
    {
      $modelAccount = new Account;
        $keyword      = null;
        $lsgi         = null;
        $unit         = null;
        $page         = null;
        $lsgi         = null;
        $supervisor         = null;
        $post         = Yii::$app->request->post();
        if (isset($post['lsgi']))
        {
            $lsgi = $post['lsgi'];
        }
        if (isset($post['unit']))
        {
            $unit = $post['unit'];
        }
        if (isset($post['supervisor']))
        {
            $supervisor = $post['supervisor'];
        }
        if (isset($_POST['name']))
        {
            $keyword = $_POST['name'];
        }

        $dataProvider = new ActiveDataProvider(
            [
                'query'      => Account::getAllQuery($lsgi, $unit, $keyword,$supervisor)->andWhere(['role' => 'admin-lsgi']),
                'pagination' => false,
                'sort'       => ['defaultOrder' => ['id' => SORT_DESC]]
            ]);
        $modelUser  = Yii::$app->user->identity;
        $associations = Yii::$app->rbac->getAssociations($modelUser->id);
        return $this->render('admin-lsgi/index', ['dataProvider' => $dataProvider,'modelAccount'=>$modelAccount,'associations' => $associations]);
    }


    public function actionView($id)
    {
        $modelAccount = $this->findModel($id);
        $personId = $modelAccount->person_id;
        $modelPerson = $this->findModelPerson($personId);
        $modelAccountWard = new AccountWard;
        $dataProvider = new ActiveDataProvider(
        [
           'query' => AccountWard::getAllQuery()->andWhere(['account_id' => $id,'status' => '1']),
           'pagination' => false,
           'sort'=> ['defaultOrder' => ['id' => SORT_DESC]]
        ]);
        $params = [
            'modelAccount'  =>$modelAccount,
            'modelAccountWard'  =>$modelAccountWard,
            'modelPerson'  =>$modelPerson,
            'dataProvider'  =>$dataProvider,
        ];

        return $this->render('view', [
            'params' => $params,
        ]);
    }

    public function actionCreate()
    {
        $auth = Yii::$app->authManager;
        $modelAccount = new Account();
        $modelPerson = new Person;
        $params = Yii::$app->request->post();
        $paramsOk =  $params && $modelPerson->load($params) && $modelAccount->load($params);
        $modelAccount->setScenario('add');
        if($paramsOk){
          $personOk = $modelPerson->validate();
          $accountOk = $modelAccount->validate();
          if($personOk && $accountOk){
            $modelAccount->hashPassword();
            $modelPerson->save(false);
            $modelAccount->person_id = $modelPerson->id;
            $modelAccount->role = "surveyor";
             $modelAccount->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
            if($modelAccount->save(false))
            {
                Yii::$app->rbac->assignAuthRole('surveyor',$modelAccount->id);
                // $modelAuthAssignment= new AuthAssignment;
                // $modelAuthAssignment->item_name = 'surveyor';
                // $modelAuthAssignment->user_id = $modelAccount->id;
                // $modelAuthAssignment->save(false);
            }
            return $this->redirect(['index']);
          }
        }
        return $this->render('create', [
            'modelPerson' => $modelPerson,
            'modelAccount' => $modelAccount,
        ]);
    }
    public function actionCreateAdminLsgi()
    {
        $modelAccount = new Account();
        $modelPerson = new Person;
        $params = Yii::$app->request->post();
        $paramsOk =  $params && $modelPerson->load($params) && $modelAccount->load($params);
        $modelAccount->setScenario('create-admin-lsgi');
        $modelAccount->setScenario('add');
        if($paramsOk){
          $personOk = $modelPerson->validate();
          $accountOk = $modelAccount->validate();
          if($personOk && $accountOk){
            $modelAccount->hashPassword();
            $modelPerson->save(false);
            $modelAccount->person_id = $modelPerson->id;
            $modelAccount->role = "admin-lsgi";
            $modelAccount->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
            if($modelAccount->save(false))
            {
                 Yii::$app->rbac->assignAuthRole('admin-lsgi',$modelAccount->id);
                // $modelAuthAssignment= new AuthAssignment;
                // $modelAuthAssignment->item_name = 'admin-lsgi';
                // $modelAuthAssignment->user_id = $modelAccount->id;
                // $modelAuthAssignment->save(false);
            }
            return $this->redirect(['admin-lsgi']);
          }
        }
        return $this->render('admin-lsgi/create', [
            'modelPerson' => $modelPerson,
            'modelAccount' => $modelAccount,
        ]);
    }
    public function actionUpdateAdminLsgi($id)
    {
        $modelAccount = $this->findModel($id);
        $personId = $modelAccount->person_id;
        $modelPerson = $this->findModelPerson($personId);
        $lsgi = $modelAccount->lsgi_id;
        $password = $modelAccount->password_hash;
        // $modelAccount->setScenario('update');
        $params = Yii::$app->request->post();
        $paramsOk =  $params && $modelPerson->load($params) && $modelAccount->load($params);
        if($paramsOk){
          $personOk = $modelPerson->validate();
          $accountOk = $modelAccount->validate();
          if($personOk && $accountOk){
            $modelAccount->hashPassword();

            $modelPerson->save(false);
            $modelAccount->person_id = $modelPerson->id;
            $modelAccount->role = "admin-lsgi";
            if(!$modelAccount->lsgi_id)
            {
                $modelAccount->lsgi_id = $lsgi;
            }
            $modelAccount->save(false);
            return $this->redirect(['admin-lsgi']);
          }
        }

        return $this->render('admin-lsgi/update', [
          'modelPerson' => $modelPerson,
          'modelAccount' => $modelAccount,
        ]);
    }

    public function actionUpdate($id)
    {
        $modelAccount = $this->findModel($id);
        $personId = $modelAccount->person_id;
        $modelPerson = $this->findModelPerson($personId);
        $password = $modelAccount->password_hash;
        $lsgi = $modelAccount->lsgi_id;
        $unit = $modelAccount->green_action_unit_id;
        $supervisor = $modelAccount->supervisor_id;
        $modelAccount->setScenario('update');
        $params = Yii::$app->request->post();
        $paramsOk =  $params && $modelPerson->load($params) && $modelAccount->load($params);
        if($paramsOk){
          // print_r($modelAccount->password_hash);exit;
          $personOk = $modelPerson->validate();
          $accountOk = $modelAccount->validate();
          // print_r($modelAccount->errors);exit;
          if($personOk && $accountOk){
            $modelAccount->hashPassword();

            $modelPerson->save(false);
            $modelAccount->person_id = $modelPerson->id;
            $modelAccount->role = "surveyor";
            if(!$modelAccount->lsgi_id)
            {
                $modelAccount->lsgi_id = $lsgi;
            }
            if(!$modelAccount->green_action_unit_id)
            {
                $modelAccount->green_action_unit_id = $unit;
            }
            if(!$modelAccount->supervisor_id)
            {
                $modelAccount->supervisor_id = $supervisor;
            }
            $modelAccount->save(false);
            return $this->redirect(['index']);
          }
        }

        return $this->render('update', [
          'modelPerson' => $modelPerson,
          'modelAccount' => $modelAccount,
        ]);
    }


    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    protected function findModelPerson($id)
    {
        if (($model = Person::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
    protected function findModel($id)
    {
        if (($model = Account::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
    public function actionDeleteAccount($id)
    {
        $model = new Account;
        $modelAccesToken = AccessToken::find()->where(['fk_access_token_user'=>$id])->all();
        if($modelAccesToken){
        foreach ($modelAccesToken as $token) {
            $token->delete();
        }
    }
        $model->deleteAccount($id);
    }
    public function actionAdminHks()
    {
       $modelAccount = new Account;
        $keyword      = null;
        $lsgi         = null;
        $unit         = null;
        $page         = null;
        $lsgi         = null;
        $supervisor         = null;
        $post         = Yii::$app->request->post();
        if (isset($post['lsgi']))
        {
            $lsgi = $post['lsgi'];
        }
        if (isset($post['unit']))
        {
            $unit = $post['unit'];
        }
        if (isset($post['supervisor']))
        {
            $supervisor = $post['supervisor'];
        }
        if (isset($_POST['name']))
        {
            $keyword = $_POST['name'];
        }
        $dataProvider = new ActiveDataProvider(
            [
                'query'      => Account::getAllQuery($lsgi, $unit, $keyword,$supervisor)->andWhere(['role' => 'admin-hks']),
                'pagination' => false,
                'sort'       => ['defaultOrder' => ['id' => SORT_DESC]]
            ]);
        $modelUser  = Yii::$app->user->identity;
         $associations = Yii::$app->rbac->getAssociations($modelUser->id);

        return $this->render('admin-hks/index', ['dataProvider' => $dataProvider,'modelAccount'=>$modelAccount,'associations' => $associations]);
    }
     public function actionCreateAdminHks()
    {
        $modelAccount = new Account();
        $modelPerson = new Person;
        $params = Yii::$app->request->post();
        $paramsOk =  $params && $modelPerson->load($params) && $modelAccount->load($params);
        $modelAccount->setScenario('create-admin-hks');
        $modelAccount->setScenario('add');
        if($paramsOk){
          $personOk = $modelPerson->validate();
          $accountOk = $modelAccount->validate();
          if($personOk && $accountOk){
            $modelAccount->hashPassword();
            $modelPerson->save(false);
            $modelAccount->person_id = $modelPerson->id;
            $modelAccount->role = "admin-hks";
            $modelAccount->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
            if($modelAccount->save(false))
            {
                Yii::$app->rbac->assignAuthRole('admin-hks',$modelAccount->id);
                // $modelAuthAssignment= new AuthAssignment;
                // $modelAuthAssignment->item_name = 'admin-hks';
                // $modelAuthAssignment->user_id = $modelAccount->id;
                // $modelAuthAssignment->save(false);
            }
            return $this->redirect(['admin-hks']);
          }
        }
        return $this->render('admin-hks/create', [
            'modelPerson' => $modelPerson,
            'modelAccount' => $modelAccount,
        ]);
    }
    public function actionUpdateAdminHks($id)
    {
        $modelAccount = $this->findModel($id);
        $personId = $modelAccount->person_id;
        $modelPerson = $this->findModelPerson($personId);
        $lsgi = $modelAccount->lsgi_id;
        $unit = $modelAccount->green_action_unit_id;
        $password = $modelAccount->password_hash;
        // $modelAccount->setScenario('update');
        $params = Yii::$app->request->post();
        $paramsOk =  $params && $modelPerson->load($params) && $modelAccount->load($params);
        if($paramsOk){
          $personOk = $modelPerson->validate();
          $accountOk = $modelAccount->validate();
          if($personOk && $accountOk){
            $modelAccount->hashPassword();

            $modelPerson->save(false);
            $modelAccount->person_id = $modelPerson->id;
            $modelAccount->role = "admin-hks";
            if(!$modelAccount->lsgi_id)
            {
                $modelAccount->lsgi_id = $lsgi;
            }
            if(!$modelAccount->green_action_unit_id)
            {
                $modelAccount->green_action_unit_id = $unit;
            }
            $modelAccount->save(false);
            return $this->redirect(['admin-hks']);
          }
        }

        return $this->render('admin-hks/update', [
          'modelPerson' => $modelPerson,
          'modelAccount' => $modelAccount,
        ]);
    }
    public function actionSupervisors()
    {
         $modelAccount = new Account;
        $keyword      = null;
        $lsgi         = null;
        $unit         = null;
        $page         = null;
        $lsgi         = null;
        $supervisor         = null;
        $post         = Yii::$app->request->post();
        if (isset($post['lsgi']))
        {
            $lsgi = $post['lsgi'];
        }
        if (isset($post['unit']))
        {
            $unit = $post['unit'];
        }
        if (isset($post['supervisor']))
        {
            $supervisor = $post['supervisor'];
        }
        if (isset($_POST['name']))
        {
            $keyword = $_POST['name'];
        }
        $dataProvider = new ActiveDataProvider(
            [
                'query'      => Account::getAllQuery($lsgi, $unit, $keyword,$supervisor)->andWhere(['role' => 'supervisor']),
                'pagination' => false,
                'sort'       => ['defaultOrder' => ['id' => SORT_DESC]]
            ]);
         $modelUser  = Yii::$app->user->identity;
        $associations = Yii::$app->rbac->getAssociations($modelUser->id);
        return $this->render('supervisor/index', ['dataProvider' => $dataProvider,'modelAccount'=>$modelAccount,'associations'=>$associations]);
    }
     public function actionCreateSupervisor()
    {
        $modelAccount = new Account();
        $modelPerson = new Person;
        $params = Yii::$app->request->post();
        $paramsOk =  $params && $modelPerson->load($params) && $modelAccount->load($params);
        $modelAccount->setScenario('supervisor');
        $modelAccount->setScenario('add');
        if($paramsOk){
          $personOk = $modelPerson->validate();
          $accountOk = $modelAccount->validate();
          if($personOk && $accountOk){
            $modelAccount->hashPassword();
            $modelPerson->save(false);
            $modelAccount->person_id = $modelPerson->id;
            $modelAccount->role = "supervisor";
            $modelAccount->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
            if($modelAccount->save(false))
            {
                Yii::$app->rbac->assignAuthRole('supervisor',$modelAccount->id);
                // $modelAuthAssignment= new AuthAssignment;
                // $modelAuthAssignment->item_name = 'supervisor';
                // $modelAuthAssignment->user_id = $modelAccount->id;
                // $modelAuthAssignment->save(false);
            }
            return $this->redirect(['supervisors']);
          }
        }
        return $this->render('supervisor/create', [
            'modelPerson' => $modelPerson,
            'modelAccount' => $modelAccount,
        ]);
    }
    public function actionUpdateSupervisor($id)
    {
        $modelAccount = $this->findModel($id);
        $personId = $modelAccount->person_id;
        $modelPerson = $this->findModelPerson($personId);
        $lsgi = $modelAccount->lsgi_id;
        $unit = $modelAccount->green_action_unit_id;
        $password = $modelAccount->password_hash;
        // $modelAccount->setScenario('update');
        $params = Yii::$app->request->post();
        $paramsOk =  $params && $modelPerson->load($params) && $modelAccount->load($params);
        if($paramsOk){
          $personOk = $modelPerson->validate();
          $accountOk = $modelAccount->validate();
          if($personOk && $accountOk){
            $modelAccount->hashPassword();

            $modelPerson->save(false);
            $modelAccount->person_id = $modelPerson->id;
            $modelAccount->role = "supervisor";
            if(!$modelAccount->lsgi_id)
            {
                $modelAccount->lsgi_id = $lsgi;
            }
            if(!$modelAccount->green_action_unit_id)
            {
                $modelAccount->green_action_unit_id = $unit;
            }
            $modelAccount->save(false);
            return $this->redirect(['supervisors']);
          }
        }

        return $this->render('supervisor/update', [
          'modelPerson' => $modelPerson,
          'modelAccount' => $modelAccount,
        ]);
    }
    public function actionSupervisor() {
       $out = [];
        if (isset($_POST['depdrop_parents'])) {
        $parents = $_POST['depdrop_parents'];
        $unit = GreenActionUnit::find()->where(['id'=>$parents[0]])->andWhere(['status'=>1])->one();
        $supervisor= Person::find()
            ->select('account.id as id,person.first_name as first_name')
            ->where(['account.status'=> 1])
            ->leftjoin('account','account.person_id=person.id')
            ->andWhere(['account.green_action_unit_id'=>$unit->id])
            ->andWhere(['account.role'=>'supervisor'])
            ->all();
        foreach ($supervisor as $id => $post) {
            $out[] = ['id' => $post['id'], 'name' => $post['first_name']];
 }
 echo Json::encode(['output' => $out, 'selected' => '']);
    }
  }
  public function actionGt() {
       $out = [];
        if (isset($_POST['depdrop_parents'])) {
        $parents = $_POST['depdrop_parents'];
        $ward = Ward::find()->where(['id'=>$parents[0]])->andWhere(['status'=>1])->one();
        $supervisor= Person::find()
            ->select('account.id as id,person.first_name as first_name')
            ->where(['account.status'=> 1])
            ->leftjoin('account','account.person_id=person.id')
            ->leftjoin('green_action_unit','account.green_action_unit_id=green_action_unit.id')
            ->leftjoin('green_action_unit_ward','green_action_unit_ward.green_action_unit_id=green_action_unit.id')
            // ->leftjoin('account','account.green_action_unit_id=green_action_unit.id')
            ->andWhere(['green_action_unit_ward.ward_id'=>$ward->id])
            ->andWhere(['account.role'=>'green-technician'])
            ->all();
        foreach ($supervisor as $id => $post) {
            $out[] = ['id' => $post['id'], 'name' => $post['first_name']];
 }
 echo Json::encode(['output' => $out, 'selected' => '']);
    }
  }
  public function actionGetGt() {
       $out = [];
        if (isset($_POST['depdrop_parents'])) {
        $parents = $_POST['depdrop_parents'];
        $hks = GreenActionUnit::find()->where(['id'=>$parents[0]])->andWhere(['status'=>1])->one();
        $supervisor= Person::find()
            ->select('account.id as id,person.first_name as first_name')
            ->where(['account.status'=> 1])
            ->leftjoin('account','account.person_id=person.id')
            ->leftjoin('green_action_unit','account.green_action_unit_id=green_action_unit.id')
            ->andWhere(['green_action_unit.id'=>$hks->id])
            ->andWhere(['account.role'=>'green-technician'])
            ->all();
        foreach ($supervisor as $id => $post) {
            $out[] = ['id' => $post['id'], 'name' => $post['first_name']];
 }
 echo Json::encode(['output' => $out, 'selected' => '']);
    }
  }
  public function actionGreenTechnicians()
    {
       $dataProvider = new ActiveDataProvider(
        [
           'query' => Account::getAllQuery()->andWhere(['role' => $this->ROLE_GT,'status' => '1']),
           'pagination' => false,
           'sort'=> ['defaultOrder' => ['id' => SORT_DESC]]
        ]);
        return $this->render('green-technician/index',['dataProvider' => $dataProvider]);
    }
     public function actionCreateGreenTechnician()
    {
        $modelAccount = new Account();
        $modelPerson = new Person;
        $params = Yii::$app->request->post();
        $paramsOk =  $params && $modelPerson->load($params) && $modelAccount->load($params);
        $modelAccount->setScenario('gt');
        $modelAccount->setScenario('add');
        if($paramsOk){
          $personOk = $modelPerson->validate();
          $accountOk = $modelAccount->validate();
          if($personOk && $accountOk){
            $modelAccount->hashPassword();
            $modelPerson->save(false);
            $modelAccount->person_id = $modelPerson->id;
            $modelAccount->role = $this->ROLE_GT;
            $modelAccount->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
            if($modelAccount->save(false))
            {
                Yii::$app->rbac->assignAuthRole('green-technician',$modelAccount->id);
                // $modelAuthAssignment= new AuthAssignment;
                // $modelAuthAssignment->item_name = 'green-technician';
                // $modelAuthAssignment->user_id = $modelAccount->id;
                // $modelAuthAssignment->save(false);
            }
            return $this->redirect(['green-technicians']);
          }
        }
        return $this->render('green-technician/create', [
            'modelPerson' => $modelPerson,
            'modelAccount' => $modelAccount,
        ]);
    }
    public function actionUpdateGreenTechnician($id)
    {
        $modelAccount = $this->findModel($id);
        $personId = $modelAccount->person_id;
        $modelPerson = $this->findModelPerson($personId);
        $lsgi = $modelAccount->lsgi_id;
        $unit = $modelAccount->green_action_unit_id;
        $supervisor = $modelAccount->supervisor_id;
        $password = $modelAccount->password_hash;
        // $modelAccount->setScenario('update');
        $params = Yii::$app->request->post();
        $paramsOk =  $params && $modelPerson->load($params) && $modelAccount->load($params);
        if($paramsOk){
          $personOk = $modelPerson->validate();
          $accountOk = $modelAccount->validate();
          if($personOk && $accountOk){
            $modelAccount->hashPassword();

            $modelPerson->save(false);
            $modelAccount->person_id = $modelPerson->id;
            $modelAccount->role = "green-technician";
            if(!$modelAccount->lsgi_id)
            {
                $modelAccount->lsgi_id = $lsgi;
            }
            if(!$modelAccount->green_action_unit_id)
            {
                $modelAccount->green_action_unit_id = $unit;
            }
            if(!$modelAccount->supervisor_id)
            {
                $modelAccount->supervisor_id = $supervisor;
            }
            $modelAccount->save(false);
            return $this->redirect(['green-technicians']);
          }
        }

        return $this->render('green-technician/update', [
          'modelPerson' => $modelPerson,
          'modelAccount' => $modelAccount,
        ]);
    }
    public function actionDeleteWard($id)
    {
        $model = new AccountWard;
        $model->deleteWard($id);
    }
    public function actionAddAccountWard($id)
    {
        $modelAccount = $this->findModel($id);
        $personId = $modelAccount->person_id;
        $modelPerson = $this->findModelPerson($personId);
      $modelAccountWard = new AccountWard;
     $dataProvider = new ActiveDataProvider(
        [
           'query' => AccountWard::getAllQuery()->andWhere(['account_id' => $id,'status' => '1']),
           'pagination' => false,
           'sort'=> ['defaultOrder' => ['id' => SORT_DESC]]
        ]);


        if ($modelAccountWard->load(Yii::$app->request->post())) {
            $modelAccountWard->account_id = $id;
            $modelAccountWard->save();
        }
$modelAccountWard = new AccountWard;
         $params = [
            'modelAccount'  =>$modelAccount,
            'modelPerson'  =>$modelPerson,
            'modelAccountWard'  =>$modelAccountWard,
            'dataProvider'  =>$dataProvider,
        ];
      return $this->render('view', [
            'params' => $params,
        ]);
    }
    public function actionViewGreenTechnician($id)
    {
        $modelAccount = $this->findModel($id);
        $personId = $modelAccount->person_id;
        $modelPerson = $this->findModelPerson($personId);
        $modelAccountWard = new AccountWard;
        $dataProvider = new ActiveDataProvider(
        [
           'query' => AccountWard::getAllQuery()->andWhere(['account_id' => $id,'status' => '1']),
           'pagination' => false,
           'sort'=> ['defaultOrder' => ['id' => SORT_DESC]]
        ]);
        $params = [
            'modelAccount'  =>$modelAccount,
            'modelAccountWard'  =>$modelAccountWard,
            'modelPerson'  =>$modelPerson,
            'dataProvider'  =>$dataProvider,
        ];

        return $this->render('green-technician/view_green_technician', [
            'params' => $params,
        ]);
    }
     public function actionLogin()
      {
       $this->layout = 'login-page';
        $modelLogin = new LoginForm();
        if (!Yii::$app->user->isGuest) {
         // $link = Yii::$app->loginComponent->loginredirect();
             $link = Url::to(['/wms-dashboard']);
         return $this->redirect($link );
        }
        if ($modelLogin->load(Yii::$app->request->post()) && $modelLogin->login()) {
          $modelUser = Account::find()->where(['username' => $modelLogin->username])->andWhere(['status' => 1])->andWhere(['is_banned'=>1])->one();
          if($modelUser->role!='customer')
        {

           $modelLoginHistory = new LoginHistory;
           $modelLoginHistory->account_id = $modelUser->id ;
           $modelLoginHistory->login_datetime = date('Y-m-d H:i:s');
            $modelLoginHistory->role = $modelUser->role;
           $modelLoginHistory->save(false);
        }
             $link = Url::to(['/wms-dashboard']);
          return $this->redirect($link);
       } else {
         $params = ['modelLogin'=>$modelLogin];
         return $this->render('login',['modelLogin'=>$modelLogin]);
       }
     }
     public function actionLogout()
     {
      Yii::$app->session->destroy();
      Yii::$app->user->logout(true);
      return $this->redirect(['account/login']);

    }
     public function actionSuperAdmin()
    {
       $modelAccount = new Account;
        $keyword      = null;
        $lsgi         = null;
        $unit         = null;
        $page         = null;
        $lsgi         = null;
        $supervisor         = null;
        $post         = Yii::$app->request->post();
        if (isset($post['lsgi']))
        {
            $lsgi = $post['lsgi'];
        }
        if (isset($post['unit']))
        {
            $unit = $post['unit'];
        }
        if (isset($post['supervisor']))
        {
            $supervisor = $post['supervisor'];
        }
        if (isset($_POST['name']))
        {
            $keyword = $_POST['name'];
        }
        $dataProvider = new ActiveDataProvider(
            [
                'query'      => Account::getAllQuery($lsgi, $unit, $keyword,$supervisor)->andWhere(['role' => 'super-admin']),
                'pagination' => false,
                'sort'       => ['defaultOrder' => ['id' => SORT_DESC]]
            ]);

        return $this->render('super-admin/index', ['dataProvider' => $dataProvider,'modelAccount'=>$modelAccount]);
    }
     public function actionCreateSuperAdmin()
    {
        $modelAccount = new Account();
        $modelPerson = new Person;
        $params = Yii::$app->request->post();
        $paramsOk =  $params && $modelPerson->load($params) && $modelAccount->load($params);
        $modelAccount->setScenario('create-super-admin');
        $modelAccount->setScenario('add');
        if($paramsOk){
          $personOk = $modelPerson->validate();
          $accountOk = $modelAccount->validate();
          if($personOk && $accountOk){
            $modelAccount->hashPassword();
            $modelPerson->save(false);
            $modelAccount->person_id = $modelPerson->id;
            $modelAccount->role = "super-admin";
            $modelAccount->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
           if($modelAccount->save(false))
            {
                Yii::$app->rbac->assignAuthRole('super-admin',$modelAccount->id);
                // $modelAuthAssignment= new AuthAssignment;
                // $modelAuthAssignment->item_name = 'super-admin';
                // $modelAuthAssignment->user_id = $modelAccount->id;
                // $modelAuthAssignment->save(false);
            }
            return $this->redirect(['super-admin']);
          }
        }
        return $this->render('super-admin/create', [
            'modelPerson' => $modelPerson,
            'modelAccount' => $modelAccount,
        ]);
    }
    public function actionUpdateSuperAdmin($id)
    {
        $modelAccount = $this->findModel($id);
        $personId = $modelAccount->person_id;
        $modelPerson = $this->findModelPerson($personId);
        $lsgi = $modelAccount->lsgi_id;
        $unit = $modelAccount->green_action_unit_id;
        $password = $modelAccount->password_hash;
        // $modelAccount->setScenario('update');
        $params = Yii::$app->request->post();
        $paramsOk =  $params && $modelPerson->load($params) && $modelAccount->load($params);
        if($paramsOk){
          $personOk = $modelPerson->validate();
          $accountOk = $modelAccount->validate();
          if($personOk && $accountOk){
            $modelAccount->hashPassword();

            $modelPerson->save(false);
            $modelAccount->person_id = $modelPerson->id;
            $modelAccount->role = "super-admin";
            if(!$modelAccount->lsgi_id)
            {
                $modelAccount->lsgi_id = $lsgi;
            }
            if(!$modelAccount->green_action_unit_id)
            {
                $modelAccount->green_action_unit_id = $unit;
            }
            $modelAccount->save(false);
            return $this->redirect(['super-admin']);
          }
        }

        return $this->render('super-admin/update', [
          'modelPerson' => $modelPerson,
          'modelAccount' => $modelAccount,
        ]);
    }

    public function actionCoordinators()
    {
         $modelAccount = new Account;
        $keyword      = null;
        $lsgi         = null;
        $unit         = null;
        $page         = null;
        $lsgi         = null;
        $coordinator         = null;
        $post         = Yii::$app->request->post();
        if (isset($post['lsgi']))
        {
            $lsgi = $post['lsgi'];
        }
        if (isset($post['unit']))
        {
            $unit = $post['unit'];
        }
        if (isset($post['coordinator']))
        {
            $coordinator = $post['coordinator'];
        }
        if (isset($_POST['name']))
        {
            $keyword = $_POST['name'];
        }
        $dataProvider = new ActiveDataProvider(
            [
                'query'      => Account::getAllQuery($lsgi, $unit, $keyword,$coordinator)->andWhere(['role' => 'coordinator']),
                'pagination' => false,
                'sort'       => ['defaultOrder' => ['id' => SORT_DESC]]
            ]);
        $modelUser  = Yii::$app->user->identity;
        $associations = Yii::$app->rbac->getAssociations($modelUser->id);
        return $this->render('coordinator/index', ['dataProvider' => $dataProvider,'modelAccount'=>$modelAccount,'associations' => $associations]);
    }
     public function actionCreateCoordinator()
    {
        $modelAccount = new Account();
        $modelPerson = new Person;
        $params = Yii::$app->request->post();
        $paramsOk =  $params && $modelPerson->load($params) && $modelAccount->load($params);

        $modelAccount->setScenario('coordinator');
        $modelAccount->setScenario('add');
        if($paramsOk){
          $personOk = $modelPerson->validate();
          $accountOk = $modelAccount->validate();
          if($personOk && $accountOk){
            $modelAccount->hashPassword();
            $modelPerson->save(false);
            $modelAccount->person_id = $modelPerson->id;
            $modelAccount->role = "coordinator";
            $modelAccount->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
            if($modelAccount->save(false))
            {
                Yii::$app->rbac->assignAuthRole('coordinator',$modelAccount->id);
                // $modelAuthAssignment= new AuthAssignment;
                // $modelAuthAssignment->item_name = 'supervisor';
                // $modelAuthAssignment->user_id = $modelAccount->id;
                // $modelAuthAssignment->save(false);
            }
            return $this->redirect(['coordinators']);
          }
        }
        return $this->render('coordinator/create', [
            'modelPerson' => $modelPerson,
            'modelAccount' => $modelAccount,
        ]);
    }
    public function actionUpdateCoordinator($id)
    {
        $modelAccount = $this->findModel($id);
        $personId = $modelAccount->person_id;
        $modelPerson = $this->findModelPerson($personId);
        $lsgi = $modelAccount->lsgi_id;
        $unit = $modelAccount->green_action_unit_id;
        $agency = $modelAccount->survey_agency_id;
        $password = $modelAccount->password_hash;
        $modelAccount->setScenario('coordinator');
        $params = Yii::$app->request->post();
        $paramsOk =  $params && $modelPerson->load($params) && $modelAccount->load($params);
        if($paramsOk){
          $personOk = $modelPerson->validate();
          // $accountOk = $modelAccount->validate();
          // print_r($modelAccount);die();
          if($personOk){

            // $modelAccount->hashPassword();

            $modelPerson->save(false);
            $modelAccount->person_id = $modelPerson->id;
            $modelAccount->role = "coordinator";
            if(!$modelAccount->lsgi_id)
            {
                $modelAccount->lsgi_id = $lsgi;
            }
            if(!$modelAccount->green_action_unit_id)
            {
                $modelAccount->green_action_unit_id = $unit;
            }
            if(!$modelAccount->survey_agency_id)
            {
                $modelAccount->survey_agency_id = $agency;
            }
            $modelAccount->save(false);
            return $this->redirect(['coordinators']);
          }
        }

        return $this->render('coordinator/update', [
          'modelPerson' => $modelPerson,
          'modelAccount' => $modelAccount,
        ]);
    }
     public function actionViewSupervisor($id)
    {
        $modelAccount = $this->findModel($id);
        $personId = $modelAccount->person_id;
        $modelPerson = $this->findModelPerson($personId);
        $modelAccountWard = new AccountWard;
        $dataProvider = new ActiveDataProvider(
        [
           'query' => AccountWard::getAllQuery()->andWhere(['account_id' => $id,'status' => '1']),
           'pagination' => false,
           'sort'=> ['defaultOrder' => ['id' => SORT_DESC]]
        ]);
        $params = [
            'modelAccount'  =>$modelAccount,
            'modelAccountWard'  =>$modelAccountWard,
            'modelPerson'  =>$modelPerson,
            'dataProvider'  =>$dataProvider,
        ];

        return $this->render('supervisor/view', [
            'params' => $params,
        ]);
    }
//       public function actionResetPassword() {
//     if(Yii::$app->user&&Yii::$app->user->identity&& Yii::$app->user->identity->id) Yii::$app->utilities->show404();

//     $model =  new LoginForm();
//     // $model->setScenario('password-reset');
//     $params = Yii::$app->request->post();
//     if($model->load($params)) {
//        $modelUser = $model->getUserData();
//        $modelUser->generatePasswordResetToken();
//        $modelUser->update(false);
//        $modelUserFull = Account::find()->where(['status'=>1])->andWhere(['username'=>$modelUser->username])->one();
//        // print_r($modelUserFull);die();
//        if($modelUserFull)
//          Yii::$app->email->sendPasswordReset($modelUserFull);
//        $model->addError('username','We have sent an email containing link to reset your password.');
//     }
// $params = ['modelLogin'=>$model];
//     return $this->renderAjax('reset-password',['params'=>$params]);
//   }
    public function actionResetPassword() {
    if(Yii::$app->user&&Yii::$app->user->identity&& Yii::$app->user->identity->id) Yii::$app->utilities->show404();

    $model =  new LoginForm();
    // $model->setScenario('password-reset');
    $params = Yii::$app->request->post();
    if($model->load($params)) {
       $modelUser = $model->getUserData();
       $modelUser->generatePasswordResetToken();
       $modelUser->update(false);
       $modelUserFull = Account::find()->where(['status'=>1])->andWhere(['username'=>$modelUser->username])->one();
       if($modelUserFull&&$modelUserFull->role!='customer'){
         Yii::$app->email->sendPasswordReset($modelUserFull);
         $model->addError('username','We have sent an email containing link to reset your password.');
        $modelPerson = Person::find()->where(['status'=>1])->andWhere(['id'=>$modelUserFull->person_id])->one();
        if($modelPerson)
        {
            $activationUrl =  Url::toRoute(['account/reset-password-user','token'=>$modelUserFull->password_reset_token],'http' );
            $authKey = Yii::$app->params['authKeyMsg'];
            $phone = $modelPerson->phone1;
            $content = "Dear User,Somebody tried to reset your password. If that were you, you can use the following link to reset your password.$activationUrl";
                   $key = 'account_id';
                   $countryCode = '91';
                   $senderId = 'WMSMGMT';
                   Yii::$app->message->sendSMS($authKey,"WMSMGMT","91",$phone,$content);
        }
       }
       else
       {
        $modelCustomer = Customer::find()->where(['lead_person_phone'=>$model->username])->andWhere(['status'=>1])->one();
                if($modelCustomer)
                {
                    $modelAccount = Account::find()->where(['status'=>1])->andWhere(['customer_id'=>$modelCustomer->id])->one();
                   $activationUrl =  Url::toRoute(['account/reset-password-user','token'=>$modelAccount->password_reset_token],'http' );
            $authKey = Yii::$app->params['authKeyMsg'];
            $phone = $modelCustomer->lead_person_phone;
            $content = "Dear User,Somebody tried to reset your password. If that were you, you can use the following link to reset your password.$activationUrl";
                   $key = 'account_id';
                   $countryCode = '91';
                   $senderId = 'WMSMGMT';
                   Yii::$app->message->sendSMS($authKey,"WMSMGMT","91",$phone,$content);
                    $model->addError('username','We have sent SMS containing link to reset your password.');
                }
       }
    }
$params = ['modelLogin'=>$model];
    return $this->renderAjax('reset-password',['params'=>$params]);
  }
  public function actionResetPasswordUser($token) {

       if(Yii::$app->user&&Yii::$app->user->identity&& Yii::$app->user->identity->id) Yii::$app->utilities->show404();
        $modelAccount = $this->findModelByResetToken($token);
        $post = Yii::$app->request->post();
        // if($modelAccount->load($params))  {
        //     $modelAccount->password_hash = Yii::$app->security->generatePasswordHash($modelAccount->password);
        //     $modelAccount->password_reset_token = null;
        //     $modelAccount->update(false);
        //     // Yii::$app->email->sendPasswordResetConfirmation($modelUser);
        //     return Yii::$app->getResponse()->redirect(['wms-dashboard/index']);
        // }
        while(true) {
       $proceed = $modelAccount->load(Yii::$app->request->post()) && $modelAccount->validate();
       $modelAcc = Account::find()->where(['password_reset_token'=>$token])->one();
       if(!$proceed)
        break;
       $password = $post['Account']['password'];

       $modelAcc->password_hash = Yii::$app->security->generatePasswordHash($password);
       $modelAcc->save(false);return Yii::$app->getResponse()->redirect(['wms-dashboard/index']);
       break;
      }
      
        return $this->render('reset-password-user',['modelAccount'=>$modelAccount]);
    }
    protected function findModelByResetToken($token)
        {
                $model = Account::find()->where(['password_reset_token' => $token])->andWhere(['status'=> 1])->one();
                if ($model !== null) {
                        return $model;
                } else {
                        throw new NotFoundHttpException('The requested page does not exist.');
                }
        }
}
