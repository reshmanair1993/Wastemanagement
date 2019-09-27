<?php

namespace frontend\models;

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
class ResidentialAssociation extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public $from,$to,$ward;
    public static function tableName()
    {
        return 'residential_association';
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
    public $district_id;
    public $assembly_constituency_id;
    public $block_id;
    public $lsgi_id;
    /**
     * {@inheritdoc}
     */

    public function rules()
    {
        return [
            [[
              'status','association_type_id','ward_id',
            ], 'integer'],
            [['association_type_id','ward_id'],'required'],
            [['created_at', 'modified_at'], 'safe'],
            [[
              'name','registration_number','address','email','year','president_name',
              'secretary_name','treasurer_name',
            ], 'string', 'max' => 255],
            [['email'],'email'],
            [[
              'name','registration_number','address','email','year'
            ], 'required'],
            [[
              'president_phone_number','secretary_phone_number','treasurer_phone_number',
              'no_of_households_in_association'
            ],'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'association_type_id' => 'Association type',
            'name' => 'Association name',
            'ward_id' => 'Ward',
            'year' => 'Year of formation',
            'president_phone_number' => 'President contact number',
            'secretary_phone_number' => 'Secretary contact number',
            'treasurer_phone_number' => 'Treasurer contact number',
            'no_of_households_in_association' => 'number of households in association',
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
    public function getFkWard()
    {
        return $this->hasOne(Ward::className(), ['id' => 'ward_id']);
    }
    public function search($params,$ward=null,$keyword=null)
    {
      
      $query = ResidentialAssociation::find()->where(['residential_association.status'=>1])->orderby('id ASC');
      if($ward)
      {
        // $query->andWhere(['residential_association.ward_id'=>$ward]);
        $query->andWhere(['in','residential_association.ward_id',$ward]);
      }
      if($keyword)
      {
        $query->andFilterWhere(['like','name', $keyword]);
      }
      
      $dataProvider = new ActiveDataProvider([
        'query' => $query,
        
      ]);
      $dataProvider->pagination->pageSize = 10;
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
    public function deleteResidentialAssociation($id){
      $connection = Yii::$app->db;
      $connection->createCommand()->update('residential_association', ['status' => 0], 'id=:id')->bindParam(':id',$id)->execute();
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
}
