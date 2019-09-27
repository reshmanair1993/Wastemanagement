<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\widgets\ActiveForm;
use yii\db\Query;
use yii\helpers\Url;
use backend\models\Country;
use yii\jui\AutoComplete;
use yii\web\JsExpression;
use yii\helpers\ArrayHelper;
use yii\web\View;
use kartik\date\DatePicker;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\TbNewslettersubscriberSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
foreach($params as $param => $val)
  ${$param} = $val;
// $peopleList = ArrayHelper::map($persons,'id','first_name');
$userList = ArrayHelper::map($users,'id','username');
$tourList = ArrayHelper::map($tours,'id','title');
$visaList = ArrayHelper::map($visa,'id','title');

?>

          <div class="row bg-title">
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                  <h4 class="page-title">Payment Links</h4>
              </div>
              <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
              </div>
                <div class="col-lg-4 col-sm-8 col-md-8 col-xs-12">

                     <?php
                       $breadcrumb[]  = ['label' => 'Payment Links', 'url' => '../payment-links?page='.$page];
                      $this->title =  $modelPaymentLink['id']?ucfirst($modelPaymentLink->notes_from):'Create';
                     $breadcrumb[] = ['label' => $this->title,'template' => "<li class='active' , style='color:#4AAFF4;'>{link}</li>\n"];

                      ?>
                    <?=$this->render('/layouts/breadcrumbs',[ 'links'=>$breadcrumb]);?>
                </div>
          </div>
        <?= $this->render('/common/loading',['class'=>'preloader']);?>
<?php Pjax::begin(['timeout' => 50000 ,'enablePushState' => false,
        					'id' =>'Pjax-payment-link-add','options'=>['data-loader'=>'.preloader']]);?>
<?php
if($modelPaymentLink->id)
	$form = ActiveForm::begin(['action' => ['view','id'=>$modelPaymentLink->id],'options' => ['','data-pjax' => true,'class' => 'form-horizontal form-material','enableAjaxValidation' => false,'enableClientValidation'=>true]]);
else
 	$form = ActiveForm::begin(['action' => 'create','options' => ['','data-pjax' => true,'class' => 'form-horizontal form-material','enableAjaxValidation' => false,'enableClientValidation'=>true]]);
