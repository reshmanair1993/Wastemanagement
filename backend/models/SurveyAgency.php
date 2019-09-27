<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "survey_agency".
 *
 * @property int $id
 * @property int $name
 * @property int $lsgi_id
 * @property string $contact_person_name
 * @property string $contact_person_number
 * @property string $contact_person_email
 * @property int $status
 * @property string $created_at
 * @property string $modified_at
 */
class SurveyAgency extends \yii\db\ActiveRecord
{
    public $assembly_constituency_id,$district_id,$block_id;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'survey_agency';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'lsgi_id', 'contact_person_name', 'contact_person_number', 'contact_person_email'], 'required'],
            [[ 'lsgi_id', 'status'], 'integer'],
            [['created_at', 'modified_at'], 'safe'],
            [['contact_person_name', 'contact_person_number', 'contact_person_email','name'], 'string', 'max' => 500],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'lsgi_id' => Yii::t('app', 'Lsgi'),
            'contact_person_name' => Yii::t('app', 'Contact Person Name'),
            'contact_person_number' => Yii::t('app', 'Contact Person Number'),
            'contact_person_email' => Yii::t('app', 'Contact Person Email'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'modified_at' => Yii::t('app', 'Modified At'),
        ];
    }
    public function getFkLsgi()
    {
        return $this->hasOne(Lsgi::className(), ['id' => 'lsgi_id']);
    }
     public function getDistricts()
    {
        $district =  District::find()->where(['status'=> 1])->all();
        return $district;
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
    public function deleteAgency($id)
    {
        SurveyAgencyWard::deleteAll(['survey_agency_id'=>$id]);
       $connection = Yii::$app->db;
       $connection->createCommand()->update('survey_agency', ['status' => 0], 'id=:id')->bindParam(':id',$id)->execute();
       return true;
    }
}
