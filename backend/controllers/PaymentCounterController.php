<?php

namespace backend\controllers;

use Yii;
use backend\models\PaymentCounter;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use backend\models\Lsgi;
use backend\models\Account;
use backend\models\Person;
use backend\models\AccountWard;
use backend\models\Ward;
use backend\models\PaymentCounterAccount;
use yii\helpers\Json;
use yii\filters\AccessControl;
use backend\components\AccessPermission;
/**
 * PaymentCounterController implements the CRUD actions for PaymentCounter model.
 */
class PaymentCounterController extends Controller
{
    /**
     * {@inheritdoc}
     */
   public function behaviors()
    {
        return [
           'access' => [
               'class' => AccessControl::className(),
               'only' => ['index','create','update','view',' create-payment-counter-admin','assign-payment-counter-admin','assign-counter-admin','delete-payment-counter','delete-payment-counter-account'],
               'ruleConfig' => [
                       'class' => AccessPermission::className(),
                   ],
               'rules' => [
                   [
                       'actions' => ['index'],
                       'allow' => true,
                       'permissions' => ['payment-counter-index'],
                   ],
                   [
                       'actions' => ['create'],
                       'allow' => true,
                       'permissions' => ['payment-counter-create'],
                   ],
                   [
                       'actions' => ['update'],
                       'allow' => true,
                       'permissions' => ['payment-counter-update'],
                   ],
                   [
                       'actions' => ['delete'],
                       'allow' => true,
                       'permissions' => ['payment-counter-delete'],
                   ],
                   [
                       'actions' => ['view'],
                       'allow' => true,
                       'permissions' => ['payment-counter-view'],
                   ],
                   [
                       'actions' => ['create-payment-counter-admin'],
                       'allow' => true,
                       'permissions' => ['payment-counter-create-payment-counter-admin'],
                   ],
                   [
                       'actions' => ['assign-payment-counter-admin'],
                       'allow' => true,
                       'permissions' => ['payment-counter-assign-payment-counter-admin'],
                   ],
                   [
                       'actions' => ['assign-counter-admin'],
                       'allow' => true,
                       'permissions' => ['payment-counter-assign-counter-admin'],
                   ],
                   [
                       'actions' => ['delete-payment-counter'],
                       'allow' => true,
                       'permissions' => ['payment-counter-delete-payment-counter'],
                   ],
                   [
                       'actions' => ['delete-payment-counter-account'],
                       'allow' => true,
                       'permissions' => ['payment-counter-delete-payment-counter-account'],
                   ],
               ],
               'denyCallback' => function($rule, $action) {
                   return $this->goHome();
               }
           ],
       ];
    }

