<?php
use yii\widgets\Pjax;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ListView;
use yii\web\View;
use backend\models\Image;
$modelImage = new Image;

	Pjax::begin(['timeout' => 50000 ,'enablePushState' => false, 'id' =>'Pjax-edit-stakeholder','options'=>['data-loader'=>'.preloader']]);
	$form = ActiveForm::begin(['action' => ['update-stakeholder','id'=>$modelResidentialAssociationStakeholders->id],'options' => ['','data-pjax' => true,'class' => 'form-horizontal form-material','enableAjaxValidation' => false,'enableClientValidation'=>true]]);
	?>
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 add-tip-formn edit-qa" style="background: #f9f9f9; border: 1px solid #eee; padding-top: 10px; padding-bottom: 10px;">
	 <div class="col-sm-12 col-xs-12">
			          <div class="form-group">
			            <div class="col-sm-12 col-xs-6">
              <?=$form->field($modelResidentialAssociationStakeholders, 'name')->textInput(['maxlength' => true,'class'=>'form-control form-control-line']);?>
           </div>
         </div>
         </div>
          <div class="col-sm-12 col-xs-12">
                <div class="form-group">
                  <div class="col-sm-12 col-xs-6">
              <?=$form->field($modelResidentialAssociationStakeholders, 'phone')->textInput(['maxlength' => true,'class'=>'form-control form-control-line']);?>
           </div>
         </div>
         </div>
	<div class="col-sm-12 col-xs-12">
			          <div class="form-group">
			            <div class="col-sm-12 col-xs-6">
			              <?=$form->field($modelResidentialAssociationStakeholders, 'position')->textInput(['maxlength' => true,'class'=>'form-control form-control-line']);?>
			           </div>
			         </div>
			       </div>
             <div class="col-sm-12 col-xs-12">
                <div class="form-group">
                   <div class="dict-cat-img">
                  <img src="<?=$modelResidentialAssociationStakeholders->getProfileUrl()?>" />
                </div>
                  <div class="col-sm-12 col-xs-6">
                    <?= $form->field($modelImage, 'uploaded_files')->fileInput(['class'=>'form-control form-control-line','placeholder' => 'Choose flag icon'])?>
                 </div>
               </div>
             </div>
	<div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
	<?= Html::submitButton(Yii::t('app', 'Update'), ['class' => 'btn btn-success pull-left']);
	?>
	</div>
	<?php
	 ActiveForm::end();
	?>
	</div>
	<?php
	$this->registerJs("

function toggleCloseAddDeal() {
   hideAllCollapses();
   $('.edit-qa').fadeIn('fast');
}

$('.close-job-toggle').click(function() {
     toggleCloseAddDeal();
   });
   function hideAllCollapses() {
  $('.collapse.in').collapse('hide'); //hide all open collapses
}
   ", View::POS_END);?>
   <?php
  $this->registerJs("
    $('#Pjax-edit-stakeholder').on('pjax:end', function() {
      $.pjax.reload({container:'#Pjax-add-stakeholder'});
    });

    ",View::POS_END);
?>
 <?php Pjax::end(); ?>