<?php

namespace backend\controllers;

use Yii;
use backend\models\AccountAuthority;
use backend\models\AccountSupervisorSearch;
use backend\models\GreenActionUnitWard;
use backend\models\BuildingType;
use backend\models\GreenActionUnit;
use backend\models\ResidenceCategory;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ArrayDataProvider;
use backend\components\AccessPermission;
use yii\filters\AccessControl;
/**
 * AccountGtController implements the CRUD actions for AccountGt model.
 */
class AccountSupervisorController extends Controller
{
    /**
     * {@inheritdoc}
     */
    //   public function behaviors()
    // {
    //     return [
    //         'access' => [
    //             'class'        => AccessControl::className(),
    //             'only'         => ['index', 'create', 'update', 'view', 'view-details'],
    //             'rules'        => [
    //                 [
    //                     // 'actions' => ['index', 'create', 'update', 'view', 'view-details'],
    //                     'allow'   => true,
    //                     'roles'   => ['@'],
    //                     'permissions' => [
    //                       'account-supervisor-update','account-supervisor-add-supervisor','account-supervisor-index',
    //                     ]
    //                 ]
    //             ],
    //             'denyCallback' => function (
    //                 $rule,
    //                 $action
    //             )
    //             {
    //                 return $this->goHome();
    //             }
    //         ]
    //     ];
    // }

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
                        'permissions' => ['account-supervisor-index']
                    ],
                    [
                        'actions' => ['update'],
                        'allow'   => true,
                        'permissions' => ['account-supervisor-update']
                    ],
                    [
                        'actions' => ['add-supervisor'],
                        'allow'   => true,
                        'permissions' => ['account-supervisor-add-supervisor',]
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
     * Lists all AccountGt models.
     * @return mixed
     */
//     public function actionIndex()
//     {
//         $hks         = null;
//         $unit         = null;
//         $association         = null;
//         $post         = Yii::$app->request->post();
//         if (isset($post['unit']))
//         {
//             $unit = $post['unit'];
//         }
//          if (isset($post['ward']))
//         {
//             $ward = $post['ward'];
//         }
//         if (isset($post['association']))
//         {
//             $association = $post['association'];
//         }
//       $modelUser  = Yii::$app->user->identity;
//       if($modelUser->role=='admin-hks'){
//         $hks = $modelUser->green_action_unit_id;
//       }if($modelUser->role=='admin-lsgi'||$modelUser->role=='super-admin')
//       {
//          $hks = $unit;
//       }
//       if(!isset($post['ward'])){
//         if($hks){
//       $wards = [];
//       $modelWards = GreenActionUnitWard::find()
//       ->leftJoin('account','account.green_action_unit_id=green_action_unit_ward.green_action_unit_id')
//       ->where(['green_action_unit_ward.status'=>1])
//       ->andWhere(['account.status'=>1])
//       ->andWhere(['account.green_action_unit_id'=>$hks])
//       ->all();
//       foreach ($modelWards as $key => $value) {
//             $wards[] = $value->ward_id;
//       }
//       $wards = array_unique($wards);
//       $wardId = '';
// foreach ($wards as $parent) {
//     $wardId .= $parent . ',';
// }

// $wardId = rtrim($wardId, ',');
// }
// }
// else
// {

//   $wardId = $ward;
// }
// // print_r($wardId);die();
//       $buildingType = [];
//       $modelBuildingType = BuildingType::find()
//       ->leftJoin('residence_category','residence_category.id=building_type.residence_category_id')
//       ->leftJoin('green_action_unit','green_action_unit.residence_category_id=residence_category.id')
//       ->where(['green_action_unit.status'=>1])
//       ->andWhere(['residence_category.status'=>1])
//       ->andWhere(['building_type.status'=>1])
//       ->andWhere(['green_action_unit.id'=>$hks])
//       ->all();
//       foreach ($modelBuildingType as $key => $value) {
//             $buildingType[] = $value->id;
//       }
//       $buildingType = array_unique($buildingType);


// $buildingIds = '';
// foreach ($buildingType as $building) {
//     $buildingIds .= $building . ',';
// }

// $buildingIds = rtrim($buildingIds, ',');
// if($hks){
//    $qry = "SELECT DISTINCT customer.created_at,customer.lead_person_name,customer.id as customer_id,customer.ward_id as ward_id,customer.building_type_id as building_type_id,building_type.name as building_type_name,customer.building_number as building_number,customer.address as address, customer.association_name as association_name, customer.association_number as association_number,customer.residential_association_id as residential_association_id FROM customer
//    LEFT JOIN account on account.customer_id=customer.id
//     LEFT JOIN building_type ON building_type.id = customer.building_type_id WHERE ward_id IN (:wards) and building_type_id IN (:buildingType) and customer.status=1 and customer.door_status=1
//      ";
//      if(isset($post['association'])&&$post['association']!=null)
//      {
//       $qry.="  and customer.residential_association_id=:association";
//      }
//      if($hks){
//     $modelHks = GreenActionUnit::find()->where(['id'=>$hks])->andWhere(['status'=>1])->one();
//     if($hks&&$modelHks&&isset($modelHks->residence_category_id)){
//       $modelResidenceCategory = ResidenceCategory::find()->where(['id'=>$modelHks->residence_category_id])->andWhere(['status'=>1])->one();
//     if($modelResidenceCategory&&$modelResidenceCategory->has_multiple_gt==1){
//       $qry.=" and account.id NOT IN(SELECT account_id_customer FROM account_authority LEFT JOIN account on account.id=account_authority.account_id_supervisor WHERE account_authority.status = 1 and account_authority.account_id_supervisor is not null and account.green_action_unit_id=:hks) GROUP BY customer_id ORDER BY customer.created_at DESC";
//     }
//     else{
//       $qry.="  and account.id NOT IN(SELECT account_id_customer FROM account_authority left join account on account.id=account_authority.account_id_supervisor WHERE account_authority.status = 1 and account_authority.account_id_supervisor is not null) GROUP BY customer_id ORDER BY customer.created_at DESC";
//     }
//   }

//   }
        
//         $command =  Yii::$app->db->createCommand($qry);
//         $command->bindParam(':wards',$wardId);
//         $command->bindParam(':buildingType',$buildingIds);
//         if(isset($post['association'])&&$post['association']!=null){
//         $command->bindParam(':association',$association);
//       }

//         if($hks){
//     $modelHks = GreenActionUnit::find()->where(['id'=>$hks])->andWhere(['status'=>1])->one();
//     if($hks&&$modelHks&&isset($modelHks->residence_category_id)){
//       $modelResidenceCategory = ResidenceCategory::find()->where(['id'=>$modelHks->residence_category_id])->andWhere(['status'=>1])->one();
//     if($modelResidenceCategory&&$modelResidenceCategory->has_multiple_gt==1){
//       $command->bindParam(':hks',$hks);
//     }
//   }

//   }
// }else
// {
//   $customersList = null;
// }

//         $customersList = $command->queryAll();
//         // print_r(count($customersList));die();
//         $dataProvider = new ArrayDataProvider([
//         'allModels' =>$customersList,
//           ]);
//           $dataProvider->pagination = false;

//         return $this->render('index', [
//             'dataProvider' => $dataProvider,
//             'hks' => $hks,
//         ]);
//     }
    public function actionIndex()
    {
        $hks         = null;
        $unit         = null;
        $association         = null;
        $post         = Yii::$app->request->post();
        if (isset($post['unit']))
        {
            $unit = $post['unit'];
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
      if($modelUser->role=='admin-hks'){
        $hks = $modelUser->green_action_unit_id;
      }if($modelUser->role=='admin-lsgi'||$modelUser->role=='super-admin')
      {
         $hks = $unit;
      }
      if(!isset($post['ward'])){
        if($hks){
      $wards = [];
      $modelWards = GreenActionUnitWard::find()
      ->leftJoin('account','account.green_action_unit_id=green_action_unit_ward.green_action_unit_id')
      ->where(['green_action_unit_ward.status'=>1])
      ->andWhere(['account.status'=>1])
      ->andWhere(['account.green_action_unit_id'=>$hks])
      ->all();
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
}
else
{

  $wardId = $ward;
}
// print_r($wardId);die();
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
      // $buildingIds = implode(',',$buildingType);
$buildingIds = '';
foreach ($buildingType as $building) {
    $buildingIds .= $building . ',';
}

$buildingIds = rtrim($buildingIds, ',');
// print_r(array($buildingType));die();
// print_r($wardId);
// print_r($buildingIds);die();
// and customer.residential_association_id=:association
if(isset($post['association'])&&$post['association']!=null){
  // print_r($post['association']);die();
   $qry = "SELECT DISTINCT customer.created_at,customer.lead_person_name,customer.id as customer_id,account.id as account_id,customer.ward_id as ward_id,customer.building_type_id as building_type_id,building_type.name as building_type_name,customer.building_number as building_number,customer.address as address, customer.association_name as association_name, customer.association_number as association_number,customer.residential_association_id as residential_association_id FROM customer LEFT JOIN account ON account.customer_id = customer.id LEFT JOIN building_type ON building_type.id = customer.building_type_id WHERE ward_id IN (:wards) and building_type_id IN (select building_type.id from building_type left join residence_category on residence_category.id=building_type.residence_category_id left Join green_action_unit on green_action_unit.residence_category_id=residence_category.id where green_action_unit.status=1 and building_type.status=1 and residence_category.status=1 and green_action_unit.id=:hks) and customer.status=1 and customer.residential_association_id=:association and customer.door_status=1 and account.id 
     ";
     if($hks){
    $modelHks = GreenActionUnit::find()->where(['id'=>$hks])->andWhere(['status'=>1])->one();
    if($hks&&$modelHks&&isset($modelHks->residence_category_id)){
      $modelResidenceCategory = ResidenceCategory::find()->where(['id'=>$modelHks->residence_category_id])->andWhere(['status'=>1])->one();
    if($modelResidenceCategory&&$modelResidenceCategory->has_multiple_gt==1){
      // $qry.=" and account_service.service_id=:service and account_service.status=1";
      $qry.=" NOT IN(SELECT account_id_customer FROM account_authority LEFT JOIN account on account.id=account_authority.account_id_supervisor WHERE account_authority.status = 1 and account_authority.account_id_supervisor is not null and account.green_action_unit_id=:hks) GROUP BY customer_id ORDER BY customer.created_at DESC";
    }
    else{
      $qry.="  NOT IN(SELECT account_id_customer FROM account_authority left join account on account.id=account_authority.account_id_supervisor WHERE account_authority.status = 1 and account_authority.account_id_supervisor is not null) GROUP BY customer_id ORDER BY customer.created_at DESC";
    }
  }

  }
        
        $command =  Yii::$app->db->createCommand($qry);
        $command->bindParam(':wards',$wardId);
        // $command->bindParam(':buildingType',$buildingIds);
        $command->bindParam(':association',$association);
         $command->bindParam(':hks',$hks);
        if($hks){
    $modelHks = GreenActionUnit::find()->where(['id'=>$hks])->andWhere(['status'=>1])->one();
    if($hks&&$modelHks&&isset($modelHks->residence_category_id)){
      $modelResidenceCategory = ResidenceCategory::find()->where(['id'=>$modelHks->residence_category_id])->andWhere(['status'=>1])->one();
    if($modelResidenceCategory&&$modelResidenceCategory->has_multiple_gt==1){
      $command->bindParam(':hks',$hks);
    }
  }

  }
         }
       else
       {
        // print_r("expression");die();
        $qry = "SELECT DISTINCT customer.created_at,customer.lead_person_name,customer.id as customer_id,account.id as account_id,customer.ward_id as ward_id,customer.building_type_id as building_type_id,building_type.name as building_type_name,customer.building_number as building_number,customer.address as address, customer.association_name as association_name, customer.association_number as association_number,customer.residential_association_id as residential_association_id FROM customer LEFT JOIN account ON account.customer_id = customer.id LEFT JOIN building_type ON building_type.id = customer.building_type_id WHERE ward_id IN (:wards) and building_type_id IN (select building_type.id from building_type left join residence_category on residence_category.id=building_type.residence_category_id left Join green_action_unit on green_action_unit.residence_category_id=residence_category.id where green_action_unit.status=1 and building_type.status=1 and residence_category.status=1 and green_action_unit.id=:hks) and customer.status=1 and customer.door_status=1  and account.id
         -- NOT IN(SELECT account_id_customer FROM account_authority left join account on account.id=account_authority.account_id_supervisor WHERE account_authority.status = 1 and account_authority.account_id_supervisor is not null and account.status=1 ) GROUP BY customer_id
     -- ORDER BY customer.created_at DESC 
     ";
     if($hks){
    $modelHks = GreenActionUnit::find()->where(['id'=>$hks])->andWhere(['status'=>1])->one();
    if($hks&&$modelHks&&isset($modelHks->residence_category_id)){
      $modelResidenceCategory = ResidenceCategory::find()->where(['id'=>$modelHks->residence_category_id])->andWhere(['status'=>1])->one();
    if($modelResidenceCategory&&$modelResidenceCategory->has_multiple_gt==1){
      // $qry.=" and account_service.service_id=:service and account_service.status=1";
      $qry.=" NOT IN(SELECT account_id_customer FROM account_authority LEFT JOIN account on account.id=account_authority.account_id_supervisor WHERE account_authority.status = 1 and account_authority.account_id_supervisor is not null and account.green_action_unit_id=:hks) GROUP BY customer_id ORDER BY customer.created_at DESC";
    }
    else{
      $qry.="  NOT IN(SELECT account_id_customer FROM account_authority left join account on account.id=account_authority.account_id_supervisor WHERE account_authority.status = 1 and account_authority.account_id_supervisor is not null) GROUP BY customer_id ORDER BY customer.created_at DESC";
    }
  }

  }
        $command =  Yii::$app->db->createCommand($qry);
        $command->bindParam(':wards',$wardId);
        $command->bindParam(':hks',$hks);
        // $command->bindParam(':buildingType',$buildingIds);
         if($hks){
    $modelHks = GreenActionUnit::find()->where(['id'=>$hks])->andWhere(['status'=>1])->one();
    if($hks&&$modelHks&&isset($modelHks->residence_category_id)){
      $modelResidenceCategory = ResidenceCategory::find()->where(['id'=>$modelHks->residence_category_id])->andWhere(['status'=>1])->one();
    if($modelResidenceCategory&&$modelResidenceCategory->has_multiple_gt==1){
      $command->bindParam(':hks',$hks);
    }
  }

  }

       }
        $customersList = $command->queryAll();
        // print_r(count($customersList));die();
        $dataProvider = new ArrayDataProvider([
        'allModels' =>$customersList,
          ]);
          $dataProvider->pagination = false;

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'hks' => $hks,
        ]);
    }
    public function actionUpdate()
    {
        $unit         = null;
        $association         = null;
        $post         = Yii::$app->request->post();
        if (isset($post['unit']))
        {
            $unit = $post['unit'];
        }
        if (isset($post['association']))
        {
            $association = $post['association'];
        }
      $modelUser  = Yii::$app->user->identity;
      if($modelUser->role=='admin-hks'){
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
      ->andWhere(['account.green_action_unit_id'=>$hks])
      ->all();
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
$buildingIds = '';
foreach ($buildingType as $building) {
    $buildingIds .= $building . ',';
}

$buildingIds = rtrim($buildingIds, ',');
// print_r($wardId);
// print_r($buildingIds);die();
// and customer.residential_association_id=:association
    $qry = "SELECT customer.lead_person_name,customer.id as customer_id,account.id as account_id,customer.ward_id as ward_id,customer.building_type_id as building_type_id FROM customer LEFT JOIN account ON account.customer_id = customer.id WHERE ward_id IN (:wards) and building_type_id IN (:buildingType) and account.id
     ORDER BY customer.created_at DESC";
        $command =  Yii::$app->db->createCommand($qry);
        $command->bindParam(':wards',$wardId);
        $command->bindParam(':buildingType',$buildingIds);
        // $command->bindParam(':association',$association);
        $customersList = $command->queryAll();
        $dataProvider = new ArrayDataProvider([
        'allModels' =>$customersList,
          ]);
          $dataProvider->pagination = false;

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'hks' => $hks,
        ]);
    }
    /**
     * Creates a new AccountGt model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */

    // public function actionAddSupervisor()
    // {
    //     $modelAccountSupervisor = new AccountAuthority;
    //     $params = Yii::$app->request->post();
    //     // print_r($params);die();
    //     $ok = $params && $modelAccountSupervisor->load($params);
    //     $customersList = explode(",",$params['AccountAuthority']['customer_id']);
    //     foreach ($customersList as $key => $value) {

    //         $modelSupervisorAccount = AccountAuthority::find()->where(['account_id_customer'=>$value])->andWhere(['status'=>1])->one();
    //         if($modelSupervisorAccount){
    //           $modelSupervisorAccount->status =0;
    //           $modelSupervisorAccount->save(false);
    //            }
    //         $qry = "INSERT INTO account_authority(account_id_supervisor,account_id_customer) SELECT :supervisor,:customer";
    //         $command =  Yii::$app->db->createCommand($qry);
    //         $supervisor = $modelAccountSupervisor->account_id_supervisor;
    //         $customer = $value;
    //         $command->bindParam(':supervisor',$supervisor);
    //         $command->bindParam(':customer',$customer);
    //         $command->execute();
    //   }


    //     return $this->redirect('index');

    // }

    public function actionAddSupervisor()
    {
        $modelAccountSupervisor = new AccountAuthority;
        $params = Yii::$app->request->post();
        $ok = $params && $modelAccountSupervisor->load($params);
        $modelAccountSupervisor->hks = isset($params['AccountAuthority']['hks'])?$params['AccountAuthority']['hks']:null;
        if($modelAccountSupervisor->hks){
          $modelHks = GreenActionUnit::find()->where(['id'=>$modelAccountSupervisor->hks])->andWhere(['status'=>1])->one();
          if($modelAccountSupervisor->hks&&$modelHks&&isset($modelHks->residence_category_id)){
            $modelResidenceCategory = ResidenceCategory::find()->where(['id'=>$modelHks->residence_category_id])->andWhere(['status'=>1])->one();
          if($modelResidenceCategory&&$modelResidenceCategory->has_multiple_gt==1){
            $customersList = explode(",",$params['AccountAuthority']['customer_id']);
        foreach ($customersList as $key => $value) {

           $qry = "INSERT INTO account_authority(account_id_customer,account_id_supervisor) SELECT :customer,:supervisor WHERE NOT EXISTS(SELECT account_id_supervisor,account_id_customer FROM account_authority WHERE account_authority.account_id_customer=:customer and account_authority.account_id_supervisor=:supervisor and status=1)";
            $command =  Yii::$app->db->createCommand($qry);
            $gt = $modelAccountSupervisor->account_id_gt;
            $supervisor = $modelAccountSupervisor->account_id_supervisor;
            $customer = $value;
            // $command->bindParam(':gt',$gt);
            $command->bindParam(':supervisor',$supervisor);
            $command->bindParam(':customer',$customer);
            $command->execute();
        
    }
           
          }
          else
          {
        $customersList = explode(",",$params['AccountAuthority']['customer_id']);
        foreach ($customersList as $key => $value) {

            $modelSupervisorAccount = AccountAuthority::find()->where(['account_id_customer'=>$value])->andWhere(['status'=>1])->one();
            if($modelSupervisorAccount){
              $modelSupervisorAccount->status =0;
              $modelSupervisorAccount->save(false);
               }
            $qry = "INSERT INTO account_authority(account_id_supervisor,account_id_customer) SELECT :supervisor,:customer";
            $command =  Yii::$app->db->createCommand($qry);
            $supervisor = $modelAccountSupervisor->account_id_supervisor;
            $customer = $value;
            $command->bindParam(':supervisor',$supervisor);
            $command->bindParam(':customer',$customer);
            $command->execute();
      }
          }
        }

        }
        else{
    $customersList = explode(",",$params['AccountAuthority']['customer_id']);
        foreach ($customersList as $key => $value) {

            $modelSupervisorAccount = AccountAuthority::find()->where(['account_id_customer'=>$value])->andWhere(['status'=>1])->one();
            if($modelSupervisorAccount){
              $modelSupervisorAccount->status =0;
              $modelSupervisorAccount->save(false);
               }
            $qry = "INSERT INTO account_authority(account_id_supervisor,account_id_customer) SELECT :supervisor,:customer";
            $command =  Yii::$app->db->createCommand($qry);
            $supervisor = $modelAccountSupervisor->account_id_supervisor;
            $customer = $value;
            $command->bindParam(':supervisor',$supervisor);
            $command->bindParam(':customer',$customer);
            $command->execute();
  }
}

        return $this->redirect('index');
       
    }
     public function actionReassign()
    {
         $hks         = null;
        $unit         = null;
        $association         = null;
        $post         = Yii::$app->request->post();
        if (isset($post['unit']))
        {
            $unit = $post['unit'];
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
      if($modelUser->role=='admin-hks'){
        $hks = $modelUser->green_action_unit_id;
      }if($modelUser->role=='admin-lsgi'||$modelUser->role=='super-admin')
      {
         $hks = $unit;
      }
      if(!isset($post['ward'])){
        if($hks){
      $wards = [];
      $modelWards = GreenActionUnitWard::find()
      ->leftJoin('account','account.green_action_unit_id=green_action_unit_ward.green_action_unit_id')
      ->where(['green_action_unit_ward.status'=>1])
      ->andWhere(['account.status'=>1])
      ->andWhere(['account.green_action_unit_id'=>$hks])
      ->all();
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
}
else
{

  $wardId = $ward;
}
// print_r($wardId);die();
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


$buildingIds = '';
foreach ($buildingType as $building) {
    $buildingIds .= $building . ',';
}

$buildingIds = rtrim($buildingIds, ',');
// print_r($wardId);
// print_r($buildingIds);die();
// and customer.residential_association_id=:association
if(isset($post['association'])&&$post['association']!=null){
  // print_r($post['association']);die();
   $qry = "SELECT DISTINCT customer.created_at,customer.lead_person_name,customer.id as customer_id,account.id as account_id,customer.ward_id as ward_id,customer.building_type_id as building_type_id,building_type.name as building_type_name,customer.building_number as building_number,customer.address as address, customer.association_name as association_name, customer.association_number as association_number,customer.residential_association_id as residential_association_id FROM customer LEFT JOIN account ON account.customer_id = customer.id LEFT JOIN building_type ON building_type.id = customer.building_type_id WHERE ward_id IN (:wards) and building_type_id IN (:buildingType) and customer.status=1 and customer.residential_association_id=:association and customer.door_status=1 and account.id 
     ";
     if($hks){
    $modelHks = GreenActionUnit::find()->where(['id'=>$hks])->andWhere(['status'=>1])->one();
    if($hks&&$modelHks&&isset($modelHks->residence_category_id)){
      $modelResidenceCategory = ResidenceCategory::find()->where(['id'=>$modelHks->residence_category_id])->andWhere(['status'=>1])->one();
    if($modelResidenceCategory&&$modelResidenceCategory->has_multiple_gt==1){
      // $qry.=" and account_service.service_id=:service and account_service.status=1";
      $qry.=" IN(SELECT account_id_customer FROM account_authority LEFT JOIN account on account.id=account_authority.account_id_supervisor WHERE account_authority.status = 1 and account_authority.account_id_supervisor is not null and account.green_action_unit_id=:hks) GROUP BY customer_id ORDER BY customer.created_at DESC";
    }
    else{
      $qry.="  IN(SELECT account_id_customer FROM account_authority left join account on account.id=account_authority.account_id_supervisor WHERE account_authority.status = 1 and account_authority.account_id_supervisor is not null) GROUP BY customer_id ORDER BY customer.created_at DESC";
    }
  }

  }
        
        $command =  Yii::$app->db->createCommand($qry);
        $command->bindParam(':wards',$wardId);
        $command->bindParam(':buildingType',$buildingIds);
        $command->bindParam(':association',$association);
        if($hks){
    $modelHks = GreenActionUnit::find()->where(['id'=>$hks])->andWhere(['status'=>1])->one();
    if($hks&&$modelHks&&isset($modelHks->residence_category_id)){
      $modelResidenceCategory = ResidenceCategory::find()->where(['id'=>$modelHks->residence_category_id])->andWhere(['status'=>1])->one();
    if($modelResidenceCategory&&$modelResidenceCategory->has_multiple_gt==1){
      $command->bindParam(':hks',$hks);
    }
  }

  }
         }
       else
       {
        // print_r("expression");die();
        $qry = "SELECT DISTINCT customer.created_at,customer.lead_person_name,customer.id as customer_id,account.id as account_id,customer.ward_id as ward_id,customer.building_type_id as building_type_id,building_type.name as building_type_name,customer.building_number as building_number,customer.address as address, customer.association_name as association_name, customer.association_number as association_number,customer.residential_association_id as residential_association_id FROM customer LEFT JOIN account ON account.customer_id = customer.id LEFT JOIN building_type ON building_type.id = customer.building_type_id WHERE ward_id IN (:wards) and building_type_id IN (:buildingType) and customer.status=1 and customer.door_status=1  and account.id
         -- NOT IN(SELECT account_id_customer FROM account_authority left join account on account.id=account_authority.account_id_supervisor WHERE account_authority.status = 1 and account_authority.account_id_supervisor is not null and account.status=1 ) GROUP BY customer_id
     -- ORDER BY customer.created_at DESC 
     ";
     if($hks){
    $modelHks = GreenActionUnit::find()->where(['id'=>$hks])->andWhere(['status'=>1])->one();
    if($hks&&$modelHks&&isset($modelHks->residence_category_id)){
      $modelResidenceCategory = ResidenceCategory::find()->where(['id'=>$modelHks->residence_category_id])->andWhere(['status'=>1])->one();
    if($modelResidenceCategory&&$modelResidenceCategory->has_multiple_gt==1){
      // $qry.=" and account_service.service_id=:service and account_service.status=1";
      $qry.=" IN(SELECT account_id_customer FROM account_authority LEFT JOIN account on account.id=account_authority.account_id_supervisor WHERE account_authority.status = 1 and account_authority.account_id_supervisor is not null and account.green_action_unit_id=:hks) GROUP BY customer_id ORDER BY customer.created_at DESC";
    }
    else{
      $qry.="  IN(SELECT account_id_customer FROM account_authority left join account on account.id=account_authority.account_id_supervisor WHERE account_authority.status = 1 and account_authority.account_id_supervisor is not null) GROUP BY customer_id ORDER BY customer.created_at DESC";
    }
  }

  }
        $command =  Yii::$app->db->createCommand($qry);
        $command->bindParam(':wards',$wardId);
        $command->bindParam(':buildingType',$buildingIds);
         if($hks){
    $modelHks = GreenActionUnit::find()->where(['id'=>$hks])->andWhere(['status'=>1])->one();
    if($hks&&$modelHks&&isset($modelHks->residence_category_id)){
      $modelResidenceCategory = ResidenceCategory::find()->where(['id'=>$modelHks->residence_category_id])->andWhere(['status'=>1])->one();
    if($modelResidenceCategory&&$modelResidenceCategory->has_multiple_gt==1){
      $command->bindParam(':hks',$hks);
    }
  }

  }

       }
        $customersList = $command->queryAll();
        // print_r(count($customersList));die();
        $dataProvider = new ArrayDataProvider([
        'allModels' =>$customersList,
          ]);
          $dataProvider->pagination = false;

        return $this->render('re-assign', [
            'dataProvider' => $dataProvider,
            'hks' => $hks,
        ]);
    }
    public function actionAssignSupervisor()
    {
        $modelAccountSupervisor = new AccountAuthority;
        $params = Yii::$app->request->post();
        $ok = $params && $modelAccountSupervisor->load($params);
        $modelAccountSupervisor->hks = isset($params['AccountAuthority']['hks'])?$params['AccountAuthority']['hks']:null;
        
        $customersList = explode(",",$params['AccountAuthority']['customer_id']);
        foreach ($customersList as $key => $value) {

            $modelSupervisorAccount = AccountAuthority::find()
            ->leftJoin('account','account.id=account_authority.account_id_supervisor')
            ->where(['account_id_customer'=>$value])
            ->andWhere(['account.green_action_unit_id'=>$modelAccountSupervisor->hks])
            ->andWhere(['account_authority.status'=>1])->one();
            if($modelSupervisorAccount){
              $modelSupervisorAccount->account_id_supervisor =$modelAccountSupervisor->account_id_supervisor;
              $modelSupervisorAccount->save(false);
               }
      }
          

        return $this->redirect('index');
       
    }
}
