<?php

namespace backend\controllers;

use Yii;
use backend\models\BuildingType;
use backend\models\BuildingTypeSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use backend\models\BuildingTypeSubTypes;
use backend\components\AccessPermission;
use backend\models\BuildingTypeSubTypesSearch;
use yii\filters\AccessControl;

/**
 * BuildingTypesController implements the CRUD actions for BuildingType model.
 */
class BuildingTypesController extends Controller
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
                         'permissions' => ['building-types-index']
                     ],
                     [
                         'actions' => ['view'],
                         'allow'   => true,
                         'permissions' => ['building-types-view']
                     ],
                     [
                         'actions' => ['create'],
                         'allow'   => true,
                         'permissions' => ['building-types-create']
                     ],
                     [
                         'actions' => ['update'],
                         'allow'   => true,
                         'permissions' => ['building-types-update']
                     ],
                     [
                         'actions' => ['delete'],
                         'allow'   => true,
                         'permissions' => ['building-types-delete']
                     ],
                     [
                         'actions' => ['delete-building-type'],
                         'allow'   => true,
                         'permissions' => ['building-types-delete-building-type']
                     ],
                     [
                         'actions' => ['add-sub-type'],
                         'allow'   => true,
                         'permissions' => ['building-types-add-sub-type']
                     ],
                     [
                         'actions' => ['delete-sub-type'],
                         'allow'   => true,
                         'permissions' => ['building-types-delete-sub-type']
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
    public function actionIndex()
    {
        $searchModel = new BuildingTypeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single BuildingType model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
      $model = $this->findModel($id);
      $modelBuildigTypeSubType = new BuildingTypeSubTypes;
      $searchModel = new BuildingTypeSubTypesSearch();
      $dataProvider = $searchModel->search(Yii::$app->request->queryParams,$id);
      $params = [
        'model' => $model,
        'modelBuildigTypeSubType' => $modelBuildigTypeSubType,
        'searchModel' => $searchModel,
        'dataProvider'=> $dataProvider
      ];
      return $this->render('view', [
            'params' => $params,
        ]);
    }

    /**
     * Creates a new BuildingType model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new BuildingType();

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

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
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
        if (($model = BuildingType::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
    public function actionDeleteBuildingType($id)
    {
        $model = new BuildingType;
        $model->deleteType($id);
    }
    public function actionAddSubType($id)
    {
        $model = $this->findModel($id);
      $modelBuildigTypeSubType = new BuildingTypeSubTypes;
      $searchModel = new BuildingTypeSubTypesSearch();
      $dataProvider = $searchModel->search(Yii::$app->request->queryParams,$id);

        if ($modelBuildigTypeSubType->load(Yii::$app->request->post())) {
            $modelBuildigTypeSubType->building_type_id = $id;
             // print_r($modelBuildigTypeSubType);die();
            $modelBuildigTypeSubType->save(false);
        }

// $modelBuildigTypeSubType = new BuildingTypeSubTypes;
         $params = [
        'model' => $model,
        'modelBuildigTypeSubType' => $modelBuildigTypeSubType,
        'searchModel' => $searchModel,
        'dataProvider'=> $dataProvider
      ];
      return $this->render('view', [
            'params' => $params,
        ]);
    }
    public function actionDeleteSubType($id)
    {
        $model = new BuildingTypeSubTypes;
        $model->deleteType($id);
    }
}
