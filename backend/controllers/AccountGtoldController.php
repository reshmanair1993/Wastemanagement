<?php

namespace backend\controllers;

use Yii;
use backend\models\AccountGt;
use backend\models\AccountAuthority;
use backend\models\AccountGtSearch;
use backend\models\GreenActionUnitWard;
use backend\models\GreenActionUnit;
use backend\models\ResidenceCategory;
use backend\models\BuildingType;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ArrayDataProvider;
use yii\filters\AccessControl;
ini_set("memory_limit","500M");
/**
 * AccountGtController implements the CRUD actions for AccountGt model.
 */
class AccountGtController extends Controller
{
    /**
     * {@inheritdoc}
     */
     public function behaviors()
    {
        return [
            'access' => [
                'class'        => AccessControl::className(),
                'only'         => ['index', 'create', 'update', 'view', 'view-details'],
                'rules'        => [
                    [
                        'actions' => ['index', 'create', 'update', 'view', 'view-details'],
                        'allow'   => true,
                        'roles'   => ['@']
                    ]
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
     * Lists all AccountGt models.
     * @return mixed
     */
    public function actionIndex()
    {
        $hks         = null;
        $unit         = null;
        $supervisor         = null;
        $service         = null;
        $post         = Yii::$app->request->post();
        if (isset($post['unit']))
        {
            $unit = $post['unit'];
        }
        if (isset($post['service']))
        {
            $service = $post['service'];
        }
         if (isset($post['supervisor']))
        {
            $supervisor = $post['supervisor'];
        }
          if (isset($post['ward']))
        {
            $ward = $post['ward'];
        }
        if (isset($post['association']))
        {
            $association = $post['association'];
        }
      $modelUser  = Yii::$app->user->identity;
       // print_r($modelUser);die();
      if($modelUser->role=='admin-hks'||$modelUser->role=='supervisor'){

        $hks = $modelUser->green_action_unit_id;
      }if($modelUser->role=='admin-lsgi'||$modelUser->role=='super-admin')
      {
         $hks = $unit;
      }
       if($modelUser->role=='supervisor'){
        $supervisor = $modelUser->id;
      }
       if(!isset($post['ward'])){
      $wards = [];
      $modelWards = GreenActionUnitWard::find()
      ->leftJoin('account','account.green_action_unit_id=green_action_unit_ward.green_action_unit_id')
      ->where(['green_action_unit_ward.status'=>1])
      ->andWhere(['account.status'=>1])
      ->andWhere(['account.green_action_unit_id'=>$hks]);

      if($modelUser->role=='supervisor'){
        $supervisor = $modelUser->id;
       $modelWards = $modelWards->leftJoin('account_authority','account_authority.account_id_supervisor=account.id')
       ->andWhere(['account_authority.account_id_supervisor'=>$modelUser->id]);
      }
      $modelWards = $modelWards->all();
      foreach ($modelWards as $key => $value) {
            $wards[] = $value->ward_id;
      }
      $wards = array_unique($wards);
       $wardId = '';
foreach ($wards as $parent) {
    $wardId .= $parent . ',';
}

$wardId = rtrim($wardId, ',');
}
else
{
 
  $wardId = $ward;
}
      $buildingType = [];
      $modelBuildingType = BuildingType::find()
      ->leftJoin('residence_category','residence_category.id=building_type.residence_category_id')
      ->leftJoin('green_action_unit','green_action_unit.residence_category_id=residence_category.id')
      ->where(['green_action_unit.status'=>1])
      ->andWhere(['residence_category.status'=>1])
      ->andWhere(['building_type.status'=>1])
      ->andWhere(['green_action_unit.id'=>$hks])
      ->all();
      foreach ($modelBuildingType as $key => $value) {
            $buildingType[] = $value->id;
      }
      $buildingType = array_unique($buildingType);

     
$BuildingIds = '';
foreach ($buildingType as $building) {
    $BuildingIds .= $building . ',';
}

$BuildingIds = rtrim($BuildingIds, ',');
if(isset($post['association'])&&$post['association']!=null){
    $qry = "SELECT customer.lead_person_name,customer.id as customer_id,account.id as account_id,customer.ward_id as ward_id,customer.building_type_id as building_type_id,account_authority.account_id_customer as account_id_customer,building_type.name as building_type_name,customer.building_number as building_number,customer.address as address, customer.association_name as association_name, customer.association_number as association_number,customer.residential_association_id as residential_association_id FROM customer LEFT JOIN account ON account.customer_id = customer.id LEFT JOIN building_type ON building_type.id = customer.building_type_id LEFT JOIN account_authority ON account.id = account_authority.account_id_customer  LEFT JOIN account_service on account_service.account_id= account_authority.account_id_customer
    WHERE ward_id IN (:wards) and building_type_id IN (:buildingType) and account_authority.account_id_supervisor=:supervisor and customer.residential_association_id=:association and account_authority.status =1 and customer.status=1 and account.id

     -- and account_service.service_id=:service and account_service.status=1

    -- NOT IN(SELECT account_id_customer FROM account_authority WHERE account_authority.status = 1 and account_authority.account_id_gt is not null ) ORDER BY customer.created_at DESC
    ";
if($hks){
    $modelHks = GreenActionUnit::find()->where(['id'=>$hks])->andWhere(['status'=>1])->one();
    if($hks&&$modelHks&&isset($modelHks->residence_category_id)){
      $modelResidenceCategory = ResidenceCategory::find()->where(['id'=>$modelHks->residence_category_id])->andWhere(['status'=>1])->one();
    if($modelResidenceCategory&&$modelResidenceCategory->has_multiple_gt==1){
      $qry.=" and account_service.service_id=:service and account_service.status=1";
    }
    else{
      $qry.="  NOT IN(SELECT account_id_customer FROM account_authority WHERE account_authority.status = 1 and account_authority.account_id_gt is not null ) ORDER BY customer.created_at DESC";
    }
  }

  }
        $command =  Yii::$app->db->createCommand($qry);
        $command->bindParam(':wards',$wardId);
        if($hks){
    $modelHks = GreenActionUnit::find()->where(['id'=>$hks])->andWhere(['status'=>1])->one();
    if($hks&&$modelHks&&isset($modelHks->residence_category_id)){
      $modelResidenceCategory = ResidenceCategory::find()->where(['id'=>$modelHks->residence_category_id])->andWhere(['status'=>1])->one();
    if($modelResidenceCategory&&$modelResidenceCategory->has_multiple_gt==1){
      $command->bindParam(':service',$service);
    }
  }

  }
        // $command->bindParam(':service',$service);
        $command->bindParam(':buildingType',$BuildingIds);
        $command->bindParam(':supervisor',$supervisor);
         $command->bindParam(':association',$association);
       }
       else
       {
        $qry = "SELECT customer.lead_person_name,customer.id as customer_id,account.id as account_id,customer.ward_id as ward_id,customer.building_type_id as building_type_id,account_authority.account_id_customer as account_id_customer,building_type.name as building_type_name,customer.building_number as building_number,customer.address as address, customer.association_name as association_name, customer.association_number as association_number,customer.residential_association_id as residential_association_id FROM customer LEFT JOIN account ON account.customer_id = customer.id LEFT JOIN building_type ON building_type.id = customer.building_type_id LEFT JOIN account_authority ON account.id = account_authority.account_id_customer  LEFT JOIN account_service on account_service.account_id= account_authority.account_id_customer WHERE ward_id IN (:wards) and building_type_id IN (:buildingType) and account_authority.account_id_supervisor=:supervisor  and account_authority.status =1 and customer.status=1 and account.id 
        -- and account_service.service_id=:service and account_service.status=1

        -- NOT IN(SELECT account_id_customer FROM account_authority WHERE account_authority.status = 1 and account_authority.account_id_gt is not null ) ORDER BY customer.created_at DESC
        ";
        if($hks){
    $modelHks = GreenActionUnit::find()->where(['id'=>$hks])->andWhere(['status'=>1])->one();
    if($hks&&$modelHks&&isset($modelHks->residence_category_id)){
      $modelResidenceCategory = ResidenceCategory::find()->where(['id'=>$modelHks->residence_category_id])->andWhere(['status'=>1])->one();
    if($modelResidenceCategory&&$modelResidenceCategory->has_multiple_gt==1){
      $qry.=" and account_service.service_id=:service and account_service.status=1";
    }
    else{
      $qry.="  NOT IN(SELECT account_id_customer FROM account_authority WHERE account_authority.status = 1 and account_authority.account_id_gt is not null ) ORDER BY customer.created_at DESC";
    }
  }

  }
        $command =  Yii::$app->db->createCommand($qry);
        $command->bindParam(':wards',$wardId);
         if($hks){
    $modelHks = GreenActionUnit::find()->where(['id'=>$hks])->andWhere(['status'=>1])->one();
    if($hks&&$modelHks&&isset($modelHks->residence_category_id)){
      $modelResidenceCategory = ResidenceCategory::find()->where(['id'=>$modelHks->residence_category_id])->andWhere(['status'=>1])->one();
    if($modelResidenceCategory&&$modelResidenceCategory->has_multiple_gt==1){
      $command->bindParam(':service',$service);
    }
  }

  }
        // $command->bindParam(':service',$service);
        $command->bindParam(':buildingType',$BuildingIds);
        $command->bindParam(':supervisor',$supervisor);
       
       }

        $customersList = $command->queryAll();
        $dataProvider = new ArrayDataProvider([
        'allModels' =>$customersList,
          ]);
          $dataProvider->pagination = false;
        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'hks' => $hks,
            'supervisorId' => $supervisor,
        ]);
    }
    public function actionUpdate()
    {
        $unit         = null;
        $supervisor         = null;
        $post         = Yii::$app->request->post();
        if (isset($post['unit']))
        {
            $unit = $post['unit'];
        }
         if (isset($post['supervisor']))
        {
            $supervisor = $post['supervisor'];
        }
      $modelUser  = Yii::$app->user->identity;
      if($modelUser->role=='admin-hks'||$modelUser->role=='supervisor'){
        $hks = $modelUser->green_action_unit_id;
      }if($modelUser->role=='admin-lsgi'||$modelUser->role=='super-admin')
      {
         $hks = $unit;
      }
      
      $wards = [];
      $modelWards = GreenActionUnitWard::find()
      ->leftJoin('account','account.green_action_unit_id=green_action_unit_ward.green_action_unit_id')
      ->where(['green_action_unit_ward.status'=>1])
      ->andWhere(['account.status'=>1])
      ->andWhere(['account.green_action_unit_id'=>$hks]);

      if($modelUser->role=='supervisor'){
        $supervisor = $modelUser->id;
       $modelWards = $modelWards->leftJoin(['account_authority','account_authority.account_id_supervisor=account.id'])
       ->andWhere(['account_authority.account_id_supervisor'=>$modelUser->id]);
      }
      $modelWards = $modelWards->all();
      foreach ($modelWards as $key => $value) {
            $wards[] = $value->ward_id;
      }
      $wards = array_unique($wards);

      $buildingType = [];
      $modelBuildingType = BuildingType::find()
      ->leftJoin('residence_category','residence_category.id=building_type.residence_category_id')
      ->leftJoin('green_action_unit','green_action_unit.residence_category_id=residence_category.id')
      ->where(['green_action_unit.status'=>1])
      ->andWhere(['residence_category.status'=>1])
      ->andWhere(['building_type.status'=>1])
      ->andWhere(['green_action_unit.id'=>$hks])
      ->all();
      foreach ($modelBuildingType as $key => $value) {
            $buildingType[] = $value->id;
      }
      $buildingType = array_unique($buildingType);

      $wardId = '';
foreach ($wards as $parent) {
    $wardId .= $parent . ',';
}

$wardId = rtrim($wardId, ',');
$BuildingIds = '';
foreach ($buildingType as $building) {
    $BuildingIds .= $building . ',';
}

$BuildingIds = rtrim($BuildingIds, ',');
    $qry = "SELECT customer.lead_person_name,customer.id as customer_id,account.id as account_id,customer.ward_id as ward_id,customer.building_type_id as building_type_id,account_authority.account_id_customer as account_id_customer FROM customer LEFT JOIN account ON account.customer_id = customer.id LEFT JOIN account_authority ON account.id = account_authority.account_id_customer  WHERE ward_id IN (:wards) and building_type_id IN (:buildingType) and account_authority.account_id_supervisor=:supervisor ORDER BY customer.created_at DESC";
        $command =  Yii::$app->db->createCommand($qry);
        $command->bindParam(':wards',$wardId);
        $command->bindParam(':buildingType',$BuildingIds);
        $command->bindParam(':supervisor',$supervisor);
        $customersList = $command->queryAll();
        $dataProvider = new ArrayDataProvider([
        'allModels' =>$customersList,
          ]);
          $dataProvider->pagination = false;
        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'hks' => $hks,
            'supervisorId' => $supervisor,
        ]);
    }

