<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\SurveyAgency */

$this->title = Yii::t('app', 'Create Survey Agency');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Survey Agencies'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="survey-agency-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
