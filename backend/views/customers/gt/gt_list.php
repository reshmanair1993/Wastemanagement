<?php
use backend\models\Customer;
// print_r($model->account_id_gt);die();
 $name = $model->fkAccountGt->fkPerson->first_name;

// $amountPaid = $model->getAmountPaid($model->payment_request_id);
?>
<tr class="footable-even  " data-key="9">
	<td style="width: 50px"><?=$index?></td>
	<td style="width: 200px"><?=$name?></td>
	 <td style="width: 50px">
	 	 <?php $deleteUrl = Yii::$app->urlManager->createUrl(['customers/delete-gt','id'=>$model->id]);?>
	 <a onclick="ConfirmDelete(function(){
		deleteItem('<?=$deleteUrl?>','#Pjax-add-gt',1,function() {});})" class="btn btn-sm btn-icon btn-pure btn-outline delete-row-btn"><i aria-hidden="true" class="ti-close"></i></a></td>
</tr>
