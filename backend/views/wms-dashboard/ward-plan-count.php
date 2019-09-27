<?php
use dosamigos\chartjs\ChartJs;
 $qry ="SELECT service.name as label, count(DISTINCT account_service.account_id) as value FROM customer
           left join ward on ward.id=customer.ward_id left join account on account.customer_id=customer.id left join account_service on account_service.account_id=account.id left join service on account_service.package_id=service.id where customer.status=1 and ward.status=1 and account.status=1 and account_service.status=1 and account_service.package_id>0 and service.is_package=1 and service.status=1";
           if(isset($ward)&&$ward!=null)
           {
            $qry.=" and customer.ward_id=:wards";
           }
           if(isset($from)&&$from!=null)
           {
            $from              = $from ? \Yii::$app->formatter->asDatetime($from, "php:Y-m-d 00:00:00") : '';
            $qry.=" and account_service.created_at>=:from";
           }
           if(isset($to)&&$to!=null)
           {
             $to                = $to ? \Yii::$app->formatter->asDatetime($to, "php:Y-m-d 23:59:59") : '';
            $qry.=" and account_service.created_at<=:to";
           }
           $qry.=" GROUP BY service.id";
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
          <div class="white-box">
          <?php
echo ChartJs::widget([
    'type' => 'pie',
    'id' => 'structurePie',
    'options' => [
        'height' => 400,
        'width' => 400,
    ],
    'data' => [
        'radius' =>  "90%",
        'labels' => $label, // Your labels
        'datasets' => [
            [
                'data' => $count ,// Your dataset
                'label' => '',
                'backgroundColor' => [
                        '#ADC3FF',
                        '#FF9A9A',
                    'rgba(190, 124, 145, 0.8)'
                ],
                'borderColor' =>  [
                        '#fff',
                        '#fff',
                        '#fff'
                ],
                'borderWidth' => 1,
                'hoverBorderColor'=>["#999","#999","#999"],                
            ]
        ]
    ],
    'clientOptions' => [
        'legend' => [
            'display' => true,
            'position' => 'bottom',
            'labels' => [
                'fontSize' => 14,
                'fontColor' => "#425062",
            ]
        ],
        'tooltips' => [
            'enabled' => true,
            'intersect' => true
        ],
        'hover' => [
            'mode' => false
        ],
        'maintainAspectRatio' => false,

    ],
    'plugins' =>
        new \yii\web\JsExpression('
        [{
            afterDatasetsDraw: function(chart, easing) {
                var ctx = chart.ctx;
                chart.data.datasets.forEach(function (dataset, i) {
                 
                    var meta = chart.getDatasetMeta(i);
                    if (!meta.hidden) {
                        meta.data.forEach(function(element, index) {
          
                            ctx.fillStyle = "rgb(0, 0, 0)";

                            var fontSize = 16;
                            var fontStyle = "normal";
                            var fontFamily = "Helvetica";
                            ctx.font = Chart.helpers.fontString(fontSize, fontStyle, fontFamily);

                            // Just naively convert to string for now
                            var dataString = dataset.data[index].toString();

                            // Make sure alignment settings are correct
                            ctx.textAlign = "center";
                            ctx.textBaseline = "middle";

                            var padding = 5;
                            var position = element.tooltipPosition();
                            ctx.fillText(dataString, position.x, position.y - (fontSize / 2) - padding);
                        });
                    }
                });
            }
        }]')
]);
?></div>