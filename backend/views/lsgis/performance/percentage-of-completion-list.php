<?php
// print_r($model);die();
?>
<tr class="footable-even  " data-key="9">
	<td style="width: 50px"><?=$index?></td>
	<td style="width: 200px"><?=isset($model->fkService->name)?$model->fkService->name:null?></td>
	<td style="width: 200px"><?=$model->start_value_percent?></td>
	<td style="width: 200px"><?=$model->end_value_percent?></td>
	<td style="width: 200px"><?=$model->performance_point?></td>
	<td style="width: 50px">
	<?php $deleteUrl = Yii::$app->urlManager->createUrl(['lsgis/delete-percentage-of-completion','id'=>$model->id]);?>
	<a onclick="ConfirmDelete(function(){
		deleteItem('<?=$deleteUrl?>','#Pjax-add-percentage-of-completion',1,function() {});})" class="btn btn-sm btn-icon btn-pure btn-outline delete-row-btn"><i aria-hidden="true" class="ti-close"></i></a></td>
</tr>