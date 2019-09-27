<?php
namespace common\components;

use Yii;
use yii\base\Component;
use yii\base\InvalidConfigException;
use yii\helpers\Url;
use backend\models\User;
use backend\models\Account;
class RbacComponent extends Component
{
    public $superAdminRole = 'super-admin';
    public $lsgiAdminRole = 'lsgi-admin';
    public $hksAdminRole = 'hks-admin';
    public $supervisorsRole = 'supervisors';
    public $greenTechniciansRole = 'green-technicians';
    public $coordinatorRole = 'coordinator';
    public $surveyorRole = 'surveyor';

    public function assignAuthRole($roleName,$accountId){
      $auth = Yii::$app->authManager;
      $role = $auth->getRole($roleName);
      $modelRole = Account::find()->where(['role'=>$roleName])->andWhere(['status'=>1])->one();
      if(!$modelRole){
        $modelRole = new Account;
        $modelRole->name = $roleName;
        $modelRole->save(false);
      }
      if(!$role){
        $role = $auth->createRole($roleName);
        $auth->add($role);
      }
      $roles = \Yii::$app->authManager->getRolesByUser($accountId);
      if(!isset($roles[$roleName]))
        $auth->assign($role,$accountId);
    }
}