<?php

namespace backend\controllers;

use Yii;
use backend\models\MemoPenalty;
use backend\models\MemoType;
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
class MemoPenaltiesController extends Controller
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
                       'permissions' => ['memo-penalties-index'],
                   ],
                   [
                       'actions' => ['create'],
                       'allow' => true,
                       'permissions' => ['memo-penalties-create'],
                   ],
                   [
                       'actions' => ['update'],
                       'allow' => true,
                       'permissions' => ['memo-penalties-update'],
                   ],
                   [
                       'actions' => ['delete'],
                       'allow' => true,
                       'permissions' => ['memo-penalties-delete'],
                   ],
                   [
                       'actions' => ['view'],
                       'allow' => true,
                       'permissions' => ['memo-penalties-view'],
                   ],
                   [
                       'actions' => ['delete-memo-penalty'],
                       'allow' => true,
                       'permissions' => ['memo-penalties-delete-memo-penalty'],
                   ],
               ],
               'denyCallback' => function($rule, $action) {
                   return $this->redirect('dashboard');
               }
           ],
       ];
    }

    /**
     * Lists all MemoPenalty models.
     * @return mixed
     */
    public function actionIndex()
    {
        $modelUser = Yii::$app->user->identity;
        $userRole  = $modelUser->role;

        $showSuccess = isset($_SESSION['showSuccess']) ? $_SESSION['showSuccess'] : null;
        if(isset($_SESSION['showSuccess']))
          unset($_SESSION['showSuccess']);
        $updateSuccess = isset($_SESSION['updateSuccess']) ? $_SESSION['updateSuccess'] : null;
        if(isset($_SESSION['updateSuccess']))
          unset($_SESSION['updateSuccess']);
        if($userRole == 'admin-lsgi'){
          $dataProvider = new ActiveDataProvider([
              'query' => MemoPenalty::getAllQuery()->where(['lsgi_id' => $modelUser->lsgi_id,'status'=>1]),
          ]);
        }
        else {
          $dataProvider = new ActiveDataProvider([
              'query' => MemoPenalty::getAllQuery(),
          ]);
        }


        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'showSuccess'=>$showSuccess,
            'updateSuccess'=>$updateSuccess,
        ]);
    }

    /**
     * Displays a single MemoPenalty model.
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
     * Creates a new MemoPenalty model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $modelUser = Yii::$app->user->identity;
        $userRole  = $modelUser->role;

        $model = new MemoPenalty();
        $modelLsgi = Lsgi::find()->where(['status' => 1])->all();
        $modelMemoType = MemoType::find()->where(['status' => 1])->all();

        $params = Yii::$app->request->post();
        $paramsOk = $params && $model->load($params);
        if($paramsOk){
          if ($userRole == 'admin-lsgi'){
            $model->lsgi_id = $modelUser->lsgi_id;
          }
          $model->save();
          $session = Yii::$app->session;
          $session->set('showSuccess', '1');
          return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
            'modelMemoType' => $modelMemoType,
            'modelLsgi' => $modelLsgi,

        ]);
    }

    /**
     * Updates an existing MemoPenalty model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $modelUser = Yii::$app->user->identity;
        $userRole  = $modelUser->role;

        $model = $this->findModel($id);
        $modelLsgi = Lsgi::find()->where(['status' => 1])->all();
        $modelMemoType = MemoType::find()->where(['status' => 1])->all();

        $params = Yii::$app->request->post();
        $paramsOk = $params && $model->load($params);
        if($paramsOk){
          if ($userRole == 'admin-lsgi'){
            $model->lsgi_id = $modelUser->lsgi_id;
          }
          $model->save();
          $session = Yii::$app->session;
          $session->set('updateSuccess', '1');
          return $this->redirect(['index']);
        }

        return $this->render('update', [
          'model' => $model,
          'modelMemoType' => $modelMemoType,
          'modelLsgi' => $modelLsgi,

        ]);
    }

    /**
     * Deletes an existing MemoPenalty model.
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
     * Finds the MemoPenalty model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return MemoPenalty the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = MemoPenalty::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    public function actionGetPenalty() {
      $out = [];
       if (isset($_POST['depdrop_parents'])) {
       $parents = $_POST['depdrop_parents'];
       $memoType = MemoType::find()->where(['id'=>$parents[0]])->andWhere(['status'=>1])->one();
       $memoPenalty= MemoPenalty::find()->where(['memo_type_id'=>$memoType['id']])->andWhere(['status'=>1])->all();

       foreach ($ward as $id => $post) {
           $out[] = ['id' => $post['id'], 'name' => $post['name']];
}
echo Json::encode(['output' => $out, 'selected' => '']);
   }
 }
 public function actionDeleteMemoPenalty($id)
 {
     $model = new MemoPenalty;
     $model->deleteMemoPenalty($id);
 }
}
