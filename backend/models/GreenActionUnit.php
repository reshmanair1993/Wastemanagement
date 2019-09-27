<?php

namespace backend\models;

use Yii;
use yii\db\Expression;
use yii\behaviors\TimestampBehavior;
/**
 * This is the model class for table "green_action_unit".
 *
 * @property int $id
 * @property string $name
 * @property int $status
 * @property string $created_at
 * @property string $modified_at
 */
class GreenActionUnit extends \yii\db\ActiveRecord
{
    public $assembly_constituency_id,$district_id,$block_id;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'green_action_unit';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name','lsgi_id'], 'required'],
            [['status','lsgi_id'], 'integer'],
            [['created_at', 'modified_at','sort_order','residence_category_id','performance_point_earned','performance_point_total'], 'safe'],
            [['name','contact_person_name','email','phone'], 'string', 'max' => 250],
            [['address'], 'string'],
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
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'status' => Yii::t('app', 'Status'),
            'lsgi_id' => Yii::t('app', 'Lsgi'),
            'fkLsgi.name' => Yii::t('app', 'Lsgi'),
            'created_at' => Yii::t('app', 'Created At'),
            'modified_at' => Yii::t('app', 'Modified At'),
            'fkResidenceCategory.name' => Yii::t('app', 'Residence Category'),
        ];
    }
      public function deleteGreenActionUnit($id)
    {
      GreenActionUnitWard::deleteAll(['green_action_unit_id'=>$id]);
       $connection = Yii::$app->db;
       $connection->createCommand()->update('green_action_unit', ['status' => 0], 'id=:id')->bindParam(':id',$id)->execute();

         $connectionaccount = Yii::$app->db;
         $connectionaccount->createCommand()->update('account', ['status' => 0], 'green_action_unit_id=:id')->bindParam(':id',$id)->execute();

       return true;
    }
     public function getFkLsgi()
    {
        return $this->hasOne(Lsgi::className(), ['id' => 'lsgi_id']);
    }
     public function getFkResidenceCategory()
    {
        return $this->hasOne(ResidenceCategory::className(), ['id' => 'residence_category_id']);
    }
     public function getLsgi()
    {
        $lsgi =  Lsgi::find()->where(['status'=> 1])->all();
        return $lsgi;
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
    public function getCategory()
    {
        $category =  ResidenceCategory::find()->where(['status'=> 1])->all();
        return $category;
    }
    public static function getAllQuery($keyword=null,$from=null,$to=null,$ward=null) {
     $query = static::find()->where(['green_action_unit.status'=>1]);
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

            $query
            ->andWhere(['green_action_unit.id'=>$unit]);
        }
        //  if($agency)
        // {

        //     $query->leftjoin('survey_agency_ward','survey_agency_ward.ward_id=ward.id')
        //     ->andWhere(['survey_agency_ward.survey_agency_id'=>$agency]);
        // }
     if($ward)
     {
        $query->leftjoin('green_action_unit_ward','green_action_unit_ward.green_action_unit_id=green_action_unit.id')
        ->andWhere(['green_action_unit_ward.ward_id'=>$ward]);
     }
     // if($lsgi)
     // {
     //    $query->andWhere(['ward.lsgi_id'=>$lsgi]);
     // }
     return $query;
   }
}
