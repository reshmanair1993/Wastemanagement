<?php

namespace backend\controllers;

use Yii;
use backend\models\AdministrationType;
use backend\models\AdministrationTypeSearch;
use yii\web\Controller;
use backend\components\AccessPermission;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * AdministrationTypeController implements the CRUD actions for AdministrationType model.
 */
class AdministrationTypeController extends Controller
{
    /**
     * {@inheritdoc}
     */
    //  public function behaviors()
    // {
    //     return [
    //        'access' => [
    //            'class' => AccessControl::className(),
    //            'only' => ['index','create','update','view'],
    //            'rules' => [
    //                [
    //                    // 'actions' => ['index','create','update','view'],
    //                    'allow' => true,
    //                    'roles' => ['@'],
    //                    'permissions' => [
    //                      'administration-type-index','administration-type-create',
    //                      'administration-type-update','administration-type-delete-administration-type'
    //                    ]
    //                ],
    //            ],
    //            'denyCallback' => function($rule, $action) {
    //                return $this->goHome();
    //            }
    //        ],
    //    ];
    // }

    public function behaviors()
    {
        return [
            'access' => [
                'class'        => AccessControl::className(),
                'only'         => ['index','create','update','view'],
                'ruleConfig' => [
                        'class' => AccessPermission::className(),
                    ],
                'rules'        => [
                    [
                        'actions' => ['index'],
                        'allow'   => true,
                        'permissions' => ['administration-type-index']
                    ],
                    [
                        'actions' => ['create'],
                        'allow'   => true,
                        'permissions' => ['administration-type-create']
                    ],
                    [
                        'actions' => ['update'],
                        'allow'   => true,
                        'permissions' => ['administration-type-update']
                    ],
                    [
                        'actions' => ['delete-administration-type'],
                        'allow'   => true,
                        'permissions' => ['administration-type-delete-administration-type']
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
     * Lists all AdministrationType models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AdministrationTypeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single AdministrationType model.
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
     * Creates a new AdministrationType model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new AdministrationType();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing AdministrationType model.
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
     * Deletes an existing AdministrationType model.
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
     * Finds the AdministrationType model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return AdministrationType the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = AdministrationType::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
    public function actionDeleteAdministrationType($id)
    {
        $model = new AdministrationType;
        $model->deleteType($id);
    }
}
