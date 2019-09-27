<?php
use yii\widgets\Pjax;
use yii\widgets\ActiveForm;
use backend\models\Ward;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ListView;
use yii\web\View;

	Pjax::begin(['timeout' => 50000 ,'enablePushState' => false, 'id' =>'Pjax-add-lsgi-schedule','options'=>['data-loader'=>'.preloader']]);
	$form = ActiveForm::begin(['action' => ['add-lsgi-schedule','id'=>$model->id],'options' => ['','data-pjax' => true,'class' => 'form-horizontal form-material','enableAjaxValidation' => false,'enableClientValidation'=>true]]);
	?>
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 add-tip-form" style="background: #f9f9f9; border: 1px solid #eee; padding-top: 10px; padding-bottom: 10px;">
	<div class="row">
	 <div class="col-sm-6 col-xs-6">
            <div class="form-group" style="margin-left:12px;">
                <div class="col-sm-6 col-xs-6">
                    <?=$form->field($modelSchedule, 'activity_name')->textInput(['maxlength' => true,'class'=>'form-control form-control-line']);?>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xs-6">
            <div class="form-group">
                <div class="col-sm-6 col-xs-6">
        <?php $services= $modelSchedule->getServices();
                    $listData=ArrayHelper::map($services, 'id', 'name');
                    echo $form->field($modelSchedule, 'service_id')->dropDownList($listData, ['prompt' => 'Select from the list','class'=>'form-control form-control-line','id'=>'services-id'])->label('Service')?>
                  </div>
                </div>
              </div>
        </div>
        <div class="row">
        	 <div class="form-group" style="margin-left:12px;">
                <div class="col-sm-6 col-xs-6">
                <?php 
                $list = ArrayHelper::map(\backend\models\Ward::find()->where(['status'=>1])->andWhere(['lsgi_id'=>$model->id])->all(), 'id', 'name');
                echo $form->field($modelSchedule, 'ward_id')->checkboxList($list, ['class' => 'form-control type_select' ,'style'=>'height:100%;margin-left:15px;'])->label('Wards') ?>
                </div>
            </div>
             </div>
             <div class="row">
             <div class="col-sm-6 col-xs-6">
                <div class="form-group" style="margin-left:12px;">
                    <div class="col-sm-6 col-xs-6">
                      <?= $form->field($modelSchedule, 'type')->dropDownList([1=>'Weekly',2=>'Monthly',3=>'Date Wise'], ['prompt' => 'Select from the list','class'=>'form-control form-control-line configuration_type','id'=>'configuration_type','value'=>$modelSchedule->type])?>
                    </div>
                </div>
            </div>
        <section id="weekly">
            <div class="col-sm-6 col-xs-6">
                <div class="form-group">
                    <div class="col-sm-6 col-xs-6">
                      <?= $form->field($modelSchedule, 'week_day')->dropDownList([1=>'Sunday',2=>'Monday',3=>'Tuesday',4=>'Wednesday',5=>'Thursday',6=>'Friday',7=>'Saturday'], ['prompt' => 'Select from the list','class'=>'form-control form-control-line'])?>
                    </div>
                </div>
            </div>
            </section>
            <section id="mothly">
            <div class="col-sm-6 col-xs-6">
                <div class="form-group">
                    <div class="col-sm-6 col-xs-6">
                      <?= $form->field($modelSchedule, 'month_day')->dropDownList(range(1,31), ['prompt' => 'Select from the list','class'=>'form-control form-control-line'])?>
                    </div>
                </div>
            </div>
            </section>
            <section id="date-wise">
            <div class="col-sm-6 col-xs-6">
            <div class="form-group">
                <div class="col-sm-6 col-xs-6">
                    <?=$form->field($modelSchedule, 'date')->textInput(['maxlength' => true,'class'=>'form-control form-control-line datepicker']);?>
                </div>
            </div>
        </div>
        </section>
        </div>
        <div class="row">
            <div class="col-sm-6 col-xs-6">
            <div class="form-group" style="margin-left:12px;">
                <div class="col-sm-6 col-xs-6">
                    <?=$form->field($modelSchedule, 'repeat_day_count')->textInput(['maxlength' => true,'class'=>'form-control form-control-line']);?>
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
	<?php if($scheduleDataProvider->getCount() > 0): ?>
		<thead>
			<tr class="footable-sortable">
				<th>#</th>
				<th>Activity Name</th>
				<th>Service</th>
				<th>Wards</th>
				<th>Type</th>
				<th>Week Day</th>
				<th>Month Day</th>
				<th>Date</th>
				<th>Repeat Day Count</th>
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
		    'dataProvider' => $scheduleDataProvider,
		    'options' => [
		        'tag' => 'div',
		        'class' => 'list-wrapper',
		        'id' => 'list-wrapper',
		    ],
		    'layout' => "{items}",
		    'itemView' => function ($model, $key, $index, $widget) {
		    	return $this->render('schedule-list', [
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
$('.btn-behaviour-filter').on('change', function() {
 $('.search-form').submit();

 });
 $('.datepicker').datepicker({
           orientation:'top',
           format:'dd-mm-yyyy',
           autoclose:true,
           todayHighlight:true,
       });
       $(document).ready(function(){
         var val = $('#configuration_type').val();
      if(val==1)
      {
                $('#weekly').show();
                $('#mothly').hide();
                $('#date-wise').hide();
      }
      if(val==2)
      {
                $('#weekly').hide();
                $('#mothly').show();
                $('#date-wise').hide();
      }
      if(val==3)
      {
                $('#weekly').hide();
                $('#mothly').hide();
                $('#date-wise').show();
      }
      if(!val){
                $('#weekly').hide();
                $('#mothly').hide();
                $('#date-wise').hide(); 
                }              
            });
    $('#configuration_type').on('change', function() {
      var val = $('#configuration_type').val();
      if(val==1)
      {
                $('#weekly').show();
                $('#mothly').hide();
                $('#date-wise').hide();
      }
      if(val==2)
      {
                $('#weekly').hide();
                $('#mothly').show();
                $('#date-wise').hide();
      }
      if(val==3)
      {
                $('#weekly').hide();
                $('#mothly').hide();
                $('#date-wise').show();
      }
    });
    $('#lsgi-id').on('change',function(){
            var cat = $('#lsgi-id').val();
            if(cat){
                $.ajax({ 
                    url:'ward-ajax',
                    data:{cat:cat},
                    method:'POST',
                    success: function(data) {
                        var target = $('.type_select');
                        target.empty();
                        $.each(data , function(key , value){
                            // var input = '<option value='+key+' >'+value+'</option>';
                          var checkbox='checkbox';
                          method='Schedule[ward_id][]';
                            var input = '<input type='+checkbox+' value='+key+' name='+method+'>'+value+'</key>';
                            target.append(input);
                        })
                    }
                });
            }
        })
 ",View::POS_END);?>