<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\ArrayHelper;
use yii\web\View;
use yii\widgets\ActiveForm;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
foreach($params as $param => $val)
  ${$param} = $val;
  global $roleType;
  $roleType = $type;
?>
<div class="row bg-title">
    <div class="col-lg-2 col-md-4 col-sm-4 col-xs-12">
        <h4 class="page-title">Users</h4>
    </div>


      <div class="col-lg-3 col-sm-8 col-md-8 col-xs-12">
           <?php   $this->title =  'Users';
           $breadcrumb[] = ['label' => $this->title, 'template' => "<li class='active' , style='color:#4AAFF4;'>{link}</li>\n"];

            ?>
          <?=$this->render('/layouts/breadcrumbs',[ 'links'=>$breadcrumb]);?>

      </div>
      <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
           <p>
            <?php if($type){
        echo Html::a(Yii::t('app', 'Create User '), ['create-user','type'=>$type], ['class' => 'btn btn-success']);
      }else{
        echo Html::a(Yii::t('app', 'Create User '), ['create-user'], ['class' => 'btn btn-success']);
      } ?>

    </p>
        </div>
      <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
        <?php $form = ActiveForm::begin(['action' => 'users-index','options' => ['','data-pjax' => true,'class' => 'page-main-form search-form app-search hidden-sm hidden-xs m-r-10']]);?>
      <div class="col-lg-2 col-sm-2 col-md-8 col-xs-12">

           <?php
           // $keyword =  Yii::$app->session->get('name');
           $keyword =  isset($_POST['name'])?$_POST['name']:'';
           ?>
            <input type="text" name="name" value="<?php if (isset($keyword)) echo $keyword; ?>" style="background-color:#ccc;color:white;margin-top: 0px;" placeholder="Search..." class="form-control btn-behaviour-filter"> <a href="" class="active"></a>


      </div>

      <?php ActiveForm::end(); ?>
      </div>
</div>
<div class="row">
  <div class="col-md-12">
    <div class="white-box">
      <div class="scrollable">
        <div class="table-responsive super-admin-table">
  <?php Pjax::begin(['timeout' => 50000,'enablePushState' => false,
                      'id' =>'pjax-user-list', 'options'=>['data-loader'=>'.preloader']]); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'summary'=>'',
        'columns' => [
              [
                    'attribute' => 'username',
                    'label' =>'User Name',
                    'contentOptions'=>[ 'style'=>'width: 250px'],
                    'format' => 'raw',
                    'value'=>function ($modelAccount) {
                      global $roleType;
                      $page = isset($_GET['page']) ? $_GET['page']:1;
                      return Html::a(ucfirst($modelAccount->username),['rbac/update-user','user_id'=>$modelAccount->id,'type'=>$roleType],['data-pjax'=>0]);
                    },
                  ],
                  [
                    'attribute' => 'reset',
                    'label' =>'Change Password',
                    'contentOptions'=>[ 'style'=>'width: 200px'],
                    'format' => 'raw',
                    'value'=>function ($model) {
                       global $roleType;
                      $page = isset($_GET['page']) ? $_GET['page']:1;
                      return Html::a('Change Password',['rbac/set-user-password','id'=>$model->id,'type'=>$roleType],['data-pjax'=>0]);
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
                   'attribute' => 'delete',
                   'label' =>'Delete',
                   'contentOptions'=>[ 'style'=>'width: 50px'],
                   'format' => 'raw',
                   'value'=>function ($modelAccount) {
                    $page = isset($_GET['page']) ? $_GET['page']:1;
                    $page = $page;
                    $url =  Yii::$app->urlManager->createUrl(['rbac/delete-user','acc_id'=>$modelAccount->id]);

                    return  "<a  onclick=\"ConfirmDelete(function(){
                      deleteItem('$url','#pjax-user-list',$page,function() {
                      });

                    })\"  class='btn btn-sm btn-icon btn-pure btn-outline delete-row-btn'><i aria-hidden='true' class='ti-close'></i></a>";

                  },
                ],
        ],
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
