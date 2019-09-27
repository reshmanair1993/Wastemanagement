<?php 
use yii\web\View;
use backend\models\Customer;
use backend\models\Ward;
use yii\db\Query;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
$modelCustomer = new Customer;
$connection = \Yii::$app->db;
use yii\helpers\Html;
?>
              <div class="col-md-12 col-sm-12">

                <div class="row bg-title">
    <div class="col-lg-2 col-md-4 col-sm-4 col-xs-12">
        <h4 class="page-title">Reports</h4>
    </div>


      <div class="col-lg-3 col-sm-8 col-md-8 col-xs-12">
           <?php   $this->title =  'Dashboard';
           $breadcrumb[] = ['label' => $this->title, 'template' => "<li class='active' , style='color:#4AAFF4;'>{link}</li>\n"];

            ?>
          <?=$this->render('/layouts/breadcrumbs',[ 'links'=>$breadcrumb]);?>

      </div>
     
      <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
        <?php $form = ActiveForm::begin(['action' =>Url::to(['wms-dashboard/view-reports','set'=>1]),'options' => ['','data-pjax' => true,'class' => 'page-main-form search-form app-search hidden-sm hidden-xs m-r-10']]);?>
      
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
            $wardData=Ward::getWards();
            $listData=ArrayHelper::map($wardData, 'id', 'name');

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
<?php
		$views = [
			  	'wardWise'  => 'ward-wise',
				'wardWisePlan' => 'ward-wise-plan',
				'wasteManagementType' => 'bio-waste',
				// 'wasteManagementTypeNonBio' => 'non-bio-waste',
				'planEnabled' => 'ward-plan-count',
				'wardWisePendingService' => 'ward-wise-pending-service',
				// 'wardWisePendingCompletedService' => 'ward-wise-service-completed-and-pending',
				// 'dealMonthly' => 'deal-month-line',
				// 'enquiryYearly' => 'enquiry-year-line',
				// 'enquiryMonthly' => 'enquiry-month-line',


		];

		foreach ($views as $action => $view) {
			?>
				<div class="col-md-12">
        <?php if($view=='ward-plan-count'):?>
        <h3>Ward Plan Count Report</h3>
        <?php endif;?>
				<?=$this->render($view,
				['ward'  => $ward,
            'from' => $from,
            'to'  => $to]); 
            	?>
				</div>
			<?php
		}	?>
		</div>