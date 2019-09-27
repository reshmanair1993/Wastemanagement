<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;

  $form = ActiveForm::begin(['action' => [''],'options' => ['','data-pjax' => true,'class' => 'form-horizontal form-material','enableAjaxValidation' => false,'enableClientValidation'=>true]]);
  $modelLsgi = $model->getLsgi($model->incident_id);
  $modelIncident = $model->getIncident($model->incident_id);
  $incidentImage = $modelIncident->image_id;
  $incidentVideo = $modelIncident->file_video_id;
  $modelImage        = $model->getImage($incidentImage);
  $modelVideo        = $model->getVideo($incidentVideo);
  $modelMemoType = $model->getMemoType($model->memo_type_id);

?>
<div class="row">
  <!-- <div class="col-sm-12 col-xs-12"> -->
    <div class="form-group">
      <div class=" memo-preview">
      <div class="row logo-section">
        <div class="col-sm-4 col-xs-4 cooperation-address">

        </div>
        <div class="col-sm-4 col-xs-4 cooperation-address">
          <?php
          if($modelLsgi)
           echo$modelLsgi->address ?>
        </div>
        <div class="col-sm-4 col-xs-4 image-logo">
          <img class="img-thumbnail" src="http://localhost/wastemanagement/common/logo/Seal_of_Corporation_of_Thiruvananthapuram.svg.png<?php //echo $modelImage->getFullUrl($url);?>" />
        </div>
      </div>
      <div class="table-section">
        <table style="width:100%">
          <tr>
            <td>Charge</td>
            <td>
              <?php
              if($modelMemoType)
                echo $modelMemoType->title;
              ?>
            </td>
          </tr>
          <tr>
            <td>Reference Rule</td>
            <td>
              <?php
              if($modelMemoType)
                echo $modelMemoType->rule_url;
              ?>
            </td>
          </tr>
          <tr>
            <td>Location</td>
            <td>
              <?php

              ?>
            </td>
          </tr>
          <tr>
            <td>Incident Description</td>
            <td></td>
          </tr>
        </table>
        <table style="width:100%">
          <tr>
            <td colspan="2">Proof</td>
          </tr>
          <tr>
            <td>Video Link</td>
            <td>Image Link</td>
          </tr>
          <tr>
            <td class="video-section">
              <?php
                  if($modelVideo){
                    $url = $modelVideo->url;
              ?>
              <video controls>
              <source src="<?php echo $modelVideo->getFullUrl($url);?>" type="video/mp4">
              </video>
              <?php
                  }
              ?>
            </td>
            <td class="image-section">
              <?php
                  if($modelImage){
                    $url = $modelImage->uri_full;
              ?>
                    <img class="img-thumbnail" src="<?php echo $modelImage->getFullUrl($url);?>" />
              <?php
                  }
              ?>
            </td>
          </tr>
        </table>
        <table style="width:100%">
          <tr>
            <td colspan="2">Penal Actions</td>
          </tr>
          <tr>
            <td>Penality Amount</td>
            <td>
              <?php
                echo $model->amount;
              ?>
            </td>
          </tr>
          <tr>
            <td>Other Legal Actions</td>
            <td>
              <?php
              if($modelMemoType)
                echo $modelMemoType->other_legal_actions;
              ?>
            </td>
          </tr>
        </table>
      </div>

      <!-- <div class="col-sm-12 col-xs-12">
        <div class="row">
        <div class="col-sm-6 col-xs-6">
          <label for="charge">Charge</label>
        </div>
        <div class="col-sm-6 col-xs-6">
          <?php

          ?>
        </div>
      </div>
      </div>
      <div class="col-sm-12 col-xs-12">
        <div class="row">
        <div class="col-sm-6 col-xs-6">
          <label for="charge">Reference Rule</label>
        </div>
        <div class="col-sm-6 col-xs-6">
          <?php

          ?>
        </div>
      </div>
      </div>
      <div class="col-sm-12 col-xs-12">
        <div class="row">
        <div class="col-sm-6 col-xs-6">
          <label for="charge">Location</label>
        </div>
        <div class="col-sm-6 col-xs-6">
          <?php

          ?>
        </div>
      </div>
      </div>
      <div class="col-sm-12 col-xs-12">
        <div class="row">
        <div class="col-sm-6 col-xs-6">
          <label for="charge">Incident Description</label>
        </div>
        <div class="col-sm-6 col-xs-6">
          <?php

          ?>
        </div>
      </div>
      </div>
      <div class="col-sm-12 col-xs-12">
        <div class="row">
        <div class="col-sm-6 col-xs-6">
          <label for="charge">Proof</label>
        </div>
        <div class="col-sm-6 col-xs-6">
          <div class="row">
            <div class="col-sm-6 col-xs-6">
              <label for="charge">Video Link</label>
            </div>
            <div class="col-sm-6 col-xs-6">
              <?php  ?>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-6 col-xs-6">
              <label for="charge">Image Link</label>
            </div>
            <div class="col-sm-6 col-xs-6">
              <?php  ?>
            </div>
          </div>
        </div>
      </div>
      </div>
      <div class="col-sm-12 col-xs-12">
        <div class="row">
        <div class="col-sm-6 col-xs-6">
          <label for="charge">Penal Actions</label>
        </div>
        <div class="col-sm-6 col-xs-6">
          <div class="row">
            <div class="col-sm-6 col-xs-6">
              <label for="charge">Penalty Amount</label>
            </div>
            <div class="col-sm-6 col-xs-6">
              <?php  ?>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-6 col-xs-6">
              <label for="charge">Other Legal Actions</label>
            </div>
            <div class="col-sm-6 col-xs-6">
              <?php  ?>
            </div>
          </div>
        </div>
      </div>
      </div>
      <div class="col-sm-12 col-xs-12">
        <div class="row">
          <?php echo "Name" ?>
        </div>
        <div class="row">
          <?php echo "Position" ?>
        </div>
        <div class="row">
          <?php echo "Signature" ?>
        </div>
      </div> -->
    </div>
    </div>
  <!-- </div> -->
</div>
<?php
ActiveForm::end();
?>
