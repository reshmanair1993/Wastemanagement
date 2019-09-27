<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;

  $form = ActiveForm::begin(['action' => [''],'options' => ['','data-pjax' => true,'class' => 'form-horizontal form-material','enableAjaxValidation' => false,'enableClientValidation'=>true]]);

  $modelImage        = $model->fkImage;
  $modelLsgiAddress = $model->getLsgi($model->incident_id);
?>
<div class="row">
  <div class="col-sm-12 col-xs-12">
    <div class="form-group">
      <div class="col-sm-12 col-xs-12">
        <img class="img-thumbnail" src="http://localhost/wastemanagement/common/uploads/incident-images/garbage-dumping-f5c7bc444942ecf3bd57963c88405f80f577617b.jpg<?php //echo $modelImage->getFullUrl($url);?>" />
      </div>
      <div class="col-sm-12 col-xs-12">
        <?php
        if($modelLsgiAddress)
         echo$modelLsgiAddress->address ?>
      </div>
    </div>
  </div>
</div>
<?php
ActiveForm::end();
?>
