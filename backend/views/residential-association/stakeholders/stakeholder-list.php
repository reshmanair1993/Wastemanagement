<?php

$name = $model->name;
$phone = $model->phone;
$position = $model->position;
?>
<tr class="footable-even  " data-key="9">
	<td style="width: 50px"><?=$index?></td>
	<td style="width: 200px"><?=$name?></td>
	<td style="width: 200px"><?=$phone?></td>
	<td style="width: 200px"><?=$position?></td>
	<td><div class="dict-cat-img"><img src="<?=$model->getProfileUrl()?>" /></div></td>
	<td> <div class="col-lg-2 col-md-4 col-sm-4 col-xs-12">
            <i class="fa fa-pencil" aria-hidden="true" data-toggle="modal" data-target="#myModal1-<?=$model->id?>"></i>
        </div></td>
	<td style="width: 50px">
	<?php $deleteUrl = Yii::$app->urlManager->createUrl(['residential-association/delete-stakeholder','id'=>$model->id]);?>
	<a onclick="ConfirmDelete(function(){
		deleteItem('<?=$deleteUrl?>','#Pjax-add-stakeholder',1,function() {});})" class="btn btn-sm btn-icon btn-pure btn-outline delete-row-btn"><i aria-hidden="true" class="ti-close"></i></a></td>
</tr>
<div aria-hidden="true" aria-labelledby="myModalLabel" class="modal fade add-word-modal" id="myModal1-<?=$model->id?>" role="dialog" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button aria-hidden="true" class="close" data-dismiss="modal" class="click" id="clicks" type="button">×</button>
        <h4 class="modal-title" id="myModalLabel">Update Stakeholder</h4>
      </div>
      <div class="modal-body">
        <div class="row">
        <?php 
    echo
    $this->render('edit-stakeholder',[
            'modelResidentialAssociationStakeholders'=> $model,
            ]);?>
      </div>
      </div>

    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>