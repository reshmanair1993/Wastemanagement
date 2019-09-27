<?php

use yii\helpers\Html;
use yii\widgets\ListView;
use yii\widgets\Pjax;
use yii\widgets\ActiveForm;
use yii\db\Query;
use yii\helpers\Url;
use yii\jui\AutoComplete;
use yii\web\JsExpression;
use yii\web\View;
use yii\helpers\ArrayHelper;
use kartik\depdrop\DepDrop;
use backend\models\BuildingType;
use backend\models\PublicPlaceType;
use backend\models\TradingType;
use backend\models\OfficeType;
use backend\models\AdministrationType;
use backend\models\BuildingTypeSubTypes;
use backend\models\ShopType;
use backend\models\FeeCollectionInterval;
use backend\models\WasteCollectionMethod;
use backend\models\TerraceFarmingHelpType;
use backend\models\PublicGatheringMethods;
use backend\models\ResidentialAssociation;
use backend\models\AccountAuthority;
foreach($params as $param => $val)
  ${$param} = $val;
if($model->door_status==1)
{
  $status  = 'Open';
}
elseif($model->door_status==0)
{
  $status  = 'Closed';
}
else
{
  $status  = 'Permenently locked';
}
if($model->has_bio_waste==1)
{
  $hasBioWaste = 'Yes';
}
else
{
  $hasBioWaste = 'No';
}
$nonBioWaste = $model->has_non_bio_waste==1?'Yes':'No';
$disposibleWaste = $model->has_disposible_waste==1?'Yes':'No';
$bioWasteManagementFacility = $model->has_bio_waste_management_facility==1?'Yes':'No';
$nonBioWasteManagementFacility = $model->has_non_bio_waste_management_facility==1?'Yes':'No';
$bioWasteManagementFacilityOperational = $model->bio_waste_management_facility_operational==1?'Yes':'No';
$bioWasteManagementFacilityRepairHelpNeeded = $model->bio_waste_management_facility_repair_help_needed==1?'Yes':'No';
$bioWasteCollectionNeeded = $model->bio_waste_collection_needed==1?'Yes':'No';
$terraceFarmingInterest = $model->has_terrace_farming_interest==1?'Yes':'No';
$greenProtocol = $model->green_protocol_system_implemented==1?'Yes':'No';
$programmes = $model->is_programmes_happening==1?'Yes':'No';
$hasPublicToilet = $model->has_public_toilet==1?'Yes':'No';
 $latCustomer = $model->lat?$model->lat:'';
    $lngCustomer =$model->lng?$model->lng:'';
    $gtName = null;
    $modelAccount = $model->fkCustomerAccount;
    if($modelAccount)
    {

      // $accountAuthority = $modelAccount->fkAccountAuthority;
      // if($accountAuthority)
      // {
      //   // print_r($accountAuthority);die();
      //   $account = $accountAuthority->fkAccountGt;
      //   if($account)
      //   {
      //     $gtDetails = $account->fkPerson;
      //     if($gtDetails)
      //     {
      //       $gtName = $gtDetails->first_name;
      //     }
      //   }
      // }
      $accountAuthority = AccountAuthority::find()->where(['account_id_customer'=>$modelAccount->id])->andWhere(['status'=>1])->all();
      if($accountAuthority)
      {
        foreach ($accountAuthority as $key => $value) {
          $account = $value->fkAccountGt;
        if($account)
        {
          $gtDetails = $account->fkPerson;
          if($gtDetails)
          {
            $gtName = $gtName.','.$gtDetails->first_name;
          }
        }
        }
        $gtName = trim($gtName,",");
      }
    }
    if($model->has_interest_in_system_provided_bio_facility==1){
      $has_interest_in_system_provided_bio_facility = 'Yes';
    }
    else
    {
      $has_interest_in_system_provided_bio_facility = 'No';
    }
?>
<div class="row bg-title">
  <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
    <h4 class="page-title">Customers</h4>
  </div>
  <?php 
  $modelUser  = Yii::$app->user->identity;
$userRole = $modelUser->role;
  if(Yii::$app->user->can('Customers-edit')):
    ?>
  <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
   <?= Html::a(Yii::t('app', 'Edit'), ['customers/edit?id='.$model->id],['data-pjax'=>0], ['class' => 'btn btn-success']) ?>
  </div>
