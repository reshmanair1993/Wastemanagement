<?php

namespace backend\controllers;

use Yii;
use backend\models\WasteCollectionInterval;
use backend\models\WasteCollectionIntervalSearch;
use backend\models\WasteCollectionMethodBuildingType;
use backend\models\NonResidentialWasteCollectionIntervalSearch;
use backend\models\NonResidentialWasteCollectionInterval;
use backend\models\NonResidentialWasteCollectionIntervalService;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use backend\components\AccessPermission;
/**
 * WasteCollectionIntervalController implements the CRUD actions for WasteCollectionInterval model.
 */
class WasteCollectionIntervalsController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
           'access' => [
               'class' => AccessControl::className(),
               'only' => ['index','create','update','view','non-residential','create-non-residential','update-non-residential'],
               'ruleConfig' => [
                       'class' => AccessPermission::className(),
                   ],
               'rules' => [
                   [
                       'actions' => ['index'],
                       'allow' => true,
                       'permissions' => ['waste-collection-intervals-index'],
                   ],
                   [
                       'actions' => ['create'],
                       'allow' => true,
                       'permissions' => ['waste-collection-intervals-create'],
                   ],
                   [
                       'actions' => ['update'],
                       'allow' => true,
                       'permissions' => ['waste-collection-intervals-update'],
                   ],
                   [
                       'actions' => ['delete'],
                       'allow' => true,
                       'permissions' => ['waste-collection-intervals-delete'],
                   ],
                   [
                       'actions' => ['view'],
                       'allow' => true,
                       'permissions' => ['waste-collection-intervals-view'],
                   ],
                   [
                       'actions' => ['non-residential'],
                       'allow' => true,
                       'permissions' => ['waste-collection-intervals-non-residential'],
                   ],
                   [
                       'actions' => ['create-non-residential'],
                       'allow' => true,
                       'permissions' => ['waste-collection-intervals-create-non-residential'],
                   ],
                   [
                       'actions' => ['update-non-residential'],
                       'allow' => true,
                       'permissions' => ['waste-collection-intervals-update-non-residential'],
                   ],
               ],
               'denyCallback' => function($rule, $action) {
                   return $this->goHome();
               }
           ],
       ];
    }


    /**
     * Lists all WasteCollectionInterval models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new WasteCollectionIntervalSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single WasteCollectionInterval model.
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
     * Creates a new WasteCollectionInterval model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    // public function actionCreate()
    // {
    //     $model = new WasteCollectionInterval();

    //     if ($model->load(Yii::$app->request->post()) && $model->save()) {
    //         return $this->redirect(['view', 'id' => $model->id]);
    //     }

    //     return $this->render('create', [
    //         'model' => $model,
    //     ]);
    // }
    public function actionCreate()
    {
        $model = new WasteCollectionInterval();

        if ($model->load(Yii::$app->request->post())) {
           $list = $model->building_type_available;
            $model->building_type_available = serialize($model->building_type_available);
            $model->save(false);
            if($list):
            foreach ($list as  $value) {
                $modelWasteCollectionMethodBuildingType = new WasteCollectionMethodBuildingType();
                $modelWasteCollectionMethodBuildingType->building_type_id = $value;
                // $modelWasteCollectionMethodBuildingType->waste_collection_method_id = $model->id;
                $modelWasteCollectionMethodBuildingType->save(false);
            }
            endif;
            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing WasteCollectionInterval model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    // public function actionUpdate($id)
    // {
    //     $model = $this->findModel($id);

    //     if ($model->load(Yii::$app->request->post()) && $model->save()) {
    //         return $this->redirect(['view', 'id' => $model->id]);
    //     }

    //     return $this->render('update', [
    //         'model' => $model,
    //     ]);
    // }
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
         // $model->building_type_available = unserialize($model->building_type_available);
        if ($model->load(Yii::$app->request->post()) ) {
            $list = $model->building_type_available;
            $model->building_type_available = serialize($model->building_type_available);
            $model->save();
            if($list):
            WasteCollectionMethodBuildingType::deleteAll(['waste_collection_method_id'=>$model->id]);
            foreach ($list as  $value) {
                $modelWasteCollectionMethodBuildingType = new WasteCollectionMethodBuildingType();
                $modelWasteCollectionMethodBuildingType->building_type_id = $value;
                // $modelWasteCollectionMethodBuildingType->waste_collection_method_id = $model->id;
                $modelWasteCollectionMethodBuildingType->save(false);
            }
            endif;
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing WasteCollectionInterval model.
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
     * Finds the WasteCollectionInterval model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return WasteCollectionInterval the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = WasteCollectionInterval::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
    protected function findModelNonResidential($id)
    {
        if (($model = NonResidentialWasteCollectionInterval::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
    public function actionNonResidential()
    {
        $searchModel = new NonResidentialWasteCollectionIntervalSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('non-residential', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
     public function actionCreateNonResidential()
    {
        $model = new NonResidentialWasteCollectionInterval();

        if ($model->load(Yii::$app->request->post())) {
          $model->service_id = serialize($model->service_id);
            $model->save(false);
            $service = unserialize($model->service_id);
            if($service)
            {
              foreach ($service as $key) {
                $modelNonResidentialWasteCollectionIntervalService = new NonResidentialWasteCollectionIntervalService;
                $modelNonResidentialWasteCollectionIntervalService->service_id = $key;
                $modelNonResidentialWasteCollectionIntervalService->non_residential_waste_collection_interval_id = $model->id;
                $modelNonResidentialWasteCollectionIntervalService->save(false);
              }
            }
            return $this->redirect(['non-residential']);
        }

        return $this->render('create-non-residential', [
            'model' => $model,
        ]);
    }
    public function actionUpdateNonResidential($id)
    {
        $model = $this->findModelNonResidential($id);
        $model->service_id = unserialize($model->service_id);
         // $model->building_type_available = unserialize($model->building_type_available);
        if ($model->load(Yii::$app->request->post()) ) {
          $model->service_id = serialize($model->service_id);
            $model->save();
            $service = unserialize($model->service_id);
            if($service)
            {
              $serviceList  = NonResidentialWasteCollectionIntervalService::find()->where(['non_residential_waste_collection_interval_id'=>$model->id])->all();
              foreach ($serviceList as $key => $value) {
                 $value->status = 0;
                 $value->save(false);
               } 
              foreach ($service as $key) {
                $modelNonResidentialWasteCollectionIntervalService = new NonResidentialWasteCollectionIntervalService;
                $modelNonResidentialWasteCollectionIntervalService->service_id = $key;
                $modelNonResidentialWasteCollectionIntervalService->non_residential_waste_collection_interval_id = $model->id;
                $modelNonResidentialWasteCollectionIntervalService->save(false);
              }
            return $this->redirect(['non-residential']);
        }
}
        return $this->render('create-non-residential', [
            'model' => $model,
        ]);
    }
     public function actionDeleteNonResidentialWasteType($id)
    {
        $model = new NonResidentialWasteCollectionInterval;
        $model->deleteType($id);
    }

  }
