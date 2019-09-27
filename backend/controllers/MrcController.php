<?php

namespace backend\controllers;

use Yii;
use backend\models\Mrc;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use backend\components\AccessPermission;
use yii\filters\AccessControl;
use backend\models\Image;
use yii\web\UploadedFile;
use backend\models\QrCode;
/**
 * BuildingTypesController implements the CRUD actions for BuildingType model.
 */
class MrcController extends Controller
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
     //                     'permissions' => ['mrc-index']
     //                 ],
     //                 [
     //                     'actions' => ['view'],
     //                     'allow'   => true,
     //                     'permissions' => ['mrc-view']
     //                 ],
     //                 [
     //                     'actions' => ['create'],
     //                     'allow'   => true,
     //                     'permissions' => ['mrc-create']
     //                 ],
     //                 [
     //                     'actions' => ['update'],
     //                     'allow'   => true,
     //                     'permissions' => ['mrc-update']
     //                 ],
     //                 [
     //                     'actions' => ['delete'],
     //                     'allow'   => true,
     //                     'permissions' => ['mrc-delete']
     //                 ],
     //                 [
     //                     'actions' => ['delete-mrc'],
     //                     'allow'   => true,
     //                     'permissions' => ['mrc-delete-mrc']
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
        $searchModel = new Mrc();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single BuildingType model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
      $model = $this->findModel($id);
      $searchModel = new BuildingTypeSubTypesSearch();
      $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
      $params = [
        'model' => $model,
        'searchModel' => $searchModel,
        'dataProvider'=> $dataProvider
      ];
      return $this->render('view', [
            'params' => $params,
        ]);
    }

    /**
     * Creates a new BuildingType model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
     public function actionCreate()
    {
        $model = new Mrc();
        $modelImage = new Image();
        // $model->setScenario('create');
        if ($model->load(Yii::$app->request->post()) && $modelImage->load(Yii::$app->request->post())&&$model->validate()) {
             $images  = UploadedFile::getInstances($modelImage, 'imageFiles');
             $logo_image_uploads_path  = Yii::$app->params['mrc_base_urls'];
              $imageId             = $modelImage->uploadAndSaveMultiple($images,$logo_image_uploads_path);
              $jsonArray = json_encode($imageId);
              $model->image_id = $jsonArray;
              $model->save(false);
              if($model->qr_code)
              {
                $modelQrCode = QrCode::find()->where(['value'=>$model->qr_code])->andWhere(['status'=>1])->one();
                if($modelQrCode)
                {
                    $modelQrCode->mrc_id = $model->id;
                    $modelQrCode->save(false);
                }
              }
            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
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
        $model = $this->findModel($id);
        $lsgi = $model->lsgi_id;
        $modelImage = new Image();
        if ($model->load(Yii::$app->request->post())&& $modelImage->load(Yii::$app->request->post())&&$model->validate()) {
            // print_r("expression");die();
            $images  = UploadedFile::getInstances($modelImage, 'imageFiles');
             $logo_image_uploads_path  = Yii::$app->params['mrc_base_urls'];
              $imageId             = $modelImage->uploadAndSaveMultiple($images,$logo_image_uploads_path);
              if($imageId){
              $jsonArray = json_encode($imageId);
              $model->image_id = $jsonArray;
          }
          if(!$model->lsgi_id)
            {
                $model->lsgi_id = $lsgi;
            }
              $model->save(false);
              if($model->qr_code)
              {
                $modelQrCode = QrCode::find()->where(['value'=>$model->qr_code])->andWhere(['status'=>1])->one();
                if($modelQrCode)
                {
                    $modelQrCode->mrc_id = $model->id;
                    $modelQrCode->save(false);
                }
              }
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
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
        if (($model = Mrc::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
    public function actionDeleteMrc($id)
    {
        $model = new Mrc;
        $model->deleteMrc($id);
    }  
}
