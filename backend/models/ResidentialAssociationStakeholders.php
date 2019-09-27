<?php

namespace backend\models;

use Yii;
use yii\db\Expression;
use yii\data\ActiveDataProvider;
use yii\behaviors\TimestampBehavior;
/**
 * This is the model class for table "residential_association".
 *
 * @property int $id
 * @property string $name
 * @property double $penalty
 * @property int $status
 * @property string $created_at
 * @property string $modified_at
 */
class ResidentialAssociationStakeholders extends \yii\db\ActiveRecord
{
   
    public static function tableName()
    {
        return 'residential_association_stakeholders';
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
    public function rules()
    {
        return [
            [[
              'status','residential_association_id','image_id'
            ], 'integer'],
            [['name','phone'],'required'],
            [['created_at', 'modified_at','position'], 'safe'],
            
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'residential_association_id' => 'Association',
            'name' => 'Stakeholder name',
            'status' => 'Status',
            'created_at' => 'Created At',
            'modified_at' => 'Modified At',
        ];
    }

    public static function getAllQuery()
    {
      $query = static::find()->where(['status' => 1]);
      return $query;
    }
    public function search($params)
    {
        
      $query = ResidentialAssociationStakeholders::find()->where(['residential_association_stakeholders.status'=>1])->orderby('id ASC');
      
      $dataProvider = new ActiveDataProvider([
        'query' => $query,
      ]);
      $this->load($params);
      if (!$this->validate()) {
        return $dataProvider;
      }
      return $dataProvider;
    }
    public function getAssociationName($associationId){
      $modelAssociationType = AssociationType::find()->where(['status'=>1,'id'=>$associationId])->one();
      return $modelAssociationType->name;
    }
    public function getWardName($wardId){
      $modelWard = Ward::find()->where(['status'=>1,'id'=>$wardId])->one();
      return $modelWard->name;
    }
    public function deleteResidentialAssociationStakeholder($id){
      $connection = Yii::$app->db;
      $connection->createCommand()->update('residential_association_stakeholders', ['status' => 0], 'id=:id')->bindParam(':id',$id)->execute();
       return true;
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
    public function getLsgis($ward=null)
    {
        $name  = null;
        $wards = Ward::find()->where(['status' => 1])->andWhere(['id' => $ward])->one();
       if ($wards)
       {
        $lsgi =  Lsgi::find()->where(['id'=> $wards->lsgi_id])->one();
        if($lsgi){
            $name = $lsgi->name;
        }
      }
      return $name;
    
  }
  public function getBlock($ward=null)
    {
        $name  = null;
        $wards = Ward::find()->where(['status' => 1])->andWhere(['id' => $ward])->one();
       if ($wards)
       {
        $lsgi =  Lsgi::find()->where(['id'=> $wards->lsgi_id])->one();
        if($lsgi){
        $block =  LsgiBlock::find()->where(['id'=> $lsgi->block_id])->one();
        if($block){
            $name = $block->name;
        }
         }
       }
      return $name;
    }
    public function getConstituency($ward=null)
    {
        $name = null;
        $wards = Ward::find()->where(['status' => 1])->andWhere(['id' => $ward])->one();
       if ($wards)
       {
         $lsgi =  Lsgi::find()->where(['id'=> $wards->lsgi_id])->one();
        if($lsgi){
         $block =  LsgiBlock::find()->where(['id'=> $lsgi->block_id])->one();
        if($block){
            $assembly_constituency = AssemblyConstituency::find()->where(['id'=> $block->assembly_constituency_id])->one();
            $name = $assembly_constituency->name;
        }

    }
  }
        return $name;
    }
     public function getDistrict($ward=null)
    {
        $name = null;
        $wards = Ward::find()->where(['status' => 1])->andWhere(['id' => $ward])->one();
       if ($wards)
       {
       $lsgi =  Lsgi::find()->where(['id'=> $wards->lsgi_id])->one();
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
  }
        return $name;
    }
     public function getAssociationslist()
    {
        $unit = null;
        $ward = null;
        $lsgi = null;
        $district = null;
        $agency = null;
        $gt   = null;
        $modelUser  = Yii::$app->user->identity;
        $userRole  = $modelUser->role;
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
        $list =  ResidentialAssociation::find()->where(['status'=> 1]);
        if($ward)
        {
          $list->andWhere(['ward_id'=>$ward]);
        }

        $list = $list->all();
        return $list;
    }
    public function getAssociationslistData()
    {
        $list =  ResidentialAssociation::find()->where(['status'=> 1])->andWhere(['residential_association.ward_id'=>['6','7']])->all();
        return $list;
    }
    public function getAssociationslistCustomer()
    {
        $list =  Customer::find()->where(['status'=> 1])->andWhere(['customer.ward_id'=>['6','7']])->andWhere(['residential_association_id'=>null])->groupBy('customer.association_name')->all();
        return $list;
    }
     public function getProfileUrl()
  {
    $logoUrl = isset(Yii::$app->params['defaultPhoto'])?(Yii::$app->params['image_base'].Yii::$app->params['defaultPhoto']):'';
    $fkLogoUrl = $this->fkImage;
    if($fkLogoUrl){
      $logoUrl = $fkLogoUrl->fullUrl();
    }
    return $logoUrl;
  }
  public function getFkImage()
  {
    return $this->hasOne(Image::className(), ['id' => 'image_id']);
  }
}
