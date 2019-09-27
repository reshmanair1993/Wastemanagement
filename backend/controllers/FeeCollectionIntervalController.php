<?php

namespace backend\controllers;

use Yii;
use backend\models\FeeCollectionInterval;
use backend\models\FeeCollectionIntervalSearch;
use backend\models\FeeCollectionIntervalBuildingType;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use backend\components\AccessPermission;
use yii\filters\AccessControl;

/**
 * FeeCollectionIntervalController implements the CRUD actions for FeeCollectionInterval model.
 */
class FeeCollectionIntervalController extends Controller
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
                         'permissions' => ['fee-collection-interval-index']
                     ],
                     [
                         'actions' => ['view'],
                         'allow'   => true,
                         'permissions' => ['fee-collection-interval-view']
                     ],
                     [
                         'actions' => ['create'],
                         'allow'   => true,
                         'permissions' => ['fee-collection-interval-create']
                     ],
                     [
                         'actions' => ['update'],
                         'allow'   => true,
                         'permissions' => ['fee-collection-interval-update']
                     ],
                     [
                         'actions' => ['delete'],
                         'allow'   => true,
                         'permissions' => ['fee-collection-interval-delete']
                     ],
                     [
                         'actions' => ['delete-fee-collection-interval'],
                         'allow'   => true,
                         'permissions' => ['fee-collection-interval-delete-fee-collection-interval']
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
     * Lists all FeeCollectionInterval models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new FeeCollectionIntervalSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single FeeCollectionInterval model.
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
     * Creates a new FeeCollectionInterval model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new FeeCollectionInterval();

        if ($model->load(Yii::$app->request->post())) {
           $list = $model->building_type;
            $model->building_type = serialize($model->building_type);
            $model->save();
            if($list):
            foreach ($list as  $value) {
                $modelFeeCollectionIntervalBuildingType = new FeeCollectionIntervalBuildingType();
                $modelFeeCollectionIntervalBuildingType->building_type_id = $value;
                $modelFeeCollectionIntervalBuildingType->fee_collection_interval_id = $model->id;
                $modelFeeCollectionIntervalBuildingType->save(false);
            }
            endif;
            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing FeeCollectionInterval model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->building_type = unserialize($model->building_type);
        if ($model->load(Yii::$app->request->post())) {
            $list = $model->building_type;
            $model->building_type = serialize($model->building_type);
            $model->save();
            if($list):
            FeeCollectionIntervalBuildingType::deleteAll(['fee_collection_interval_id'=>$model->id]);
            foreach ($list as  $value) {
                $modelFeeCollectionIntervalBuildingType = new FeeCollectionIntervalBuildingType();
                $modelFeeCollectionIntervalBuildingType->building_type_id = $value;
                $modelFeeCollectionIntervalBuildingType->fee_collection_interval_id = $model->id;
                $modelFeeCollectionIntervalBuildingType->save(false);
            }
            endif;
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing FeeCollectionInterval model.
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
     * Finds the FeeCollectionInterval model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return FeeCollectionInterval the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = FeeCollectionInterval::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
    public function actionDeleteFeeCollectionInterval($id)
    {
        $model = new FeeCollectionInterval;
        $model->deleteFeeCollectionInterval($id);
    }
}
