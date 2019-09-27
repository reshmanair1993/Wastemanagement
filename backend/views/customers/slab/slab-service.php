<?php
use yii\widgets\Pjax;
use yii\widgets\ActiveForm;
use backend\models\Service;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ListView;
use kartik\depdrop\DepDrop;
use yii\helpers\Url;

	Pjax::begin(['timeout' => 50000 ,'enablePushState' => false, 'id' =>'Pjax-add-service-slab','options'=>['data-loader'=>'.preloader']]);
	$form = ActiveForm::begin(['action' => ['add-service-slab','id'=>$model->id],'options' => ['','data-pjax' => true,'class' => 'form-horizontal form-material','enableAjaxValidation' => false,'enableClientValidation'=>true]]);
	?>
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 add-tip-form" style="background: #f9f9f9; border: 1px solid #eee; padding-top: 10px; padding-bottom: 10px;">
	<div class="row">
	 <div class="col-sm-6 col-xs-6">
            <div class="form-group">
                <div class="col-sm-6 col-xs-6">
                <?php $service= Service::find()
                ->leftjoin('account_service','account_service.service_id=service.id')
                ->where(['account_service.status'=> 1])
                ->andWhere(['service.status'=> 1])
                ->andWhere(['service.ask_waste_quantity'=> 1])
                ->andWhere(['account_service.account_id'=> $modelAccount->id])
                ->all();
                    $listData=ArrayHelper::map($service, 'id', 'name');
                    echo $form->field($modelAccountSlabService, 'service_id')->dropDownList($listData, ['prompt' => 'Select from the list','class'=>'form-control form-control-line','id'=>'service-id'])->label('Service')?>
                </div>
            </div>
        </div>
         <div class="col-sm-6 col-xs-6">
            <div class="form-group">
                <div class="col-sm-6 col-xs-6">
               <?php 

                        echo $form->field($modelAccountSlabService, 'slab_id')->widget(DepDrop::classname(), [
                        'type'=>DepDrop::TYPE_SELECT2,
                        'options'=>['id'=>'slab-id','class'=>'form-control form-control-line'],
                        'select2Options'=>['pluginOptions'=>['allowClear'=>true]],
                        'pluginOptions'=>[
                           'depends'=>['service-id'],
                           'class'=>'form-control form-control-line',
                            'placeholder'=>'Select...',
                            'url'=>Url::to(['/lsgis/get-slab'])
                        ]
                    ])->label('Slab');
                    ?>
                </div>
            </div>
        </div>
        <?=$form->field($modelAccountSlabService, 'account_id_customer')->textInput(['maxlength' => true,'class'=>'form-control form-control-line','value'=>$modelAccount->id]);?>
        
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
	<?php if($slabAccountServiceDataProvider->getCount() > 0): ?>
		<thead>
			<tr class="footable-sortable">
				<th>#</th>
				<th>Service</th>
				<th>Slab Name</th>
				<!-- <th>Edit</th> -->
				<th><a href="" data-sort="status">Delete</a></th>
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
		    'dataProvider' => $slabAccountServiceDataProvider,
		    'options' => [
		        'tag' => 'div',
		        'class' => 'list-wrapper',
		        'id' => 'list-wrapper',
		    ],
		    'layout' => "{items}",
		    'itemView' => function ($model, $key, $index, $widget) {
		    	return $this->render('slab-service-list', [
		    		'model' => $model,
		    		'index' => $index+1,
		    	]);
		    },
		]);
	?>
	</tbody>
 </table>
 <?php Pjax::end(); ?>