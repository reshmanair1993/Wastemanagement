<?php
use dosamigos\chartjs\ChartJs;
$qry ="SELECT ward.name as label, count(*) as value FROM customer left join ward on ward.id=customer.ward_id where customer.status=1 and ward.status=1";
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
          $label =[]; 
          $count =[];
          foreach ($users as $value) {
          $label[]= $value['label'];
          $count[]=$value['value'];
          }?><br>
          <h3>Ward Wise Count</h3>
            <div class="white-box" style="width:1000px; overflow-x: scroll; overflow-y: scroll;">
<?= ChartJs::widget([
    'type' => 'bar',
    'id' => 'test',
    'options' => [
        'height' => 150,
        'width' => 400,
    ],
    'data' => [
        'labels' => $label,
        'datasets' => [
             [
                'data' => $count ,// Your dataset
                'label' => '',
                'width'=>10,
                'backgroundColor' => [
                        '#ADC3FF',
                        '#FF9A9A',
                    'rgba(190, 124, 145, 0.8)',
                    '#FF0000', '#8B0000',  '#FF00FF', '#FFD700','#26B99A', '#00FFFF',  '#00BFFF', '#00008B','#800080','#696969','#D2691E','#800000','#C71585','#808080','#000000','#808000','#008000','#00FFFF','#008080','#0000FF','#000080','#E9967A','#FF0000', '#8B0000',  '#FF00FF', '#FFD700','#26B99A', '#00FFFF',  '#00BFFF', '#00008B','#800080','#696969','#D2691E','#800000','#C71585','#808080','#000000','#808000','#008000','#00FFFF','#008080','#0000FF','#000080','#E9967A'
                ],
                'borderColor' =>  [
                        '#fff',
                        '#fff',
                        '#fff'
                ],
                'borderWidth' => 1,
                'scroll'=>true
                // 'hoverBorderColor'=>["#999","#999","#999"],                
            ]
        ]
    ]
]);
?>
</div>
