<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use backend\models\ItemMrc;
use backend\models\ItemMrfRrf;
use backend\models\ItemMrcSurveyAgency;
use backend\components\AccessPermission;
use yii\filters\AccessControl;

/**
 * BuildingTypesController implements the CRUD actions for BuildingType model.
 */
class ItemMrcController extends Controller
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
                         'permissions' => ['item-mrc-index']
                     ],
                     [
                         'actions' => ['view'],
                         'allow'   => true,
                         'permissions' => ['item-mrc-view']
                     ],
                     [
                         'actions' => ['create'],
                         'allow'   => true,
                         'permissions' => ['item-mrc-create']
                     ],
                     [
                         'actions' => ['update'],
                         'allow'   => true,
                         'permissions' => ['item-mrc-update']
                     ],
                     [
                         'actions' => ['delete'],
                         'allow'   => true,
                         'permissions' => ['item-mrc-delete']
                     ],
                     [
                         'actions' => ['delete-item'],
                         'allow'   => true,
                         'permissions' => ['item-mrc-delete-item']
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
     * Lists all BuildingType models.
     * @return mixed
     */
    public function actionIndex($set = null)
    {
         $post = yii::$app->request->post();
      $get = yii::$app->request->get();
      $params  = array_merge($post,$get);
        $item=null;
        $mrc=null;
        $from=null;
        $to=null;
        $lsgi=null;
      $vars = [ 
    
    'item',
    'mrc',
    'from',
    'to' ,
    'lsgi'
    ];
    $newParams = [];
     if($set==null)
        {
            $session = Yii::$app->session;
            $session->destroy();
        }
        else{
    $session = Yii::$app->session;
    foreach($vars as $param) {
      ${$param} = isset($params[$param])?$params[$param]:null;
      $newParams[$param] = ${$param};
      if(${$param} !== null) {
      $session->set($param,${$param});
      }
    } 
    $item = Yii::$app->session->get('item');
    $mrc = Yii::$app->session->get('mrc');
    $from          = Yii::$app->session->get('from');
    $to            = Yii::$app->session->get('to');
    $lsgi            = Yii::$app->session->get('lsgi');
    
    $from              = $from ? \Yii::$app->formatter->asDatetime($from, "php:Y-m-d 00:00:00") : '';
    $to                = $to ? \Yii::$app->formatter->asDatetime($to, "php:Y-m-d 23:59:00") : '';
}
        $searchModel = new ItemMrc();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,$item,$mrc,$from,$to,$lsgi);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    /**
     * Creates a new BuildingType model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ItemMrc();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing BuildingType model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $lsgi = $model->lsgi_id;
        $unit = $model->green_action_unit_id;
        if ($model->load(Yii::$app->request->post())) {
            if(!$model->lsgi_id)
            {
                $model->lsgi_id = $lsgi;
            }
            if(!$model->green_action_unit_id)
            {
                $model->green_action_unit_id = $unit;
            }
            $model->save();
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing BuildingType model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the BuildingType model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return BuildingType the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ItemMrc::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
    public function actionDeleteItem($id)
    {
        $model = new ItemMrc;
        $model->deleteItem($id);
    }
   public function actionList($set = null)
    {
        $post = yii::$app->request->post();
      $get = yii::$app->request->get();
      $params  = array_merge($post,$get);
        $item=null;
        $mrf=null;
        $rrf=null;
        $from=null;
        $to=null;
        $lsgi=null;
      $vars = [ 
    
    'item',
    'mrf',
    'rrf',
    'from',
    'to',
    'lsgi' 
    ];
    $newParams = [];
     if($set==null)
        {
            $session = Yii::$app->session;
            $session->destroy();
        }
        else{
    $session = Yii::$app->session;
    foreach($vars as $param) {
      ${$param} = isset($params[$param])?$params[$param]:null;
      $newParams[$param] = ${$param};
      if(${$param} !== null) {
      $session->set($param,${$param});
      }
    } 
    $item = Yii::$app->session->get('item');
    $mrf = Yii::$app->session->get('mrf');
    $rrf = Yii::$app->session->get('rrf');
    $from          = Yii::$app->session->get('from');
    $to            = Yii::$app->session->get('to');
    
    $from              = $from ? \Yii::$app->formatter->asDatetime($from, "php:Y-m-d 00:00:00") : '';
    $to                = $to ? \Yii::$app->formatter->asDatetime($to, "php:Y-m-d 23:59:00") : '';
}
        $searchModel = new ItemMrfRrf();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,$item,$mrf,$rrf,$from,$to,$lsgi);

        return $this->render('list', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    public function actionAddMrfRrf()
    {
        $model = new ItemMrfRrf();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['list']);
        }

        return $this->render('add-mrf-rrf', [
            'model' => $model,
        ]);
    }
      public function actionUpdateMrfRrf($id)
    {
        $model = $this->findModelMrfRrf($id);
        $lsgi = $model->lsgi_id;
        if ($model->load(Yii::$app->request->post())) {
             if(!$model->lsgi_id)
            {
                $model->lsgi_id = $lsgi;
            }
            $model->save();
            return $this->redirect(['list']);
        }

        return $this->render('add-mrf-rrf', [
            'model' => $model,
        ]);
    }
    protected function findModelMrfRrf($id)
    {
        if (($model = ItemMrfRrf::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
    public function actionDeleteList($id)
    {
        $model = new ItemMrfRrf;
        $model->deleteItem($id);
    }
    public function actionSalesModule($set = null)
    {
        $post = yii::$app->request->post();
      $get = yii::$app->request->get();
      $params  = array_merge($post,$get);
        $item=null;
        $mrc=null;
        $from=null;
        $to=null;
      $vars = [ 
    
    'item',
    'mrc',
    'from',
    'to' 
    ];
    $newParams = [];
     if($set==null)
        {
            $session = Yii::$app->session;
            $session->destroy();
        }
        else{
    $session = Yii::$app->session;
    foreach($vars as $param) {
      ${$param} = isset($params[$param])?$params[$param]:null;
      $newParams[$param] = ${$param};
      if(${$param} !== null) {
      $session->set($param,${$param});
      }
    } 
    $item = Yii::$app->session->get('item');
    $mrc = Yii::$app->session->get('mrc');
    $from          = Yii::$app->session->get('from');
    $to            = Yii::$app->session->get('to');
    
    $from              = $from ? \Yii::$app->formatter->asDatetime($from, "php:Y-m-d 00:00:00") : '';
    $to                = $to ? \Yii::$app->formatter->asDatetime($to, "php:Y-m-d 23:59:00") : '';
}
        $searchModel = new ItemMrcSurveyAgency();
        $model = new ItemMrcSurveyAgency();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,$item,$mrc,$from,$to);

        return $this->render('item-list', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'model' => $model,
        ]);
    }
    public function actionAddMrcSurveyAgency()
    {
        $model = new ItemMrcSurveyAgency();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['item-list']);
        }

        return $this->render('add-mrc-survey-agency', [
            'model' => $model,
        ]);
    }
     public function actionUpdateMrcSurveyAgency($id)
    {
        $model = $this->findModelMrcSurveyAgency($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['item-list']);
        }

        return $this->render('add-mrc-survey-agency', [
            'model' => $model,
        ]);
    }
    protected function findModelMrcSurveyAgency($id)
    {
        if (($model = ItemMrcSurveyAgency::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
    public function actionDeleteItemList($id)
    {
        $model = new ItemMrcSurveyAgency;
        $model->deleteItem($id);
    }
    
}
