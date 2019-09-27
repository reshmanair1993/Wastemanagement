<?php
use yii\widgets\Pjax;
use yii\widgets\ActiveForm;
use backend\models\Image;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ListView;
$modelImage = new Image;
	Pjax::begin(['timeout' => 50000 ,'enablePushState' => false, 'id' =>'Pjax-add-status-options','options'=>['data-loader'=>'.preloader']]);
	$form = ActiveForm::begin(['action' => ['add-status-options','id'=>$model->id],'options' => ['','data-pjax' => true,'class' => 'form-horizontal form-material','enableAjaxValidation' => false,'enableClientValidation'=>true]]);
	?>
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 add-tip-form" style="background: #f9f9f9; border: 1px solid #eee; padding-top: 10px; padding-bottom: 10px;">
	 <div class="col-sm-6 col-xs-6">
            <div class="form-group">
                <div class="col-sm-6 col-xs-6">
                   <?=$form->field($modelServicingStatusOption, 'value')->textInput(['maxlength' => true,'class'=>'form-control form-control-line']);?>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xs-6">
            <div class="form-group">
                <div class="col-sm-6 col-xs-6">
                   <?=$form->field($modelServicingStatusOption, 'name_ml')->textInput(['maxlength' => true,'class'=>'form-control form-control-line']);?>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xs-6">
            <div class="form-group">
                <div class="col-sm-6 col-xs-6">
                   <?=$form->field($modelServicingStatusOption, 'sort_order')->textInput(['maxlength' => true,'class'=>'form-control form-control-line']);?>
                </div>
            </div>
        </div>
        <div class="row">
        <div class="col-sm-6 col-xs-6">
          <div class="form-group">
            <div class="col-sm-6 col-xs-6">
              <?= $form->field($modelServicingStatusOption, 'ask_waste_quality')->dropDownList([1=>'Yes',0=>'No'], ['prompt' => 'Select from the list','class'=>'form-control form-control-line'])?>
            </div>
          </div>
        </div>
        <div class="col-sm-6 col-xs-6">
          <div class="form-group">
            <div class="col-sm-6 col-xs-6">
              <?= $form->field($modelServicingStatusOption, 'ask_waste_quantity')->dropDownList([1=>'Yes',0=>'No'], ['prompt' => 'Select from the list','class'=>'form-control form-control-line'])?>
            </div>
          </div>
        </div>
    </div>
        <div class="col-sm-12 col-xs-12">
                <div class="form-group">
                 <!--  <div class="dict-cat-img">
                  <img src="<?=$model->getProfileUrl()?>" />
                </div> -->
                  <div class="col-sm-12 col-xs-6">
                    <?= $form->field($modelImage, 'uploaded_files')->fileInput(['class'=>'form-control form-control-line','placeholder' => 'Choose flag icon'])?>
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
	<?php if($dataProvider->getCount() > 0): ?>
		<thead>
			<tr class="footable-sortable">
				<th>#</th>
				<th>Status Option Name</th>
				<th>Sort Order</th>
				<th>Ask Waste Quantity</th>
				<th>Ask Waste Quality</th>
				<th>Edit</th>
				<th><a href="" data-sort="status">Delete</a></th>
			</tr>
			<tr id="w1-filters" class="hidden">
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
		    	return $this->render('status-options-list', [
		    		'model' => $model,
		    		'index' => $index+1,
		    	]);
		    },
		]);
	?>
	</tbody>
 </table>
 <?php Pjax::end(); ?>