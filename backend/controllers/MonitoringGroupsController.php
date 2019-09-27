<?php

namespace backend\controllers;

use Yii;
use backend\models\MonitoringGroup;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use backend\models\Account;
use backend\models\Camera;
use backend\models\MonitoringGroupUser;
use backend\models\MonitoringGroupCamera;
use yii\filters\AccessControl;
use backend\components\AccessRule;
use backend\components\AccessPermission;
/**
 * MonitoringGroupsController implements the CRUD actions for MonitoringGroup model.
 */
class MonitoringGroupsController extends Controller
{
    /**
     * @inheritdoc
     */
     public function behaviors()
    {
        return [
           'access' => [
               'class' => AccessControl::className(),
               'only' => ['index','create','update','view','add-monitoring-group-user','add-monitoring-group-camera','delete-group','delete-monitoring-group-user','delete-monitoring-group-camera'],
               'ruleConfig' => [
                       'class' => AccessPermission::className(),
                   ],
               'rules' => [
                   [
                       'actions' => ['index'],
                       'allow' => true,
                       'permissions' => ['monitoring-groups-index'],
                   ],
                   [
                       'actions' => ['create'],
                       'allow' => true,
                       'permissions' => ['monitoring-groups-create'],
                   ],
                   [
                       'actions' => ['update'],
                       'allow' => true,
                       'permissions' => ['monitoring-groups-update'],
                   ],
                   [
                       'actions' => ['delete'],
                       'allow' => true,
                       'permissions' => ['monitoring-groups-delete'],
                   ],
                   [
                       'actions' => ['view'],
                       'allow' => true,
                       'permissions' => ['monitoring-groups-view'],
                   ],
                   [
                       'actions' => ['delete-monitoring-group-camera'],
                       'allow' => true,
                       'permissions' => ['monitoring-groups-delete-monitoring-group-camera'],
                   ],
                   [
                       'actions' => ['delete-monitoring-group-user'],
                       'allow' => true,
                       'permissions' => ['monitoring-groups-delete-monitoring-group-user'],
                   ],
                   [
                       'actions' => ['add-monitoring-group-camera'],
                       'allow' => true,
                       'permissions' => ['monitoring-groups-add-monitoring-group-camera'],
                   ],
                   [
                       'actions' => ['add-monitoring-group-user'],
                       'allow' => true,
                       'permissions' => ['monitoring-groups-add-monitoring-group-user'],
                   ],
                   [
                       'actions' => ['delete-group'],
                       'allow' => true,
                       'permissions' => ['monitoring-groups-delete-group'],
                   ],
               ],
               'denyCallback' => function($rule, $action) {
                   return $this->redirect('dashboard');
               }
           ],
       ];
    }
    /**
     * Lists all MonitoringGroup models.
     * @return mixed
     */
    public function actionIndex($showSuccess=null)
    {
        $showSuccess = isset($_SESSION['showSuccess']) ? $_SESSION['showSuccess'] : '';
        if(isset($_SESSION['showSuccess']))
          unset($_SESSION['showSuccess']);
        $updateSuccess = isset($_SESSION['updateSuccess']) ? $_SESSION['updateSuccess'] : null;
        if(isset($_SESSION['updateSuccess']))
          unset($_SESSION['updateSuccess']);

        $modelUser = Yii::$app->user->identity;
        $userRole  = $modelUser->role;

        if ($userRole == 'admin-lsgi')
        {
          $query = MonitoringGroup::find()
          ->leftJoin('account','account.id = monitoring_group.account_id_created_by')
          ->where(['account.lsgi_id' => $modelUser->lsgi_id])
          ->andWhere(['monitoring_group.status' => 1,'account.status' => 1])
          ->orderBy(['id' => SORT_DESC]);
          $dataProvider = new ActiveDataProvider([
              'query' => $query,
          ]);
        }
        else{
          $dataProvider = new ActiveDataProvider([
              'query' => MonitoringGroup::find()->andWhere(['status' => 1])->orderBy(['id' => SORT_DESC]),
          ]);
        }

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'showSuccess'=>$showSuccess,
            'updateSuccess'=>$updateSuccess,
        ]);
    }

    /**
     * Displays a single MonitoringGroup model.
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
     * Creates a new MonitoringGroup model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    // public function actionCreate()
    // {
    //     $model = new MonitoringGroup();
    //
    //     if ($model->load(Yii::$app->request->post()) && $model->save()) {
    //         return $this->redirect(['view', 'id' => $model->id]);
    //     }
    //
    //     return $this->render('create', [
    //         'model' => $model,
    //     ]);
    // }

    public function actionCreate()
    {
        $model = new MonitoringGroup();
        // $modelWard = Ward::getAllQuery()->all();
        // $modelAccount = Account::getAllQuery()->all();
        $modelUser = Yii::$app->user->identity;
        $userId  = $modelUser->id;
        $params = Yii::$app->request->post();
        $paramsOk = $params && $model->load($params);
        if($paramsOk){
          $groupOk = $model->validate();
          if($paramsOk && $groupOk){
            $model->account_id_created_by = $userId;
            $model->save(false);
            $session = Yii::$app->session;
            $session->set('showSuccess', '1');
            return $this->redirect(['index']);
          }
        }
        return $this->render('create', [
            'model' => $model,
            // 'modelWard' => $modelWard,
            // 'modelAccount' => $modelAccount
        ]);
    }

    /**
     * Updates an existing MonitoringGroup model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $modelUser = Yii::$app->user->identity;
        $userId  = $modelUser->id;
        $userSuccess = isset($_SESSION['userSuccess']) ? $_SESSION['userSuccess'] : null;
        if( isset($_SESSION['userSuccess']))
          unset($_SESSION['userSuccess']);
        $cameraSuccess = isset($_SESSION['cameraSuccess']) ? $_SESSION['cameraSuccess'] : null;
        if( isset($_SESSION['cameraSuccess']))
          unset($_SESSION['cameraSuccess']);
        $model = $this->findModel($id);
        $modelAccount = Account::getAllQuery()->where(['status' => 1])
        ->andWhere(['role' => "camera-monitoring-admin"])
        ->orWhere(['role' => "camera-technician"])->all();
        $modelCamera = Camera::getAllQuery()->all();
        $modelMonitoringGroupUser = new MonitoringGroupUser();
        $modelMonitoringGroupCamera = new MonitoringGroupCamera();
        $params = Yii::$app->request->post();
        $paramsOk = $model->load($params) && $model->validate();
        if ($paramsOk) {
          $query = $model->getAllUserQuery($model->id);
          $userDataProvider = new ActiveDataProvider([
             'query' => $query,
            ]);
          $query = $model->getAllCameraQuery($model->id);
          $cameraDataProvider = new ActiveDataProvider([
             'query' => $query,
            ]);
          $model->account_id_created_by = $userId;
          $model->update(false);
          $session = Yii::$app->session;
          $session->set('updateSuccess', '1');
          return $this->redirect(['index']);
        }
        $query = $model->getAllUserQuery($model->id);
        // print_r($model);exit;
        $userDataProvider = new ActiveDataProvider([
           'query' => $query,
          ]);
        $query = $model->getAllCameraQuery($model->id);
        $cameraDataProvider = new ActiveDataProvider([
           'query' => $query,
          ]);
        return $this->render('update', [
            'model' => $model,
            'modelAccount' => $modelAccount,
            'modelCamera' => $modelCamera,
            'modelMonitoringGroupUser' => $modelMonitoringGroupUser,
            'modelMonitoringGroupCamera' => $modelMonitoringGroupCamera,
            'userDataProvider' => $userDataProvider,
            'cameraDataProvider' => $cameraDataProvider,
            'userSuccess'=>$userSuccess,
            'cameraSuccess'=>$cameraSuccess,
        ]);
    }
    public function actionAddMonitoringGroupUser($id)
    {
        $model = $this->findModel($id);
        $modelAccount = Account::getAllQuery()->where(['status' => 1,'role' => "camera-monitoring-admin"])->all();
        $modelMonitoringGroupUser = new MonitoringGroupUser();
        $params = Yii::$app->request->post();
        $monitoringUserOk = $modelMonitoringGroupUser->load($params);
        $modelMonitoringGroupUser->monitoring_group_id = $id;
        $paramsOk = $monitoringUserOk && $modelMonitoringGroupUser->validate();
        $query = $model->getAllUserQuery($model->id);
        $userDataProvider = new ActiveDataProvider([
           'query' => $query,
          ]);
        if ($paramsOk) {
          $modelMonitoringGroupUser->save(false);
          $session = Yii::$app->session;
          $session->set('userSuccess', '1');
          return $this->redirect(['update','id' => $model->id]);
        }

        return $this->render('add_monitoring_group_user', [
            'model' => $model,
            'modelAccount' => $modelAccount,
            'modelMonitoringGroupUser' => $modelMonitoringGroupUser,
            'userDataProvider' => $userDataProvider,

        ]);
    }
    public function actionAddMonitoringGroupCamera($id)
    {
        $model = $this->findModel($id);
        // $modelAccount = Account::getAllQuery()->all();
        $modelCamera = Camera::getAllQuery()->all();
        $modelMonitoringGroupCamera = new MonitoringGroupCamera();
        $params = Yii::$app->request->post();
        $monitoringCameraOk = $modelMonitoringGroupCamera->load($params);
        $modelMonitoringGroupCamera->monitoring_group_id = $id;
        $paramsOk = $monitoringCameraOk && $modelMonitoringGroupCamera->validate();
        $query = $model->getAllCameraQuery($model->id);
        $cameraDataProvider = new ActiveDataProvider([
           'query' => $query,
          ]);
        if ($paramsOk) {
          $modelMonitoringGroupCamera->save(false);
          $session = Yii::$app->session;
          $session->set('cameraSuccess', '1');
          return $this->redirect(['update','id' => $model->id]);
        }

        return $this->render('add_monitoring_group_camera', [
            'model' => $model,
            // 'modelAccount' => $modelAccount,
            'modelCamera' => $modelCamera,
            'modelMonitoringGroupCamera' => $modelMonitoringGroupCamera,
            'cameraDataProvider' => $cameraDataProvider,

        ]);
    }

    /**
     * Deletes an existing MonitoringGroup model.
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
     * Finds the MonitoringGroup model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return MonitoringGroup the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = MonitoringGroup::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
    public function actionDeleteGroup($id)
    {
        $model = new MonitoringGroup;
        $model->deleteGroup($id);
    }
    public function actionDeleteMonitoringGroupUser($id)
    {
        $model = new MonitoringGroupUser;
        $model->deleteMonitoringGroupUser($id);
    }
    public function actionDeleteMonitoringGroupCamera($id)
    {
        $model = new MonitoringGroupCamera;
        $model->deleteMonitoringGroupCamera($id);
    }
}
