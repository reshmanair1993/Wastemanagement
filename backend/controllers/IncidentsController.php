<?php

namespace backend\controllers;

use Yii;
use backend\models\Incident;
use backend\models\Memo;
use backend\models\MemoPenalty;
use backend\models\DeviceTokenTest;
use backend\models\Lsgi;
use backend\models\IncidentType;
use backend\models\Camera;
use backend\models\MemoType;
use backend\models\LsgiAuthorizedSignatory;
use backend\models\Image;
use backend\models\FileVideo;
use backend\models\IncidentImage;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use opensooq\firebase\FirebaseNotifications;
use backend\models\FirebaseToken;
use yii\filters\AccessControl;
use backend\components\EmailComponent;
use backend\components\AccessPermission;
use backend\components\AccessRule;

/**
 * IncidentsController implements the CRUD actions for Incident model.
 */
class IncidentsController extends Controller
{
    /**
     * @inheritdoc
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
                         'permissions' => ['incidents-index']
                     ],
                     [
                         'actions' => ['view'],
                         'allow'   => true,
                         'permissions' => ['incidents-view']
                     ],
                     [
                         'actions' => ['incident-preview'],
                         'allow'   => true,
                         'permissions' => ['incidents-incident-preview']
                     ],
                     [
                         'actions' => ['incident-detail'],
                         'allow'   => true,
                         'permissions' => ['incidents-incident-detail']
                     ],
                     [
                         'actions' => ['approve-incident'],
                         'allow'   => true,
                         'permissions' => ['incidents-approve-incident']
                     ],
                     [
                         'actions' => ['test-notification'],
                         'allow'   => true,
                         'permissions' => ['incidents-test-notification']
                     ],
                     [
                         'actions' => ['memo-amount'],
                         'allow'   => true,
                         'permissions' => ['incidents-memo-amount']
                     ],
                     [
                         'actions' => ['generate-memo'],
                         'allow'   => true,
                         'permissions' => ['incidents-generate-memo']
                     ],
                     [
                         'actions' => ['create'],
                         'allow'   => true,
                         'permissions' => ['incidents-create']
                     ],
                     [
                         'actions' => ['update'],
                         'allow'   => true,
                         'permissions' => ['incidents-update']
                     ],
                     [
                         'actions' => ['delete-incident'],
                         'allow'   => true,
                         'permissions' => ['incidents-delete-incident']
                     ],
                     [
                         'actions' => ['autocomplete'],
                         'allow'   => true,
                         'permissions' => ['incidents-autocomplete']
                     ],
                     [
                         'actions' => ['get-memo'],
                         'allow'   => true,
                         'permissions' => ['incidents-get-memo']
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
     * Lists all Incident models.
     * @return mixed
     */
    public function actionIndex($id = null)
    {
      $modelUser = Yii::$app->user->identity;
      $userRole  = $modelUser->role;
      $post   = yii::$app->request->post();
      // print_r($post);exit;
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
        $modelIncident = new Incident;
        $dataProvider  = $modelIncident->search(Yii::$app->request->queryParams,$ward, $lsgi, $group);
        // print_r($id);exit;
        $status = 0;
        if($id){
          $incident = Incident::find()->where(['camera_id'=>$id,'status'=>1])->orderBy(['incident.id' => SORT_DESC]);
          if($incident){
            $dataProvider = new ActiveDataProvider([
                'query' => $incident,
            ]);
          }
          $status = 1;

        }
        $modelUser  = Yii::$app->user->identity;
        $associations = Yii::$app->rbac->getAssociations($modelUser->id);
        // print_r($associations);exit;
        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'modelIncident' => $modelIncident,
            'associations' => $associations,
            'status' => $status
        ]);
    }

    /**
     * Displays a single Incident model.
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
    public function actionIncidentPreview($id)
    {
      $this->layout = "memo-layout";
      $modelMemo = $this->findMemo($id);
      $model = $this->findModel($id);
      $modelIncidentType = $model->getIncidentType($model->incident_type_id);
      return $this->render('incident-preview',[
        'model' => $model,
        'modelIncidentType' => $modelIncidentType,
        'modelMemo' => $modelMemo

      ]);
    }
    public function actionIncidentDetail($id)
    {
        $model = $this->findModel($id);
        $modelMemo = $this->findMemo($id);
        $incident_base = Yii::$app->params['incident_base'];
        $approveSuccess = isset($_SESSION['approveSuccess']) ? $_SESSION['approveSuccess'] : null;
        if(isset($_SESSION['approveSuccess']))
          unset($_SESSION['approveSuccess']);
        $showSuccess = isset($_SESSION['showSuccess']) ? $_SESSION['showSuccess'] : null;
        if(isset($_SESSION['showSuccess']))
          unset($_SESSION['showSuccess']);
        $modelIncidentType = $model->getIncidentType($model->incident_type_id);
         $incidentImages = IncidentImage::find()->where(['incident_id'=>$id])->andWhere(['status'=>1])->all();
        return $this->render('incident-detail', [
            'model' => $model,
            'modelIncidentType' => $modelIncidentType,
              'modelMemo' => $modelMemo,
              'showSuccess' => $showSuccess,
              'approveSuccess' => $approveSuccess,
              'incidentImages' => $incidentImages,
              'incident_base' => $incident_base

        ]);
    }
    public function actionApproveIncident(){
      $post = Yii::$app->request->post();
      if(!isset($post['id'])) {
        throw new NotFoundHttpException('The requested page does not exist.');
      }
      $id = $post['id'];
      $modelIncident = new Incident;
      $model = $this->findModel($id);
      if($model->is_approved == 0){
        $model->is_approved = 1;
      }
      else
      {
        $model->is_approved = 0;
      }
      $model->update(false);
      if($model->is_approved == 1)
        $this->sendNotification($model->id);
      // print_r($model->is_approved);exit;
      $ret  = [
        "status" =>$model->is_approved
    ];
      return json_encode($ret);
    }
    public function sendNotification($id){
      // print_r($id);exit;
      $modelIncident = new Incident;
      $model = $this->findModel($id);
      $message = $modelIncident->getIncidentType($model->incident_type_id);
      $modelTokens = $modelIncident->getToken($model->camera_id);
      // foreach ($modelTokens as $modelToken) {
      //   print_r($modelToken->account_id);
      //
      // }exit;
      // print_r($model);exit;
      $incident_base = Yii::$app->params['incident_base'];
      // $incident_base = 'http://139.162.54.79/development/wastemanagement/backend/web/incidents/incident-preview?id=';
      $incident_url = $incident_base.$model->id;
      $groupNames = $modelIncident->getGroup($model->camera_id);
      $authKey = Yii::$app->params['authKey'];
      $service = new FirebaseNotifications(['authKey' => $authKey]);
      $groups = [];
      foreach ($groupNames as $groupName) {
        $group= str_replace(' ', '', $groupName->name);
        $groups[]=$group;
        $modelUsers = $modelIncident->getUsers($groupName->id);
        // print_r($groupName);exit;

        // print_r($modelUsers);exit;
        foreach ($modelUsers as $modelUser) {
          $tokens = $modelUser->token;
          $id = $model->id;
          $service->addToTopic($tokens,$group);
        }
        $modelVideo       = $model->fkVideo;
        if($modelVideo){
          $url = $modelVideo->url;
          $fullUrl = $modelVideo->getFullUrl($url);
        }
        $modelIncidentType = $model->getIncidentType($model->incident_type_id);
        // $incidentTypeName = Html::encode($modelIncidentType);
        $cameraId         = $model->camera_id;
        $ward = $model->getWard($cameraId);
        if($ward)
         $wardName = $ward->name;

        $modelCamera = $model->fkCamera;
        if($modelCamera)
          $modelCameraName = $modelCamera->name;
        if($modelUsers) {
            $service->sendNotificationToTopic($message ,$group,$id,$fullUrl,$modelIncidentType,$wardName,$modelCameraName,$incident_url);
          }
        }
    }
    public function actionTestNotification()
     {
          $message = 'Incident Name';
          // $modelTokens = DeviceTokenTest::find()->where(['status' => 1])->all();
          $modelTokens = FirebaseToken::find()->where(['status' => 1])->all();
          $tokens = [];
          $service = new FirebaseNotifications(['authKey' => 'AIzaSyAX8tFIBJBpWcyaYHscZrEDcWa3Kp2V7mI']);
          foreach ($modelTokens as $modelToken) {
            $tokens[] = $modelToken->token;
            // $service->addToTopic($tokens, $message ,$id);

          }
          $options = ['id' =>104];
          $service->sendNotification($modelToken->token,$message ,$options);
     }
     public function actionMemoAmount($id,$incidentId){
        $ret = ['amount'=>0];
       $model = $this->findModel($incidentId);
       $cameraId = $model->camera_id;
       $modelLsgi = Lsgi::find()
       ->innerJoin('ward','ward.lsgi_id=lsgi.id')
       ->innerJoin('camera','camera.ward_id=ward.id')
       ->where(['lsgi.status' =>1])
       ->andWhere(['camera.status'=>1])
       ->andWhere(['ward.status'=>1])
       ->andWhere(['camera.id'=>$cameraId])
       ->andWhere(['ward.status' =>1])->one();
       if($modelLsgi){
       $modelPenalties=  MemoPenalty::findOne(['memo_type_id'=>$id,'lsgi_id' => $modelLsgi->id]);
       if($modelPenalties){
       $ret  = [
         'amount'=>$modelPenalties->amount
       ];
     }
     }
       return json_encode($ret);
     }
    public function actionGenerateMemo($id)
    {
        $model = $this->findModel($id);
        $showSuccess = false;
        $modelGenerateMemo = new Memo;
        $params = Yii::$app->request->post();

        // $modelMemoType = MemoType::getAllQuery()->all();
        // $modelAccount = Yii::$app->user->identity->id;
        $cameraId = $model->camera_id;
        $modelLsgi = Lsgi::find()
        ->innerJoin('ward','ward.lsgi_id=lsgi.id')
        ->innerJoin('camera','camera.ward_id=ward.id')
        ->where(['lsgi.status' =>1])
        ->andWhere(['camera.id'=>$cameraId])
        ->andWhere(['ward.status' =>1])->one();
        // $modelPenalties = MemoPenalty::getAllQuery()
        // ->where(['lsgi_id'=>$modelLsgi->id])->all();
        $modelMemoType = MemoType::find()
        ->leftJoin('memo_penalty','memo_penalty.memo_type_id=memo_type.id')
        ->andWhere(['memo_penalty.lsgi_id'=>$modelLsgi->id])
        ->where(['memo_type.status' => 1, 'memo_penalty.status' =>1])->all();
        // print_r($modelMemoType);exit;

        // // $modelMemoType = new MemoType;
        // foreach($modelPenalties as $penalty){
        //   $modelMemoType =
        // }
        $modelAuthorizedSignatory = LsgiAuthorizedSignatory::getAllQuery()->where(['lsgi_id'=>$modelLsgi->id])->all();
        $paramsOk = $params && $modelGenerateMemo->load($params);
        if($paramsOk){
          $modelGenerateMemo->incident_id = $id;
          if($modelLsgi){
          $modelGenerateMemo->lsgi_id = $modelLsgi->id;
          $memoOk = $modelGenerateMemo->validate();
          if($paramsOk && $memoOk){
            $modelGenerateMemo->save(false);
            $mail = Yii::$app->email->sendEmail($modelGenerateMemo);
            $session = Yii::$app->session;
            $session->set('showSuccess', '1');
            return $this->redirect(['incident-detail','id' => $id]);
          }}
        }
        return $this->render('generate_memo', [
            'model' => $model,
            'modelGenerateMemo' => $modelGenerateMemo,
            'modelMemoType' => $modelMemoType,
            'modelAuthorizedSignatory' => $modelAuthorizedSignatory
        ]);
    }
    // public function change_status() {
    //     $isApproved = $this->input->post('isApproved');
    //     $this->update_status($isApproved);
    // }
    public function getIncidentImage()
    {
      $modelImage = new Image(['incident_image_uploads_path'=>Yii::t('app','Incident picture')]);
      $modelImage->setScenario('single-image-upload-image-optional');
      return $modelImage;
    }
    public function getIncidentVideo()
    {
      $modelVideo = new FileVideo(['incident_video_uploads_path'=>Yii::t('app','Incident video')]);
      $modelVideo->setScenario('single-video-upload-video-optional');
      return $modelVideo;
    }
    public function actionCreate()
    {
        $model = new Incident();
        $modelImage = $this->getIncidentImage();
        $modelVideo = $this->getIncidentVideo();
        $modelIncidentType = IncidentType::getAllQuery()->all();;
        $modelCamera = Camera::getAllQuery()->all();
        $params = Yii::$app->request->post();
        $paramsOk = $params && $model->load($params);
        if($paramsOk){
          $incidentOk = $model->validate();
          $imageOk = $modelImage->validate();
          $videoOk = $modelVideo->validate();
          if($paramsOk && $incidentOk && $imageOk && $videoOk){
            if($modelImage) {
              $images                       = UploadedFile::getInstanceByName('photo');
              $incident_image_uploads_path  = Yii::$app->params['incident_image_uploads_path'];
              $modelImageSaveId             = $modelImage->uploadAndSave($images,$incident_image_uploads_path);
              if($modelImageSaveId) {
                  $model->image_id = $modelImageSaveId;
              }
            }
            if($modelVideo) {
              $videos                       = UploadedFile::getInstanceByName('video');
              $modelVideoSaveId             = $modelVideo->uploadAndSave($videos);
              if(sizeof($modelVideoSaveId)) {
                  $modelVideoSaveId = $modelVideoSaveId[0];
                  $model->file_video_id = $modelVideoSaveId;
              }
            }
            $model->save(false);
            return $this->redirect(['index']);
          }
        }
        return $this->render('create', [
            'model' => $model,
            'modelImage' => $modelImage,
            'modelVideo' => $modelVideo,
            'modelCamera' => $modelCamera,
            'modelIncidentType' => $modelIncidentType
        ]);
    }

    /**
     * Updates an existing Incident model.
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
     * Deletes an existing Incident model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDeleteIncident($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }
    public function actionDeleteIncidentData($id)
    {
        $modelIncident = new Incident;
        $modelIncident->deleteIncident($id);
    }

    /**
     * Finds the Incident model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Incident the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Incident::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
    protected function findModelIncidentType($id)
    {
        if (($model = IncidentType::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
    public function actionAutocomplete()
{
    $memoTypesList = $_POST['memoTypesList'];
    $query = MemoPenalty::find()->where(['memoTypesList'=> $memoTypesList])->one();
    $data[] = ['amount' => $query->amount];
    echo json_encode($data);
}
    public function actionGetMemo()
    {
         // echo "s";exit;
         $params = $_GET;
         $term = isset($params['term'])?$params['term']:'';
         // print_r($term);exit;
         $penalty = MemoPenalty::find()->select(['amount'])
             ->andFilterWhere(['like','memo_type_id',$term])->andWhere(['status' => 1])->one();
        $amount = $penalty['amount'];
        $rets[] = ['amount' => $amount];
       // foreach($persons as $person){
       //     $first_name = $person['first_name'];
       //     $middle_name = $person['middle_name'];
       //     $last_name = $person['last_name'];
       //     $phone1 = $person['phone1'];
       //     $email = $person['email'];
       //    $rets[] = ['label'=>$email,'first_name'=>$first_name,'middle_name'=>$middle_name,'last_name'=>$last_name,'phone'=>$phone1];
       // }
      return json_encode($rets);
     }
     public function findMemo($id){
       $modelMemo = Memo::find()->where(['incident_id'=>$id,'status'=>1])->one();
       return $modelMemo;
     }
}
