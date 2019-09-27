<?php

namespace backend\controllers;

use Yii;
use backend\models\CmsHome;
use backend\models\CmsHomeSearch;
use backend\models\Image;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
/**
 * HomeController implements the CRUD actions for CmsHome model.
 */
class HomeController extends Controller
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
     * Lists all CmsHome models.
     * @return mixed
     */
    public function actionIndex()
    {
      $id = 1;
      $model = $this->findModel($id);

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Displays a single CmsHome model.
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
     * Creates a new CmsHome model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new CmsHome();
        $modelImage = new Image;
        if ($model->load(Yii::$app->request->post())&& $modelImage->load(Yii::$app->request->post())) {
             $images                       = UploadedFile::getInstanceByName('uploaded_files');
              $images1                       = UploadedFile::getInstanceByName('uploaded_files1');
              $images2                       = UploadedFile::getInstanceByName('uploaded_files2');
              $images3                       = UploadedFile::getInstanceByName('uploaded_files3');
              $images4                       = UploadedFile::getInstanceByName('uploaded_files4');
              $images5                       = UploadedFile::getInstanceByName('uploaded_files5');
              $images6                       = UploadedFile::getInstanceByName('uploaded_files6');
              $images7                       = UploadedFile::getInstanceByName('uploaded_files7');
              $images8                       = UploadedFile::getInstanceByName('uploaded_files8');
              $images9                       = UploadedFile::getInstanceByName('uploaded_files9');
              $images10                       = UploadedFile::getInstanceByName('uploaded_files10');
              $images11                       = UploadedFile::getInstanceByName('uploaded_files11');
              $images12                       = UploadedFile::getInstanceByName('uploaded_files12');
            
              $logo_image_uploads_path  = Yii::$app->params['home_image_base_urls'];
              $imageId             = $modelImage->uploadAndSave($images,$logo_image_uploads_path);
              $imageId1             = $modelImage->uploadAndSave($images1,$logo_image_uploads_path);
              $imageId2             = $modelImage->uploadAndSave($images2,$logo_image_uploads_path);
              $imageId3             = $modelImage->uploadAndSave($images3,$logo_image_uploads_path);
              $imageId4             = $modelImage->uploadAndSave($images4,$logo_image_uploads_path);
              $imageId5             = $modelImage->uploadAndSave($images5,$logo_image_uploads_path);
              $imageId6             = $modelImage->uploadAndSave($images6,$logo_image_uploads_path);
              $imageId7             = $modelImage->uploadAndSave($images7,$logo_image_uploads_path);
              $imageId8             = $modelImage->uploadAndSave($images8,$logo_image_uploads_path);
              $imageId9             = $modelImage->uploadAndSave($images9,$logo_image_uploads_path);
              $imageId10             = $modelImage->uploadAndSave($images10,$logo_image_uploads_path);
              $imageId11             = $modelImage->uploadAndSave($images11,$logo_image_uploads_path);
              $imageId12             = $modelImage->uploadAndSave($images12,$logo_image_uploads_path);
            if ($imageId)
            {
                $model->fk_image_banner = $imageId;
            }
            if ($imageId1)
            {
                $model->fk_image_top_box_one = $imageId1;
            }
            if ($imageId2)
            {
                $model->fk_image_top_box_two = $imageId2;
            }
            if ($imageId3)
            {
                $model->fk_image_top_box_three = $imageId3;
            }
            if ($imageId4)
            {
                $model->fk_image_abt = $imageId4;
            }
            if ($imageId5)
            {
                $model->fk_image_mid_four_one = $imageId5;
            }
            if ($imageId6)
            {
                $model->fk_image_mid_four_two = $imageId6;
            }
            if ($imageId7)
            {
                $model->fk_image_mid_four_three = $imageId7;
            }
            if ($imageId8)
            {
                $model->fk_image_mid_four_four = $imageId8;
            }
            if ($imageId9)
            {
                $model->fk_image_circle_menu_one = $imageId9;
            }
            if ($imageId10)
            {
                $model->fk_image_circle_menu_two = $imageId10;
            }
            if ($imageId11)
            {
                $model->fk_image_circle_menu_three = $imageId11;
            }
            if ($imageId12)
            {
                $model->fk_image_circle_menu_four = $imageId12;
            }

            $model->save(false);
            return $this->redirect(['index']);
        }
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing CmsHome model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);


        $modelImage = new Image;
        if ($model->load(Yii::$app->request->post())&& $modelImage->load(Yii::$app->request->post())) {
          // print_r("expression");die();
            $images                       = UploadedFile::getInstanceByName('uploaded_files');
              $images1                       = UploadedFile::getInstanceByName('uploaded_files1');
              $images2                       = UploadedFile::getInstanceByName('uploaded_files2');
              $images3                       = UploadedFile::getInstanceByName('uploaded_files3');
              $images4                       = UploadedFile::getInstanceByName('uploaded_files4');
              $images5                       = UploadedFile::getInstanceByName('uploaded_files5');
              $images6                       = UploadedFile::getInstanceByName('uploaded_files6');
              $images7                       = UploadedFile::getInstanceByName('uploaded_files7');
              $images8                       = UploadedFile::getInstanceByName('uploaded_files8');
              $images9                       = UploadedFile::getInstanceByName('uploaded_files9');
              $images10                       = UploadedFile::getInstanceByName('uploaded_files10');
              $images11                       = UploadedFile::getInstanceByName('uploaded_files11');
              $images12                       = UploadedFile::getInstanceByName('uploaded_files12');
                // print_r($images1 );die();
              $logo_image_uploads_path  = Yii::$app->params['home_image_base_urls'];
              $imageId             = $modelImage->uploadAndSave($images,$logo_image_uploads_path);
              $imageId1             = $modelImage->uploadAndSave($images1,$logo_image_uploads_path);

              $imageId2             = $modelImage->uploadAndSave($images2,$logo_image_uploads_path);
              $imageId3             = $modelImage->uploadAndSave($images3,$logo_image_uploads_path);
              $imageId4             = $modelImage->uploadAndSave($images4,$logo_image_uploads_path);
              $imageId5             = $modelImage->uploadAndSave($images5,$logo_image_uploads_path);
              $imageId6             = $modelImage->uploadAndSave($images6,$logo_image_uploads_path);
              $imageId7             = $modelImage->uploadAndSave($images7,$logo_image_uploads_path);
              $imageId8             = $modelImage->uploadAndSave($images8,$logo_image_uploads_path);
              $imageId9             = $modelImage->uploadAndSave($images9,$logo_image_uploads_path);
              $imageId10             = $modelImage->uploadAndSave($images10,$logo_image_uploads_path);
              $imageId11             = $modelImage->uploadAndSave($images11,$logo_image_uploads_path);
              $imageId12             = $modelImage->uploadAndSave($images12,$logo_image_uploads_path);
            if ($imageId)
            {
                $model->fk_image_banner = $imageId;
            }
            if ($imageId1)
            {
                $model->fk_image_top_box_one = $imageId1;
            }
            if ($imageId2)
            {
                $model->fk_image_top_box_two = $imageId2;
            }
            if ($imageId3)
            {
                $model->fk_image_top_box_three = $imageId3;
            }
            if ($imageId4)
            {
                $model->fk_image_abt = $imageId4;
            }
            if ($imageId5)
            {
                $model->fk_image_mid_four_one = $imageId5;
            }
            if ($imageId6)
            {
                $model->fk_image_mid_four_two = $imageId6;
            }
            if ($imageId7)
            {
                $model->fk_image_mid_four_three = $imageId7;
            }
            if ($imageId8)
            {
                $model->fk_image_mid_four_four = $imageId8;
            }
            if ($imageId9)
            {
                $model->fk_image_circle_menu_one = $imageId9;
            }
            if ($imageId10)
            {
                $model->fk_image_circle_menu_two = $imageId10;
            }
            if ($imageId11)
            {
                $model->fk_image_circle_menu_three = $imageId11;
            }
            if ($imageId12)
            {
                $model->fk_image_circle_menu_four = $imageId12;
            }

            $model->save(false);
             return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing CmsHome model.
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
     * Finds the CmsHome model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return CmsHome the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CmsHome::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
    public function actionCms() {
        $type=1;
      $data = Yii::$app->cms->getPostsDataProvider($type);
      print_r($data);die();
    }
    public function actionSettings() {
      $settings = Yii::$app->cms->getSettings();
      print_r($settings);die();
    }
    public function actionPost($slug) {
        
     $post =  Yii::$app->cms->getPost($slug);
     print_r($post);die();
    }
    public function actionPage($name) {
        // $type=1;
      $page =Yii::$app->cms->getPage($name);
      print_r($page);die();
    }
    public function actionLabel($name='select_your_option') {
        // $type=1;
      $page =Yii::$app->cms->getLabel($name);
      print_r($page);die();
    }
}
