<?php

use yii\helpers\Html;
// use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\web\View;
use kartik\date\DatePicker;
use kartik\grid\GridView;
use backend\models\Ward;
use backend\models\ResidentialAssociation;
$modelUser  = Yii::$app->user->identity;
    $userRole = $modelUser->role;
$scrollingTop = 30;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Residential association';
$this->params['breadcrumbs'][] = $this->title;

foreach($params as $param => $val)
  ${$param} = $val;

?>
<?php Pjax::begin(['timeout' => 50000,'enablePushState' => false,
                      'id' =>'pjax-residential-ass-list', 'options'=>['data-loader'=>'.preloader']]); ?>

<div class="generate-memo-index">

  <div class="row bg-title">
    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
    </div>
    <div class="col-lg-4 col-sm-8 col-md-8 col-xs-12">
        <?php
        $breadcrumb[] = ['label' => $this->title,];

        ?>
        <?=$this->render('/layouts/breadcrumbs',[ 'links'=>$breadcrumb]);?>
      </div>
            <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
        <?php $form = ActiveForm::begin(['action' =>Url::to(['residential-association/index','set'=>1]),'options' => ['','data-pjax' => true,'class' => 'page-main-form search-form app-search m-r-10']]);?>
           <?php  if(!isset($associations['ward_id'])):
          
            ?>
          <div class="col-lg-2 col-sm-2 col-md-4 col-xs-12">

            <div class="form-group" style=" margin-top: -13px;">
                 <?php
      $params = array_merge(Yii::$app->request->post(),Yii::$app->request->get());

      $options = ['class'=>'form-control btn-behaviour-filter','prompt' => 'Ward..','name'=>'ward'];
      $key =  isset($_POST['ward'])?$_POST['ward']:'';

      if(isset($key)) {
        $option = $key;
        $options['options'] = [$option => ['selected'=>'selected']];
      }
            $ward=Ward::getWards();
            $listData=ArrayHelper::map($ward, 'id', 'name_en');

            echo $form->field($modelResidentialAssociation, 'ward')->dropDownList($listData, $options)->label(false)?>
            </div>
          </div>
        <?php endif;?>
        <?php  if(isset($associations['ward_id'])&&json_decode($associations['ward_id'])&&sizeof(json_decode($associations['ward_id']))>0):
          
            ?>
          <div class="col-lg-2 col-sm-2 col-md-4 col-xs-12">

            <div class="form-group" style=" margin-top: -13px;">
                 <?php
      $params = array_merge(Yii::$app->request->post(),Yii::$app->request->get());

      $options = ['class'=>'form-control btn-behaviour-filter','prompt' => 'Ward..','name'=>'ward'];
      $key =  isset($_POST['ward'])?$_POST['ward']:'';

      if(isset($key)) {
        $option = $key;
        $options['options'] = [$option => ['selected'=>'selected']];
      }
            $ward=Ward::getWards();
            $listData=ArrayHelper::map($ward, 'id', 'name');

            echo $form->field($modelResidentialAssociation, 'ward')->dropDownList($listData, $options)->label('Ward')?>
            </div>
          </div>
        <?php endif;?>

      <?php ActiveForm::end(); ?>
      </div>
      <?php if((Yii::$app->user->can('residential-association-create')||$userRole=='super-admin')&&$userRole!='residence-association-admin'){?>
      <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
             <p>
        <?= Html::a(Yii::t('app', 'Create Association'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
        </div>
      <?php }?>
  </div>
  <div class="col-lg-12 col-md-12 colsm-6 col-6">

</div>
<?php
        $columns = [

            [
              'label' =>'Name Of association',
              'contentOptions'=>[ 'style'=>'width: 250px'],
              'format' => 'raw',
              'value'=>function ($modelResidentialAssociation) {
                $modelUser  = Yii::$app->user->identity;
$userRole = $modelUser->role;
                 if(Yii::$app->user->can('residential-association-view')||$userRole=='super-admin'){
                return Html::a(ucfirst($modelResidentialAssociation->name),['residential-association/view','id'=>$modelResidentialAssociation->id],['data-pjax'=>0]);
              }else
              {
                return ucfirst($modelResidentialAssociation->name);
              }
              },
            ],
            [
              'label' =>'Association type',
              'contentOptions'=>[ 'style'=>'width: 250px'],
              'format' => 'raw',
              'value'=>function ($modelResidentialAssociation) {
                $associationId = $modelResidentialAssociation->association_type_id;
                return $modelResidentialAssociation->getAssociationName($associationId);
              },
            ],
            [
              'label' =>'Name Of ward',
              'contentOptions'=>[ 'style'=>'width: 250px'],
              'format' => 'raw',
              'value'=>function ($modelResidentialAssociation) {
                $wardId = $modelResidentialAssociation->ward_id;
                return $modelResidentialAssociation->getWardName($wardId);
              },
            ],
            [
              // 'attribute' => 'name',
              'label' =>'Registration number',
              'contentOptions'=>[ 'style'=>'width: 250px'],
              'format' => 'raw',
              'value'=>function ($modelResidentialAssociation) {
                return $modelResidentialAssociation->registration_number;
              },
            ],
            [
              'label' =>'Address',
              'contentOptions'=>[ 'style'=>'width: 250px'],
              'format' => 'raw',
              'value'=>function ($modelResidentialAssociation) {
                return $modelResidentialAssociation->address;
              },
            ],
            [
              'label' =>'Email id of association',
              'contentOptions'=>[ 'style'=>'width: 250px'],
              'format' => 'raw',
              'value'=>function ($modelResidentialAssociation) {
                return $modelResidentialAssociation->email;
              },
            ],
            [
              'label' =>'	Year of formation',
              'contentOptions'=>[ 'style'=>'width: 250px'],
              'format' => 'raw',
              'value'=>function ($modelResidentialAssociation) {
                return $modelResidentialAssociation->year;
              },
            ],
            [
              'label' =>'	President name',
              'contentOptions'=>[ 'style'=>'width: 250px'],
              'format' => 'raw',
              'value'=>function ($modelResidentialAssociation) {
                return $modelResidentialAssociation->president_name;
              },
            ],
            [
              'label' =>'President contact number',
              'contentOptions'=>[ 'style'=>'width: 250px'],
              'format' => 'raw',
              'value'=>function ($modelResidentialAssociation) {
                return $modelResidentialAssociation->president_phone_number;
              },
            ],
            [
              'label' =>'Secretary name',
              'contentOptions'=>[ 'style'=>'width: 250px'],
              'format' => 'raw',
              'value'=>function ($modelResidentialAssociation) {
                return $modelResidentialAssociation->secretary_name;
              },
            ],
            [
              'label' =>'Secretary contact number',
              'contentOptions'=>[ 'style'=>'width: 250px'],
              'format' => 'raw',
              'value'=>function ($modelResidentialAssociation) {
                return $modelResidentialAssociation->secretary_phone_number;
              },
            ],
            [
              'label' =>'Treasurer name',
              'contentOptions'=>[ 'style'=>'width: 250px'],
              'format' => 'raw',
              'value'=>function ($modelResidentialAssociation) {
                return $modelResidentialAssociation->treasurer_name;
              },
            ],
            [
              'label' =>'Treasurer contact number	',
              'contentOptions'=>[ 'style'=>'width: 250px'],
              'format' => 'raw',
              'value'=>function ($modelResidentialAssociation) {
                return $modelResidentialAssociation->treasurer_phone_number;
              },
            ],
            [
              'label' =>'No of households in association',
              'contentOptions'=>[ 'style'=>'width: 250px'],
              'format' => 'raw',
              'value'=>function ($modelResidentialAssociation) {
                return $modelResidentialAssociation->no_of_households_in_association;
              },
            ],
            [
              'label' =>'No of households surveyed in association',
              'contentOptions'=>[ 'style'=>'width: 250px'],
              'format' => 'raw',
              'value'=>function ($modelResidentialAssociation) {
                $ward          = Yii::$app->session->get('ward');
                $ward = isset($ward)?$ward:null;
                $count = ResidentialAssociation::getCount($modelResidentialAssociation->id,$ward);
                return $count;
              },
            ],
            
            // ['class' => 'yii\grid\ActionColumn'],
        ];
        if(Yii::$app->user->can('residential-association-delete-association')||$userRole=='super-admin'){
        $columns2 = [             
                   [
               'label' =>'Delete',
               'contentOptions'=>[ 'style'=>'width: 50px'],
               'format' => 'raw',
               'value'=>function ($modelResidentialAssociation) {
                $page = isset($_GET['page']) ? $_GET['page']:1;
                $page = $page;
                $url =  Yii::$app->urlManager->createUrl(['residential-association/delete-association','id'=>$modelResidentialAssociation->id]);

                return  "<a  onclick=\"ConfirmDelete(function(){
                  deleteItem('$url','#pjax-residential-ass-list',$page,function() {
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
         'columns' => $columns,

        'containerOptions' => ['style'=>'overflow: auto'], // only set when 
          'toolbar' =>  [
              [
              
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
      ]);
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
 Pjax::end();?>