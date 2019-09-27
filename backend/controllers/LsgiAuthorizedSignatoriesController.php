<?php

namespace backend\controllers;

use Yii;
use backend\models\LsgiAuthorizedSignatory;
use backend\models\Lsgi;
use backend\models\Image;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use backend\components\AccessPermission;
use yii\filters\AccessControl;

/**
 * LsgiAuthorizedSignatoriesController implements the CRUD actions for LsgiAuthorizedSignatory model.
 */
class LsgiAuthorizedSignatoriesController extends Controller
{
    /**
     * {@inheritdoc}
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
                         'permissions' => ['lsgi-authorized-signatories-index']
                     ],
                     [
                         'actions' => ['view'],
                         'allow'   => true,
                         'permissions' => ['lsgi-authorized-signatories-view']
                     ],
                     [
                         'actions' => ['create'],
                         'allow'   => true,
                         'permissions' => ['lsgi-authorized-signatories-create']
                     ],
                     [
                         'actions' => ['update'],
                         'allow'   => true,
                         'permissions' => ['lsgi-authorized-signatories-update']
                     ],
                     [
                         'actions' => ['delete'],
                         'allow'   => true,
                         'permissions' => ['lsgi-authorized-signatories-delete']
                     ],
                     [
                         'actions' => ['delete-lsgi-athorized-signatory'],
                         'allow'   => true,
                         'permissions' => ['lsgi-authorized-signatories-delete-lsgi-athorized-signatory']
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
     * Lists all LsgiAuthorizedSignatory models.
     * @return mixed
     */
    public function actionIndex()
    {
        $modelUser = Yii::$app->user->identity;
        $userRole  = $modelUser->role;

        $showSuccess = isset($_SESSION['showSuccess']) ? $_SESSION['showSuccess'] : null;
        if(isset($_SESSION['showSuccess']))
          unset($_SESSION['showSuccess']);
        $updateSuccess = isset($_SESSION['updateSuccess']) ? $_SESSION['updateSuccess'] : null;
        if(isset($_SESSION['updateSuccess']))
          unset($_SESSION['updateSuccess']);
            if ($userRole == 'admin-lsgi'){
              $dataProvider = new ActiveDataProvider([
                  'query' => LsgiAuthorizedSignatory::getAllQuery()->where(['lsgi_id'=> $modelUser->lsgi_id,'status'=>1]),
              ]);
            }
            else {
              $dataProvider = new ActiveDataProvider([
                  'query' => LsgiAuthorizedSignatory::getAllQuery(),
              ]);
            }


        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'showSuccess'=>$showSuccess,
            'updateSuccess'=>$updateSuccess,
        ]);
    }

    /**
     * Displays a single LsgiAuthorizedSignatory model.
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
     * Creates a new LsgiAuthorizedSignatory model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $modelUser = Yii::$app->user->identity;
        $userRole  = $modelUser->role;

        $model = new LsgiAuthorizedSignatory();
        $modelImage = $this->getSignatureImage();
        $modelLsgi = Lsgi::find()->where(['status' => 1])->all();
        $params = Yii::$app->request->post();
        $paramsOk = $params && $model->load($params);
        if($paramsOk){
          $lsgiAuthorizedSignatoriesOk = $model->validate();
          $imageOk = $modelImage->validate();
          if($paramsOk && $lsgiAuthorizedSignatoriesOk && $imageOk ){
            if($modelImage) {
              $images                       = UploadedFile::getInstanceByName('photo');
              $signature_image_uploads_path  = Yii::$app->params['signature_image_uploads_path'];
              $modelImageSaveIds             = $modelImage->uploadAndSave($images,$signature_image_uploads_path);
              if($modelImageSaveIds) {
                // print_r($modelImageSaveIds);exit;
                // foreach ($modelImageSaveIds as $modelImageSaveId) {
                $model->image_id_signature = $modelImageSaveIds;
                // }
              }
            }
            if ($userRole == 'admin-lsgi'){
              $model->lsgi_id = $modelUser->lsgi_id;
            }
            $model->save(false);
            $session = Yii::$app->session;
            $session->set('showSuccess', '1');
            return $this->redirect(['index']);
          }
        }
        return $this->render('create', [
            'model' => $model,
            'modelLsgi' => $modelLsgi,
            'modelImage' => $modelImage,
        ]);
    }
    public function getSignatureImage()
    {
      $modelImage = new Image(['signature_image_uploads_path'=>Yii::t('app','Signature')]);
      // $modelImage->setScenario('signature-image-upload');
      return $modelImage;
    }

    /**
     * Updates an existing LsgiAuthorizedSignatory model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $modelUser = Yii::$app->user->identity;
        $userRole  = $modelUser->role;

        $model = $this->findModel($id);
        $modelImage = $this->getSignatureImage();
        $modelLsgi = Lsgi::find()->where(['status' => 1])->all();
        $imageId = $model->image_id_signature;
        $params = Yii::$app->request->post();
        $paramsOk = $params && $model->load($params);
        if($paramsOk){
          // $lsgiAuthorizedSignatoriesOk = $model->validate();
          $imageOk = $modelImage->validate();
          if($paramsOk && $imageOk ){
            if($modelImage) {
              $images                       = UploadedFile::getInstanceByName('photo');
              $signature_image_uploads_path  = Yii::$app->params['signature_image_uploads_path'];
              $modelImageSaveIds             = $modelImage->uploadAndSave($images,$signature_image_uploads_path);
              if($modelImageSaveIds) {
                $model->image_id_signature = $modelImageSaveIds;
              }
            }
            if ($userRole == 'admin-lsgi'){
              $model->lsgi_id = $modelUser->lsgi_id;
            }
          // $lsgiAuthorizedSignatoriesOk = $model->validate();
          // $imageOk = $modelImage->validate();
          //     $imageOk = $modelImage->validate();
              $model->update(false);
              $session = Yii::$app->session;
              $session->set('updateSuccess', '1');
              return $this->redirect(['index']);
            }
          }
        return $this->render('update', [
            'model' => $model,
            'modelLsgi' => $modelLsgi,
            'modelImage' => $modelImage,
        ]);
    }

    /**
     * Deletes an existing LsgiAuthorizedSignatory model.
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
     * Finds the LsgiAuthorizedSignatory model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return LsgiAuthorizedSignatory the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = LsgiAuthorizedSignatory::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    public function actionDeleteLsgiAuthorizedSignatory($id)
    {
        $model = new LsgiAuthorizedSignatory;
        $model->deleteLsgiAuthorizedSignatory($id);
    }
}
