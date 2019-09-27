<?php

namespace backend\controllers;

use Yii;
use backend\models\AssemblyConstituency;
use backend\models\AssemblyConstituencySearch;
use backend\models\District;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use backend\components\AccessPermission;
use yii\helpers\Json;
use yii\filters\AccessControl;
/**
 * AssemblyContituencyController implements the CRUD actions for AssemblyContituency model.
 */
class AssemblyConstituencyController extends Controller
{
    /**
     * @inheritdoc
     */
   // public function behaviors()
   //  {
   //      return [
   //         'access' => [
   //             'class' => AccessControl::className(),
   //             'only' => ['index','create','update','view'],
   //             'rules' => [
   //                 [
   //                     // 'actions' => ['index','create','update','view'],
   //                     'allow' => true,
   //                     'roles' => ['@'],
   //                     'permissions' => [
   //                       'assembly-constituency-create','assembly-constituency-update',
   //                       'assembly-constituency-index','assembly-constituency-delete-assembly-constituency'
   //                   ]
   //                 ],
   //             ],
   //             'denyCallback' => function($rule, $action) {
   //                 return $this->goHome();
   //             }
   //         ],
   //     ];
   //  }

   public function behaviors()
   {
       return [
           'access' => [
               'class'        => AccessControl::className(),
               'only'         => ['index','create','update','view'],
               'ruleConfig' => [
                       'class' => AccessPermission::className(),
                   ],
               'rules'        => [
                   [
                       'actions' => ['index'],
                       'allow'   => true,
                       'permissions' => ['assembly-constituency-index']
                   ],
                   [
                       'actions' => ['create'],
                       'allow'   => true,
                       'permissions' => ['assembly-constituency-create']
                   ],
                   [
                       'actions' => ['update'],
                       'allow'   => true,
                       'permissions' => ['assembly-constituency-update']
                   ],
                   [
                       'actions' => ['delete-assembly-constituency'],
                       'allow'   => true,
                       'permissions' => ['assembly-constituency-delete-assembly-constituency']
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
     * Lists all AssemblyContituency models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AssemblyConstituencySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single AssemblyContituency model.
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
     * Creates a new AssemblyContituency model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new AssemblyConstituency();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing AssemblyContituency model.
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
     * Deletes an existing AssemblyContituency model.
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
     * Finds the AssemblyContituency model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return AssemblyContituency the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = AssemblyConstituency::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
   public function actionDeleteAssemblyConstituency($id)
    {
        $model =  new AssemblyConstituency();
      $model->deleteAssemblyConstituency($id);
    
    }
public function actionConstituency() {
       $out = [];
        if (isset($_POST['depdrop_parents'])) {
        $parents = $_POST['depdrop_parents'];
        $district = District::find()->where(['id'=>$parents[0]])->andWhere(['status'=>1])->one();
        $constituency= AssemblyConstituency::find()->where(['district_id'=>$district['id']])->andWhere(['status'=>1])->all();

        foreach ($constituency as $id => $post) {
            $out[] = ['id' => $post['id'], 'name' => $post['name']];
 }
 echo Json::encode(['output' => $out, 'selected' => '']);
    }
  }

}
