<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\ArrayHelper;
use yii\web\View;
use yii\widgets\ActiveForm;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
$modelUser  = Yii::$app->user->identity;
$userRole = $modelUser->role;
$this->title = Yii::t('app', 'Supervisor');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row bg-title">
    <div class="col-lg-2 col-md-4 col-sm-4 col-xs-12">
        <h4 class="page-title">Supervisor</h4>
    </div>


      <div class="col-lg-3 col-sm-8 col-md-8 col-xs-12">
           <?php   $this->title =  'Supervisor';
           $breadcrumb[] = ['label' => $this->title, 'template' => "<li class='active' , style='color:#4AAFF4;'>{link}</li>\n"];

            ?>
          <?=$this->render('/layouts/breadcrumbs',[ 'links'=>$breadcrumb]);?>

      </div>
      <?php if(Yii::$app->user->can('create-supervisor')||$userRole=='super-admin'):?>

      <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
           <p>
        <?= Html::a(Yii::t('app', 'Create Supervisor'), ['create-supervisor'], ['class' => 'btn btn-success']) ?>
    </p>
        </div>
      <?php endif;?>
      <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
        <?php $form = ActiveForm::begin(['action' => 'supervisors','options' => ['','data-pjax' => true,'class' => 'page-main-form search-form app-search hidden-sm hidden-xs m-r-10']]);?>
      <div class="col-lg-2 col-sm-2 col-md-8 col-xs-12">
        
           <?php  
           // $keyword =  Yii::$app->session->get('name'); 
           $keyword =  isset($_POST['name'])?$_POST['name']:''; 
           ?>
            <input type="text" name="name" value="<?php if (isset($keyword)) echo $keyword; ?>" style="background-color:#ccc;color:white;margin-top: 0px;" placeholder="Search..." class="form-control btn-behaviour-filter"> <a href="" class="active"></a>
            

      </div>
      <?php  if(!isset($associations['lsgi_id'])):?>
       <div class="col-lg-2 col-sm-2 col-md-8 col-xs-12">
            <div class="form-group" style=" margin-top: -13px;">
                 <?php 
      $params = array_merge(Yii::$app->request->post(),Yii::$app->request->get());

      $options = ['class'=>'form-control btn-behaviour-filter','prompt' => 'Lsgi..','name'=>'lsgi'];
      $key =  isset($_POST['lsgi'])?$_POST['lsgi']:''; 
      
      if(isset($key)) { 
        $option = $key;
        $options['options'] = [$option => ['selected'=>'selected']];
      }
            $lsgi=$modelAccount->getLsgi();
            $listData=ArrayHelper::map($lsgi, 'id', 'name');

            echo $form->field($modelAccount, 'lsgi_id')->dropDownList($listData, $options)->label(false)?>
            </div>
          </div>
        <?php endif;?>
           <?php  if(!isset($associations['hks_id'])):?>
          <div class="col-lg-3 col-sm-3 col-md-4 col-xs-12">
            <div class="form-group" style=" margin-top: -13px;">
                 <?php 
      $params = array_merge(Yii::$app->request->post(),Yii::$app->request->get());

      $options = ['class'=>'form-control btn-behaviour-filter','prompt' => 'Green Action Unit..','name'=>'unit'];
      $key =  isset($_POST['unit'])?$_POST['unit']:''; 
      
      if(isset($key)) { 
        $option = $key;
        $options['options'] = [$option => ['selected'=>'selected']];
      }
            $unit=$modelAccount->getGreenActionUnit();
            $listData=ArrayHelper::map($unit, 'id', 'name');

            echo $form->field($modelAccount, 'green_action_unit_id')->dropDownList($listData, $options)->label(false)?>
            </div>
          </div>
        <?php endif;?>
      <?php ActiveForm::end(); ?>
      </div>
</div>
<div class="row">
  <div class="col-md-12">
    <div class="white-box">
      <div class="scrollable">
        <div class="table-responsive">
  <?php Pjax::begin(['timeout' => 50000,'enablePushState' => false,
                      'id' =>'pjax-account-supervisor-list', 'options'=>['data-loader'=>'.preloader']]); ?>
    <?php 
    $columns = [
              [
                    'attribute' => 'username',
                    'label' =>'User Name',
                    'contentOptions'=>[ 'style'=>'width: 250px'],
                    'format' => 'raw',
                    'value'=>function ($model) {
                      $page = isset($_GET['page']) ? $_GET['page']:1;
                      $modelUser  = Yii::$app->user->identity;
                      $userRole = $modelUser->role;
                      if(Yii::$app->user->can('update-supervisor')||$userRole=='super-admin'):
                      return Html::a($model->username,['account/view-supervisor?id='.$model->id],['data-pjax'=>0]);
                    else:
                      return $model->username;
                    endif;
                    },
                  ],
                  [
                    'attribute' => 'fkPerson.first_name',
                    'label' => 'Name'
                  ],
                  [
                    'attribute' => 'fkPerson.phone1',
                    'label' => 'Phone'
                  ],
                  [
                    'attribute' => 'fkLsgi.name',
                    'label' => 'Lsgi'
                  ],
                  // [
                  //   'attribute' => 'is_banned',
                  //   'label' => 'Is Banned'
                  // ],
                  [
                   'attribute' => 'delete',
                   'label' =>'Delete',
                   'contentOptions'=>[ 'style'=>'width: 50px'],
                   'format' => 'raw',
                   'value'=>function ($model) {
                    $page = isset($_GET['page']) ? $_GET['page']:1;
                    $page = $page;
                    $url =  Yii::$app->urlManager->createUrl(['account/delete-account','id'=>$model->id]);

                    return  "<a  onclick=\"ConfirmDelete(function(){
                      deleteItem('$url','#pjax-account-supervisor-list',$page,function() {
                      });

                    })\"  class='btn btn-sm btn-icon btn-pure btn-outline delete-row-btn'><i aria-hidden='true' class='ti-close'></i></a>";

                  },
                ],
        ];
        if(Yii::$app->user->can('delete-supervisor')||$userRole=='super-admin'){
         $columns2 = [             
                  [
                   'attribute' => 'delete',
                   'label' =>'Delete',
                   'contentOptions'=>[ 'style'=>'width: 50px'],
                   'format' => 'raw',
                   'value'=>function ($model) {
                    $page = isset($_GET['page']) ? $_GET['page']:1;
                    $page = $page;
                    $url =  Yii::$app->urlManager->createUrl(['account/delete-account','id'=>$model->id]);

                    return  "<a  onclick=\"ConfirmDelete(function(){
                      deleteItem('$url','#pjax-account-admin-lsgi-list',$page,function() {
                      });

                    })\"  class='btn btn-sm btn-icon btn-pure btn-outline delete-row-btn'><i aria-hidden='true' class='ti-close'></i></a>";

                  },
                ],
              ];
    }else
    {
      $columns2 = [];
    }
    $columns = array_merge($columns,$columns2);

   ?>

         <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'summary'=>'',
        'columns'=>$columns,
    ]); ?>
     <?php
 $this->registerJs("
$('.btn-behaviour-filter').on('change', function() {
 $('.search-form').submit();

 });
 ",View::POS_END);
 Pjax::end();?>
</div>
</div>
</div>
</div>
</div>
