<?php

    use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use backend\models\WasteCategory;
use yii\web\View;
    foreach ($params as $param => $val)
    {
        ${
            $param} = $val;
    }

?>
<div class="row bg-title">
  <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
    <h4 class="page-title">Complaints</h4>
  </div>
  <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
  </div>
  <div class="col-lg-4 col-sm-8 col-md-8 col-xs-12">
   <?php
       $breadcrumb[]                  = ['label' => 'Complaints', 'url' => ['/complaints']];
       $this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Green Action Unit'), 'url' => ['index']];
       if ($model->id)
       {
           $this->title = ucfirst($model->name);
       }
       else
       {
           $this->title = 'Create';
       }
       $breadcrumb[] = ['label' => $this->title, 'template' => "<li class='active' , style='color:#4AAFF4;'>{link}</li>\n"];
   ?>
   <?=$this->render('/layouts/breadcrumbs', ['links' => $breadcrumb]);?>
 </div>
</div>
<div class="row">
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <!-- Custom Tabs -->
    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
            <li class="active"><a href="#tab_1" data-toggle="tab" aria-expanded="false">Complaints Info</a></li>
            <li><a href="#tab_2" data-toggle="tab" aria-expanded="false">Status Options</a></li>
        </ul>
        <div class="tab-content" style="margin-left: 18px;">
            <div class="tab-pane active" id="tab_1">
                <?php
                    Pjax::begin(['timeout' => 50000, 'enablePushState' => false, 'id' => 'Pjax-add-complaints', 'options' => ['data-loader' => '.preloader']]);
                    $form = ActiveForm::begin(['action' => ['update', 'id' => $model->id], 'options' => ['', 'data-pjax' => false, 'class' => 'form-horizontal form-material', 'enableAjaxValidation' => false, 'enableClientValidation' => true]]);
                ?>
        <div class="row">
          <div class="col-sm-6 col-xs-6">
            <div class="form-group">
                <div class="col-sm-6 col-xs-6">
                    <?=$form->field($model, 'name')->textInput(['maxlength' => true, 'class' => 'form-control form-control-line']);?>
                </div>
            </div>
        </div>
         <div class="col-sm-6 col-xs-6">
          <div class="form-group">
              <div class="col-sm-6 col-xs-6">
            <?= $form->field($model, 'name_ml')->textInput(['maxlength' => true,'class'=>'form-control form-control-line']) ?>
          </div>
        </div>
      </div>
         
        </div>
    <div class="row">
    <div class="col-sm-6 col-xs-6">
            <div class="form-group">
                <div class="col-sm-6 col-xs-6">
                    <?=$form->field($model, 'sort_order')->textInput(['maxlength' => true, 'class' => 'form-control form-control-line']);?>
                </div>
            </div>
        </div>
       <div class="col-sm-6 col-xs-6">
                <div class="form-group">
                    <div class="col-sm-6 col-xs-6">
                      <?= $form->field($model, 'is_non_residential')->checkbox(); ?>
                    </div>
                </div>
            </div>
             <div class="col-sm-6 col-xs-6">
                <div class="form-group">
                    <div class="col-sm-6 col-xs-6">
                      <?= $form->field($model, 'is_residential')->checkbox(); ?>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xs-6">
                <div class="form-group">
                    <div class="col-sm-6 col-xs-6">
                      <?= $form->field($model, 'is_cityzen')->checkbox(); ?>
                    </div>
                </div>
            </div>  
             <div class="col-sm-6 col-xs-6">
                        <div class="form-group">
                        <div class="dict-cat-img">
                  <img src="<?=$model->getProfileUrl()?>" />
                </div>
                            <div class="col-sm-6 col-xs-6">

            <?=$form->field($modelImage, 'uploaded_files')->fileInput(['class' => 'form-control form-control-line', 'placeholder' => 'Choose flag icon']);?>
                    </div>
                </div>
            </div>
             <div class="col-sm-6 col-xs-6">
                        <div class="form-group">
                            <div class="col-sm-6 col-xs-6">
                    <?php
                        echo Html::submitButton(Yii::t('app', 'Update'), ['class' => 'btn btn-primary']);
                        ActiveForm::end();
                        Pjax::end();
                    ?>
            </div>
            </div>
            </div>
            </div>
            </div>
                <!-- /.tab-pane -->
                <div class="tab-pane" id="tab_2">
                    <?=$this->render('status-options', [
    'model'                      => $model,
    'modelServicingStatusOption' => $modelServicingStatusOption,
    'searchModel'                => $searchModel,
    'dataProvider'               => $dataProvider
]);?>
                </div>
                <!-- /.tab-pane -->
            </div>
            <!-- /.tab-content -->
        </div>
        <!-- nav-tabs-custom -->
    </div>
</div>
