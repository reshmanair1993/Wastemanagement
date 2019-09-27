<?php

namespace backend\controllers;

use Yii;
use backend\models\TradingType;
use backend\models\TradingTypeSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use backend\components\AccessPermission;
/**
 * TradingTypesController implements the CRUD actions for TradingType model.
 */
class TradingTypesController extends Controller
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
                       'permissions' => ['trading-types-index'],
                   ],
                   [
                       'actions' => ['create'],
                       'allow' => true,
                       'permissions' => ['trading-types-create'],
                   ],
                   [
                       'actions' => ['update'],
                       'allow' => true,
                       'permissions' => ['trading-types-update'],
                   ],
                   [
                       'actions' => ['delete'],
                       'allow' => true,
                       'permissions' => ['trading-types-delete'],
                   ],
                   [
                       'actions' => ['view'],
                       'allow' => true,
                       'permissions' => ['trading-types-view'],
                   ],
                   [
                       'actions' => ['delete-trading-type'],
                       'allow' => true,
                       'permissions' => ['trading-types-delete-trading-type'],
                   ],
               ],
               'denyCallback' => function($rule, $action) {
                   return $this->goHome();
               }
           ],
       ];
    }
    /**
     * Lists all TradingType models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TradingTypeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TradingType model.
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
     * Creates a new TradingType model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new TradingType();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing TradingType model.
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
     * Deletes an existing TradingType model.
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
     * Finds the TradingType model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TradingType the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TradingType::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
    public function actionDeleteTradingType($id)
    {
        $model = new TradingType;
        $model->deleteType($id);
    }
}
