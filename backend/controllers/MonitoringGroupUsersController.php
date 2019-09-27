<?php

namespace backend\controllers;

use Yii;
use backend\models\MonitoringGroupUser;
use backend\models\Account;
use backend\models\District;
use backend\models\AssemblyConstituency;
use backend\models\LsgiBlock;
use backend\models\Person;
use backend\models\Ward;
use backend\models\AccountWard;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use backend\components\AccessRule;
use backend\components\AccessPermission;
/**
 * MonitoringGroupUsersController implements the CRUD actions for MonitoringGroupUser model.
 */
class MonitoringGroupUsersController extends Controller
{
    /**
     * {@inheritdoc}
     */
     public function behaviors()
    {
        return [
           'access' => [
               'class' => AccessControl::className(),
               'only'         => ['index','create','create-technician','create-monitoring-person','update-technician','update-monitoring-person','change-password','delete-monitoring-person','delete-camera-technician'],
               'ruleConfig' => [
                       'class' => AccessPermission::className(),
                   ],
               'rules' => [
                   [
                       'actions' => ['index'],
                       'allow' => true,
                       'permissions' => ['monitoring-group-users-index'],
                   ],
                   [
                       'actions' => ['create'],
                       'allow' => true,
                       'permissions' => ['monitoring-group-users-create'],
                   ],
                   [
                       'actions' => ['create-technician'],
                       'allow' => true,
                       'permissions' => ['monitoring-group-users-create-technician'],
                   ],
                   [
                       'actions' => ['update-technician'],
                       'allow' => true,
                       'permissions' => ['monitoring-group-users-update-technician'],
                   ],
                   [
                       'actions' => ['delete-monitoring-person'],
                       'allow' => true,
                       'permissions' => ['monitoring-group-users-delete-monitoring-person'],
                   ],
                   [
                       'actions' => ['delete-camera-technician'],
                       'allow' => true,
                       'permissions' => ['monitoring-group-users-delete-camera-technician'],
                   ],
                   [
                       'actions' => ['create-monitoring-person'],
                       'allow' => true,
                       'permissions' => ['monitoring-group-users-create-monitoring-person'],
                   ],
                   [
                       'actions' => ['update-monitoring-person'],
                       'allow' => true,
                       'permissions' => ['monitoring-group-users-update-monitoring-person'],
                   ],
                   [
                       'actions' => ['change-password'],
                       'allow' => true,
                       'permissions' => ['monitoring-group-users-change-password'],
                   ],
               ],
               'denyCallback' => function($rule, $action) {
                   return $this->redirect('dashboard');
               }
           ],
       ];
    }
    /**
     * Lists all MonitoringGroupUser models.
     * @return mixed
     */
    public function actionIndex($technicianSuccess=null,$updateTechnicianSuccess=null,$monitoringPersonSuccess=null,$updateMonitoringPersonSuccess=null)
    {
      $modelUser = Yii::$app->user->identity;
      $userRole  = $modelUser->role;
      // print_r($updateMonitoringPersonSuccess);exit;
        $technicianSuccess = isset($_SESSION['technicianSuccess']) ? $_SESSION['technicianSuccess'] : null;
        if(isset($_SESSION['technicianSuccess']))
          unset($_SESSION['technicianSuccess']);
        $updateTechnicianSuccess = isset($_SESSION['updateTechnicianSuccess']) ? $_SESSION['updateTechnicianSuccess'] : null;
        if(isset($_SESSION['updateTechnicianSuccess']))
          unset($_SESSION['updateTechnicianSuccess']);
        $monitoringPersonSuccess = isset($_SESSION['monitoringPersonSuccess']) ? $_SESSION['monitoringPersonSuccess'] : null;
        if(isset($_SESSION['monitoringPersonSuccess']))
          unset($_SESSION['monitoringPersonSuccess']);
        $updateMonitoringPersonSuccess = isset($_SESSION['updateMonitoringPersonSuccess']) ? $_SESSION['updateMonitoringPersonSuccess'] : null;
        if(isset($_SESSION['updateMonitoringPersonSuccess']))
          unset($_SESSION['updateMonitoringPersonSuccess']);
        $passwordSuccess = isset($_SESSION['passwordSuccess']) ? $_SESSION['passwordSuccess'] : null;
        if(isset($_SESSION['passwordSuccess']))
          unset($_SESSION['passwordSuccess']);

        if($userRole == 'admin-lsgi'){
          $dataProvider = new ActiveDataProvider([
              'query' => Account::find()->where(['role' => "camera-technician",'status' => 1,'lsgi_id' => $modelUser->lsgi_id])->orderBy(['id' => SORT_DESC]),
          ]);
          $monitoringPersonDataProvider = new ActiveDataProvider([
              'query' => Account::find()->where(['role' => "camera-monitoring-admin",'status' => 1,'lsgi_id' => $modelUser->lsgi_id])->orderBy(['id' => SORT_DESC]),
          ]);
        }
        else {
          $dataProvider = new ActiveDataProvider([
              'query' => Account::find()->where(['role' => "camera-technician",'status' => 1])->orderBy(['id' => SORT_DESC]),
          ]);
          $monitoringPersonDataProvider = new ActiveDataProvider([
              'query' => Account::find()->where(['role' => "camera-monitoring-admin",'status' => 1])->orderBy(['id' => SORT_DESC]),
          ]);
        }

        $modelAccount = new Account();

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'modelAccount' => $modelAccount,
            'monitoringPersonDataProvider' => $monitoringPersonDataProvider,
            'technicianSuccess' => $technicianSuccess,
            'updateTechnicianSuccess' => $updateTechnicianSuccess,
            'monitoringPersonSuccess' => $monitoringPersonSuccess,
            'updateMonitoringPersonSuccess' => $updateMonitoringPersonSuccess,
            'passwordSuccess' => $passwordSuccess
        ]);
    }

    /**
     * Displays a single MonitoringGroupUser model.
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
     * Creates a new MonitoringGroupUser model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new MonitoringGroupUser();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }
    public function actionCreateTechnician()
    {
        $modelUser = Yii::$app->user->identity;
        $userRole  = $modelUser->role;
        $model = new Account();
        $modelPerson = new Person;
        $modelAccountWard = new AccountWard;
        $modelWard = Ward::getAllQuery()->all();
        $post = Yii::$app->request->post();
        $paramsOk = $model->load($post) && $modelPerson->load($post) && $modelAccountWard->load($post);
        if ($paramsOk) {
            $personOk = $modelPerson->validate();
            // print_r($modelPerson->errors);exit;
              $modelPerson->save(false);
              $model->username = $modelPerson->email;
              $model->setScenario('create-technician');
              $accountOk = $model->validate();
              if($accountOk){
                $model->person_id = $modelPerson->id;
                $model->role = 'camera-technician';
                $model->hashPassword();
                if ($userRole == 'admin-lsgi'){
                  $model->district_id = $this->findDistrict($modelUser->lsgi_id);
                  $model->assembly_constituency_id = $this->findAssemblyConstituency($modelUser->lsgi_id);
                  $model->block_id = $this->findBlock($modelUser->lsgi_id);
                  $model->lsgi_id = $modelUser->lsgi_id;
                }
                $model->save(false);
                Yii::$app->rbac->assignAuthRole('camera-technician',$model->id);
                $session = Yii::$app->session;
                $session->set('technicianSuccess', '1');
                $modelAccountWard->account_id = $model->id;
                $modelAccountWard->save(false);
                return $this->redirect(['index']);
              }
        }

        return $this->render('create-technician', [
            'model' => $model,
            'modelPerson' => $modelPerson,
            'modelWard' => $modelWard,
            'modelAccountWard' => $modelAccountWard
        ]);
    }
    public function findDistrict($id){
      $district = District::find()
      ->leftJoin('assembly_constituency','assembly_constituency.district_id=district.id')
      ->leftJoin('lsgi_block','lsgi_block.assembly_constituency_id=assembly_constituency.id')
      ->leftJoin('lsgi','lsgi.block_id=lsgi_block.id')
      ->where(['lsgi.id' => $id])
      ->andWhere(['district.status' => 1, 'assembly_constituency.status'=>1, 'lsgi_block.status'=>1, 'lsgi.status' =>1])
      ->one();
      return $district->id;
    }
    public function findAssemblyConstituency($id){
      $assemblyConstituency = AssemblyConstituency::find()
      ->leftJoin('lsgi_block','lsgi_block.assembly_constituency_id=assembly_constituency.id')
      ->leftJoin('lsgi','lsgi.block_id=lsgi_block.id')
      ->where(['lsgi.id' => $id])
      ->andWhere(['assembly_constituency.status'=>1, 'lsgi_block.status'=>1, 'lsgi.status' =>1])
      ->one();
      return $assemblyConstituency->id;
    }
    public function findBlock($id){
      $block = LsgiBlock::find()
      ->leftJoin('lsgi','lsgi.block_id=lsgi_block.id')
      ->where(['lsgi.id' => $id])
      ->andWhere(['lsgi_block.status'=>1, 'lsgi.status' =>1])
      ->one();
      return $block->id;
    }
    public function actionCreateMonitoringPerson()
    {
      $modelUser = Yii::$app->user->identity;
      $userRole  = $modelUser->role;
      $model = new Account();
      $modelPerson = new Person;
      $modelAccountWard = new AccountWard;
      $modelWard = Ward::getAllQuery()->all();
      $post = Yii::$app->request->post();
      $paramsOk = $model->load($post) && $modelPerson->load($post) && $modelAccountWard->load($post);
      if ($paramsOk) {
          $personOk = $modelPerson->validate();
          $modelPerson->save(false);
          $model->username = $modelPerson->email;
          $model->setScenario('create-technician');
          $accountOk = $model->validate();
          // print_r($model->errors);exit;
          if($accountOk){
            $model->person_id = $modelPerson->id;
            $model->role = 'camera-monitoring-admin';
            $model->hashPassword();
            $model->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
            if ($userRole == 'admin-lsgi'){
              $model->district_id = $this->findDistrict($modelUser->lsgi_id);
              $model->assembly_constituency_id = $this->findAssemblyConstituency($modelUser->lsgi_id);
              $model->block_id = $this->findBlock($modelUser->lsgi_id);
              $model->lsgi_id = $modelUser->lsgi_id;
            }
            $model->save(false);
            Yii::$app->rbac->assignAuthRole('camera-monitoring-admin',$model->id);
            $session = Yii::$app->session;
            $session->set('monitoringPersonSuccess', '1');
            $modelAccountWard->account_id = $model->id;
            // $modelAccountWard->ward_id = $
            $modelAccountWard->save(false);
            return $this->redirect(['index']);
          }
      }

        return $this->render('create-monitoring-person', [
          'model' => $model,
          'modelPerson' => $modelPerson,
          'modelWard' => $modelWard,
          'modelAccountWard' => $modelAccountWard
        ]);
    }
    /**
     * Updates an existing MonitoringGroupUser model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdateTechnician($id)
    {
        $model = $this->findAccountModel($id);
        $modelPerson = $this->findPersonModel($model->person_id);
        // $modelWard = Ward::getAllQuery()->all();
        $modelAccountWard = $this->findAccountWard($id);
        if($modelAccountWard){
          $ward = $modelAccountWard->ward_id;
          $account = $modelAccountWard->account_id;
        }
        $post = Yii::$app->request->post();
        $paramsOk = $model->load($post) && $modelPerson->load($post) && $modelAccountWard->load($post);
        // $ward_id = $modelAccountWard->ward_id;
        if ($paramsOk) {
            $modelPerson->save(false);
            // $modelLsgi = $model->getLsgiId($ward_id);
            // $model->lsgi_id = $modelLsgi->lsgi_id;
            $model->save(false);
            if(!$modelAccountWard->account_id)
            {
              $modelAccountWard->account_id = $account;
            }
            if(!$modelAccountWard->ward_id)
            {
                $modelAccountWard->ward_id = $ward;
            }
            $modelAccountWard->save(false);
            $session = Yii::$app->session;
            $session->set('updateTechnicianSuccess', '1');
            return $this->redirect(['index']);
        }

        return $this->render('update-technician', [
            'model' => $model,
            'modelPerson' => $modelPerson,
            // 'modelWard' => $modelWard,
            'modelAccountWard' => $modelAccountWard


        ]);
    }
    public function actionUpdateMonitoringPerson($id)
    {
        $model = $this->findAccountModel($id);
        $modelPerson = $this->findPersonModel($model->person_id);
        $modelAccountWard = $this->findAccountWard($id);
        if($modelAccountWard){
          $ward = $modelAccountWard->ward_id;
          $account = $modelAccountWard->account_id;
        }
        else
        {
          $modelAccountWard = new AccountWard;
        }
        $post = Yii::$app->request->post();
        $paramsOk = $model->load($post) && $modelPerson->load($post) && $modelAccountWard->load($post);
        // print_r($modelAccountWard);exit;

        if ($paramsOk) {
            $modelPerson->save(false);
            // $modelLsgi = $model->getLsgiId($ward_id);
            // $model->lsgi_id = $modelLsgi->lsgi_id;
            $model->save(false);
            $session = Yii::$app->session;
            $session->set('updateMonitoringPersonSuccess', '1');
            if(!$modelAccountWard->account_id)
            {
              $modelAccountWard->account_id = $account;
            }
            if(!$modelAccountWard->ward_id)
            {
                $modelAccountWard->ward_id = $ward;
            }
            $modelAccountWard->save(false);
            return $this->redirect(['index']);
        }

        return $this->render('update-monitoring-person', [
            'model' => $model,
            'modelPerson' => $modelPerson,
            // 'modelWard' => $modelWard,
            'modelAccountWard' => $modelAccountWard
        ]);
    }
    /**
     * Deletes an existing MonitoringGroupUser model.
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
    public function actionChangePassword($id){

      $model = $this->findAccountModel($id);
      $modelPerson = $this->findPersonModel($model->person_id);
      $post = Yii::$app->request->post();
      $paramsOk = $model->load($post) && $modelPerson->load($post);
      if ($paramsOk) {
          $modelPerson->save(false);
          $model->hashPassword();
          $model->save(false);
          $session = Yii::$app->session;
          $session->set('passwordSuccess', '1');
          return $this->redirect(['index']);
      }
      $model->password_hash = null;
      $model->save(false);
      return $this->render('change-password', [
          'model' => $model,
          'modelPerson' => $modelPerson
      ]);

    }
    /**
     * Finds the MonitoringGroupUser model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return MonitoringGroupUser the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = MonitoringGroupUser::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
    protected function findAccountModel($id)
    {
        if (($model = Account::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
    protected function findAccountWard($id)
    {
        $modelAccountWard = AccountWard::find()->where(['account_id' => $id,'status' =>1])->one();
        if($modelAccountWard){
           return $modelAccountWard;
         }
        // throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
    protected function findPersonModel($id)
    {
        if (($model = Person::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
    public function actionDeleteMonitoringPerson($id)
    {
        $model = new Account;
        $model->deleteMonitoringPerson($id);
    }
    public function actionDeleteCameraTechnician($id)
    {
        $model = new Account;
        $model->deleteCameraTechnician($id);
    }

}
