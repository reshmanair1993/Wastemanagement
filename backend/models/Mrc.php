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
class Mrc extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mrc';
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
              'status'
            ], 'integer'],
            [['created_at', 'modified_at','phone1','phone2','image_id','lat','lng','type','lsgi_id'], 'safe'],
            [['name','qr_code'], 'string', 'max' => 255],
            [['name','lat','lng'],'required'],
            [['qr_code'],'if_qrcode_assigned'],
        ];
    }
    public function if_qrcode_assigned($attribute){
   $qr_code = $this->qr_code;
   if($this->id)
    $modelMrcdata = Mrc::find()->where(['id'=>$this->id])->andWhere(['status'=>1])->one();
   $modelMrc = Mrc::find()->where(['qr_code'=>$qr_code])->andWhere(['status'=>1])->one();
   $modelQrCode = QrCode::find()->where(['value'=>$qr_code])->andWhere(['account_id'=>null])->andWhere(['mrc_id'=>null])->andWhere(['status'=>1])->one();
   if(!$this->id){
   if($modelMrc||!$modelQrCode){
     $this->addError('qr_code','Qr code must be unique');
     return;
   }
 }elseif($modelMrcdata->qr_code==null){
  if($modelMrc||!$modelQrCode){
     $this->addError('qr_code','Qr code must be unique');
     return;
   }
 }
 elseif($modelMrcdata->qr_code!=null)
 {
 
  if($modelMrc->qr_code!=$modelMrcdata->qr_code)
  {
    $this->addError('qr_code','Qr code must be unique');
     return;
  }
 }
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
            'lsgi_id' => 'Lsgi',
        ];
    }

    public static function getAllQuery()
    {
      $query = static::find()->where(['status' => 1]);
      return $query;
    }
    public function deleteMrc($id){
      $connection = Yii::$app->db;
      $connection->createCommand()->update('mrc', ['status' => 0], 'id=:id')->bindParam(':id',$id)->execute();
       return true;
    }
    public function search($params)
    {
      $query = Mrc::find()->where(['status'=>1])->orderby('id ASC');
      $dataProvider = new ActiveDataProvider([
        'query' => $query,
      ]);
      $this->load($params);
      if (!$this->validate()) {
        return $dataProvider;
      }
      return $dataProvider;
    }
     public function getProfileUrl()
  {
    // $logoUrl = isset(Yii::$app->params['defaultPhoto'])?(Yii::$app->params['image_base'].Yii::$app->params['defaultPhoto']):'';
    $logoUrl =[];
    if($this->image_id)
    {
      $imageId = json_decode($this->image_id);
    }
    foreach ($imageId as $value) {
     $fkLogoUrl = Image::find()->where(['id'=>$value])->andWhere(['status'=>1])->one();
    if($fkLogoUrl){
      $logoUrl[] = $fkLogoUrl->fullUrlMrc();
    }
    }
    
    return $logoUrl;
  }
  public function getFkImage()
  {
    return $this->hasOne(Image::className(), ['id' => 'image_id']);
  }
}
