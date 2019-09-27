
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


                <div class="col-md-6">
                <div class="white-box">
                <div class="x_panel">
                  <div class="x_title">
                  <h2>Non Bio Wastemanagement</h2>
                   
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content2">
                    <div id="graph_pie_non_bio" style="width:100%; height:300px;"></div>
                  </div>
                </div>
                </div>
                </div>
            
         <?php  $usersCount = Customer::find()->select('count(*)')->scalar(); 
        
           $qry ="SELECT waste_collection_method.name as label, count(*) as value FROM customer left join waste_collection_method on waste_collection_method.id=customer.non_bio_waste_collection_method_id where customer.status=1 and waste_collection_method.status=1 and waste_collection_method.waste_category_id=3";
           if(isset($ward)&&$ward!=null)
           {
            $qry.=" and customer.ward_id=:wards";
           }
           // if(isset($from)&&$from!=null)
           // {
           //  $qry.=" and customer.created_at>=:from";
           // }
           // if(isset($to)&&$to!=null)
           // {
           //  $qry.=" and customer.created_at<=:to";
           // }
           $qry.=" GROUP BY waste_collection_method.id";
          $command =  Yii::$app->db->createCommand($qry);
          if(isset($ward)&&$ward!=null)
           {
           $command->bindParam(':wards',$ward);
           }
           // if(isset($from)&&$from!=null)
           // {
           //  $command->bindParam(':from',$from);
           // }
           // if(isset($to)&&$to!=null)
           // {
           //  $command->bindParam(':to',$to);
           // }
          $users = $command->queryAll(); 
          $user = json_encode($users);
             ?> 
        
<?php $this->registerJs("Morris.Donut({
          element: 'graph_pie_non_bio',
          'colors':['#26B99A', '#34495E', '#ACADAC', '#3498DB','#FFA07A','#FF0000','#DC143C','#FFD700','#228B22','#00FA9A','#808000','#00008B','#483D8B','#8B008B','#8B4513'],
          data: $user
          ,
       xLabelFormat: function(waste_collection_method) {
            return waste_collection_method.label;
  },
  // parseTime:false,
     
  //         resize: true
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