<?php
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
use yii\helpers\Html;
use yii\widgets\ListView;
use yii\grid\GridView;


$this->title = Yii::t('app', 'Incidents List');
$this->params['breadcrumbs'][] = $this->title;
?>
<h1><?= Html::encode($this->title) ?></h1>
<div class="row">
<div class ="col-lg-2 col-md-2 col-sm-6 col-12 incident-index incident-list">

<?php
Pjax::begin(
      [
        'id' => 'incident-pjax',
        'enablePushState' => false,
        'timeout' => 50000
      ]
);
?>

    <p>
        <?= Html::a(Yii::t('app', 'Create Incident'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>


    <?php
        // echo ListView::widget(
        // [
        // 'dataProvider' => $dataProvider,
        // 'itemView' => 'incident-single',
        // 'summary' => "",
        // ]);
        // $cameraId         = $model->camera_id;
        // $wardName = $model->getWard($cameraId);
        GridView::widget([
           'dataProvider' => $dataProvider,
               'layout' => '{items}',
               'columns' => [
                   ['class' => 'yii\grid\SerialColumn'],
                   [
                     'attribute' => 'name',
                     'label' =>'Incident Name',
                     'contentOptions'=>[ 'style'=>'width: 250px'],
                     'format' => 'raw',
                     'value'=>function ($model) {
                       // print_r($model);exit;
                       $page = isset($_GET['page']) ? $_GET['page']:1;
                       return Html::a($model->getIncidentType($model->incident_type_id),['camera/update?id='.$model->id],['data-pjax'=>0]);
                     },
                   ],
                   [
                     'label' =>'Ward',
                     'format' => 'raw',
                     // 'value'=> 'fkWard.name',
                   ],


               ['class' => 'yii\grid\ActionColumn'],
           ],
       ]);
    ?>

<?php Pjax::end();?>
</div>
</div>
