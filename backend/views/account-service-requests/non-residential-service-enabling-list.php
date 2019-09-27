<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\web\View;
use backend\models\Ward;
use backend\models\District;
use backend\models\Customer;
use backend\models\Account;
use backend\models\AccountServiceRequest;
use backend\models\AccountAuthority;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
$modelUser  = Yii::$app->user->identity;
$userRole = $modelUser->role;
$modelCustomer = new Customer;
$modelAccountServiceRequest = new AccountServiceRequest;
$this->title = Yii::t('app', 'Subscription');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row bg-title">
    <div class="col-lg-2 col-md-4 col-sm-4 col-xs-12">
        <h4 class="page-title">Subscriptions</h4>
    </div>


      <div class="col-lg-3 col-sm-8 col-md-8 col-xs-12">
           <?php   $this->title =  'Subscription';
           $breadcrumb[] = ['label' => $this->title, 'template' => "<li class='active' , style='color:#4AAFF4;'>{link}</li>\n"];

            ?>
          <?=$this->render('/layouts/breadcrumbs',[ 'links'=>$breadcrumb]);?>

      </div>
    <!--   <?php if(Yii::$app->user->can('account-service-requests-create-account-service-requests')||$userRole=='super-admin'):?>
      <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <p>
          <?= Html::a(Yii::t('app', 'Create Subscription'), ['create-account-service-requests'], ['class' => 'btn btn-success']) ?>
        </p>
      </div>
    <?php endif;?> -->
      <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
        <?php $form = ActiveForm::begin(['action' =>Url::to(['account-service-requests/non-residential-service-enabling-request','set'=>1]),'options' => ['','data-pjax' => true,'class' => 'page-main-form search-form app-search hidden-sm hidden-xs m-r-10']]);?>
         <?php if($userRole!='customer'):?>
       <div class="col-lg-2 col-sm-2 col-md-8 col-xs-12">

           <?php
           // $keyword =  Yii::$app->session->get('name');
           $keyword =  isset($_POST['name'])?$_POST['name']:'';
           ?>
            <input type="text" name="name" value="<?php if (isset($keyword)) echo $keyword; ?>" style="background-color:#ccc;color:white;margin-top: 0px;" placeholder="Search..." class="form-control btn-behaviour-filter"> <a href="" class="active"></a>
          </div>
          <?php if(!isset($associations['district_id'])):?>
            <div class="col-lg-3 col-sm-3 col-md-4 col-xs-12">
            <div class="form-group" style=" margin-top: -13px;">
                 <?php
      $params = array_merge(Yii::$app->request->post(),Yii::$app->request->get());

      $options = ['class'=>'form-control btn-behaviour-filter','prompt' => 'District..','name'=>'district'];
      $key =  isset($_POST['district'])?$_POST['district']:'';

      if(isset($key)) {
        $option = $key;
        $options['options'] = [$option => ['selected'=>'selected']];
      }
            $district=Ward::getDistricts();
            $listData=ArrayHelper::map($district, 'id', 'name');

            echo $form->field($modelCustomer, 'district_id')->dropDownList($listData, $options)->label(false)?>
            </div>
          </div>
        <?php endif;?>
        <?php  if(!isset($associations['lsgi_id'])):?>
       <div class="col-lg-2 col-sm-2 col-md-8 col-xs-12">
            <div class="form-group" style=" margin-top: -13px;">
                 <?php
      $params = array_merge(Yii::$app->request->post(),Yii::$app->request->get());

      $options = ['class'=>'form-control btn-behaviour-filter','prompt' => 'Lsgi..','name'=>'lsgi'];
      $key =  isset($_POST['lsgi'])?$_POST['lsgi']:'';

      if(isset($key)) {
        $option = $key;
        $options['options'] = [$option => ['selected'=>'selected']];
      }
            $lsgi=Account::getLsgi();
            $listData=ArrayHelper::map($lsgi, 'id', 'name');

            echo $form->field($modelCustomer, 'lsgi_id')->dropDownList($listData, $options)->label(false)?>
            </div>
          </div>
        <?php endif;?>
        <?php  if(!isset($associations['ward_id'])):?>
          <div class="col-lg-2 col-sm-2 col-md-4 col-xs-12">
            <div class="form-group" style=" margin-top: -13px;">
                 <?php
      $params = array_merge(Yii::$app->request->post(),Yii::$app->request->get());

      $options = ['class'=>'form-control btn-behaviour-filter','prompt' => 'Ward..','name'=>'ward'];
      $key =  isset($_POST['ward'])?$_POST['ward']:'';

      if(isset($key)) {
        $option = $key;
        $options['options'] = [$option => ['selected'=>'selected']];
      }
            $ward=Ward::getWards();
            $listData=ArrayHelper::map($ward, 'id', 'name_en');

            echo $form->field($modelCustomer, 'ward_id')->dropDownList($listData, $options)->label(false)?>
            </div>
          </div>
        <?php endif;?>
         <?php  if(isset($associations['ward_id'])&&json_decode($associations['ward_id'])&&sizeof(json_decode($associations['ward_id']))>0):
          
            ?>
          <div class="col-lg-2 col-sm-2 col-md-4 col-xs-12">

            <div class="form-group" style=" margin-top: -13px;">
                 <?php
      $params = array_merge(Yii::$app->request->post(),Yii::$app->request->get());

      $options = ['class'=>'form-control btn-behaviour-filter','prompt' => 'Ward..','name'=>'ward'];
      $key =  isset($_POST['ward'])?$_POST['ward']:'';

      if(isset($key)) {
        $option = $key;
        $options['options'] = [$option => ['selected'=>'selected']];
      }
            $ward=Ward::getWards();
            $listData=ArrayHelper::map($ward, 'id', 'name_en');

            echo $form->field($modelCustomer, 'ward_id')->dropDownList($listData, $options)->label('Ward')?>
            </div>
          </div>
        <?php endif;?>
        <?php endif;?>
          <div class="col-lg-2 col-sm-2 col-md-4 col-xs-12">
            <div class="form-group" style=" margin-top: -13px;">
                 <?php
      $params = array_merge(Yii::$app->request->post(),Yii::$app->request->get());

      $options = ['class'=>'form-control btn-behaviour-filter','prompt' => 'Status..','name'=>'status'];
      $key =  isset($_POST['status'])?$_POST['status']:'';

      if(isset($key)) {
        $option = $key;
        $options['options'] = [$option => ['selected'=>'selected']];
      }
            $listData= ['2' => 'Not Approved','1' => 'Approved'];

            echo $form->field($modelAccountServiceRequest, 'is_approved')->dropDownList($listData, $options)->label(false)?>
            </div>
          </div>

         <div class="col-lg-2 col-sm-6 col-md-8 col-xs-12">
          <div class="form-group" style=" margin-top: -13px;">
          <?php $from = isset($_POST['from'])?$_POST['from']:'';?>
            <input type="text" name="from" value="<?php if (isset($from))
    {
        echo $from;
}
?>" style="background-color:#ccc;color:white;margin-top: 0px;" placeholder="From....." class="form-control datepicker btn-behaviour-filter"> <a href="" class="active"></a>
      </div>
      </div>
      <div class="col-lg-2 col-sm-6 col-md-8 col-xs-12">
      <div class="form-group" style=" margin-top: -13px;">
      <?php $to = isset($_POST['to'])?$_POST['to']:'';?>
            <input type="text" name="to" value="<?php if (isset($to))
    {
        echo $to;
}
?>" style="background-color:#ccc;color:white;margin-top: 0px;" placeholder="To....." class="form-control datepicker btn-behaviour-filter"> <a href="" class="active"></a>
      </div>
      </div>
      <?php ActiveForm::end(); ?>
      </div>
