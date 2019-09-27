<?php

namespace backend\models;

use Yii;
use yii\db\Expression;
use yii\behaviors\TimestampBehavior;
/**
 * This is the model class for table "ward".
 *
 * @property int $id
 * @property string $name
 * @property string $code
 * @property int $isgi_id
 * @property int $status
 * @property string $created_at
 * @property string $modified_at
 */
class Ward extends \yii\db\ActiveRecord
{
    public $assembly_constituency_id,$district_id,$block_id;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ward';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'code', 'lsgi_id'], 'required'],
            [['lsgi_id', 'status'], 'integer'],
            [['created_at', 'modified_at','sort_order','ward_no','name_en'], 'safe'],
            [['name', 'code','name_en'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'code' => Yii::t('app', 'Code'),
            'lsgi_id' => Yii::t('app', 'Lsgi'),
            'fkLsgi.name' => Yii::t('app', 'Lsgi'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'modified_at' => Yii::t('app', 'Modified At'),
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
    public function deleteWard($id)
    {
       $connection = Yii::$app->db;
       $connection->createCommand()->update('ward', ['status' => 0], 'id=:id')->bindParam(':id',$id)->execute();
       return true;
    }
    public function getFkLsgi()
    {
        return $this->hasOne(Lsgi::className(), ['id' => 'lsgi_id']);
    }
     public function getIsgi()
    {
        $isgi =  Lsgi::find()->where(['status'=> 1])->all();
        return $isgi;
    }
     public function getLsgi($id)
    {
        $name  = null;
        $lsgi =  Lsgi::find()->where(['id'=> $id])->one();
        if($lsgi){
            $name = $lsgi->name;  
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
     public function getDistricts()
    {
        $district =  District::find()->where(['status'=> 1])->all();
        return $district;
    }
     public function getWard($lsgiId=null,$hks=null,$agency=null)
    {
        // $wards =  Ward::find()->where(['ward.status'=> 1])
        // ->leftJoin('account_ward','account_ward.ward_id=ward.id')
        // ->andWhere(['account_ward.ward_id'=>null])
        // ->orWhere(['account_ward.status'=>0])
        // ->andWhere(['lsgi_id'=>$lsgiId]);
        // if($hks)
        // {
        //     $wards->leftJoin('green_action_unit_ward','green_action_unit_ward.ward_id=ward.id')
        //     ->andWhere(['green_action_unit_ward.status'=>1])
        //     ->andWhere(['green_action_unit_ward.green_action_unit_id'=>$hks]);
        // }
        // if($agency)
        // {
        //     $wards->leftJoin('survey_agency_ward','survey_agency_ward.ward_id=ward.id')
        //     ->andWhere(['survey_agency_ward.status'=>1])
        //     ->andWhere(['survey_agency_ward.survey_agency_id'=>$agency]);
        // }
        // $wards = $wards->all();

        // SELECT `ward`.* FROM `ward` left join green_action_unit_ward on green_action_unit_ward.ward_id=ward.id  WHERE ward.id NOT IN (SELECT ward_id FROM account_ward WHERE status = 1) AND (`lsgi_id`=13) and ward.status=1 and green_action_unit_ward.status=1 and green_action_unit_ward.green_action_unit_id=12 
        if($hks||$agency){
        if($hks){
         $wards = "SELECT `ward`.* FROM `ward` left join green_action_unit_ward on green_action_unit_ward.ward_id=ward.id  WHERE ward.id NOT IN (SELECT ward_id FROM account_ward WHERE status = 1 ) AND (`lsgi_id`=:lsgi) and ward.status=1 and green_action_unit_ward.status=1 and green_action_unit_ward.green_action_unit_id=:hks ";
         $command =  Yii::$app->db->createCommand($wards);
         $command->bindParam(':lsgi',$lsgiId);
         $command->bindParam(':hks',$hks);
     }
     if($agency){
         $wards = "SELECT `ward`.* FROM `ward` left join survey_agency_ward on survey_agency_ward.ward_id=ward.id  WHERE ward.id NOT IN (SELECT ward_id FROM account_ward WHERE status = 1 and (account_ward.ward_id is null )) AND (`lsgi_id`=:lsgi) and ward.status=1 and survey_agency_ward.status=1 and survey_agency_ward.survey_agency_id=:agency ";
         $command =  Yii::$app->db->createCommand($wards);
         $command->bindParam(':lsgi',$lsgiId);
         $command->bindParam(':agency',$agency);
     }
       $wards = $command->queryAll();
   }else
   {
    $wards =  Ward::find()->where(['ward.status'=> 1])
        ->leftJoin('account_ward','account_ward.ward_id=ward.id')
        ->andWhere(['account_ward.ward_id'=>null])
        ->orWhere(['account_ward.status'=>0])
        ->andWhere(['lsgi_id'=>$lsgiId]);      
   }
    return $wards;
    }
    public static function getAllQuery($keyword=null,$from=null,$to=null,$ward=null) {
     $query = static::find()->where(['ward.status'=>1]);
     $lsgi = null;
     $unit = null;
        $agency = null;
    $modelUser  = Yii::$app->user->identity;
    $userRole = $modelUser->role;
        $gt   = null;
        $associations = Yii::$app->rbac->getAssociations($modelUser->id);
        if(isset($associations['lsgi_id']))
        {
            $lsgi = $associations['lsgi_id'];
        }
        if(isset($associations['district_id']))
        {
            $district = $associations['district_id'];
        }
        if(isset($associations['ward_id'])&&!$ward)
        {
            $ward = $associations['ward_id'];
            $ward = json_decode($ward);
        }
        if(isset($associations['hks_id']))
        {
            $unit = $associations['hks_id'];
        }
        if(isset($associations['gt_id']))
        {
            $gt = $associations['gt_id'];
        }
        if(isset($associations['survey_agency_id']))
        {
            $agency = $associations['survey_agency_id'];
        }
    if($unit!=null)
        {

            $query->leftjoin('green_action_unit_ward','green_action_unit_ward.ward_id=ward.id')
            ->andWhere(['green_action_unit_ward.green_action_unit_id'=>$unit]);
        }
         if($agency)
        {

            $query->leftjoin('survey_agency_ward','survey_agency_ward.ward_id=ward.id')
            ->andWhere(['survey_agency_ward.survey_agency_id'=>$agency]);
        }
     if($ward)
     {
        $query->andWhere(['ward.id'=>$ward]);
     }
     if($lsgi)
     {
        $query->andWhere(['ward.lsgi_id'=>$lsgi]);
     }
     return $query;
   }
   public function getWards()
   {
    $modelUser  = Yii::$app->user->identity;
        $userRole  = $modelUser->role;
        $associations = Yii::$app->rbac->getAssociations($modelUser->id);
        if(isset($associations['ward_id'])&&json_decode($associations['ward_id'])&&sizeof(json_decode($associations['ward_id']))>0)
        {
            $ward = $associations['ward_id'];
            $ward = json_decode($ward);
            foreach ($ward as $key => $value) {
                $ward[] =  Ward::find()->where(['ward.status'=> 1])->andWhere(['ward.id'=>$value])->one();
            }
        }
        else
        {
    $modelUser  = Yii::$app->user->identity;
    $ward =  Ward::find()->where(['ward.status'=> 1]);
    if($modelUser->lsgi_id)
    {
        $ward = $ward->andWhere(['ward.lsgi_id'=>$modelUser->lsgi_id]);
    }
    if($modelUser->green_action_unit_id)
    {
        $ward = $ward->leftJoin('green_action_unit_ward','green_action_unit_ward.ward_id=ward.id')
        ->andWhere(['green_action_unit_ward.status'=>1])
        ->andWhere(['green_action_unit_ward.green_action_unit_id'=>$modelUser->green_action_unit_id]);
    }
       $ward =  $ward->all();
   }
       return $ward;
   }
   public function getWardList()
   {
    $ward =  Ward::find()->where(['ward.status'=> 1]);
       $ward =  $ward->all();
       return $ward;
   }
   
}
