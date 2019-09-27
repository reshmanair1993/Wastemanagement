<?php

namespace api\modules\v1\controllers;

use Yii;
use api\modules\v1\models\AuthItem;
use api\modules\v1\models\AuthAssignment;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\web\UploadedFile;
use yii\data\ActiveDataProvider;
use api\modules\v1\models\Account;

/**
 * CategoriesController implements the CRUD actions for Categories model.
 */
class RbacController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['get'],
                ],
            ],
        ];
    }
    public $gtRole = 'green-technician';
    // public $gropAdminRole = 'Group Admin';
    // public $manager = 'Manager';
    // public $groupManager = 'Group Manager';

    /**
     * Lists all Categories models.
     * @return mixed
     */
     public function init() {
       $auth = Yii::$app->authManager;

       $this->createGtRole($auth);
       // $this->createGroupAdminRole($auth);
       // $this->createManagerRole($auth);
       // $this->createGroupManagerRole($auth);
     }
    public function getCurrentUser() {
      $currentUser = Yii::$app->loginComponent->getLoggedInUser();
      return $currentUser;
    }
     public function actionIndex() {
     $auth = Yii::$app->authManager;
     $admin = $auth->getRole("green-technician");
     // $groupAdmin = $auth->getRole("Group Admin");
     $all = $auth->createPermission('all');
      $auth->add($all);
      $auth->addChild($admin, $all);
      // $auth->addChild($groupAdmin, $all);
     }
     public function actionRoles() {
       $model =  new AuthItem;
       $query = AuthItem::find()->where(['type'=>1]);
   		 $this->view->params['hasCreate'] = true;
   		 $this->view->params['createBtnTitle'] = 'Add new role';
   		 $this->view->params['createBtnAction'] = 'create-role';


       $dataProvider = new ActiveDataProvider([
           'query' => $query,
           'sort'=> ['defaultOrder' => ['name'=>$this->superAdminRole,'updated_at' => SORT_DESC]],
       ]);

       $params = [
         'dataProvider' => $dataProvider,
         'model' => $model,
         'superAdminRole' => $this->superAdminRole
       ];
       return $this->render('roles',[
         'params' => $params
       ]);

     }
     public function setJobsPanelPermissions($authManager ) {
       $sections = [
       ];
       foreach( $sections as $section ) {
         $fnName = ucFirst($section);
         $this->{"set".$fnName}($authManager);

       }
     }


     public function createNormalUserRole($authManager) {
       $roleName = $this->normalUserRole;
       if(!($normalUserRole = $authManager->getRole($roleName))) {
         $normalUserRole = $authManager->createRole($roleName);
         $authManager->add($normalUserRole);
       }
     }
     public function createAffiliateUserRole($authManager) {
       $roleName = $this->affilitateUserRole;
       if(!($affilitateUserRole = $authManager->getRole($roleName))) {
         $affilitateUserRole = $authManager->createRole($roleName);
         $authManager->add($affilitateUserRole);
       } 
	   $permissions = Yii::$app->params['affiliatePermissions'];
	   foreach($permissions as $section => $permissionData) {
		   
       $this->addPermissions($authManager,$permissionData);
		foreach($permissionData as $permissionName => $desr) {
			$permission = $authManager->getPermission($permissionName); 
			if($permission&&!$authManager->hasChild($affilitateUserRole, $permission))
				$authManager->addChild($affilitateUserRole, $permission);
			 
		}
	   }

     }
     public function createGtRole($authManager) {
       $roleName = $this->gtRole;
       if(!($admin = $authManager->getRole($roleName))) {
         $admin = $authManager->createRole($roleName);
         $authManager->add($admin);
       }
     }
     // public function createGroupAdminRole($authManager) {
     //   $roleName = $this->gropAdminRole;
     //   if(!($admin = $authManager->getRole($roleName))) {
     //     $admin = $authManager->createRole($roleName);
     //     $authManager->add($admin);
     //   }
     // }
     // public function createManagerRole($authManager) {
     //   $roleName = $this->manager;
     //   if(!($admin = $authManager->getRole($roleName))) {
     //     $admin = $authManager->createRole($roleName);
     //     $authManager->add($admin);
     //   }
     // }
     // public function createGroupManagerRole($authManager) {
     //   $roleName = $this->groupManager;
     //   if(!($admin = $authManager->getRole($roleName))) {
     //     $admin = $authManager->createRole($roleName);
     //     $authManager->add($admin);
     //   }
     // }
     public function isGtRole($roleName) {
       return $roleName == $this->gtRole;
     }
     public function isAffiliateUserRole($roleName) {
       return $roleName == $this->affilitateUserRole;
     }
     public function isNormalUserRole($roleName) {
       return $roleName == $this->normalUserRole;
     }
     public function addPermissions($authManager,$permissionNames) {

       $auth = $authManager;
       $rulesToAdd = [];
       foreach($permissionNames as $permission) {
        $permissionName = $permission;
        if(is_array($permission)) {
          $permissionName = $permission['name'];
          if(isset($permission['rules'])) {
            $rules = $permission['rules'];
            foreach($rules as $rule) {
              $ruleObj =  new $rule;
              $auth->add($ruleObj);
              $rulesToAdd[] = $ruleObj;
            }
          }
        }   
         if(!$this->permissionExists($permissionName)) {
           $permission = $auth->createPermission($permissionName);
           foreach($rulesToAdd as $ruleObj) {
            $permission->ruleName = $ruleObj->name;
           }
           $auth->add($permission);
          }
       }
     }
     public function permissionExists($permissionName) {
       $query = "SELECT COUNT(*)  as count FROM auth_item WHERE name = :name AND type = 2" ;
       $command =  Yii::$app->db->createCommand($query)->bindParam(':name',$permissionName);
       $ret = $command->queryOne();
       $count = $ret['count'];
       return $count != 0;
     }
     public function roleExists($roleName) {
       $query = "SELECT COUNT(*)  as count FROM auth_item WHERE name = :name AND type =1" ;
       $command =  Yii::$app->db->createCommand($query)->bindParam(':name',$roleName);
       $ret = $command->queryOne();
       $count = $ret['count'];
       return $count != 0;
     }

     public function setListingsPermissions($authManager) {
       $permissionNames = [
         'View Listing List',
         'Change Listing Status',
         'Create Listing',
         'Update Listing',
         'Delete Listing',
         'Publish Listing',
         'Manage Others Listings'
       ];
       $this->addPermissions($authManager,$permissionNames);
     }
     public function setListingDetailPermissions($authManager) {
       $permissionNames = [
         'View Listing Detail',
       ];
       $this->addPermissions($authManager,$permissionNames);
     }
     public function setCategoriesPermissions($authManager) {
       $permissionNames = [
         'View Category List',
         'View Category Detail',
         'Create Category',
         'Update Category',
         'Delete Category',
         'Change Category Status'
       ];
       $this->addPermissions($authManager,$permissionNames);

     }
     public function setReportsPermissions($authManager) {
       $permissions = Yii::$app->params['permissions'];
       $permissionNames = $permissions['reports'];
       $permissionNames =  array_values($permissionNames);

       $this->addPermissions($authManager,$permissionNames);

     }
     public function setLocationsPermissions($authManager) {
       $permissionNames = [
         'View Location List',
         'View Location Detail',
         'Create Location',
         'Update Location',
         'Delete Location',
         'Change Location Status'
       ];
       $this->addPermissions($authManager,$permissionNames);

     }
     public function setClaimsPermissions($authManager) {
       $permissionNames = [
         'View Claims List',
         'View Claim Detail',
         'Change Claim Status',
       ];
       $this->addPermissions($authManager,$permissionNames);

     }
     public function setUsersPermissions($authManager) {
       $permissionNames = [
         'View Users List',
         'View User Detail',
         'Change User Status',
         'Update User',
         'Delete User',
       ];
       $this->addPermissions($authManager,$permissionNames);
     }
     public function setReviewsPermissions($authManager) {
       $permissionNames = [
         'View Reviews List',
         'View Review Detail',
         'Change Review Status',
         'Update Review',
         'Delete Review',
       ];
       $this->addPermissions($authManager,$permissionNames);

     }
     public function setNewslettersPermissions($authManager) {

     }
     public function setFeedbacksPermissions($authManager) {

     }
     public function setDealsPermissions($authManager) {

     }
     public function setPackagesPermissions($authManager) {

     }
     public function setMetaPermissions($authManager) {

     }
     public function setMetaGroupPermissions($authManager) {

     }

     public function actionDelete($id)
     {
       $role = $id;
       if(!($this->isSuperAdminRole($role)||$this->isNormalUserRole($role)||$this->isAffiliateUserRole($role))) {
         $authItem = $this->findModel($role,1,true); // redirect if not existing
		 if($authItem) {
			$auth = Yii::$app->authManager;
			$roleToBeDeleted = $auth->createRole($authItem->name);
			$auth->remove($roleToBeDeleted);
		 }

      }
         return $this->redirect(['/rbac/roles']);
     }
     protected function findModelOf($className,$id)
     {
         if (($model = $className::findOne($id)) !== null) {
             return $model;
         } else {
             throw new NotFoundHttpException('The requested page does not exist buddy.');
         }
     }
     protected function findModel($name,$type=1,$return = false)
     {
         if (($model = AuthItem::find()->where(['name'=>$name])->andWhere(['type'=>$type])->one()) !== null) {
             return $model;
         } else {
			 if(!$return)
				throw new NotFoundHttpException('The requested page does not exist.');
			 else
				return null;
		 }
     }

     public function actionUpdateRole($role) {
       $authItem = $this->findModel($role);
       $authItem->oldName = $role;
       $auth = Yii::$app->authManager;
       $params = ['authItem' => $authItem];
       $currentPermissions = [];

       while(true) {
         $query = AuthItem::find()->where(['type'=> 2])->orderBy(['created_at'=>SORT_ASC]);
         $params['dataProvider'] = new ActiveDataProvider([
             'query' => $query,
             'pagination' => false
         ]);

         $currentPermissions = $auth->getPermissionsByRole($role);

         $perms = [];
         foreach($currentPermissions as $perm) {
           $perms[$perm->name] = 1;
         }
         $currentPermissions = $perms;

         if($this->isSuperAdminRole($role) || $this->isNormalUserRole($role) || $this->isAffiliateUserRole($role))
         break;
//print_r("expression");die();

         $postParams = Yii::$app->request->post();
         $continue = [];
         $authItem->type = 1;
         $continue = ($authItem->load($postParams) && $authItem->validate());

         if(!$continue)break;
         $permissions = Yii::$app->request->post('permissions');
         $permissions =  array_filter($permissions, function($a) { return ($a != '0'); });
         $permissions = array_values($permissions);

         if(!$permissions) {
          $currentPermissions = [];
          $authItem->addError('permissions',  'Please choose atleast one permissions for the role');
          break;
         };

         $auth = Yii::$app->authManager;
         $updatedRole = $auth->createRole($authItem->name);
         $updatedRole->description = $authItem->description;
         $authItem->oldName = $updatedRole->name;
         $auth->update($role,$updatedRole);
         $auth->removeChildren($updatedRole);
         $currentPermissions = [];
         foreach($permissions as $permissionName) {
            $currentPermissions[$permissionName]  = 1;
            $permission = $auth->getPermission($permissionName);
            $auth->addChild($updatedRole, $permission);
         }





         break;
       }

       $params['currentPermissions'] = $currentPermissions; //associative array
       return $this->render('create',[ 'params' => $params ]);

     }
     public function actionCreateRole() {
       $authItem = new AuthItem;
       $params = ['authItem' => $authItem];
       $currentPermissions = [];

       while(true) {
         $query = AuthItem::find()->where(['type'=> 2])->orderBy(['created_at'=>SORT_ASC]);
         $params['dataProvider'] = new ActiveDataProvider([
             'query' => $query,
             'pagination' => false
         ]);

         $params['currentPermissions'] = $currentPermissions;


         $postParams = Yii::$app->request->post();
         $continue = [];
         $authItem->type = 1;
         $continue = ($authItem->load($postParams) && $authItem->validate());

         if(!$continue)break;
         $permissions = Yii::$app->request->post('permissions');
         $permissions =  array_filter($permissions, function($a) { return ($a != '0'); });
         $permissions = array_values($permissions);

         if(!$permissions) {
          $currentPermissions = [];
          $authItem->addError('permissions',  'Please choose atleast one permissions for the role');
          break;
         };

         $auth = Yii::$app->authManager;
         $newRole = $auth->createRole($authItem->name);
         $newRole->description = $authItem->description;
         $auth->add($newRole);
         foreach($permissions as $permissionName) {
            $permission = $auth->getPermission($permissionName);
            $auth->addChild($newRole, $permission);
         }

         return $this->redirect(['roles']);



         break;
       }
       return $this->render('create',[ 'params' => $params ]);
     }


     public function actionAdmins() {
       $params = [];
       $authItem = new AuthItem;
       $params = ['authItem' => $authItem];
   		 $this->view->params['hasCreate'] = true;
   		 $this->view->params['createBtnTitle'] = 'Add new admin user';
   		 $this->view->params['createBtnAction'] = 'create-admin';
       $currentPermissions = [];

       $query = AuthAssignment::find();


       $dataProvider = new ActiveDataProvider([
           'query' => $query,
       ]);
       $params['dataProvider'] = $dataProvider;
       return $this->render('admins',[ 'params' => $params ]);

     }
     public function actionViewAdmin($id) {

         $auth = Yii::$app->authManager;
         $modelUser = $this->findModelOf(TbUsers::className(),$id);
         $oldPassword = $modelUser->password;
         $modelPerson = $modelUser->fkUserPerson;
         $modelUser->password = '';
         $params = [
           'userModel' => $modelUser,
           'personModel' => $modelPerson
         ];
         $userRoles = $auth->getRolesByUser($id);
         $userRolesArr = [];
         foreach($userRoles as $userRole) {
           $userRolesArr[$userRole->name] = $userRole->name;
         }
         $roles = $auth->getRoles();
         $rolesArr = [];
         foreach($roles as $role) {
           $roleName = $role->name;
           if(isset($userRolesArr[$roleName]))continue;

           if(($roleName != $this->superAdminRole) && ($roleName != $this->normalUserRole) && ($roleName != $this->affilitateUserRole)) {
             $rolesArr[$roleName] = $roleName;
           }
         }

         $rolesArr = array_merge($userRolesArr,$rolesArr);
         $postParams = Yii::$app->request->post();

         while(1) {
           $modelUser->setScenario('updateAdmin');
           $modelPerson->setScenario('createAdmin');
           $continue = ($modelUser->load($postParams) && $modelUser->validate());
           $continue = (($modelPerson->load($postParams) && $modelPerson->validate())&&$continue);

           if(!$continue) break;
           $role = $auth->getRole($modelUser->role);

           if(!$role)break;


           $modelPerson->email = $modelUser->username;
           $modelPerson->update(false);
           if($modelUser->password) {
             $modelUser->password = Yii::$app->security->generatePasswordHash($modelUser->password);
           }
           else {
               $modelUser->password = $oldPassword;
           }
           $modelUser->update(false);
           $auth->revokeAll($modelUser->id);
           $auth->assign($role,$modelUser->id);



           return $this->redirect(['admins']);
           //$modelUser = $modelUser->insertFromPersonIfNotExisting($modelPerson);

           if(!$continue) break;
           break;
         }
         $params['roles'] = $rolesArr;
         return $this->render('admin-form',['params'=>$params]);
     }
     public function actionCreateAdmin() {
       $auth = Yii::$app->authManager;
       $modelUser = new TbUsers;
       $modelPerson = new TbPerson;
       $params = [
         'userModel' => $modelUser,
         'personModel' => $modelPerson
       ];
       $roles = $auth->getRoles();
       $rolesArr = [];
       foreach($roles as $role) {
         $roleName = $role->name;
         if(($roleName != $this->superAdminRole) && ($roleName != $this->normalUserRole) && ($roleName != $this->affilitateUserRole)) {
           $rolesArr[$roleName] = $roleName;
         }
       }
       $postParams = Yii::$app->request->post();

       while(1) {
         $modelUser->setScenario('createAdmin');
         $modelPerson->setScenario('createAdmin');
         $continue = ($modelUser->load($postParams) && $modelUser->validate());
         $continue = (($modelPerson->load($postParams) && $modelPerson->validate())&&$continue);
         if(!$continue) break;
         $role = $auth->getRole($modelUser->role);

         if(!$role)break;

         $modelUser->account_verfied = 1;
         $modelUser->phone_verified = 1;
         $modelUser->email_verified = 1;
         $modelPerson->email = $modelUser->username;
         $modelPerson = $modelPerson->insertNew();
         $modelUser->password = Yii::$app->security->generatePasswordHash($modelUser->password);
 				 $modelUser->fk_user_person = $modelPerson->id;
         $modelUser->password_reset_token = sha1(time());
         $modelUser->auth_key = sha1(time());
         $modelUser->status = 1;
         $modelUser = $modelUser->insertNew();
         $userId = $modelUser->id;
         $auth->assign($role,$userId);



         return $this->redirect(['admins']);
         //$modelUser = $modelUser->insertFromPersonIfNotExisting($modelPerson);

         if(!$continue) break;
         break;
       }
       $params['roles'] = $rolesArr;
       return $this->render('admin-form',['params'=>$params]);
     }
     public function actionDeleteAdmin($id) {
      while(true) {
       if($id==Yii::$app->user->identity->id)break;

       $auth = Yii::$app->authManager;
       $user = $this->findModelOf(TbUsers::className(),$id);
       $auth->revokeAll($id);
       $user->delete(false);

       break;
      }
       return $this->redirect(['admins']);

     }

}
