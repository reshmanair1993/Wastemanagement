<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $model backend\models\PaymentCounter */

$this->title = 'Update Payment Counter';
$this->params['breadcrumbs'][] = ['label' => 'Payment Counters', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$modelUser = Yii::$app->user->identity;
$userRole  = $modelUser->role;
?>

<div class="payment-counter-update">
  <div class="row bg-title">
    <div class="col-lg-6 col-md- col-sm-4 col-xs-12">
      <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <div class="col-lg-6 col-sm-8 col-md-8 col-xs-12">
      <?php
        $breadcrumb[]  = ['label' => 'Payment Counter', 'url' => ['/payment-counter/index']];
        $this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Payment Counters'), 'url' => ['index']];
        if($model->id){
        $this->title =  'Update';
        }
        else
        {
        $this->title =  'Create';
        }
        $breadcrumb[] = ['label' => $this->title,'template' => "<li class='active' , style='color:#4AAFF4;'>{link}</li>\n"];
      ?>
      <?=$this->render('/layouts/breadcrumbs',[ 'links'=>$breadcrumb]);?>
    </div>
  </div>
  <?php $form = ActiveForm::begin(['action' =>['update','id'=>$model->id],'options' => ['','data-pjax' => true,'class' => 'add-engg-form','enableAjaxValidation' => false,'enableClientValidation'=>true]]); ?>
    <div class="row">
    <div class="col-lg-4 col-md-4 col-sm-6 col-12">
    </div>
    <div class="col-lg-4 col-md-4 col-sm-6 col-12">
      <div class="row">
        <div class="form-group col-lg-12 col-md-12 col-sm-6 col-12">
          <label for="eh-first-name">Counter Name</label>
          <?= $form->field($model, 'name')->textInput(['class' => 'form-control','placeholder' => 'Name'])->label(false); ?>
        </div>
      </div>
      <?php if (!($userRole == 'admin-lsgi')){ ?>
      <div class="row">
        <div class="form-group col-lg-12 col-md-12 col-sm-6 col-12">
          <label for="eh-first-name">Lsgi</label>
          <?php
            $lsgiList = ArrayHelper::map($modelLsgi,'id','name');
            echo $form->field($model, 'lsgi_id')->widget(Select2::classname(), [
            'data' => $lsgiList,
            'language' => 'de',
            'options' => ['placeholder' => 'Select Lsgi'],
            'pluginOptions' => [
            'allowClear' => true
            ],
            ])->label(false);
          ?>
        </div>
      </div>
    <?php } ?>
      <div class="row">
        <div class="col-lg-12 ta-right">
          <!-- <button type="button" class="btn btn-success" data-dismiss="modal">cancel</button> -->
          <a href="<?=Url::to(['/payment-counter/index'])?>" class="btn btn-success">cancel</a>
          <?= Html::submitButton('Submit', ['class' => 'btn btn-success']) ?>
        </div>
      </div>
    </div>
    <div class="col-lg-4 col-md-4 col-sm-6 col-12">
    </div>
    </div>
  <?php ActiveForm::end(); ?>
</div>
