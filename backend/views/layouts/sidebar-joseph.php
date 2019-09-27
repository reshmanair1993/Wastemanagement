<?php
use yii\widgets\Menu;
use backend\models\Person;
use backend\models\AccountBackend;
use backend\models\AccountProvider;
use backend\models\Image;
use backend\models\Lsgi;
use yii\helpers\Url;
$modelUser  = Yii::$app->user->identity;
$lsgi = isset($modelUser->lsgi_id)?$modelUser->lsgi_id:null;
 ?>
  <div class="navbar-default sidebar" role="navigation">
    <div class="sidebar-nav slimscrollsidebar">
        <div class="sidebar-head">
            <h3><span class="fa-fw open-close"><i class="ti-menu hidden-xs"></i><i class="ti-close visible-xs"></i></span> <span class="hide-menu"> Management</span></h3>
        </div>
          <?php
          if(isset($modelUser->role))
          $userRole = $modelUser->role;
          $linksSuperAdmin = [
            [
              'label' => 'Dashboard',
              'url' => ['wms-dashboard/'],
              'roles' => ['super-admin','admin-lsgi','admin-hks','coordinator','supervisor'],
              'permissions' => ['wms-dashboard-index'],
              'icon' => 'fa fa-dashboard'
            ],
            [
              'label' => 'Users',
              'icon' => 'fa fa-book',
              'roles' => ['super-admin','admin-lsgi','admin-hks','coordinator','supervisor'],
              'permissions' => [
                'Account-super-admin','Account-index','Account-admin-lsgi','Account-admin-hks',
                'Account-supervisors','Account-green-technicians','Account-coordinators'
              ],
              'children' => [
                [
                  'label' => 'Super Admin',
                  'url' => ['account/super-admin'],
                  'roles' => ['super-admin'],
                  'permissions' => ['Account-super-admin'],
                  'icon' => 'fa fa-dashboard'
                ],
                [
                  'label' => 'Surveyor',
                  'url' => ['account/index'],
                  'roles' => ['super-admin','admin-lsgi','coordinator'],
                  'permissions' => ['Account-index'],
                  'icon' => 'fa fa-dashboard'
                ],
                [
                  'label' => 'Lsgi Admins',
                  'url' => ['account/admin-lsgi'],
                  'roles' => ['super-admin'],
                  'permissions' => ['Account-admin-lsgi'],
                  'icon' => 'fa fa-dashboard'
                ],
                [
                  'label' => 'Hks Admins ',
                  'url' => ['account/admin-hks'],
                  'roles' => ['super-admin','admin-lsgi'],
                  'permissions' => ['Account-admin-hks'],
                  'icon' => 'fa fa-dashboard'
                ],
                [
                  'label' => 'Supervisors ',
                  'url' => ['account/supervisors'],
                  'roles' => ['super-admin','admin-lsgi','admin-hks'],
                  'permissions' => ['Account-supervisors'],
                  'icon' => 'fa fa-dashboard'
                ],
                [
                  'label' => 'Green Technicians ',
                  'url' => ['account/green-technicians'],
                  'roles' => ['super-admin','admin-lsgi','admin-hks','supervisor'],
                  'permissions' => ['Account-green-technicians'],
                  'icon' => 'fa fa-dashboard'
                ],
                [
                  'label' => 'Survey Coordinators ',
                  'url' => ['account/coordinators'],
                  'roles' => ['super-admin','admin-lsgi'],
                  'permissions' => ['Account-coordinators'],
                  'icon' => 'fa fa-dashboard'
                ],
              ]
            ],
            [
              'label' => 'Survey Agencies ',
              'url' => ['survey-agencies/index'],
              'roles' => ['super-admin','admin-lsgi'],
              'permissions' => [
                'survey-agencies-index'
              ],
              'icon' => 'fa fa-dashboard'
            ],
            [
              'label' => 'Assign Supervisor ',
              'url' => ['account-supervisor/index'],
              'roles' => ['super-admin','admin-lsgi','admin-hks'],
              'permissions' => [
                'account-supervisor-index'
              ],
              'icon' => 'fa fa-dashboard'
            ],
            [
              'label' => 'Assign Gt ',
              'url' => ['account-gt/index'],
              'roles' => ['super-admin','admin-lsgi','admin-hks','supervisor'],
              'permissions' => [
                'account-gt-index'
              ],
              'icon' => 'fa fa-dashboard'
            ],
            [
              'label' => 'Schedule ',
              'url' => ['schedule/index'],
              'roles' => ['supervisor'],
              'permissions' => ['schedule-index'],
              'icon' => 'fa fa-dashboard'
            ],
            [
              'label' => 'QR Code ',
              'url' => ['qr-code/'],
              'roles' => ['super-admin','admin-lsgi'],
              'permissions' => ['qr-code-index'],
              'icon' => 'fa fa-dashboard'
            ],
            [
              'label' => 'Customers  ',
              'url' => ['customers/index'],
              'roles' => ['super-admin','admin-lsgi','admin-hks','supervisor','coordinator'],
              'permissions' => ['Customers-index'],
              'icon' => 'fa fa-dashboard'
            ],
            [
              'label' => 'Account Service Requests  ',
              'url' => ['account-service-requests/index'],
               'roles' => ['super-admin','admin-lsgi','admin-hks','supervisor'],
               'permissions' => ['account-service-requests-index'],
              'icon' => 'fa fa-dashboard'
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
              'icon' => 'fa fa-dashboard'
            ],
            [
              'label' => 'Payments  ',
              'url' => ['payments/index'],
               'roles' => ['super-admin','admin-lsgi','admin-hks','supervisor'],
               'permissions' => ['payments-index'],
              'icon' => 'fa fa-dashboard'
            ],
            [
              'label' => 'Service Requests  ',
              'url' => ['service-requests/index'],
               'roles' => ['super-admin','admin-lsgi','admin-hks','supervisor'],
               'permissions' => ['service-requests-index'],
              'icon' => 'fa fa-dashboard'
            ],
            [
              'label' => 'Complaints  ',
              'url' => ['requests/complaints'],
               'roles' => ['super-admin','admin-lsgi','admin-hks','supervisor'],
               'permissions' => ['requests-complaints'],
              'icon' => 'fa fa-dashboard'
            ],
            [
              'label' => 'Reports',
              'icon' => 'fa fa-book',
               'roles' => ['super-admin','admin-lsgi','admin-hks','coordinator'],
               'permissions' => [
                 'reports-door-close','reports-survey-count','reports-waste-management-type',
                 'reports-ward-wise-count'
               ],
              'children' => [
               [
              'label' => 'Door Close Report',
              'url' => ['reports/door-close'],
               'roles' => ['super-admin','admin-lsgi','coordinator'],
               'permissions' => ['reports-door-close'],
              'icon' => 'fa fa-dashboard'
            ],
                [
              'label' => 'Survey Count Report',
              'url' => ['reports/survey-count'],
               'roles' => ['super-admin','admin-lsgi','coordinator'],
               'permissions' => ['reports-survey-count'],
              'icon' => 'fa fa-dashboard'
            ],
            [
              'label' => 'Waste Management Type Report',
              'url' => ['reports/waste-management-type'],
               'roles' => ['super-admin','admin-lsgi','admin-hks','supervisor'],
               'permissions' => ['reports-waste-management-type'],
              'icon' => 'fa fa-dashboard'
            ],
            [
              'label' => 'Ward Wise Count Report',
              'url' => ['reports/ward-wise-count'],
               'roles' => ['super-admin','admin-lsgi','coordinator'],
               'permissions' => ['reports-ward-wise-count'],
              'icon' => 'fa fa-dashboard'
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
              'icon' => 'fa fa-book',
              'children' => [
                [
              'label' => 'State',
              'url' => ['states/'],
              'roles' => ['super-admin'],
              'permissions' => ['states-index'],
              'icon' => 'fa fa-dashboard'
            ],
            [
              'label' => 'District',
              'url' => ['districts/'],
              'roles' => ['super-admin'],
              'permissions' => ['districts-index'],
              'icon' => 'fa fa-dashboard'
            ],
            [
              'label' => 'Parliament Constituency',
              'url' => ['parliament-constituency/'],
              'roles' => ['super-admin'],
              'permissions' => ['parliament-constituency-index'],
              'icon' => 'fa fa-dashboard'
            ],
             [
              'label' => 'Assembly Constituency',
              'url' => ['assembly-constituency/'],
              'roles' => ['super-admin'],
              'permissions' => ['assembly-constituency-index'],
              'icon' => 'fa fa-dashboard'
            ],
            [
              'label' => 'lsgi Type',
              'url' => ['lsgi-type/'],
              'roles' => ['super-admin','admin-lsgi'],
              'permissions'=>['lsgi-type-index'],
              'icon' => 'fa fa-dashboard'
            ],
            [
              'label' => 'Lsgi Block ',
              'url' => ['lsgi-blocks/'],
              'roles' => ['super-admin','admin-lsgi'],
              'permissions' => ['lsgi-blocks-index'],
              'icon' => 'fa fa-dashboard'
            ],
            [
              'label' => 'Lsgi ',
              'url' => ['lsgis/'],
              'roles' => ['super-admin','admin-lsgi'],
              'permissions' => ['lsgis-index'],
              'icon' => 'fa fa-dashboard'
            ],
            [
              'label' => 'Ward ',
              'url' => ['wards/'],
              'roles' => ['super-admin','admin-lsgi'],
              'permissions' => ['wards-index'],
              'icon' => 'fa fa-dashboard'
            ],
            [
              'label' => 'Residence Categories ',
              'url' => ['residence-categories/index'],
              'roles' => ['super-admin','admin-lsgi'],
              'permissions' => ['residence-categories-index'],
              'icon' => 'fa fa-dashboard'
            ],
            [
              'label' => 'Waste Category ',
              'url' => ['waste-categories/'],
              'roles' => ['super-admin','admin-lsgi'],
              'permissions' => ['waste-categories-index'],
              'icon' => 'fa fa-dashboard'
            ],
            [
              'label' => 'Residence Categories ',
              'url' => ['residence-categories/index'],
              'roles' => ['super-admin','admin-lsgi'],
              'permissions' => ['residence-categories-index'],
              'icon' => 'fa fa-dashboard'
            ],
           [
              'label' => 'Haritha Karma Sena ',
              'url' => ['green-action-units/'],
              'roles' => ['super-admin','admin-lsgi'],
              'permissions' => ['green-action-units-index'],
              'icon' => 'fa fa-dashboard'
            ],
            [
              'label' => 'Public Gathering Methods ',
              'url' => ['public-gathering-methods/'],
              'roles' => ['super-admin','admin-lsgi'],
              'permissions' => ['public-gathering-methods-index'],
              'icon' => 'fa fa-dashboard'
            ],
           [
              'label' => 'Waste Collection Method ',
              'url' => ['waste-collection-methods/'],
              'roles' => ['super-admin','admin-lsgi'],
              'permissions' => ['waste-collection-methods-index'],
              'icon' => 'fa fa-dashboard'
            ],
            [
              'label' => 'Building Type ',
              'url' => ['building-types/'],
              'roles' => ['super-admin','admin-lsgi'],
              'permissions' => ['building-types-index'],
              'icon' => 'fa fa-dashboard'
            ],
            [
              'label' => 'Trading Type ',
              'url' => ['trading-types/'],
              'roles' => ['super-admin','admin-lsgi'],
              'permissions' => ['trading-types-index'],
              'icon' => 'fa fa-dashboard'
            ],
            [
              'label' => 'Shop Type ',
              'url' => ['shop-types/'],
              'roles' => ['super-admin','admin-lsgi'],
              'permissions' => ['shop-types-index'],
              'icon' => 'fa fa-dashboard'
            ],
            [
              'label' => 'Fee Collection interval ',
              'url' => ['fee-collection-interval/'],
              'roles' => ['super-admin','admin-lsgi'],
              'permissions' => ['fee-collection-interval-index'],
              'icon' => 'fa fa-dashboard'
            ],
            [
              'label' => 'Terrace Farming Help Type ',
              'url' => ['terrace-farming-help-types/'],
              'roles' => ['super-admin','admin-lsgi'],
              'permissions' => ['terrace-farming-help-types-index'],
              'icon' => 'fa fa-dashboard'
            ],
            [
              'label' => 'Services ',
              'url' => ['services/'],
              'roles' => ['super-admin','admin-lsgi'],
              'permissions'=>['services-index'],
              'icon' => 'fa fa-dashboard'
            ],
            [
              'label' => 'Bundled Services ',
              'url' => ['bundled-services/'],
              'roles' => ['super-admin','admin-lsgi'],
              'permissions' => ['bundled-services-index'],
              'icon' => 'fa fa-dashboard'
            ],
            [
              'label' => 'Complaints  ',
              'url' => ['complaints/index'],
              'roles' => ['super-admin','admin-lsgi'],
              'permissions' => ['complaints-index'],
              'icon' => 'fa fa-dashboard'
            ],
            [
              'label' => 'Adminstration Types ',
              'url' => ['administration-type/index'],
              'roles' => ['super-admin','admin-lsgi'],
              'permissions' => ['administration-type-index'],
              'icon' => 'fa fa-dashboard'
            ],
            ]
            ],
            [
              'label' => 'Camera Surveillance',
              'icon' => 'fa fa-book',
              'roles' => ['super-admin','admin-lsgi','admin-hks','supervisor','camera-monitoring-admin'],
              'permissions' => [
                'dashboard-index','camera-index','camera-qr-code-index','incidents-index',
                'monitoring-groups-index','incident-reports-index','memo-payment-reports-index',
                'payment-counter-reports-index','outstanding-payment-reports-index',
                'monitoring-group-users-index','memos-index','memo-types-index','memo-penalties-index',
                'lsgi-authorized-signatories-index','payment-counter'
              ],
              'children' => [
                [
                  'label' => 'Dashboard',
                  'url' => ['dashboard/'],
                  'roles' => ['super-admin','admin-lsgi','admin-hks','supervisor','camera-monitoring-admin'],
                  'permissions' => ['dashboard-index'],
                  'icon' => 'fa fa-dashboard'
                ],
                [
                  'label' => 'Camera List',
                  'url' => ['camera/index'],
                  'roles' => ['super-admin','admin-lsgi','camera-monitoring-admin'],
                  'permissions' => ['camera-index'],
                  'icon' => 'fa fa-dashboard'
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
                  'icon' => 'fa fa-dashboard'
                ],
                [
                  'label' => 'Incident List',
                  'url' => ['incidents/index'],
                  'roles' => ['super-admin','admin-lsgi','camera-monitoring-admin'],
                  'permissions' => ['incidents-index'],
                  'icon' => 'fa fa-dashboard'
                ],
                [
                  'label' => 'Monitoring Groups',
                  'url' => ['monitoring-groups/'],
                  'roles' => ['super-admin','admin-lsgi','camera-monitoring-admin'],
                  'permissions' => ['monitoring-groups-index'],
                  'icon' => 'fa fa-dashboard'
                ],
                 [
                  'label' => 'Reports',
                  'roles' => ['super-admin','admin-lsgi','camera-monitoring-admin'],
                  'permissions' => [
                    'incident-reports-index','memo-payment-reports-index','payment-counter-reports-index',
                    'outstanding-payment-reports-index'
                  ],
                  'icon' => 'fa fa-book',
                    'children' => [
                      [
                        'label' => 'Incident Reports',
                        'url' => ['incident-reports/index'],
                        'roles' => ['super-admin','admin-lsgi','camera-monitoring-admin'],
                        'permissions' => ['incident-reports-index'],
                        'icon' => 'fa fa-dashboard'
                      ],
                      [
                        'label' => 'Memo Payment Reports',
                        'url' => ['memo-payment-reports/index'],
                        'roles' => ['super-admin','admin-lsgi','camera-monitoring-admin'],
                        'permissions' => ['memo-payment-reports-index'],
                        'icon' => 'fa fa-dashboard'
                      ],
                      [
                        'label' => 'Counterwise Reports',
                        'url' => ['payment-counter-reports/index'],
                        'roles' => ['super-admin','admin-lsgi','camera-monitoring-admin'],
                        'permissions' => ['payment-counter-reports-index'],
                        'icon' => 'fa fa-dashboard'
                      ],
                      [
                        'label' => 'Outstanding Payment Reports',
                        'url' => ['outstanding-payment-reports/index'],
                        'roles' => ['super-admin','admin-lsgi','camera-monitoring-admin'],
                        'permissions' => ['outstanding-payment-reports-index'],
                        'icon' => 'fa fa-dashboard'
                      ],
                    ],
                ],
                [
                  'label' => 'Users',
                  'url' => ['monitoring-group-users/'],
                  'roles' => ['super-admin','admin-lsgi','camera-monitoring-admin'],
                  'permissions' => ['monitoring-group-users-index'],
                  'icon' => 'fa fa-dashboard'
                ],
                [
                  'label' => 'Memos',
                  'url' => ['memos/index'],
                  'roles' => ['super-admin','admin-lsgi','camera-monitoring-admin'],
                  'permissions' => ['memos-index'],
                  'icon' => 'fa fa-dashboard'
                ],
                [
                  'label' => 'Memo Type',
                  'url' => ['memo-types/'],
                  'roles' => ['super-admin','admin-lsgi','camera-monitoring-admin'],
                  'permissions' => ['memo-types-index'],
                  'icon' => 'fa fa-dashboard'
                ],
                [
                  'label' => 'Memo Penalty',
                  'url' => ['memo-penalties/'],
                  'roles' => ['super-admin','admin-lsgi','camera-monitoring-admin'],
                  'permissions' => ['memo-penalties-index'],
                  'icon' => 'fa fa-dashboard'
                ],
                [
                  'label' => 'Lsgi Authorized Signatory',
                  'url' => ['lsgi-authorized-signatories/'],
                  'roles' => ['super-admin','admin-lsgi','camera-monitoring-admin'],
                  'permissions' => ['lsgi-authorized-signatories-index'],
                  'icon' => 'fa fa-dashboard'
                ],
                [
                  'label' => 'Payment Counter',
                  'url' => ['payment-counter/'],
                  'roles' => ['super-admin','admin-lsgi','camera-monitoring-admin'],
                  'permissions' => ['payment-counter-index'],
                  'icon' => 'fa fa-dashboard'
                ],
                [
                  'label' => 'Camera Service',
                  'url' => ['camera-service/index'],
                  'roles' => ['super-admin','admin-lsgi','camera-monitoring-admin'],
                  'permissions' => ['camera-service-index'],
                  'icon' => 'fa fa-dashboard'
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
              'icon' => 'fa fa-dashboard'
            ],
            [
              'label' => 'Residential association',
              'url' => ['residential-association/'],
              'roles' => ['super-admin','admin-lsgi','camera-monitoring-admin'],
              'permissions' => ['residential-association-index'],
              'icon' => 'fa fa-dashboard'
            ],
            [
              'label' => 'Association type',
              'url' => ['association-type/'],
              'roles' => ['super-admin','admin-lsgi','camera-monitoring-admin'],
              'permissions' => ['association-type-index'],
              'icon' => 'fa fa-dashboard'
            ],
            [
              'label' => 'Installation checklist',
              'url' => ['installation-checklist/'],
              'roles' => ['super-admin','admin-lsgi','camera-monitoring-admin'],
              'permissions' => ['installation-checklist-index'],
              'icon' => 'fa fa-dashboard'
            ],
            [
              'label' => 'Access controller',
              'roles' => ['super-admin'],
              'permissions' => ['rbac-controllers-index','rbac-roles-index','rbac-users-index'],
              'icon' => 'fa fa-book',
              'children' => [
                [
                  'label' => 'Controllers',
                  'url' => ['rbac/controllers-index'],
                  'permissions' => ['rbac-controllers-index'],
                  'roles' => ['super-admin'],
                  'icon' => 'fa fa-dashboard'
                ],
                [
                  'label' => 'Roles',
                  'url' => ['rbac/roles-index'],
                  'roles' => ['super-admin'],
                  'permissions' => ['rbac-roles-index'],
                  'icon' => 'fa fa-dashboard'
                ],
                [
                  'label' => 'Users',
                  'url' => ['rbac/users-index'],
                  'roles' => ['super-admin'],
                  'permissions' => ['rbac-users-index'],
                  'icon' => 'fa fa-dashboard'
                ],
              ],
            ],
            [
              'label' => 'Logout',
              'url' => ['account/logout'],
              'roles' => ['super-admin','admin-lsgi','admin-hks','coordinator','supervisor','surveyor','camera-monitoring-admin'],
              'permissions' => ['Account-logout'],
              'icon' => 'fa fa-sign-out'
            ],
          ];

          $linksWastemanagement = [
          [
                  'label' => 'Dashboard',
                  'url' => ['wms-dashboard/'],
                  'roles' => ['super-admin','admin-lsgi','admin-hks','coordinator','supervisor'],
                  'permissions' => ['wms-dashboard-index'],
                  'icon' => 'fa fa-dashboard'
                ],
          [

              'label' => 'Users',
              'icon' => 'fa fa-book',
               'roles' => ['super-admin','admin-lsgi','coordinator','admin-hks','supervisor'],
               'permissions' => [
                 'Account-super-admin','Account-index','Account-admin-lsgi','Account-admin-hks',
                 'Account-supervisors','Account-green-technicians','Account-coordinators'
               ],
              'children' => [
               [
              'label' => 'Super Admin',
              'url' => ['account/super-admin'],
               'roles' => ['super-admin'],
               'permissions' => ['Account-super-admin'],
              'icon' => 'fa fa-dashboard'
            ],
                [
              'label' => 'Surveyor',
              'url' => ['account/index'],
               'roles' => ['super-admin','admin-lsgi','coordinator'],
               'permissions' => ['Account-index'],
              'icon' => 'fa fa-dashboard'
            ],
                [
              'label' => 'Lsgi Admins',
              'url' => ['account/admin-lsgi'],
               'roles' => ['super-admin'],
               'permissions' => ['Account-admin-lsgi'],
              'icon' => 'fa fa-dashboard'
            ],
            [
              'label' => 'Hks Admins ',
              'url' => ['account/admin-hks'],
               'roles' => ['super-admin','admin-lsgi'],
               'permissions' => ['Account-admin-hks'],
              'icon' => 'fa fa-dashboard'
            ],
            [
              'label' => 'Supervisors ',
              'url' => ['account/supervisors'],
               'roles' => ['super-admin','admin-lsgi','admin-hks'],
               'permissions' => ['Account-supervisors'],
              'icon' => 'fa fa-dashboard'
            ],
            [
              'label' => 'Green Technicians ',
              'url' => ['account/green-technicians'],
               'roles' => ['super-admin','admin-lsgi','admin-hks','supervisor'],
               'permissions' => ['Account-green-technicians'],
              'icon' => 'fa fa-dashboard'
            ],
            [
              'label' => 'Survey Coordinator ',
              'url' => ['account/coordinators'],
              'roles' => ['super-admin','admin-lsgi'],
              'permissions' => ['Account-coordinators'],
              'icon' => 'fa fa-dashboard'
            ],
              ]
            ],
            [
              'label' => 'Assign Supervisor ',
              'url' => ['account-supervisor/index'],
              'roles' => ['super-admin','admin-lsgi','admin-hks'],
              'permissions' => [
                'account-supervisor-index'
              ],
              'icon' => 'fa fa-dashboard'
            ],
            [
              'label' => 'Assign Gt ',
              'url' => ['account-gt/index'],
              'roles' => ['super-admin','admin-lsgi','admin-hks','supervisor'],
              'permissions' => [
                'account-gt-index'
              ],
              'icon' => 'fa fa-dashboard'
            ],
            [
              'label' => 'Schedule ',
              'url' => ['schedule/index'],
              'roles' => ['super-admin','supervisor'],
              'permissions' => ['schedule-index'],
              'icon' => 'fa fa-dashboard'
            ],
            [
              'label' => 'QR Code ',
              'url' => ['qr-code/'],
               'roles' => ['super-admin','admin-lsgi'],
               'permissions' => ['qr-code-index'],
              'icon' => 'fa fa-dashboard'
            ],
            [
              'label' => 'Customers  ',
              'url' => ['customers/index'],
                'roles' => ['super-admin','admin-lsgi','admin-hks','supervisor','coordinator'],
                'permissions' => ['Customers-index'],
              'icon' => 'fa fa-dashboard'
            ],
            [
              'label' => 'Account Service Requests  ',
              'url' => ['account-service-requests/index'],
                'roles' => ['super-admin','admin-lsgi','admin-hks','supervisor'],
                'permissions' => ['account-service-requests-index'],
              'icon' => 'fa fa-dashboard'
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
              'icon' => 'fa fa-dashboard'
            ],
            [
              'label' => 'Payments  ',
              'url' => ['payments/index'],
                'roles' => ['super-admin','admin-lsgi','admin-hks','supervisor'],
                'permissions' => ['payments-index'],
              'icon' => 'fa fa-dashboard'
            ],
            [
              'label' => 'Service Requests  ',
              'url' => ['service-requests/index'],
                'roles' => ['super-admin','admin-lsgi','admin-hks','supervisor'],
                'permissions' => ['service-requests-index'],
              'icon' => 'fa fa-dashboard'
            ],
            [
              'label' => 'Complaints  ',
              'url' => ['requests/complaints'],
               'roles' => ['super-admin','admin-lsgi','admin-hks','supervisor'],
               'permissions' => ['requests-complaints'],
              'icon' => 'fa fa-dashboard'
            ],
            [
              'label' => 'Reports',
              'icon' => 'fa fa-book',
               'roles' => ['super-admin','admin-lsgi','admin-hks','coordinator'],
               'permissions' => [
                 'reports-door-close','reports-survey-count','reports-waste-management-type',
                 'reports-ward-wise-count'
               ],
              'children' => [
               [
              'label' => 'Door Close Report',
              'url' => ['reports/door-close'],
               'roles' => ['super-admin','admin-lsgi','coordinator'],
               'permissions' => ['reports-door-close'],
              'icon' => 'fa fa-dashboard'
            ],
                [
              'label' => 'Survey Count Report',
              'url' => ['reports/survey-count'],
               'roles' => ['super-admin','admin-lsgi','coordinator'],
               'permissions' => ['reports-survey-count'],
              'icon' => 'fa fa-dashboard'
            ],
            [
              'label' => 'Waste Management Type Report',
              'url' => ['reports/waste-management-type'],
               'roles' => ['super-admin','admin-lsgi','admin-hks','supervisor'],
               'permissions' => ['reports-waste-management-type'],
              'icon' => 'fa fa-dashboard'
            ],
            [
              'label' => 'Ward Wise Count Report',
              'url' => ['reports/ward-wise-count'],
               'roles' => ['super-admin','admin-lsgi','coordinator'],
               'permissions' => ['reports-ward-wise-count'],
              'icon' => 'fa fa-dashboard'
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
                'waste-categories-index',
                'green-action-units-index','public-gathering-methods-index',
                'waste-collection-methods-index','building-types-index',
                'trading-types-index','shop-types-index','fee-collection-interval-index',
                'terrace-farming-help-types-index','services-index',
                'complaints-index','administration-type-index',
              ],
              'icon' => 'fa fa-book',
              'children' => [
                [
              'label' => 'State',
              'url' => ['states/'],
              'roles' => ['super-admin'],
              'permissions' => ['states-index'],
              'icon' => 'fa fa-dashboard'
            ],
            [
              'label' => 'District',
              'url' => ['districts/'],
              'roles' => ['super-admin'],
              'permissions' => ['districts-index'],
              'icon' => 'fa fa-dashboard'
            ],
            [
              'label' => 'Parliament Constituency',
              'url' => ['parliament-constituency/'],
              'roles' => ['super-admin'],
              'permissions' => ['parliament-constituency-index'],
              'icon' => 'fa fa-dashboard'
            ],
             [
              'label' => 'Assembly Constituency',
              'url' => ['assembly-constituency/'],
              'roles' => ['super-admin'],
              'permissions' => ['assembly-constituency-index'],
              'icon' => 'fa fa-dashboard'
            ],
            [
              'label' => 'lsgi Type',
              'url' => ['lsgi-type/'],
              'roles' => ['super-admin','admin-lsgi'],
              'permissions'=>['lsgi-type-index'],
              'icon' => 'fa fa-dashboard'
            ],
            [
              'label' => 'Lsgi Block ',
              'url' => ['lsgi-blocks/'],
              'roles' => ['super-admin','admin-lsgi'],
              'permissions' => ['lsgi-blocks-index'],
              'icon' => 'fa fa-dashboard'
            ],
            [
              'label' => 'Lsgi ',
              'url' => ['lsgis/'],
              'roles' => ['super-admin','admin-lsgi'],
              'permissions' => ['lsgis-index'],
              'icon' => 'fa fa-dashboard'
            ],
            [
              'label' => 'Ward ',
              'url' => ['wards/'],
              'roles' => ['super-admin','admin-lsgi'],
              'permissions' => ['wards-index'],
              'icon' => 'fa fa-dashboard'
            ],
            [
              'label' => 'Residence Categories ',
              'url' => ['residence-categories/index'],
              'roles' => ['super-admin','admin-lsgi'],
              'permissions' => ['residence-categories-index'],
              'icon' => 'fa fa-dashboard'
            ],
            [
              'label' => 'Waste Category ',
              'url' => ['waste-categories/'],
              'roles' => ['super-admin','admin-lsgi'],
              'permissions' => ['waste-categories-index'],
              'icon' => 'fa fa-dashboard'
            ],
           [
              'label' => 'Haritha Karma Sena ',
              'url' => ['green-action-units/'],
              'roles' => ['super-admin','admin-lsgi'],
              'permissions' => ['green-action-units-index'],
              'icon' => 'fa fa-dashboard'
            ],
            [
              'label' => 'Public Gathering Methods ',
              'url' => ['public-gathering-methods/'],
              'roles' => ['super-admin','admin-lsgi'],
              'permissions' => ['public-gathering-methods-index'],
              'icon' => 'fa fa-dashboard'
            ],
           [
              'label' => 'Waste Collection Method ',
              'url' => ['waste-collection-methods/'],
              'roles' => ['super-admin','admin-lsgi'],
              'permissions' => ['waste-collection-methods-index'],
              'icon' => 'fa fa-dashboard'
            ],
            [
              'label' => 'Building Type ',
              'url' => ['building-types/'],
              'roles' => ['super-admin','admin-lsgi'],
              'permissions' => ['building-types-index'],
              'icon' => 'fa fa-dashboard'
            ],
            [
              'label' => 'Trading Type ',
              'url' => ['trading-types/'],
              'roles' => ['super-admin','admin-lsgi'],
              'permissions' => ['trading-types-index'],
              'icon' => 'fa fa-dashboard'
            ],
            [
              'label' => 'Shop Type ',
              'url' => ['shop-types/'],
              'roles' => ['super-admin','admin-lsgi'],
              'permissions' => ['shop-types-index'],
              'icon' => 'fa fa-dashboard'
            ],
            [
              'label' => 'Fee Collection interval ',
              'url' => ['fee-collection-interval/'],
              'roles' => ['super-admin','admin-lsgi'],
              'permissions' => ['fee-collection-interval-index'],
              'icon' => 'fa fa-dashboard'
            ],
            [
              'label' => 'Terrace Farming Help Type ',
              'url' => ['terrace-farming-help-types/'],
              'roles' => ['super-admin','admin-lsgi'],
              'permissions' => ['terrace-farming-help-types-index'],
              'icon' => 'fa fa-dashboard'
            ],
            [
              'label' => 'Services ',
              'url' => ['services/'],
              'roles' => ['super-admin','admin-lsgi'],
              'permissions'=>['services-index'],
              'icon' => 'fa fa-dashboard'
            ],
            [
              'label' => 'Complaints  ',
              'url' => ['complaints/index'],
              'roles' => ['super-admin','admin-lsgi'],
              'permissions' => ['complaints-index'],
              'icon' => 'fa fa-dashboard'
            ],
            [
              'label' => 'Adminstration Types ',
              'url' => ['administration-type/index'],
              'roles' => ['super-admin','admin-lsgi'],
              'permissions' => ['administration-type-index'],
              'icon' => 'fa fa-dashboard'
            ],
            ]
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
              'roles' => ['super-admin','admin-lsgi','admin-hks','coordinator','supervisor','surveyor'],
              'permissions' => ['Account-logout'],
              'icon' => 'fa fa-sign-out'
            ],
          ];
          $linksCamera = [
          [
                  'label' => 'Dashboard',
                  'url' => ['wms-dashboard/'],
                  'roles' => ['super-admin','admin-lsgi','admin-hks','coordinator','supervisor','camera-monitoring-admin'],
                  'permissions' => ['wms-dashboard-index'],
                  'icon' => 'fa fa-dashboard'
                ],
          [

              'label' => 'Users',
              'icon' => 'fa fa-book',
              'roles' => ['super-admin','admin-lsgi','admin-hks','coordinator','supervisor'],
              'permissions' => [
                'Account-super-admin','Account-index','Account-admin-lsgi','Account-admin-hks',
                'Account-supervisors','Account-green-technicians','Account-coordinators'
              ],
              'children' => [
               [
              'label' => 'Super Admin',
              'url' => ['account/super-admin'],
              'roles' => ['super-admin'],
              'permissions' => ['Account-super-admin'],
              'icon' => 'fa fa-dashboard'
            ],
                [
              'label' => 'Surveyor',
              'url' => ['account/index'],
              'roles' => ['super-admin','admin-lsgi','coordinator'],
              'permissions' => ['Account-index'],
              'icon' => 'fa fa-dashboard'
            ],
                [
              'label' => 'Lsgi Admins',
              'url' => ['account/admin-lsgi'],
              'roles' => ['super-admin'],
              'permissions' => ['Account-admin-lsgi'],
              'icon' => 'fa fa-dashboard'
            ],
            [
              'label' => 'Hks Admins ',
              'url' => ['account/admin-hks'],
              'roles' => ['super-admin','admin-lsgi'],
              'permissions' => ['Account-admin-hks'],
              'icon' => 'fa fa-dashboard'
            ],
            [
              'label' => 'Supervisors ',
              'url' => ['account/supervisors'],
              'roles' => ['super-admin','admin-lsgi','admin-hks'],
              'permissions' => ['Account-supervisors'],
              'icon' => 'fa fa-dashboard'
            ],
            [
              'label' => 'Green Technicians ',
              'url' => ['account/green-technicians'],
              'roles' => ['super-admin','admin-lsgi','admin-hks','supervisor'],
              'permissions' => ['Account-green-technicians'],
              'icon' => 'fa fa-dashboard'
            ],
            [
              'label' => 'Survey Coordinator ',
              'url' => ['account/coordinators'],
              'roles' => ['super-admin','admin-lsgi'],
              'permissions' => ['Account-coordinators'],
              'icon' => 'fa fa-dashboard'
            ],
              ]
            ],
            [
              'label' => 'QR Code ',
              'url' => ['qr-code/'],
             'roles' => ['super-admin','admin-lsgi'],
             'permissions' => ['qr-code-index'],
              'icon' => 'fa fa-dashboard'
            ],
            [
              'label' => 'Customers  ',
              'url' => ['customers/index'],
               'roles' => ['super-admin','admin-lsgi','admin-hks','supervisor','coordinator'],
               'permissions' => ['Customers-index'],
              'icon' => 'fa fa-dashboard'
            ],
            [
              'label' => 'Account Service Requests  ',
              'url' => ['account-service-requests/index'],
              'roles' => ['super-admin','admin-lsgi','admin-hks','supervisor'],
              'permissions' => ['account-service-requests-index'],
              'icon' => 'fa fa-dashboard'
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
              'icon' => 'fa fa-dashboard'
            ],
            [
              'label' => 'Payments  ',
              'url' => ['payments/index'],
               'roles' => ['super-admin','admin-lsgi','admin-hks','supervisor'],
               'permissions' => ['payments-index'],
              'icon' => 'fa fa-dashboard'
            ],
            [
              'label' => 'Service Requests  ',
              'url' => ['service-requests/index'],
               'roles' => ['super-admin','admin-lsgi','admin-hks','supervisor'],
               'permissions' => ['service-requests-index'],
              'icon' => 'fa fa-dashboard'
            ],
            [
              'label' => 'Complaints  ',
              'url' => ['requests/complaints'],
              'roles' => ['super-admin','admin-lsgi','admin-hks','supervisor'],
              'permissions' => ['requests-complaints'],
              'icon' => 'fa fa-dashboard'
            ],
           [
              'label' => 'Reports',
              'icon' => 'fa fa-book',
               'roles' => ['super-admin','admin-lsgi','admin-hks','coordinator'],
               'permissions' => [
                 'reports-door-close','reports-survey-count','reports-waste-management-type',
                 'reports-ward-wise-count'
               ],
              'children' => [
               [
              'label' => 'Door Close Report',
              'url' => ['reports/door-close'],
               'roles' => ['super-admin','admin-lsgi','coordinator'],
               'permissions' => ['reports-door-close'],
              'icon' => 'fa fa-dashboard'
            ],
                [
              'label' => 'Survey Count Report',
              'url' => ['reports/survey-count'],
               'roles' => ['super-admin','admin-lsgi','coordinator'],
               'permissions' => ['reports-survey-count'],
              'icon' => 'fa fa-dashboard'
            ],
            [
              'label' => 'Waste Management Type Report',
              'url' => ['reports/waste-management-type'],
               'roles' => ['super-admin','admin-lsgi','admin-hks','supervisor'],
               'permissions' => ['reports-waste-management-type'],
              'icon' => 'fa fa-dashboard'
            ],
            [
              'label' => 'Ward Wise Count Report',
              'url' => ['reports/ward-wise-count'],
               'roles' => ['super-admin','admin-lsgi','coordinator'],
               'permissions' => ['reports-ward-wise-count'],
              'icon' => 'fa fa-dashboard'
            ],

              ]
            ],
            [
              'label' => 'Camera Surveillance',
              'icon' => 'fa fa-book',
              'roles' => ['super-admin','admin-lsgi','admin-hks','supervisor','camera-monitoring-admin'],
              'permissions' => [
                'dashboard-index','camera-index','camera-qr-code-index','incidents-index',
                'monitoring-groups-index','incident-reports-index','memo-payment-reports-index',
                'payment-counter-reports-index','outstanding-payment-reports-index',
                'monitoring-group-users-index','memos-index','memo-types-index','memo-penalties-index',
                'lsgi-authorized-signatories-index','payment-counter','payment-counter-assign-counter-admin'
              ],
              'children' => [
                [
                  'label' => 'Dashboard',
                  'url' => ['dashboard/'],
                  'roles' => ['super-admin','admin-lsgi','admin-hks','supervisor','camera-monitoring-admin'],
                  'permissions' => ['dashboard-index'],
                  'icon' => 'fa fa-dashboard'
                ],
                [
                  'label' => 'Camera List',
                  'url' => ['camera/index'],
                  'roles' => ['super-admin','admin-lsgi','camera-monitoring-admin'],
                  'permissions' => ['camera-index'],
                  'icon' => 'fa fa-dashboard'
                ],
                [
                  'label' => 'QR Code ',
                  'url' => ['camera-qr-code/'],
                  'roles' => ['super-admin','admin-lsgi','camera-monitoring-admin'],
                  'permissions' => ['camera-qr-code-index'],
                  'icon' => 'fa fa-dashboard'
                ],
                [
                  'label' => 'Incident List',
                  'url' => ['incidents/index'],
                  'roles' => ['super-admin','admin-lsgi','camera-monitoring-admin'],
                  'permissions' => ['incidents-index'],
                  'icon' => 'fa fa-dashboard'
                ],
                [
                  'label' => 'Monitoring Groups',
                  'url' => ['monitoring-groups/'],
                  'roles' => ['super-admin','admin-lsgi','camera-monitoring-admin'],
                  'permissions' => ['monitoring-groups-index'],
                  'icon' => 'fa fa-dashboard'
                ],
                 [
                  'label' => 'Reports',
                  // 'url' => ['incident-reports/index'],
                  'roles' => ['super-admin','admin-lsgi','camera-monitoring-admin'],
                  'permissions' => [
                    'incident-reports-index','memo-payment-reports-index','payment-counter-reports-index',
                    'outstanding-payment-reports-index'
                  ],
                  'icon' => 'fa fa-book',
                  'children' => [
                    [
                      'label' => 'Incident Reports',
                      'url' => ['incident-reports/index'],
                      'roles' => ['super-admin','admin-lsgi','camera-monitoring-admin'],
                      'permissions' => ['incident-reports-index'],
                      'icon' => 'fa fa-dashboard'
                    ],
                    [
                      'label' => 'Memo Payment Reports',
                      'url' => ['memo-payment-reports/index'],
                      'roles' => ['super-admin','admin-lsgi','camera-monitoring-admin'],
                      'permissions' => ['memo-payment-reports-index'],
                      'icon' => 'fa fa-dashboard'
                    ],
                    [
                      'label' => 'Counterwise Reports',
                      'url' => ['payment-counter-reports/index'],
                      'roles' => ['super-admin','admin-lsgi','camera-monitoring-admin'],
                      'permissions' => ['payment-counter-reports-index'],
                      'icon' => 'fa fa-dashboard'
                    ],
                    [
                      'label' => 'Outstanding Payment Reports',
                      'url' => ['outstanding-payment-reports/index'],
                      'roles' => ['super-admin','admin-lsgi','camera-monitoring-admin'],
                      'permissions' => ['outstanding-payment-reports-index'],
                      'icon' => 'fa fa-dashboard'
                    ],
                  ],
                ],
                [
                  'label' => 'Users',
                  'url' => ['monitoring-group-users/'],
                  'roles' => ['super-admin','admin-lsgi','camera-monitoring-admin'],
                  'permissions' => ['monitoring-group-users-index'],
                  'icon' => 'fa fa-dashboard'
                ],
                [
                  'label' => 'Memos',
                  'url' => ['memos/index'],
                  'roles' => ['super-admin','admin-lsgi','camera-monitoring-admin'],
                  'permissions' => ['memos-index'],
                  'icon' => 'fa fa-dashboard'
                ],
                [
                  'label' => 'Memo Type',
                  'url' => ['memo-types/'],
                  'roles' => ['super-admin','admin-lsgi','camera-monitoring-admin'],
                  'permissions' => ['memo-types-index'],
                  'icon' => 'fa fa-dashboard'
                ],
                [
                  'label' => 'Memo Penalty',
                  'url' => ['memo-penalties/'],
                  'roles' => ['super-admin','admin-lsgi','camera-monitoring-admin'],
                  'permissions' => ['memo-penalties-index'],
                  'icon' => 'fa fa-dashboard'
                ],
                [
                  'label' => 'Lsgi Authorized Signatory',
                  'url' => ['lsgi-authorized-signatories/'],
                  'roles' => ['super-admin','admin-lsgi','camera-monitoring-admin'],
                  'permissions' => ['lsgi-authorized-signatories-index'],
                  'icon' => 'fa fa-dashboard'
                ],
                [
                  'label' => 'Payment Counters',
                  'url' => ['payment-counter/'],
                  'roles' => ['super-admin','admin-lsgi','camera-monitoring-admin'],
                  'permissions' => ['payment-counter-index'],
                  'icon' => 'fa fa-dashboard'
                ],
                [
                  'label' => 'Camera Service',
                  'url' => ['camera-service/index'],
                  'roles' => ['super-admin','admin-lsgi','camera-monitoring-admin'],
                  'permissions' => ['camera-service-index'],
                  'icon' => 'fa fa-dashboard'
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
              'icon' => 'fa fa-dashboard'
            ],
            [
              'label' => 'Residential association',
              'url' => ['residential-association/'],
              'roles' => ['super-admin','admin-lsgi','camera-monitoring-admin'],
              'permissions' => ['residential-association-index'],
              'icon' => 'fa fa-dashboard'
            ],
            [
              'label' => 'Association type',
              'url' => ['association-type/'],
              'roles' => ['super-admin','admin-lsgi','camera-monitoring-admin'],
              'permissions' => ['association-type-index'],
              'icon' => 'fa fa-dashboard'
            ],
            [
              'label' => 'Installation checklist',
              'url' => ['installation-checklist/'],
              'roles' => ['super-admin','admin-lsgi','camera-monitoring-admin'],
              'permissions' => ['installation-checklist-index'],
              'icon' => 'fa fa-dashboard'
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
              'icon' => 'fa fa-sign-out'
            ],
          ];

              function createMenu($settings,$currentLevel = 1,$currentUserRole=null) {
                $modelUser  = Yii::$app->user->identity;

                $currentUserRole = $userRole = isset($modelUser->role)?$modelUser->role:'';

                $modelAuthItemChilds = \backend\models\AuthItemChild::find()->where(['parent'=>$currentUserRole])->all();
                $currentUserPermission = [];
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
