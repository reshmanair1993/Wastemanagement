<?php
use yii\widgets\Menu;
use backend\models\Person;
use backend\models\AccountBackend;
use backend\models\AccountProvider;
use backend\models\CmsPostTypes;
use backend\models\Image;
use backend\models\Lsgi;
use yii\helpers\Url;
$modelUser  = Yii::$app->user->identity;
$lsgi = isset($modelUser->lsgi_id)?$modelUser->lsgi_id:null;
 ?>
  <div class="navbar-default sidebar" role="navigation">
    <div class="sidebar-nav slimscrollsidebar">
       <!--  <div class="sidebar-head">
            <h3><span class="fa-fw open-close"><i class="ti-menu hidden-xs"></i><i class="ti-close visible-xs"></i></span> <span class="hide-menu"> Management</span></h3>
        </div> -->
        <div class="sidebar-header hide-for-print">
        <a class="" href="<?=Url::to(['dashboard/index'])?>">
          <img src=<?=Url::to('@web/images/logo-trivandrum-copy.png')?> alt="Green Trivandrum" style="margin-left: 26px;">
        </a>
      </div>
          <?php
         if(isset($modelUser->role)&&Yii::$app->user&&Yii::$app->user->identity&& Yii::$app->user->identity->id){
          $userRole = $modelUser->role;
          $query = \backend\models\AuthItem::find()
          ->leftjoin('auth_item_child','auth_item_child.child=auth_item.name')
          ->where(['auth_item_child.parent'=>$userRole])
          ->andWhere(['auth_item.type'=>1])
          ->all();
          $roleArray = [];
          if($query){
            foreach ($query as $qry) {
              $roleList['label'] = $qry->name;
              $roleList['url'] = ['rbac/users-index','type'=>$qry->name];
              $roleList['permissions'] = ['user-list'];
              $roleList['icon'] = 'admin_user_unselected.png';
              $roleList['active_icon'] = 'admin_user_selected.png';
              $roleArray[] = $roleList;
            }
          }else{
            $roleList = [];
          }
           }
        else{
            $roleList = [];
            $roleArray[] = $roleList;
          }
          // print_r($roleArray);exit;
          $rbacChildrenList = $roleArray;
          // print_r($rbacChildrenList);exit;

           $contentArray1 = [];
              $contentList1['label'] = 'Post Types';
              $contentList1['url'] = ['post-types/index'];
              $contentList1['permissions'] = ['post-types-index'];
              $contentList1['icon'] = 'post_type_unselected.png';
              $contentList1['active_icon'] = 'post_type.png';
              $contentArray1[] = $contentList1;
          $contentArray2 = [];
              $contentList2['label'] = 'Posts';
              $contentList2['url'] = ['posts/index'];
              $contentList2['permissions'] = ['posts-index'];
              $contentList2['icon'] = 'post_unselected.png';
              $contentList2['active_icon'] = 'post.png';
              $contentArray2[] = $contentList2;
          $contentArray3 = [];
              $contentList3['label'] = 'Pages';
              $contentList3['url'] = ['pages/index'];
              $contentList3['permissions'] = ['pages-index'];
              $contentList3['icon'] = 'pages_unselected.png';
              $contentList3['active_icon'] = 'pages.png';
              $contentArray3[] = $contentList3;
          $contentArray4 = [];
              $contentList4['label'] = 'Settings';
              $contentList4['url'] = ['settings/index'];
              $contentList4['permissions'] = ['settings-index'];
              $contentList4['icon'] = 'settings_unselected.png';
              $contentList4['active_icon'] = 'settings.png';
              $contentArray4[] = $contentList4;

            $queryNew = \backend\models\CmsPostTypes::find()
          ->where(['cms_post_types.status'=>1])
          ->all();
          $contentArray = [];
          if($queryNew){
            foreach ($queryNew as $qry) {
              $contentList['label'] = $qry->name;
              $contentList['url'] = ['posts/index','type'=>$qry->id];
              $contentList['permissions'] = ['posts-index'];
              $contentList['icon'] = 'post_type_unselected_.png';
              $contentList['active_icon'] = 'post_type_.png';
              $contentArray[] = $contentList;
            }
          }else{
            $contentList = [];
            $contentArray[] = $contentList;
          }
