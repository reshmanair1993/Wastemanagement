<?php

namespace backend\controllers;

use Yii;
use backend\models\InoculamBagsRequest;
use backend\models\CustomerSelfService;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use backend\models\Image;
use yii\web\UploadedFile;

/**
 * ServicesController implements the CRUD actions for Service model.
 */
class SelfServicesController extends Controller
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
               'rules' => [
                   [
                       'actions' => ['index','create','update','view'],
                       'allow' => true,
                       'roles' => ['@'],
                   ],
               ],
               'denyCallback' => function($rule, $action) {
                   return $this->goHome();
               }
           ],
       ];
    }
    public function actionDeleteSelfService($id)
    {
        $model = new CustomerSelfService;
        $model->deleteSelfService($id);
    }
    public function actionDeleteInoculamBagsRequest($id)
    {
        $model = new InoculamBagsRequest;
        $model->deleteInoculamBagsRequest($id);
    }
    public function actionToggleStatusApprovedInoculamBagsRequest($id)
    {
         $model = $this->findModel($id);
        $status=$model->toggleStatusApprovedInoculamBagsRequest();
        echo json_encode(['status'=> $status]);
    }
    public function actionToggleStatusDisApprovedInoculamBagsRequest($id)
    {
         $model = $this->findModel($id);
        $status=$model->toggleStatusDisApprovedInoculamBagsRequest();
        echo json_encode(['status'=> $status]);
    }
    protected function findModel($id)
    {
        if (($model = InoculamBagsRequest::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
    protected function findSelfServiceModel($id)
    {
        if (($model = CustomerSelfService::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    /**
     * Lists all Service models.
     * @return mixed
     */
    public function actionIndex()
    {
        $post   = yii::$app->request->post();
        $get    = yii::$app->request->get();
        $params = array_merge($post, $get);
        $vars   = [

            'name',
            'service',
            'mrc',
            'from',
            'to',
            'status',
        ];
        $newParams = [];
        $session   = Yii::$app->session;
        foreach ($vars as $param)
        {
            ${
                $param}          = isset($params[$param]) ? $params[$param] : null;
            $newParams[$param] = ${
                $param};
        }
         $keyword      = null;
        $service         = null;
        $status         = null;
        $from         = null;
        $to         = null;
        $mrc         = null;
        $post         = Yii::$app->request->post();
        if (isset($post['service']))
        {
            $service = $post['service'];
        }
        if (isset($post['status']))
        {
            $status = $post['status'];
        }
        if (isset($post['mrc']))
        {
            $mrc = $post['mrc'];
        }
        if (isset($_POST['name']))
        {
            $keyword = $_POST['name'];
        }
        if (isset($_POST['from']))
        {
            $from = $_POST['from'];
        }
        if (isset($_POST['to']))
        {
            $to = $_POST['to'];
        }
        $modelCustomerSelfService = new CustomerSelfService;
        $dataProvider = $modelCustomerSelfService->search(Yii::$app->request->queryParams,$keyword,$service,$from,$to,$mrc);

        return $this->render('index', [
            // 'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'modelCustomerSelfService' => $modelCustomerSelfService,
        ]);
    }
    public function actionInoculamBagsRequests()
    {
        $post   = yii::$app->request->post();
        $get    = yii::$app->request->get();
        $params = array_merge($post, $get);
        $vars   = [

            'name',
            'mrc',
            'from',
            'to',
            'status',
        ];
        $newParams = [];
        $session   = Yii::$app->session;
        foreach ($vars as $param)
        {
            ${
                $param}          = isset($params[$param]) ? $params[$param] : null;
            $newParams[$param] = ${
                $param};
        }
         $keyword      = null;
        $service         = null;
        $status         = null;
        $from         = null;
        $to         = null;
        $mrc         = null;
        $post         = Yii::$app->request->post();
        if (isset($post['service']))
        {
            $service = $post['service'];
        }
        if (isset($post['status']))
        {
            $status = $post['status'];
        }
        if (isset($post['mrc']))
        {
            $mrc = $post['mrc'];
        }
        if (isset($_POST['name']))
        {
            $keyword = $_POST['name'];
        }
        if (isset($_POST['from']))
        {
            $from = $_POST['from'];
        }
        if (isset($_POST['to']))
        {
            $to = $_POST['to'];
        }
        $modelInoculamBagsRequest = new InoculamBagsRequest;
        $dataProvider = $modelInoculamBagsRequest->search(Yii::$app->request->queryParams,$keyword,$service,$from,$to,$mrc);

        return $this->render('inoculam-bags-request', [
            // 'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'modelInoculamBagsRequest' => $modelInoculamBagsRequest,
        ]);
    }
    public function actionUpdateInoculamBagRequest($id)
    {
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post())) {
          $post = Yii::$app->request->post();
            $model->save(false);
             return $this->redirect(['inoculam-bags-requests']);
          }
           

        return $this->render('update-inoculam-bag-request', [
            'model' => $model,
        ]);
    }
    public function actionUpdateSelfService($id)
    {
        $model = $this->findSelfServiceModel($id);
        if ($model->load(Yii::$app->request->post())) {
          $post = Yii::$app->request->post();
            $model->save(false);
             return $this->redirect(['index']);
          }
           

        return $this->render('update-self-service', [
            'model' => $model,
        ]);
    }
}
