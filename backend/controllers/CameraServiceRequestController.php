<?php

namespace backend\controllers;

use Yii;
use backend\models\CameraServiceRequest;
use backend\models\Camera;
use backend\models\CameraService;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use backend\models\CameraServiceRequestAssignment;
use backend\components\AccessPermission;

/**
 * CameraServiceRequestController implements the CRUD actions for CameraServiceRequest model.
 */
class CameraServiceRequestController extends Controller
{
    /**
     * {@inheritdoc}
     */
     public function behaviors()
     {

       return [
           'access' => [
               'class'        => AccessControl::className(),
               'only'         => ['index','update','create','add-technician','add-status','delete-camera-service-request'],
               'ruleConfig' => [
                       'class' => AccessPermission::className(),
                   ],
               'rules'        => [
                   [
                       'actions' => ['index'],
                       'allow'   => true,
                       'permissions' => ['camera-service-request-index']
                   ],
                   [
                       'actions' => ['create'],
                       'allow'   => true,
                       'permissions' => ['camera-service-request-create']
                   ],
                   [
                       'actions' => ['update'],
                       'allow'   => true,
                       'permissions' => ['camera-service-request-update']
                   ],
                   [
                       'actions' => ['delete-camera-service-request'],
                       'allow'   => true,
                       'permissions' => ['camera-service-request-delete-camera-service-request']
                   ],
                   [
                       'actions' => ['add-status'],
                       'allow'   => true,
                       'permissions' => ['camera-service-request-add-status']
                   ],
                   [
                       'actions' => ['add-technician'],
                       'allow'   => true,
                       'permissions' => ['camera-service-request-add-technician']
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
     * Lists all CameraServiceRequest models.
     * @return mixed
     */
    public function actionIndex()
    {
        $modelUser = Yii::$app->user->identity;
        $userRole  = $modelUser->role;
        // print_r(Yii::$app->cron->ServiceRequestNotification());exit;
// print_r($userRole);exit;
        if($userRole == 'admin-lsgi'){
          // echo "string";exit;
          $modelCameraServiceRequest = CameraServiceRequest::find()
          ->innerJoin('camera','camera.id = camera_service_request.camera_id')
          ->innerJoin('camera_service','camera_service.id = camera_service_request.service_id')
          ->innerJoin('ward','ward.id = camera.ward_id')
          // ->innerJoin('lsgi','lsgi.id = ward.lsgi_id')
          ->where(['camera.status'=>1,'camera_service_request.status'=>1,'ward.lsgi_id' => $modelUser->lsgi_id]);

          // ->where(['camera.status'=>1,'camera_service.status'=>1,'camera_service_request.status'=>1,'ward.lsgi_id' => $modelUser->lsgi_id]);
          $dataProvider = new ActiveDataProvider([
              'query' => $modelCameraServiceRequest,
          ]);
        }
        else{

          $modelCameraServiceRequest = CameraServiceRequest::find()
          ->innerJoin('camera','camera.id = camera_service_request.camera_id')
          ->innerJoin('camera_service','camera_service.id = camera_service_request.service_id')
          ->where(['camera.status'=>1,'camera_service.status'=>1,'camera_service_request.status'=>1]);
          $dataProvider = new ActiveDataProvider([
              'query' => $modelCameraServiceRequest,
          ]);
        }

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single CameraServiceRequest model.
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
     * Creates a new CameraServiceRequest model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    // public function actionCreate()
    // {
    //     $model = new CameraServiceRequest();
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
      $modelUser = Yii::$app->user->identity;
      $userRole  = $modelUser->role;
      if($userRole == 'admin-lsgi'){
        $modelCamera = Camera::getAllQuery()
        ->innerJoin('ward','ward.id = camera.ward_id')
        ->where(['ward.lsgi_id' => $modelUser->lsgi_id,'ward.status' => 1,'camera.status' => 1])->all();
      }
      else {
        $modelCamera = Camera::getAllQuery()->all();
      }
        $modelService = CameraService::getAllQuery()->all();
        $model = new CameraServiceRequest();
        $params = Yii::$app->request->post();
        $paramsOk = $params && $model->load($params);
        if($paramsOk){
          $cameraServiceRequestOk = $model->validate();
          if($paramsOk && $cameraServiceRequestOk){
            $model->request_date = date('Y-m-d H:i:s');
            $model->save(false);
            $session = Yii::$app->session;
            $session->set('showSuccess', '1');
            return $this->redirect(['index']);
          }
        }
        return $this->render('create', [
            'model' => $model,
            'modelCamera' => $modelCamera,
            'modelService' => $modelService,
        ]);
    }
    /**
     * Updates an existing CameraServiceRequest model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    // public function actionUpdate($id)
    // {
    //     $model = $this->findModel($id);
    //
    //     if ($model->load(Yii::$app->request->post()) && $model->save()) {
    //         return $this->redirect(['view', 'id' => $model->id]);
    //     }
    //
    //     return $this->render('update', [
    //         'model' => $model,
    //     ]);
    // }
    public function actionUpdate($id)
    {
        $modelUser = Yii::$app->user->identity;
        $userRole  = $modelUser->role;
        if($userRole == 'admin-lsgi'){
          $modelCamera = Camera::getAllQuery()
          ->innerJoin('ward','ward.id = camera.ward_id')
          ->where(['ward.lsgi_id' => $modelUser->lsgi_id,'ward.status' => 1,'camera.status' => 1])->all();
        }
        else {
          $modelCamera = Camera::getAllQuery()->all();
        }
        $model = $this->findModel($id);
        $modelService = CameraService::getAllQuery()->all();
        $modelCameraServiceAssignment = new CameraServiceRequestAssignment;
        $params = Yii::$app->request->post();
        $paramsOk = $params && $model->load($params);
        if($paramsOk){
          if($paramsOk){
              $model->update(false);
              $session = Yii::$app->session;
              $session->set('updateSuccess', '1');
              return $this->redirect(['index']);
            }
          }
        return $this->render('update-info', [
            'model' => $model,
            'modelCamera' => $modelCamera,
            'modelService' => $modelService,
            'modelCameraServiceAssignment' => $modelCameraServiceAssignment,
        ]);
    }
    public function actionAddStatus($id)
    {
      $model = $this->findModel($id);
      $modelCamera = Camera::getAllQuery()->all();
      $modelService = CameraService::getAllQuery()->all();
      $modelServiceAssignmentData = CameraServiceRequestAssignment::find()->where(['camera_service_request_id'=>$id])->andWhere(['status'=>1])->one();
    if($modelServiceAssignmentData)
    {
      $modelCameraServiceAssignment= $modelServiceAssignmentData;
    }
    else
    {
      $modelCameraServiceAssignment= new CameraServiceRequestAssignment;
    }
    $modelCameraServiceAssignment->setScenario('add-status');
    // print_r($_POST);exit;
      if ($modelCameraServiceAssignment->load(Yii::$app->request->post())&&$modelCameraServiceAssignment->validate()) {
        // print_r($modelCameraServiceAssignment);EXIT;
          $modelCameraServiceAssignment->camera_service_request_id = $id;
          $modelCameraServiceAssignment->date = date('Y-m-d H:i:s');
           // $modelCameraServiceAssignment->servicing_datetime =$modelCameraServiceAssignment->servicing_datetime?\Yii::$app->formatter->asDatetime($modelCameraServiceAssignment->servicing_datetime, "php:Y-m-d H:i:s"):'';
          $modelCameraServiceAssignment->save(false);
      }
      // else
      // {
      //     print_r($modelServiceAssignment->getErrors());die();
      // }

    return $this->redirect('update-info',
    [
      'model' => $model,
      'modelCameraServiceAssignment' => $modelCameraServiceAssignment,
      'modelCamera' => $modelCamera,
      'modelService' => $modelService,
      ]
    );
  }

    public function actionAddTechnician($id)
    {
        $model = $this->findModel($id);
         $modelServiceAssignmentData = CameraServiceRequestAssignment::find()->where(['camera_service_request_id'=>$id])->andWhere(['status'=>1])->one();
      if($modelServiceAssignmentData)
      {
        $modelCameraServiceAssignment= $modelServiceAssignmentData;
      }
      else
      {
        $modelCameraServiceAssignment= new CameraServiceRequestAssignment;
      }
        // $modelImage = new Image;
        $modelCamera = Camera::getAllQuery()->all();
        $modelService = CameraService::getAllQuery()->all();
        if ($modelCameraServiceAssignment->load(Yii::$app->request->post()) ) {
          // print_r($modelCameraServiceAssignment->account_id_technician);exit;

            $modelCameraServiceAssignment->camera_service_request_id = $id;
            $modelCameraServiceAssignment->camera_id = $model->camera_id;
            $modelCameraServiceAssignment->service_id = $model->service_id;
            if($modelServiceAssignmentData){
            $modelCameraServiceAssignment->camera_id = $modelServiceAssignmentData->camera_id;
            // $modelServiceAssignment->remarks = $modelServiceAssignmentData->remarks;
            $modelCameraServiceAssignment->date = $modelServiceAssignmentData->date;
            $modelCameraServiceAssignment->camera_servicing_status_option_id = $modelServiceAssignmentData->camera_servicing_status_option_id;
            $modelCameraServiceAssignment->lat_update_from = $modelServiceAssignmentData->lat_update_from;
            $modelCameraServiceAssignment->lng_updated_from = $modelServiceAssignmentData->lng_updated_from;
            // $modelServiceAssignmentData->status = 0;
            // $modelServiceAssignmentData->save(false);
        }
        $modelCameraServiceAssignment->save();
        }
      return $this->redirect('update-info',
      [
        'model' => $model,
        'modelCameraServiceAssignment' => $modelCameraServiceAssignment,
        'modelCamera' => $modelCamera,
        'modelService' => $modelService,
        ]
      );
    }
    // public function actionAddTechnician($id)
    // {
    //     $model = $this->findModel($id);
    //     $modelCameraServiceAssignment = CameraServiceRequestAssignment::find()->where(['camera_service_request_id' => $id])->andWhere(['status'=>1])->one();
    //   if($modelCameraServiceAssignment)
    //   {
    //     $modelCameraServiceAssignment = $modelCameraServiceAssignment;
    //   }
    //   else
    //   {
    //     $modelCameraServiceAssignment = new CameraServiceRequestAssignment;
    //   }
    //     if ($modelCameraServiceAssignment->load(Yii::$app->request->post()) ) {
    //         $modelCameraServiceAssignment->camera_service_request_id = $id;
    //         $modelCameraServiceAssignment->camera_id = $model->camera_id;
    //         $modelCameraServiceAssignment->service_id = $model->service_id;
    //         if($modelCameraServiceAssignment){
    //         $modelCameraServiceAssignment->camera_id = $modelCameraServiceAssignment->camera_id;
    //         $modelCameraServiceAssignment->camera_servicing_status_option_id = $modelCameraServiceAssignment->camera_servicing_status_option_id;
    //         $modelCameraServiceAssignment->lat_update_from = $modelCameraServiceAssignment->lat_update_from;
    //         $modelCameraServiceAssignment->lng_updated_from = $modelCameraServiceAssignment->lng_updated_from;
    //         $modelCameraServiceAssignment->status = 0;
    //         $modelCameraServiceAssignment->save(false);
    //     }
    //     $modelCameraServiceAssignment->save();
    //     }
    //     return $this->redirect('index');
    // }

    /**
     * Deletes an existing CameraServiceRequest model.
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
     * Finds the CameraServiceRequest model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return CameraServiceRequest the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CameraServiceRequest::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    public function actionDeleteCameraServiceRequest($id)
    {
        $model = new CameraServiceRequest;
        $model->deleteCameraServiceRequest($id);
    }
}
