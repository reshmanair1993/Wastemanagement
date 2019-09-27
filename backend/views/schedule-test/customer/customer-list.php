<?php
use backend\models\Customer;
$name = isset($model->fkAccount->fkCustomer->lead_person_name)?$model->fkAccount->fkCustomer->lead_person_name:null;
$customer_id = Customer::getFormattedCustomerId($model->fkAccount->fkCustomer->id);
$building_name = isset($model->fkAccount->fkCustomer->fkBuildingType->name)?$model->fkAccount->fkCustomer->fkBuildingType->name:null;
$building_number = isset($model->fkAccount->fkCustomer->building_number)?$model->fkAccount->fkCustomer->building_number:null;
$association_number = isset($model->fkAccount->fkCustomer->association_number)?$model->fkAccount->fkCustomer->association_number:null;
$address = isset($model->fkAccount->fkCustomer->address)?$model->fkAccount->fkCustomer->address:null;
$association = isset($model->fkAccount->fkCustomer->fkAssociation->name)?$model->fkAccount->fkCustomer->fkAssociation->name:null;

// $amountPaid = $model->getAmountPaid($model->payment_request_id);
?>
<tr class="footable-even  " data-key="9">
	<td style="width: 50px"><?=$index?></td>
	<td style="width: 200px"><?=$name?></td>
	<td style="width: 200px"><?=$customer_id?></td>
	<td style="width: 200px"><?=$building_name?></td>
	<td style="width: 200px"><?=$building_number?></td>
	<td style="width: 200px"><?=$address?></td>
	<td style="width: 200px"><?=$association?></td>
	<td style="width: 200px"><?=$association_number?></td>
</tr>
