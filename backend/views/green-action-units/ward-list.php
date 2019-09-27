<?php

$name = $model->getWardName();
if($model->fkGreenActionUnit->residence_category_id==3):
$services = $model->getServices($model->service_id);
endif;
?>
<tr class="footable-even  " data-key="9">
	<td style="width: 50px"><?=$index?></td>
	<td style="width: 200px"><?=$name?></td>
	<?php if($model->fkGreenActionUnit->residence_category_id==3):?>
	<td style="width: 200px"><?=$services?></td>
<?php endif;?>
	<td style="width: 50px">
<?php $deleteUrl = Yii::$app->urlManager->createUrl(['green-action-units/delete-ward','id'=>$model->id]);?>
	<a onclick="ConfirmDelete(function(){
		deleteItem('<?=$deleteUrl?>','#Pjax-add-unit-ward',1,function() {});})" class="btn btn-sm btn-icon btn-pure btn-outline delete-row-btn"><i aria-hidden="true" class="ti-close"></i></a></td>
</tr>