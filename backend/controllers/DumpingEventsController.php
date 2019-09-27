<?php

namespace backend\controllers;

use Yii;
use backend\models\DumpingEvent;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use backend\components\AccessPermission;
use yii\filters\AccessControl;
use backend\models\Image;
use backend\models\Account;
use backend\models\Person;
use yii\web\UploadedFile;
/**
 * BuildingTypesController implements the CRUD actions for BuildingType model.
 */
class DumpingEventsController extends Controller
{
    /**
     * @inheritdoc
     */
     // public function behaviors()
     // {
     //     return [
     //         'access' => [
     //             'class'        => AccessControl::className(),
     //             'only'         => ['index', 'create', 'update', 'view', 'view-details'],
     //             'ruleConfig' => [
     //                     'class' => AccessPermission::className(),
     //                 ],
     //             'rules'        => [
     //                 [
     //                     'actions' => ['index'],
     //                     'allow'   => true,
     //                     'permissions' => ['DumpingEvent-index']
     //                 ],
     //                 [
     //                     'actions' => ['view'],
     //                     'allow'   => true,
     //                     'permissions' => ['DumpingEvent-view']
     //                 ],
     //                 [
     //                     'actions' => ['create'],
     //                     'allow'   => true,
     //                     'permissions' => ['DumpingEvent-create']
     //                 ],
     //                 [
     //                     'actions' => ['update'],
     //                     'allow'   => true,
     //                     'permissions' => ['DumpingEvent-update']
     //                 ],
     //                 [
     //                     'actions' => ['delete'],
     //                     'allow'   => true,
     //                     'permissions' => ['DumpingEvent-delete']
     //                 ],
     //                 [
     //                     'actions' => ['delete-DumpingEvent'],
     //                     'allow'   => true,
     //                     'permissions' => ['DumpingEvent-delete-DumpingEvent']
     //                 ],
     //             ],
     //             'denyCallback' => function (
     //                 $rule,
     //                 $action
     //             )
     //             {
     //                 return $this->goHome();
     //             }
     //         ]
     //     ];
     // }

    /**
     * Lists all BuildingType models.
     * @return mixed
     */
    public function actionIndex()
    {
    	$type      = null;
    	$post         = Yii::$app->request->post();
        if (isset($post['type']))
        {
            $type = $post['type'];
        }
        $model = new DumpingEvent();
        $dataProvider = $model->search(Yii::$app->request->queryParams,$type);

        return $this->render('index', [
            'model' => $model,
            'dataProvider' => $dataProvider,
        ]);
    }
    /**
     * Creates a new BuildingType model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
    	$lsgi = null;
      $district = null;
      $ward = null;
      $unit = null;
      $gt_id = null;
      $agency = null;
      $user = yii::$app->user->identity->role;
       $modelUser  = Yii::$app->user->identity;
        $userRole = $modelUser->role;
        $associations = Yii::$app->rbac->getAssociations($modelUser->id);
        if(isset($associations['lsgi_id']))
        {
            $lsgi = $associations['lsgi_id'];
        }
        if(isset($associations['district_id']))
        {
            $district = $associations['district_id'];
        }
        if(isset($associations['district_id']))
        {
            $district = $associations['district_id'];
        }
        if(isset($associations['ward_id']))
        {
            $ward = $associations['ward_id'];
            $ward = json_decode($ward);
        }
        if(isset($associations['hks_id']))
        {
            $unit = $associations['hks_id'];
        }
        if(isset($associations['gt_id']))
        {
            $gt_id = $associations['gt_id'];
        }
        if(isset($associations['survey_agency_id']))
        {
            $agency = $associations['survey_agency_id'];
        }
      $modelAccount = Account::find()->where(['status'=>1,'role'=>'supervisor']);
      if($unit)
      {
        $modelAccount = $modelAccount->andWhere(['green_action_unit_id'=>$unit]);
      }

      $modelAccount = $modelAccount->all();
      $modelPerson = [];
      foreach ($modelAccount as $account) {
        $modelPerson[] = Person::find()->where(['status'=>1,'id'=>$account->person_id])->one();
      }
        $model = new DumpingEvent();
        $modelImage = new Image();

        if ($model->load(Yii::$app->request->post()) && $modelImage->load(Yii::$app->request->post())) {
             $images  = UploadedFile::getInstance($modelImage, 'uploaded_files');
             $logo_image_uploads_path  = Yii::$app->params['dumping_base_urls'];
              $imageId             = $modelImage->uploadAndSave($images,$logo_image_uploads_path);
             if($imageId)
              $model->image_id = $imageId;
          	$account = Account::find()->where(['status'=>1,'customer_id'=>$model->account_id_customer])->one();
          if($account){
            $model->account_id_customer = $account->id;
          }else{
            $model->account_id_customer = 0;
          }
              $model->save(false);
            return $this->redirect(['index']);
        }

        return $this->render('_form', [
            'model' => $model,
            'modelImage' => $modelImage,
            'modelAccount' => $modelAccount,
            'modelPerson' => $modelPerson,
        ]);
    }

    /**
     * Updates an existing BuildingType model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
    	$lsgi = null;
      $district = null;
      $ward = null;
      $unit = null;
      $gt_id = null;
      $agency = null;
      $user = yii::$app->user->identity->role;
       $modelUser  = Yii::$app->user->identity;
        $userRole = $modelUser->role;
        $associations = Yii::$app->rbac->getAssociations($modelUser->id);
        if(isset($associations['lsgi_id']))
        {
            $lsgi = $associations['lsgi_id'];
        }
        if(isset($associations['district_id']))
        {
            $district = $associations['district_id'];
        }
        if(isset($associations['district_id']))
        {
            $district = $associations['district_id'];
        }
        if(isset($associations['ward_id']))
        {
            $ward = $associations['ward_id'];
            $ward = json_decode($ward);
        }
        if(isset($associations['hks_id']))
        {
            $unit = $associations['hks_id'];
        }
        if(isset($associations['gt_id']))
        {
            $gt_id = $associations['gt_id'];
        }
        if(isset($associations['survey_agency_id']))
        {
            $agency = $associations['survey_agency_id'];
        }
      $modelAccount = Account::find()->where(['status'=>1,'role'=>'supervisor']);
      if($unit)
      {
        $modelAccount = $modelAccount->andWhere(['green_action_unit_id'=>$unit]);
      }

      $modelAccount = $modelAccount->all();
      $modelPerson = [];
      foreach ($modelAccount as $account) {
        $modelPerson[] = Person::find()->where(['status'=>1,'id'=>$account->person_id])->one();
      }
        $model = $this->findModel($id);
        $modelImage = new Image();
        if ($model->load(Yii::$app->request->post())&& $modelImage->load(Yii::$app->request->post())) {
            $images  = UploadedFile::getInstance($modelImage, 'uploaded_files');
             $logo_image_uploads_path  = Yii::$app->params['dumping_base_urls'];
              $imageId             = $modelImage->uploadAndSaveMultiple($images,$logo_image_uploads_path);
              if($imageId){
              $model->image_id = $imageId;
          }
              $model->save(false);
            return $this->redirect(['index']);
        }

       return $this->render('_form', [
            'model' => $model,
            'modelImage' => $modelImage,
            'modelAccount' => $modelAccount,
            'modelPerson' => $modelPerson,
        ]);
    }

    /**
     * Deletes an existing BuildingType model.
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
     * Finds the BuildingType model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return BuildingType the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DumpingEvent::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
    public function actionDeleteDumpingEvents($id)
    {
        $model = new DumpingEvent;
        $model->deleteDumpingEvent($id);
    }  
    
}
