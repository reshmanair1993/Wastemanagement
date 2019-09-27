<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use backend\models\WasteCategory;
use yii\web\View;
/* @var $this yii\web\View */
/* @var $model backend\models\Service */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="row bg-title">
  <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
    <h4 class="page-title">Services</h4>
  </div>
  <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
  </div>
  <div class="col-lg-4 col-sm-8 col-md-8 col-xs-12">
   <?php
   $breadcrumb[]  = ['label' => 'Services', 'url' => ['/services/index']];
   $this->title = Yii::t('app', 'Create Services');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Services'), 'url' => ['index']];
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
     <div class="col-sm-6 col-xs-6">
            <div class="form-group">
                <div class="col-sm-6 col-xs-6">
                    <?=$form->field($model, 'sort_order')->textInput(['maxlength' => true,'class'=>'form-control form-control-line']);?>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xs-6">
          <div class="form-group">
            <div class="col-sm-6 col-xs-6">
              <?= $form->field($model, 'is_special_service')->dropDownList([1=>'Yes',0=>'No'], ['prompt' => 'Select from the list','class'=>'form-control form-control-line'])?>
            </div>
          </div>
        </div>
      
    </div>
    <div class="row">
      <div class="col-sm-6 col-xs-6">
            <div class="form-group">
                <div class="col-sm-6 col-xs-6">
                    <?php $category= WasteCategory::getCategory();
                    $listData=ArrayHelper::map($category, 'id', 'name');
                    echo $form->field($model, 'waste_category_id')->dropDownList($listData, ['prompt' => 'Select from the list','class'=>'form-control form-control-line category_select','id' => 'category_select'])->label('Waste Category')?>
                </div>
            </div>
        </div>
    	<div class="col-sm-6 col-xs-6">
         	<div class="form-group">
            	<div class="col-sm-6 col-xs-6">
				<?php 
				$list = ArrayHelper::map(\backend\models\WasteCollectionMethod::find()->where(['status'=>1])->andWhere(['waste_category_id'=>$model->waste_category_id])->all(), 'id', 'name');
				echo $form->field($model, 'waste_collection_method')->checkboxList($list, ['class' => 'form-control type_select' ,'style'=>'height:100%'])->label('Waste Collection Methods Available for Service') ?>
 				</div>
 			</div>
 		</div>
            
      </div>
     <div class="col-sm-12 col-xs-12">
                <div class="form-group">
                  <div class="dict-cat-img">
                  <img src="<?=$model->getProfileUrl()?>" />
                </div>
                  <div class="col-sm-12 col-xs-6">
                    <?= $form->field($modelImage, 'uploaded_files')->fileInput(['class'=>'form-control form-control-line','placeholder' => 'Choose flag icon'])?>
                 </div>
               </div>
             </div>    
               <div class="col-sm-6 col-xs-6">
                <div class="form-group">
                    <div class="col-sm-6 col-xs-6">
                      <?= $form->field($model, 'is_quantity_entering_enabled')->checkbox(); ?>
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

    		<?php ActiveForm::end(); ?>

			</div>
		</div>
	</div>
</div>
<?php
$this->registerJs("
 $('.category_select').on('change',function(){
            var cat = $('.category_select').val();
            if(cat){
                $.ajax({ 
                    url:'waste-category-type-ajax',
                    data:{cat:cat},
                    method:'POST',
                    success: function(data) {
                        var target = $('.type_select');
                        target.empty();
                        $.each(data , function(key , value){
                            // var input = '<option value='+key+' >'+value+'</option>';
                          var checkbox='checkbox';
                          method='Service[waste_collection_method][]';
                            var input = '<input type='+checkbox+' value='+key+' name='+method+'>'+value+'</key>';
                            target.append(input);
                        })
                    }
                });
            }
        })
", View::POS_END);
 ?>