$contentArrayNew = array_merge($contentArray4,$contentArray3);
$contentArrayNew = array_merge($contentArrayNew,$contentArray1);
$contentArrayNew = array_merge($contentArrayNew,$contentArray2);
$contentArrayNew = array_merge($contentArrayNew,$contentArray);
          $contentChildrenList = $contentArrayNew;


          $linksSuperAdmin = [
            [
              'label' => 'Dashboard',
              'url' => ['wms-dashboard/'],
              'roles' => ['super-admin','admin-lsgi','admin-hks','coordinator','supervisor'],
              'permissions' => ['wms-dashboard-index'],
              // 'icon' => 'fa fa-dashboard',
'icon' => 'dashboard_unselected.png',
              'active_icon' => 'dashboard_selected.png'
            ],
            [
              'label' => 'Profile',
              'url' => ['customers/profile'],
              'roles' => ['super-admin','admin-lsgi','admin-hks','coordinator','supervisor'],
              'permissions' => ['Customers-profile'],
              // 'icon' => 'fa fa-dashboard',
              'icon' => 'dashboard_unselected.png',
              'active_icon' => 'dashboard_selected.png'
            ],
            [
              'label' => 'Users',
              'icon' => 'user_unselected.png',
              'active_icon' => 'user_selected.png',
              'roles' => ['super-admin','admin-lsgi','admin-hks','coordinator','supervisor'],
              'permissions' => [
                'Account-super-admin','Account-index','Account-admin-lsgi','Account-admin-hks',
                'Account-supervisors','Account-green-technicians','Account-coordinators'
              ],
              'children' => $rbacChildrenList
            ],
            [
              'label' => 'Survey Agencies ',
              'url' => ['survey-agencies/index'],
              'roles' => ['super-admin','admin-lsgi'],
              'permissions' => [
                'survey-agencies-index'
            ],
              'icon' => 'survey_agencies_unselected.png',
              'active_icon' => 'survey_agency_selected.png'
            ],
            [
              'label' => 'Assign Supervisor ',
              'url' => ['account-supervisor/index'],
              'roles' => ['super-admin','admin-lsgi','admin-hks'],
              'permissions' => [
                'account-supervisor-index'
              ],
              'icon' => 'supervisor_unselected.png',
              'active_icon' => 'supervisor_selected.png'
            ],
            [
              'label' => 'Assign Gt ',
              'url' => ['account-gt/index'],
              'roles' => ['super-admin','admin-lsgi','admin-hks','supervisor'],
              'permissions' => [
                'account-gt-index'
              ],
              'icon' => 'gt_unselected.png',
              'active_icon' => 'gt_selected.png'
            ],
            [
              'label' => 'Schedule ',
              'url' => ['schedule/index'],
              'roles' => ['supervisor'],
              'permissions' => ['schedule-index'],
              'icon' => 'schedule_unselected.png',
              'active_icon' => 'schedule_selected.png'
            ],
            [
              'label' => 'QR Code ',
              'url' => ['qr-code/'],
              'roles' => ['super-admin','admin-lsgi'],
              'permissions' => ['qr-code-index'],
              'icon' => 'qr_code_unselected.png',
              'active_icon' => 'qr_code_selected.png'
            ],
            [
              'label' => 'Customers  ',
              'url' => ['customers/index'],
              'roles' => ['super-admin','admin-lsgi','admin-hks','supervisor','coordinator'],
              'permissions' => ['Customers-index'],
              'icon' => 'customer_unselected.png',
              'active_icon' => 'customer_selected.png'
            ],
            [
              'label' => 'Subscriptions  ',
              'url' => ['account-service-requests/index'],
               'roles' => ['super-admin','admin-lsgi','admin-hks','supervisor'],
               'permissions' => ['account-service-requests-index'],
              'icon' => 'Subscription_unselected.png',
              'active_icon' => 'Subscription_selected.png'
            ],
            // [
            //   'label' => 'Deactivation Requests  ',
            //   'url' => ['deactivation-requests/index'],
            //   'icon' => 'fa fa-dashboard'
            // ],
            // [
            //   'label' => 'Fee collected  ',
            //   'url' => ['account-fee/index'],
            //   'icon' => 'fa fa-dashboard'
            // ],
            [
              'label' => 'Payment Requests  ',
              'url' => ['payment-requests/index'],
               'roles' => ['super-admin','admin-lsgi','admin-hks','supervisor'],
               'icon' => 'payment_request_unselected.png',
               'active_icon' => 'payment_request_selected.png'
            ],
            [
              'label' => 'Payments  ',
              'url' => ['payments/index'],
               'roles' => ['super-admin','admin-lsgi','admin-hks','supervisor'],
               'permissions' => ['payments-index'],
              'icon' => 'payment_unselected.png',
              'active_icon' => 'payments_selected.png'
            ],
            [
              'label' => 'Service Requests  ',
              'url' => ['service-requests/index'],
               'roles' => ['super-admin','admin-lsgi','admin-hks','supervisor'],
               'permissions' => ['service-requests-index'],
              'icon' => 'service_request_unselected.png',
              'active_icon' => 'service_request_selected.png'
            ],
            [
              'label' => 'Special Service Requests  ',
              'url' => ['service-requests/special-services'],
               'roles' => ['super-admin','admin-lsgi','admin-hks','supervisor'],
               'permissions' => ['service-requests-special-services'],
              'icon' => 'special_service_request.png',
              'active_icon' => 'special_service_request_selected.png'
            ],
            [
              'label' => 'Complaints  ',
              'url' => ['requests/complaints'],
               'roles' => ['super-admin','admin-lsgi','admin-hks','supervisor'],
               'permissions' => ['requests-complaints'],
              'icon' => 'complaints_unselected.png',
              'active_icon' => 'complaints_selected.png'
            ],
            [
              'label' => 'Nearest Mrc',
              'url' => ['mrc/index'],
               'roles' => ['super-admin','admin-lsgi'],
               'permissions' => ['Mrc-index'],
              'icon' => 'mrf_unselected.png',
              'active_icon' => 'mrf.png',
            ],
            [
              'label' => 'Dumping Events',
              'url' => ['dumping-events/index'],
               'roles' => ['super-admin','admin-lsgi'],
               'permissions' => ['dumping-events-index'],
              'icon' => 'dumping_event_unselected.png',
              'active_icon' => 'dumping_event.png',
            ],
            [
              'label' => 'Escalated Service Requests',
              'url' => ['escalations/service'],
               'roles' => ['super-admin','admin-lsgi'],
               'permissions' => ['escalations-service'],
               'icon' => 'escalated_service_request_unselected.png',
              'active_icon' => 'escalator_service_request.png',
            ],
             [
              'label' => 'Escalated Complaints',
              'url' => ['escalations/complaints'],
               'roles' => ['super-admin','admin-lsgi','coordinator'],
               'permissions' => ['escalations-complaints'],
              'icon' => 'escalator_complaint_unselected.png',
              'active_icon' => 'escalator_complaint.png',
              ],

              [
              'label' => 'CMS',
              // 'url' => ['reports/collection'],
               'roles' => ['super-admin','admin-lsgi','coordinator'],
               'permissions' => ['posts-index'],
              'icon' => 'cms_unselected.png',
               'active_icon' => 'cms.png',
               'children' =>  $contentChildrenList
            ],
            [
              'label' => 'Enquiries',
              'url' => ['enquiries/index'],
               'roles' => ['super-admin','admin-lsgi','coordinator'],
               'permissions' => ['enquiries-index'],
              'icon' => 'enquiry_unselected.png',
              'active_icon' => 'enquiry.png',
              ],

            [
              'label' => 'Reports',
              'icon' => 'reports_unselected.png',
              'active_icon' => 'reports_selected.png',
               'roles' => ['super-admin','admin-lsgi','admin-hks','coordinator'],
               'permissions' => [
                 'reports-door-close','reports-survey-count','reports-waste-management-type',
                 'reports-ward-wise-count','reports-collection'
               ],
              'children' => [
               [
              'label' => 'Door Close Report',
              'url' => ['reports/door-close'],
               'roles' => ['super-admin','admin-lsgi','coordinator'],
               'permissions' => ['reports-door-close'],
              'icon' => 'Closed_door_unselected.png',
              'active_icon' => 'Closed_door_selected.png'
            ],
                [
              'label' => 'Survey Count Report',
              'url' => ['reports/survey-count'],
               'roles' => ['super-admin','admin-lsgi','coordinator'],
               'permissions' => ['reports-survey-count'],
              'icon' => 'Survey_count_report_unselected.png',
              'active_icon' => 'survey_count_report_selected.png'
            ],
            [
              'label' => 'Waste Management Type Report',
              'url' => ['reports/waste-management-type'],
               'roles' => ['super-admin','admin-lsgi','admin-hks','supervisor'],
               'permissions' => ['reports-waste-management-type'],
              'icon' => 'waste_management_report_unselected.png',
              'active_icon' => 'waste_management_report_selected.png'
            ],
            [
              'label' => 'Ward Wise Count Report',
              'url' => ['reports/ward-wise-count'],
               'roles' => ['super-admin','admin-lsgi','coordinator'],
               'permissions' => ['reports-ward-wise-count'],
              'icon' => 'Ward_wise_Report_unselected.png',
              'active_icon' => 'ward_wise_report_selected.png'
            ],
            [
              'label' => 'Collection Report',
              // 'url' => ['reports/collection'],
               'roles' => ['super-admin','admin-lsgi','coordinator'],
               'permissions' => ['reports-collection'],
              'icon' => 'collection Report_unselected.png',
               'active_icon' => 'collection Report_selected.png',
               'children' => [
                [
              'label' => 'Detailed Report',
              'url' => ['reports/collection'],
               'roles' => ['super-admin','admin-lsgi','coordinator'],
               'permissions' => ['reports-collection'],
              'icon' => 'Detailed Report_unselected.png',
               'active_icon' => 'Detailed Report_selected.png'
              ],
              [
              'label' => 'Summary Report',
              'url' => ['reports/collection-summary'],
               'roles' => ['super-admin','admin-lsgi','coordinator'],
               'permissions' => ['reports-collection-summary'],
              'icon' => 'Summary Report_unselected.png',
               'active_icon' => 'Summary Report_selected.png'
              ],
               ],
            ],

            [
              'label' => 'Subscription Report',
              // 'url' => ['reports/collection'],
               'roles' => ['super-admin','admin-lsgi','coordinator'],
               'permissions' => ['reports-subscriptions'],
              'icon' => 'Subscription Report_unselected.png',
              'active_icon' => 'Subscription Report_selected.png',
               'children' => [
                [
              'label' => 'Detailed Report',
              'url' => ['reports/subscriptions'],
               'roles' => ['super-admin','admin-lsgi','coordinator'],
               'permissions' => ['reports-subscriptions'],
              'icon' => 'Detailed Report_unselected.png',
               'active_icon' => 'Detailed Report_selected.png'
              ],
              [
              'label' => 'Summary Report',
              'url' => ['reports/subscription-summary'],
               'roles' => ['super-admin','admin-lsgi','coordinator'],
               'permissions' => ['reports-subscription-summary'],
              'icon' => 'Summary Report_unselected.png',
               'active_icon' => 'Summary Report_selected.png'
              ],
               ],
            ],
            [
              'label' => 'Service Account Disabled Report',
              // 'url' => ['reports/collection'],
               'roles' => ['super-admin','admin-lsgi','coordinator'],
               'permissions' => ['reports-subscription-disabled'],
              'icon' => 'Service Account  Report_unselected.png',
               'active_icon' => 'Service Account  Report_selected.png',
               'children' => [
                [
              'label' => 'Detailed Report',
              'url' => ['reports/subscription-disabled'],
               'roles' => ['super-admin','admin-lsgi','coordinator'],
               'permissions' => ['reports-subscription-disabled'],
              'icon' => 'Detailed Report_unselected.png',
               'active_icon' => 'Detailed Report_selected.png'
              ],
              [
              'label' => 'Summary Report',
              'url' => ['reports/subscription-disabled-summary'],
               'roles' => ['super-admin','admin-lsgi','coordinator'],
               'permissions' => ['reports-subscription-disabled-summary'],
              'icon' => 'Summary Report_unselected.png',
               'active_icon' => 'Summary Report_selected.png'
              ],
               ],
            ],

            [
              'label' => 'Complaints Report',
              // 'url' => ['reports/collection'],
               'roles' => ['super-admin','admin-lsgi','coordinator'],
               'permissions' => ['reports-complaints'],
              'icon' => 'complaint_reports_unselected.png',
              'active_icon' => 'complaint_reports_selected.png',
               'children' => [
                [
              'label' => 'Detailed Report',
              'url' => ['reports/complaints'],
               'roles' => ['super-admin','admin-lsgi','coordinator'],
               'permissions' => ['reports-complaints'],
              'icon' => 'Detailed Report_unselected.png',
               'active_icon' => 'Detailed Report_selected.png'
              ],
              [
              'label' => 'Summary Report',
              'url' => ['reports/complaints-summary'],
               'roles' => ['super-admin','admin-lsgi','coordinator'],
               'permissions' => ['reports-complaints-summary'],
              'icon' => 'Summary Report_unselected.png',
               'active_icon' => 'Summary Report_selected.png'
              ],
               ],
            ],
            [
              'label' => 'Complaints Resolution Report',
              // 'url' => ['reports/collection'],
               'roles' => ['super-admin','admin-lsgi','coordinator'],
               'permissions' => ['reports-complaint-resolution'],
              'icon' => 'complaint_resolution_unselected.png',
              'active_icon' => 'complaint_resolution_selected.png',
               'children' => [
                [
              'label' => 'Detailed Report',
              'url' => ['reports/complaint-resolution'],
               'roles' => ['super-admin','admin-lsgi','coordinator'],
               'permissions' => ['reports-complaint-resolution'],
              'icon' => 'Detailed Report_unselected.png',
               'active_icon' => 'Detailed Report_selected.png'
              ],
              [
              'label' => 'Summary Report',
              'url' => ['reports/complaint-resolution-summary'],
               'roles' => ['super-admin','admin-lsgi','coordinator'],
               'permissions' => ['reports-complaint-resolution-summary'],
              'icon' => 'Summary Report_unselected.png',
               'active_icon' => 'Summary Report_selected.png'
              ],
               ],
            ],
            [
              'label' => 'Service Completion Report',
              // 'url' => ['reports/collection'],
               'roles' => ['super-admin','admin-lsgi','coordinator'],
               'permissions' => ['reports-service-completion'],
              'icon' => 'Service_completion_unselected.png',
               'active_icon' => 'Service_completion_selected.png',
               'children' => [
                [
              'label' => 'Detailed Report',
              'url' => ['reports/service-completion'],
               'roles' => ['super-admin','admin-lsgi','coordinator'],
               'permissions' => ['reports-service-completion'],
              'icon' => 'Detailed Report_unselected.png',
               'active_icon' => 'Detailed Report_selected.png'
              ],
              [
              'label' => 'Summary Report',
              'url' => ['reports/service-completion-summary'],
               'roles' => ['super-admin','admin-lsgi','coordinator'],
               'permissions' => ['reports-service-completion-summary'],
              'icon' => 'Summary Report_unselected.png',
               'active_icon' => 'Summary Report_selected.png'
              ],
               ],
            ],
            [
              'label' => 'Service Pending Report',
              // 'url' => ['reports/collection'],
               'roles' => ['super-admin','admin-lsgi','coordinator'],
               'permissions' => ['reports-service-pending'],
              'icon' => 'service_pending_unselected.png',
               'active_icon' => 'service_pending_selected.png',
               'children' => [
                [
              'label' => 'Detailed Report',
              'url' => ['reports/service-pending'],
               'roles' => ['super-admin','admin-lsgi','coordinator'],
               'permissions' => ['reports-service-pending'],
              'icon' => 'Detailed Report_unselected.png',
               'active_icon' => 'Detailed Report_selected.png'
              ],
              [
              'label' => 'Summary Report',
              'url' => ['reports/service-pending-summary'],
               'roles' => ['super-admin','admin-lsgi','coordinator'],
               'permissions' => ['reports-service-pending-summary'],
             'icon' => 'Summary Report_unselected.png',
               'active_icon' => 'Summary Report_selected.png'
              ],
               ],
            ],
            [
              'label' => 'Customer Plan Report',
              // 'url' => ['reports/collection'],
               'roles' => ['super-admin','admin-lsgi','coordinator'],
               'permissions' => ['reports-customer-plan'],
              'icon' => 'customer_plan_unselected.png',
              'active_icon' => 'customer_plan_selected.png',
               'children' => [
                [
              'label' => 'Detailed Report',
              'url' => ['reports/customer-plan'],
               'roles' => ['super-admin','admin-lsgi','coordinator'],
               'permissions' => ['reports-customer-plan'],
              'icon' => 'Detailed Report_unselected.png',
              'active_icon' => 'Detailed Report_selected.png'
              ],
              [
              'label' => 'Summary Report',
              'url' => ['reports/customer-plan-summary'],
               'roles' => ['super-admin','admin-lsgi','coordinator'],
               'permissions' => ['reports-customer-plan-summary'],
              'icon' => 'Summary Report_unselected.png',
              'active_icon' => 'Summary Report_selected.png'
              ],
               ],
            ],
            [
              'label' => 'Itemwise Service Completion Report',
              'url' => ['reports/itemwise-service-completion'],
               'roles' => ['super-admin','admin-lsgi','coordinator'],
               'permissions' => ['reports-itemwise-service-completion'],
              'icon' => 'Itemwis_service_completion_unselected.png',
              'active_icon' => 'Itemwis_service_completion_selected.png'
            ],
            [
              'label' => 'Itemwise Service Pending Report',
              'url' => ['reports/itemwise-service-pending'],
               'roles' => ['super-admin','admin-lsgi','coordinator'],
               'permissions' => ['reports-itemwise-service-pending'],
              'icon' => 'Itemwis_service_pending_unselected.png',
              'active_icon' => 'Itemwis_service_pending_selected.png'
            ],
            [
              'label' => 'Waste Quality Collected Report',
              'url' => ['reports/waste-quality-collected'],
               'roles' => ['super-admin','admin-lsgi','coordinator'],
               'permissions' => ['reports-waste-quality-collected'],
              'icon' => 'Waste_quality_collected_unselected.png',
              'active_icon' => 'Waste_quality_collected_selected.png'
            ],
            [
              'label' => 'Waste Quantity Collected Report',
              'url' => ['reports/waste-quantity-collected'],
               'roles' => ['super-admin','admin-lsgi','coordinator'],
               'permissions' => ['reports-waste-quantity-collected'],
              'icon' => 'Waste_Quantity_Collected_unselected.png',
              'active_icon' => 'Waste_Quantity_Collected_selected.png'
            ],
            
              ]
            ],
           [
              'label' => 'Preliminary Configuration',
              'roles' => ['super-admin','admin-lsgi'],
              'permissions' => [
                'states-index','districts-index','parliament-constituency-index',
                'assembly-constituency-index','lsgi-type-index','lsgi-blocks-index',
                'lsgis-index','wards-index','residence-categories-index',
                'waste-categories-index','residence-categories-index',
                'green-action-units-index','public-gathering-methods-index',
                'waste-collection-methods-index','building-types-index',
                'trading-types-index','shop-types-index','fee-collection-interval-index',
                'terrace-farming-help-types-index','services-index','bundled-services-index',
                'complaints-index','administration-type-index',
              ],
              'icon' => 'configuration_unselected.png',
              'active_icon' => 'configuration_selected.png',
              'children' => [
                [
              'label' => 'State',
              'url' => ['states/'],
              'roles' => ['super-admin'],
              'permissions' => ['states-index'],
               'icon' => 'state-unselected.png',
              'active_icon' => 'state-selected.png'
            ],
            [
              'label' => 'District',
              'url' => ['districts/'],
              'roles' => ['super-admin'],
              'permissions' => ['districts-index'],
              'icon' => 'district_unselected.png',
              'active_icon' => 'district_selected.png'
            ],
            [
              'label' => 'Parliament Constituency',
              'url' => ['parliament-constituency/'],
              'roles' => ['super-admin'],
              'permissions' => ['parliament-constituency-index'],
              'icon' => 'parlaiment_unselected.png',
              'active_icon' => 'parlaiment_selected.png'
            ],
             [
              'label' => 'Assembly Constituency',
              'url' => ['assembly-constituency/'],
              'roles' => ['super-admin'],
              'permissions' => ['assembly-constituency-index'],
              'icon' => 'assembly_unselected.png',
              'active_icon' => 'assembly_selected.png'
            ],
            [
              'label' => 'lsgi Type',
              'url' => ['lsgi-type/'],
              'roles' => ['super-admin','admin-lsgi'],
              'permissions'=>['lsgi-type-index'],
              'icon' => 'lsgi_unselected.png',
              'active_icon' => 'lsgi_selected.png'
            ],
            [
              'label' => 'Lsgi Block ',
              'url' => ['lsgi-blocks/'],
              'roles' => ['super-admin','admin-lsgi'],
              'permissions' => ['lsgi-blocks-index'],
              'icon' => 'lsgi_unselected.png',
              'active_icon' => 'lsgi_selected.png'
            ],
            [
              'label' => 'Lsgi ',
              'url' => ['lsgis/'],
              'roles' => ['super-admin','admin-lsgi'],
              'permissions' => ['lsgis-index'],
              'icon' => 'lsgi_unselected.png',
              'active_icon' => 'lsgi_selected.png'
            ],
            [
              'label' => 'Ward ',
              'url' => ['wards/'],
              'roles' => ['super-admin','admin-lsgi'],
              'permissions' => ['wards-index'],
              'icon' => 'ward_unselected.png',
              'active_icon' => 'ward_selected.png'
            ],
            [
              'label' => 'Residence Categories ',
              'url' => ['residence-categories/index'],
              'roles' => ['super-admin','admin-lsgi'],
              'permissions' => ['residence-categories-index'],
              'icon' => 'residence_categories_unselected.png',
              'active_icon' => 'residence_categories_selected.png'
            ],
            [
              'label' => 'Waste Category ',
              'url' => ['waste-categories/'],
              'roles' => ['super-admin','admin-lsgi'],
              'permissions' => ['waste-categories-index'],
              'icon' => 'waste_categories_unselected.png',
              'active_icon' => 'waste_categories_selected.png'
            ],
            // [
            //   'label' => 'Residence Categories ',
            //   'url' => ['residence-categories/index'],
            //   'roles' => ['super-admin','admin-lsgi'],
            //   'permissions' => ['residence-categories-index'],
            //   'icon' => 'fa fa-dashboard'
            // ],
           [
              'label' => 'Haritha Karma Sena ',
              'url' => ['green-action-units/'],
              'roles' => ['super-admin','admin-lsgi'],
              'permissions' => ['green-action-units-index'],
              'icon' => 'haritha_karma_sena_unselected.png',
              'active_icon' => 'haritha_karma_sena_selected.png',
            ],
            [
              'label' => 'Public Gathering Methods ',
              'url' => ['public-gathering-methods/'],
              'roles' => ['super-admin','admin-lsgi'],
              'permissions' => ['public-gathering-methods-index'],
              'icon' => 'public_gathering_unselected.png',
              'active_icon' => 'public_gathering_selected.png'
            ],
           [
              'label' => 'Waste Collection Method ',
              'url' => ['waste-collection-methods/'],
              'roles' => ['super-admin','admin-lsgi'],
              'permissions' => ['waste-collection-methods-index'],
              'icon' => 'waste_collection_unselected.png',
              'active_icon' => 'waste_collection_selected.png'
            ],
            [
              'label' => 'Building Type ',
              'url' => ['building-types/'],
              'roles' => ['super-admin','admin-lsgi'],
              'permissions' => ['building-types-index'],
              'icon' => 'building_type_unselected.png',
              'active_icon' => 'building_type_selected.png'
            ],
            [
              'label' => 'Trading Type ',
              'url' => ['trading-types/'],
              'roles' => ['super-admin','admin-lsgi'],
              'permissions' => ['trading-types-index'],
              'icon' => 'trading_type_unselected.png',
              'active_icon' => 'trading_type_selected.png'
            ],
            [
              'label' => 'Shop Type ',
              'url' => ['shop-types/'],
              'roles' => ['super-admin','admin-lsgi'],
              'permissions' => ['shop-types-index'],
              'icon' => 'shop_unselected.png',
              'active_icon' => 'shop_selected.png'
            ],
            [
              'label' => 'Fee Collection interval ',
              'url' => ['fee-collection-interval/'],
              'roles' => ['super-admin','admin-lsgi'],
              'permissions' => ['fee-collection-interval-index'],
              'icon' => 'fee_collection_unselected.png',
              'active_icon' => 'fee_collection_selected.png'
            ],
            [
              'label' => 'Terrace Farming Help Type ',
              'url' => ['terrace-farming-help-types/'],
              'roles' => ['super-admin','admin-lsgi'],
              'permissions' => ['terrace-farming-help-types-index'],
              'icon' => 'terrace_farming_unselected.png',
              'active_icon' => 'terrace_farming_selected.png'
            ],
            [
              'label' => 'Services ',
              'url' => ['services/'],
              'roles' => ['super-admin','admin-lsgi'],
              'permissions'=>['services-index'],
              'icon' => 'service_unselected.png',
                'active_icon' => 'service_selected.png'
            ],
            [
              'label' => 'Bundled Services ',
              'url' => ['bundled-services/'],
              'roles' => ['super-admin','admin-lsgi'],
              'permissions' => ['bundled-services-index'],
              'icon' => 'bundled_unselected.png',
              'active_icon' => 'bundled_selected.png'
            ],
            [
              'label' => 'Complaints  ',
              'url' => ['complaints/index'],
              'roles' => ['super-admin','admin-lsgi'],
              'permissions' => ['complaints-index'],
              'icon' => 'complaints_unselected.png',
              'active_icon' => 'complaints_selected.png'
            ],
            [
              'label' => 'Adminstration Types ',
              'url' => ['administration-type/index'],
              'roles' => ['super-admin','admin-lsgi'],
              'permissions' => ['administration-type-index'],
              'icon' => 'administrator-type_unselected.png',
              'active_icon' => 'administrator-type_selected.png'
            ],
            [
              'label' => 'Dumping Event types',
              'url' => ['dumping-event-types/index'],
               'roles' => ['super-admin','admin-lsgi'],
               'permissions' => ['dumping-event-types-index'],
              'icon' => 'dumping_event_type_unselected.png',
              'active_icon' => 'dumping_event_type.png'
            ],
            [
              'label' => 'Labels and Translations',
              'url' => ['labels/index'],
               'roles' => ['super-admin','admin-lsgi'],
               'permissions' => ['labels-index'],
              'icon' => 'labels_and_translations_unselected.png',
              'active_icon' => 'labels_and_translations.png'
            ],
            
            ]
            ],
            [
              'label' => 'Camera Surveillance',
              'icon' => 'camera_unselected.png',
              'active_icon' => 'camera_selected.png',
              'roles' => ['super-admin','admin-lsgi','admin-hks','supervisor','camera-monitoring-admin'],
              'permissions' => [
                'dashboard-index'
                // ,'camera-index','camera-qr-code-index','incidents-index',
                // 'monitoring-groups-index','incident-reports-index','memo-payment-reports-index',
                // 'payment-counter-reports-index','outstanding-payment-reports-index',
                // 'monitoring-group-users-index','memos-index','memo-types-index','memo-penalties-index',
                // 'lsgi-authorized-signatories-index','payment-counter'
              ],
              'children' => [
                [
                  'label' => 'Dashboard',
                  'url' => ['dashboard/'],
                  'roles' => ['super-admin','admin-lsgi','admin-hks','supervisor','camera-monitoring-admin'],
                  'permissions' => ['dashboard-index'],
                  'icon' => 'camera_dashboard_unselected.png',
                  'active_icon' => 'camera_dashboard_selected.png'
                ],
                [
                  'label' => 'Camera List',
                  'url' => ['camera/index'],
                  'roles' => ['super-admin','admin-lsgi','camera-monitoring-admin'],
                  'permissions' => ['camera-index'],
                  'icon' => 'camera_list_unselected.png',
                  'active_icon' => 'camera_list_selected.png'
                ],
                [
                  'label' => 'Camera Service Requests',
                  'url' => ['camera-service-request/index'],
                  'roles' => ['super-admin','admin-lsgi','camera-monitoring-admin'],
                  'permissions' => ['camera-service-request-index'],
                  'icon' => 'camera_list_unselected.png',
                  'active_icon' => 'camera_list_selected.png'
                ],
            //     [
            //   'label' => 'Assign Supervisor ',
            //   'url' => ['account-supervisor/index'],
            //   'roles' => ['super-admin','admin-lsgi','admin-hks'],
            //   'icon' => 'fa fa-dashboard'
            // ],
            //     [
            //   'label' => 'Assign Gt ',
            //   'url' => ['account-gt/index'],
            //   'roles' => ['super-admin','admin-lsgi','admin-hks','supervisor'],
            //   'icon' => 'fa fa-dashboard'
            // ],
                [
                  'label' => 'QR Code ',
                  'url' => ['camera-qr-code/'],
                  'roles' => ['super-admin','admin-lsgi','camera-monitoring-admin'],
                  'permissions' => ['camera-qr-code-index'],
                  'icon' => 'qr_code_unselected.png',
                  'active_icon' => 'qr_code_selected.png',
                ],
                [
                  'label' => 'Incident List',
                  'url' => ['incidents/index'],
                  'roles' => ['super-admin','admin-lsgi','camera-monitoring-admin'],
                  'permissions' => ['incidents-index'],
                  'icon' => 'incident_list_unselected.png',
                  'active_icon' => 'incident_list_selected.png'
                ],
                [
                  'label' => 'Monitoring Groups',
                  'url' => ['monitoring-groups/'],
                  'roles' => ['super-admin','admin-lsgi','camera-monitoring-admin'],
                  'permissions' => ['monitoring-groups-index'],
                  'icon' => 'monitoring_group_unselected.png',
                  'active_icon' => 'monitoring_group_selected.png'
                ],
                 [
                  'label' => 'Reports',
                  'roles' => ['super-admin','admin-lsgi','camera-monitoring-admin'],
                  'permissions' => [
                    'incident-reports-index','memo-payment-reports-index','payment-counter-reports-index',
                    'outstanding-payment-reports-index'
                  ],
                  'icon' => 'camera-reports_unselected.png',
                  'active_icon' => 'camera-reports_selected.png',
                    'children' => [
                      [
                        'label' => 'Incident Reports',
                        'url' => ['incident-reports/index'],
                        'roles' => ['super-admin','admin-lsgi','camera-monitoring-admin'],
                        'permissions' => ['incident-reports-index'],
                        'icon' => 'incident_list_unselected.png',
                        'active_icon' => 'incident_list_selected.png'
                      ],
                      [
                        'label' => 'Memo Payment Reports',
                        'url' => ['memo-payment-reports/index'],
                        'roles' => ['super-admin','admin-lsgi','camera-monitoring-admin'],
                        'permissions' => ['memo-payment-reports-index'],
                        'icon' => 'memo_payment_collected_unselected.png',
                        'active_icon' => 'memo_payment_collected_selected.png'
                      ],
                      [
                        'label' => 'Counterwise Reports',
                        'url' => ['payment-counter-reports/index'],
                        'roles' => ['super-admin','admin-lsgi','camera-monitoring-admin'],
                        'permissions' => ['payment-counter-reports-index'],
                        'icon' => 'counterwise_unselected.png',
                        'active_icon' => 'counterwise_selected.png'
                      ],
                      [
                        'label' => 'Outstanding Payment Reports',
                        'url' => ['outstanding-payment-reports/index'],
                        'roles' => ['super-admin','admin-lsgi','camera-monitoring-admin'],
                        'permissions' => ['outstanding-payment-reports-index'],
                        'icon' => 'outstanding_payments_unselected.png',
                        'active_icon' => 'outstanding_payments_selected.png'
                      ],
                    ],
                ],
                [
                  'label' => 'Users',
                  'url' => ['monitoring-group-users/'],
                  'roles' => ['super-admin','admin-lsgi','camera-monitoring-admin'],
                  'permissions' => ['monitoring-group-users-index'],
                  'icon' => 'user_unselected.png',
                  'active_icon' => 'user_selected.png'
                ],
                [
                  'label' => 'Memos',
                  'url' => ['memos/index'],
                  'roles' => ['super-admin','admin-lsgi','camera-monitoring-admin'],
                  'permissions' => ['memos-index'],
                  'icon' => 'memos_unselected.png',
                  'active_icon' => 'memos_selected.png'
                ],
                [
                  'label' => 'Memo Type',
                  'url' => ['memo-types/'],
                  'roles' => ['super-admin','admin-lsgi','camera-monitoring-admin'],
                  'permissions' => ['memo-types-index'],
                  'icon' => 'memos_unselected.png',
                  'active_icon' => 'memos_selected.png'
                ],
                [
                  'label' => 'Memo Penalty',
                  'url' => ['memo-penalties/'],
                  'roles' => ['super-admin','admin-lsgi','camera-monitoring-admin'],
                  'permissions' => ['memo-penalties-index'],
                  'icon' => 'memose_penalty_unselected.png',
                  'active_icon' => 'memose_penalty_selected.png'
                ],
                [
                  'label' => 'Lsgi Authorized Signatory',
                  'url' => ['lsgi-authorized-signatories/'],
                  'roles' => ['super-admin','admin-lsgi','camera-monitoring-admin'],
                  'permissions' => ['lsgi-authorized-signatories-index'],
                  'icon' => 'lsgi_authorized_unselected.png',
                  'active_icon' => 'lsgi_authorized_selected.png'
                ],
                [
                  'label' => 'Payment Counter',
                  'url' => ['payment-counter/'],
                  'roles' => ['super-admin','admin-lsgi','camera-monitoring-admin'],
                  'permissions' => ['payment-counter-index'],
                  'icon' => 'payment_counter_unselected.png',
                  'active_icon' => 'payment_counter_selected.png'
                ],
                [
                  'label' => 'Camera Service',
                  'url' => ['camera-service/index'],
                  'roles' => ['super-admin','admin-lsgi','camera-monitoring-admin'],
                  'permissions' => ['camera-service-index'],
                  'icon' => 'camera_service_unselected.png',
                  'active_icon' => 'camera_service_selected.png'
                ],
                // [
                //   'label' => 'Assign Counter Admin',
                //   'url' => ['payment-counter/assign-counter-admin'],
                //   'roles' => ['super-admin','admin-lsgi','camera-monitoring-admin'],
                //   'permissions' => ['payment-counter-assign-counter-admin'],
                //   'icon' => 'fa fa-dashboard'
                // ],
              ]
            ],
            [
              'label' => 'Residential association',
              'url' => ['residential-association/index'],
              'roles' => ['super-admin','admin-lsgi','camera-monitoring-admin'],
              'permissions' => ['residential-association-index'],
              'icon' => 'residential_association_unselected.png',
              'active_icon' => 'residential_association_selected.png'
            ],
             [
              'label' => 'Change Residential association',
              'url' => ['residential-association/change-association'],
              'roles' => ['super-admin','admin-lsgi'],
              'permissions' => ['residential-association-change-association'],
              'icon' => 'change_residential_unselected.png',
              'active_icon' => 'change_residential_selected.png'
            ],
            [
              'label' => 'Association type',
              'url' => ['association-type/'],
              'roles' => ['super-admin','admin-lsgi','camera-monitoring-admin'],
              'permissions' => ['association-type-index'],
              'icon' => 'association_type_unselected.png',
              'active_icon' => 'association_type_selected.png'
            ],
            [
              'label' => 'Installation checklist',
              'url' => ['installation-checklist/'],
              'roles' => ['super-admin','admin-lsgi','camera-monitoring-admin'],
              'permissions' => ['installation-checklist-index'],
               'icon' => 'Installer_checklist_payments_unselected.png',
              'active_icon' => 'Installer_checklist_payments_unselected.png'
            ],
            [
              'label' => 'Access controller',
              'roles' => ['super-admin'],
              'permissions' => ['rbac-controllers-index','rbac-roles-index','rbac-users-index'],
              'icon' => 'access_controller_unselected.png',
              'active_icon' => 'access_controller_selected.png',
              'children' => [
                [
                  'label' => 'Controllers',
                  'url' => ['rbac/controllers-index'],
                  'permissions' => ['rbac-controllers-index'],
                  'roles' => ['super-admin'],
                  'icon' => 'controller_unselected.png',
                  'active_icon' => 'controller_selected.png'
                ],
                [
                  'label' => 'Roles',
                  'url' => ['rbac/roles-index'],
                  'roles' => ['super-admin'],
                  'permissions' => ['rbac-roles-index'],
                  'icon' => 'roles_unselected.png',
                  'active_icon' => 'roles_selected.png'
                ],
                [
                  'label' => 'Users',
                  'url' => ['rbac/users-index'],
                  'roles' => ['super-admin'],
                  'permissions' => ['rbac-users-index'],
                  'icon' => 'access_controller_user_unselected.png',
                  'active_icon' => 'access_controller_user_selected.png'
                ],
              ],
            ],
            [
              'label' => 'Logout',
              'url' => ['account/logout'],
              'roles' => ['super-admin','admin-lsgi','admin-hks','coordinator','supervisor','surveyor','camera-monitoring-admin'],
              'permissions' => ['Account-logout'],
              'icon' => 'logout_unselected.png',
              'active_icon' => 'logout_selected.png'
            ],
          ];

          $linksWastemanagement = [
          [
                  'label' => 'Dashboard',
                  'url' => ['wms-dashboard/'],
                  'roles' => ['super-admin','admin-lsgi','admin-hks','coordinator','supervisor'],
                  'permissions' => ['wms-dashboard-index'],
                  'icon' => 'dashboard_unselected.png',
                  'active_icon' => 'dashboard_selected.png'
                ],
          [

              'label' => 'Users',
              'icon' =>'user_unselected.png',
              'active_icon' =>'user_selected.png',
               'roles' => ['super-admin','admin-lsgi','coordinator','admin-hks','supervisor'],
               'permissions' => [
                 'Account-super-admin','Account-index','Account-admin-lsgi','Account-admin-hks',
                 'Account-supervisors','Account-green-technicians','Account-coordinators','user-list'
               ],
              'children' => $rbacChildrenList
              // [
            //    [
            //   'label' => 'Super Admin',
            //   'url' => ['account/super-admin'],
            //    'roles' => ['super-admin'],
            //    'permissions' => ['Account-super-admin'],
            //   'icon' => 'fa fa-dashboard'
            // ],
            //     [
            //   'label' => 'Surveyor',
            //   'url' => ['account/index'],
            //    'roles' => ['super-admin','admin-lsgi','coordinator'],
            //    'permissions' => ['Account-index'],
            //   'icon' => 'fa fa-dashboard'
            // ],
            //     [
            //   'label' => 'Lsgi Admins',
            //   'url' => ['account/admin-lsgi'],
            //    'roles' => ['super-admin'],
            //    'permissions' => ['Account-admin-lsgi'],
            //   'icon' => 'fa fa-dashboard'
            // ],
            // [
            //   'label' => 'Hks Admins ',
            //   'url' => ['account/admin-hks'],
            //    'roles' => ['super-admin','admin-lsgi'],
            //    'permissions' => ['Account-admin-hks'],
            //   'icon' => 'fa fa-dashboard'
            // ],
            // [
            //   'label' => 'Supervisors ',
            //   'url' => ['account/supervisors'],
            //    'roles' => ['super-admin','admin-lsgi','admin-hks'],
            //    'permissions' => ['Account-supervisors'],
            //   'icon' => 'fa fa-dashboard'
            // ],
            // [
            //   'label' => 'Green Technicians ',
            //   'url' => ['account/green-technicians'],
            //    'roles' => ['super-admin','admin-lsgi','admin-hks','supervisor'],
            //    'permissions' => ['Account-green-technicians'],
            //   'icon' => 'fa fa-dashboard'
            // ],
            // [
            //   'label' => 'Survey Coordinator ',
            //   'url' => ['account/coordinators'],
            //   'roles' => ['super-admin','admin-lsgi'],
            //   'permissions' => ['Account-coordinators'],
            //   'icon' => 'fa fa-dashboard'
            // ],
            //   ]
            ],
            [
              'label' => 'Survey Agencies ',
              'url' => ['survey-agencies/index'],
              'roles' => ['super-admin','admin-lsgi'],
              'permissions' => [
                'survey-agencies-index'
            ],
              'icon' => 'survey_agencies_unselected.png',
              'active_icon' => 'survey_agency_selected.png'
            ],
            [
              'label' => 'Assign Supervisor ',
              'url' => ['account-supervisor/index'],
              'roles' => ['super-admin','admin-lsgi','admin-hks'],
              'permissions' => [
                'account-supervisor-index'
              ],
              'icon' => 'supervisor_unselected.png',
              'active_icon' => 'supervisor_selected.png'
            ],
            [
              'label' => 'Assign Gt ',
              'url' => ['account-gt/index'],
              'roles' => ['super-admin','admin-lsgi','admin-hks','supervisor'],
              'permissions' => [
                'account-gt-index'
              ],
              'icon' => 'gt_unselected.png',
              'active_icon' => 'gt_selected.png'
            ],
            [
              'label' => 'Schedule ',
              'url' => ['schedule/index'],
              'roles' => ['supervisor'],
              'permissions' => ['schedule-index'],
              'icon' => 'schedule_unselected.png',
              'active_icon' => 'schedule_selected.png'
            ],
            [
              'label' => 'QR Code ',
              'url' => ['qr-code/'],
              'roles' => ['super-admin','admin-lsgi'],
              'permissions' => ['qr-code-index'],
              'icon' => 'qr_code_unselected.png',
              'active_icon' => 'qr_code_selected.png'
            ],
            [
              'label' => 'Customers  ',
              'url' => ['customers/index'],
              'roles' => ['super-admin','admin-lsgi','admin-hks','supervisor','coordinator'],
              'permissions' => ['Customers-index'],
              'icon' => 'customer_unselected.png',
              'active_icon' => 'customer_selected.png'
            ],
            [
              'label' => 'Subscriptions  ',
              'url' => ['account-service-requests/index'],
               'roles' => ['super-admin','admin-lsgi','admin-hks','supervisor'],
               'permissions' => ['account-service-requests-index'],
              'icon' => 'Subscription_unselected.png',
              'active_icon' => 'Subscription_selected.png'
            ],
            // [
            //   'label' => 'Deactivation Requests  ',
            //   'url' => ['deactivation-requests/index'],
            //   'icon' => 'fa fa-dashboard'
            // ],
            // [
            //   'label' => 'Fee collected  ',
            //   'url' => ['account-fee/index'],
            //   'icon' => 'fa fa-dashboard'
            // ],
            [
              'label' => 'Payment Requests  ',
              'url' => ['payment-requests/index'],
               'roles' => ['super-admin','admin-lsgi','admin-hks','supervisor'],
               'icon' => 'payment_request_unselected.png',
               'active_icon' => 'payment_request_selected.png'
            ],
            [
              'label' => 'Payments  ',
              'url' => ['payments/index'],
               'roles' => ['super-admin','admin-lsgi','admin-hks','supervisor'],
               'permissions' => ['payments-index'],
              'icon' => 'payment_unselected.png',
              'active_icon' => 'payments_selected.png'
            ],
            [
              'label' => 'Service Requests  ',
              'url' => ['service-requests/index'],
               'roles' => ['super-admin','admin-lsgi','admin-hks','supervisor'],
               'permissions' => ['service-requests-index'],
              'icon' => 'service_request_unselected.png',
              'active_icon' => 'service_request_selected.png'
            ],
            [
              'label' => 'Special Service Requests  ',
              'url' => ['service-requests/special-services'],
               'roles' => ['super-admin','admin-lsgi','admin-hks','supervisor'],
               'permissions' => ['service-requests-special-services'],
             'icon' => 'special_service_request.png',
              'active_icon' => 'special_service_request_selected.png'
            ],
            [
              'label' => 'Complaints  ',
              'url' => ['requests/complaints'],
               'roles' => ['super-admin','admin-lsgi','admin-hks','supervisor'],
               'permissions' => ['requests-complaints'],
              'icon' => 'complaints_unselected.png',
              'active_icon' => 'complaints_selected.png'
            ],
            [
              'label' => 'Nearest Mrc',
              'url' => ['mrc/index'],
               'roles' => ['super-admin','admin-lsgi'],
               'permissions' => ['Mrc-index'],
              'icon' => 'mrf_unselected.png',
              'active_icon' => 'mrf.png',
            ],
            [
              'label' => 'Dumping Events',
              'url' => ['dumping-events/index'],
               'roles' => ['super-admin','admin-lsgi'],
               'permissions' => ['dumping-events-index'],
              'icon' => 'dumping_event_unselected.png',
              'active_icon' => 'dumping_event.png',
            ],
            [
              'label' => 'Escalated Service Requests',
              'url' => ['escalations/service'],
               'roles' => ['super-admin','admin-lsgi'],
               'permissions' => ['escalations-service'],
               'icon' => 'escalated_service_request_unselected.png',
              'active_icon' => 'escalator_service_request.png',
            ],
             [
              'label' => 'Escalated Complaints',
              'url' => ['escalations/complaints'],
               'roles' => ['super-admin','admin-lsgi','coordinator'],
               'permissions' => ['escalations-complaints'],
              'icon' => 'escalator_complaint_unselected.png',
              'active_icon' => 'escalator_complaint.png',
              ],
             [
              'label' => 'CMS',
              // 'url' => ['reports/collection'],
               'roles' => ['super-admin','admin-lsgi','coordinator'],
               'permissions' => ['posts-index'],
              'icon' => 'cms_unselected.png',
               'active_icon' => 'cms.png',
               'children' =>  $contentChildrenList
            ],
            [
              'label' => 'Enquiries',
              'url' => ['enquiries/index'],
               'roles' => ['super-admin','admin-lsgi','coordinator'],
               'permissions' => ['enquiries-index'],
              'icon' => 'enquiry_unselected.png',
              'active_icon' => 'enquiry.png',
              ],
            [
              'label' => 'Reports',
              'icon' => 'reports_unselected.png',
              'active_icon' => 'reports_selected.png',
               'roles' => ['super-admin','admin-lsgi','admin-hks','coordinator'],
               'permissions' => [
                 'reports-door-close','reports-survey-count','reports-waste-management-type',
                 'reports-ward-wise-count','reports-collection'
               ],
              'children' => [
               [
              'label' => 'Door Close Report',
              'url' => ['reports/door-close'],
               'roles' => ['super-admin','admin-lsgi','coordinator'],
               'permissions' => ['reports-door-close'],
              'icon' => 'Closed_door_unselected.png',
              'active_icon' => 'Closed_door_selected.png'
            ],
                [
              'label' => 'Survey Count Report',
              'url' => ['reports/survey-count'],
               'roles' => ['super-admin','admin-lsgi','coordinator'],
               'permissions' => ['reports-survey-count'],
              'icon' => 'Survey_count_report_unselected.png',
              'active_icon' => 'survey_count_report_selected.png'
            ],
            [
              'label' => 'Waste Management Type Report',
              'url' => ['reports/waste-management-type'],
               'roles' => ['super-admin','admin-lsgi','admin-hks','supervisor'],
               'permissions' => ['reports-waste-management-type'],
              'icon' => 'waste_management_report_unselected.png',
              'active_icon' => 'waste_management_report_selected.png'
            ],
            [
              'label' => 'Ward Wise Count Report',
              'url' => ['reports/ward-wise-count'],
               'roles' => ['super-admin','admin-lsgi','coordinator'],
               'permissions' => ['reports-ward-wise-count'],
              'icon' => 'Ward_wise_Report_unselected.png',
              'active_icon' => 'ward_wise_report_selected.png'
            ],
            [
              'label' => 'Collection Report',
              // 'url' => ['reports/collection'],
               'roles' => ['super-admin','admin-lsgi','coordinator'],
               'permissions' => ['reports-collection'],
              'icon' => 'collection Report_unselected.png',
               'active_icon' => 'collection Report_selected.png',
               'children' => [
                [
              'label' => 'Detailed Report',
              'url' => ['reports/collection'],
               'roles' => ['super-admin','admin-lsgi','coordinator'],
               'permissions' => ['reports-collection'],
              'icon' => 'Detailed Report_unselected.png',
               'active_icon' => 'Detailed Report_selected.png'
              ],
              [
              'label' => 'Summary Report',
              'url' => ['reports/collection-summary'],
               'roles' => ['super-admin','admin-lsgi','coordinator'],
               'permissions' => ['reports-collection-summary'],
              'icon' => 'Summary Report_unselected.png',
               'active_icon' => 'Summary Report_selected.png'
              ],
               ],
            ],

            [
              'label' => 'Subscription Report',
              // 'url' => ['reports/collection'],
               'roles' => ['super-admin','admin-lsgi','coordinator'],
               'permissions' => ['reports-subscriptions'],
              'icon' => 'Subscription Report_unselected.png',
              'active_icon' => 'Subscription Report_selected.png',
               'children' => [
                [
              'label' => 'Detailed Report',
              'url' => ['reports/subscriptions'],
               'roles' => ['super-admin','admin-lsgi','coordinator'],
               'permissions' => ['reports-subscriptions'],
              'icon' => 'Detailed Report_unselected.png',
               'active_icon' => 'Detailed Report_selected.png'
              ],
              [
              'label' => 'Summary Report',
              'url' => ['reports/subscription-summary'],
               'roles' => ['super-admin','admin-lsgi','coordinator'],
               'permissions' => ['reports-subscription-summary'],
              'icon' => 'Summary Report_unselected.png',
               'active_icon' => 'Summary Report_selected.png'
              ],
               ],
            ],
            [
              'label' => 'Service Account Disabled Report',
              // 'url' => ['reports/collection'],
               'roles' => ['super-admin','admin-lsgi','coordinator'],
               'permissions' => ['reports-subscription-disabled'],
              'icon' => 'Service Account  Report_unselected.png',
               'active_icon' => 'Service Account  Report_selected.png',
               'children' => [
                [
              'label' => 'Detailed Report',
              'url' => ['reports/subscription-disabled'],
               'roles' => ['super-admin','admin-lsgi','coordinator'],
               'permissions' => ['reports-subscription-disabled'],
              'icon' => 'Detailed Report_unselected.png',
               'active_icon' => 'Detailed Report_selected.png'
              ],
              [
              'label' => 'Summary Report',
              'url' => ['reports/subscription-disabled-summary'],
               'roles' => ['super-admin','admin-lsgi','coordinator'],
               'permissions' => ['reports-subscription-disabled-summary'],
              'icon' => 'Summary Report_unselected.png',
               'active_icon' => 'Summary Report_selected.png'
              ],
               ],
            ],

            [
              'label' => 'Complaints Report',
              // 'url' => ['reports/collection'],
               'roles' => ['super-admin','admin-lsgi','coordinator'],
               'permissions' => ['reports-complaints'],
              'icon' => 'complaint_reports_unselected.png',
              'active_icon' => 'complaint_reports_selected.png',
               'children' => [
                [
              'label' => 'Detailed Report',
              'url' => ['reports/complaints'],
               'roles' => ['super-admin','admin-lsgi','coordinator'],
               'permissions' => ['reports-complaints'],
              'icon' => 'Detailed Report_unselected.png',
               'active_icon' => 'Detailed Report_selected.png'
              ],
              [
              'label' => 'Summary Report',
              'url' => ['reports/complaints-summary'],
               'roles' => ['super-admin','admin-lsgi','coordinator'],
               'permissions' => ['reports-complaints-summary'],
              'icon' => 'Summary Report_unselected.png',
               'active_icon' => 'Summary Report_selected.png'
              ],
               ],
            ],
            [
              'label' => 'Complaints Resolution Report',
              // 'url' => ['reports/collection'],
               'roles' => ['super-admin','admin-lsgi','coordinator'],
               'permissions' => ['reports-complaint-resolution'],
              'icon' => 'complaint_resolution_unselected.png',
              'active_icon' => 'complaint_resolution_selected.png',
               'children' => [
                [
              'label' => 'Detailed Report',
              'url' => ['reports/complaint-resolution'],
               'roles' => ['super-admin','admin-lsgi','coordinator'],
               'permissions' => ['reports-complaint-resolution'],
              'icon' => 'Detailed Report_unselected.png',
               'active_icon' => 'Detailed Report_selected.png'
              ],
              [
              'label' => 'Summary Report',
              'url' => ['reports/complaint-resolution-summary'],
               'roles' => ['super-admin','admin-lsgi','coordinator'],
               'permissions' => ['reports-complaint-resolution-summary'],
              'icon' => 'Summary Report_unselected.png',
               'active_icon' => 'Summary Report_selected.png'
              ],
               ],
            ],
            [
              'label' => 'Service Completion Report',
              // 'url' => ['reports/collection'],
               'roles' => ['super-admin','admin-lsgi','coordinator'],
               'permissions' => ['reports-service-completion'],
              'icon' => 'Service_completion_unselected.png',
               'active_icon' => 'Service_completion_selected.png',
               'children' => [
                [
              'label' => 'Detailed Report',
              'url' => ['reports/service-completion'],
               'roles' => ['super-admin','admin-lsgi','coordinator'],
               'permissions' => ['reports-service-completion'],
              'icon' => 'Detailed Report_unselected.png',
               'active_icon' => 'Detailed Report_selected.png'
              ],
              [
              'label' => 'Summary Report',
              'url' => ['reports/service-completion-summary'],
               'roles' => ['super-admin','admin-lsgi','coordinator'],
               'permissions' => ['reports-service-completion-summary'],
              'icon' => 'Summary Report_unselected.png',
               'active_icon' => 'Summary Report_selected.png'
              ],
               ],
            ],
            [
              'label' => 'Service Pending Report',
              // 'url' => ['reports/collection'],
               'roles' => ['super-admin','admin-lsgi','coordinator'],
               'permissions' => ['reports-service-pending'],
              'icon' => 'service_pending_unselected.png',
               'active_icon' => 'service_pending_selected.png',
               'children' => [
                [
              'label' => 'Detailed Report',
              'url' => ['reports/service-pending'],
               'roles' => ['super-admin','admin-lsgi','coordinator'],
               'permissions' => ['reports-service-pending'],
              'icon' => 'Detailed Report_unselected.png',
               'active_icon' => 'Detailed Report_selected.png'
              ],
              [
              'label' => 'Summary Report',
              'url' => ['reports/service-pending-summary'],
               'roles' => ['super-admin','admin-lsgi','coordinator'],
               'permissions' => ['reports-service-pending-summary'],
             'icon' => 'Summary Report_unselected.png',
               'active_icon' => 'Summary Report_selected.png'
              ],
               ],
            ],
            [
              'label' => 'Customer Plan Report',
              // 'url' => ['reports/collection'],
               'roles' => ['super-admin','admin-lsgi','coordinator'],
               'permissions' => ['reports-customer-plan'],
              'icon' => 'customer_plan_unselected.png',
              'active_icon' => 'customer_plan_selected.png',
               'children' => [
                [
              'label' => 'Detailed Report',
              'url' => ['reports/customer-plan'],
               'roles' => ['super-admin','admin-lsgi','coordinator'],
               'permissions' => ['reports-customer-plan'],
              'icon' => 'Detailed Report_unselected.png',
              'active_icon' => 'Detailed Report_selected.png'
              ],
              [
              'label' => 'Summary Report',
              'url' => ['reports/customer-plan-summary'],
               'roles' => ['super-admin','admin-lsgi','coordinator'],
               'permissions' => ['reports-customer-plan-summary'],
              'icon' => 'Summary Report_unselected.png',
              'active_icon' => 'Summary Report_selected.png'
              ],
               ],
            ],
            [
              'label' => 'Itemwise Service Completion Report',
              'url' => ['reports/itemwise-service-completion'],
               'roles' => ['super-admin','admin-lsgi','coordinator'],
               'permissions' => ['reports-itemwise-service-completion'],
              'icon' => 'Itemwis_service_completion_unselected.png',
              'active_icon' => 'Itemwis_service_completion_selected.png'
            ],
            [
              'label' => 'Itemwise Service Pending Report',
              'url' => ['reports/itemwise-service-pending'],
               'roles' => ['super-admin','admin-lsgi','coordinator'],
               'permissions' => ['reports-itemwise-service-pending'],
              'icon' => 'Itemwis_service_pending_unselected.png',
              'active_icon' => 'Itemwis_service_pending_selected.png'
            ],
            [
              'label' => 'Waste Quality Collected Report',
              'url' => ['reports/waste-quality-collected'],
               'roles' => ['super-admin','admin-lsgi','coordinator'],
               'permissions' => ['reports-waste-quality-collected'],
              'icon' => 'Waste_quality_collected_unselected.png',
              'active_icon' => 'Waste_quality_collected_selected.png'
            ],
            [
              'label' => 'Waste Quantity Collected Report',
              'url' => ['reports/waste-quantity-collected'],
               'roles' => ['super-admin','admin-lsgi','coordinator'],
               'permissions' => ['reports-waste-quantity-collected'],
              'icon' => 'Waste_Quantity_Collected_unselected.png',
              'active_icon' => 'Waste_Quantity_Collected_selected.png'
            ],
            
              ]
            ],
           [
              'label' => 'Preliminary Configuration',
              'roles' => ['super-admin','admin-lsgi'],
              'permissions' => [
                'states-index','districts-index','parliament-constituency-index',
                'assembly-constituency-index','lsgi-type-index','lsgi-blocks-index',
                'lsgis-index','wards-index','residence-categories-index',
                'waste-categories-index','residence-categories-index',
                'green-action-units-index','public-gathering-methods-index',
                'waste-collection-methods-index','building-types-index',
                'trading-types-index','shop-types-index','fee-collection-interval-index',
                'terrace-farming-help-types-index','services-index','bundled-services-index',
                'complaints-index','administration-type-index',
              ],
              'icon' => 'configuration_unselected.png',
              'active_icon' => 'configuration_selected.png',
              'children' => [
                [
              'label' => 'State',
              'url' => ['states/'],
              'roles' => ['super-admin'],
              'permissions' => ['states-index'],
               'icon' => 'state-unselected.png',
              'active_icon' => 'state-selected.png'
            ],
            [
              'label' => 'District',
              'url' => ['districts/'],
              'roles' => ['super-admin'],
              'permissions' => ['districts-index'],
              'icon' => 'district_unselected.png',
              'active_icon' => 'district_selected.png'
            ],
            [
              'label' => 'Parliament Constituency',
              'url' => ['parliament-constituency/'],
              'roles' => ['super-admin'],
              'permissions' => ['parliament-constituency-index'],
              'icon' => 'parlaiment_unselected.png',
              'active_icon' => 'parlaiment_selected.png'
            ],
             [
              'label' => 'Assembly Constituency',
              'url' => ['assembly-constituency/'],
              'roles' => ['super-admin'],
              'permissions' => ['assembly-constituency-index'],
              'icon' => 'assembly_unselected.png',
              'active_icon' => 'assembly_selected.png'
            ],
            [
              'label' => 'lsgi Type',
              'url' => ['lsgi-type/'],
              'roles' => ['super-admin','admin-lsgi'],
              'permissions'=>['lsgi-type-index'],
              'icon' => 'lsgi_unselected.png',
              'active_icon' => 'lsgi_selected.png'
            ],
            [
              'label' => 'Lsgi Block ',
              'url' => ['lsgi-blocks/'],
              'roles' => ['super-admin','admin-lsgi'],
              'permissions' => ['lsgi-blocks-index'],
              'icon' => 'lsgi_unselected.png',
              'active_icon' => 'lsgi_selected.png'
            ],
            [
              'label' => 'Lsgi ',
              'url' => ['lsgis/'],
              'roles' => ['super-admin','admin-lsgi'],
              'permissions' => ['lsgis-index'],
              'icon' => 'lsgi_unselected.png',
              'active_icon' => 'lsgi_selected.png'
            ],
            [
              'label' => 'Ward ',
              'url' => ['wards/'],
              'roles' => ['super-admin','admin-lsgi'],
              'permissions' => ['wards-index'],
              'icon' => 'ward_unselected.png',
              'active_icon' => 'ward_selected.png'
            ],
            [
              'label' => 'Residence Categories ',
              'url' => ['residence-categories/index'],
              'roles' => ['super-admin','admin-lsgi'],
              'permissions' => ['residence-categories-index'],
              'icon' => 'residence_categories_unselected.png',
              'active_icon' => 'residence_categories_selected.png'
            ],
            [
              'label' => 'Waste Category ',
              'url' => ['waste-categories/'],
              'roles' => ['super-admin','admin-lsgi'],
              'permissions' => ['waste-categories-index'],
              'icon' => 'waste_categories_unselected.png',
              'active_icon' => 'waste_categories_selected.png'
            ],
            // [
            //   'label' => 'Residence Categories ',
            //   'url' => ['residence-categories/index'],
            //   'roles' => ['super-admin','admin-lsgi'],
            //   'permissions' => ['residence-categories-index'],
            //   'icon' => 'fa fa-dashboard'
            // ],
           [
              'label' => 'Haritha Karma Sena ',
              'url' => ['green-action-units/'],
              'roles' => ['super-admin','admin-lsgi'],
              'permissions' => ['green-action-units-index'],
              'icon' => 'haritha_karma_sena_unselected.png',
              'active_icon' => 'haritha_karma_sena_selected.png',
            ],
            [
              'label' => 'Public Gathering Methods ',
              'url' => ['public-gathering-methods/'],
              'roles' => ['super-admin','admin-lsgi'],
              'permissions' => ['public-gathering-methods-index'],
              'icon' => 'public_gathering_unselected.png',
              'active_icon' => 'public_gathering_selected.png'
            ],
           [
              'label' => 'Waste Collection Method ',
              'url' => ['waste-collection-methods/'],
              'roles' => ['super-admin','admin-lsgi'],
              'permissions' => ['waste-collection-methods-index'],
              'icon' => 'waste_collection_unselected.png',
              'active_icon' => 'waste_collection_selected.png'
            ],
            [
              'label' => 'Building Type ',
              'url' => ['building-types/'],
              'roles' => ['super-admin','admin-lsgi'],
              'permissions' => ['building-types-index'],
              'icon' => 'building_type_unselected.png',
              'active_icon' => 'building_type_selected.png'
            ],
            [
              'label' => 'Trading Type ',
              'url' => ['trading-types/'],
              'roles' => ['super-admin','admin-lsgi'],
              'permissions' => ['trading-types-index'],
              'icon' => 'trading_type_unselected.png',
              'active_icon' => 'trading_type_selected.png'
            ],
            [
              'label' => 'Shop Type ',
              'url' => ['shop-types/'],
              'roles' => ['super-admin','admin-lsgi'],
              'permissions' => ['shop-types-index'],
              'icon' => 'shop_unselected.png',
              'active_icon' => 'shop_selected.png'
            ],
            [
              'label' => 'Fee Collection interval ',
              'url' => ['fee-collection-interval/'],
              'roles' => ['super-admin','admin-lsgi'],
              'permissions' => ['fee-collection-interval-index'],
              'icon' => 'fee_collection_unselected.png',
              'active_icon' => 'fee_collection_selected.png'
            ],
            [
              'label' => 'Terrace Farming Help Type ',
              'url' => ['terrace-farming-help-types/'],
              'roles' => ['super-admin','admin-lsgi'],
              'permissions' => ['terrace-farming-help-types-index'],
              'icon' => 'terrace_farming_unselected.png',
              'active_icon' => 'terrace_farming_selected.png'
            ],
            [
              'label' => 'Services ',
              'url' => ['services/'],
              'roles' => ['super-admin','admin-lsgi'],
              'permissions'=>['services-index'],
              'icon' => 'service_unselected.png',
                'active_icon' => 'service_selected.png'
            ],
            [
              'label' => 'Bundled Services ',
              'url' => ['bundled-services/'],
              'roles' => ['super-admin','admin-lsgi'],
              'permissions' => ['bundled-services-index'],
              'icon' => 'bundled_unselected.png',
              'active_icon' => 'bundled_selected.png'
            ],
            [
              'label' => 'Complaints  ',
              'url' => ['complaints/index'],
              'roles' => ['super-admin','admin-lsgi'],
              'permissions' => ['complaints-index'],
              'icon' => 'complaints_unselected.png',
              'active_icon' => 'complaints_selected.png'
            ],
            [
              'label' => 'Adminstration Types ',
              'url' => ['administration-type/index'],
              'roles' => ['super-admin','admin-lsgi'],
              'permissions' => ['administration-type-index'],
              'icon' => 'administrator-type_unselected.png',
              'active_icon' => 'administrator-type_selected.png'
            ],
            [
              'label' => 'Dumping Event types',
              'url' => ['dumping-event-types/index'],
               'roles' => ['super-admin','admin-lsgi'],
               'permissions' => ['dumping-event-types-index'],
              'icon' => 'dumping_event_type_unselected.png',
              'active_icon' => 'dumping_event_type.png'
            ],
            [
              'label' => 'Labels and Translations',
              'url' => ['labels/index'],
               'roles' => ['super-admin','admin-lsgi'],
               'permissions' => ['labels-index'],
              'icon' => 'labels_and_translations_unselected.png',
              'active_icon' => 'labels_and_translations.png'
            ],
            
            ]
            ],
            [
              'label' => 'Residential association',
              'url' => ['residential-association/index'],
              'roles' => ['super-admin','admin-lsgi','camera-monitoring-admin'],
              'permissions' => ['residential-association-index'],
              'icon' => 'residential_association_unselected.png',
              'active_icon' => 'residential_association_selected.png'
            ],
            [
              'label' => 'Logout',
              'url' => ['account/logout'],
              'roles' => ['super-admin','admin-lsgi','admin-hks','coordinator','supervisor','surveyor'],
              'permissions' => ['Account-logout'],
              'icon' => 'logout_unselected.png',
              'active_icon' => 'logout_selected.png'
            ],
          ];
         $linksCamera = [
          [
                  'label' => 'Dashboard',
                  'url' => ['wms-dashboard/'],
                  'roles' => ['super-admin','admin-lsgi','admin-hks','coordinator','supervisor','camera-monitoring-admin'],
                  'permissions' => ['wms-dashboard-index'],
                  'icon' => 'dashboard_unselected.png',
                  'active_icon' => 'dashboard_selected.png'
                ],
          [

              'label' => 'Users',
              'icon' => 'user_unselected.png',
              'active_icon' => 'user_selected.png',
              'roles' => ['super-admin','admin-lsgi','admin-hks','coordinator','supervisor'],
              'permissions' => [
                'Account-super-admin','Account-index','Account-admin-lsgi','Account-admin-hks',
                'Account-supervisors','Account-green-technicians','Account-coordinators','user-list'
              ],
              'children' => $rbacChildrenList
            //   [
            //    [
            //   'label' => 'Super Admin',
            //   'url' => ['account/super-admin'],
            //   'roles' => ['super-admin'],
            //   'permissions' => ['Account-super-admin'],
            //   'icon' => 'fa fa-dashboard'
            // ],
            //     [
            //   'label' => 'Surveyor',
            //   'url' => ['account/index'],
            //   'roles' => ['super-admin','admin-lsgi','coordinator'],
            //   'permissions' => ['Account-index'],
            //   'icon' => 'fa fa-dashboard'
            // ],
            //     [
            //   'label' => 'Lsgi Admins',
            //   'url' => ['account/admin-lsgi'],
            //   'roles' => ['super-admin'],
            //   'permissions' => ['Account-admin-lsgi'],
            //   'icon' => 'fa fa-dashboard'
            // ],
            // [
            //   'label' => 'Hks Admins ',
            //   'url' => ['account/admin-hks'],
            //   'roles' => ['super-admin','admin-lsgi'],
            //   'permissions' => ['Account-admin-hks'],
            //   'icon' => 'fa fa-dashboard'
            // ],
            // [
            //   'label' => 'Supervisors ',
            //   'url' => ['account/supervisors'],
            //   'roles' => ['super-admin','admin-lsgi','admin-hks'],
            //   'permissions' => ['Account-supervisors'],
            //   'icon' => 'fa fa-dashboard'
            // ],
            // [
            //   'label' => 'Green Technicians ',
            //   'url' => ['account/green-technicians'],
            //   'roles' => ['super-admin','admin-lsgi','admin-hks','supervisor'],
            //   'permissions' => ['Account-green-technicians'],
            //   'icon' => 'fa fa-dashboard'
            // ],
            // [
            //   'label' => 'Survey Coordinator ',
            //   'url' => ['account/coordinators'],
            //   'roles' => ['super-admin','admin-lsgi'],
            //   'permissions' => ['Account-coordinators'],
            //   'icon' => 'fa fa-dashboard'
            // ],
            //   ]
            ],
            [
              'label' => 'QR Code ',
              'url' => ['qr-code/'],
             'roles' => ['super-admin','admin-lsgi'],
             'permissions' => ['qr-code-index'],
             'icon' => 'qr_code_unselected.png',
             'active_icon' => 'qr_code_selected.png'
            ],
            [
              'label' => 'Customers  ',
              'url' => ['customers/index'],
               'roles' => ['super-admin','admin-lsgi','admin-hks','supervisor','coordinator'],
               'permissions' => ['Customers-index'],
               'icon' => 'customer_unselected.png',
               'active_icon' => 'customer_selected.png'
            ],
            [
              'label' => 'Subscriptions  ',
              'url' => ['account-service-requests/index'],
              'roles' => ['super-admin','admin-lsgi','admin-hks','supervisor'],
              'permissions' => ['account-service-requests-index'],
              'icon' => 'Subscription_unselected.png',
              'active_icon' => 'Subscription_selected.png'
            ],
            // [
            //   'label' => 'Deactivation Requests  ',
            //   'url' => ['deactivation-requests/index'],
            //   'icon' => 'fa fa-dashboard'
            // ],
            // [
            //   'label' => 'Fee collected  ',
            //   'url' => ['account-fee/index'],
            //   'icon' => 'fa fa-dashboard'
            // ],
            [
              'label' => 'Payment Requests  ',
              'url' => ['payment-requests/index'],
               'roles' => ['super-admin','admin-lsgi','admin-hks','supervisor'],
               'permissions' => ['payment-requests-index'],
               'icon' => 'payment_request_unselected.png',
               'active_icon' => 'payment_request_selected.png'
            ],
            [
              'label' => 'Payments  ',
              'url' => ['payments/index'],
               'roles' => ['super-admin','admin-lsgi','admin-hks','supervisor'],
               'permissions' => ['payments-index'],
               'icon' => 'payment_unselected.png',
               'active_icon' => 'payments_selected.png'
            ],
            [
              'label' => 'Service Requests  ',
              'url' => ['service-requests/index'],
               'roles' => ['super-admin','admin-lsgi','admin-hks','supervisor'],
               'permissions' => ['service-requests-index'],
               'icon' => 'service_request_unselected.png',
               'active_icon' => 'service_request_selected.png'
            ],
            [
              'label' => 'Special Service Requests  ',
              'url' => ['service-requests/special-services'],
               'roles' => ['super-admin','admin-lsgi','admin-hks','supervisor'],
               'permissions' => ['service-requests-special-services'],
              'icon' => 'special_service_request.png',
              'active_icon' => 'special_service_request_selected.png'
            ],
            [
              'label' => 'Complaints  ',
              'url' => ['requests/complaints'],
              'roles' => ['super-admin','admin-lsgi','admin-hks','supervisor'],
              'permissions' => ['requests-complaints'],
              'icon' => 'complaints_unselected.png',
              'active_icon' => 'complaints_selected.png'
            ],
           [
              'label' => 'Reports',
              'icon' => 'complaint_reports_unselected.png',
              'active_icon' => 'complaint_reports_selected.png',
               'roles' => ['super-admin','admin-lsgi','admin-hks','coordinator'],
               'permissions' => [
                 'reports-door-close','reports-survey-count','reports-waste-management-type',
                 'reports-ward-wise-count','reports-collection'
               ],
              'children' => [
               [
              'label' => 'Door Close Report',
              'url' => ['reports/door-close'],
               'roles' => ['super-admin','admin-lsgi','coordinator'],
               'permissions' => ['reports-door-close'],
               'icon' => 'Closed_door_unselected.png',
               'active_icon' => 'Closed_door_selected.png'
            ],
                [
              'label' => 'Survey Count Report',
              'url' => ['reports/survey-count'],
               'roles' => ['super-admin','admin-lsgi','coordinator'],
               'permissions' => ['reports-survey-count'],
               'icon' => 'Survey_count_report_unselected.png',
               'active_icon' => 'survey_count_report_selected.png'
            ],
            [
              'label' => 'Waste Management Type Report',
              'url' => ['reports/waste-management-type'],
               'roles' => ['super-admin','admin-lsgi','admin-hks','supervisor'],
               'permissions' => ['reports-waste-management-type'],
               'icon' => 'waste_management_report_unselected.png',
               'active_icon' => 'waste_management_report_selected.png'
            ],
            [
              'label' => 'Ward Wise Count Report',
              'url' => ['reports/ward-wise-count'],
               'roles' => ['super-admin','admin-lsgi','coordinator'],
               'permissions' => ['reports-ward-wise-count'],
               'icon' => 'Ward_wise_report_unselected.png',
               'active_icon' => 'ward_wise_report_selected.png'
            ],
            [
              'label' => 'Collection Report',
              // 'url' => ['reports/collection'],
               'roles' => ['super-admin','admin-lsgi','coordinator'],
               'permissions' => ['reports-collection'],
               'icon' => 'collection Report_unselected.png',
               'active_icon' => 'collection Report_selected.png',
               'children' => [
                [
              'label' => 'Detailed Report',
              'url' => ['reports/collection'],
               'roles' => ['super-admin','admin-lsgi','coordinator'],
               'permissions' => ['reports-collection'],
               'icon' => 'Detailed Report_unselected.png',
               'active_icon' => 'Detailed Report_selected.png'
              ],
              [
              'label' => 'Summary Report',
              'url' => ['reports/collection-summary'],
               'roles' => ['super-admin','admin-lsgi','coordinator'],
               'permissions' => ['reports-collection-summary'],
               'icon' => 'Summary Report_unselected.png',
               'active_icon' => 'Summary Report_selected.png'
              ],
               ],
            ],
            [
              'label' => 'Subscription Report',
              // 'url' => ['reports/collection'],
               'roles' => ['super-admin','admin-lsgi','coordinator'],
               'permissions' => ['reports-subscriptions'],
               'icon' => 'Subscription Report_unselected.png',
               'active_icon' => 'Subscription Report_selected.png',
               'children' => [
                [
              'label' => 'Detailed Report',
              'url' => ['reports/subscriptions'],
               'roles' => ['super-admin','admin-lsgi','coordinator'],
               'permissions' => ['reports-subscriptions'],
               'icon' => 'Detailed Report_unselected.png',
               'active_icon' => 'Detailed Report_selected.png'
              ],
              [
              'label' => 'Summary Report',
              'url' => ['reports/subscription-summary'],
               'roles' => ['super-admin','admin-lsgi','coordinator'],
               'permissions' => ['reports-subscription-summary'],
               'icon' => 'Summary Report_unselected.png',
               'active_icon' => 'Summary Report_selected.png'
              ],
               ],
            ],
            [
              'label' => 'Service Account Disabled Report',
              // 'url' => ['reports/collection'],
               'roles' => ['super-admin','admin-lsgi','coordinator'],
               'permissions' => ['reports-subscription-disabled'],
              'icon' => 'Service Account  Report_unselected.png',
              'active_icon' => 'Service Account  Report_selected.png',
               'children' => [
                [
              'label' => 'Detailed Report',
              'url' => ['reports/subscription-disabled'],
               'roles' => ['super-admin','admin-lsgi','coordinator'],
               'permissions' => ['reports-subscription-disabled'],
               'icon' => 'Detailed Report_unselected.png',
               'active_icon' => 'Detailed Report_selected.png'
              ],
              [
              'label' => 'Summary Report',
              'url' => ['reports/subscription-disabled-summary'],
               'roles' => ['super-admin','admin-lsgi','coordinator'],
               'permissions' => ['reports-subscription-disabled-summary'],
               'icon' => 'Summary Report_unselected.png',
               'active_icon' => 'Summary Report_selected.png'
              ],
               ],
            ],

            [
              'label' => 'Complaints Report',
              // 'url' => ['reports/collection'],
               'roles' => ['super-admin','admin-lsgi','coordinator'],
               'permissions' => ['reports-complaints'],
               'icon' => 'complaint_reports_unselected.png',
               'active_icon' => 'complaint_reports_selected.png',
               'children' => [
                [
              'label' => 'Detailed Report',
              'url' => ['reports/complaints'],
               'roles' => ['super-admin','admin-lsgi','coordinator'],
               'permissions' => ['reports-complaints'],
               'icon' => 'Detailed Report_unselected.png',
               'active_icon' => 'Detailed Report_selected.png'
              ],
              [
              'label' => 'Summary Report',
              'url' => ['reports/complaints-summary'],
               'roles' => ['super-admin','admin-lsgi','coordinator'],
               'permissions' => ['reports-complaints-summary'],
               'icon' => 'Summary Report_unselected.png',
               'active_icon' => 'Summary Report_selected.png'
              ],
               ],
            ],
            [
              'label' => 'Complaints Resolution Report',
              // 'url' => ['reports/collection'],
               'roles' => ['super-admin','admin-lsgi','coordinator'],
               'permissions' => ['reports-complaint-resolution'],
               'icon' => 'complaint_resolution_unselected.png',
               'active_icon' => 'complaint_resolution_selected.png',
               'children' => [
                [
              'label' => 'Detailed Report',
              'url' => ['reports/complaint-resolution'],
               'roles' => ['super-admin','admin-lsgi','coordinator'],
               'permissions' => ['reports-complaint-resolution'],
               'icon' => 'Detailed Report_unselected.png',
               'active_icon' => 'Detailed Report_selected.png'
              ],
              [
              'label' => 'Summary Report',
              'url' => ['reports/complaint-resolution-summary'],
               'roles' => ['super-admin','admin-lsgi','coordinator'],
               'permissions' => ['reports-complaint-resolution-summary'],
               'icon' => 'Summary Report_unselected.png',
               'active_icon' => 'Summary Report_selected.png'
              ],
               ],
            ],
            [
              'label' => 'Service Completion Report',
              // 'url' => ['reports/collection'],
               'roles' => ['super-admin','admin-lsgi','coordinator'],
               'permissions' => ['reports-service-completion'],
               'icon' => 'Service_completion_unselected.png',
               'active_icon' => 'Service_completion_selected.png',
               'children' => [
                [
              'label' => 'Detailed Report',
              'url' => ['reports/service-completion'],
               'roles' => ['super-admin','admin-lsgi','coordinator'],
               'permissions' => ['reports-service-completion'],
               'icon' => 'Detailed Report_unselected.png',
               'active_icon' => 'Detailed Report_selected.png'
              ],
              [
              'label' => 'Summary Report',
              'url' => ['reports/service-completion-summary'],
               'roles' => ['super-admin','admin-lsgi','coordinator'],
               'permissions' => ['reports-service-completion-summary'],
               'icon' => 'Summary Report_unselected.png',
               'active_icon' => 'Summary Report_selected.png'
              ],
               ],
            ],
            [
              'label' => 'Service Pending Report',
              // 'url' => ['reports/collection'],
               'roles' => ['super-admin','admin-lsgi','coordinator'],
               'permissions' => ['reports-service-pending'],
               'icon' => 'service_pending_unselected.png',
               'active_icon' => 'service_pending_selected.png',
               'children' => [
                [
              'label' => 'Detailed Report',
              'url' => ['reports/service-pending'],
               'roles' => ['super-admin','admin-lsgi','coordinator'],
               'permissions' => ['reports-service-pending'],
               'icon' => 'Detailed Report_unselected.png',
               'active_icon' => 'Detailed Report_selected.png'
              ],
              [
              'label' => 'Summary Report',
              'url' => ['reports/service-pending-summary'],
               'roles' => ['super-admin','admin-lsgi','coordinator'],
               'permissions' => ['reports-service-pending-summary'],
               'icon' => 'Summary Report_unselected.png',
               'active_icon' => 'Summary Report_selected.png'
              ],
               ],
            ],
            [
              'label' => 'Customer Plan Report',
              // 'url' => ['reports/collection'],
               'roles' => ['super-admin','admin-lsgi','coordinator'],
               'permissions' => ['reports-customer-plan'],
               'icon' => 'customer_plan_unselected.png',
               'active_icon' => 'customer_plan_selected.png',
               'children' => [
                [
              'label' => 'Detailed Report',
              'url' => ['reports/customer-plan'],
               'roles' => ['super-admin','admin-lsgi','coordinator'],
               'permissions' => ['reports-customer-plan'],
               'icon' => 'Detailed Report_unselected.png',
               'active_icon' => 'Detailed Report_selected.png'
              ],
              [
              'label' => 'Summary Report',
              'url' => ['reports/customer-plan-summary'],
               'roles' => ['super-admin','admin-lsgi','coordinator'],
               'permissions' => ['reports-customer-plan-summary'],
               'icon' => 'Summary Report_unselected.png',
               'active_icon' => 'Summary Report_selected.png'
              ],
               ],
            ],
            [
              'label' => 'Itemwise Service Completion Report',
              'url' => ['reports/itemwise-service-completion'],
               'roles' => ['super-admin','admin-lsgi','coordinator'],
               'permissions' => ['reports-itemwise-service-completion'],
              'icon' => 'Service_completion_unselected.png',
              'active_icon' => 'Service_completion_selected.png'
            ],
            [
              'label' => 'Itemwise Service Pending Report',
              'url' => ['reports/itemwise-service-pending'],
               'roles' => ['super-admin','admin-lsgi','coordinator'],
               'permissions' => ['reports-itemwise-service-pending'],
               'icon' => 'service_pending_unselected.png',
               'active_icon' => 'service_pending_selected.png'
            ],
            [
              'label' => 'Waste Quality Collected Report',
              'url' => ['reports/waste-quality-collected'],
               'roles' => ['super-admin','admin-lsgi','coordinator'],
               'permissions' => ['reports-waste-quality-collected'],
               'icon' => 'Waste_quality_collected_unselected.png',
               'active_icon' => 'Waste_quality_collected_selected.png'
            ],
            [
              'label' => 'Waste Quantity Collected Report',
              'url' => ['reports/waste-quantity-collected'],
               'roles' => ['super-admin','admin-lsgi','coordinator'],
               'permissions' => ['reports-waste-quantity-collected'],
               'icon' => 'Waste_Quantity_Collected_unselected.png',
               'active_icon' => 'Waste_Quantity_Collected_selected.png'
            ],
              ]
            ],
            [
              'label' => 'Camera Surveillance',
              'icon' => 'camera_unselected.png',
              'active_icon' => 'camera_selected.png',
              'roles' => ['super-admin','admin-lsgi','admin-hks','supervisor','camera-monitoring-admin'],
              'permissions' => [
                'dashboard-index'
                // ,'camera-index','camera-qr-code-index','incidents-index',
                // 'monitoring-groups-index','incident-reports-index','memo-payment-reports-index',
                // 'payment-counter-reports-index','outstanding-payment-reports-index',
                // 'monitoring-group-users-index','memos-index','memo-types-index','memo-penalties-index',
                // 'lsgi-authorized-signatories-index','payment-counter','payment-counter-assign-counter-admin'
              ],
              'children' => [
                [
                  'label' => 'Dashboard',
                  'url' => ['dashboard/'],
                  'roles' => ['super-admin','admin-lsgi','admin-hks','supervisor','camera-monitoring-admin'],
                  'permissions' => ['dashboard-index'],
                  'icon' => 'dashboard_unselected.png',
                  'active_icon' => 'dashboard_selected.png'
                ],
                [
                  'label' => 'Camera List',
                  'url' => ['camera/index'],
                  'roles' => ['super-admin','admin-lsgi','camera-monitoring-admin'],
                  'permissions' => ['camera-index'],
                  'icon' => 'camera_list_unselected.png',
                  'active_icon' => 'camera_list_selected.png'
                ],
                [
                  'label' => 'Camera Service Requests',
                  'url' => ['camera-service-request/index'],
                  'roles' => ['super-admin','admin-lsgi','camera-monitoring-admin'],
                  'permissions' => ['camera-service-request-index'],
                  'icon' => 'camera_list_unselected.png',
                  'active_icon' => 'camera_list_selected.png'
                ],
                [
                  'label' => 'QR Code ',
                  'url' => ['camera-qr-code/'],
                  'roles' => ['super-admin','admin-lsgi','camera-monitoring-admin'],
                  'permissions' => ['camera-qr-code-index'],
                  'icon' => 'qr_code_unselected.png',
                  'active_icon' => 'qr_code_selected.png'
                ],
                [
                  'label' => 'Incident List',
                  'url' => ['incidents/index'],
                  'roles' => ['super-admin','admin-lsgi','camera-monitoring-admin'],
                  'permissions' => ['incidents-index'],
                  'icon' => 'incident_list_unselected.png',
                  'active_icon' => 'incident_list_selected.png'
                ],
                [
                  'label' => 'Monitoring Groups',
                  'url' => ['monitoring-groups/'],
                  'roles' => ['super-admin','admin-lsgi','camera-monitoring-admin'],
                  'permissions' => ['monitoring-groups-index'],
                  'icon' => 'monitoring_group_unselected.png',
                  'active_icon' => 'monitoring_group_selected.png'
                ],
                 [
                  'label' => 'Reports',
                  // 'url' => ['incident-reports/index'],
                  'roles' => ['super-admin','admin-lsgi','camera-monitoring-admin'],
                  'permissions' => [
                    'incident-reports-index','memo-payment-reports-index','payment-counter-reports-index',
                    'outstanding-payment-reports-index'
                  ],
                  'icon' => 'camera-reports_unselected.png',
                  'active_icon' => 'camera-reports_selected.png',
                  'children' => [
                    [
                      'label' => 'Incident Reports',
                      'url' => ['incident-reports/index'],
                      'roles' => ['super-admin','admin-lsgi','camera-monitoring-admin'],
                      'permissions' => ['incident-reports-index'],
                      'icon' => 'incident_list_unselected.png',
                      'active_icon' => 'incident_list_selected.png'
                    ],
                    [
                      'label' => 'Memo Payment Reports',
                      'url' => ['memo-payment-reports/index'],
                      'roles' => ['super-admin','admin-lsgi','camera-monitoring-admin'],
                      'permissions' => ['memo-payment-reports-index'],
                      'icon' => 'memo_payment_collected_unselected.png',
                      'active_icon' => 'memo_payment_collected_selected.png'
                    ],
                    [
                      'label' => 'Counterwise Reports',
                      'url' => ['payment-counter-reports/index'],
                      'roles' => ['super-admin','admin-lsgi','camera-monitoring-admin'],
                      'permissions' => ['payment-counter-reports-index'],
                      'icon' => 'counterwise_unselected.png',
                      'active_icon' => 'counterwise_selected.png'
                    ],
                    [
                      'label' => 'Outstanding Payment Reports',
                      'url' => ['outstanding-payment-reports/index'],
                      'roles' => ['super-admin','admin-lsgi','camera-monitoring-admin'],
                      'permissions' => ['outstanding-payment-reports-index'],
                      'icon' => 'outstanding_payments_unselected.png',
                      'active_icon' => 'outstanding_payments_selected.png'
                    ],
                  ],
                ],
                [
                  'label' => 'Users',
                  'url' => ['monitoring-group-users/'],
                  'roles' => ['super-admin','admin-lsgi','camera-monitoring-admin'],
                  'permissions' => ['monitoring-group-users-index'],
                  'icon' => 'user_unselected.png',
                  'active_icon' => 'user_selected.png',
                ],
                [
                  'label' => 'Memos',
                  'url' => ['memos/index'],
                  'roles' => ['super-admin','admin-lsgi','camera-monitoring-admin'],
                  'permissions' => ['memos-index'],
                  'icon' => 'memos_unselected.png',
                  'active_icon' => 'memos_selected.png'
                ],
                [
                  'label' => 'Memo Type',
                  'url' => ['memo-types/'],
                  'roles' => ['super-admin','admin-lsgi','camera-monitoring-admin'],
                  'permissions' => ['memo-types-index'],
                  'icon' => 'memos_unselected.png',
                  'active_icon' => 'memos_selected.png'
                ],
                [
                  'label' => 'Memo Penalty',
                  'url' => ['memo-penalties/'],
                  'roles' => ['super-admin','admin-lsgi','camera-monitoring-admin'],
                  'permissions' => ['memo-penalties-index'],
                  'icon' => 'memose_penalty_unselected.png',
                  'active_icon' => 'memose_penalty_selected.png'
                ],
                [
                  'label' => 'Lsgi Authorized Signatory',
                  'url' => ['lsgi-authorized-signatories/'],
                  'roles' => ['super-admin','admin-lsgi','camera-monitoring-admin'],
                  'permissions' => ['lsgi-authorized-signatories-index'],
                  'icon' => 'lsgi_authorized_unselected.png',
                  'active_icon' => 'lsgi_authorized_selected.png'
                ],
                [
                  'label' => 'Payment Counters',
                  'url' => ['payment-counter/'],
                  'roles' => ['super-admin','admin-lsgi','camera-monitoring-admin'],
                  'permissions' => ['payment-counter-index'],
                  'icon' => 'payment_counter_unselected.png',
                  'active_icon' => 'payment_counter_selected.png'
                ],
                [
                  'label' => 'Camera Service',
                  'url' => ['camera-service/index'],
                  'roles' => ['super-admin','admin-lsgi','camera-monitoring-admin'],
                  'permissions' => ['camera-service-index'],
                  'icon' => 'camera_service_unselected.png',
                  'active_icon' => 'camera_service_selected.png'
                ],
                // [
                //   'label' => 'Assign Counter Admin',
                //   'url' => ['payment-counter/assign-counter-admin'],
                //   'roles' => ['super-admin','admin-lsgi','camera-monitoring-admin'],
                //   'permissions' => ['payment-counter-assign-counter-admin'],
                //   'icon' => 'fa fa-dashboard'
                // ],
              ]
            ],
            [
              'label' => 'Camera service request',
              'url' => ['camera-service-request/index'],
              'roles' => ['super-admin','admin-lsgi','camera-monitoring-admin'],
              'permissions' => ['camera-service-request-index'],
              'icon' => 'service_request_unselected.png',
              'active_icon' => 'service_request_selected.png'
            ],
            [
              'label' => 'Residential association',
              'url' => ['residential-association/index'],
              'roles' => ['super-admin','admin-lsgi','camera-monitoring-admin'],
              'permissions' => ['residential-association-index'],
              'icon' => 'residential_association_unselected.png',
              'active_icon' => 'residential_association_selected.png'
            ],
            [
              'label' => 'Change Residential association',
              'url' => ['residential-association/change-association'],
              'roles' => ['super-admin','admin-lsgi'],
              'permissions' => ['residential-association-change-association'],
              'icon' => 'change_residential_unselected.png',
              'active_icon' => 'change_residential_selected.png'
            ],
            [
              'label' => 'Association type',
              'url' => ['association-type/'],
              'roles' => ['super-admin','admin-lsgi','camera-monitoring-admin'],
              'permissions' => ['association-type-index'],
              'icon' => 'association_type_unselected.png',
              'active_icon' => 'association_type_selected.png'
            ],
            [
              'label' => 'Installation checklist',
              'url' => ['installation-checklist/'],
              'roles' => ['super-admin','admin-lsgi','camera-monitoring-admin'],
              'permissions' => ['installation-checklist-index'],
              'icon' => 'Installer_checklist_payments_unselected.png',
              'active_icon' => 'Installer_checklist_payments_unselected.png'
            ],
            // [
            //   'label' => 'Access controller',
            //   'roles' => ['super-admin'],
            //   'icon' => 'fa fa-book',
            //   'children' => [
            //     [
            //       'label' => 'Controllers',
            //       'url' => ['rbac/controllers-index'],
            //       'roles' => ['super-admin'],
            //       'icon' => 'fa fa-dashboard'
            //     ],
            //     [
            //       'label' => 'Roles',
            //       'url' => ['rbac/roles-index'],
            //       'roles' => ['super-admin'],
            //       'icon' => 'fa fa-dashboard'
            //     ],
            //     [
            //       'label' => 'Users',
            //       'url' => ['rbac/users-index'],
            //       'roles' => ['super-admin'],
            //       'icon' => 'fa fa-dashboard'
            //     ],
            //   ],
            // ],
            [
              'label' => 'Logout',
              'url' => ['account/logout'],
              'roles' => ['super-admin','admin-lsgi','admin-hks','coordinator','supervisor','surveyor','camera-monitoring-admin'],
              'permissions' => ['Account-logout'],
              'icon' => 'logout_unselected.png',
              'active_icon' => 'logout_selected.png'
            ],
          ];

              function createMenu($settings,$currentLevel = 1,$currentUserRole=null) {
                $modelUser  = Yii::$app->user->identity;

                $currentUserRole = $userRole = isset($modelUser->role)?$modelUser->role:'';

                $modelAuthItemChilds = \backend\models\AuthItemChild::find()->where(['parent'=>$currentUserRole])->all();
                $currentUserPermission = ['Account-logout','user-list','type-list'];
                foreach ($modelAuthItemChilds as $modelAuthItemChild) {
                  $currentUserPermission[] = $modelAuthItemChild->child;
                }
                $listTags = "";
                $nextLevel = $currentLevel+1;
                $isActiveUl = false;
                foreach($settings as $setting) {
                 // print_r($setting['roles']);
                 // print_r($currentUserRole);die();
                  $roles = isset($setting['roles'])?$setting['roles']:[];
                  $permissions = isset($setting['permissions'])?$setting['permissions']:[];
                  $arrayinterset = array_intersect($currentUserPermission, $permissions);
                  if(!$arrayinterset)
                  // if(!in_array($currentUserRole,$roles))

                  continue;
                  $path = isset($setting['url'])?Url::to($setting['url']):'#';
                  $currentLabel = isset($setting['label'])?$setting['label']:'';
                  $currentLabel = ucwords($currentLabel);
                  $linkVal = "<span class='hide-menu'>$currentLabel</span>";
                  $linkVal = isset($setting['icon'])?"<i class = '{$setting['icon']}' ></i>&nbsp;".$linkVal:$linkVal;



                  $linkVal2 = "<span class='hide-menu'>&nbsp;$currentLabel</span>";

                  //$linkVal = isset($setting['icon'])?"<i class = '{$setting['icon']}' ></i>&nbsp;".$linkVal:$linkVal;
                  $imgUrl = Url::to('@web/images/');
                  $linkVal2 = isset($setting['active_icon'])?"<img src='$imgUrl{$setting['active_icon']}' class='active_icon' >":$linkVal2;

                  $linkVal = isset($setting['icon'])?"<img src='$imgUrl{$setting['icon']}' class='icon' >".$linkVal2.$linkVal:$linkVal;

                  //$linkVal = "<img src='$imgUrl{$setting['active_icon']}' class='icon' >".$linkVal;
                  //$linkVal2 = isset($setting['active_icon'])?"<img src='$imgUrl{$setting['active_icon']}' class='active_icon' >&nbsp;".$linkVal2:$linkVal2;
                  $linkVal2 = isset($setting['active_icon'])?"<img src='$imgUrl{$setting['active_icon']}' class='active_icon' >&nbsp;":$linkVal2;

                  //$linkVal = $linkVal.' '.$linkVal2;



                  if(isset($setting['active'])&&$setting['active']) {
                    $active = 'active';
                    $isActiveUl =  true;
                  } else {
                    $active = '';
                  }
                  $currentLink = "<a  class = 'waves-effect $active' href = '$path'>$linkVal</a>";
                  $liInnerHtml = $currentLink;
                  if(isset($setting['children'])) {
                    //$liInnerHtml .= createMenu($setting['children'],$nextLevel);

                    $rt = createMenu($setting['children'],$nextLevel,$setting['roles']);

                    $liInnerHtml .= $rt['html'];
                    $isActiveUl = $rt['isActive'];

                  }
                  $currentItem = "<li class = '$active'>$liInnerHtml</li>";
                  $listTags .= $currentItem;
                }
                $levelLabels = [
                  '2' =>'nav-second-level',
                  '3' => 'nav-third-level',
                  '4' => 'nav-fourth-level'
                ];
                $levelLabel = isset($levelLabels[$currentLevel])?$levelLabels[$currentLevel]:"";
                $ulClass = $levelLabel;
                $id ='';
                if($isActiveUl) {
                  $ulClass .= " collapse";
                }
                if($currentLevel>1) $tmp ='ok';
                //$ulClass .= !(isset($settings['active'])&&$settings['active'])?"":'';
                else {
                  $id ='id = "side-menu"';
                }

                $ret = "<ul class = 'nav  $ulClass' $id>$listTags</ul>";

                return ['html'=>$ret,'isActive'=> $isActiveUl];

              }
              $modelUser  = Yii::$app->user->identity;
                $lsgi = isset($modelUser->lsgi_id)?$modelUser->lsgi_id:null;
                $modelLsgi = Lsgi::find()->where(['id'=>$lsgi])->one();
                if($modelLsgi){
                if($modelLsgi->is_wastemanagement_required==1&&$modelLsgi->is_camera_surveillance_required==1){
                  $links = ['super-admin' => $linksSuperAdmin];
                }
                elseif($modelLsgi->is_wastemanagement_required==1&&$modelLsgi->is_camera_surveillance_required==0){
                  $links = ['super-admin' => $linksWastemanagement];
                }
                elseif($modelLsgi->is_wastemanagement_required==0&&$modelLsgi->is_camera_surveillance_required==1){
                  $links = ['super-admin' => $linksCamera];
                }
                else
                {
                  $links = ['super-admin' => $linksSuperAdmin];
                }
              }
              else
                {
                  $links = ['super-admin' => $linksSuperAdmin];
                }

              $link = $links['super-admin'];
              echo createMenu($link)['html'];

              ?>

    </div>
</div>
<style>
 #sidebar .sidebar-header {
    padding: 20px;
    background: transparent;
    text-align: center;
}
#side-menu {
    padding-top: 20px;
    overflow: hidden;
}
</style>