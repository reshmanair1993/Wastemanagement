<?php

$name = $model->getServiceName($model->service_id);
$category = $model->getCategoryName($model->residence_category_id);
$type ='--';
if($model->payment_collection_type==1)
{
	$type = 'Subscription';
}
elseif($model->payment_collection_type==2)
{
	$type = 'Collection';
}
?>
<tr class="footable-even  " data-key="9">
	<td style="width: 50px"><?=$index?></td>
	<td style="width: 200px"><?=$name?></td>
	<td style="width: 200px"><?=$category?></td>
	<td style="width: 200px"><?=$type?></td>
	<td style="width: 200px"><?=$model->amount?></td>
	<td style="width: 50px">
	<?php $deleteUrl = Yii::$app->urlManager->createUrl(['lsgis/delete-fee','id'=>$model->id]);?>
	<a onclick="ConfirmDelete(function(){
		deleteItem('<?=$deleteUrl?>','#Pjax-add-lsgi-service-fee',1,function() {});})" class="btn btn-sm btn-icon btn-pure btn-outline delete-row-btn"><i aria-hidden="true" class="ti-close"></i></a></td>
</tr>