<?php

$name = $model->name;
?>
<tr class="footable-even  " data-key="9">
	<td style="width: 50px"><?=$index?></td>
	<td style="width: 200px"><?=$name?></td>
	<td style="width: 50px"><a onclick="ConfirmDelete(function(){
		deleteItem('/demo1/wb/backend/web/dictionary/delete-word-translation?id=<?=$model->id?>','#Pjax-page-set-add-translation',1,function() {});})" class="btn btn-sm btn-icon btn-pure btn-outline delete-row-btn"><i aria-hidden="true" class="ti-close"></i></a></td>
</tr>