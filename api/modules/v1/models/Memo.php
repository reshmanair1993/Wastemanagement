<?php

namespace api\modules\v1\models;
use yii\helpers\Url;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;


use Yii;

/**
 * This is the model class for table "generate_Memo".
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $address
 * @property string $subject
 * @property string $description
 * @property double $amount
 * @property int $incident_id
 * @property int $account_id
 */
class Memo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'memo';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'email', 'amount','memo_type_id','lsgi_authorized_signatory_id','subject', 'description','address'], 'required'],
            [['amount'], 'number'],
            [['incident_id', 'account_id','memo_type_id','lsgi_authorized_signatory_id','lsgi_id'], 'integer'],
            [['name', 'email', 'address', 'subject', 'description'], 'string', 'max' => 255],
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

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'email' => 'Email',
            'address' => 'Address',
            'subject' => 'Subject',
            'description' => 'Description',
            'amount' => 'Amount',
            'incident_id' => 'Incident ID',
            'account_id' => 'Account ID',
            'memo_type_id' =>'Memo Type',
            'lsgi_authorized_signatory_id' => 'Lsgi Authorized Signatory'
        ];
    }
    public function getIncidentType($id){
      $modelIncident= Incident::find()->where(['id' => $id,'status'=>1])->one();
      // print_r($modelIncident);exit;
      if($modelIncident){
        $modelIncidentType = IncidentType::find()->where(['id'=>$modelIncident->incident_type_id,'status'=>1])->one();
        return $modelIncidentType;
      }
    }
    public function getIncident($id){
      $modelIncident= Incident::find()->where(['id' => $id,'status'=>1])->one();
      if($modelIncident){
        return $modelIncident;
      }
    }
    public function getAuthorizedSignatory($id){
      $modelAuthorizedSignatory= LsgiAuthorizedSignatory::find()->where(['id' => $id,'status'=>1])->one();
      if($modelAuthorizedSignatory){
        return $modelAuthorizedSignatory;
      }
    }
    public function getMemoType($id){
      $modelMemoType= MemoType::find()->where(['id' => $id,'status'=>1])->one();
      if($modelMemoType){
        return $modelMemoType;
      }
    }
    public function getImage($id)
    {
      $modelImage = Image::find()->where(['id' => $id,'status'=>1])->one();
      if($modelImage)
        return $modelImage;
    }
    public function getVideo($id)
    {
      $modelVideo = FileVideo::find()->where(['id' => $id,'status'=>1])->one();
      if($modelVideo)
        return $modelVideo;
    }
    public function getPreviewUrl(){
      return Url::to(['memos/preview','id' =>$this->id]);
    }
    public function getLsgi($id){
      $lsgi =Lsgi::find()->where(['id'=>$id])->one();
      return $lsgi;
    }
    public static function getAllQuery() {
     $query = static::find()->where(['status'=>1]);
     return $query;
   }
  
}
