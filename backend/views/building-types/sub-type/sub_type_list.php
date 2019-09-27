<?php

$name = $model->name;
?>
<tr class="footable-even  " data-key="9">
	<td style="width: 50px"><?=$index?></td>
	<td style="width: 200px"><?=$name?></td>
	<td style="width: 50px">
	<?php $deleteUrl = Yii::$app->urlManager->createUrl(['building-types/delete-sub-type','id'=>$model->id]);?>
	<a onclick="ConfirmDelete(function(){
		deleteItem('<?=$deleteUrl?>','#Pjax-add-sub-type',1,function() {});})" class="btn btn-sm btn-icon btn-pure btn-outline delete-row-btn"><i aria-hidden="true" class="ti-close"></i></a></td>
</tr>