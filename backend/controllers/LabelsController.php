<?php

namespace backend\controllers;

use Yii;
use backend\models\Labels;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use backend\components\AccessPermission;
use yii\filters\AccessControl;
use backend\models\Image;
use backend\models\Account;
use backend\models\Person;
use yii\web\UploadedFile;
/**
 * BuildingTypesController implements the CRUD actions for BuildingType model.
 */
class LabelsController extends Controller
{
    /**
     * @inheritdoc
     */
     // public function behaviors()
     // {
     //     return [
     //         'access' => [
     //             'class'        => AccessControl::className(),
     //             'only'         => ['index', 'create', 'update', 'view', 'view-details'],
     //             'ruleConfig' => [
     //                     'class' => AccessPermission::className(),
     //                 ],
     //             'rules'        => [
     //                 [
     //                     'actions' => ['index'],
     //                     'allow'   => true,
     //                     'permissions' => ['Labels-index']
     //                 ],
     //                 [
     //                     'actions' => ['view'],
     //                     'allow'   => true,
     //                     'permissions' => ['Labels-view']
     //                 ],
     //                 [
     //                     'actions' => ['create'],
     //                     'allow'   => true,
     //                     'permissions' => ['Labels-create']
     //                 ],
     //                 [
     //                     'actions' => ['update'],
     //                     'allow'   => true,
     //                     'permissions' => ['Labels-update']
     //                 ],
     //                 [
     //                     'actions' => ['delete'],
     //                     'allow'   => true,
     //                     'permissions' => ['Labels-delete']
     //                 ],
     //                 [
     //                     'actions' => ['delete-Labels'],
     //                     'allow'   => true,
     //                     'permissions' => ['Labels-delete-Labels']
     //                 ],
     //             ],
     //             'denyCallback' => function (
     //                 $rule,
     //                 $action
     //             )
     //             {
     //                 return $this->goHome();
     //             }
     //         ]
     //     ];
     // }

    /**
     * Lists all BuildingType models.
     * @return mixed
     */
    public function actionIndex()
    {
        $model = new Labels();
        $dataProvider = $model->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'model' => $model,
            'dataProvider' => $dataProvider,
        ]);
    }
    /**
     * Creates a new BuildingType model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Labels();
        $modelImage = new Image();

        if ($model->load(Yii::$app->request->post())) {
              $model->save(false);
            return $this->redirect(['index']);
        }

        return $this->render('_form', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing BuildingType model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post())) {
              $model->save(false);
            return $this->redirect(['index']);
        }

       return $this->render('_form', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing BuildingType model.
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
     * Finds the BuildingType model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return BuildingType the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Labels::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
    public function actionDeleteLabels($id)
    {
        $model = new Labels;
        $model->deleteLabel($id);
    }  
    
}
