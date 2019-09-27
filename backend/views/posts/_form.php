<?php

    use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\depdrop\DepDrop;
use yii\helpers\Url;
use yii\web\JsExpression;
use dosamigos\ckeditor\CKEditor;
use backend\models\Image;
use yii\web\View;
$modelImage = new Image;
    /* @var $this yii\web\View */
    /* @var $model backend\models\Isgi */
    /* @var $form yii\widgets\ActiveForm */
    $modelUser  = Yii::$app->user->identity;
    $userRole = $modelUser->role;
?>
<div class="row bg-title">
  <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
    <h4 class="page-title">Post</h4>
  </div>
  <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
  </div>
  <div class="col-lg-4 col-sm-8 col-md-8 col-xs-12">
   <?php
   $breadcrumb[]  = ['label' => 'Post', 'url' => ['/posts/index','type'=>$type]];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Posts'), 'url' => ['index']];
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
    <?php if(!$model->id){
          $form = ActiveForm::begin([
            'action' =>['create','type'=>$type],
            'options' => ['','data-pjax' => true,'class' => 'form-horizontal form-material','enctype' => 'multipart/form-data', 'enableAjaxValidation' => true]]);
          }else{
            $form = ActiveForm::begin([
              'action' =>['update','id'=>$model->id,'type'=>$type],
              'options' => ['','data-pjax' => true,'class' => 'form-horizontal form-material','enctype' => 'multipart/form-data', 'enableAjaxValidation' => true]]);
          }?>
          <div class="row">
        <div class="col-sm-6 col-xs-6">
            <div class="form-group">
                <div class="col-sm-6 col-xs-6">
                    <?=$form->field($model, 'name')->textInput(['maxlength' => true,'class'=>'form-control form-control-line']);?>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xs-6">
            <div class="form-group">
                <div class="col-sm-6 col-xs-6">
                    <?php $type= $model->getTypes();
                    $listData=ArrayHelper::map($type, 'id', 'name');
                    echo $form->field($model, 'post_type')->dropDownList($listData, ['prompt' => 'Select from the list','class'=>'form-control form-control-line category_select','id' => 'category_select'])?>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xs-6">
            <div class="form-group">
                <div class="col-sm-6 col-xs-6">
                    <?=$form->field($model, 'title_ml')->textInput(['maxlength' => true,'class'=>'form-control form-control-line']);?>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xs-6">
            <div class="form-group">
                <div class="col-sm-6 col-xs-6">
                    <?=$form->field($model, 'title_en')->textInput(['maxlength' => true,'class'=>'form-control form-control-line']);?>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xs-6">
            <div class="form-group">
                <div class="col-sm-6 col-xs-6">
                    <?=$form->field($model, 'sub_title_en')->textInput(['maxlength' => true,'class'=>'form-control form-control-line']);?>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xs-6">
            <div class="form-group">
                <div class="col-sm-6 col-xs-6">
                    <?=$form->field($model, 'sub_title_ml')->textInput(['maxlength' => true,'class'=>'form-control form-control-line']);?>
                </div>
            </div>
        </div>
        <!-- <div class="col-sm-6 col-xs-6">
            <div class="form-group">
                <div class="col-sm-6 col-xs-6">
                    <?=$form->field($model, 'lat')->textInput(['maxlength' => true,'class'=>'form-control form-control-line']);?>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xs-6">
            <div class="form-group">
                <div class="col-sm-6 col-xs-6">
                    <?=$form->field($model, 'lng')->textInput(['maxlength' => true,'class'=>'form-control form-control-line']);?>
                </div>
            </div>
        </div> -->
       <div class="col-sm-6 col-xs-6">
            <div class="form-group">
                <div class="col-sm-6 col-xs-6">
    <label for="location">Choose Location</label>
    <?= $form->field($model, 'location_name')->textInput(['class' => 'form-control height-auto', 'placeholder' => '', 'id' => 'location'])->label(false) ?>
  </div>
  </div>
  </div>
  <div class="col-sm-6 col-xs-6">
            <div class="form-group">
                <div class="col-sm-6 col-xs-6">
    <label for="location">Latitude</label>
    <?= $form->field($model, 'lat')->textInput(['class' => 'form-control height-auto', 'placeholder' => '', 'id' => 'up-lat'])->label(false) ?>
  </div>
  </div>
  </div>
  <div class="col-sm-6 col-xs-6">
            <div class="form-group">
                <div class="col-sm-6 col-xs-6">
    <label for="location">Longitude</label>
    <?= $form->field($model, 'lng')->textInput(['class' => 'form-control height-auto', 'placeholder' => '', 'id' => 'up-lng'])->label(false) ?>
  </div>
  </div>
  </div>