?>
  <br><div class="col-md-12 col-sm-12">
      <div class="white-box">
        <div class="row">
          <div class="col-sm-12 col-xs-12">
            <div class="col-sm-12 col-xs-12">
              <div class="form-group">
                <!-- <div class="col-md-12"> -->
                  <label for="">Email</label>
                <?=
                 AutoComplete::widget([
                   'model' => $modelPerson,
                   'attribute' => 'email',
                   'clientOptions' => [
                     'source' => Url::to(['payment-links/get-people']),
                     'select'=> new JsExpression("function( event, ui ) {
                                $('#first-name').val(ui.item.first_name);
                                $('#middle-name').val(ui.item.middle_name);
                                $('#last-name').val(ui.item.last_name);
                                $('#phone').val(ui.item.phone);
                                }"),
                     'autoFill'=>true,
                     // 'label' => 'Email',
                   ],
                   // 'label' => 'Email',
                   'options'=>[
                     'id' => 'email',
                     'tabindex'=> '4',
                     'class' => 'form-control',
                     'data-attr-name' => 'email',
                     'data-textbox-val-attr' =>'email',
                     'placeholder' =>'Enter your email',
                     // 'label' =>'Email',
                   ],
                 ]);?>

                <!-- </div> -->
              </div>
            </div>
            <div class="col-sm-12 col-xs-12">
              <div class="form-group">
                <div class="col-md-12">
                  <?= $form->field($modelPerson, 'first_name')->textInput(['id'=>'first-name','maxlength' => true,'class'=>'form-control form-control-line','placeholder' => 'Enter first name'])->label('First Name')?>
                </div>
              </div>
            </div>
            <div class="col-sm-12 col-xs-12">
              <div class="form-group">
                <div class="col-md-12">
                  <?= $form->field($modelPerson, 'middle_name')->textInput(['id' => 'middle-name','maxlength' => true,'class'=>'form-control form-control-line','placeholder' => 'Enter middle name'])->label('Middle Name')?>
                </div>
              </div>
            </div>
            <div class="col-sm-12 col-xs-12">
              <div class="form-group">
                <div class="col-md-12">
                  <?= $form->field($modelPerson, 'last_name')->textInput(['id' => 'last-name','maxlength' => true,'class'=>'form-control form-control-line','placeholder' => 'Enter last name'])->label('Last Name')?>
                </div>
              </div>
            </div>
            <div class="col-sm-12 col-xs-12">
              <div class="form-group">
                <div class="col-md-12">
                  <?= $form->field($modelPerson, 'phone1')->textInput(['id' => 'phone','maxlength' => true,'class'=>'form-control form-control-line','placeholder' => 'Enter last phone number'])->label('Phone Number')?>
                </div>
              </div>
            </div>
            <!-- <div class="col-sm-12 col-xs-12">
              <div class="form-group">
                <div class="col-md-12">
                     <?php
                      //$form->field($modelPaymentLink, 'fk_account_frontend')->dropDownList($userList,['prompt'=>'Select','maxlength' => true,'class'=>'form-control form-control-line',])->label('Email')
                      ?>
                </div>
              </div>
            </div> -->
            <!-- <div class="col-sm-12 col-xs-12">
              <div class="form-group">
                <div class="col-md-12">
                     <?php //= $form->field($modelPaymentLink, 'fk_person')->dropDownList($peopleList,['prompt'=>'Select','maxlength' => true,'class'=>'form-control form-control-line',])->label('Name') ?>
                </div>
              </div>
            </div> -->
             <div class="col-sm-12 col-xs-12">
               <div class="form-group">
                 <div class="col-md-12">
                      <?= $form->field($modelPaymentLink, 'notes_from')->textArea(['maxlength' => true,'class'=>'form-control form-control-line','placeholder' => "Enter Notes",])->label('Notes From') ?>
                 </div>
               </div>
             </div>
             <div class="col-sm-12 col-xs-12">
               <div class="form-group">
                 <div class="col-md-12">
                      <?= $form->field($modelPaymentLink, 'fk_tour')->dropDownList($tourList,['prompt'=>'Select Tour','maxlength' => true,'class'=>'form-control form-control-line',])->label('Tour') ?>
                 </div>
               </div>
             </div>
             <div class="col-sm-12 col-xs-12">
               <div class="form-group">
                 <div class="col-md-12">
                      <?= $form->field($modelBooking, 'start_date')->widget(DatePicker::class, [
                              'pluginOptions' => [
                                'format' => 'yyyy-mm-dd',

                              ]

                              //'language' => 'ru',
                              //'dateFormat' => 'yyyy-MM-dd',
                          ],['maxlength' => true,'class'=>'form-control form-control-line',])->label('Tour Date')?>
                 </div>
               </div>
             </div>
             <div class="col-sm-12 col-xs-12">
               <div class="form-group">
                 <div class="col-md-12">
                      <?= $form->field($modelPaymentLink, 'fk_visa')->dropDownList($visaList,['prompt'=>'Select Visa','maxlength' => true,'class'=>'form-control form-control-line',])->label('Visa') ?>
                 </div>
               </div>
             </div>
             <div class="col-sm-12 col-xs-12">
               <div class="form-group">
                 <div class="col-md-12">
                      <?= $form->field($modelPaymentLink, 'expiry_ts')->widget(DatePicker::class, [
                              'pluginOptions' => [
                                'format' => 'yyyy-mm-dd',

                              ]

                              //'language' => 'ru',
                              //'dateFormat' => 'yyyy-MM-dd',
                          ],['maxlength' => true,'class'=>'form-control form-control-line',])->label('Expire Date')?>
                 </div>
               </div>
             </div>
             <div class="col-sm-12 col-xs-12">
               <div class="form-group">
                 <div class="col-md-12">
                      <?= $form->field($modelPaymentLink, 'amount')->textInput(['maxlength' => true,'class'=>'form-control form-control-line','placeholder' => "Enter Amount",])->label('Amount') ?>
                 </div>
               </div>
             </div>

            <?= Html::submitButton($modelPaymentLink->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $modelPaymentLink->isNewRecord ? 'btn btn-success pull-right' : 'btn btn-primary pull-right']) ?>

          </div>
        </div>
      </div>
    </div>
  </div>
  <?php ActiveForm::end(); ?>
<?php
if(isset($showSuccess) && $showSuccess) {
	$this->registerJs("
    //  alert(1);
	$('#Pjax-payment-link-add').on('pjax:end', function() {
        $.pjax.reload({container:'#pjax-payment-link-list'});
        $('.add-testimonial-modal').modal('hide');
   });

	",View::POS_END);
 }
?>
<?php Pjax::end();?>
