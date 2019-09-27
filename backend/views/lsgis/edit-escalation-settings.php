<?php
use yii\widgets\Pjax;
use yii\widgets\ActiveForm;
use backend\models\AuthItem;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ListView;
use yii\web\View;
 $userRole = Yii::$app->user->identity->role;
$query = AuthItem::find()
      ->leftjoin('auth_item_child','auth_item_child.child=auth_item.name')
      ->where(['auth_item_child.parent'=>$userRole])
      ->andWhere(['auth_item.type'=>1])
      ->all();
      if($query){
        foreach ($query as $qry) {
          $roleList[$qry->name] = $qry->name;
        }
      }else{
        $roleList = [];
      }
	Pjax::begin(['timeout' => 50000 ,'enablePushState' => false, 'id' =>'Pjax-edit-escalation-settings','options'=>['data-loader'=>'.preloader']]);
	$form = ActiveForm::begin(['action' => ['update-escalation-settings','id'=>$modelEscalationSettings->id],'options' => ['','data-pjax' => true,'class' => 'form-horizontal form-material','enableAjaxValidation' => false,'enableClientValidation'=>true]]);
	?>
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 add-tip-formn edit-qa" style="background: #f9f9f9; border: 1px solid #eee; padding-top: 10px; padding-bottom: 10px;">
	 <div class="col-sm-12 col-xs-12">
			          <div class="form-group">
			            <div class="col-sm-12 col-xs-6">
                  <?php
                   echo $form->field($modelEscalationSettings, 'role')->dropDownList($roleList, ['prompt' => 'Select from the list','id'=>'role-name','class'=>'form-control form-control-line'])->label('Role');
                   ?>
           </div>
         </div>
         </div>
          <div class="col-sm-12 col-xs-12">
                <div class="form-group">
                  <div class="col-sm-12 col-xs-6">
               <?=$form->field($modelEscalationSettings, 'complaint_escalation_min')->textInput(['maxlength' => true,'class'=>'form-control form-control-line']);?>
           </div>
         </div>
         </div>
	<div class="col-sm-12 col-xs-12">
			          <div class="form-group">
			            <div class="col-sm-12 col-xs-6">
			              <?=$form->field($modelEscalationSettings, 'service_escalation_min')->textInput(['maxlength' => true,'class'=>'form-control form-control-line']);?>
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
    $('#Pjax-edit-escalation-settings').on('pjax:end', function() {
      $.pjax.reload({container:'#Pjax-add-lsgi-escalation-settings'});
    });

    ",View::POS_END);
?>
 <?php Pjax::end(); ?>