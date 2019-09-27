<?php

    use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use backend\models\Item;
use backend\models\GreenActionUnit;
use backend\models\Mrc;
use backend\models\Account;
use kartik\depdrop\DepDrop;
use yii\helpers\Url;
    /* @var $this yii\web\View */
    /* @var $model backend\models\Isgi */
    /* @var $form yii\widgets\ActiveForm */
    $modelUser  = Yii::$app->user->identity;
        $userRole  = $modelUser->role;
        $associations = Yii::$app->rbac->getAssociations($modelUser->id);
  $modelAccount = new Account;
?>
<div class="row bg-title">
  <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
    <h4 class="page-title">Items To MCF/RRF</h4>
  </div>
  <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
  </div>
  <div class="col-lg-4 col-sm-8 col-md-8 col-xs-12">
   <?php
   $breadcrumb[]  = ['label' => 'Items To MCF/RRF', 'url' => ['/item-mrc']];
   $this->title = Yii::t('app', 'Create');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Lsgis'), 'url' => ['index']];
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
<div class="col-md-12 col-sm-12">
  <div class="white-box">
    <div class="row">
      <div class="col-sm-12 col-xs-12">
    <?php $form = ActiveForm::begin();?>
        <!-- <div class="col-sm-6 col-xs-6">
            <div class="form-group">
                <div class="col-sm-6 col-xs-6">
                    <?php
                    $unit= GreenActionUnit::find()->where(['status'=>1])->all();
                    $listData=ArrayHelper::map($unit, 'id', 'name');
                     echo $form->field($model, 'green_action_unit_id')->widget(Select2::classname(), [
                    'data' => $listData,
                    'options' => ['placeholder' => 'Select...'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
              ]);?>
                </div>
            </div>
        </div> -->
                    <?php if(!isset($associations['lsgi_id'])):?>
            <div class="row">
            <div class="col-sm-6 col-xs-6">
            <div class="form-group">
                <div class="col-sm-6 col-xs-6">
        <?php $district= $modelAccount->getDistricts();
                    $listData=ArrayHelper::map($district, 'id', 'name');
                    echo $form->field($modelAccount, 'district_id')->dropDownList($listData, ['prompt' => 'Select from the list','class'=>'form-control form-control-line','id'=>'district-id','value'=>$modelAccount->getDistrict($model->lsgi_id)])->label('District')?>
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-sm-6 col-xs-6">
                <div class="form-group">
                    <div class="col-sm-6 col-xs-6">
                           <?php 

                        echo $form->field($modelAccount, 'assembly_constituency_id')->widget(DepDrop::classname(), [
                        'type'=>DepDrop::TYPE_SELECT2,
                        'data'=>[$modelAccount->getConstituency($model->lsgi_id)],
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

                        echo $form->field($modelAccount, 'block_id')->widget(DepDrop::classname(), [
                        'type'=>DepDrop::TYPE_SELECT2,
                        'data'=>[$modelAccount->getBlock($model->lsgi_id)],
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

            echo $form->field($model, 'lsgi_id')->widget(DepDrop::classname(), [
                'type'=>DepDrop::TYPE_SELECT2,
                 'data'=>[$modelAccount->getLsgis($model->lsgi_id)],
                 'options'=>['id'=>'lsgi-id','class'=>'form-control form-control-line'],
                'select2Options'=>['pluginOptions'=>['allowClear'=>true]],
                'pluginOptions'=>[
                   'depends'=>['block-id'],
                    'placeholder'=>'Select...',
                    'class'=>'form-control form-control-line',
                    'url'=>Url::to(['/lsgis/lsgi'])
                ]
            ]);
            ?>
                    </div>
                </div>
            </div>
             <div class="col-sm-6 col-xs-6">
                        <div class="form-group">
                            <div class="col-sm-6 col-xs-6">
             
            <?php

            echo $form->field($modelAccount, 'green_action_unit_id')->widget(DepDrop::classname(), [
                'type'=>DepDrop::TYPE_SELECT2,
                 'data'=>[$modelAccount->getUnit($model->green_action_unit_id)],
                'options'=>['class'=>'form-control form-control-line'],
                'select2Options'=>['pluginOptions'=>['allowClear'=>true]],
                'pluginOptions'=>[
                   'depends'=>['lsgi-id'],
                    'placeholder'=>'Select...',
                    'class'=>'form-control form-control-line',
                    'url'=>Url::to(['/green-action-units/unit'])
                ]
            ]);
            ?>
                    </div>
                </div>
            </div>
        </div>
            <?php else:?>
            <div class="row">
            <?php

            echo $form->field($model, 'lsgi_id')->hiddenInput(['value'=>$modelUser->lsgi_id])->label(false);
            ?>
             <div class="col-sm-6 col-xs-6">
                        <div class="form-group">
                            <div class="col-sm-6 col-xs-6">
             
            <?php $hks= $modelAccount->getHks($modelUser->lsgi_id);
                    $listData=ArrayHelper::map($hks, 'id', 'name');
                    echo $form->field($model, 'green_action_unit_id')->dropDownList($listData, ['prompt' => 'Select from the list','class'=>'form-control form-control-line','id'=>'hks-id'])?>
                    </div>
                </div>
            </div>
            </div>
          <?php endif;?>
        <div class="col-sm-6 col-xs-6">
            <div class="form-group">
                <div class="col-sm-6 col-xs-6">
                    <?php
                    $item= Item::find()->where(['status'=>1])->all();
                    $listData=ArrayHelper::map($item, 'id', 'name');
                     echo $form->field($model, 'item_id')->widget(Select2::classname(), [
                    'data' => $listData,
                    'options' => ['placeholder' => 'Select...'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
              ]);?>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xs-6">
            <div class="form-group">
                <div class="col-sm-6 col-xs-6">
                    <?php
                    $mrc= Mrc::find()->where(['status'=>1])->all();
                    $listData=ArrayHelper::map($mrc, 'id', 'name');
                     echo $form->field($model, 'mrc_id')->widget(Select2::classname(), [
                    'data' => $listData,
                    'options' => ['placeholder' => 'Select...'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
              ]);?>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xs-6">
              <div class="form-group">
                <div class="col-sm-6 col-xs-6">
                  <?=$form->field($model, 'qty')->textInput(['maxlength' => true,'class'=>'form-control form-control-line']);?>
                </div>
              </div>
            </div>
        <div class="form-group">
            <?=Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']);?>
        </div>

    <?php ActiveForm::end();?>

            </div>
        </div>
    </div>
</div>
