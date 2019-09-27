<?php
namespace api\modules\v1\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use  api\modules\v1\models\Incident;
use  api\modules\v1\models\IncidentType;
use  api\modules\v1\models\Image;
use api\modules\v1\models\Camera;
use  api\modules\v1\models\FileVideo;
use  api\modules\v1\models\IncidentMeta;
use yii\filters\auth\HttpBearerAuth;
use yii\rest\ActiveController;
use yii\filters\AccessControl;
use yii\web\UploadedFile;


class CameraIncidentsController extends ActiveController
{
     public $modelClass = '\api\modules\v1\models\Incident';
     public function actions() {
             $actions = parent::actions();
             $unsetActions = ['create','update','index','delete'];
             foreach($unsetActions as $action) {
               unset($actions[$action]);
             }
             return $actions;
     }
	 public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
            'access' => [
              'class' => AccessControl::className(),
              'rules' => [
                [
                  'actions' => ['add-incident'],
                  'allow' => true,
                  'roles' => ['?'],
                ],
              ],
            ],
        ];
    }

    public function actionAddIncident()
    {
        $modelIncident = new Incident;
        $modelCamera   = new Camera;
        $params        = Yii::$app->request->post();
        $ret           = [];
        // print_r($params);exit;
        if ($params)
        {
            $incidentTypeName = isset($params['incident_type']) ? $params['incident_type'] : null;
            $cameraId         = isset($params['camera_id']) ? $params['camera_id'] : null;
            if (isset($params['camera_id']))
            {
                $modelCamera = $this->findModelCamera($cameraId);
                // print_r($modelCamera);exit;
                if ($modelCamera)
                {
                    $modelIncident->camera_id = $modelCamera->id;
                }
                else
                {
                  $msg = ['This camera not exist'];
                  $error = ['camera_id'=>$msg];
                  $ret = ['errors' =>$error];
                  return $ret;
                }

            }
            else
            {
              $msg = ['Camera can not be blank'];
              $error = ['camera_id'=>$msg];
              $ret = ['errors' =>$error];
              return $ret;
            }
             if (isset($params['incident_type']))
            {
                $modelIncidentType = $this->findModelIncidentType($incidentTypeName);
                if ($modelIncidentType == null)
                {
                    $modelIncidentType       = new IncidentType;
                    $modelIncidentType->name = $incidentTypeName;
                    $modelIncidentType->save(false);
                    $modelIncident->incident_type_id = $modelIncidentType->id;
                }
                else
                {
                    $modelIncident->incident_type_id = $modelIncidentType->id;
                }
            }
            else {
              $msg = ['Incident Type Can not be blank'];
              $error = ['incident_type'=>$msg];
              $ret = ['errors' =>$error];
              return $ret;
            }
            // if(!isset($params['incident_type']) && isset($params['camera_id']))
            // {
            //   $msg = ['Camera can not be blank'];
            //   $error = ['camera_id'=>$msg];
            //   $ret = ['errors' =>$error];
            //   return $ret;
            // }
            $capturedAt = isset($params['captured_at']) ? $params['captured_at'] : date('Y-m-d H:i:s');
            $duration   = isset($params['duration']) ? $params['duration'] : null;

            if(isset($params['incident_type']) && isset($params['camera_id']))
            {
              $modelImage                   = new Image;
              $modelVideo                   = new FileVideo;
              $images                       = UploadedFile::getInstanceByName('photo');
              $incident_image_uploads_path  = Yii::$app->params['incident_image_uploads_path'];
              $modelImageSaveId             = $modelImage->uploadAndSave($images, $incident_image_uploads_path);
              $videos                       = UploadedFile::getInstanceByName('video');
              $modelVideoSaveId             = $modelVideo->uploadAndSave($videos);
              $modelIncident->image_id      = $modelImageSaveId;
              $modelIncident->file_video_id = $modelVideoSaveId;
              $modelIncident->captured_at   = $capturedAt;
              $modelIncident->duration      = $duration;
              $modelIncident->save(false);
              $meta = isset($params['meta']) ? $params['meta'] : null;
              if ($meta)
              {
                  foreach ($meta as $key => $value)
                  {
                      $modelMeta               = new IncidentMeta;
                      $modelMeta->incident_key = $key;
                      $modelMeta->value        = $value;
                      $modelMeta->incident_id  = $modelIncident->id;
                      $modelMeta->save(false);
                      // print_r($mode)
                  }
              }
              $image_url = $this->findModelImage($modelIncident->image_id);
              $video_url = $this->findModelVideo($modelIncident->file_video_id);
              if ($image_url)
              {
                  $incident_image_url = Yii::$app->params['incident_image_base_url'] . $image_url->uri_full;
              }
              if ($video_url)
              {
                  $incident_video_url = Yii::$app->params['incident_video_base_url'] . $video_url->url;
              }
              $ret = [
                'incident_id' => $modelIncident->id,
                'image_url'   => isset($incident_image_url) ? $incident_image_url : "",
                'video_url'   => isset($incident_video_url) ? $incident_video_url : ""
            ];
            return $ret;
            }


          }
      }
    protected function  findModelIncidentType($incidentTypeName)
    {
      $modelIncidentType = IncidentType::getAllQuery()->andWhere(['name' => $incidentTypeName])->one();
      return $modelIncidentType;
    }
    protected function  findModelImage($image_id)
    {
      $modelImageId = Image::find()->andWhere(['id' => $image_id])->one();
      return $modelImageId;
    }
    protected function  findModelVideo($video_id)
    {
      $modelVideoId = FileVideo::find()->andWhere(['id' => $video_id])->one();
      return $modelVideoId;
    }
    protected function findModelCamera($cameraId)
    {
        $modelCamera = Camera::find()->andWhere(['id' => $cameraId,'status' => 1])->one();
        return $modelCamera;
    }
}
