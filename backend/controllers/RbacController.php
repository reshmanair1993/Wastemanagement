<?php
namespace backend\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use backend\models\AuthControllers;
use backend\models\AuthAction;
use yii\helpers\Json;
use backend\models\AuthItem;
use backend\models\AuthItemChild;
use backend\models\Account;
use backend\models\AccountWard;
use backend\models\Person;
use backend\models\Lsgi;
use backend\models\Ward;
use backend\models\LsgiBlock;
use backend\models\AssemblyConstituency;
use backend\models\AuthAssociation;
use backend\models\RoleAssociation;
use backend\models\District;
use backend\models\GreenActionUnit;
use backend\models\SurveyAgency;
use backend\models\GreenActionUnitWard;
use backend\models\ResidentialAssociation;
use backend\components\AccessPermission;
use yii\helpers\ArrayHelper;
ini_set("memory_limit","500M");
/**
 * Site controller
 */

class RbacController extends Controller
{
    public $superAdminRole = 'super-admin';
    public $lsgiAdminRole = 'lsgi-admin';
    public $hksAdminRole = 'hks-admin';
    public $supervisorsRole = 'supervisors';
    public $greenTechniciansRole = 'green-technicians';
    public $coordinatorRole = 'coordinator';
    public $surveyorRole = 'surveyor';


