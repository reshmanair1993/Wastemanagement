<?php

namespace backend\controllers;

use Yii;
use backend\models\Service;
use backend\models\ServiceSearch;
use backend\models\WasteCollectionMethodService;
use backend\models\WasteCollectionMethod;
use backend\models\ServicingStatusOptionSearch;
use backend\models\ServicingStatusOption;
use backend\models\ServiceEnablerSettings;
use backend\models\ServiceEnablerSettingsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use backend\models\Image;
use yii\web\UploadedFile;
use backend\components\AccessPermission;

/**
 * ServicesController implements the CRUD actions for Service model.
 */
class ServicesController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
           'access' => [
               'class' => AccessControl::className(),
               'only' => ['index','create','update','view'],
               'ruleConfig' => [
                       'class' => AccessPermission::className(),
                   ],
               'rules' => [
                   [
                       'actions' => ['index'],
                       'allow' => true,
                       'permissions' => ['services-index'],
                   ],
                   [
                       'actions' => ['create'],
                       'allow' => true,
                       'permissions' => ['services-create'],
                   ],
                   [
                       'actions' => ['update'],
                       'allow' => true,
                       'permissions' => ['services-update'],
                   ],
                   [
                       'actions' => ['delete'],
                       'allow' => true,
                       'permissions' => ['services-delete'],
                   ],
                   [
                       'actions' => ['view'],
                       'allow' => true,
                       'permissions' => ['services-view'],
                   ],
                   [
                       'actions' => ['delete-service'],
                       'allow' => true,
                       'permissions' => ['services-delete-service'],
                   ],
                   [
                       'actions' => ['view-service'],
                       'allow' => true,
                       'permissions' => ['services-view-service'],
                   ],
                   [
                       'actions' => ['add-status-options'],
                       'allow' => true,
                       'permissions' => ['services-add-status-options'],
                   ],
                   [
                       'actions' => ['delete-status-options'],
                       'allow' => true,
                       'permissions' => ['services-delete-status-options'],
                   ],
                   [
                       'actions' => ['delete-service-enabler'],
                       'allow' => true,
                       'permissions' => ['services-delete-service-enabler'],
                   ],
                   [
                       'actions' => ['add-service-enabler'],
                       'allow' => true,
                       'permissions' => ['services-add-service-enabler'],
                   ],
               ],
               'denyCallback' => function($rule, $action) {
                   return $this->goHome();
               }
           ],
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
        $searchModel = new ServiceSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,$keyword,$type);

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
        $modelWasteCollectionMethodService = new WasteCollectionMethodService();
        $post = Yii::$app->request->post();
        if ($model->load(Yii::$app->request->post())&& $modelImage->load(Yii::$app->request->post())) {
              $modelImage->uploaded_files = UploadedFile::getInstance($modelImage, 'uploaded_files');
            $imageId                    = $modelImage->uploadAndSave();
            if ($imageId)
            {
                $model->image_id = $imageId;
            }

            $list = $post['Service']['waste_collection_method']?$post['Service']['waste_collection_method']:'';
            $model->waste_collection_method = serialize($list);
            $model->type = 1;
            $model->save();
            if($post['Service']['waste_collection_method']):
            foreach ($list as  $value) {
                $modelWasteCollectionMethodService = new WasteCollectionMethodService();
                $modelWasteCollectionMethodService->waste_collection_method_id = $value;
                $modelWasteCollectionMethodService->service_id = $model->id;
                $modelWasteCollectionMethodService->save(false);
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
        $model->waste_collection_method = unserialize($model->waste_collection_method);
        if ($model->load(Yii::$app->request->post())&& $modelImage->load(Yii::$app->request->post())) {
            $modelImage->uploaded_files = UploadedFile::getInstance($modelImage, 'uploaded_files');
            $imageId                    = $modelImage->uploadAndSave();
            if ($imageId)
            {
                $model->image_id = $imageId;
            }
             $list = $model->waste_collection_method;
            $model->waste_collection_method = serialize($model->waste_collection_method);
            $model->save();
            if($list):
            WasteCollectionMethodService::deleteAll(['service_id'=>$model->id]);
            foreach ($list as  $value) {
                $modelWasteCollectionMethodService = new WasteCollectionMethodService();
                $modelWasteCollectionMethodService->waste_collection_method_id = $value;
                $modelWasteCollectionMethodService->service_id = $model->id;
                $modelWasteCollectionMethodService->save(false);
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
    protected function findStatusOptionModel($id)
    {
        if (($model = ServicingStatusOption::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
    public function actionDeleteService($id)
    {
        $model = new Service;
        $model->deleteService($id);
    }
    public function actionWasteCategoryTypeAjax(){
        
        $arr = [];
        if(Yii::$app->request->isAjax):
            $data = Yii::$app->request->Post();
            $cat = $data['cat'];//echo $cat;exit;
            $arr = \yii\helpers\ArrayHelper::map(WasteCollectionMethod::find()->where(['status'=>1])->andWhere(['waste_category_id'=>$cat])->all(), 'id', 'name');
            
        endif;
        yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $arr;
    }
     public function actionViewService($id)
    {
      $success = false;
      $model = $this->findModel($id);
      $model->waste_collection_method = unserialize($model->waste_collection_method);
      $modelServicingStatusOption = new ServicingStatusOption;
      $searchModel = new ServicingStatusOptionSearch;
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
        'success'=> $success,
        'modelServiceEnablerSettings' => $modelServiceEnablerSettings,
        'serviceEnablerSettings' => $serviceEnablerSettings,
        'serviceEnablerSettingsDataProvider'=> $serviceEnablerSettingsDataProvider
      ];
      return $this->render('view', [
            'params' => $params,
        ]);
    }
    public function actionAddStatusOptions($id)
    {
      $model = $this->findModel($id);
      $success = false;
      $modelImage = new Image;
      $modelServicingStatusOption = new ServicingStatusOption;
      $searchModel = new ServicingStatusOptionSearch;
      $dataProvider = $searchModel->search(Yii::$app->request->queryParams,$id);
      $modelServiceEnablerSettings = new ServiceEnablerSettings;
      $serviceEnablerSettings = new ServiceEnablerSettingsSearch;
      $serviceEnablerSettingsDataProvider = $serviceEnablerSettings->search(Yii::$app->request->queryParams,$id);
        if ($modelServicingStatusOption->load(Yii::$app->request->post())&& $modelImage->load(Yii::$app->request->post())) {
            $modelImage->uploaded_files = UploadedFile::getInstance($modelImage, 'uploaded_files');
            $imageId                    = $modelImage->uploadAndSave();
            if ($imageId)
            {
                $modelServicingStatusOption->image_id = $imageId;
            }
            $modelServicingStatusOption->service_id = $id;
            $modelServicingStatusOption->save();
            $success = true;
        }
              $modelServicingStatusOption = new ServicingStatusOption;
         $params = [
        'model' => $model,
        'modelServicingStatusOption' => $modelServicingStatusOption,
        'modelImage' => $modelImage,
        'searchModel' => $searchModel,
        'dataProvider'=> $dataProvider,
        'success'=> $success,
        'modelServiceEnablerSettings' => $modelServiceEnablerSettings,
        'serviceEnablerSettings' => $serviceEnablerSettings,
        'serviceEnablerSettingsDataProvider'=> $serviceEnablerSettingsDataProvider
      ];
      return $this->render('view', [
            'params' => $params,
        ]);
    }
    public function actionDeleteStatusOptions($id)
    {
        $model = new ServicingStatusOption;
        $model->deleteServicingStatusOption($id);
    }
    public function actionDeleteServiceEnabler($id)
    {
        $model = new ServiceEnablerSettings;
        $model->deleteServiceEnabler($id);
    }
     public function actionDetailsAjax(){
        
        $arr = [];
        if(Yii::$app->request->isAjax):
            $data = Yii::$app->request->Post();
            $question = $data['cat'];
            $answers = [];
            $answers['has_bio_waste'] = [1=>'Yes',0=>'No'];
            $answers['has_non_bio_waste'] = [1=>'Yes',0=>'No'];
            $answers['has_disposible_waste'] = [1=>'Yes',0=>'No'];
            $answers['has_non_bio_waste_management_facility'] = [1=>'Yes',0=>'No'];
            $answers['bio_waste_management_facility_operational'] = [1=>'Yes',0=>'No'];
            $answers['bio_waste_management_facility_repair_help_needed'] = [1=>'Yes',0=>'No'];
            $answers['bio_waste_collection_needed'] = [1=>'Yes',0=>'No'];
            $answers['has_terrace_farming_interest'] = [1=>'Yes',0=>'No'];
            $answers['daily_collection_needed_bio'] = [1=>'Yes',0=>'No'];
            $answers['space_available_for_bio_waste_management_facility'] = [1=>'Yes',0=>'No'];
            $answers['space_available_for_non_bio_waste_management_facility'] = [1=>'Yes',0=>'No'];
            $answers['help_needed_for_bio_waste_management_facility_construction'] = [1=>'Yes',0=>'No'];
            $answers['has_space_for_non_bio_waste_management_facility'] = [1=>'Yes',0=>'No'];
            $answers['has_interest_for_allotting_space_for_non_bio_management_facility'] = [1=>'Yes',0=>'No'];
            $answers['has_interest_in_bio_waste_management_facility'] = [1=>'Yes',0=>'No'];
            $answers['green_protocol_system_implemented'] = [1=>'Yes',0=>'No'];
            $answers['bio_medical_waste_collection_facility'] = [1=>'Yes',0=>'No'];
            $answers['has_bio_medical_incinerator'] = [1=>'Yes',0=>'No'];
            $answers['has_interest_in_system_provided_bio_facility'] = [1=>'Yes',0=>'No'];


            $answers['bio_waste_collection_method_id'] = WasteCollectionMethod::getBioMethod();
            $answers['non_bio_waste_collection_method_id'] = WasteCollectionMethod::getNonBioMethods();
            $answers['bio_medical_waste_collection_method'] = WasteCollectionMethod::getBioMedicalMethod();

        endif;
        yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $answers[$question];
    }
    public function actionAddServiceEnabler($id)
    {
      $success = false;
      $model = $this->findModel($id);
      $modelImage = new Image;
      $modelServicingStatusOption = new ServicingStatusOption;
      $searchModel = new ServicingStatusOptionSearch;
      $dataProvider = $searchModel->search(Yii::$app->request->queryParams,$id);
      $modelServiceEnablerSettings = new ServiceEnablerSettings;
      $serviceEnablerSettings = new ServiceEnablerSettingsSearch;
      $serviceEnablerSettingsDataProvider = $serviceEnablerSettings->search(Yii::$app->request->queryParams,$id);

        if ($modelServiceEnablerSettings->load(Yii::$app->request->post())){
            $modelServiceEnablerSettings->service_id = $id;
            $modelServiceEnablerSettings->save(false);
        }
              $modelServicingStatusOption = new ServicingStatusOption;
              $modelServiceEnablerSettings = new ServiceEnablerSettings;
         $params = [
        'model' => $model,
        'modelServicingStatusOption' => $modelServicingStatusOption,
        'modelImage' => $modelImage,
        'searchModel' => $searchModel,
        'success'=> $success,
        'dataProvider'=> $dataProvider,
        'modelServiceEnablerSettings' => $modelServiceEnablerSettings,
        'serviceEnablerSettingsDataProvider' => $serviceEnablerSettingsDataProvider,
        'serviceEnablerSettings' => $serviceEnablerSettings,
      ];
      return $this->render('view', [
            'params' => $params,
        ]);
    }
     public function actionUpdateStatusOption($id)
    {
      $success = false;
      $modelServicingStatusOption = $this->findStatusOptionModel($id);
      $model = $this->findModel($id);
      $modelImage = new Image;
      // $modelServicingStatusOption = new ServicingStatusOption;
      $searchModel = new ServicingStatusOptionSearch;
      $dataProvider = $searchModel->search(Yii::$app->request->queryParams,$modelServicingStatusOption->service_id);
      $modelServiceEnablerSettings = new ServiceEnablerSettings;
      $serviceEnablerSettings = new ServiceEnablerSettingsSearch;
      $serviceEnablerSettingsDataProvider = $serviceEnablerSettings->search(Yii::$app->request->queryParams,$id);
        if ($modelServicingStatusOption->load(Yii::$app->request->post())&& $modelImage->load(Yii::$app->request->post())) {
            $modelImage->uploaded_files = UploadedFile::getInstance($modelImage, 'uploaded_files');
            $imageId                    = $modelImage->uploadAndSave();
            if ($imageId)
            {
                $modelServicingStatusOption->image_id = $imageId;
            }
            $modelServicingStatusOption->save();
        }
              $modelServicingStatusOption = new ServicingStatusOption;
         $params = [
        'model' => $model,
        'modelServicingStatusOption' => $modelServicingStatusOption,
        'modelImage' => $modelImage,
        'searchModel' => $searchModel,
        'dataProvider'=> $dataProvider,
        'success'=> $success,
        'modelServiceEnablerSettings' => $modelServiceEnablerSettings,
        'serviceEnablerSettings' => $serviceEnablerSettings,
        'serviceEnablerSettingsDataProvider'=> $serviceEnablerSettingsDataProvider
      ];
      return $this->render('view', [
            'params' => $params,
        ]);
    }
}