    /**
     * Lists all PaymentCounter models.
     * @return mixed
     */
    public function actionIndex()
    {
      $modelUser = Yii::$app->user->identity;
      $userRole  = $modelUser->role;

      $showSuccess = isset($_SESSION['showSuccess']) ? $_SESSION['showSuccess'] : null;
      if(isset($_SESSION['showSuccess']))
        unset($_SESSION['showSuccess']);
      $updateSuccess = isset($_SESSION['updateSuccess']) ? $_SESSION['updateSuccess'] : null;
      if(isset($_SESSION['updateSuccess']))
        unset($_SESSION['updateSuccess']);
      if ($userRole == 'admin-lsgi')
      {
        $query = PaymentCounter::getAllQuery()
        ->where(['lsgi_id' => $modelUser->lsgi_id,'status' => 1]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
      }
      else {
        $dataProvider = new ActiveDataProvider([
            'query' => PaymentCounter::getAllQuery(),
        ]);
      }
      return $this->render('index', [
          'dataProvider' => $dataProvider,
          'showSuccess'=>$showSuccess,
          'updateSuccess'=>$updateSuccess,
      ]);
    }

    /**
     * Displays a single PaymentCounter model.
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
     * Creates a new PaymentCounter model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new PaymentCounter();
        $user = Yii::$app->user->identity->id;
        $modelUser = Yii::$app->user->identity;
        $userRole  = $modelUser->role;
        $modelAccount = Account::find()->where(['id'=>$user,'status'=>1])->one();
        // print_r($modelAccount->role);exit;
        $modelLsgi = Lsgi::find()->where(['status' => 1])->all();

        $params = Yii::$app->request->post();
        $paramsOk = $params && $model->load($params);
        if($paramsOk){
          if ($userRole == 'admin-lsgi'){
            $model->lsgi_id = $modelUser->lsgi_id;
          }
          $model->save(false);
          $session = Yii::$app->session;
          $session->set('showSuccess', '1');
          return $this->redirect(['index']);
        }
        return $this->render('create', [
            'model' => $model,
            'modelLsgi' => $modelLsgi,
        ]);
    }
    public function actionCreatePaymentCounterAdmin()
    {
        $model = new Account();
        $modelPerson = new Person;
        $modelAccountWard = new AccountWard;
        $modelWard = Ward::getAllQuery()->all();
        $modelUser = Yii::$app->user->identity;
        $userRole  = $modelUser->role;
        $user = Yii::$app->user->identity->id;
        $modelAccount = Account::find()->where(['id'=>$user,'status'=>1])->one();
        $post = Yii::$app->request->post();
        // print_r($_POST);exit;
        $paramsOk = $model->load($post) && $modelPerson->load($post) && $modelAccountWard->load($post);
        // $ward_id = $model->lsgi_id;
        // print_r($post);exit;
        if ($paramsOk) {
            $personOk = $modelPerson->validate();
            // print_r($modelPerson->errors);exit;
              $modelPerson->save(false);
              $model->username = $modelPerson->email;
              $model->setScenario('create-technician');
              $accountOk = $model->validate();
              if($accountOk){
                $model->person_id = $modelPerson->id;
                $model->role = 'payment-counter-admin';
                $model->hashPassword();
                // $modelLsgi = $model->getLsgiId($ward_id);
                // if($modelLsgi)
                //   $model->lsgi_id = $modelLsgi->lsgi_id;
                $model->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
                if ($userRole == 'admin-lsgi'){
                  $model->district_id = $modelAccount->district_id;
                  $model->assembly_constituency_id = $modelAccount->assembly_constituency_id;
                  $model->block_id = $modelAccount->block_id;
                  $model->lsgi_id = $modelUser->lsgi_id;
                }
                $model->save(false);
                Yii::$app->rbac->assignAuthRole('payment-counter-admin',$model->id);
                $session = Yii::$app->session;
                $session->set('counterAdminSuccess', '1');
                $modelAccountWard->account_id = $model->id;
                $modelAccountWard->save(false);
                return $this->redirect(['index']);
              }
        }

        return $this->render('create-payment-counter-admin', [
            'model' => $model,
            'modelPerson' => $modelPerson,
            'modelWard' => $modelWard,
            'modelAccountWard' => $modelAccountWard
        ]);
    }
    public function actionAssignPaymentCounterAdmin()
    {
      $modelUser = Yii::$app->user->identity;
      $userRole  = $modelUser->role;

      $model = new PaymentCounterAccount();
      if ($userRole == 'admin-lsgi'){
        $modelAccount = Account::getAllQuery()->where(['status' => 1,'role' => "payment-counter-admin",'lsgi_id' => $modelUser->lsgi_id])->all();
        $modelCounter = PaymentCounter::getAllQuery()->where(['status' => 1,'lsgi_id' => $modelUser->lsgi_id])->all();
      }
      else{
        $modelAccount = Account::getAllQuery()->where(['status' => 1,'role' => "payment-counter-admin"])->all();
        $modelCounter = PaymentCounter::getAllQuery()->where(['status' => 1])->all();
      }  
      $params = Yii::$app->request->post();
      $paymentCounterAccountOk = $model->load($params);
      $paramsOk = $paymentCounterAccountOk && $model->validate();
      $query = $model->getAllQuery();
      $dataProvider = new ActiveDataProvider([
         'query' => $query,
        ]);
      if ($paramsOk) {
        $model->save(false);
        $session = Yii::$app->session;
        $session->set('userSuccess', '1');
        return $this->redirect(['index']);
      }

      return $this->render('assign-payment-counter-admin', [
          'model' => $model,
          'modelAccount' => $modelAccount,
          'modelCounter' => $modelCounter,
          'dataProvider' => $dataProvider
      ]);
    }
    /**
     * Updates an existing PaymentCounter model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    // public function actionUpdate($id)
    // {
    //     $model = $this->findModel($id);
    //
    //     if ($model->load(Yii::$app->request->post()) && $model->save()) {
    //         return $this->redirect(['view', 'id' => $model->id]);
    //     }
    //
    //     return $this->render('update', [
    //         'model' => $model,
    //     ]);
    // }
    public function actionAssignCounterAdmin() {
      $out = [];
      if (isset($_POST['depdrop_parents'])) {
      $parents = $_POST['depdrop_parents'];
      $paymentCounter = PaymentCounter::find()->where(['id'=>$parents[0]])->andWhere(['status'=>1])->one();
      $paymentCounterAdmin = Account::find()
      ->where(['lsgi_id'=>$paymentCounter['lsgi_id'],'role'=>'payment-counter-admin'])->all();
// print_r($paymentCounterAdmin);exit;
      foreach ($paymentCounterAdmin as $id => $post) {
      $out[] = ['id' => $post['id'], 'name' => $post['username']];
     }
     echo Json::encode(['output' => $out, 'selected' => '']);
        }
      }
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $modelLsgi = Lsgi::find()->where(['status' => 1])->all();
        // $modelPaymentCounter = PaymentCounter::find()->where(['status' => 1])->all();
        $modelUser = Yii::$app->user->identity;
        $userRole  = $modelUser->role;
        $params = Yii::$app->request->post();
        $paramsOk = $params && $model->load($params);
        if($paramsOk){
          if ($userRole == 'admin-lsgi'){
            $model->lsgi_id = $modelUser->lsgi_id;
          }
          $model->save(false);
          $session = Yii::$app->session;
          $session->set('updateSuccess', '1');
          return $this->redirect(['index']);
        }

        return $this->render('update', [
          'model' => $model,
          // 'modelPaymentCounter' => $modelPaymentCounter,
          'modelLsgi' => $modelLsgi,

        ]);
    }
    /**
     * Deletes an existing PaymentCounter model.
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
     * Finds the PaymentCounter model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return PaymentCounter the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PaymentCounter::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    public function actionDeletePaymentCounter($id)
    {
        $model = new PaymentCounter;
        $model->deletePaymentCounter($id);
    }
    public function actionDeletePaymentCounterAccount($id)
    {
        $model = new PaymentCounterAccount;
        $model->deletePaymentCounterAccount($id);
    }
}
