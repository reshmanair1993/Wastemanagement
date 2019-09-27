<?php

namespace backend\controllers;

use Yii;
use backend\models\Ward;
use backend\models\WardSearch;
use backend\models\Lsgi;
use backend\models\GreenActionUnit;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Json; 
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use backend\components\AccessPermission;
/**
 * WardsController implements the CRUD actions for Ward model.
 */
class WardsController extends Controller
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
               'ruleConfig' => [
                       'class' => AccessPermission::className(),
                   ],
               'rules' => [
                   [
                       'actions' => ['index'],
                       'allow' => true,
                       'permissions' => ['wards-index'],
                   ],
                   [
                       'actions' => ['create'],
                       'allow' => true,
                       'permissions' => ['wards-create'],
                   ],
                   [
                       'actions' => ['update'],
                       'allow' => true,
                       'permissions' => ['wards-update'],
                   ],
                   [
                       'actions' => ['delete'],
                       'allow' => true,
                       'permissions' => ['wards-delete'],
                   ],
                   [
                       'actions' => ['view'],
                       'allow' => true,
                       'permissions' => ['wards-view'],
                   ],
                   [
                       'actions' => ['delete-ward'],
                       'allow' => true,
                       'permissions' => ['wards-delete-ward'],
                   ],
               ],
               'denyCallback' => function($rule, $action) {
                   return $this->goHome();
               }
           ],
       ];
    }

    /**
     * Lists all Ward models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new WardSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Ward model.
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
     * Creates a new Ward model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Ward();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Ward model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $lsgi = $model->lsgi_id;
        if ($model->load(Yii::$app->request->post())) {
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
     * Deletes an existing Ward model.
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
     * Finds the Ward model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Ward the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Ward::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
    public function actionDeleteWard($id)
    {
        $model = new Ward;
        $model->deleteWard($id);
    }
     public function actionGetWards() {
       $out = [];
        if (isset($_POST['depdrop_parents'])) {
        $parents = $_POST['depdrop_parents'];
        $lsgi = Lsgi::find()->where(['id'=>$parents[0]])->andWhere(['status'=>1])->one();
        $ward= Ward::find()->where(['lsgi_id'=>$lsgi['id']])->andWhere(['status'=>1])->all();

        foreach ($ward as $id => $post) {
            $out[] = ['id' => $post['id'], 'name' => $post['name_en']];
 }
 echo Json::encode(['output' => $out, 'selected' => '']);
    }
  }
  public function actionWards() {
       $out = [];
        if (isset($_POST['depdrop_parents'])) {
        $parents = $_POST['depdrop_parents'];
        $hks = GreenActionUnit::find()->where(['id'=>$parents[0]])->andWhere(['status'=>1])->one();
        $ward= Ward::find()->leftJoin('green_action_unit_ward','green_action_unit_ward.ward_id=ward.id')
        ->andWhere(['green_action_unit_ward.status'=>1])
        ->andWhere(['green_action_unit_ward.green_action_unit_id'=>$hks['id']])->all();

        foreach ($ward as $id => $post) {
            $out[] = ['id' => $post['id'], 'name' => $post['name_en']];
 }
 echo Json::encode(['output' => $out, 'selected' => '']);
    }
  }
}
