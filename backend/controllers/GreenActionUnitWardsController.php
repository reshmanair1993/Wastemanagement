<?php

namespace backend\controllers;

use Yii;
use backend\models\GreenActionUnitWard;
use backend\models\GreenActionUnitWardSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use backend\components\AccessPermission;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

/**
 * GreenActionUnitWardsController implements the CRUD actions for GreenActionUnitWard model.
 */
class GreenActionUnitWardsController extends Controller
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
                         'permissions' => ['green-action-unit-wards-index']
                     ],
                     [
                         'actions' => ['create'],
                         'allow'   => true,
                         'permissions' => ['green-action-unit-wards-create']
                     ],
                     [
                         'actions' => ['update'],
                         'allow'   => true,
                         'permissions' => ['green-action-unit-wards-update']
                     ],
                     [
                         'actions' => ['view'],
                         'allow'   => true,
                         'permissions' => ['green-action-unit-wards-view']
                     ],
                     [
                         'actions' => ['delete'],
                         'allow'   => true,
                         'permissions' => ['green-action-unit-wards-delete']
                     ],
                     [
                         'actions' => ['delete-green-action-unit-ward'],
                         'allow'   => true,
                         'permissions' => ['green-action-unit-wards-delete-green-action-unit-ward']
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
     * Lists all GreenActionUnitWard models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new GreenActionUnitWardSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single GreenActionUnitWard model.
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
     * Creates a new GreenActionUnitWard model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new GreenActionUnitWard();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing GreenActionUnitWard model.
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
     * Deletes an existing GreenActionUnitWard model.
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
     * Finds the GreenActionUnitWard model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return GreenActionUnitWard the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = GreenActionUnitWard::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
    public function actionDeleteGreenActionUnitWard($id)
    {
        $model = new GreenActionUnitWard;
        $model->deleteUnit($id);
    }
}
