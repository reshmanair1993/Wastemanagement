<?php
use yii\widgets\Pjax;
use yii\widgets\ActiveForm;
use backend\models\Ward;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ListView;

	Pjax::begin(['timeout' => 50000 ,'enablePushState' => false, 'id' =>'Pjax-add-complaints-count','options'=>['data-loader'=>'.preloader']]);
	$form = ActiveForm::begin(['action' => ['add-complaints-count','id'=>$model->id],'options' => ['','data-pjax' => true,'class' => 'form-horizontal form-material','enableAjaxValidation' => false,'enableClientValidation'=>true]]);
	?>
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 add-tip-form" style="background: #f9f9f9; border: 1px solid #eee; padding-top: 10px; padding-bottom: 10px;">
	     <div class="row">
         <div class="col-sm-4 col-xs-4">
            <div class="form-group">
                <div class="col-sm-4 col-xs-4">
                    <?=$form->field($modelEvaluationConfigComplaintsCount, 'start_value_count')->textInput(['maxlength' => true,'class'=>'form-control form-control-line']);?>
                </div>
            </div>
        </div>
        <div class="col-sm-4 col-xs-4">
            <div class="form-group">
                <div class="col-sm-4 col-xs-4">
                    <?=$form->field($modelEvaluationConfigComplaintsCount, 'end_value_count')->textInput(['maxlength' => true,'class'=>'form-control form-control-line']);?>
                </div>
            </div>
        </div>
        <div class="col-sm-4 col-xs-4">
            <div class="form-group">
                <div class="col-sm-4 col-xs-4">
                    <?=$form->field($modelEvaluationConfigComplaintsCount, 'performance_point')->textInput(['maxlength' => true,'class'=>'form-control form-control-line']);?>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
	<div class="col-sm-6 col-xs-6">
            <div class="form-group">
                <div class="col-sm-6 col-xs-6">
	<?= Html::submitButton(Yii::t('app', 'Add'), ['class' => 'btn btn-success pull-right']);
	?>
	</div>
	</div>
	</div>
	</div>
	<?php
	 ActiveForm::end();
	?>
	</div>
	<table id="demo-foo-addrow" class="table table-hover footable-loaded footable">
	<?php if($evaluationConfigComplaintsCountDataProvider->getCount() > 0): ?>
		<thead>
			<tr class="footable-sortable">
				<th>#</th>
				<th>Start Value</th>
				<th>End Value</th>
				<th>Point</th>
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
		    'dataProvider' => $evaluationConfigComplaintsCountDataProvider,
		    'options' => [
		        'tag' => 'div',
		        'class' => 'list-wrapper',
		        'id' => 'list-wrapper',
		    ],
		    'layout' => "{items}",
		    'itemView' => function ($model, $key, $index, $widget) {
		    	return $this->render('complaints-count-list', [
		    		'model' => $model,
		    		'index' => $index+1,
		    	]);
		    },
		]);
	?>
	</tbody>
 </table>
 <?php Pjax::end(); ?>