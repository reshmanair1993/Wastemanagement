<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use kartik\export\ExportMenu;
use yii\widgets\Pjax;
use yii\helpers\ArrayHelper;
use yii\web\View;
use yii\widgets\ActiveForm;
use backend\models\Ward;
use backend\models\District;
use backend\models\Account;
use backend\models\Customer;
use backend\models\AccountService;
use yii\helpers\Url;
use backend\models\ResidentialAssociation;
use yii\widgets\ListView;
$modelCustomer = new Customer;
$modelAccount = new Account;
$scrollingTop = 10;
$modelUser  = Yii::$app->user->identity;
$userRole = $modelUser->role;
$this->title = Yii::t('app', 'Customers');
$this->params['breadcrumbs'][] = $this->title;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\TbNewslettersubscriberSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
// foreach($params as $param => $val)
//   ${$param} = $val;
?>
  <div class="row bg-title">
    <div class="col-lg-2 col-md-4 col-sm-4 col-xs-12">
        <h4 class="page-title">Subscription Summary Report</h4>
    </div>


      <div class="col-lg-3 col-sm-8 col-md-8 col-xs-12">
           <?php   $this->title =  'Subscription Summary Report';
           $breadcrumb[] = ['label' => $this->title, 'template' => "<li class='active' , style='color:#4AAFF4;'>{link}</li>\n"];

            ?>
          <?=$this->render('/layouts/breadcrumbs',[ 'links'=>$breadcrumb]);?>

      </div>
     
      <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
        <?php $form = ActiveForm::begin(['action' =>Url::to(['reports/subscription-summary','set'=>1]),'options' => ['','data-pjax' => true,'class' => 'page-main-form search-form app-search m-r-10']]);?>        
          <div class="col-lg-2 col-sm-2 col-md-4 col-xs-12">
            <div class="form-group" style=" margin-top: -13px;">
            <label>ward</label>
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

            echo $form->field($modelCustomer, 'ward_id')->dropDownList($listData, $options)->label(false)?>
            </div>
          </div>
       <div class="col-lg-2 col-sm-2 col-md-4 col-xs-12">
            <div class="form-group" style=" margin-top: -13px;">
          <?php 
          // $dateFrom = Yii::$app->session->get('from');
           $from=  isset($_POST['from'])?$_POST['from']:'';
          ?>

            <input type="text" name="from" value="<?php if (isset($from))
    {
        echo $from;
}
?>" style="background-color:#ccc;color:white;margin-top: 0px;" placeholder="From....." class="form-control datepicker btn-behaviour-filter"> <a href="" class="active"></a>
      </div>
      </div>
      <div class="col-lg-2 col-sm-2 col-md-4 col-xs-12">
            <div class="form-group" style=" margin-top: -13px;">
      <?php 
      // $to = Yii::$app->session->get('to');
       $to =  isset($_POST['to'])?$_POST['to']:'';
      ?>
            <input type="text" name="to" value="<?php if (isset($to))
    {
        echo $to;
}
?>" style="background-color:#ccc;color:white;margin-top: 0px;" placeholder="To....." class="form-control datepicker btn-behaviour-filter"> <a href="" class="active"></a>
      </div>
      </div>
      <?php ActiveForm::end(); ?>
      </div>
</div>
   <br>
<?php Pjax::begin(['timeout' => 50000,'enablePushState' => false, 'id' =>'pjax-list', 'options'=>['data-loader'=>'.preloader']]);?>
<style type="text/css">.white-box{overflow:hidden;}</style>
<div class="row">
  <div class="col-md-12">
    <div class="white-box">
      <div class="scrollable">
        <div class="table-responsive">
          <div id="w1" class="grid-view">
            <table id="data-table" class="table table-hover contact-list footable-loaded footable">
              <thead>
                <tr class="footable-sortable">
                  <th>#</th>
                  <th>Ward</th>
                  <?php
                   if($serviceDataprovider->getModels()){
      foreach ($serviceDataprovider->getModels() as $key => $value) {
                  ?>
                  <th><?=$value->name?></th>
                  <?php }
                  }?>
                </tr>
                </thead>
                <tbody>
                  <?php
                    if($dataProvider->getCount() > 0){
                    echo ListView::widget([
                      'dataProvider' => $dataProvider,
                      'itemView' => function ($model, $key, $index, $widget) {
                          return $this->render('subscription-summary-single', [
                              'model' => $model,
                              'index' => $index,
                              'widget' => $widget,
                          ]);
                      },
                      'summary'=> '',
                    ]);
                   }
                  ?>
                </tbody>
              </table>
            </div>
          </div>
      </div>
    </div>
  </div>
</div>


<?php Pjax::end();?>
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
        $('#w1-togdata-page').on('click', function() {
 location.reload();
 });
 ",View::POS_END);
 // Pjax::end();
 ?>
 <?= $this->registerJs("
    $('.datepicker').datepicker({
           orientation:'top',
           format:'dd-mm-yyyy',
           autoclose:true,
           todayHighlight:true,
       });

$('.btn-behaviour-filter').on('change', function() {
 $('.search-form').submit();

 });
  $('table').tableExport({
    headings: true,                    // (Boolean), display table headings (th/td elements) in the <thead>
    footers: true,                     // (Boolean), display table footers (th/td elements) in the <tfoot>
    formats: ['xls', 'csv', 'txt','pdf'],    // (String[]), filetypes for the export
    fileName: 'Subscription Summary Report',                    // (id, String), filename for the downloaded file
    bootstrap: true,                   // (Boolean), style buttons using bootstrap
    position: 'bottom'                 // (top, bottom), position of the caption element relative to table
    // ignoreRows: null,                  // (Number, Number[]), row indices to exclude from the exported file(s)
    // ignoreCols: null,                  // (Number, Number[]), column indices to exclude from the exported file(s)
    // ignoreCSS: '.tableexport-ignore',  // (selector, selector[]), selector(s) to exclude from the exported file(s)
    // emptyCSS: '.tableexport-empty',    // (selector, selector[]), selector(s) to replace cells with an empty string in the exported file(s)
    // trimWhitespace: false              // (Boolean), remove all leading/trailing newlines, spaces, and tabs from cell text in the exported file(s)
});
 ",View::POS_END);?>
