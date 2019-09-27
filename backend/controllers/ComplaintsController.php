<?php

namespace backend\controllers;

use Yii;
use backend\models\Service;
use backend\models\ServiceSearch;
use backend\models\WasteCollectionMethodService;
use backend\models\WasteCollectionMethod;
use backend\models\ServicingStatusOptionSearch;
use backend\models\ServicingStatusOption;
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
class ComplaintsController extends Controller
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
                         'permissions' => ['complaints-index']
                     ],
                     [
                         'actions' => ['view'],
                         'allow'   => true,
                         'permissions' => ['complaints-view']
                     ],
                     [
                         'actions' => ['create'],
                         'allow'   => true,
                         'permissions' => ['complaints-create']
                     ],
                     [
                         'actions' => ['update'],
                         'allow'   => true,
                         'permissions' => ['complaints-update']
                     ],
                     [
                         'actions' => ['delete'],
                         'allow'   => true,
                         'permissions' => ['complaints-delete']
                     ],
                     [
                         'actions' => ['delete-service'],
                         'allow'   => true,
                         'permissions' => ['complaints-delete-service']
                     ],
                     [
                         'actions' => ['view-complaints'],
                         'allow'   => true,
                         'permissions' => ['complaints-view-complaints']
                     ],
                     [
                         'actions' => ['add-status-options'],
                         'allow'   => true,
                         'permissions' => ['complaints-add-status-options']
                     ],
                     [
                         'actions' => ['delete-status-options'],
                         'allow'   => true,
                         'permissions' => ['complaints-delete-status-options']
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
        $type = 2;
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
        $post = Yii::$app->request->post();
        if ($model->load(Yii::$app->request->post())&& $modelImage->load(Yii::$app->request->post())) {
              $modelImage->uploaded_files = UploadedFile::getInstance($modelImage, 'uploaded_files');
            $imageId                    = $modelImage->uploadAndSave();
            if ($imageId)
            {
                $model->image_id = $imageId;
            }
            $model->type = 2;
            $model->save();
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
        if ($model->load(Yii::$app->request->post())&& $modelImage->load(Yii::$app->request->post())) {
            $modelImage->uploaded_files = UploadedFile::getInstance($modelImage, 'uploaded_files');
            $imageId                    = $modelImage->uploadAndSave();
            if ($imageId)
            {
                $model->image_id = $imageId;
            }
            $model->save();
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
     public function actionViewComplaints($id)
    {
      $model = $this->findModel($id);
      $modelServicingStatusOption = new ServicingStatusOption;
      $searchModel = new ServicingStatusOptionSearch;
      $modelImage = new Image;
      $dataProvider = $searchModel->search(Yii::$app->request->queryParams,$id);
      $params = [
        'model' => $model,
        'modelServicingStatusOption' => $modelServicingStatusOption,
        'modelImage' => $modelImage,
        'searchModel' => $searchModel,
        'dataProvider'=> $dataProvider
      ];
      return $this->render('view', [
            'params' => $params,
        ]);
    }
    public function actionAddStatusOptions($id)
    {
      $model = $this->findModel($id);
      $modelImage = new Image;
      $modelServicingStatusOption = new ServicingStatusOption;
      $searchModel = new ServicingStatusOptionSearch;
      $dataProvider = $searchModel->search(Yii::$app->request->queryParams,$id);

        if ($modelServicingStatusOption->load(Yii::$app->request->post())&& $modelImage->load(Yii::$app->request->post())) {
            $modelImage->uploaded_files = UploadedFile::getInstance($modelImage, 'uploaded_files');
            $imageId                    = $modelImage->uploadAndSave();
            if ($imageId)
            {
                $modelServicingStatusOption->image_id = $imageId;
            }
            $modelServicingStatusOption->service_id = $id;
            $modelServicingStatusOption->save();
        }
              $modelServicingStatusOption = new ServicingStatusOption;
         $params = [
        'model' => $model,
        'modelServicingStatusOption' => $modelServicingStatusOption,
        'modelImage' => $modelImage,
        'searchModel' => $searchModel,
        'dataProvider'=> $dataProvider
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
}
