<?php

namespace backend\controllers;

use Yii;
use backend\models\State;
use backend\models\StateSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use backend\components\AccessPermission;
/**
 * StatesController implements the CRUD actions for State model.
 */
class StatesController extends Controller
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
                       'permissions' => ['states-index'],
                   ],
                   [
                       'actions' => ['create'],
                       'allow' => true,
                       'permissions' => ['states-create'],
                   ],
                   [
                       'actions' => ['update'],
                       'allow' => true,
                       'permissions' => ['states-update'],
                   ],
                   [
                       'actions' => ['delete'],
                       'allow' => true,
                       'permissions' => ['states-delete'],
                   ],
                   [
                       'actions' => ['view'],
                       'allow' => true,
                       'permissions' => ['states-view'],
                   ],
                   [
                       'actions' => ['delete-state'],
                       'allow' => true,
                       'permissions' => ['states-delete-state'],
                   ],
               ],
               'denyCallback' => function($rule, $action) {
                   return $this->goHome();
               }
           ],
       ];
    }

    /**
     * Lists all State models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new StateSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single State model.
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
     * Creates a new State model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new State();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing State model.
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
     * Deletes an existing State model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDeleteState($id)
    {
        $modelState = new State;
        $modelState->deleteState($id);
    }

    /**
     * Finds the State model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return State the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = State::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
