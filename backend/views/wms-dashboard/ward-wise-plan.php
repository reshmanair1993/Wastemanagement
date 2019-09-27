
<?php 
use yii\web\View;
use backend\models\Customer;
use backend\models\Ward;
use yii\db\Query;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use bsadnu\googlecharts\ColumnChart;
$modelCustomer = new Customer;
$connection = \Yii::$app->db;
use yii\helpers\Html;
?>


<br>
                <div class="white-box">
                <div class="x_panel">
                  <div class="x_title">
                  <h2>Ward Wise Count Vs Plan Enabled Customer Count </h2>
                   
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content2">
                    <div id="graph_ward" style="width:100%; height:300px;"></div>
                  </div>
                </div>
                </div>
            
         <?php  $usersCount = Customer::find()->select('count(*)')->scalar(); 
        
           $qry ="SELECT ward.name as ward, count(*) as count FROM customer left join ward on ward.id=customer.ward_id where customer.status=1 and ward.status=1";
           if(isset($ward)&&$ward!=null)
           {
            $qry.=" and ward.id=:wards";
           }
           if(isset($from)&&$from!=null)
           {
          $from              = $from ? \Yii::$app->formatter->asDatetime($from, "php:Y-m-d 00:00:00") : '';
            $qry.=" and customer.created_at>=:from";
           }
           if(isset($to)&&$to!=null)
           {
            $to                = $to ? \Yii::$app->formatter->asDatetime($to, "php:Y-m-d 23:59:59") : '';
            $qry.=" and customer.created_at<=:to";
           }
           $qry.=" GROUP BY ward_id";
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




           // $qry1 ="SELECT ward.name as ward, count(*) as valuenew FROM customer left join ward on ward.id=customer.ward_id left join account on account.customer_id=customer.id left join account_service on account_service.account_id=account.id where customer.status=1 and ward.status=1 and account.status=1 and account_service.status=1 and account_service.package_id>0";
          $qry1 = "SELECT ward.name as ward, count(DISTINCT account_service.account_id) as valuenew FROM ward left join customer on ward.id=customer.ward_id left join account on account.customer_id=customer.id left join account_service on account_service.account_id=account.id where customer.status=1 and ward.status=1 and account.status=1 and account_service.status=1 and account_service.package_id>0 ";
           if(isset($ward)&&$ward!=null)
           {
            $qry1.=" and ward.id=:wards";
           }
           if(isset($from)&&$from!=null)
           {
             $from              = $from ? \Yii::$app->formatter->asDatetime($from, "php:Y-m-d 00:00:00") : '';
            $qry1.=" and account_service.created_at>=:from";
           }
           if(isset($to)&&$to!=null)
           {
            $to                = $to ? \Yii::$app->formatter->asDatetime($to, "php:Y-m-d 23:59:59") : '';
            $qry1.=" and account_service.created_at<=:to";
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

          $users = array_merge($users1,$users);
          $user = json_encode($users);
          // $graph_data = [];
          // $graph_data[] =['Ward', 'Customer Count', 'Plan Enabled Count']; 
          //  foreach ($users as $user) {
          // foreach ($users1 as $value) {  
          //     if($value['ward']==$user['ward'])
          //     {
          //       $arr['ward'] = $user['ward'];
          //       $arr['count'] = $user['count'];
          //       $arr['valuenew'] = $value['valuenew'];
          //     }
          //     else
          //     {
          //       $arr['ward'] = $user['ward'];
          //       $arr['count'] = $user['count'];
          //       $arr['valuenew'] = 0;
          //     }
          //     $graph_data[] = [$arr['ward'],$arr['count'],$arr['valuenew']];
          //   }
            
          // }
        
        ?> 
        
<?php $this->registerJs("Morris.Bar({
          element: 'graph_ward',
          xkey: 'ward',
          ykeys: ['count','valuenew'],
          labels: ['Customer Count','Plan Enabled Count'],
           xLabelAngle: 90,
          hideHover: 'auto',
          'stacked':'true',
          barColors: ['#26B99A', '#87CEFA'],
  //   var user = '$user' ,
          data: $user
          ,
       xLabelFormat: function(ward) {

            return ward.label;
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