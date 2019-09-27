<?php
namespace api\modules\v1\controllers;

use Yii;
use yii\filters\VerbFilter;
use yii\rest\ActiveController;
use yii\data\ActiveDataProvider;
use api\modules\v1\models\Camera;
use api\modules\v1\models\CameraQrCode;
use yii\filters\auth\HttpBearerAuth;
use api\modules\v1\models\CameraService;
use api\modules\v1\models\CameraServiceAssignment;
use api\modules\v1\models\CameraServicingStatusOption;
use api\modules\v1\models\Image;
use yii\web\UploadedFile;
class CameraController extends ActiveController
{
    /**
     * @var string
     */
    public $modelClass = '\api\modules\v1\models\Camera';
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
            'verbs' => [
                'class'   => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST']
                ]
            ],
            'auth'  => [
                'class' => HttpBearerAuth::className()
            ]
        ];
    }

    /**
     * @return mixed
     */
    public function actionRegistration($camera_id = null)
    {
          $modelImage                   = new Image;
        $modelUser = Yii::$app->user->identity;
        $userId    = $modelUser->id;
        $ret       = [];
        $params    = Yii::$app->request->post();
	$camera_id = $camera_id?$camera_id:null;
        if($params && isset($params['camera_id'])) {
          $camera_id = $params['camera_id'];
        } 
        while (true)
        {
            if (!$camera_id)
            {
                if (!isset($params['name']))
                {
                    $msg   = ['Name is mandatory'];
                    $error = ['name' => $msg];
                    $ret   = ['errors' => $error];
                    break;

                    return $ret;
                }
                if (!isset($params['host_name']))
                {
                    $msg   = ['Host name is mandatory'];
                    $error = ['host_name' => $msg];
                    $ret   = ['errors' => $error];
                    break;

                    return $ret;
                }
                if (isset($params['host_name'])&&!$camera_id)
                {
                    $cameraData = Camera::find()->where(['host_name'=>$params['host_name']])->andWhere(['status'=>1])->one();
                    if($cameraData){
                    $msg   = ['Host name already exists'];
                    $error = ['host_name' => $msg];
                    $ret   = ['errors' => $error];
                    break;

                    return $ret;
                }
                }
                if (!isset($params['ward_id']))
                {
                    $msg   = ['Ward is mandatory'];
                    $error = ['ward_id' => $msg];
                    $ret   = ['errors' => $error];
                    break;

                    return $ret;
                }
                if (!isset($params['lat']))
                {
                    $msg   = ['Latitude is mandatory'];
                    $error = ['lat' => $msg];
                    $ret   = ['errors' => $error];
                    break;

                    return $ret;
                }
                if (!isset($params['lng']))
                {
                    $msg   = ['Longitude is mandatory'];
                    $error = ['lng' => $msg];
                    $ret   = ['errors' => $error];
                    break;

                    return $ret;
                }
                if (!isset($params['qr_code']))
                {
                    $msg   = ['Qr code is mandatory'];
                    $error = ['qr_code' => $msg];
                    $ret   = ['errors' => $error];
                    break;

                    return $ret;
                }
            }
            if ($params)
            {
                if (!$camera_id)
                {
                    $modelCamera = new Camera;
                }
                else
                {
                    $modelCamera = Camera::find()->where(['id' => $camera_id])->one();
                }
// echo "test ";exit;
                 $images                       = UploadedFile::getInstanceByName('photo');
            $camera_image_uploads_path  = Yii::$app->params['camera_image_uploads_path'];
            // $modelImageSaveId             = $modelImage->uploadAndSave($images, $camera_image_uploads_path);
            // $modelCamera->image_id      = $modelImageSaveId;
            if($images){
            $modelImageSaveId             = $modelImage->uploadAndSave($images, $camera_image_uploads_path);
            $modelCamera->image_id      = $modelImageSaveId;
        }
                $modelCamera->name                  = isset($params['name']) ? $params['name'] : $modelCamera->name;
                $modelCamera->host_name                  = isset($params['host_name']) ? $params['host_name'] : $modelCamera->host_name;
                $modelCamera->ward_id               = isset($params['ward_id']) ? $params['ward_id'] : $modelCamera->ward_id;
                $modelCamera->lat                   = isset($params['lat']) ? $params['lat'] : $modelCamera->lat;
                $modelCamera->lng                   = isset($params['lng']) ? $params['lng'] : $modelCamera->lng;
                  $modelCamera->location_name                   = isset($params['location_name']) ? $params['location_name'] : $modelCamera->location_name;
                $modelCamera->account_id_technician = $userId;
                $modelQrCode                        = CameraQrCode::find()->where(['value' => $params['qr_code']])->andWhere(['account_id' => null])->andWhere(['status' => 1])->one();
                if (!$camera_id)
                {
                    if ($modelQrCode)
                    {
                        $modelQrCode->account_id = $userId;
                        $modelQrCode->save(false);
                        $modelCamera->qr_code_id = $modelQrCode->id;
                        $modelCamera->serial_no  = $modelQrCode->value;

                        $modelCamera->save(false);
                    }
                    else
                    {
                        $msg   = ['Qr code is invalid'];
                        $error = ['qr_code' => $msg];
                        $ret   = ['errors' => $error];
                        break;

                        return $ret;
                    }
                }
                 $image_url = $this->findModelImage($modelCamera->image_id);
                 if ($image_url)
            {
                $camera_image_url = Yii::$app->params['camera_image_base_url'] . $image_url->uri_full;}
                $ret = [
                    'id'      => $modelCamera->id,
                    'name'    => $modelCamera->name,
                    'lat'     => $modelCamera->lat,
                    'lng'     => $modelCamera->lng,
                    'lng'     => $modelCamera->lng,
                    'location_name'     => $modelCamera->location_name,
                    'qr_code' => $modelCamera->serial_no,
                    'image_url'   => isset($camera_image_url) ? $camera_image_url : "",
                ];
            }
            break;
        }

        return $ret;
    }
    protected function findModelImage($image_id)
    {
        $modelImageId = Image::find()->andWhere(['id' => $image_id])->one();

        return $modelImageId;
    }

    /**
     * @param $serial_no
     * @return mixed
     */
    public function actionCameraId($serial_no = null)
    {
        $query = Camera::getAllQuery();
        if ($serial_no)
        {
            $query->andWhere(['serial_no' => $serial_no]);
            $dataProvider = new ActiveDataProvider([
                'query' => $query

            ]);
            $models = $dataProvider->getModels();
            $data   = Camera::find()->where(['serial_no' => $serial_no])->one();
            if ($data)
            {
                foreach ($models as $model)
                {
                    $date1 = isset($model->fkHeartBeat->timestamp)?$model->fkHeartBeat->timestamp:date('Y-m-d H:i:s');
            $date2 = date('Y-m-d H:i:s');
            $hrs = isset($model->fkWard->fkLsgi->camera_fault_calculation_interval_hours)?$model->fkWard->fkLsgi->camera_fault_calculation_interval_hours:null;
            $hours = round((strtotime($date2) - strtotime($date1))/3600, 1);
            if($hours>=$hrs){
                $active = 0;
            }
            else
            {
                $active =1;
            }
                    $ret = [
                        'id' => $model->id,
                         'camera_active'=> isset($model->fkHeartBeat->camera_active)?$model->fkHeartBeat->camera_active:null,
                         'is_active'=>$active,
                        'last_heart_beat_timestamp'=> $model->getHeartBeat($model->id),
                'camera_fault_calculation_interval_hours'=> isset($model->fkWard->fkLsgi->camera_fault_calculation_interval_hours)?$model->fkWard->fkLsgi->camera_fault_calculation_interval_hours:null,
                'location_name'=>$model->location_name,
                    ];

                    return $ret;
                }
            }
            else
            {
                $msg   = ['Invalid serial number'];
                $error = ['serial_no' => $msg];
                $ret   = ['errors' => $error];

                return $ret;
            }
        }
        else
        {
            $msg   = ['Serial number mandatory'];
            $error = ['serial_no' => $msg];
            $ret   = ['errors' => $error];

            return $ret;
        }
    }

    /**
     * @param $service_id
     * @return mixed
     */
    public function actionServiceStatus($service_id = null)
    {
        $image_base = isset(Yii::$app->params['base_url_technician']) ? Yii::$app->params['base_url_technician'] : null;
        $query      = CameraServicingStatusOption::getAllQuery();
        if ($service_id)
        {
            $query->andWhere(['service_id' => $service_id]);
            $dataProvider = new ActiveDataProvider([
                'query' => $query

            ]);
            $models = $dataProvider->getModels();
            $models = $dataProvider->getModels();
            $items  = [];
            foreach ($models as $model)
            {
                $image      = null;
                $modelImage = $model->fkImage;
                if ($modelImage)
                {
                    $image = $modelImage->uri_full;
                }

                $items[] = [
                    'id'    => $model->id,
                    'name'  => $model->value,
                    'image' => $image
                ];
            }

            return $ret = [
                'image_base' => $image_base,
                'items'      => $items
            ];
        }
        else
        {
            $msg   = ['Service id is mandatory'];
            $error = ['service_id' => $msg];
            $ret   = ['errors' => $error];

            return $ret;
        }
    }

    /**
     * @return mixed
     */
    public function actionStatusOptions()
    {
        $modelUser = Yii::$app->user->identity;
        $userId    = $modelUser->id;
        $ret       = [];

        while (true)
        {
            $params = Yii::$app->request->post();
            if (!isset($params['service_assignment_id']))
            {
                $msg   = ['Service Assignment id is mandatory'];
                $error = ['service_assignment_id' => $msg];
                $ret   = ['errors' => $error];

                return $ret;
            }
            if (!isset($params['status_option_id']))
            {
                $msg   = ['Status option is mandatory'];
                $error = ['status_option_id' => $msg];
                $ret   = ['errors' => $error];

                return $ret;
            }
            if (!isset($params['lat']))
                {
                  $msg   = ['Latitude is mandatory'];
                  $error = ['lat' => $msg];
                  $ret   = ['errors' => $error];  
                  return $ret;
                }
                if (!isset($params['lng']))
                {
                  $msg   = ['Longitude is mandatory'];
                  $error = ['lng' => $msg];
                  $ret   = ['errors' => $error];  
                  return $ret;
                }
            if (isset($params['service_assignment_id']) && isset($params['status_option_id']))
            {
                $modelServiceAssignment                                    = CameraServiceAssignment::find()->where(['id'=>$params['service_assignment_id']])->one();;
                $modelServiceAssignment->camera_servicing_status_option_id = isset($params['status_option_id']) ? $params['status_option_id'] : '';
                $modelServiceAssignment->lat_update_from                    = $params['lat'];
                    $modelServiceAssignment->lng_updated_from                   = $params['lng'];
                $modelServiceAssignment->save(false);
                $ret = [
                    'service_assignment_id'       => $modelServiceAssignment->id,
                    'service_id'       => $modelServiceAssignment->service_id,
                    'status_option_id' => $modelServiceAssignment->camera_servicing_status_option_id,
                    'camera_id'        => $modelServiceAssignment->camera_id,
                    'lat'           => $modelServiceAssignment->lat_update_from,
                        'lng'           => $modelServiceAssignment->lng_updated_from
                    // 'date'           => $modelServiceAssignment->date
                ];
            }
            break;
        }

        return $ret;
    }

    /**
     * @param $page
     * @param $per_page
     * @param $keyword
     * @return mixed
     */
    public function actionServicesMaster(
        $page = 1,
        $per_page = 30,
        $keyword = null
    )
    {
        $image_base = isset(Yii::$app->params['base_url_technician']) ? Yii::$app->params['base_url_technician'] : null;
        $query      = CameraService::getAllQuery()->andWhere(['camera_service.status' => 1]);
        if ($keyword)
        {
            $query
                ->andFilterWhere(['like', 'camera_service.name', $keyword]);
        }
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
            $image      = null;
            $modelImage = $model->fkImage;
            if ($modelImage)
            {
                $image = $modelImage->uri_full;
            }

            $ret[] = [
                'service_id'   => $model->id,
                'service_name' => $model->name,
                'image'        => $image
            ];
        }

        return $ret = [
            'image_base' => $image_base,
            'items'      => $ret
        ];
    }
    public function actionServices($keyword = null,$ward_id=null,$camera_id=null)
    {
        $image_base = isset(Yii::$app->params['base_url_technician']) ? Yii::$app->params['base_url_technician'] : null;
        $ret =[];
        $modelUser = Yii::$app->user->identity;
        $userId    = $modelUser->id;

        $query = CameraServiceAssignment::getAllQuery()->andWhere(['camera_service_assignment.account_id_technician'=>$userId]);
        if ($keyword)
        {
            $query->leftJoin('camera_service','camera_service.id=camera_service_assignment.service_id')
            ->andFilterWhere(['like', 'camera_service.name', $keyword]);
        }
        if ($ward_id&&!$camera_id)
        {
            $query->leftJoin('camera','camera.id=camera_service_assignment.camera_id')
            ->andWhere(['camera.ward_id'=> $ward_id]);
        }
        if ($ward_id&&$camera_id)
        {
            $query->leftJoin('camera','camera.id=camera_service_assignment.camera_id')
            ->andWhere(['camera.ward_id'=> $ward_id])
            ->andWhere(['camera.id'=> $camera_id]);
        }
        if (!$ward_id&&$camera_id)
        {
            $query->leftJoin('camera','camera.id=camera_service_assignment.camera_id')
            ->andWhere(['camera.id'=> $camera_id]);
        }
            $dataProvider = new ActiveDataProvider([
                'query' => $query

            ]);
            $models = $dataProvider->getModels();
         foreach ($models as $model)
        {
            $modelCamera = $model->fkCamera;
            $modelService = $model->fkService;
            $image = null;
            $modelImage = $modelService->fkImage;
            if($modelImage)
                $image = $modelImage->uri_full;
               $ret[] = [
                'service_id'=>$model->fkService?$model->fkService->id:null,
                'service'=>$model->fkService?$model->fkService->name:null,
                'service_assignment_id' => $model->id,
                'service_image' => $image,
                'camera' =>[
                    'id'=>$modelCamera->id,
                    'name'=>$modelCamera->name,
                    'ward'=>$modelCamera->fkWard?$modelCamera->fkWard->name:null,
                    'lat'     => $modelCamera->lat,
                    'lng'     => $modelCamera->lng,
                    'lng'     => $modelCamera->lng,
                    'qr_code' => $modelCamera->serial_no

                ],
            ];
        }
        return $ret = [
            'image_base' => $image_base,
            'items'      => $ret
        ];

}
public function actionList(
        $page = 1,
        $per_page = 30,
        $keyword=null,
        $id=null,
        $host_name =null
    )
    {
        $modelUser = Yii::$app->user->identity;
        $userId    = $modelUser->id;
        $role = $modelUser->role;
        if($role=='camera-technician'){
        $query     = Camera::getAllQuery()
            ->andWhere(['camera.status' => 1])
            ->orderBy(['camera.id' => SORT_DESC])
            ->andWhere(['camera.account_id_technician' => $userId]);
        }
        elseif($role=='camera-monitoring-admin')
        {
            $query     = Camera::getAllQuery()
            ->andWhere(['camera.status' => 1])
            ->orderBy(['camera.id' => SORT_DESC])
            ->leftJoin('monitoring_group_camera','monitoring_group_camera.camera_id=camera.id')
            ->leftJoin('monitoring_group_user','monitoring_group_camera.monitoring_group_id=monitoring_group_user.monitoring_group_id')
            ->andWhere(['monitoring_group_user.account_id' => $userId]);
        }
        if($keyword)
        {
            $query->andFilterWhere(['like', 'camera.name', $keyword]);
        }
        if($id)
        {
            $query->andWhere(['camera.id'=>$id]);
        }
        if($host_name)
        {
            $query->andWhere(['camera.host_name'=>$host_name]);
        }
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
            $date1 = isset($model->fkHeartBeat->timestamp)?$model->fkHeartBeat->timestamp:date('Y-m-d H:i:s');
            $date2 = date('Y-m-d H:i:s');
            $hrs = isset($model->fkWard->fkLsgi->camera_fault_calculation_interval_hours)?$model->fkWard->fkLsgi->camera_fault_calculation_interval_hours:null;
            $hours = round((strtotime($date2) - strtotime($date1))/3600, 1);
            if($hours>=$hrs){
                $active = 0;
            }
            else
            {
                $active =1;
            }
             $image_url = $this->findModelImage($model->image_id);
                 if ($image_url)
            {
                $camera_image_url = Yii::$app->params['camera_image_base_url'] . $image_url->uri_full;}

            $ret[] = [
                'id'             => $model->id,
                'host_name'             => $model->host_name,
                'name'           => $model->name,
                'serial_no'           => $model->serial_no,
                'ward'           => $model->fkWard ? $model->fkWard->name : null,
                'lat'            => $model->lat,
                'lng'            => $model->lng,
                'camera_active'=> isset($model->fkHeartBeat->camera_active)?$model->fkHeartBeat->camera_active:null,
                'last_heart_beat_timestamp'=> $model->getHeartBeat($model->id),
                'camera_fault_calculation_interval_hours'=> isset($model->fkWard->fkLsgi->camera_fault_calculation_interval_hours)?$model->fkWard->fkLsgi->camera_fault_calculation_interval_hours:null,
                'is_active'=>$active,
                'location_name'=>$model->location_name,
                'image_url'   => isset($camera_image_url) ? $camera_image_url : "",
                'incident_count' => [
                    'new' => $model->getIncidentCount($model->id)
                ]
            ];
        }

        return $ret;
    }
}
