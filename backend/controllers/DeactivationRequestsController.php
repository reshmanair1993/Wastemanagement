<?php

namespace backend\controllers;

use Yii;
use backend\models\DeactivationRequest;
use backend\models\DeactivationRequestSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use backend\components\AccessPermission;
use yii\filters\AccessControl;
/**
 * DeactivationRequestsController implements the CRUD actions for DeactivationRequest model.
 */
class DeactivationRequestsController extends Controller
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
                         'permissions' => ['deactivation-requests-index']
                     ],
                     [
                         'actions' => ['view'],
                         'allow'   => true,
                         'permissions' => ['deactivation-requests-view']
                     ],
                     [
                         'actions' => ['update'],
                         'allow'   => true,
                         'permissions' => ['deactivation-requests-update']
                     ],
                     [
                         'actions' => ['delete'],
                         'allow'   => true,
                         'permissions' => ['deactivation-requests-delete']
                     ],
                     [
                         'actions' => ['toggle-status-banned'],
                         'allow'   => true,
                         'permissions' => ['deactivation-requests-toggle-status-banned']
                     ],
                     [
                         'actions' => ['delete-deactivation-request'],
                         'allow'   => true,
                         'permissions' => ['deactivation-requests-delete-deactivation-request']
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
     * Lists all DeactivationRequest models.
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
        $searchModel = new DeactivationRequestSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $keyword, $ward, $lsgi, $district, $from, $to);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single DeactivationRequest model.
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
     * Creates a new DeactivationRequest model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new DeactivationRequest();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing DeactivationRequest model.
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
     * Deletes an existing DeactivationRequest model.
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
     * Finds the DeactivationRequest model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return DeactivationRequest the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DeactivationRequest::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
    public function actionToggleStatusBanned($id)
      {
        $modelDeactivationRequest = $this->findModel($id);
        $status=$modelDeactivationRequest->toggleStatusbanned();
        echo json_encode(['status'=> $status]);
    }
    public function actionDeleteDeactivationRequest($id)
    {
        $model = new DeactivationRequest;
        $model->deleteRequest($id);
    }
}
