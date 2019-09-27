<?php
use yii\widgets\Pjax;
use yii\widgets\ActiveForm;
use backend\models\AuthItem;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ListView;
 $userRole = Yii::$app->user->identity->role;
$query = AuthItem::find()
      ->leftjoin('auth_item_child','auth_item_child.child=auth_item.name')
      ->where(['auth_item_child.parent'=>$userRole])
      ->andWhere(['auth_item.type'=>1])
      ->all();
      if($query){
        foreach ($query as $qry) {
          $roleList[$qry->name] = $qry->name;
        }
      }else{
        $roleList = [];
      }

	Pjax::begin(['timeout' => 50000 ,'enablePushState' => false, 'id' =>'Pjax-add-lsgi-escalation-settings','options'=>['data-loader'=>'.preloader']]);
	$form = ActiveForm::begin(['action' => ['add-escalation-settings','id'=>$model->id],'options' => ['','data-pjax' => true,'class' => 'form-horizontal form-material','enableAjaxValidation' => false,'enableClientValidation'=>true]]);
	?>
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 add-tip-form" style="background: #f9f9f9; border: 1px solid #eee; padding-top: 10px; padding-bottom: 10px;">
	<div class="row">
	<div class="col-sm-6 col-xs-6">
             <div class="form-group">
               <div class="col-sm-6 col-xs-6">
                 <?php
                   echo $form->field($modelEscalationSettings, 'role')->dropDownList($roleList, ['prompt' => 'Select from the list','id'=>'role-name','class'=>'form-control form-control-line'])->label('Role');
                   ?>
               </div>
             </div>
           </div>
        <div class="col-sm-6 col-xs-6">
            <div class="form-group">
                <div class="col-sm-6 col-xs-6">
                    <?=$form->field($modelEscalationSettings, 'complaint_escalation_min')->textInput(['maxlength' => true,'class'=>'form-control form-control-line']);?>
                </div>
            </div>
        </div>
         </div>
    <div class="row">
         <div class="col-sm-6 col-xs-6">
            <div class="form-group">
                <div class="col-sm-6 col-xs-6">
                    <?=$form->field($modelEscalationSettings, 'service_escalation_min')->textInput(['maxlength' => true,'class'=>'form-control form-control-line']);?>
                </div>
            </div>
        </div>
        
	<div class="col-sm-6 col-xs-6">
            <div class="form-group">
                <div class="col-sm-6 col-xs-6">
	<?= Html::submitButton(Yii::t('app', 'Add'), ['class' => 'btn btn-success pull-right']);
	?>
	</div>
	</div>
	</div>
	</div>
	<?php
	 ActiveForm::end();
	?>
	</div>
	<table id="demo-foo-addrow" class="table table-hover footable-loaded footable">
	<?php if($escalationSettingsDataProvider->getCount() > 0): ?>
		<thead>
			<tr class="footable-sortable">
				<th>#</th>
				<th>Role</th>
				<th>Complaint Escalation Minutes</th>
				<th>Service Escalation Minutes</th>
				<th>Edit</th>
			
				<th><a href="" data-sort="status">Delete</a></th>
			</tr>
			<tr id="w1-filters" class="hidden">
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td><input type="text" class="form-control" name="Audio[status]"></td>
			</tr>
		</thead>
	<?php endif; ?>
		<tbody>
	<?php
		echo ListView::widget([
		    'dataProvider' => $escalationSettingsDataProvider,
		    'options' => [
		        'tag' => 'div',
		        'class' => 'list-wrapper',
		        'id' => 'list-wrapper',
		    ],
		    'layout' => "{items}",
		    'itemView' => function ($model, $key, $index, $widget) {
		    	return $this->render('escalation_settings_list', [
		    		'model' => $model,
		    		'index' => $index+1,
		    	]);
		    },
		]);
	?>
	</tbody>
 </table>
 <?php Pjax::end(); ?>