<?php endif;?>
  <div class="col-lg-4 col-sm-8 col-md-8 col-xs-12">
   <?php
//    $breadcrumb[]  = ['label' => 'Customers', 'url' => ['/customers/index']];
// $this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Customers'), 'url' => ['index']];
if($model->id){
   $this->title =  'details';
}
else
{
   $this->title =  'Create';
}
$breadcrumb[] = ['label' => $this->title,'template' => "<li class='active' , style='color:#4AAFF4;'>{link}</li>\n"];
   ?>
   <?=$this->render('/layouts/breadcrumbs',[ 'links'=>$breadcrumb]);?>
</div>
</div>
<div class="row">
  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

       <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 profile">
        <div class="panel panel-default" style="width: 544px;">
        <div class="panel-heading"><b>Basic Informations</b></div>
        <title><?= Html::encode($this->title) ?></title>
          <table>
      <?php if(Yii::$app->user->can('Customers-view-qr-code')||$userRole=='super-admin'):
    ?>
          <?php if(isset($modelQrCode)):?>
        <tr><td><b> QR Code</b></td><td>
       <!--  <img src="https://chart.googleapis.com/chart?chs=200x200&cht=qr&chl=<?=$modelQrCode->value?>&choe=UTF-8"><br><?=$modelQrCode->value?> -->
       <img style="width: 200px;" src="https://www.qr-code-generator.com/phpqrcode/getCode.php?cht=qr&chl=<?=$modelQrCode->value?>&chs=200x200&choe=UTF-8&chld=L|0"><br><?=$modelQrCode->value?>
        </td>
        </tr>
        <tr>
        <td></td>
        </tr>
      <?php else:?>
         <tr><td><b> QR Code</b></td><td>
        Not Set
        </td></tr>
      <?php endif;?>
      <?php endif;?>
      <tr><td><b>Username</b></td><td><?=$model->fkCustomerAccount->username?></td></tr>
        <tr><td><b>Customer Id</b></td><td><?=$model->getFormattedCustomerId($model->id)?></td></tr>
        <tr><td><b>Ward</b></td><td><?=$model->fkWard->name?></td></tr>
        <tr><td><b>Surveyor</b></td><td><?=isset($model->fkAccount->fkPerson->first_name)?$model->fkAccount->fkPerson->first_name:''?></td></tr>
        <tr><td><b>Has Bio waste</b></td><td><?=$hasBioWaste?></td></tr>
        <tr><td><b>Disposible waste</b></td><td><?=$disposibleWaste?></td></tr>
        <tr><td><b>Bio Waste Management Facility</b></td><td><?=$bioWasteManagementFacility?></td></tr>
        <tr><td><b>Non Bio Waste Management Facility</b></td><td><?=$nonBioWasteManagementFacility?></td></tr>
        <tr><td><b> Bio Waste Management Facility Operational</b></td><td><?=$bioWasteManagementFacilityOperational?></td></tr>
        <tr><td><b> Bio Waste Management Facility Repair Help Needed</b></td><td><?=$bioWasteManagementFacilityRepairHelpNeeded?></td></tr>
        <tr><td><b> Bio Waste Collection Needed</b></td><td><?=$bioWasteCollectionNeeded?></td></tr>
        <tr><td><b> Green Technician</b></td><td><?=$gtName?></td></tr>

        </table>
        </div>
      </div>
      <?php if($model->building_type_id==1):?>
      <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 profile">
        <div class="panel panel-default" style="width: 544px;">
        <div class="panel-heading"><b>House Details</b></div>
        <title><?= Html::encode($this->title) ?></title>
          <table>
        <tr><td><b>House Name</b></td><td><?=$model->building_name?></td></tr>
        <tr><td><b>House Number</b></td><td><?=$model->building_number?></td></tr>

        <?php if($model->association_name){?>
          <tr><td><b>Association Name</b></td><td><?=$model->association_name?></td></tr>
        <?php }else{
          $associationId = $model->residential_association_id;
          if($associationId):
            $modelResidentialAssociation = ResidentialAssociation::find()->where(['status'=>1,'id'=>$associationId])->one();
            $associationName = isset($modelResidentialAssociation->name)?$modelResidentialAssociation->name:''; ?>
            <tr><td><b>Association Name</b></td><td><?=$associationName?></td></tr>
          <?php endif; }?>

        <tr><td><b>Association Number</b></td><td><?=$model->association_number?></td></tr>
        <tr><td><b>Door Status</b></td><td><b><font size="24"><?=$status?></font></b></td></tr>
        <tr><td><b>House Owner Name</b></td><td><?=$model->lead_person_name?></td></tr>
        <tr><td><b>Phone Number</b></td><td><?=$model->lead_person_phone?></td></tr>
        <tr><td><b>Address</b></td><td><?=$model->address?></td></tr>
        <tr><td><b>Total Members </b></td><td><?=$model->people_count?></td></tr>
        <tr><td><b>Adult Count </b></td><td><?=$model->house_adult_count?></td></tr>
        <tr><td><b>Child Count </b></td><td><?=$model->house_children_count?></td></tr>
        <tr><td><b> Is Willing To Move To Corporation System</b></td><td><?=$has_interest_in_system_provided_bio_facility?></td></tr>
        <tr><td><b>Area </b></td><td><?=isset($model->fkBuildingSubType->name)?$model->fkBuildingSubType->name:null?></td></tr>
        <tr><td><b>Biowaste collection method </b></td><td><?=isset($model->fkBioWasteCollectionMethod->name)?$model->fkBioWasteCollectionMethod->name:null?></td></tr>
        <tr><td><b>Non biowaste collection method </b></td><td><?=isset($model->fkNonBioWasteCollectionMethod->name)?$model->fkNonBioWasteCollectionMethod->name:null?></td></tr>
        </table>
        </div>
      </div>
      <?php elseif($model->building_type_id==2):?>
       <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 profile">
        <div class="panel panel-default" style="width: 544px;">
        <div class="panel-heading"><b>Shop Details</b></div>
        <title><?= Html::encode($this->title) ?></title>
          <table>
        <tr><td><b>Shop Name</b></td><td><?=$model->shop_name?></td></tr>
         <tr><td><b>Shop Owner Name</b></td><td><?=$model->lead_person_name?></td></tr>
         <tr><td><b>Phone Number</b></td><td><?=$model->lead_person_phone?></td></tr>
          <tr><td><b>Building Owner Name</b></td><td><?=$model->building_owner_name?></td></tr>
         <tr><td><b>Building Owner Phone</b></td><td><?=$model->building_owner_phone?></td></tr>
        <tr><td><b>Address</b></td><td><?=$model->address?></td></tr>
         <tr><td><b>Door Status</b></td><td><b><font size="24"><?=$status?></font></b></td></tr>
         <tr><td><b>Shop Type </b></td><td><?=$model->fkShopType?$model->fkShopType->name:''?></td></tr>
        <tr><td><b>Trading Type </b></td><td><?=$model->fkTradingType?$model->fkTradingType->name:''?></td></tr>
        <tr><td><b>Licence Number </b></td><td><?=$model->licence_no?></td></tr>
        <tr><td><b>Employee Count </b></td><td><?=$model->employee_count?></td></tr>
        <tr><td><b> Is Willing To Move To Corporation System</b></td><td><?=$has_interest_in_system_provided_bio_facility?></td></tr>
        <tr><td><b>Daily Bio waste Quantity </b></td><td><?=$model->daily_bio_waste_quantity?></td></tr>
        <tr><td><b>Biowaste collection method </b></td><td><?=isset($model->fkBioWasteCollectionMethod->name)?$model->fkBioWasteCollectionMethod->name:null?></td></tr>
        <tr><td><b>Non biowaste collection method </b></td><td><?=isset($model->fkNonBioWasteCollectionMethod->name)?$model->fkNonBioWasteCollectionMethod->name:null?></td></tr>
        </table>
        </div>
      </div>
       <?php elseif($model->building_type_id==3):?>
       <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 profile">
        <div class="panel panel-default" style="width: 544px;">
        <div class="panel-heading"><b>Flat</b></div>
        <title><?= Html::encode($this->title) ?></title>
          <table>
        <tr><td><b>Flat Name</b></td><td><?=$model->building_name?></td></tr>
        <tr><td><b>Address</b></td><td><?=$model->address?></td></tr>
        <tr><td><b>Total House</b></td><td><?=$model->house_count?></td></tr>
        <tr><td><b>Phone Number</b></td><td><?=$model->lead_person_phone?></td></tr>
        </table>
        </div>
      </div>
       <?php elseif($model->building_type_id==7):?>
      <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 profile">
        <div class="panel panel-default" style="width: 544px;">
        <div class="panel-heading"><b>Office Details</b></div>
        <title><?= Html::encode($this->title) ?></title>
          <table>
        <tr><td><b>Office Name</b></td><td><?=$model->building_name?></td></tr>
        <tr><td><b> Owner Name</b></td><td><?=$model->lead_person_name?></td></tr>
         <tr><td><b>Phone Number</b></td><td><?=$model->lead_person_phone?></td></tr>
        <tr><td><b>Office Type</b></td><td><?=$model->fkOfficeType?$model->fkOfficeType:''?></td></tr>
        <tr><td><b>Employee Count </b></td><td><?=$model->employee_count?></td></tr>
        <tr><td><b>Contact Person </b></td><td><?=$model->office_contact_person?></td></tr>
         <tr><td><b>Door Status</b></td><td><b><font size="24"><?=$status?></font></b></td></tr>
        <tr><td><b>Contact Person Designation </b></td><td><?=$model->office_contact_person_designation?></td></tr>
        <tr><td><b>Green protocol System Implemented </b></td><td><?=$greenProtocol?></td></tr>
        <tr><td><b>Administrative Type</b></td><td><?=$model->fkAdministrationType?$model->fkAdministrationType->name:null?></td></tr>
        <tr><td><b> Is Willing To Move To Corporation System</b></td><td><?=$has_interest_in_system_provided_bio_facility?></td></tr>
         <tr><td><b>Biowaste collection method </b></td><td><?=isset($model->fkBioWasteCollectionMethod->name)?$model->fkBioWasteCollectionMethod->name:null?></td></tr>
        <tr><td><b>Non biowaste collection method </b></td><td><?=isset($model->fkNonBioWasteCollectionMethod->name)?$model->fkNonBioWasteCollectionMethod->name:null?></td></tr>
        </table>
        </div>
      </div>
       <?php elseif($model->building_type_id==9):?>
       <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 profile">
        <div class="panel panel-default" style="width: 544px;">
        <div class="panel-heading"><b>Auditorium Details</b></div>
        <title><?= Html::encode($this->title) ?></title>
          <table>
        <tr><td><b>Auditorium Name</b></td><td><?=$model->building_name?></td></tr>
        <tr><td><b>Address</b></td><td><?=$model->address?></td></tr>
        <tr><td><b> Owner Name</b></td><td><?=$model->lead_person_name?></td></tr>
         <tr><td><b>Phone Number</b></td><td><?=$model->lead_person_phone?></td></tr>
          <tr><td><b>Door Status</b></td><td><b><font size="24"><?=$status?></font></b></td></tr>
         <tr><td><b>Seating</b></td><td><?=$model->seating_capacity?></td></tr>
         <tr><td><b>Monthly Booking</b></td><td><?=$model->monthly_booking_count?></td></tr>
          <tr><td><b>Green protocol System Implemented </b></td><td><?=$greenProtocol?></td></tr>
        </table>
        </div>
      </div>
       <?php elseif($model->building_type_id==10):?>
      <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 profile">
        <div class="panel panel-default" style="width: 544px;">
        <div class="panel-heading"><b>Market Details</b></div>
        <title><?= Html::encode($this->title) ?></title>
          <table>
        <tr><td><b>Market Name</b></td><td><?=$model->building_name?></td></tr>
        <tr><td><b>Address</b></td><td><?=$model->address?></td></tr>
        <tr><td><b> Visitors Count</b></td><td><?=$model->market_visiters_count?></td></tr>
        <tr><td><b>Green protocol System Implemented </b></td><td><?=$greenProtocol?></td></tr>
        </table>
        </div>
      </div>
       <?php elseif($model->building_type_id==5):?>
      <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 profile">
        <div class="panel panel-default" style="width: 544px;">
        <div class="panel-heading"><b>Hospital Details</b></div>
        <title><?= Html::encode($this->title) ?></title>
          <table>
        <tr><td><b>Hospital Name</b></td><td><?=$model->building_name?></td></tr>
        <tr><td><b>Hospital Type</b></td><td><?=$model->fkOfficeType?$model->fkOfficeType:''?></td></tr>
        <tr><td><b>Owner Name</b></td><td><?=$model->lead_person_name?></td></tr>
        <tr><td><b>Owner Phone</b></td><td><?=$model->lead_person_phone?></td></tr>
        <tr><td><b>Building Owner Name</b></td><td><?=$model->building_owner_name?></td></tr>
         <tr><td><b>Building Owner Phone</b></td><td><?=$model->building_owner_phone?></td></tr>
        <tr><td><b>Employee Count </b></td><td><?=$model->employee_count?></td></tr>
        <tr><td><b>Contact Person </b></td><td><?=$model->office_contact_person?></td></tr>
        <tr><td><b>Contact Person Designation </b></td><td><?=$model->office_contact_person_designation?></td></tr>
         <tr><td><b>Phone Number</b></td><td><?=$model->lead_person_phone?></td></tr>
        <tr><td><b>Green protocol System Implemented </b></td><td><?=$greenProtocol?></td></tr>
         <tr><td><b>Door Status</b></td><td><b><font size="24"><?=$status?></font></b></td></tr>
        <tr><td><b>Bed Count</b></td><td><?=$model->fkBuildingSubType?$model->fkBuildingSubType->name:''?></td></tr>
        <tr><td><b>Bio Medical Waste Management Method</b></td><td><?=$model->fkBioMedicalWasteCollectionMethod?$model->fkBioMedicalWasteCollectionMethod->name:''?></td></tr>
        <tr><td><b>Waste Collection Interval</b></td><td><?=$model->fkWasteCollectionInterval?$model->fkWasteCollectionInterval->name:''?></td></tr>
        </table>
        </div>
      </div>
       <?php elseif($model->building_type_id==6):?>
      <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 profile">
        <div class="panel panel-default" style="width: 544px;">
        <div class="panel-heading"><b>Public Place Details</b></div>
        <title><?= Html::encode($this->title) ?></title>
          <table>
        <tr><td><b>Public Name</b></td><td><?=$model->building_name?></td></tr>
        <tr><td><b>Address</b></td><td><?=$model->address?></td></tr>
        <tr><td><b>Area</b></td><td><?=$model->public_place_area?></td></tr>
        <tr><td><b>Public Place Type</b></td><td><?=$model->fkPublicPlaceType?$model->fkPublicPlaceType->name:''?></td></tr>
        <tr><td><b>Public Gathering Method</b></td><td><?=$model->fkPublicGatheringMethod?$model->fkPublicGatheringMethod->name:''?></td></tr>
        <tr><td><b>Programmes Happening</b></td><td><?=$programmes?></td></tr>
        <tr><td><b>Green protocol System Implemented </b></td><td><?=$greenProtocol?></td></tr>
        <tr><td><b>Has Public Toilet</b></td><td><?=$hasPublicToilet?></td></tr>
        <tr><td><b>Public Toilet Count Men</b></td><td><?=$model->public_toilet_count_men?></td></tr>
        <tr><td><b>Public Toilet Count Women</b></td><td><?=$model->public_toilet_count_women?></td></tr>
        <tr><td><b>Public Toilet Count </b></td><td><?=$model->public_toilet_count?></td></tr>
        </table>
        </div>
      </div>
       <?php elseif($model->building_type_id==11):?>
      <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 profile">
        <div class="panel panel-default" style="width: 544px;">
        <div class="panel-heading"><b>Religious Institution</b></div>
        <title><?= Html::encode($this->title) ?></title>
          <table>
        <tr><td><b>Public Name</b></td><td><?=$model->building_name?></td></tr>
        <tr><td><b>Address</b></td><td><?=$model->address?></td></tr>
        <tr><td><b>Has Toilet</b></td><td><?=$hasPublicToilet?></td></tr>
        <tr><td><b>Toilet Count Men</b></td><td><?=$model->public_toilet_count_men?></td></tr>
        <tr><td><b>Toilet Count Women</b></td><td><?=$model->public_toilet_count_women?></td></tr>
        <tr><td><b>Toilet Count </b></td><td><?=$model->public_toilet_count?></td></tr>
        </table>
        </div>
      </div>
       <?php endif;?>
                 <div class = 'col-md-6' style="overflow:hidden !important">
        <h4>Customer Location</h4>
         <iframe style="
    width: 100%;
    height: 330px;
" src = "https://maps.google.com/maps?q=<?=$latCustomer?>,<?=$lngCustomer?>&hl=es;z=14&output=embed"></iframe>
            </div>
  </div>
</div>

<style>
  td, th {
    padding: 10px;
}
</style>
