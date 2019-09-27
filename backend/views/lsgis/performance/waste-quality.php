<?php
use yii\widgets\Pjax;
use yii\widgets\ActiveForm;
use backend\models\WasteQuality;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ListView;

	Pjax::begin(['timeout' => 50000 ,'enablePushState' => false, 'id' =>'Pjax-add-waste-quality','options'=>['data-loader'=>'.preloader']]);
	$form = ActiveForm::begin(['action' => ['add-waste-quality','id'=>$model->id],'options' => ['','data-pjax' => true,'class' => 'form-horizontal form-material','enableAjaxValidation' => false,'enableClientValidation'=>true]]);
	?>
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 add-tip-form" style="background: #f9f9f9; border: 1px solid #eee; padding-top: 10px; padding-bottom: 10px;">
	     <div class="row">
         <div class="col-sm-4 col-xs-4">
            <div class="form-group">
                <div class="col-sm-4 col-xs-4">
                    <?php $quality = WasteQuality::find()->where(['status'=>1])->all();
                    $listData=ArrayHelper::map($quality, 'id', 'name');
                    echo $form->field($modelEvaluationConfigWasteQuality, 'quality_type_id')->dropDownList($listData, ['prompt' => 'Select from the list','class'=>'form-control form-control-line'])->label('Waste Quality')?>
                </div>
            </div>
        </div>
        <div class="col-sm-4 col-xs-4">
            <div class="form-group">
                <div class="col-sm-4 col-xs-4">
                    <?=$form->field($modelEvaluationConfigWasteQuality, 'performance_point')->textInput(['maxlength' => true,'class'=>'form-control form-control-line']);?>
                </div>
            </div>
        </div>
	<div class="col-sm-4 col-xs-4">
            <div class="form-group">
                <div class="col-sm-4 col-xs-4">
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
	<?php if($evaluationConfigWasteQualityDataProvider->getCount() > 0): ?>
		<thead>
			<tr class="footable-sortable">
				<th>#</th>
				<th>Quality Type</th>
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
		    'dataProvider' => $evaluationConfigWasteQualityDataProvider,
		    'options' => [
		        'tag' => 'div',
		        'class' => 'list-wrapper',
		        'id' => 'list-wrapper',
		    ],
		    'layout' => "{items}",
		    'itemView' => function ($model, $key, $index, $widget) {
		    	return $this->render('waste-quality-list', [
		    		'model' => $model,
		    		'index' => $index+1,
		    	]);
		    },
		]);
	?>
	</tbody>
 </table>
 <?php Pjax::end(); ?>