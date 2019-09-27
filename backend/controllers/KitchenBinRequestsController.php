<?php

namespace backend\controllers;

use Yii;
use backend\models\KitchenBinRequest;
use backend\models\KitchenBinRequestSearch;
use backend\models\Ward;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use backend\components\AccessRule;
use yii\helpers\Json;
/**
 * KitchenBinRequestsController implements the CRUD actions for KitchenBinRequest model.
 */
class KitchenBinRequestsController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
         {
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
                             'actions' => ['create','update','index'],
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
     * Lists all KitchenBinRequest models.
     * @return mixed
     */
    public function actionIndex()
    {
        $model = new KitchenBinRequest();
        $searchModel = new KitchenBinRequestSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'model' => $model,
        ]);
    }

    /**
     * Displays a single KitchenBinRequest model.
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
     * Creates a new KitchenBinRequest model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new KitchenBinRequest();

        if ($model->load(Yii::$app->request->post())) {
            $ward = Ward::find()->where(['id'=>$model->ward_id])->andWhere(['status'=>1])->one();
            if($ward)
            {
                $model->lsgi_id = $ward->lsgi_id;
            }
            $model->save();
            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing KitchenBinRequest model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        // $lsgi = $model->lsgi_id;
        // $ward = $model->ward_id;

        if ($model->load(Yii::$app->request->post())) {
            //  if(!$model->lsgi_id)
            // {
            //     $model->lsgi_id = $lsgi;
            // }
            // if(!$model->ward_id)
            // {
            //     $model->ward_id = $ward;
            // }
            $ward = Ward::find()->where(['id'=>$model->ward_id])->andWhere(['status'=>1])->one();
            if($ward)
            {
                $model->lsgi_id = $ward->lsgi_id;
            }
            $model->save();
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing KitchenBinRequest model.
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
     * Finds the KitchenBinRequest model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return KitchenBinRequest the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = KitchenBinRequest::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
     public function actionToggleStatusApproved($id)
      {
        $model = $this->findModel($id);
        $status=$model->toggleStatusApproved();
        echo json_encode(['status'=> $status]);
    }
    public function actionDeleteRequest($id)
    {
        $model = new KitchenBinRequest;
        $model->deleteRequest($id);
    }
}
