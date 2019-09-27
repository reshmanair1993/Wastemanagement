
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



                <br><br>
                <div class="col-md-6">
                <div class="white-box">
                <div class="x_panel">
                  <div class="x_title">
                  <h2>Ward Wise Pending Services </h2>
                    
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content2">
                    <div id="pending_service" style="width:100%; height:300px;"></div>
                  </div>
                </div>
                </div>
                </div>
            
         <?php  $usersCount = Customer::find()->select('count(*)')->scalar(); 
        
           $qry ="SELECT ward.name as ward, count(*) as count FROM customer left join ward on ward.id=customer.ward_id left join account on account.customer_id=customer.id left join service_request on service_request.account_id_customer=account.id left join service_assignment on service_assignment.service_request_id=service_request.id left join service on service.id=service_request.service_id where customer.status=1 and ward.status=1 and account.status=1 and service_request.status=1 and service.status=1 and service_assignment.servicing_status_option_id is null and service.type=1";
           if(isset($ward)&&$ward!=null)
           {
            $qry.=" and ward.id=:wards";
           }
           if(isset($from)&&$from!=null)
           {
            $from              = $from ? \Yii::$app->formatter->asDatetime($from, "php:Y-m-d 00:00:00") : '';
            $qry.=" and service_request.created_at>=:from";
           }
           if(isset($to)&&$to!=null)
           {
            $to                = $to ? \Yii::$app->formatter->asDatetime($to, "php:Y-m-d 23:59:59") : '';
            $qry.=" and service_request.created_at<=:to";
           }
           $qry.=" GROUP BY ward.id";
          $command =  Yii::$app->db->createCommand($qry);
          if(isset($ward)&&$ward!=null)
           {
           $command->bindParam(':wards',$ward);
           }
           if(isset($from)&&$from!=null)
           {
            $command->bindParam(':from',$from);
           }
           if(isset($to)&&$to!=null)
           {
            $command->bindParam(':to',$to);
           }
          $users = $command->queryAll(); 
          $user = json_encode($users);
        
             ?> 
        
<?php $this->registerJs("
  var arrColors = ['#FF0000', '#8B0000',  '#FF00FF', '#FFD700','#26B99A', '#00FFFF',  '#00BFFF', '#00008B','#800080','#696969','#D2691E','#800000','#C71585'];
  Morris.Bar({
          element: 'pending_service',
          xkey: 'ward',
          ykeys: ['count'],
          labels: ['Pending Service Count'],
           xLabelAngle: 45,
          hideHover: 'auto',
          'stacked':'true',
          // barColors: ['#26B99A', '#87CEFA'],
  //   var user = '$user' ,
          data: $user
          ,
       xLabelFormat: function(ward) {

            return ward.label;
  },
   barColors: function (row, series, type) {
        return arrColors[row.x];
    }, 
  parseTime:false,
     
          resize: true
        });

       ",View::POS_END);
     ?>
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
 // Pjax::end();
 ?>
 <div class="col-md-6">
                <div class="white-box">
                <div class="x_panel">
                  <div class="x_title">
                  <h2>Ward Wise Service Completion Vs Ward Wise Pending</h2>
                    
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content2">
                    <div id="completed_pending" style="width:100%; height:300px;"></div>
                  </div>
                </div>
                </div>
                </div>
            
         <?php  $usersCount = Customer::find()->select('count(*)')->scalar(); 
        
           $qryNew ="SELECT ward.name as ward, count(service_request.id) as count FROM ward left join customer on ward.id=customer.ward_id left join account on account.customer_id=customer.id left join service_request on service_request.account_id_customer=account.id left join service_assignment on service_assignment.service_request_id=service_request.id LEFT JOIN service on service.id=service_request.service_id where customer.status=1 and ward.status=1 and account.status=1 and service_request.status=1 and service_assignment.servicing_status_option_id is null and service.type=1";
           if(isset($ward)&&$ward!=null)
           {
            $qryNew.=" and ward.id=:wards";
           }
           if(isset($from)&&$from!=null)
           {
             $from              = $from ? \Yii::$app->formatter->asDatetime($from, "php:Y-m-d 00:00:00") : '';
            $qryNew.=" and service_request.created_at>=:from";
           }
           if(isset($to)&&$to!=null)
           {
             $to                = $to ? \Yii::$app->formatter->asDatetime($to, "php:Y-m-d 23:59:59") : '';
            $qryNew.=" and service_request.created_at<=:to";
           }
           $qryNew.=" GROUP BY ward.id";
          $commandNew =  Yii::$app->db->createCommand($qryNew);
          if(isset($ward)&&$ward!=null)
           {
           $commandNew->bindParam(':wards',$ward);
           }
           if(isset($from)&&$from!=null)
           {
            $commandNew->bindParam(':from',$from);
           }
           if(isset($to)&&$to!=null)
           {
            $commandNew->bindParam(':to',$to);
           }
          $usersNew = $commandNew->queryAll(); 




           $qry1 ="SELECT ward.name as ward, count(service_request.id) as completed FROM ward left join customer on ward.id=customer.ward_id left join account on account.customer_id=customer.id left join service_request on service_request.account_id_customer=account.id left join service_assignment on service_assignment.service_request_id=service_request.id LEFT JOIN service on service.id=service_request.service_id where customer.status=1 and ward.status=1 and account.status=1 and service_request.status=1 and service_assignment.servicing_status_option_id is not null and service.type=1 and service_assignment.status=1";
         
           if(isset($ward)&&$ward!=null)
           {
            $qry1.=" and ward.id=:wards";
           }
           if(isset($from)&&$from!=null)
           {
            $from              = $from ? \Yii::$app->formatter->asDatetime($from, "php:Y-m-d 00:00:00") : '';
    
            $qry1.=" and service_assignment.servicing_datetime>=:from";
           }
           if(isset($to)&&$to!=null)
           {
            $to                = $to ? \Yii::$app->formatter->asDatetime($to, "php:Y-m-d 23:59:59") : '';
            $qry1.=" and service_assignment.servicing_datetime<=:to";
           }
           $qry1.=" GROUP BY ward.id";
          $command1 =  Yii::$app->db->createCommand($qry1);
          if(isset($ward)&&$ward!=null)
           {
           $command1->bindParam(':wards',$ward);
           }
           if(isset($from)&&$from!=null)
           {
            $command1->bindParam(':from',$from);
           }
           if(isset($to)&&$to!=null)
           {
            $command1->bindParam(':to',$to);
           }
          $users1 = $command1->queryAll(); 

          $usersNew = array_merge($users1,$usersNew);
          $userNew = json_encode($usersNew);
        
             ?> 
        
<?php $this->registerJs("
  var arrColors = ['#FF0000', '#8B0000',  '#FF00FF', '#FFD700','#26B99A', '#00FFFF',  '#00BFFF', '#00008B','#800080','#696969','#D2691E','#800000','#C71585'];
  Morris.Bar({
          element: 'completed_pending',
          xkey: 'ward',
          ykeys: ['count','completed'],
          labels: ['pending','completed'],
           xLabelAngle: 45,
          hideHover: 'auto',
          'stacked':'true',
          // barColors: ['#26B99A', '#87CEFA'],
  //   var user = '$user' ,
          data: $userNew
          ,
       xLabelFormat: function(ward) {

            return ward.label;
  },
   barColors: function (row, series, type) {
        return arrColors[row.x];
    }, 
  parseTime:false,
     
          resize: true
        });

       ",View::POS_END);
     ?>
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
 // Pjax::end();
 ?>