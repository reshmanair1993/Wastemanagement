<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\models\Image;
$modelImage = new Image;
/* @var $this yii\web\View */
/* @var $model backend\models\Home */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="row bg-title">
  <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
    <h4 class="page-title">Home</h4>
  </div>
  <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
  </div>
  <div class="col-lg-4 col-sm-8 col-md-8 col-xs-12">
   <?php
   $breadcrumb[]  = ['label' => 'Home', 'url' => ['/home']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Home'), 'url' => ['index']];
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
    <?php $form = ActiveForm::begin(['action'=>'update?id='.$model->id]); ?>
    <div class="row">
     <div class="col-sm-4 col-xs-4">
          <div class="form-group">
            <div class="col-sm-4 col-xs-4">
    <?= $form->field($model, 'title')->textInput(['maxlength' => true,'class'=>'form-control form-control-line']) ?>
</div>
</div>
</div>
 <div class="col-sm-4 col-xs-4">
            <div class="form-group">
                <div class="col-sm-4 col-xs-4">
                    <?=$form->field($model, 'sub_title')->textInput(['maxlength' => true,'class'=>'form-control form-control-line']);?>
                </div>
            </div>
        </div>
    <div class="col-sm-4 col-xs-4">
                <div class="form-group">
                  <div class="dict-cat-img">
                  <img src="<?=$model->getProfileUrl()?>" />
                </div>
                  <div class="col-sm-4 col-xs-6">
                    <?= $form->field($modelImage, 'uploaded_files')->fileInput(['class'=>'form-control form-control-line','placeholder' => 'Choose flag icon'])?>
                 </div>
               </div>
             </div>
</div>

    <div class="row">
     <div class="col-sm-4 col-xs-4">
          <div class="form-group">
            <div class="col-sm-4 col-xs-4">
    <?= $form->field($model, 'top_box_one_title')->textInput(['maxlength' => true,'class'=>'form-control form-control-line']) ?>
</div>
</div>
</div>
 <div class="col-sm-4 col-xs-4">
            <div class="form-group">
                <div class="col-sm-4 col-xs-4">
                    <?=$form->field($model, 'top_box_one_sub')->textInput(['maxlength' => true,'class'=>'form-control form-control-line']);?>
                </div>
            </div>
        </div>
    <div class="col-sm-4 col-xs-4">
                <div class="form-group">
                  <div class="dict-cat-img">
                  <img src="<?=$model->getProfileUrlTopBox1()?>" />
                </div>
                  <div class="col-sm-4 col-xs-6">
                    <?= $form->field($modelImage, 'uploaded_files1')->fileInput(['class'=>'form-control form-control-line','placeholder' => 'Choose flag icon'])?>
                 </div>
               </div>
             </div>
</div>
<div class="row">
     <div class="col-sm-4 col-xs-4">
          <div class="form-group">
            <div class="col-sm-4 col-xs-4">
    <?= $form->field($model, 'top_box_two_title')->textInput(['maxlength' => true,'class'=>'form-control form-control-line']) ?>
</div>
</div>
</div>
 <div class="col-sm-4 col-xs-4">
            <div class="form-group">
                <div class="col-sm-4 col-xs-4">
                    <?=$form->field($model, 'top_box_two_sub')->textInput(['maxlength' => true,'class'=>'form-control form-control-line']);?>
                </div>
            </div>
        </div>
    <div class="col-sm-4 col-xs-4">
                <div class="form-group">
                  <div class="dict-cat-img">
                  <img src="<?=$model->getProfileUrlTopBox2()?>" />
                </div>
                  <div class="col-sm-4 col-xs-6">
                    <?= $form->field($modelImage, 'uploaded_files2')->fileInput(['class'=>'form-control form-control-line','placeholder' => 'Choose flag icon'])?>
                 </div>
               </div>
             </div>
</div>
<div class="row">
     <div class="col-sm-4 col-xs-4">
          <div class="form-group">
            <div class="col-sm-4 col-xs-4">
    <?= $form->field($model, 'top_box_three_title')->textInput(['maxlength' => true,'class'=>'form-control form-control-line']) ?>
</div>
</div>
</div>
 <div class="col-sm-4 col-xs-4">
            <div class="form-group">
                <div class="col-sm-4 col-xs-4">
                    <?=$form->field($model, 'top_box_three_sub')->textInput(['maxlength' => true,'class'=>'form-control form-control-line']);?>
                </div>
            </div>
        </div>
    <div class="col-sm-4 col-xs-4">
                <div class="form-group">
                  <div class="dict-cat-img">
                  <img src="<?=$model->getProfileUrlTopBox3()?>" />
                </div>
                  <div class="col-sm-4 col-xs-6">
                    <?= $form->field($modelImage, 'uploaded_files3')->fileInput(['class'=>'form-control form-control-line','placeholder' => 'Choose flag icon'])?>
                 </div>
               </div>
             </div>
</div>
<div class="row">
     <div class="col-sm-4 col-xs-4">
          <div class="form-group">
            <div class="col-sm-4 col-xs-4">
    <?= $form->field($model, 'abt_head_one')->textInput(['maxlength' => true,'class'=>'form-control form-control-line']) ?>
</div>
</div>
</div>
 <div class="col-sm-4 col-xs-4">
            <div class="form-group">
                <div class="col-sm-4 col-xs-4">
                    <?=$form->field($model, 'abt_head_two')->textInput(['maxlength' => true,'class'=>'form-control form-control-line']);?>
                </div>
            </div>
        </div>
        <div class="col-sm-4 col-xs-4">
            <div class="form-group">
                <div class="col-sm-4 col-xs-4">
                    <?=$form->field($model, 'abt_head_three')->textInput(['maxlength' => true,'class'=>'form-control form-control-line']);?>
                </div>
            </div>
        </div>
   
</div>
<div class="row">
 <div class="col-sm-4 col-xs-4">
            <div class="form-group">
                <div class="col-sm-4 col-xs-4">
                    <?=$form->field($model, 'abt_head_four')->textInput(['maxlength' => true,'class'=>'form-control form-control-line']);?>
                </div>
            </div>
        </div>
         <div class="col-sm-4 col-xs-4">
                <div class="form-group">
                  <div class="dict-cat-img">
                  <img src="<?=$model->getProfileUrlAbout()?>" />
                </div>
                  <div class="col-sm-4 col-xs-6">
                    <?= $form->field($modelImage, 'uploaded_files4')->fileInput(['class'=>'form-control form-control-line','placeholder' => 'Choose flag icon'])?>
                 </div>
               </div>
             </div>
             
</div>
<div class="col-sm-4 col-xs-4">
            <div class="form-group">
                <div class="col-sm-4 col-xs-4">
                    <?=$form->field($model, 'mid_four_title')->textInput(['maxlength' => true,'class'=>'form-control form-control-line']);?>
                </div>
            </div>
        </div>
        <div class="col-sm-4 col-xs-4">
            <div class="form-group">
                <div class="col-sm-4 col-xs-4">
                    <?=$form->field($model, 'mid_four_sub_title')->textInput(['maxlength' => true,'class'=>'form-control form-control-line']);?>
                </div>
            </div>
        </div>
        <div class="col-sm-4 col-xs-4">
            <div class="form-group">
                <div class="col-sm-4 col-xs-4">
                    <?=$form->field($model, 'mid_four_one_title')->textInput(['maxlength' => true,'class'=>'form-control form-control-line']);?>
                </div>
            </div>
        </div>
        <div class="col-sm-4 col-xs-4">
            <div class="form-group">
                <div class="col-sm-4 col-xs-4">
                    <?=$form->field($model, 'mid_four_one_sub_title')->textInput(['maxlength' => true,'class'=>'form-control form-control-line']);?>
                </div>
            </div>
        </div>
       <div class="col-sm-4 col-xs-4">
                <div class="form-group">
                  <div class="dict-cat-img">
                  <img src="<?=$model->getProfileUrlMid4()?>" />
                </div>
                  <div class="col-sm-4 col-xs-6">
                    <?= $form->field($modelImage, 'uploaded_files5')->fileInput(['class'=>'form-control form-control-line','placeholder' => 'Choose flag icon'])?>
                 </div>
               </div>
             </div>
        <div class="col-sm-4 col-xs-4">
            <div class="form-group">
                <div class="col-sm-4 col-xs-4">
                    <?=$form->field($model, 'mid_four_two_title')->textInput(['maxlength' => true,'class'=>'form-control form-control-line']);?>
                </div>
            </div>
        </div>
        <div class="col-sm-4 col-xs-4">
            <div class="form-group">
                <div class="col-sm-4 col-xs-4">
                    <?=$form->field($model, 'mid_four_two_sub_title')->textInput(['maxlength' => true,'class'=>'form-control form-control-line']);?>
                </div>
            </div>
        </div>
        <div class="col-sm-4 col-xs-4">
                <div class="form-group">
                  <div class="dict-cat-img">
                  <img src="<?=$model->getProfileUrlMidFour2()?>" />
                </div>
                  <div class="col-sm-4 col-xs-6">
                    <?= $form->field($modelImage, 'uploaded_files6')->fileInput(['class'=>'form-control form-control-line','placeholder' => 'Choose flag icon'])?>
                 </div>
               </div>
             </div>
        <div class="col-sm-4 col-xs-4">
            <div class="form-group">
                <div class="col-sm-4 col-xs-4">
                    <?=$form->field($model, 'mid_four_three_title')->textInput(['maxlength' => true,'class'=>'form-control form-control-line']);?>
                </div>
            </div>
        </div>
        <div class="col-sm-4 col-xs-4">
            <div class="form-group">
                <div class="col-sm-4 col-xs-4">
                    <?=$form->field($model, 'mid_four_three_sub_title')->textInput(['maxlength' => true,'class'=>'form-control form-control-line']);?>
                </div>
            </div>
        </div>
        <div class="col-sm-4 col-xs-4">
                <div class="form-group">
                  <div class="dict-cat-img">
                  <img src="<?=$model->getProfileUrlMidFour3()?>" />
                </div>
                  <div class="col-sm-4 col-xs-6">
                    <?= $form->field($modelImage, 'uploaded_files7')->fileInput(['class'=>'form-control form-control-line','placeholder' => 'Choose flag icon'])?>
                 </div>
               </div>
             </div>
             <div class="col-sm-4 col-xs-4">
            <div class="form-group">
                <div class="col-sm-4 col-xs-4">
                    <?=$form->field($model, 'mid_four_four_title')->textInput(['maxlength' => true,'class'=>'form-control form-control-line']);?>
                </div>
            </div>
        </div>
        <div class="col-sm-4 col-xs-4">
            <div class="form-group">
                <div class="col-sm-4 col-xs-4">
                    <?=$form->field($model, 'mid_four_four_sub_title')->textInput(['maxlength' => true,'class'=>'form-control form-control-line']);?>
                </div>
            </div>
        </div>
        <div class="col-sm-4 col-xs-4">
                <div class="form-group">
                  <div class="dict-cat-img">
                  <img src="<?=$model->getProfileUrlMidFour4()?>" />
                </div>
                  <div class="col-sm-4 col-xs-6">
                    <?= $form->field($modelImage, 'uploaded_files8')->fileInput(['class'=>'form-control form-control-line','placeholder' => 'Choose flag icon'])?>
                 </div>
               </div>
             </div>
        <div class="col-sm-4 col-xs-4">
            <div class="form-group">
                <div class="col-sm-4 col-xs-4">
                    <?=$form->field($model, 'video_title')->textInput(['maxlength' => true,'class'=>'form-control form-control-line']);?>
                </div>
            </div>
        </div>
        <div class="col-sm-4 col-xs-4">
            <div class="form-group">
                <div class="col-sm-4 col-xs-4">
                    <?=$form->field($model, 'video_sub_title')->textInput(['maxlength' => true,'class'=>'form-control form-control-line']);?>
                </div>
            </div>
        </div>
        <div class="col-sm-4 col-xs-4">
            <div class="form-group">
                <div class="col-sm-4 col-xs-4">
                    <?=$form->field($model, 'video_url')->textInput(['maxlength' => true,'class'=>'form-control form-control-line']);?>
                </div>
            </div>
        </div>
        <div class="col-sm-4 col-xs-4">
            <div class="form-group">
                <div class="col-sm-4 col-xs-4">
                    <?=$form->field($model, 'circle_menu_one')->textInput(['maxlength' => true,'class'=>'form-control form-control-line']);?>
                </div>
            </div>
        </div>
        <div class="col-sm-4 col-xs-4">
                <div class="form-group">
                  <div class="dict-cat-img">
                  <img src="<?=$model->getProfileUrlCircleMenu1()?>" />
                </div>
                  <div class="col-sm-4 col-xs-6">
                    <?= $form->field($modelImage, 'uploaded_files9')->fileInput(['class'=>'form-control form-control-line','placeholder' => 'Choose flag icon'])?>
                 </div>
               </div>
             </div>
             <div class="col-sm-4 col-xs-4">
            <div class="form-group">
                <div class="col-sm-4 col-xs-4">
                    <?=$form->field($model, 'circle_menu_two')->textInput(['maxlength' => true,'class'=>'form-control form-control-line']);?>
                </div>
            </div>
        </div>
        <div class="col-sm-4 col-xs-4">
                <div class="form-group">
                  <div class="dict-cat-img">
                  <img src="<?=$model->getProfileUrlCircleMenu2()?>" />
                </div>
                  <div class="col-sm-4 col-xs-6">
                    <?= $form->field($modelImage, 'uploaded_files10')->fileInput(['class'=>'form-control form-control-line','placeholder' => 'Choose flag icon'])?>
                 </div>
               </div>
             </div>
             <div class="col-sm-4 col-xs-4">
            <div class="form-group">
                <div class="col-sm-4 col-xs-4">
                    <?=$form->field($model, 'circle_menu_three')->textInput(['maxlength' => true,'class'=>'form-control form-control-line']);?>
                </div>
            </div>
        </div>
        <div class="col-sm-4 col-xs-4">
                <div class="form-group">
                  <div class="dict-cat-img">
                  <img src="<?=$model->getProfileUrlCircleMenu3()?>" />
                </div>
                  <div class="col-sm-4 col-xs-6">
                    <?= $form->field($modelImage, 'uploaded_files11')->fileInput(['class'=>'form-control form-control-line','placeholder' => 'Choose flag icon'])?>
                 </div>
               </div>
             </div> 
        <div class="col-sm-4 col-xs-4">
            <div class="form-group">
                <div class="col-sm-4 col-xs-4">
                    <?=$form->field($model, 'circle_menu_four')->textInput(['maxlength' => true,'class'=>'form-control form-control-line']);?>
                </div>
            </div>
        </div>
        <div class="col-sm-4 col-xs-4">
                <div class="form-group">
                  <div class="dict-cat-img">
                  <img src="<?=$model->getProfileUrlCircleMenu4()?>" />
                </div>
                  <div class="col-sm-4 col-xs-6">
                    <?= $form->field($modelImage, 'uploaded_files12')->fileInput(['class'=>'form-control form-control-line','placeholder' => 'Choose flag icon'])?>
                 </div>
               </div>
             </div>
    <div class="col-sm-6 col-xs-6">
          <div class="form-group">
            <div class="col-sm-6 col-xs-6">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>
    </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
</div>
</div>
</div>
