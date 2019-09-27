<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use kartik\depdrop\DepDrop;
use yii\helpers\Url;
use yii\jui\Autocomplete;
use yii\web\JsExpression;
use yii\web\View;


/* @var $this yii\web\View */
/* @var $model backend\models\Camera */

$this->title = Yii::t('app', 'Create Memo');
// $this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Cameras'), 'url' => ['index']];
// $this->params['breadcrumbs'][] = $this->title;
?>
<div class="memo-create">
  <div class="row bg-title">
    <div class="col-lg-6 col-md- col-sm-4 col-xs-12">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <div class="col-lg-6 col-sm-8 col-md-8 col-xs-12">
     <?php
     $breadcrumb[]  = ['label' => 'Incidents', 'url' => ['/incidents/index']];
  $this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Memo'), 'url' => ['index']];
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


    <div class="memo-form">

      <?php $form = ActiveForm::begin(['action' =>['generate-memo','id' => $model->id],'options' => ['','data-pjax' => true,'class' => 'add-incident-form','enableAjaxValidation' => false,'enableClientValidation'=>true]]); ?>
      <div class="row">
        <div class="col-lg-4 col-md-4 col-sm-6 col-12">
        </div>
        <div class="col-lg-4 col-md-4 col-sm-6 col-12">
            <b><?php echo "To,";?></b>
          <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-6 col-12">
              <label for="eh-first-name">Name</label>
              <?= $form->field($modelGenerateMemo, 'name')->textInput(['class' => 'form-control','placeholder' => 'Name'])->label(false); ?>
            </div>
          </div>
          <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-6 col-12">
              <label for="eh-first-name">Email</label>
              <?= $form->field($modelGenerateMemo, 'email')->textInput(['class' => 'form-control','placeholder' => 'Email'])->label(false); ?>
            </div>
          </div>
          <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-6 col-12">
              <label for="eh-first-name">Address</label>
              <?= $form->field($modelGenerateMemo, 'address')->textarea(['rows' => '6', 'class' => 'form-control','placeholder' => 'Address'])->label(false);?>
            </div>
          </div>
          <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-6 col-12">
              <label for="eh-first-name">Subject</label>
              <?= $form->field($modelGenerateMemo, 'subject')->textInput(['class' => 'form-control','placeholder' => 'Subject'])->label(false); ?>
            </div>
          </div>
          <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-6 col-12">
              <label for="eh-first-name">Description</label>
              <?= $form->field($modelGenerateMemo, 'description')->textarea(['rows' => '6', 'class' => 'form-control','placeholder' => 'Description'])->label(false);?>
            </div>
          </div>
          <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-6 col-12">
              <label for="eh-first-name">Memo Type</label>
            <?php
            $memoTypesList = ArrayHelper::map($modelMemoType,'id','name');
            echo $form->field($modelGenerateMemo, 'memo_type_id')->widget(Select2::classname(), [
            'data' => $memoTypesList,
            'language' => 'de',
            'options' => ['placeholder' => 'Select Memo Type'],
            'pluginOptions' => [
            'allowClear' => true
            ],
            ])->label(false);
            ?>
          </div>
          </div>
          <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-6 col-12">
              <label for="eh-first-name">Amount</label>

              <?php echo $form->field($modelGenerateMemo, 'amount')->textInput(['class' => 'form-control','placeholder' => 'Amount', 'id' => 'amount'])->label(false); ?>
            </div>
          </div>
          <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-6 col-12">
              <label for="eh-first-name">Authorized Signatory</label>
            <?php
              $authorizedSignatoryList = ArrayHelper::map($modelAuthorizedSignatory,'id','position');
              echo $form->field($modelGenerateMemo, 'lsgi_authorized_signatory_id')->widget(Select2::classname(), [
              'data' => $authorizedSignatoryList,
              'language' => 'de',
              'options' => [
                'placeholder' => 'Select Authorized Signatory',
                'id' => 'memo_type'
              ],
              'pluginOptions' => [
              'allowClear' => true
              ],
              ])->label(false);
            ?>
          </div>
          </div>
          <div class="row">
            <div class="col-lg-12 ta-right">
              <!-- <button type="button" class="btn btn-success" data-dismiss="modal">cancel</button> -->
              <?= Html::submitButton('Submit', ['class' => 'btn btn-success']) ?>
            </div>
          </div>
        <?php ActiveForm::end(); ?>
      </div>
    </div>
  </div>
</div>
<?php
$url = Url::to(['incidents/memo-amount']);
$incidentId = $model->id;
$this->registerJs("
function complete(id){

        $.ajax({
           url: '?r=incidents/autocomplete',
           type: 'post',
           data: {cnic: id},
           success: function(data) {

               var obj = JSON.parse(data);

                $('#amount').val(obj[0].amount);
           }
        });
      }
      $('#memo-memo_type_id').on('change',function(){
          $.ajax({
              url: '$url',
              dataType: 'json',
              method: 'GET',
              data: {id: $(this).val(),incidentId: '$incidentId'},
              success: function (data, textStatus, jqXHR) {
                  $('#amount').val(data.amount);
              },
              beforeSend: function (xhr) {
                  // alert('loading!');
              },
              error: function (jqXHR, textStatus, errorThrown) {
                  console.log('An error occured!');
                  alert('Error in ajax request');
              }
          });
      });
",View::POS_END);
 ?>
