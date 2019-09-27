<div class="content-area dashboard">
  <h1>Dashboard</h1>
    <div class="row">
    <?php if(Yii::$app->user->can('wms-dashboard-view-total-supervisors')||$userRole=='super-admin'):?>
      <div class="col-lg-6 col-md-6 col-sm-6 col-12">
      <div class="dashboard-box">
        <div class="row">
          <h1><?=$supervisorCount?></h1>
        </div>
        <div class="row">
          <h1><label for="eh-first-name">Total Supervisors</label></h1>
        </div>
      </div>
    </div>
  <?php endif;?>
  <?php if(Yii::$app->user->can('wms-dashboard-view-total-gt')||$userRole=='super-admin'):?>
      <div class="col-lg-6 col-md-6 col-sm-6 col-12">
        <div class="dashboard-box">
        <div class="row">
            <h1><?=$gtCount?></h1>
        </div>
        <div class="row">
          <h1><label for="eh-first-name">Total Green Technicians</label></h1>
        </div>
        </div>
      </div>
      </div>
       <?php endif;?>
    <div class="row">
     <?php if(Yii::$app->user->can('wms-dashboard-view-total-customer')||$userRole=='super-admin'):?>
      <div class="col-lg-6 col-md-6 col-sm-6 col-12 m-top">
        <div class="dashboard-box">
        <div class="row">
          <h1><?=$customerCount?></h1>
        </div>
        <div class="row">
          <h1><label for="eh-first-name">Total Survey Count</label></h1>
        </div>
      </div>
      </div>
    <?php endif;?>
    <?php if(Yii::$app->user->can('wms-dashboard-view-total-customer-package')||$userRole=='super-admin'):?>
      <div class="col-lg-6 col-md-6 col-sm-6 col-12 m-top">
        <div class="dashboard-box">
        <div class="row">
          <h1><?=$packageEnabledCustomerCount?></h1>
        </div>
        <div class="row">
          <h1><label for="eh-first-name">Total Customer Count</label></h1>
        </div>
      </div>
      </div>
      <?php endif;?>
   
    
  </div>
   <div class="row">
  <?php if(Yii::$app->user->can('wms-dashboard-view-total-pending-service')||$userRole=='super-admin'):?>
      <div class="col-lg-6 col-md-6 col-sm-6 col-12 m-top">
        <div class="dashboard-box">
        <div class="row">
          <h1><?=$pendingService?></h1>
        </div>
        <div class="row">
          <h1><label for="eh-first-name">Pending Service</label></h1>
        </div>
      </div>
      </div>
    <?php endif;?>
    <?php if(Yii::$app->user->can('wms-dashboard-view-total-pending-complaint')||$userRole=='super-admin'):?>
      <div class="col-lg-6 col-md-6 col-sm-6 col-12 m-top">
        <div class="dashboard-box">
        <div class="row">
          <h1><?=$pendingComplaints?></h1>
        </div>
        <div class="row">
          <h1><label for="eh-first-name">Pending Complaints</label></h1>
        </div>
      </div>
      </div>
      <?php endif;?>
  </div>


<?php if($userRole=='customer'):?>
  <div class="row">
      <div class="col-lg-6 col-md-6 col-sm-6 col-12">
      <div class="dashboard-box">
        <div class="row">
          <h1><?=$totalServiceCustomer?></h1>
        </div>
        <div class="row">
          <h1><label for="eh-first-name">Total Service</label></h1>
        </div>
      </div>
    </div>
      <div class="col-lg-6 col-md-6 col-sm-6 col-12">
        <div class="dashboard-box">
        <div class="row">
            <h1><?=$pendingServiceCustomer?></h1>
        </div>
        <div class="row">
          <h1><label for="eh-first-name">Pending Service</label></h1>
        </div>
        </div>
      </div>
      </div>

      <div class="row">
      <div class="col-lg-6 col-md-6 col-sm-6 col-12">
      <div class="dashboard-box">
        <div class="row">
          <h1><?=$totalComplaintsCustomer?></h1>
        </div>
        <div class="row">
          <h1><label for="eh-first-name">Total Complaints</label></h1>
        </div>
      </div>
    </div>
      <div class="col-lg-6 col-md-6 col-sm-6 col-12">
        <div class="dashboard-box">
        <div class="row">
            <h1><?=$pendingComplaintsCustomer?></h1>
        </div>
        <div class="row">
          <h1><label for="eh-first-name">Pending Complaints</label></h1>
        </div>
        </div>
      </div>
      </div>
<?php endif;?>
</div>
<div class="row">
  <?php if(Yii::$app->user->can('wms-dashboard-view-total-surveyor')||$userRole=='super-admin'):?>
      <div class="col-lg-6 col-md-6 col-sm-6 col-12 m-top">
        <div class="dashboard-box">
        <div class="row">
          <h1><?=$surveyorCount?></h1>
        </div>
        <div class="row">
          <h1><label for="eh-first-name">Total Surveyor Count</label></h1>
        </div>
      </div>
      </div>
    <?php endif;?>
</div>