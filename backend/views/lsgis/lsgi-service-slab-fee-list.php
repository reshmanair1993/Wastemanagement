<?php

$name = $model->getServiceName($model->service_id);
$slab = isset($model->fkSlab->name)?$model->fkSlab->name:'';
$interval = isset($model->fkCollectionInterval->name)?$model->fkCollectionInterval->name:'';
?>
<tr class="footable-even  " data-key="9">
	<td style="width: 50px"><?=$index?></td>
	<td style="width: 200px"><?=$name?></td>
	<td style="width: 200px"><?=$model->amount?></td>
  <td style="width: 200px"><?=$slab?></td>
  <td style="width: 200px"><?=$interval?></td>
  <td style="width: 200px"><?=$model->start_value?></td>
  <td style="width: 200px"><?=$model->end_value?></td>
	 <td> <div class="col-lg-2 col-md-4 col-sm-4 col-xs-12">
            <i class="fa fa-pencil" aria-hidden="true" data-toggle="modal" data-target="#myModal-<?=$model->id?>"></i>
        </div></td>
	<td style="width: 50px">
	<?php $deleteUrl = Yii::$app->urlManager->createUrl(['lsgis/delete-slab-fee','id'=>$model->id]);?>
	<a onclick="ConfirmDelete(function(){
		deleteItem('<?=$deleteUrl?>','#Pjax-add-lsgi-service-slab-fee',1,function() {});})" class="btn btn-sm btn-icon btn-pure btn-outline delete-row-btn"><i aria-hidden="true" class="ti-close"></i></a></td>
</tr>
<div aria-hidden="true" aria-labelledby="myModalLabel" class="modal fade add-word-modal" id="myModal-<?=$model->id?>" role="dialog" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button aria-hidden="true" class="close" data-dismiss="modal" class="click" id="clicks" type="button">×</button>
        <h4 class="modal-title" id="myModalLabel">Slab Fee Settings</h4>
      </div>
      <div class="modal-body">
        <div class="row">
        <?php 
    echo
    $this->render('edit-lsgi-service-slab-fee',[
            'modelLsgiServiceSlabFee'=> $model,
            ]);?>
      </div>
      </div>

    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>