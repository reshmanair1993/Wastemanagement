<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;

$form = ActiveForm::begin(['action' => ['update','id'=>$model->id],'options' => ['','data-pjax' => true,'class' => 'form-horizontal form-material','enableAjaxValidation' => false,'enableClientValidation'=>true]]);
?>
<div class="row">
  <div class="col-sm-6 col-xs-6">
    <div class="form-group">
      <div class="col-sm-6 col-xs-6">
        <?php
          echo $form->field($model, 'name')->textInput()->label('Name')?>
        </div>
    </div>
  </div>
</div>
<div class="row">
  <div class="col-sm-6 col-xs-6">
    <div class="form-group">
      <div class="col-sm-6 col-xs-6">
      <?php
        echo $form->field($model, 'email')->textInput()->label('Email')?>
      </div>
    </div>
  </div>
</div>
<div class="row">
  <div class="col-sm-6 col-xs-6">
    <div class="form-group">
      <div class="col-sm-6 col-xs-6">
      <?php
        echo $form->field($model, 'subject')->textInput()->label('Subject')?>
      </div>
    </div>
  </div>
</div>
<div class="row">
  <div class="col-sm-6 col-xs-6">
    <div class="form-group">
      <div class="col-sm-6 col-xs-6">
        <?php
          echo $form->field($model, 'description')->textInput()->label('Description')?>
      </div>
    </div>
  </div>
</div>
<div class="row">
  <div class="col-sm-6 col-xs-6">
    <div class="form-group">
      <div class="col-sm-6 col-xs-6">
      <?php
        echo $form->field($model, 'amount')->textInput()->label('Amount')?>
      </div>
    </div>
  </div>
</div>
<div class="row">
    <div class="col-sm-6 col-xs-6">
    <div class="form-group">
        <div class="col-sm-6 col-xs-6">
          <?php
            echo $form->field($model, 'incident_id')->textInput()->label('Incident')?>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-sm-6 col-xs-6">
        <div class="form-group">
          <div class="col-sm-6 col-xs-6">
            <?php
              echo Html::submitButton(Yii::t('app', 'Update'), ['class' => 'btn btn-primary']);
            ?>
          </div>
        </div>
      </div>
    </div>

<?php ActiveForm::end(); ?>
