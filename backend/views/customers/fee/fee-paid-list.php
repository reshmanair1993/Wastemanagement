<?php

$name = $model->getServiceName($model->payment_request_id);

// $amountPaid = $model->getAmountPaid($model->payment_request_id);
?>
<tr class="footable-even  " data-key="9">
	<td style="width: 50px"><?=$index?></td>
	<td style="width: 200px"><?=$name?></td>
	<td style="width: 200px"><?=$model->amount?></td>
	<td style="width: 200px"><?=$model->fkAccount->fkPerson->first_name?></td>
	<td style="width: 200px"><?=$model->paid_at?></td>
	<!-- <td style="width: 50px"><a onclick="ConfirmDelete(function(){
		deleteItem('/wastemanagement/backend/web/customers/delete-fee?id=<?=$model->id?>','#Pjax-add-customer-fee',1,function() {});})" class="btn btn-sm btn-icon btn-pure btn-outline delete-row-btn"><i aria-hidden="true" class="ti-close"></i></a></td> -->
</tr>
