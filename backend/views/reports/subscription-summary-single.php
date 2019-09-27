<?php
	use yii\helpers\Html;
	use backend\models\AccountService;
	use backend\models\Service;
	use yii\data\ActiveDataProvider;
	// $empId = $model->id;
	// $dateAbsent = $widget->viewParams['dateAbsent'];
	// $siteId = $widget->viewParams['siteId'];
	// $att = Attendance::checkAttendance($empId, $dateAbsent, $siteId);
	// $lastAttendanceDate = Attendance::checkLastAttendance($empId);
	 $modelService = new Service;
        $serviceDataprovider = new ActiveDataProvider(
            [
                'query'      => $modelService->getAllQuery(),
                'pagination' => false,
                'sort'       => ['defaultOrder' => ['id' => SORT_DESC]]
            ]);
	 $page = isset($_GET['page'])?$_GET['page']:1; 
	 $from   = Yii::$app->session->get('start');
     $to     = Yii::$app->session->get('end');
                       $from              = $from ? \Yii::$app->formatter->asDatetime($from, "php:Y-m-d 00:00:00") : '';
                       $to                = $to ? \Yii::$app->formatter->asDatetime($to, "php:Y-m-d 23:59:00") : '';
       // print_r($to);die();

                      $page = isset($_GET['page']) ? $_GET['page']:1;
                      
?>
<tr class="abs-col">
<?php if($page>1):
$index = ($page*50)-50+$index+1;
// elseif($page>1&&$index!=0):
// 	$index = 
else:
$index = $index+1;
endif;
?>
	<td style="width: 50px"><?=$index?></td>
	<td style="width: auto"><?=$model->name_en?></td>
	 <?php
                   if($serviceDataprovider->getModels()){
      foreach ($serviceDataprovider->getModels() as $key => $value) {
      	 $from          = Yii::$app->session->get('start');
    $to            = Yii::$app->session->get('end');
      	$count = AccountService::getCount($model->id,$from,$to,$value->id);
                  ?>
	<td style="width: auto"><?=$count?></td>
	<?php
}
}
?>
</tr>


