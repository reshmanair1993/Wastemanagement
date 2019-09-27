<?php
use yii\widgets\Pjax;
use yii\widgets\ActiveForm;
use backend\models\Service;
use backend\models\Slab;
use backend\models\NonResidentialWasteCollectionInterval;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ListView;

	Pjax::begin(['timeout' => 50000 ,'enablePushState' => false, 'id' =>'Pjax-add-lsgi-service-slab-fee','options'=>['data-loader'=>'.preloader']]);
	$form = ActiveForm::begin(['action' => ['add-lsgi-service-slab-fee','id'=>$model->id],'options' => ['','data-pjax' => true,'class' => 'form-horizontal form-material','enableAjaxValidation' => false,'enableClientValidation'=>true]]);
	?>
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 add-tip-form" style="background: #f9f9f9; border: 1px solid #eee; padding-top: 10px; padding-bottom: 10px;">
	<div class="row">
	 <div class="col-sm-6 col-xs-6">
            <div class="form-group">
                <div class="col-sm-6 col-xs-6">
                <?php $service= Service::find()->where(['status'=> 1])->andWhere(['is_quantity_entering_enabled'=>1])->all();
                    $listData=ArrayHelper::map($service, 'id', 'name');
                    echo $form->field($modelLsgiServiceSlabFee, 'service_id')->dropDownList($listData, ['prompt' => 'Select from the list','class'=>'form-control form-control-line'])->label('Service')?>
                </div>
            </div>
        </div>
         <div class="col-sm-6 col-xs-6">
            <div class="form-group">
                <div class="col-sm-6 col-xs-6">
                <?php $slab= Slab::find()->where(['status'=>1])->all();
              	$listData=ArrayHelper::map($slab, 'id', 'name');
                echo $form->field($modelLsgiServiceSlabFee, 'slab_id')->dropDownList($listData, ['prompt' => 'Select from the list','class'=>'form-control form-control-line'])->label('Slab')?>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xs-6">
            <div class="form-group">
                <div class="col-sm-6 col-xs-6">
                <?php $waste= NonResidentialWasteCollectionInterval::find()->where(['status'=>1])->all();
              	$listData=ArrayHelper::map($waste, 'id', 'name');
                echo $form->field($modelLsgiServiceSlabFee, 'collection_interval')->dropDownList($listData, ['prompt' => 'Select from the list','class'=>'form-control form-control-line'])->label('Collection Interval')?>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xs-6">
            <div class="form-group">
                <div class="col-sm-6 col-xs-6">
                    <?=$form->field($modelLsgiServiceSlabFee, 'start_value')->textInput(['maxlength' => true,'class'=>'form-control form-control-line']);?>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xs-6">
            <div class="form-group">
                <div class="col-sm-6 col-xs-6">
                    <?=$form->field($modelLsgiServiceSlabFee, 'end_value')->textInput(['maxlength' => true,'class'=>'form-control form-control-line']);?>
                </div>
            </div>
        </div>
        </div>
    <div class="row">
    <div class="col-sm-6 col-xs-6">
            <div class="form-group">
                <div class="col-sm-6 col-xs-6">
                    <?=$form->field($modelLsgiServiceSlabFee, 'amount')->textInput(['maxlength' => true,'class'=>'form-control form-control-line']);?>
                </div>
            </div>
            </div>
        <div class="col-sm-6 col-xs-6">
            <div class="form-group">
                <div class="col-sm-6 col-xs-6">
                    <?=$form->field($modelLsgiServiceSlabFee, 'corporation_percentage')->textInput(['maxlength' => true,'class'=>'form-control form-control-line']);?>
                </div>
            </div>
        </div>
        </div>
        <div class="row">
         <div class="col-sm-6 col-xs-6">
            <div class="form-group">
                <div class="col-sm-6 col-xs-6">
                    <?=$form->field($modelLsgiServiceSlabFee, 'service_provider_percentage')->textInput(['maxlength' => true,'class'=>'form-control form-control-line']);?>
                </div>
            </div>
        </div>
         <div class="col-sm-6 col-xs-6">
                <div class="form-group">
                    <div class="col-sm-6 col-xs-6">
                      <?= $form->field($modelLsgiServiceSlabFee, 'use_for_per_kg_rate')->checkbox(); ?>
                    </div>
                </div>
            </div>
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
	<?php if($lsgiServiceSlabFeeDataProvider->getCount() > 0): ?>
		<thead>
			<tr class="footable-sortable">
				<th>#</th>
				<th>Service</th>
				<th>Fee</th>
                 <th>Slab</th>
                <th>Collection Interval</th>
                <th>Start Value</th>
                <th>End Value</th>
				<th>Edit</th>
				<th><a href="" data-sort="status">Delete</a></th>
			</tr>
			<tr id="w1-filters" class="hidden">
				<td>&nbsp;</td>
				<td>&nbsp;</td>
                <td>&nbsp;</td>
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
		    'dataProvider' => $lsgiServiceSlabFeeDataProvider,
		    'options' => [
		        'tag' => 'div',
		        'class' => 'list-wrapper',
		        'id' => 'list-wrapper',
		    ],
		    'layout' => "{items}",
		    'itemView' => function ($model, $key, $index, $widget) {
		    	return $this->render('lsgi-service-slab-fee-list', [
		    		'model' => $model,
		    		'index' => $index+1,
		    	]);
		    },
		]);
	?>
	</tbody>
 </table>
 <?php Pjax::end(); ?>