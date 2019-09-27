<?php
use yii\widgets\Pjax;
use yii\widgets\ActiveForm;
use backend\models\Image;
use backend\models\Service;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ListView;
use yii\web\JsExpression;


	$form = ActiveForm::begin(['action' => ['add-status','id'=>$model->id],'options' => ['','data-pjax' => true,'class' => 'form-horizontal form-material','enableAjaxValidation' => true,'enableClientValidation'=>true]]);
	?>
	<div class="row">
		<div class="col-lg-4 col-md-4 col-sm-6 col-12">
    </div>
		<div class="col-lg-4 col-md-4 col-sm-6 col-12">
			<div class="row">
				<div class="form-group col-lg-12 col-md-12 col-sm-6 col-12">
				<?php
					$status=$model->getStatusOption($model->service_id);
					$listData=ArrayHelper::map($status, 'id', 'value');
					// print_r($listData);exit;
					echo $form->field($modelCameraServiceAssignment, 'camera_servicing_status_option_id')->dropDownList($listData,['prompt' => 'Choose option','class'=>'form-control form-control-line','value'=>$model->fkCameraServiceAssignment?$model->fkCameraServiceAssignment->camera_servicing_status_option_id:''])->label('Status Option')?>
				</div>
			</div>
			<div class="row">
				<div class="form-group col-lg-12 col-md-12 col-sm-6 col-12">


					<?php
					       echo $form->field($modelCameraServiceAssignment, 'location')->widget('\pigolab\locationpicker\CoordinatesPicker' , [
					         'key' => 'AIzaSyBnG4QUmKyu5PVqlMjlYnml5KAht7eVtow',
					         'valueTemplate' => '{latitude},{longitude}',
					         'options' => [
					           'style' => 'width: 100%; height: 400px',
					           ] ,
					           'enableSearchBox' => true ,
					           'searchBoxPosition' => new JsExpression('google.maps.ControlPosition.TOP_LEFT'),
					           'mapOptions' => [
					             'mapTypeControl' => true,
					             'mapTypeControlOptions' => [
					               'style'    => new JsExpression('google.maps.MapTypeControlStyle.HORIZONTAL_BAR'),
					               'position' => new JsExpression('google.maps.ControlPosition.TOP_LEFT'),
					             ],
					             'streetViewControl' => true,
					           ],
					           'clientOptions' => [
					             'radius'    => 300,
					             'addressFormat' => 'street_number',
					             'inputBinding' => [
					               'latitudeInput'     => new JsExpression("$('#latitude')"),
					               'longitudeInput'    => new JsExpression("$('#longitude')"),
					             ],
					           ],
					         ]);
					         ?>
					           <label for="longitude">Longitude</label>
					           <?=$form->field($modelCameraServiceAssignment, 'lng_updated_from')->textInput(['class' => 'form-control', 'data-attr-name' => 'lng', 'id' => 'longitude', 'data-textbox-val-attr' => 'name'])->label(false);?>
					         </div>
								 </div>
			<div class="row">
				<div class="form-group col-lg-12 col-md-12 col-sm-6 col-12">
					<label for="latitude">Latitude</label>
 				 <?=$form->field($modelCameraServiceAssignment, 'lat_update_from')->textInput(['class' => 'form-control', 'data-attr-name' => 'lat','id' => 'latitude', 'data-textbox-val-attr' => 'name'])->label(false);?>
        </div>
      </div>
			<div class="row">
				<div class="form-group col-lg-12 col-md-12 col-sm-6 col-12">
					<?= Html::submitButton(Yii::t('app', 'Add'), ['class' => 'btn btn-success pull-right']);
					?>
				</div>
			</div>
		</div>
		<div class="col-lg-4 col-md-4 col-sm-6 col-12">
		</div>
	</div>
	<?php
	 ActiveForm::end();
	?>
