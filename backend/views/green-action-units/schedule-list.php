<?php

$name = $model->activity_name;
$service = $model->getService();
$wards = $model->fkWard?$model->fkWard->name:null;
$type = $model->getType();
$dayCount = $model->repeat_day_count;
$weekDay = $model->getWeekDay();

?>
<tr class="footable-even  " data-key="9">
	<td style="width: 50px"><?=$index?></td>

	<td style="width: 200px"><?=$service?></td>
	<td style="width: 200px"><?=$wards?></td>
	<td style="width: 200px"><?=$type?></td>
	<td style="width: 200px"><?=$weekDay?></td>
	<td style="width: 200px"><?=$model->month_day?></td>
	<td style="width: 200px"><?=$model->date?date('d-M-Y',strtotime($model->date)):null?></td>
	<td style="width: 50px"><a onclick="ConfirmDelete(function(){
		deleteItem('/development/wastemanagement/backend/web/schedule/delete-schedule?id=<?=$model->id?>','#Pjax-add-hks-schedule',1,function() {});})" class="btn btn-sm btn-icon btn-pure btn-outline delete-row-btn"><i aria-hidden="true" class="ti-close"></i></a></td>
</tr>