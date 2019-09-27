<?php

namespace backend\controllers;

use Yii;
use backend\models\Camera;
use backend\models\Account;
use backend\models\Person;
use backend\models\MonitoringGroupUser;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use backend\components\AccessPermission;
use yii\filters\AccessControl;
use backend\models\CameraServiceRequestAssignment;

/**
 * CameraController implements the CRUD actions for Camera model.
 */
class DashboardController extends Controller
{
    /**
     * @inheritdoc
     */
     public function behaviors()
     {
         return [
             'access' => [
                 'class'        => AccessControl::className(),
                 'only'         => ['index', 'create', 'update', 'view', 'view-details'],
                 'ruleConfig' => [
                         'class' => AccessPermission::className(),
                     ],
                 'rules'        => [
                     [
                         'actions' => ['index'],
                         'allow'   => true,
                         'permissions' => ['dashboard-index']
                     ],
                     [
                         'actions' => ['view'],
                         'allow'   => true,
                         'permissions' => ['dashboard-view']
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
     * Lists all Camera models.
     * @return mixed
     */
    public function actionIndex()
    {
      $modelUser = Yii::$app->user->identity;
      $userRole  = $modelUser->role;
      
      if ($userRole == 'admin-lsgi')
      {
          $modelMonitoringGroupUser = Account::getAllQuery()->andWhere(['lsgi_id' => $modelUser->lsgi_id,'role' => 'camera-monitoring-admin','status' => 1])->all();
          $modelCameraTechnician = Account::getAllQuery()->andWhere(['lsgi_id' => $modelUser->lsgi_id,'role' => 'camera-technician','status' => 1])->all();

          $modelCameraError = CameraServiceRequestAssignment::find()
          ->leftJoin('camera','camera.id = camera_service_assignment.camera_id')
          ->leftJoin('ward','ward.id = camera.ward_id')
          ->leftJoin('lsgi','lsgi.id = ward.lsgi_id')
          ->where(['lsgi.id' => $modelUser->lsgi_id,'camera_service_assignment.camera_servicing_status_option_id' => null])
          ->andWhere(['camera.status'=>1,'ward.status' => 1,'lsgi.status' => 1,'camera_service_assignment.status' => 1 ])->all();
          $modelCamera  = Camera::find()
          ->leftJoin('ward','ward.id = camera.ward_id')
          ->leftJoin('lsgi','lsgi.id = ward.lsgi_id')
          ->where(['lsgi.id' => $modelUser->lsgi_id])
          ->andWhere(['camera.status'=>1,'ward.status' => 1,'lsgi.status' => 1])->all();
      }
      else{
        $modelCamera                   = Camera::find()->where(['status'=>1])->all();
        $modelMonitoringGroupUser      = Account::find()
        ->where(['role' => 'camera-monitoring-admin'])
        ->andWhere(['status' => 1])->all();

        $modelCameraError = CameraServiceRequestAssignment::find()->where(['camera_servicing_status_option_id' => null,'status' => 1 ])->all();

        $modelCameraTechnician          = Account::find()
        ->where(['role' => 'camera-technician'])
        ->andWhere(['status' => 1])->all();
      }
      $cameraCount                   = count($modelCamera);
      $cameraMonitoringAdminCount     = count($modelMonitoringGroupUser);
      $modelCameraErrorCount = count($modelCameraError);
      $cameraTechnicianCount          = count($modelCameraTechnician);

        return $this->render('index', [
            'cameraCount' => $cameraCount,
            'cameraMonitoringAdminCount' => $cameraMonitoringAdminCount,
            'cameraTechnicianCount' => $cameraTechnicianCount,
            'modelCameraErrorCount' => $modelCameraErrorCount
        ]);
    }

    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

}
