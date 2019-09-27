<?php
use yii\helpers\Url;
$resultQuantity = null;
$resultQuality = null;
if($model->ask_waste_quantity==1)
{
  $resultQuantity = 'Yes';
}
if($model->ask_waste_quantity==0)
{
  $resultQuantity = 'No';
}
if($model->ask_waste_quality==1)
{
  $resultQuality = 'Yes';
}
if($model->ask_waste_quality==0)
{
  $resultQuality = 'No';
}

?>
<tr class="footable-even  " data-key="9">
  <td style="width: 50px"><?=$index?></td>
  <td style="width: 200px"><?=$model->value?></td>
  <td style="width: 200px"><?=$model->sort_order?></td>
  <td style="width: 200px"><?=$resultQuantity?></td>
  <td style="width: 200px"><?=$resultQuality?></td>
  <td> <div class="col-lg-2 col-md-4 col-sm-4 col-xs-12">
            <i class="fa fa-pencil" aria-hidden="true" data-toggle="modal" data-target="#myModal1-<?=$model->id?>"></i>
        </div></td>
  <td style="width: 50px">
  <?php $deleteUrl = Yii::$app->urlManager->createUrl(['services/delete-status-options','id'=>$model->id]);?>
  <a onclick="ConfirmDelete(function(){
    deleteItem('<?=$deleteUrl?>','#Pjax-add-status-options',1,function() {});})" class="btn btn-sm btn-icon btn-pure btn-outline delete-row-btn"><i aria-hidden="true" class="ti-close"></i></a></td>
</tr>
<div aria-hidden="true" aria-labelledby="myModalLabel" class="modal fade add-word-modal" id="myModal1-<?=$model->id?>" role="dialog" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button aria-hidden="true" class="close" data-dismiss="modal" class="click" id="clicks" type="button">×</button>
        <h4 class="modal-title" id="myModalLabel">Status Option</h4>
      </div>
      <div class="modal-body">
        <div class="row">
        <?php 
    echo
    $this->render('edit-status-option',[
            'modelServicingStatusOption'=> $model,
            ]);?>
      </div>
      </div>

    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>