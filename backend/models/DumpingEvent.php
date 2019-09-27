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
class DumpingEvent extends \yii\db\ActiveRecord
{
  public $supervisor;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'dumping_event';
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

    public function rules()
    {
        return [
            [[
              'status','account_id_customer','image_id','incident_type_id'
            ], 'integer'],
            [['created_at', 'modified_at','lat','lng','location_name'], 'safe'],
            [['remarks'], 'string'],
            [['account_id_customer','lat','lng'],'required'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
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
    public function deleteDumpingEvent($id){
      $connection = Yii::$app->db;
      $connection->createCommand()->update('dumping_event', ['status' => 0], 'id=:id')->bindParam(':id',$id)->execute();
       return true;
    }
    public function getFkImage()
  {
    return $this->hasOne(Image::className(), ['id' => 'image_id']);
  }
   public function getFkAccount()
  {
    return $this->hasOne(Account::className(), ['id' => 'account_id_customer']);
  }
  public function getFkType()
  {
    return $this->hasOne(DumpingEventType::className(), ['id' => 'incident_type_id']);
  }
  public function getProfileUrl()
  {
    $logoUrl = isset(Yii::$app->params['defaultPhoto'])?(Yii::$app->params['image_base'].Yii::$app->params['defaultPhoto']):'';
    $fkLogoUrl = $this->fkImage;
    if($fkLogoUrl){
      $logoUrl = $fkLogoUrl->fullUrlEvents();
    }
    return $logoUrl;
  }
   public function search($params,$type =null)
    {
       $unit = null;
        $agency = null;
        $lsgi   = null;
        $district   = null;
        $ward   = null;
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
      $query = DumpingEvent::find()->where(['dumping_event.status'=>1])->orderby('dumping_event.id DESC');
      if($type)
      {
        $query->andWhere(['incident_type_id'=>$type]);
      }
      if($lsgi)
      {
        $query->leftjoin('account','account.id=dumping_event.account_id_customer')
              ->leftjoin('customer','customer.id=account.customer_id')
              ->leftjoin('ward','ward.id=customer.ward_id')
              ->leftjoin('lsgi','lsgi.id=ward.lsgi_id')
              ->andWhere(['lsgi.id'=>$lsgi])
              ->andWhere(['lsgi.status'=>1])
              ->andWhere(['customer.status'=>1])
              ->andWhere(['account.status'=>1])
              ;
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
    public function getTypes()
   {
       $type =  DumpingEventType::find()->where(['status'=> 1])->all();
       return $type;
   }
   public function getCustomer()
    {
        $name  = null;       
        if(isset($this->fkAccount->fkCustomer->lead_person_name)){
            $name = $this->fkAccount->fkCustomer->lead_person_name;
        }
      return $name;
    }
}
