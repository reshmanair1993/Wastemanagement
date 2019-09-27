<?php
namespace api\modules\v1\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use api\modules\v1\models\CameraHeartbeat;
use api\modules\v1\models\Camera;
use yii\filters\auth\HttpBearerAuth;
use yii\rest\ActiveController;
use yii\filters\AccessControl;

class CameraHeartbeatsController extends ActiveController
{
  /**
   * @var string
   */
  public $modelClass = '\api\modules\v1\models\CameraHeartbeat';
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
                'actions' => ['add-heartbeat'],
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
  public function actionAddHeartbeat()
  {
    $modelCameraHeartbeat   = new CameraHeartbeat;
    $params        = Yii::$app->request->post();
    $ret           = [];
    if ($params)
    {
        $cameraId         = isset($params['camera_id']) ? $params['camera_id'] : null;
        $hostName         = isset($params['host_name']) ? $params['host_name'] : null;
        $timestamp = isset($params['timestamp']) ? $params['timestamp'] : date('Y-m-d H:i:s');

        if (isset($params['camera_id'])||isset($params['host_name']))
        {
          if(isset($params['camera_id'])){
            $modelCamera = $this->findModelCamera($cameraId);
          }elseif(isset($params['host_name']))
          {
            $modelCamera = $this->findModelCameraByHost($hostName);
          }
            if ($modelCamera)
            {
                $modelCameraHeartbeat->camera_id = $modelCamera->id;
            }
            else{
              $msg = ['Camera does not exist'];
              $error = ['camera_id'=>$msg];
              $ret = ['errors' =>$error];
              return $ret;
            }
        }
        else
        {
          $msg = ['Camera id or Host name cannot be blank'];
          $error = ['camera_id'=>$msg];
          $ret = ['errors' =>$error];
          return $ret;
        }
        if ($timestamp)
        {
          $modelCameraHeartbeat->timestamp =  $timestamp;
        }
        else
        {
          $msg = ['Timestamp cannot be blank'];
          $error = ['timestamp'=>$msg];
          $ret = ['errors' =>$error];
          return $ret;
        }
        if((isset($params['camera_id'])||isset($params['host_name'])) && $timestamp&&$modelCamera){
          $modelHeartbeat = CameraHeartbeat::find()->where(['camera_id' => $modelCamera->id,'status'=> 1])->orderBy(['id'=> SORT_DESC])->one();
          if($modelHeartbeat){
          $modelCameraHeartbeat->previous_entry_created_at = $modelHeartbeat->created_at;}
          $modelCameraHeartbeat->processed = 0;
          $modelCameraHeartbeat->camera_active = isset($params['camera_active'])?$params['camera_active']:null;
          $modelCameraHeartbeat->save(false);
          // if()
          $ret = [
            'camera_heartbeat_id' => $modelCameraHeartbeat->id,
        ];
        return $ret;
        }
      }
      else {
        $msg = ['Camera id and Timestamp cannot be blank'];
        $error = ['timestamp'=>$msg];
        $ret = ['errors' =>$error];
        return $ret;
      }
    }
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
}
