<?php

namespace backend\models;

use Yii;
use yii\db\Expression;
use yii\behaviors\TimestampBehavior;
/**
 * This is the model class for table "green_action_unit_ward".
 *
 * @property int $id
 * @property int $green_action_unit_id
 * @property int $ward_id
 * @property int $status
 * @property string $created_at
 * @property string $modified_at
 */
class SurveyAgencyWard extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'survey_agency_ward';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['survey_agency_id', 'ward_id'], 'required'],
            [['survey_agency_id', 'ward_id', 'status'], 'integer'],
            [['created_at', 'modified_at'], 'safe'],
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
            'survey_agency_id' => Yii::t('app', 'Survey Agency'),
            'ward_id' => Yii::t('app', 'Ward'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'modified_at' => Yii::t('app', 'Modified At'),
        ];
    }
    public function deleteWard($id)
    {
       $connection = Yii::$app->db;
       $connection->createCommand()->update('survey_agency_ward', ['status' => 0], 'id=:id')->bindParam(':id',$id)->execute();
       return true;
    }
     public function getFkWard()
      {
        return $this->hasOne(Ward::className(), ['id' => 'ward_id']);
      }
      public function getFkSurveyAgency()
      {
        return $this->hasOne(SurveyAgency::className(), ['id' => 'survey_agency_id']);
      }
       public function getWardName()
  {
    $wardName = '';

    $ward = $this->fkWard;
    if($ward != null){
        $wardName = $ward->name_en;
    }
    return $wardName;
  }
      public function getWard($lsgiId=null)
    {
        $wards =  Ward::find()->where(['ward.status'=> 1])
        ->leftJoin('survey_agency_ward','survey_agency_ward.ward_id=ward.id')
        ->andWhere(['survey_agency_ward.ward_id'=>null])
        ->orWhere(['survey_agency_ward.status'=>0])
        ->andWhere(['ward.lsgi_id'=>$lsgiId])->all();
        return $wards;
    }
    public function getAgency()
    {
        $type =  SurveyAgency::find()->where(['status'=> 1])->all();
        return $type;
    }
}
