<?php

use yii\helpers\Html;
use yii\grid\GridView;
use kartik\export\ExportMenu;
use yii\widgets\Pjax;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\depdrop\DepDrop;
use yii\helpers\Url;
use yii\web\View;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->title = Yii::t('app', 'QR Code');
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="row bg-title">
  <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
    <h4 class="page-title">QR Code</h4>
  </div>
  <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
  </div>
  <div class="col-lg-4 col-sm-8 col-md-8 col-xs-12">
   <?php
   $breadcrumb[]  = ['label' => 'QR Code', 'url' => ['/qr-code']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'QR Code'), 'url' => ['index']];
if($modelQrCode->id){
   $this->title =  ucfirst($modelQrCode->value);
}
else
{
   $this->title =  'Print';
}
$breadcrumb[] = ['label' => $this->title,'template' => "<li class='active' , style='color:#4AAFF4;'>{link}</li>\n"];
   ?>
   <?=$this->render('/layouts/breadcrumbs',[ 'links'=>$breadcrumb]);?>
 </div>
</div>

<div class="row">
  <div class="col-md-12">
    <div class="white-box">
      <div class="scrollable">
        <?php $form = ActiveForm::begin(['method'=>'GET','action' => ['print-codes'],'options' => ['','data-pjax' => true,'class' => 'form-horizontal form-material','enableAjaxValidation' => false,'enableClientValidation'=>true]]);?>
        <div class="row">
         <div class="col-sm-6 col-xs-6">
            <div class="form-group">
                <div class="col-sm-6 col-xs-6">
                    <?=$form->field($modelQrCode, 'start')->textInput(['maxlength' => true,'class'=>'form-control form-control-line','name'=>'start']);?>
                </div>
            </div>
        </div>
         <div class="col-sm-6 col-xs-6">
            <div class="form-group">
                <div class="col-sm-6 col-xs-6">
                    <?=$form->field($modelQrCode, 'limit')->textInput(['maxlength' => true,'class'=>'form-control form-control-line','name'=>'limit'])->label('No.of Codes');?>
                </div>
            </div>
        </div>
        </div>
        <div class="row">
         <div class="col-sm-6 col-xs-6">
            <div class="form-group">
                <div class="col-sm-6 col-xs-6">
                    <?=$form->field($modelQrCode, 'columns')->textInput(['maxlength' => true,'class'=>'form-control form-control-line','name'=>'columns','value'=>4]);?>
                </div>
            </div>
        </div>
         <div class="col-sm-6 col-xs-6">
            <div class="form-group">
                <div class="col-sm-6 col-xs-6">
        <?php $district= $modelQrCode->getDistricts();
                    $listData=ArrayHelper::map($district, 'id', 'name');
                    echo $form->field($modelQrCode, 'district_id')->dropDownList($listData, ['prompt' => 'Select from the list','class'=>'form-control form-control-line','id'=>'district-id','value'=>$modelQrCode->getDistrict($modelQrCode->lsgi_id)])->label('District')?>
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-sm-6 col-xs-6">
                <div class="form-group">
                    <div class="col-sm-6 col-xs-6">
                           <?php 

                        echo $form->field($modelQrCode, 'assembly_constituency_id')->widget(DepDrop::classname(), [
                        'type'=>DepDrop::TYPE_SELECT2,
                        'data'=>[$modelQrCode->getConstituency($modelQrCode->lsgi_id)],
                        'options'=>['id'=>'constituency-id','class'=>'form-control form-control-line'],
                        'select2Options'=>['pluginOptions'=>['allowClear'=>true]],
                        'pluginOptions'=>[
                           'depends'=>['district-id'],
                           'class'=>'form-control form-control-line',
                            'placeholder'=>'Select...',
                            'url'=>Url::to(['/assembly-constituency/constituency'])
                        ]
                    ])->label('Assembly Constituency');
                    ?>
                    </div>
                </div>
            </div>
          <div class="col-sm-6 col-xs-6">
                    <div class="form-group">
                        <div class="col-sm-6 col-xs-6">
                           <?php 

                        echo $form->field($modelQrCode, 'block_id')->widget(DepDrop::classname(), [
                        'type'=>DepDrop::TYPE_SELECT2,
                        'data'=>[$modelQrCode->getBlock($modelQrCode->lsgi_id)],
                        'options'=>['id'=>'block-id','class'=>'form-control form-control-line'],
                        'select2Options'=>['pluginOptions'=>['allowClear'=>true]],
                        'pluginOptions'=>[
                           'depends'=>['constituency-id'],
                           'class'=>'form-control form-control-line',
                            'placeholder'=>'Select...',
                            'url'=>Url::to(['/lsgi-blocks/blocks'])
                        ]
                    ])->label('Block');
                    ?>
                    </div>
                </div>
            </div>
            </div>
            <div class="row">
            <div class="col-sm-6 col-xs-6">
                        <div class="form-group">
                            <div class="col-sm-6 col-xs-6">
             
            <?php

            echo $form->field($modelQrCode, 'lsgi_id')->widget(DepDrop::classname(), [
                'type'=>DepDrop::TYPE_SELECT2,
                 'data'=>[$modelQrCode->getLsgis($modelQrCode->lsgi_id)],
                'options'=>['class'=>'form-control form-control-line','name'=>'lsgi_id'],
                'select2Options'=>['pluginOptions'=>['allowClear'=>true]],
                'pluginOptions'=>[
                   'depends'=>['block-id'],
                    'placeholder'=>'Select...',
                    'class'=>'form-control form-control-line',
                    'url'=>Url::to(['/lsgis/lsgi'])
                ]
            ])->label('Lsgi');
            ?>
                    </div>
                </div>
            </div>
        <div class="col-lg-1 col-md-1 col-sm-6 col-xs-12"></div>
  <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
  <?= Html::submitButton(Yii::t('app', 'Print'), ['class' => 'btn btn-success pull-right','target'=>'_blank']);
  ?>
  </div>
  </div>
        <?php ActiveForm::end();?>
                      <?php

$this->registerJs("
$('#cmd').click(function () {   
   window.print();
});
 ",View::POS_END);
 ?>
</div>
</div>
</div>
</div>
