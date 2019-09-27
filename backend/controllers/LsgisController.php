<?php

namespace backend\controllers;

use Yii;
use backend\models\Lsgi;
use backend\models\LsgiSearch;
use backend\models\LsgiServiceFee;
use backend\models\LsgiType;
use backend\models\LsgiBlock;
use backend\models\Ward;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use backend\models\Image;
use backend\models\Schedule;
use backend\models\EvaluationConfigCustomerRating;
use backend\models\EvaluationConfigComplaintsCount;
use backend\models\EvaluationConfigCompletionTime;
use backend\models\EvaluationConfigWasteQuality;
use backend\models\EvaluationConfigCompletionPercentage;
use backend\models\EvaluationConfigComplaintsResolution;
use backend\models\ScheduleWard;
use backend\models\EscalationSettings;
use backend\models\LsgiServiceSlabFee;
use backend\components\AccessPermission;
use yii\web\UploadedFile;
/**
 * LsgisController implements the CRUD actions for Lsgi model.
 */
class LsgisController extends Controller
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
                       'permissions' => ['lsgis-index'],
                   ],
                   [
                       'actions' => ['create'],
                       'allow' => true,
                       'permissions' => ['lsgis-create'],
                   ],
                   [
                       'actions' => ['update'],
                       'allow' => true,
                       'permissions' => ['lsgis-update'],
                   ],
                   [
                       'actions' => ['delete'],
                       'allow' => true,
                       'permissions' => ['lsgis-delete'],
                   ],
                   [
                       'actions' => ['view'],
                       'allow' => true,
                       'permissions' => ['lsgis-view'],
                   ],
                   [
                       'actions' => ['add-lsgi-ward'],
                       'allow' => true,
                       'permissions' => ['lsgis-add-lsgi-ward'],
                   ],
                   [
                       'actions' => ['add-lsgi-service-fee'],
                       'allow' => true,
                       'permissions' => ['lsgis-add-lsgi-service-fee'],
                   ],
                   [
                       'actions' => ['delete-lsgi'],
                       'allow' => true,
                       'permissions' => ['lsgis-delete-lsgi'],
                   ],
                   [
                       'actions' => ['delete-fee'],
                       'allow' => true,
                       'permissions' => ['lsgis-delete-fee'],
                   ],
                   [
                       'actions' => ['lsgi'],
                       'allow' => true,
                       'permissions' => ['lsgis-lsgi'],
                   ],
                   [
                       'actions' => ['add-lsgi-schedule'],
                       'allow' => true,
                       'permissions' => ['lsgis-add-lsgi-schedule'],
                   ],
               ],
               'denyCallback' => function($rule, $action) {
                   return $this->goHome();
               }
           ],
       ];
    }

    /**
     * Lists all Lsgi models.
     * @return mixed
     */

    public function actionIndex()
    {
        $searchModel = new LsgiSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		$lsgiTypes = LsgiType::getAllQuery()->all();
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
			'types' => $lsgiTypes
        ]);
    }

    /**
     * Displays a single Lsgi model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $modelWard = new Ward;
        $dataProvider = new ActiveDataProvider(
        [
           'query' => Ward::getAllQuery()->andWhere(['lsgi_id' => $id,'status' => '1']),
           'pagination' => false,
           'sort'=> ['defaultOrder' => ['id' => SORT_DESC]]
        ]);

        $modelLsgiServiceFee = new LsgiServiceFee;
        $lsgiServiceFeeDataProvider = new ActiveDataProvider(
        [
           'query' => $modelLsgiServiceFee->getAllQuery()->andWhere(['lsgi_id' => $id,'status' => '1']),
           'pagination' => false,
           'sort'=> ['defaultOrder' => ['id' => SORT_DESC]]
        ]);
        $modelSchedule = new Schedule;
        $scheduleDataProvider = new ActiveDataProvider(
        [
           'query' => $modelSchedule->getAllQuery()->andWhere(['lsgi_id' => $id,'status' => '1']),
           'pagination' => false,
           'sort'=> ['defaultOrder' => ['id' => SORT_DESC]]
        ]);

        $modelEscalationSettings = new EscalationSettings;
        $escalationSettingsDataProvider = new ActiveDataProvider(
        [
           'query' => $modelEscalationSettings->getAllQuery()->andWhere(['lsgi_id' => $id,'status' => '1']),
           'pagination' => false,
           'sort'=> ['defaultOrder' => ['id' => SORT_DESC]]
        ]);
        $modelLsgiServiceSlabFee = new LsgiServiceSlabFee;
        $lsgiServiceFeeSlabDataProvider = new ActiveDataProvider(
        [
           'query' => $modelLsgiServiceSlabFee->getAllQuery()->andWhere(['lsgi_id' => $id,'status' => '1']),
           'pagination' => false,
           'sort'=> ['defaultOrder' => ['id' => SORT_DESC]]
        ]);
        $params = [
            'model'  =>$model,
            'modelWard'  =>$modelWard,
            'modelLsgiServiceFee'  =>$modelLsgiServiceFee,
            'modelSchedule'  =>$modelSchedule,
            'dataProvider'  =>$dataProvider,
            'lsgiServiceFeeDataProvider'  =>$lsgiServiceFeeDataProvider,
            'scheduleDataProvider'  =>$scheduleDataProvider,
            'modelEscalationSettings'  =>$modelEscalationSettings,
            'escalationSettingsDataProvider'  =>$escalationSettingsDataProvider,
            'modelLsgiServiceSlabFee'  =>$modelLsgiServiceSlabFee,
            'lsgiServiceFeeSlabDataProvider'  =>$lsgiServiceFeeSlabDataProvider,
        ];

        return $this->render('view', [
            'params' => $params,
        ]);
    }
    public function actionAddLsgiWard($id)
    {
        $model = $this->findModel($id);
      $modelWard = new Ward;
     $dataProvider = new ActiveDataProvider(
        [
           'query' => Ward::getAllQuery()->andWhere(['lsgi_id' => $id,'status' => '1']),
           'pagination' => false,
           'sort'=> ['defaultOrder' => ['id' => SORT_DESC]]
        ]);
       $modelLsgiServiceFee = new LsgiServiceFee;
        $lsgiServiceFeeDataProvider = new ActiveDataProvider(
        [
           'query' => $modelLsgiServiceFee->getAllQuery()->andWhere(['lsgi_id' => $id,'status' => '1']),
           'pagination' => false,
           'sort'=> ['defaultOrder' => ['id' => SORT_DESC]]
        ]);
         $modelSchedule = new Schedule;
        $scheduleDataProvider = new ActiveDataProvider(
        [
           'query' => $modelSchedule->getAllQuery()->andWhere(['lsgi_id' => $id,'status' => '1']),
           'pagination' => false,
           'sort'=> ['defaultOrder' => ['id' => SORT_DESC]]
        ]);
        $modelEscalationSettings = new EscalationSettings;
        $escalationSettingsDataProvider = new ActiveDataProvider(
        [
           'query' => $modelEscalationSettings->getAllQuery()->andWhere(['lsgi_id' => $id,'status' => '1']),
           'pagination' => false,
           'sort'=> ['defaultOrder' => ['id' => SORT_DESC]]
        ]);
        $modelLsgiServiceSlabFee = new LsgiServiceSlabFee;
        $lsgiServiceFeeSlabDataProvider = new ActiveDataProvider(
        [
           'query' => $modelLsgiServiceSlabFee->getAllQuery()->andWhere(['lsgi_id' => $id,'status' => '1']),
           'pagination' => false,
           'sort'=> ['defaultOrder' => ['id' => SORT_DESC]]
        ]);
        if ($modelWard->load(Yii::$app->request->post())) {
            $modelWard->lsgi_id = $id;
            $modelWard->save();
        }
$modelWard = new Ward;
         $params = [
            'model'  =>$model,
            'modelWard'  =>$modelWard,
            'modelLsgiServiceFee'  =>$modelLsgiServiceFee,
            'modelSchedule'  =>$modelSchedule,
            'scheduleDataProvider'  =>$scheduleDataProvider,
            'dataProvider'  =>$dataProvider,
            'lsgiServiceFeeDataProvider'  =>$lsgiServiceFeeDataProvider,
            'modelEscalationSettings'  =>$modelEscalationSettings,
            'escalationSettingsDataProvider'  =>$escalationSettingsDataProvider,
            'modelLsgiServiceSlabFee'  =>$modelLsgiServiceSlabFee,
            'lsgiServiceFeeSlabDataProvider'  =>$lsgiServiceFeeSlabDataProvider,
        ];
      return $this->render('view', [
            'params' => $params,
        ]);
    }
    public function actionAddLsgiServiceFee($id)
    {
        $model = $this->findModel($id);
     $modelWard = new Ward;
        $dataProvider = new ActiveDataProvider(
        [
           'query' => Ward::getAllQuery()->andWhere(['lsgi_id' => $id,'status' => '1']),
           'pagination' => false,
           'sort'=> ['defaultOrder' => ['id' => SORT_DESC]]
        ]);

        $modelLsgiServiceFee = new LsgiServiceFee;
        $lsgiServiceFeeDataProvider = new ActiveDataProvider(
        [
           'query' => $modelLsgiServiceFee->getAllQuery()->andWhere(['lsgi_id' => $id,'status' => '1']),
           'pagination' => false,
           'sort'=> ['defaultOrder' => ['id' => SORT_DESC]]
        ]);
        $modelSchedule = new Schedule;
        $scheduleDataProvider = new ActiveDataProvider(
        [
           'query' => $modelSchedule->getAllQuery()->andWhere(['lsgi_id' => $id,'status' => '1']),
           'pagination' => false,
           'sort'=> ['defaultOrder' => ['id' => SORT_DESC]]
        ]);
        $modelEscalationSettings = new EscalationSettings;
        $escalationSettingsDataProvider = new ActiveDataProvider(
        [
           'query' => $modelEscalationSettings->getAllQuery()->andWhere(['lsgi_id' => $id,'status' => '1']),
           'pagination' => false,
           'sort'=> ['defaultOrder' => ['id' => SORT_DESC]]
        ]);
        $modelLsgiServiceSlabFee = new LsgiServiceSlabFee;
        $lsgiServiceFeeSlabDataProvider = new ActiveDataProvider(
        [
           'query' => $modelLsgiServiceSlabFee->getAllQuery()->andWhere(['lsgi_id' => $id,'status' => '1']),
           'pagination' => false,
           'sort'=> ['defaultOrder' => ['id' => SORT_DESC]]
        ]);
        if ($modelLsgiServiceFee->load(Yii::$app->request->post())) {
            $modelLsgiServiceFee->lsgi_id = $id;
            $modelLsgiServiceFee->save();
        }
        $modelWard = new Ward;
$modelLsgiServiceFee = new LsgiServiceFee;
         $params = [
            'model'  =>$model,
            'modelWard'  =>$modelWard,
            'modelLsgiServiceFee'  =>$modelLsgiServiceFee,
            'modelSchedule'  =>$modelSchedule,
            'scheduleDataProvider'  =>$scheduleDataProvider,
            'dataProvider'  =>$dataProvider,
            'lsgiServiceFeeDataProvider'  =>$lsgiServiceFeeDataProvider,
            'modelEscalationSettings'  =>$modelEscalationSettings,
            'escalationSettingsDataProvider'  =>$escalationSettingsDataProvider,
            'modelLsgiServiceSlabFee'  =>$modelLsgiServiceSlabFee,
            'lsgiServiceFeeSlabDataProvider'  =>$lsgiServiceFeeSlabDataProvider,
        ];
      return $this->render('view', [
            'params' => $params,
        ]);
    }

    /**
     * Creates a new Lsgi model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
     public function getLogoImage()
     {
       $modelImage = new Image(['logo_image_uploads_path'=>Yii::t('app','Logo')]);
       $modelImage->setScenario('single-image-upload-image-optional');
       return $modelImage;
     }
    public function actionCreate()
    {
        $model = new Lsgi();
        $modelImage = $this->getLogoImage();

        if ($model->load(Yii::$app->request->post()) && $modelImage->load(Yii::$app->request->post())) {

              $images                       = UploadedFile::getInstanceByName('uploaded_files');
              $images1                       = UploadedFile::getInstanceByName('uploaded_files1');
              $images2                       = UploadedFile::getInstanceByName('uploaded_files2');
              $logo_image_uploads_path  = Yii::$app->params['logo_image_base_urls'];
              $imageId             = $modelImage->uploadAndSave($images,$logo_image_uploads_path);
              $imageId1             = $modelImage->uploadAndSave($images1,$logo_image_uploads_path);
              $imageId2             = $modelImage->uploadAndSave($images2,$logo_image_uploads_path);
             // $modelImage->uploaded_files = UploadedFile::getInstance($modelImage, 'uploaded_files');
            // $imageId                    = $modelImage->uploadAndSave();
            if ($imageId)
            {
                $model->image_id = $imageId;
            }
            if ($imageId1)
            {
                $model->header_image_id = $imageId1;
            }
            if ($imageId2)
            {
                $model->footer_image_id = $imageId2;
            }
            $model->save(false);

            return $this->redirect(['index']);
        }
		$lsgiTypes = LsgiType::getAllQuery()->all();
        return $this->render('create', [
            'model' => $model,
			'types' => $lsgiTypes
        ]);
    }

    /**
     * Updates an existing Lsgi model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        // $modelImage = new Image;
        $modelImage = $this->getLogoImage();
        $block = $model->block_id;
        $post = Yii::$app->request->post();
        if ($model->load(Yii::$app->request->post())&& $modelImage->load(Yii::$app->request->post())) {
          $images                            = UploadedFile::getInstance($modelImage, 'uploaded_files');
              $images1                       = UploadedFile::getInstance($modelImage, 'uploaded_files1');
              $images2                       = UploadedFile::getInstance($modelImage, 'uploaded_files2');
          $logo_image_uploads_path  = Yii::$app->params['logo_image_base_urls'];
          $imageId             = $modelImage->uploadAndSave($images,$logo_image_uploads_path);
          $imageId1             = $modelImage->uploadAndSave($images1,$logo_image_uploads_path);
              $imageId2             = $modelImage->uploadAndSave($images2,$logo_image_uploads_path);
            // $modelImage->uploaded_files = UploadedFile::getInstance($modelImage, 'uploaded_files');
            // $imageId                    = $modelImage->uploadAndSave();
            if ($imageId)
            {
                $model->image_id = $imageId;
            }
            if ($imageId1)
            {
                $model->header_image_id = $imageId1;
            }
            if ($imageId2)
            {
                $model->footer_image_id = $imageId2;
            }
            if(!$model->block_id)
            {
                $model->block_id = $block;
            }
            $model->save(false);
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Lsgi model.
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
     * Finds the Lsgi model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Lsgi the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Lsgi::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
    protected function findSlabModel($id)
    {
        if (($model = LsgiServiceSlabFee::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
    public function actionDeleteLsgi($id)
    {
        $model = new Lsgi;
        $model->deleteLsgi($id);
    }
    public function actionDeleteFee($id)
    {
        $model = new LsgiServiceFee;
        $model->deleteFee($id);
    }
    public function actionDeleteSlabFee($id)
    {
        $model = new LsgiServiceSlabFee;
        $model->deleteFee($id);
    }
    public function actionLsgi() {
       $out = [];
        if (isset($_POST['depdrop_parents'])) {
        $parents = $_POST['depdrop_parents'];
        $block = LsgiBlock::find()->where(['id'=>$parents[0]])->andWhere(['status'=>1])->one();
        $lsgi= Lsgi::find()->where(['block_id'=>$block['id']])->andWhere(['status'=>1])->all();

        foreach ($lsgi as $id => $post) {
            $out[] = ['id' => $post['id'], 'name' => $post['name']];
 }
 echo Json::encode(['output' => $out, 'selected' => '']);
    }
  }
   public function actionAddLsgiSchedule($id)
    {
     $model = $this->findModel($id);
    $modelWard = new Ward;
     $dataProvider = new ActiveDataProvider(
        [
           'query' => Ward::getAllQuery()->andWhere(['lsgi_id' => $id,'status' => '1']),
           'pagination' => false,
           'sort'=> ['defaultOrder' => ['id' => SORT_DESC]]
        ]);
       $modelLsgiServiceFee = new LsgiServiceFee;
        $lsgiServiceFeeDataProvider = new ActiveDataProvider(
        [
           'query' => $modelLsgiServiceFee->getAllQuery()->andWhere(['lsgi_id' => $id,'status' => '1']),
           'pagination' => false,
           'sort'=> ['defaultOrder' => ['id' => SORT_DESC]]
        ]);
        $modelSchedule = new Schedule;
        $scheduleDataProvider = new ActiveDataProvider(
        [
           'query' => $modelSchedule->getAllQuery()->andWhere(['lsgi_id' => $id,'status' => '1']),
           'pagination' => false,
           'sort'=> ['defaultOrder' => ['id' => SORT_DESC]]
        ]);
        $modelScheduleWard = new ScheduleWard();
        if ($modelSchedule->load(Yii::$app->request->post())) {
            $modelSchedule->lsgi_id = $id;
            $post = Yii::$app->request->post();
            $list = $post['Schedule']['ward_id']?$post['Schedule']['ward_id']:'';
            $modelSchedule->ward_id = serialize($list);
            $modelUser = Yii::$app->user->identity;
            $userId = $modelUser->id;
            $modelSchedule->account_id_creator= $userId;
            $modelSchedule->date= $modelSchedule->date ? \Yii::$app->formatter->asDatetime($modelSchedule->date , "php:Y-m-d") : '';
            $modelSchedule->save(false);
            if($post['Schedule']['ward_id']):
            foreach ($list as  $value) {
                $modelScheduleWard = new ScheduleWard();
                $modelScheduleWard->ward_id = $value;
                $modelScheduleWard->schedule_id = $modelSchedule->id;
                $modelScheduleWard->save(false);
            }
            endif;
        }
        $modelWard = new Ward;
        $modelSchedule = new Schedule;
         $params = [
            'model'  =>$model,
            'modelWard'  =>$modelWard,
            'modelLsgiServiceFee'  =>$modelLsgiServiceFee,
            'dataProvider'  =>$dataProvider,
            'lsgiServiceFeeDataProvider'  =>$lsgiServiceFeeDataProvider,
            'modelSchedule'  =>$modelSchedule,
            'scheduleDataProvider'  =>$scheduleDataProvider,
        ];
      return $this->render('view', [
            'params' => $params,
        ]);
    }
    public function actionPerformanceEvaluation($id)
    {
        $model = $this->findModel($id);
        $modelEvaluationConfigCustomerRating = new EvaluationConfigCustomerRating;
        $dataProvider = new ActiveDataProvider(
        [
           'query' => EvaluationConfigCustomerRating::find()->andWhere(['lsgi_id' => $id,'status' => '1']),
           'pagination' => false,
           'sort'=> ['defaultOrder' => ['id' => SORT_DESC]]
        ]);

        $modelEvaluationConfigWasteQuality = new EvaluationConfigWasteQuality;
        $evaluationConfigWasteQualityDataProvider = new ActiveDataProvider(
        [
           'query' => EvaluationConfigWasteQuality::find()->andWhere(['lsgi_id' => $id,'status' => '1']),
           'pagination' => false,
           'sort'=> ['defaultOrder' => ['id' => SORT_DESC]]
        ]);
        $modelEvaluationConfigComplaintsCount = new EvaluationConfigComplaintsCount;
        $evaluationConfigComplaintsCountDataProvider = new ActiveDataProvider(
        [
           'query' => EvaluationConfigComplaintsCount::find()->andWhere(['lsgi_id' => $id,'status' => '1']),
           'pagination' => false,
           'sort'=> ['defaultOrder' => ['id' => SORT_DESC]]
        ]);
        $modelEvaluationConfigCompletionTime = new EvaluationConfigCompletionTime;
        $evaluationConfigCompletionTimeDataProvider = new ActiveDataProvider(
        [
           'query' => EvaluationConfigCompletionTime::find()->andWhere(['lsgi_id' => $id,'status' => '1']),
           'pagination' => false,
           'sort'=> ['defaultOrder' => ['id' => SORT_DESC]]
        ]);
        $modelEvaluationConfigCompletionPercentage = new EvaluationConfigCompletionPercentage;
        $evaluationConfigCompletionPercentageDataProvider = new ActiveDataProvider(
        [
           'query' => EvaluationConfigCompletionPercentage::find()->andWhere(['lsgi_id' => $id,'status' => '1']),
           'pagination' => false,
           'sort'=> ['defaultOrder' => ['id' => SORT_DESC]]
        ]);

        $modelEvaluationConfigComplaintResolution = new EvaluationConfigComplaintsResolution;
        $evaluationConfigComplaintResolutionDataProvider = new ActiveDataProvider(
        [
           'query' => EvaluationConfigComplaintsResolution::find()->andWhere(['lsgi_id' => $id,'status' => '1']),
           'pagination' => false,
           'sort'=> ['defaultOrder' => ['id' => SORT_DESC]]
        ]);

        $model->service_assigment_expiry_hours= $model->service_assigment_expiry_hours/24;
            $model->rating_calculation_interval_hours= $model->rating_calculation_interval_hours/24;
            $model->complaints_count_rating_calculation_interval_hours= $model->complaints_count_rating_calculation_interval_hours/24;
        $params = [
            'model'  =>$model,
            'modelEvaluationConfigCustomerRating'  =>$modelEvaluationConfigCustomerRating,
            'modelEvaluationConfigWasteQuality'  =>$modelEvaluationConfigWasteQuality,
            'evaluationConfigWasteQualityDataProvider'  =>$evaluationConfigWasteQualityDataProvider,
            'dataProvider'  =>$dataProvider,
            'modelEvaluationConfigComplaintsCount'  =>$modelEvaluationConfigComplaintsCount,
            'evaluationConfigComplaintsCountDataProvider'  =>$evaluationConfigComplaintsCountDataProvider,
            'modelEvaluationConfigCompletionTime'  =>$modelEvaluationConfigCompletionTime,
            'evaluationConfigCompletionTimeDataProvider'  =>$evaluationConfigCompletionTimeDataProvider,
            'modelEvaluationConfigCompletionPercentage'  =>$modelEvaluationConfigCompletionPercentage,
            'evaluationConfigCompletionPercentageDataProvider'  =>$evaluationConfigCompletionPercentageDataProvider,
            'evaluationConfigComplaintResolutionDataProvider'  =>$evaluationConfigComplaintResolutionDataProvider,
            'modelEvaluationConfigComplaintResolution'  =>$modelEvaluationConfigComplaintResolution,
        ];

        return $this->render('performance-list', [
            'params' => $params,
        ]);
    }
    public function actionAddCustomerRating($id)
    {
        $model = $this->findModel($id);
        $modelEvaluationConfigCustomerRating = new EvaluationConfigCustomerRating;
        $dataProvider = new ActiveDataProvider(
        [
           'query' => EvaluationConfigCustomerRating::find()->andWhere(['lsgi_id' => $id,'status' => '1']),
           'pagination' => false,
           'sort'=> ['defaultOrder' => ['id' => SORT_DESC]]
        ]);

        $modelEvaluationConfigWasteQuality = new EvaluationConfigWasteQuality;
        $evaluationConfigWasteQualityDataProvider = new ActiveDataProvider(
        [
           'query' => EvaluationConfigWasteQuality::find()->andWhere(['lsgi_id' => $id,'status' => '1']),
           'pagination' => false,
           'sort'=> ['defaultOrder' => ['id' => SORT_DESC]]
        ]);
        $modelEvaluationConfigComplaintsCount = new EvaluationConfigComplaintsCount;
        $evaluationConfigComplaintsCountDataProvider = new ActiveDataProvider(
        [
           'query' => EvaluationConfigComplaintsCount::find()->andWhere(['lsgi_id' => $id,'status' => '1']),
           'pagination' => false,
           'sort'=> ['defaultOrder' => ['id' => SORT_DESC]]
        ]);
        $modelEvaluationConfigCompletionTime = new EvaluationConfigCompletionTime;
        $evaluationConfigCompletionTimeDataProvider = new ActiveDataProvider(
        [
           'query' => EvaluationConfigCompletionTime::find()->andWhere(['lsgi_id' => $id,'status' => '1']),
           'pagination' => false,
           'sort'=> ['defaultOrder' => ['id' => SORT_DESC]]
        ]);
        $modelEvaluationConfigCompletionPercentage = new EvaluationConfigCompletionPercentage;
        $evaluationConfigCompletionPercentageDataProvider = new ActiveDataProvider(
        [
           'query' => EvaluationConfigCompletionPercentage::find()->andWhere(['lsgi_id' => $id,'status' => '1']),
           'pagination' => false,
           'sort'=> ['defaultOrder' => ['id' => SORT_DESC]]
        ]);
        $modelEvaluationConfigComplaintResolution = new EvaluationConfigComplaintsResolution;
        $evaluationConfigComplaintResolutionDataProvider = new ActiveDataProvider(
        [
           'query' => EvaluationConfigComplaintsResolution::find()->andWhere(['lsgi_id' => $id,'status' => '1']),
           'pagination' => false,
           'sort'=> ['defaultOrder' => ['id' => SORT_DESC]]
        ]);

        if ($modelEvaluationConfigCustomerRating->load(Yii::$app->request->post())&&$modelEvaluationConfigCustomerRating->validate()) {
            $modelEvaluationConfigCustomerRating->lsgi_id = $id;
            $modelEvaluationConfigCustomerRating->save();
        }
        $model->service_assigment_expiry_hours= $model->service_assigment_expiry_hours/24;
            $model->rating_calculation_interval_hours= $model->rating_calculation_interval_hours/24;
            $model->complaints_count_rating_calculation_interval_hours= $model->complaints_count_rating_calculation_interval_hours/24;
        // else
        // {
        //   print_r($modelEvaluationConfigCustomerRating->getErrors());die();
        // }
        $modelEvaluationConfigCustomerRating = new EvaluationConfigCustomerRating;
         $params = [
            'model'  =>$model,
            'modelEvaluationConfigCustomerRating'  =>$modelEvaluationConfigCustomerRating,
            'modelEvaluationConfigWasteQuality'  =>$modelEvaluationConfigWasteQuality,
            'evaluationConfigWasteQualityDataProvider'  =>$evaluationConfigWasteQualityDataProvider,
            'dataProvider'  =>$dataProvider,
            'modelEvaluationConfigComplaintsCount'  =>$modelEvaluationConfigComplaintsCount,
            'evaluationConfigComplaintsCountDataProvider'  =>$evaluationConfigComplaintsCountDataProvider,
            'modelEvaluationConfigCompletionTime'  =>$modelEvaluationConfigCompletionTime,
            'evaluationConfigCompletionTimeDataProvider'  =>$evaluationConfigCompletionTimeDataProvider,
            'modelEvaluationConfigCompletionPercentage'  =>$modelEvaluationConfigCompletionPercentage,
            'evaluationConfigCompletionPercentageDataProvider'  =>$evaluationConfigCompletionPercentageDataProvider,
            'evaluationConfigComplaintResolutionDataProvider'  =>$evaluationConfigComplaintResolutionDataProvider,
            'modelEvaluationConfigComplaintResolution'  =>$modelEvaluationConfigComplaintResolution,
        ];
      return $this->render('performance-list', [
            'params' => $params,
        ]);
    }
    public function actionAddWasteQuality($id)
    {
        $model = $this->findModel($id);
        $modelEvaluationConfigCustomerRating = new EvaluationConfigCustomerRating;
        $dataProvider = new ActiveDataProvider(
        [
           'query' => EvaluationConfigCustomerRating::find()->andWhere(['lsgi_id' => $id,'status' => '1']),
           'pagination' => false,
           'sort'=> ['defaultOrder' => ['id' => SORT_DESC]]
        ]);

        $modelEvaluationConfigWasteQuality = new EvaluationConfigWasteQuality;
        $evaluationConfigWasteQualityDataProvider = new ActiveDataProvider(
        [
           'query' => EvaluationConfigWasteQuality::find()->andWhere(['lsgi_id' => $id,'status' => '1']),
           'pagination' => false,
           'sort'=> ['defaultOrder' => ['id' => SORT_DESC]]
        ]);
        $modelEvaluationConfigComplaintsCount = new EvaluationConfigComplaintsCount;
        $evaluationConfigComplaintsCountDataProvider = new ActiveDataProvider(
        [
           'query' => EvaluationConfigComplaintsCount::find()->andWhere(['lsgi_id' => $id,'status' => '1']),
           'pagination' => false,
           'sort'=> ['defaultOrder' => ['id' => SORT_DESC]]
        ]);
        $modelEvaluationConfigCompletionTime = new EvaluationConfigCompletionTime;
        $evaluationConfigCompletionTimeDataProvider = new ActiveDataProvider(
        [
           'query' => EvaluationConfigCompletionTime::find()->andWhere(['lsgi_id' => $id,'status' => '1']),
           'pagination' => false,
           'sort'=> ['defaultOrder' => ['id' => SORT_DESC]]
        ]);
        $modelEvaluationConfigCompletionPercentage = new EvaluationConfigCompletionPercentage;
        $evaluationConfigCompletionPercentageDataProvider = new ActiveDataProvider(
        [
           'query' => EvaluationConfigCompletionPercentage::find()->andWhere(['lsgi_id' => $id,'status' => '1']),
           'pagination' => false,
           'sort'=> ['defaultOrder' => ['id' => SORT_DESC]]
        ]);
        $modelEvaluationConfigComplaintResolution = new EvaluationConfigComplaintsResolution;
        $evaluationConfigComplaintResolutionDataProvider = new ActiveDataProvider(
        [
           'query' => EvaluationConfigComplaintsResolution::find()->andWhere(['lsgi_id' => $id,'status' => '1']),
           'pagination' => false,
           'sort'=> ['defaultOrder' => ['id' => SORT_DESC]]
        ]);

        if ($modelEvaluationConfigWasteQuality->load(Yii::$app->request->post())&&$modelEvaluationConfigWasteQuality->validate()) {
            $modelEvaluationConfigWasteQuality->lsgi_id = $id;
            $modelEvaluationConfigWasteQuality->save();
        }
        $model->service_assigment_expiry_hours= $model->service_assigment_expiry_hours/24;
            $model->rating_calculation_interval_hours= $model->rating_calculation_interval_hours/24;
            $model->complaints_count_rating_calculation_interval_hours= $model->complaints_count_rating_calculation_interval_hours/24;
        $modelEvaluationConfigWasteQuality = new EvaluationConfigWasteQuality;
         $params = [
            'model'  =>$model,
            'modelEvaluationConfigCustomerRating'  =>$modelEvaluationConfigCustomerRating,
            'modelEvaluationConfigWasteQuality'  =>$modelEvaluationConfigWasteQuality,
            'evaluationConfigWasteQualityDataProvider'  =>$evaluationConfigWasteQualityDataProvider,
            'dataProvider'  =>$dataProvider,
            'modelEvaluationConfigComplaintsCount'  =>$modelEvaluationConfigComplaintsCount,
            'evaluationConfigComplaintsCountDataProvider'  =>$evaluationConfigComplaintsCountDataProvider,
            'modelEvaluationConfigCompletionTime'  =>$modelEvaluationConfigCompletionTime,
            'evaluationConfigCompletionTimeDataProvider'  =>$evaluationConfigCompletionTimeDataProvider,
            'modelEvaluationConfigCompletionPercentage'  =>$modelEvaluationConfigCompletionPercentage,
            'evaluationConfigCompletionPercentageDataProvider'  =>$evaluationConfigCompletionPercentageDataProvider,
            'evaluationConfigComplaintResolutionDataProvider'  =>$evaluationConfigComplaintResolutionDataProvider,
            'modelEvaluationConfigComplaintResolution'  =>$modelEvaluationConfigComplaintResolution,
        ];
      return $this->render('performance-list', [
            'params' => $params,
        ]);
    }
    public function actionAddComplaintsCount($id)
    {
        $model = $this->findModel($id);
        $modelEvaluationConfigCustomerRating = new EvaluationConfigCustomerRating;
        $dataProvider = new ActiveDataProvider(
        [
           'query' => EvaluationConfigCustomerRating::find()->andWhere(['lsgi_id' => $id,'status' => '1']),
           'pagination' => false,
           'sort'=> ['defaultOrder' => ['id' => SORT_DESC]]
        ]);

        $modelEvaluationConfigWasteQuality = new EvaluationConfigWasteQuality;
        $evaluationConfigWasteQualityDataProvider = new ActiveDataProvider(
        [
           'query' => EvaluationConfigWasteQuality::find()->andWhere(['lsgi_id' => $id,'status' => '1']),
           'pagination' => false,
           'sort'=> ['defaultOrder' => ['id' => SORT_DESC]]
        ]);
        $modelEvaluationConfigComplaintsCount = new EvaluationConfigComplaintsCount;
        $evaluationConfigComplaintsCountDataProvider = new ActiveDataProvider(
        [
           'query' => EvaluationConfigComplaintsCount::find()->andWhere(['lsgi_id' => $id,'status' => '1']),
           'pagination' => false,
           'sort'=> ['defaultOrder' => ['id' => SORT_DESC]]
        ]);
        $modelEvaluationConfigCompletionTime = new EvaluationConfigCompletionTime;
        $evaluationConfigCompletionTimeDataProvider = new ActiveDataProvider(
        [
           'query' => EvaluationConfigCompletionTime::find()->andWhere(['lsgi_id' => $id,'status' => '1']),
           'pagination' => false,
           'sort'=> ['defaultOrder' => ['id' => SORT_DESC]]
        ]);
        $modelEvaluationConfigCompletionPercentage = new EvaluationConfigCompletionPercentage;
        $evaluationConfigCompletionPercentageDataProvider = new ActiveDataProvider(
        [
           'query' => EvaluationConfigCompletionPercentage::find()->andWhere(['lsgi_id' => $id,'status' => '1']),
           'pagination' => false,
           'sort'=> ['defaultOrder' => ['id' => SORT_DESC]]
        ]);
        $modelEvaluationConfigComplaintResolution = new EvaluationConfigComplaintsResolution;
        $evaluationConfigComplaintResolutionDataProvider = new ActiveDataProvider(
        [
           'query' => EvaluationConfigComplaintsResolution::find()->andWhere(['lsgi_id' => $id,'status' => '1']),
           'pagination' => false,
           'sort'=> ['defaultOrder' => ['id' => SORT_DESC]]
        ]);

        if ($modelEvaluationConfigComplaintsCount->load(Yii::$app->request->post())&&$modelEvaluationConfigComplaintsCount->validate()) {
            $modelEvaluationConfigComplaintsCount->lsgi_id = $id;
            $modelEvaluationConfigComplaintsCount->save();
        }
        $modelEvaluationConfigComplaintsCount = new EvaluationConfigComplaintsCount;
        $model->service_assigment_expiry_hours= $model->service_assigment_expiry_hours/24;
            $model->rating_calculation_interval_hours= $model->rating_calculation_interval_hours/24;
            $model->complaints_count_rating_calculation_interval_hours= $model->complaints_count_rating_calculation_interval_hours/24;
         $params = [
            'model'  =>$model,
            'modelEvaluationConfigCustomerRating'  =>$modelEvaluationConfigCustomerRating,
            'modelEvaluationConfigWasteQuality'  =>$modelEvaluationConfigWasteQuality,
            'evaluationConfigWasteQualityDataProvider'  =>$evaluationConfigWasteQualityDataProvider,
            'dataProvider'  =>$dataProvider,
            'modelEvaluationConfigComplaintsCount'  =>$modelEvaluationConfigComplaintsCount,
            'evaluationConfigComplaintsCountDataProvider'  =>$evaluationConfigComplaintsCountDataProvider,
            'modelEvaluationConfigCompletionTime'  =>$modelEvaluationConfigCompletionTime,
            'evaluationConfigCompletionTimeDataProvider'  =>$evaluationConfigCompletionTimeDataProvider,
            'modelEvaluationConfigCompletionPercentage'  =>$modelEvaluationConfigCompletionPercentage,
            'evaluationConfigCompletionPercentageDataProvider'  =>$evaluationConfigCompletionPercentageDataProvider,
            'evaluationConfigComplaintResolutionDataProvider'  =>$evaluationConfigComplaintResolutionDataProvider,
            'modelEvaluationConfigComplaintResolution'  =>$modelEvaluationConfigComplaintResolution,
        ];
      return $this->render('performance-list', [
            'params' => $params,
        ]);
    }
    public function actionAddTimeOfCompletion($id)
    {
        $model = $this->findModel($id);
        $modelEvaluationConfigCustomerRating = new EvaluationConfigCustomerRating;
        $dataProvider = new ActiveDataProvider(
        [
           'query' => EvaluationConfigCustomerRating::find()->andWhere(['lsgi_id' => $id,'status' => '1']),
           'pagination' => false,
           'sort'=> ['defaultOrder' => ['id' => SORT_DESC]]
        ]);

        $modelEvaluationConfigWasteQuality = new EvaluationConfigWasteQuality;
        $evaluationConfigWasteQualityDataProvider = new ActiveDataProvider(
        [
           'query' => EvaluationConfigWasteQuality::find()->andWhere(['lsgi_id' => $id,'status' => '1']),
           'pagination' => false,
           'sort'=> ['defaultOrder' => ['id' => SORT_DESC]]
        ]);
        $modelEvaluationConfigComplaintsCount = new EvaluationConfigComplaintsCount;
        $evaluationConfigComplaintsCountDataProvider = new ActiveDataProvider(
        [
           'query' => EvaluationConfigComplaintsCount::find()->andWhere(['lsgi_id' => $id,'status' => '1']),
           'pagination' => false,
           'sort'=> ['defaultOrder' => ['id' => SORT_DESC]]
        ]);
        $modelEvaluationConfigCompletionTime = new EvaluationConfigCompletionTime;
        $evaluationConfigCompletionTimeDataProvider = new ActiveDataProvider(
        [
           'query' => EvaluationConfigCompletionTime::find()->andWhere(['lsgi_id' => $id,'status' => '1']),
           'pagination' => false,
           'sort'=> ['defaultOrder' => ['id' => SORT_DESC]]
        ]);
        $modelEvaluationConfigCompletionPercentage = new EvaluationConfigCompletionPercentage;
        $evaluationConfigCompletionPercentageDataProvider = new ActiveDataProvider(
        [
           'query' => EvaluationConfigCompletionPercentage::find()->andWhere(['lsgi_id' => $id,'status' => '1']),
           'pagination' => false,
           'sort'=> ['defaultOrder' => ['id' => SORT_DESC]]
        ]);
        $modelEvaluationConfigComplaintResolution = new EvaluationConfigComplaintsResolution;
        $evaluationConfigComplaintResolutionDataProvider = new ActiveDataProvider(
        [
           'query' => EvaluationConfigComplaintsResolution::find()->andWhere(['lsgi_id' => $id,'status' => '1']),
           'pagination' => false,
           'sort'=> ['defaultOrder' => ['id' => SORT_DESC]]
        ]);
        if ($modelEvaluationConfigCompletionTime->load(Yii::$app->request->post())&&$modelEvaluationConfigCompletionTime->validate()) {
            $modelEvaluationConfigCompletionTime->lsgi_id = $id;
            $modelEvaluationConfigCompletionTime->save();
        }
        $modelEvaluationConfigCompletionTime = new EvaluationConfigCompletionTime;
        $model->service_assigment_expiry_hours= $model->service_assigment_expiry_hours/24;
            $model->rating_calculation_interval_hours= $model->rating_calculation_interval_hours/24;
            $model->complaints_count_rating_calculation_interval_hours= $model->complaints_count_rating_calculation_interval_hours/24;
         $params = [
            'model'  =>$model,
            'modelEvaluationConfigCustomerRating'  =>$modelEvaluationConfigCustomerRating,
            'modelEvaluationConfigWasteQuality'  =>$modelEvaluationConfigWasteQuality,
            'evaluationConfigWasteQualityDataProvider'  =>$evaluationConfigWasteQualityDataProvider,
            'dataProvider'  =>$dataProvider,
            'modelEvaluationConfigComplaintsCount'  =>$modelEvaluationConfigComplaintsCount,
            'evaluationConfigComplaintsCountDataProvider'  =>$evaluationConfigComplaintsCountDataProvider,
            'modelEvaluationConfigCompletionTime'  =>$modelEvaluationConfigCompletionTime,
            'evaluationConfigCompletionTimeDataProvider'  =>$evaluationConfigCompletionTimeDataProvider,
            'modelEvaluationConfigCompletionPercentage'  =>$modelEvaluationConfigCompletionPercentage,
            'evaluationConfigCompletionPercentageDataProvider'  =>$evaluationConfigCompletionPercentageDataProvider,
            'evaluationConfigComplaintResolutionDataProvider'  =>$evaluationConfigComplaintResolutionDataProvider,
            'modelEvaluationConfigComplaintResolution'  =>$modelEvaluationConfigComplaintResolution,
        ];
      return $this->render('performance-list', [
            'params' => $params,
        ]);
    }
    public function actionAddPercentageOfCompletion($id)
    {
        $model = $this->findModel($id);
        $modelEvaluationConfigCustomerRating = new EvaluationConfigCustomerRating;
        $dataProvider = new ActiveDataProvider(
        [
           'query' => EvaluationConfigCustomerRating::find()->andWhere(['lsgi_id' => $id,'status' => '1']),
           'pagination' => false,
           'sort'=> ['defaultOrder' => ['id' => SORT_DESC]]
        ]);

        $modelEvaluationConfigWasteQuality = new EvaluationConfigWasteQuality;
        $evaluationConfigWasteQualityDataProvider = new ActiveDataProvider(
        [
           'query' => EvaluationConfigWasteQuality::find()->andWhere(['lsgi_id' => $id,'status' => '1']),
           'pagination' => false,
           'sort'=> ['defaultOrder' => ['id' => SORT_DESC]]
        ]);
        $modelEvaluationConfigComplaintsCount = new EvaluationConfigComplaintsCount;
        $evaluationConfigComplaintsCountDataProvider = new ActiveDataProvider(
        [
           'query' => EvaluationConfigComplaintsCount::find()->andWhere(['lsgi_id' => $id,'status' => '1']),
           'pagination' => false,
           'sort'=> ['defaultOrder' => ['id' => SORT_DESC]]
        ]);
        $modelEvaluationConfigCompletionTime = new EvaluationConfigCompletionTime;
        $evaluationConfigCompletionTimeDataProvider = new ActiveDataProvider(
        [
           'query' => EvaluationConfigCompletionTime::find()->andWhere(['lsgi_id' => $id,'status' => '1']),
           'pagination' => false,
           'sort'=> ['defaultOrder' => ['id' => SORT_DESC]]
        ]);
        $modelEvaluationConfigCompletionPercentage = new EvaluationConfigCompletionPercentage;
        $evaluationConfigCompletionPercentageDataProvider = new ActiveDataProvider(
        [
           'query' => EvaluationConfigCompletionPercentage::find()->andWhere(['lsgi_id' => $id,'status' => '1']),
           'pagination' => false,
           'sort'=> ['defaultOrder' => ['id' => SORT_DESC]]
        ]);
        $modelEvaluationConfigComplaintResolution = new EvaluationConfigComplaintsResolution;
        $evaluationConfigComplaintResolutionDataProvider = new ActiveDataProvider(
        [
           'query' => EvaluationConfigComplaintsResolution::find()->andWhere(['lsgi_id' => $id,'status' => '1']),
           'pagination' => false,
           'sort'=> ['defaultOrder' => ['id' => SORT_DESC]]
        ]);

        if ($modelEvaluationConfigCompletionPercentage->load(Yii::$app->request->post())&&$modelEvaluationConfigCompletionPercentage->validate()) {
          // print_r("expression");die();
            $modelEvaluationConfigCompletionPercentage->lsgi_id = $id;
            $modelEvaluationConfigCompletionPercentage->save();
        }
        // else
        // {
        //   print_r($modelEvaluationConfigCompletionPercentage->getErrors());die();
        // }
        $modelEvaluationConfigCompletionPercentage = new EvaluationConfigCompletionPercentage;
        $model->service_assigment_expiry_hours= $model->service_assigment_expiry_hours/24;
            $model->rating_calculation_interval_hours= $model->rating_calculation_interval_hours/24;
            $model->complaints_count_rating_calculation_interval_hours= $model->complaints_count_rating_calculation_interval_hours/24;
         $params = [
            'model'  =>$model,
            'modelEvaluationConfigCustomerRating'  =>$modelEvaluationConfigCustomerRating,
            'modelEvaluationConfigWasteQuality'  =>$modelEvaluationConfigWasteQuality,
            'evaluationConfigWasteQualityDataProvider'  =>$evaluationConfigWasteQualityDataProvider,
            'dataProvider'  =>$dataProvider,
            'modelEvaluationConfigComplaintsCount'  =>$modelEvaluationConfigComplaintsCount,
            'evaluationConfigComplaintsCountDataProvider'  =>$evaluationConfigComplaintsCountDataProvider,
            'modelEvaluationConfigCompletionTime'  =>$modelEvaluationConfigCompletionTime,
            'evaluationConfigCompletionTimeDataProvider'  =>$evaluationConfigCompletionTimeDataProvider,
            'modelEvaluationConfigCompletionPercentage'  =>$modelEvaluationConfigCompletionPercentage,
            'evaluationConfigCompletionPercentageDataProvider'  =>$evaluationConfigCompletionPercentageDataProvider,
            'evaluationConfigComplaintResolutionDataProvider'  =>$evaluationConfigComplaintResolutionDataProvider,
            'modelEvaluationConfigComplaintResolution'  =>$modelEvaluationConfigComplaintResolution,
        ];
      return $this->render('performance-list', [
            'params' => $params,
        ]);
    }
    public function actionDeleteCustomerRating($id)
    {
        $model = new EvaluationConfigCustomerRating;
        $model->deleteRating($id);
    }
    public function actionDeleteWasteQuality($id)
    {
        $model = new EvaluationConfigWasteQuality;
        $model->deleteQuality($id);
    }
    public function actionDeleteComplaintsCount($id)
    {
        $model = new EvaluationConfigComplaintsCount;
        $model->deleteComplaintsCount($id);
    }
    public function actionDeleteTimeOfCompletion($id)
    {
        $model = new EvaluationConfigCompletionTime;
        $model->deleteCompletionTime($id);
    }
    public function actionDeletePercentageOfCompletion($id)
    {
        $model = new EvaluationConfigCompletionPercentage;
        $model->deleteCompletionTime($id);
    }
    public function actionDeletePercentageOfComplaints($id)
    {
        $model = new EvaluationConfigComplaintsResolution;
        $model->deleteComplaintsResolution($id);
    }
     public function actionAddLsgiSettings($id)
    {
        $model = $this->findModel($id);
        $modelEvaluationConfigCustomerRating = new EvaluationConfigCustomerRating;
        $dataProvider = new ActiveDataProvider(
        [
           'query' => EvaluationConfigCustomerRating::find()->andWhere(['lsgi_id' => $id,'status' => '1']),
           'pagination' => false,
           'sort'=> ['defaultOrder' => ['id' => SORT_DESC]]
        ]);

        $modelEvaluationConfigWasteQuality = new EvaluationConfigWasteQuality;
        $evaluationConfigWasteQualityDataProvider = new ActiveDataProvider(
        [
           'query' => EvaluationConfigWasteQuality::find()->andWhere(['lsgi_id' => $id,'status' => '1']),
           'pagination' => false,
           'sort'=> ['defaultOrder' => ['id' => SORT_DESC]]
        ]);
        $modelEvaluationConfigComplaintsCount = new EvaluationConfigComplaintsCount;
        $evaluationConfigComplaintsCountDataProvider = new ActiveDataProvider(
        [
           'query' => EvaluationConfigComplaintsCount::find()->andWhere(['lsgi_id' => $id,'status' => '1']),
           'pagination' => false,
           'sort'=> ['defaultOrder' => ['id' => SORT_DESC]]
        ]);
        $modelEvaluationConfigCompletionTime = new EvaluationConfigCompletionTime;
        $evaluationConfigCompletionTimeDataProvider = new ActiveDataProvider(
        [
           'query' => EvaluationConfigCompletionTime::find()->andWhere(['lsgi_id' => $id,'status' => '1']),
           'pagination' => false,
           'sort'=> ['defaultOrder' => ['id' => SORT_DESC]]
        ]);
        $modelEvaluationConfigCompletionPercentage = new EvaluationConfigCompletionPercentage;
        $evaluationConfigCompletionPercentageDataProvider = new ActiveDataProvider(
        [
           'query' => EvaluationConfigCompletionPercentage::find()->andWhere(['lsgi_id' => $id,'status' => '1']),
           'pagination' => false,
           'sort'=> ['defaultOrder' => ['id' => SORT_DESC]]
        ]);
        $modelEvaluationConfigComplaintResolution = new EvaluationConfigComplaintsResolution;
        $evaluationConfigComplaintResolutionDataProvider = new ActiveDataProvider(
        [
           'query' => EvaluationConfigComplaintsResolution::find()->andWhere(['lsgi_id' => $id,'status' => '1']),
           'pagination' => false,
           'sort'=> ['defaultOrder' => ['id' => SORT_DESC]]
        ]);
        if ($model->load(Yii::$app->request->post())&&$model->validate()) {
            $model->service_assigment_expiry_hours= $model->service_assigment_expiry_hours*24;
            $model->rating_calculation_interval_hours= $model->rating_calculation_interval_hours*24;
            $model->complaints_count_rating_calculation_interval_hours= $model->complaints_count_rating_calculation_interval_hours*24;
            $model->save();
        }
        $model->service_assigment_expiry_hours= $model->service_assigment_expiry_hours/24;
            $model->rating_calculation_interval_hours= $model->rating_calculation_interval_hours/24;
            $model->complaints_count_rating_calculation_interval_hours= $model->complaints_count_rating_calculation_interval_hours/24;
        $modelEvaluationConfigCustomerRating = new EvaluationConfigCustomerRating;
         $params = [
            'model'  =>$model,
            'modelEvaluationConfigCustomerRating'  =>$modelEvaluationConfigCustomerRating,
            'modelEvaluationConfigWasteQuality'  =>$modelEvaluationConfigWasteQuality,
            'evaluationConfigWasteQualityDataProvider'  =>$evaluationConfigWasteQualityDataProvider,
            'dataProvider'  =>$dataProvider,
            'modelEvaluationConfigComplaintsCount'  =>$modelEvaluationConfigComplaintsCount,
            'evaluationConfigComplaintsCountDataProvider'  =>$evaluationConfigComplaintsCountDataProvider,
            'modelEvaluationConfigCompletionTime'  =>$modelEvaluationConfigCompletionTime,
            'evaluationConfigCompletionTimeDataProvider'  =>$evaluationConfigCompletionTimeDataProvider,
            'modelEvaluationConfigCompletionPercentage'  =>$modelEvaluationConfigCompletionPercentage,
            'evaluationConfigCompletionPercentageDataProvider'  =>$evaluationConfigCompletionPercentageDataProvider,
            'evaluationConfigComplaintResolutionDataProvider'  =>$evaluationConfigComplaintResolutionDataProvider,
            'modelEvaluationConfigComplaintResolution'  =>$modelEvaluationConfigComplaintResolution,
        ];
      return $this->render('performance-list', [
            'params' => $params,
        ]);
    }
    public function actionUpdateCustomerRating($id)
    {
        
        $modelEvaluationConfigCustomerRating = EvaluationConfigCustomerRating::find()->where(['id'=>$id])->andWhere(['status'=>1])->one();
        $model = $this->findModel($modelEvaluationConfigCustomerRating->lsgi_id);
        $dataProvider = new ActiveDataProvider(
        [
           'query' => EvaluationConfigCustomerRating::find()->andWhere(['lsgi_id' => $id,'status' => '1']),
           'pagination' => false,
           'sort'=> ['defaultOrder' => ['id' => SORT_DESC]]
        ]);

        $modelEvaluationConfigWasteQuality = new EvaluationConfigWasteQuality;
        $evaluationConfigWasteQualityDataProvider = new ActiveDataProvider(
        [
           'query' => EvaluationConfigWasteQuality::find()->andWhere(['lsgi_id' => $id,'status' => '1']),
           'pagination' => false,
           'sort'=> ['defaultOrder' => ['id' => SORT_DESC]]
        ]);
        $modelEvaluationConfigComplaintsCount = new EvaluationConfigComplaintsCount;
        $evaluationConfigComplaintsCountDataProvider = new ActiveDataProvider(
        [
           'query' => EvaluationConfigComplaintsCount::find()->andWhere(['lsgi_id' => $id,'status' => '1']),
           'pagination' => false,
           'sort'=> ['defaultOrder' => ['id' => SORT_DESC]]
        ]);
        $modelEvaluationConfigCompletionTime = new EvaluationConfigCompletionTime;
        $evaluationConfigCompletionTimeDataProvider = new ActiveDataProvider(
        [
           'query' => EvaluationConfigCompletionTime::find()->andWhere(['lsgi_id' => $id,'status' => '1']),
           'pagination' => false,
           'sort'=> ['defaultOrder' => ['id' => SORT_DESC]]
        ]);
        $modelEvaluationConfigCompletionPercentage = new EvaluationConfigCompletionPercentage;
        $evaluationConfigCompletionPercentageDataProvider = new ActiveDataProvider(
        [
           'query' => EvaluationConfigCompletionPercentage::find()->andWhere(['lsgi_id' => $id,'status' => '1']),
           'pagination' => false,
           'sort'=> ['defaultOrder' => ['id' => SORT_DESC]]
        ]);
        $modelEvaluationConfigComplaintResolution = new EvaluationConfigComplaintsResolution;
        $evaluationConfigComplaintResolutionDataProvider = new ActiveDataProvider(
        [
           'query' => EvaluationConfigComplaintsResolution::find()->andWhere(['lsgi_id' => $id,'status' => '1']),
           'pagination' => false,
           'sort'=> ['defaultOrder' => ['id' => SORT_DESC]]
        ]);
        if ($modelEvaluationConfigCustomerRating->load(Yii::$app->request->post())) {
            $modelEvaluationConfigCustomerRating->save(false);
        }
        // else
        // {
        //   print_r($modelEvaluationConfigCustomerRating->getErrors());die();
        // }
        $modelEvaluationConfigCustomerRating = new EvaluationConfigCustomerRating;
        $model->service_assigment_expiry_hours= $model->service_assigment_expiry_hours/24;
            $model->rating_calculation_interval_hours= $model->rating_calculation_interval_hours/24;
            $model->complaints_count_rating_calculation_interval_hours= $model->complaints_count_rating_calculation_interval_hours/24;
         $params = [
            'model'  =>$model,
            'modelEvaluationConfigCustomerRating'  =>$modelEvaluationConfigCustomerRating,
            'modelEvaluationConfigWasteQuality'  =>$modelEvaluationConfigWasteQuality,
            'evaluationConfigWasteQualityDataProvider'  =>$evaluationConfigWasteQualityDataProvider,
            'dataProvider'  =>$dataProvider,
            'modelEvaluationConfigComplaintsCount'  =>$modelEvaluationConfigComplaintsCount,
            'evaluationConfigComplaintsCountDataProvider'  =>$evaluationConfigComplaintsCountDataProvider,
            'modelEvaluationConfigCompletionTime'  =>$modelEvaluationConfigCompletionTime,
            'evaluationConfigCompletionTimeDataProvider'  =>$evaluationConfigCompletionTimeDataProvider,
            'modelEvaluationConfigCompletionPercentage'  =>$modelEvaluationConfigCompletionPercentage,
            'evaluationConfigCompletionPercentageDataProvider'  =>$evaluationConfigCompletionPercentageDataProvider,
            'evaluationConfigComplaintResolutionDataProvider'  =>$evaluationConfigComplaintResolutionDataProvider,
            'modelEvaluationConfigComplaintResolution'  =>$modelEvaluationConfigComplaintResolution,
        ];
      return $this->render('performance-list', [
            'params' => $params,
        ]);
    }
    public function actionAddPercentageOfComplaints($id)
    {
        $model = $this->findModel($id);
        $modelEvaluationConfigCustomerRating = new EvaluationConfigCustomerRating;
        $dataProvider = new ActiveDataProvider(
        [
           'query' => EvaluationConfigCustomerRating::find()->andWhere(['lsgi_id' => $id,'status' => '1']),
           'pagination' => false,
           'sort'=> ['defaultOrder' => ['id' => SORT_DESC]]
        ]);

        $modelEvaluationConfigWasteQuality = new EvaluationConfigWasteQuality;
        $evaluationConfigWasteQualityDataProvider = new ActiveDataProvider(
        [
           'query' => EvaluationConfigWasteQuality::find()->andWhere(['lsgi_id' => $id,'status' => '1']),
           'pagination' => false,
           'sort'=> ['defaultOrder' => ['id' => SORT_DESC]]
        ]);
        $modelEvaluationConfigComplaintsCount = new EvaluationConfigComplaintsCount;
        $evaluationConfigComplaintsCountDataProvider = new ActiveDataProvider(
        [
           'query' => EvaluationConfigComplaintsCount::find()->andWhere(['lsgi_id' => $id,'status' => '1']),
           'pagination' => false,
           'sort'=> ['defaultOrder' => ['id' => SORT_DESC]]
        ]);
        $modelEvaluationConfigCompletionTime = new EvaluationConfigCompletionTime;
        $evaluationConfigCompletionTimeDataProvider = new ActiveDataProvider(
        [
           'query' => EvaluationConfigCompletionTime::find()->andWhere(['lsgi_id' => $id,'status' => '1']),
           'pagination' => false,
           'sort'=> ['defaultOrder' => ['id' => SORT_DESC]]
        ]);
        $modelEvaluationConfigCompletionPercentage = new EvaluationConfigCompletionPercentage;
        $evaluationConfigCompletionPercentageDataProvider = new ActiveDataProvider(
        [
           'query' => EvaluationConfigCompletionPercentage::find()->andWhere(['lsgi_id' => $id,'status' => '1']),
           'pagination' => false,
           'sort'=> ['defaultOrder' => ['id' => SORT_DESC]]
        ]);
         $modelEvaluationConfigComplaintResolution = new EvaluationConfigComplaintsResolution;
        $evaluationConfigComplaintResolutionDataProvider = new ActiveDataProvider(
        [
           'query' => EvaluationConfigComplaintsResolution::find()->andWhere(['lsgi_id' => $id,'status' => '1']),
           'pagination' => false,
           'sort'=> ['defaultOrder' => ['id' => SORT_DESC]]
        ]);
        if ($modelEvaluationConfigComplaintResolution->load(Yii::$app->request->post())&&$modelEvaluationConfigComplaintResolution->validate()) {
          // print_r("expression");die();
            $modelEvaluationConfigComplaintResolution->lsgi_id = $id;
            $modelEvaluationConfigComplaintResolution->hours = $modelEvaluationConfigComplaintResolution->hours+1;
            $modelEvaluationConfigComplaintResolution->save();
        }
        // else
        // {
        //   print_r($modelEvaluationConfigCompletionPercentage->getErrors());die();
        // }
        $modelEvaluationConfigComplaintResolution = new EvaluationConfigComplaintsResolution;
        $model->service_assigment_expiry_hours= $model->service_assigment_expiry_hours/24;
            $model->rating_calculation_interval_hours= $model->rating_calculation_interval_hours/24;
            $model->complaints_count_rating_calculation_interval_hours= $model->complaints_count_rating_calculation_interval_hours/24;
         $params = [
            'model'  =>$model,
            'modelEvaluationConfigCustomerRating'  =>$modelEvaluationConfigCustomerRating,
            'modelEvaluationConfigWasteQuality'  =>$modelEvaluationConfigWasteQuality,
            'evaluationConfigWasteQualityDataProvider'  =>$evaluationConfigWasteQualityDataProvider,
            'dataProvider'  =>$dataProvider,
            'modelEvaluationConfigComplaintsCount'  =>$modelEvaluationConfigComplaintsCount,
            'evaluationConfigComplaintsCountDataProvider'  =>$evaluationConfigComplaintsCountDataProvider,
            'modelEvaluationConfigCompletionTime'  =>$modelEvaluationConfigCompletionTime,
            'evaluationConfigCompletionTimeDataProvider'  =>$evaluationConfigCompletionTimeDataProvider,
            'modelEvaluationConfigCompletionPercentage'  =>$modelEvaluationConfigCompletionPercentage,
            'evaluationConfigCompletionPercentageDataProvider'  =>$evaluationConfigCompletionPercentageDataProvider,
            'evaluationConfigComplaintResolutionDataProvider'  =>$evaluationConfigComplaintResolutionDataProvider,
            'modelEvaluationConfigComplaintResolution'  =>$modelEvaluationConfigComplaintResolution,
        ];
      return $this->render('performance-list', [
            'params' => $params,
        ]);
    }
    public function actionAddEscalationSettings($id)
    {
        $model = $this->findModel($id);
     $modelWard = new Ward;
        $dataProvider = new ActiveDataProvider(
        [
           'query' => Ward::getAllQuery()->andWhere(['lsgi_id' => $id,'status' => '1']),
           'pagination' => false,
           'sort'=> ['defaultOrder' => ['id' => SORT_DESC]]
        ]);

        $modelLsgiServiceFee = new LsgiServiceFee;
        $lsgiServiceFeeDataProvider = new ActiveDataProvider(
        [
           'query' => $modelLsgiServiceFee->getAllQuery()->andWhere(['lsgi_id' => $id,'status' => '1']),
           'pagination' => false,
           'sort'=> ['defaultOrder' => ['id' => SORT_DESC]]
        ]);
        $modelSchedule = new Schedule;
        $scheduleDataProvider = new ActiveDataProvider(
        [
           'query' => $modelSchedule->getAllQuery()->andWhere(['lsgi_id' => $id,'status' => '1']),
           'pagination' => false,
           'sort'=> ['defaultOrder' => ['id' => SORT_DESC]]
        ]);
        $modelEscalationSettings = new EscalationSettings;
        $escalationSettingsDataProvider = new ActiveDataProvider(
        [
           'query' => $modelEscalationSettings->getAllQuery()->andWhere(['lsgi_id' => $id,'status' => '1']),
           'pagination' => false,
           'sort'=> ['defaultOrder' => ['id' => SORT_DESC]]
        ]);
        $modelLsgiServiceSlabFee = new LsgiServiceSlabFee;
        $lsgiServiceFeeSlabDataProvider = new ActiveDataProvider(
        [
           'query' => $modelLsgiServiceSlabFee->getAllQuery()->andWhere(['lsgi_id' => $id,'status' => '1']),
           'pagination' => false,
           'sort'=> ['defaultOrder' => ['id' => SORT_DESC]]
        ]);
        $modelEscalationSettings->setScenario('create');
        if ($modelEscalationSettings->load(Yii::$app->request->post())) {
            $modelEscalationSettings->lsgi_id = $id;
            $modelEscalationSettings->save();
        }
        $modelWard = new Ward;
$modelLsgiServiceFee = new LsgiServiceFee;
$modelEscalationSettings = new EscalationSettings;
         $params = [
            'model'  =>$model,
            'modelWard'  =>$modelWard,
            'modelLsgiServiceFee'  =>$modelLsgiServiceFee,
            'modelSchedule'  =>$modelSchedule,
            'scheduleDataProvider'  =>$scheduleDataProvider,
            'dataProvider'  =>$dataProvider,
            'lsgiServiceFeeDataProvider'  =>$lsgiServiceFeeDataProvider,
            'modelEscalationSettings'  =>$modelEscalationSettings,
            'escalationSettingsDataProvider'  =>$escalationSettingsDataProvider,
            'modelLsgiServiceSlabFee'  =>$modelLsgiServiceSlabFee,
            'lsgiServiceFeeSlabDataProvider'  =>$lsgiServiceFeeSlabDataProvider,
        ];
      return $this->render('view', [
            'params' => $params,
        ]);
    }
     public function actionDeleteEscalationSettings($id)
    {
        $model = new EscalationSettings;
        $model->deleteEscalationSettings($id);
    }
    public function actionUpdateEscalationSettings($id)
    {
      $success = false;
      $modelEscalationSettings = $this->findEscalationModel($id);
      $model = $this->findModel($modelEscalationSettings->lsgi_id);
      
        $modelWard = new Ward;
        $dataProvider = new ActiveDataProvider(
        [
           'query' => Ward::getAllQuery()->andWhere(['lsgi_id' => $id,'status' => '1']),
           'pagination' => false,
           'sort'=> ['defaultOrder' => ['id' => SORT_DESC]]
        ]);

        $modelLsgiServiceFee = new LsgiServiceFee;
        $lsgiServiceFeeDataProvider = new ActiveDataProvider(
        [
           'query' => $modelLsgiServiceFee->getAllQuery()->andWhere(['lsgi_id' => $id,'status' => '1']),
           'pagination' => false,
           'sort'=> ['defaultOrder' => ['id' => SORT_DESC]]
        ]);
        $modelSchedule = new Schedule;
        $scheduleDataProvider = new ActiveDataProvider(
        [
           'query' => $modelSchedule->getAllQuery()->andWhere(['lsgi_id' => $id,'status' => '1']),
           'pagination' => false,
           'sort'=> ['defaultOrder' => ['id' => SORT_DESC]]
        ]);
        $modelLsgiServiceSlabFee = new LsgiServiceSlabFee;
        $lsgiServiceFeeSlabDataProvider = new ActiveDataProvider(
        [
           'query' => $modelLsgiServiceSlabFee->getAllQuery()->andWhere(['lsgi_id' => $id,'status' => '1']),
           'pagination' => false,
           'sort'=> ['defaultOrder' => ['id' => SORT_DESC]]
        ]);
        if ($modelEscalationSettings->load(Yii::$app->request->post())) {
            // $modelEscalationSettings->lsgi_id = $id;
            $modelEscalationSettings->save();
        }
        $modelWard = new Ward;
$modelLsgiServiceFee = new LsgiServiceFee;
 $modelEscalationSettings = new EscalationSettings;
        $escalationSettingsDataProvider = new ActiveDataProvider(
        [
           'query' => $modelEscalationSettings->getAllQuery()->andWhere(['lsgi_id' => $id,'status' => '1']),
           'pagination' => false,
           'sort'=> ['defaultOrder' => ['id' => SORT_DESC]]
        ]);
         $params = [
            'model'  =>$model,
            'modelWard'  =>$modelWard,
            'modelLsgiServiceFee'  =>$modelLsgiServiceFee,
            'modelSchedule'  =>$modelSchedule,
            'scheduleDataProvider'  =>$scheduleDataProvider,
            'dataProvider'  =>$dataProvider,
            'lsgiServiceFeeDataProvider'  =>$lsgiServiceFeeDataProvider,
            'modelEscalationSettings'  =>$modelEscalationSettings,
            'escalationSettingsDataProvider'  =>$escalationSettingsDataProvider,
            'modelLsgiServiceSlabFee'  =>$modelLsgiServiceSlabFee,
            'lsgiServiceFeeSlabDataProvider'  =>$lsgiServiceFeeSlabDataProvider,
        ];
      return $this->render('view', [
            'params' => $params,
        ]);
    }
    protected function findEscalationModel($id)
    {
        if (($model = EscalationSettings::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
     public function actionAddLsgiServiceSlabFee($id)
    {
        $model = $this->findModel($id);
     $modelWard = new Ward;
        $dataProvider = new ActiveDataProvider(
        [
           'query' => Ward::getAllQuery()->andWhere(['lsgi_id' => $id,'status' => '1']),
           'pagination' => false,
           'sort'=> ['defaultOrder' => ['id' => SORT_DESC]]
        ]);

        $modelLsgiServiceFee = new LsgiServiceFee;
        $lsgiServiceFeeDataProvider = new ActiveDataProvider(
        [
           'query' => $modelLsgiServiceFee->getAllQuery()->andWhere(['lsgi_id' => $id,'status' => '1']),
           'pagination' => false,
           'sort'=> ['defaultOrder' => ['id' => SORT_DESC]]
        ]);
        $modelSchedule = new Schedule;
        $scheduleDataProvider = new ActiveDataProvider(
        [
           'query' => $modelSchedule->getAllQuery()->andWhere(['lsgi_id' => $id,'status' => '1']),
           'pagination' => false,
           'sort'=> ['defaultOrder' => ['id' => SORT_DESC]]
        ]);
        $modelEscalationSettings = new EscalationSettings;
        $escalationSettingsDataProvider = new ActiveDataProvider(
        [
           'query' => $modelEscalationSettings->getAllQuery()->andWhere(['lsgi_id' => $id,'status' => '1']),
           'pagination' => false,
           'sort'=> ['defaultOrder' => ['id' => SORT_DESC]]
        ]);
        $modelLsgiServiceSlabFee = new LsgiServiceSlabFee;
        $lsgiServiceFeeSlabDataProvider = new ActiveDataProvider(
        [
           'query' => $modelLsgiServiceSlabFee->getAllQuery()->andWhere(['lsgi_id' => $id,'status' => '1']),
           'pagination' => false,
           'sort'=> ['defaultOrder' => ['id' => SORT_DESC]]
        ]);
        if ($modelLsgiServiceSlabFee->load(Yii::$app->request->post())) {
            $modelLsgiServiceSlabFee->lsgi_id = $id;
            $modelLsgiServiceSlabFee->save();
        }
        $modelWard = new Ward;
$modelLsgiServiceSlabFee = new LsgiServiceSlabFee;
         $params = [
            'model'  =>$model,
            'modelWard'  =>$modelWard,
            'modelLsgiServiceFee'  =>$modelLsgiServiceFee,
            'modelSchedule'  =>$modelSchedule,
            'scheduleDataProvider'  =>$scheduleDataProvider,
            'dataProvider'  =>$dataProvider,
            'lsgiServiceFeeDataProvider'  =>$lsgiServiceFeeDataProvider,
            'modelEscalationSettings'  =>$modelEscalationSettings,
            'escalationSettingsDataProvider'  =>$escalationSettingsDataProvider,
            'modelLsgiServiceSlabFee'  =>$modelLsgiServiceSlabFee,
            'lsgiServiceFeeSlabDataProvider'  =>$lsgiServiceFeeSlabDataProvider,
        ];
      return $this->render('view', [
            'params' => $params,
        ]);
    }
    public function actionUpdateLsgiServiceSlabFee($id)
    {
      // print_r(Yii::$app->request->post());die();
      $success = false;
      $modelLsgiServiceSlabFee = $this->findSlabModel($id);
      $model = $this->findModel($modelLsgiServiceSlabFee->lsgi_id);
      
        $modelWard = new Ward;
        $dataProvider = new ActiveDataProvider(
        [
           'query' => Ward::getAllQuery()->andWhere(['lsgi_id' => $id,'status' => '1']),
           'pagination' => false,
           'sort'=> ['defaultOrder' => ['id' => SORT_DESC]]
        ]);

        $modelLsgiServiceFee = new LsgiServiceFee;
        $lsgiServiceFeeDataProvider = new ActiveDataProvider(
        [
           'query' => $modelLsgiServiceFee->getAllQuery()->andWhere(['lsgi_id' => $id,'status' => '1']),
           'pagination' => false,
           'sort'=> ['defaultOrder' => ['id' => SORT_DESC]]
        ]);
        $modelSchedule = new Schedule;
        $scheduleDataProvider = new ActiveDataProvider(
        [
           'query' => $modelSchedule->getAllQuery()->andWhere(['lsgi_id' => $id,'status' => '1']),
           'pagination' => false,
           'sort'=> ['defaultOrder' => ['id' => SORT_DESC]]
        ]);
        
        if ($modelLsgiServiceSlabFee->load(Yii::$app->request->post())) {
            $modelLsgiServiceSlabFee->save(fasle);
        }
        $modelLsgiServiceSlabFee = new LsgiServiceSlabFee;
        $lsgiServiceFeeSlabDataProvider = new ActiveDataProvider(
        [
           'query' => $modelLsgiServiceSlabFee->getAllQuery()->andWhere(['lsgi_id' => $id,'status' => '1']),
           'pagination' => false,
           'sort'=> ['defaultOrder' => ['id' => SORT_DESC]]
        ]);
        $modelWard = new Ward;
$modelLsgiServiceFee = new LsgiServiceFee;
 $modelEscalationSettings = new EscalationSettings;
        $escalationSettingsDataProvider = new ActiveDataProvider(
        [
           'query' => $modelEscalationSettings->getAllQuery()->andWhere(['lsgi_id' => $id,'status' => '1']),
           'pagination' => false,
           'sort'=> ['defaultOrder' => ['id' => SORT_DESC]]
        ]);
         $params = [
            'model'  =>$model,
            'modelWard'  =>$modelWard,
            'modelLsgiServiceFee'  =>$modelLsgiServiceFee,
            'modelSchedule'  =>$modelSchedule,
            'scheduleDataProvider'  =>$scheduleDataProvider,
            'dataProvider'  =>$dataProvider,
            'lsgiServiceFeeDataProvider'  =>$lsgiServiceFeeDataProvider,
            'modelEscalationSettings'  =>$modelEscalationSettings,
            'escalationSettingsDataProvider'  =>$escalationSettingsDataProvider,
            'modelLsgiServiceSlabFee'  =>$modelLsgiServiceSlabFee,
            'lsgiServiceFeeSlabDataProvider'  =>$lsgiServiceFeeSlabDataProvider,
        ];
      return $this->render('view', [
            'params' => $params,
        ]);
    }
     public function actionGetSlab() {
       $out = [];
        if (isset($_POST['depdrop_parents'])) {
        $parents = $_POST['depdrop_parents'];
        $slab = LsgiServiceSlabFee::find()->where(['service_id'=>$parents[0]])->andWhere(['status'=>1])->all();
        foreach ($slab as $id => $post) {
            $out[] = ['id' => $post['id'], 'name' => $post['slab_name']];
 }
 echo Json::encode(['output' => $out, 'selected' => '']);
    }
  }
}
