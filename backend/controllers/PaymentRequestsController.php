<?php

namespace backend\controllers;

use Yii;
use backend\models\PaymentRequest;
use backend\models\Payment;
use backend\models\PaymentRequestSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use backend\components\AccessPermission;
/**
 * PaymentRequestsController implements the CRUD actions for PaymentRequest model.
 */
class PaymentRequestsController extends Controller
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
                       'permissions' => ['payment-requests-index'],
                   ],
                   [
                       'actions' => ['create'],
                       'allow' => true,
                       'permissions' => ['payment-requests-create'],
                   ],
                   [
                       'actions' => ['update'],
                       'allow' => true,
                       'permissions' => ['payment-requests-update'],
                   ],
                   [
                       'actions' => ['delete'],
                       'allow' => true,
                       'permissions' => ['payment-requests-delete'],
                   ],
                   [
                       'actions' => ['view'],
                       'allow' => true,
                       'permissions' => ['payment-requests-view'],
                   ],
                   [
                       'actions' => ['delete-request'],
                       'allow' => true,
                       'permissions' => ['payment-requests-delete-request'],
                   ],
               ],
               'denyCallback' => function($rule, $action) {
                   return $this->goHome();
               }
           ],
       ];
    }

    /**
     * Lists all PaymentRequest models.
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
        $searchModel = new PaymentRequestSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $keyword, $ward, $lsgi, $district, $from, $to);
         $modelUser  = Yii::$app->user->identity;
        $associations = Yii::$app->rbac->getAssociations($modelUser->id);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'associations' => $associations
        ]);
    }

    /**
     * Displays a single PaymentRequest model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        // Yii::$app->cache->flush();
        $model        = $this->findModel($id);
        $modelPayments = new Payment;
        $dataProvider = new ActiveDataProvider(
        [
           'query' => $modelPayments->getAllQuery()
           ->andWhere(['payment.payment_request_id' => $model->id]),
           'pagination' => false,
           'sort'=> ['defaultOrder' => ['id' => SORT_DESC]]
        ]);

        $params       = [
            'model'        => $model,
            'modelPayments' => $modelPayments,
            'dataProvider' => $dataProvider,
        ];

        return $this->render('view', [
            'params' => $params
        ]);
    }

    /**
     * Creates a new PaymentRequest model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new PaymentRequest();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing PaymentRequest model.
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
     * Deletes an existing PaymentRequest model.
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
     * Finds the PaymentRequest model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return PaymentRequest the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PaymentRequest::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
    public function actionDeleteRequest($id)
    {
        $model = new Payment;
        $model->deleteRequest($id);
    }
}
