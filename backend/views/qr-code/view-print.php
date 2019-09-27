  
  <?php
use yii\web\View;
use backend\models\QrCodes;

  ?>
  <table> 
   <?php


   $params       = Yii::$app->request->get();
   $models = $newDataProvider->getModels();
   $i = 0;
   $colCount = isset($params['columns'])&&$params['columns']!=null?$params['columns']:4;
   $rowOpened  = true;
   $rowReached = false;
   $rowClosed = false;

   foreach ($models as $key => $value) {
          $lsgi = QrCodes::getLsgis($value->lsgi_id);
            $rowReached = $i%$colCount == 0;
            if($rowReached) {?>
               
                <?php if($i != 0):?>
                  </tr>

                <?php endif;?>
                  <tr>
<?php

            } ?>
             <td style="border: 1px solid black;">
             <p><?=$lsgi?></p>
            <!--  <img src="https://chart.googleapis.com/chart?chs=200x200&cht=qr&chl=<?=$value->value?>&choe=UTF-8"> -->
             <img style="width: 200px;" src="https://www.qr-code-generator.com/phpqrcode/getCode.php?cht=qr&chl=<?=$value->value?>&chs=200x200&choe=UTF-8&chld=L|0">
                <br><p><?=$value->value?></p>
                </td>
           
            <?php
           $i++;
        } ?>

          
            </tr>
 

        
     
        </table>

        <?php
    ?>
 <?php

$this->registerJs("
$(document).ready(function() {
   window.print();
});
 ",View::POS_END);
 ?>
 <style>
 td{
          text-align: center;
          padding: 10px;
        }
@media print {
         td{
          text-align: center;
          padding: 10px;
        }
      }   
 </style>