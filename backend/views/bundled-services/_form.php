<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use backend\models\Service;
use yii\web\View;
use kartik\select2\Select2;
/* @var $this yii\web\View */
/* @var $model backend\models\Service */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="row bg-title">
  <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
    <h4 class="page-title">Bundled Services</h4>
  </div>
  <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
  </div>
  <div class="col-lg-4 col-sm-8 col-md-8 col-xs-12">
   <?php
   $breadcrumb[]  = ['label' => 'Bundled Services', 'url' => ['/bundled-services/index']];
   $this->title = Yii::t('app', 'Create Services');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Bundled Services'), 'url' => ['index']];
if($model->id){
   $this->title =  ucfirst($model->name);
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
		<?php $form = ActiveForm::begin(); ?>
  <div class="row">
 		<div class="col-sm-6 col-xs-6">
          <div class="form-group">
            	<div class="col-sm-6 col-xs-6">
    				<?= $form->field($model, 'name')->textInput(['maxlength' => true,'class'=>'form-control form-control-line']) ?>
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
       <div class="col-sm-12 col-xs-12">
            <div class="form-group">
                <div class="col-sm-12 col-xs-12">
                    <?php echo $form->field($model, 'services')->widget(Select2::classname(), [
    'data' => ArrayHelper::map(Service::find()->where(['type'=>1])->andWhere(['status'=>1])->andWhere(['is_package'=>0])->all(),'id','name'),
    'language' => 'de',
    'options' => ['placeholder' => 'Select.... ...','multiple' => true,],
    'pluginOptions' => [
        'allowClear' => true
    ],
])->label('Services');?>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
    <div class="col-sm-6 col-xs-6">
          <div class="form-group">
              <div class="col-sm-6 col-xs-6">
            <?= $form->field($model, 'sort_order')->textInput(['maxlength' => true,'class'=>'form-control form-control-line']) ?>
          </div>
        </div>
      </div>
             <div class="col-sm-6 col-xs-6">
                <div class="form-group">
                  <div class="dict-cat-img">
                  <img src="<?=$model->getProfileUrl()?>" />
                </div>
                  <div class="col-sm-6 col-xs-6">
                    <?= $form->field($modelImage, 'uploaded_files')->fileInput(['class'=>'form-control form-control-line','placeholder' => 'Choose flag icon'])->label('Image');?>
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
     
              
		<div class="col-sm-8 col-xs-8">
         	<div class="form-group">
            	<div class="col-sm-8 col-xs-8">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    	</div>
    	</div>
    	</div>
       </div>

    		<?php ActiveForm::end(); ?>

			</div>
		</div>
	</div>
</div>
