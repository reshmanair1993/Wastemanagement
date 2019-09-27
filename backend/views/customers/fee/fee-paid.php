<?php
use yii\widgets\Pjax;
use yii\widgets\ActiveForm;
use backend\models\Ward;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ListView;

	Pjax::begin(['timeout' => 50000 ,'enablePushState' => false, 'id' =>'Pjax-add-customer-fee','options'=>['data-loader'=>'.preloader']]);
	?>
	<table id="demo-foo-addrow" class="table table-hover footable-loaded footable">
	<?php if($dataProvider->getCount() > 0): 
	$totalAmount = $modelAccountFee->getTotalAmount($model->id);
	$paidAmount = $modelAccountFee->getPaidAmount($model->id);?>
	<style type="text/css">.white-box{overflow:hidden;}</style>
	<div class="row att-today-count">
  <div class="col-md-12">
    <div class="white-box">
      <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
        <h4>Total Amount<span class="btn btn-primary"><?=$totalAmount?></span></h4>
      </div>
      <div class="col-lg-1 col-md-4 col-sm-4 col-xs-12">
       
      </div>
      <div class="col-lg-2 col-md-4 col-sm-4 col-xs-12">
        <h4>Paid<span class="btn btn-success"><?=$paidAmount?></span></h4>
      </div>
       <div class="col-lg-2 col-md-4 col-sm-4 col-xs-12">
       
      </div>
      <div class="col-lg-2 col-md-4 col-sm-4 col-xs-12">
        <h4>Pending<span class="btn btn-danger"><?=$totalAmount-$paidAmount?></span></h4>
      </div>
       <div class="col-lg-2 col-md-4 col-sm-4 col-xs-12">
       
      </div>
     </div>
  </div>
</div>
		<thead>
	<!-- <tr>
		<td>Total Amount</td>
		<td><?=$totalAmount?></td>
		<td>Amount Paid</td>
		<td><?=$totalAmount?></td>
		<td>Amount Pending</td>
		<td><?=$totalAmount?></td>
	</tr> -->
			<tr class="footable-sortable">
				<th>#</th>
				<th>service</th>
				<th>Amount Paid</th>
				<th>Green Technician</th>
				<th>Date</th>
				<!-- <th><a href="" data-sort="status">Delete</a></th> -->
			</tr>
			<tr id="w1-filters" class="hidden">
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td><input type="text" class="form-control" name="Audio[status]"></td>
			</tr>
		</thead>
	<?php endif; ?>
		<tbody>
	<?php
		echo ListView::widget([
		    'dataProvider' => $dataProvider,
		    'options' => [
		        'tag' => 'div',
		        'class' => 'list-wrapper',
		        'id' => 'list-wrapper',
		    ],
		    'layout' => "{items}",
		    'itemView' => function ($model, $key, $index, $widget) {
		    	return $this->render('fee-paid-list', [
		    		'model' => $model,
		    		'index' => $index+1,
		    	]);
		    },
		]);
	?>
	</tbody>
 </table>
 <?php Pjax::end(); ?>