</div>
<div class="row">
  <div class="col-md-12">
    <div class="white-box">
      <div class="scrollable">
        <div class="table-responsive">
          <?php Pjax::begin(['timeout' => 50000,'enablePushState' => false,
                      'id' =>'pjax-account-service-requests-non-residentiallist', 'options'=>['data-loader'=>'.preloader']]); ?>
    <?php
        $columns = [
                // [
                // 'label' =>'Customer Name',
                // 'value'=>'fkAccount.fkCustomer.lead_person_name',
                // return Html::a($model->name,['building-types/view?id='.$model->id],['data-pjax'=>0]);
                // ],
                [
                    'attribute' =>  'lead_person_name',
                    'label' =>'Customer Name',
                    'contentOptions'=>[ 'style'=>'width: 250px'],
                    'format' => 'raw',
                    'value'=>function ($model) {
                       $page = isset($_GET['page']) ? $_GET['page']:1;
                      $modelUser  = Yii::$app->user->identity;
                      $userRole = $modelUser->role;
                      $name =  isset($model->fkAccount->fkCustomer->id)?$model->fkAccount->fkCustomer->lead_person_name:'';
                      if($model->is_approved!=1)
                      {
                        return Html::a($name,['account-service-requests/update-service-enabling-request?id='.$model->id],['data-pjax'=>0]);
                      }
                      elseif($modelUser->role=='junior-health-inspector')
                      {
                        return Html::a($name,['account-service-requests/update-service-enabling-request?id='.$model->id],['data-pjax'=>0]);
                      }
                      else
                      {
                        return Html::a($name);
                      }
                    },
                      
                  ],
                // [
                //     'attribute' =>  'lead_person_name',
                //     'label' =>'Customer Id',
                //     'contentOptions'=>[ 'style'=>'width: 250px'],
                //     'format' => 'raw',
                //     'value'=>function ($model) {
                //       $page = isset($_GET['page']) ? $_GET['page']:1;
                //       return isset($model->fkAccount->fkCustomer->id)?Customer::getFormattedCustomerId($model->fkAccount->fkCustomer->id):'';
                //        // return Html::a($model->lead_person_name);
                      
                //     },
                //   ],
                  [
                    'attribute' =>  'lead_person_name',
                    'label' =>'Building Type',
                    'contentOptions'=>[ 'style'=>'width: 250px'],
                    'format' => 'raw',
                    'value'=>function ($model) {
                      $page = isset($_GET['page']) ? $_GET['page']:1;
                      return isset($model->fkAccount->fkCustomer->fkBuildingType)?$model->fkAccount->fkCustomer->fkBuildingType->name:'';
                       // return Html::a($model->lead_person_name);
                      
                    },
                  ],
                   [
                    'attribute' =>  'lead_person_name',
                    'label' =>'Ward',
                    'contentOptions'=>[ 'style'=>'width: 250px'],
                    'format' => 'raw',
                    'value'=>function ($model) {
                      $page = isset($_GET['page']) ? $_GET['page']:1;
                      return isset($model->fkAccount->fkCustomer->fkWard->name_en)?$model->fkAccount->fkCustomer->fkWard->name_en:'';
                    },
                  ],
                  [
                    'attribute' =>  'lead_person_name',
                    'label' =>'Institution Name',
                    'contentOptions'=>[ 'style'=>'width: 250px'],
                    'format' => 'raw',
                    'value'=>function ($model) {
                      $page = isset($_GET['page']) ? $_GET['page']:1;
                      return isset($model->fkAccount->fkCustomer->building_name)?$model->fkAccount->fkCustomer->building_name:'';
                    },
                  ],
                  [
                    'attribute' =>  'requested_by',
                    'label' =>'Requested By',
                    'contentOptions'=>[ 'style'=>'width: 250px'],
                    'format' => 'raw',
                    'value'=>function ($model) {
                      $page = isset($_GET['page']) ? $_GET['page']:1;
                      return $model->getRequestedDetails($model->account_id_requested_by);
                      
                    },
                  ],
                  [
                    'attribute' =>  'requested_by',
                    'label' =>'First Level Verification By',
                    'contentOptions'=>[ 'style'=>'width: 250px'],
                    'format' => 'raw',
                    'value'=>function ($model) {
                      $page = isset($_GET['page']) ? $_GET['page']:1;
                      return $model->getRequestedDetails($model->account_id_pre_approved_by);
                      
                    },
                  ],
                  [
                    'attribute' =>  'requested_by',
                    'label' =>'Second Level Verification By',
                    'contentOptions'=>[ 'style'=>'width: 250px'],
                    'format' => 'raw',
                    'value'=>function ($model) {
                      $page = isset($_GET['page']) ? $_GET['page']:1;
                      return $model->getRequestedDetails($model->account_id_approved_by);
                      
                    },
                  ],
                // [
                // 'label' =>'Green Technician',
                // 'value'=>'fkAccountGt.fkPerson.first_name',
                // ],
                [
                    'attribute' =>  'lead_person_name',
                    'label' =>'Green Technician',
                    'contentOptions'=>[ 'style'=>'width: 250px'],
                    'format' => 'raw',
                    'value'=>function ($model) {
                      $gtName = null;
                      $page = isset($_GET['page']) ? $_GET['page']:1;
                      $modelUser = Yii::$app->user->identity;
                      if(isset($model->account_id))
                      {

                        $accountAuthority = AccountAuthority::find()->where(['account_id_customer'=>$model->account_id])->andWhere(['status'=>1]);
                        if($modelUser->role=='supervisor')
                        {
                          $accountAuthority->andWhere(['account_id_supervisor'=>$modelUser->id]);
                        }
                        $accountAuthority = $accountAuthority->all();
      if($accountAuthority)
      {
        foreach ($accountAuthority as $key => $value) {
          $account = $value->fkAccountGt;
        if($account)
        {
          $gtDetails = $account->fkPerson;
          if($gtDetails)
          {
            $gtName = $gtName.','.$gtDetails->first_name;
          }
        }
        }
        $gtName = trim($gtName,",");
      }
      return $gtName;
                      }
                      else{
                        return '--';
                      }
                    },
                  ],
                  // [
                  //   'attribute' =>  'lead_person_name',
                  //   'label' =>'Services',
                  //   'contentOptions'=>[ 'style'=>'width: 250px'],
                  //   'format' => 'raw',
                  //   'value'=>function ($model) {
                  //     $page = isset($_GET['page']) ? $_GET['page']:1;
                  //     if(isset($model->fkService->name))
                  //     {
                  //       return $model->fkService->name;
                  //     }
                  //     else
                  //     {
                  //       return $model->getServices($model->sub_service);
                  //     }
                      
                  //   },
                  // ],
                // [
                // 'label' =>'Service',
                // 'value'=>'fkService.name',
                // ],
                [
                    'attribute' =>  'date',
                     'label' =>'Date',
                    'contentOptions'=>[ 'style'=>'width: 250px'],
                    'format' => 'raw',
                    'value'=>function ($model) {
                      $page = isset($_GET['page']) ? $_GET['page']:1;
                      return date('d-M-Y', strtotime($model->requested_at));
                    },
                  ],
                  // [
                  //   'attribute' =>  'request_type',
                  //    'label' =>'Request Type',
                  //   'contentOptions'=>[ 'style'=>'width: 250px'],
                  //   'format' => 'raw',
                  //   'value'=>function ($model) {
                  //     $page = isset($_GET['page']) ? $_GET['page']:1;
                  //     if($model->request_type==1)
                  //       $type = 'Enable request';
                  //   else
                  //       $type = 'Disable request';
                  //     return $type;
                  //   },
                  // ],
                           
        ];
        if($userRole=='super-admin'||$userRole=='health-inspector'){
        $columns3 = [             
                   [
                  'format'=>'raw',
                  'attribute' => 'is_pre_approved',
                  // 'label' => 'Active/Inactive' ,
                  'label' => 'Verify' ,
                  'encodeLabel' => false,
                  'value' => function ($model)
                  {
                    $statusLabels = [
                     ['label'=>'pre-approve','cssClass'=>'label-danger'],
                     // ['label'=>'Inactive','cssClass'=>'label-success'],
                     ['label'=>'Pre-approved','cssClass'=>'label-success'],
                   ];
                    $status = (int)$model->is_pre_approved;
                    $status = $status != -1?$status:1;
                    $labelCur = $statusLabels[$status];
                    $cssClass = $labelCur['cssClass'];
                    $class = "label $cssClass";
                    $label = $labelCur['label'];
                    $page = isset($_GET['page'])?trim($_GET['page']):0;
                    $user = Yii::$app->user->identity;
                    $url = Yii::$app->urlManager->createUrl(['account-service-requests/toggle-status-first-approved','id'=>$model->id]);
                    if($model->is_pre_approved==0){
                    return Html::a("<a style='color: #fff' onclick='deleteItem(\"$url\",\"#pjax-account-service-requests-non-residentiallist\",$page)'  id='btn-status' class='btn $class margin-$model->id' >$label </button>");
                }else
                {
                    return Html::a("<a style='color: #fff' id='btn-status' class='btn $class margin-$model->id' >$label </button>");
                }

                  },
                   ],  

              ];
    }else
    {
      $columns3 = [];
    }
        if($userRole=='super-admin'||$userRole=='health-supervisor'){
        $columns2 = [             
                   [
                  'format'=>'raw',
                  'attribute' => 'is_approved',
                  // 'label' => 'Active/Inactive' ,
                  'label' => 'Approve' ,
                  'encodeLabel' => false,
                  'value' => function ($model)
                  {
                    $statusLabels = [
                     ['label'=>'Approve','cssClass'=>'label-danger'],
                     // ['label'=>'Inactive','cssClass'=>'label-success'],
                     ['label'=>'Approved','cssClass'=>'label-success'],
                   ];
                    $status = (int)$model->is_approved;
                    $status = $status != -1?$status:1;
                    $labelCur = $statusLabels[$status];
                    $cssClass = $labelCur['cssClass'];
                    $class = "label $cssClass";
                    $label = $labelCur['label'];
                    $page = isset($_GET['page'])?trim($_GET['page']):0;
                    $user = Yii::$app->user->identity;
                    $url = Yii::$app->urlManager->createUrl(['account-service-requests/toggle-status-approved-non-residential','id'=>$model->id]);
                    if($model->is_approved==0&&$model->is_pre_approved==1){
                    return Html::a("<a style='color: #fff' onclick='deleteItem(\"$url\",\"#pjax-account-service-requests-non-residentiallist\",$page)'  id='btn-status' class='btn $class margin-$model->id' >$label </button>");
                }elseif($model->is_pre_approved==1)
                {
                    return Html::a("<a style='color: #fff' id='btn-status' class='btn $class margin-$model->id' >$label </button>");
                }else
                {
                  return 'Not Approved';
                }

                  },
                   ],  

              ];
    }else
    {
      $columns2 = [];
    }
    $columns = array_merge($columns,$columns3);
    $columns = array_merge($columns,$columns2);
    

?>

         <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'summary'=>'',
        'columns'=>$columns,
    ]); ?>
  <?php
 $this->registerJs("
$('.btn-behaviour-filter').on('change', function() {
 $('.search-form').submit();

 });
 $('.datepicker').datepicker({
           orientation:'top',
           format:'dd-mm-yyyy',
           autoclose:true,
           todayHighlight:true,
       });
 ",View::POS_END);
 Pjax::end();?>
</div>
</div>
</div>
</div>
</div>
