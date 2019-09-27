<?php 
$customerField = $model->getCustomerField($model->customer_field);
$customerFieldValue = $model->getCustomerFieldValue($model->customer_field,$model->customer_field_value);
?>
<tr class="footable-even  " data-key="9">
	<td style="width: 50px"><?=$index?></td>
	<td style="width: 200px"><?=$customerField?></td>
	<td style="width: 200px"><?=$customerFieldValue?></td>
	<td style="width: 50px">
	<?php $deleteUrl = Yii::$app->urlManager->createUrl(['services/delete-service-enabler','id'=>$model->id]);?>
	<a onclick="ConfirmDelete(function(){
		deleteItem('<?=$deleteUrl?>','#Pjax-add-service-enabler',1,function() {});})" class="btn btn-sm btn-icon btn-pure btn-outline delete-row-btn"><i aria-hidden="true" class="ti-close"></i></a></td>
</tr>