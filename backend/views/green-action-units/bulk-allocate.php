<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\widgets\ActiveForm;
use yii\db\Query;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\jui\AutoComplete;
use yii\web\JsExpression;
use yii\web\View;
use backend\models\AccountGt;
?>
<?php Pjax::begin(['timeout' => 50000 ,'enablePushState' => false,
'id' =>'Pjax-bulk-add','options'=>['data-loader'=>'.preloader']]);?>
<?php

  $form = ActiveForm::begin(['action' => ['add-gt','id'=>$modelPageSet->id],'options' => ['','data-pjax' => true,'class' => 'form-horizontal form-material','enableAjaxValidation' => false,'enableClientValidation'=>true]]);

?>
<br>   <div class="col-md-12 col-sm-12">
  <div class="white-box">
    <div class="row">
      <div class="col-sm-12 col-xs-12">

    
        <div class="col-sm-12 col-xs-12">
          <div class="form-group">
            <div class="col-sm-12 col-xs-6">
               <?php 
                  // $gt=Service::getGt();
                  $gt=AccountGt::getGt($hks);
                $listData=ArrayHelper::map($gt, 'id', 'first_name'); 
                echo $form->field($modelAccount, 'account_id_gt')->dropDownList($listData,['prompt' => 'Select from the list','class'=>'form-control form-control-line'])->label('Green Technician')?>
           </div>
         </div>
     </div>
<?= Html::submitButton($modelAccount->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $modelAccount->isNewRecord ? 'btn btn-success pull-right' : 'btn btn-primary pull-right']) ?>
   </div>
 </div>
</div>
<?php ActiveForm::end(); ?>


<?php
if(isset($showSuccess) && $showSuccess) {
  $this->registerJs("
    $('#Pjax-bulk-add').on('pjax:end', function() {
      $.pjax.reload({container:'#pjax-account-gt-list'});
      $('.add-page-set-modal').modal('hide');
    });

    ",View::POS_END);
}
?>
<?php Pjax::end();?>
