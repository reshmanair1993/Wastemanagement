<?php

namespace backend\controllers;

use Yii;
use backend\models\Chalan;
use backend\components\AccessPermission;
use backend\models\IncidentType;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * ChalanController implements the CRUD actions for Chalan model.
 */
class ChalansController extends Controller
{
    /**
     * {@inheritdoc}
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
                         'permissions' => ['chalans-index']
                     ],
                     [
                         'actions' => ['create'],
                         'allow'   => true,
                         'permissions' => ['chalans-create']
                     ],
                     [
                         'actions' => ['update'],
                         'allow'   => true,
                         'permissions' => ['chalans-update']
                     ],
                     [
                         'actions' => ['delete'],
                         'allow'   => true,
                         'permissions' => ['chalans-delete']
                     ],
                     [
                         'actions' => ['view'],
                         'allow'   => true,
                         'permissions' => ['chalans-view']
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
     * Lists all Chalan models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Chalan::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Chalan model.
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
     * Creates a new Chalan model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Chalan();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Chalan model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $modelIncidentType = IncidentType::getAllQuery()->all();;
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
            'modelIncidentType' => $modelIncidentType
        ]);
    }

    /**
     * Deletes an existing Chalan model.
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
     * Finds the Chalan model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Chalan the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Chalan::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
