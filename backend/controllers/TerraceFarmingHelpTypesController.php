<?php

namespace backend\controllers;

use Yii;
use backend\models\TerraceFarmingHelpType;
use backend\models\TerraceFarmingHelpTypeSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use backend\components\AccessPermission;
/**
 * TerraceFarmingHelpTypesController implements the CRUD actions for TerraceFarmingHelpType model.
 */
class TerraceFarmingHelpTypesController extends Controller
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
                       'permissions' => ['terrace-farming-help-types-index'],
                   ],
                   [
                       'actions' => ['create'],
                       'allow' => true,
                       'permissions' => ['terrace-farming-help-types-create'],
                   ],
                   [
                       'actions' => ['update'],
                       'allow' => true,
                       'permissions' => ['terrace-farming-help-types-update'],
                   ],
                   [
                       'actions' => ['delete'],
                       'allow' => true,
                       'permissions' => ['terrace-farming-help-types-delete'],
                   ],
                   [
                       'actions' => ['view'],
                       'allow' => true,
                       'permissions' => ['terrace-farming-help-types-view'],
                   ],
                   [
                       'actions' => ['delete-terrace-farming-help-types'],
                       'allow' => true,
                       'permissions' => ['terrace-farming-help-types-delete-terrace-farming-help-type'],
                   ],
               ],
               'denyCallback' => function($rule, $action) {
                   return $this->goHome();
               }
           ],
       ];
    }

    /**
     * Lists all TerraceFarmingHelpType models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TerraceFarmingHelpTypeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TerraceFarmingHelpType model.
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
     * Creates a new TerraceFarmingHelpType model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new TerraceFarmingHelpType();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing TerraceFarmingHelpType model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing TerraceFarmingHelpType model.
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
     * Finds the TerraceFarmingHelpType model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TerraceFarmingHelpType the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TerraceFarmingHelpType::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
    public function actionDeleteTerraceFarmingHelpType($id)
    {
        $model = new TerraceFarmingHelpType;
        $model->deleteHelp($id);
    }
}
