<?php

namespace backend\controllers;

use Yii;
use backend\models\MonitoringGroup;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use backend\models\Memo;
use backend\models\Incident;
use backend\models\Ward;
use backend\models\MonitoringGroupUser;
use backend\models\MonitoringGroupCamera;
use yii\filters\AccessControl;
use backend\components\AccessRule;
use backend\components\AccessPermission;

/**
 * MonitoringGroupsController implements the CRUD actions for MonitoringGroup model.
 */
class OutstandingPaymentReportsController extends Controller
{
    /**
     * @inheritdoc
     */
     public function behaviors()
     {
       return [
          'access' => [
              'class' => AccessControl::className(),
              'only' => ['index','create','update','view'],
              'ruleConfig' => [
                      'class' => AccessPermission::className(),
                  ],
              'rules' => [
                  [
                      'actions' => ['index'],
                      'allow' => true,
                      'permissions' => ['outstanding-payment-reports-index'],
                  ],
              ],
              'denyCallback' => function($rule, $action) {
                  return $this->goHome();
              }
          ],
      ];
     }

    /**
     * Lists all MonitoringGroup models.
     * @return mixed
     */
    public function actionIndex()
    {
      $post   = yii::$app->request->post();
      $get    = yii::$app->request->get();
      $params = array_merge($post, $get);
      $vars   = [
        'ward',
        'lsgi'
        ];
        $newParams = [];
        foreach ($vars as $param)
        {
            ${
                $param}          = isset($params[$param]) ? $params[$param] : null;
            $newParams[$param] = ${
                $param};
        }

        $ward          = isset($post['ward'])?$post['ward']:'';
        $lsgi         = isset($post['lsgi'])?$post['lsgi']:'';
        $modelMemo = new Memo;
        $modelUser  = Yii::$app->user->identity;
        $associations = Yii::$app->rbac->getAssociations($modelUser->id);
        $dataProvider  = $modelMemo->searchOutstandingPayment(Yii::$app->request->queryParams,$lsgi,$ward);
        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'modelMemo' => $modelMemo,
            'associations' => $associations
        ]);

        // $model = new Incident;
        // $dataProvider = new ActiveDataProvider([
        //     'query' => Incident::find()->andWhere(['status' => 1])->orderBy(['id' => SORT_DESC]),
        // ]);
        // $dataProvider = new ActiveDataProvider(
        //     [
        //         'query'      => $modelMemo->getAllQuery()->where(['is_paid'=>1]),
        //         'pagination' => false,
        //         'sort'       => ['defaultOrder' => ['id' => SORT_DESC]]
        //     ]);
      //
      // return $this->render('index', [
      //     'dataProvider' => $dataProvider,
      //     'modelMemo' => $modelMemo,
      //     // 'from'         =>$from,
      //     // 'to'           =>$to
      //   ]);
  }


    /**
     * Displays a single MonitoringGroup model.
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


    protected function findModel($id)
    {
        if (($model = Incident::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
    // public function actionDeleteGroup($id)
    // {
    //     $model = new MonitoringGroup;
    //     $model->deleteGroup($id);
    // }

}
