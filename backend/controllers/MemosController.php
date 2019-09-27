<?php

namespace backend\controllers;

use Yii;
use backend\models\Memo;
use backend\models\PaymentCounter;
use backend\models\Account;
use backend\models\IncidentType;
use backend\models\Incident;
use backend\models\Lsgi;
use backend\models\Ward;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use  yii\web\Session;
use yii\filters\AccessControl;
use backend\components\AccessRule;
use yii\helpers\Json;
use backend\components\AccessPermission;

/**
 * MemoController implements the CRUD actions for Memo model.
 */
class MemosController extends Controller
{
    /**
     * {@inheritdoc}
     */
     public function behaviors()
         {

           return [
              'access' => [
                  'class' => AccessControl::className(),
                  'only' => ['index','create','update'],
                  'ruleConfig' => [
                          'class' => AccessPermission::className(),
                      ],
                  'rules' => [
                      [
                          'actions' => ['index'],
                          'allow' => true,
                          'permissions' => ['memos-index'],
                      ],
                      [
                          'actions' => ['create'],
                          'allow' => true,
                          'permissions' => ['memos-create'],
                      ],
                      [
                          'actions' => ['update'],
                          'allow' => true,
                          'permissions' => ['memos-update'],
                      ],
                      [
                          'actions' => ['is-paid'],
                          'allow' => true,
                          'permissions' => ['memo-is-paid'],
                      ],
                      [
                          'actions' => ['preview'],
                          'allow' => true,
                          'permissions' => ['?'],
                      ],
                      [
                          'actions' => ['memo-preview'],
                          'allow' => true,
                          'permissions' => ['?'],
                      ],
                      [
                          'actions' => ['search-memo'],
                          'allow' => true,
                          'permissions' => ['?'],
                      ],
                      [
                          'actions' => ['pay-memo'],
                          'allow' => true,
                          'permissions' => ['?'],
                      ],
                      [
                          'actions' => ['pay-memo-counter'],
                          'allow' => true,
                          'permissions' => ['memos-pay-memo-counter'],
                      ],
                      [
                          'actions' => ['payment-counter-admin'],
                          'allow' => true,
                          'permissions' => ['memos-payment-counter-admin'],
                      ],
                      [
                          'actions' => ['get-wards'],
                          'allow' => true,
                          'permissions' => ['memos-get-wards'],
                      ],
                      [
                          'actions' => ['delete-memo'],
                          'allow' => true,
                          'permissions' => ['memos-delete-memo'],
                      ],
                  ],
                  'denyCallback' => function($rule, $action) {
                      return $this->redirect('dashboard');
                  }
              ],
          ];
             return [
                 'access' => [
                     'class'        => AccessControl::className(),
                     'only'         => ['index','create','update'],
                     'ruleConfig'   => [
                       'class' => AccessRule::className(),
                     ],
                     'rules'        => [
                         [
                             'actions' => ['index','create','update'],
                             'allow'   => true,
                             'roles'   => ['super-admin','admin-lsgi']
                         ],
                         [
                             'actions' => ['preview','pay-memo','memo-preview','search-memo'],
                             'allow'   => true,
                             'roles'   => ['?']
                         ]
                     ],
                     'denyCallback' => function($rule, $action) {
                         return $this->goHome();
                     }
                 ]
             ];
         }

