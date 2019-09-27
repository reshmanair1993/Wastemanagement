<?php

namespace backend\controllers;

use Yii;
use backend\models\MonitoringGroup;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use backend\models\Account;
use backend\models\Incident;
use backend\models\Ward;
use backend\models\MonitoringGroupUser;
use backend\models\MonitoringGroupCamera;
use yii\filters\AccessControl;
use backend\components\AccessPermission;
use backend\components\AccessRule;

/**
 * MonitoringGroupsController implements the CRUD actions for MonitoringGroup model.
 */
class IncidentReportsController extends Controller
{
    /**
     * @inheritdoc
     */
     public function behaviors()
     {
         return [
             'access' => [
                 'class'        => AccessControl::className(),
                 'only'         => ['index', 'create', 'update', 'view', 'view-details'],
                 'ruleConfig' => [
                         'class' => AccessPermission::className(),
                     ],
                 'rules'        => [
                     [
                         'actions' => ['index'],
                         'allow'   => true,
                         'permissions' => ['incident-reports-index']
                     ],
                     [
                         'actions' => ['view'],
                         'allow'   => true,
                         'permissions' => ['incident-reports-view']
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
     * Lists all MonitoringGroup models.
     * @return mixed
     */
    public function actionIndex()
    {
        // $keyword = null;
      $from = null;
      $to = null;
      $ward = null;
       if (isset($_POST['from']))
      {
          $from = $_POST['from'];
              $from              = $from ? \Yii::$app->formatter->asDatetime($from, "php:Y-m-d H:i:s") : '';

      }
      if (isset($_POST['to']))
      {
          $to = $_POST['to'];
              $to                = $to ? \Yii::$app->formatter->asDatetime($to, "php:Y-m-d 23:59:59") : '';

      }
      if (isset($_POST['ward']))
      {
          $ward = $_POST['ward'];
      }
      $session = Yii::$app->session;
      $session->set('start', $from);
      $session->set('end', $to);
        $modelWard = new Ward;
        $model = new Incident;
        // $dataProvider = new ActiveDataProvider([
        //     'query' => Incident::find()->andWhere(['status' => 1])->orderBy(['id' => SORT_DESC]),
        // ]);
        $dataProvider = new ActiveDataProvider(
            [
                'query'      => $modelWard->getAllQuery($from, $to,$ward),
                'pagination' => false,
                'sort'       => ['defaultOrder' => ['id' => SORT_DESC]]
            ]);

      return $this->render('index', [
          'dataProvider' => $dataProvider,
          'modelWard' => $modelWard,
          'from'         =>$from,
          'to'           =>$to]);
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
