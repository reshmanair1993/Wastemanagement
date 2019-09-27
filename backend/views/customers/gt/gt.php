<?php
use yii\widgets\Pjax;
use yii\widgets\ActiveForm;
use backend\models\Account;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ListView;
	Pjax::begin(['timeout' => 50000 ,'enablePushState' => false, 'id' =>'Pjax-add-gt','options'=>['data-loader'=>'.preloader']]);
	$form = ActiveForm::begin(['action' => ['add-gt','id'=>$model->id],'options' => ['','data-pjax' => true,'class' => 'form-horizontal form-material','enableAjaxValidation' => false,'enableClientValidation'=>true]]);
	?>
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 add-tip-form" style="background: #fff; border: 1px solid #eee; padding-top: 10px; padding-bottom: 10px; margin-left: -28px;width: 1217px;">
	 <div class="col-sm-6 col-xs-6">
            <div class="form-group">
                <div class="col-sm-6 col-xs-6">
                    <?php 
                      $gt= $model->getGtList($model->ward_id,$modelAccount->id);
                    // $gt= $model->getGt($model->ward_id);
                    $listData=ArrayHelper::map($gt, 'id', 'first_name');
                    echo $form->field($modelAccountAuthority, 'account_id_gt')->dropDownList($listData, ['prompt' => 'Select from the list','class'=>'form-control form-control-line'])->label('Green Technician')?>
                </div>
            </div>
        </div>
	<div class="col-lg-1 col-md-1 col-sm-6 col-xs-12"></div>
	<div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
	<?= Html::submitButton(Yii::t('app', 'Add'), ['class' => 'btn btn-success pull-right']);
	?>
	</div>
	<?php
	 ActiveForm::end();
	?>
	</div>
	<table id="demo-foo-addrow" class="table table-hover footable-loaded footable">
	<?php if($authorityDataProvider->getCount() > 0): ?>
		<thead>
			<tr class="footable-sortable">
				<th>#</th>
				<th>Green Technician</th>
				<th><a href="" data-sort="status">Delete</a></th>
			</tr>
			<tr id="w1-filters" class="hidden">
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td><input type="text" class="form-control" name="Audio[status]"></td>
			</tr>
		</thead>
	<?php endif; ?>
		<tbody>
	<?php
		echo ListView::widget([
		    'dataProvider' => $authorityDataProvider,
		    'options' => [
		        'tag' => 'div',
		        'class' => 'list-wrapper',
		        'id' => 'list-wrapper',
		    ],
		    'layout' => "{items}",
		    'itemView' => function ($model, $key, $index, $widget) {
		    	return $this->render('gt_list', [
		    		'model' => $model,
		    		'index' => $index+1,
		    	]);
		    },
		]);
	?>
	</tbody>
 </table>
 <?php Pjax::end(); ?>