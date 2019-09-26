<?php
namespace common\components;

use Yii;
use yii\base\Component;
use yii\base\InvalidConfigException;
use yii\helpers\Url;
use backend\models\User;
use backend\models\Account;
use backend\models\AuthAssociation;

class RbacComponent extends Component
{
    public $superAdminRole = 'super-admin';
    public $lsgiAdminRole = 'admin-lsgi';
    public $hksAdminRole = 'admin-hks';
    public $supervisorsRole = 'supervisor';
    public $greenTechniciansRole = 'green-technician';
    public $coordinatorRole = 'coordinator';
    public $surveyorRole = 'surveyor';
    public $cameraMonitoringAdminRole = 'camera-monitoring-admin';
    public $cameraTechnicianRole = 'camera-technician';

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

    public function getAssociations($user_id){
      $modelAuthAssociation = AuthAssociation::find()->where(['status'=>1,'user_id'=>$user_id])->one();
      if($modelAuthAssociation){
      return [
        'district_id' => $modelAuthAssociation->district_id,
        'lsgi_id'     => $modelAuthAssociation->lsgi_id,
        'ward_id'     => $modelAuthAssociation->ward_id,
        'hks_id'      => $modelAuthAssociation->hks_id,
        'gt_id'       => $modelAuthAssociation->gt_id,
        'survey_agency_id' => $modelAuthAssociation->survey_agency_id,
        'supervisor_id' => $modelAuthAssociation->supervisor_id,
        'residential_association_id' => $modelAuthAssociation->residential_association_id
      ];
    }
    else 
    {
      return [];
    }
    }
    public function setAssociations($user_id,$associations){
      $modelAuthAssociation = new AuthAssociation;
      $modelAuthAssociation->user_id = $user_id;
      if(is_array($associations)){
        foreach ($associations as $key => $association) {
          foreach ($modelAuthAssociation as $columnName => $value) {
            if($columnName == $key){
              $modelAuthAssociation->$columnName = $association;
            }
          }
        }
        $modelAuthAssociation->save(false);
      }else{
        return "Given parameter is not an array. Please give an array.";
      }
    }

}
