<?php

namespace backend\controllers;

use Yii;
use backend\models\Ward;
use backend\models\Memo;
use backend\models\Lsgi;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use backend\components\AccessRule;
use backend\components\AccessPermission;

/**
 * MemoPenaltiesController implements the CRUD actions for MemoPenalty model.
 */
class MemoPaymentReportsController extends Controller
{
    /**
     * {@inheritdoc}
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
                       'permissions' => ['memo-payment-reports-index'],
                   ],
               ],
               'denyCallback' => function($rule, $action) {
                   return $this->redirect('dashboard');
               }
           ],
       ];
    }
  //   public function actionIndex()
  //   {
  //     $from = null;
  //     $to = null;
  //     $memo = null;
  //      if (isset($_POST['from']))
  //     {
  //         $from = $_POST['from'];
  //             $from              = $from ? \Yii::$app->formatter->asDatetime($from, "php:Y-m-d H:i:s") : '';
  //
  //     }
  //     if (isset($_POST['to']))
  //     {
  //         $to = $_POST['to'];
  //             $to                = $to ? \Yii::$app->formatter->asDatetime($to, "php:Y-m-d 23:59:59") : '';
  //
  //     }
  //     // print_r($from);exit;
  //     $session = Yii::$app->session;
  //     $session->set('start', $from);
  //     $session->set('end', $to);
  //     $modelMemo = new Memo;
  //     $dataProvider = new ActiveDataProvider(
  //           [
  //               'query'      => $modelMemo->getAllQuery(),
  //               'pagination' => false,
  //               'sort'       => ['defaultOrder' => ['id' => SORT_DESC]]
  //           ]);
  //     // print_r($dataProvider);exit;
  //     return $this->render('index', [
  //         'dataProvider' => $dataProvider,
  //         'modelMemo' => $modelMemo,
  //         'from'         =>$from,
  //         'to'           =>$to
  //       ]);
  // }
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
      $model = new Memo;
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


    protected function findModel($id)
    {
        if (($model = Memo::findOne($id)) !== null) {
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
