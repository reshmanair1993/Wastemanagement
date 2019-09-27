<?php

namespace backend\controllers;

use Yii;
use backend\models\SurveyAgency;
use backend\models\SurveyAgencySearch;
use backend\models\SurveyAgencyWard;
use backend\models\Lsgi;
use backend\models\SurveyAgencyWardSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use yii\filters\AccessControl;
use backend\components\AccessPermission;
/**
 * SurveyAgenciesController implements the CRUD actions for SurveyAgency model.
 */
class SurveyAgenciesController extends Controller
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
                       'permissions' => ['survey-agencies-index'],
                   ],
                   [
                       'actions' => ['create'],
                       'allow' => true,
                       'permissions' => ['survey-agencies-create'],
                   ],
                   [
                       'actions' => ['update'],
                       'allow' => true,
                       'permissions' => ['survey-agencies-update'],
                   ],
                   [
                       'actions' => ['delete'],
                       'allow' => true,
                       'permissions' => ['survey-agencies-delete'],
                   ],
                   [
                       'actions' => ['view'],
                       'allow' => true,
                       'permissions' => ['survey-agencies-view'],
                   ],
                   [
                       'actions' => ['add-agency-ward'],
                       'allow' => true,
                       'permissions' => ['survey-agencies-add-agency-ward'],
                   ],
                   [
                       'actions' => ['delete-agency'],
                       'allow' => true,
                       'permissions' => ['survey-agencies-delete-agency'],
                   ],
                   [
                       'actions' => ['delete-ward'],
                       'allow' => true,
                       'permissions' => ['survey-agencies-delete-ward'],
                   ],
               ],
               'denyCallback' => function($rule, $action) {
                   return $this->goHome();
               }
           ],
       ];
    }

    /**
     * Lists all SurveyAgency models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SurveyAgencySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single SurveyAgency model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
       $model = $this->findModel($id);
      $modelSurveyAgencyWard = new SurveyAgencyWard;
      $searchModel = new SurveyAgencyWardSearch();
      $dataProvider = $searchModel->search(Yii::$app->request->queryParams,$id);
      $params = [
        'model' => $model,
        'modelSurveyAgencyWard' => $modelSurveyAgencyWard,
        'searchModel' => $searchModel,
        'dataProvider'=> $dataProvider
      ];
      return $this->render('view', [
            'params' => $params,
        ]);
    }

    /**
     * Creates a new SurveyAgency model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new SurveyAgency();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect('index');
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing SurveyAgency model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $lsgi = $model->lsgi_id;
        if ($model->load(Yii::$app->request->post()) ) {
            if(!$model->lsgi_id)
            {
                $model->lsgi_id = $lsgi;
            }
            $model->save();
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing SurveyAgency model.
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
     * Finds the SurveyAgency model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return SurveyAgency the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = SurveyAgency::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
    public function actionAddAgencyWard($id)
    {
        $model = $this->findModel($id);
      $modelSurveyAgencyWard = new SurveyAgencyWard;
      $searchModel = new SurveyAgencyWardSearch();
      $dataProvider = $searchModel->search(Yii::$app->request->queryParams,$id);

        if ($modelSurveyAgencyWard->load(Yii::$app->request->post())) {
            $modelSurveyAgencyWard->survey_agency_id = $id;
            $modelSurveyAgencyWard->save();
        }

         $params = [
        'model' => $model,
        'modelSurveyAgencyWard' => $modelSurveyAgencyWard,
        'searchModel' => $searchModel,
        'dataProvider'=> $dataProvider
      ];
      return $this->render('view', [
            'params' => $params,
        ]);
    }
    public function actionDeleteWard($id)
    {
        $model = new SurveyAgencyWard;
        $model->deleteWard($id);
    }
    public function actionDeleteAgency($id)
    {
        $model = new SurveyAgency;
        $model->deleteAgency($id);
    }
     public function actionAgency() {
       $out = [];
        if (isset($_POST['depdrop_parents'])) {
        $parents = $_POST['depdrop_parents'];
        $lsgi = Lsgi::find()->where(['id'=>$parents[0]])->andWhere(['status'=>1])->one();
        $agency= SurveyAgency::find()->where(['lsgi_id'=>$lsgi['id']])->andWhere(['status'=>1])->all();

        foreach ($agency as $id => $post) {
            $out[] = ['id' => $post['id'], 'name' => $post['name']];
 }
 echo Json::encode(['output' => $out, 'selected' => '']);
    }
  }
}
