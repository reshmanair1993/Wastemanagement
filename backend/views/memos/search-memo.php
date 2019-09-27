<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;

$form = ActiveForm::begin(['action' => ['search-memo'],'options' => ['','data-pjax' => true,'class' => 'form-horizontal form-material','enableAjaxValidation' => false,'enableClientValidation'=>true]]);
$this->title = 'Search Memos';
// $modelLsgi = $model->getLsgi($model->lsgi_id);
?>
<div class="row bg-title">
  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
      <h1><?= Html::encode($this->title) ?></h1>
  </div>
</div>
<div class="row">
  <div class="col-lg-12 col-sm-12 col-xs-12 white-box payment-memo">
    <!-- <div class="payment-memo"> -->
      <div class="col-lg-4 col-sm-4 col-xs-12">
      </div>
      <div class="col-lg-4 col-sm-4 col-xs-12">
      <div class="col-lg-12 col-sm-12 col-xs-12">
        <label for="eh-first-name">Memo No.</label>
      <?= $form->field($model, 'id')->textInput(['class' => 'form-control'])->label(false); ?>
      </div>
      <div class="col-lg-12 col-sm-12 col-xs-12">
      <?php
        echo Html::submitButton(Yii::t('app', 'Submit'), ['class' => 'btn btn-primary']);
      ?>
    </div>
  </div>
  <div class="col-lg-4 col-sm-4 col-xs-12">
  </div>
    </div>
<!-- </div> -->
</div>
<!-- </div> -->
<?php ActiveForm::end(); ?>
<?php
  $title = isset($title)?$title:'Success';
  $type = isset($type)?$type:'success';
  $message = isset($message)?$message:'Memo Paid successfully';
  $title = Html::encode(trim($title));
  $message = Html::encode(trim($message));
  $title =  $title;
  $message =  $message; //but need to escape apppstrope
  if ($showSuccess == 1):
    $this->registerJs("
    swal({title:'$title',text: '$message', type:'$type'});
    ");
  endif ;
?>
