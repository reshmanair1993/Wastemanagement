<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "kitchen_bin_request".
 *
 * @property int $id
 * @property string $house_owner_name
 * @property string $house_number
 * @property string $residence_association
 * @property string $association_number
 * @property int $ward_id
 * @property string $contact_no
 * @property string $address
 * @property int $ownership_of_house 1.Own 2. Rent
 * @property string $owner_name
 * @property string $contact_number_owner
 * @property int $status
 * @property int $approval_status
 * @property string $created_at
 * @property string $modified_at
 */
class KitchenBinRequest extends \yii\db\ActiveRecord
{
    public $district_id,$block_id,$assembly_constituency_id;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'kitchen_bin_request';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
        [['ward_id','house_owner_name'],'required'],
            [['ward_id', 'ownership_of_house', 'status', 'approval_status','lsgi_id','adult_count','childrens_count','is_veg_farming'], 'integer'],
            [['address'], 'string'],
            [['created_at', 'modified_at'], 'safe'],
            [['house_owner_name', 'house_number', 'association_number', 'contact_no', 'owner_name', 'contact_number_owner','email',], 'string', 'max' => 250],
            [['residence_association'], 'string', 'max' => 500],
            [['email'], 'email'],
        ];
    }
    public function getFkWard()
    {
        return $this->hasOne(Ward::className(), ['id' => 'ward_id']);
    }
    public function getFkAssociation()
    {
        return $this->hasOne(ResidentialAssociation::className(), ['id' => 'residence_association']);
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'house_owner_name' => Yii::t('app', 'House Owner Name'),
            'house_number' => Yii::t('app', 'House Number/TC Number'),
            'residence_association' => Yii::t('app', 'Residence Association'),
            'association_number' => Yii::t('app', 'Association Number'),
            'ward_id' => Yii::t('app', 'Ward'),
            'contact_no' => Yii::t('app', 'Contact No'),
            'address' => Yii::t('app', 'Address'),
            'ownership_of_house' => Yii::t('app', 'Ownership Of House'),
            'owner_name' => Yii::t('app', 'Owner Name'),
            'contact_number_owner' => Yii::t('app', 'Contact Number Owner'),
            'status' => Yii::t('app', 'Status'),
            'approval_status' => Yii::t('app', 'Approval Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'modified_at' => Yii::t('app', 'Modified At'),
        ];
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
            $name = $ward->name;
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
    public function toggleStatusApproved()
    {
        $modelUser = Yii::$app->user->identity;
        $userId    = $modelUser->id;
        
        if($this->approval_status==1)
        {
            $this->approval_status =0;
        }
        else
        {
            $this->approval_status =1;
        }
        $this->save(false);

        return $this->approval_status;
    }
    public function deleteRequest($id)
    {
       $connection = Yii::$app->db;
       $connection->createCommand()->update('kitchen_bin_request', ['status' => 0], 'id=:id')->bindParam(':id',$id)->execute();
       return true;
    }
}
