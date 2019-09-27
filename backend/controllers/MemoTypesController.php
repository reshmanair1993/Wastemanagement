<?php

namespace backend\controllers;

use Yii;
use backend\models\MemoType;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use backend\components\AccessRule;
use backend\components\AccessPermission;

/**
 * MemoTypesController implements the CRUD actions for MemoType model.
 */
class MemoTypesController extends Controller
{
    /**
     * {@inheritdoc}
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
                       'permissions' => ['memo-types-index'],
                   ],
                   [
                       'actions' => ['create'],
                       'allow' => true,
                       'permissions' => ['memo-types-create'],
                   ],
                   [
                       'actions' => ['update'],
                       'allow' => true,
                       'permissions' => ['memo-types-update'],
                   ],
                   [
                       'actions' => ['delete'],
                       'allow' => true,
                       'permissions' => ['memo-types-delete'],
                   ],
                   [
                       'actions' => ['view'],
                       'allow' => true,
                       'permissions' => ['memo-types-view'],
                   ],
                   [
                       'actions' => ['delete-memo-type'],
                       'allow' => true,
                       'permissions' => ['memo-types-delete-memo-type'],
                   ],
               ],
               'denyCallback' => function($rule, $action) {
                   return $this->redirect('dashboard');
               }
           ],
       ];
    }

    /**
     * Lists all MemoType models.
     * @return mixed
     */
    public function actionIndex()
    {
      $showSuccess = isset($_SESSION['showSuccess']) ? $_SESSION['showSuccess'] : null;
      if(isset($_SESSION['showSuccess']))
        unset($_SESSION['showSuccess']);
      $updateSuccess = isset($_SESSION['updateSuccess']) ? $_SESSION['updateSuccess'] : null;
      if(isset($_SESSION['updateSuccess']))
        unset($_SESSION['updateSuccess']);
      $dataProvider = new ActiveDataProvider([
          'query' => MemoType::getAllQuery(),
      ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'showSuccess'=>$showSuccess,
            'updateSuccess'=>$updateSuccess,
        ]);
    }

    /**
     * Displays a single MemoType model.
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
     * Creates a new MemoType model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new MemoType();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $session = Yii::$app->session;
            $session->set('showSuccess', '1');
            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing MemoType model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
          $session = Yii::$app->session;
          $session->set('updateSuccess', '1');
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing MemoType model.
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
     * Finds the MemoType model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return MemoType the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = MemoType::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    public function actionDeleteMemoType($id)
    {
        $model = new MemoType;
        $model->deleteMemoType($id);
    }
}