</div>
<div class="col-sm-8 col-xs-8">
            <div class="form-group">
                <div class="col-sm-8 col-xs-8">
<?php
echo $form->field($model, 'map_input')->widget('\pigolab\locationpicker\CoordinatesPicker' , [
  'key' => 'AIzaSyBnG4QUmKyu5PVqlMjlYnml5KAht7eVtow',
  'valueTemplate' => '{latitude},{longitude}',
  'options' => [
    'style' => 'width: 100%; height: 400px; border: 1px solid #FF001F',
    ] ,
    'enableSearchBox' => true ,
    'searchBoxPosition' => new JsExpression('google.maps.ControlPosition.TOP_LEFT'),
    'mapOptions' => [
      'mapTypeControl' => true,
      'mapTypeControlOptions' => [
        'style'    => new JsExpression('google.maps.MapTypeControlStyle.HORIZONTAL_BAR'),
        'position' => new JsExpression('google.maps.ControlPosition.TOP_LEFT'),
      ],
      'streetViewControl' => true,
    ],
    'clientOptions' => [
      'location' => [
                'latitude'  => $model->lat,
                'longitude' => $model->lng,
            ],
      'radius'    => 300,
      'addressFormat' => 'street_number',
      'inputBinding' => [
        'latitudeInput'     => new JsExpression("$('#up-lat')"),
        'longitudeInput'    => new JsExpression("$('#up-lng')"),
        'locationNameInput' => new JsExpression("$('#location')")
      ],
    ],
  ]);
  ?>
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
             <div class="col-sm-12 col-xs-12">
            <div class="form-group">
                <div class="col-sm-12 col-xs-12">
                    <?= $form->field($model, 'description_en')->widget(CKEditor::className(), [
        'options' => ['rows' => 6],
        'preset' => 'advanced'
    ]) ?>
                </div>
            </div>
        </div>
         <div class="col-sm-12 col-xs-12">
            <div class="form-group">
                <div class="col-sm-12 col-xs-12">
                    <?= $form->field($model, 'description_ml')->widget(CKEditor::className(), [
        'options' => ['rows' => 6],
        'preset' => 'advanced'
    ]) ?>
                </div>
            </div>
        </div>
        <div class="col-sm-12 col-xs-12">
            <div class="form-group">
                <div class="col-sm-12 col-xs-12">
                    <?= $form->field($model, 'short_description_en')->widget(CKEditor::className(), [
        'options' => ['rows' => 6],
        'preset' => 'advanced'
    ]) ?>
                </div>
            </div>
        </div>
         <div class="col-sm-12 col-xs-12">
            <div class="form-group">
                <div class="col-sm-12 col-xs-12">
                    <?= $form->field($model, 'short_description_ml')->widget(CKEditor::className(), [
        'options' => ['rows' => 6],
        'preset' => 'advanced'
    ]) ?>
                </div>
            </div>
        </div>
         
         <div class="col-sm-6 col-xs-6">
                        <div class="form-group">
                            <div class="col-sm-6 col-xs-6">
            <?=Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']);?>
            </div>
            </div>
        </div>
        </div>

    <?php ActiveForm::end();?>

            </div>
        </div>
    </div>
</div>

<?=$this->registerJs("
  CKEDITOR.replace('CmsPages[description_en]', {
      extraPlugins: 'docprops',
      allowedContent: true,
      height: 320
    });

  CKEDITOR.replace('CmsPages[description_ml]', {
    extraPlugins: 'docprops',
    allowedContent: true,
    height: 320
  });

  CKEDITOR.replace('CmsPages[short_description_en]', {
    extraPlugins: 'docprops',
    allowedContent: true,
    height: 320
  });

  CKEDITOR.replace('CmsPages[short_description_ml]', {
    extraPlugins: 'docprops',
    allowedContent: true,
    height: 320
  });
    ",View::POS_END);?>