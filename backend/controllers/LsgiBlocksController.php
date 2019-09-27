<?php

namespace backend\controllers;

use Yii;
use backend\models\LsgiBlock;
use backend\models\LsgiBlockSearch;
use backend\models\AssemblyConstituency;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use backend\components\AccessPermission;
use yii\filters\AccessControl;
/**
 * IsgiController implements the CRUD actions for Isgi model.
 */
class LsgiBlocksController extends Controller
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
                         'permissions' => ['lsgi-blocks-index']
                     ],
                     [
                         'actions' => ['view'],
                         'allow'   => true,
                         'permissions' => ['lsgi-blocks-view']
                     ],
                     [
                         'actions' => ['create'],
                         'allow'   => true,
                         'permissions' => ['lsgi-blocks-create']
                     ],
                     [
                         'actions' => ['update'],
                         'allow'   => true,
                         'permissions' => ['lsgi-blocks-update']
                     ],
                     [
                         'actions' => ['delete'],
                         'allow'   => true,
                         'permissions' => ['lsgi-blocks-delete']
                     ],
                     [
                         'actions' => ['delete-lsgi'],
                         'allow'   => true,
                         'permissions' => ['lsgi-blocks-delete-lsgi']
                     ],
                     [
                         'actions' => ['blocks'],
                         'allow'   => true,
                         'permissions' => ['lsgi-blocks-blocks']
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
     * Lists all Isgi models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new LsgiBlockSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Isgi model.
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
     * Creates a new Isgi model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new LsgiBlock();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Isgi model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $constituency = $model->assembly_constituency_id;
        if ($model->load(Yii::$app->request->post())) {
            if(!$model->assembly_constituency_id)
            {
                $model->assembly_constituency_id = $constituency;
            }
            $model->save();
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Isgi model.
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
     * Finds the Isgi model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Isgi the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = LsgiBlock::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
    public function actionDeleteLsgi($id)
    {
        $model = new LsgiBlock;
        $model->deleteLsgi($id);
    }
    public function actionBlocks() {
       $out = [];
        if (isset($_POST['depdrop_parents'])) {
        $parents = $_POST['depdrop_parents'];
        $assembly = AssemblyConstituency::find()->where(['id'=>$parents[0]])->andWhere(['status'=>1])->one();
        $block= LsgiBlock::find()->where(['assembly_constituency_id'=>$assembly['id']])->andWhere(['status'=>1])->all();

        foreach ($block as $id => $post) {
            $out[] = ['id' => $post['id'], 'name' => $post['name']];
 }
 echo Json::encode(['output' => $out, 'selected' => '']);
    }
  }
}
