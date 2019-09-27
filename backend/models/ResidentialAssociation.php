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
            [['association_type_id','ward_id','phone1'],'required'],
            [['created_at', 'modified_at','phone1','phone2'], 'safe'],
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
    public function search($params,$ward=null)
    {
      $residential_association_id = null;
      $unit = null;
        $agency = null;
        $gt   = null;
        $lsgi   = null;
        $modelUser  = Yii::$app->user->identity;
        $userRole = $modelUser->role;
        $associations = Yii::$app->rbac->getAssociations($modelUser->id);
        // print_r($associations);die();
        if(isset($associations['lsgi_id']))
        {
            $lsgi = $associations['lsgi_id'];
        }
        if(isset($associations['district_id']))
        {
            $district = $associations['district_id'];
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
        if(isset($associations['residential_association_id']))
        {
            $residential_association_id = $associations['residential_association_id'];
        }
      $query = ResidentialAssociation::find()->where(['residential_association.status'=>1])->orderby('id DESC');
      if($userRole=='residence-association-admin'){
      if($residential_association_id)
      {
        // print_r($residential_association_id);die();
        $query->andWhere(['residential_association.id'=>$residential_association_id]);
      }
    }
      else
    {
      if($ward)
      {
        // $query->andWhere(['residential_association.ward_id'=>$ward]);
        $query->andWhere(['residential_association.ward_id'=>$ward]);
      }
      if($lsgi!=null||$unit!=null)
      {
        $query->leftjoin('ward','ward.id=residential_association.ward_id');
        if($lsgi!=null){
            $query->andWhere(['ward.lsgi_id'=>$lsgi]);
          }
        if($unit!=null){
            $query->leftjoin('green_action_unit_ward','green_action_unit_ward.ward_id=residential_association.ward_id')
            ->andWhere(['green_action_unit_ward.green_action_unit_id'=>$unit]);
          }
      }
    }
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
      return $modelWard->name_en;
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
           $name = $wards->name_en;
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
    public function getAssociations($userId)
    {
        $list =  ResidentialAssociation::find()
        ->leftjoin('account_ward','account_ward.ward_id=residential_association.ward_id')
        ->where(['residential_association.status'=> 1])
        ->andWhere(['account_ward.status'=>1])
        ->andWhere(['account_ward.account_id'=>$userId])
        ->all();
        return $list;
    }
     public function getAssociationslist($ward=null)
    {
        $unit = null;
        // $ward = null;
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
    public function getCount($id=null,$ward=null)
    {
        $count =0 ;
        $query = "SELECT COUNT(*)  as count FROM customer left join account on account.customer_id=customer.id WHERE customer.residential_association_id =:id and customer.status=1 and account.status=1" ;
         if($ward!=null)
        {
            $query.= " and customer.ward_id = :ward";
        }
       $command =  Yii::$app->db->createCommand($query)
       ->bindParam(':id',$id)
       ;
       if($ward)
       {
        $command->bindParam(':ward',$ward);
       }
       $ret = $command->queryOne();
       $count = $ret['count'];


        return $count;
    }
}
