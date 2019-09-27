
<?php
use yii\helpers\Html;
use yii\helpers\Url;
$isConfirm = isset($confirmationCallback)?$confirmationCallback:null;
$title = isset($title)?$title:'Alert';
$type = isset($type)?$type:'success';
$message = isset($message)?$message:'Awesome';
$title = Html::encode(trim($title));
$message = Html::encode(trim($message));
$title =  $title;
$message =  $message; //but need to escape apppstrope
if(!$isConfirm)
	$this->registerJs("
		closeAllModals();
		swal({title:'$title',text: '$message', type:'$type'});
	");
else
	$this->registerJs("
		closeAllModals();
		swal({title:'$title',text: '$message', type:'$type'},$isConfirm);
	");

?>
