<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use backend\models\IncidentType;
use backend\models\Incident;

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
            // echo $form->field($model, 'incident_id')->textInput()->label('Incident')
            ?>
            <?php
            // $modelIncidentType = [];
            // foreach ($modelIncident as $incident) {
            //   $modelIncidentType[] = IncidentType::find()->where(['status'=>1])->all();
            // }
              $modelIncidentType = IncidentType::find()->where(['status'=>1])->all();
              $memoIncidentTypeList = ArrayHelper::map($modelIncidentType,'id','name');
            // print_r($memoIncidentTypeList);exit;
            // $modelIncident = Incident::find()->where(['status'=>1,'id'=>$model->incident_id])->one();
            // $modelIncidentType = IncidentType::find()
            // ->leftjoin('incident','incident.incident_type_id = incident_type.id')
            // ->leftjoin('memo','memo.incident_id = incident.id')
            // ->where(['incident.id'=>$model->incident_id])
            // ->andWhere(['memo.status'=>1,'incident.status'=>1,'incident_type.status'=>1])->all();
            // return $modelLanguage;
            // $memoIncidentList = ArrayHelper::map($modelIncidentType,'id','name');
            // $memoIncidentList = ArrayHelper::map($modelIncidentType,'id','name');
            $memoIncidentList = ArrayHelper::map($modelIncident,'id','id');
            $incidentType = IncidentType::find()
            ->leftjoin('incident','incident.incident_type_id = incident_type.id')
            ->where(['incident.id' =>$model->incident_id])
            ->andWhere(['incident.status' => 1,'incident_type.status' => 1])->one();
            // print_r($incidentType);exit;
            // print_r($incidentType->name);exit;

            echo $form->field($model, 'incident_type_id')->widget(Select2::classname(), [
            'data' => $memoIncidentTypeList,
            'language' => 'de',
            'options' => [
              'placeholder' => 'Select Incident',
              'value' => isset($incidentType->id)?$incidentType->id:'',
            ],
            'pluginOptions' => [
            'allowClear' => true
            ],
            ])->label(false);
            ?>
          </div>
        </div>
      </div>
    </div>
    <div class="col-sm-1 col-xs-12" style="display:none;" >
      <?= $form->field($model, 'incident_id')->hiddenInput(['maxlength' => true,'class'=>'form-control form-control-line'])->label('') ?>
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
