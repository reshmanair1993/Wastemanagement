<?php

namespace backend\models;
use yii\data\ActiveDataProvider;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

use Yii;

/**
 * This is the model class for table "incident".
 *
 * @property int $id
 * @property int $incident_type_id
 * @property int $camera_id
 * @property int $image_id
 * @property int $file_video_id
 * @property int $duration
 * @property int $is_approved
 * @property string $captured_at
 * @property int $status
 * @property string $created_at
 * @property string $modified_at
 */
class Incident extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
     public $ward_id, $lsgi_id,$group_id,$district_id,$date_to,$date_from;
    public static function tableName()
    {
        return 'incident';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['incident_type_id', 'camera_id', 'image_id', 'file_video_id', 'duration', 'is_approved', 'status'], 'integer'],
            [['captured_at', 'created_at', 'modified_at'], 'safe'],
            [['incident_type_id', 'camera_id'],'required'],
        ];
    }
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'modified_at',
                'value' => new Expression('NOW()'),
            ]
        ];
    }
    public function deleteIncident($id)
    {
        $connection = Yii::$app->db;
        $connection->createCommand()->update('incident', ['status' => 0], 'id=:id')->bindParam(':id', $id)->execute();

        return true;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'incident_type_id' => Yii::t('app', 'Incident Type'),
            'camera_id' => Yii::t('app', 'Camera'),
            'image_id' => Yii::t('app', 'Image'),
            'file_video_id' => Yii::t('app', 'File Video'),
            'duration' => Yii::t('app', 'Duration'),
            'is_approved' => Yii::t('app', 'Is Approved'),
            'captured_at' => Yii::t('app', 'Captured At'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'modified_at' => Yii::t('app', 'Modified At'),
        ];
    }

    public function getLsgi($cameraId){
      $modelLsgi = Lsgi::find()
      ->innerJoin('ward','ward.lsgi_id=lsgi.id')
      ->innerJoin('camera','camera.ward_id = ward.id')
      ->where(['camera.id' => $cameraId])
      ->andWhere(['camera.status' =>1,'ward.status' =>1,'lsgi.status' => 1])->one();
      if($modelLsgi)
        return $modelLsgi;
    }
    public function getImage($id)
    {
      $modelImage = Image::find()->where(['id' => $id,'status'=>1])->one();
      if($modelImage)
        return $modelImage;
    }
    public function search($params,$ward=null,$lsgi=null,$group=null)
    {
        // $query = Incident::find()->where(['incident.status'=>1])->orderby('id DESC');
        // print_r($group);exit;
        $modelUser  = Yii::$app->user->identity;
        $userRole  = $modelUser->role;
        $associations = Yii::$app->rbac->getAssociations($modelUser->id);
        if(isset($associations['lsgi_id']))
        {
            $lsgi = $associations['lsgi_id'];
        }
        if(isset($associations['ward_id']))
        {
            $ward = $associations['ward_id'];
        }
        if(isset($associations['group_id']))
        {
            $group = $associations['group_id'];
        }
        // if($userRole == 'camera-monitoring-admin'){
        //   // echo $modelUser->lsgi_id;exit;
        //   $query = Incident::find()
        //   ->leftJoin('camera','camera.id = incident.camera_id')
        //   ->leftJoin('ward','ward.id = camera.ward_id')
        //   ->innerJoin('lsgi','lsgi.id = ward.lsgi_id')
        //   ->where(['camera.status'=> 1,'incident.status'=> 1,'lsgi.status'=> 1,'ward.status'=> 1, 'lsgi.id' => $modelUser->lsgi_id])
        //   ->orderBy(['incident.id' => SORT_DESC]);
        // }
        // else{
        $query = Incident::find()
        ->leftJoin('camera','camera.id = incident.camera_id')
        ->andWhere(['camera.status'=> 1,'incident.status'=> 1])
        ->orderBy(['incident.id' => SORT_DESC]);

        if($ward!=null||$lsgi!=null||$group!=null){
          // $query->innerJoin('camera','camera.id=incident.camera_id');
      if($lsgi!=null)
      {
        $query
        ->innerJoin('ward','ward.id=camera.ward_id')
        ->innerJoin('lsgi','lsgi.id=ward.lsgi_id')
        ->andWhere(['lsgi.id'=>$lsgi])
        ->andWhere(['ward.status' =>1,'lsgi.status'=>1]);
    }
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
}
// }
        // if($lsgi!=null&&$district==null)
        // {
        //     $query->innerJoin('camera','camera.id=incident.camera_id')
        //     ->leftjoin('ward','ward.id=camera.ward_id')
        //     ->leftjoin('lsgi','lsgi.id=ward.lsgi_id')
        //     ->andWhere(['lsgi.id'=>$lsgi]);
        // }
        //  if($district!=null&&$lsgi!=null)
        // {
        //     $query->innerJoin('camera','camera.id=incident.camera_id')
        //     ->leftjoin('ward','ward.id=incident.ward_id')
        //     ->leftjoin('lsgi','lsgi.id=ward.lsgi_id')
        //     ->leftjoin('lsgi_block','lsgi_block.id=lsgi.block_id')
        //     ->leftjoin('assembly_constituency','assembly_constituency.id=lsgi_block.assembly_constituency_id')
        //     ->leftjoin('district','district.id=assembly_constituency.district_id')
        //     ->andWhere(['district.id'=>$district]);
        // }
        // if($district!=null&&$lsgi==null)
        // {
        //     $query->innerJoin('camera','camera.id=incident.camera_id')
        //     ->leftjoin('ward','ward.id=incident.ward_id')
        //     ->leftjoin('lsgi','lsgi.id=ward.lsgi_id')
        //     ->leftjoin('lsgi_block','lsgi_block.id=lsgi.block_id')
        //     ->leftjoin('assembly_constituency','assembly_constituency.id=lsgi_block.assembly_constituency_id')
        //     ->leftjoin('district','district.id=assembly_constituency.district_id')
        //     ->andWhere(['district.id'=>$district]);
        // }

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
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
      $query = static::find()->where(['incident.status'=>1])->orderBy(['incident.id' => SORT_DESC]);
      return $query;
    }
    // public function getToken($id){
    //   $query = FirebaseToken::find()
    //   // ->innerJoin('account','account.id=firebase_token.account_id')
    //   ->innerJoin('monitoring_group_user','monitoring_group_user.account_id = firebase_token.account_id')
    //   ->innerJoin('monitoring_group_camera','monitoring_group_camera.monitoring_group_id = monitoring_group_user.monitoring_group_id')
    //   // ->innerJoin('lsgi','lsgi.id=account.lsgi_id')
    //   // ->innerJoin('ward','ward.lsgi_id=lsgi.id')
    //   // ->innerJoin('camera','camera.id=monitoring_group_camera.camera_id')
    //   ->where(['firebase_token.status' =>1])
    //   ->andWhere(['monitoring_group_user.status' => 1,'monitoring_group_camera.status' => 1])
    //   ->andWhere(['monitoring_group_camera.camera_id'=> $id])->all();
    //   return $query;
    // }
    public function getToken($id){
      $query = FirebaseToken::find()
      ->leftJoin('monitoring_group_user','monitoring_group_user.account_id=firebase_token.account_id')
      ->leftJoin('monitoring_group_camera','monitoring_group_camera.monitoring_group_id=monitoring_group_user.monitoring_group_id')
      ->where(['monitoring_group_camera.camera_id'=>$id])
      ->andWhere(['firebase_token.status'=>1])
      ->andWhere(['monitoring_group_user.status'=>1])
      ->andWhere(['monitoring_group_camera.status'=>1])->all();
      return $query;
    }
    public function getUsers($groupId){
        $query = FirebaseToken::find()
        ->leftJoin('monitoring_group_user','monitoring_group_user.account_id=firebase_token.account_id')
        ->leftJoin('monitoring_group','monitoring_group.id = monitoring_group_user.monitoring_group_id')
        ->where(['monitoring_group.id'=>$groupId])
        ->andWhere(['monitoring_group_user.status'=>1])
        ->andWhere(['firebase_token.status'=>1])
        ->andWhere(['monitoring_group.status'=>1])->all();
        return $query;
    }
    public function getGroup($cameraId){
      $modelGroup = MonitoringGroup::find()
      ->leftJoin('monitoring_group_camera','monitoring_group_camera.monitoring_group_id=monitoring_group.id')
      ->where(['monitoring_group_camera.camera_id'=>$cameraId])
      ->andWhere(['monitoring_group_camera.status'=>1])
      ->andWhere(['monitoring_group.status'=>1])->all();
      return $modelGroup;
    }
    public function getFkIncidentType()
    {
        return $this->hasOne(IncidentType::className(), ['id' => 'incident_type_id'])->andWhere(['status' => 1]);
    }
    public function getFkImage()
    {
        return $this->hasOne(Image::className(), ['id' => 'image_id'])->andWhere(['status' => 1]);
    }
    public function getFkVideo()
    {
        return $this->hasOne(FileVideo::className(), ['id' => 'file_video_id'])->andWhere(['status' => 1]);
    }
    public function getWard($cameraId)
    {
      $modelCamera = Camera::find()->andWhere(['id' => $cameraId,'status' => 1])->one();
      if($modelCamera){
        $modelWard = Ward::find()->andWhere(['id' => $modelCamera->ward_id,'status' => 1])->one();
        if($modelWard){
          return $modelWard;
        }
      }
    }
    public function getWardName($cameraId)
    {
      $modelCamera = Camera::find()->andWhere(['id' => $cameraId,'status' => 1])->one();
      if($modelCamera){
        $modelWard = Ward::find()->andWhere(['id' => $modelCamera->ward_id,'status' => 1])->one();
        if($modelWard){
          return $modelWard->name;
        }
      }
    }
    // public function getIncidents($date){
    //   $modelIncidents = Incident::find()->where(['between', 'created_at', "2019-01-15", "2019-01-16" ])
    //   ->andWhere(['status'=> 1])->all();
    //   return $modelIncidents;
    // }
    public function getIncidentsCount($id = null,$from=null,$to=null)
   {
       $count  = 0;
       $incidents = Incident::find()
       ->leftjoin('camera','camera.id=incident.camera_id')
       ->leftjoin('ward','ward.id=camera.ward_id')
       ->where(['incident.status' => 1])
       ->andWhere(['ward.id'=>$id])
       ->andWhere(['camera.status' =>1,'ward.status'=> 1]);
         // $customers =Customer::find()->where(['ward_id'=>$id])->andWhere(['building_type_id'=>1])->andWhere(['status'=>1]);
       // $customers =Customer::find()->where(['ward_id'=>$id])->andWhere(['building_type_id'=>1])->andWhere(['status'=>1]);
       // $customers =Customer::find()->where(['creator_account_id'=>$id])->andWhere(['building_type_id'=>1])->andWhere(['status'=>1])->all();
       if($from!=null)
       {
           $incidents->andWhere(['>=', 'incident.created_at', $from]);
       }
       if($to!=null)
       {
           $incidents->andWhere(['<=', 'incident.created_at', $to]);
       }
       $incidents=$incidents->all();
       if ($incidents)
       {
           $count = count($incidents);
       }

       return $count;
   }
    public function getIncidentType($id)
    {
      $modelIncident = IncidentType::find()->andWhere(['id' => $id,'status' => 1])->one();
      if($modelIncident){
          return $modelIncident->name;
        }
    }
    public function getIncidentMeta($id)
    {
      $modelIncidents = IncidentMeta::find()->andWhere(['incident_id' => $id,'status' => 1])->all();
      if($modelIncidents){
          return $modelIncidents;
      }
    }
    public function getImageHeight($id)
    {
      $modelIncidents = IncidentMeta::find()->andWhere(['incident_id' => $id,'status' => 1])->andWhere(['incident_key'=>'image_height'])->one();
      if($modelIncidents){
          return $modelIncidents->value.'px';
      }
    }
    public function getImageWidth($id)
    {
      $modelIncidents = IncidentMeta::find()->andWhere(['incident_id' => $id,'status' => 1])->andWhere(['incident_key'=>'image_width'])->one();
      if($modelIncidents){
          return $modelIncidents->value.'px';
      }
    }
    public function getVideoHeight($id)
    {
      $modelIncidents = IncidentMeta::find()->andWhere(['incident_id' => $id,'status' => 1])->andWhere(['incident_key'=>'video_height'])->one();
      if($modelIncidents){
          return $modelIncidents->value.'px';
      }
    }
    public function getVideoWidth($id)
    {
      $modelIncidents = IncidentMeta::find()->andWhere(['incident_id' => $id,'status' => 1])->andWhere(['incident_key'=>'video_width'])->one();
      if($modelIncidents){
          return $modelIncidents->value.'px';
      }
    }
    public function getWardCamera($id){
      $modelCameras = Camera::find()->andWhere(['ward_id' => $id,'status' => 1])->all();
      $ret = [];
      if($modelCameras){
      foreach($modelCameras as $modelCamera)
      {
        $modelIncidents= Incident::find()->where(['camera_id' => $modelCamera->id,'status' => 1])->all();
        foreach ($modelIncidents as $modelIncident) {
          $modelIncidentId = $modelIncident;
          if($modelIncidentId){
            $ret[] = $modelIncidentId;
          }
        }

      }
      }
      return $ret;
    }
    public function getFkCamera()
    {
        return $this->hasOne(Camera::className(), ['id' => 'camera_id'])->andWhere(['status' => 1]);
    }
    public function update_status($isApproved) {
      if($isApproved)
        $this->is_approved = 1;
      else
        $this->is_approved = 0;
    }
    public function getPenalty($id)
    {
        $memoPenalty =  MemoPenalty::find()->where(['memo_type_id'=> $id])->one();
        return $memoPenalty;
    }
    public function getIncidentMemo($id){
      $modelMemo = Memo::find()->where(['incident_id' =>$id,'status' =>1])->one();
      return $modelMemo;
    }


}
