<?php

namespace backend\controllers;

use Yii;
use backend\models\Camera;
use backend\models\Account;
use backend\models\Incident;
use backend\models\Person;
use backend\models\Ward;
use backend\models\Memo;
use backend\models\DeviceTokenTest;
use backend\models\Lsgi;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use yii\helpers\ArrayHelper;
use opensooq\firebase\FirebaseNotifications;
use yii\filters\AccessControl;
use backend\components\AccessRule;
use backend\components\AccessPermission;

/**
 * CameraController implements the CRUD actions for Camera model.
 */
class CameraController extends Controller
{
    /**
     * @inheritdoc
     */
     public function behaviors()
     {

       return [
           'access' => [
               'class'        => AccessControl::className(),
               'only'         => ['index', 'create', 'update'],
               'ruleConfig' => [
                       'class' => AccessPermission::className(),
                   ],
               'rules'        => [
                   [
                       'actions' => ['index'],
                       'allow'   => true,
                       'permissions' => ['camera-index']
                   ],
                   [
                       'actions' => ['create'],
                       'allow'   => true,
                       'permissions' => ['camera-create']
                   ],
                   [
                       'actions' => ['update'],
                       'allow'   => true,
                       'permissions' => ['camera-update']
                   ],
                   [
                       'actions' => ['delete-camera'],
                       'allow'   => true,
                       'permissions' => ['camera-delete-camera']
                   ],
                   [
                       'actions' => ['incident-list'],
                       'allow'   => true,
                       'permissions' => ['camera-incident-list']
                   ],
                   [
                       'actions' => ['incident-detail'],
                       'allow'   => true,
                       'permissions' => ['camera-incident-detail']
                   ],
                   [
                       'actions' => ['account-technician'],
                       'allow'   => true,
                       'permissions' => ['camera-account-technician']
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
     * Lists all Camera models.
     * @return mixed
     */
    public function actionIndex($showSuccess=null)
    {
      $showSuccess = isset($_SESSION['showSuccess']) ? $_SESSION['showSuccess'] : null;
      if(isset($_SESSION['showSuccess']))
        unset($_SESSION['showSuccess']);
      $updateSuccess = isset($_SESSION['updateSuccess']) ? $_SESSION['updateSuccess'] : null;
      if(isset($_SESSION['updateSuccess']))
        unset($_SESSION['updateSuccess']);
      $post   = yii::$app->request->post();
      $get    = yii::$app->request->get();
      $params = array_merge($post, $get);
      $vars   = [
        'name',
        'group',
        'ward',
        'lsgi'
        ];
        $newParams = [];
        foreach ($vars as $param)
        {
            ${
                $param}          = isset($params[$param]) ? $params[$param] : null;
            $newParams[$param] = ${
                $param};
        }

        $ward          = isset($post['ward'])?$post['ward']:'';
        $lsgi         = isset($post['lsgi'])?$post['lsgi']:'';
        $group         = isset($post['group'])?$post['group']:'';
        $modelCamera = new Camera;
        // print_r($showSuccess);exit;
        $modelUser  = Yii::$app->user->identity;
        $associations = Yii::$app->rbac->getAssociations($modelUser->id);
        // print_r($associations);exit;
        $dataProvider  = $modelCamera->search(Yii::$app->request->queryParams,$ward, $lsgi, $group);
        // print_r($dataProvider);exit;
        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'modelCamera' => $modelCamera,
            'showSuccess'=>$showSuccess,
            'updateSuccess'=>$updateSuccess,
            'associations' => $associations

            // 'saved' => $saved
        ]);
    }

    /**
     * Displays a single Camera model.
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
    public function actionPhpInfo(){
      return $this->render('phpinfo');
    }
    public function actionCreate()
    {
        // $saved = 0;]
        $showSuccess = false;
        $model = new Camera();
        $modelWard = Ward::getAllQuery()->all();
        $params = Yii::$app->request->post();
        $paramsOk = $params && $model->load($params);
        if($paramsOk){
          $teamOk = $model->validate();
          if($paramsOk && $teamOk){
            $model->save(false);
            // $saved = 1;
            $session = Yii::$app->session;
            $session->set('showSuccess', '1');
            return $this->redirect(['index']);
          }
        }
        return $this->render('create', [
            'model' => $model,
            'modelWard' => $modelWard,
            // 'saved' => $saved,
        ]);
    }

    /**
     * Updates an existing Camera model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        // $modelAccount = new Account;
        $updateSuccess = false;
        $model = $this->findModel($id);
        $accountTechnician = $model->account_id_technician;
        $ward = $model->ward_id;
        $modelWard = Ward::getAllQuery()->all();
        // $modelAccountList = Person::find()->leftJoin('account', 'account.person_id=person.id')
        // ->where(['account.role' => 'camera-technician'])
        // ->andWhere(['account.status' => 1 , 'person.status' => 1])->all();
        // $modelAccount = new Account;
        $params = Yii::$app->request->post();
        $paramsOk = $params && $model->load($params);
        if($paramsOk){
          $teamOk = $model->validate();
          if($paramsOk && $teamOk){
            if(!$model->ward_id)
            {
                $model->ward_id = $ward;
            }
            if(!$model->account_id_technician)
            {
                $model->account_id_technician = $accountTechnician;
            }
            $model->save(false);
            $session = Yii::$app->session;
            $session->set('updateSuccess', '1');
        // if ($model->load(Yii::$app->request->post()) && $model->save()) {
          return $this->redirect(['index']);
        }
      }
        return $this->render('update', [
            'model' => $model,
            'modelWard' => $modelWard,
            // 'modelAccount' => $modelAccount,
            // 'modelAccountList' => $modelAccountList
        ]);
    }
    public function actionIncidentList($id)
    {
        $modelIncident = Incident::find()->where(['camera_id'=>$id,'status'=>1]);
        if($modelIncident){
          $dataProvider = new ActiveDataProvider([
              'query' => $modelIncident,
          ]);

          return $this->render('incident-list', [
              'dataProvider' => $dataProvider,
              'modelIncident' => $modelIncident
          ]);
        }
    }
    public function actionIncidentDetail($id)
    {
        $model = $this->findIncidentModel($id);
        // print_r($model);exit;
        $modelMemo = $this->findMemo($id);
        $incident_base = 'http://139.162.54.79/development/wastemanagement/backend/web/incidents/incident-preview?id=';
        $approveSuccess = isset($_SESSION['approveSuccess']) ? $_SESSION['approveSuccess'] : null;
        if(isset($_SESSION['approveSuccess']))
          unset($_SESSION['approveSuccess']);
        $showSuccess = isset($_SESSION['showSuccess']) ? $_SESSION['showSuccess'] : null;
        if(isset($_SESSION['showSuccess']))
          unset($_SESSION['showSuccess']);
        $modelIncidentType = $model->getIncidentType($model->incident_type_id);
        return $this->render('incident-detail', [
            'model' => $model,
            'modelIncidentType' => $modelIncidentType,
              'modelMemo' => $modelMemo,
              'showSuccess' => $showSuccess,
              'approveSuccess' => $approveSuccess,
              'incident_base' => $incident_base

        ]);
    }
    public function actionAccountTechnician() {
       $out = [];
        if (isset($_POST['depdrop_parents'])) {
        $parents = $_POST['depdrop_parents'];
        $ward = Ward::find()->where(['id'=>$parents[0]])->andWhere(['status'=>1])->one();
        if($ward){
        $wardId = $ward->lsgi_id;
        $accountTechnician= Person::find()
            ->select('account.id as id,person.first_name as first_name')
            ->where(['account.status'=> 1])
            ->leftjoin('account','account.person_id=person.id')
            ->andWhere(['account.lsgi_id'=>$wardId])
            ->andWhere(['account.role'=>'camera-technician'])
            ->all();
            // print_r($accountTechnician);exit;

        foreach ($accountTechnician as $id => $post) {
            $out[] = ['id' => $post['id'], 'name' => $post['first_name']];
 }
 echo Json::encode(['output' => $out, 'selected' => '']);
    }
  }
  }

    /**
     * Deletes an existing Camera model.
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
    public function actionDeleteCamera($id)
    {
        $model = new Camera;
        $model->deleteCamera($id);
    }
    /**
     * Finds the Camera model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Camera the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Camera::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
    protected function findIncidentModel($id)
    {
        if (($model = Incident::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
    public function findMemo($id){
      $modelMemo = Memo::find()->where(['incident_id'=>$id,'status'=>1])->one();
      return $modelMemo;
    }

}
