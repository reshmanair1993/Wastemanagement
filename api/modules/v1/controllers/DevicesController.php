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
use yii\filters\AccessControl;
class DevicesController extends ActiveController
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
          'access' => [
            'class' => AccessControl::className(),
            'rules' => [
              [
                'actions' => ['index','id'],
                'allow' => true,
                'roles' => ['?'],
              ],
            ],
          ],
          // 'auth'  => [
          //     'class' => HttpBearerAuth::className()
          // ]
      ];
  }

    /**
     * @return mixed
     */
   
    public function actionId($serial_no = null)
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
                    $ret = [
                        'id'             => $model->id,
                        'name'           => $model->name,
                        'serial_no'           => $model->serial_no,
                        'ward'           => $model->fkWard ? $model->fkWard->name : null,
                        'lat'            => $model->lat,
                        'lng'            => $model->lng,
                        'incident_count' => [
                            'new' => $model->getIncidentCount($model->id)
                        ]
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


public function actionIndex(
        $page = 1,
        $per_page = 30,
        $keyword=null,
        $id=null
    )
    {
        // $modelUser = Yii::$app->user->identity;
        // $userId    = $modelUser->id;
        // $role = $modelUser->role;
        // if($role=='camera-technician'){
        // $query     = Camera::getAllQuery()
        //     ->andWhere(['camera.status' => 1])
        //     ->orderBy(['camera.id' => SORT_DESC])
        //     ->andWhere(['camera.account_id_technician' => $userId]);
        // }
        // elseif($role=='camera-monitoring-admin')
        // {
            $query     = Camera::getAllQuery()
            ->andWhere(['camera.status' => 1])
            ->orderBy(['camera.id' => SORT_DESC]);
        //     ->leftJoin('monitoring_group_camera','monitoring_group_camera.camera_id=camera.id')
        //     ->leftJoin('monitoring_group_user','monitoring_group_camera.monitoring_group_id=monitoring_group_user.monitoring_group_id')
        //     ->andWhere(['monitoring_group_user.account_id' => $userId]);
        // }
        if($keyword)
        {
            $query->andFilterWhere(['like', 'camera.name', $keyword]);
        }
        if($id)
        {
            $query->andWhere(['camera.id'=>$id]);
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
            $ret[] = [
                'id'             => $model->id,
                'name'           => $model->name,
                'serial_no'           => $model->serial_no,
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