    /**
     * Lists all Memo models.
     * @return mixed
     */
    public function actionIndex()
    {
        // $modelMemo = new Memo();
        $updateSuccess = isset($_SESSION['updateSuccess']) ? $_SESSION['updateSuccess'] : null;
        if(isset($_SESSION['updateSuccess']))
          unset($_SESSION['updateSuccess']);
          $post   = yii::$app->request->post();
          $get    = yii::$app->request->get();
          $params = array_merge($post, $get);
          $vars   = [
              'keyword',
          ];
          $newParams = [];
          foreach ($vars as $param)
          {
              ${
                  $param}          = isset($params[$param]) ? $params[$param] : null;
              $newParams[$param] = ${
                  $param};
          }
          $keyword       =isset($params['id'])?$params['id']:null;
          $modelMemo = new Memo;
          $dataProvider  = $modelMemo->search(Yii::$app->request->queryParams,$keyword);
        // $dataProvider = new ActiveDataProvider([
        //     'query' => Memo::find()->where(['status' => 1])->orderBy(['id' => SORT_DESC]),
        // ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'modelMemo' => $modelMemo,
            'updateSuccess' => $updateSuccess
        ]);
    }

    /**
     * Displays a single Memo model.
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
     * Creates a new Memo model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Memo();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }
    public function actionPreview($id)
    {
      $this->layout = "memo-layout";
      $model = $this->findModel($id);
      return $this->render('preview',['model' => $model]);
    }
    public function actionPayMemo($id)
    {
      $this->layout = "memo-layout";
      $model = $this->findModel($id);
      return $this->render('pay-memo',[
        'model' => $model
      ]);
    }
    public function actionPayMemoCounter($id)
    {
      $modelUser = Yii::$app->user->identity;
      $userRole  = $modelUser->role;
      $model = $this->findModel($id);
      $modelLsgi = Lsgi::find()->where(['id'=>$model->lsgi_id,'status' => 1])->one();
      if($userRole == 'payment-counter-admin'){
        $modelCounter = PaymentCounter::find()
        ->innerJoin('payment_counter_account','payment_counter_account.payment_counter_id = payment_counter.id')
        ->where(['payment_counter.status' => 1,'payment_counter_account.status' => 1,'payment_counter_account.account_id'=> $modelUser->id])->all();

        $modelAccount = Account::getAllQuery()->where(['status' => 1,'role' => "payment-counter-admin",'id' => $modelUser->id])->all();
      }
      else{
        $modelAccount = Account::getAllQuery()->where(['status' => 1,'role' => "payment-counter-admin"])->all();
        $modelCounter = PaymentCounter::getAllQuery()->where(['status' => 1])->all();
      }
      $post = Yii::$app->request->post();
      $params = $post && $model->load($post);
      if($params){
        $counterId = $post['Memo']['payment_counter_id'];
        $accountId = $post['Memo']['payment_counter_account_id'];
        $paramsOk = $model->validate();
        if($paramsOk){
          $model->payment_counter_id = $counterId;
          $model->payment_counter_account_id = $accountId;
          $model->is_paid = 1;
          $model->payment_date = date('Y-m-d H:i:s');
          $model->save();
          $mail = Yii::$app->email->sendPaidEmail($model);
          $invoice = Yii::$app->email->sendPaidInvoice($model,$modelLsgi);
          return $this->redirect(['index']);
        }
      }
      return $this->render('pay-memo-counter',[
        'model' => $model,
        'modelAccount' => $modelAccount,
        'modelCounter' => $modelCounter,
      ]);
    }
    public function actionPaymentCounterAdmin() {
      $out = [];
      if (isset($_POST['depdrop_parents'])) {
      $parents = $_POST['depdrop_parents'];
      $paymentCounter = PaymentCounter::find()->where(['id'=>$parents[0]])->andWhere(['status'=>1])->one();
      $paymentCounterAdmin = Account::find()
      ->leftjoin('payment_counter_account','payment_counter_account.account_id = account.id')
      ->where(['payment_counter_account.payment_counter_id'=>$paymentCounter['id']])
      ->andWhere(['payment_counter_account.status'=>1,'account.status'=>1])->all();
// print_r($paymentCounterAdmin);exit;
      foreach ($paymentCounterAdmin as $id => $post) {
      $out[] = ['id' => $post['id'], 'name' => $post['username']];
     }
     echo Json::encode(['output' => $out, 'selected' => '']);
        }
      }
      public function actionGetWards() {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
        $parents = $_POST['depdrop_parents'];
        $lsgi = Lsgi::find()->where(['id'=>$parents[0]])->andWhere(['status'=>1])->one();
        $wards = Ward::find()
        ->where(['ward.lsgi_id'=>$lsgi['id']])
        ->andWhere(['ward.status'=>1])->all();
        foreach ($wards as $id => $post) {
        $out[] = ['id' => $post['id'], 'name' => $post['name']];
       }
       echo Json::encode(['output' => $out, 'selected' => '']);
          }
        }
    // public function actionMemoPreview(){
    //   // $this->layout = "memo-layout";
    //   $model = new Memo;
    //   $post = Yii::$app->request->post();
    //   $model->setScenario('search');
    //   $params = $post && $model->load($post);
    //   if($params){
    //     $paramsOk = $model->validate();
    //     if($paramsOk){
    //       $memoId = $model->id;
    //       // $memoId = isset($post['Memo']['id'])?$post['Memo']['id']:'';
    //       if($memoId){
    //           return $this->redirect(['pay-memo', 'id' => $memoId]);
    //       }
    //     }
    //   }
    // }
    public function actionSearchMemo(){
      $showSuccess = isset($_SESSION['showSuccess']) ? $_SESSION['showSuccess'] : null;
      if(isset($_SESSION['showSuccess']))
        unset($_SESSION['showSuccess']);
      $this->layout = "memo-layout";
      $model = new Memo;
      $post = Yii::$app->request->post();
      $model->setScenario('search');
      $params = $post && $model->load($post);
      if($params){
        $paramsOk = $model->validate();
        if($paramsOk){
          $memoId = $model->id;
          // $memoId = isset($post['Memo']['id'])?$post['Memo']['id']:'';
          if($memoId){
              return $this->redirect(['pay-memo', 'id' => $memoId]);
          }
        }
      }
      return $this->render('search-memo',[
        'model' => $model,
        'showSuccess'=>$showSuccess,
      ]
    );
    }
    public function actionViewMemo($id){
      $modelMemo = $this->findIncidentMemo($id);
      return $this->redirect(['update','id' => $modelMemo->id]);
    }
    /**
     * Updates an existing Memo model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $updateSuccess = false;
        $modelIncidentType = IncidentType::getAllQuery()->all();
        $modelIncident = Incident::getAllQuery()->all();
        $params =$model->load(Yii::$app->request->post());
        if ($params) {
          // print_r($_POST['Memo']['incident_type_id']);exit;

            $model->save();
            $session = Yii::$app->session;
            $session->set('updateSuccess', '1');
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
            'modelIncidentType' => $modelIncidentType,
            'modelIncident' => $modelIncident,
        ]);
    }
    public function actionIsPaid($id){
      $modelMemo = new Memo;
      $model = $this->findModel($id);
      $modelLsgi = Lsgi::find()->where(['id'=>$model->lsgi_id,'status' => 1])->one();
      if($model->is_paid == 0){
        $model->is_paid = 1;
      }else{
        $model->is_paid = 0;
      }
      $model->payment_date = date('Y-m-d H:i:s');
      $model->update(false);
      $mail = Yii::$app->email->sendPaidEmail($model);
      $invoice = Yii::$app->email->sendPaidInvoice($model,$modelLsgi);
      $session = Yii::$app->session;
      $session->set('showSuccess', '1');
      return $this->redirect(['search-memo']);
      $ret  = [
        "status" =>$model->is_paid
    ];

      return json_encode($ret);
    }

    /**
     * Deletes an existing Memo model.
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
     * Finds the Memo model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Memo the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        $model = Memo::find()->where(['id'=> $id,'status' => 1])->one();
        if ($model !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    public function findIncidentMemo($id){
      $modelMemo = Memo::find()->where(['incident_id' =>$id,'status' =>1])->one();
      return $modelMemo;
    }
    public function actionDeleteMemo($id)
    {
        $model = new Memo;
        $model->deleteMemo($id);
    }
}
