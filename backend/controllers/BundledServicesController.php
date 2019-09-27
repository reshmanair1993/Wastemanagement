<?php

namespace backend\controllers;

use Yii;
use backend\models\Service;
use backend\models\ServiceSearch;
use backend\models\WasteCollectionMethodService;
use backend\models\ServicePackageService;
use backend\models\WasteCollectionMethod;
use backend\models\ServicingStatusOptionSearch;
use backend\models\ServicingStatusOption;
use backend\models\ServiceEnablerSettings;
use backend\models\ServiceEnablerSettingsSearch;
use backend\components\AccessPermission;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use backend\models\Image;
use yii\web\UploadedFile;

/**
 * ServicesController implements the CRUD actions for Service model.
 */
class BundledServicesController extends Controller
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
                         'permissions' => ['bundled-services-index']
                     ],
                     [
                         'actions' => ['view'],
                         'allow'   => true,
                         'permissions' => ['bundled-services-view']
                     ],
                     [
                         'actions' => ['create'],
                         'allow'   => true,
                         'permissions' => ['bundled-services-create']
                     ],
                     [
                         'actions' => ['update'],
                         'allow'   => true,
                         'permissions' => ['bundled-services-update']
                     ],
                     [
                         'actions' => ['delete'],
                         'allow'   => true,
                         'permissions' => ['bundled-services-delete']
                     ],
                     [
                         'actions' => ['delete-service'],
                         'allow'   => true,
                         'permissions' => ['bundled-services-delete-service']
                     ],
                     [
                         'actions' => ['view-service'],
                         'allow'   => true,
                         'permissions' => ['bundled-services-view-service']
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
     * Lists all Service models.
     * @return mixed
     */
    public function actionIndex()
    {
        $keyword      = null;
        if (isset($_POST['name']))
        {
            $keyword = $_POST['name'];
        }
        $type = 1;
        $is_package = 1;
        $searchModel = new ServiceSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,$keyword,$type,$is_package);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Service model.
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
     * Creates a new Service model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model                       = new Service();
        $modelImage = new Image;
        $modelServicePackageService = new ServicePackageService();
        $post = Yii::$app->request->post();
        if ($model->load(Yii::$app->request->post())&& $modelImage->load(Yii::$app->request->post())) {
              $modelImage->uploaded_files = UploadedFile::getInstance($modelImage, 'uploaded_files');
            $imageId                    = $modelImage->uploadAndSave();
            if ($imageId)
            {
                $model->image_id = $imageId;
            }

            // $list = $post['Service']['waste_collection_method']?$post['Service']['waste_collection_method']:'';
            // $model->waste_collection_method = serialize($list);
            $model->type = 1;
            $model->is_package = 1;
            $model->save();
            if($post['Service']['services']):
              $list = $post['Service']['services'];
            foreach ($list as  $value) {
                $modelServicePackageService = new ServicePackageService;
                $modelServicePackageService->service_id_service = $value;
                $modelServicePackageService->service_id = $model->id;
                $modelServicePackageService->save(false);
            }
            endif;

            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
            'modelImage' => $modelImage,
        ]);
    }

    /**
     * Updates an existing Service model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $modelImage = new Image;
        $model = $this->findModel($id);
        $post = Yii::$app->request->post();
        $services = ServicePackageService::find()->where(['service_id'=>$id])->all();
        if($services)
        {
          $servicesList = [];
          foreach ($services as $key => $value) {
            $servicesList[] = $value->service_id_service;
          }
          $model->services = $servicesList;
        }
        if ($model->load(Yii::$app->request->post())&& $modelImage->load(Yii::$app->request->post())) {
            $modelImage->uploaded_files = UploadedFile::getInstance($modelImage, 'uploaded_files');
            $imageId                    = $modelImage->uploadAndSave();
            if ($imageId)
            {
                $model->image_id = $imageId;
            }
            $model->save();
           if($post['Service']['services']):
            ServicePackageService::deleteAll(['service_id'=>$model->id]);
              $list = $post['Service']['services'];
              foreach ($list as  $value) {
                $modelServicePackageService = new ServicePackageService();
                $modelServicePackageService->service_id_service = $value;
                $modelServicePackageService->service_id = $model->id;
                $modelServicePackageService->save(false);
            }
            endif;
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
            'modelImage' => $modelImage,
        ]);
    }

    /**
     * Deletes an existing Service model.
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
     * Finds the Service model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Service the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Service::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
    public function actionDeleteService($id)
    {
        $model = new Service;
        $model->deleteService($id);
    }

     public function actionViewService($id)
    {
      $model = $this->findModel($id);
       $services = ServicePackageService::find()->where(['service_id'=>$id])->all();
        if($services)
        {
          $servicesList = [];
          foreach ($services as $key => $value) {
            $servicesList[] = $value->service_id_service;
          }
          $model->services = $servicesList;
        }
      $modelImage = new Image;
      $dataProvider = $searchModel->search(Yii::$app->request->queryParams,$id);

      $modelServiceEnablerSettings = new ServiceEnablerSettings;
      $serviceEnablerSettings = new ServiceEnablerSettingsSearch;
      $serviceEnablerSettingsDataProvider = $serviceEnablerSettings->search(Yii::$app->request->queryParams,$id);
      $params = [
        'model' => $model,
        'modelServicingStatusOption' => $modelServicingStatusOption,
        'modelImage' => $modelImage,
        'searchModel' => $searchModel,
        'dataProvider'=> $dataProvider,
        'modelServiceEnablerSettings' => $modelServiceEnablerSettings,
        'serviceEnablerSettings' => $serviceEnablerSettings,
        'serviceEnablerSettingsDataProvider'=> $serviceEnablerSettingsDataProvider
      ];
      return $this->render('view', [
            'params' => $params,
        ]);
    }

}
