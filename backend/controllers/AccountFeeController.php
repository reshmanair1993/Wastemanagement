<?php

namespace backend\controllers;

use Yii;
use backend\models\AccountFee;
use backend\models\AccountFeeSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * AccountFeeController implements the CRUD actions for AccountFee model.
 */
class AccountFeeController extends Controller
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
                'rules'        => [
                    [
                        'actions' => ['index', 'create', 'update', 'view', 'view-details'],
                        'allow'   => true,
                        'roles'   => ['@']
                    ]
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
     * Lists all AccountFee models.
     * @return mixed
     */
    public function actionIndex()
    {
        $post   = yii::$app->request->post();
        $get    = yii::$app->request->get();
        $params = array_merge($post, $get);
        $vars   = [

            'name',
            'keyword',
            'district',
            'ward',
            'door',
            'lsgi',
            'surveyor',
            'from',
            'to'
        ];
        $newParams = [];
        foreach ($vars as $param)
        {
            ${
                $param}          = isset($params[$param]) ? $params[$param] : null;
            $newParams[$param] = ${
                $param};
        }
        $keyword       =isset($params['name'])?$params['name']:null;
        $district      = isset($params['district'])?$params['district']:null;
        $ward          = isset($params['ward'])?$params['ward']:null;
        $lsgi          = isset($params['lsgi'])?$params['lsgi']:null;;
        $from          = isset($params['from'])?$params['from']:null;
        $to            = isset($params['to'])?$params['to']:null;
        $from              = $from ? \Yii::$app->formatter->asDatetime($from, "php:Y-m-d H:i:s") : '';
        $to                = $to ? \Yii::$app->formatter->asDatetime($to, "php:Y-m-d 23:59:59") : '';
        $searchModel = new AccountFeeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $keyword, $ward, $lsgi, $district, $from, $to);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single AccountFee model.
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
     * Creates a new AccountFee model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new AccountFee();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing AccountFee model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing AccountFee model.
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
     * Finds the AccountFee model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return AccountFee the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = AccountFee::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
    public function actionDeleteAccountFee($id)
    {
        $model = new AccountFee;
        $model->deleteFee($id);
    }
}