    public function behaviors()
    {
        return [
            'access' => [
                'class'        => AccessControl::className(),
                'only'         => ['index','create','update','view'],
                'ruleConfig' => [
                        'class' => AccessPermission::className(),
                    ],
                'rules'        => [
                    [
                        'actions' => ['controllers-index'],
                        'allow'   => true,
                        'permissions' => ['rbac-controllers-index']
                    ],
                    [
                        'actions' => ['create-controllers'],
                        'allow'   => true,
                        'permissions' => ['rbac-create-controllers']
                    ],
                    [
                        'actions' => ['update-controllers'],
                        'allow'   => true,
                        'permissions' => ['rbac-update-controllers']
                    ],
                    [
                        'actions' => ['create-action'],
                        'allow'   => true,
                        'permissions' => ['rbac-create-action']
                    ],
                    [
                        'actions' => ['delete-controllers'],
                        'allow'   => true,
                        'permissions' => ['rbac-delete-controllers']
                    ],
                    [
                        'actions' => ['delete-actions'],
                        'allow'   => true,
                        'permissions' => ['rbac-delete-actions']
                    ],
                    [
                        'actions' => ['create-roles'],
                        'allow'   => true,
                        'permissions' => ['rbac-create-roles']
                    ],
                    [
                        'actions' => ['update-roles'],
                        'allow'   => true,
                        'permissions' => ['rbac-update-roles']
                    ],
                    [
                        'actions' => ['assign-association'],
                        'allow'   => true,
                        'permissions' => ['rbac-assign-association']
                    ],
                    [
                        'actions' => ['users-index'],
                        'allow'   => true,
                        'permissions' => ['rbac-users-index']
                    ],
                    [
                        'actions' => ['create-user'],
                        'allow'   => true,
                        'permissions' => ['rbac-create-user']
                    ],
                    [
                        'actions' => ['update-user'],
                        'allow'   => true,
                        'permissions' => ['rbac-update-user']
                    ],
                    [
                        'actions' => ['delete-user'],
                        'allow'   => true,
                        'permissions' => ['rbac-delete-user']
                    ],
                    [
                        'actions' => ['set-user-password'],
                        'allow'   => true,
                        'permissions' => ['rbac-set-user-password']
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

    public function assignAuthRole($roleName,$accountId){
      $auth = Yii::$app->authManager;
      $role = $auth->getRole($roleName);
      $modelRole = Account::find()->where(['role'=>$roleName])->andWhere(['status'=>1])->one();
      if(!$modelRole){
        $modelRole = new Account;
        $modelRole->name = $roleName;
        $modelRole->save(false);
      }
      if(!$role){
        $role = $auth->createRole($roleName);
        $auth->add($role);
      }
      $roles = \Yii::$app->authManager->getRolesByUser($accountId);
      if(!isset($roles[$roleName]))
        $auth->assign($role,$accountId);
    }
    public function actionControllersIndex(){
      $modelAuthControllers = new AuthControllers;
      $dataProvider = $modelAuthControllers->search(Yii::$app->request->queryParams);
      $params = [
        'modelAuthControllers' => $modelAuthControllers,
        'dataProvider' => $dataProvider,
      ];
      return $this->render('controllers-index',[
        'params' => $params,
      ]);
    }

    public function actionCreateControllers(){
      $saved = false;
      $modelAuthControllers = new AuthControllers;
      $params = Yii::$app->request->post();
      $paramsOk = $params && $modelAuthControllers->load($params);
      if($paramsOk){
        $modelAuthControllersOk = $modelAuthControllers->validate();
        if($modelAuthControllersOk){
          $controllerName = $modelAuthControllers->name;
          $modelAuthControllers->save(false);
          $saved = true;
          return $this->redirect('controllers-index');
        }
      }
      $params = [
        'modelAuthControllers' => $modelAuthControllers,
      ];
      return $this->render('create-controllers-form',[
        'params' => $params,
      ]);
    }
    public function actionUpdateControllers($id){
      $modelAuthAction = $this->findModelAuthAction($id);
      $modelAuthControllers = $this->findModelAuthControllers($id);
      $dataProvider = new ActiveDataProvider([
        'query' => $modelAuthAction,
      ]);
      $params = [
        'dataProvider' => $dataProvider,
        'modelAuthControllers' => $modelAuthControllers,
      ];
      return $this->render('action-index',[
        'params' => $params,
      ]);
    }
    public function actionCreateAction($id){
      $saved = false;
      $auth = Yii::$app->authManager;
      $modelAuthControllers = $this->findModelAuthControllers($id);
      $modelAuthAction = new AuthAction;
      $params = Yii::$app->request->post();
      $paramsOk = $params && $modelAuthAction->load($params);
      if($paramsOk){
        $modelAuthAction->auth_controllers_id = $modelAuthControllers->id;
        $modelAuthActionOk = $modelAuthAction->validate();
        if($modelAuthActionOk){
          $controllerName = $modelAuthControllers->name;
          $actionName = $modelAuthAction->name;
          $permissionName = $controllerName."-".$actionName;
          $permission = $auth->getPermissions($permissionName);
          if($permission == $permissionName){
            $modelAuthAction->addError($modelAuthAction->name,"Permission already given");
          }else{
            $permission = $auth->createPermission($permissionName);
            $auth->add($permission);
            $modelAuthControllers->save(false);
            $modelAuthAction->auth_controllers_id = $modelAuthControllers->id;
            $modelAuthAction->save(false);
            $saved = true;
            // return $this->redirect('controllers-index');
            $modelAuthAction = $this->findModelAuthAction($modelAuthControllers->id);
            $modelAuthControllers = $this->findModelAuthControllers($modelAuthControllers->id);
            $dataProvider = new ActiveDataProvider([
              'query' => $modelAuthAction,
            ]);
            $params = [
              'dataProvider' => $dataProvider,
              'modelAuthControllers' => $modelAuthControllers,
            ];
            return $this->render('action-index',[
              'params' => $params
            ]);
          }
        }
      }
      $params = [
        'modelAuthControllers' => $modelAuthControllers,
        'modelAuthAction' => $modelAuthAction,
      ];
      return $this->render('update-action-form',[
        'params' => $params,
      ]);
    }
    public function actionDeleteControllers($id){
      $authController = $this->findModelAuthControllers($id);
      $modelAuthItem = new AuthItem;
      // $modelAuthItem->deleteAuthControllerItem($authController->name);
      $modelAuthControllers = new AuthControllers;
      $modelAuthControllers->deleteAuthControllers($id);
      $modelAuthAction = new AuthAction;
      $modelAuthAction->deleteAuthActionController($id);
    }
    public function actionDeleteActions($action_id,$controller_id){
      $authAction = $this->modelAuthAction($action_id);
      $authController = $this->findModelAuthControllers($controller_id);
      $name = $authController->name."-".$authAction->name;
      $modelAuthItem = new AuthItem;
      $modelAuthItem->deleteAuthItem($name);
      $modelAuthAction = new AuthAction;
      $modelAuthAction->deleteAuthAction($action_id);
    }
    public function findModelAuthControllers($id){
      $modelAuthControllers = AuthControllers::find()->where(['status'=>1,'id'=>$id])->one();
      return $modelAuthControllers;
    }
    public function findModelAuthAction($id){
      $modelAuthAction = AuthAction::find()->where(['status'=>1,'auth_controllers_id'=>$id]);
      return $modelAuthAction;
    }
    public function modelAuthAction($id){
      $modelAuthAction = AuthAction::find()->where(['status'=>1,'id'=>$id])->one();
      return $modelAuthAction;
    }
    public function findModelAuthAssociation($userId){
      $modelAuthAssociation = AuthAssociation::find()->where(['status'=>1,'user_id'=>$userId])->one();
      return $modelAuthAssociation;
    }

    public function actionRolesIndex(){
      $modelAuthItem = AuthItem::find()->where(['type'=>1])->orderby('name ASC');
      $dataProvider = new ActiveDataProvider([
        'query' => $modelAuthItem,
      ]);
      $params = [
        'dataProvider' => $dataProvider,
      ];
      return $this->render('roles-index',[
        'params' => $params,
      ]);
    }
    public function actionCreateRoles(){
      $auth = Yii::$app->authManager;
      $modelAuthItem = new AuthItem;
      $params = Yii::$app->request->post();
      $paramsOk = $params && $modelAuthItem->load($params);
      if($paramsOk){
        $roleName = $modelAuthItem->name;
        $role = $auth->getRole($roleName);
        if($role){
          $modelAuthItem->addError($modelAuthItem->name,"Role already exists.");
        }else {
          $this->redirect(['update-roles','name'=>$roleName]);
        }
      }
      $params = [
        'modelAuthItem' => $modelAuthItem,
      ];
      return $this->render('create-roles',[
        'params' => $params
      ]);
    }
    public function actionUpdateRoles($name){
      $checked = false;
      $auth = Yii::$app->authManager;
      $modelAuthControllers = new AuthControllers;
      $modelAuthAction = new AuthAction;
      $modelAuthItem = new AuthItem;
      $modelRoleAssociation = new RoleAssociation;
      $authControllers = AuthControllers::find()->where(['status'=>1])->all();
      $controllerList = ArrayHelper::map($authControllers,'id','name');
      $params = Yii::$app->request->post();
      $modelAuthAction->setScenario('create-role');
      $modelAuthControllers->setScenario('create-role');
      $paramsOk = $params && $modelAuthAction->load($params);
      $parentRoles = isset($_POST['AuthItem']['rule_name'])?$_POST['AuthItem']['rule_name']:'';
      $getRole = $auth->getRole($name);
      if($getRole){
        $role = $auth->getRole($name);
      }else{
        $role = $auth->createRole($name);
      }
      if($parentRoles){
        // print_r($parentRoles);exit;
        $connection = Yii::$app->db;
        $connection->createCommand()->delete('auth_item_child', ['child' => $role->name])->execute();
        foreach ($parentRoles as $parentRole) {
          $parentRoleName = $auth->getRole($parentRole);
          $auth->addChild($parentRoleName,$role);
        }
      }
      if($paramsOk){
        $models = $modelAuthAction['action_id'];
        $getRole = $auth->getRole($name);
        if($getRole){
          $role = $auth->getRole($name);
        }else{
          $role = $auth->createRole($name);
          $auth->add($role);
        }
        $connection = Yii::$app->db;
        if($role->name == 'super-admin'){
        }else{
          $connection->createCommand()->delete('auth_item_child', ['parent' => $role->name])->execute();
        }
        foreach ($models as $model) {
          if($model){
            foreach ($model as $value) {
              $modelAuthAction = AuthAction::find()->where(['id'=>$value])->one();
              $actionName = $modelAuthAction->name;
              $modelAuthControllers = AuthControllers::find()->where(['id'=>$modelAuthAction->auth_controllers_id])->one();
              $controllerName = $modelAuthControllers->name;
              $permissionName = $controllerName."-".$actionName;
              $permission = $auth->getPermissions($permissionName);
              if($permission[$permissionName]){
                $qry = $modelAuthItem->getPermissions($role->name);
                foreach ($qry as $key => $value) {
                  $qry[] = $value;
                }
                if (in_array($permission[$permissionName]->name,$qry)){
                }else{
                  $auth->addChild($role,$permission[$permissionName]);
                }
              }
            }
          }
        }
      }
      $roleName = $role->name;
      $permissionArray = $modelAuthItem->getPermissions($roleName);
      $query = AuthItemChild::find()->where(['child'=>$roleName])->all();
      $parentRoleNameArray = [];
      foreach ($query as $qry) {
        $parentRoleNameArray[] = $qry->parent;
      }
      $params = [
        'permissionArray' => $permissionArray,
        'parentRoleNameArray' => $parentRoleNameArray,
        'controllerList' => $controllerList,
        'modelAuthControllers' => $modelAuthControllers,
        'modelAuthAction' => $modelAuthAction,
        'name' => $name,
        'modelRoleAssociation' => $modelRoleAssociation,
      ];
      return $this->render('update-roles',[
        'params' => $params
      ]);
    }
    public function actionAssignAssociation($name){
      $modelRoleAssociation = RoleAssociation::find()->where(['status'=>1,'role'=>$name])->one();
      if(!$modelRoleAssociation){
        $modelRoleAssociation = new RoleAssociation;
      }
      $post = Yii::$app->request->post();
      $paramsOk = $post && $modelRoleAssociation->load($post);
      if($paramsOk){
        $modelRoleAssociationOk = $modelRoleAssociation->validate();
        if($modelRoleAssociationOk){
          $modelRoleAssociation->role = $name;
          $modelRoleAssociation->save(false);
        }
      }
      return $this->redirect(['update-roles','name' => $name]);
    }
    public function actionUsersIndex($type=null){
      $modelUser = Yii::$app->user->identity;
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
      if($modelUser->role=='supervisor')
      {
        $supervisor = $modelUser->id;
      }
      if($type){
        $dataProvider = new ActiveDataProvider(
          [
            // 'query'      => Account::find()->where(['status'=>1,'role'=>$type]),
          'query'      => Account::getAllQuery($lsgi, $unit, $keyword,$supervisor)->andWhere(['role'=>$type]),
            'pagination' => false,
            'sort'       => ['defaultOrder' => ['id' => SORT_DESC]]
          ]
        );
      }else{
        $dataProvider = new ActiveDataProvider(
          [
            'query'      => Account::getAllQuery($lsgi, $unit, $keyword,$supervisor)->andWhere(['<>','role','customer']),
            'pagination' => false,
            'sort'       => ['defaultOrder' => ['id' => SORT_DESC]]
          ]
        );
      }
      $params = [
        'modelAccount' => $modelAccount,
        'dataProvider' => $dataProvider,
        'type' => $type
      ];
      return $this->render('users-index',[
        'params' => $params,
      ]);
    }
    public function actionCreateUser($type=null){
      $modelAccount = new Account();
      $modelPerson = new Person;
      $modelAuthAssociation = new AuthAssociation;
      $modelAccountWard = new AccountWard;
      $userRole = Yii::$app->user->identity->role;
      $query = AuthItem::find()
      ->leftjoin('auth_item_child','auth_item_child.child=auth_item.name')
      ->where(['auth_item_child.parent'=>$userRole])
      ->andWhere(['auth_item.type'=>1])
      ->all();
      if($query){
        foreach ($query as $qry) {
          $roleList[$qry->name] = $qry->name;
        }
      }else{
        $roleList = [];
      }
      $modelAuthItems = AuthItem::find()->where(['type'=>1])->all();
      foreach ($modelAuthItems as $modelAuthItem) {
        // $roleList[$modelAuthItem->name] = $modelAuthItem->name;
      }
      $modelAuthItems = new AuthItem;
      $params = Yii::$app->request->post();
      $paramsOk =  $params && $modelPerson->load($params) && $modelAccount->load($params) && $modelAuthItem->load($params);
      $modelAccount->setScenario('create-super-admin');
      $modelAccount->setScenario('add');
      if($paramsOk){
        // print_r($params);exit;
        $personOk = $modelPerson->validate();
        $accountOk = $modelAccount->validate();
        if($personOk && $accountOk){
          if(isset($_POST['AuthAssociation'])){
            $post = $_POST['AuthAssociation'];
            $modelAuthAssociation = new AuthAssociation;
            if(isset($post['district_id'])){
              $modelAuthAssociation->district_id = $post['district_id'];
            }
            if(isset($post['lsgi_id'])){
              $modelAuthAssociation->lsgi_id = $post['lsgi_id'];
              $modelAccount->lsgi_id = $post['lsgi_id'];
            }
            
            if(isset($post['hks_id'])){
              $modelAuthAssociation->hks_id = $post['hks_id'];
              $modelAccount->green_action_unit_id = $post['hks_id'];
            }
            if(isset($post['gt_id'])){
              $modelAuthAssociation->gt_id = $post['gt_id'];
            }
            if(isset($post['supervisor_id'])){
              $modelAuthAssociation->supervisor_id = $post['supervisor_id'];
              $modelAccount->supervisor_id = $post['supervisor_id'];
            }
            if(isset($post['residential_association_id'])){
              $modelAuthAssociation->residential_association_id = $post['residential_association_id'];
              $modelAccount->residential_association_id = $post['residential_association_id'];
            }
            if(isset($post['survey_agency_id'])){
              $modelAuthAssociation->survey_agency_id = $post['survey_agency_id'];
              $modelAccount->survey_agency_id = $post['survey_agency_id'];
            }
            $modelAccount->hashPassword();
            $modelPerson->save(false);
            $modelAccount->person_id = $modelPerson->id;
            $modelAccount->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
            $modelAccount->role = $modelAuthItem->name;
            $modelAccount->save(false);
            if(isset($post['ward_id'])&&$post['ward_id']!=null){
              $jsonArray = json_encode($post['ward_id']);
              $modelAuthAssociation->ward_id = $jsonArray;
              $wardIds = $post['ward_id'];
              if($wardIds){
                foreach($wardIds as $wardId){
                  // print_r($wardId);die();
                  $modelAccountWard = new AccountWard;
                  $modelAccountWard->account_id = $modelAccount->id;
                  $modelAccountWard->ward_id = $wardId;
                  // print_r($modelAccountWard);die();
                  $modelAccountWard->save(false);
                }
              }
            }
            $this->assignAuthRole($modelAuthItem->name,$modelAccount->id);
            $modelAuthAssociation->user_id = $modelAccount->id;
            // $modelAccountWard->account_id = $modelAccount->id;
            // $modelAccountWard->save(false);
            $modelAuthAssociation->save(false);
            return $this->redirect(['users-index','type'=>$type]);
          }else{
            $modelAuthItem->addError($modelAuthItem->name,'This role has no association');
          }
        }
      }
      return $this->render('create-user', [
          'modelPerson' => $modelPerson,
          'modelAccount' => $modelAccount,
          'roleList' => $roleList,
          'modelAuthItem' => $modelAuthItem,
          'modelAuthAssociation' => $modelAuthAssociation,
          'type' => $type
      ]);
    }
    public function actionUpdateUser($user_id,$type=null){
      $modelAccount = $this->findModelAccount($user_id);
      
      $modelAccountWard = new AccountWard;
      $modelPerson = Person::find()->where(['status'=>1,'id'=>$modelAccount->person_id])->one();
      $authAssociation = AuthAssociation::find()->where(['status'=>1,'user_id'=>$modelAccount->id])->one();
      if($authAssociation){
        $modelAuthAssociation = $authAssociation;
      }else{
        $modelAuthAssociation = new AuthAssociation;
      }
      $supervisor = $modelAuthAssociation->supervisor_id;
      $residential_association_id = $modelAuthAssociation->residential_association_id;
      $gt = $modelAuthAssociation->gt_id;
      $ward = $modelAuthAssociation->ward_id;
      $lsgi = $modelAuthAssociation->lsgi_id;
      $district_id = $modelAuthAssociation->district_id;
      $hks = $modelAuthAssociation->hks_id;
      $agency = $modelAuthAssociation->survey_agency_id;
      $userRole = Yii::$app->user->identity->role;
      $query = AuthItem::find()
      ->leftjoin('auth_item_child','auth_item_child.child=auth_item.name')
      ->where(['auth_item_child.parent'=>$userRole])
      ->andWhere(['auth_item.type'=>1])
      ->all();
      if($query){
        foreach ($query as $qry) {
          $roleList[$qry->name] = $qry->name;
        }
      }else{
        $roleList = [];
      }
      $modelAuthItems = AuthItem::find()->where(['type'=>1])->all();
      foreach ($modelAuthItems as $modelAuthItem) {
        // $roleList[$modelAuthItem->name] = $modelAuthItem->name;
      }
      $params = Yii::$app->request->post();
      $paramsOk =  $params && $modelPerson->load($params) && $modelAccount->load($params) && $modelAuthItem->load($params);
      $modelAccount->setScenario('create-super-admin');
      $modelAccount->setScenario('update');
      if($paramsOk){
        $personOk = $modelPerson->validate();
        $accountOk = $modelAccount->validate();
        if($personOk && $accountOk){
          if(isset($_POST['AuthAssociation'])){
            $post = $_POST['AuthAssociation'];
            if(isset($post['district_id'])){
              $modelAuthAssociation->district_id = $post['district_id'];
            }else{
              $modelAuthAssociation->district_id = $district_id;
            }
            if(isset($post['lsgi_id'])){
              $modelAuthAssociation->lsgi_id = $post['lsgi_id'];
              $modelAccount->lsgi_id = $post['lsgi_id'];
            }else{
              $modelAuthAssociation->lsgi_id = $lsgi;
              $modelAccount->lsgi_id = $lsgi;
            }
            if(isset($post['ward_id'])&&$post['ward_id']!=null){
              $jsonArray = json_encode($post['ward_id']);
              $modelAuthAssociation->ward_id = $jsonArray;
              $wardIds = $post['ward_id'];
              if($wardIds){
                $this->deleteModelAccountWard($user_id);
                foreach($wardIds as $wardId){
                  $modelAccountWard = new AccountWard;
                  $modelAccountWard->account_id = $modelAccount->id;
                  $modelAccountWard->ward_id = $wardId;
                  $modelAccountWard->save(false);
                }
              }
            }else{
              $modelAuthAssociation->ward_id = $ward;
              // $modelAccountWard->ward_id = null;
            }
            if(isset($post['hks_id'])){
              $modelAuthAssociation->hks_id = $post['hks_id'];
              $modelAccount->green_action_unit_id = $post['hks_id'];
            }else{
              $modelAuthAssociation->hks_id = $hks;
              $modelAccount->green_action_unit_id = $hks;
            }
            if(isset($post['gt_id'])){
              $modelAuthAssociation->gt_id = $post['gt_id'];
            }else{
              $modelAuthAssociation->gt_id = $gt;
            }
            if(isset($post['supervisor_id'])){
              $modelAuthAssociation->supervisor_id = $post['supervisor_id'];
              $modelAccount->supervisor_id = $post['supervisor_id'];
            }
            else
            {
              $modelAuthAssociation->supervisor_id = $supervisor;
              $modelAccount->supervisor_id = $supervisor;
            }
            if(isset($post['residential_association_id'])){
              $modelAuthAssociation->residential_association_id = $post['residential_association_id'];
              $modelAccount->residential_association_id = $post['residential_association_id'];
            }
            else
            {
              $modelAuthAssociation->residential_association_id = $residential_association_id;
              $modelAccount->residential_association_id = $residential_association_id;
            }
            if(isset($post['survey_agency_id'])){
              $modelAuthAssociation->survey_agency_id = $post['survey_agency_id'];
              $modelAccount->survey_agency_id = $post['survey_agency_id'];
            }else{
              $modelAuthAssociation->survey_agency_id = $agency;
              $modelAccount->survey_agency_id = $agency;
            }
            $modelPerson->save(false);
            $modelAccount->person_id = $modelPerson->id;
            $modelAccount->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
            $modelAccount->role = $modelAuthItem->name;
            $modelAuthAssociation->user_id = $modelAccount->id;
            $modelAuthAssociation->save(false);
            $modelAccount->save(false);
            $this->assignAuthRole($modelAuthItem->name,$modelAccount->id);
            return $this->redirect(['users-index','type'=>$type]);
          }else{
            $modelAuthItem->addError($modelAuthItem->name,'This role has no association');
          }
        }
      }
      return $this->render('create-user', [
          'modelPerson' => $modelPerson,
          'modelAccount' => $modelAccount,
          'roleList' => $roleList,
          'modelAuthItem' => $modelAuthItem,
          'modelAuthAssociation' => $modelAuthAssociation,
          'type' => $type
      ]);
    }
    public function actionDeleteUser($acc_id){
      $modelAuthAssociation = new AuthAssociation;
      $modelAuthAssociation->deleteUser($acc_id);
    }
    protected function findModelAccount($accId)
    {
        $modelAccount = Account::find()->where(['status'=>1,'id' => $accId])->one();
        if($modelAccount){
          return $modelAccount;
        }
    }
    protected function deleteModelAccountWard($accId)
    {
      $connection = Yii::$app->db;
       $connection->createCommand()->update('account_ward', ['status' => 0], 'account_id=:id')->bindParam(':id',$accId)->execute();
    }
                          //functions to define in rbac component
                                  //start
    public function getAssociations($user_id){
      $modelAuthAssociation = AuthAssociation::find()->where(['status'=>1,'user_id'=>$user_id])->one();
      return [
        'district_id' => $modelAuthAssociation->district_id,
        'lsgi_id'     => $modelAuthAssociation->lsgi_id,
        'ward_id'     => json_decode($modelAuthAssociation->ward_id),
        'hks_id'      => $modelAuthAssociation->hks_id,
        'gt_id'       => $modelAuthAssociation->gt_id,
        'survey_agency_id' => $modelAuthAssociation->survey_agency_id
      ];
    }

    public function setAssociations($user_id,$associations){
      $authAssociation = $this->findModelAuthAssociation($user_id);
      $modelAuthAssociation = isset($authAssociation)?$authAssociation:new AuthAssociation;
      $modelAuthAssociation->user_id = $user_id;
      if(is_array($associations)){
        foreach ($associations as $key => $association) {
          foreach ($modelAuthAssociation as $columnName => $value) {
            if($columnName == $key){
              if(is_array($association)){
                $modelAuthAssociation->ward_id = json_encode($association);
              }else{
                $modelAuthAssociation->$columnName = $association;
              }
            }
          }
        }
        $modelAuthAssociation->save(false);
      }else{
        return "Given parameter is not an array. Please give an array.";
      }
    }
                                                  //end


    public function actionGetAuthActions() {
      $out = [];
      if (isset($_POST['depdrop_parents'])) {
        $parents = $_POST['depdrop_parents'];
        $modelAuthControllers = AuthControllers::find()->where(['id'=>$parents[0]])->andWhere(['status'=>1])->one();
        $modelAuthAction = AuthAction::find()->where(['auth_controllers_id'=>$modelAuthControllers['id']])->andWhere(['status'=>1])->all();
        foreach ($modelAuthAction as $id => $post) {
          $out[] = ['id' => $post['id'], 'name' => $post['name']];
        }
        echo Json::encode(['output' => $out, 'selected' => '']);
      }
    }
    public function actionGetLsgi(){
      $out = [];
      if (isset($_POST['depdrop_parents'])) {
        $parents = $_POST['depdrop_parents'];
        $userId = Yii::$app->user->identity->id;
        $modelAuthAssociation = AuthAssociation::find()->where(['status'=>1,'user_id'=>$userId])->one();
        $modelRoleAssociation = RoleAssociation::find()->where(['role'=>$parents[0]])->andWhere(['status'=>1])->one();
        $district = District::find()->where(['id'=>$parents[1]])->andWhere(['status'=>1])->one();
        if($modelRoleAssociation->has_lsgi_association == 1 && $modelRoleAssociation->district_association == 1){
          if(isset($modelAuthAssociation->district_id)){
            $distId = $modelAuthAssociation->district_id;
          }else{
            $distId = $district->id;
          }
          $modelLsgi = Lsgi::find()
          ->leftJoin('lsgi_block','lsgi_block.id=lsgi.block_id')
          ->leftJoin('assembly_constituency','assembly_constituency.id=lsgi_block.assembly_constituency_id')
          ->where(['assembly_constituency.district_id'=>$distId])
          ->andWhere(['lsgi.status'=>1])
          ->andWhere(['lsgi_block.status'=>1])
          ->andWhere(['assembly_constituency.status'=>1])
          ->all();
        }elseif($modelRoleAssociation->has_lsgi_association == 1 && $modelRoleAssociation->district_association == 0){
          if(isset($modelAuthAssociation->lsgi_id)){
            $modelLsgi = Lsgi::find()->where(['id'=>$modelAuthAssociation->lsgi_id,'status'=>1])->all();
            foreach ($modelLsgi as $id => $post) {
              $out[] = ['id' => $post['id'], 'name' => $post['name']];
            }
            echo Json::encode(['output' => $out, 'selected' => '']);
          }elseif(isset($modelAuthAssociation->district_id)){
            $modelLsgi = Lsgi::find()
            ->leftJoin('lsgi_block','lsgi_block.id=lsgi.block_id')
            ->leftJoin('assembly_constituency','assembly_constituency.id=lsgi_block.assembly_constituency_id')
            ->where(['assembly_constituency.district_id'=>$modelAuthAssociation->district_id])
            ->andWhere(['lsgi.status'=>1])
            ->andWhere(['lsgi_block.status'=>1])
            ->andWhere(['assembly_constituency.status'=>1])
            ->all();
            foreach ($modelLsgi as $id => $post) {
              $out[] = ['id' => $post['id'], 'name' => $post['name']];
            }
            echo Json::encode(['output' => $out, 'selected' => '']);
          }else{
            $modelLsgi = Lsgi::find()->where(['status'=>1])->all();
          }
        }
        foreach ($modelLsgi as $id => $post) {
          $out[] = ['id' => $post['id'], 'name' => $post['name']];
        }
        echo Json::encode(['output' => $out, 'selected' => '']);
      }
    }

    public function actionGetWard() {
      $out = [];
      if (isset($_POST['depdrop_parents'])) {
        $parents = $_POST['depdrop_parents'];
        $userId = Yii::$app->user->identity->id;
        $modelAuthAssociation = AuthAssociation::find()->where(['status'=>1,'user_id'=>$userId])->one();
        $modelRoleAssociation = RoleAssociation::find()->where(['role'=>$parents[0]])->andWhere(['status'=>1])->one();
        $district = District::find()->where(['id'=>$parents[1]])->andWhere(['status'=>1])->one();
        $modelLsgi = Lsgi::find()->where(['id'=>$parents[2]])->andWhere(['status'=>1])->one();
        if($modelRoleAssociation->has_ward_association == 1){
          if($modelLsgi){
            $wardId = isset($modelAuthAssociation->ward_id)?json_decode($modelAuthAssociation->ward_id):'';
            if($wardId){
              $modelWard = Ward::find()->where(['status'=>1,'id'=>$wardId])->all();
            }else{
              $modelWard = Ward::find()->where(['status'=>1,'lsgi_id'=>$modelLsgi->id])->all();
            }
          }elseif(
            $modelRoleAssociation->has_lsgi_association == 0
            && $modelRoleAssociation->district_association == 1
          ){
            if($modelAuthAssociation->ward_id){
              $wardId = json_decode($modelAuthAssociation->ward_id);
              $modelWard = Ward::find()->where(['status'=>1,'id'=>$wardId])->all();
            }else{
              $modelWard = Ward::find()
              ->leftjoin('lsgi','lsgi.id=ward.lsgi_id')
              ->leftjoin('lsgi_block','lsgi_block.id=lsgi.block_id')
              ->leftjoin('assembly_constituency','assembly_constituency.id=lsgi_block.assembly_constituency_id')
              ->where(['assembly_constituency.district_id'=>$district->id])
              ->andWhere(['ward.status'=>1])
              ->andWhere(['lsgi.status'=>1])
              ->andWhere(['lsgi_block.status'=>1])
              ->andWhere(['assembly_constituency.status'=>1])
              ->andWhere(['ward.status'=>1])
              ->all();
            }
          }elseif(
            $modelRoleAssociation->has_lsgi_association == 0
            && $modelRoleAssociation->district_association == 0
          ){
            if(isset($modelAuthAssociation->ward_id)){
              $wardId = json_decode($modelAuthAssociation->ward_id);
              $modelWard = Ward::find()->where(['status'=>1])->andWhere(['id'=>$wardId])->all();
            }elseif(isset($modelAuthAssociation->lsgi_id)){
              $modelWard = Ward::find()->where(['status'=>1,'lsgi_id'=>$modelAuthAssociation->lsgi_id])->all();
            }elseif(isset($modelAuthAssociation->district_id)){
              $modelWard = Ward::find()
              ->leftjoin('lsgi','lsgi.id=ward.lsgi_id')
              ->leftjoin('lsgi_block','lsgi_block.id=lsgi.block_id')
              ->leftjoin('assembly_constituency','assembly_constituency.id=lsgi_block.assembly_constituency_id')
              ->where(['assembly_constituency.district_id'=>$modelAuthAssociation->district_id])
              ->andWhere(['ward.status'=>1])
              ->andWhere(['lsgi.status'=>1])
              ->andWhere(['lsgi_block.status'=>1])
              ->andWhere(['assembly_constituency.status'=>1])
              ->andWhere(['ward.status'=>1])
              ->all();
            }else{
              $modelWard = Ward::find()->where(['status'=>1])->all();
            }
          }
          foreach ($modelWard as $id => $post) {
            $out[] = ['id' => $post['id'], 'name' => $post['name_en']];
          }
          echo Json::encode(['output' => $out, 'selected' => '']);
        }
      }
    }
    public function actionGetGreenActionUnit() {
      $out = [];
      if (isset($_POST['depdrop_parents'])) {
        $parents = $_POST['depdrop_parents'];
        $userId = Yii::$app->user->identity->id;
        $modelAuthAssociation = AuthAssociation::find()->where(['status'=>1,'user_id'=>$userId])->one();
        $modelRoleAssociation = RoleAssociation::find()->where(['role'=>$parents[0]])->andWhere(['status'=>1])->one();
        if (isset($parents[3])) {
          $modelWards = Ward::find()->where(['id'=>$parents[3]])->andWhere(['status'=>1])->all();
        }else{
          $modelWards = [];
        }
        if($modelRoleAssociation->has_hks_association == 1){
          if(isset($modelAuthAssociation->hks_id)){
            $modelGreenActionUnit = GreenActionUnit::find()->where(['status'=>1,'id'=>$modelAuthAssociation->hks_id])->all();
          }
          elseif($modelWards){
            $wardId = [];
            foreach($modelWards as $modelWard){
              $wardId[] = $modelWard->id;
            }
            $modelGreenActionUnit = GreenActionUnit::find()
            ->leftJoin('green_action_unit_ward','green_action_unit_ward.green_action_unit_id=green_action_unit.id')
            ->where(['green_action_unit_ward.ward_id'=>$wardId])
            ->andWhere(['green_action_unit_ward.status'=>1])
            ->all();
          }
          elseif(
            $modelRoleAssociation->has_lsgi_association == 1
          ){
            $modelGreenActionUnit = GreenActionUnit::find()->where(['status'=>1,'lsgi_id'=>$parents[2]])->all();
          }
          elseif(
            $modelRoleAssociation->district_association == 1
          ){
            $modelGreenActionUnit = GreenActionUnit::find()
            ->leftjoin('lsgi','lsgi.id=green_action_unit.lsgi_id')
            ->leftjoin('lsgi_block','lsgi_block.id=lsgi.block_id')
            ->leftjoin('assembly_constituency','assembly_constituency.id=lsgi_block.assembly_constituency_id')
            ->where(['assembly_constituency.district_id'=>$parents[1]])
            ->andWhere(['lsgi.status'=>1])
            ->andWhere(['lsgi_block.status'=>1])
            ->andWhere(['assembly_constituency.status'=>1])
            ->andWhere(['green_action_unit.status'=>1])
            ->all();
          }else{
            $modelGreenActionUnit = GreenActionUnit::find()->where(['status'=>1])->all();
          }
        }
        foreach ($modelGreenActionUnit as $id => $post) {
          $out[] = ['id' => $post['id'], 'name' => $post['name']];
        }
        echo Json::encode(['output' => $out, 'selected' => '']);
      }
    }
    public function actionGetGt() {
      $out = [];
      if (isset($_POST['depdrop_parents'])) {
        $parents = $_POST['depdrop_parents'];
        $userId = Yii::$app->user->identity->id;
        $modelAuthAssociation = AuthAssociation::find()->where(['status'=>1,'user_id'=>$userId])->one();
        $modelRoleAssociation = RoleAssociation::find()->where(['role'=>$parents[0]])->andWhere(['status'=>1])->one();
        if($modelRoleAssociation->has_gt_association == 1){
          if(isset($modelAuthAssociation->gt_id)){
            $modelAccount = Account::find()->where(['id'=>$modelAuthAssociation->gt_id])->andWhere(['status'=>1,'role'=>'gt'])->all();
          }
          elseif($modelRoleAssociation->has_hks_association == 1){
            $account = Account::find()->where(['green_action_unit_id'=>$parents[4]])->andWhere(['status'=>1,'role'=>'gt'])->all();
            if($account){
              $modelAccount = $account;
            }else {
              $modelAccount = [];
            }
          }
          elseif($modelAuthAssociation->ward_id){
            $wardId = json_decode($modelAuthAssociation);
            $modelAccount = Account::find()
            ->leftjoin('green_action_unit','green_action_unit.id=account.green_action_unit_id')
            ->leftjoin('green_action_unit_ward','green_action_unit_ward.green_action_unit_id=green_action_unit.id')
            ->where(['green_action_unit_ward.ward_id' => $wardId])
            ->andWhere(['green_action_unit.status'=>1])
            ->andWhere(['green_action_unit_ward.status'=>1])
            ->andWhere(['account.role'=>'gt'])
            ->andWhere(['account.status'=>1])
            ->all();
          }
          elseif(
            $modelRoleAssociation->has_ward_association == 1
          ){
            $modelWards = Ward::find()->where(['id'=>$parents[3]])->andWhere(['status'=>1])->all();
            $wardId = [];
            foreach ($modelWards as $modelWard) {
              $wardId[] = $modelWard->id;
            }
            $modelAccount = Account::find()
            ->leftjoin('green_action_unit','green_action_unit.id=account.green_action_unit_id')
            ->leftjoin('green_action_unit_ward','green_action_unit_ward.green_action_unit_id=green_action_unit.id')
            ->where(['green_action_unit_ward.ward_id' => $wardId])
            ->andWhere(['green_action_unit.status'=>1])
            ->andWhere(['green_action_unit_ward.status'=>1])
            ->andWhere(['account.role'=>'gt'])
            ->andWhere(['account.status'=>1])
            ->all();
          }
          elseif(
            $modelRoleAssociation->district_association == 1
          ){
            $modelAccount = Account::find()
            ->leftjoin('green_action_unit','green_action_unit.id=account.green_action_unit_id')
            ->leftjoin('lsgi','lsgi.id=green_action_unit.lsgi_id')
            ->leftjoin('lsgi_block','lsgi_block.id=lsgi.block_id')
            ->leftjoin('assembly_constituency','assembly_constituency.id=lsgi_block.assembly_constituency_id')
            ->where(['assembly_constituency.district_id' => $parents[1]])
            ->andWhere(['green_action_unit.status'=>1])
            ->andWhere(['lsgi.status'=>1])
            ->andWhere(['lsgi_block.status'=>1])
            ->andWhere(['assembly_constituency.status'=>1])
            ->andWhere(['account.role'=>'gt'])
            ->andWhere(['account.status'=>1])
            ->all();
          }
          elseif(
            $modelRoleAssociation->has_lsgi_association == 1
          ){
            $modelAccount = Account::find()
            ->leftjoin('green_action_unit','green_action_unit.id=account.green_action_unit_id')
            ->leftjoin('lsgi','lsgi.id=green_action_unit.lsgi_id')
            ->where(['lsgi.id' => $parents[2]])
            ->andWhere(['green_action_unit.status'=>1])
            ->andWhere(['lsgi.status'=>1])
            ->andWhere(['account.role'=>'gt'])
            ->andWhere(['account.status'=>1])
            ->all();
          }
          else{
            $modelAccount = Account::find()->where(['status'=>1,'role'=>'gt'])->all();
          }
        }
        foreach ($modelAccount as $id => $post) {
          $out[] = ['id' => $post['id'], 'name' => $post['username']];
        }
        echo Json::encode(['output' => $out, 'selected' => '']);
      }
    }
    public function actionGetSurveyAgency() {
      $out = [];
      if (isset($_POST['depdrop_parents'])) {
        $parents = $_POST['depdrop_parents'];
        $userId = Yii::$app->user->identity->id;
        $modelAuthAssociation = AuthAssociation::find()->where(['status'=>1,'user_id'=>$userId])->one();
        $modelRoleAssociation = RoleAssociation::find()->where(['role'=>$parents[0]])->andWhere(['status'=>1])->one();
        $surveyAgency = SurveyAgency::find()->where(['lsgi_id'=>$parents[2]])->andWhere(['status'=>1])->all();
        if($modelRoleAssociation->has_survey_agency_association == 1){
          if(isset($modelAuthAssociation->survey_agency_id)){
            $modelSurveyAgency = SurveyAgency::find()->where(['id'=>$modelAuthAssociation->survey_agency_id])->andWhere(['status'=>1])->all();
          }
          if($surveyAgency){
            $modelSurveyAgency = $surveyAgency;
          }
          elseif(
            $modelRoleAssociation->district_association == 1
          ){
            $modelSurveyAgency = SurveyAgency::find()
            ->leftjoin('lsgi','lsgi.id=survey_agency.lsgi_id')
            ->leftjoin('lsgi_block','lsgi_block.id=lsgi.block_id')
            ->leftjoin('assembly_constituency','assembly_constituency.id=lsgi_block.assembly_constituency_id')
            ->where(['assembly_constituency.district_id'=>$parents[1]])
            ->andWhere(['lsgi.status'=>1])
            ->andWhere(['lsgi_block.status'=>1])
            ->andWhere(['assembly_constituency.status'=>1])
            ->andWhere(['survey_agency.status'=>1])
            ->all();
          }
        }
        foreach ($modelSurveyAgency as $id => $post) {
          $out[] = ['id' => $post['id'], 'name' => $post['name']];
        }
        echo Json::encode(['output' => $out, 'selected' => '']);
      }
    }
    public function actionGetRole(){
      $out = [];
      if (isset($_POST['name'])) {
        $roleName = $_POST['name'];
        $modelRoleAssociation = RoleAssociation::find()->where(['role'=>$roleName,'status'=>1])->one();
        if($modelRoleAssociation){
          echo Json::encode([
            'has_lsgi_association' => $modelRoleAssociation->has_lsgi_association,
            'has_ward_association' => $modelRoleAssociation->has_ward_association,
            'has_hks_association' => $modelRoleAssociation->has_hks_association,
            'has_gt_association' => $modelRoleAssociation->has_gt_association,
            'has_supervisor_association' => $modelRoleAssociation->has_supervisor_association,
            'has_survey_agency_association' => $modelRoleAssociation->has_survey_agency_association,
            'district_association' => $modelRoleAssociation->district_association,
            'has_residential_association' => $modelRoleAssociation->has_residential_association,
          ]);
        }
      }
    }
    public function actionGetDistrict() {
      $out = [];
      if (isset($_POST['depdrop_parents'])) {
        $parents = $_POST['depdrop_parents'];
        $userId = Yii::$app->user->identity->id;
        $modelAuthAssociation = AuthAssociation::find()->where(['status'=>1,'user_id'=>$userId])->one();
        $modelRoleAssociation = RoleAssociation::find()->where(['role'=>$parents[0]])->andWhere(['status'=>1])->one();
        if($modelRoleAssociation->district_association == 1){
          if(isset($modelAuthAssociation->district_id)){
            $modelDistrict = \backend\models\District::find()->where(['status'=>1,'id'=>$modelAuthAssociation->district_id])->all();
          }else{
            $modelDistrict = \backend\models\District::find()->where(['status'=>1])->all();
          }
          foreach ($modelDistrict as $id => $post) {
            $out[] = ['id' => $post['id'], 'name' => $post['name']];
          }
          echo Json::encode(['output' => $out, 'selected' => '']);
        }
      }
    }
     public function actionSetUserPassword($id=null,$type=null)
    {
      $modelAccount = new Account;
      $modelAccount->setScenario('reset-user-password');
      $post = Yii::$app->request->post();
      while(true) {
       $proceed = $modelAccount->load(Yii::$app->request->post()) && $modelAccount->validate();
       $modelAcc = Account::find()->where(['id'=>$id])->one();
       if(!$proceed)
        break;
       $password = $post['Account']['password'];

       $modelAcc->password_hash = Yii::$app->security->generatePasswordHash($password);
       $modelAcc->save(false);
        return $this->redirect(['users-index','type'=>$type]);
       break;
      }
      $modelAccount->password = null;
      $modelAccount->confirm_password = null;
      $params = [
        'modelAccount'=> $modelAccount,
        'id'=>$id,
        'type'=>$type
      ];
      return $this->render('change-password', ['params'=> $params]);
    }
    public function actionGetSupervisor() {
      $out = [];
      if (isset($_POST['depdrop_parents'])) {
        $parents = $_POST['depdrop_parents'];
        $userId = Yii::$app->user->identity->id;
        $modelAuthAssociation = AuthAssociation::find()->where(['status'=>1,'user_id'=>$userId])->one();
        $modelRoleAssociation = RoleAssociation::find()->where(['role'=>$parents[0]])->andWhere(['status'=>1])->one();
        if($modelRoleAssociation->has_supervisor_association == 1){
          if(isset($modelAuthAssociation->supervisor_id)){
            $modelAccount = Account::find()->where(['id'=>$modelAuthAssociation->supervisor_id])->andWhere(['status'=>1,'role'=>'supervisor'])->all();
          }
          elseif($modelRoleAssociation->has_hks_association == 1){
            $account = Account::find()->where(['green_action_unit_id'=>$parents[4]])->andWhere(['status'=>1,'role'=>'supervisor'])->all();
            if($account){
              $modelAccount = $account;
            }else {
              $modelAccount = [];
            }
          }
          elseif($modelAuthAssociation->ward_id){
            $wardId = json_decode($modelAuthAssociation);
            $modelAccount = Account::find()
            ->leftjoin('green_action_unit','green_action_unit.id=account.green_action_unit_id')
            ->leftjoin('green_action_unit_ward','green_action_unit_ward.green_action_unit_id=green_action_unit.id')
            ->where(['green_action_unit_ward.ward_id' => $wardId])
            ->andWhere(['green_action_unit.status'=>1])
            ->andWhere(['green_action_unit_ward.status'=>1])
            ->andWhere(['account.role'=>'supervisor'])
            ->andWhere(['account.status'=>1])
            ->all();
          }
          elseif(
            $modelRoleAssociation->has_ward_association == 1
          ){
            $modelWards = Ward::find()->where(['id'=>$parents[3]])->andWhere(['status'=>1])->all();
            $wardId = [];
            foreach ($modelWards as $modelWard) {
              $wardId[] = $modelWard->id;
            }
            $modelAccount = Account::find()
            ->leftjoin('green_action_unit','green_action_unit.id=account.green_action_unit_id')
            ->leftjoin('green_action_unit_ward','green_action_unit_ward.green_action_unit_id=green_action_unit.id')
            ->where(['green_action_unit_ward.ward_id' => $wardId])
            ->andWhere(['green_action_unit.status'=>1])
            ->andWhere(['green_action_unit_ward.status'=>1])
            ->andWhere(['account.role'=>'supervisor'])
            ->andWhere(['account.status'=>1])
            ->all();
          }
          elseif(
            $modelRoleAssociation->district_association == 1
          ){
            $modelAccount = Account::find()
            ->leftjoin('green_action_unit','green_action_unit.id=account.green_action_unit_id')
            ->leftjoin('lsgi','lsgi.id=green_action_unit.lsgi_id')
            ->leftjoin('lsgi_block','lsgi_block.id=lsgi.block_id')
            ->leftjoin('assembly_constituency','assembly_constituency.id=lsgi_block.assembly_constituency_id')
            ->where(['assembly_constituency.district_id' => $parents[1]])
            ->andWhere(['green_action_unit.status'=>1])
            ->andWhere(['lsgi.status'=>1])
            ->andWhere(['lsgi_block.status'=>1])
            ->andWhere(['assembly_constituency.status'=>1])
            ->andWhere(['account.role'=>'supervisor'])
            ->andWhere(['account.status'=>1])
            ->all();
          }
          elseif(
            $modelRoleAssociation->has_lsgi_association == 1
          ){
            $modelAccount = Account::find()
            ->leftjoin('green_action_unit','green_action_unit.id=account.green_action_unit_id')
            ->leftjoin('lsgi','lsgi.id=green_action_unit.lsgi_id')
            ->where(['lsgi.id' => $parents[2]])
            ->andWhere(['green_action_unit.status'=>1])
            ->andWhere(['lsgi.status'=>1])
            ->andWhere(['account.role'=>'supervisor'])
            ->andWhere(['account.status'=>1])
            ->all();
          }
          else{
            $modelAccount = Account::find()->where(['status'=>1,'role'=>'gt'])->all();
          }
        }
        foreach ($modelAccount as $id => $post) {
          $out[] = ['id' => $post['id'], 'name' => $post['username']];
        }
        echo Json::encode(['output' => $out, 'selected' => '']);
      }
    }
    public function actionGetResidentialAssociation() {
      $out = [];
      if (isset($_POST['depdrop_parents'])) {
        $parents = $_POST['depdrop_parents'];
        $userId = Yii::$app->user->identity->id;
        $modelAuthAssociation = AuthAssociation::find()->where(['status'=>1,'user_id'=>$userId])->one();
        $modelRoleAssociation = RoleAssociation::find()->where(['role'=>$parents[0]])->andWhere(['status'=>1])->one();
        $associationList = ResidentialAssociation::find()->where(['ward_id'=>$parents[3]])->andWhere(['status'=>1])->all();
        if($modelRoleAssociation->has_residential_association == 1){
          if(isset($modelAuthAssociation->residential_association_id)){
            $residentialAssociation = ResidentialAssociation::find()->where(['id'=>$modelAuthAssociation->residential_association_id])->andWhere(['status'=>1])->all();
          }
          if($associationList){
            $residentialAssociation = $associationList;
          }
          elseif(
            $modelRoleAssociation->has_ward_association == 1
          ){
            $residentialAssociation = ResidentialAssociation::find()
            ->leftjoin('ward','ward.id=residential_association.ward_id')
            ->where(['residential_association.ward_id'=>$parents[3]])
            ->andWhere(['residential_association.status'=>1])
            ->andWhere(['ward.status'=>1])
            ->all();
          }
        }
        foreach ($residentialAssociation as $id => $post) {
          $out[] = ['id' => $post['id'], 'name' => $post['name']];
        }
        echo Json::encode(['output' => $out, 'selected' => '']);
      }
    }
}
