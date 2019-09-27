<?php
use yii\widgets\Pjax;
use yii\widgets\ActiveForm;
use backend\models\Image;
use backend\models\Customer;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ListView;
use yii\web\View;
$modelImage = new Image;
	Pjax::begin(['timeout' => 50000 ,'enablePushState' => false, 'id' =>'Pjax-add-service-enabler','options'=>['data-loader'=>'.preloader']]);
	$form = ActiveForm::begin(['action' => ['add-service-enabler','id'=>$model->id],'options' => ['','data-pjax' => true,'class' => 'form-horizontal form-material','enableAjaxValidation' => false,'enableClientValidation'=>true]]);
	?>
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 add-tip-form" style="background: #f9f9f9; border: 1px solid #eee; padding-top: 10px; padding-bottom: 10px;">
	 	<div class="col-sm-6 col-xs-6">
            <div class="form-group">
                <div class="col-sm-6 col-xs-6">
                   <?php
        //            $obj = new Customer;
				    // $arrFields = array_keys($obj->attributes);
				   $arrFields = $modelServiceEnablerSettings->getFields();
                   echo $form->field($modelServiceEnablerSettings, 'customer_field')->dropDownList($arrFields, ['prompt' => 'Select from the list','class'=>'form-control form-control-line field_type','id'=>'customer_field'])->label('Fields')?>
                </div>
            </div>
        </div>
       <div class="col-sm-6 col-xs-6">
            <div class="form-group">
                <div class="col-sm-6 col-xs-6">
                   <?php
                    $list = [1=>'Yes',0=>'No'];
                   echo $form->field($modelServiceEnablerSettings, 'customer_field_value')->dropDownList($list, ['prompt' => 'Select from the list','class'=>'form-control form-control-line','id'=>'customer_field_value'])->label('Field Value')?>
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
	<?php if($serviceEnablerSettingsDataProvider->getCount() > 0): ?>
		<thead>
			<tr class="footable-sortable">
				<th>#</th>
				<th>Customer Field</th>
				<th>Customer Field Value</th>
				<th><a href="" data-sort="status">Delete</a></th>
			</tr>
			<tr id="w1-filters" class="hidden">
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
		    'dataProvider' => $serviceEnablerSettingsDataProvider,
		    'options' => [
		        'tag' => 'div',
		        'class' => 'list-wrapper',
		        'id' => 'list-wrapper',
		    ],
		    'layout' => "{items}",
		    'itemView' => function ($model, $key, $index, $widget) {
		    	return $this->render('service-enabler-list', [
		    		'model' => $model,
		    		'index' => $index+1,
		    	]);
		    },
		]);
	?>
	</tbody>
 </table>
 <?php Pjax::end(); ?>
 <?php
 $this->registerJs("
    $('#customer_field').on('change',function(){
            var cat = $('#customer_field').val();
            if(cat){
                $.ajax({ 
                    url:'details-ajax',
                    data:{cat:cat},
                    method:'POST',
                    success: function(data) {
                        var target = $('#customer_field_value');
                        target.empty();
                        // target.append('<option value='' >Select from the list</option>');
                        $.each(data , function(key , value){
                            var input = '<option value='+key+' >'+value+'</option>';
                            target.append(input);
                        })
                    }
                });
            }
        })
 ",View::POS_END);?>