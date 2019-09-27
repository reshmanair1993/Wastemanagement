<?php
namespace frontend\controllers;

use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use frontend\models\ResidentialAssociation;
use frontend\models\ResidentialAssociationStakeholders;
use frontend\models\Customer;
use yii\data\ActiveDataProvider;
/**
 * Site controller
 */
class ResidentialAssociationController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex($set = null)
    {
        $ward = null;
        $keyword = null;
        $post   = yii::$app->request->post();
        $get    = yii::$app->request->get();
        $params = array_merge($post, $get);
        $vars   = [
            'ward',
            'name',
        ];
        $newParams = [];
        if($set==null)
            {
            $session = Yii::$app->session;
            $session->destroy();
        }

            else{
        $session   = Yii::$app->session;
        foreach ($vars as $param)
        {
            ${
                $param}          = isset($params[$param]) ? $params[$param] : null;
            $newParams[$param] = ${
                $param};
            if (${
                $param} !== null)
            {
                $session->set($param, ${
                    $param});
            }
        }
        $ward          = Yii::$app->session->get('ward');
        $keyword          = Yii::$app->session->get('name');
    }
    
    
        $modelResidentialAssociation = new ResidentialAssociation;
      $dataProvider = $modelResidentialAssociation->search(Yii::$app->request->queryParams,$ward,$keyword);
      $params = [
        'modelResidentialAssociation' => $modelResidentialAssociation,
        'dataProvider' => $dataProvider,
        'set' => $set,
      ];
      return $this->render('index',[
        'params' => $params,
      ]);
    }
    public function actionDetails($id,$set=null)
    {
        $keyword = null;
        $post   = yii::$app->request->post();
        $get    = yii::$app->request->get();
        $params = array_merge($post, $get);
        $vars   = [
            'keyword',
        ];
        $newParams = [];
        if($set==null)
            {
            $session = Yii::$app->session;
            $session->destroy();
        }

            else{
        $session   = Yii::$app->session;
        foreach ($vars as $param)
        {
            ${
                $param}          = isset($params[$param]) ? $params[$param] : null;
            $newParams[$param] = ${
                $param};
            if (${
                $param} !== null)
            {
                $session->set($param, ${
                    $param});
            }
        }
        $keyword          = Yii::$app->session->get('keyword');
    }
      $model        = $this->findModel($id);
        $modelResidentialAssociationStakeholders = new ResidentialAssociationStakeholders;
        $dataProvider = new ActiveDataProvider(
        [
           'query' => ResidentialAssociationStakeholders::getAllQuery()->andWhere(['residential_association_id' => $id,'status' => '1']),
           'pagination' => false,
           'sort'=> ['defaultOrder' => ['id' => SORT_DESC]]
        ]);
        $modelCustomer = new Customer;
        $customerDataProvider = new ActiveDataProvider(
        [
           'query' => Customer::find()->where(['status'=>1])
           ->andWhere(['residential_association_id' => $id,'status' => '1'])
           ->andFilterWhere(['like','lead_person_name', $keyword]),
           'pagination' => [
           'pageSize'=>10
           ],
           'sort'=> ['defaultOrder' => ['id' => SORT_DESC]]
        ]);
        $params       = [
            'model'        => $model,
            'modelResidentialAssociationStakeholders'   => $modelResidentialAssociationStakeholders,
            'dataProvider' => $dataProvider,
            'modelCustomer' => $modelCustomer,
            'customerDataProvider' => $customerDataProvider,
        ];

        return $this->render('residential-association-detail', [
            'params' => $params
        ]);
    }
    protected function findModel($id)
    {
        if (($model = ResidentialAssociation::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
    public function actionTest()
    {
        $ward = null;
        $keyword = null;
         $modelResidentialAssociation = new ResidentialAssociation;
      $dataProvider = $modelResidentialAssociation->search(Yii::$app->request->queryParams,$ward,$keyword);
      $params = [
        'modelResidentialAssociation' => $modelResidentialAssociation,
        'dataProvider' => $dataProvider,
        // 'set' => $set,
      ];
      return $this->render('test',[
        'params' => $params,
      ]);
    }
   
}
