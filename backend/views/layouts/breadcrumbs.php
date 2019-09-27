  <?php

use yii\widgets\Breadcrumbs;


 echo  Breadcrumbs::widget([
   'homeLink' => [
                  'label' => Yii::t('yii', 'Home'),
                  'url' => Yii::$app->urlManager->createUrl(['dashboard/index']),
             ],
     'links' => $links ,
     'options'	=>['data-pjax'=>0,'class'=>'breadcrumb'], //  attributes on container
     ]);
  ?>
