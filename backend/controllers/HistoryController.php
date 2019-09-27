<?php

namespace backend\controllers;

use Yii;
use backend\models\Account;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;
use backend\models\LoginHistory;
use yii\helpers\Json;
use common\models\LoginForm;
use yii\helpers\Url;
use backend\components\AccessPermission;
use yii\filters\AccessControl;
use backend\components\AccessRule;
/**
 * DistrictsController implements the CRUD actions for District model.
 */
class HistoryController extends Controller
{
     public function behaviors()
     {
         return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index'],
                'ruleConfig' => [
                        'class' => AccessPermission::className(),
                    ],
                'rules' => [
                    [
                        'actions' => ['index'],
                        'allow' => true,
                        'permissions' => ['history-index'],
                    ],
                ],
                'denyCallback' => function($rule, $action) {
                    return $this->goHome();
                }
            ],
        ];
     }

    /**
     * Lists all District models.
     * @return mixed
     */
   public function actionIndex($set=null)
    {
       $modelLoginHistory = new LoginHistory;
       $post   = yii::$app->request->post();
        $get    = yii::$app->request->get();
        $params = array_merge($post, $get);
        $keyword       = null;
        $from              = null;
        $to   =null;
        $type   =null;
        $vars   = [

            'name',
            'from',
            'to',
            'type'
        ];
        $newParams = [];
         if($set==null)
        {
            $session = Yii::$app->session;
            $session->destroy();
        }
        else{
        $session   = Yii::$app->session;
        foreach ($vars as $param)
        {
            ${
                $param}          = isset($params[$param]) ? $params[$param] : null;
            $newParams[$param] = ${
                $param};
            if (${
                $param} !== null)
            {
                $session->set($param, ${
                    $param});
            }
        }
        $keyword       = Yii::$app->session->get('name');
        $from          = Yii::$app->session->get('from');
        $to            = Yii::$app->session->get('to');
        $type            = Yii::$app->session->get('type');
        $from              = $from ? \Yii::$app->formatter->asDatetime($from, "php:Y-m-d 00:00:00") : '';
        $to                = $to ? \Yii::$app->formatter->asDatetime($to, "php:Y-m-d 23:59:59") : '';
      }

        $dataProvider = new ActiveDataProvider(
            [
                'query'      => LoginHistory::getAllQuery($keyword,$type,$from,$to),
                'pagination' => ['pageSize'=>50],
                // 'sort'       => ['defaultOrder' => ['id' => SORT_DESC]]
            ]);
        $modelUser  = Yii::$app->user->identity;
        $associations = Yii::$app->rbac->getAssociations($modelUser->id);
        return $this->render('index',['dataProvider' => $dataProvider,'modelLoginHistory'=>$modelLoginHistory,'associations'=>$associations]);
    }
    
}
