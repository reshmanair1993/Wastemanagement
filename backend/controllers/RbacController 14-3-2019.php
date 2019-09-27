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
use yii\helpers\ArrayHelper;

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
      $modelAuthItem->deleteAuthControllerItem($authController->name);
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
        $connection->createCommand()->delete('auth_item_child', ['parent' => $role->name])->execute();
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
    public function actionUsersIndex(){
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
          'query'      => Account::getAllQuery($lsgi, $unit, $keyword,$supervisor)->andWhere(['<>','role','customer']),
          'pagination' => false,
          'sort'       => ['defaultOrder' => ['id' => SORT_DESC]]
        ]
      );
      $params = [
        'modelAccount' => $modelAccount,
        'dataProvider' => $dataProvider,
      ];
      return $this->render('users-index',[
        'params' => $params,
      ]);
    }
    public function actionCreateUser(){
      $modelAccount = new Account();
      $modelPerson = new Person;
      $modelAuthAssociation = new AuthAssociation;
      $modelAuthItems = AuthItem::find()->where(['type'=>1])->all();
      foreach ($modelAuthItems as $modelAuthItem) {
        $roleList[$modelAuthItem->name] = $modelAuthItem->name;
      }
      $params = Yii::$app->request->post();
      $paramsOk =  $params && $modelPerson->load($params) && $modelAccount->load($params) && $modelAuthItem->load($params);
      $modelAccount->setScenario('create-super-admin');
      $modelAccount->setScenario('add');
      if($paramsOk){
        $personOk = $modelPerson->validate();
        $accountOk = $modelAccount->validate();
        if($personOk && $accountOk){
          $modelAccount->hashPassword();
          $modelPerson->save(false);
          $modelAccount->person_id = $modelPerson->id;
          $modelAccount->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
          $modelAccount->role = $modelAuthItem->name;
          $post = $_POST['AuthAssociation'];
          $modelAuthAssociation = new AuthAssociation;
          if(isset($post['district_id'])){
            $modelAuthAssociation->district_id = $post['district_id'];
          }
          if(isset($post['lsgi_id'])){
            $modelAuthAssociation->lsgi_id = $post['lsgi_id'];
            $modelAccount->lsgi_id = $post['lsgi_id'];
          }
          if(isset($post['ward_id'])){
            $modelAuthAssociation->ward_id = $post['ward_id'];
          }
          if(isset($post['hks_id'])){
            $modelAuthAssociation->hks_id = $post['hks_id'];
            $modelAccount->green_action_unit_id = $post['hks_id'];
          }
          if(isset($post['gt_id'])){
            $modelAuthAssociation->gt_id = $post['gt_id'];
          }
          if(isset($post['survey_agency_id'])){
            $modelAuthAssociation->survey_agency_id = $post['survey_agency_id'];
            $modelAccount->survey_agency_id = $post['survey_agency_id'];
          }
          $modelAuthAssociation->user_id = $modelAccount->id;
          $modelAuthAssociation->save(false);
          $modelAccount->save(false);
          return $this->redirect(['users-index']);
        }
      }
      return $this->render('create-user', [
          'modelPerson' => $modelPerson,
          'modelAccount' => $modelAccount,
          'roleList' => $roleList,
          'modelAuthItem' => $modelAuthItem,
          'modelAuthAssociation' => $modelAuthAssociation,
      ]);
    }
    public function actionUpdateUser($user_id){
      $modelAccount = $this->findModelAccount($user_id);
      $modelPerson = Person::find()->where(['status'=>1,'id'=>$modelAccount->person_id])->one();
      $authAssociation = AuthAssociation::find()->where(['status'=>1,'user_id'=>$modelAccount->id])->one();
      if($authAssociation){
        $modelAuthAssociation = $authAssociation;
      }else{
        $modelAuthAssociation = new AuthAssociation;
      }
      $modelAuthItems = AuthItem::find()->where(['type'=>1])->all();
      foreach ($modelAuthItems as $modelAuthItem) {
        $roleList[$modelAuthItem->name] = $modelAuthItem->name;
      }
      $params = Yii::$app->request->post();
      $paramsOk =  $params && $modelPerson->load($params) && $modelAccount->load($params) && $modelAuthItem->load($params);
      $modelAccount->setScenario('create-super-admin');
      $modelAccount->setScenario('update');
      if($paramsOk){
        $personOk = $modelPerson->validate();
        $accountOk = $modelAccount->validate();
        if($personOk && $accountOk){
          $modelAccount->hashPassword();
          $modelPerson->save(false);
          $modelAccount->person_id = $modelPerson->id;
          $modelAccount->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
          $modelAccount->role = $modelAuthItem->name;
          $post = $_POST['AuthAssociation'];
          if(isset($post['district_id'])){
            $modelAuthAssociation->district_id = $post['district_id'];
          }else{
            $modelAuthAssociation->district_id = null;
          }
          if(isset($post['lsgi_id'])){
            $modelAuthAssociation->lsgi_id = $post['lsgi_id'];
            $modelAccount->lsgi_id = $post['lsgi_id'];
          }else{
            $modelAuthAssociation->lsgi_id = null;
            $modelAccount->lsgi_id = null;
          }
          if(isset($post['ward_id'])){
            $modelAuthAssociation->ward_id = $post['ward_id'];
          }else{
            $modelAuthAssociation->ward_id = null;
          }
          if(isset($post['hks_id'])){
            $modelAuthAssociation->hks_id = $post['hks_id'];
            $modelAccount->green_action_unit_id = $post['hks_id'];
          }else{
            $modelAuthAssociation->hks_id = null;
            $modelAccount->green_action_unit_id = null;
          }
          if(isset($post['gt_id'])){
            $modelAuthAssociation->gt_id = $post['gt_id'];
          }else{
            $modelAuthAssociation->gt_id = null;
          }
          if(isset($post['survey_agency_id'])){
            $modelAuthAssociation->survey_agency_id = $post['survey_agency_id'];
            $modelAccount->survey_agency_id = $post['survey_agency_id'];
          }else{
            $modelAuthAssociation->survey_agency_id = null;
            $modelAccount->survey_agency_id = null;
          }
          $modelAuthAssociation->user_id = $modelAccount->id;
          $modelAuthAssociation->save(false);
          $modelAccount->save(false);
          return $this->redirect(['users-index']);
        }
      }
      return $this->render('create-user', [
          'modelPerson' => $modelPerson,
          'modelAccount' => $modelAccount,
          'roleList' => $roleList,
          'modelAuthItem' => $modelAuthItem,
          'modelAuthAssociation' => $modelAuthAssociation,
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
        throw new NotFoundHttpException('The requested page does not exist.');
    }
                          //functions to define in rbac component
                                  //start
    public function getAssociations($user_id){
      $modelAuthAssociation = AuthAssociation::find()->where(['status'=>1,'user_id'=>$user_id])->one();
      return [
        'district_id' => $modelAuthAssociation->district_id,
        'lsgi_id'     => $modelAuthAssociation->lsgi_id,
        'ward_id'     => $modelAuthAssociation->ward_id,
        'hks_id'      => $modelAuthAssociation->hks_id,
        'gt_id'       => $modelAuthAssociation->gt_id,
        'survey_agency_id' => $modelAuthAssociation->survey_agency_id
      ];
    }
    public function setAssociations($user_id,$associations){
      $modelAuthAssociation = new AuthAssociation;
      $modelAuthAssociation->user_id = $user_id;
      if(is_array($associations)){
        foreach ($associations as $key => $association) {
          foreach ($modelAuthAssociation as $columnName => $value) {
            if($columnName == $key){
              $modelAuthAssociation->$columnName = $association;
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
        $modelRoleAssociation = RoleAssociation::find()->where(['role'=>$parents[0]])->andWhere(['status'=>1])->one();
        $district = District::find()->where(['id'=>$parents[1]])->andWhere(['status'=>1])->one();
        if($modelRoleAssociation->has_lsgi_association == 1){
          if($district){
            $modelAssemblyConstituencys = AssemblyConstituency::find()->where(['status'=>1,'district_id'=>$district->id])->all();
            foreach ($modelAssemblyConstituencys as $modelAssemblyConstituency) {
              $modelLsgiBlocks = LsgiBlock::find()->where(['status'=>1,'assembly_constituency_id'=>$modelAssemblyConstituency->id])->all();
              foreach ($modelLsgiBlocks as $modelLsgiBlock) {
                $modelLsgis = Lsgi::find()->where(['status'=>1,'block_id'=>$modelLsgiBlock->id])->all();
                foreach ($modelLsgis as $lsgi) {
                  $modelLsgi[] = $lsgi;
                }
              }
            }
            foreach ($modelLsgi as $id => $post) {
              $out[] = ['id' => $post['id'], 'name' => $post['name']];
            }
            echo Json::encode(['output' => $out, 'selected' => '']);
          }elseif($modelRoleAssociation->has_lsgi_association == 1 && $modelRoleAssociation->district_association == 0){
            $modelLsgi = Lsgi::find()->where(['status'=>1])->all();
            foreach ($modelLsgi as $id => $post) {
              $out[] = ['id' => $post['id'], 'name' => $post['name']];
            }
            echo Json::encode(['output' => $out, 'selected' => '']);
          }
        }
      }
    }
    public function actionGetWard() {
      $out = [];
      if (isset($_POST['depdrop_parents'])) {
        $parents = $_POST['depdrop_parents'];
        $modelRoleAssociation = RoleAssociation::find()->where(['role'=>$parents[0]])->andWhere(['status'=>1])->one();
        $district = District::find()->where(['id'=>$parents[1]])->andWhere(['status'=>1])->one();
        $modelLsgi = Lsgi::find()->where(['id'=>$parents[2]])->andWhere(['status'=>1])->one();
        if($modelRoleAssociation->has_ward_association == 1){
          if($modelLsgi){
            $modelWard = Ward::find()->where(['status'=>1,'lsgi_id'=>$modelLsgi->id])->all();
            foreach ($modelWard as $id => $post) {
              $out[] = ['id' => $post['id'], 'name' => $post['name']];
            }
            echo Json::encode(['output' => $out, 'selected' => '']);
          }elseif(
            $modelRoleAssociation->has_ward_association == 1
            && $modelRoleAssociation->has_lsgi_association == 0
            && $modelRoleAssociation->district_association == 0
          ){
            $modelWard = Ward::find()->where(['status'=>1])->all();
            foreach ($modelWard as $id => $post) {
              $out[] = ['id' => $post['id'], 'name' => $post['name']];
            }
            echo Json::encode(['output' => $out, 'selected' => '']);
          }elseif(
            $modelRoleAssociation->has_ward_association == 1
            && $modelRoleAssociation->has_lsgi_association == 0
            && $modelRoleAssociation->district_association == 1
          ){
            // print_r($district->id);exit;
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
            foreach ($modelWard as $id => $post) {
              $out[] = ['id' => $post['id'], 'name' => $post['name']];
            }
            echo Json::encode(['output' => $out, 'selected' => '']);
          }
        }
      }
    }
    public function actionGetGreenActionUnit() {
      $out = [];
      if (isset($_POST['depdrop_parents'])) {
        $parents = $_POST['depdrop_parents'];
        $modelRoleAssociation = RoleAssociation::find()->where(['role'=>$parents[0]])->andWhere(['status'=>1])->one();
        $modelWard = Ward::find()->where(['id'=>$parents[3]])->andWhere(['status'=>1])->one();
        if($modelRoleAssociation->has_hks_association == 1){
          if($modelWard){
            $modelGreenActionUnitWards = GreenActionUnitWard::find()->where(['status'=>1,'ward_id'=>$modelWard->id])->all();
            if($modelGreenActionUnitWards){
              foreach ($modelGreenActionUnitWards as $modelGreenActionUnitWard) {
                $modelGreenActionUnits = GreenActionUnit::find()->where(['status'=>1,'id'=>$modelGreenActionUnitWard->green_action_unit_id])->all();
                foreach ($modelGreenActionUnits as $greenActionUnit) {
                  $modelGreenActionUnit[] = $greenActionUnit;
                }
              }
              foreach ($modelGreenActionUnit as $id => $post) {
                $out[] = ['id' => $post['id'], 'name' => $post['name']];
              }
              echo Json::encode(['output' => $out, 'selected' => '']);
            }
          }elseif(
            $modelRoleAssociation->has_hks_association == 1 &&
            $modelRoleAssociation->has_ward_association == 0 &&
            $modelRoleAssociation->has_lsgi_association == 0 &&
            $modelRoleAssociation->district_association == 0
          ){
            $modelGreenActionUnit = GreenActionUnit::find()->where(['status'=>1])->all();
            foreach ($modelGreenActionUnit as $id => $post) {
              $out[] = ['id' => $post['id'], 'name' => $post['name']];
            }
            echo Json::encode(['output' => $out, 'selected' => '']);
          }
          elseif(
            $modelRoleAssociation->has_hks_association == 1 &&
            $modelRoleAssociation->has_ward_association == 0 &&
            $modelRoleAssociation->has_lsgi_association == 1 &&
            $modelRoleAssociation->district_association == 0
          ){
            $modelGreenActionUnit = GreenActionUnit::find()->where(['status'=>1,'lsgi_id'=>$parents[2]])->all();
            foreach ($modelGreenActionUnit as $id => $post) {
              $out[] = ['id' => $post['id'], 'name' => $post['name']];
            }
            echo Json::encode(['output' => $out, 'selected' => '']);
          }
          elseif(
            $modelRoleAssociation->has_hks_association == 1 &&
            $modelRoleAssociation->has_ward_association == 0 &&
            $modelRoleAssociation->has_lsgi_association == 0 &&
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
            foreach ($modelGreenActionUnit as $id => $post) {
              $out[] = ['id' => $post['id'], 'name' => $post['name']];
            }
            echo Json::encode(['output' => $out, 'selected' => '']);
          }
        }
      }
    }
    public function actionGetGt() {
      $out = [];
      if (isset($_POST['depdrop_parents'])) {
        $parents = $_POST['depdrop_parents'];
        $modelRoleAssociation = RoleAssociation::find()->where(['role'=>$parents[0]])->andWhere(['status'=>1])->one();
        $modelAccount = Account::find()->where(['green_action_unit_id'=>$parents[4]])->andWhere(['status'=>1,'role'=>'gt'])->all();
        if($modelRoleAssociation->has_gt_association == 1){
          if($modelAccount){
            foreach ($modelAccount as $id => $post) {
              $out[] = ['id' => $post['id'], 'name' => $post['username']];
            }
            echo Json::encode(['output' => $out, 'selected' => '']);
          }
          elseif(
            $modelRoleAssociation->has_gt_association == 1 &&
            $modelRoleAssociation->has_hks_association == 0 &&
            $modelRoleAssociation->district_association == 0 &&
            $modelRoleAssociation->has_lsgi_association == 0 &&
            $modelRoleAssociation->has_ward_association == 0
          ){
            $account = Account::find()->where(['status'=>1,'role'=>'gt'])->all();
            foreach ($account as $id => $post) {
              $out[] = ['id' => $post['id'], 'name' => $post['username']];
            }
            echo Json::encode(['output' => $out, 'selected' => '']);
          }
          elseif(
            $modelRoleAssociation->has_gt_association == 1 &&
            $modelRoleAssociation->has_hks_association == 0 &&
            $modelRoleAssociation->district_association == 1 &&
            $modelRoleAssociation->has_lsgi_association == 0 &&
            $modelRoleAssociation->has_ward_association == 0
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
            foreach ($modelAccount as $id => $post) {
              $out[] = ['id' => $post['id'], 'name' => $post['username']];
            }
            echo Json::encode(['output' => $out, 'selected' => '']);
          }
          elseif(
            $modelRoleAssociation->has_gt_association == 1 &&
            $modelRoleAssociation->has_hks_association == 0 &&
            $modelRoleAssociation->district_association == 0 &&
            $modelRoleAssociation->has_lsgi_association == 1 &&
            $modelRoleAssociation->has_ward_association == 0
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
            foreach ($modelAccount as $id => $post) {
              $out[] = ['id' => $post['id'], 'name' => $post['username']];
            }
            echo Json::encode(['output' => $out, 'selected' => '']);
          }
          elseif(
            $modelRoleAssociation->has_gt_association == 1 &&
            $modelRoleAssociation->has_hks_association == 0 &&
            $modelRoleAssociation->district_association == 0 &&
            $modelRoleAssociation->has_lsgi_association == 0 &&
            $modelRoleAssociation->has_ward_association == 1
          ){
            $modelAccount = Account::find()
            ->leftjoin('green_action_unit','green_action_unit.id=account.green_action_unit_id')
            ->leftjoin('green_action_unit_ward','green_action_unit_ward.green_action_unit_id=green_action_unit.id')
            ->where(['green_action_unit_ward.ward_id' => $parents[3]])
            ->andWhere(['green_action_unit.status'=>1])
            ->andWhere(['green_action_unit_ward.status'=>1])
            ->andWhere(['account.role'=>'gt'])
            ->andWhere(['account.status'=>1])
            ->all();
            foreach ($modelAccount as $id => $post) {
              $out[] = ['id' => $post['id'], 'name' => $post['username']];
            }
            echo Json::encode(['output' => $out, 'selected' => '']);
          }
        }
      }
    }
    public function actionGetSurveyAgency() {
      $out = [];
      if (isset($_POST['depdrop_parents'])) {
        $parents = $_POST['depdrop_parents'];
        $modelRoleAssociation = RoleAssociation::find()->where(['role'=>$parents[0]])->andWhere(['status'=>1])->one();
        $modelSurveyAgency = SurveyAgency::find()->where(['lsgi_id'=>$parents[2]])->andWhere(['status'=>1])->all();
        if($modelRoleAssociation->has_survey_agency_association == 1){
          if($modelSurveyAgency){
            foreach ($modelSurveyAgency as $id => $post) {
              $out[] = ['id' => $post['id'], 'name' => $post['name']];
            }
            echo Json::encode(['output' => $out, 'selected' => '']);
          }
          elseif(
            $modelRoleAssociation->has_survey_agency_association == 1 &&
            $modelRoleAssociation->has_lsgi_association == 0 &&
            $modelRoleAssociation->district_association == 0
          ){
            $modelSurveyAgency = SurveyAgency::find()->where(['status'=>1])->all();
            foreach ($modelSurveyAgency as $id => $post) {
              $out[] = ['id' => $post['id'], 'name' => $post['name']];
            }
            echo Json::encode(['output' => $out, 'selected' => '']);
          }
          elseif(
            $modelRoleAssociation->has_survey_agency_association == 1 &&
            $modelRoleAssociation->has_lsgi_association == 0 &&
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
            foreach ($modelSurveyAgency as $id => $post) {
              $out[] = ['id' => $post['id'], 'name' => $post['name']];
            }
            echo Json::encode(['output' => $out, 'selected' => '']);
          }
        }
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
            'has_survey_agency_association' => $modelRoleAssociation->has_survey_agency_association,
            'district_association' => $modelRoleAssociation->district_association,
          ]);
        }
      }
    }
    public function actionGetDistrict() {
      $out = [];
      if (isset($_POST['depdrop_parents'])) {
        $parents = $_POST['depdrop_parents'];
        $modelRoleAssociation = RoleAssociation::find()->where(['role'=>$parents[0]])->andWhere(['status'=>1])->one();
        if($modelRoleAssociation->district_association == 1){
          $modelDistrict = \backend\models\District::find()->where(['status'=>1])->all();
          foreach ($modelDistrict as $id => $post) {
            $out[] = ['id' => $post['id'], 'name' => $post['name']];
          }
          echo Json::encode(['output' => $out, 'selected' => '']);
        }
      }
    }
}
