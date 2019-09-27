<?php

namespace backend\controllers;

use Yii;
use backend\models\WasteCollectionMethod;
use backend\models\WasteCollectionMethodSearch;
use backend\models\WasteCollectionMethodBuildingType;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use backend\components\AccessPermission;
/**
 * WasteTypesController implements the CRUD actions for WasteType model.
 */
class WasteCollectionMethodsController extends Controller
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
                       'permissions' => ['waste-collection-methods-index'],
                   ],
                   [
                       'actions' => ['create'],
                       'allow' => true,
                       'permissions' => ['waste-collection-methods-create'],
                   ],
                   [
                       'actions' => ['update'],
                       'allow' => true,
                       'permissions' => ['waste-collection-methods-update'],
                   ],
                   [
                       'actions' => ['delete'],
                       'allow' => true,
                       'permissions' => ['waste-collection-methods-delete'],
                   ],
                   [
                       'actions' => ['view'],
                       'allow' => true,
                       'permissions' => ['waste-collection-methods-view'],
                   ],
                   [
                       'actions' => ['delete-waste-type'],
                       'allow' => true,
                       'permissions' => ['waste-collection-methods-delete-waste-type'],
                   ],
               ],
               'denyCallback' => function($rule, $action) {
                   return $this->goHome();
               }
           ],
       ];
    }

    /**
     * Lists all WasteType models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new WasteCollectionMethodSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single WasteType model.
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
     * Creates a new WasteType model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new WasteCollectionMethod();

        if ($model->load(Yii::$app->request->post())) {
           $list = $model->building_type;
            $model->building_type = serialize($model->building_type);
            $model->save();
            if($list):
            foreach ($list as  $value) {
                $modelWasteCollectionMethodBuildingType = new WasteCollectionMethodBuildingType();
                $modelWasteCollectionMethodBuildingType->building_type_id = $value;
                $modelWasteCollectionMethodBuildingType->waste_collection_method_id = $model->id;
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
     * Updates an existing WasteType model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->building_type = unserialize($model->building_type);
        if ($model->load(Yii::$app->request->post()) ) {
            $list = $model->building_type;
            $model->building_type = serialize($model->building_type);
            $model->save();
            if($list):
            WasteCollectionMethodBuildingType::deleteAll(['waste_collection_method_id'=>$model->id]);
            foreach ($list as  $value) {
                $modelWasteCollectionMethodBuildingType = new WasteCollectionMethodBuildingType();
                $modelWasteCollectionMethodBuildingType->building_type_id = $value;
                $modelWasteCollectionMethodBuildingType->waste_collection_method_id = $model->id;
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
     * Deletes an existing WasteType model.
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
     * Finds the WasteType model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return WasteType the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = WasteCollectionMethod::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
    public function actionDeleteWasteType($id)
    {
        $model = new WasteCollectionMethod;
        $model->deleteType($id);
    }
}
