<?php
use yii\widgets\Menu;
use backend\models\Person;
use backend\models\AccountBackend;
use backend\models\AccountProvider;
use backend\models\Image;
use yii\helpers\Url;
 ?>
  <div class="navbar-default sidebar" role="navigation">
    <div class="sidebar-nav slimscrollsidebar">
        <div class="sidebar-head">
            <h3><span class="fa-fw open-close"><i class="ti-menu hidden-xs"></i><i class="ti-close visible-xs"></i></span> <span class="hide-menu"> Management</span></h3>
        </div>
          <?php
          $linksSuperAdmin = [
          [
              'label' => 'Users',
              'icon' => 'fa fa-book',
              'children' => [
               [
              'label' => 'Super Admin',
              'url' => ['account/super-admin'],
              'icon' => 'fa fa-dashboard'
            ],
                [
              'label' => 'Surveyor',
              'url' => ['account/index'],
              'icon' => 'fa fa-dashboard'
            ],
                [
              'label' => 'Lsgi Admins',
              'url' => ['account/admin-lsgi'],
              'icon' => 'fa fa-dashboard'
            ],
            [
              'label' => 'Hks Admins ',
              'url' => ['account/admin-hks'],
              'icon' => 'fa fa-dashboard'
            ],
            [
              'label' => 'Supervisors ',
              'url' => ['account/supervisors'],
              'icon' => 'fa fa-dashboard'
            ],
            [
              'label' => 'Green Technicians ',
              'url' => ['account/green-technicians'],
              'icon' => 'fa fa-dashboard'
            ]
              ]
            ],
            [
              'label' => 'QR Code ',
              'url' => ['qr-code/'],
              'icon' => 'fa fa-dashboard'
            ],
            [
              'label' => 'Customers  ',
              'url' => ['customers/index'],
              'icon' => 'fa fa-dashboard'
            ],
            [
              'label' => 'Service Requests  ',
              'url' => ['service-requests/index'],
              'icon' => 'fa fa-dashboard'
            ],
            [
              'label' => 'Complaints  ',
              'url' => ['complaints/index'],
              'icon' => 'fa fa-dashboard'
            ],
            [
              'label' => 'Reports',
              'icon' => 'fa fa-book',
              'children' => [
               [
              'label' => 'Door Close Report',
              'url' => ['reports/door-close'],
              'icon' => 'fa fa-dashboard'
            ],
                [
              'label' => 'Survey Count Report',
              'url' => ['reports/survey-count'],
              'icon' => 'fa fa-dashboard'
            ],
            [
              'label' => 'Waste Managemnt Type Report',
              'url' => ['reports/waste-management-type'],
              'icon' => 'fa fa-dashboard'
            ],
            [
              'label' => 'Ward Wise Count Report',
              'url' => ['reports/ward-wise-count'],
              'icon' => 'fa fa-dashboard'
            ],
           
              ]
            ],
           [
              'label' => 'Preliminary Configuration',
              'icon' => 'fa fa-book',
              'children' => [
                [
              'label' => 'State',
              'url' => ['states/'],
              'icon' => 'fa fa-dashboard'
            ],
            [
              'label' => 'District',
              'url' => ['districts/'],
              'icon' => 'fa fa-dashboard'
            ],
            [
              'label' => 'Parliament Constituency',
              'url' => ['parliament-constituency/'],
              'icon' => 'fa fa-dashboard'
            ],
             [
              'label' => 'Assembly Constituency',
              'url' => ['assembly-constituency/'],
              'icon' => 'fa fa-dashboard'
            ],
            [
              'label' => 'lsgi Type',
              'url' => ['lsgi-type/'],
              'icon' => 'fa fa-dashboard'
            ],
            [
              'label' => 'Lsgi Block ',
              'url' => ['lsgi-blocks/'],
              'icon' => 'fa fa-dashboard'
            ],
            [
              'label' => 'Lsgi ',
              'url' => ['lsgis/'],
              'icon' => 'fa fa-dashboard'
            ],
            [
              'label' => 'Ward ',
              'url' => ['wards/'],
              'icon' => 'fa fa-dashboard'
            ],
            [
              'label' => 'Waste Category ',
              'url' => ['waste-categories/'],
              'icon' => 'fa fa-dashboard'
            ],
           [
              'label' => 'Green Action Unit ',
              'url' => ['green-action-units/'],
              'icon' => 'fa fa-dashboard'
            ],
            [
              'label' => 'Public Gathering Methods ',
              'url' => ['public-gathering-methods/'],
              'icon' => 'fa fa-dashboard'
            ],
           [
              'label' => 'Waste Collection Method ',
              'url' => ['waste-collection-methods/'],
              'icon' => 'fa fa-dashboard'
            ],
            [
              'label' => 'Building Type ',
              'url' => ['building-types/'],
              'icon' => 'fa fa-dashboard'
            ],
            [
              'label' => 'Trading Type ',
              'url' => ['trading-types/'],
              'icon' => 'fa fa-dashboard'
            ],
            [
              'label' => 'Shop Type ',
              'url' => ['shop-types/'],
              'icon' => 'fa fa-dashboard'
            ],
            [
              'label' => 'Fee Collection interval ',
              'url' => ['fee-collection-interval/'],
              'icon' => 'fa fa-dashboard'
            ], 
            [
              'label' => 'Terrace Farming Help Type ',
              'url' => ['terrace-farming-help-types/'],
              'icon' => 'fa fa-dashboard'
            ],
            [
              'label' => 'Service ',
              'url' => ['services/'],
              'icon' => 'fa fa-dashboard'
            ],
            [
              'label' => 'Adminstration Types ',
              'url' => ['administration-type/index'],
              'icon' => 'fa fa-dashboard'
            ],
            ]
            ],
            [
              'label' => 'Logout',
              'url' => ['account/logout'],
              'icon' => 'fa fa-sign-out'
            ],
          ];

              function createMenu($settings,$currentLevel = 1) {
                $listTags = "";
                $nextLevel = $currentLevel+1;
                $isActiveUl = false;
                foreach($settings as $setting) {
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

                    $rt = createMenu($setting['children'],$nextLevel);

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

              // $user_id = Yii::$app->user->identity->id;
              // $role_user = \Yii::$app->authManager->getRolesByUser($user_id);
              // $role =  array_shift($role_user)->name;
              $links = ['super-admin' => $linksSuperAdmin];
              // $link = $links[$role];
              $link = $links['super-admin'];
              echo createMenu($link)['html'];

              ?>

    </div>
</div>
