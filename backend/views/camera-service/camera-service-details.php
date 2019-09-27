<?php

    use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use backend\models\WasteCategory;
use yii\web\View;


    $lat = isset($modelCameraServiceAssignment->lat_update_from)?$modelCameraServiceAssignment->lat_update_from:'';
    $lng = isset($modelCameraServiceAssignment->lng_update_from)?$modelCameraServiceAssignment->lng_update_from:'';
    $camera = $modelCameraServiceAssignment->fkCamera->name?$modelCameraServiceAssignment->fkCamera->name:'';
    $technician = $modelCameraServiceAssignment->fkTechnician->first_name?$modelCameraServiceAssignment->fkTechnician->first_name:'';
    if($modelCameraServiceAssignment->date){
      $date = Yii::$app->formatter->asTime($modelCameraServiceAssignment->date, 'dd-MM-yyyy hh:mm:ss');
    }
    else {
      $date = 'Nil';
    }
    $status = $modelCameraServiceAssignment->fkStatus->value?$modelCameraServiceAssignment->fkStatus->value:'';

//     $lng =$model->fkServiceAssignment?$model->fkServiceAssignment->lng_updated_from:'';
//     $latCustomer = $model->fkCustomer?$model->fkCustomer->lat:'';
//     $lngCustomer =$model->fkCustomer?$model->fkCustomer->lng:'';
// $link = "/wastemanagement/backend/web/customers/view-details?id=".$model->fkCustomer->id;
// $status = '';
// if($model->fkServiceAssignment)
// {
//   if($model->fkServiceAssignment->door_status==1)
//     $status = 'Open';
//   else
//     $status = ' Closed';
// }
// $marked = $model->marked_rating_value?$model->marked_rating_value:0;
// $total = $model->total_rating_value?$model->total_rating_value:0;

?>
<div class="row">
  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <div class = 'col-md-6' style="overflow:hidden !important">
      <div class="panel panel-default" >
      <div class="panel-heading"><b>Service Details</b></div>
      <title><?= Html::encode($this->title) ?></title>
      <table>
      <tr><td><b>Camera</b></td><td><?=$camera?></td></tr>
      <tr><td><b>Technician</b></td><td><?=$technician?></td></tr>
      <tr><td><b>Date</b></td><td><?=$date?></td></tr>
      <tr><td><b>Service Status</b></td><td><?=$status?></td></tr>
      </table>
      </div>
    </div>
    <div class = 'col-md-6' style="overflow:hidden !important">
          <h4>Marked Location</h4>
           <iframe style="
      width: 100%;
      height: 330px;
      " src = "https://maps.google.com/maps?q=<?=$lat?>,<?=$lng?>&hl=es;z=14&output=embed"></iframe>
    </div>
  </div>
</div>
 <style>
  td, th {
    padding: 10px;
}
</style>
