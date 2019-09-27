<?php

namespace backend\controllers;

use Yii;
use backend\models\GreenActionUnit;
use backend\models\GreenActionUnitSearch;
use backend\models\GreenActionUnitWard;
use backend\models\GreenActionUnitWardSearch;
use backend\models\Lsgi;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use yii\filters\AccessControl;
use backend\models\Schedule;
use backend\models\Customer;
use backend\models\ScheduleWard;
use backend\models\ScheduleCustomer;
use yii\data\ActiveDataProvider;
use backend\models\ServicePackageService;
use backend\models\Service;
use backend\models\GreenActionUnitService;
use backend\components\AccessPermission;
/**
 * GreenActionUnitsController implements the CRUD actions for GreenActionUnit model.
 */
class GreenActionUnitsController extends Controller
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
                         'permissions' => ['green-action-units-index']
                     ],
                     [
                         'actions' => ['view'],
                         'allow'   => true,
                         'permissions' => ['green-action-units-view']
                     ],
                     [
                         'actions' => ['create'],
                         'allow'   => true,
                         'permissions' => ['green-action-units-create']
                     ],
                     [
                         'actions' => ['update'],
                         'allow'   => true,
                         'permissions' => ['green-action-units-update']
                     ],
                     [
                         'actions' => ['delete'],
                         'allow'   => true,
                         'permissions' => ['green-action-units-delete']
                     ],
                     [
                         'actions' => ['delete-green-action-unit'],
                         'allow'   => true,
                         'permissions' => ['green-action-units-delete-green-action-unit']
                     ],
                     [
                         'actions' => ['view-green-action-unit'],
                         'allow'   => true,
                         'permissions' => ['green-action-units-view-green-action-unit']
                     ],
                     [
                         'actions' => ['add-unit-ward'],
                         'allow'   => true,
                         'permissions' => ['green-action-units-add-unit-ward']
                     ],
                     [
                         'actions' => ['delete-ward'],
                         'allow'   => true,
                         'permissions' => ['green-action-units-delete-ward']
                     ],
                     [
                         'actions' => ['unit'],
                         'allow'   => true,
                         'permissions' => ['green-action-units-unit']
                     ],
                     [
                         'actions' => ['gt-ajax'],
                         'allow'   => true,
                         'permissions' => ['green-action-units-gt-ajax']
                     ],
                     [
                         'actions' => ['add-hks-schedule'],
                         'allow'   => true,
                         'permissions' => ['green-action-units-add-hks-schedule']
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
     * Lists all GreenActionUnit models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new GreenActionUnitSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single GreenActionUnit model.
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

    /**
     * Creates a new GreenActionUnit model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new GreenActionUnit();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing GreenActionUnit model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $lsgi = $model->lsgi_id;
        if ($model->load(Yii::$app->request->post()) ) {
            if(!$model->lsgi_id)
            {
                $model->lsgi_id = $lsgi;
            }
            $model->save();
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing GreenActionUnit model.
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
     * Finds the GreenActionUnit model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return GreenActionUnit the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = GreenActionUnit::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
    public function actionDeleteGreenActionUnit($id)
    {
        $model = new GreenActionUnit;
        $model->deleteGreenActionUnit($id);
    }
     public function actionViewGreenActionUnit($id)
    {
      $model = $this->findModel($id);
      $modelGreenActionUnitWard = new GreenActionUnitWard;
      $searchModel = new GreenActionUnitWardSearch();
      $dataProvider = $searchModel->search(Yii::$app->request->queryParams,$id);
       $modelSchedule = new Schedule;
        $scheduleDataProvider = new ActiveDataProvider(
        [
           'query' => $modelSchedule->getAllQuery()->andWhere(['green_action_unit_id' => $id,'status' => '1']),
           'pagination' => false,
           'sort'=> ['defaultOrder' => ['id' => SORT_DESC]]
        ]);
        $modelGreenActionUnitService = new GreenActionUnitService;;
        $serviceDataProvider = new ActiveDataProvider(
        [
           'query' => $modelGreenActionUnitService->getAllQuery()->andWhere(['green_action_unit_id' => $id,'status' => '1']),
           'pagination' => false,
           'sort'=> ['defaultOrder' => ['id' => SORT_DESC]]
        ]);
      $params = [
        'model' => $model,
        'modelGreenActionUnitWard' => $modelGreenActionUnitWard,
        'searchModel' => $searchModel,
        'dataProvider'=> $dataProvider,
         'modelSchedule'  =>$modelSchedule,
         'scheduleDataProvider'  =>$scheduleDataProvider,
         'modelGreenActionUnitService'  =>$modelGreenActionUnitService,
         'serviceDataProvider'  =>$serviceDataProvider,
      ];
      return $this->render('view', [
            'params' => $params,
        ]);
    }
    public function actionAddUnitWard($id)
    {
        $model = $this->findModel($id);
      $modelGreenActionUnitWard = new GreenActionUnitWard;
      $searchModel = new GreenActionUnitWardSearch();
      $post = Yii::$app->request->post();
      $dataProvider = $searchModel->search(Yii::$app->request->queryParams,$id);

        if ($modelGreenActionUnitWard->load(Yii::$app->request->post())) {
            $modelGreenActionUnitWard->green_action_unit_id = $id;
             if(isset($modelGreenActionUnitWard['service_id'])&&$modelGreenActionUnitWard['service_id']!=null){
              $jsonArray = json_encode($modelGreenActionUnitWard['service_id']);
              $modelGreenActionUnitWard->service_id = $jsonArray;
            }
            $modelGreenActionUnitWard->save();
        }
         $modelGreenActionUnitService = new GreenActionUnitService;;
        $serviceDataProvider = new ActiveDataProvider(
        [
           'query' => $modelGreenActionUnitService->getAllQuery()->andWhere(['green_action_unit_id' => $id,'status' => '1']),
           'pagination' => false,
           'sort'=> ['defaultOrder' => ['id' => SORT_DESC]]
        ]);

         $params = [
        'model' => $model,
        'modelGreenActionUnitWard' => $modelGreenActionUnitWard,
        'searchModel' => $searchModel,
        'dataProvider'=> $dataProvider,
        'modelGreenActionUnitService'=> $modelGreenActionUnitService,
        'serviceDataProvider'=> $serviceDataProvider,
      ];
      return $this->render('view', [
            'params' => $params,
        ]);
    }
    public function actionDeleteWard($id)
    {
        $model = new GreenActionUnitWard;
        $model->deleteUnit($id);
    }
     public function actionUnit() {
       $out = [];
        if (isset($_POST['depdrop_parents'])) {
        $parents = $_POST['depdrop_parents'];
        $lsgi = Lsgi::find()->where(['id'=>$parents[0]])->andWhere(['status'=>1])->one();
        $unit= GreenActionUnit::find()->where(['lsgi_id'=>$lsgi['id']])->andWhere(['status'=>1])->all();

        foreach ($unit as $id => $post) {
            $out[] = ['id' => $post['id'], 'name' => $post['name']];
 }
 echo Json::encode(['output' => $out, 'selected' => '']);
    }
  }
  public function actionGtAjax(){

        // $arr = [];
        // if(Yii::$app->request->isAjax):
        //     $data = Yii::$app->request->Post();
        //     $gt = $data['gt'];//echo $cat;exit;
        //     $ward = $data['ward'];//echo $cat;exit;
        //     $service = $data['service'];//echo $cat;exit;
        //     $arr = \yii\helpers\ArrayHelper::map(\backend\models\Customer::find()->where(['customer.status'=>1])->leftJoin('account','account.customer_id=customer.id')
        //   ->leftJoin('account_service','account.id=account_service.account_id')
        //   ->leftJoin('account_authority','account.id=account_authority.account_id_customer')
        //   ->andWhere(['customer.ward_id'=>$ward])
        //   ->andWhere(['account_service.service_id'=>$service])
        //   ->andWhere(['account_authority.account_id_gt'=>$gt])
        //   ->all(), 'id', 'lead_person_name');

        // endif;
        // yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        // return $arr;
     $arr = [];
        if(Yii::$app->request->isAjax):
            $data = Yii::$app->request->Post();
            $gt = $data['gt'];//echo $cat;exit;
            $ward = $data['ward'];//echo $cat;exit;
            $service = $data['service'];//echo $cat;exit;

           $modelServicePackageService = ServicePackageService::find()->where(['service_id_service'=>$service])->andWhere(['status'=>1])->one();
            if($modelServicePackageService)
            {
                $modelService = Service::find()->where(['id'=>$modelServicePackageService->service_id])->andWhere(['status'=>1])->one();
                if($modelService&&$modelService->is_package==1)
                {
                     $arr = \yii\helpers\ArrayHelper::map(\backend\models\Customer::find()->where(['customer.status'=>1])->leftJoin('account','account.customer_id=customer.id')
          ->leftJoin('account_service','account.id=account_service.account_id')
          ->leftJoin('service_package_service','account_service.service_id=service_package_service.service_id')
          ->leftJoin('account_authority','account.id=account_authority.account_id_customer')
          ->andWhere(['customer.ward_id'=>$ward])
          ->andWhere(['account_service.service_id'=>$modelService->id])
          ->andWhere(['account_authority.account_id_gt'=>$gt])
          ->all(), 'id', 'lead_person_name');

                }
            }else{

            $arr = \yii\helpers\ArrayHelper::map(\backend\models\Customer::find()->where(['customer.status'=>1])->leftJoin('account','account.customer_id=customer.id')
          ->leftJoin('account_service','account.id=account_service.account_id')
          ->leftJoin('account_authority','account.id=account_authority.account_id_customer')
          ->andWhere(['customer.ward_id'=>$ward])
          ->andWhere(['account_service.service_id'=>$service])
          ->andWhere(['account_authority.account_id_gt'=>$gt])
          ->all(), 'id', 'lead_person_name');

    }


        endif;
        yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $arr;

    }
    public function actionAddHksSchedule($id)
    {
     $model = $this->findModel($id);
       $modelSchedule = new Schedule;
        $ScheduleCustomer = new ScheduleCustomer();
        if ($modelSchedule->load(Yii::$app->request->post())) {
            $modelSchedule->green_action_unit_id = $id;
            $post = Yii::$app->request->post();
            $list = $post['Schedule']['customer_id']?$post['Schedule']['customer_id']:'';
            $modelUser = Yii::$app->user->identity;
            $userId = $modelUser->id;
            $modelSchedule->account_id_creator= $userId;
            $modelSchedule->date= $modelSchedule->date ? \Yii::$app->formatter->asDatetime($modelSchedule->date , "php:Y-m-d") : '';
            $modelSchedule->save(false);
            if($post['Schedule']['customer_id']):
            foreach ($list as  $value) {
                $ScheduleCustomer = new ScheduleCustomer();
                $ScheduleCustomer->account_id_customer = $value;
                $ScheduleCustomer->schedule_id = $modelSchedule->id;
                $ScheduleCustomer->save(false);
            }
            endif;
        }
        $modelGreenActionUnitWard = new GreenActionUnitWard;
      $searchModel = new GreenActionUnitWardSearch();
      $dataProvider = $searchModel->search(Yii::$app->request->queryParams,$id);
       $modelSchedule = new Schedule;
        $scheduleDataProvider = new ActiveDataProvider(
        [
           'query' => $modelSchedule->getAllQuery()->andWhere(['green_action_unit_id' => $id,'status' => '1']),
           'pagination' => false,
           'sort'=> ['defaultOrder' => ['id' => SORT_DESC]]
        ]);
      $params = [
        'model' => $model,
        'modelGreenActionUnitWard' => $modelGreenActionUnitWard,
        'searchModel' => $searchModel,
        'dataProvider'=> $dataProvider,
         'modelSchedule'  =>$modelSchedule,
         'scheduleDataProvider'  =>$scheduleDataProvider,
      ];
      return $this->render('view', [
            'params' => $params,
        ]);
         $params = [
            'model'  =>$model,
            'modelWard'  =>$modelWard,
            'modelLsgiServiceFee'  =>$modelLsgiServiceFee,
            'dataProvider'  =>$dataProvider,
            'lsgiServiceFeeDataProvider'  =>$lsgiServiceFeeDataProvider,
            'modelSchedule'  =>$modelSchedule,
            'scheduleDataProvider'  =>$scheduleDataProvider,
        ];
      return $this->render('view', [
            'params' => $params,
        ]);
    }
    public function actionAddUnitService($id)
    {
        $model = $this->findModel($id);
      $modelGreenActionUnitWard = new GreenActionUnitWard;
      $searchModel = new GreenActionUnitWardSearch();
      $dataProvider = $searchModel->search(Yii::$app->request->queryParams,$id);
        $modelGreenActionUnitService = new GreenActionUnitService;
        $serviceDataProvider = new ActiveDataProvider(
        [
           'query' => $modelGreenActionUnitService->getAllQuery()->andWhere(['green_action_unit_id' => $id,'status' => '1']),
           'pagination' => false,
           'sort'=> ['defaultOrder' => ['id' => SORT_DESC]]
        ]);
        if ($modelGreenActionUnitService->load(Yii::$app->request->post())) {
            $modelGreenActionUnitService->green_action_unit_id = $id;
            $modelGreenActionUnitService->save();
        }
         $modelGreenActionUnitService = new GreenActionUnitService;
         $params = [
        'model' => $model,
        'modelGreenActionUnitWard' => $modelGreenActionUnitWard,
        'searchModel' => $searchModel,
        'dataProvider'=> $dataProvider,
        'modelGreenActionUnitService'=> $modelGreenActionUnitService,
        'serviceDataProvider'=> $serviceDataProvider,
      ];
      return $this->render('view', [
            'params' => $params,
        ]);
    }
    public function actionDeleteService($id)
    {
        $model = new GreenActionUnitService;
        $model->deleteUnit($id);
    }

}
