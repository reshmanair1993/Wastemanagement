<?php

$name = $model->getWardName();
?>
<tr class="footable-even  " data-key="9">
	<td style="width: 50px"><?=$index?></td>
	<td style="width: 200px"><?=$name?></td>
	<td style="width: 50px"><a onclick="ConfirmDelete(function(){
		deleteItem('/wastemanagement/backend/web/account/delete-ward?id=<?=$model->id?>','#Pjax-add-account-ward',1,function() {});})" class="btn btn-sm btn-icon btn-pure btn-outline delete-row-btn"><i aria-hidden="true" class="ti-close"></i></a></td>
</tr>