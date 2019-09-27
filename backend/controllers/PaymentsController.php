<?php

namespace backend\controllers;

use Yii;
use backend\models\Payment;
use backend\models\PaymentRequest;
use backend\models\PaymentSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use backend\components\AccessPermission;
/**
 * PaymentsController implements the CRUD actions for Payment model.
 */
class PaymentsController extends Controller
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
                       'permissions' => ['payments-index'],
                   ],
                   [
                       'actions' => ['create'],
                       'allow' => true,
                       'permissions' => ['payments-create'],
                   ],
                   [
                       'actions' => ['update'],
                       'allow' => true,
                       'permissions' => ['payments-update'],
                   ],
                   [
                       'actions' => ['delete'],
                       'allow' => true,
                       'permissions' => ['payments-delete'],
                   ],
                   [
                       'actions' => ['view'],
                       'allow' => true,
                       'permissions' => ['payments-view'],
                   ],
               ],
               'denyCallback' => function($rule, $action) {
                   return $this->goHome();
               }
           ],
       ];
    }

    /**
     * Lists all Payment models.
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
        $searchModel = new PaymentSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $keyword, $ward, $lsgi, $district, $from, $to);
        $modelUser  = Yii::$app->user->identity;
        $associations = Yii::$app->rbac->getAssociations($modelUser->id);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'associations' => $associations,
        ]);
    }

    /**
     * Displays a single Payment model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        // Yii::$app->cache->flush();
        $model        = $this->findModel($id);
        $modelPaymentRequest = new PaymentRequest;
        $dataProvider = new ActiveDataProvider(
        [
           'query' => $modelPaymentRequest->getAllQuery()
           ->andWhere(['payment_request.id' => $model->payment_request_id]),
           'pagination' => false,
           'sort'=> ['defaultOrder' => ['id' => SORT_DESC]]
        ]);

        $params       = [
            'model'        => $model,
            'modelPaymentRequest' => $modelPaymentRequest,
            'dataProvider' => $dataProvider,
        ];

        return $this->render('view', [
            'params' => $params
        ]);
    }

    /**
     * Creates a new Payment model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Payment();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Payment model.
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
     * Deletes an existing Payment model.
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
     * Finds the Payment model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Payment the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Payment::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