    /**
     * Displays a single AccountGt model.
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

   
    public function actionAddGt()
    {
        $modelAccountGt = new AccountAuthority;
        $params = Yii::$app->request->post();
        $ok = $params && $modelAccountGt->load($params);
        if($modelAccountGt->hks){
          $modelHks = GreenActionUnit::find()->where(['id'=>$modelAccountGt->hks])->andWhere(['status'=>1])->one();
          if($modelAccountGt->hks&&$modelHks&&isset($modelHks->residence_category_id)){
            $modelResidenceCategory = ResidenceCategory::find()->where(['id'=>$modelHks->residence_category_id])->andWhere(['status'=>1])->one();
          if($modelResidenceCategory&&$modelResidenceCategory->has_multiple_gt==1){
            $customersList = explode(",",$params['AccountAuthority']['customer_id']);
        foreach ($customersList as $key => $value) {

           $qry = "INSERT INTO account_authority(account_id_gt,account_id_customer,account_id_supervisor) SELECT :gt,:customer,:supervisor WHERE NOT EXISTS(SELECT account_id_gt,account_id_customer FROM account_authority WHERE account_authority.account_id_customer=:customer and account_authority.account_id_gt=:gt and status=1)";
            $command =  Yii::$app->db->createCommand($qry);
            $gt = $modelAccountGt->account_id_gt;
            $supervisor = $modelAccountGt->account_id_supervisor;
            $customer = $value;
            $command->bindParam(':gt',$gt);
            $command->bindParam(':supervisor',$supervisor);
            $command->bindParam(':customer',$customer);
            $command->execute();
        
    }
           
          }
          else
          {
            $customersList = explode(",",$params['AccountAuthority']['customer_id']);
        foreach ($customersList as $key => $value) {

            $modelGtAccount = AccountAuthority::find()->where(['account_id_customer'=>$value])->andWhere(['status'=>1])->andWhere(['>','account_id_gt',0])->andWhere(['account_id_supervisor'=>$modelAccountGt->account_id_supervisor])->one();
            $modelGt = AccountAuthority::find()->where(['account_id_customer'=>$value])->andWhere(['status'=>1])->andWhere(['account_id_gt'=>null])->andWhere(['account_id_supervisor'=>$modelAccountGt->account_id_supervisor])->one();
            if($modelGtAccount){
                $modelGtAccount->status = 0;
                $modelGtAccount->save(false);
            }elseif($modelGt)
            {
                $modelGt->account_id_gt =$modelAccountGt->account_id_gt;
                $modelGt->save(false);
            }
            else{
            $qry = "INSERT INTO account_authority(account_id_gt,account_id_customer,account_id_supervisor) SELECT :gt,:customer,:supervisor";
            $command =  Yii::$app->db->createCommand($qry);
            $gt = $modelAccountGt->account_id_gt;
            $supervisor = $modelAccountGt->account_id_supervisor;
            $customer = $value;
            $command->bindParam(':gt',$gt);
            $command->bindParam(':supervisor',$supervisor);
            $command->bindParam(':customer',$customer);
            $command->execute();
          }
          }
          }
        }

        }
        else{
        $customersList = explode(",",$params['AccountAuthority']['customer_id']);
        foreach ($customersList as $key => $value) {

            $modelGtAccount = AccountAuthority::find()->where(['account_id_customer'=>$value])->andWhere(['status'=>1])->andWhere(['>','account_id_gt',0])->andWhere(['account_id_supervisor'=>$modelAccountGt->account_id_supervisor])->one();
            $modelGt = AccountAuthority::find()->where(['account_id_customer'=>$value])->andWhere(['status'=>1])->andWhere(['account_id_gt'=>null])->andWhere(['account_id_supervisor'=>$modelAccountGt->account_id_supervisor])->one();
            if($modelGtAccount){
                $modelGtAccount->status = 0;
                $modelGtAccount->save(false);
            }elseif($modelGt)
            {
                $modelGt->account_id_gt =$modelAccountGt->account_id_gt;
                $modelGt->save(false);
            }
            else{
            $qry = "INSERT INTO account_authority(account_id_gt,account_id_customer,account_id_supervisor) SELECT :gt,:customer,:supervisor";
            $command =  Yii::$app->db->createCommand($qry);
            $gt = $modelAccountGt->account_id_gt;
            $supervisor = $modelAccountGt->account_id_supervisor;
            $customer = $value;
            $command->bindParam(':gt',$gt);
            $command->bindParam(':supervisor',$supervisor);
            $command->bindParam(':customer',$customer);
            $command->execute();
        }
           // $qry = "INSERT INTO account_authority(account_id_gt,account_id_customer,account_id_supervisor) SELECT :gt,:customer,:supervisor WHERE NOT EXISTS(SELECT account_id_gt,account_id_customer FROM account_authority WHERE account_authority.account_id_customer=:customer and account_authority.account_id_gt=:gt and status=1)";
           //  $command =  Yii::$app->db->createCommand($qry);
           //  $gt = $modelAccountGt->account_id_gt;
           //  $supervisor = $modelAccountGt->account_id_supervisor;
           //  $customer = $value;
           //  $command->bindParam(':gt',$gt);
           //  $command->bindParam(':supervisor',$supervisor);
           //  $command->bindParam(':customer',$customer);
           //  $command->execute();
        
    }
  }

        return $this->redirect('index');
       
    }
}
