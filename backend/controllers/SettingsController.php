<?php

namespace backend\controllers;

use Yii;
use backend\models\CmsSettings;
use backend\models\CmsSettingsSearch;
use backend\models\CmsSocialMedia;
use backend\models\Image;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;
use yii\web\UploadedFile;
use backend\models\Settings;
/**
 * SettingsController implements the CRUD actions for CmsSettings model.
 */
class SettingsController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all CmsSettings models.
     * @return mixed
     */
    public function actionIndex()
    {
       $id = isset(Yii::$app->params['settingsId'])?Yii::$app->params['settingsId']:1; //Assumes first entry
      $modelSettings = $this->findModel($id);
      $modelSocialMedia = new CmsSocialMedia;
      $dataProvider = new ActiveDataProvider(
        [
           'query' => CmsSocialMedia::getAllQuery(),
           'pagination' => false,
           'sort'=> ['defaultOrder' => ['id' => SORT_DESC]]
        ]);


      $post = Yii::$app->request->post();
      $settingsOk = $modelSettings->load($post) && $modelSettings->validate();
      if($settingsOk)
      {
        $modelSettings->update(false);

        $params = [
          'modelSettings' => $modelSettings,
          'modelSocialMedia' => $modelSocialMedia,
          'dataProvider' => $dataProvider,
        ];
        return $this->renderAjax('index', [
           'params' => $params
          ]);
      }

      $params = [
        'modelSettings' => $modelSettings,
        'modelSocialMedia' => $modelSocialMedia,
          'dataProvider' => $dataProvider,
      ];
      return $this->render('index', ['params'=> $params]);
    }

    /**
     * Displays a single CmsSettings model.
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
     * Creates a new CmsSettings model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new CmsSettings();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing CmsSettings model.
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
     * Deletes an existing CmsSettings model.
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
     * Finds the CmsSettings model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return CmsSettings the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CmsSettings::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
    protected function findSettingsModel($id)
    {
        if (($model = Settings::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
    protected function findSocialMediaModel($id)
    {
        if (($model = CmsSocialMedia::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
    public function actionAddSocialMedia($id)
    {
        $modelSettings = $this->findModel($id);
        $modelSocialMedia = new CmsSocialMedia;
        $modelImage = new Image;
        $dataProvider = new ActiveDataProvider(
        [
           'query' => CmsSocialMedia::getAllQuery(),
           'pagination' => false,
           'sort'=> ['defaultOrder' => ['id' => SORT_DESC]]
        ]);
        if ($modelSocialMedia->load(Yii::$app->request->post())&& $modelImage->load(Yii::$app->request->post())) {
            $modelImage->uploaded_files = UploadedFile::getInstance($modelImage, 'uploaded_files');
            $imageId                    = $modelImage->uploadAndSave();
            if ($imageId)
            {
                $modelSocialMedia->icon = $imageId;
            }
            $modelSocialMedia->save();
        }
        $modelSocialMedia = new CmsSocialMedia;
         $params = [
            'modelSettings'  =>$modelSettings,
            'modelSocialMedia'  =>$modelSocialMedia,
            'dataProvider'  =>$dataProvider,
        ];
      return $this->render('index', [
            'params' => $params,
        ]);
    }
     public function actionDeleteSocialMedia($id)
    {
        $model = new CmsSocialMedia;
        $model->deleteSocialMedia($id);
    }
    public function actionUpdateSocialMedia($id)
    {
      $sid = isset(Yii::$app->params['settingsId'])?Yii::$app->params['settingsId']:1; //Assumes first entry
      $modelImage = new Image;
      $modelSettings = $this->findModel($sid);
      $modelSocialMedia = $this->findSocialMediaModel($id);
      $dataProvider = new ActiveDataProvider(
        [
           'query' => CmsSocialMedia::getAllQuery(),
           'pagination' => false,
           'sort'=> ['defaultOrder' => ['id' => SORT_DESC]]
        ]);
        if ($modelSocialMedia->load(Yii::$app->request->post())&& $modelImage->load(Yii::$app->request->post())) {
            $modelImage->uploaded_files = UploadedFile::getInstance($modelImage, 'uploaded_files');
            $imageId                    = $modelImage->uploadAndSave();
            if ($imageId)
            {
                $modelSocialMedia->icon = $imageId;
            }
            $modelSocialMedia->save();
        }
              $modelSocialMedia = new CmsSocialMedia;
         $params = [
         'modelSettings'  =>$modelSettings,
            'modelSocialMedia'  =>$modelSocialMedia,
            'dataProvider'  =>$dataProvider,
      ];
      return $this->render('index', [
            'params' => $params,
        ]);
    }
    public function actionAbout()
    {
       $id = isset(Yii::$app->params['settingsId'])?Yii::$app->params['settingsId']:1; //Assumes first entry
      $modelSettings = $this->findSettingsModel($id);


      $post = Yii::$app->request->post();
      $settingsOk = $modelSettings->load($post) && $modelSettings->validate();
      if($settingsOk)
      {
        $modelSettings->update(false);

        $params = [
          'modelSettings' => $modelSettings,
        ];
        return $this->renderAjax('about', [
           'params' => $params
          ]);
      }

      $params = [
        'modelSettings' => $modelSettings,
      ];
      return $this->render('about', ['params'=> $params]);
    }
}
