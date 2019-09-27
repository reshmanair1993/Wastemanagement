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
        $ret           = [];
        if ($params)
        {
            $incidentTypeName = isset($params['incident_type']) ? $params['incident_type'] : null;
            $cameraId         = isset($params['camera_id']) ? $params['camera_id'] : null;
            if ($cameraId)
            {
                $modelCamera = $this->findModelCamera($cameraId);
                if ($modelCamera)
                {
                    $modelIncident->camera_id = $modelCamera->id;
                }
            }
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
            $ret = [
                'incident_id' => $modelIncident->id,
                'image_url'   => isset($incident_image_url) ? $incident_image_url : "",
                'video_url'   => isset($incident_video_url) ? $incident_video_url : ""
            ];
        }
        else
        {
            $ret = [
                'error' => $modelIncident->errors
            ];
        }

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
        $modelCamera = Camera::getAllQuery()->andWhere(['id' => $cameraId])->one();

        return $modelCamera;
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
        $per_page = 30
    )
    {
        $modelUser     = Yii::$app->user->identity;
        $userId        = $modelUser->id;
        $image_base    = isset(Yii::$app->params['incident_image_base_url']) ? Yii::$app->params['incident_image_base_url'] : null;
        $video_base    = isset(Yii::$app->params['incident_video_base_url']) ? Yii::$app->params['incident_video_base_url'] : null;
        $incident_base = 'http://139.162.54.79/wastemanagement/backend/web/';
        $query         = Incident::getAllQuery()
            ->andWhere(['incident.status' => 1])
            ->orderBy(['incident.id' => SORT_DESC])
            ->leftJoin('camera', 'camera.id=incident.camera_id')
            ->andWhere(['camera.account_id_technician' => $userId]);
        $dataProvider = new ActiveDataProvider([
            'query'      => $query,
            'pagination' => [
                'pageSize' => $per_page,
                'page'     => $page - 1
            ]

        ]);
        $models = $dataProvider->getModels();
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
                'sharing_url' => null,
                'camera'      => $cameraDetails,
                'length'      => $model->duration,
                'datetime'    => $model->captured_at ? $model->captured_at : null,
                'image'       => $image,
                'video'       => $video,
                'meta'        => $metaDetails

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
    public function actionCameraList(
        $page = 1,
        $per_page = 30
    )
    {
        $modelUser = Yii::$app->user->identity;
        $userId    = $modelUser->id;
        $query     = Camera::getAllQuery()
            ->andWhere(['camera.status' => 1])
            ->orderBy(['camera.id' => SORT_DESC])
            ->andWhere(['camera.account_id_technician' => $userId]);
        $dataProvider = new ActiveDataProvider([
            'query'      => $query,
            'pagination' => [
                'pageSize' => $per_page,
                'page'     => $page - 1
            ]

        ]);
        $models = $dataProvider->getModels();
        $ret    = [];
        foreach ($models as $model)
        {
            $ret = [
                'id'             => $model->id,
                'name'           => $model->name,
                'ward'           => $model->fkWard ? $model->fkWard->name : null,
                'lat'            => $model->lat,
                'lng'            => $model->lng,
                'incident_count' => [
                    'new' => $model->getIncidentCount($model->id)
                ]
            ];
        }

        return $ret;
    }
}
