<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\depdrop\DepDrop;
use yii\helpers\Url;
use kartik\date\DatePicker;
/* @var $this yii\web\View */
/* @var $model backend\models\Account */
/* @var $form yii\widgets\ActiveForm */
foreach($params as $param => $val)
  ${$param} = $val;


?>
<div class="row bg-title">
  <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
    <h4 class="page-title">Create Roles</h4>
  </div>
  <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
  </div>
  <div class="col-lg-4 col-sm-8 col-md-8 col-xs-12">
   <?php
   $breadcrumb[]  = ['label' => 'Roles', 'url' => ['rbac/roles-index']];
   $this->title =  'Create roles';

$breadcrumb[] = ['label' => $this->title,'template' => "<li class='active' , style='color:#4AAFF4;'>{link}</li>\n"];
   ?>
   <?=$this->render('/layouts/breadcrumbs',[ 'links'=>$breadcrumb]);?>
 </div>
</div>
<div class="col-md-12 col-sm-12">
  <div class="white-box">
    <div class="row">
      <div class="col-sm-12 col-xs-12">
        <?php
          echo $this->render('assign-parent-roles',['params'=>$params]);
         ?>
      </div>
    </div>
  </div>
</div>
<div class="col-md-12 col-sm-12" style="margin-top: 10px;">
  <div class="white-box">
    <div class="row">
      <div class="col-sm-12 col-xs-12">

        <?php $form = ActiveForm::begin(['action' =>['update-roles','name'=>$name]]);?>
        <h3>Permissions</h3>
        <br>
        <?php foreach ($controllerList as $controllerId => $controller){?>
          <div class="row">
            <div class="col-sm-12 col-xs-12">
              <div class="form-group">
                <div class="col-sm-6 col-xs-6">
                  <h4><?=ucfirst($controller)?></h4>
                  <div class="row">
                    <div class="col-sm-12 col-xs-12">
                      <?php
              				$list = ArrayHelper::map(\backend\models\AuthAction::find()->where(['status'=>1])->andWhere(['auth_controllers_id'=>$controllerId])->all(), 'id', 'name');
                      if($list){
                        $permissionName = [];
                        $permissionId = [];
                        foreach ($list as $key => $value) {
                          $permissionName[] = $controller."-".$value;
                        }
                        $arrayIntersect = array_intersect($permissionName, $permissionArray);
                        if($arrayIntersect){
                          foreach ($arrayIntersect as $key => $value) {
                            $length = strlen($controller)+1;
                            $str = substr($value,$length);
                            $var = ltrim($str,"-");
                            $id = $controllerId;
                            $authAction = \backend\models\AuthAction::find()->where(['auth_controllers_id'=>$id])->andWhere(['name'=>$str])->all();
                            foreach ($authAction as $key => $model) {
                              $actionId[] = $model->id;
                            }
                          }
                        }
                        if(isset($actionId)){
                          // print_r($actionId);
                          $modelAuthAction->action_id[$controller] = $actionId;
                        }
                        echo $form->field($modelAuthAction, 'action_id['.$controller.']')->checkboxList($list, ['class' => '','id'=>'action-id-'.$controllerId,'style'=>''])->label(false);
                      }else{
                        echo "No items to list.";
                      }?>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        <?php }
        ?>
        <div class="row">
          <div class="col-sm-6 col-xs-6">
            <div class="form-group">
              <div class="col-sm-6 col-xs-6">
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
<div class="col-md-12 col-sm-12" style="margin-top: 10px;">
  <div class="white-box">
    <div class="row">
      <div class="col-sm-12 col-xs-12">
        <?php
          echo $this->render('assign-association',['params'=>$params]);
         ?>
      </div>
    </div>
  </div>
</div>
<?php

$title = isset($title)?$title:'Success';
$type = isset($type)?$type:'success';
$message = isset($message)?$message:'Access Controller has been added successfully.';
$title = Html::encode(trim($title));
$message = Html::encode(trim($message));
$title =  $title;
$message =  $message;
if (isset($saved) && $saved == 1):
  $this->registerJs("
  swal({title:'Success',text: '$message', type:'$type'});
  $.pjax.reload('#pjax-roles-list');
  ");
endif ;

?>
