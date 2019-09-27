<?php

namespace backend\controllers;

use Yii;
use backend\models\CameraService;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use backend\models\Image;
use backend\models\CameraServiceRequestAssignment;
use yii\web\UploadedFile;
use yii\filters\AccessControl;
use backend\components\AccessPermission;

/**
 * CameraServiceRequestController implements the CRUD actions for CameraServiceRequest model.
 */
class CameraServiceController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {

      return [
          'access' => [
              'class'        => AccessControl::className(),
              'only'         => ['index','update','create','delete-camera-service'],
              'ruleConfig' => [
                      'class' => AccessPermission::className(),
                  ],
              'rules'        => [
                  [
                      'actions' => ['index'],
                      'allow'   => true,
                      'permissions' => ['camera-service-index']
                  ],
                  [
                      'actions' => ['create'],
                      'allow'   => true,
                      'permissions' => ['camera-service-create']
                  ],
                  [
                      'actions' => ['update'],
                      'allow'   => true,
                      'permissions' => ['camera-service-update']
                  ],
                  [
                      'actions' => ['delete-camera-service'],
                      'allow'   => true,
                      'permissions' => ['camera-service-delete-camera-service']
                  ],
                  [
                      'actions' => ['camera-service-details'],
                      'allow'   => true,
                      'permissions' => ['camera-service-camera-service-details']
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
     * Lists all CameraService models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => CameraService::getAllQuery(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single CameraService model.
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
     * Creates a new CameraService model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    // public function actionCreate()
    // {
    //     $model = new CameraService();
    //
    //     if ($model->load(Yii::$app->request->post()) && $model->save()) {
    //         return $this->redirect(['index']);
    //     }
    //
    //     return $this->render('create', [
    //         'model' => $model,
    //     ]);
    // }
    public function getServiceImage()
    {
      $modelImage = new Image(['camera_service_image_uploads_path'=>Yii::t('app','Camera Service')]);
      $modelImage->setScenario('single-image-upload-image-optional');
      return $modelImage;
    }
    public function actionCreate()
    {
        $model = new CameraService();
        $modelImage = $this->getServiceImage();
        $params = Yii::$app->request->post();
        $paramsOk = $params && $model->load($params);
        if($paramsOk){
          $cameraServiceOk = $model->validate();
          $imageOk = $modelImage->validate();
          if($paramsOk && $cameraServiceOk && $imageOk ){
            if($modelImage) {
              $images                       = UploadedFile::getInstanceByName('photo');
              $service_image_uploads_path  = Yii::$app->params['camera_service_image_uploads_path'];
              $modelImageSaveIds             = $modelImage->uploadAndSave($images,$service_image_uploads_path);
              if($modelImageSaveIds) {
                // print_r($modelImageSaveIds);exit;
                // foreach ($modelImageSaveIds as $modelImageSaveId) {
                $model->image_id = $modelImageSaveIds;
                // }
              }
            }
            $model->save(false);
            $session = Yii::$app->session;
            $session->set('showSuccess', '1');
            return $this->redirect(['index']);
          }
        }
        return $this->render('create', [
            'model' => $model,
            'modelImage' => $modelImage,
        ]);
    }
    /**
     * Updates an existing CameraService model.
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
        $model = $this->findModel($id);
        $modelImage = $this->getServiceImage();
        $imageId = $model->image_id;
        $sortOrder = $model->sort_order;
        $params = Yii::$app->request->post();
        $paramsOk = $params && $model->load($params);
        if($paramsOk){
          $imageOk = $modelImage->validate();
          if($paramsOk && $imageOk ){
            if($modelImage) {
              $images                       = UploadedFile::getInstanceByName('photo');
              $service_image_uploads_path  = Yii::$app->params['camera_service_image_uploads_path'];
              $modelImageSaveIds             = $modelImage->uploadAndSave($images,$service_image_uploads_path);
              if($modelImageSaveIds) {
                $model->image_id = $modelImageSaveIds;
              }
            }
              $model->update(false);
              $session = Yii::$app->session;
              $session->set('updateSuccess', '1');
              return $this->redirect(['index']);
            }
          }
        return $this->render('update', [
            'model' => $model,
            'modelImage' => $modelImage,
        ]);
    }
    public function actionCameraServiceDetails($id)
    {
        $model = $this->findModel($id);
        $modelImage = $this->getServiceImage();
        $imageId = $model->image_id;
        $sortOrder = $model->sort_order;
        $params = Yii::$app->request->post();
        $paramsOk = $params && $model->load($params);
        if($paramsOk){
          $sort = $params['CameraService']['sort_order'];
          // $lsgiAuthorizedSignatoriesOk = $model->validate();
          $imageOk = $modelImage->validate();
          if($paramsOk && $imageOk ){
            if($modelImage) {
              $images                       = UploadedFile::getInstanceByName('photo');
              $service_image_uploads_path  = Yii::$app->params['camera_service_image_uploads_path'];
              $modelImageSaveIds             = $modelImage->uploadAndSave($images,$service_image_uploads_path);
              if($modelImageSaveIds) {
                // print_r($modelImageSaveIds);exit;
                // foreach ($modelImageSaveIds as $modelImageSaveId) {
                $model->image_id = $modelImageSaveIds;
                // }
              }
            }
          // $lsgiAuthorizedSignatoriesOk = $model->validate();
          // $imageOk = $modelImage->validate();
          //     $imageOk = $modelImage->validate();
              $model->sort_order = $sort;
              $model->update(false);
              $session = Yii::$app->session;
              $session->set('updateSuccess', '1');
              return $this->redirect(['index']);
            }
          }
        return $this->render('update-info', [
            'model' => $model,
            'modelImage' => $modelImage,
        ]);
    }
    /**
     * Deletes an existing CameraService model.
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
     * Finds the CameraService model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return CameraService the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CameraService::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    public function actionDeleteCameraService($id)
    {
        $model = new CameraService;
        $model->deleteCameraService($id);
    }
}
