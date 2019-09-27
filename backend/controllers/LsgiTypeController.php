<?php

namespace backend\controllers;

use Yii;
use backend\models\LsgiType;
use backend\models\LsgiTypeSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use backend\components\AccessPermission;

/**
 * IsgiTypeController implements the CRUD actions for IsgiType model.
 */
class LsgiTypeController extends Controller
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
                        'permissions' => ['lsgi-type-index'],
                    ],
                    [
                        'actions' => ['view'],
                        'allow' => true,
                        'permissions' => ['lsgi-type-view'],
                    ],
                    [
                        'actions' => ['create'],
                        'allow' => true,
                        'permissions' => ['lsgi-type-create'],
                    ],
                    [
                        'actions' => ['update'],
                        'allow' => true,
                        'permissions' => ['lsgi-type-update'],
                    ],
                    [
                        'actions' => ['delete'],
                        'allow' => true,
                        'permissions' => ['lsgi-type-delete'],
                    ],
                    [
                        'actions' => ['delete-type'],
                        'allow' => true,
                        'permissions' => ['lsgi-type-delete-type'],
                    ],
                ],
                'denyCallback' => function($rule, $action) {
                    return $this->goHome();
                }
            ],
        ];
     }

    /**
     * Lists all IsgiType models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new LsgiTypeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single IsgiType model.
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
     * Creates a new IsgiType model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new LsgiType();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing IsgiType model.
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
     * Deletes an existing IsgiType model.
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
     * Finds the IsgiType model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return IsgiType the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = LsgiType::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
     public function actionDeleteType($id)
    {
        $model = new LsgiType;
        $model->deleteType($id);
    }
}
