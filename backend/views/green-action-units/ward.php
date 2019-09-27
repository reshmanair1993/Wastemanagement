<?php
use yii\widgets\Pjax;
use yii\widgets\ActiveForm;
use backend\models\Service;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ListView;
use kartik\select2\Select2;

	Pjax::begin(['timeout' => 50000 ,'enablePushState' => false, 'id' =>'Pjax-add-unit-ward','options'=>['data-loader'=>'.preloader']]);
	$form = ActiveForm::begin(['action' => ['add-unit-ward','id'=>$model->id],'options' => ['','data-pjax' => true,'class' => 'form-horizontal form-material','enableAjaxValidation' => false,'enableClientValidation'=>true]]);
	?>
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 add-tip-form" style="background: #f9f9f9; border: 1px solid #eee; padding-top: 10px; padding-bottom: 10px;">
	 <div class="col-sm-6 col-xs-6">
            <div class="form-group">
                <div class="col-sm-6 col-xs-6">
                    <?php $ward= $modelGreenActionUnitWard->getWard($model->lsgi_id,$model->residence_category_id,$model->id);
                    $listData=ArrayHelper::map($ward, 'id', 'name_en');
                    echo $form->field($modelGreenActionUnitWard, 'ward_id')->dropDownList($listData, ['prompt' => 'Select from the list','class'=>'form-control form-control-line'])?>
                </div>
            </div>
        </div>
        <?php if($model->residence_category_id==3):?>
          <div class="col-sm-12 col-xs-12">
            <div class="form-group">
                <div class="col-sm-12 col-xs-12">
                    <?php echo $form->field($modelGreenActionUnitWard, 'service_id')->widget(Select2::classname(), [
    'data' => ArrayHelper::map(Service::find()
    	->leftjoin('green_action_unit_service','green_action_unit_service.service_id=service.id')
    	->where(['type'=>1])
    	->andWhere(['service.type'=>1])
    	->andWhere(['green_action_unit_service.green_action_unit_id'=>$model->id])
    	->andWhere(['service.status'=>1])->andWhere(['service.is_package'=>0])->all(),'id','name'),
    'language' => 'de',
    'options' => ['placeholder' => 'Select.... ...','multiple' => true,],
    'pluginOptions' => [
        'allowClear' => true
    ],
])->label('Services');?>
                </div>
            </div>
        </div>
    <?php endif;?>
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
				<th>Ward</th>
				 <?php if($model->residence_category_id==3):?>
				<th>Services</th>
			<?php endif;?>
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
		    'dataProvider' => $dataProvider,
		    'options' => [
		        'tag' => 'div',
		        'class' => 'list-wrapper',
		        'id' => 'list-wrapper',
		    ],
		    'layout' => "{items}",
		    'itemView' => function ($model, $key, $index, $widget) {
		    	return $this->render('ward-list', [
		    		'model' => $model,
		    		'index' => $index+1,
		    	]);
		    },
		]);
	?>
	</tbody>
 </table>
 <?php Pjax::end(); ?>