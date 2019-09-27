<?php
use yii\widgets\ListView;
use yii\helpers\Url;
foreach($params as $param => $val)
  ${$param} = $val;
    echo ListView::widget([
        'dataProvider' => $dataProvider,
        'options' => [
            'tag' => 'div',
            'class' => 'list-wrapper',
            'id' => 'list-wrapper',
        ],
        'layout' => "{items}",
        'itemView' => function ($model, $key, $index, $widget) {
          return $this->render('test-list', [
            'model' => $model,
            'index' => $index+1,
          ]);
        },
    ]);
  ?>