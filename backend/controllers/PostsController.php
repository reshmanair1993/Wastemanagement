<?php

namespace backend\controllers;

use Yii;
use backend\models\CmsPost;
use backend\models\CmsPostSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use backend\models\Image;
use yii\web\UploadedFile;

/**
 * PostsController implements the CRUD actions for CmsPost model.
 */
class PostsController extends Controller
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
     * Lists all CmsPost models.
     * @return mixed
     */
    public function actionIndex($type=null)
    {
        $searchModel = new CmsPost();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,$type);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'type' => $type,
        ]);
    }

    /**
     * Displays a single CmsPost model.
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
     * Creates a new CmsPost model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($type=null)
    {
        $model = new CmsPost();
        $modelImage = new Image;
        $model->post_type = $type;
        if ($model->load(Yii::$app->request->post()) && $modelImage->load(Yii::$app->request->post())) {
            $modelImage->uploaded_files = UploadedFile::getInstance($modelImage, 'uploaded_files');
            $imageId                    = $modelImage->uploadAndSave();
            if ($imageId)
            {
                $model->featured_image_id = $imageId;
            }
            $model->date = date('Y-m-d H:i:s');
            $model->save(false);
            return $this->redirect(['index','type'=>$type]);
        }

        return $this->render('create', [
            'model' => $model,
            'type' => $type,
        ]);
    }

    /**
     * Updates an existing CmsPost model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id,$type=null)
    {
        $model = $this->findModel($id);
         $modelImage = new Image;

        if ($model->load(Yii::$app->request->post()) && $modelImage->load(Yii::$app->request->post())) {
            $modelImage->uploaded_files = UploadedFile::getInstance($modelImage, 'uploaded_files');
            $imageId                    = $modelImage->uploadAndSave();
            if ($imageId)
            {
                $model->featured_image_id = $imageId;
            }

            $model->save(false);
            return $this->redirect(['index','type'=>$type]);
        }

        return $this->render('update', [
            'model' => $model,
            'type' => $type,
        ]);
    }

    /**
     * Deletes an existing CmsPost model.
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
    public function actionDeletePost($id)
    {
        $model = new CmsPost;
        $model->deletePost($id);
    }

    /**
     * Finds the CmsPost model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return CmsPost the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CmsPost::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
