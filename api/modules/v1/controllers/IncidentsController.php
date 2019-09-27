<?php
namespace api\modules\v1\controllers;

use Yii;
use yii\web\UploadedFile;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\rest\ActiveController;
use api\modules\v1\models\Image;
use yii\data\ActiveDataProvider;
use api\modules\v1\models\Camera;
use api\modules\v1\models\Incident;
use api\modules\v1\models\FileVideo;
use yii\filters\auth\HttpBearerAuth;
use api\modules\v1\models\IncidentMeta;
use api\modules\v1\models\IncidentType;
use api\modules\v1\models\IncidentImage;

class IncidentsController extends ActiveController
{
    /**
     * @var string
     */
    public $modelClass = '\api\modules\v1\models\Incident';
    /**
     * @return mixed
     */
    public function actions()
    {
        $actions      = parent::actions();
        $unsetActions = ['create', 'update', 'index', 'delete'];
        foreach ($unsetActions as $action)
        {
            unset($actions[$action]);
        }

        return $actions;
    }

    public function behaviors()
    {
        return [
            'verbs'  => [
                'class'   => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST']
                ]
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['add-incident', 'index', 'camera-list'],
                        'allow'   => true,
                        'roles'   => ['?']
                    ]
                ]
            ],
            'auth'   => [
                'class' => HttpBearerAuth::className()
            ]
        ];
    }

    /**
     * @return mixed
     */
    public function actionAddIncident()
    {
        $modelIncident = new Incident;
        $modelCamera   = new Camera;
        $params        = Yii::$app->request->post();
        $photo = $_FILES;
       $merged_array = [
       'post_params'=>$params,
       'files'=>$photo,
       ];
       // array_merge($params,$photo);
       // print_r($merged_array);die();
       $postJson = json_encode($merged_array);
       // $this->loguse  api\modules\v1\models\Log;($postJson);
        $ret           = [];
        if ($params)
        {
             if (!isset($params['camera_id'])&&!isset($params['host_name']))
        {
          $msg = ['Camera id or Host name required'];
          $error = ['camera_id'=>$msg];
          $ret = ['errors' =>$error];
          return $ret;
        }
        if (!isset($params['incident_type']))
        {
          $msg = ['Incident Type Can Not be Blank'];
          $error = ['incident_type'=>$msg];
          $ret = ['errors' =>$error];
          return $ret;
        }

            $incidentTypeName = isset($params['incident_type']) ? $params['incident_type'] : null;
            $cameraId         = isset($params['camera_id']) ? $params['camera_id'] : null;
            $hostName         = isset($params['host_name']) ? $params['host_name'] : null;
            if ($cameraId)
            {
                $modelCamera = $this->findModelCamera($cameraId);
                if ($modelCamera)
                {
                    $modelIncident->camera_id = $modelCamera->id;
                }
                   else{
          $msg = ['Invalid camera id'];
          $error = ['camera_id'=>$msg];
          $ret = ['errors' =>$error];
          return $ret;
                }  
            }elseif($hostName)
            {
              $modelCamera = $this->findModelCameraByHost($hostName);
                if ($modelCamera)
                {
                    $modelIncident->camera_id = $modelCamera->id;
                }  
                else{
          $msg = ['Invalid host name'];
          $error = ['host_name'=>$msg];
          $ret = ['errors' =>$error];
          return $ret;
                }  
            }
            // print_r($modelCamera);die();
            $capturedAt = isset($params['captured_at']) ? $params['captured_at'] : null;
            $duration   = isset($params['duration']) ? $params['duration'] : null;
            if ($incidentTypeName)
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
            $modelImage                   = new Image;
            $modelVideo                   = new FileVideo;
            $images                       = UploadedFile::getInstanceByName('photo');
            $imagesArray                  = UploadedFile::getInstancesByName('incident_images'); 
            $videos                       = UploadedFile::getInstanceByName('video');
            $incident_image_uploads_path  = Yii::$app->params['incident_image_uploads_path'];
           $modelImageSaveIdList             = $modelImage->uploadAndSave($imagesArray, $incident_image_uploads_path);
          
            if(isset($images->size)&&$images->size>0){
                 $modelImageSaveId             = $modelImage->uploadAndSave($images, $incident_image_uploads_path);
             }else
             {

                 $msg   =    ['File size is zero'];
                    $error = ['photo' => $msg];
                    $ret   = ['errors' => $error];
                    // break;

                    return $ret;
             }
             if(isset($videos->size)&&$videos->size>0){
                 $modelVideoSaveId             = $modelVideo->uploadAndSave($videos);
             }else
             {
                
                 $msg   =    ['File size is zero'];
                    $error = ['video' => $msg];
                    $ret   = ['errors' => $error];
                    // break;

                    return $ret;
             }
            // $modelVideoSaveId             = $modelVideo->uploadAndSave($videos);
            $modelIncident->image_id      = $modelImageSaveId;
            $modelIncident->file_video_id = $modelVideoSaveId;
            $modelIncident->captured_at   = $capturedAt;
            $modelIncident->duration      = $duration;
            $modelIncident->vehicle_number      = isset($params['vehicle_number'])?$params['vehicle_number']:null;
            $modelIncident->vehicle_type      = isset($params['vehicle_type'])?$params['vehicle_type']:null;
            $modelIncident->save(false);

            if($modelImageSaveIdList)
          {
            foreach ($modelImageSaveIdList as $value) {
               $modelIncidentImage = new IncidentImage;
               $modelIncidentImage->image_id = $value;
               $modelIncidentImage->incident_id = $modelIncident->id;
               $modelIncidentImage->save(false);
            }
          }

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
                }
            }
            if(isset($params['video_height'])||isset($params['video_width'])||isset($params['image_height'])||isset($params['image_width']))
            {
                if(isset($params['video_width']))
                {
                    $modelMeta = new IncidentMeta;
                    $modelMeta->incident_key = 'video_width';
                    $modelMeta->value        = $params['video_width'];
                    $modelMeta->incident_id  = $modelIncident->id;
                    $modelMeta->save(false);
                }
                if(isset($params['video_height']))
                {
                    $modelMeta = new IncidentMeta;
                    $modelMeta->incident_key = 'video_height';
                    $modelMeta->value        = $params['video_height'];
                    $modelMeta->incident_id  = $modelIncident->id;
                    $modelMeta->save(false);
                }
                if(isset($params['image_width']))
                {
                    $modelMeta = new IncidentMeta;
                    $modelMeta->incident_key = 'image_width';
                    $modelMeta->value        = $params['image_width'];
                    $modelMeta->incident_id  = $modelIncident->id;
                    $modelMeta->save(false);
                }
                if(isset($params['image_height']))
                {
                    $modelMeta = new IncidentMeta;
                    $modelMeta->incident_key = 'image_height';
                    $modelMeta->value        = $params['image_height'];
                    $modelMeta->incident_id  = $modelIncident->id;
                    $modelMeta->save(false);
                }
            }
            $image_url = $this->findModelImage($modelIncident->image_id);
            $video_url = $this->findModelVideo($modelIncident->file_video_id);
            if ($image_url)
            {
                $incident_image_url = Yii::$app->params['incident_image_base_url'] . $image_url->uri_full;}
            if ($video_url)
            {
                $incident_video_url = Yii::$app->params['incident_video_base_url'] . $video_url->url;}
        }
        if ($modelIncident->validate())
        {
             $incidentImages = IncidentImage::find()->where(['incident_id'=>$modelIncident->id])->andWhere(['status'=>1])->all();
             $incidentImageNames = '';
             if($incidentImages)
             {
                foreach ($incidentImages as $incidentImage) {
                   $incidentImageData = $this->findModelImage($incidentImage->image_id);
                   if($incidentImageData){
                    $incidentImageNames = $incidentImageNames.$incidentImageData->uri_full.',';
                   }
                }
             }
             $incident_images = rtrim($incidentImageNames,',');
            $ret = [
            'base_url' =>Yii::$app->params['incident_image_base_url'],
                'incident_id' => $modelIncident->id,
                'image_url'   => isset($incident_image_url) ? $incident_image_url : "",
                'video_url'   => isset($incident_video_url) ? $incident_video_url : "",
                'vehicle_number'   => $modelIncident->vehicle_number,
                'vehicle_type'   => $modelIncident->vehicle_type,
                'incident_images' =>[$incident_images],
            ];
        }
        // else
        // {
        //     $ret = [
        //         'error' => $modelIncident->errors
        //     ];
        // }

        return $ret;
    }

    /**
     * @param  $incidentTypeName
     * @return mixed
     */
    protected function findModelIncidentType($incidentTypeName)
    {
        $modelIncidentType = IncidentType::getAllQuery()->andWhere(['name' => $incidentTypeName])->one();

        return $modelIncidentType;
    }

    /**
     * @param  $cameraId
     * @return mixed
     */
     protected function findModelCamera($cameraId)
     {
         $modelCamera = Camera::find()->andWhere(['id' => $cameraId,'status' => 1])->one();
         return $modelCamera;
     }
      protected function findModelCameraByHost($hostName)
    {
       $modelCamera = Camera::find()->andWhere(['host_name' => $hostName,'status' => 1])->one();
        // if ($modelCamera) {
            return $modelCamera;
        // }

        // throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    /**
     * @param  $image_id
     * @return mixed
     */
    protected function findModelImage($image_id)
    {
        $modelImageId = Image::find()->andWhere(['id' => $image_id])->one();

        return $modelImageId;
    }

    /**
     * @param  $video_id
     * @return mixed
     */
    protected function findModelVideo($video_id)
    {
        $modelVideoId = FileVideo::find()->andWhere(['id' => $video_id])->one();

        return $modelVideoId;
    }

    /**
     * @param  $page
     * @param  $per_page
     * @return mixed
     */
    public function actionIndex(
        $page = 1,
        $per_page = 30,
        $camera_id=null,$ward_id=null,$serial_no=null,$memo_id=null
    )
    {
        $modelUser     = Yii::$app->user->identity;
        $userId        = $modelUser->id;
        // $image_base    = 'http://139.162.54.79'.isset(Yii::$app->params['incident_image_base_url']) ? Yii::$app->params['incident_image_base_url'] : null;
         $image_base    = Yii::$app->params['incident_image_base_url'] ? Yii::$app->params['incident_image_base_url'] : null;
        $video_base    = isset(Yii::$app->params['incident_video_base_url']) ? Yii::$app->params['incident_video_base_url'] : null;
        // $incident_base = 'http://139.162.54.79/wastemanagement/backend/web/memos/preview?id=';
        $incident_base = 'http://139.162.54.79/wms-demo/backend/web/incidents/incident-detail?id=';
        $role = $modelUser->role;
        $query         = Incident::getAllQuery()
        ->andWhere(['incident.status' => 1])
        ->andWhere(['incident.is_approved' => 1])
            ->orderBy(['incident.created_at' => SORT_DESC])
            ->leftJoin('camera', 'camera.id=incident.camera_id');
        if($role=='camera-technician'){


            $query->andWhere(['camera.account_id_technician' => $userId]);
        }
        elseif($role=='camera-monitoring-admin')
        {
            $query->leftJoin('monitoring_group_camera','monitoring_group_camera.camera_id=camera.id')
            ->leftJoin('monitoring_group_user','monitoring_group_camera.monitoring_group_id=monitoring_group_user.monitoring_group_id')
            ->andWhere(['monitoring_group_user.account_id' => $userId]);
        }
        if($camera_id)
        {
            $query->andWhere(['camera.id'=>$camera_id]);
        }
         if($ward_id)
        {
            $query->andWhere(['camera.ward_id'=>$ward_id]);
        }
        if($memo_id)
        {
            $query->leftJoin('memo','memo.incident_id=incident.id')
            ->andWhere(['memo.id'=>$memo_id]);
        }
        if($serial_no)
        {
            $query->andFilterWhere(['like', 'camera.serial_no', $serial_no]);
        }
        $dataProvider = new ActiveDataProvider([
            'query'      => $query,
            'pagination' => [
                'pageSize' => 50,
                'page'     => $page - 1
            ]

        ]);
        $models = $dataProvider->getModels();
        // print_r($models);die();
        $ret    = [];
        foreach ($models as $model)
        {
            $cameraDetails = null;
            $modelCamera   = $model->fkCamera;
            if ($modelCamera)
            {
                $cameraDetails = [
                    'id'   => $modelCamera->id,
                    'name' => $modelCamera->name,
                    'ward' => $modelCamera->fkWard ? $modelCamera->fkWard->name : null,
                    'lat'  => $modelCamera->lat,
                    'lng'  => $modelCamera->lng
                ];
            }
            $metaDetails = null;
            $modelMeta   = IncidentMeta::find()->where(['incident_id' => $model->id])->all();
            if ($modelMeta)
            {
                foreach ($modelMeta as $value)
                {
                    $metaDetails[] = [
                        $value->incident_key => $value->value
                    ];
                }
            }
            $image      = null;
            $modelImage = $model->fkImage;
            if ($modelImage)
            {
                $image = $modelImage->uri_full;
            }

            $video          = null;
            $modelFileVideo = $model->fkFileVideo;
            if ($modelFileVideo)
            {
                $video = $modelFileVideo->url;
            }

            $ret[] = [
                'id'          => $model->id,
                'type'        => $model->fkIncidentType?$model->fkIncidentType->name:null,
                'sharing_url' => $model->getSharingUrl(),
                'camera'      => $cameraDetails,
                'length'      => $model->duration,
                'datetime'    => $model->captured_at ? $model->captured_at : null,
                'image'       => $image,
                'video'       => $video,
                'meta'        => $metaDetails,
                'memo_id'     => $model->getMemo(),
                'video_height'=> $model->getVideoHeight($model->id),
                'video_width'=> $model->getVideoWidth($model->id),
                'image_height'=> $model->getImageHeight($model->id),
                'image_width'=> $model->getImageWidth($model->id),

            ];
        }

        $ret = array_values($ret);

        return $ret = [
            'image_base'    => $image_base,
            'video_base'    => $video_base,
            'incident_base' => $incident_base,
            'items'         => $ret
        ];
    }

    /**
     * @param $page
     * @param $per_page
     * @return mixed
     */

}
