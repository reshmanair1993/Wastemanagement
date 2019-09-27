<?php
namespace backend\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\UploadedFile;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use backend\models\CameraInstallationCheckList;
use backend\models\Image;
use yii\helpers\ArrayHelper;
use backend\components\AccessPermission;

/**
 * Site controller
 */
class CameraInstallationController extends Controller
{
    /**
     * {@inheritdoc}
     */

     public function behaviors()
     {
       return [
           'access' => [
               'class'        => AccessControl::className(),
               'only'         => ['index','create','delete-association','view'],
               'ruleConfig' => [
                       'class' => AccessPermission::className(),
                   ],
               'rules'        => [
                   [
                       'actions' => ['index'],
                       'allow'   => true,
                       'permissions' => ['camera-installation-index']
                   ],
                   [
                       'actions' => ['create'],
                       'allow'   => true,
                       'permissions' => ['camera-installation-create']
                   ],
                   [
                       'actions' => ['update'],
                       'allow'   => true,
                       'permissions' => ['camera-installation-update']
                   ],
                   [
                       'actions' => ['delete-camera-installation'],
                       'allow'   => true,
                       'permissions' => ['camera-installation-delete-camera-installation']
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
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
      $modelCameraInstallationCheckList = new CameraInstallationCheckList;
      $dataProvider = $modelCameraInstallationCheckList->search(Yii::$app->request->queryParams);
      $params = [
        'modelCameraInstallationCheckList' => $modelCameraInstallationCheckList,
        'dataProvider' => $dataProvider,
      ];
      return $this->render('index',[
        'params' => $params,
      ]);
    }

    public function actionCreate()
    {
      $saved = false;
      $modelCameraInstallationCheckList = new CameraInstallationCheckList;
      $modelImage = new Image;
      $params = Yii::$app->request->post();
      $paramsOk = $params && $modelCameraInstallationCheckList->load($params) && $modelImage->load($params);
      if($paramsOk){
        $modelCameraInstallationCheckListOk = $modelCameraInstallationCheckList->validate();
        $modelImage->uploaded_files = UploadedFile::getInstance($modelImage, 'uploaded_files');
        $imgId                    = $modelImage->uploadAndSave();
          if($imgId) {
              $modelCameraInstallationCheckList->image_id = $imgId;
          }
          $modelCameraInstallationCheckList->save(false);
          // $saved = true;
          // $modelResidentialAssociation = new ResidentialAssociation;
          return $this->redirect('index');
      }
      $params = [
        'modelCameraInstallationCheckList' => $modelCameraInstallationCheckList,
        'modelImage' => $modelImage,
      ];
      return $this->render('add-camera-installation',[
        'params' => $params,
      ]);
    }
    public function actionUpdate($id){
      $modelCameraInstallationCheckList = $this->findModelCameraInstallationCheckList($id);
      $modelImage = new Image;
      $params = Yii::$app->request->post();
      $paramsOk = $params && $modelCameraInstallationCheckList->load($params) && $modelImage->load($params);
      if($paramsOk){
        $modelCameraInstallationCheckListOk = $modelCameraInstallationCheckList->validate();
        $modelImage->uploaded_files = UploadedFile::getInstance($modelImage, 'uploaded_files');
        $imgId                    = $modelImage->uploadAndSave();
          if($imgId) {
              $modelCameraInstallationCheckList->image_id = $imgId;
          }
          $modelCameraInstallationCheckList->update(false);
          // $saved = true;
          // $modelResidentialAssociation = new ResidentialAssociation;
          return $this->redirect('index');
      }
      $params = [
        'modelCameraInstallationCheckList' => $modelCameraInstallationCheckList,
        'modelImage' => $modelImage,
      ];
      return $this->render('update-camera-installation',[
        'params' => $params,
      ]);
    }
    public function findModelCameraInstallationCheckList($id){
      $modelCameraInstallationCheckList = CameraInstallationCheckList::find()->where(['status'=>1,'id'=>$id])->one();
      return $modelCameraInstallationCheckList;
    }
    public function actionDeleteCameraInstallation($id)
    {
      $modelCameraInstallationCheckList = new CameraInstallationCheckList;
      $modelCameraInstallationCheckList->deleteCameraInstallation($id);
    }
    /**
     * Login action.
     *
     * @return string
     */

    /**
     * Logout action.
     *
     * @return string
     */

}
