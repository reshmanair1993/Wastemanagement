<?php
namespace backend\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use backend\models\ResidentialAssociation;
use backend\models\AssociationType;
use backend\models\Ward;
use backend\models\District;
use backend\models\Image;
use yii\web\UploadedFile;
use backend\models\ResidentialAssociationStakeholders;
use yii\helpers\ArrayHelper;
use backend\components\AccessPermission;
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
           'access' => [
               'class' => AccessControl::className(),
              'only' => ['index','create','delete-association','view'],
               'ruleConfig' => [
                       'class' => AccessPermission::className(),
                   ],
               'rules' => [
                   [
                       'actions' => ['index'],
                       'allow' => true,
                       'permissions' => ['residential-association-index'],
                   ],
                   [
                       'actions' => ['create'],
                       'allow' => true,
                       'permissions' => ['residential-association-create'],
                   ],
                   [
                       'actions' => ['update'],
                       'allow' => true,
                       'permissions' => ['residential-association-update'],
                   ],
                   [
                       'actions' => ['delete'],
                       'allow' => true,
                       'permissions' => ['residential-association-delete'],
                   ],
                   [
                       'actions' => ['view'],
                       'allow' => true,
                       'permissions' => ['residential-association-view'],
                   ],
                   [
                       'actions' => ['change-association'],
                       'allow' => true,
                       'permissions' => ['residential-association-change-association'],
                   ],
                   [
                       'actions' => ['delete-association'],
                       'allow' => true,
                       'permissions' => ['residential-association-delete-association'],
                   ],
               ],
               'denyCallback' => function($rule, $action) {
                   return $this->goHome();
               }
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
     * @return string
     */
    public function actionIndex($set=null)
    {
      $post   = yii::$app->request->post();
        $get    = yii::$app->request->get();
        $params = array_merge($post, $get);
        $ward = null;
        $vars   = [
            'ward'
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
      }
      $modelResidentialAssociation = new ResidentialAssociation;
      $dataProvider = $modelResidentialAssociation->search(Yii::$app->request->queryParams,$ward);
      $params = [
        'modelResidentialAssociation' => $modelResidentialAssociation,
        'dataProvider' => $dataProvider,
      ];
      return $this->render('index',[
        'params' => $params,
      ]);
    }

    public function actionCreate()
    {
      $saved = false;
      $modelResidentialAssociation = new ResidentialAssociation;
      $modelAssociationType = new AssociationType;
      $modelDistrict = District::find()->where(['status'=>1])->all();
      $districtList = ArrayHelper::map($modelDistrict,'id','name');
      $associationTypes = $modelAssociationType->getAllQuery()->all();
      $associationTypeList = ArrayHelper::map($associationTypes,'id','name');
      $params = Yii::$app->request->post();
      $paramsOk = $params && $modelResidentialAssociation->load($params);
      if($paramsOk){
        $modelResidentialAssociationOk = $modelResidentialAssociation->validate();
        if($modelResidentialAssociationOk){
          $modelResidentialAssociation->save(false);
          // $saved = true;
          // $modelResidentialAssociation = new ResidentialAssociation;
          return $this->redirect('index');
        }
      }
      $modelUser  = Yii::$app->user->identity;
       $associations = Yii::$app->rbac->getAssociations($modelUser->id);
      $params = [
        'modelResidentialAssociation' => $modelResidentialAssociation,
        'associationTypeList' => $associationTypeList,
        'districtList' => $districtList,
        'associations' => $associations,
      ];
      return $this->render('add-residential-association',[
        'params' => $params,
      ]);
    }
    public function actionUpdate($id){
      $modelResidentialAssociation = $this->findModelResidentialAssociation($id);
      $modelAssociationType = new AssociationType;
      $ward = $modelResidentialAssociation->ward_id;
      $modelDistrict = District::find()->where(['status'=>1])->all();
      $districtList = ArrayHelper::map($modelDistrict,'id','name');
      $associationTypes = $modelAssociationType->getAllQuery()->all();
      $associationTypeList = ArrayHelper::map($associationTypes,'id','name');
      $params = Yii::$app->request->post();
      $paramsOk = $params && $modelResidentialAssociation->load($params);
      if($paramsOk){
        $modelResidentialAssociationOk = $modelResidentialAssociation->validate();
        if($modelResidentialAssociationOk){
          if(!$modelResidentialAssociation->ward_id)
            {
                $modelResidentialAssociation->ward_id = $ward;
            }
          $modelResidentialAssociation->update(false);
          // $saved = true;
          // $modelResidentialAssociation = new ResidentialAssociation;
          return $this->redirect('index');
        }
      }
      $params = [
        'modelResidentialAssociation' => $modelResidentialAssociation,
        'associationTypeList' => $associationTypeList,
        'districtList' => $districtList,
      ];
      return $this->render('update-residential-association',[
        'params' => $params,
      ]);
    }
    public function findModelResidentialAssociation($id){
      $modelResidentialAssociation = ResidentialAssociation::find()->where(['status'=>1,'id'=>$id])->one();
      return $modelResidentialAssociation;
    }
     protected function findModel($id)
    {
        if (($model = ResidentialAssociationStakeholders::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
    public function actionDeleteAssociation($id)
    {
      $modelResidentialAssociation = new ResidentialAssociation;
      $modelResidentialAssociation->deleteResidentialAssociation($id);
    }
    public function actionDeleteStakeholder($id)
    {
      $modelResidentialAssociation = new ResidentialAssociationStakeholders;
      $modelResidentialAssociation->deleteResidentialAssociationStakeholder($id);
    }
     public function actionChangeAssociation()
    {
      $modelResidentialAssociation = new ResidentialAssociation;
      $params = Yii::$app->request->post();
       // print_r($params);die();
      if($params)
      {
         
        $qry = "UPDATE customer set residential_association_id=:to where association_name=:from and customer.status=1";
        
         $command =  Yii::$app->db->createCommand($qry);
         $from = $params['from'];
         $to = $params['to'];
         $command->bindParam(':from',$from);
         $command->bindParam(':to',$to);
         $command->execute();
      }
      return $this->render('change-association',[
        'modelResidentialAssociation' => $modelResidentialAssociation,
      ]);
    }
    public function actionGetAssociation() {
       $out = [];
        if (isset($_POST['depdrop_parents'])) {
        $parents = $_POST['depdrop_parents'];
        $wards = Ward::find()->where(['id'=>$parents[0]])->andWhere(['status'=>1])->one();
        $residentialAssociation= ResidentialAssociation::find()
        ->where(['residential_association.ward_id'=>$wards['id']])->all();

        foreach ($ward as $id => $post) {
            $out[] = ['id' => $post['id'], 'name' => $post['name']];
 }
 echo Json::encode(['output' => $out, 'selected' => '']);
    }
  }
  public function actionView($id)
    {
        $modelResidentialAssociation = $this->findModelResidentialAssociation($id);
        $modelAssociationType = new AssociationType;
      $ward = $modelResidentialAssociation->ward_id;
      $modelDistrict = District::find()->where(['status'=>1])->all();
      $districtList = ArrayHelper::map($modelDistrict,'id','name');
      $associationTypes = $modelAssociationType->getAllQuery()->all();
      $associationTypeList = ArrayHelper::map($associationTypes,'id','name');
        $modelResidentialAssociationStakeholders = new ResidentialAssociationStakeholders;
        $dataProvider = new ActiveDataProvider(
        [
           'query' => ResidentialAssociationStakeholders::getAllQuery()->andWhere(['residential_association_id' => $id,'status' => '1']),
           'pagination' => false,
           'sort'=> ['defaultOrder' => ['id' => SORT_DESC]]
        ]);
        $modelUser  = Yii::$app->user->identity;
        $associations = Yii::$app->rbac->getAssociations($modelUser->id);
        $params = [
            'modelResidentialAssociation' => $modelResidentialAssociation,
        'associationTypeList' => $associationTypeList,
        'districtList' => $districtList,
        'modelResidentialAssociationStakeholders' => $modelResidentialAssociationStakeholders,
        'dataProvider' => $dataProvider,
        'associations' => $associations,
        ];

        return $this->render('view', [
            'params' => $params,
        ]);
    }
    public function actionAddStakeholder($id)
    {
      $modelResidentialAssociation = $this->findModelResidentialAssociation($id);
        $modelAssociationType = new AssociationType;
      $ward = $modelResidentialAssociation->ward_id;
      $modelDistrict = District::find()->where(['status'=>1])->all();
      $districtList = ArrayHelper::map($modelDistrict,'id','name');
      $associationTypes = $modelAssociationType->getAllQuery()->all();
      $associationTypeList = ArrayHelper::map($associationTypes,'id','name');
        $modelResidentialAssociationStakeholders = new ResidentialAssociationStakeholders;
        $dataProvider = new ActiveDataProvider(
        [
           'query' => ResidentialAssociationStakeholders::getAllQuery()->andWhere(['residential_association_id' => $id,'status' => '1']),
           'pagination' => false,
           'sort'=> ['defaultOrder' => ['id' => SORT_DESC]]
        ]);
        $modelImage = new Image;
         if ($modelResidentialAssociationStakeholders->load(Yii::$app->request->post())&& $modelImage->load(Yii::$app->request->post())) {
            $modelResidentialAssociationStakeholders->residential_association_id = $id;
             $modelImage->uploaded_files = UploadedFile::getInstance($modelImage, 'uploaded_files');
            $imageId                    = $modelImage->uploadAndSave();
            if ($imageId)
            {
                $modelResidentialAssociationStakeholders->image_id = $imageId;
            }
            $modelResidentialAssociationStakeholders->save();
        }
        $modelResidentialAssociationStakeholders = new ResidentialAssociationStakeholders;
        $modelUser  = Yii::$app->user->identity;
        $associations = Yii::$app->rbac->getAssociations($modelUser->id);
        $params = [
            'modelResidentialAssociation' => $modelResidentialAssociation,
        'associationTypeList' => $associationTypeList,
        'districtList' => $districtList,
        'modelResidentialAssociationStakeholders' => $modelResidentialAssociationStakeholders,
        'dataProvider' => $dataProvider,
        'associations' => $associations,
        ];

        return $this->render('view', [
            'params' => $params,
        ]);
    }
    public function actionUpdateStakeholder($id)
    {
      $modelResidentialAssociationStakeholders = $this->findModel($id);
       $modelResidentialAssociation = $this->findModelResidentialAssociation($modelResidentialAssociationStakeholders->residential_association_id);
        $modelAssociationType = new AssociationType;
      $ward = $modelResidentialAssociation->ward_id;
      $modelDistrict = District::find()->where(['status'=>1])->all();
      $districtList = ArrayHelper::map($modelDistrict,'id','name');
      $associationTypes = $modelAssociationType->getAllQuery()->all();
      $associationTypeList = ArrayHelper::map($associationTypes,'id','name');
        
        $modelImage = new Image;

        if ($modelResidentialAssociationStakeholders->load(Yii::$app->request->post())&& $modelImage->load(Yii::$app->request->post())) {
            $modelImage->uploaded_files = UploadedFile::getInstance($modelImage, 'uploaded_files');
            $imageId                    = $modelImage->uploadAndSave();
            if ($imageId)
            {
                $modelResidentialAssociationStakeholders->image_id = $imageId;
            }
            $modelResidentialAssociationStakeholders->save();
        }
        $modelResidentialAssociationStakeholders = new ResidentialAssociationStakeholders;
        $dataProvider = new ActiveDataProvider(
        [
           'query' => ResidentialAssociationStakeholders::getAllQuery()->andWhere(['residential_association_id' => $id,'status' => '1']),
           'pagination' => false,
           'sort'=> ['defaultOrder' => ['id' => SORT_DESC]]
        ]);
               $modelResidentialAssociationStakeholders = new ResidentialAssociationStakeholders;
        $modelUser  = Yii::$app->user->identity;
        $associations = Yii::$app->rbac->getAssociations($modelUser->id);
        $params = [
            'modelResidentialAssociation' => $modelResidentialAssociation,
        'associationTypeList' => $associationTypeList,
        'districtList' => $districtList,
        'modelResidentialAssociationStakeholders' => $modelResidentialAssociationStakeholders,
        'dataProvider' => $dataProvider,
        'associations' => $associations,
        ];
      return $this->render('view', [
            'params' => $params,
        ]);
    }
}
