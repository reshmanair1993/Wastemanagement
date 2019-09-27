<?php

use yii\helpers\Html;
// use yii\grid\GridView;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use backend\models\Ward;
use backend\models\Camera;
use backend\models\Account;
use backend\models\MonitoringGroup;
use yii\web\View;
use kartik\depdrop\DepDrop;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
$scrollingTop = 30;
$this->title = Yii::t('app', 'Camera List');
$this->params['breadcrumbs'][] = $this->title;
// print_r($saved);exit;
?>
<div class="camera-index">

    <div class="row bg-title">
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
          <h1><?= Html::encode($this->title) ?></h1>
        </div>
        <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
          <?php
          $breadcrumb[] = ['label' => $this->title,];

          ?>
          <?=$this->render('/layouts/breadcrumbs',[ 'links'=>$breadcrumb]);?>
        </div>
        <div class="col-lg-3">
         <p>
        <?= Html::a(Yii::t('app', 'Create'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    </div>
        <div class="col-lg-12 col-sm-12 col-md-6 col-xs-12">
          <div class="col-lg-8 col-sm-8 col-md-6 col-xs-12">
            <?php
             $form = ActiveForm::begin(['action' => 'index','options' => ['','data-pjax' => true,'class' => 'page-main-form search-form app-search hidden-sm hidden-xs m-r-10']]);
            // print_r($associations['lsgi_id']);exit;
            ?>
            <?php if(!isset($associations['lsgi_id'])):?>
            <div class="col-lg-4 col-sm-4 col-md-4 col-xs-12">
              <label>Lsgi</label>
              <div class="form-group" style=" margin-top: -13px;">
                <?php
                $params = array_merge(Yii::$app->request->post(),Yii::$app->request->get());
                $options = ['class'=>'form-control btn-behaviour-filter','id'=>'lsgi-id','prompt' => 'Lsgi..','name'=>'lsgi'];
                $key =  isset($_POST['lsgi'])?$_POST['lsgi']:'';

                if(isset($key)) {
                $option = $key;
                $options['options'] = [$option => ['selected'=>'selected']];
                }

                $lsgi=Account::getLsgi();
                $listData=ArrayHelper::map($lsgi, 'id', 'name');

                echo $form->field($modelCamera, 'lsgi_id')->dropDownList($listData, $options)->label(false)?>
              </div>
            </div>
          <?php endif;?>
          <?php  if(!isset($associations['wardId'])):?>
            <div class="col-lg-4 col-sm-4 col-md-4 col-xs-12">
              <label>Ward</label>
              <div class="form-group" style=" margin-top: -13px;">
                <?php
                $params = array_merge(Yii::$app->request->post(),Yii::$app->request->get());
                $options = ['class'=>'form-control btn-behaviour-filter','id'=>'ward-id','prompt' => 'Ward..','name'=>'ward'];
                $key =  isset($_POST['ward'])?$_POST['ward']:'';
                $lsgi_id =  isset($_POST['lsgi'])?$_POST['lsgi']:'';

                if(isset($key)) {
                $option = $key;
                $options['options'] = [$option => ['selected'=>'selected']];
                }
                if(isset($associations['lsgi_id'])){
                    $lsgi_id = $associations['lsgi_id'];
                    $ward = Camera::getWards($lsgi_id);
                }
                else{
                  $ward = Camera::getWards($lsgi_id);
                }
                $listData = ArrayHelper::map($ward, 'id', 'name');
                // print_r($lsgi_id);exit;
                echo $form->field($modelCamera, 'wardId')->dropDownList($listData, $options)->label(false)?>
              </div>
            </div>
            <?php endif;?>
            <?php  if(!isset($associations['group_id'])):?>
            <div class="col-lg-4 col-sm-4 col-md-4 col-xs-12">
              <label>Group</label>
              <div class="form-group" style=" margin-top: -13px;">
                <?php
                $params = array_merge(Yii::$app->request->post(),Yii::$app->request->get());

                $options = ['class'=>'form-control btn-behaviour-filter','prompt' => 'Group..','name'=>'group'];
                $key =  isset($_POST['group'])?$_POST['group']:'';

                if(isset($key)) {
                $option = $key;
                $options['options'] = [$option => ['selected'=>'selected']];
                }
                $modelUser  = Yii::$app->user->identity;
                $group=MonitoringGroup::getGroup($modelUser->id);
                $listData=ArrayHelper::map($group, 'id', 'name');

                echo $form->field($modelCamera, 'group_id')->dropDownList($listData, $options)->label(false)?>
              </div>
            </div>
          <?php endif;?>
            <?php ActiveForm::end(); ?>
          </div>
        </div>
      </div>
       <br>
       <div class="row">
      <div class="col-md-12">
        <div class="white-box">
          <div class="scrollable">
            <div class="table-responsive">

              <?php Pjax::begin(['timeout' => 50000,'enablePushState' => false,
                                    'id' =>'pjax-camera-list', 'options'=>['data-loader'=>'.preloader']]); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        // 'layout' => '{items}',
        // 'layout' => "{items}\n{pager}",
        'columns' => [
            [
              'attribute' => 'name',
              'label' =>'Camera Name',
              'contentOptions'=>[ 'style'=>'width: 250px'],
              'format' => 'raw',
              'value'=>function ($model) {
                $page = isset($_GET['page']) ? $_GET['page']:1;
                return Html::a(ucfirst($model->name),['incidents/index?id='.$model->id],['data-pjax'=>0]);
              },
            ],
            [
              'label' =>'Ward',
              'format' => 'raw',
              'value' =>  function ($model) {
                $modelWard = $model->getWardName();
                if($modelWard)
                  return $modelWard;
                else
                  return 'Nil';
              }
              // 'value'=> 'fkWard.name',
            ],
            // [
            //   'label' =>'Qr Code',
            //   'format' => 'raw',
            //   'value' =>  function ($model) {
            //     if($model->fkQrCode)
            //       return $model->fkQrCode->value;
            //     else
            //       return 'Nil';
            //   }
            //   // 'value'=> 'fkWard.name',
            // ],
            [
                    'attribute' => 'code',
                    'label' =>'Code',
                    'contentOptions'=>[ 'style'=>'width: 250px'],
                    'format' => 'raw',
                    'value'=>function ($model) {
                      $page = isset($_GET['page']) ? $_GET['page']:1;
                       if($model->fkQrCode){
                       $path = 'https://www.qr-code-generator.com/phpqrcode/getCode.php?cht=qr&chl='.$model->fkQrCode->value.'&chs=200x200&choe=UTF-8&chld=L|0';
                      $type = pathinfo($path, PATHINFO_EXTENSION);
                      $data = file_get_contents($path);
                      $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                      return '<img style="width: 100px;" src="'.$base64.'" /><br>'.$model->fkQrCode->value;
                    }
                    else
                    {
                  return 'Nil';
              }
                    },
                  ],
                  // 'value',

            [
              'label' =>'Added Technician',
              'format' => 'raw',
              'value' =>  function ($model) {
                $modelTechnician = $model->getTechnicianName();
                if($modelTechnician)
                  return $modelTechnician;
                else
                  return 'Nil';
              },
              // 'value'=> 'fkAccountTechnician.username',
            ],
            [
              'label' =>'Serial No.',
              'format' => 'raw',
              'value'=>   'serial_no',
            ],
            [
              'label' =>'Group',
              'format' => 'raw',
              'value'=>   function ($model) {
                $modelGroups = $model->getMonitoringGroups();
                // return implode(', ',$modelGroups);
                return count($modelGroups);
              },
            ],
            [
                    'attribute' => 'image_id',
                    'format' => 'raw',
                    'label' => 'Photo',
                    'value'=>function ($model) {
                       return Html::img($model->getProfileUrl(),
                ['width' => '70px','height'=>'50px']);
                      // return $model->getProfileUrl();
                    }

                  ],
            [
               'attribute' => 'delete',
               'label' =>'Delete',
               'contentOptions'=>[ 'style'=>'width: 50px'],
               'format' => 'raw',
               'value'=>function ($model) {
                $page = isset($_GET['page']) ? $_GET['page']:1;
                $page = $page;
                $url =  Yii::$app->urlManager->createUrl(['camera/delete-camera','id'=>$model->id]);

                return  "<a  onclick=\"ConfirmDelete(function(){
                  deleteItem('$url','#pjax-camera-list',$page,function() {
                  });

                })\"  class='btn btn-sm btn-icon btn-pure btn-outline delete-row-btn'><i aria-hidden='true' class='ti-close'></i></a>";

              },
            ],
        ],
        'containerOptions' => ['style'=>'overflow: auto'], // only set when $responsive = false
        // 'beforeHeader'=>[
        //     [
        //         'columns'=>[
        //             ['content'=>'Header Before 1', 'options'=>['colspan'=>4, 'class'=>'text-center warning']],
        //             ['content'=>'Header Before 2', 'options'=>['colspan'=>4, 'class'=>'text-center warning']],
        //             ['content'=>'Header Before 3', 'options'=>['colspan'=>3, 'class'=>'text-center warning']],
        //         ],
        //         'options'=>['class'=>'skip-export'] // remove this row from export
        //     ]
        // ],
        'toolbar' =>  [
            [
            // 'content'=>
            //     Html::button('&lt;i class="glyphicon glyphicon-plus">&lt;/i>', ['type'=>'button', 'title'=>Yii::t('kvgrid', 'Add Book'), 'class'=>'btn btn-success', 'onclick'=>'alert("This will launch the book creation form.\n\nDisabled for this demo!");']) . ' '.
            //     Html::a('&lt;i class="glyphicon glyphicon-repeat">&lt;/i>', ['grid-demo'], ['data-pjax'=>0, 'class' => 'btn btn-default', 'title'=>Yii::t('kvgrid', 'Reset Grid')])
            ],
            '{export}',
            '{toggleData}'
        ],
        'pjax' => true,
        'bordered' => true,
        'striped' => false,
        'condensed' => false,
        'responsive' => true,
        'hover' => true,
        'floatHeader' => true,
        'floatHeaderOptions' => ['scrollingTop' => $scrollingTop],
        'showPageSummary' => true,
        'panel' => [
            'type' => GridView::TYPE_PRIMARY
        ],
    ]); ?>
    <?phpPjax::end();?>
    <?php
      $title = isset($title)?$title:'Success';
      $type = isset($type)?$type:'success';
      $message = isset($message)?$message:'Camera has been added successfully';
      $title = Html::encode(trim($title));
      $message = Html::encode(trim($message));
      $title =  $title;
      $message =  $message; //but need to escape apppstrope
      // print_r($showSuccess);exit;
      if ($showSuccess == 1):
        $this->registerJs("
        swal({title:'$title',text: '$message', type:'$type'});
        ");
      endif ;
      if ($updateSuccess == 1):
        $this->registerJs("
        swal({title:'Success',text: 'Camera has been updated successfully', type:'$type'});
        ");
      endif ;
    ?>
  </div>
  <?php
  $this->registerJs("
  $('.btn-behaviour-filter').on('change', function() {
  $('.search-form').submit();

  });
  $('.datepicker').datepicker({
          orientation:'top',
          format:'dd-mm-yyyy',
          autoclose:true,
          todayHighlight:true,
      });
  ",View::POS_END);
  ?>
</div>
</div>
</div>
</div>
</div>
