<?php

namespace backend\models;

use Yii;
use yii\db\Expression;
use yii\behaviors\TimestampBehavior;
/**
 * This is the model class for table "schedule".
 *
 * @property int $id
 * @property int $lsgi_id
 * @property int $account_id_creator
 * @property int $week_day
 * @property int $month_day
 * @property string $date
 * @property int $repeat_day_count
 * @property int $service_id
 * @property int $activity_name
 * @property int $status
 * @property string $created_at
 * @property string $modified_at
 */
class Schedule extends \yii\db\ActiveRecord
{
     public $assembly_constituency_id,$district_id,$block_id,$customer_id,$association_id;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'schedule';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['lsgi_id', 'account_id_creator', 'week_day', 'month_day', 'repeat_day_count', 'service_id', 'status','ward_id','type','account_id_gt','green_action_unit_id','residential_association_id','is_non_residential'], 'integer'],
            [['lsgi_id'], 'required'],
             // [['customer_id'],'required','message' => 'Please choose customers'],
            [['date', 'created_at', 'modified_at','activity_name'], 'safe'],
        ];
    }
    public function behaviors() {
    return [
      [
        'class' => TimestampBehavior::className(),
        'createdAtAttribute' => 'created_at',
        'updatedAtAttribute' => 'modified_at',
        'value' => new Expression('NOW()')
      ]
    ];

 }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'lsgi_id' => Yii::t('app', 'Lsgi'),
            'account_id_creator' => Yii::t('app', 'Account Id Creator'),
            'week_day' => Yii::t('app', 'Week Day'),
            'month_day' => Yii::t('app', 'Month Day'),
            'date' => Yii::t('app', 'Date'),
            'repeat_day_count' => Yii::t('app', 'Repeat Day Count'),
            'service_id' => Yii::t('app', 'Service ID'),
            'activity_name' => Yii::t('app', 'Activity Name'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'modified_at' => Yii::t('app', 'Modified At'),
            'customer_id' => Yii::t('app', 'Customer'),
        ];
    }
    public function getLsgi()
    {
        $lsgi =  Lsgi::find()->where(['status'=> 1])->all();
        return $lsgi;
    }
    public function getFkType()
    {
        return $this->hasOne(NonResidentialWasteCollectionInterval::className(), ['id' => 'type']);
    }
     public function getServices()
    {
        $services =  Service::find()->where(['status'=> 1])->andWhere(['type'=>1])->all();
        return $services;
    }
     public function getServiceList()
    {
        $services =  Service::find()->where(['status'=> 1])->andWhere(['type'=>1])->andWhere(['=','is_package',0])->all();
        return $services;
    }
    public function getDistricts()
    {
        $district =  District::find()->where(['status'=> 1])->all();
        return $district;
    }
    public function getConstituency($id)
    {
        $name = null;
         $lsgi =  Lsgi::find()->where(['id'=> $id])->one();
        if($lsgi){
         $block =  LsgiBlock::find()->where(['id'=> $lsgi->block_id])->one();
        if($block){
            $assembly_constituency = AssemblyConstituency::find()->where(['id'=> $block->assembly_constituency_id])->one(); 
            $name = $assembly_constituency->name;
        }

    }
          
        return $name;
    }
    public function getBlock($id)
    {
        $name  = null;
        $lsgi =  Lsgi::find()->where(['id'=> $id])->one();
        if($lsgi){
        $block =  LsgiBlock::find()->where(['id'=> $lsgi->block_id])->one();
        if($block){
            $name = $block->name;  
        }
         }
      return $name;
    }
    public function getLsgis($id)
    {
        $name  = null;
        $lsgi =  Lsgi::find()->where(['id'=> $id])->one();
        if($lsgi){
            $name = $lsgi->name;  
        }
      return $name;
    }
     public function getWard($id)
    {
        $name  = null;
        $ward =  Ward::find()->where(['id'=> $id])->one();
        if($ward){
            $name = $ward->name_en;  
        }
      return $name;
    }
    public function getGtName($id)
    {
        $name  = null;
        $accountGt = Account::find()->where(['id'=>$id])->andWhere(['status'=>1])->one();
        $personGt = $accountGt?$accountGt->fkPerson:null;
        // $gt =  Ward::find()->where(['id'=> $id])->one();
        if($personGt){
            $name = $personGt->first_name;  
        }
      return $name;
    }
    public function getDistrict($id)
    {
        $name = null;
       $lsgi =  Lsgi::find()->where(['id'=> $id])->one();
        if($lsgi){
         $block =  LsgiBlock::find()->where(['id'=> $lsgi->block_id])->one();
        if($block){
            $assembly_constituency = AssemblyConstituency::find()->where(['id'=> $block->assembly_constituency_id])->one();
            if($assembly_constituency)
            {
                $district = District::find()->where(['id'=> $assembly_constituency->district_id])->one();
                $name = $district->id;  
            } 
            
        }
    }
        return $name;
    }
    public function deleteSchedule($id)
    {
       $connection = Yii::$app->db;
       $connection->createCommand()->update('schedule', ['status' => 0], 'id=:id')->bindParam(':id',$id)->execute();
       return true;
    }
     public static function getAllQuery()
    {
        return static::find()->where(['status'=> 1]);
    }
    public function getService()
    {
        $name  = null;
        $service =  Service::find()->where(['id'=> $this->service_id])->one();
        if($service){
            $name = $service->name;  
        }
      return $name;
    }
    public function getWards()
    {
        $name  = null;
        $wards =  ScheduleWard::find()->where(['schedule_id'=> $this->id])->all();
        foreach ($wards as $key => $value) {
           if($value->ward_id)
           {
            $modelWard =  Ward::find()->where(['id'=> $value->ward_id])->one();
            $name = $name.','.$modelWard->name;
           }
        }
      return trim($name,',');
    }
    public function getType()
    {
        $type  = null;
        if($this->type==1)
        {
            $type = 'Weekly';
        }
        elseif($this->type==2)
        {
            $type = 'Monthly';
        }
        if($this->type==3)
        {
            $type = 'Date Wise';
        }
        elseif($this->type==4)
        {
            $type = 'Daily';
        }
        elseif($this->type==5)
        {
            $type = 'Fortnight';
        }
        return $type;
    }
     public function getWeekDay()
    {
        $weekDay = null;
        if($this->week_day==1)
        {
            $weekDay = 'Sunday';
        }
        elseif($this->week_day==2)
        {
            $weekDay = 'Monday';
        }
        elseif($this->week_day==3)
        {
            $weekDay = 'Tuesday';
        }
        elseif($this->week_day==4)
        {
            $weekDay = 'Wednesday';
        }
        elseif($this->week_day==5)
        {
            $weekDay = 'Thursday';
        }
        elseif($this->week_day==6)
        {
            $weekDay = 'Friday';
        }
        elseif($this->week_day==7)
        {
            $weekDay = 'Saturday';
        }
        return $weekDay;
    }
    public function getWardHks($hks=null)
    {
        $wardId = null;
         $ward =  Ward::find()->where(['ward.status'=> 1]);
        $modelUser  = Yii::$app->user->identity;
        $associations = Yii::$app->rbac->getAssociations($modelUser->id);
        if(isset($associations['ward_id']))
        {
            $wardId = $associations['ward_id'];
            $wardId = json_decode($wardId);
        }
         if(isset($associations['hks_id']))
        {
            $hks = $associations['hks_id'];
        }
        if($wardId)
        {
           $ward = $ward->leftJoin('account_ward','account_ward.ward_id=ward.id')
        ->andWhere(['account_ward.status'=>1])
        ->andWhere(['account_ward.ward_id'=>$wardId]); 
        }
         if($hks)
    {
        $ward = $ward->leftJoin('green_action_unit_ward','green_action_unit_ward.ward_id=ward.id')
        ->andWhere(['green_action_unit_ward.status'=>1])
        ->andWhere(['green_action_unit_ward.green_action_unit_id'=>$hks]);
    }
       $ward =  $ward->all();
       return $ward;
    }
    public function getGt($hks,$userId=null)
    {
        $modelUser  = Yii::$app->user->identity;
    $userRole = $modelUser->role;
        $gt =  Person::find()
                ->select('account.id as id,person.first_name as first_name')
                ->where(['account.status' => 1])
                ->leftjoin('account', 'account.person_id=person.id')
                
                // ->leftjoin('green_action_unit','green_action_unit.id=account.green_action_unit_id')
                // ->leftjoin('green_action_unit_ward','green_action_unit.id=green_action_unit_ward.green_action_unit_id')
                // ->leftjoin('account_ward','account_ward.account_id=account.id')
                // ->andWhere(['account.supervisor_id'=>$userId])
                ->andWhere(['account.role' => 'green-technician']);
                if($userId&&$userRole=='supervisor')
                {
                    $gt = $gt
                    ->leftjoin('account_authority', 'account_authority.account_id_gt=account.id')->andWhere(['account_authority.account_id_supervisor'=>$userId]);
                }
                 if($hks)
                {
                    $gt = $gt->leftjoin('green_action_unit','green_action_unit.id=account.green_action_unit_id')
                ->leftjoin('green_action_unit_ward','green_action_unit.id=green_action_unit_ward.green_action_unit_id')
                ->leftjoin('account_ward','account_ward.account_id=account.id')
                ->andWhere(['green_action_unit.id'=>$hks]);
                }

                $gt = $gt->all();
                
        return $gt;

    }
     public function getFkWard()
    {
        return $this->hasOne(Ward::className(), ['id' => 'ward_id']);
    }
    public function getFkAssociation()
    {
        return $this->hasOne(ResidentialAssociation::className(), ['id' => 'residential_association_id']);
    }
    public function getUnit($id)
    {
        $name  = null;
        $unit =  GreenActionUnit::find()->where(['id'=> $id])->one();
        if($unit){
            $name = $unit->name;
        }
      return $name;
    }
    public function getCount($id)
    {
        $count  = 0;
        $modelScheduleCustomer = ScheduleCustomer::find()->where(['schedule_id'=>$id])->andWhere(['status'=>1])->all();
        $count = count($modelScheduleCustomer);
          
        return $count;
    }
}
