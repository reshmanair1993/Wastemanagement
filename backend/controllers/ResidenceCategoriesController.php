<?php

namespace backend\controllers;

use Yii;
use backend\models\ResidenceCategory;
use backend\models\BuildingType;
use backend\models\BuildingTypeSearch;
use backend\models\ResidenceCategorySearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use backend\components\AccessPermission;
/**
 * ResidenceCategoriesController implements the CRUD actions for ResidenceCategory model.
 */
class ResidenceCategoriesController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
           'access' => [
               'class' => AccessControl::className(),
               'only' => ['index','create','update','view','delete-category'],
               'ruleConfig' => [
                       'class' => AccessPermission::className(),
                   ],
               'rules' => [
                   [
                       'actions' => ['index'],
                       'allow' => true,
                       'permissions' => ['residence-categories-index'],
                   ],
                   [
                       'actions' => ['create'],
                       'allow' => true,
                       'permissions' => ['residence-categories-create'],
                   ],
                   [
                       'actions' => ['update'],
                       'allow' => true,
                       'permissions' => ['residence-categories-update'],
                   ],
                   [
                       'actions' => ['delete-category'],
                       'allow' => true,
                       'permissions' => ['residence-categories-delete-category'],
                   ],
                   [
                       'actions' => ['view'],
                       'allow' => true,
                       'permissions' => ['residence-categories-view'],
                   ],
               ],
               'denyCallback' => function($rule, $action) {
                   return $this->goHome();
               }
           ],
       ];
    }

    /**
     * Lists all ResidenceCategory models.
     * @return mixed
     */
    public function actionIndex()
    {
        $keyword      = null;
        if (isset($_POST['name']))
        {
            $keyword = $_POST['name'];
        }
        $searchModel = new ResidenceCategorySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,$keyword);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ResidenceCategory model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $modelBuildingType = new BuildingType;
        $searchModel = new BuildingTypeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,$model->id);
        $params = [
            'model' => $model,
            'modelBuildingType' => $modelBuildingType,
            'dataProvider' => $dataProvider,
       
      ];
      return $this->render('view', [
            'params' => $params,
        ]);
    }

    /**
     * Creates a new ResidenceCategory model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ResidenceCategory();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect('index');
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing ResidenceCategory model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect('index');
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing ResidenceCategory model.
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
     * Finds the ResidenceCategory model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ResidenceCategory the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ResidenceCategory::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
    public function actionDeleteCategory($id)
    {
        $model = new ResidenceCategory;
        $model->deleteCategory($id);
    }
}
