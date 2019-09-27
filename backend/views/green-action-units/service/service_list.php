<?php

$name = $model->getWardName();
?>
<tr class="footable-even  " data-key="9">
	<td style="width: 50px"><?=$index?></td>
	<td style="width: 200px"><?=$name?></td>
	<td style="width: 50px">
<?php $deleteUrl = Yii::$app->urlManager->createUrl(['green-action-units/delete-service','id'=>$model->id]);?>
	<a onclick="ConfirmDelete(function(){
		deleteItem('<?=$deleteUrl?>','#Pjax-add-unit-service',1,function() {});})" class="btn btn-sm btn-icon btn-pure btn-outline delete-row-btn"><i aria-hidden="true" class="ti-close"></i></a></td>
</tr>