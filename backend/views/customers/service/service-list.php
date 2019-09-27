<?php

$name = $model->getServiceName($model->service_id);
$interval = isset($model->collection_interval)?$model->fkCollectionInterval->name:'';
// $amountPaid = $model->getAmountPaid($model->payment_request_id);
?>
<tr class="footable-even  " data-key="9">
	<td style="width: 50px"><?=$index?></td>
	<td style="width: 200px"><?=$name?></td>
	<td style="width: 200px"><?=$interval?></td>
	 <td style="width: 50px">
	 <?php $deleteUrl = Yii::$app->urlManager->createUrl(['customers/delete-service','id'=>$model->id]);?>
	 <a onclick="ConfirmDelete(function(){
		deleteItem('<?=$deleteUrl?>','#Pjax-add-customer-service',1,function() {});})" class="btn btn-sm btn-icon btn-pure btn-outline delete-row-btn"><i aria-hidden="true" class="ti-close"></i></a></td>
</tr>
