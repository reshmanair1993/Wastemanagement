<?php

namespace backend\models;
use yii\data\ActiveDataProvider;

use Yii;

/**
 * This is the model class for table "camera".
 *
 * @property int $id
 * @property string $name
 * @property string $serial_no
 * @property double $lat
 * @property double $lng
 * @property int $account_technician_id
 * @property int $ward_id
 * @property int $qr_code_id
 * @property int $status
 * @property string $created_at
 * @property string $modified_at
 */
class Camera extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public $district_id,$block_id,$assembly_constituency_id,$lsgi_id,$group_id,$wardId;
    public static function tableName()
    {
        return 'camera';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['serial_no', 'account_id_technician', 'ward_id', 'qr_code_id', 'status'], 'integer'],
            [['lat', 'lng'], 'number'],
            [['serial_no','ward_id','name','account_id_technician'],'required'],
            [['serial_no','name'],'unique'],
            [['created_at', 'modified_at','location_name'], 'safe'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Camera Name'),
            'serial_no' => Yii::t('app', 'Serial No'),
            'lat' => Yii::t('app', 'Lat'),
            'lng' => Yii::t('app', 'Lng'),
            'account_id_technician' => Yii::t('app', 'Account Technician'),
            'ward_id' => Yii::t('app', 'Ward'),
            'qr_code_id' => Yii::t('app', 'Qr Code ID'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'modified_at' => Yii::t('app', 'Modified At'),
        ];
    }
    public function search($params,$ward=null,$lsgi=null,$group=null)
    {
        $modelUser  = Yii::$app->user->identity;
        $userRole  = $modelUser->role;
        $associations = Yii::$app->rbac->getAssociations($modelUser->id);
        if(isset($associations['lsgi_id']))
        {
            $lsgi = $associations['lsgi_id'];
        }
        if(isset($associations['wardId']))
        {
            $ward = $associations['wardId'];
        }
        if(isset($associations['group_id']))
        {
            $group = $associations['group_id'];
        }
        $query = Camera::getAllQuery();

        if($lsgi!=null)
        {
          $query
          ->innerJoin('ward','ward.id=camera.ward_id')
          ->innerJoin('lsgi','lsgi.id=ward.lsgi_id')
          ->andWhere(['lsgi.id'=>$lsgi])
          ->andWhere(['ward.status' =>1,'lsgi.status'=>1]);
      }
      // print_r($ward);exit;
      if($ward!=null)
      {
        $query
        ->andWhere(['camera.ward_id'=>$ward]);
      }
      if($group!=null)
      {
        $query
        ->innerJoin('monitoring_group_camera','monitoring_group_camera.camera_id=camera.id')
        ->andWhere(['monitoring_group_camera.status' =>1])
        ->andWhere(['monitoring_group_camera.monitoring_group_id'=>$group]);
    }
// print_r($query->all());exit;

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
             'pagination' => [ 'pageSize' => 10 ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        return $dataProvider;
    }
    public static function getAllQuery()
    {
      $query = static::find()->where(['camera.status'=>1])->orderBy(['id' => SORT_DESC]);
      return $query;
    }
    public function getFkWard()
    {
        return $this->hasOne(Ward::className(), ['id' => 'ward_id']);
    }
    public function getFkQrCode()
    {
        return $this->hasOne(CameraQrCode::className(), ['id' => 'qr_code_id']);
    }
    public function getWardName(){
      $modelWard = $this->fkWard;
      if($modelWard){
          return $modelWard->name;
      }
    }
    public function getDistricts()
   {
       $district =  District::find()->where(['status'=> 1])->all();
       return $district;
   }
   public function getDistrict($ward)
   {
     $name = null;
       $wards = Ward::find()->where(['status' => 1])->andWhere(['id' => $ward])->one();
       if ($wards)
       {
           $lsgi = Lsgi::find()->where(['id' => $wards->lsgi_id])->one();
           if ($lsgi)
           {
               $block = LsgiBlock::find()->where(['id' => $lsgi->block_id])->one();
               if ($block)
               {
                   $assembly_constituency = AssemblyConstituency::find()->where(['id' => $block->assembly_constituency_id])->one();
                   if ($assembly_constituency)
                   {
                       $district = District::find()->where(['id' => $assembly_constituency->district_id])->one();
                       $name     = $district->id;
                   }
               }
           }
       }

       return $name;
   }
   public function getConstituency($ward)
   {
     $name = null;
       $wards = Ward::find()->where(['status' => 1])->andWhere(['id' => $ward])->one();
       if ($wards)
       {
           $lsgi = Lsgi::find()->where(['id' => $wards->lsgi_id])->one();
           if ($lsgi)
           {
               $block = LsgiBlock::find()->where(['id' => $lsgi->block_id])->one();
               if ($block)
               {
                   $assembly_constituency = AssemblyConstituency::find()->where(['id' => $block->assembly_constituency_id])->one();
                   if ($assembly_constituency)
                   {
                       $name = $assembly_constituency->name;
                   }
               }
           }
       }

       return $name;
   }

   public function getBlock($ward)
   {
       $name  = null;
       $wards = Ward::find()->where(['status' => 1])->andWhere(['id' => $ward])->one();
       if ($wards)
       {
           $lsgi = Lsgi::find()->where(['id' => $wards->lsgi_id])->one();
           if ($lsgi)
           {
               $block = LsgiBlock::find()->where(['id' => $lsgi->block_id])->one();
               if ($block)
               {
                   $name = $block->name;
               }
           }
       }

       return $name;
   }
  public function getLsgis($ward)
  {
      $name = null;
      $ward = Ward::find()->where(['id' => $ward])->one();
      if ($ward)
      {
          $lsgi = Lsgi::find()->where(['id' => $ward->lsgi_id])->one();
          if ($lsgi)
          {
              $name = $lsgi->name;
          }
      }

      return $name;
  }
  // public function getWard($ward = null)
  // {
  //     $name  = null;
  //     $wards = Ward::find()->where(['status' => 1])->andWhere(['id' => $ward])->one();
  //     if ($wards)
  //     {
  //         $name = $wards->name;
  //     }
  //
  //     return $name;
  // }
  public function getAccountTechnician($id)
  {
      $name  = null;
      $account =  Account::find()->where(['id'=> $id])->one();
      if($account){
          $person = Person::find()->where(['id'=>$account->person_id])->one();
          if($person)
          $name = $person->first_name;
      }
    return $name;
  }
    public function getFkAccountTechnician()
    {
      // $modelUser = Account::find()->where(['id' => $this->account_id_technician ,'role' => 'camera-technician','status' => 1])->one();
      // // print_r($modelUser);exit;
      // if($modelUser)
      //   $modelPerson = Person::find()->where(['id' => $modelUser->person_id ,'status' => 1])->one();
      //   if($modelPerson)
      //     return $modelPerson;
        return $this->hasOne(Account::className(), ['id' => 'account_id_technician']);
    }
    public function getTechnicianName(){
      $modelTechnician = $this->fkAccountTechnician;
      // print_r($modelTechnician);exit;
      if($modelTechnician){
          $modelPerson = Person::find()->where(['id' => $modelTechnician->person_id ,'status' => 1])->one();
          if($modelPerson)
          // print_r($modelPerson->first_name);exit;
          return $modelPerson->first_name;
      }
    }
    public function deleteCamera($id)
     {
    $connection = Yii::$app->db;
    $connection->createCommand()->update('camera', ['status' => 0], 'id=:id')->bindParam(':id',$id)->execute();
    return true;
 }
 // public function getMonitoringGroups($id)
 // {
 //   $modelGroups = MonitoringGroupCamera::find()->where(['camera_id' => $id ,'status' => 1])->all();
 //   foreach ($modelGroups as $modelGroup) {
 //     $modelMonitoringGroup = MonitoringGroup::find()->where(['id' => $modelGroup->monitoring_group_id,'status' => 1])->one();
 //     if($modelMonitoringGroup)
 //       return $modelMonitoringGroup->name;
 //   }
 // }
 public function getFkMonitoringGroups()
 {
     return $this->hasMany(MonitoringGroupCamera::className(), ['camera_id' => 'id'])->andWhere(['status' => 1]);
 }
 public function getMonitoringGroups()
 {
   $ret = [];
   $modelMonitoringGroupCameras = $this->fkMonitoringGroups;
   foreach($modelMonitoringGroupCameras as $modelMonitoringGroupCamera)
   {
     $modelGroup = MonitoringGroup::find()->where(['id' => $modelMonitoringGroupCamera->monitoring_group_id,'status' => 1])->one();
     if($modelGroup){
     $modelMonitoringGroup = $modelGroup->name;
     if($modelMonitoringGroup){
       $ret[] = $modelMonitoringGroup;
     }
   }
   }
   return $ret;
 }
 public function getWard($ward = null)
 {
     $name  = null;
     $wards = Ward::find()->where(['status' => 1])->andWhere(['id' => $ward])->one();
     if ($wards)
     {
         $name = $wards->name;
     }

     return $name;
 }
 public function getWards($id)
 {
     $ward =  Ward::find()->where(['status'=> 1,'lsgi_id'=>$id])->all();
     return $ward;
 }
 public function getProfileUrl()
  {
    $logoUrl = isset(Yii::$app->params['defaultPhoto'])?(Yii::$app->params['image_base'].Yii::$app->params['defaultPhoto']):'';
    $fkLogoUrl = $this->fkImage;
    if($fkLogoUrl){
      $logoUrl = $fkLogoUrl->fullUrlCamera();
    }
    return $logoUrl;
  }
   public function getFkImage()
  {
    return $this->hasOne(Image::className(), ['id' => 'image_id']);
  }
}
